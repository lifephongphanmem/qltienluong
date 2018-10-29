<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_dinhmuc extends Model
{
    protected $table = 'nguonkinhphi_dinhmuc';
    protected $fillable = [
        'id',
        'maso',
        'noidung',
        'manguonkp',
        'tungay',
        'denngay',
        'luongcoban',
        'baohiem',
        'madv'
    ];
}
