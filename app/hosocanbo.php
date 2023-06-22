<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosocanbo extends Model
{
    protected $table = 'hosocanbo';
    protected $fillable = [
        'id',
        'stt',
        'manguonkp',
        'mapb',
        'macvcq',
        'macvd',
        'macanbo',
        'anh',
        'macongchuc',
        'sunghiep',
        'tencanbo',
        'tenkhac',
        'dantoc',
        'tongiao',
        'ngaysinh',
        'gioitinh',
        'socmnd',
        'ngaycap',
        'noicap',
        'sodt',
        'email',
        'sotk',
        'tennganhang',
        'madv',
        'lvtd',//nơi công tác
        //thông tin lương hiện tại
        'msngbac',
        'ngaytu',
        'ngayden',
        'tnntungay',
        'tnndenngay',
        'bac',
        'heso',
        'hesopc',
        'hesobl',
        'hesott',
        'truylinhtungay',
        'truylinhdenngay',
        'vuotkhung',
        'pthuong',
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

        'pclade',
        'pcud61',
        'pcxaxe',
        'pcdith',
        'luonghd',
        'pcphth',
        'pcctp',
        'pctaicu',
        'pcdp',//thêm phụ cấp dân phòng
        'theodoi',
        'mact',
        'baohiem',
        'bhxh',
        'bhyt',
        'bhtn',
        'bhtnld',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'bhtnld_dv',
        'kpcd_dv',
        'nguoiphuthuoc',
        'ngaybc',
        'ngayvao',
        'lvhd',
        'khongnopbaohiem',
        'pclaunam',
        'mucluongbaohiem',

    ];
}

//bangluong_ct_01; bangluong_ct_02; bangluong_ct_03; bangluong_ct_04; bangluong_ct_05; bangluong_ct_06;
//bangluong_ct_07; bangluong_ct_08; bangluong_ct_09; bangluong_ct_10; bangluong_ct_11; bangluong_ct_12;
//dutoanluong_bangluong; dutoanluong_nangluong; nguonkinhphi_nangluong; nguonkinhphi_bangluong
//tonghopluong_donvi_bangluong; tonghopluong_donvi_chitiet;

//hosocanbo; hosocanbo_kiemnhiem; hosocanbo_kiemnhiem_temp; hosodieudong;
//hosothoicongtac; hosotruylinh


/*
ALTER TABLE `bangluong_ct_01` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_02` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_03` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_04` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_05` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_06` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_07` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_08` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_09` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_10` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;

ALTER TABLE `bangluong_ct_11` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_12` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `dutoanluong_bangluong` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `dutoanluong_nangluong` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `nguonkinhphi_nangluong` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `nguonkinhphi_bangluong` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `tonghopluong_donvi_bangluong` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `tonghopluong_donvi_chitiet` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;

ALTER TABLE `bangluong_ct_01` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_02` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_03` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_04` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_05` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_06` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_07` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_08` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_09` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_10` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;

ALTER TABLE `bangluong_ct_11` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `bangluong_ct_12` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `dutoanluong_bangluong` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `dutoanluong_nangluong` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `nguonkinhphi_nangluong` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `nguonkinhphi_bangluong` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `tonghopluong_donvi_bangluong` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `tonghopluong_donvi_chitiet` ADD `st_pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;

ALTER TABLE `hosocanbo` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `hosocanbo_kiemnhiem` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `hosocanbo_kiemnhiem_temp` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `hosodieudong` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `hosothoicongtac` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;
ALTER TABLE `hosotruylinh` ADD `pclaunam` DOUBLE NOT NULL DEFAULT '0' AFTER `pcthni`;

 * */