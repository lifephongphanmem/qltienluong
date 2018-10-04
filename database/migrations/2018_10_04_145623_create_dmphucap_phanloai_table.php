<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphucapPhanloaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphucap_phanloai', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maphanloai', 50)->nullable();
            $table->string('mapc', 50)->nullable();
            $table->string('phanloai')->nullable();
            $table->string('congthuc')->nullable();
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
        Schema::drop('dmphucap_phanloai');
    }
}
