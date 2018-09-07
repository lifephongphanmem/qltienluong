<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotamngungtheodoi extends Model
{
    protected $table = 'hosotamngungtheodoi';
    protected $fillable = [
        'id',
        'maso',
        'macanbo',
        'tencanbo',//chưa dùng
        'soqd',//chưa dùng
        'ngayqd',//chưa dùng
        'nguoiky', //chưa dùng
        'coquanqd',//chưa dùng
        'maphanloai',
        'noidung',
        'ngaytu',
        'ngayden',
        'songaynghi',
        'madv'
    ];
}
