<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo extends Model
{
    protected $table = 'hosocanbo';
    protected $fillable = [
        'id',
        'stt',
        'manguonkp',
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
        'tnntungay',
        'tnndenngay',
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
        'ngaybc',
        'ngayvao',
        'lvhd'
    ];
}
//bangluongct; bangluongdangky_ct; dutoanluong_bangluong; dutoanluong_nangluong


//dsnangluong_chitiet; dsnangthamnien_chitiet; hosocanbo_kiemnhiem; hosocanbo_kiemnhiem_temp; hosodieudong;
//hosothoicongtac; nguonkinhphi_bangluong; tonghopluong_donvi_bangluong

//thêm pcctp
//nguonkinhphi_bangluong; dsnangthamnien_chitiet; hosodieudong; hosothoicongtac; tonghopluong_donvi_bangluong

