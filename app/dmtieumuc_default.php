<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmtieumuc_default extends Model
{
    protected $table = 'dmtieumuc_default';
    protected $fillable = [
        'id',
        'muc',
        'tieumuc',
        'noidung',
        //các điều kiện để lấy dữ liệu (có thể bỏ trống)
        'sunghiep',
        'macongtac',
        'mapc',
        'ghichu'
    ];
}
