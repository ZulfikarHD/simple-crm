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


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_inventory')
                    ->withPivot('quantity_used', 'price_per_unit', 'discount', 'tax_rate', 'total_price')
                    ->withTimestamps();
    }
}
