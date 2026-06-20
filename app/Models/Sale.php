<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'cashier_id',
        'cash_opening_id',
        'total',
        'discount',
        'tax',
        'status',
        'payment_method',
        'voucher_type',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function cashOpening()
    {
        return $this->belongsTo(CashOpening::class, 'cash_opening_id');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }
}