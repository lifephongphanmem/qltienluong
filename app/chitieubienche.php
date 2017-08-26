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
        'soluongduocgiao',
        'soluongbienche',
        'soluongkhongchuyentrach',
        'soluonguyvien',
        'soluongdaibieuhdnd',
        'ghichu'
    ];
}
