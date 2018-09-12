<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsnangthamnien_ct extends Model
{
    protected $table = 'dsnangthamnien_chitiet';
    protected $fillable = [
        'id',
        'manl',
        'macanbo',
        'phanloai',
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'heso',
        'vuotkhung',
        'pthuong',
        'hesott',
        'truylinhtungay',
        'truylinhdenngay',
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
        'pctdt',
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'ghichu'
    ];
}
