<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmchucvucqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmchucvucq', function (Blueprint $table) {
            $table->increments('id');
            $table->string('macvcq', 50)->unique();
            $table->string('tencv', 100)->nullable();
            $table->string('ghichu')->nullable();
            $table->integer('sapxep')->default(99);
            $table->string('madv', 50)->nullable();
            $table->string('maphanloai', 50)->nullable();
            $table->string('phannhom', 50)->nullable();
            $table->string('tenvt', 100)->nullable();
            $table->integer('ttdv')->default(0);
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
        Schema::drop('dmchucvucq');
    }
}
