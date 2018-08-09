<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmdonvibaocaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmdonvibaocao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('madvbc', 50)->nullable();
            $table->string('tendvbc')->nullable();
            $table->string('level')->nullable(); //đơn vị sử dụng, tổng hợp dữ liệu huyện, sở ban ngành; đơn vị tổng hợp, kết xuất báo cáo toàn tỉnh
            $table->string('ghichu')->nullable();
            $table->string('madvcq', 50)->nullable();// đơn vị chủ quản, quản lý của khối báo cáo
            //vào đây để tạo các đơn vị thuộc khối này
            //madvcq có thể null=>khi khối này đã có đơn vị => đưa ra thông báo chọn đơn vị chủ quản
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
        Schema::drop('dmdonvibaocao');
    }
}
