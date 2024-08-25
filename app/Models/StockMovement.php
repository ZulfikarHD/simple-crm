<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'quantity',
        'movement_type', // 'in' for stock additions, 'out' for stock deductions
        'description',
        'movement_date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
