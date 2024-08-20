<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';

    protected $fillable = ['order_id', 'amount', 'payment_date', 'payment_method'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
