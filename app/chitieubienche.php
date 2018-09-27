<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chitieubienche extends Model
{
    protected $table = 'chitieubienche';
    protected $fillable = [
        'id',
        'madv',
        'nam',
        'mact',
        'macongtac',
        'ngaylap',//chưa dùng
        'linhvuchoatdong',//chưa dùng
        'soluongduocgiao',
        'soluongbienche',
        'soluongkhongchuyentrach',
        'soluonguyvien',
        'soluongdaibieuhdnd',
        'ghichu'
    ];
}
