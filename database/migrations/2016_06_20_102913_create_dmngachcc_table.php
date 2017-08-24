<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmngachccTable extends Migration
{
    /**
     * Dùng chung cho tất cả các đơn vị
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmngachcc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('msngbac', 50)->nullable();
            $table->string('phanloai', 150)->nullable();
            $table->string('tenngbac', 150)->nullable();
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
        Schema::drop('dmngachcc');
    }
}
