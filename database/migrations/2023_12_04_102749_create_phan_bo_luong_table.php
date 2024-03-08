<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhanBoLuongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phanboluong', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('masodv',50)->unique();
            $table->string('masok',50)->nullable();
            $table->string('masoh',50)->nullable();
            $table->string('masot',50)->nullable();
            $table->string('namns')->nullable();
            $table->double('luongnb_pbl')->default(0);
            $table->double('luonghs_pbl')->default(0);
            $table->double('luongbh_pbl')->default(0);
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
            $table->string('phanloai',20)->default('DUUOC')->nullable();
            $table->string('phanloaixa',20)->nullable();
            $table->double('sothonxabiengioi')->default(0);
            $table->double('sothonxakhokhan')->default(0);
            $table->double('sothonxatrongdiem')->default(0);
            $table->double('sothonxakhac')->default(0);
            $table->double('sothonxaloai1')->default(0);
            $table->double('sothonxabiengioi_heso')->default(0);
            $table->double('sothonxakhokhan_heso')->default(0);
            $table->double('sothonxaloai1_heso')->default(0);
            $table->double('sothonxatrongdiem_heso')->default(0);
            $table->double('sothonxakhac_heso')->default(0);
            $table->double('phanloaixa_heso')->default(0);
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
        Schema::dropIfExists('phanboluong');
    }
}
