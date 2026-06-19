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

    public function create()
    {
        $products = Product::with('category')->get();
        $customers = User::where('role', 'customer')->get();
        $categories = \App\Models\Category::all();
        return view('cashier.sales.create', compact('products', 'customers', 'categories'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'cashier_id'  => Auth::id(),
                'total'       => 0
            ]);

            $total = 0;

            foreach ($request->products as $item) {
                $product  = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                    'subtotal'   => $subtotal
                ]);

                $product->stock -= $item['quantity'];
                $product->save();

                $total += $subtotal;
            }

            $sale->update(['total' => $total]);

            $starsEarned = (int) floor($total);

            $client = Client::where('user_id', $request->customer_id)->first();

            if ($client && $starsEarned > 0) {
                $client->accumulated_stars += $starsEarned;
                $client->save();

                StarHistory::create([
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