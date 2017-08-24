<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsdaotaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsdaotao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mads', 50)->unique();
            $table->string('soqd', 50)->nullable();
            $table->string('ngayqd')->nullable();
            $table->string('nguoiky', 50)->nullable();
            $table->string('coquanqd', 150)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('nguonkinhphi')->nullable();
            $table->text('ghichu')->nullable();
            $table->date('ngayhoanthanh')->nullable();
            $table->text('trangthai')->nullable();//đã hoàn thành / đang thực hiện
            $table->string('madv',50)->nullable();
            $table->string('dinhkem')->nullable();
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
        Schema::drop('dsdaotao');
    }
}
