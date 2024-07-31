<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'inventory_id', 'description', 'quantity', 'unit_price', 'total_price'
    ];

    public function serviceHistory() : BelongsTo
    {
        return $this->belongsTo(ServiceHistory::class, 'service_id');
    }

    public function inventory() : BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
