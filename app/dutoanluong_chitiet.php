<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong_chitiet extends Model
{
    protected $table = 'dutoanluong_chitiet';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'canbo_congtac',//lấy số lượng thực tế tại đơn vị
        'canbo_dutoan',
        'macongtac',
        'mact',
        'luongnb',
        'luonghs',
        'luongbh',
        'luongnb_dt',
        'luonghs_dt',
        'luongbh_dt',
        'ghichu',
    ];
}
