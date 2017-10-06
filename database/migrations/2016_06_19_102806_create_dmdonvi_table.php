<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdonviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdonvi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madv', 50)->unique();
            $table->string('tendv')->unique();
            $table->string('diachi')->nullable();
            $table->string('sodt')->nullable();
            $table->string('lanhdao')->nullable();
            $table->integer('songuoi')->default(0);
            $table->string('macqcq')->nullable();
            $table->string('diadanh')->nullable();
            $table->string('cdlanhdao')->nullable();
            $table->string('nguoilapbieu')->nullable();
            $table->string('makhoipb')->nullable();//lĩnh vực hoạt động
            $table->string('madvbc')->nullable();

            $table->string('capdonvi')->nullable();//đơn vị cấp X, H, T
            $table->string('maphanloai')->nullable();//xác định đơn vị thuộc khối hcsn / xp
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
        Schema::drop('dmdonvi');
    }
}
