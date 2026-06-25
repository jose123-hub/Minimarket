<?php

namespace App\Models;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'company_name',
        'ruc',
        'contact_name',
        'phone',
        'email',
        'address',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
    public function purchaseOrders()
    {
    return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }
}