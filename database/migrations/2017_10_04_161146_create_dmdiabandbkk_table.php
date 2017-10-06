<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdiabandbkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdiabandbkk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madiaban')->nullable();
            $table->string('tendiaban')->nullable();
            $table->date('ngaytu')->nullable();
            $table->string('thangtu')->nullable();
            $table->string('namtu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('thangden')->nullable();
            $table->string('namden')->nullable();
            $table->string('phanloai')->nullable();//phân loại theo thông tư, quyết định: theo thông tu 2017,...
            $table->string('madv')->nullable();
            $table->string('ghichu')->nullable();
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
        Schema::drop('dmdiabandbkk');
    }
}
