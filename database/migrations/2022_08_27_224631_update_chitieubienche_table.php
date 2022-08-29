<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateChitieubiencheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chitieubienche', function (Blueprint $table) {
            $table->double('soluongcongchuc')->default(0);
            $table->double('soluongvienchuc')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chitieubienche', function (Blueprint $table) {
            //
        });
    }
}
