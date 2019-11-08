<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosonhansu extends Model
{
    protected $table = 'hosocanbo';
    protected $fillable = [
        'id',
        'mapb',
        'stt',
        'macvcq',
        'macvd',
        'macanbo',
        'anh',
        'macongchuc',
        'sunghiep',
        'tencanbo',
        'tenkhac',
        'ngaysinh',
        'gioitinh',
        'nsxa',
        'nshuyen',
        'nstinh',
        'qqxa',
        'qqhuyen',
        'qqtinh',
        'dantoc',
        'tongiao',
        'tpgd', //thành phần gia đình
        'hktt',
        'noio',
        'ngaytd',
        'cqtd',
        'ngaybn',
        'ngayvao',//ngày vào cơ quan làm việc
        'cvcn', //chức vụ cao nhất đảm nhiệm
        'lvhd',//lĩnh vực hoạt động
        'nguontd',
        'httd', //hình thức tuyển dụng
        'lvtd', //lĩnh vực tuyển dụng
        'ngaybc',
        'tdgdpt', //trình độ giáo dục phổ thông
        'tdcm',//trình độ chuyên môn ------ xem có nên tách bảng hoặc tự động lấy thông tin cao nhất
        'chuyennganh',
        'noidt',
        'hinhthuc',
        'khoadt',
        'llct', //lý luận chính trị
        'qlnhanuoc',
        'ngoaingu',
        'trinhdonn',
        'trinhdoth',
        'ngayvd',
        'ngayvdct',
        'noikn',
        'ngayctctxh',//ngày công tác tổ chức chính trị - xã hội - có thể từ .. đến
        'cvtcxh', //chức vụ
        'ngaynn',
        'ngayxn',
        'qhcn', //quân hàm cao nhất
        'dhpt', //danh hiệu phong tặng
        'stct', //Sở trường công tác
        'ttsk',
        'chieucao',
        'cannang',
        'nhommau',
        'thuongtat',
        'sothuongtat',
        'thuongbinh',
        'giadinhcs',
        'socmnd',
        'ngaycap',
        'noicap',
        'lichsubt',
        'lichsuct',
        'thannhannn',
        'tnxbt',
        'soBHXH',
        'ngayBHXH',
        'thangtapsu',
        'sodt',
        'email',
        'sotk',
        'tennganhang',
        'tthn',
        'bhtn',
        'madv',
        //thông tin lương hiện tại
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'heso',
        'hesopc',
        'hesobl',
        'hesott',
        'truylinhtungay',
        'truylinhdenngay',
        'vuotkhung',
        'pthuong',
        'pcct',//dùng để thay thế phụ cấp ghép lớp
        'pckct',//dùng để thay thế phụ cấp bằng cấp cho cán bộ không chuyên trách
        'pck',
        'pccv',
        'pckv',
        'pcth',
        'pcdd',
        'pcdh',
        'pcld',
        'pcdbqh',
        'pcudn',
        'pctn',
        'pctnn',
        'pcdbn',
        'pcvk',//dùng để thay thế phụ cấp Đảng uy viên
        'pckn',
        'pcdang',
        'pccovu',
        'pclt', //lưu thay phụ cấp phân loại xã
        'pcd',
        'pctr',
        'pctdt',
        'pctnvk',
        'pcbdhdcu',
        'pcthni',

        'pclade',
        'pcud61',
        'pcxaxe',
        'pcdith',
        'luonghd',
        'pcphth',
        'pcctp',
        'pctaicu',
        'pclaunam',
        'theodoi',
        'mact',
        'baohiem',
        'bhxh',
        'bhyt',
        'bhtn',
        'bhtnld',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'bhtnld_dv',
        'kpcd_dv',
        'nguoiphuthuoc',

        'theodoi',
        'tenct',
        'sodinhdanhcanhan',
        'macvcqkn',
    ];
}
/*
 ALTER TABLE `hosocanbo` ADD `ngayctctxh` DATE NULL AFTER `noicap`;
ALTER TABLE `hosocanbo` ADD `cvtcxh` VARCHAR(50) NULL AFTER `macq`;
ALTER TABLE `hosocanbo` ADD `qhcn` VARCHAR(50) NULL AFTER `macq`;
ALTER TABLE `hosocanbo` ADD `dhpt` VARCHAR(50) NULL AFTER `macq`;

ALTER TABLE `hosocanbo` ADD `nsxa` VARCHAR(50) NULL AFTER `macq`;
ALTER TABLE `hosocanbo` ADD `nshuyen` VARCHAR(50) NULL AFTER `macq`;
ALTER TABLE `hosocanbo` ADD `nstinh` VARCHAR(50) NULL AFTER `macq`;

ALTER TABLE `hosocanbo` ADD `qqxa` VARCHAR(50) NULL AFTER `macq`;
ALTER TABLE `hosocanbo` ADD `qqhuyen` VARCHAR(50) NULL AFTER `macq`;
ALTER TABLE `hosocanbo` ADD `qqtinh` VARCHAR(50) NULL AFTER `macq`;
 */
