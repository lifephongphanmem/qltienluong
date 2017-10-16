<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosoluong extends Model
{
    protected $table = 'hosoluong';
    protected $fillable = [
        'id',
        'manl',
        'macanbo',
        'phanloai',
        'msngbac',
        'ngaytu',
        'ngayden',
        'bac',
        'heso',
        'vuotkhung',
        'pthuong',
        'ketqua',
        'madv'
    ];
}
