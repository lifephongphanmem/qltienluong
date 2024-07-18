<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi20240717Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            $table->double('quythuong_2a')->default(0);
            $table->double('thuchien2')->default(0);
            $table->double('nsnngiam')->default(0);
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
            $table->dropColumn('quythuong_2a');
            $table->dropColumn('thuchien2');
            $table->dropColumn('nsnngiam');
        });
    }
}
