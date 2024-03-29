<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phanboluong_chitiet extends Model
{
    protected $table = 'phanboluong_chitiet';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'canbo_congtac', //lấy số lượng thực tế tại đơn vị
        'canbo_dutoan',
        'macongtac',
        'phanloai', //Theo dõi: COMAT, CHUATUYEN        
        'mact',
        'luongnb',
        'luonghs',
        'luongbh',
        'luongnb_pbl',
        'luonghs_pbl',
        'luongbh_pbl',

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
        'pclade',
        'pcud61',
        'pcxaxe',
        'pcdith',
        'luonghd',
        'pcphth',
        'pcctp',
        'pctaicu',
        'pclaunam',
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
        'st_pctaicu',
        'st_pcctp',
        'st_pclaunam',
        //bảo hiểm quy đổi hệ số
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        'tongbh_dv',
        //Số tiền bảo hiểm
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv',
        'tonghs',
        'ttl',
        'ghichu',
                //thêm phụ cấp dân phòng
                'pcdp',
                'st_pcdp'
    ];
}
