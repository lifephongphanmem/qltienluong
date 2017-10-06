<?php

namespace App\Http\Controllers;

use App\dmphanloaidonvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmphanloaidonviController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $model=dmphanloaidonvi::all();
            return view('system.danhmuc.phanloaidonvi.index')
                ->with('model',$model)
                ->with('furl','/danh_muc/pl_don_vi/')
                ->with('pageTitle','Danh mục phân loại đơn vị');
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
        if($inputs['maphanloai']==''){$inputs['maphanloai']=getdate()[0];}
        dmphanloaidonvi::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmphanloaidonvi::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/pl_don_vi/index');
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
        $model = dmphanloaidonvi::where('maphanloai',$inputs['maphanloai'])->first();
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
        $model = dmphanloaidonvi::where('maphanloai',$inputs['maphanloai'])->first();
        die($model);
    }
}
