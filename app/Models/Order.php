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
        'customer_id', 'service_date', 'status', 'notes', 'subtotal', 'total_amount'
    ];


    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'order_inventory')
                    ->withPivot('quantity_used', 'price_per_unit', 'discount', 'tax_rate', 'total_price')
                    ->withTimestamps();
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice() : HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
