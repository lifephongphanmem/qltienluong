<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmthongtuquyetdinh extends Model
{
    protected $table = 'dmthongtuquyetdinh';
    protected $fillable = [
        'id',
        'sohieu',
        'tenttqd',
        'donvibanhanh',
        'ngayapdung',
        'muccu',
        'mucapdung',//mức thay đổi có thể là hệ số hoặc %
        'chenhlech',
        'ghichu'
    ];
}
