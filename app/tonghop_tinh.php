<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghop_tinh extends Model
{
    protected $table = 'tonghop_tinh';
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
        'ghichu',
        'madv',
        'macqcq',
        'madvbc'
    ];
}
