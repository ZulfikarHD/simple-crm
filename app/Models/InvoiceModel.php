<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceModel extends Model
{
    use HasFactory;
    protected $table = "invoices";

    protected $fillable = [
        'invoice_number',
        'order_id',
        'issue_date',
        'due_date',
        'amount',
        'status',
    ];

    public function order() : BelongsTo
    {
        return $this->belongsTo(OrderModel::class);
    }
}
