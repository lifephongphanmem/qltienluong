<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangthuyetminhChitietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangthuyetminh_chitiet', function (Blueprint $table) {
            $table->bigIncrements('id');           
            $table->string('mathuyetminh')->nullable();
            $table->integer('stt')->default('1');
            
            $table->string('tanggiam')->nullable(); //tăng hoặc giảm
            $table->string('noidung')->nullable();
            $table->double('sotien')->default('0');
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
        Schema::dropIfExists('bangthuyetminh_chitiet');
    }
}
