<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bangluong_thuetncn extends Model
{
    protected $table='bangluong_thuetncn';
    protected $fillable=[
        'mabl','stt','macanbo','tencanbo','thang01','thang02','thang03','thang04','thang05','thang06','thang07','thang08','thang09','thang10','thang11','thang12','tongthuetncn'
    ];
}
