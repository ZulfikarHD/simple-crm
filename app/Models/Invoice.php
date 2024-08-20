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
        'invoice_number', 'order_id', 'issue_date', 'due_date', 'total_amount', 'status', 'amount_paid'
    ];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
