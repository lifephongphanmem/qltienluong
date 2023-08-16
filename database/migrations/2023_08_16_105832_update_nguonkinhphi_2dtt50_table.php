<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi2dtt50Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            //Mẫu 2d
            $table->double('soluongcanbo_2d')->default(0);
            $table->double('hesoluongbq_2d')->default(0);
            $table->double('hesophucapbq_2d')->default(0);
            $table->double('tyledonggop_2d')->default(0);
            $table->double('soluongdinhbien_2d')->default(0);
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
            $table->dropColumn('soluongcanbo_2d');
            $table->dropColumn('hesoluongbq_2d');
            $table->dropColumn('hesophucapbq_2d');
            $table->dropColumn('tyledonggop_2d');
            $table->dropColumn('soluongdinhbien_2d');
        });
    }
}
