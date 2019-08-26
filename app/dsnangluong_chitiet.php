<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsnangluong_chitiet extends Model
{
    protected $table = 'dsnangluong_chitiet';
    protected $fillable = [
        'id',
        'manl',
        'macanbo',
        'phanloai',
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'heso',
        'vuotkhung',
        'pthuong',
        'hesott',
        'truylinhtungay',
        'truylinhdenngay',
        'manguonkp',
        'luongcoban',
        'thangtl',
        'ngaytl',
        'ghichu',
        'msngbac_cu',
        'bac_cu',
        'heso_cu',
        'vuotkhung_cu',
        /*
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
        */
    ];
}

/*
 * ALTER TABLE `dsnangluong_chitiet` ADD `msngbac_cu` VARCHAR(50) NULL AFTER `ngaytl`, ADD `bac_cu` INT NOT NULL DEFAULT '1' AFTER `msngbac_cu`, ADD `heso_cu` DOUBLE NOT NULL DEFAULT '0' AFTER `bac_cu`, ADD `vuotkhung_cu` DOUBLE NOT NULL DEFAULT '0' AFTER `heso_cu`
 * */
