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
            'lanhdao',
            'songuoi',
            'macqcq',
            'diadanh',
            'cdlanhdao',
            'nguoilapbieu',
            'makhoipb',
            'maphanloai',
            'madvbc',
            'capdonvi',
            'phanloaixa',
            'phanloainguon',
            'linhvuchoatdong'
        ];
}
