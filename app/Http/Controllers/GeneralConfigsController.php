<?php

namespace App\Http\Controllers;

use App\GeneralConfigs;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class GeneralConfigsController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = GeneralConfigs::first();
            return view('system.general.global.index')
                ->with('model', $model)
                ->with('pageTitle', 'Cấu hình hệ thống');

        } else
            return view('errors.notlogin');
    }

    public function edit()
    {
        if (Session::has('admin')) {
            $model = GeneralConfigs::first();
            return view('system.general.global.edit')
                ->with('model', $model)
                ->with('pageTitle', 'Chỉnh sửa cấu hình hệ thống');

        } else
            return view('errors.notlogin');
    }

    public function update(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = GeneralConfigs::first();
            $model->tuoinu = chkDbl($inputs['tuoinu']);
            $model->tuoinam = chkDbl($inputs['tuoinam']);
            $model->luongcb = chkDbl($inputs['luongcb']);
            $model->tg_hetts = chkDbl($inputs['tg_hetts']);
            $model->tg_xetnl = chkDbl($inputs['tg_xetnl']);
            $model->save();

            return redirect('/he_thong/quan_tri/he_thong');

        } else
            return view('errors.notlogin');
    }
}
