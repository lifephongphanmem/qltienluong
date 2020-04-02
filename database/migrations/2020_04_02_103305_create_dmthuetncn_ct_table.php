<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmthuetncnCtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmthuetncn_ct', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sohieu')->nullable();
            $table->double('muctu')->default(0);
            $table->double('mucden')->default(0);
            $table->double('phantram')->default(0);
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
        Schema::dropIfExists('dmthuetncn_ct');
    }
}
