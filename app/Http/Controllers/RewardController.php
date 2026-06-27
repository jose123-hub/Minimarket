<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::orderByDesc('created_at')->get();

        return view('admin.rewards.index', compact('rewards'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateReward($request);

        Reward::create($validated);

        return redirect()->route('admin.rewards')->with('success', 'Reward created successfully.');
    }

    public function update(Request $request, Reward $reward)
    {
        $validated = $this->validateReward($request);

        $reward->update($validated);

        return redirect()->route('admin.rewards')->with('success', 'Reward updated successfully.');
    }

    public function destroy(Reward $reward)
    {
        if ($reward->redemptions()->exists()) {
            $reward->update(['status' => 'inactive']);
            return redirect()->route('admin.rewards')->with('success', 'This reward has redemption history, so it was deactivated instead of deleted.');
        }

        $reward->delete();

        return redirect()->route('admin.rewards')->with('success', 'Reward deleted successfully.');
    }

    private function validateReward(Request $request): array
    {
    return $request->validate([
        'name'             => 'required|string|max:255',
        'description'      => 'nullable|string',
        'type'             => 'required|in:discount,gift',
        'stars_required'   => 'required|integer|min:1',
        'discount_value'   => 'required_if:type,discount|nullable|numeric|min:0.01',
        'available_stock'  => 'required|integer|min:0',
        'status'           => 'required|in:active,inactive',
        'start_date'       => 'nullable|date',
        'end_date'         => $request->filled('start_date') ? 'nullable|date|after_or_equal:start_date' : 'nullable|date',
    ]);
    }
}