<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotruc extends Model
{
    protected $table = 'hosotruc';
    protected $fillable = [
        'id',
        'maso',
        'macanbo',
        'tencanbo',
        'dantoc',
        'tongiao',
        'ngaysinh',
        'gioitinh',
        'heso',
        'madv',

        'vuotkhung',
        'pccv',
        'pcdh',
        'pctn',
        'pcudn',
        'pcud61',
        'pcld',
        'pclade',
        'songaycong',
        'songaytruc',
        'thang',
        'nam'
    ];
    /*chưa update cho Lạng Sơn
     * ALTER TABLE `hosotruc` ADD `macvcq` VARCHAR(30) NULL AFTER `id`, ADD `mapb` VARCHAR(30) NULL AFTER `macvcq`, ADD `mact` VARCHAR(30) NULL AFTER `mapb`, ADD `congtac` VARCHAR(30) NULL AFTER `mact`, ADD `sotien` DOUBLE NOT NULL DEFAULT '0' AFTER `congtac`
    27.08.19
    ALTER TABLE `hosotruc` ADD `vuotkhung` DOUBLE NOT NULL DEFAULT '0' AFTER `heso`, ADD `pccv` DOUBLE NOT NULL DEFAULT '0' AFTER `vuotkhung`, ADD `pcdh` DOUBLE NOT NULL DEFAULT '0' AFTER `pccv`, ADD `pctn` DOUBLE NOT NULL DEFAULT '0' AFTER `pcdh`, ADD `pcudn` DOUBLE NOT NULL DEFAULT '0' AFTER `pctn`, ADD `pcud61` DOUBLE NOT NULL DEFAULT '0' AFTER `pcudn`, ADD `songaycong` DOUBLE NOT NULL DEFAULT '0' AFTER `pcud61`, ADD `songaytruc` DOUBLE NOT NULL DEFAULT '0' AFTER `songaycong`, ADD `thang` VARCHAR(10) NULL AFTER `songaytruc`, ADD `nam` VARCHAR(10) NULL AFTER `thang`;

    * */
}
