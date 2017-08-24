<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsdaotao extends Model
{
    protected $table = 'dsdaotao';
    protected $fillable = [
        'id',
        'mads',
        'soqd',
        'ngayqd',
        'nguoiky',
        'coquanqd',
        'noidung',
        'ngaytu',
        'ngayden',
        'nguonkinhphi',
        'ghichu',
        'trangthai',
        'dinhkem',
        'madv'
    ];
}
