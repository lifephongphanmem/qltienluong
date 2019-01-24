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
            $table->string('tinh', 30)->nullable();
            $table->string('huyen', 30)->nullable();
            $table->double('luongcb')->default(0);
            $table->text('thongbao')->nullable();
            $table->double('tg_hetts')->default(0);//Thời gian xet hết tập sự
            $table->double('tg_xetnl')->default(0);//Thời gian xet nâng lương
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
