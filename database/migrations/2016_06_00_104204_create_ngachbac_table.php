<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNgachbacTable extends Migration
{
    /**
     * Dùng chung cho các đơn vị
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngachbac', function (Blueprint $table) {
            $table->increments('id');
            $table->string('msngbac', 50)->nullable();
            $table->string('plnb', 50)->nullable();
            $table->string('tennb', 50)->nullable();
            $table->integer('namnb')->default(0);
            $table->integer('bac')->default(1);
            $table->double('heso')->default(0);
            $table->double('ptvk')->default(0);
            $table->boolean('vk')->default(0);
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
        Schema::drop('ngachbac');
    }
}
