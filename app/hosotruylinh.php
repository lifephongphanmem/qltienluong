<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hosotruylinh extends Model
{
    protected $table = 'hosotruylinh';
    protected $fillable = [
        'id',
        'maso',
        'stt',
        'macanbo',
        'tencanbo',
        'soqd',//chưa dùng
        'ngayqd',//chưa dùng
        'nguoiky',//chưa dùng
        'coquanqd',//chưa dùng
        'mabl',
        'ngaytu',
        'ngayden',
        'madv',

        'msngbac',
        'hesott',
        //chưa dùng, cho trường hợp truy lĩnh cả phụ cấp
        'heso',
        'vuotkhung',
        'pcct',
        'pckct',
        'pck',
        'pccv',
        'pckv',
        'pcth',
        'pcdd',
        'pcdh',
        'pcld',
        'pcdbqh',
        'pcudn',
        'pctn',
        'pctnn',
        'pcdbn',
        'pcvk',
        'pckn',
        'pcdang',
        'pccovu',
        'pclt',
        'pcd',
        'pctr',
        'pctnvk',
        'pcthni',
        'pcbdhdcu'
    ];
}