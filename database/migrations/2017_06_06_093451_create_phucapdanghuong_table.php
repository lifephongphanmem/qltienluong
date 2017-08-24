<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhucapdanghuongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phucapdanghuong', function (Blueprint $table) {
            $table->increments('id');
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->string('mapc', 50)->nullable();
            $table->double('hesopc')->default(0)->nullable();
            $table->double('pthuong')->default(0)->nullable();//chưa dùng
            $table->string('hinhthuc')->nullable();//chưa dùng
            $table->boolean('baohiem')->nullable();
            $table->text('ghichu')->nullable();
            $table->string('madv', 50)->nullable();
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
        Schema::drop('phucapdanghuong');
    }
}
