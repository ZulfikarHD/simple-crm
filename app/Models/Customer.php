<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'name', 'address', 'phone_number', 'email', 'social_media_profile', 'feedback', 'loyalty_points'
    ];

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reminders() : HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function serviceHistories() : HasMany
    {
        return $this->hasMany(ServiceHistory::class);
    }
}
