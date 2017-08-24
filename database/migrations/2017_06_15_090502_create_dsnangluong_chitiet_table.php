<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsnangluongChitietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsnangluong_chitiet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mads', 50)->unique();
            $table->string('macanbo', 50)->nullable();
            $table->string('phanloai')->nullable();

            $table->string('msngbac_cu', 50)->nullable();
            $table->date('ngaytu_cu')->nullable();
            $table->date('ngayden_cu')->nullable();
            $table->integer('bac_cu')->default(1);
            $table->double('heso_cu')->default(0);
            $table->double('vuotkhung_cu')->default(0);
            $table->double('pthuong_cu')->default(100);

            $table->string('msngbac', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->integer('bac')->default(1);
            $table->double('heso')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pthuong')->default(100);
            $table->double('heso_truythu')->default(0);

            $table->text('ghichu')->nullable();
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
        Schema::drop('dsnangluong_chitiet');
    }
}
