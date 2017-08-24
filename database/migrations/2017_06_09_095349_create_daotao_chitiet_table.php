<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaotaoChitietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsdaotao_chitiet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mads')->nullable();
            $table->string('macanbo')->nullable();
            $table->string('mapb')->nullable();
            $table->string('macvcq')->nullable();
            $table->string('noidung')->nullable(); // ghi lý do tốt nghiệp hoặc chưa hoàn thành khóa học
            $table->text('ghichu')->nullable();
            $table->string('trangthai')->nullable();//đã hoàn thành / đang thực hiện
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
        Schema::drop('dsdaotao_chitiet');
    }
}
