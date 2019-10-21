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
            $table->string('maso', 50)->nullable();
            $table->string('manl', 50)->nullable();
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
            $table->string('ghichu')->nullable();
            $table->string('madv', 50)->nullable();
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
