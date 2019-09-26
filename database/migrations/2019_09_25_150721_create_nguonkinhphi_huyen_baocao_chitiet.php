<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiHuyenBaocaoChitiet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_huyen_baocao_chitiet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv', 50)->nullable();
            $table->string('masoh', 50)->nullable();
            $table->string('masot', 50)->nullable();
            $table->string('sohieu')->nullable();
            $table->string('namns')->nullable();
            $table->string('linhvuchoatdong')->nullable();

            $table->double('tietkiem')->default(0);
            //Tự đảm bảo
            //Học phí
            $table->double('dbhocphi')->default(0);
            //Viện phí
            $table->double('dbvienphi')->default(0);
            //Khác
            $table->double('dbkhac')->default(0);
            //Chưa tự đảm bảo
            //Học phí
            $table->double('kdbhocphi')->default(0);
            //Viện phí
            $table->double('kdbvienphi')->default(0);
            //Khác
            $table->double('kdbkhac')->default(0);
            //Tiết kiệm theo 18, 19
            $table->double('tietkiemchi')->default(0);

            $table->string('madv', 50)->nullable();
            $table->string('madvbc', 50)->nullable();
            $table->string('macqcq', 50)->nullable();
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
        Schema::dropIfExists('nguonkinhphi_huyen_baocao_chitiet');
    }
}
