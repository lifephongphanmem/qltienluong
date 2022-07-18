<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaidonvi_baocao extends Model
{
    protected $table = 'dmphanloaidonvi_baocao';
    protected $fillable = [
        'id',
        'masobc',
        'madvbc',
        'maphanloai_goc',
        'maphanloai_nhom',
        'tenphanloai_nhom',
        'chitiet',
        'capdo_nhom',
        'sapxep',
        'ghichu'
    ];
}
