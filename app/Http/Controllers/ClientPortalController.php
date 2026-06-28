<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\Sale;
use App\Models\StarHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
                'star_progress_amount' => 0,
                'store_credit_balance' => 0,
            ]
        );
    }

    public function stars()
    {
        $client = $this->currentClient();

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

    public function redeemReward(Reward $reward)
    {
        $creditAmount = DB::transaction(function () use ($reward) {
            $client = Client::where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

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
                ->lockForUpdate()
                ->findOrFail($reward->id);

            if ($client->accumulated_stars < $reward->stars_required) {
                throw ValidationException::withMessages([
                    'reward' => 'You do not have enough stars to redeem this reward.',
                ]);
            }

            $creditAmount = $reward->type === 'discount'
                ? (float) $reward->discount_value
                : 0;

            $client->update([
                'accumulated_stars' => $client->accumulated_stars - $reward->stars_required,
                'store_credit_balance' => round(((float) ($client->store_credit_balance ?? 0)) + $creditAmount, 2),
            ]);

            $reward->update([
                'available_stock' => $reward->available_stock - 1,
            ]);

            $redemption = RewardRedemption::create([
                'redemption_date' => now(),
                'stars_used' => $reward->stars_required,
                'status' => 'completed',
                'client_id' => $client->id_cliente,
                'reward_id' => $reward->id,
                'employee_id' => null,
                'sale_id' => null,
            ]);

            StarHistory::create([
                'movement_type' => 'redeemed',
                'amount' => $reward->stars_required,
                'reason' => 'Reward redeemed - ' . $reward->name . ($creditAmount > 0 ? ' - S/ ' . number_format($creditAmount, 2) . ' store credit' : ''),
                'date' => now(),
                'client_id' => $client->id_cliente,
                'redemption_id' => $redemption->id,
            ]);

            return $creditAmount;
        });

        $message = $creditAmount > 0
            ? 'Reward redeemed. S/ ' . number_format($creditAmount, 2) . ' was added to your rewards credit.'
            : 'Reward redeemed successfully.';

        return redirect()
            ->route('client.stars')
            ->with('success', $message);
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