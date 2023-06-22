<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosothoicongtac extends Model
{
    protected $table = 'hosothoicongtac';
    protected $fillable = [
        'id',
        'maphanloai',
        'maso',
        'soqd',
        'ngayqd',
        'nguoiky',
        'coquanqd',
        'lydo',
        'ngaynghi',

        'macanbo',
        'tencanbo',
        'mapb',
        'macvcq',
        'heso',
        'hesopc',
        'pcdbn',
        'pctn',
        'pctnn',
        'pck',
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'hesott',
        'hesobl',
        'vuotkhung',
        'pthuong',
        'pcct',
        'pckct',
        'pccv',
        'pckv',
        'pcth',
        'pcdd',
        'pcdh',
        'pcld',
        'pcdbqh',
        'pcudn',
        'pcvk',
        'pckn',
        'pcdang',
        'pccovu',
        'pclt',
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
        'mact',

        'ghichu',
        'madv',
        'pclaunam',
        'pcdp',//thêm phụ cấp dân phòng
    ];
}
