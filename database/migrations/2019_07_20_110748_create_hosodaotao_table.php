<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosodaotaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosodaotao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo', 50);
            $table->string('phanloai', 100)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->string('tencoso', 150)->nullable();
            $table->string('chuyennganh', 100)->nullable();
            $table->string('hinhthuc', 100)->nullable();
            $table->string('vanbang', 100)->nullable();
            $table->string('ghichu')->nullable();
            $table->string('madv', 50)->nullable();
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
        Schema::drop('hosodaotao');
    }
}
