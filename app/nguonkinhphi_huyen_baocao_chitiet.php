<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_huyen_baocao_chitiet extends Model
{
    protected $table = 'nguonkinhphi_huyen_baocao_chitiet';
    protected $fillable = [
        'id',
        'masodv',
        'masoh',
        'masot',
        'sohieu',
        'namns',
        'linhvuchoatdong',

        'tietkiem',
        //Tự đảm bảo
        //Học phí
        'dbhocphi',
        //Viện phí
        'dbvienphi',
        //Khác
        'dbkhac',
        //Chưa tự đảm bảo
        //Học phí
        'kdbhocphi',
        //Viện phí
        'kdbvienphi',
        //Khác
        'kdbkhac',
        //Tiết kiệm theo 18, 19
        'tietkiemchi',
        'madv',
        'madvbc',
        'macqcq',
    ];
}
