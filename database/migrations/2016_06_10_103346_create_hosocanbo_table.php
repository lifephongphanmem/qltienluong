<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHosocanboTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosocanbo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stt', 10)->nullable();
            $table->string('mapb', 50)->nullable();
            $table->string('manguonkp',50)->nullable();//nguồn kinh phí lấy lương
            $table->string('macvcq', 50)->nullable();
            $table->string('macvd', 50)->nullable();
            $table->string('macanbo', 50)->unique();
            $table->string('anh', 150)->nullable();
            $table->string('macongchuc', 50)->nullable();
            $table->string('sunghiep', 100)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('tenkhac', 50)->nullable();
            $table->date('ngaysinh')->nullable();
            $table->string('gioitinh', 10)->nullable();
            $table->string('dantoc', 20)->nullable();
            $table->string('tongiao', 20)->nullable();
            $table->string('hktt')->nullable();
            $table->string('noio')->nullable();
            $table->date('ngaytd')->nullable();
            $table->string('lvtd')->nullable();//nơi công tác
            $table->string('cqtd')->nullable();
            $table->date('ngaybn')->nullable();
            $table->date('ngayvao')->nullable();//ngày vào cơ quan làm việc
            $table->string('cvcn', 50)->nullable();//chức vụ cao nhất đảm nhiệm
            $table->string('lvhd')->nullable();//lĩnh vực hoạt động
            $table->string('nguontd')->nullable();
            $table->string('httd', 50)->nullable();//hình thức tuyển dụng
            $table->date('ngaybc')->nullable();
            $table->string('tdgdpt', 20)->nullable();//trình độ giáo dục phổ thông
            $table->string('tdcm')->nullable();//trình độ chuyên môn ------ xem có nên tách bảng hoặc tự động lấy thông tin cao nhất
            $table->string('chuyennganh', 100)->nullable();
            $table->string('noidt', 150)->nullable();
            $table->string('hinhthuc', 100)->nullable();
            $table->string('khoadt', 50)->nullable();
            $table->string('llct', 50)->nullable();//lý luận chính trị
            $table->string('qlnhanuoc', 100)->nullable();
            $table->string('ngoaingu', 30)->nullable();
            $table->string('trinhdonn', 30)->nullable();
            $table->string('trinhdoth', 30)->nullable();
            $table->date('ngayvd')->nullable();
            $table->date('ngayvdct')->nullable();
            $table->string('noikn', 100)->nullable();
            $table->string('stct', 150)->nullable();//Sở trường công tác
            $table->string('ttsk', 50)->nullable();
            $table->string('chieucao', 20)->nullable();
            $table->string('cannang', 20)->nullable();
            $table->string('nhommau', 20)->nullable();
            $table->string('socmnd', 20)->nullable();
            $table->date('ngaycap')->nullable();
            $table->string('noicap', 100)->nullable();
            $table->string('soBHXH', 30)->nullable();
            $table->date('ngayBHXH')->nullable();
            $table->string('sodt', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('sotk', 50)->nullable();
            $table->string('tennganhang', 150)->nullable();
            $table->string('tthn', 100)->nullable();
            //$table->boolean('bhtn')->default(1);
            $table->string('madv',50)->nullable();
            //Thông tin lương hiện tại
            $table->string('msngbac', 50)->nullable();
            $table->date('ngaytu')->nullable();
            $table->date('ngayden')->nullable();
            $table->integer('bac')->default(1);
            $table->double('heso')->default(0);
            $table->double('hesobl')->default(0);
            $table->double('hesopc')->default(0);
            $table->double('vuotkhung')->default(0);
            $table->double('pthuong')->default(100);
            $table->double('pcct')->default(0);//dùng để thay thế phụ cấp ghép lớp
            $table->double('pckct')->default(0);//dùng để thay thế phụ cấp bằng cấp cho cán bộ không chuyên trách
            $table->double('pck')->default(0);
            $table->double('pccv')->default(0);
            $table->double('pckv')->default(0);
            $table->double('pcth')->default(0);
            $table->double('pcdd')->default(0);
            $table->double('pcdh')->default(0);
            $table->double('pcld')->default(0);
            $table->double('pcdbqh')->default(0);
            $table->double('pcudn')->default(0);
            $table->double('pctn')->default(0);
            $table->double('pctnn')->default(0);
            $table->double('pcdbn')->default(0);
            $table->double('pcvk')->default(0);//dùng để thay thế phụ cấp Đảng uy viên
            $table->double('pckn')->default(0);
            $table->double('pcdang')->default(0);
            $table->double('pccovu')->default(0);
            $table->double('pclt')->default(0); //lưu thay phụ cấp phân loại xã
            $table->double('pcd')->default(0);
            $table->double('pctr')->default(0);
            $table->double('pctdt')->default(0);
            $table->double('pctnvk')->default(0);
            $table->double('pcbdhdcu')->default(0);
            $table->double('pcthni')->default(0);
            //chưa thêm
            $table->double('pclade')->default(0); //làm đêm
            $table->double('pcud61')->default(0); //ưu đãi theo tt61
            $table->double('pcxaxe')->default(0); //điện thoại
            $table->double('pcdith')->default(0); //điện thoại
            $table->double('luonghd')->default(0); //lương hợp đồng (số tiền)
            $table->double('pcctp')->default(0);//phụ cấp công tác phí
            $table->double('pctaicu')->default(0);//phụ cấp tái ứng cử
            $table->double('pclaunam')->default(0);//công tác lâu năm

            //thông tin truy lĩnh lương, sau khi tính lại bảng lương xét lại về ban đầu
            $table->date('truylinhtungay')->nullable();
            $table->date('truylinhdenngay')->nullable();
            $table->double('hesott')->default(0);//hệ số truy thu, sau khi tạo bảng lương -> set = 0;

            $table->date('tnntungay')->nullable();
            $table->date('ttnndenngay')->nullable();

            $table->string('mact')->nullable();
            $table->string('theodoi',5)->default(1)->nullable();
            $table->double('baohiem')->default(1);
            $table->string('sodinhdanhcanhan')->nullable();

            $table->double('bhxh')->default(0);
            $table->double('bhyt')->default(0);
            $table->double('kpcd')->default(0);
            $table->double('bhtn')->default(0);
            $table->double('bhtnld')->default(0);
            $table->double('bhxh_dv')->default(0);
            $table->double('bhyt_dv')->default(0);
            $table->double('kpcd_dv')->default(0);
            $table->double('bhtn_dv')->default(0);
            $table->double('bhtnld_dv')->default(0);
            $table->double('nguoiphuthuoc')->default(0);

            $table->string('khongnopbaohiem')->nullable();// các loại phụ cấp không nộp bảo hiểm
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
        Schema::drop('hosocanbo');
    }
}
