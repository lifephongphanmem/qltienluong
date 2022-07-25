<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphanloaidonvi_nhom extends Model
{
    protected $table = 'dmphanloaidonvi_nhom';
    protected $fillable = [
        'id',
        'maphanloai_nhom',
        'tenphanloai_nhom',
        'capdo_nhom',
        'ghichu',
    ];
}
