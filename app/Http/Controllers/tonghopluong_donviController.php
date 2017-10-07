<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_donviController extends Controller
{
    function index(Request $requests){
        if (Session::has('admin')) {
            $a_data=array('thang'=>'01','thang'=>'02','thang'=>'03',
                'thang'=>'04','thang'=>'05','thang'=>'06',
                'thang'=>'07','thang'=>'08','thang'=>'09',
                'thang'=>'10','thang'=>'11','thang'=>'12');
            $inputs=$requests->all();
            return view('functions.tonghopluong.donvi.index')
                ->with('furl','/chuc_nang/tong_hop_luong/don_vi/')
                ->with('nam',$inputs['nam'])
                ->with('model',$a_data)
                ->with('pageTitle','Danh sách tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }
}
