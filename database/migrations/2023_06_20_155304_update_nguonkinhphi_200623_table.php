<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi200623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            //Mẫu 4a
            $table->double('tinhgiambc_4a')->default(0);
            $table->double('satnhapdaumoi_4a')->default(0);
            $table->double('thaydoicochetuchu_4a')->default(0);
            $table->double('satnhapxa_4a')->default(0);
           
            $table->double('huydongtx_hocphi_4a')->default(0);
            $table->double('huydongtx_vienphi_4a')->default(0);
            $table->double('huydongtx_khac_4a')->default(0);
            $table->double('huydongktx_hocphi_4a')->default(0);
            $table->double('huydongktx_vienphi_4a')->default(0);
            $table->double('huydongktx_khac_4a')->default(0);
            $table->double('kinhphigiamxa_4a')->default(0);
            
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            $table->dropColumn('tinhgiambc_4a');
            $table->dropColumn('satnhapdaumoi_4a');
            $table->dropColumn('thaydoicochetuchu_4a');
            $table->dropColumn('satnhapxa_4a');           
            $table->dropColumn('huydongtx_hocphi_4a');
            $table->dropColumn('huydongtx_vienphi_4a');
            $table->dropColumn('huydongtx_khac_4a');
            $table->dropColumn('huydongktx_hocphi_4a');
            $table->dropColumn('huydongktx_vienphi_4a');
            $table->dropColumn('huydongktx_khac_4a');
            $table->dropColumn('kinhphigiamxa_4a');
        });
    }
}
