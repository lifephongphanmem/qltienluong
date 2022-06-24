<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChitieubiencheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chitieubienche', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso')->nullable();
            $table->double('nam')->default(0);
            $table->string('linhvuchoatdong',50)->nullable();
            $table->string('mact',50)->nullable();
            $table->string('macongtac',50)->nullable();
            $table->double('soluongduocgiao')->default(0);
            $table->double('soluongbienche')->default(0);
            $table->double('soluongkhongchuyentrach')->default(0);
            $table->double('soluonguyvien')->default(0);
            $table->double('soluongdaibieuhdnd')->default(0);
            $table->double('soluongtuyenthem')->default(0);
            $table->string('ghichu')->nullable();
            //thông tin cán bộ tuyển thêm
            $table->double('pthuong')->default(100);
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
            $table->double('hesopc')->default(0);
            $table->double('vuotkhung')->default(0);
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
            $table->double('pcxaxe')->default(0); //xăng xe
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng (số tiền)
            $table->double('pcctp')->default(0);//phụ cấp công tác phí
            $table->double('pctaicu')->default(0);//phụ cấp tái ứng cử
            $table->double('pclaunam')->default(0);//công tác lâu năm

            $table->double('baohiem')->default(1);
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
            $table->string('madv')->nullable();
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
        Schema::drop('chitieubienche');
    }
}
