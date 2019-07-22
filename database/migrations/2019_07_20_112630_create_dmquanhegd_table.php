<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmquanhegdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmquanhegd', function (Blueprint $table) {
            $table->increments('id');
            $table->string('quanhe', 30)->unique();
            $table->integer('nhom')->default(99);
            $table->integer('sapxep')->default(99);
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
        Schema::drop('dmquanhegd');
    }
}
