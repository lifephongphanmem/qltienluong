<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphucap extends Model
{
    protected $table = 'dmphucap';
    protected $fillable = [
        'id',
        'mapc',
        'tenpc',
        'hesopc',
        'baohiem',
        'ghichu'
    ];
}
