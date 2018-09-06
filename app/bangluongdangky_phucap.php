<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluongdangky_phucap extends Model
{
    protected $table = 'bangluongdangky_phucap';
    protected $fillable = [
        'id',
        'mabl',
        'maso',//mã phụ cấp or mã chức vụ
        'ten', //tên phụ cấp or mã chức vụ
        'macanbo',
        'tencanbo',
        'phanloai',
        'congthuc',
        'heso_goc',
        'heso',
        'sotien',
        'stbhxh',
        'stbhyt',
        'stkpcd',
        'stbhtn',
        'ttbh',
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv',
        'ghichu'
    ];
}
