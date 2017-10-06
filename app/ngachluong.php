<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ngachluong extends Model
{
    protected $table = 'ngachluong';
    protected $fillable = [
        'id',
        'msngbac',
        'manhom',
        'tenngachluong'
    ];
}
