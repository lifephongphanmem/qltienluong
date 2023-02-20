<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangthuyetminh extends Model
{
    protected $table = 'bangthuyetminh';
    protected $fillable = [
        'id',
        'mathuyetminh',
        'phanloai', 
        'thang',
        'nam',
        'noidung',
        'ghichu',
        'madv',
    ];
}
