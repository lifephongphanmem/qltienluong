<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo extends Model
{
    protected $table = 'hosocanbo';
    protected $fillable = [
        'id',
        'stt',
        'mapb',
        'macvcq',
        'macvd',
        'macanbo',
        'anh',
        'macongchuc',
        'sunghiep',
        'tencanbo',
        'tenkhac',
        'dantoc',
        'tongiao',
        'ngaysinh',
        'gioitinh',
        'socmnd',
        'ngaycap',
        'noicap',
        'sodt',
        'email',
        'sotk',
        'tennganhang',
        'madv',
        'lvtd',//nơi công tác
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
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'theodoi',
        'mact',
        'ngaybc',
        'ngayvao',
        'lvhd'
    ];
}
