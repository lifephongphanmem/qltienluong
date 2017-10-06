<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphongban extends Model
{
    protected $table = 'dmphongban';
    protected $fillable = [
        'id',
        'mapb',
        'tenpb',
        'diengiai',
        'sapxep',
        'madv'
    ];
}
