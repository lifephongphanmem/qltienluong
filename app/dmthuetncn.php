<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmthuetncn extends Model
{
    protected $table = 'dmthuetncn';
    protected $fillable = [
        'id',
        'sohieu',
        'tenttqd',
        'donvibanhanh',
        'ngayapdung',
        'banthan',//mức giảm trừ bản thân
        'phuthuoc',//mức giảm trừ của người phụ thuộc
        'ghichu',
    ];
}
