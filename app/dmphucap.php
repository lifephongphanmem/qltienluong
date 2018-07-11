<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphucap extends Model
{
    protected $table = 'dmphucap';
    protected $fillable = [
        'id',
        'mapc',
        'tenpc',
        'baohiem',//chưa dùng
        'form', //tiêu đề trên Form
        'report', //tiêu đề trên Report
        'phanloai',
        'congthuc',
        'ghichu'
    ];
}
