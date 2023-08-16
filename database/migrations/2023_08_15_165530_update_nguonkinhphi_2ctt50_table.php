<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi2ctt50Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Lưu thông tin vào _2d do làm từ trước
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            //Mẫu 2c
            $table->double('sotodanphobiengioi_2d')->default(0);
            $table->double('sothon350hgd_2d')->default(0);
            $table->double('sotodanpho500hgd_2d')->default(0);
            $table->double('sochuyentuthon350hgd_2d')->default(0);
            $table->double('sothonbiengioi_tong')->default(0);
            $table->double('sotodanphobiengioi_tong')->default(0);
            $table->double('sothon350hgd__tong')->default(0);
            $table->double('sotodanpho500hgd_tong')->default(0);
            $table->double('sothontrongdiem_tong')->default(0);
            $table->double('sochuyentuthon350hgd_tong')->default(0);
            $table->double('sothonconlai_tong')->default(0);
            $table->double('sotoconlai_tong')->default(0);
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
            $table->dropColumn('sotodanphobiengioi_2d');
            $table->dropColumn('sothon350hgd_2d');
            $table->dropColumn('sotodanpho500hgd_2d');
            $table->dropColumn('sochuyentuthon350hgd_2d');
            $table->dropColumn('sothonbiengioi_tong');
            $table->dropColumn('sotodanphobiengioi_tong');
            $table->dropColumn('sothon350hgd__tong');
            $table->dropColumn('sotodanpho500hgd_tong');
            $table->dropColumn('sothontrongdiem_tong');
            $table->dropColumn('sochuyentuthon350hgd_tong');
            $table->dropColumn('sothonconlai_tong');
            $table->dropColumn('sotoconlai_tong');           
        });
    }
}
