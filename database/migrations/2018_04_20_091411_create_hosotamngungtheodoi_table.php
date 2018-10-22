<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosotamngungtheodoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosotamngungtheodoi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso', 50)->unique();
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('soqd', 50)->nullable();//chưa dùng
            $table->string('ngayqd')->nullable();//chưa dùng
            $table->string('nguoiky', 50)->nullable();//chưa dùng
            $table->string('coquanqd', 150)->nullable();//chưa dùng
            $table->string('maphanloai', 50)->nullable();
            $table->text('noidung')->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->double('songaynghi')->default(0);
            $table->double('songaycong')->default(0);
            $table->string('madv')->nullable();
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
        Schema::drop('hosotamngungtheodoi');
    }
}
