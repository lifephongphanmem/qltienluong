<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosotruylinhNguonTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosotruylinh_nguon_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso', 50)->nullable();
            $table->string('manguonkp',50)->nullable();
            $table->double('luongcoban')->default(0);
            $table->string('macanbo')->nullable();
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
        Schema::drop('hosotruylinh_nguon_temp');
    }
}
