<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierOnlineOrderController extends Controller
{
    private function onlineOrderQuery()
    {
        return Sale::with(['details.product', 'customer'])
            ->whereNotNull('receipt_number')
            ->where('payment_status', 'paid');
    }

    public function index()
    {
        $orders = $this->onlineOrderQuery()
            ->whereIn('order_status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->get();

        return view('cashier.online-orders.index', compact('orders'));
    }

    public function show(Sale $sale)
    {
        $sale = $this->onlineOrderQuery()
            ->where('id', $sale->id)
            ->firstOrFail();

        return view('cashier.online-orders.show', compact('sale'));
    }

    public function updateStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'order_status' => 'required|in:preparing,ready,delivered',
        ]);

        $sale = Sale::whereNotNull('receipt_number')
            ->where('payment_status', 'paid')
            ->where('id', $sale->id)
            ->firstOrFail();

        if (in_array($sale->order_status, ['cancelled', 'delivered'])) {
            return back()->with('error', 'This order can no longer be updated.');
        }

        $sale->update([
            'order_status' => $request->order_status,
            'cashier_id' => Auth::id(),
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }
}