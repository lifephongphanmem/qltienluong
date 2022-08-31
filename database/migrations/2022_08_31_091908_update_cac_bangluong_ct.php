<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCacBangluongCt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bangluong_ct_01', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_02', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_03', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_04', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_05', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_06', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_07', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_08', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_09', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_10', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_11', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
        Schema::table('bangluong_ct_12', function (Blueprint $table) {
            $table->double('hs_pclt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bangluong_ct_01', function (Blueprint $table) {
            //
        });
    }
}
