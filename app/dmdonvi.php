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
        'makhoipb',
        'maphanloai',
        'phanloaitaikhoan', //phân loại đơn vị tổng hợp; đơn vị sử dụng
        'phamvitonghop',
        'madvbc',
        'capdonvi',
        'phanloaixa',
        'phanloainguon',
        'linhvuchoatdong',
        'phucaploaitru',
        'songaycong',
        'ptdaingay',
        'lamtron',
        'ngaydung',
        'trangthai',
        'caphanhchinh',
        'chuyendoi', //biến lưu chuyển đổi tài khoản
        'dinhmucnguon',
        'sotk',
        'tennganhang',
        'phucaploaitru',
        'phucapluusotien',
        'machuong',
        'maloaikhoan',
    ];
}
