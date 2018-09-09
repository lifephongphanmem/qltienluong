<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsnangthamnien extends Model
{
    protected $table = 'dsnangthamnien';
    protected $fillable = [
        'id',
        'manl',
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
