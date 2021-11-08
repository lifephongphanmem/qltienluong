<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_dinhmuc_ct extends Model
{
    protected $table = 'nguonkinhphi_dinhmuc_ct';
    protected $fillable = [
        'id',
        'maso',
        'mapc',
        'tenpc',
        'tinhtheodm',
        'luongcoban',
        'ghichu'
    ];
}
