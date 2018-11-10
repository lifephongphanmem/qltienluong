<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class dmphanloaictController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $m_pb=dmphanloaicongtac::all();
            return view('system.danhmuc.congtac.index')
                ->with('model',$m_pb)
                ->with('furl','/danh_muc/cong_tac/')
                ->with('pageTitle','Danh mục nhóm phân loại công tác');
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
        $inputs['macongtac']=getdate()[0];
        dmphanloaicongtac::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmphanloaicongtac::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/cong_tac/index');
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
        $model = dmphanloaicongtac::where('macongtac',$inputs['macongtac'])->first();
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
        $model = dmphanloaicongtac::where('macongtac',$inputs['macongtac'])->first();
        /*
        //$model_bh = dmphanloaicongtac_baohiem::where('maphanloai',session('admin')->maphanloai)->where('macongtac',$inputs['macongtac'])->first();
        $model_bh = dmphanloaicongtac_baohiem::where('macongtac',$inputs['macongtac'])->first();
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
        */
        die($model);
    }

    public function detail($macongtac){
        if (Session::has('admin')) {
            $m_pb = dmphanloaict::where('macongtac', $macongtac)->get();
            $m_nhom = dmphanloaicongtac::where('macongtac', $macongtac)->first();
            return view('system.danhmuc.congtac.detail')
                ->with('model', $m_pb)
                ->with('m_nhom', $m_nhom)
                ->with('macongtac', $macongtac)
                ->with('furl', '/danh_muc/cong_tac/')
                ->with('pageTitle', 'Danh mục phân loại công tác');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request)
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
        $inputs['mact']=getdate()[0];
        dmphanloaict::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy_detail($id){
        if (Session::has('admin')) {
            $model = dmphanloaict::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/cong_tac/index');
        }else
            return view('errors.notlogin');
    }

    function update_detail(Request $request){
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

        $model = dmphanloaict::where('mact',$inputs['mact'])->first();
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function getinfo_detail(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphanloaict::where('mact',$inputs['mact'])->first();
        die($model);
    }


}
