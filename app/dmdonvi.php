<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmdonvi extends Model
{
    protected $table = 'dmdonvi';
    protected $fillable = [
        'id',
        'madv',
        'maqhns',
        'tendv',
        'diachi',
        'sodt',
        'cdlanhdao',
        'lanhdao',
        'cdketoan',
        'ketoan',
        'songuoi',
        'macqcq',
        'diadanh',
        'nguoilapbieu',
        'makhoipb', //phân loại đơn vị tổng hợp; đơn vị sử dụng
        'maphanloai',
        'madvbc',
        'capdonvi',
        'phanloaixa',
        'phanloainguon',
        'linhvuchoatdong',
        //
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
        'pcvk',//dùng để thay thế phụ cấp Đảng ủy viên
        'pckn',
        'pcdang',
        'pccovu',
        'pclt', //lưu thay phụ cấp phân loại xã
        'pcd',
        'pctr',
        'pctnvk',
        'pcbdhdcu',
        'pcthni'
    ];
}
