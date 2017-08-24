<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosobinhbauTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosobinhbau', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->foreign('macanbo')->references('macanbo')->on('hosocanbo')->onUpdate('cascade')->onDelete('cascade');
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('hinhthuc', 100)->nullable();
            $table->string('noidung')->nullable();
            $table->string('ketqua', 100)->nullable();
            $table->string('madv', 50)->nullable();
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
        Schema::drop('hosobinhbau');
    }
}
