<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_truc extends Model
{
    protected $table = 'bangluong_truc';
    protected $fillable = [
        'id',
        'mabl',
        'macanbo',
        'tencanbo',
        'songay',
        'heso',
        'ttl'
    ];
}
