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
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class tonghopnguon_tinhController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nguon_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->where('trangthai','DAGUI')->get();
           
            $model = dmdonvibaocao::where('level', 'H')->get();
            // dd($model);
            $a_trangthai = getStatus();
            foreach ($model as $val) {
                $model_nguon = $model_nguon_tinh->where('madv', $val->madvcq)->first();
                $val->trangthai = $model_nguon->trangthai ?? 'CHUADL';
                $val->sohieu = $model_nguon->sohieu ?? $inputs['sohieu'];
                $val->masodv = $model_nguon->masodv ?? null;
              
            }
            //$soluong = 0;
            $inputs['trangthai'] = 'DAGUI'; //xem dữ liệu 
             //dd($model); 
            return view('functions.tonghopnguon.tinh.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                //->with('soluong', $soluong)
                ->with('inputs', $inputs)
                ->with('a_dvbc', array_column($model->toArray(), 'tendvbc', 'madvbc'))
                ->with('furl', '/chuc_nang/tong_hop_nguon/tinh/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/tinh')
                ->with('furl_th', '/chuc_nang/tong_hop_nguon/huyen/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi_tinh::where('masodv', $inputs['masodv'])->first();
            //dd($model);
            $model->trangthai = 'CHOGUI';
            $model->ngaylap = Carbon::now()->toDateTimeString();
            $model->save();
            return redirect('/chuc_nang/tong_hop_nguon/tinh/index?sohieu=' . $inputs['sohieu']);
        } else
            return view('errors.notlogin');
    }
}
