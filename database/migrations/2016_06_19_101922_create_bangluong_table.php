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
            $table->string('mabl_trichnop',50)->nullable();
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->string('noidung')->nullable();
            $table->date('ngaylap')->nullable();
            $table->string('nguoilap')->nullable();
            $table->string('ghichu')->nullable();
            $table->string('manguonkp',50)->nullable();
            $table->string('phanloai',50)->nullable()->default('BANGLUONG');//bảng lương cán bộ /  bảng truy lĩnh lương
            $table->string('linhvuchoatdong')->nullable();//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
            $table->double('phantramhuong')->default(100);//Tùy theo nguồn kinh phí để tùy chọn % hưởng lương
            $table->string('phucaploaitru')->nullable();//lưu(cập nhật) khi tạo bảng lương
            $table->string('phucapluusotien')->nullable();//lưu(cập nhật) khi tạo bảng lương
            $table->double('luongcoban')->default(0);
            $table->date('ngaygui')->nullable();
            $table->string('nguoigui')->nullable();
            $table->string('trangthai')->nullable();
            $table->string('maquy',30)->nullable();
            $table->string('tenquy')->nullable();
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
