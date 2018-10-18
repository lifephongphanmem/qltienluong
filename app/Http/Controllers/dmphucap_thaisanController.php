<?php

namespace App\Http\Controllers;

use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmphucap_thaisanController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = dmphucap_thaisan::where('madv', session('admin')->madv)->get();
            $a_pc = array('heso', 'vuotkhung', 'hesott','hesobl');
            $a_pc = array_merge($a_pc, array_column($model->toarray(), 'mapc'));
            $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', $a_pc)->get();

            return view('system.danhmuc.phucapthaisan.index')
                ->with('model', $model)
                ->with('model_phucap', array_column($model_phucap->toarray(), 'tenpc', 'mapc'))
                ->with('furl', '/danh_muc/thai_san/')
                ->with('pageTitle', 'Thông tin phân loại phụ cấp thai sản');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['tenpc'] = dmphucap_donvi::where('mapc',$inputs['mapc'])->first()->tenpc;
            $inputs['madv'] = session('admin')->madv;
            dmphucap_thaisan::create($inputs);
            return redirect('/danh_muc/thai_san/danh_sach');
        } else
            return view('errors.notlogin');
    }


    function destroy($id){
        if (Session::has('admin')) {
            $model = dmphucap_thaisan::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/thai_san/danh_sach');
        }else
            return view('errors.notlogin');
    }
}
