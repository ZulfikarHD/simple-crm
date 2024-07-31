<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'customer_id', 'service_date', 'status', 'notes'
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice() : HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function orderInventories() : HasMany
    {
        return $this->hasMany(OrderInventory::class);
    }

    public function serviceHistories() : HasMany
    {
        return $this->hasMany(ServiceHistory::class);
    }

    public function reminders() : HasMany
    {
        return $this->hasMany(Reminder::class);
    }
}
