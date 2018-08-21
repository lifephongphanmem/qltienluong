<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\nguonkinhphi;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\nguonkinhphi_tinh;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class xemdulieu_nguonController extends Controller
{
    public function index_khoi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');

            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $model_nguon = nguonkinhphi::wherein('madv', function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
            })->get();

            $model_nguon_khoi = nguonkinhphi_huyen::where('madv', $madv)->get();

            foreach($model_donvi as $dv){
                //kiểm tra xem đã tổng hợp thành dữ liệu khối  gửi lên huyện chưa?
                $nguon_khoi = $model_nguon_khoi->where('sohieu',$inputs['sohieu'])->first();
                if(count($nguon_khoi)>0  && $nguon_khoi->trangthai == 'DAGUI'){
                    $dv->tralai = false;
                }else{
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('sohieu',$inputs['sohieu'])->where('madv',$dv->madv)->first();
                if(count($nguon)> 0 && $nguon->trangthai == 'DAGUI'){
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                }else{
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai',$inputs['trangthai']);
            }

            return view('functions.viewdata.nguonkinhphi.index')
                ->with('model', $model_donvi)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('furl_th', 'chuc_nang/tong_hop_nguon/khoi/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/khoi')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn');

        } else
            return view('errors.notlogin');
    }

    public function index_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');

            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $model_nguon = nguonkinhphi_huyen::wherein('madv', function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
            })->get();

            $model_nguon_khoi = nguonkinhphi_tinh::where('madv', $madv)->get();

            foreach($model_donvi as $dv){
                //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
                $nguon_khoi = $model_nguon_khoi->where('sohieu',$inputs['sohieu'])->first();
                if(count($nguon_khoi)>0 && $nguon_khoi->trangthai == 'DAGUI'){
                    $dv->tralai = false;
                }else{
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('sohieu',$inputs['sohieu'])->where('madv',$dv->madv)->first();
                if(count($nguon)> 0 && $nguon->trangthai == 'DAGUI'){
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                }else{
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai',$inputs['trangthai']);
            }

            return view('functions.viewdata.nguonkinhphi.index')
                ->with('model', $model_donvi)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('furl_th', 'chuc_nang/tong_hop_nguon/huyen/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/huyen')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn');

        } else
            return view('errors.notlogin');
    }
}