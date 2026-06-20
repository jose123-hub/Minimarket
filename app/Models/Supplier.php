<?php

namespace App\Models;

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
}