<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotruylinh_nguon_temp extends Model
{
    protected $table = 'hosotruylinh_nguon_temp';
    protected $fillable = [
        'id',
        'maso',
        'manguonkp',
        'luongcoban',
        'macanbo',
        'madv',
    ];
}
