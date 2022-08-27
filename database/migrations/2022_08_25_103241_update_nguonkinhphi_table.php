<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            $table->integer('tongsonguoi1')->default(0);
            $table->integer('tongsonguoi2')->default(0);
            $table->integer('tongsonguoi3')->default(0);
            $table->double('quy1_1')->default(0);
            $table->double('quy1_2')->default(0);
            $table->double('quy1_3')->default(0);
            $table->double('quy2_1')->default(0);
            $table->double('quy2_2')->default(0);
            $table->double('quy2_3')->default(0);
            $table->integer('tongsonguoi2015')->default(0);
            $table->integer('tongsonguoi2017')->default(0);
            $table->double('quyluong')->default(0);
            $table->integer('tongsodonvi1')->default(0);
            $table->integer('tongsodonvi2')->default(0);
            $table->double('quy_tuchu')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            //
        });
    }
}
