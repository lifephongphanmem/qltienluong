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

        'tonghs',
        'ttl',
        'giaml',
        'luongtn',
        'stbhxh_dv',
        'stbhyt_dv',
        'stkpcd_dv',
        'stbhtn_dv',
        'ttbh_dv'
    ];
}
