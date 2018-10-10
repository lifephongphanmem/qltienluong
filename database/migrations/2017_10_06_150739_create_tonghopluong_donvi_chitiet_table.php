<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTonghopluongDonviChitietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tonghopluong_donvi_chitiet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mathdv',50)->nullable();
            $table->string('mathk',50)->nullable();
            $table->string('mathh',50)->nullable();
            $table->string('matht',50)->nullable();
            $table->string('manguonkp',50)->nullable();
            $table->string('linhvuchoatdong')->nullable();//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
            $table->string('macongtac')->nullable();
            $table->string('mact')->nullable();
            $table->double('soluong')->default(0);
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

            //thêm vào => chưa dùng vì ko cần tổng hợp
            $table->double('pclade')->default(0); //làm đêm
            $table->double('pcud61')->default(0); //ưu đãi theo tt61
            $table->double('pcxaxe')->default(0); //điện thoại
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng, lương khoán (số tiền)
            $table->double('pcphth')->default(0); //phẫu thuật, thủ thuật

            $table->double('tonghs')->default(0);
            $table->double('giaml')->default(0);
            $table->double('luongtn')->default(0);
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
        Schema::drop('tonghopluong_donvi_chitiet');
    }
}
