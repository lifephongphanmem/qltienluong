<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong extends Model
{
    protected $table = 'dutoanluong';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'namns',
        'luongnb_dt',
        'luonghs_dt',
        'luongbh_dt',
        'madv',
        'madvbc',
        'macqcq',
        'trangthai',
        'ngayguidv',
        'nguoiguidv',
        'ngayguih',
        'nguoiguih',
        'lydo'
    ];
}
