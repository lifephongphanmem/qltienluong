<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphongbanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphongban', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mapb', 50)->unique();
            $table->string('tenpb', 150)->nullable();
            $table->string('diengiai')->nullable();
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
        Schema::drop('dmphongban');
    }
}
