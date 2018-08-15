<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dutoanluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dutoanluong_khoiController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            $model_nguon = dutoanluong::where('trangthai','DAGUI')->where('macqcq', $madv)->get();
            $model_nguon_khoi = dutoanluong_khoi::where('madv', $madv)->get();
            $model = dutoanluong::select('namns')->where('madvbc', session('admin')->madvbc)->distinct()->get();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $soluong = $model_donvi->count();

            foreach($model as $dv){
                $nguon_khoi = $model_nguon_khoi->where('namns', $dv->namns)->first();
                if(count($nguon_khoi)>0){
                    //Đã tổng hợp dữ liệu
                    $dv->sldv = $soluong . '/' . $soluong;
                    $dv->masodv = $nguon_khoi->masodv;
                    $dv->trangthai = $nguon_khoi->trangthai;
                    //$dv->trangthai = 'DAGUI';
                }else{
                    //Chưa tổng hợp dữ liệu
                    $sl = $model_nguon->where('namns', $dv->namns)->count();
                    $dv->sldv = $sl . '/' . $soluong;
                    $dv->masodv = null;
                    if($sl==0){
                        $dv->trangthai = 'CHUADL';
                    }elseif($sl < $soluong){
                        $dv->trangthai = 'CHUADAYDU';
                    }else{
                        $dv->trangthai = 'CHUATAO';
                    }
                }
            }

            return view('functions.dutoanluong.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$soluong)
                ->with('furl_xem','/chuc_nang/xem_du_lieu/du_toan/khoi')
                ->with('furl_th','/chuc_nang/du_toan_luong/khoi/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp dự toán lương');

        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();

            return redirect('/chuc_nang/xem_du_lieu/du_toan/khoi?namns=' . $model->namns . '&trangthai=ALL');
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $madv = session('admin')->madv;
            $model_nguon_khoi = dutoanluong_khoi::where('namns',$inputs['namns'])->where('madv', $madv)->first();
            if (count($model_nguon_khoi) > 0) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_khoi->trangthai = 'DAGUI';
                $model_nguon_khoi->nguoilap = session('admin')->name;
                $model_nguon_khoi->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_khoi->save();
                dutoanluong_huyen::where('namns',$inputs['namns'])->where('madv', $madv)
                    ->update(['trangthai' => 'DAGUI', 'nguoilap' => session('admin')->name,'ngaylap'=> Carbon::now()->toDateTimeString()]);

            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                dutoanluong::where('namns',$inputs['namns'])->where('macqcq', $madv)
                    ->update(['masok' => $inputs['masodv'], 'masoh' => $inputs['masodv']]);

                //import vào bảng nguonkinhphi_khoi do nguonkinhphi_khoi(TH) = nguonkinhphi(SD)
                dutoanluong_khoi::create($inputs);
                dutoanluong_huyen::create($inputs);
            }
            return redirect('/chuc_nang/du_toan_luong/khoi/index');
        } else
            return view('errors.notlogin');
    }

    //In dữ liệu 1 đơn vị trong khối
    function printf(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();

            $a_nhomct = getNhomCongTac(false);
            foreach($model as $ct){
                $ct->tencongtac = isset($a_nhomct[$ct->macongtac])? $a_nhomct[$ct->macongtac]:'';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;
            }
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $thongtin = array('nam' => $model_thongtin->namns);


            return view('reports.viewdata.dutoan.donvi')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết dự toán lương tại đơn vị.');
        } else
            return view('errors.notlogin');
    }

    //Tổng hợp dữ liệu trong khối
    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //lấy dự toán lương chi tiết

            if(!isset($inputs['madv'])){
                $inputs['madv'] = session('admin')->madv;
            }
            $model = dutoanluong_chitiet::wherein('masodv', function($query) use ($inputs){
                $query->select('masodv')->from('dutoanluong')->wherein('madv', function($q) use ($inputs){
                    $q->select('madv')->from('dmdonvi')->where('macqcq',$inputs['madv'])->get();
                })->where('namns',$inputs['namns'])->get();
            })->get();

            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use ($inputs){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$inputs['madv'])->where('madv','<>',$inputs['madv'])->get();
                })->get();

            $model_dutoan = dutoanluong::wherein('madv', function($query) use ($inputs){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$inputs['madv'])->get();
            })->get();

            $a_nhomct = getNhomCongTac(false);

            foreach($model as $ct) {
                $dutoan = $model_dutoan->where('masodv', $ct->masodv)->first();
                $ct->madv = count($dutoan) > 0 ? $dutoan->madv : null;
                $ct->tencongtac = isset($a_nhomct[$ct->macongtac]) ? $a_nhomct[$ct->macongtac] : '';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;
            }

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            $thongtin = array('nam' => $inputs['namns']);

            return view('reports.viewdata.dutoan.khoi')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_donvi', $model_donvi)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Tổng hợp dự toán lương.');
        } else
            return view('errors.notlogin');
    }
}
