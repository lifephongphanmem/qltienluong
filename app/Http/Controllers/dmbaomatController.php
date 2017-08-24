<?php

namespace App\Http\Controllers;

use App\dmbaomat;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmbaomatController extends Controller
{
    public function index(Request $request){
        if (Session::has('admin')) {
            if(!session('admin')->level=='SA' && !session('admin')->level=='SSA'){
                return view('errors.noperm');
            }

            $a_baomat=array('X'=>'Cấp xã','H'=>'Cấp huyện','T'=>'Cấp tỉnh');

            $model=dmbaomat::where('level',$request->level)->get();
            return view('system.danhmuc.baomat.index')
                ->with('model',$model)
                ->with('a_baomat',$a_baomat)
                ->with('level',$request->level)
                ->with('furl','/danh_muc/bao_mat/')
                ->with('furl_ajax','/ajax/bao_mat/')
                ->with('pageTitle','Danh mục phân cấp bảo mật');
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

        dmbaomat::create( $request->all());

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
        $model = dmbaomat::find($inputs['id']);
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmbaomat::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/bao_mat/ma_so='.session('admin')->level);
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
        $model = dmbaomat::find($inputs['id']);
        die($model);
    }
}
