<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphucapTable extends Migration
{
    /**
     * Dùng chung cho các đơn vị
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphucap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mapc', 50)->unique();
            $table->string('tenpc', 100)->nullable();
            $table->boolean('baohiem')->nullable();//chưa dùng
            $table->string('form')->nullable(); //tiêu đề trên Form
            $table->string('report')->nullable(); //tiêu đề trên Report
            $table->string('phanloai')->nullable();
            $table->string('congthuc')->nullable();//
            $table->string('ghichu')->nullable();
            $table->integer('stt')->default(99);
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
        Schema::drop('dmphucap');
    }
}
