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
    protected $table = 'inventory';

    protected $fillable = [
        'item_name',
        'unit_price',
        'description',
    ];

    /**
     * Relationship to StockMovements.
     */
    public function stockMovements() : HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Calculate current stock based on stock movements.
     */
    public function currentStock()
    {
        return $this->stockMovements()->sum('quantity_change');
    }
}
