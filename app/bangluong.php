<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong extends Model
{
    protected $table = 'bangluong';
    protected $fillable = [
        'id',
        'mabl',
        'mabl_trichnop',
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
        'phucapluusotien',
        'madv',
        'luongcoban',
        'maquy',
        'tenquy'
    ];
}
//DELETE FROM `bangluong_ct` WHERE mabl not in (SELECT mabl FROM bangluong);

/*
    UPDATE bangluong_ct
    INNER JOIN bangluong ON bangluong_ct.mabl = bangluong.mabl
    SET bangluong_ct.manguonkp = bangluong.manguonkp
    WHERE bangluong.phanloai ='BANGLUONG';

INSERT INTO  bangluong_ct_01 SELECT * FROM bangluong_ct WHERE thang = '01';
INSERT INTO  bangluong_ct_02 SELECT * FROM bangluong_ct WHERE thang = '02';
INSERT INTO  bangluong_ct_03 SELECT * FROM bangluong_ct WHERE thang = '03';
INSERT INTO  bangluong_ct_04 SELECT * FROM bangluong_ct WHERE thang = '04';
INSERT INTO  bangluong_ct_05 SELECT * FROM bangluong_ct WHERE thang = '05';
INSERT INTO  bangluong_ct_06 SELECT * FROM bangluong_ct WHERE thang = '06';

INSERT bangluong_ct_07 SELECT * FROM bangluong_ct WHERE thang = '07';
INSERT bangluong_ct_08 SELECT * FROM bangluong_ct WHERE thang = '08';
INSERT bangluong_ct_09 SELECT * FROM bangluong_ct WHERE thang = '09';
INSERT bangluong_ct_10 SELECT * FROM bangluong_ct WHERE thang = '10';
INSERT bangluong_ct_11 SELECT * FROM bangluong_ct WHERE thang = '11';
INSERT bangluong_ct_12 SELECT * FROM bangluong_ct WHERE thang = '12';


 */
