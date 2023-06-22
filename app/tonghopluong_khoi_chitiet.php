<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_khoi_chitiet extends Model
{
    protected $table = 'tonghopluong_khoi_chitiet';
    protected $fillable = [
        'id',
        'mathdv',
        'manguonkp',
        'linhvuchoatdong',//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
        'macongtac',
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
