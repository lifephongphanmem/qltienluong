<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosollvtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosollvt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->foreign('macanbo')->references('macanbo')->on('hosocanbo')->onUpdate('cascade')->onDelete('cascade');

            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('quanham', 100)->nullable();
            $table->string('chucvu', 100)->nullable();
            $table->boolean('qhcn')->default(0);
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
        Schema::drop('hosollvt');
    }
}
