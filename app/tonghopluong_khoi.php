<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_khoi extends Model
{
    protected $table = 'tonghopluong_khoi';
    protected $fillable = [
        'id',
        'mathdv',
        'thang',
        'nam',
        'noidung',
        'ngaylap',
        'nguoilap',
        'nguoigui',
        'trangthai',
        'phanloai',
        'lydo',
        'ghichu',
        'madv',
        'macqcq',
        'madvbc'
    ];
}
