<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi extends Model
{
    protected $table = 'nguonkinhphi';
    protected $fillable = [
        'id',
        'masodv',
        'masoh',
        'masot',
        'sohieu',
        'manguonkp',
        'noidung',
        'namns',
        'linhvuchoatdong',
        'nhucau',
        'luongphucap',
        'daibieuhdnd',
        'nghihuu',
        'canbokct',
        'uyvien',
        'boiduong',
        'thunhapthap',
        'diaban',
        'tinhgiam',
        'nghihuusom',
        'nguonkp',
        'tietkiem',
        'hocphi',
        'vienphi',
        'nguonthu',
        'madv',
        'macqcq',
        'madvbc',
        'maphanloai',
        'trangthai',
        'ngayguidv',
        'nguoiguidv',
        'ngayguih',
        'nguoiguih',
        'lydo'
    ];
}
