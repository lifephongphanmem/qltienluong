<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tuoinu')->default(0);
            $table->integer('tuoinam')->default(0);
            $table->integer('thangnu')->default(0);
            $table->integer('thangnam')->default(0);
            $table->string('tinh', 30)->nullable();
            $table->string('huyen', 30)->nullable();
            $table->double('luongcb')->default(0);
            $table->text('thongbao')->nullable();
            $table->double('tg_hetts')->default(0);//Thời gian xet hết tập sự
            $table->double('tg_xetnl')->default(0);//Thời gian xet nâng lương
            $table->string('kytuthapphan',10)->nullable()->defautt(',');
            $table->string('kytunhom',10)->nullable()->default('.');
            $table->string('ipf1')->nullable();
            $table->string('ipf2')->nullable();
            $table->string('ipf3')->nullable();
            $table->string('ipf4')->nullable();
            $table->string('ipf5')->nullable();
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
        Schema::drop('general_configs');
    }
}
