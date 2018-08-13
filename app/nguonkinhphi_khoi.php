<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_khoi extends Model
{
    protected $table = 'nguonkinhphi_khoi';
    protected $fillable = [
        'id',
        'masodv',
        'sohieu',
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
