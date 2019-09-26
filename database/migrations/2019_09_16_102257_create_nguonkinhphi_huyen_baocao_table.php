<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiHuyenBaocaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_huyen_baocao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv', 50)->nullable();
            $table->string('masoh', 50)->nullable();
            $table->string('masot', 50)->nullable();
            $table->string('sohieu')->nullable();
            $table->string('manguonkp', 50)->nullable();
            $table->text('noidung')->nullable();
            $table->string('namns')->nullable();

            $table->double('thuchien')->default(0);
            $table->double('dutoan19')->default(0);
            $table->double('dutoan18')->default(0);
            $table->double('tietkiem17')->default(0);
            $table->double('tietkiem18')->default(0);
            $table->double('tietkiem19')->default(0);
            $table->double('tietkiem19gd')->default(0);
            $table->double('tietkiem19dt')->default(0);
            $table->double('tietkiem19yte')->default(0);
            $table->double('tietkiem19khac')->default(0);
            $table->double('tietkiem19qlnn')->default(0);
            $table->double('tietkiem19xa')->default(0);
            //Tự đảm bảo
            //Học phí
            $table->double('dbhocphi')->default(0);
            $table->double('dbhocphigd')->default(0);
            $table->double('dbhocphidt')->default(0);
            $table->double('dbhocphiyte')->default(0);
            $table->double('dbhocphikhac')->default(0);
            $table->double('dbhocphiqlnn')->default(0);
            $table->double('dbhocphixa')->default(0);
            //Viện phí
            $table->double('dbvienphi')->default(0);
            $table->double('dbvienphigd')->default(0);
            $table->double('dbvienphidt')->default(0);
            $table->double('dbvienphiyte')->default(0);
            $table->double('dbvienphikhac')->default(0);
            $table->double('dbvienphiqlnn')->default(0);
            $table->double('dbvienphixa')->default(0);
            //Khác
            $table->double('dbkhac')->default(0);
            $table->double('dbkhacgd')->default(0);
            $table->double('dbkhacdt')->default(0);
            $table->double('dbkhacyte')->default(0);
            $table->double('dbkhackhac')->default(0);
            $table->double('dbkhacqlnn')->default(0);
            $table->double('dbkhacxa')->default(0);
            //Chưa tự đảm bảo
            //Học phí
            $table->double('kdbhocphi')->default(0);
            $table->double('kdbhocphigd')->default(0);
            $table->double('kdbhocphidt')->default(0);
            $table->double('kdbhocphiyte')->default(0);
            $table->double('kdbhocphikhac')->default(0);
            $table->double('kdbhocphiqlnn')->default(0);
            $table->double('kdbhocphixa')->default(0);
            //Viện phí
            $table->double('kdbvienphi')->default(0);
            $table->double('kdbvienphigd')->default(0);
            $table->double('kdbvienphidt')->default(0);
            $table->double('kdbvienphiyte')->default(0);
            $table->double('kdbvienphikhac')->default(0);
            $table->double('kdbvienphiqlnn')->default(0);
            $table->double('kdbvienphixa')->default(0);
            //Khác
            $table->double('kdbkhac')->default(0);
            $table->double('kdbkhacgd')->default(0);
            $table->double('kdbkhacdt')->default(0);
            $table->double('kdbkhacyte')->default(0);
            $table->double('kdbkhackhac')->default(0);
            $table->double('kdbkhacqlnn')->default(0);
            $table->double('kdbkhacxa')->default(0);
            //Tiết kiệm theo 18, 19
            $table->double('tietkiemchi')->default(0);
            $table->double('tietkiemchigd')->default(0);
            $table->double('tietkiemchidt')->default(0);
            $table->double('tietkiemchiyte')->default(0);
            $table->double('tietkiemchikhac')->default(0);
            $table->double('tietkiemchiqlnn')->default(0);
            $table->double('tietkiemchixa')->default(0);

            $table->double('bosung')->default(0);
            $table->double('caicach')->default(0);

            $table->string('madv', 50)->nullable();
            $table->string('madvbc', 50)->nullable();
            $table->string('macqcq', 50)->nullable();
            $table->string('trangthai')->nullable();
            $table->date('ngayguidv')->nullable();
            $table->string('nguoiguidv')->nullable();
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
        Schema::drop('nguonkinhphi_huyen_baocao');
    }
}
