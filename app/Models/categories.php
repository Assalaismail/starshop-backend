<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function subcategory(){
        return $this->hasMany(subcategory::class, 'category_id', 'id');
    }


    public function products(){
        return $this->hasMany(products::class, 'category_id', 'id');
    }
}
