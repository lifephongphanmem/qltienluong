<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPcdpToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bangluong_ct_01', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });
        Schema::table('bangluong_ct_02', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_03', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_04', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_05', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_06', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_07', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_08', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_09', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_10', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_11', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('bangluong_ct_12', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });

        Schema::table('bangluongdangky_ct', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });

        Schema::table('dutoanluong_bangluong', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });        
        Schema::table('dutoanluong_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });
        Schema::table('dutoanluong_nangluong', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });  
        Schema::table('chitieubienche', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('hosocanbo', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('hosocanbo_kiemnhiem_temp', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('hosocanbo_kiemnhiem', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });

        Schema::table('hosodieudong', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });  
        Schema::table('hosothoicongtac', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('hosotruylinh', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('nguonkinhphi_01thang', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });  
        Schema::table('nguonkinhphi_bangluong', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        }); 
        Schema::table('nguonkinhphi_nangluong', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        }); 
        Schema::table('nguonkinhphi_phucap', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });
        Schema::table('tonghop_huyen_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghop_huyen_diaban', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghop_tinh_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghop_tinh_diaban', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_donvi_bangluong', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });
        Schema::table('tonghopluong_donvi_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
            $table->double('st_pcdp')->default(0);
        });
        Schema::table('tonghopluong_donvi_diaban', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_huyen_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_huyen_diaban', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_khoi_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_khoi_diaban', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_tinh_chitiet', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
        });
        Schema::table('tonghopluong_tinh_diaban', function (Blueprint $table) {
            $table->double('pcdp')->default(0);
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
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_02', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_03', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_04', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_05', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_06', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_07', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_08', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_09', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_10', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_11', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluong_ct_12', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('bangluongdangky_ct', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('dutoanluong_bangluong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('dutoanluong_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('dutoanluong_nangluong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('nguonkinhphi_01thang', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('nguonkinhphi_bangluong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('nguonkinhphi_nangluong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('nguonkinhphi_phucap', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('tonghopluong_donvi_bangluong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('tonghopluong_donvi_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
            $table->dropColumn('st_pcdp');
        });
        Schema::table('chitieubienche', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosocanbo', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosocanbo', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosocanbo_kiemnhiem_temp', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosocanbo_kiemnhiem', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosodieudong', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosothoicongtac', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('hosotruylinh', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghop_huyen_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghop_huyen_diaban', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghop_tinh_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghop_tinh_diaban', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_donvi_diaban', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_huyen_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_huyen_diaban', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_khoi_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_khoi_diaban', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_tinh_chitiet', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        Schema::table('tonghopluong_tinh_diaban', function (Blueprint $table) {
            $table->dropColumn('pcdp');
        });
        
    }
}
