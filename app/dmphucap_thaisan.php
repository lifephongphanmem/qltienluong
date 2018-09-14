<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphucap_thaisan extends Model
{
    protected $table = 'dmphucap_thaisan';
    protected $fillable = [
        'id',
        'mapc',
        'tenpc',
        'ghichu',
        'madv'
    ];
}
