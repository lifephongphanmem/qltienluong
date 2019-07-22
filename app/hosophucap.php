<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosophucap extends Model
{
    protected $table = 'hosophucap';
    protected $fillable = [
        'id',
        'stt',//theo danh mục
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
        'baohiem', //phân biệt xem loại phụ cấp này có phải nộp bảo hiểm ko => có thể bỏ khi nhân trực tiếp với hệ số A*0 = 0
        'bhxh',
        'bhyt',
        'kpcd',
        'bhtn',
        'bhtnld',
        'bhxh_dv',
        'bhyt_dv',
        'kpcd_dv',
        'bhtn_dv',
        'bhtnld_dv',
        'ghichu',
        'madv'//dùng update thông tin, xong xóa bỏ
    ];
    /*DELETE FROM `hosocanbo` WHERE madv not in (SELECT madv FROM dmdonvi);
     * Hệ số lương
     *
     *
     */
}
