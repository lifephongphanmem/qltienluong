<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluongdangky_ct extends Model
{
    protected $table = 'bangluongdangky_ct';
    protected $fillable = [
        'id',
        'mabl',
        'macvcq',
        'mapb',
        'mact',
        'msngbac',
        'stt',
        'phanloai',
        'macanbo',
        'tencanbo',
        'macongchuc',
        'heso',
        'hesopc',
        'hesott',
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
        'pctdt',
        'ptbhxh',
        'ptbhyt',
        'ptkpcd',
        'ptbhtn',
        'pcbdhdcu',
        'pctnvk',
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
        'bhct',
        'tluong',
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
        'gttncn',
        'luongtn',
        'thangtl',
        //lưu hệ số gốc 1 số loại pc tính %
        'hs_vuotkhung',
        'hs_pctnn',
        'hs_pccovu',
        'hs_pcud61',
        'hs_pcudn',
        'st_pcctp',
        'pcctp',
        'pctaicu',
        'st_pctaicu',
                        //thêm phụ cấp dân phòng
                        'pcdp',
                        'st_pcdp'
    ];

    /*
     12/03/19
        ALTER TABLE `bangluongdangky_ct` ADD `hs_vuotkhung` FLOAT NOT NULL DEFAULT '0' AFTER `ttbh_dv`;
        ALTER TABLE `bangluongdangky_ct` ADD `hs_pctnn` FLOAT NOT NULL DEFAULT '0' AFTER `ttbh_dv`;
        ALTER TABLE `bangluongdangky_ct` ADD `hs_pccovu` FLOAT NOT NULL DEFAULT '0' AFTER `ttbh_dv`;
        ALTER TABLE `bangluongdangky_ct` ADD `hs_pcud61` FLOAT NOT NULL DEFAULT '0' AFTER `ttbh_dv`;
        ALTER TABLE `bangluongdangky_ct` ADD `hs_pcudn` FLOAT NOT NULL DEFAULT '0' AFTER `ttbh_dv`;

        UPDATE bangluongdangky_ct SET hs_vuotkhung = vuotkhung * 100 / heso WHERE vuotkhung > 0 and hs_vuotkhung = 0
     * */
}
