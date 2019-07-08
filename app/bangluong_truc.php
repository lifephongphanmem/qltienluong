<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_truc extends Model
{
    protected $table = 'bangluong_truc';
    protected $fillable = [
        'id',
        'stt',
        'macvcq',
        'mapb',
        'mact',
        'congtac',
        'mabl',
        'macanbo',
        'tencanbo',
        'songay',
        'ngaycong',
        'heso',
        'luongcoban',
        'ttl'
    ];
}
