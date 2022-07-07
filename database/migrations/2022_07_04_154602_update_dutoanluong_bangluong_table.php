<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDutoanluongBangluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Lưu các bảo hiểm quy đổi hệ số
        Schema::table('dutoanluong_bangluong', function (Blueprint $table) {
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->double('tongbh_dv')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
