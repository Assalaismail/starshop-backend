<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'shipment_status',
        'amount',
        'due_amount',
        'total',
        'wallet_amount',
        'payment_id',
        'coupon_code',
        'discount_amount',
        'is_confirmed',
    ];
}
