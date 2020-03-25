<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosotrucTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosotruc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso', 50)->unique();
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('tenkhac', 50)->nullable();
            $table->date('ngaysinh')->nullable();
            $table->string('gioitinh', 10)->nullable();
            $table->string('dantoc', 20)->nullable();
            $table->string('tongiao', 20)->nullable();
            $table->double('heso')->default(0);
            $table->string('madv',50)->nullable();

            $table->double('vuotkhung')->default(0);
            $table->double('pccv')->default(0);
            $table->double('pcdh')->default(0);
            $table->double('pctn')->default(0);
            $table->double('pcudn')->default(0);
            $table->double('pcud61')->default(0);
            $table->double('pcld')->default(0);
            $table->double('pclade')->default(0);
            $table->double('songaycong')->default(100);
            $table->double('songaytruc')->default(100);
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
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
        Schema::drop('hosotruc');
    }
}
