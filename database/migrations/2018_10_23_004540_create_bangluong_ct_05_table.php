<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongCt05Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluong_ct_05', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl', 25)->nullable();
            $table->string('macvcq', 25)->nullable();
            $table->string('mapb', 25)->nullable();
            $table->string('msngbac', 25)->nullable();
            $table->string('mact', 25)->nullable();
            $table->string('stt', 10)->nullable();
            $table->string('phanloai',25)->default('CVCHINH');//phân loại kiêm nhiệm / chính thức
            $table->string('congtac',25)->default('CONGTAC');//phân loại kiêm nhiệm / chính thức
            $table->string('macanbo', 25)->nullable();
            $table->string('tencanbo', 30)->nullable();
            $table->string('macongchuc', 25)->nullable();
            $table->float('heso')->default(0);
            $table->float('hesobl')->default(0);
            $table->float('hesopc')->default(0);
            $table->float('hesott')->default(0);//hệ số truy lĩnh
            $table->float('thangtl')->default(0);//số tháng được truy lĩnh lương
            $table->float('ngaytl')->default(0);//số tháng được truy lĩnh lương
            $table->float('luongcoban')->default(0);//mức lương co bản được truy lĩnh lương
            $table->float('songaytruc')->default(0);
            $table->float('vuotkhung')->default(0);

            $table->float('pcct')->default(0);//dùng để thay thế phụ cấp ghép lớp
            $table->float('pckct')->default(0);
            $table->float('pck')->default(0);
            $table->float('pccv')->default(0);
            $table->float('pckv')->default(0);
            $table->float('pcth')->default(0);
            $table->float('pcdd')->default(0);
            $table->float('pcdh')->default(0);
            $table->float('pcld')->default(0);
            $table->float('pcdbqh')->default(0);
            $table->float('pcudn')->default(0);
            $table->float('pctn')->default(0);
            $table->float('pctnn')->default(0);
            $table->float('pcdbn')->default(0);
            $table->float('pcvk')->default(0);//dùng để thay thế phụ cấp Đảng uy viên
            $table->float('pckn')->default(0);
            $table->float('pcdang')->default(0);
            $table->float('pccovu')->default(0);
            $table->float('pclt')->default(0);//lưu thay phụ cấp phân loại xã
            $table->float('pcd')->default(0);
            $table->float('pctr')->default(0);
            $table->float('pctdt')->default(0);
            $table->float('pctnvk')->default(0);
            $table->float('pcbdhdcu')->default(0);
            $table->float('pcthni')->default(0);

            $table->float('pclade')->default(0); //làm đêm
            $table->float('pcud61')->default(0); //ưu đãi theo tt61
            $table->float('pcxaxe')->default(0); //điện thoại
            $table->float('pcdith')->default(0); //điện thoại
            $table->float('luonghd')->default(0); //lương hợp đồng, lương khoán (số tiền)
            $table->float('pcphth')->default(0); //phẫu thuật, thủ thuật

            $table->float('tonghs')->default(0);
            $table->float('ttl')->default(0);
            $table->float('giaml')->default(0);
            $table->float('bhct')->default(0);
            $table->float('tluong')->default(0);
            $table->float('stbhxh')->default(0);
            $table->float('stbhyt')->default(0);
            $table->float('stkpcd')->default(0);
            $table->float('stbhtn')->default(0);
            $table->float('ttbh')->default(0);
            $table->float('gttncn')->default(0);
            $table->float('luongtn')->default(0);
            $table->float('stbhxh_dv')->default(0);
            $table->float('stbhyt_dv')->default(0);
            $table->float('stkpcd_dv')->default(0);
            $table->float('stbhtn_dv')->default(0);
            $table->float('ttbh_dv')->default(0);
            //lưu theo số tiền
            $table->float('st_heso')->default(0);
            $table->float('st_hesobl')->default(0);
            $table->float('st_hesopc')->default(0);
            $table->float('st_vuotkhung')->default(0);
            $table->float('st_pcct')->default(0);
            $table->float('st_pckct')->default(0);
            $table->float('st_pck')->default(0);
            $table->float('st_pccv')->default(0);
            $table->float('st_pckv')->default(0);
            $table->float('st_pcth')->default(0);
            $table->float('st_pcdd')->default(0);
            $table->float('st_pcdh')->default(0);
            $table->float('st_pcld')->default(0);
            $table->float('st_pcdbqh')->default(0);
            $table->float('st_pcudn')->default(0);
            $table->float('st_pctn')->default(0);
            $table->float('st_pctnn')->default(0);
            $table->float('st_pcdbn')->default(0);
            $table->float('st_pcvk')->default(0);
            $table->float('st_pckn')->default(0);
            $table->float('st_pcdang')->default(0);
            $table->float('st_pccovu')->default(0);
            $table->float('st_pclt')->default(0);
            $table->float('st_pcd')->default(0);
            $table->float('st_pctr')->default(0);
            $table->float('st_pctdt')->default(0);
            $table->float('st_pctnvk')->default(0);
            $table->float('st_pcbdhdcu')->default(0);
            $table->float('st_pcthni')->default(0);
            $table->float('st_pclade')->default(0);
            $table->float('st_pcud61')->default(0);
            $table->float('st_pcxaxe')->default(0);
            $table->float('st_pcdith')->default(0);
            $table->float('st_luonghd')->default(0);
            $table->float('st_pcphth')->default(0);
            //lưu tỷ lệ bảo hiểm (đã quy về hệ số)
            $table->float('bhxh')->default(0);
            $table->float('bhyt')->default(0);
            $table->float('bhtn')->default(0);
            $table->float('kpcd')->default(0);
            $table->float('bhxh_dv')->default(0);
            $table->float('bhyt_dv')->default(0);
            $table->float('bhtn_dv')->default(0);
            $table->float('kpcd_dv')->default(0);
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
        Schema::drop('bangluong_ct_05');
    }
}
