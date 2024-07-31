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
        'invoice_id', 'payment_date', 'amount', 'payment_method'
    ];

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
