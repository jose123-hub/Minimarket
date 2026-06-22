<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Client;
use App\Models\StarHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function catalog()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $categories = Category::all();
        return view('client.catalog', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products'               => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;

            foreach ($request->products as $item) {
                $product  = Product::findOrFail($item['product_id']);

                if ($item['quantity'] > $product->stock) {
                    throw new \Exception("Not enough stock for \"{$product->name}\". Available: {$product->stock}, requested: {$item['quantity']}.");
                }

                $total += $product->price * $item['quantity'];
            }

            if ($total >= 30) {
                $priority = 'high';
            } elseif ($total >= 10) {
                $priority = 'medium';
            } else {
                $priority = 'low';
            }

            $sale = Sale::create([
                'customer_id'    => Auth::id(),
                'cashier_id'     => null,
                'total'          => $total,
                'status'         => 'pending',
                'payment_method' => 'pending',
                'voucher_type'   => 'receipt',
            ]);

            foreach ($request->products as $item) {
                $product  = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                    'subtotal'   => $subtotal,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', "Order placed successfully! Priority: {$priority}. Total: S/ " . number_format($total, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error placing order: ' . $e->getMessage());
        }
    }
}