<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaicongtac extends Model
{
    protected $table = 'dmphanloaicongtac';
    protected $fillable = [
        'id',
        'macongtac',
        'tencongtac',
        'sapxep',
        'ghichu'
    ];
}
