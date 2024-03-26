<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhomphanloaictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhomphanloaict', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('manhom')->nullable();
            $table->string('tennhom')->nullable();
            $table->timestamps();
        });
        Schema::create('nhomphanloaict_phanloaict', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('manhom')->nullable();
            $table->string('maphanloaict')->nullable();
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
        Schema::dropIfExists('nhomphanloaict');
        Schema::dropIfExists('nhomphanloaict_phanloaict');
    }
}
