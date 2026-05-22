<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardUsage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function rule()
    {
        return $this->belongsTo(RewardRule::class, 'reward_rule_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
