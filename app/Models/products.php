<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'images',
        'sku',
        'barcode',
        'status',
        'stock_status',
        'price',
        'discount_price',
        'new_price',
        'quantity',
        'color',
        'size',
        'shoes_size',
        'category_id',
        'subcategory_id',
        'subcategory_abbreviation',
        'season_code',

    ];

    public function categories(){
        return $this->belongsTo(categories::class, 'category_id', 'id');
    }

    public function subcategory(){
        return $this->belongsTo(subcategory::class, 'subcategory_id', 'id');
    }

}
