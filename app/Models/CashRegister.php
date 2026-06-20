<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $table = 'cash_registers';

    protected $fillable = [
        'name',
        'location',
        'status',
    ];

    public function openings()
    {
        return $this->hasMany(CashOpening::class, 'cash_register_id');
    }

    public function currentOpening()
    {
        return $this->hasOne(CashOpening::class, 'cash_register_id')
                    ->where('status', 'open')
                    ->latest();
    }
}