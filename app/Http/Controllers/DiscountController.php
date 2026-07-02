<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\PromotionCode;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = ProductDiscount::with(['product', 'discount'])
            ->latest()
            ->get();
        $products = Product::with('category')
            ->orderBy('name')
            ->get();
        $promoCodes = PromotionCode::latest()->get();
        return view('admin.promotions.index', compact('discounts', 'products', 'promoCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
         'product_id' => 'required|exists:products,id',
         'value' => 'required|integer|min:1|max:100',
         'start_date' => 'required|date',
         'end_date' => 'required|date|after:start_date',
        ]);

        $status = $request->has('activate_now') ? 'active' : 'inactive';

        $discount = Discount::create([
            'name'       => 'Discount ' . $request->value . '% — ' . Product::find($request->product_id)->name,
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
    $request->validate([
        'value'      => 'required|integer|min:1|max:100',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after:start_date',
        'status'     => 'required|in:active,inactive',
    ]);

    $discount->update([
        'value'      => $request->value,
        'start_date' => $request->start_date,
        'end_date'   => $request->end_date,
        'status'     => $request->status,
    ]);

    return redirect('/admin/promotions')->with('success', 'Promotion updated successfully.');
    }
    public function storeCode(Request $request)
    {
    $validated = $request->validate([
        'code' => 'required|string|max:30|unique:promotion_codes,code',
        'payment_method' => 'required|in:all,cash,card,yape,plin',
        'discount_type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0.01',
        'minimum_amount' => 'required|numeric|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'usage_limit' => 'nullable|integer|min:1',
        'status' => 'required|in:active,inactive',
    ]);

    $validated['code'] = strtoupper(trim($validated['code']));

    PromotionCode::create($validated);

    return redirect()
        ->route('admin.promotions')
        ->with('success', 'Promotion code created successfully.');
    }

    public function updateCode(Request $request, PromotionCode $promotionCode)
    {
    $validated = $request->validate([
        'code' => 'required|string|max:30|unique:promotion_codes,code,' . $promotionCode->id,
        'payment_method' => 'required|in:all,cash,card,yape,plin',
        'discount_type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0.01',
        'minimum_amount' => 'required|numeric|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'usage_limit' => 'nullable|integer|min:1',
        'status' => 'required|in:active,inactive',
    ]);

    $validated['code'] = strtoupper(trim($validated['code']));

    $promotionCode->update($validated);

    return redirect()
        ->route('admin.promotions')
        ->with('success', 'Promotion code updated successfully.');
    }

    public function destroyCode(PromotionCode $promotionCode) 
    {
    $promotionCode->delete();

    return redirect()
        ->route('admin.promotions')
        ->with('success', 'Promotion code deleted successfully.');
    }
}