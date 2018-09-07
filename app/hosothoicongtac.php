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
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'mact',

        'ghichu',
        'madv'
    ];
}