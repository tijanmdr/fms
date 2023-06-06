<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order');
            $table->foreign('order')->references('id')->on('orders');
            $table->unsignedBigInteger('user');
            $table->foreign('user')->references('id')->on('users');
            $table->unsignedBigInteger('food_id')->nullable();
            $table->foreign('food_id')->references('id')->on('foods');
            $table->unsignedBigInteger('beverage_id')->nullable();
            $table->foreign('beverage_id')->references('id')->on('beverages');
            $table->integer('entree'); // 0 - entree, 1 - mains, 2 - dessert
            $table->integer('serve'); // 0 - first serve, 1 - second serve and so on
            $table->integer('quantity')->default(1);
            $table->string('note');
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
        Schema::dropIfExists('order_details');
    }
}
