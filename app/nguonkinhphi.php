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
        'luongchuyentrach', //thừa
        'luongkhongchuyentrach', //thừa
        'tongnhucau1', //tổng nhu cầu kinh trước 1 năm
        'tongnhucau2', //tổng nhu cầu kinh trước 2 năm
        //Báo cáo nhu cầu kinh phí mẫu Vạn Ninh
        'tongsonguoi1', //mẫu 2b
        'quy1_1', //mẫu 2b
        'quy1_2', //mẫu 2b
        'quy1_3', //mẫu 2b
        'tongsonguoi2', //mẫu 2b
        'quy2_1', //mẫu 2b
        'quy2_2', //mẫu 2b
        'quy2_3', //mẫu 2b
        'tongsonguoi3', //mẫu 2b
        'tongsonguoi2015', //mẫu 2đ
        'tongsonguoi2017', //mẫu 2đ
        'quyluong', //mẫu 2đ
        'tongsodonvi1', //mẫu 2e
        'tongsodonvi2', //mẫu 2e
        'quy_tuchu' //mẫu 2e
    ];
}
