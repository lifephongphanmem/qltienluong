<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmkhoipbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmkhoipb', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level')->nullable();
            $table->string('makhoipb', 50)->nullable();
            $table->string('tenkhoipb')->nullable();
            $table->text('ghichu')->nullable();
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
        Schema::drop('dmkhoipb');
    }
}
