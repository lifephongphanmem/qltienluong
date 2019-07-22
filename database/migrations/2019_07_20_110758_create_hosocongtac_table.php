<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosocongtacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosocongtac', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->date('ngaytu');
            $table->date('ngayden');
            $table->string('noidung')->nullable();
            $table->string('phanloai')->nullable();
            $table->string('linhvuc')->nullable();
            $table->string('noiden')->nullable();
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
        Schema::drop('hosocongtac');
    }
}
