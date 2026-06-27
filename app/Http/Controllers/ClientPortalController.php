<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reward;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class ClientPortalController extends Controller
{
    private function currentClient()
    {
        $user = Auth::user();

        return Client::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
                'type' => 'regular',
                'accumulated_stars' => 0,
            ]
        );
    }

    public function stars()
    {
    $user = Auth::user();

    $client = Client::firstOrCreate(
        ['user_id' => $user->id],
        [
            'first_name' => $user->name,
            'last_name' => '',
            'email' => $user->email,
            'type' => 'regular',
            'accumulated_stars' => 0,
        ]
    );

    $rewards = Reward::where('status', 'active')
        ->where('available_stock', '>', 0)
        ->where(function ($query) {
            $query->whereNull('start_date')
                ->orWhereDate('start_date', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')
                ->orWhereDate('end_date', '>=', now());
        })
        ->orderBy('stars_required')
        ->get();

    return view('client.stars', compact('client', 'rewards'));
    }
    public function orders()
    {
        $client = $this->currentClient();

        $sales = Sale::with('details.product')
            ->where(function ($query) use ($client) {
                $query->where('customer_id', $client->id_cliente)
                    ->orWhere('customer_id', $client->user_id);
            })
            ->latest()
            ->get();

        return view('client.orders', compact('client', 'sales'));
    }

    public function profile()
    {
        $client = $this->currentClient();

        return view('client.profile', compact('client'));
    }
    public function receipt($saleId)
    {
    $client = $this->currentClient();

    $sale = Sale::with('details.product')
        ->where('id', $saleId)
        ->where(function ($query) use ($client) {
            $query->where('customer_id', Auth::id())
                  ->orWhere('customer_id', $client->id_cliente);
        })
        ->firstOrFail();

    return view('client.receipt', compact('client', 'sale'));
    }
}