<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiDinhmucTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * Xem sét nếu lưu thời gian => một mã nguồn có nhiều thời điểm lương
     * mã phụ cấp ko thay đổi (nếu thay đổi => db lớn)
     * chưa lưu thời gian => mỗi mã nguồn có 1 dòng
     */
    public function up()
    {
        Schema::create('nguonkinhphi_dinhmuc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso',50)->unique();
            $table->string('mact')->default('ALL');
            $table->text('noidung')->nullable();
            $table->string('manguonkp', 50)->nullable();
            $table->date('tungay')->nullable();//chưa dùng
            $table->date('denngay')->nullable();//chưa dùng
            $table->double('luongcoban')->default(0);//dùng làm mốc khi thay đổi lương cơ bản => các loại pc tự cập nhập
            $table->double('baohiem')->default(1);
            $table->string('madv',50)->nullable();
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
        Schema::drop('nguonkinhphi_dinhmuc');
    }
}
