<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosodieudongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosodieudong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso', 50)->unique();
            $table->string('soqd', 50)->nullable(); //chưa dùng
            $table->string('ngayqd')->nullable(); //chưa dùng
            $table->string('nguoiky', 50)->nullable(); //chưa dùng
            $table->string('coquanqd', 150)->nullable(); //chưa dùng
            $table->date('ngaylc')->nullable();
            $table->date('ngaylctu')->nullable();
            $table->date('ngaylcden')->nullable();
            $table->string('maphanloai')->nullable();//thuyên chuyển / điều động (có ngày tháng)
            $table->string('trangthai')->nullable();
            $table->string('madv_dd',50)->nullable();
            $table->string('macvcq_dd', 50)->nullable();
            $table->string('noidung')->nullable();

            $table->string('stt', 10)->nullable();
            $table->string('mapb', 50)->nullable();
            $table->string('manguonkp',50)->nullable();//nguồn kinh phí lấy lương
            $table->string('macvcq', 50)->nullable();
            $table->string('macanbo', 50)->unique();
            $table->string('anh', 150)->nullable();
            $table->string('macongchuc', 50)->nullable();
            $table->string('sunghiep', 100)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('tenkhac', 50)->nullable();
            $table->date('ngaysinh')->nullable();
            $table->string('gioitinh', 10)->nullable();
            $table->string('dantoc', 20)->nullable();
            $table->string('tongiao', 20)->nullable();
            $table->string('lvtd')->nullable();//nơi công tác
            $table->string('lvhd')->nullable();//lĩnh vực hoạt động
            $table->string('socmnd', 20)->nullable();
            $table->date('ngaycap')->nullable();
            $table->string('noicap', 100)->nullable();
            $table->string('sotk', 50)->nullable();
            $table->string('tennganhang', 150)->nullable();
            $table->string('madv',50)->nullable();
            //Thông tin lương hiện tại
            $table->string('msngbac', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->integer('bac')->default(1);
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
            $table->double('hesopc')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pthuong')->default(100);
            $table->double('pcct')->default(0);//dùng để thay thế phụ cấp ghép lớp
            $table->double('pckct')->default(0);//dùng để thay thế phụ cấp bằng cấp cho cán bộ không chuyên trách
            $table->double('pck')->default(0);
            $table->double('pccv')->default(0);
            $table->double('pckv')->default(0);
            $table->double('pcth')->default(0);
            $table->double('pcdd')->default(0);
            $table->double('pcdh')->default(0);
            $table->double('pcld')->default(0);
            $table->double('pcdbqh')->default(0);
            $table->double('pcudn')->default(0);
            $table->double('pctn')->default(0);
            $table->double('pctnn')->default(0);
            $table->double('pcdbn')->default(0);
            $table->double('pcvk')->default(0);//dùng để thay thế phụ cấp Đảng uy viên
            $table->double('pckn')->default(0);
            $table->double('pcdang')->default(0);
            $table->double('pccovu')->default(0);
            $table->double('pclt')->default(0); //lưu thay phụ cấp phân loại xã
            $table->double('pcd')->default(0);
            $table->double('pctr')->default(0);
            $table->double('pctdt')->default(0);
            $table->double('pctnvk')->default(0);
            $table->double('pcbdhdcu')->default(0);
            $table->double('pcthni')->default(0);

            $table->double('pclade')->default(0); //làm đêm
            $table->double('pcud61')->default(0); //ưu đãi theo tt61
            $table->double('pcxaxe')->default(0); //điện thoại
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng, lương khoán (số tiền)
            $table->double('pcphth')->default(0); //phẫu thuật, thủ thuật
            $table->double('pctaicu')->default(0);//phụ cấp tái ứng cử
            $table->double('pcctp')->default(0);
            $table->double('pclaunam')->default(0);//công tác lâu năm

            $table->date('tnntungay')->nullable();
            $table->date('ttnndenngay')->nullable();
            $table->string('mact')->nullable();
            $table->string('theodoi',5)->default(1)->nullable();
            $table->double('baohiem')->default(1);
            $table->string('sodinhdanhcanhan')->nullable();

            $table->double('bhxh')->default(0);
            $table->double('bhyt')->default(0);
            $table->double('kpcd')->default(0);
            $table->double('bhtn')->default(0);
            $table->double('bhtnld')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('bhtnld_dv')->default(0);
            $table->double('nguoiphuthuoc')->default(0);

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
        Schema::drop('hosodieudong');
    }
}
