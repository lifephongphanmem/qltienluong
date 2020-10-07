<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vanphonghotro extends Model
{
    protected $table = 'vanphonghotro';
    protected $fillable = [
        'id',
        'maso',
        'vanphong',
        'hoten',
        'chucvu',
        'sdt',
        'skype',
        'facebook',
        'sapxep',
    ];
}
