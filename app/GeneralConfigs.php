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
        'thangnu',
        'thangnam',
        'luongcb',
        'tinh',
        'huyen',
        'thongbao',
        'tg_hetts',
        'tg_xetnl',
        'kytuthapphan',
        'kytunhom',
        'tg_xetnl',
        'ipf1',
        'ipf2',
        'ipf3',
        'ipf4',
        'ipf5',
        'mact_tuyenthem'
    ];
    //ALTER TABLE `general_configs` ADD `thongbao` TEXT NULL AFTER `huyen`;
}
