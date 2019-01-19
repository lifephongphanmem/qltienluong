<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmtieumucDefaultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmtieumuc_default', function (Blueprint $table) {
            $table->increments('id');
            $table->string('muc', 10)->nullable();
            $table->string('tieumuc', 50)->nullable();
            $table->string('noidung', 50)->nullable(); //lay theo muc luc ngan sach
            //các điều kiện để lấy dữ liệu (có thể bỏ trống)
            $table->string('phanloai', 50)->default('CHILUONG');//chi lương / bảo hiểm
            $table->string('sunghiep', 50)->nullable();
            $table->string('macongtac', 50)->nullable();
            $table->string('mapc', 50)->nullable();
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
        Schema::drop('dmtieumuc_default');
    }
}
