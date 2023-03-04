<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDsdonviquanlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsdonviquanly', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('macqcq',50)->nullable();           
            $table->string('nam',10)->nullable();
            $table->string('thang',10)->nullable();
            $table->string('noidung')->nullable();
            $table->string('ghichu')->nullable();
            $table->string('madv',50);
            //làm dự phòng theo dõi theo ngày tháng
            $table->date('tungay')->nullable();
            $table->date('denngay')->nullable();
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
        Schema::dropIfExists('dsdonviquanly');
    }
}
