<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nhomngachluong extends Model
{
    protected $table = 'nhomngachluong';
    protected $fillable = [
        'id',
        'manhom',
        'tennhom',
        'phanloai',
        'namnb',
        'baclonnhat',
        'bacvuotkhung',
        'heso',
        'hesolonnhat',
        'vuotkhung',
        'hesochenhlech',
        'ghichu'
    ];
}
