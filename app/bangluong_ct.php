<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_ct extends Model
{
    protected $table = 'bangluong_ct';
    protected $fillable = [
        'id',
        'mabl',
        'macvcq',
        'mapb',
        'mact',
        'msngbac',
        'stt',
        'phanloai',
        'congtac',
        'macanbo',
        'tencanbo',
        'macongchuc',
        'luongcoban',
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
        'thuetn',
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
        'ngaytl',
        'songaytruc',
//lưu theo số tiền
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
        //lưu tỷ lệ bảo hiểm (đã quy về hệ số)
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv'
    ];
}
/*
 *
        ALTER TABLE `bangluong_ct` ADD `st_heso` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_hesobl` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_hesopc` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_vuotkhung` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcct` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pckct` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pck` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pccv` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pckv` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcth` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcdd` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcdh` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcld` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcdbqh` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcudn` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pctn` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pctnn` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcdbn` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcvk` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pckn` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcdang` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pccovu` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pclt` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcd` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pctr` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pctdt` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pctnvk` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcbdhdcu` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcthni` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pclade` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcud61` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcxaxe` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcdith` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_luonghd` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;
        ALTER TABLE `bangluong_ct` ADD `st_pcphth` FLOAT NOT NULL DEFAULT '0' AFTER `kpcd_dv`;

    ALTER TABLE `bangluong_ct` CHANGE  `st_heso` `st_heso` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_hesobl` `st_hesobl` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_hesopc` `st_hesopc` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_vuotkhung` `st_vuotkhung` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcct` `st_pcct` DOUBLE NOT NULL DEFAULT '0' ;
        ALTER TABLE `bangluong_ct` CHANGE  `st_pckct` `st_pckct` DOUBLE NOT NULL DEFAULT '0';

    ALTER TABLE `bangluong_ct` CHANGE  `st_pck` `st_pck` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pccv` `st_pccv` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pckv` `st_pckv` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcth` `st_pcth` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcdd` `st_pcdd` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcdh` `st_pcdh` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcld` `st_pcld` DOUBLE NOT NULL DEFAULT '0';

    ALTER TABLE `bangluong_ct` CHANGE  `st_pcdbqh` `st_pcdbqh` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcudn` `st_pcudn` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pctn` `st_pctn` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pctnn` `st_pctnn` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcdbn` `st_pcdbn` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcvk` `st_pcvk` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pckn` `st_pckn` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcdang` `st_pcdang` DOUBLE NOT NULL DEFAULT '0';

    ALTER TABLE `bangluong_ct` CHANGE  `st_pccovu` `st_pccovu` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pclt` `st_pclt` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcd` `st_pcd` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pctr` `st_pctr` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pctdt` `st_pctdt` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pctnvk` `st_pctnvk` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcbdhdcu` `st_pcbdhdcu` DOUBLE NOT NULL DEFAULT '0';

    ALTER TABLE `bangluong_ct` CHANGE  `st_pcthni` `st_pcthni` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pclade` `st_pclade` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcud61` `st_pcud61` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcxaxe` `st_pcxaxe` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcdith` `st_pcdith` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_luonghd` `st_luonghd` DOUBLE NOT NULL DEFAULT '0';
        ALTER TABLE `bangluong_ct` CHANGE  `st_pcphth` `st_pcphth` DOUBLE NOT NULL DEFAULT '0';
*/