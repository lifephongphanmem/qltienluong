<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmthuetncnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmthuetncn', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sohieu')->unique();
            $table->string('tenttqd')->nullable();
            $table->string('donvibanhanh')->nullable();
            $table->date('ngayapdung')->nullable();
            $table->double('banthan')->default(0);//mức giảm trừ bản thân
            $table->double('phuthuoc')->default(0);//mức giảm trừ của người phụ thuộc
            $table->text('ghichu')->nullable();
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
        Schema::dropIfExists('dmthuetncn');
    }
}
