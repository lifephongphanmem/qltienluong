<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_nangluong extends Model
{
    protected $table = 'nguonkinhphi_nangluong';
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
/*
 ALTER TABLE `nguonkinhphi_nangluong` ADD `stbhyt` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `nguonkinhphi_nangluong` ADD `stbhtn` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `nguonkinhphi_nangluong` ADD `stbhxh` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `nguonkinhphi_nangluong` ADD `stkpcd` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
ALTER TABLE `nguonkinhphi_nangluong` ADD `ttbh` FLOAT NOT NULL DEFAULT '0' AFTER `luongtn`;
 */
