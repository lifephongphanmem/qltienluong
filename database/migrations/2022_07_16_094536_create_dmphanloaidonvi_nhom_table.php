<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphanloaidonviNhomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphanloaidonvi_nhom', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maphanloai_nhom')->nullable();
            $table->string('tenphanloai_nhom')->nullable();
            $table->integer('capdo_nhom')->default(1);
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
        Schema::dropIfExists('dmphanloaidonvi_nhom');
    }
}
