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
        'pcctp',
        'pctaicu',

        'st_heso',
        'st_hesobl',
        'st_hesopc',
        'st_vuotkhung',
        'st_pcct',
        'st_pckct',
        'st_pck',
        'st_pccv',
        'st_pckv',
        'st_pcth',
        'st_pcdd',
        'st_pcdh',
        'st_pcld',
        'st_pcdbqh',
        'st_pcudn',
        'st_pctn',
        'st_pctnn',
        'st_pcdbn',
        'st_pcvk',
        'st_pckn',
        'st_pcdang',
        'st_pccovu',
        'st_pclt',
        'st_pcd',
        'st_pctr',
        'st_pctdt',
        'st_pctnvk',
        'st_pcbdhdcu',
        'st_pcthni',
        'st_pclade',
        'st_pcud61',
        'st_pcxaxe',
        'st_pcdith',
        'st_luonghd',
        'st_pcphth',
        'st_pcctp',
        'st_pctaicu',

        'tonghs',
        'ttl',
        'giaml',
        'luongtn',
        'stbhxh',
        'stbhyt',
        'stkpcd',
        'stbhtn',
        'ttbh',
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv',
        'pclaunam',
        'st_pclaunam',
                        //thêm phụ cấp dân phòng
                        'pcdp',
                        'st_pcdp'
    ];
    /*
     19/02/19
    ALTER TABLE `tonghopluong_donvi_bangluong` ADD `phanloai` VARCHAR(50) NULL DEFAULT 'BANGLUONG' AFTER `mapb`, ADD `thangtl` DOUBLE NOT NULL DEFAULT '0' AFTER `phanloai`, ADD `ngaytl` DOUBLE NOT NULL DEFAULT '0' AFTER `thangtl`;
     * */
}
