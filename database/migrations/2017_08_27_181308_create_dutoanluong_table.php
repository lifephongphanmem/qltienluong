<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDutoanluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dutoanluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv',50)->unique();
            $table->string('masok',50)->nullable();
            $table->string('masoh',50)->nullable();
            $table->string('masot',50)->nullable();
            $table->string('namns')->nullable();
            $table->double('luongnb_dt')->default(0);
            $table->double('luonghs_dt')->default(0);
            $table->double('luongbh_dt')->default(0);
            $table->double('luongcoban')->default(0);
            $table->double('luongnb_nl')->default(0);
            $table->double('luonghs_nl')->default(0);
            $table->double('luongbh_nl')->default(0);
            $table->string('ghichu')->nullable();
            $table->string('madv')->nullable();
            $table->string('madvbc',50)->nullable();
            $table->string('macqcq',50)->nullable();
            $table->string('trangthai',50)->default('CHUAGUI');
            $table->text('lydo')->nullable();

            //Đơn vị tổng hợp sau đó tùy level mà gửi lên huyện hoặc tỉnh
            $table->date('ngayguidv')->nullable();
            $table->string('nguoiguidv',50)->nullable();
            //Thông tin người gửi cấp huyện (huyện kết xuất báo cáo xem dữ liệu)
            $table->date('ngayguih')->nullable();
            $table->string('nguoiguih',50)->nullable();
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
        Schema::drop('dutoanluong');
    }
}
