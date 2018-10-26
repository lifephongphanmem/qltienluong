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
            $table->integer('namnb')->default(0);
            $table->integer('baclonnhat')->default(1);
            $table->integer('bacvuotkhung')->default(0);
            $table->double('heso')->default(0);
            $table->double('hesolonnhat')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('hesochenhlech')->default(0);
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
