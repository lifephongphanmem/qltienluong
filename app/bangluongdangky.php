<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluongdangky extends Model
{
    protected $table = 'bangluongdangky';
    protected $fillable = [
        'id',
        'mabl',
        'thang',
        'nam',
        'noidung',
        'ngaylap',
        'nguoilap',
        'ghichu',
        'linhvuchoatdong',
        'manguonkp',
        'phanloai',
        'madv',
        'luongcoban'
    ];
}
