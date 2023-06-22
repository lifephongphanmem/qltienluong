<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_ct_01 extends Model
{
    protected $table = 'bangluong_ct_01';
    protected $fillable = [
        'id',
        'mabl',
        'manguonkp',//lưu mã nguồn bảng lương truy lĩnh
        'maso',//mã số truy lĩnh
        'macvcq',
        'mapb',
        'mact',
        'msngbac',
        'stt',
        'phanloai',
        'congtac',
        'macanbo',
        'tencanbo',
        'macongchuc',
        'luongcoban',
        'heso',
        'hesopc',
        'hesott',
        'hesobl',
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
        'ptbhxh',
        'ptbhyt',
        'ptkpcd',
        'ptbhtn',
        'pcbdhdcu',
        'pctnvk',
        'pcthni',

        'pclade',
        'pcud61',
        'pcxaxe',
        'pcdith',
        'luonghd',
        'pcphth',

        'tonghs',
        'ttl',
        'giaml',
        'thuetn',
        'tienthuong',
        'trichnop',
        'bhct',
        'tluong',
        'stbhxh',
        'stbhyt',
        'stkpcd',
        'stbhtn',
        'ttbh',
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv',
        'gttncn',
        'luongtn',
        'thangtl',
        'ngaytl',
        'songaytruc',
        'songaycong',
        'songaylv',
        'tongngaylv',
        //lưu theo số tiền
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
        'st_pcctp',
        'pcctp',
        'st_pctaicu',
        'pctaicu',
        //lưu tỷ lệ bảo hiểm (đã quy về hệ số)
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        //lưu hệ số gốc 1 số loại pc tính %
        'hs_vuotkhung',
        'hs_pctnn',
        'hs_pccovu',
        'hs_pcud61',
        'hs_pcudn',
        'hs_pclt',
        'luuheso',
        'ghichu',
        'pclaunam',
        'st_pclaunam',
        //thêm phụ cấp dân phòng
        'pcdp',
        'st_pcdp'
    ];
}
/* 1534853505_1540178256
UPDATE bangluong_ct set st_heso = heso * if(heso> 100, 1, luongcoban) WHERE st_heso = 0;
UPDATE bangluong_ct set st_hesobl = hesobl * if(hesobl> 100, 1, luongcoban) WHERE st_hesobl = 0;
UPDATE bangluong_ct set st_hesopc = hesopc * if(hesopc> 100, 1, luongcoban) WHERE st_hesopc = 0;
UPDATE bangluong_ct set st_vuotkhung = vuotkhung * if(vuotkhung> 100, 1, luongcoban) WHERE st_vuotkhung = 0;
UPDATE bangluong_ct set st_pcct = pcct * if(pcct> 100, 1, luongcoban) WHERE st_pcct = 0;
UPDATE bangluong_ct set st_pckct = pckct * if(pckct> 100, 1, luongcoban) WHERE st_pckct = 0;
UPDATE bangluong_ct set st_pck = pck * if(pck> 100, 1, luongcoban) WHERE st_pck = 0;

UPDATE bangluong_ct set st_pccv = pccv * if(pccv> 100, 1, luongcoban) WHERE st_pccv = 0;
UPDATE bangluong_ct set st_pckv = pckv * if(pckv> 100, 1, luongcoban) WHERE st_pckv = 0;
UPDATE bangluong_ct set st_pcth = pcth * if(pcth> 100, 1, luongcoban) WHERE st_pcth = 0;
UPDATE bangluong_ct set st_pcdd = pcdd * if(pcdd> 100, 1, luongcoban) WHERE st_pcdd = 0;
UPDATE bangluong_ct set st_pcdh = pcdh * if(pcdh> 100, 1, luongcoban) WHERE st_pcdh = 0;
UPDATE bangluong_ct set st_pcld = pcld * if(pcld> 100, 1, luongcoban) WHERE st_pcld = 0;
UPDATE bangluong_ct set st_pcdbqh = pcdbqh * if(pcdbqh> 100, 1, luongcoban) WHERE st_pcdbqh = 0;

UPDATE bangluong_ct set st_pcudn = pcudn * if(pcudn> 100, 1, luongcoban) WHERE st_pcudn = 0;
UPDATE bangluong_ct set st_pctn = pctn * if(pctn> 100, 1, luongcoban) WHERE st_pctn = 0;
UPDATE bangluong_ct set st_pctnn = pctnn * if(pctnn> 100, 1, luongcoban) WHERE st_pctnn = 0;
UPDATE bangluong_ct set st_pcdbn = pcdbn * if(pcdbn> 100, 1, luongcoban) WHERE st_pcdbn = 0;
UPDATE bangluong_ct set st_pcvk = pcvk * if(pcvk> 100, 1, luongcoban) WHERE st_pcvk = 0;
UPDATE bangluong_ct set st_pckn = pckn * if(pckn> 100, 1, luongcoban) WHERE st_pckn = 0;
UPDATE bangluong_ct set st_pcdang = pcdang * if(pcdang> 100, 1, luongcoban) WHERE st_pcdang = 0;

UPDATE bangluong_ct set st_pccovu = pccovu * if(pccovu> 100, 1, luongcoban) WHERE st_pccovu = 0;
UPDATE bangluong_ct set st_pclt = pclt * if(pclt> 100, 1, luongcoban) WHERE st_pclt = 0;
UPDATE bangluong_ct set st_pcd = pcd * if(pcd> 100, 1, luongcoban) WHERE st_pcd = 0;
UPDATE bangluong_ct set st_pctr = pctr * if(pctr> 100, 1, luongcoban) WHERE st_pctr = 0;
UPDATE bangluong_ct set st_pctdt = pctdt * if(pctdt> 100, 1, luongcoban) WHERE st_pctdt = 0;
UPDATE bangluong_ct set st_pctnvk = pctnvk * if(pctnvk> 100, 1, luongcoban) WHERE st_pctnvk = 0;
UPDATE bangluong_ct set st_pcbdhdcu = pcbdhdcu * if(pcbdhdcu> 100, 1, luongcoban) WHERE st_pcbdhdcu = 0;

UPDATE bangluong_ct set st_pcthni = pcthni * if(pcthni> 100, 1, luongcoban) WHERE st_pcthni = 0;
UPDATE bangluong_ct set st_pclade = pclade * if(pclade> 100, 1, luongcoban) WHERE st_pclade = 0;
UPDATE bangluong_ct set st_pcud61 = pcud61 * if(pcud61> 100, 1, luongcoban) WHERE st_pcud61 = 0;
UPDATE bangluong_ct set st_pcxaxe = pcxaxe * if(pcxaxe> 100, 1, luongcoban) WHERE st_pcxaxe = 0;
UPDATE bangluong_ct set st_pcdith = pcdith * if(pcdith> 100, 1, luongcoban) WHERE st_pcdith = 0;
UPDATE bangluong_ct set st_luonghd = luonghd * if(luonghd> 100, 1, luongcoban) WHERE st_luonghd = 0;
UPDATE bangluong_ct set st_pcphth = pcphth * if(pcphth> 100, 1, luongcoban) WHERE st_pcphth = 0;


INSERT INTO `bangluong_ct_01` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
`tencanbo`, `macongchuc`, `heso`, `hesobl`, `hesopc`, `luuheso`, `ghichu`, `hesott`, `thangtl`, `ngaytl`, `luongcoban`, `songaytruc`, `songaycong`, `vuotkhung`,
`pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`,
`pcd`, `pctr`, `pctdt`, `pclade`, `pcud61`, `pcxaxe`, `pcdith`, `luonghd`, `st_pctaicu`, `pctaicu`, `pcphth`, `st_pcctp`, `pcctp`, `pctnvk`, `pcbdhdcu`, `pcthni`,
`tonghs`, `ttl`, `giaml`, `thuetn`, `tienthuong`, `trichnop`, `bhct`, `tluong`, `stbhxh`, `stbhyt`, `stkpcd`, `stbhtn`, `ttbh`, `gttncn`, `luongtn`, `stbhxh_dv`,
 `stbhyt_dv`, `stkpcd_dv`, `stbhtn_dv`, `ttbh_dv`, `bhxh`, `bhyt`, `bhtn`, `kpcd`, `bhxh_dv`, `bhyt_dv`, `bhtn_dv`, `kpcd_dv`, `hs_pcudn`, `hs_pcud61`, `hs_pccovu`,
 `hs_pctnn`, `hs_vuotkhung`, `st_pcphth`, `st_luonghd`, `st_pcdith`, `st_pcxaxe`, `st_pcud61`, `st_pclade`, `st_pcthni`, `st_pcbdhdcu`, `st_pctnvk`, `st_pctdt`,
 `st_pctr`, `st_pcd`, `st_pclt`, `st_pccovu`, `st_pcdang`, `st_pckn`, `st_pcvk`, `st_pcdbn`, `st_pctnn`, `st_pctn`, `st_pcudn`, `st_pcdbqh`, `st_pcld`, `st_pcdh`,
 `st_pcdd`, `st_pcth`, `st_pckv`, `st_pccv`, `st_pck`, `st_pckct`, `st_pcct`, `st_vuotkhung`, `st_hesopc`, `st_hesobl`, `st_heso`)
 select `mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
`tencanbo`, `macongchuc`, `heso`, `hesobl`, `hesopc`, `luuheso`, `ghichu`, `hesott`, `thangtl`, `ngaytl`, `luongcoban`, `songaytruc`, `songaycong`, `vuotkhung`,
`pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`,
`pcd`, `pctr`, `pctdt`, `pclade`, `pcud61`, `pcxaxe`, `pcdith`, `luonghd`, `st_pctaicu`, `pctaicu`, `pcphth`, `st_pcctp`, `pcctp`, `pctnvk`, `pcbdhdcu`, `pcthni`,
`tonghs`, `ttl`, `giaml`, `thuetn`, `tienthuong`, `trichnop`, `bhct`, `tluong`, `stbhxh`, `stbhyt`, `stkpcd`, `stbhtn`, `ttbh`, `gttncn`, `luongtn`, `stbhxh_dv`,
 `stbhyt_dv`, `stkpcd_dv`, `stbhtn_dv`, `ttbh_dv`, `bhxh`, `bhyt`, `bhtn`, `kpcd`, `bhxh_dv`, `bhyt_dv`, `bhtn_dv`, `kpcd_dv`, `hs_pcudn`, `hs_pcud61`, `hs_pccovu`,
 `hs_pctnn`, `hs_vuotkhung`, `st_pcphth`, `st_luonghd`, `st_pcdith`, `st_pcxaxe`, `st_pcud61`, `st_pclade`, `st_pcthni`, `st_pcbdhdcu`, `st_pctnvk`, `st_pctdt`,
 `st_pctr`, `st_pcd`, `st_pclt`, `st_pccovu`, `st_pcdang`, `st_pckn`, `st_pcvk`, `st_pcdbn`, `st_pctnn`, `st_pctn`, `st_pcudn`, `st_pcdbqh`, `st_pcld`, `st_pcdh`,
 `st_pcdd`, `st_pcth`, `st_pckv`, `st_pccv`, `st_pck`, `st_pckct`, `st_pcct`, `st_vuotkhung`, `st_hesopc`, `st_hesobl`, `st_heso`
 from `bangluong_ct` where `bangluong_ct`.`thang` = '01'
*/