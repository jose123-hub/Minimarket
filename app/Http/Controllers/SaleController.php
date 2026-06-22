<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Models\Client;
use App\Models\StarHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'cashier'])->latest()->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
    $products   = Product::with('category')->get();
    $customers  = User::where('role', 'customer')->get();
    $categories = \App\Models\Category::all();
    $orderItems = collect();

    if ($request->has('order_id')) {
        $order = Sale::with('details.product')->find($request->order_id);
        if ($order && $order->status === 'pending') {
            $orderItems = $order->details;
        }
    }

    return view('cashier.sales.create', compact('products', 'customers', 'categories', 'orderItems'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'customer_id'            => 'required_without:order_id|exists:users,id',
        'order_id'               => 'nullable|exists:sales,id',
        'products'               => 'required|array|min:1',
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.quantity'   => 'required|integer|min:1',
    ]);

    DB::beginTransaction();
    try {
        if ($request->has('order_id') && $request->order_id) {
            $sale = Sale::findOrFail($request->order_id);
            $sale->update([
                'cashier_id'     => Auth::id(),
                'status'         => 'completed',
                'payment_method' => $request->payment_method ?? 'cash',
            ]);
            $sale->details()->delete();
        } else {
            $sale = Sale::create([
                'customer_id'    => $request->customer_id,
                'cashier_id'     => Auth::id(),
                'total'          => 0,
                'status'         => 'completed',
                'payment_method' => $request->payment_method ?? 'cash',
                'voucher_type'   => 'receipt',
            ]);
        }

        $total = 0;

        foreach ($request->products as $item) {
            $product  = Product::findOrFail($item['product_id']);

            if ($item['quantity'] > $product->stock) {
                throw new \Exception("Not enough stock for \"{$product->name}\". Available: {$product->stock}, requested: {$item['quantity']}.");
            }

            $subtotal = $product->price * $item['quantity'];

            SaleDetail::create([
                'sale_id'    => $sale->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
                'subtotal'   => $subtotal,
            ]);

            $product->stock -= $item['quantity'];
            $product->save();

            $total += $subtotal;
        }

        $sale->update(['total' => $total]);

        $starsEarned = (int) floor($total);
        $client = \App\Models\Client::where('user_id', $sale->customer_id)->first();

        if ($client && $starsEarned > 0) {
            $client->accumulated_stars += $starsEarned;
            $client->save();

            \App\Models\StarHistory::create([
                'movement_type' => 'earned',
                'amount'        => $starsEarned,
                'reason'        => 'Purchase — Sale #' . $sale->id,
                'date'          => now(),
                'client_id'     => $client->id_cliente,
                'sale_id'       => $sale->id,
            ]);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Sale registered successfully. ' . ($starsEarned > 0 ? "+{$starsEarned} stars earned!" : ''));

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error registering sale: ' . $e->getMessage());
    }
    }
}