<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdonviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdonvi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv', 50)->unique();
            $table->string('tendv')->unique();
            $table->string('diachi')->nullable();
            $table->string('sodt')->nullable();
            $table->string('lanhdao')->nullable();
            $table->integer('songuoi')->default(0);
            $table->string('macqcq')->nullable();
            $table->string('diadanh')->nullable();
            $table->string('cdlanhdao')->nullable();
            $table->string('nguoilapbieu')->nullable();
            $table->string('makhoipb')->nullable();
            $table->string('level')->nullable();

            $table->string('capdonvi')->nullable();//đơn vị cấp X, H, T
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
        Schema::drop('dmdonvi');
    }
}
