<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphanloaicongtacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphanloaicongtac', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maphanloai')->nullable();
            $table->string('macongtac')->nullable();
            $table->string('tencongtac')->nullable();
            $table->double('bhxh')->default(0);
            $table->double('bhyt')->default(0);
            $table->double('bhtn')->default(0);
            $table->double('kpcd')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->double('sapxep')->default(1);
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
        Schema::drop('dmphanloaicongtac');
    }
}
