<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    protected $table = 'return_details';

    protected $fillable = [
        'quantity',
        'unit_price',
        'subtotal',
        'return_id',
        'product_id',
    ];

    public function returnRecord()
    {
    return $this->belongsTo(SaleReturn::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}