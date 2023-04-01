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
        'songaycong',
        'ngaythanhtoan',
        'madv'
    ];
}
//31.03.2023 thêm trường 'ngaythanhtoan' 
// update hosotamngungtheodoi set ngaythanhtoan = ngaytu where id >0