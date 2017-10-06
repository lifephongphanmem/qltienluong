<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNgachluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngachluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manhom', 50)->nullable();
            $table->string('msngbac', 50)->nullable();
            $table->string('tenngachluong')->nullable();
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
        Schema::drop('ngachluong');
    }
}
