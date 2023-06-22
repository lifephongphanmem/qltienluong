<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghop_tinh_diaban extends Model
{
    protected $table = 'tonghop_tinh_diaban';
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
        'pcdp',//thêm phụ cấp dân phòng
        'tonghs',

        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv'
    ];
}
