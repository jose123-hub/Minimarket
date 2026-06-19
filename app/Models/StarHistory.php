<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarHistory extends Model
{
    protected $table = 'star_history';

    protected $fillable = [
        'movement_type',
        'amount',
        'reason',
        'date',
        'client_id',
        'sale_id',
        'redemption_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_cliente');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function redemption()
    {
        return $this->belongsTo(RewardRedemption::class, 'redemption_id');
    }
}