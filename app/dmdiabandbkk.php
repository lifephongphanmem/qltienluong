<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmdiabandbkk extends Model
{
    protected $table = 'dmdiabandbkk';
    protected $fillable = [
        'id',
        'madiaban',
        'tendiaban',
        'ngaytu',
        'thangtu',
        'namtu',
        'ngayden',
        'thangden',
        'namden',
        'phanloai',
        'nguoilapbieu',
        'madv',
        'ghichu'
    ];
}
