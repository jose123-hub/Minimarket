<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierOnlineOrderController extends Controller
{
    public function index()
    {
        $orders = Sale::with(['details.product', 'customer'])
            ->whereNotNull('receipt_number')
            ->where('payment_status', 'paid')
            ->whereIn('order_status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->get();

        return view('cashier.online-orders.index', compact('orders'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['details.product', 'customer']);

        return view('cashier.online-orders.show', compact('sale'));
    }

    public function updateStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'order_status' => 'required|in:preparing,ready,delivered',
        ]);

        $sale->update([
            'order_status' => $request->order_status,
            'cashier_id' => Auth::id(),
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }
}