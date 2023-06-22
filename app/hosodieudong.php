<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosodieudong extends Model
{
    protected $table = 'hosodieudong';
    protected $fillable = [
        'id',
        'maso',
        'soqd',  //chưa dùng
        'ngayqd', //chưa dùng
        'nguoiky',  //chưa dùng
        'coquanqd',  //chưa dùng
        'ngaylc',
        'ngaylctu',
        'ngaylcden',
        'maphanloai',//thuyên chuyển / điều động (có ngày tháng)
        'trangthai',
        'madv_dd',
        'macvcq_dd',
        'noidung',

        'stt',
        'mapb',
        'manguonkp',//nguồn kinh phí lấy lương
        'macvcq',
        'macanbo',
        'anh',
        'macongchuc',
        'sunghiep',
        'tencanbo',
        'tenkhac',
        'ngaysinh',
        'gioitinh',
        'dantoc',
        'tongiao',
        'lvtd',//nơi công tác
        'lvhd',//lĩnh vực hoạt động
        'socmnd',
        'ngaycap',
        'noicap',
        'sotk',
        'tennganhang',
        'madv',
        //Thông tin lương hiện tại
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'heso',
        'hesobl',
        'hesopc',
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
        'pclade', //làm đêm
        'pcud61', //ưu đãi theo tt61
        'pcxaxe', //điện thoại
        'pcdith', //điện thoại
        'luonghd', //lương hợp đồng (số tiền)
        'pcctp',
        'pctaicu',
        'tnntungay',
        'ttnndenngay',
        'mact',
        'theodoi',
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
        'sodinhdanhcanhan',
        'pclaunam',
        'pcdp',//thêm phụ cấp dân phòng
    ];
}
