<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi210623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            $table->double('sobiencheduocgiao')->default(0);
            //Mẫu 2c           
            $table->double('soluongqt_2c')->default(0);
            $table->double('sotienqt_2c')->default(0);
            $table->double('soluongcanbo_2c')->default(0);
            $table->double('hesoluong_2c')->default(0);
            $table->double('phucapchucvu_2c')->default(0);
            $table->double('phucapvuotkhung_2c')->default(0);
            $table->double('phucaptnn_2c')->default(0);
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
            $table->dropColumn('sobiencheduocgiao');
            $table->dropColumn('soluongqt_2c');
            $table->dropColumn('sotienqt_2c');
            $table->dropColumn('soluongcanbo_2c');
            $table->dropColumn('hesoluong_2c');
            $table->dropColumn('phucapchucvu_2c');
            $table->dropColumn('phucapvuotkhung_2c');
            $table->dropColumn('phucaptnn_2c');
        });
    }
}
