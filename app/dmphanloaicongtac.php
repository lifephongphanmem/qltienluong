<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaicongtac extends Model
{
    protected $table = 'dmphanloaicongtac';
    protected $fillable = [
        'id',
        'macongtac',
        'tencongtac',
        'bhxh',
        'bhyt',
        'bhtn',
        'kpcd',
        'bhxh_dv',
        'bhyt_dv',
        'bhtn_dv',
        'kpcd_dv',
        'sapxep',
        'ghichu'
    ];
}
