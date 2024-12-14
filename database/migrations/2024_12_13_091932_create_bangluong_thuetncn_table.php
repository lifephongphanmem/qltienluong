<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongThuetncnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluong_thuetncn', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mabl',50)->nullable();
            $table->double('stt')->default('1');
            $table->string('macanbo',50)->nullable();
            $table->string('tencanbo')->nullable();
            $table->double('thang01')->default(0);
            $table->double('thang02')->default(0);
            $table->double('thang03')->default(0);
            $table->double('thang04')->default(0);
            $table->double('thang05')->default(0);
            $table->double('thang06')->default(0);
            $table->double('thang07')->default(0);
            $table->double('thang08')->default(0);
            $table->double('thang09')->default(0);
            $table->double('thang10')->default(0);
            $table->double('thang11')->default(0);
            $table->double('thang12')->default(0);
            $table->double('tongthuetncn')->default(0);
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
        Schema::dropIfExists('bangluong_thuetncn');
    }
}
