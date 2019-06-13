<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmnguonkpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmnguonkinhphi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manguonkp')->nullable();
            $table->string('tennguonkp')->nullable();
            $table->string('phanloai')->nullable();
            $table->double('macdinh')->default(0);
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
        Schema::drop('dmnguonkinhphi');
    }
}
