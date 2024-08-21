<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id', 'quantity_change', 'movement_type',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
