<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong_tinh extends Model
{
    protected $table = 'dutoanluong_tinh';
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
