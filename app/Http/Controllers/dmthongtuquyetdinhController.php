<?php

namespace App\Http\Controllers;

use App\dmthongtuquyetdinh;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmthongtuquyetdinhController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $model = dmthongtuquyetdinh::all();
            return view('system.danhmuc.thongtu.index')
                ->with('model', $model)
                ->with('furl', '/danh_muc/thong_tu/')
                ->with('pageTitle', 'Danh mục thông tư, quyết định');
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
        $inputs['namdt'] = date('Y', strtotime($inputs['ngayapdung'])) ?? date('Y');        
        $inputs['muccu'] = chkDbl($inputs['muccu']);
        $inputs['mucapdung'] = chkDbl($inputs['mucapdung']);
        $inputs['chenhlech'] = chkDbl($inputs['chenhlech']);
        if ($inputs['id'] == 'ADD') {
            $inputs['sohieu'] = str_replace("-", "_", chuanhoatruong($inputs['sohieu']));
            $inputs['sohieu'] = str_replace("*", "", $inputs['sohieu']);
            $inputs['sohieu'] = str_replace("%", "", $inputs['sohieu']);
            dmthongtuquyetdinh::create($inputs);
        } else {
            dmthongtuquyetdinh::find($inputs['id'])->update($inputs);
        }
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dmthongtuquyetdinh::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/thong_tu/index');
        } else
            return view('errors.notlogin');
    }

    function getinfo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmthongtuquyetdinh::where('id', $inputs['id'])->first();
        die($model);
    }
}
