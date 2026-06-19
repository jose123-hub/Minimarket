<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    protected $table = 'reward_redemptions';

    protected $fillable = [
        'redemption_date',
        'stars_used',
        'status',
        'client_id',
        'reward_id',
        'employee_id',
        'sale_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_cliente');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}