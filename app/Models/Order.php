<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'customer_id', 'service_date', 'status', 'notes'
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // public function inventories() : BelongsToMany
    // {
    //     return $this->belongsToMany(Inventory::class)->withPivot('quantity', 'unit_price');
    // }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->inventories->sum(function ($inventory) {
            return $inventory->pivot->quantity * $inventory->pivot->unit_price;
        });
    }
    public function inventories()
{
    return $this->belongsToMany(Inventory::class, 'order_inventory')
                ->withPivot('quantity_used', 'price_per_unit', 'discount', 'tax_rate', 'total_price')
                ->withTimestamps();
}
}
