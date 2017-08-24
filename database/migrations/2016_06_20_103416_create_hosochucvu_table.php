<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosochucvuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosochucvu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->foreign('macanbo')->references('macanbo')->on('hosocanbo')->onUpdate('cascade')->onDelete('cascade');
            $table->string('macvcq', 50)->nullable();
            $table->foreign('macvcq')->references('macvcq')->on('dmchucvucq')->onUpdate('cascade')->onDelete('set null');
            $table->string('mapb', 50)->nullable();
            $table->foreign('mapb')->references('mapb')->on('dmphongban')->onUpdate('cascade')->onDelete('set null');

            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('noidungqd', 255)->nullable();
            $table->string('soqd', 50)->nullable();
            $table->date('ngayqd')->nullable();
            $table->string('nguoiky')->nullable();
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
        Schema::drop('hosochucvu');
    }
}
