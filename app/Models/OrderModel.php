<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'service_date',
        'status',
        'total_amount',
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(CustomerModel::class);
    }

    public function invoices() : HasMany
    {
        return $this->hasMany(InvoiceModel::class);
    }
}
