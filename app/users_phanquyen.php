<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class users_phanquyen extends Model
{
    protected $table = 'users_phanquyen';
    protected $fillable = [
        'id',
        'username',
        'machucnang',
        'phanquyen', //phân quyền chung để lọc
        'danhsach', //phân quyền; nếu 2 chức năng còn lại true => mặc định true
        'thaydoi',
        'hoanthanh',
        'ghichu',
    ];
}
