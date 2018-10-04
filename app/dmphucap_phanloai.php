<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphucap_phanloai extends Model
{
    protected $table = 'dmphucap_phanloai';
    protected $fillable = [
        'id',
        'mapc',
        'phanloai',
        'congthuc',
        'maphanloai'
    ];
}
