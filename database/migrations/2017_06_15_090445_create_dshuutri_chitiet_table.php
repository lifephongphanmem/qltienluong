<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDshuutriChitietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dshuutri_chitiet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mads', 50)->unique();
            $table->string('macanbo', 50)->nullable();
            $table->string('noidung')->nullable();
            $table->text('ghichu')->nullable();
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
        Schema::drop('dshuutri_chitiet');
    }
}
