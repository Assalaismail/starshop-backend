<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'abbreviation',
        'status',
        'image',
        'category_id',
    ];

    public function categories(){
        return $this->belongsTo(categories::class, 'category_id', 'id');
    }

    public function products(){
        return $this->hasMany(products::class, 'subcategory_id', 'id');
    }
}
