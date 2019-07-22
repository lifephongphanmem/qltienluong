<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosoquanhegdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosoquanhegd', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->string('phanloai')->nullable();
            $table->string('quanhe', 30)->nullable();
            $table->string('hoten', 50)->nullable();
            $table->string('ngaysinh', 20)->nullable();
            $table->string('thongtinct')->nullable();
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
        Schema::drop('hosoquanhegd');
    }
}
