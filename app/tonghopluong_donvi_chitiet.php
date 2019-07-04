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
        'tonghop',
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
        'giaml',
        'luongtn',
        'stbhxh',
        'stbhyt',
        'stkpcd',
        'stbhtn',
        'ttbh',
        'stbhxh_dv',
        'stbhyt_dv',
        'stpctaicu',
        'stbhtn_dv',
        'ttbh_dv'
    ];
}
    //19/02/2018
    //ALTER TABLE `tonghopluong_donvi_chitiet` ADD `tonghop` VARCHAR(50) NULL DEFAULT 'BANGLUONG' AFTER `linhvuchoatdong`;

    /* 04/07/2019 thêm trường số tiền
     *
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_heso` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_hesobl` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_hesopc` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_vuotkhung` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcct` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pckct` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pck` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pccv` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pckv` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcth` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcdd` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcdh` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcld` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcdbqh` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcudn` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pctn` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pctnn` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcdbn` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcvk` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pckn` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcdang` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pccovu` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pclt` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcd` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pctr` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pctdt` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pctnvk` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcbdhdcu` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcthni` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pclade` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcud61` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcxaxe` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcdith` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_luonghd` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcphth` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pcctp` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;
        ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pctaicu` FLOAT NOT NULL DEFAULT '0' AFTER `pctaicu`;

     *
     * */

