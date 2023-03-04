<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsdonviquanly extends Model
{
    protected $table = 'dsdonviquanly';
    protected $fillable = [
        'id',
        'thang',
        'nam',
        'madv',       
        'macqcq',
        'noidung',
        'ghichu',        
        'tungay',
        'denngay',
    ];
}
//04.03.2023 cập nhật danh đơn vị gửi
//TRUNCATE TABLE dsdonviquanly;
//INSERT INTO dsdonviquanly (madv, nam, macqcq) SELECT distinct madv, nam, macqcq FROM tonghopluong_donvi WHERE trangthai = 'DAGUI';
