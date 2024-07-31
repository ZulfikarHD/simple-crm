<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;
    protected $table = "inventory";

    protected $fillable = [
        'item_name', 'quantity', 'unit_price', 'threshold_level', 'reorder_quantity'
    ];

    public function orderInventories() : HasMany
    {
        return $this->hasMany(OrderInventory::class);
    }

    public function serviceItems() : HasMany
    {
        return $this->hasMany(ServiceItem::class);
    }
}
