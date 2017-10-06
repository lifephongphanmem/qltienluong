<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmnguonkinhphi extends Model
{
    protected $table = 'dmnguonkinhphi';
    protected $fillable = [
        'id',
        'manguonkp',
        'tennguonkp',
        'linhvuchoatdong',
        'ghichu'
    ];
}
