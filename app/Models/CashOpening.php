<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashOpening extends Model
{
    protected $table = 'cash_openings';

    protected $fillable = [
        'opening_date',
        'closing_date',
        'initial_amount',
        'final_amount',
        'total_sales',
        'difference',
        'status',
        'cash_register_id',
        'user_id',
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'cash_opening_id');
    }
}