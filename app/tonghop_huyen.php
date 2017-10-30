<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tonghop_huyen extends Model
{
    protected $table = 'tonghop_huyen';
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
