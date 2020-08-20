<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("order_id")
                ->comment("Заказ");

            $table->string("sku")
                ->comment("Артикул вариации");

            $table->decimal("price")
                ->default(0)
                ->comment("Цена");

            $table->integer("quantity")
                ->default(1)
                ->comment("Количество");

            $table->decimal("total")
                ->default(0)
                ->comment("Сумма");

            $table->string("description")
                ->nullable()
                ->comment("Описание вариации");

            $table->string("title")
                ->comment("Заголовок товара");

            $table->unsignedBigInteger("product_id")
                ->comment("Ссылка на товар");

            $table->unsignedBigInteger("variation_id")
                ->comment("Id вариации");

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
        Schema::dropIfExists('order_items');
    }
}
