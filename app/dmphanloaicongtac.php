<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaicongtac extends Model
{
    protected $table = 'dmphanloaicongtac';
    protected $fillable = [
        'id',
        'maphanloai',
        'macongtac',
        'tencongtac',
        'sapxep',
        'ghichu'
    ];
}
