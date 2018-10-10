<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_donvi_chitiet extends Model
{
    protected $table = 'tonghopluong_donvi_chitiet';
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
        'luongcoban',
        'soluong',
        'heso',
        'hesopc',
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
        'giaml',
        'luongtn',
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv'
    ];
}
