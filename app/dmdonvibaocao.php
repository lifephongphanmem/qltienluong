<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmdonvibaocao extends Model
{
    protected $table = 'dmdonvibaocao';
    protected $fillable = [
        'id',
        'madvbc',
        'tendvbc',
        'ghichu',
        'madvcq',
        'level',
        'baocao',
        'sapxep',
    ];
}
