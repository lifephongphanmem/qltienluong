<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhomngachluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhomngachluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manhom', 50)->nullable();
            $table->string('tennhom')->nullable();
            $table->string('phanloai')->nullable();//chuyên viêc, cán sự, ...
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
        Schema::drop('nhomngachluong');
    }
}
