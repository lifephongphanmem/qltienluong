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
        'dieudong',
        'thuetn',
        'tapsu',
        'form', //tiêu đề trên Form
        'report', //tiêu đề trên Report
        'phanloai',
        'congthuc',
        'ghichu',
        'madv',
        'stt',
        'baohiem_plct',
        'pccoso',
        'dutoan',
    ];
    //21.05.2022
    //ALTER TABLE `dmphucap_donvi` ADD `baohiem_plct` VARCHAR(255) NULL DEFAULT 'ALL' AFTER `congthuc`;
}
