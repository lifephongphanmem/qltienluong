<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHosotruylinh290623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hosotruylinh', function (Blueprint $table) {
            $table->double('pctaicu')->default(0);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hosotruylinh', function (Blueprint $table) {
            $table->dropColumn('pctaicu');           
        });
    }
}
