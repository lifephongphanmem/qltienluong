<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongctTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluong_ct', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl', 50)->nullable();
            $table->string('macvcq', 50)->nullable();
            $table->string('mapb', 50)->nullable();
            $table->string('msngbac', 50)->nullable();
            $table->string('mact', 50)->nullable();
            $table->string('stt', 10)->nullable();
            $table->string('phanloai',50)->default('CVCHINH');//phân loại kiêm nhiệm / chính thức
            $table->string('congtac',50)->default('CONGTAC');//phân loại kiêm nhiệm / chính thức
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('macongchuc', 50)->nullable();
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
            $table->double('hesopc')->default(0);
            $table->double('hesott')->default(0);//hệ số truy lĩnh
            $table->double('thangtl')->default(0);//số tháng được truy lĩnh lương
            $table->double('ngaytl')->default(0);//số tháng được truy lĩnh lương
            $table->double('luongcoban')->default(0);//mức lương co bản được truy lĩnh lương
            $table->double('songaytruc')->default(0);
            $table->double('vuotkhung')->default(0);

            $table->double('pcct')->default(0);//dùng để thay thế phụ cấp ghép lớp
            $table->double('pckct')->default(0);
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
            $table->double('pclt')->default(0);//lưu thay phụ cấp phân loại xã
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

            $table->double('tonghs')->default(0);
            $table->double('ttl')->default(0);
            $table->double('giaml')->default(0);
            $table->double('tienthuong')->default(0);
            $table->double('trichnop')->default(0);
            $table->double('thuetn')->default(0);
            $table->double('bhct')->default(0);
            $table->double('tluong')->default(0);
            $table->double('stbhxh')->default(0);
            $table->double('stbhyt')->default(0);
            $table->double('stkpcd')->default(0);
            $table->double('stbhtn')->default(0);
            $table->double('ttbh')->default(0);
            $table->double('gttncn')->default(0);
            $table->double('luongtn')->default(0);
            $table->double('stbhxh_dv')->default(0);
            $table->double('stbhyt_dv')->default(0);
            $table->double('stkpcd_dv')->default(0);
            $table->double('stbhtn_dv')->default(0);
            $table->double('ttbh_dv')->default(0);
            //lưu theo số tiền
            $table->double('st_heso')->default(0);
            $table->double('st_hesobl')->default(0);
            $table->double('st_hesopc')->default(0);            
            $table->double('st_vuotkhung')->default(0);
            $table->double('st_pcct')->default(0);
            $table->double('st_pckct')->default(0);
            $table->double('st_pck')->default(0);
            $table->double('st_pccv')->default(0);
            $table->double('st_pckv')->default(0);
            $table->double('st_pcth')->default(0);
            $table->double('st_pcdd')->default(0);
            $table->double('st_pcdh')->default(0);
            $table->double('st_pcld')->default(0);
            $table->double('st_pcdbqh')->default(0);
            $table->double('st_pcudn')->default(0);
            $table->double('st_pctn')->default(0);
            $table->double('st_pctnn')->default(0);
            $table->double('st_pcdbn')->default(0);
            $table->double('st_pcvk')->default(0);
            $table->double('st_pckn')->default(0);
            $table->double('st_pcdang')->default(0);
            $table->double('st_pccovu')->default(0);
            $table->double('st_pclt')->default(0);
            $table->double('st_pcd')->default(0);
            $table->double('st_pctr')->default(0);
            $table->double('st_pctdt')->default(0);
            $table->double('st_pctnvk')->default(0);
            $table->double('st_pcbdhdcu')->default(0);
            $table->double('st_pcthni')->default(0);
            $table->double('st_pclade')->default(0); 
            $table->double('st_pcud61')->default(0);
            $table->double('st_pcxaxe')->default(0);
            $table->double('st_pcdith')->default(0);
            $table->double('st_luonghd')->default(0);
            $table->double('st_pcphth')->default(0);
            //lưu tỷ lệ bảo hiểm (đã quy về hệ số)
            $table->double('bhxh')->default(0);
            $table->double('bhyt')->default(0);
            $table->double('bhtn')->default(0);
            $table->double('kpcd')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->timestamps();
        });
    }

    public function integer($column, $autoIncrement = false, $unsigned = false)
    {
        return $this->addColumn('integer', $column, compact('autoIncrement', 'unsigned'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bangluong_ct');
    }
}
