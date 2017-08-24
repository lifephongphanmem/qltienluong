<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsthuyenchuyenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsthuyenchuyen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mads', 50)->unique();
            $table->string('soqd', 50)->nullable();
            $table->string('ngayqd')->nullable();
            $table->string('nguoiky', 50)->nullable();
            $table->string('coquanqd', 150)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('phanloai')->nullable();//thuyên chuyển / điều động (có ngày tháng)
            $table->string('noidung')->nullable();
            $table->text('ghichu')->nullable();
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
        Schema::drop('dsthuyenchuyen');
    }
}
