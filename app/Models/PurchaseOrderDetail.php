<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    protected $table = 'purchase_order_details';

    protected $fillable = [
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'subtotal',
        'purchase_order_id',
        'product_id',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}