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
            $table->double('bhxh')->default(0);
            $table->double('bhyt')->default(0);
            $table->double('bhtn')->default(0);
            $table->double('kpcd')->default(0);
            $table->double('stbhxh')->default(0);
            $table->double('stbhyt')->default(0);
            $table->double('stbhtn')->default(0);
            $table->double('stkpcd')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->double('stbhxh_dv')->default(0);
            $table->double('stbhyt_dv')->default(0);
            $table->double('stbhtn_dv')->default(0);
            $table->double('stkpcd_dv')->default(0);
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
