<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmkhoipb;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class baocaobangluongController extends Controller
{
    function index() {
        if (Session::has('admin')) {
            return view('reports.bangluong.index')
                ->with('furl','/bao_cao/bang_luong/')
                ->with('pageTitle','Báo cáo số lượng, chất lượng cán bộ');
        } else
            return view('errors.notlogin');
    }

    function mauc02ahd(Request $request){
        if (Session::has('admin')) {

            $inputs=$request->all();

            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();

            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }
            $bl=new bangluongController();
            return $bl->inbangluong($model_bangluong->mabl);


        } else{return view('errors.notlogin');}

    }

    function mauc02x(Request $request){
        if (Session::has('admin')) {

            $inputs=$request->all();

            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();

            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }
            $bl=new bangluongController();
            return $bl->inbangluong($model_bangluong->mabl);


        } else{return view('errors.notlogin');}

    }

    function maubaohiem(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();

            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }
            $bl=new bangluongController();
            return $bl->inbaohiem($model_bangluong->mabl);

        } else{return view('errors.notlogin');}

    }

    function mauc02ahd_th(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();

            $makpb=array();
            $mabl=array();
            foreach( $model_dv as $donvi){
                $model_bangluong=bangluong::where('madv',$donvi->madv)
                    ->where('thang',$inputs['thang'])
                    ->where('nam',$inputs['nam'])->first();

                $donvi->mabl=!isset($model_bangluong->mabl)?NULL:$model_bangluong->mabl;
                $makpb[]=$donvi->makhoipb;
                $mabl[]=$donvi->mabl;
            }
            //$model_bangluong=bangluong::all();
            $model_bangluong_ct=bangluong_ct::wherein('mabl',$mabl)->get();
            $model_khoipb=dmkhoipb::wherein('makhoipb',$makpb)->get();
            $m_dv=dmdonvi::where('madv',session('admin')->maxa)->first();

            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach($model_bangluong_ct as $hs){
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$inputs['thang'],
                'nam'=>$inputs['nam']);


            return view('reports.bangluong.maubangluong_tonghop')
                ->with('model_bangluong_ct',$model_bangluong_ct)
                ->with('model_kpb',$model_khoipb)
                ->with('thongtin',$thongtin)
                ->with('model_dv',$model_dv)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo bảng lương');
        } else
            return view('errors.notlogin');
    }
}
