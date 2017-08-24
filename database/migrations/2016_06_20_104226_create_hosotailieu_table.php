<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosotailieuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosotailieu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->foreign('macanbo')->references('macanbo')->on('hosocanbo')->onUpdate('cascade')->onDelete('cascade');

            $table->date('ngaybangiao')->nullable();
            $table->string('nguoinhan', 50)->nullable();
            $table->string('tentailieu', 100)->nullable();
            $table->string('phanloai', 50)->nullable();
            $table->string('ghichu')->nullable();
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
        Schema::drop('hosotailieu');
    }
}
