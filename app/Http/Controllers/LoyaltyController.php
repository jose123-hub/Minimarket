<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\StarHistory;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::with('user')->get();
        $selected = null;
        $history = collect();

        if ($request->has('client_id')) {
            $selected = Client::with('user')->find($request->client_id);
            $history = StarHistory::where('client_id', $selected->id_cliente)
                ->latest('date')
                ->take(10)
                ->get();
        }

        return view('cashier.loyalty', compact('clients', 'selected', 'history'));
    }

    public function earn(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $stars = (int) floor($request->amount);

        if ($stars <= 0) {
            return redirect()->back()->with('error', 'Invalid amount.');
        }

        $client->accumulated_stars += $stars;
        $client->save();

        StarHistory::create([
            'movement_type' => 'earned',
            'amount'        => $stars,
            'reason'        => 'Manual purchase — S/ ' . $request->amount,
            'date'          => now(),
            'client_id'     => $client->id_cliente,
        ]);

        return redirect()->route('cashier.loyalty', ['client_id' => $client->id_cliente])
            ->with('success', "+{$stars} stars added successfully.");
    }

    public function redeem(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $stars = (int) $request->stars;

        if ($stars <= 0) {
            return redirect()->back()->with('error', 'Invalid stars amount.');
        }

        if ($client->accumulated_stars < $stars) {
            return redirect()->back()->with('error', 'Not enough stars.');
        }

        $client->accumulated_stars -= $stars;
        $client->save();

        StarHistory::create([
            'movement_type' => 'redeemed',
            'amount'        => $stars,
            'reason'        => 'Redemption — discount S/ ' . number_format($stars / 20, 2),
            'date'          => now(),
            'client_id'     => $client->id_cliente,
        ]);

        return redirect()->route('cashier.loyalty', ['client_id' => $client->id_cliente])
            ->with('success', "{$stars} stars redeemed successfully.");
    }
}