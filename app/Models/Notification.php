<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    protected $table = "notifications";

    protected $fillable = [
        'user_id', 'message', 'notification_date', 'read_status'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
