<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmphanloaicongtac_baohiemController extends Controller
{
    // <editor-fold defaultstate="collapsed" desc="--Bảo hiểm--">
    function index()
    {
        if (Session::has('admin')) {
            $model = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            $model_phanloai = dmphanloaicongtac::all();
            if (count($model) == 0) {
                foreach ($model_phanloai as $ct) {
                    $new = new dmphanloaicongtac_baohiem();
                    $new->madv = session('admin')->madv;
                    $new->macongtac = $ct->macongtac;
                    $new->bhxh = $ct->bhxh;
                    $new->bhyt = $ct->bhyt;
                    $new->bhyt = $ct->bhyt;
                    $new->bhtn = $ct->bhtn;
                    $new->kpcd = $ct->kpcd;
                    $new->bhxh_dv = $ct->bhxh_dv;
                    $new->bhyt_dv = $ct->bhyt_dv;
                    $new->bhtn_dv = $ct->bhtn_dv;
                    $new->kpcd_dv = $ct->kpcd_dv;
                    $new->save();
                }
                $model = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            }
            $a_pl = array_column($model_phanloai->toArray(),'tencongtac','macongtac');

            foreach($model as $ct) {
                $ct->tencongtac = isset($a_pl[$ct->macongtac]) ? $a_pl[$ct->macongtac] : '';
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
        $model = dmphanloaicongtac::where('macongtac',$inputs['macongtac'])->first();
        $model_bh = dmphanloaicongtac_baohiem::where('madv',$inputs['madv'])
            ->where('macongtac',$inputs['macongtac'])->first();
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
        $model_bh = dmphanloaicongtac_baohiem::where('madv',$inputs['madv'])->where('macongtac',$inputs['macongtac'])->first();
        $model_bh->update($inputs);
        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }
    // </editor-fold>
}
