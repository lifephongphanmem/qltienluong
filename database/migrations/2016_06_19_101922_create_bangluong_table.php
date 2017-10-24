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
            $table->string('manguonkp',50)->nullable();
            $table->string('linhvuchoatdong')->nullable();//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
            $table->double('phantramhuong')->default(100);//Tùy theo nguồn kinh phí để tùy chọn % hưởng lương

            $table->double('luongcoban')->default(0);
            $table->double('luongnb')->default(0);//tổng lương ngạch bậc
            $table->double('pckv')->default(0);//khu vực
            $table->double('pccv')->default(0);//chưc vự
            $table->double('pctn')->default(0);//thâm niên
            $table->double('pctnvk')->default(0);//thâm niên vượt khung
            $table->double('pcudn')->default(0);//ưu đãi ngành
            $table->double('pcth')->default(0);//thu hút
            $table->double('pctnn')->default(0);//thâm niên ngành
            $table->double('pccovu')->default(0);//công vụ
            $table->double('pcdang')->default(0);//đảng
            $table->double('pckn')->default(0);//kiêm nhiệm
            $table->double('pck')->default(0);//khác

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
        Schema::drop('bangluong');
    }
}
