<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNguonkinhphi20240720Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nguonkinhphi', function (Blueprint $table) {
            $table->double('nguonketdu_4a')->default(0);
            $table->double('nguontralai_4a')->default(0);
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
            $table->dropColumn('nguonketdu_4a');
            $table->dropColumn('nguontralai_4a');
        });
    }
}
