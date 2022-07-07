<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDutoanluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Thêm trường lưu hệ số
        Schema::table('dutoanluong', function (Blueprint $table) {
            $table->double('sothonxabiengioi_heso')->default(0);
            $table->double('sothonxakhokhan_heso')->default(0);
            $table->double('sothonxaloai1_heso')->default(0);
            $table->double('sothonxatrongdiem_heso')->default(0);
            $table->double('sothonxakhac_heso')->default(0);

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
