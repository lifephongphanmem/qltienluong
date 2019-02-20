<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_donvi_bangluong extends Model
{
    protected $table = 'tonghopluong_donvi_bangluong';
    protected $fillable = [
        'id',
        'mathdv',
        'mathk',
        'mathh',
        'matht',
        'manguonkp',
        'linhvuchoatdong',//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
        'macongtac',
        'mact',
        'phanloai',
        'macvcq',
        'mapb',
        'thangtl',
        'ngaytl',
        'msngbac',
        'macanbo',
        'tencanbo',
        'stt',
        'luongcoban',
        'soluong',
        'heso',
        'hesopc',
        'hesobl',
        'hesott',
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
        'tonghs',
        'ttl',
        'giaml',
        'luongtn',
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv'
    ];
    /*
     19/02/19
    ALTER TABLE `tonghopluong_donvi_bangluong` ADD `phanloai` VARCHAR(50) NULL DEFAULT 'BANGLUONG' AFTER `mapb`, ADD `thangtl` DOUBLE NOT NULL DEFAULT '0' AFTER `phanloai`, ADD `ngaytl` DOUBLE NOT NULL DEFAULT '0' AFTER `thangtl`;
     * */
}
