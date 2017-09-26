<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosoluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosoluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manl', 50)->nullable();
            $table->string('macanbo', 50);
            $table->string('phanloai', 50)->nullable();
            $table->string('msngbac', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->double('bac')->default(1);
            $table->double('heso')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pthuong')->default(100);
            $table->string('ketqua', 50)->nullable();
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
        Schema::drop('hosoluong');
    }
}
