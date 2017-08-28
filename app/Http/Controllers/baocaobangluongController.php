<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dutoanluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class baocaobangluongController extends Controller
{
    function index() {
        if (Session::has('admin')) {
            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();
            $model_dvbc=dmdonvibaocao::where('level','H')->get();
            return view('reports.bangluong.index')
                ->with('furl','/bao_cao/bang_luong/')
                ->with('model_dv',$model_dv)
                ->with('model_dvbc', $model_dvbc)
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

    function chitraluong(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_bangluong=bangluong_ct::wherein('mabl',function ($query) {
                $query->select('mabl')->from('bangluong')
                    ->where('madv',session('admin')->madv)
                    ->where('nam','2017');
            })->get();

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $m_dv->luongnb=$model_bangluong->sum('ttl');
            $m_dv->ttbh=$model_bangluong->sum('ttbh_dv');
            $m_dv->tpc=($model_bangluong->sum('tonghs')-$model_bangluong->sum('heso'))*1300000;
            $m_dv->tongcong= $m_dv->luongnb + $m_dv->ttbh + $m_dv->tpc;

            $thongtin=array('nguoilap'=>session('admin')->name,
                'tu'=>$inputs['tuthang'].'/'.$inputs['tunam'],
                'den'=>$inputs['denthang'].'/'.$inputs['dennam']);


            return view('reports.bangluong.chitraluong')
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_th(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();

            $makpb=array();
            foreach( $model_dv as $donvi){
                $madv=$donvi->madv;
                $model_bangluong=bangluong_ct::wherein('mabl',function ($query) use ($madv){
                    $query->select('mabl')->from('bangluong')
                        ->where('madv',$madv)
                        ->where('nam','2017');
                })->get();

                $donvi->bienche= rand(20,30);
                $donvi->soluong=$donvi->bienche;
                $donvi->luongnb=$model_bangluong->sum('ttl');
                $donvi->ttbh=$model_bangluong->sum('ttbh_dv');
                $donvi->tpc=($model_bangluong->sum('tonghs')-$model_bangluong->sum('heso'))*1300000;
                $donvi->tongcong= $donvi->luongnb + $donvi->ttbh + $donvi->tpc;

                $makpb[]=$donvi->makhoipb;
            }
            $model_khoipb=dmkhoipb::wherein('makhoipb',$makpb)->get();
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();



            $thongtin=array('nguoilap'=>session('admin')->name,
                'tu'=>$inputs['tuthang'].'/'.$inputs['tunam'],
                'den'=>$inputs['denthang'].'/'.$inputs['dennam']);


            return view('reports.bangluong.chitraluong_tonghop')
                ->with('model_kpb',$model_khoipb)
                ->with('thongtin',$thongtin)
                ->with('model_dv',$model_dv)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function dutoanluong(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_bangluong=bangluong_ct::wherein('mabl',function ($query) {
                $query->select('mabl')->from('bangluong')
                    ->where('madv',session('admin')->madv)
                    ->where('nam','2017');
            })->get();

            $model_dutoan=dutoanluong::where('madv',session('admin')->madv)
                ->where('namns','2018')->first();
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $m_dv->luongnb=$model_bangluong->sum('ttl');
            $m_dv->luongbh=$model_bangluong->sum('ttbh_dv');
            $m_dv->luonghs=($model_bangluong->sum('tonghs')-$model_bangluong->sum('heso'))*1300000;
            $m_dv->tongcong= $m_dv->luongnb + $m_dv->ttbh + $m_dv->tpc;

            if(isset($model_dutoan)) {
                $m_dv->luongnb_dt = $model_dutoan->luongnb_dt;
                $m_dv->luongbh_dt = $model_dutoan->luongbh_dt;
                $m_dv->luonghs_dt = $model_dutoan->luonghs_dt;
                $m_dv->tongcong_dt = $m_dv->luongnb_dt + $m_dv->luongbh_dt + $m_dv->luonghs_dt;
            }
            $thongtin=array('nguoilap'=>session('admin')->name);


            return view('reports.bangluong.dutoanluong')
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function dutoanluong_th(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();

            $makpb=array();
            foreach( $model_dv as $donvi){
                $madv=$donvi->madv;
                $model_bangluong=bangluong_ct::wherein('mabl',function ($query) use ($madv){
                    $query->select('mabl')->from('bangluong')
                        ->where('madv',$madv)
                        ->where('nam','2017');
                })->get();

                $donvi->bienche= rand(20,30);
                $donvi->soluong=$donvi->bienche;
                $donvi->luongnb=$model_bangluong->sum('ttl');
                $donvi->ttbh=$model_bangluong->sum('ttbh_dv');
                $donvi->tpc=($model_bangluong->sum('tonghs')-$model_bangluong->sum('heso'))*1300000;
                $donvi->tongcong= $donvi->luongnb + $donvi->ttbh + $donvi->tpc;

                $model_dutoan=dutoanluong::where('madv',$donvi->madv)
                    ->where('namns','2018')->first();

                $donvi->luongnb=$model_bangluong->sum('ttl');
                $donvi->luongbh=$model_bangluong->sum('ttbh_dv');
                $donvi->luonghs=($model_bangluong->sum('tonghs')-$model_bangluong->sum('heso'))*1300000;
                $donvi->tongcong= $donvi->luongnb + $donvi->ttbh + $donvi->tpc;

                if(isset($model_dutoan)) {
                    $donvi->luongnb_dt = $model_dutoan->luongnb_dt;
                    $donvi->luongbh_dt = $model_dutoan->luongbh_dt;
                    $donvi->luonghs_dt = $model_dutoan->luonghs_dt;
                    $donvi->tongcong_dt = $donvi->luongnb_dt + $donvi->luongbh_dt + $donvi->luonghs_dt;
                }

                $makpb[]=$donvi->makhoipb;
            }
            $model_khoipb=dmkhoipb::wherein('makhoipb',$makpb)->get();
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name);

            return view('reports.bangluong.dutoanluong_tonghop')
                ->with('model_kpb',$model_khoipb)
                ->with('thongtin',$thongtin)
                ->with('model_dv',$model_dv)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

}
