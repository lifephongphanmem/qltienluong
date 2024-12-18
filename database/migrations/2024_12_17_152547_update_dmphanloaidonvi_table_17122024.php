<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDmphanloaidonviTable17122024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dmphanloaidonvi', function (Blueprint $table) {
            $table->double('stt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dmphanloaidonvi', function (Blueprint $table) {
            $table->dropColumn('stt');
        });
    }
}
