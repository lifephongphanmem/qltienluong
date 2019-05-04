<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsnangluong_nguon extends Model
{
    protected $table = 'dsnangluong_nguon';
    protected $fillable = [
        'id',
        'manl',
        'macanbo',
        'manguonkp',
        'luongcoban',
    ];
}
