<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dmphucap_donvi extends Model
{
    protected $table = 'dmphucap_donvi';
    protected $fillable = [
        'id',
        'stt',
        'mapc',
        'tenpc',
        'baohiem',
        'thaisan',
        'nghiom',
        'form', //tiêu đề trên Form
        'report', //tiêu đề trên Report
        'phanloai',
        'congthuc',
        'ghichu',
        'madv'
    ];
}
