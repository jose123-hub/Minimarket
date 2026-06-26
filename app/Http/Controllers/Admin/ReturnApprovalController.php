<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\SaleReturn;
use App\Models\StarHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReturnApprovalController extends Controller
{
    public function index()
    {
        $returns = SaleReturn::with(['sale', 'user', 'details.product'])
            ->latest()
            ->get();

        return view('admin.returns.index', compact('returns'));
    }

    public function approve(SaleReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'This return has already been processed.');
        }

        DB::transaction(function () use ($return) {
            $return->load(['sale', 'details']);

            foreach ($return->details as $detail) {
                if ($detail->quantity > 0) {
                    $detail->product()->increment('stock', $detail->quantity);
                }
            }

            $return->status = 'approved';
            $return->save();

            $sale = $return->sale;

            if ($sale && $sale->customer_id) {
                $client = Client::where('user_id', $sale->customer_id)
                    ->lockForUpdate()
                    ->first();

                if ($client) {
                    $starsToRemove = (int) floor($return->amount_returned / 5);

                    $starsToRemove = min($starsToRemove, (int) $client->accumulated_stars);

                    if ($starsToRemove > 0) {
                        $client->accumulated_stars -= $starsToRemove;
                        $client->save();

                        StarHistory::create([
                            'movement_type' => 'redeemed',
                            'amount'        => $starsToRemove,
                            'reason'        => 'Stars adjusted for approved return #' . $return->id,
                            'date'          => now(),
                            'client_id'     => $client->id_cliente,
                            'redemption_id' => null,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Return approved successfully. Stock and customer stars were adjusted.');
    }

    public function reject(Request $request, SaleReturn $return)
    {
    if ($return->status !== 'pending') {
        return redirect()
            ->back()
            ->with('error', 'This return has already been processed.');
    }

    $request->validate([
        'rejection_reason' => 'required|string|max:255',
    ]);

    $return->status = 'rejected';
    $return->rejection_reason = $request->rejection_reason;
    $return->save();

    return redirect()
        ->back()
        ->with('success', 'Return rejected successfully.');
    }
}