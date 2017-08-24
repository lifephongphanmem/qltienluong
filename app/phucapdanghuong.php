<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phucapdanghuong extends Model
{
    protected $table = 'phucapdanghuong';
    protected $fillable = [
        'id',
        'ngaytu',
        'ngayden',
        'macanbo',
        'mapc',
        'hesopc',
        'pthuong',
        'hinhthuc',
        'baohiem',
        'ghichu',
        'madv'
    ];
}
