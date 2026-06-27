<?php

namespace App\Models;

use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
    'invoice_number',
    'receipt_number',

    'customer_id',
    'cashier_id',
    'cash_opening_id',

    'total',
    'discount',
    'tax',

    'status',
    'order_status',

    'payment_method',
    'payment_status',
    'card_last_four',

    'voucher_type',

    'delivery_type',
    'delivery_address',
    'delivery_reference',
    'delivery_phone',

    'pickup_store',
    'pickup_note',
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
        return $this->hasMany(SaleDetail::class);
    }
    public function returns()
    {
    return $this->hasMany(SaleReturn::class);
    }
}