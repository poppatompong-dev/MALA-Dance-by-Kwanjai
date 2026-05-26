<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesChannel extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('sort_order');
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * ((float) $this->commission_percent / 100), 2);
    }
}
