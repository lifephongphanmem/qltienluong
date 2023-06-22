<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotruylinh extends Model
{
    protected $table = 'hosotruylinh';
    protected $fillable = [
        'id',
        'maso',
        'stt',
        'macvcq',
        'mapb',
        'mact',
        'maphanloai',//phân loại truy lĩnh
        'phanloai', //phân loại kiêm nhiệm
        'macanbo',
        'tencanbo',
        'soqd',//chưa dùng
        'ngayqd',//chưa dùng
        'nguoiky',//chưa dùng
        'coquanqd',//chưa dùng
        'mabl',
        //'manguonkp',
        //'luongcoban',
        'thangtl',
        'ngaytl',
        'ngaytu',
        'ngayden',
        'noidung',
        'madv',
        'msngbac',
        'pthuong',
        'hesott',
        //chưa dùng, cho trường hợp truy lĩnh cả phụ cấp
        'heso',
        'hesopc',
        'hesobl',
        'vuotkhung',
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
        'pcthni',
        'pcbdhdcu',
        'pclade',
        'pcud61',
        'pcxaxe',
        'pcdith',
        'luonghd',
        'pcphth',
        'pcctp',
        'pctdt',
        'pclaunam',
        'pcdp',//thêm phụ cấp dân phòng
    ];
}
//UPDATE `hosotruylinh` SET `mabl` = NULL WHERE mabl not in (SELECT mabl FROM bangluong);
