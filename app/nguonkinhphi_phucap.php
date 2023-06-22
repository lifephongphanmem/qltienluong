<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_phucap extends Model
{
    protected $table = 'nguonkinhphi_phucap';
    protected $fillable = [
        'id',
        'masodv',
        'masok',
        'masoh',
        'masot',
        'sohieu',
        'manguonkp', //chưa dùng
        'macongtac',
        'mact',
        'canbo_congtac',
        'canbo_dutoan',
        //lưu theo từng phân loại sau 
        'heso',
        'hesobl',
        'hesopc',
        'vuotkhung',
        'pcct', //dùng để thay thế phụ cấp ghép lớp
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
        'pcvk', //dùng để thay thế phụ cấp Đảng uy viên
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
        'pcxaxe', //điện thoại
        'pcdith', //điện thoại
        'luonghd', //lương hợp đồng, lương khoán (số tiền)
        'pcphth', //phẫu thuật, thủ thuật
        'pcctp', //phụ cấp công tác phí
        'pctaicu', //phụ cấp tái ứng cử            
        'pclaunam', //công tác lâu năm
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
        'st_pclaunam', //công tác lâu năm
        //tính bảo hiểm
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        'tongbh_dv',
        //
        'tonghs',
        'ttl',
                        //thêm phụ cấp dân phòng
                        'pcdp',
                        'st_pcdp'
    ];
}
