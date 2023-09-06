<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class setattributes extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'status',
    ];

    public function attributes(){
        return $this->hasMany(attributes::class, 'attribute_set_id', 'id');
    }
}
