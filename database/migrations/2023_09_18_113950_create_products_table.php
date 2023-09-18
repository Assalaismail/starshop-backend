<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->json('images');
            $table->string('sku');
            $table->string('barcode');
            $table->string('status');
            $table->string('stock_status');
            $table->string('price');
            $table->string('discount_price');
            $table->string('new_price');
            $table->string('quantity');
            $table->string('color');
            $table->string('size');
            $table->string('shoes_size');
            $table->string('category_id');
            $table->string('subcategory_id');
            $table->string('subcategory_abbreviation');
            $table->string('season_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
