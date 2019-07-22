<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosonhanxetdg extends Model
{
    protected $table = 'hosonhanxetdg';
    protected $fillable = [
        'id',
        'macanbo',
        'ngaytu',
        'ngayden',
        'danhgia',
        'nhanxet',
        'xeploai',
        'madv'
    ];
}
