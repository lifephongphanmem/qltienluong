<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTonghopluongDonviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tonghopluong_donvi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mathdv',50)->unique();
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngaylap')->nullable();
            $table->string('nguoilap')->nullable();
            $table->string('ghichu')->nullable();
            $table->date('ngaygui')->nullable();
            $table->string('nguoigui')->nullable();
            $table->string('trangthai')->nullable();
            $table->string('madv',50);
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
        //
    }
}
