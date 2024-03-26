<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nhomphanloaict_phanloaict extends Model
{
    protected $table = 'nhomphanloaict_phanloaict';
    protected $fillable = [
        'id',
        'manhom',
        'maphanloaict'
    ];
}
