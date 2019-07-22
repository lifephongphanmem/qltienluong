<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosodaotao extends Model
{
    protected $table = 'hosodaotao';
    protected $fillable = [
        'id',
        'macanbo',
        'phanloai',
        'ngaytu',
        'ngayden',
        'tencoso',
        'chuyennganh',
        'hinhthuc',
        'vanbang',
        'ghichu',
        'madv'
    ];
}
