<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\hosocanbo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmphanloaicongtac_baohiemController extends Controller
{
    // <editor-fold defaultstate="collapsed" desc="--Bảo hiểm--">
    function index(){
        if (Session::has('admin')) {
            $model = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            $model_phanloai = dmphanloaict::all();
            $a_pl = array_column($model_phanloai->toArray(),'tenct','mact');

            foreach($model as $ct) {
                $ct->tencongtac = isset($a_pl[$ct->mact]) ? $a_pl[$ct->mact] : '';
            }
            return view('system.danhmuc.mucbaohiem.index')
                ->with('model', $model)
                ->with('furl', '/he_thong/don_vi/')
                ->with('pageTitle', 'Thông tin mức đóng bảo hiểm');
        } else
            return view('errors.notlogin');
    }

    function getinfo(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = dmphanloaict::where('mact',$inputs['mact'])->first();
        $model_bh = dmphanloaicongtac_baohiem::where('madv',$inputs['madv'])
            ->where('mact',$inputs['mact'])->first();
        if(count($model_bh)>0){
            $model->bhxh = $model_bh->bhxh;
            $model->bhyt = $model_bh->bhyt;
            $model->kpcd = $model_bh->kpcd;
            $model->bhtn = $model_bh->bhtn;
            $model->bhxh_dv = $model_bh->bhxh_dv;
            $model->bhyt_dv = $model_bh->bhyt_dv;
            $model->kpcd_dv = $model_bh->kpcd_dv;
            $model->bhtn_dv = $model_bh->bhtn_dv;
        }

        die($model);
    }

    function update(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model_bh = dmphanloaicongtac_baohiem::where('madv',$inputs['madv'])->where('mact',$inputs['mact'])->first();
        $model_bh->update($inputs);
        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function capnhat(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_bh = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->where('mact', $inputs['mact_bh'])->first();
            //chia làm 2 nhóm
            //công chức
            hosocanbo::where('madv', session('admin')->madv)->where('mact', $inputs['mact_bh'])->where('sunghiep', 'Công chức')
                ->update([
                        'bhxh' => $model_bh->bhxh,
                        'bhyt' => $model_bh->bhyt,
                        'bhtn' => 0,
                        'kpcd' => $model_bh->kpcd,
                        'bhxh_dv' => $model_bh->bhxh_dv,
                        'bhyt_dv' => $model_bh->bhyt_dv,
                        'bhtn_dv' => 0,
                        'kpcd_dv' => $model_bh->kpcd_dv
                    ]);
            //viên chức
            hosocanbo::where('madv', session('admin')->madv)->where('mact', $inputs['mact_bh'])->where('sunghiep','<>','Công chức')
                ->update([
                    'bhxh' => $model_bh->bhxh,
                    'bhyt' => $model_bh->bhyt,
                    'bhtn' => $model_bh->bhtn,
                    'kpcd' => $model_bh->kpcd,
                    'bhxh_dv' => $model_bh->bhxh_dv,
                    'bhyt_dv' => $model_bh->bhyt_dv,
                    'bhtn_dv' => $model_bh->bhtn_dv,
                    'kpcd_dv' => $model_bh->kpcd_dv
                ]);

            return redirect('/he_thong/don_vi/bao_hiem');
        } else
            return view('errors.notlogin');
    }
    // </editor-fold>
}
