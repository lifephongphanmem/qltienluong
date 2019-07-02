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
            $table->string('macongtac', 50)->nullable();
            $table->double('tonghop')->default(0);
            $table->string('mact', 50)->nullable();
            $table->string('tenct', 50)->nullable();
            $table->double('bhxh')->default(0);
            $table->double('bhyt')->default(0);
            $table->double('bhtn')->default(0);
            $table->double('kpcd')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
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
        Schema::drop('dmphanloaict');
    }
}
