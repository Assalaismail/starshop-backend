<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class couponCodes extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'value',
        'type',
        'type_option'
    ];
}
