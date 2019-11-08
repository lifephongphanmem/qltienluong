<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HosotruylinhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosotruylinh', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso', 50)->unique();
            $table->string('stt', 10)->nullable();
            $table->string('macvcq', 30)->nullable();
            $table->string('mapb', 30)->nullable();
            $table->string('mact', 30)->nullable();
            $table->string('maphanloai', 30)->nullable();//phân loại truy lĩnh
            $table->string('phanloai', 30)->nullable();//phân loại công tác
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('soqd', 50)->nullable();//chưa dùng
            $table->string('ngayqd')->nullable();//chưa dùng
            $table->string('nguoiky', 50)->nullable();//chưa dùng
            $table->string('coquanqd', 150)->nullable();//chưa dùng
            $table->string('mabl', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->text('noidung')->nullable();
            $table->string('madv')->nullable();

            $table->string('manguonkp',50)->nullable();
            $table->double('pthuong')->default(100);
            $table->double('luongcoban')->default(0);
            $table->double('thangtl')->default(0);//số tháng được truy lĩnh lương
            $table->double('ngaytl')->default(0);//số tháng được truy lĩnh lương

            $table->string('msngbac', 50)->nullable();
            $table->double('hesott')->default(0);
            //chưa dùng, cho trường hợp truy lĩnh cả phụ cấp
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
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
            $table->double('pctnvk')->default(0);
            $table->double('pcthni')->default(0);
            $table->double('pcbdhdcu')->default(0);

            $table->double('pclade')->default(0); //làm đêm
            $table->double('pcud61')->default(0); //ưu đãi theo tt61
            $table->double('pcxaxe')->default(0); //điện thoại
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng, lương khoán (số tiền)
            $table->double('pcphth')->default(0); //phẫu thuật, thủ thuật
            $table->double('pcctp')->default(0);//phụ cấp công tác phí
            $table->double('pctdt')->default(0);
            $table->double('pclaunam')->default(0);//công tác lâu năm

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
        Schema::drop('hosotruylinh');
    }
}
