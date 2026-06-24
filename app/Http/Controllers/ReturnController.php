<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $returns = SaleReturn::with(['sale', 'details.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('cashier.returns.index', compact('returns'));
    }

    public function create()
    {
        return view('cashier.returns.create');
    }

    public function saleLookup(Request $request, $sale)
    {
        $saleModel = Sale::where('id', $sale)
            ->orWhere('invoice_number', $sale)
            ->with('details.product')
            ->first();

        if (!$saleModel) {
            return response()->json(['message' => 'Sale not found.'], 404);
        }

        $alreadyReturned = ReturnDetail::whereHas('returnRecord', function ($q) use ($saleModel) {
                $q->where('sale_id', $saleModel->id)->whereIn('status', ['pending', 'approved']);
            })
            ->selectRaw('product_id, SUM(quantity) as qty')
            ->groupBy('product_id')
            ->pluck('qty', 'product_id');

        $items = $saleModel->details->map(function ($detail) use ($alreadyReturned) {
            $returned = $alreadyReturned[$detail->product_id] ?? 0;
            return [
                'product_id'        => $detail->product_id,
                'product_name'      => $detail->product->name ?? 'Unknown product',
                'unit_price'        => (float) $detail->price,
                'quantity_sold'     => $detail->quantity,
                'quantity_returnable' => max(0, $detail->quantity - $returned),
            ];
        });

        return response()->json([
            'sale_id'        => $saleModel->id,
            'invoice_number' => $saleModel->invoice_number,
            'items'          => $items,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id'           => 'required|exists:sales,id',
            'reason'            => 'required|string|max:500',
            'items'             => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        $sale = Sale::with('details')->findOrFail($request->sale_id);

        $alreadyReturned = ReturnDetail::whereHas('returnRecord', function ($q) use ($sale) {
                $q->where('sale_id', $sale->id)->whereIn('status', ['pending', 'approved']);
            })
            ->selectRaw('product_id, SUM(quantity) as qty')
            ->groupBy('product_id')
            ->pluck('qty', 'product_id');

        $detailsByProduct = $sale->details->keyBy('product_id');
        $amountReturned = 0;
        $validatedItems = [];

        foreach ($request->items as $item) {
            $saleDetail = $detailsByProduct->get($item['product_id']);

            if (!$saleDetail) {
                return back()->withErrors(['items' => 'One of the selected products was not part of this sale.'])->withInput();
            }

            $alreadyForThisProduct = $alreadyReturned[$item['product_id']] ?? 0;
            $returnable = $saleDetail->quantity - $alreadyForThisProduct;

            if ($item['quantity'] > $returnable) {
                return back()->withErrors([
                    'items' => "Cannot return {$item['quantity']} of \"{$saleDetail->product->name}\" — only {$returnable} left available to return.",
                ])->withInput();
            }

            $subtotal = $saleDetail->price * $item['quantity'];
            $amountReturned += $subtotal;

            $validatedItems[] = [
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $saleDetail->price,
                'subtotal'   => $subtotal,
            ];
        }

        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $return = SaleReturn::create([
                'return_date'     => now(),
                'reason'          => $request->reason,
                'amount_returned' => $amountReturned,
                'status'          => 'pending',
                'sale_id'         => $sale->id,
                'user_id'         => $user->id,
            ]);

            foreach ($validatedItems as $item) {
                ReturnDetail::create([
                    'return_id'  => $return->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['subtotal'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Could not register the return. Please try again.'])->withInput();
        }

        return redirect()->route('cashier.returns')->with('success', 'Return request submitted. It will be reviewed by an administrator.');
    }
}