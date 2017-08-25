<?php

namespace App\Http\Controllers;

use App\dmkhoipb;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmkhoipbController extends Controller
{
    public function index($level){
        if (Session::has('admin')) {
            $a_baomat=array('KVHCSN'=>'Khu vực HCSN, Đảng, Đoàn thể','KVXP'=>'Khu vực xã, phường, thị trấn');
            $model=dmkhoipb::where('level',$level)->get();

            return view('system.danhmuc.khoipb.index')
                ->with('model',$model)
                ->with('a_baomat',$a_baomat)
                ->with('level',$level)
                ->with('furl','/danh_muc/khoi_pb/')
                ->with('furl_ajax','/ajax/khoi_pb/')
                ->with('pageTitle','Danh mục khối phòng ban');
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
        $model->level = $inputs['level'];
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
