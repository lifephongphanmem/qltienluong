<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiDinhmucCtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_dinhmuc_ct', function (Blueprint $table) {
            $table->increments('id');
            $table->string('maso', 50)->nullable();
            $table->string('mapc', 50)->nullable();
            $table->string('tenpc', 100)->nullable();
            $table->boolean('tinhtheodm')->default(0)->nullable();
            $table->double('luongcoban')->default(0);
            $table->string('ghichu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nguonkinhphi_dinhmuc_ct');
    }
}
