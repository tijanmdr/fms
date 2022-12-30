<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beverages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('photo');
            $table->text('allergic')->default("[]"); // default []
            $table->integer('allergic_id')->default(-1);
            $table->text('ingredients');
            $table->double('price', 8, 2);
            // $table->integer('hot')->default(0); // 0 normal, 1 mild, 2 hot, 3 very hot
            // $table->string('sauce')->default("[]"); // default []
            $table->integer('hide')->default(0); // 0 show 1 hide
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
        Schema::dropIfExists('beverages');
    }
}
