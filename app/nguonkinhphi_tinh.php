<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_tinh extends Model
{
    protected $table = 'nguonkinhphi_tinh';
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
