<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaict extends Model
{
    protected $table = 'dmphanloaict';
    protected $fillable = [
        'id',
        'macongtac',
        'tonghop',
        'dutoan',
        'mact',
        'tenct',
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        'ghichu',
        'nhomnhucau_hc',
        'nhomnhucau_xp',
        'nhucaukp',
    ];
}
