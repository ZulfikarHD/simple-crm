<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "invoices";

    protected $fillable = [
        'order_id',
        'invoice_number',
        'total_amount',
        'amount_paid',
        'issue_date',
        'due_date',
        'status'
    ];

    // Relationships
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class, 'order_id', 'order_id');
    }
}
