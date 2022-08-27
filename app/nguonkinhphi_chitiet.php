<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_chitiet extends Model
{
    protected $table = 'nguonkinhphi_chitiet';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'sohieu',
        'manguonkp',
        'macongtac',
        'mact',
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
        'luongchuyentrach',//thừa
        'luongkhongchuyentrach',//thừa
        'tongnhucau1', //tổng nhu cầu kinh trước 1 năm
        'tongnhucau2', //tổng nhu cầu kinh trước 2 năm

    ];
}
