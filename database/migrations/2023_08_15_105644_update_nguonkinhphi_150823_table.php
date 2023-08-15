<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi150823Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            //Mẫu 2b
            $table->double('quy3_1')->default(0);
            $table->double('quy3_2')->default(0);
            $table->double('quy3_3')->default(0);           
            //Tổng nhu cầu theo quỹ
            $table->double('quy1_tong')->default(0);
            $table->double('quy2_tong')->default(0);
            $table->double('quy3_tong')->default(0);
           
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
            $table->dropColumn('quy3_1');
            $table->dropColumn('quy3_2');
            $table->dropColumn('quy3_3');
            $table->dropColumn('quy1_tong');
            $table->dropColumn('quy2_tong');
            $table->dropColumn('quy3_tong');
           
        });
    }
}
