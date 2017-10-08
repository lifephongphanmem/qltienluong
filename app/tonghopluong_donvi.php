<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_donvi extends Model
{
    protected $table = 'tonghopluong_donvi';
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
        'ghichu',
        'madv',
        'macqcq',
        'madvbc'
    ];
}
