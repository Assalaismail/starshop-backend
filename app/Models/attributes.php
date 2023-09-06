<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attributes extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'abbreviation',
        'status',
        'color',
        'attribute_set_id',
    ];


    public function setattributes(){
        return $this->belongsTo(setattributes::class, 'attribute_set_id', 'id');
    }
}
