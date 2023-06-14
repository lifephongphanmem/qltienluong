<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDmphanloaict140623Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dmphanloaict', function (Blueprint $table) {
            $table->string('nhomnhucau_hc')->nullable();
            $table->string('nhomnhucau_xp')->nullable();
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
            $table->dropColumn('nhomnhucau_hc');
            $table->dropColumn('nhomnhucau_xp');
        });
    }
}
