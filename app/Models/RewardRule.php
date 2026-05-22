<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'eligible_product_ids' => 'array',
        'eligible_category_ids' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'is_stackable' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(RewardUsage::class);
    }
}
