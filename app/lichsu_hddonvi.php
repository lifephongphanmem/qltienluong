<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lichsu_hddonvi extends Model
{
    use HasFactory;
    protected $table='lichsu_hddonvi';
    protected $fillable=[
        'mals',
        'madv',
        'macqcq',
        'action',
        'ghichu',
        'ngaythang',

    ];
}
