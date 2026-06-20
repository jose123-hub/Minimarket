<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'order_number',
        'order_date',
        'estimated_delivery',
        'actual_delivery',
        'total',
        'status',
        'notes',
        'supplier_id',
        'user_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }
}