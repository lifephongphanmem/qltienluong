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
            $table->string('lanhdao',50)->nullable();
            $table->integer('songuoi')->default(0);
            $table->string('macqcq',50)->nullable();
            $table->string('diadanh',50)->nullable();
            $table->string('cdlanhdao',50)->nullable();
            $table->string('nguoilapbieu',50)->nullable();
            $table->string('makhoipb',50)->nullable();//trường hợp cần tổng hợp theo ngành
            $table->string('madvbc',50)->nullable();
            $table->string('capdonvi',50)->nullable();//cấp dư toán 1,2,3,4
            $table->string('maphanloai',50)->nullable();//xác định đơn vị thuộc khối hcsn / xp
            $table->string('phanloaixa',50)->nullable();//đơn vị cấp X, H, T
            $table->string('phanloainguon')->nullable();
            $table->string('linhvuchoatdong')->nullable();//lĩnh vực hoạt động
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
