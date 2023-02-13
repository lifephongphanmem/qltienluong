<?php

namespace App\Http\Controllers\baocao\donvi;

use App\dutoanluong;
use App\Http\Controllers\Controller;
use App\Http\Controllers\dutoanluong_insolieuController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class dutoanluongController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Session::has('admin')) {
                return redirect('/');
            };
            return $next($request);
        });
    }

    function tonghopcanboxa(Request $request)
    {
        $inputs = $request->all();
        $model = dutoanluong::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->first();
        if ($model != null)
            return redirect('/nghiep_vu/quan_ly/du_toan/tonghopcanboxa?maso=' . $model->masodv);
        else {
            return view('errors.nodata')->with('message', 'Dự toán năm ' . $inputs['namns'] . ' chưa được khởi tạo.');
        }
    }

    function kinhphikhongchuyentrach(Request $request)
    {
        $inputs = $request->all();
        $model = dutoanluong::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->first();
        if ($model != null)
            return redirect('/nghiep_vu/quan_ly/du_toan/kinhphikhongchuyentrach?maso=' . $model->masodv);
        else {
            return view('errors.nodata')->with('message', 'Dự toán năm ' . $inputs['namns'] . ' chưa được khởi tạo.');
        }
    }

    function tonghopbienche(Request $request)
    {
        $inputs = $request->all();
        $model = dutoanluong::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->first();
        if ($model != null) {
            $request->merge(['masodv' => $model->masodv, 'mact'=>['1506672780','1506673604']]);//chưa làm chọn danh sách mact            
            $con = new dutoanluong_insolieuController();
            return $con->tonghopbienche($request);
        } else {
            return view('errors.nodata')->with('message', 'Dự toán năm ' . $inputs['namns'] . ' chưa được khởi tạo.');
        }
    }

    function tonghophopdong(Request $request)
    {
        $inputs = $request->all();
        $model = dutoanluong::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->first();
        if ($model != null) {
            $request->merge(['masodv' => $model->masodv, 'mact'=>['1506673585']]);//chưa làm chọn danh sách mact            
            $con = new dutoanluong_insolieuController();
            return $con->tonghophopdong($request);
        }else {
            return view('errors.nodata')->with('message', 'Dự toán năm ' . $inputs['namns'] . ' chưa được khởi tạo.');
        }
    }
}
