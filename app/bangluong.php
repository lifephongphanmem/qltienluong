<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong extends Model
{
    protected $table = 'bangluong';
    protected $fillable = [
        'id',
        'mabl',
        'thang',
        'nam',
        'noidung',
        'ngaylap',
        'nguoilap',
        'ghichu',
        'linhvuchoatdong', //Phân loại xã phường ko cần chọn lĩnh vực hoạt động
        'manguonkp',
        'phanloai',
        'phantramhuong',
        'phucaploaitru',
        'madv',
        'luongcoban'
    ];
}
//DELETE FROM `bangluong_ct` WHERE mabl not in (SELECT mabl FROM bangluong);

/*
    UPDATE bangluong_ct
    INNER JOIN bangluong ON bangluong_ct.mabl = bangluong.mabl
    SET bangluong_ct.manguonkp = bangluong.manguonkp
    WHERE bangluong.phanloai ='BANGLUONG';
 */
