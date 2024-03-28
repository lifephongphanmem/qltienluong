<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nhomphanloaict extends Model
{
    protected $table = 'nhomphanloaict';
    protected $fillable = [
        'id',
        'manhom',
        'tennhom',
        'stt'
    ];
}
