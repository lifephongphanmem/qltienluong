<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsnangluong extends Model
{
    protected $table = 'dsnangluong';
    protected $fillable = [
        'id',
        'manl',
        'maphanloai',
        'loaids',
        'soqd',
        'ngayqd',
        'nguoiky',
        'coquanqd',
        'noidung',
        'ngayxet',
        'kemtheo',
        'trangthai',
        'madv'
    ];
}
