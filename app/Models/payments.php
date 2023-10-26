<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'user_id',
        'charge_id',
        'payment_channel',
        'amount',
        'order_id',
        'customer_id',
        'amount_from_wallet',
        'status',
    ];
}
