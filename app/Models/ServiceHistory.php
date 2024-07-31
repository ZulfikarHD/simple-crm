<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceHistory extends Model
{
    use HasFactory;
    protected $table = "service_history";

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function serviceItems() : HasMany
    {
        return $this->hasMany(ServiceItem::class);
    }
}
