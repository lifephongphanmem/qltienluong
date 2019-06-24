<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmthongtuquyetdinhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmthongtuquyetdinh', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sohieu')->nullable();
            $table->string('tenttqd')->nullable();
            $table->string('cancu')->nullable();
            $table->string('namdt')->nullable();
            $table->string('donvibanhanh')->nullable();
            $table->date('ngayapdung')->nullable();
            $table->double('mucu')->default(0);//lưu mức cũ vì khi tạo đơn vi đa phần đã update mức mới vào bảng gen
            $table->double('mucapdung')->default(0);//mức thay đổi có thể là hệ số hoặc %
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
        Schema::drop('dmthongtuquyetdinh');
    }
}
