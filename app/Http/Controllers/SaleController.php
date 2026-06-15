<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
    $sales = Sale::with([
        'customer',
        'cashier'
    ])->latest()->get();

    return view(
        'admin.sales.index',
        compact('sales')
    );
    }
    public function create()
    {
        $products = Product::all();

        $customers = User::where('role', 'customer')->get();

        return view('cashier.sales.create', compact(
            'products',
            'customers'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'cashier_id' => Auth::id(),
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->products as $item) {

                $product = Product::findOrFail(
                    $item['product_id']
                );

                $subtotal =
                    $product->precio *
                    $item['quantity'];

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->precio,
                    'subtotal' => $subtotal
                ]);

                $product->stock -= $item['quantity'];
                $product->save();

                $total += $subtotal;
            }

            $sale->update([
                'total' => $total
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Sale registered successfully.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Error registering sale.'
                );
        }
    }
}