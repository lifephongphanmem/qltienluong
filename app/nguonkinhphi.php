<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi extends Model
{
    protected $table = 'nguonkinhphi';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
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
        'baohiem',
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
        'lydo',
        //thêm mới theo thông tư 46/2019
        'tietkiem1', //trước 1 năm
        'tietkiem2', //trước 2 năm
        'thuchien1', //trước 1 năm
        'dutoan',
        'dutoan1', //trước 1 năm
        'bosung',
        'caicach',
        'kpthuhut',
        'kpuudai',
        'luongchuyentrach',
        'luongkhongchuyentrach',//thừa
    ];
}
