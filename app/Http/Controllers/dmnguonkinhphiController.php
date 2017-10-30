<?php

namespace App\Http\Controllers;

use App\dmnguonkinhphi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmnguonkinhphiController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $model=dmnguonkinhphi::all();
            $a_phanloai = getPhanLoaiNguon();
            foreach($model as $ct){
                $ct->phanloai = isset($a_phanloai[ $ct->phanloai])?$a_phanloai[ $ct->phanloai]:'';
            }
            return view('system.danhmuc.nguonkinhphi.index')
                ->with('model',$model)
                ->with('a_phanloai',$a_phanloai)
                ->with('furl','/danh_muc/nguon_kinh_phi/')
                ->with('pageTitle','Danh mục nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        if($inputs['manguonkp']==''){$inputs['manguonkp']=getdate()[0];}
        dmnguonkinhphi::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmnguonkinhphi::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/nguon_kinh_phi/index');
        }else
            return view('errors.notlogin');
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
        $model = dmnguonkinhphi::where('manguonkp',$inputs['manguonkp'])->first();
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
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
        $model = dmnguonkinhphi::where('manguonkp',$inputs['manguonkp'])->first();
        die($model);
    }
}
