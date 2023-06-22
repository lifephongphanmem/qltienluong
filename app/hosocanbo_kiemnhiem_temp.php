<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo_kiemnhiem_temp extends Model
{
    protected $table = 'hosocanbo_kiemnhiem_temp';
    protected $fillable = [
        'id',
        'phanloai',
        'mapb',
        'manguonkp',
        'mact',
        'baohiem',
        'macvcq',
        'macanbo',
        'pthuong',
        'heso',
        'hesopc',
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
        'hesobl',
        'pcctp',
        'pctaicu',
        'madv',
        'pclaunam',
        'pcdp',//thêm phụ cấp dân phòng
    ];
}
