<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_states', function (Blueprint $table) {
            $table->id();

            $table->string("title")
                ->comment("Заголовок");

            $table->string("slug")
                ->unique()
                ->comment("Адресная строка");

            $table->string("key")
                ->unique()
                ->comment("Ключ для доступа");

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
        Schema::dropIfExists('order_states');
    }
}
