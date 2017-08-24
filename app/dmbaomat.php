<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmbaomat extends Model
{
    protected $table = 'dmbaomat';
    protected $fillable = [
        'id',
        'level',
        'macapdo',
        'tencapdo',
        'ghichu'
    ];
}
