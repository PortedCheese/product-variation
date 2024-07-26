<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_sets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("order_item_id")
                ->comment("Позиция заказа для комплекта");

            $table->unsignedBigInteger("variation_id")
                ->comment("Вариация товара");

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
        Schema::dropIfExists('order_item_sets');

    }
}
