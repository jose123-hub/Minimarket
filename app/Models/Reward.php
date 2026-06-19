<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RewardRedemption;

class Reward extends Model
{
    protected $table = 'rewards';

    protected $fillable = [
        'name',
        'description',
        'type',
        'stars_required',
        'discount_value',
        'available_stock',
        'status',
        'start_date',
        'end_date',
    ];

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class, 'reward_id');
    }
}