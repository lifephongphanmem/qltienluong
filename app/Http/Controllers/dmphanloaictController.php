<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaict;
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
        die($model);
    }

    public function detail($macongtac){
        if (Session::has('admin')) {
            $m_pb=dmphanloaict::where('macongtac',$macongtac)->get();
            return view('system.danhmuc.congtac.detail')
                ->with('model',$m_pb)
                ->with('macongtac',$macongtac)
                ->with('furl','/danh_muc/cong_tac/')
                ->with('pageTitle','Danh mục phân loại công tác');
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
