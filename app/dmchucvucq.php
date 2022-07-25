<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmchucvucq extends Model
{
    protected $table = 'dmchucvucq';
    protected $fillable = [
        'id',
        'macvcq',
        'tencv',
        'tenvt',
        'ghichu',
        'sapxep',
        'madv',
        'phannhom',
        'maphanloai',
        'mact',
        'ttdv'
    ];
}
