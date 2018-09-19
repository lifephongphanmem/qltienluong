<?php

namespace App\Http\Controllers;

use App\dmphucap_donvi;
use App\nguonkinhphi;
use App\nguonkinhphi_dinhmuc;
use App\nguonkinhphi_dinhmuc_ct;
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

    public function phucap(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nguon = nguonkinhphi_dinhmuc::where('maso', $inputs['maso'])->first();

            $model = nguonkinhphi_dinhmuc_ct::where('maso', $inputs['maso'])->get();
            $a_pc =  array_column($model->toarray(), 'mapc');

            $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', $a_pc)->get();
            //$model_phucap = dmphucap_donvi::wherenotin('mapc', $a_pc)->get();
            return view('system.danhmuc.dinhmucnguon.details')
                ->with('model', $model)
                ->with('model_nguon', $model_nguon)
                ->with('model_phucap', array_column($model_phucap->toarray(), 'tenpc', 'mapc'))
                ->with('furl', '/he_thong/dinh_muc/')
                ->with('pageTitle', 'Danh mục định mức nguồn');
        } else
            return view('errors.notlogin');
    }

    function store_pc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['tenpc'] = dmphucap_donvi::where('madv', session('admin')->madv)->where('mapc',$inputs['mapc'])->first()->tenpc;
            $inputs['madv'] = session('admin')->madv;
            nguonkinhphi_dinhmuc_ct::create($inputs);
            return redirect('/he_thong/dinh_muc/phu_cap?maso='.$inputs['maso']);
        } else
            return view('errors.notlogin');
    }


    function destroy_pc($id){
        if (Session::has('admin')) {
            $model = nguonkinhphi_dinhmuc_ct::findOrFail($id);
            $model->delete();
            return redirect('/he_thong/dinh_muc/phu_cap?maso='.$model->maso);
        }else
            return view('errors.notlogin');
    }
}
