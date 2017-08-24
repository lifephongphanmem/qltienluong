<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmchucvucdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmchucvud', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macvd', 50)->unique();
            $table->string('tencv', 100)->nullable();
            $table->string('ghichu')->nullable();
            $table->integer('sapxep')->default(99);
            $table->string('madv', 50)->nullable();
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
        Schema::drop('dmchucvud');
    }
}
