<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo extends Model
{
    protected $table = 'hosocanbo';
    protected $fillable = [
        'id',
        'mapb',
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
        'hesott',
        'vuotkhung',
        'pthuong',
        'pcct',
        'pckct',
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
        'pcvk',
        'pckn',
        'pcdang',
        'pccovu',
        'pclt',
        'pcd',
        'pctr',
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'theodoi',
        'mact',
        //'sodinhdanhcanhan',
        'macvcqkn'//chức vụ kiêm nhiệm
    ];
}
