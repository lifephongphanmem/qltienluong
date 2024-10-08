<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong extends Model
{
    protected $table = 'dutoanluong';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'namns',
        'luongnb_dt',
        'luonghs_dt',
        'luongbh_dt',
        'luongcoban',
        'luongnb_nl',
        'luonghs_nl',
        'luongbh_nl',
        'madv',
        'madvbc',
        'macqcq',
        'trangthai',
        'ngayguidv',
        'nguoiguidv',
        'ngayguih',
        'nguoiguih',
        'lydo',
        'phanloai',//DUTOAN; DUUOC
        'phanloaixa',
        'phanloaixa_heso',
        'sothonxabiengioi',
        'sothonxabiengioi_heso',
        'sothonxakhokhan',
        'sothonxakhokhan_heso',
        'sothonxa350ho',
        'sothonxa350ho_heso',
        'sotodanpho500ho',
        'sotodanpho500ho_heso',
        'sothonxatrongdiem',
        'sothonxatrongdiem_heso',
        'sochuyentuthon350hgd',
        'sochuyentuthon350hgd_heso',
        'sothonxakhac',
        'sothonxakhac_heso',
        'sotodanphokhac',
        'sotodanphokhac_heso',
        'sothonxaloai1',
        'sothonxaloai1_heso',
        'socanbotangthem' //Số cán bộ tăng thêm theo nd33/2024
    ];
}
