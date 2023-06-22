<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chitieubienche extends Model
{
    protected $table = 'chitieubienche';
    protected $fillable = [
        'id',
        'madv',
        'nam',
        'mact',
        'macongtac',
        'ngaylap',//chưa dùng
        'linhvuchoatdong',//chưa dùng
        'soluongduocgiao',
        'soluongcongchuc',
        'soluongvienchuc',
        'soluongbienche',
        'soluongtuyenthem',
        'soluongkhongchuyentrach',
        'soluonguyvien',
        'soluongdaibieuhdnd',
        'ghichu',
        //cán bộ tuyển thêm
        'mact_tuyenthem',
        'pthuong',
        'heso',
        'hesopc',
        'hesobl',
        'vuotkhung',
        'pcct',//dùng để thay thế phụ cấp ghép lớp
        'pckct',//dùng để thay thế phụ cấp bằng cấp cho cán bộ không chuyên trách
        'pck',
        'pccv',
        'pckv',
        'pcth',
        'pcdd',
        'pcdh',
        'pcld',
        'pcdbqh',
        'pcudn',
        'pctn',
        'pctnn',
        'pcdbn',
        'pcvk',//dùng để thay thế phụ cấp Đảng uy viên
        'pckn',
        'pcdang',
        'pccovu',
        'pclt', //lưu thay phụ cấp phân loại xã
        'pcd',
        'pctr',
        'pctdt',
        'pctnvk',
        'pcbdhdcu',
        'pcthni',
        'pclade', //làm đêm
        'pcud61', //ưu đãi theo tt61
        'pcxaxe', //xăng xe
        'pcdith', //điện thoại
        'luonghd', //lương hợp đồng (số tiền)
        'pcctp',//phụ cấp công tác phí
        'pctaicu',//phụ cấp tái ứng cử
        'pclaunam',//công tác lâu năm
        'pcdp',//thêm phụ cấp dân phòng
        'baohiem',
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
    ];
}
//ALTER TABLE `chitieubienche` ADD `soluongtuyenthem` DOUBLE NOT NULL DEFAULT '0' AFTER `soluongdaibieuhdnd`;
/*
ALTER TABLE `chitieubienche` ADD `mact_tuyenthem` VARCHAR(50) NOT NULL AFTER `ghichu`,
ADD `pthuong` DOUBLE NOT NULL DEFAULT '0' AFTER `mact_tuyenthem`,
ADD `heso` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `hesopc` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `hesobl` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `vuotkhung` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcct` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pckct` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pck` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pccv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pckv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcth` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcdd` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcdh` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcld` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcdbqh` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcudn` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pctn` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pctnn` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcdbn` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcvk` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pckn` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcdang` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pccovu` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pclt` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcd` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pctr` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pctdt` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pctnvk` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcbdhdcu` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcthni` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pclade` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcud61` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcxaxe` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcdith` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `luonghd` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pcctp` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pctaicu` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,

ADD `baohiem` TINYINT NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhxh` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhyt` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `kpcd` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhtn` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhtnld` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhxh_dv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhyt_dv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `kpcd_dv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhtn_dv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`,
ADD `bhtnld_dv` DOUBLE NOT NULL DEFAULT '0' AFTER `pthuong`;
 * */