<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_ct_03 extends Model
{
    protected $table = 'bangluong_ct_03';
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
        'luuheso',
        'ghichu',
        'pclaunam',
        'st_pclaunam',
    ];
}

/*
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


 INSERT INTO `bangluong_ct_02` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '02'

 INSERT INTO `bangluong_ct_03` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '03'

 INSERT INTO `bangluong_ct_04` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '04'

 INSERT INTO `bangluong_ct_05` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '05'

 INSERT INTO `bangluong_ct_06` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '06'

 INSERT INTO `bangluong_ct_07` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '07'

 INSERT INTO `bangluong_ct_08` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '08'

 INSERT INTO `bangluong_ct_09` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '09'

 INSERT INTO `bangluong_ct_10` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '10'

 INSERT INTO `bangluong_ct_11` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '11'

 INSERT INTO `bangluong_ct_12` (`mabl`, `maso`, `manguonkp`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `congtac`, `macanbo`,
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
 from `bangluong_ct` where `bangluong_ct`.`thang` = '12'
 * */