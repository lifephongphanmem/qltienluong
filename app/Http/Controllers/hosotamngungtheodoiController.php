<?php

namespace App\Http\Controllers;

use App\hosocanbo;
use App\hosotamngungtheodoi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosotamngungtheodoiController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = hosotamngungtheodoi::where('madv', session('admin')->madv)->get();
            $a_phanloai = getPhanLoaiTamNgungTheoDoi();
            $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->where('theodoi','<','9')->get()->toarray(), 'tencanbo', 'macanbo');
            foreach ($model as $hs) {
                $hs->phanloai = isset($a_phanloai[$hs->maphanloai]) ? $a_phanloai[$hs->maphanloai] : "";
                $hs->tencanbo = isset($a_canbo[$hs->macanbo]) ? $a_canbo[$hs->macanbo] : "";
            }

            return view('manage.tamngungtheodoi.index')
                ->with('model', $model)
                ->with('a_phanloai', $a_phanloai)
                ->with('a_canbo', $a_canbo)
                ->with('furl', '/nghiep_vu/tam_ngung/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('pageTitle', 'Danh sách cán bộ tạm ngưng theo dõi');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {

            $insert = $request->all();
            $insert['maso'] = session('admin')->madv .'_'.getdate()[0];
            $insert['ngayden']=getDateTime($insert['ngayden']);
            $insert['songaynghi'] = chkDbl($insert['songaynghi']);
            $insert['madv'] = session('admin')->madv;
            hosotamngungtheodoi::create($insert);
            /*
            dd($insert);
            $model = hosotamngungtheodoi::where('maso', $insert['maso'])->first();

            if(count($model)==0){
                $insert['maso'] = session('admin')->madv .'_'.getdate()[0];

            }else{
                $model->update($insert);
            }
            */

            return redirect('nghiep_vu/tam_ngung/danh_sach');
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

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            if($inputs['phanloai'] == 'THAISAN'){
               $a_phanloai = array('THAISAN' => 'Nghỉ thai sản');
                $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->where('theodoi','<','9')->where('gioitinh','Nữ')->get()->toarray(), 'tencanbo', 'macanbo');
            }else{
                $a_phanloai = array(
                    'NGHIPHEP'=> 'Nghỉ phép',
                    'NGHIOM'=> 'Nghỉ ốm');
                $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->where('theodoi','<','9')->get()->toarray(), 'tencanbo', 'macanbo');
            }
            return view('manage.tamngungtheodoi.create')
                ->with('furl', '/nghiep_vu/tam_ngung/')
                ->with('inputs',$inputs)
                ->with('a_phanloai',$a_phanloai)
                ->with('a_canbo',$a_canbo)
                ->with('pageTitle', 'Thêm mới cán bộ tạm ngưng theo dõi');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {

            $insert = $request->all();
            $model = hosotamngungtheodoi::where('maso', $insert['maso'])->first();
            $insert['ngayden'] = getDateTime($insert['ngayden']);
            $insert['songaynghi'] = chkDbl($insert['songaynghi']);
            $model->update($insert);
            return redirect('nghiep_vu/tam_ngung/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = hosotamngungtheodoi::where('maso',$inputs['maso'])->first();
            if($model->maphanloai == 'THAISAN'){
                $a_phanloai = array('THAISAN' => 'Nghỉ thai sản');
                $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->where('theodoi','<','9')->where('gioitinh','Nữ')->get()->toarray(), 'tencanbo', 'macanbo');
            }else{
                $a_phanloai = array(
                    'NGHIPHEP'=> 'Nghỉ phép',
                    'NGHIOM'=> 'Nghỉ ốm');
                $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->where('theodoi','<','9')->get()->toarray(), 'tencanbo', 'macanbo');
            }
            $inputs['phanloai'] = $model->maphanloai;

            return view('manage.tamngungtheodoi.edit')
                ->with('model', $model)
                ->with('a_phanloai',$a_phanloai)
                ->with('a_canbo',$a_canbo)
                ->with('inputs',$inputs)
                ->with('furl', '/nghiep_vu/tam_ngung/')
                ->with('pageTitle', 'Sửa thông tin tạm ngưng theo dõi');
        } else
            return view('errors.notlogin');
    }
}
