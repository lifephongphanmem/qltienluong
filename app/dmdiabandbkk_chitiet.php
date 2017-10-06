<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmdiabandbkk_chitiet extends Model
{
    protected $table = 'dmdiabandbkk_chitiet';
    protected $fillable = [
        'id',
        'madiaban',
        'macanbo',
        'ghichu'
    ];
}
