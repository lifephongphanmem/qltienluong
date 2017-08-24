<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphucapTable extends Migration
{
    /**
     * Dùng chung cho các đơn vị
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphucap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mapc', 50)->unique();
            $table->string('tenpc', 100)->nullable();
            $table->double('hesopc')->default(0)->nullable();
            $table->boolean('baohiem')->nullable();
            $table->string('ghichu', 255)->nullable();
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
        Schema::drop('dmphucap');
    }
}
