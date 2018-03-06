<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class phanloaiphucapController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = dmdonvi::where('madv', session('admin')->madv)->first();

            return view('system.danhmuc.phucap.index')
                ->with('model', $model)
                ->with('furl', '/he_thong/don_vi/')
                ->with('pageTitle', 'Thông tin phân loại phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $model = dmdonvi::where('madv',$insert['madv'])->first();

            $model->update($insert);
            return redirect('/he_thong/don_vi/phu_cap');
        }else
            return view('errors.notlogin');
    }

}
