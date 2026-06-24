<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\StarHistory;
use Illuminate\Http\Request;

class LoyaltyOverviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('user')->withCount('starHistory');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                  ->orWhere('last_name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $clients = $query->orderByDesc('accumulated_stars')->get();

        $totalStarsOutstanding = $clients->sum('accumulated_stars');
        $totalEarned   = StarHistory::where('movement_type', 'earned')->sum('amount');
        $totalRedeemed = StarHistory::where('movement_type', 'redeemed')->sum('amount');

        return view('admin.loyalty.index', compact(
            'clients', 'totalStarsOutstanding', 'totalEarned', 'totalRedeemed'
        ));
    }

    public function show(Client $client)
    {
        $client->load('user');

        $history = StarHistory::where('client_id', $client->id_cliente)
            ->with(['sale', 'redemption.reward'])
            ->latest('date')
            ->get();

        return view('admin.loyalty.show', compact('client', 'history'));
    }
}