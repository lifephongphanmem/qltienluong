<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaidonvi extends Model
{
    protected $table = 'dmphanloaidonvi';
    protected $fillable = [
        'id',
        'maphanloai',
        'tenphanloai',
        'ghichu'
    ];
}
