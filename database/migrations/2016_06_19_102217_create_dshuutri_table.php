<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDshuutriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dshuutri', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maht', 50)->unique();
            $table->string('soqd', 50)->nullable();
            $table->string('ngayqd')->nullable();
            $table->string('nguoiky', 50)->nullable();
            $table->string('coquanqd', 150)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngayxet')->nullable();
            $table->string('madv',50)->nullable();
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
        Schema::drop('dshuutri');
    }
}
