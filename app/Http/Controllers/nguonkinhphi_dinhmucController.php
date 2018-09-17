<?php

namespace App\Http\Controllers;

use App\nguonkinhphi;
use App\nguonkinhphi_dinhmuc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class nguonkinhphi_dinhmucController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi_dinhmuc::where('madv', session('admin')->madv)->get();
            $a_pl = getNguonKP(false);
            foreach($model as $ct){
                $ct->tennguonkp = isset($a_pl[$ct->manguonkp]) ? $a_pl[$ct->manguonkp] : '';
            }
            return view('system.danhmuc.dinhmucnguon.index')
                ->with('model', $model)
                ->with('furl', '/he_thong/dinh_muc/')
                ->with('pageTitle', 'Danh mục định mức nguồn');
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
        $model = nguonkinhphi_dinhmuc::where('maso',$inputs['maso'])->first();
        $a_pl = getNguonKP(false);
        $model->tennguonkp = isset($a_pl[$model->manguonkp]) ? $a_pl[$model->manguonkp] : '';
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
        $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
        $model = nguonkinhphi_dinhmuc::where('maso',$inputs['maso'])->first();
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

}
