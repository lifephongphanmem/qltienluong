<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosoluanchuyen extends Model
{
    protected $table = 'hosoluanchuyen';
    protected $fillable = [
        'id',
        'macanbo',
        'phanloai',
        'ngaylc',
        'madv',
        'mapb',
        'macvcq',
        'soqd',
        'ngayqd',
        'nguoiky'
    ];
}
