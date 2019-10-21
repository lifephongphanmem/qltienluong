<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosophucap extends Model
{
    protected $table = 'hosophucap';
    protected $fillable = [
        'id',
        'maso',
        'manl',//ma nâng lương ng bậc, tnn
        'mapc',
        'macanbo',
        'macvcq',
        'mact',
        'maphanloai',//phân loại công tác: CONGTAC, DBHDND, QUANSU, ...
        'phanloai',//phân loai tính phụ cấp: số tiền, hệ số, phần trăm, ...
        'congthuc',
        'ngaytu',
        'ngayden',
        'msngbac',
        'bac',
        'heso',
        'ghichu',
        'madv',
    ];
    /*DELETE FROM `hosocanbo` WHERE madv not in (SELECT madv FROM dmdonvi);
     * Hệ số lương
     *
     *
     */
}
