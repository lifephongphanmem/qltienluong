<?php

namespace App\Http\Controllers;

use App\GeneralConfigs;
use App\dmphanloaict;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class GeneralConfigsController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = GeneralConfigs::first(); 
            $a_mact=  array_column(dmphanloaict::select('tenct', 'macongtac', 'mact')->get()->toarray(), 'tenct', 'mact');
            $model->mact = isset($model->mact_tuyenthem)? $a_mact[$model->mact_tuyenthem]:'';
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
                ->with('a_mact', array_column(dmphanloaict::select('tenct', 'macongtac', 'mact')->get()->toarray(), 'tenct', 'mact'))
                ->with('pageTitle', 'Chỉnh sửa cấu hình hệ thống');

        } else
            return view('errors.notlogin');
    }

    public function update(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            if(isset($inputs['ipf1'])){
                $ipf1 = $request->file('ipf1');
                $inputs['ipf1'] = '1.'.$ipf1->getClientOriginalName();
                $ipf1->move(public_path() . '/data/huongdan/', $inputs['ipf1']);
                session('admin')->ipf1 = $inputs['ipf1'];
            }

            $model = GeneralConfigs::first();
            if($model != null){
                $model->tuoinu = chkDbl($inputs['tuoinu']);
                $model->tuoinam = chkDbl($inputs['tuoinam']);
                $model->thangnu = chkDbl($inputs['thangnu']);
                $model->thangnam = chkDbl($inputs['thangnam']);
                $model->luongcb = chkDbl($inputs['luongcb']);
                $model->tg_hetts = chkDbl($inputs['tg_hetts']);
                $model->tg_xetnl = chkDbl($inputs['tg_xetnl']);
                $model->thongbao = $inputs['thongbao'];
                $model->mact_tuyenthem = $inputs['mact_tuyenthem'];
                if(isset($inputs['ipf1'])){
                    $model->ipf1 = $inputs['ipf1'];
                }
                $model->save();
            }else{
                GeneralConfigs::create($inputs);
            }

            return redirect('/he_thong/quan_tri/he_thong');
        } else
            return view('errors.notlogin');
    }
}
