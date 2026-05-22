<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'points', 'tier', 'total_spent', 'visit_count', 'birth_date', 'last_visit_at'];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function usages()
    {
        return $this->hasMany(RewardUsage::class);
    }
}
