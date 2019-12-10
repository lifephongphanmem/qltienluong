<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiNangluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_nangluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv',50)->nullable();
            $table->string('masok',50)->nullable();
            $table->string('masoh',50)->nullable();
            $table->string('masot',50)->nullable();
            $table->string('thang',10)->nullable();
            $table->string('nam',10)->nullable();
            $table->string('manguonkp',50)->nullable();
            $table->string('linhvuchoatdong')->nullable();//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
            $table->string('congtac',25)->default('CONGTAC');//công tác/ nghỉ hưu
            $table->string('macongtac')->nullable();
            $table->string('mact')->nullable();
            $table->string('maphanloai')->nullable();
            $table->string('macvcq', 50)->nullable();
            $table->string('mapb', 50)->nullable();
            $table->string('msngbac', 50)->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->double('stt')->default(1);
            $table->double('luongcoban')->default(0);
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
            $table->double('hesott')->default(0);//hệ số truy thu
            $table->double('hesopc')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pcct')->default(0);
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
            $table->double('pcvk')->default(0);
            $table->double('pckn')->default(0);
            $table->double('pcdang')->default(0);
            $table->double('pccovu')->default(0);
            $table->double('pclt')->default(0);
            $table->double('pcd')->default(0);
            $table->double('pctr')->default(0);
            $table->double('pctdt')->default(0);
            $table->double('pctnvk')->default(0);
            $table->double('pcbdhdcu')->default(0);
            $table->double('pcthni')->default(0);

            //thêm vào chưa dùng => các loại phụ cấp ko tổng hợp
            $table->double('pclade')->default(0); //làm đêm
            $table->double('pcud61')->default(0); //ưu đãi theo tt61
            $table->double('pcxaxe')->default(0); //điện thoại
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng, lương khoán (số tiền)
            $table->double('pcphth')->default(0); //phẫu thuật, thủ thuật
            $table->double('pclaunam')->default(0);//công tác lâu năm
            $table->double('st_pclaunam')->default(0);//công tác lâu năm

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
            $table->double('pcctp')->default(0);
            $table->double('st_pcctp')->default(0);
            $table->double('pctaicu')->default(0);//phụ cấp tái ứng cử
            $table->double('st_pctaicu')->default(0);

            $table->double('tonghs')->default(0);
            $table->double('ttl')->default(0);
            $table->double('giaml')->default(0);
            $table->double('luongtn')->default(0);
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
        Schema::drop('nguonkinhphi_nangluong');
    }
}
