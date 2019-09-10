<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv', 50)->nullable();
            $table->string('masok', 50)->nullable();
            $table->string('masoh', 50)->nullable();
            $table->string('masot', 50)->nullable();
            $table->string('sohieu')->nullable();
            $table->string('manguonkp', 50)->nullable();
            $table->text('noidung')->nullable();
            $table->string('namns')->nullable();
            $table->string('linhvuchoatdong')->nullable();
            //nhucau = cộng các dự toán lại
            $table->double('nhucau')->default(0);
            $table->double('luongphucap')->default(0);
            $table->double('daibieuhdnd')->default(0);
            $table->double('nghihuu')->default(0);
            $table->double('canbokct')->default(0);
            $table->double('uyvien')->default(0);
            $table->double('boiduong')->default(0);
            $table->double('thunhapthap')->default(0);
            $table->double('diaban')->default(0);
            $table->double('tinhgiam')->default(0);
            $table->double('nghihuusom')->default(0);
            $table->double('baohiem')->default(0);
            //nguonkp = cộng các nguồn lại
            $table->double('nguonkp')->default(0);
            $table->double('tietkiem')->default(0);
            $table->double('hocphi')->default(0);
            $table->double('vienphi')->default(0);
            $table->double('nguonthu')->default(0);
            $table->string('madv', 50)->nullable();
            $table->string('madvbc', 50)->nullable();
            $table->string('macqcq', 50)->nullable();
            $table->string('maphanloai')->nullable();//lấy trong bảng danh sách đơn vị add vào
            $table->string('trangthai')->nullable();
            //Thông tin người gửi cấp đơn vị
            //Đơn vị tổng hợp sau đó tùy level mà gửi lên huyện hoặc tỉnh
            $table->date('ngayguidv')->nullable();
            $table->string('nguoiguidv')->nullable();

            //Thông tin người gửi cấp huyện (huyện kết xuất báo cáo xem dữ liệu)
            //Sau đó gửi lên tỉnh (update vào masoh)
            $table->date('ngayguih')->nullable();
            $table->string('nguoiguih')->nullable();
            $table->text('lydo')->nullable();
            //thêm mới theo thông tư 46/2019
            $table->double('tietkiem1')->default(0); //trước 1 năm
            $table->double('tietkiem2')->default(0); //trước 2 năm
            $table->double('thuchien1')->default(0); //trước 1 năm
            $table->double('dutoan')->default(0);
            $table->double('dutoan1')->default(0); //trước 1 năm
            $table->double('bosung')->default(0);
            $table->double('caicach')->default(0);
            $table->double('kpthuhut')->default(0);
            $table->double('kpuudai')->default(0);

            $table->double('luongchuyentrach')->default(0);//thừa
            $table->double('luongkhongchuyentrach')->default(0);//thừa

            $table->double('tongnhucau1')->default(0);
            $table->double('tongnhucau2')->default(0);
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
        Schema::drop('nguonkinhphi');
    }
}
