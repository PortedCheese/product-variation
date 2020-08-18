<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("product_id")
                ->comment("Ссылка на товар");

            $table->string("sku")
                ->unique()
                ->comment("Артикул");

            $table->decimal("price")
                ->comment("Цена");

            $table->decimal("sale_price")
                ->nullable()
                ->comment("Цена со скидкой");

            $table->boolean("sale")
                ->default(0)
                ->comment("Действует скидка");

            $table->dateTime("disabled_at")
                ->nullable()
                ->comment("Недоступно");

            $table->string("description")
                ->nullable()
                ->comment("Описание");

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
        Schema::dropIfExists('product_variations');
    }
}
