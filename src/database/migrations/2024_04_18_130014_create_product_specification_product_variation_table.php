<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSpecificationProductVariationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_specification_product_variation', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variation_id')
                ->comment('Вариация товара');
            $table->unsignedBigInteger('product_specification_id')
                ->comment('Характеристики товара');
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
        Schema::dropIfExists('product_specification_product_variation');
    }
}
