<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\tonghopluong_donvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class xemdulieucapduoiController extends Controller
{
    public function donvi_luong(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            $madv = session('admin')->madv;
            $list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();
            $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')->where('macqcq', $madv)->orwhere('madv', session('admin')->madv)->get();

            foreach($model_donvi as $dv){
                $donvi = $list_donvi->where('madv',$dv->macqcq)->first();
                $dv->tendvcq =(isset($donvi)?$donvi->tendv:NULL);
                $model_bangluong = tonghopluong_donvi::where('madv', $dv->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->first();

                $dv->mathdv = (isset($model_bangluong)?$model_bangluong->mathdv:NULL);
            }
            //dd($model_donvi->toarray());
            return view('functions.viewdata.index')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }
}
