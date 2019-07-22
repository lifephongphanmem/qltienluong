<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocongtac extends Model
{
    protected $table = 'hosocongtac';
    protected $fillable = [
        'id',
        'macanbo',
        'ngaytu',
        'ngayden',
        'noidung',
        'phanloai',
        'linhvuc',
        'noiden',
        'madv'
    ];
}