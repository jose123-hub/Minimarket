<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class OnlineOrderController extends Controller
{
    public function index()
    {
        $orders = Sale::with(['details.product', 'customer'])
            ->whereNotNull('receipt_number')
            ->latest()
            ->get();

        return view('admin.online-orders.index', compact('orders'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['details.product', 'customer']);

        return view('admin.online-orders.show', compact('sale'));
    }

    public function updateStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'order_status' => 'required|in:pending,preparing,ready,delivered,cancelled',
        ]);

        $sale->update([
            'order_status' => $request->order_status,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }
}