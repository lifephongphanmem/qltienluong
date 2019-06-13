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
        'luongcoban',
        'luongnb_nl',
        'luonghs_nl',
        'luongbh_nl',
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
