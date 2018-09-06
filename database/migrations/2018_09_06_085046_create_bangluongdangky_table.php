<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongdangkyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluongdangky', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl',50)->unique();
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngaylap')->nullable();
            $table->string('nguoilap')->nullable();
            $table->string('ghichu')->nullable();
            $table->string('manguonkp',50)->nullable();
            $table->string('phanloai',50)->nullable();//bảng lương cán bộ /  bảng truy lĩnh lương
            $table->string('linhvuchoatdong')->nullable();//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
            $table->double('luongcoban')->default(0);
            $table->date('ngaygui')->nullable();
            $table->string('nguoigui')->nullable();
            $table->string('trangthai')->nullable();
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
        Schema::drop('bangluongdangky');
    }
}
