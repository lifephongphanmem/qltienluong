<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo_temp extends Model
{
    protected $table = 'hosocanbo_temp';
    protected $fillable = [
        'mapb',
        'macvcq',
        'macvd',
        'macanbo',
        'tencanbo',
        'ngaysinh',
        'madv',
        //Thông tin lương hiện tại
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'heso',
        'vuotkhung',
        'pthuong',
        //Các trường thông tin phụ cho chức năng: bồi dưỡng, nghỉ hưu, nâng lương, ...
        'mads',
        'phanloai',
        'noidung',
        'msngbac_cu',
        'ngaytu_cu',
        'ngayden_cu',
        'bac_cu',
        'heso_cu',
        'vuotkhung_cu',
        'pthuong_cu'
    ];
}
