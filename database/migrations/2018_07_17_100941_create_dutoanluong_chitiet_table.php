<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDutoanluongChitietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //dự toán = canbo_congtac * (luongnb/ luonghs/ luongbh)

        Schema::create('dutoanluong_chitiet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv',50)->nullable();
            $table->string('masok',50)->nullable();
            $table->string('masoh',50)->nullable();
            $table->string('masot',50)->nullable();
            $table->double('canbo_congtac')->default(0);//lấy số lượng thực tế tại đơn vị
            $table->double('canbo_dutoan')->default(0);
            $table->string('macongtac', 50)->nullable();
            $table->string('mact', 50)->nullable();
            //dự toán cho cán bộ chưa tuyển
            $table->double('luongnb')->default(0);
            $table->double('luonghs')->default(0);
            $table->double('luongbh')->default(0);
            //dự toán cho cán bộ đang công tác
            $table->double('luongnb_dt')->default(0);
            $table->double('luonghs_dt')->default(0);
            $table->double('luongbh_dt')->default(0);
            
            $table->string('ghichu')->nullable();
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
        Schema::drop('dutoanluong_chitiet');
    }
}
