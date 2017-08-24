<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdantocTable extends Migration
{
    /**
     * Dùng chung cho tất cả các đơn vị
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdantoc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dantoc', 30)->unique();
            $table->boolean('thieuso');
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
        Schema::drop('dmdantoc');
    }
}
