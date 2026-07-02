<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionCode extends Model
{
    protected $fillable = [
        'code',
        'payment_method',
        'discount_type',
        'value',
        'minimum_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function isAvailableFor(string $paymentMethod, float $subtotal): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->payment_method !== 'all' && $this->payment_method !== $paymentMethod) {
            return false;
        }

        if ($subtotal < $this->minimum_amount) {
            return false;
        }

        if ($this->start_date && now()->toDateString() < $this->start_date->toDateString()) {
            return false;
        }

        if ($this->end_date && now()->toDateString() > $this->end_date->toDateString()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            return round($subtotal * ($this->value / 100), 2);
        }

        return min((float) $this->value, $subtotal);
    }
}