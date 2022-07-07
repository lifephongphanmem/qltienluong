<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDutoanluongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Thêm phân loại, và lưu thông tin số lượng thôn
        Schema::table('dutoanluong', function (Blueprint $table) {
            $table->string('phanloai',20)->default('DUUOC')->nullable();
            $table->string('phanloaixa',20)->nullable();
            $table->double('sothonxabiengioi')->default(0);
            $table->double('sothonxakhokhan')->default(0);
            $table->double('sothonxatrongdiem')->default(0);
            $table->double('sothonxakhac')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
