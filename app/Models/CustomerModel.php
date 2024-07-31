<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
    ];

    public function orders() : HasMany
    {
        return $this->hasMany(OrderModel::class);
    }
}
