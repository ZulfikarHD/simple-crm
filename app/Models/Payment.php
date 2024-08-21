<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'amount_paid',
        'payment_method',
        'payment_date'
    ];

    // Relationships
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'order_id', 'order_id');
    }
}
