<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countries extends Model
{
    use HasFactory;


    protected $fillable = [
        'sortname',
        'name',
        'phonecode',
    ];


    public function states(){
        return $this->hasMany(states::class, 'country_id', 'id');
    }
}
