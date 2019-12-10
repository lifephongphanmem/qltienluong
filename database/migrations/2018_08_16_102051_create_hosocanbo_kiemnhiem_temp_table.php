<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosocanboKiemnhiemTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosocanbo_kiemnhiem_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phanloai', 50)->nullable();
            $table->string('mapb', 50)->nullable();
            $table->string('manguonkp',50)->nullable();//nguồn kinh phí lấy lương
            $table->string('macvcq', 50)->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->double('heso')->default(0);
            $table->double('hesopc')->default(0);
            $table->double('pcdbn')->default(0);
            $table->double('pctn')->default(0);
            $table->double('pctnn')->default(0);
            $table->double('pck')->default(0);

            //chưa dùng đến
            $table->string('msngbac', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->integer('bac')->default(1);
            $table->double('hesott')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pthuong')->default(100);
            $table->double('pcct')->default(0);//dùng để thay thế phụ cấp ghép lớp
            $table->double('pckct')->default(0);
            $table->double('pccv')->default(0);
            $table->double('pckv')->default(0);
            $table->double('pcth')->default(0);
            $table->double('pcdd')->default(0);
            $table->double('pcdh')->default(0);
            $table->double('pcld')->default(0);
            $table->double('pcdbqh')->default(0);
            $table->double('pcudn')->default(0);
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
            $table->double('hesobl')->default(0);
            $table->double('pclade')->default(0); //làm đêm
            $table->double('pcud61')->default(0); //ưu đãi theo tt61
            $table->double('pcxaxe')->default(0); //điện thoại
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng, lương khoán (số tiền)
            $table->double('pcphth')->default(0); //phẫu thuật, thủ thuật
            $table->double('pcctp')->default(0);//phụ cấp công tác phí
            $table->double('pctaicu')->default(0);//phụ cấp tái ứng cử
            $table->double('pclaunam')->default(0);//công tác lâu năm

            $table->string('mact')->nullable();
            $table->double('baohiem')->default(1);
            $table->string('madv',50)->nullable();
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
        Schema::drop('hosocanbo_kiemnhiem_temp');
    }
}
