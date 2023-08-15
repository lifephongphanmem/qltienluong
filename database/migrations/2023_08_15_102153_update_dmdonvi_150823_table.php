<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDmdonvi150823Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dmdonvi', function (Blueprint $table) {
            $table->string('machuong')->nullable();            
            $table->string('maloaikhoan')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dmdonvi', function (Blueprint $table) {
            $table->dropColumn('machuong');           
            $table->dropColumn('maloaikhoan');           
        });
    }
}
