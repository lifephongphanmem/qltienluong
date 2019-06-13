<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotruc extends Model
{
    protected $table = 'hosotruc';
    protected $fillable = [
        'id',
        'macanbo',
        'tencanbo',
        'dantoc',
        'tongiao',
        'ngaysinh',
        'gioitinh',
        'heso',
        'madv'
    ];
    /*chưa update cho Lạng Sơn
     * ALTER TABLE `hosotruc` ADD `macvcq` VARCHAR(30) NULL AFTER `id`, ADD `mapb` VARCHAR(30) NULL AFTER `macvcq`, ADD `mact` VARCHAR(30) NULL AFTER `mapb`, ADD `congtac` VARCHAR(30) NULL AFTER `mact`, ADD `sotien` DOUBLE NOT NULL DEFAULT '0' AFTER `congtac`
     * */
}
