<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_ct_06 extends Model
{
    protected $table = 'bangluong_ct_06';
    protected $fillable = [
        'id',
        'mabl',
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
        //lưu tỷ lệ bảo hiểm (đã quy về hệ số)
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv'
    ];
}