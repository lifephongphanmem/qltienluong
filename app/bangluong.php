<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong extends Model
{
    protected $table = 'bangluong';
    protected $fillable = [
        'id',
        'mabl',
        'thang',
        'nam',
        'noidung',
        'ngaylap',
        'nguoilap',
        'ghichu',
        'linhvuchoatdong', //Phân loại xã phường ko cần chọn lĩnh vực hoạt động
        'manguonkp',
        'phantramhuong',
        'madv',
        'luongcoban'
    ];


}
