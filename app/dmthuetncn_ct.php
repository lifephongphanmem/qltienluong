<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmthuetncn_ct extends Model
{
    protected $table = 'dmthuetncn_ct';
    protected $fillable = [
        'id',
        'sohieu',
        'muctu',
        'mucden',
        'phantram',
        'ghichu',
    ];
}
