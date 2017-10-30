<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_donvi_diaban extends Model
{
    //Bảng dành cho đơn vị KVXP tổng hợp lương theo thôn, tổ dân phố
    protected $table = 'tonghopluong_donvi_diaban';
    protected $fillable = [
        'id',
        'mathdv',
        'madiaban',
        'luongcoban',
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
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'tonghs',

        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv'
    ];

}
