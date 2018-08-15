<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTonghopluongTinhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tonghopluong_tinh', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mathdv',50)->unique();
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->text('noidung')->nullable();
            $table->date('ngaylap')->nullable();
            $table->string('nguoilap',50)->nullable();
            $table->text('ghichu')->nullable();
            $table->date('ngaygui')->nullable();
            $table->string('nguoigui',50)->nullable();
            $table->string('trangthai',50)->nullable();
            $table->string('phanloai',50)->nullable(); //dữ liệu của đơn vị / dữ liệu của đơn vị cấp dưới
            $table->text('lydo')->nullable();
            $table->string('madv',50)->nullable();
            $table->string('madvbc',50)->nullable();
            $table->string('macqcq',50)->nullable();
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
        Schema::drop('tonghopluong_tinh');
    }
}
