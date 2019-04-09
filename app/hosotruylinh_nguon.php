<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotruylinh_nguon extends Model
{
    protected $table = 'hosotruylinh_nguon';
    protected $fillable = [
        'id',
        'maso',
        'manguonkp',
        'luongcoban',
    ];
    //INSERT INTO `hosotruylinh_nguon` (`manguonkp`, `maso`, `luongcoban`) SELECT `manguonkp`, `maso`, `luongcoban` FROM `hosotruylinh` WHERE id > 0;
    //ALTER TABLE `hosotruylinh` DROP `manguonkp`, DROP `luongcoban`;
}
