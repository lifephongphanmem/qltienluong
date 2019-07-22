<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosokhenthuongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosokhenthuong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->date('ngaythang')->nullable();
            $table->string('hinhthuc', 100)->nullable();
            $table->string('noidung')->nullable();
            $table->string('capqd', 100)->nullable();
            $table->string('ghichu')->nullable();
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
        Schema::drop('hosokhenthuong');
    }
}
