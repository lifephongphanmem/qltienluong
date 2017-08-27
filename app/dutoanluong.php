<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dutoanluong extends Model
{
    protected $table = 'dutoanluong';
    protected $fillable = [
        'id',
        'namns',
        'luongnb',
        'luonghs',
        'luongbh',
        'luongnb_dt',
        'luonghs_dt',
        'luongbh_dt',
        'madv'
    ];
}
