<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;
    // Specify the table name if it's not the plural form of the model name
    protected $table = 'inventories';

    // Allow mass assignment on these attributes
    protected $fillable = [
        'item_name',
        'quantity',
        'unit_price',
    ];

    /**
     * The orders that belong to the inventory item.
     * This defines the many-to-many relationship between orders and inventory items.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_inventory')
                    ->withPivot('quantity_used', 'price_per_unit', 'discount', 'tax_rate', 'total_price')
                    ->withTimestamps();
    }



    // protected $table = "inventory";

    // protected $fillable = [
    //     'item_name', 'quantity', 'unit_price', 'threshold_level', 'reorder_quantity'
    // ];

    // public function orderInventories() : HasMany
    // {
    //     return $this->hasMany(OrderInventory::class);
    // }

    // public function serviceItems() : HasMany
    // {
    //     return $this->hasMany(ServiceItem::class);
    // }

    // public function orders() :BelongsToMany
    // {
    //     return $this->belongsToMany(Order::class)->withPivot('quantity', 'unit_price');
    // }
}
