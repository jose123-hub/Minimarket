<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
        'condition',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_discounts', 'discount_id', 'product_id');
    }
}