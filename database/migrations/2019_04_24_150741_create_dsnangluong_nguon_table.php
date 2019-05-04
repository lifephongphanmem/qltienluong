<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsnangluongNguonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsnangluong_nguon', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manl', 50)->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->string('manguonkp',50)->nullable();
            $table->double('luongcoban')->default(0);
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
        Schema::drop('dsnangluong_nguon');
    }
}
