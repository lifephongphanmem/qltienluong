<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosoluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosoluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manl', 50)->nullable();
            $table->foreign('manl')->references('manl')->on('dsnangluong')->onUpdate('cascade')->onDelete('cascade');
            $table->string('mahts', 50)->nullable();
            $table->foreign('mahts')->references('mahts')->on('dshettapsu')->onUpdate('cascade')->onDelete('cascade');
            // $table->integer('id_nb', 50)->length(10)->unsigned();//tham chiếu bảng ngachbac lấy mã, bậc, hệ số,....
            $table->string('macanbo', 50);
            $table->foreign('macanbo')->references('macanbo')->on('hosocanbo')->onUpdate('cascade')->onDelete('cascade');

            $table->string('phanloai', 50)->nullable();
            $table->string('msngbac', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->integer('bac')->default(1);
            $table->double('heso')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pthuong')->default(100);
            $table->string('ketqua', 50)->nullable();
            $table->string('madv', 50)->nullable();
            $table->timestamps();
        });
    }

    public function integer($column, $autoIncrement = false, $unsigned = false)
    {
        return $this->addColumn('integer', $column, compact('autoIncrement', 'unsigned'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hosoluong');
    }
}
