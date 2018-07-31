<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDutoanluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dutoanluong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masodv',50)->unique();
            $table->string('masok',50)->nullable();
            $table->string('masoh',50)->nullable();
            $table->string('masot',50)->nullable();
            $table->string('namns')->nullable();
            $table->double('luongnb_dt')->default(0);
            $table->double('luonghs_dt')->default(0);
            $table->double('luongbh_dt')->default(0);
            $table->string('ghichu')->nullable();
            $table->string('madv')->nullable();
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
        Schema::drop('dutoanluong');
    }
}
