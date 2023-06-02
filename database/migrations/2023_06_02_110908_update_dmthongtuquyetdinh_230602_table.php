<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDmthongtuquyetdinh230602Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dmthongtuquyetdinh', function (Blueprint $table) {
            $table->string('masobaocao',50)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dmthongtuquyetdinh', function (Blueprint $table) {
            $table->dropColumn('masobaocao');
        });
    }
}
