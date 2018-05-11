<?php

namespace App\Http\Controllers;

use App\hosocanbo;
use App\hosotruylinh;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosotruylinhController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = hosocanbo::where('madv', session('admin')->madv)
                ->wherenotnull('truylinhtungay')
                ->get();
            //Select chọn lại về select2
            //Mã ngạch lương =====> chưa có
            //Lấy danh sách mã ngạch lương
            //Khi chon cán bộ lấy mã ngach lương set lại trên form nhập
            $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->get()->toarray(), 'tencanbo', 'macanbo');

            return view('manage.truylinh.index')
                ->with('model', $model)
                //->with('a_phanloai', $a_phanloai)
                ->with('a_canbo', $a_canbo)
                ->with('furl', '/nghiep_vu/truy_linh/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('pageTitle', 'Danh sách cán bộ được truy lĩnh lương');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {
            $insert = $request->all();

            $model = hosotruylinh::where('maso', $insert['maso'])->first();
            $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->get()->toarray(), 'tencanbo', 'macanbo');
            $insert['tencanbo'] = isset($a_canbo[$insert['macanbo']]) ? $a_canbo[$insert['macanbo']] : "";
            dd($insert);
            if(!isset($model)){
                hosotruylinh::create($insert);
            }else{
                $model->update($insert);
            }

            return redirect('nghiep_vu/truy_linh/danh_sach');
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
        $model = hosotamngungtheodoi::where('maso',$inputs['maso'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosotamngungtheodoi::find($id);
            //$macanbo = $model->macanbo;
            $model->delete();
            return redirect('/nghiep_vu/tam_ngung/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
