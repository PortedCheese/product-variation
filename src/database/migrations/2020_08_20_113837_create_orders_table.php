<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string("number")
                ->unique()
                ->comment("Номер заказа");

            $table->unsignedBigInteger("user_id")
                ->nullable()
                ->comment("Пользователь");

            $table->json("user_data")
                ->comment("Данные пользователя");

            $table->unsignedBigInteger("state_id")
                ->comment("Статус заказа");

            $table->unsignedDecimal("total")
                ->default(0)
                ->comment("Сумма");

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
        Schema::dropIfExists('orders');
    }
}
