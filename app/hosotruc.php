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
}
