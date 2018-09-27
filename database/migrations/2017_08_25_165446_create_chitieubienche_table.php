<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChitieubiencheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chitieubienche', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv')->nullable();
            $table->double('nam')->default(0);
            $table->string('linhvuchoatdong',50)->nullable();
            $table->string('mact',50)->nullable();
            $table->string('macongtac',50)->nullable();
            $table->double('soluongduocgiao')->default(0);
            $table->double('soluongbienche')->default(0);
            $table->double('soluongkhongchuyentrach')->default(0);
            $table->double('soluonguyvien')->default(0);
            $table->double('soluongdaibieuhdnd')->default(0);
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
        Schema::drop('chitieubienche');
    }
}
