<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDmphanloaict090623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dmphanloaict', function (Blueprint $table) {
            $table->boolean('nhucaukp')->default(0);
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
            $table->dropColumn('nhucaukp');
        });
    }
}
