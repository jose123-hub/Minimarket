<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'return_date',
        'reason',
        'amount_returned',
        'status',
        'sale_id',
        'user_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}