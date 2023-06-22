<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong_nangluong extends Model
{
    protected $table = 'dutoanluong_nangluong';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'thang',
        'nam',
        'manguonkp',
        'linhvuchoatdong',//Phân loại xã phường ko cần chọn lĩnh vực hoạt động
        'congtac',//đang công tác, nghỉ hưu
        'macongtac',
        'maphanloai',
        'mact',
        'macvcq',
        'mapb',
        'msngbac',
        'macanbo',
        'tencanbo',
        'stt',
        'luongcoban',
        'heso',
        'hesobl',
        'hesopc',
        'vuotkhung',
        'pcct',
        'pckct',
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
        'pcvk',
        'pckn',
        'pcdang',
        'pccovu',
        'pclt',
        'pcd',
        'pctr',
        'pctdt',
        'pctnvk',
        'pcbdhdcu',
        'pcthni',

        //thêm vào chưa dùng => các loại phụ cấp ko tổng hợp
        'pclade', //làm đêm
        'pcud61', //ưu đãi theo tt61
        'pcxaxe', //điện thoại
        'pcdith', //điện thoại
        'pcphth', //phẫu thuật, thủ thuật
        'luonghd', //đã thêm vào tổng hợp=> ko dự toán =>thừa

        'st_heso',
        'st_hesobl',
        'st_hesopc',
        'st_vuotkhung',
        'st_pcct',
        'st_pckct',
        'st_pck',
        'st_pccv',
        'st_pckv',
        'st_pcth',
        'st_pcdd',
        'st_pcdh',
        'st_pcld',
        'st_pcdbqh',
        'st_pcudn',
        'st_pctn',
        'st_pctnn',
        'st_pcdbn',
        'st_pcvk',
        'st_pckn',
        'st_pcdang',
        'st_pccovu',
        'st_pclt',
        'st_pcd',
        'st_pctr',
        'st_pctdt',
        'st_pctnvk',
        'st_pcbdhdcu',
        'st_pcthni',
        'st_pclade',
        'st_pcud61',
        'st_pcxaxe',
        'st_pcdith',
        'st_luonghd',
        'st_pcphth',
        'pcctp',
        'st_pcctp',
        'pctaicu',
        'st_pctaicu',
        'tonghs',
        'ttl',
        'giaml',
        'luongtn',
        'stbhxh',
        'stbhyt',
        'stkpcd',
        'stbhtn',
        'ttbh',
        'stbhxh_dv',
        'stbhyt_dv',
        'stluonghd',
        'stbhtn_dv',
        'ttbh_dv',
        'pclaunam',
        'st_pclaunam',
                        //thêm phụ cấp dân phòng
                        'pcdp',
                        'st_pcdp'
    ];
}

/*11.06.19
        ALTER TABLE `dutoanluong_nangluong` ADD `st_heso` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_hesobl` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_hesopc` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_vuotkhung` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcct` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pckct` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pck` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pccv` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pckv` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcth` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcdd` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcdh` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcld` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcdbqh` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcudn` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pctn` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pctnn` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcdbn` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcvk` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pckn` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcdang` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pccovu` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pclt` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcd` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pctr` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pctdt` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pctnvk` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcbdhdcu` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcthni` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pclade` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcud61` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcxaxe` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcdith` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_luonghd` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcphth` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `st_pcctp` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;
        ALTER TABLE `dutoanluong_nangluong` ADD `pcctp` FLOAT NOT NULL DEFAULT '0' AFTER `luonghd`;

ALTER TABLE `dutoanluong_nangluong` ADD `stbhyt` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `dutoanluong_nangluong` ADD `stbhtn` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `dutoanluong_nangluong` ADD `stbhxh` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `dutoanluong_nangluong` ADD `stkpcd` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `dutoanluong_nangluong` ADD `ttbh` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
    */
