<?php

namespace App\Http\Controllers;

use App\Models\RewardRedemption;
use App\Models\Reward;
use App\Models\Client;
use App\Models\StarHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class LoyaltyController extends Controller
{
    public function index(Request $request)
    {
    $clients = Client::with('user')->get();
    $selected = null;
    $history = collect();

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

    if ($request->has('client_id')) {
        $selected = Client::with('user')->find($request->client_id);

        if ($selected) {
            $history = StarHistory::where('client_id', $selected->id_cliente)
                ->latest('date')
                ->take(10)
                ->get();
        }
    }

    return view('cashier.loyalty', compact('clients', 'selected', 'history', 'rewards'));
    }

    public function redeem(Request $request)
    {
    $request->validate([
        'client_id' => 'required|exists:clientes,id_cliente',
        'reward_id' => 'required|exists:rewards,id',
    ]);

    DB::beginTransaction();

    try {
        $client = Client::findOrFail($request->client_id);

        $reward = Reward::where('status', 'active')
            ->where('available_stock', '>', 0)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now());
            })
            ->findOrFail($request->reward_id);

        if ($client->accumulated_stars < $reward->stars_required) {
            DB::rollBack();

            return redirect()
                ->route('cashier.loyalty', ['client_id' => $client->id_cliente])
                ->with('error', 'Not enough stars to redeem this reward.');
        }

        $client->accumulated_stars -= $reward->stars_required;
        $client->save();

        $reward->available_stock -= 1;
        $reward->save();

        $redemption = RewardRedemption::create([
            'redemption_date' => now(),
            'stars_used'      => $reward->stars_required,
            'status'          => 'completed',
            'client_id'       => $client->id_cliente,
            'reward_id'       => $reward->id,
            'employee_id'     => Auth::id(),
            'sale_id'         => null,
        ]);

        StarHistory::create([
            'movement_type' => 'redeemed',
            'amount'        => $reward->stars_required,
            'reason'        => 'Reward redeemed — ' . $reward->name,
            'date'          => now(),
            'client_id'     => $client->id_cliente,
            'redemption_id' => $redemption->id,
        ]);

        DB::commit();

        return redirect()
            ->route('cashier.loyalty', ['client_id' => $client->id_cliente])
            ->with('success', "Reward redeemed successfully: {$reward->name}.");
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->with('error', 'Error redeeming reward: ' . $e->getMessage());
    }
    }
}