<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StarHistory;
use App\Models\RewardRedemption;

class Client extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'type',
        'accumulated_stars',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function starHistory()
    {
        return $this->hasMany(StarHistory::class, 'client_id', 'id_cliente');
    }

    public function rewardRedemptions()
    {
        return $this->hasMany(RewardRedemption::class, 'client_id', 'id_cliente');
    }
}