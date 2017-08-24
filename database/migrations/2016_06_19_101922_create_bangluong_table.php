<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl',50)->unique();
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngaylap')->nullable();
            $table->string('nguoilap')->nullable();
            $table->string('ghichu')->nullable();
            $table->double('luongcb')->default(0)->nullable(); //lưu lại ko hiển thị ra màn hình
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
        Schema::drop('bangluong');
    }
}
