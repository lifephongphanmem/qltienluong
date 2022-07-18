<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphanloaidonviBaocaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphanloaidonvi_baocao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masobc')->nullable();
            $table->string('madvbc')->nullable();
            $table->string('maphanloai_goc')->nullable();
            $table->string('maphanloai_nhom')->nullable();
            $table->string('tenphanloai_nhom')->nullable();
            $table->boolean('chitiet')->default(1);//trong trường hợp maphanloai != '' => 1: dải chi tiết đơn vị; 0: nhóm thành 01
            $table->integer('capdo_nhom')->default(1);
            $table->integer('sapxep')->default(1);
            $table->string('maphanloai')->nullable(); //liệt kê các phân loại cần thống kê vào nhóm
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
        Schema::dropIfExists('dmphanloaidonvi_baocao');
    }
}
