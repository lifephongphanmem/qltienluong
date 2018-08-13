<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong_khoi extends Model
{
    protected $table = 'dutoanluong_khoi';
    protected $fillable = [
        'id',
        'masodv',
        'namns',
        'noidung',
        'ngaylap',
        'nguoilap',
        'ghichu',
        'ngaygui',
        'nguoigui',
        'trangthai',
        'lydo',
        'madv',
        'madvbc',
        'macqcq'
    ];
}
