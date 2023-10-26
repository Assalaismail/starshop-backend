<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class couponCodes extends Model
{
    use HasFactory;

    protected $table = 'coupon_codes';

    protected $fillable = [
        'title',
        'code',
        'value',
        'start_date',
        'end_date',
        'type',
        'type_option'

    ];
}
