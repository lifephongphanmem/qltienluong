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
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        'tg_hetts',
        'tg_xetnl'
    ];
}
