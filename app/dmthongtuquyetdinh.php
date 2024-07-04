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
        'cancu',
        'cancundtruoc',//Nghị định trước khi áp dụng
        'namdt',
        'donvibanhanh',
        'ngayapdung',
        'muccu',
        'mucapdung',//mức thay đổi có thể là hệ số hoặc %
        'chenhlech',
        'ghichu',
        'masobaocao',
    ];
}
