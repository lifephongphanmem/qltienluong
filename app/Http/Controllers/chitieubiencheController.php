<?php

namespace App\Http\Controllers;

use App\chitieubienche;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\hosocanbo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class chitieubiencheController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = chitieubienche::where('madv',session('admin')->madv)->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            return view('manage.chitieubienche.index')
                ->with('furl','/nghiep_vu/quan_ly/chi_tieu/')
                ->with('model',$model)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle','Danh sách chỉ tiêu biên chế của đơn vị');
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
        $model = chitieubienche::find($inputs['id']);
        die($model);
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
        $inputs['soluongduocgiao'] = chkDbl($inputs['soluongduocgiao']);
        $inputs['soluongbienche'] = chkDbl($inputs['soluongbienche']);
        $inputs['soluongkhongchuyentrach'] = chkDbl($inputs['soluongkhongchuyentrach']);
        $inputs['soluonguyvien'] = chkDbl($inputs['soluonguyvien']);
        $inputs['soluongdaibieuhdnd'] = chkDbl($inputs['soluongdaibieuhdnd']);
        if ($inputs['id'] == 'ADD') {
            //chưa bắt trùng nam + mact + madv
            $inputs['madv'] = session('admin')->madv;
            unset($inputs['id']);
            chitieubienche::create($inputs);
        } else {
            //unset($inputs['id']);
            chitieubienche::find($inputs['id'])->update($inputs);
        }
        $result['message'] = "Thêm mới thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }



    function destroy($id){
        if (Session::has('admin')) {
            $model = chitieubienche::find($id);
            $model->delete();
            return redirect('/nghiep_vu/quan_ly/chi_tieu/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
