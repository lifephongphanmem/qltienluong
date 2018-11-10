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
            $table->integer('namnb')->default(0);
            $table->integer('baclonnhat')->default(1);
            $table->integer('bacvuotkhung')->default(0);
            $table->double('heso')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('hesochenhlech')->default(0);
            $table->double('hesolonnhat')->default(0);
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
