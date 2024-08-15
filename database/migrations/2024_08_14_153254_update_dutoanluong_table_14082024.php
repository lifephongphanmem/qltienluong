<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDutoanluongTable14082024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dutoanluong', function (Blueprint $table) {
            $table->double('sothonxa350ho')->default(0);
            $table->double('sotodanpho500ho')->default(0);
            $table->double('sochuyentuthon350hgd')->default(0);
            $table->double('sotodanphokhac')->default(0);

            $table->double('sothonxa350ho_heso')->default(0);
            $table->double('sotodanpho500ho_heso')->default(0);
            $table->double('sochuyentuthon350hgd_heso')->default(0);
            $table->double('sotodanphokhac_heso')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dutoanluong', function (Blueprint $table) {
            $table->dropColumn('sothonxa350ho');
            $table->dropColumn('sotodanpho500ho');
            $table->dropColumn('sochuyentuthon350hgd');
            $table->dropColumn('sotodanphokhac');

            $table->dropColumn('sothonxa350ho_heso');
            $table->dropColumn('sotodanpho500ho_heso');
            $table->dropColumn('sochuyentuthon350hgd_heso');
            $table->dropColumn('sotodanphokhac_heso');

        });
    }
}
