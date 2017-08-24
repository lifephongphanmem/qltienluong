<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluongphucap extends Model
{
    protected $table = 'bangluongphucap';
    protected $fillable = [
        'id',
        'mabl',
        'macanbo',
        'mapc',
        'hesopc',
        'thanhtien',
        'baohiem',
        'ghichu'
    ];
}
