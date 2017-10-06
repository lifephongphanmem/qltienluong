<?php

namespace App\Http\Controllers;

use App\dmkhoipb;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmkhoipbController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            //Chuyển khối phòng ban thành lĩnh vực hoạt động
            $model=dmkhoipb::all();
            return view('system.danhmuc.khoipb.index')
                ->with('model',$model)
                ->with('furl','/danh_muc/khoi_pb/')
                ->with('furl_ajax','/ajax/khoi_pb/')
                ->with('pageTitle','Danh mục lĩnh vực hoạt động');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
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
        $model = new dmkhoipb();
        $model->makhoipb = $inputs['makhoipb'];
        $model->tenkhoipb = $inputs['tenkhoipb'];
        $model->ghichu = $inputs['ghichu'];
        $model->save();

        $result['message'] = "Thêm mới thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
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
        $model = dmkhoipb::find($inputs['id']);
        $model->tenkhoipb = $inputs['tenkhoipb'];
        $model->ghichu = $inputs['ghichu'];
       $model->save();

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmkhoipb::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/khoi_pb/ma_so='.$model->level);
        }else
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
        $model = dmkhoipb::find($inputs['id']);
        die($model);
    }
}
