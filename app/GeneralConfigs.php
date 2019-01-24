<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralConfigs extends Model
{
    protected $table = 'general_configs';
    protected $fillable = [
        'id',
        'tuoinu',
        'tuoinam',
        'luongcb',
        'tinh',
        'huyen',
        'thongbao',
        'tg_hetts',
        'tg_xetnl'
    ];
    //ALTER TABLE `general_configs` ADD `thongbao` TEXT NULL AFTER `huyen`;
}
