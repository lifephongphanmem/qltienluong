<?php

namespace App\Http\Controllers;

use App\dmdonvibaocao;
use App\dmthongtuquyetdinh;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\nguonkinhphi;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_tinh;
use Illuminate\Support\Facades\Session;

class tonghopnguon_tinhController extends Controller
{
    public function index(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model_nguon_tinh= nguonkinhphi_tinh::where('sohieu',$inputs['sohieu'])->get();
            $model_nkp=nguonkinhphi::where('sohieu',$inputs['sohieu'])->get();
// dd($model_nkp->first());
            // dd($model_nguon_huyen);
            $model=dmdonvibaocao::where('baocao',1)->get();
            // dd($model);
            $a_trangthai=getStatus();
            foreach($model as $val){
                $model_nguon=$model_nguon_tinh->where('madvbc',$val->madvbc)->first();
                $m_nkp=$model_nkp->where('macqcq',$val->madvcq)->first();
                $val->trangthai=isset($model_nguon)?$model_nguon->trangthai:'CHUADL';
                $val->sohieu=isset($model_nguon)?$model_nguon->sohieu:'';
                $val->macqcq=isset($m_nkp)?$m_nkp->macqcq:'';
            }
            $soluong=0;
            // dd($model); 
            return view('functions.tonghopnguon.tinh.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong', $soluong)
                ->with('inputs', $inputs)
                ->with('a_dvbc',array_column($model->toArray(),'tendvbc','madvbc'))
                ->with('furl','/chuc_nang/tong_hop_nguon/tinh/')
                ->with('furl_xem','/chuc_nang/xem_du_lieu/nguon/tinh')
                ->with('furl_th','/chuc_nang/tong_hop_nguon/huyen/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp nguồn kinh phí');

        } else
        return view('errors.notlogin');
    }

    public function tralai(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            dd($inputs);
        } else
        return view('errors.notlogin');
    }
}
