<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $appends = ['total_item'];
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function transactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function salesChannel()
    {
        return $this->belongsTo(SalesChannel::class);
    }
    public function getTotalItemAttribute()
    {
        // Priority 1: SQL-aggregated value from ->withSum() — zero extra query
        if (!is_null($this->getAttribute('products_sum_quantity'))) {
            return (int) $this->products_sum_quantity;
        }
        // Priority 2: Already-loaded relation — zero extra query
        if ($this->relationLoaded('products')) {
            return $this->products->sum('quantity');
        }
        // Fallback: one DB query (avoid in list contexts — use withSum instead)
        return $this->products()->sum('quantity');
    }
   
}
