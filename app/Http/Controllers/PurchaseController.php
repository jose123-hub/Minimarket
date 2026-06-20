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
                $product  = Product::findOrFail($item['product_id']);
                $subtotal = $item['quantity'] * $item['unit_cost'];

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $order->id,
                    'product_id'        => $product->id,
                    'quantity_ordered'  => $item['quantity'],
                    'quantity_received' => 0,
                    'unit_cost'         => $item['unit_cost'],
                    'subtotal'          => $subtotal,
                ]);

                $product->stock += $item['quantity'];
                $product->cost   = $item['unit_cost'];
                $product->save();

                $total += $subtotal;
            }

            $order->update(['total' => $total, 'status' => 'received', 'actual_delivery' => now()]);

            DB::commit();
            return redirect('/admin/purchases')->with('success', 'Purchase order registered and stock updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error registering purchase: ' . $e->getMessage());
        }
    }
}
