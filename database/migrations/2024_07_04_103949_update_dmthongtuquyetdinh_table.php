<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDmthongtuquyetdinhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dmthongtuquyetdinh', function (Blueprint $table) {
            $table->string('cancundtruoc')->nullable();//Nghị định trước khi áp dụng
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
            $table->dropColumn('cancundtruoc');
        });
    }
}
