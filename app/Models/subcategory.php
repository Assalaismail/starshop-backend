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
        'category_id',
    ];

    public function categories(){
        return $this->belongsTo(categories::class, 'category_id', 'id');
    }

    public static function getByCategoryId($category_id)
    {
        return self::where('category_id', $category_id)->get();
    }
}
