<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nguonkinhphi_huyen_baocao extends Model
{
    protected $table = 'nguonkinhphi_huyen_baocao';
    protected $fillable = [
        'id',
       'masodv',
       'masoh',
       'masot',
       'sohieu',
       'manguonkp',
       'noidung',
       'namns',

        'thuchien',
        'dutoan19',
        'dutoan18',
        'tietkiem17',
        'tietkiem18',
        'tietkiem19',
        'tietkiem19gd',
        'tietkiem19dt',
        'tietkiem19yte',
        'tietkiem19khac',
        'tietkiem19qlnn',
        'tietkiem19xa',
            //Tự đảm bảo
            //Học phí
        'dbhocphi',
        'dbhocphigd',
        'dbhocphidt',
        'dbhocphiyte',
        'dbhocphikhac',
        'dbhocphiqlnn',
        'dbhocphixa',
            //Viện phí
        'dbvienphi',
        'dbvienphigd',
        'dbvienphidt',
        'dbvienphiyte',
        'dbvienphikhac',
        'dbvienphiqlnn',
        'dbvienphixa',
            //Khác
        'dbkhac',
        'dbkhacgd',
        'dbkhacdt',
        'dbkhacyte',
        'dbkhackhac',
        'dbkhacqlnn',
        'dbkhacxa',
            //Chưa tự đảm bảo
            //Học phí
        'kdbhocphi',
        'kdbhocphigd',
        'kdbhocphidt',
        'kdbhocphiyte',
        'kdbhocphikhac',
        'kdbhocphiqlnn',
        'kdbhocphixa',
            //Viện phí
        'kdbvienphi',
        'kdbvienphigd',
        'kdbvienphidt',
        'kdbvienphiyte',
        'kdbvienphikhac',
        'kdbvienphiqlnn',
        'kdbvienphixa',
            //Khác
        'kdbkhac',
        'kdbkhacgd',
        'kdbkhacdt',
        'kdbkhacyte',
        'kdbkhackhac',
        'kdbkhacqlnn',
        'kdbkhacxa',
            //Tiết kiệm theo 18, 19
        'tietkiemchi',
        'tietkiemchigd',
        'tietkiemchidt',
        'tietkiemchiyte',
        'tietkiemchikhac',
        'tietkiemchiqlnn',
        'tietkiemchixa',

        'bosung',
        'caicach',

       'madv',
       'madvbc',
       'macqcq',
       'trangthai',
        'ngayguidv',
       'nguoiguidv'
    ];
}
