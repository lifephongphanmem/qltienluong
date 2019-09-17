<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_dinhmuc extends Model
{
    protected $table = 'nguonkinhphi_dinhmuc';
    protected $fillable = [
        'id',
        'maso',
        'mact',
        'noidung',
        'manguonkp',
        'tungay',
        'denngay',
        'luongcoban',
        'baohiem',
        'madv'
    ];
}

//DELETE FROM `nguonkinhphi_dinhmuc` WHERE maso not in (SELECT DISTINCT maso FROM nguonkinhphi_dinhmuc_ct);
