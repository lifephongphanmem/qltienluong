<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePhanboluongBangluong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phanboluong_bangluong', function (Blueprint $table) {
            $table->double('pcdp')->default(0); //phụ cấp dân phòng
            $table->double('st_pcdp')->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phanboluong_bangluong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
    }
}
