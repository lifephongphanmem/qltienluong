<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo_kiemnhiem extends Model
{
    protected $table = 'hosocanbo_kiemnhiem';
    protected $fillable = [
        'id',
        'phanloai',
        'mapb',
        'mact',
        'baohiem',
        'macvcq',
        'macanbo',
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
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'madv'
    ];
}
