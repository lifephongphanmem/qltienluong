<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdonviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdonvi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv', 50)->unique();
            $table->string('maqhns', 50)->nullable();
            $table->string('tendv',100)->nullable();
            $table->string('diachi',100)->nullable();
            $table->string('sodt',50)->nullable();
            $table->string('cdlanhdao',50)->nullable();
            $table->string('lanhdao',50)->nullable();
            $table->string('cdketoan',50)->nullable();
            $table->string('ketoan',50)->nullable();
            $table->double('songuoi')->default(0);
            $table->string('macqcq',50)->nullable();
            $table->string('diadanh',50)->nullable();
            $table->string('nguoilapbieu',50)->nullable();
            $table->string('makhoipb',50)->nullable();//phân loại đơn vị tổng hợp; đơn vị sử dụng
            $table->string('madvbc',50)->nullable();
            $table->string('capdonvi',50)->nullable();//cấp dư toán 1,2,3,4
            $table->string('caphanhchinh',50)->default('XA');//cấp dư toán 1,2,3,4
            $table->string('maphanloai',50)->nullable();//xác định đơn vị thuộc khối hcsn / xp
            $table->string('phanloaixa',50)->nullable();//đơn vị cấp X, H, T
            $table->string('phanloainguon')->nullable();
            $table->string('linhvuchoatdong')->nullable();//lĩnh vực hoạt động
            $table->string('phucaploaitru')->nullable();//lưu(cập nhật) khi tạo bảng lương
            $table->string('phanloaitaikhoan',50)->default('SD');//đơn vị nhập liệu; đơn vị tổng hợp
            $table->string('phamvitonghop',50)->default('KHOI');//Khối, sở ban ngành; Cả huyện, tất cả các sở ban ngành(chỉ có 1 tài khoản 1 huyện)
            $table->double('songaycong')->default(24);
            $table->double('ptdaingay')->default(100);
            $table->double('lamtron')->default(3);
            $table->date('ngaydung')->nullable();
            $table->double('chuyendoi')->default(0);
            $table->string('trangthai')->nullable();
            $table->string('dinhmucnguon',15)->default('HANHCHINH');
            $table->string('sotk')->nullable();
            $table->string('tennganhang')->nullable();
            $table->string('phucapluusotien')->nullable();//lưu(cập nhật) khi tạo bảng lương
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
        Schema::drop('dmdonvi');
    }
}
