<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmphucapDonviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmphucap_donvi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv', 50)->nullable();
            $table->string('mapc', 50)->nullable();
            $table->string('tenpc', 100)->nullable();
            $table->boolean('baohiem')->nullable()->default(0);
            $table->boolean('thaisan')->nullable()->default(0);
            $table->boolean('nghiom')->nullable()->default(0);
            $table->boolean('dieudong')->nullable()->default(0);
            $table->boolean('thuetn')->nullable()->default(0);
            $table->boolean('tapsu')->nullable()->default(0);
            $table->string('form')->nullable(); //tiêu đề trên Form
            $table->string('report')->nullable(); //tiêu đề trên Report
            $table->string('phanloai')->nullable();
            $table->string('congthuc')->nullable();//
            $table->string('ghichu')->nullable();
            $table->integer('stt')->default(99);
            $table->string('baohiem_plct')->nullable();//phân loại công tác cho bảo hiểm
            $table->boolean('pccoso')->nullable()->default(0);//phụ cấp cơ sở để tính
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
        Schema::drop('dmphucap_donvi');
    }
}
