<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo_kiemnhiem extends Model
{
    protected $table = 'hosocanbo_kiemnhiem';
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
        'pctnn',
        'pck'
    ];
}
