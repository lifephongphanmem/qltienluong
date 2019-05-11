<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dsnangthamnien_nguon extends Model
{
    protected $table = 'dsnangthamnien_nguon';
    protected $fillable = [
        'id',
        'manl',
        'macanbo',
        'manguonkp',
        'luongcoban',
    ];
}
