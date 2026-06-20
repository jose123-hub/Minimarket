<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductDiscount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = ProductDiscount::with(['product', 'discount'])
            ->latest()
            ->get();
        $products = Product::all();
        return view('admin.promotions.index', compact('discounts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'value'      => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $status = $request->has('activate_now') ? 'active' : 'inactive';

        $discount = Discount::create([
            'name'       => 'Discount ' . $request->value . '% — ' . Product::find($request->product_id)->nombre,
            'type'       => 'percentage',
            'value'      => $request->value,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $status,
        ]);

        ProductDiscount::create([
            'product_id'  => $request->product_id,
            'discount_id' => $discount->id,
        ]);

        return redirect('/admin/promotions')->with('success', 'Promotion created successfully.');
    }

    public function update(Request $request, Discount $discount)
    {
        $discount->update([
            'value'      => $request->value,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $request->status,
        ]);

        return redirect('/admin/promotions')->with('success', 'Promotion updated successfully.');
    }
}