<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmtieumuc_default extends Model
{
    protected $table = 'dmtieumuc_default';
    protected $fillable = [
        'id',
        'muc',
        'tieumuc',
        'noidung',
        //các điều kiện để lấy dữ liệu (có thể bỏ trống)
        'phanloai',
        'sunghiep',
        'mact',
        'mapc',
        'ghichu'
    ];
    //ALTER TABLE `dmtieumuc_default` ADD `phanloai` VARCHAR(50) NULL DEFAULT 'CHILUONG' AFTER `id`;

//INSERT INTO `dmtieumuc_default` (`id`, `phanloai`, `muc`, `tieumuc`, `noidung`, `sunghiep`, `macongtac`, `mapc`, `ghichu`, `created_at`, `updated_at`) VALUES (NULL, 'BAOHIEM', '6300', '6301', 'Bảo hiểm xã hội', 'ALL', 'ALL', 'stbhxh,stbhxh_dv', NULL, NULL, NULL);
//INSERT INTO `dmtieumuc_default` (`id`, `phanloai`, `muc`, `tieumuc`, `noidung`, `sunghiep`, `macongtac`, `mapc`, `ghichu`, `created_at`, `updated_at`) VALUES (NULL, 'BAOHIEM', '6300', '6302', 'Bảo hiểm y tế', 'ALL', 'ALL', 'stbhyt,stbhyt_dv', NULL, NULL, NULL);
//INSERT INTO `dmtieumuc_default` (`id`, `phanloai`, `muc`, `tieumuc`, `noidung`, `sunghiep`, `macongtac`, `mapc`, `ghichu`, `created_at`, `updated_at`) VALUES (NULL, 'BAOHIEM', '6300', '6303', 'Kinh phí công đoàn', 'ALL', 'ALL', 'stkpcd,stkpcd_dv', NULL, NULL, NULL);
//INSERT INTO `dmtieumuc_default` (`id`, `phanloai`, `muc`, `tieumuc`, `noidung`, `sunghiep`, `macongtac`, `mapc`, `ghichu`, `created_at`, `updated_at`) VALUES (NULL, 'BAOHIEM', '6300', '6304', 'Bảo hiểm thất nghiệp', 'ALL', 'ALL', 'stbhtn,stbhtn_dv', NULL, NULL, NULL);
//INSERT INTO `dmtieumuc_default` (`id`, `phanloai`, `muc`, `tieumuc`, `noidung`, `sunghiep`, `macongtac`, `mapc`, `ghichu`, `created_at`, `updated_at`) VALUES (NULL, 'BAOHIEM', '6300', '6349', 'Các khoản đóng góp khác', 'ALL', 'ALL', 'stbhk,stbhk_dv', NULL, NULL, NULL);
}
