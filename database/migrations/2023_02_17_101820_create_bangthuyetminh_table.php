<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangthuyetminhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangthuyetminh', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mathuyetminh')->unique();
            $table->string('phanloai')->nullable(); 
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->string('noidung')->nullable();
            $table->string('ghichu')->nullable();
            $table->string('madv',50);
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
        Schema::dropIfExists('bangthuyetminh');
    }
}
