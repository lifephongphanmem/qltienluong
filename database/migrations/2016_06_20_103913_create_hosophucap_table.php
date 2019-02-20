<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosophucapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosophucap', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stt')->default(99);//theo danh mục
            $table->string('mapc', 50)->nullable();
            $table->string('macanbo', 50);
            $table->string('macvcq', 50);
            $table->string('mact', 50);
            $table->string('maphanloai', 50);//phân loại công tác: CONGTAC, DBHDND, QUANSU, ...
            $table->string('phanloai')->nullable();//phân loai tính phụ cấp: số tiền, hệ số, phần trăm, ...
            $table->string('congthuc')->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('msngbac', 50)->nullable();
            $table->integer('bac')->default(1);
            $table->double('heso')->default(0);

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

            $table->string('ghichu')->nullable();
            $table->string('madv', 50)->nullable();//dùng update thông tin
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
        Schema::drop('hosophucap');
    }
}
