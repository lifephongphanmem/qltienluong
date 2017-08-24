<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsdaotao_chitiet extends Model
{
    protected $table = 'dsdaotao_chitiet';
    protected $fillable = [
        'mads',
        'macanbo',
        'mapb',
        'macvcq',
        'noidung',
        'ghichu',
        'trangthai'
    ];
}
