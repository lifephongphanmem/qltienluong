<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghopluong_tinh extends Model
{
    protected $table = 'tonghopluong_tinh';
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
