<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaicongtac_baohiem extends Model
{
    protected $table = 'dmphanloaicongtac_baohiem';
    protected $fillable = [
        'id',
        'madv',
        'macongtac',
        'mact',
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        'sapxep',
        'ghichu'
    ];
}
