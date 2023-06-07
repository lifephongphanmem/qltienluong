<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi070623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //trường để xác định xem đã tổng hợp phụ cấp ra bảng: nguonkinhphi_phucap
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            $table->boolean('nangcap_phucap')->default(0);
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
            $table->dropColumn('nangcap_phucap');
        });
    }
}
