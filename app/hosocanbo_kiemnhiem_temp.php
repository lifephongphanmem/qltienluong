<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo_kiemnhiem_temp extends Model
{
    protected $table = 'hosocanbo_kiemnhiem_temp';
    protected $fillable = [
        'id',
        'phanloai',
        'mapb',
        'macvcq',
        'macanbo',
        'heso',
        'hesopc',
        'pcdbn',
        'pctn',
        'pcthni',
        'pckn',
        'pck',
        'madv'
    ];
}
