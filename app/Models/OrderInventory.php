<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInventory extends Model
{
    use HasFactory;
    // protected $tables = "order_inventory";
    protected $guarded = ['id','created_at','updated_at'];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function inventory() : BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
