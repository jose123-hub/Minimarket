<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = PurchaseOrder::with(['supplier', 'user'])->latest()->get();
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::with('category')->get();
        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'estimated_delivery'   => 'nullable|date',
            'products'             => 'required|array|min:1',
            'products.*.product_id'=> 'required|exists:products,id',
            'products.*.quantity'  => 'required|integer|min:1',
            'products.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $order = PurchaseOrder::create([
                'order_number'      => 'OC-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'order_date'        => now(),
                'estimated_delivery'=> $request->estimated_delivery,
                'status'            => 'pending',
                'notes'             => $request->notes,
                'supplier_id'       => $request->supplier_id,
                'user_id'           => Auth::id(),
                'total'             => 0,
            ]);

            $total = 0;

            foreach ($request->products as $item) {
                $subtotal = $item['quantity'] * $item['unit_cost'];

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $order->id,
                    'product_id'        => $item['product_id'],
                    'quantity_ordered'  => $item['quantity'],
                    'quantity_received' => 0,
                    'unit_cost'         => $item['unit_cost'],
                    'subtotal'          => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            DB::commit();
            return redirect('/admin/purchases')->with('success', 'Purchase order created. Stock will update once it is received.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error registering purchase: ' . $e->getMessage());
        }
    }

    public function receiveForm(PurchaseOrder $purchase)
    {
        $purchase->load(['supplier', 'details.product']);
        return view('admin.purchases.receive', compact('purchase'));
    }
    public function receive(Request $request, PurchaseOrder $purchase)
    {
        $request->validate([
            'received' => 'required|array',
            'received.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchase->load('details.product');
            $fullyReceived = true;

            foreach ($purchase->details as $detail) {
                $newlyReceived = (int) ($request->received[$detail->id] ?? 0);
                $newlyReceived = max(0, min($newlyReceived, $detail->quantity_ordered - $detail->quantity_received));

                if ($newlyReceived > 0) {
                    $detail->quantity_received += $newlyReceived;
                    $detail->save();

                    $product = $detail->product;
                    $product->stock += $newlyReceived;
                    $product->cost = $detail->unit_cost;
                    $product->save();
                }

                if ($detail->quantity_received < $detail->quantity_ordered) {
                    $fullyReceived = false;
                }
            }

            $purchase->update([
                'status'          => $fullyReceived ? 'received' : 'partial',
                'actual_delivery' => $fullyReceived ? now() : $purchase->actual_delivery,
            ]);

            DB::commit();
            return redirect('/admin/purchases')->with('success', $fullyReceived
                ? 'Purchase order fully received. Stock updated.'
                : 'Partial reception saved. Stock updated for received items.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error receiving purchase: ' . $e->getMessage());
        }
    }

    /**
     * Full detail view of a single purchase order.
     */
    public function show(PurchaseOrder $purchase)
    {
        $purchase->load(['supplier', 'user', 'details.product']);
        return view('admin.purchases.show', compact('purchase'));
    }
    public function edit(PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect('/admin/purchases')->with('error', 'Only pending orders can be edited.');
        }

        $purchase->load(['supplier', 'details.product']);
        $suppliers = Supplier::where('status', 'active')->get();

        return view('admin.purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect('/admin/purchases')->with('error', 'Only pending orders can be edited.');
        }

        $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'estimated_delivery' => 'nullable|date',
            'notes'              => 'nullable|string',
            'details'            => 'required|array|min:1',
            'details.*.id'         => 'required|exists:purchase_order_details,id',
            'details.*.quantity'   => 'required|integer|min:1',
            'details.*.unit_cost'  => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchase->update([
                'supplier_id'        => $request->supplier_id,
                'estimated_delivery' => $request->estimated_delivery,
                'notes'              => $request->notes,
            ]);

            $total = 0;

            foreach ($request->details as $row) {
                $detail = PurchaseOrderDetail::where('purchase_order_id', $purchase->id)->findOrFail($row['id']);
                $subtotal = $row['quantity'] * $row['unit_cost'];

                $detail->update([
                    'quantity_ordered' => $row['quantity'],
                    'unit_cost'        => $row['unit_cost'],
                    'subtotal'         => $subtotal,
                ]);

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            DB::commit();
            return redirect('/admin/purchases')->with('success', 'Purchase order updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }
    public function cancel(PurchaseOrder $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect('/admin/purchases')->with('error', 'Only pending orders can be cancelled — this one already has received stock.');
        }

        $purchase->update(['status' => 'cancelled']);

        return redirect('/admin/purchases')->with('success', 'Purchase order cancelled.');
    }
    public function destroy(PurchaseOrder $purchase)
    {
        if (!in_array($purchase->status, ['pending', 'cancelled'])) {
            return redirect('/admin/purchases')->with('error', 'Only pending or cancelled orders can be deleted.');
        }

        $purchase->details()->delete();
        $purchase->delete();

        return redirect('/admin/purchases')->with('success', 'Purchase order deleted.');
    }
}