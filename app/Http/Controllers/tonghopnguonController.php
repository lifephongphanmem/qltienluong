<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopnguonController extends Controller
{
    public function index(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madv = session('admin')->madv;
            $model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])
                ->where('trangthai','DAGUI')
                ->where('macqcq', $madv)->get();

            $a_trangthai = getStatus();
            $model_donvi = dmdonvi::select('madv', 'tendv')->where('macqcq', $madv)->get();
            foreach($model_donvi as $dv){
                $nguon = $model_nguon->where('madv',$dv->madv)->first();
                if(isset($nguon)){
                    $dv->trangthai = $nguon->trangthai;
                    $dv->masodv = $nguon->masodv;
                }else{
                    $dv->trangthai = 'CHUATAO';
                    $dv->masodv = NULL;
                }
            }

            return view('functions.tonghopnguon.index')
                ->with('model', $model_donvi)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$model_nguon->count('madv').'/'.$model_donvi->count('madv'))
                ->with('furl','/chuc_nang/tong_hop_nguon/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp nguồn kinh phí');

        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            return redirect('/chuc_nang/tong_hop_nguon/index?sohieu=TT67/2017/TT-BTC');
        } else
            return view('errors.notlogin');
    }

    public function printf_khoi($sohieu){
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('sohieu',$sohieu)
                ->where('macqcq',session('admin')->madv)->get();

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $data[]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $data[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $data[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $data[]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế');
            $data[]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác');
            $data[]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể');
            $data[]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã');
            //cán bộ xã tính riêng
            for($i=0;$i<6;$i++){
                $nguon = $model->where('linhvuchoatdong',$data[$i]['val']);
                if(count($nguon) > 0){
                    $data[$i]['nhucau'] = $nguon->sum('nhucau');
                    $data[$i]['nguonkp'] = $nguon->sum('nguonkp');
                    $data[$i]['tietkiem'] = $nguon->sum('tietkiem');
                    $data[$i]['hocphi'] = $nguon->sum('hocphi');
                    $data[$i]['vienphi'] = $nguon->sum('vienphi');
                    $data[$i]['nguonthu'] = $nguon->sum('nguonthu');
                }else{
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['nguonthu'] = 0;
                }

            }
            //Số liệu cho cán bộ xã
            $nguon_xp = $model->where('maphanloai','KVXP');
            if(count($nguon_xp) > 0){
                $data[6]['nhucau'] = $nguon_xp->sum('nhucau');
                $data[6]['nguonkp'] = $nguon_xp->sum('nguonkp');
                $data[6]['tietkiem'] = $nguon_xp->sum('tietkiem');
                $data[6]['hocphi'] = $nguon_xp->sum('hocphi');
                $data[6]['vienphi'] = $nguon_xp->sum('vienphi');
                $data[6]['nguonthu'] = $nguon_xp->sum('nguonthu');
            }else{
                $data[6]['nhucau'] = 0;
                $data[6]['nguonkp'] = 0;
                $data[6]['tietkiem'] = 0;
                $data[6]['hocphi'] = 0;
                $data[6]['vienphi'] = 0;
                $data[6]['nguonthu'] = 0;
            }

            //Giáo dục + đào tạo
            $data[0]['nhucau'] = $data[1]['nhucau']+ $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;
            //dd($data);

            //Sự nghiệp khác

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[3]['nhucau'] - $data[5]['nhucau'];;
            $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[3]['nguonkp']- $data[5]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[3]['tietkiem']- $data[5]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[3]['hocphi']- $data[5]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[3]['vienphi']- $data[5]['vienphi'];
            $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[3]['nguonthu']- $data[5]['nguonthu'];

            return view('reports.thongtu67.donvi.mau4b')
                //->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');

    }

    public function index_huyen(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])
                ->where('trangthai','DAGUI')
                ->where('madvbc', $madvbc)->get();

            $a_trangthai = getStatus();
            $model_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)->get();
            foreach($model_donvi as $dv){
                $nguon = $model_nguon->where('madv',$dv->madv)->first();
                if(isset($nguon)){
                    $dv->trangthai = $nguon->trangthai;
                    $dv->masodv = $nguon->masodv;
                }else{
                    $dv->trangthai = 'CHUATAO';
                    $dv->masodv = NULL;
                }
            }

            return view('functions.tonghopnguon.index_huyen')
                ->with('model', $model_donvi)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$model_nguon->count('madv').'/'.$model_donvi->count('madv'))
                ->with('furl','/chuc_nang/tong_hop_nguon/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp nguồn kinh phí');

        } else
            return view('errors.notlogin');
    }

    public function printf_huyen($sohieu){
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('sohieu',$sohieu)
                ->where('madvbc',session('admin')->madvbc)->get();

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $data[]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $data[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $data[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $data[]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế');
            $data[]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác');
            $data[]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể');
            $data[]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã');
            //cán bộ xã tính riêng
            for($i=0;$i<6;$i++){
                $nguon = $model->where('linhvuchoatdong',$data[$i]['val']);
                if(count($nguon) > 0){
                    $data[$i]['nhucau'] = $nguon->sum('nhucau');
                    $data[$i]['nguonkp'] = $nguon->sum('nguonkp');
                    $data[$i]['tietkiem'] = $nguon->sum('tietkiem');
                    $data[$i]['hocphi'] = $nguon->sum('hocphi');
                    $data[$i]['vienphi'] = $nguon->sum('vienphi');
                    $data[$i]['nguonthu'] = $nguon->sum('nguonthu');
                }else{
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['nguonthu'] = 0;
                }

            }
            //Số liệu cho cán bộ xã
            $nguon_xp = $model->where('maphanloai','KVXP');
            if(count($nguon_xp) > 0){
                $data[6]['nhucau'] = $nguon_xp->sum('nhucau');
                $data[6]['nguonkp'] = $nguon_xp->sum('nguonkp');
                $data[6]['tietkiem'] = $nguon_xp->sum('tietkiem');
                $data[6]['hocphi'] = $nguon_xp->sum('hocphi');
                $data[6]['vienphi'] = $nguon_xp->sum('vienphi');
                $data[6]['nguonthu'] = $nguon_xp->sum('nguonthu');
            }else{
                $data[6]['nhucau'] = 0;
                $data[6]['nguonkp'] = 0;
                $data[6]['tietkiem'] = 0;
                $data[6]['hocphi'] = 0;
                $data[6]['vienphi'] = 0;
                $data[6]['nguonthu'] = 0;
            }

            //Giáo dục + đào tạo
            $data[0]['nhucau'] = $data[1]['nhucau']+ $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;
            //dd($data);

            //Sự nghiệp khác

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[3]['nhucau'] - $data[5]['nhucau'];;
            $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[3]['nguonkp']- $data[5]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[3]['tietkiem']- $data[5]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[3]['hocphi']- $data[5]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[3]['vienphi']- $data[5]['vienphi'];
            $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[3]['nguonthu']- $data[5]['nguonthu'];

            return view('reports.thongtu67.donvi.mau4b')
                //->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');

    }

    public function index_tinh(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = $inputs['madiaban'];
            //$madvqlkv = dmdonvibaocao::where('madvbc',$madvbc)->first()->madvcq;
            $model_dvbc = dmdonvibaocao::all();

            $model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])
                ->where('trangthai','DAGUI')
                ->where('madvbc', $madvbc)->get();

            $a_trangthai = getStatus();
            $model_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)->get();
            foreach($model_donvi as $dv){
                $nguon = $model_nguon->where('madv',$dv->madv)->first();
                if(isset($nguon)){
                    $dv->trangthai = $nguon->trangthai;
                    $dv->masodv = $nguon->masodv;
                }else{
                    $dv->trangthai = 'CHUATAO';
                    $dv->masodv = NULL;
                }
            }

            return view('functions.tonghopnguon.index_tinh')
                ->with('model', $model_donvi)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$model_nguon->count('madv').'/'.$model_donvi->count('madv'))
                ->with('madvbc',$madvbc)
                ->with('a_dvbc',array_column($model_dvbc->toArray(),'tendvbc','madvbc'))
                ->with('furl','/chuc_nang/tong_hop_nguon/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp nguồn kinh phí');

        } else
            return view('errors.notlogin');
    }

    public function printf_tinh(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('sohieu',$inputs['sohieu'])
                ->where('madvbc',$inputs['madiban'])->get();
            $model_thongtu=dmthongtuquyetdinh::where('sohieu',$inputs['sohieu'])->first();
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $data[]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $data[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $data[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $data[]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế');
            $data[]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác');
            $data[]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể');
            $data[]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã');
            //cán bộ xã tính riêng
            for($i=0;$i<6;$i++){
                $nguon = $model->where('linhvuchoatdong',$data[$i]['val']);
                if(count($nguon) > 0){
                    $data[$i]['nhucau'] = $nguon->sum('nhucau');
                    $data[$i]['nguonkp'] = $nguon->sum('nguonkp');
                    $data[$i]['tietkiem'] = $nguon->sum('tietkiem');
                    $data[$i]['hocphi'] = $nguon->sum('hocphi');
                    $data[$i]['vienphi'] = $nguon->sum('vienphi');
                    $data[$i]['nguonthu'] = $nguon->sum('nguonthu');
                }else{
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['nguonthu'] = 0;
                }

            }
            //Số liệu cho cán bộ xã
            $nguon_xp = $model->where('maphanloai','KVXP');
            if(count($nguon_xp) > 0){
                $data[6]['nhucau'] = $nguon_xp->sum('nhucau');
                $data[6]['nguonkp'] = $nguon_xp->sum('nguonkp');
                $data[6]['tietkiem'] = $nguon_xp->sum('tietkiem');
                $data[6]['hocphi'] = $nguon_xp->sum('hocphi');
                $data[6]['vienphi'] = $nguon_xp->sum('vienphi');
                $data[6]['nguonthu'] = $nguon_xp->sum('nguonthu');
            }else{
                $data[6]['nhucau'] = 0;
                $data[6]['nguonkp'] = 0;
                $data[6]['tietkiem'] = 0;
                $data[6]['hocphi'] = 0;
                $data[6]['vienphi'] = 0;
                $data[6]['nguonthu'] = 0;
            }

            //Giáo dục + đào tạo
            $data[0]['nhucau'] = $data[1]['nhucau']+ $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;
            //dd($data);

            //Sự nghiệp khác

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[3]['nhucau'] - $data[5]['nhucau'];;
            $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[3]['nguonkp']- $data[5]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[3]['tietkiem']- $data[5]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[3]['hocphi']- $data[5]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[3]['vienphi']- $data[5]['vienphi'];
            $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[3]['nguonthu']- $data[5]['nguonthu'];

            return view('reports.thongtu67.donvi.mau4b')
                // ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('model_thongtu',$model_thongtu)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');

    }
}
