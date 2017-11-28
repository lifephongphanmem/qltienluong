<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\tonghop_huyen;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_huyen;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class xemdulieucapduoiController extends Controller
{
    public function donvi_luong(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            $madv = session('admin')->madv;
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $a_trangthai=array('ALL'=>'--Chọn trạng thái dữ liệu--','CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu');
            $list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();

            if(!isset($inputs['trangthai']) || $inputs['trangthai']=='ALL'){
                $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')->where('macqcq', $madv)->get();
            }else{
                $trangthai = $inputs['trangthai'];

                //có thời gian nên làm chỉ cần biết đơn vi cấp dưới gửi dữ liệu chưa
                //ko cần chi tiết đơn vị đã tạo nhưng chưa gửi => mặc định là chưa gửi hết
                switch($trangthai){
                    case 'DAGUI':{
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $trangthai, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai',$trangthai)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();
                        break;
                    }
                    default :{
                       //Đơn vị đã tổng hợp dữ liệu nhưng chưa gửi
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai','<>','DAGUI')
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        //Đơn vi chưa tổng hợp dữ liệu
                        $model_donvi_chuatao = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->where('macqcq',$madv)
                            ->wherenotin('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        foreach($model_donvi_chuatao as $donvi){
                            $model_donvi->add($donvi);
                        }

                        break;
                    }
                }
            }

            foreach($model_donvi as $dv){
                $donvi = $list_donvi->where('madv',$dv->macqcq)->first();
                $dv->tendvcq =(isset($donvi)?$donvi->tendv:NULL);
                $model_bangluong = tonghopluong_donvi::where('madv', $dv->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->first();

                if(isset($model_bangluong)){
                    $dv->mathdv = $model_bangluong->mathdv;
                    $model_bangluong_ct = tonghopluong_donvi_chitiet::where('mathdv', $dv->mathdv)->first();
                    $dv->tralai = $model_bangluong_ct->mathk != null?false:true;
                }else{
                    $dv->mathdv = NULL;
                    $dv->tralai = true;
                }
            }
            //dd($model_donvi->toarray());
            return view('functions.viewdata.index')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }

    public function index_huyen(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madvqlkv = session('admin')->madvqlkv;

            $a_trangthai=array('ALL'=>'--Chọn trạng thái dữ liệu--','CHOGUI'=>'Chưa gửi dữ liệu','CHONHAN'=>'Đã gửi dữ liệu');
            $a_phanloai=array('DONVI'=>'Dữ liệu tổng hợp của đơn vị','CAPDUOI'=>'Dữ liệu tổng hợp của các đơn vị cấp dưới');
            $list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();

            //lấy danh sách đơn vị quản lý khối
            $model_qlkhoi = dmdonvi::select('madv', 'tendv', DB::raw('"CAPDUOI" as phanloai'))
                ->wherein('madv', function($query) use($madvbc){
                    $query->select('macqcq')->from('dmdonvi')->where('madvbc',$madvbc)->distinct();
                })->get();
            //danh sách đơn vị gửi dữ liệu cho đơn vị quản lý khối và đơn vị quản lý khối.
            $model_donvi = dmdonvi::select('madv', 'tendv',DB::raw('"DONVI" as phanloai'))
                ->wherein('madv', function($query) use($madvqlkv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madvqlkv)
                        ->orwhere('madv',$madvqlkv)->get();
                })->get();
            //Gộp danh sách đơn vị
            foreach($model_qlkhoi as $donvi){
                $model_donvi->add($donvi);
            }

            foreach($model_donvi as $dv){
                $dulieu = tonghopluong_huyen::where('madv', $dv->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('phanloai', $dv->phanloai)
                    ->first();

                $tonghop = tonghop_huyen::where('madvbc',$madvbc)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->first();
                $dv->tralai = true;
                if(isset($tonghop)){
                    $model_bangluong_ct = tonghopluong_donvi_chitiet::where('matht', $tonghop->madvth)->first();
                    $dv->tralai =isset($model_bangluong_ct->mathh)?false:true;
                }

                if(isset($dulieu)){
                    $dv->phanloai = $dulieu->phanloai;
                    $dv->mathdv = $dulieu->mathdv;
                }else{
                    $dv->mathdv = NULL;
                }
                $dv->tenphanloai = isset($a_phanloai[$dv->phanloai]) ? $a_phanloai[$dv->phanloai]: '';

            }

            $model_donvi = $model_donvi->sortby('madv');
            if(isset($inputs['trangthai'])){
                if($inputs['trangthai']=='CHONHAN'){
                    $model_donvi = $model_donvi->where('mathdv','<>',null);
                }
                if($inputs['trangthai']=='CHOGUI'){
                    $model_donvi = $model_donvi->where('mathdv',null);
                }
            }

            return view('functions.viewdata.index_huyen')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }
}
