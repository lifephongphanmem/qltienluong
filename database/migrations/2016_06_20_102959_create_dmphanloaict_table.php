<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphanloaictTable extends Migration
{
    /**
     * Dùng chung cho tất cả các đơn vị
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphanloaict', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phanloaict', 50)->nullable();
            $table->string('kieuct', 50)->nullable();
            $table->string('tenct', 50)->nullable();
            $table->integer('nhomct')->default(99);
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
        Schema::drop('dmphanloaict');
    }
}
