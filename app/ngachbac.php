<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ngachbac extends Model
{
    protected $table = 'ngachbac';
    protected $fillable = [
        'id',
        'msngbac',
        'plnb',
        'tennb',
        'namnb',
        'bac',
        'heso',
        'ptvk',
        'vk'
    ];
}
