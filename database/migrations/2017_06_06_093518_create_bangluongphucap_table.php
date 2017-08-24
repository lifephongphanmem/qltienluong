<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongphucapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluongphucap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl', 50)->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->string('mapc', 50)->nullable();
            $table->double('hesopc')->default(0)->nullable();
            $table->double('thanhtien')->default(0)->nullable();
            $table->boolean('baohiem')->nullable();
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
        Schema::drop('bangluongphucap');
    }
}
