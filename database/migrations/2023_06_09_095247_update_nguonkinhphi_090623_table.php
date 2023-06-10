<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi090623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            //Mẫu 2đ
            $table->double('soluonghientai_2dd')->default(0);
            $table->double('quyluonghientai_2dd')->default(0);
            $table->double('kinhphitietkiem_2dd')->default(0);
            $table->double('quyluongtietkiem_2dd')->default(0);
            //Mẫu 2h
            $table->double('soluonghientai_2h')->default(0);
            $table->double('hesoluong_2h')->default(0);
            $table->double('hesophucap_2h')->default(0);
            $table->double('tonghesophucapnd61_2h')->default(0);
            $table->double('tonghesophucapqd244_2h')->default(0);
            //Mẫu 2i
            $table->double('soluonghientai_2i')->default(0);
            $table->double('hesoluong_2i')->default(0);
            $table->double('hesophucap_2i')->default(0);
            //Mẫu 2k
            $table->double('soluonggiam_2k')->default(0);
            $table->double('quyluonggiam_2k')->default(0);
            //Mẫu 2d
            $table->double('sothonbiengioi_2d')->default(0);
            $table->double('sothontrongdiem_2d')->default(0);
            $table->double('sothonconlai_2d')->default(0);
            $table->double('sotoconlai_2d')->default(0);
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
            $table->dropColumn('soluonghientai_2dd');
            $table->dropColumn('quyluonghientai_2dd');
            $table->dropColumn('kinhphitietkiem_2dd');
            $table->dropColumn('quyluongtietkiem_2dd');            
            $table->dropColumn('soluonghientai_2h');
            $table->dropColumn('hesoluong_2h');
            $table->dropColumn('hesophucap_2h');
            $table->dropColumn('tonghesophucapnd61_2h');
            $table->dropColumn('tonghesophucapqd244_2h');           
            $table->dropColumn('soluonghientai_2i');
            $table->dropColumn('hesoluong_2i');
            $table->dropColumn('hesophucap_2i');            
            $table->dropColumn('soluonggiam_2k');
            $table->dropColumn('quyluonggiam_2k');            
            $table->dropColumn('sothonbiengioi_2d');
            $table->dropColumn('sothontrongdiem_2d');
            $table->dropColumn('sothonconlai_2d');
            $table->dropColumn('sotoconlai_2d');
        });
    }
}
