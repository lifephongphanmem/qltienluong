<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsnangluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsnangluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manl', 50)->unique();
            $table->string('maphanloai', 50)->nullable()->default('NANGLUONG');//nâng lương, tnn
            $table->string('loaids', 50)->nullable();
            $table->string('soqd', 50)->nullable();
            $table->string('ngayqd')->nullable();
            $table->string('nguoiky', 50)->nullable();
            $table->string('coquanqd', 150)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngayxet')->nullable();
            $table->string('kemtheo')->nullable();
            $table->string('trangthai',50)->nullable();
            $table->string('madv',50)->nullable();
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
        Schema::drop('dsnangluong');
    }
}
