<?php

namespace App\Models;
use App\Models\Discount;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode',
        'name',
        'description',
        'price',
        'cost',
        'stock',
        'image',
        'min_stock',
        'max_stock',
        'category_id',
        'supplier_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function discounts()
    {
    return $this->belongsToMany(Discount::class, 'product_discounts', 'product_id', 'discount_id');
    }

    public function activeDiscount()
    {
    return $this->discounts()
        ->where('status', 'active')
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->orderByDesc('value')
        ->first();
    }   
    public function finalPrice()
    {
    $discount = $this->activeDiscount();

    if (! $discount) {
        return (float) $this->price;
    }

    if ($discount->type === 'percentage') {
        return round($this->price - ($this->price * $discount->value / 100), 2);
    }

    if ($discount->type === 'fixed') {
        return max(round($this->price - $discount->value, 2), 0);
    }

    return (float) $this->price;
    }
}