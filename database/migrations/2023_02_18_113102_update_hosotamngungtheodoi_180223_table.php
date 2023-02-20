<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHosotamngungtheodoi180223Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hosotamngungtheodoi', function (Blueprint $table) {
            $table->date('ngaythanhtoan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hosotamngungtheodoi', function (Blueprint $table) {
            $table->dropColumn('ngaythanhtoan');
        });
    }
}
