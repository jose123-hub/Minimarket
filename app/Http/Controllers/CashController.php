<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    public function index()
    {
        $opening = CashOpening::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        $registers = CashRegister::where('status', 'active')->get();

        return view('cashier.cash', compact('opening', 'registers'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'cash_register_id' => 'required|exists:cash_registers,id',
            'initial_amount'   => 'required|numeric|min:0',
        ]);

        $existing = CashOpening::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You already have an open cash register.');
        }

        CashOpening::create([
            'opening_date'     => now(),
            'initial_amount'   => $request->initial_amount,
            'total_sales'      => 0,
            'status'           => 'open',
            'cash_register_id' => $request->cash_register_id,
            'user_id'          => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Cash register opened successfully.');
    }

    public function close(Request $request)
    {
    $request->validate([
        'counted_amount' => 'nullable|numeric|min:0',
    ]);

    $opening = CashOpening::where('user_id', Auth::id())
        ->where('status', 'open')
        ->firstOrFail();

    $totalSales = $opening->sales()->sum('total');
    $finalAmount = $opening->initial_amount + $totalSales;

    $opening->update([
        'closing_date' => now(),
        'final_amount' => $finalAmount,
        'total_sales'  => $totalSales,
        'difference'   => $finalAmount - ($request->counted_amount ?? $finalAmount),
        'status'       => 'closed',
    ]);

    return redirect()->back()->with('success', 'Cash register closed. Total sales: S/ ' . number_format($totalSales, 2));
    }
}