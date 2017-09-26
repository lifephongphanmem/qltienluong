<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosoluanchuyenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosoluanchuyen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->string('phanloai', 100)->nullable();
            $table->date('ngaylc')->nullable();
            $table->string('mapb', 100)->nullable();
            $table->string('macvcq', 50)->nullable();
            $table->string('soqd', 50)->nullable();
            $table->date('ngayqd')->nullable();
            $table->string('nguoiky', 50)->nullable();
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
        Schema::drop('hosoluanchuyen')->nullable();
    }
}
