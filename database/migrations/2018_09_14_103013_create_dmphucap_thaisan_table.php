<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphucapThaisanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphucap_thaisan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv', 50)->nullable();
            $table->string('mapc', 50)->nullable();
            $table->string('tenpc', 100)->nullable();
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
        Schema::drop('dmphucap_thaisan');
    }
}
