<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosobaohiemxahoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosobaohiemxahoi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macanbo')->nullable();
            $table->date('ngaytu')->nullable();
            $table->integer('tuthang')->default(0);
            $table->integer('tunam')->default(0);

            $table->date('ngayden')->nullable();
            $table->integer('denthang')->default(0);
            $table->integer('dennam')->default(0);

            $table->double('mucnop')->default(0);
            $table->double('bhxh')->default(0);
            $table->double('bhtn')->default(0);

            $table->string('noidung')->nullable();
            $table->string('msngbac')->nullable();
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
        Schema::drop('hosobaohiemxahoi');
    }
}
