<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiHuyenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_huyen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv',50)->nullable();
            $table->string('sohieu')->nullable();
            $table->text('noidung')->nullable();
            $table->date('ngaylap')->nullable();
            $table->string('nguoilap',50)->nullable();
            $table->text('ghichu')->nullable();
            $table->date('ngaygui')->nullable();
            $table->string('nguoigui',50)->nullable();
            $table->string('trangthai',50)->nullable();
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
        Schema::drop('nguonkinhphi_huyen');
    }
}
