<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongPhucapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //lưu thông tin về lương theo các khoản phụ cấp, chức vụ kiêm nhiệm của cán bộ
        Schema::create('bangluong_phucap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl', 50)->nullable();
            $table->string('maso', 50)->nullable();//mã phụ cấp or mã chức vụ
            $table->string('ten', 100)->nullable();//tên phụ cấp or mã chức vụ
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('phanloai')->nullable();
            $table->string('congthuc')->nullable();
            $table->double('heso_goc')->default(0);//lấy từ bảng danh sách cán bộ
            $table->double('heso')->default(0);
            $table->double('sotien')->default(0);
            $table->double('stbhxh')->default(0);
            $table->double('stbhyt')->default(0);
            $table->double('stkpcd')->default(0);
            $table->double('stbhtn')->default(0);
            $table->double('ttbh')->default(0);
            $table->double('stbhxh_dv')->default(0);
            $table->double('stbhyt_dv')->default(0);
            $table->double('stkpcd_dv')->default(0);
            $table->double('stbhtn_dv')->default(0);
            $table->double('ttbh_dv')->default(0);

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
        Schema::drop('bangluong_phucap');
    }
}
