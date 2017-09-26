<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosophucapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosophucap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mapc', 50)->nullable();
            $table->string('macanbo', 50);
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->double('hesopc')->default(0);
            $table->string('ghichupc')->nullable();
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
        Schema::drop('hosophucap');
    }
}
