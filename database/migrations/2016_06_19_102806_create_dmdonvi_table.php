<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdonviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdonvi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv', 50)->unique();
            $table->string('maqhns', 50)->nullable();
            $table->string('tendv',100)->nullable();
            $table->string('diachi',100)->nullable();
            $table->string('sodt',50)->nullable();
            $table->string('cdlanhdao',50)->nullable();
            $table->string('lanhdao',50)->nullable();
            $table->string('cdketoan',50)->nullable();
            $table->string('ketoan',50)->nullable();
            $table->integer('songuoi')->default(0);
            $table->string('macqcq',50)->nullable();
            $table->string('diadanh',50)->nullable();
            $table->string('nguoilapbieu',50)->nullable();
            $table->string('makhoipb',50)->nullable();//trường hợp cần tổng hợp theo ngành
            $table->string('madvbc',50)->nullable();
            $table->string('capdonvi',50)->nullable();//cấp dư toán 1,2,3,4
            $table->string('maphanloai',50)->nullable();//xác định đơn vị thuộc khối hcsn / xp
            $table->string('phanloaixa',50)->nullable();//đơn vị cấp X, H, T
            $table->string('phanloainguon')->nullable();
            $table->string('linhvuchoatdong')->nullable();//lĩnh vực hoạt động
            //theo dõi phụ cấp tại đơn vị 0:hệ số; 1:phần trăm; 2: số tiền
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
            $table->double('pcvk')->default(0);//dùng để thay thế phụ cấp Đảng ủy viên
            $table->double('pckn')->default(0);
            $table->double('pcdang')->default(0);
            $table->double('pccovu')->default(0);
            $table->double('pclt')->default(0); //lưu thay phụ cấp phân loại xã
            $table->double('pcd')->default(0);
            $table->double('pctr')->default(0);
            $table->double('pctnvk')->default(0);
            $table->double('pcbdhdcu')->default(0);
            $table->double('pcthni')->default(0);

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
        Schema::drop('dmdonvi');
    }
}
