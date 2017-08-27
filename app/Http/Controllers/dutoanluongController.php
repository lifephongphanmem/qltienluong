<?php

namespace App\Http\Controllers;

use App\dutoanluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dutoanluongController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dutoanluong::where('madv',session('admin')->madv)->get();

            return view('manage.dutoanluong.index')
                ->with('furl','/nghiep_vu/quan_ly/du_toan/')
                ->with('furl_ajax','/ajax/du_toan/')
                ->with('model',$model)
                ->with('pageTitle','Danh sách dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function get_detail(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dutoanluong::find($inputs['id']);
        die($model);
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
        $inputs['madv']=session('admin')->madv;
        dutoanluong::create($inputs);
        /*
        $model = new chitieubienche();

        $model->macanbo = $inputs['macanbo'];
        $model->ngaytu = getDateTime($inputs['ngaytu']);
        $model->ngayden = getDateTime($inputs['ngayden']);
        $model->quanham = $inputs['quanham'];
        $model->chucvu = $inputs['chucvu'];

        $model->save();
        */
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
        $model = dutoanluong::find($inputs['id']);
        $model->update($inputs);
        /*
        $model->ngaytu = getDateTime($inputs['ngaytu']);
        $model->ngayden = getDateTime($inputs['ngayden']);
        $model->quanham = $inputs['quanham'];
        $model->chucvu = $inputs['chucvu'];
         $model->save();
        */


        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dutoanluong::find($id);
            $model->delete();
            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
