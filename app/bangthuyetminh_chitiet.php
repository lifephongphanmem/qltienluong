<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangthuyetminh_chitiet extends Model
{
    protected $table = 'bangthuyetminh_chitiet';
    protected $fillable = [
        'id',
        'mathuyetminh',
        'stt',
        
        'noidung',
        'sotien',
        'ghichu',
        'tanggiam',//tăng hoặc giảm
    ];
}

