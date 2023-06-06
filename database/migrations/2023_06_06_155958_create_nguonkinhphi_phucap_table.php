<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiPhucapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_phucap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('masodv', 50)->nullable();
            $table->string('masok', 50)->nullable();
            $table->string('masoh', 50)->nullable();
            $table->string('masot', 50)->nullable();
            $table->string('sohieu')->nullable();
            $table->string('manguonkp', 50)->nullable(); //chưa dùng
            $table->string('macongtac', 50)->nullable();
            $table->string('mact', 50)->nullable();
            $table->integer('canbo_congtac')->default(0);
            $table->integer('canbo_dutoan')->default(0);
            //lưu theo từng phân loại sau 
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
            $table->double('hesopc')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pcct')->default(0); //dùng để thay thế phụ cấp ghép lớp
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
            $table->double('pcvk')->default(0); //dùng để thay thế phụ cấp Đảng uy viên
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
            $table->double('pcctp')->default(0); //phụ cấp công tác phí
            $table->double('pctaicu')->default(0); //phụ cấp tái ứng cử            
            $table->double('pclaunam')->default(0); //công tác lâu năm
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
            $table->double('st_pctaicu')->default(0);
            $table->double('st_pcctp')->default(0);
            $table->double('st_pclaunam')->default(0); //công tác lâu năm
            //tính bảo hiểm
            $table->double('stbhxh_dv')->default(0);
            $table->double('stbhyt_dv')->default(0);
            $table->double('stkpcd_dv')->default(0);
            $table->double('stbhtn_dv')->default(0);
            $table->double('ttbh_dv')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->double('tongbh_dv')->default(0);
            //
            $table->double('tonghs')->default(0);
            $table->double('ttl')->default(0);
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
        Schema::dropIfExists('nguonkinhphi_phucap');
    }
}
