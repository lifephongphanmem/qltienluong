<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongTrucTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluong_truc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl', 50)->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->double('heso')->default(0);
            $table->double('songay')->default(0);
            $table->double('ttl')->default(0);
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
        Schema::drop('bangluong_truc');
    }
}