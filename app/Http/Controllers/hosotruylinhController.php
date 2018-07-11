<?php

namespace App\Http\Controllers;

use App\hosocanbo;
use App\hosotruylinh;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosotruylinhController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            //Khi chon cán bộ lấy mã ngach lương set lại trên form nhập
            $model_canbo = hosocanbo::where('madv', session('admin')->madv)
                ->wherenotin('macanbo', function ($qr) {
                    $qr->select('macanbo')->from('hosotruylinh')
                        ->whereNull('mabl')
                        ->where('madv', session('admin')->madv)->get();
                })->get();
            $m_plnb = nhomngachluong::select('manhom', 'tennhom', 'heso', 'namnb')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong', 'manhom', 'msngbac', 'heso', 'namnb')->get();
            $model = hosotruylinh::where('madv', session('admin')->madv)->get();
            /* Hiển thị mã ngạch dễ nhớ hơn tên ngạch lương
            foreach ($model as $ct) {
                $ngachbac = $m_pln->where('msngbac', $ct->msngbac)->first();
                $ct->tenngachluong = count($ngachbac) > 0 ? $ngachbac->tenngachluong : '';
            }
            */
            //dd($m_pln);
            return view('manage.truylinh.index')
                ->with('model', $model)
                ->with('m_plnb', $m_plnb)
                ->with('m_pln', $m_pln)
                //->with('a_phanloai', $a_phanloai)
                ->with('a_canbo', array_column($model_canbo->toarray(), 'tencanbo', 'macanbo'))
                ->with('furl', '/nghiep_vu/truy_linh/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('pageTitle', 'Danh sách cán bộ được truy lĩnh lương');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $a_canbo = hosocanbo::select('tencanbo')->where('macanbo', $insert['macanbo'])->first();
            if(count($a_canbo) == 0){return redirect('nghiep_vu/truy_linh/danh_sach');}
            $insert['tencanbo'] = $a_canbo->tencanbo;
            $insert['stt'] = $a_canbo->stt;
            $insert['maso'] = session('admin')->madv . '_' . getdate()[0];
            hosotruylinh::create($insert);
            return redirect('nghiep_vu/truy_linh/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $model = hosotruylinh::where('maso', $insert['maso_edit'])->first();
            $insert['ngaytu'] = $insert['ngaytu_edit'];
            $insert['msngbac'] = $insert['msngbac_edit'];
            $insert['hesott'] = $insert['hesott_edit'];
            $model->update($insert);
            return redirect('nghiep_vu/truy_linh/danh_sach');
        } else
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
        $model = hosotruylinh::where('maso',$inputs['maso'])->first();
        //dd($model);
        die($model);
    }

    function get_thongtin_canbo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = hosocanbo::where('macanbo', $inputs['maso'])->first();
        if (count($model) == 0) {
            die($model);
        }

        $model_nb = ngachluong::where('msngbac', $model->msngbac)->first();
        if (count($model_nb) == 0) {
            die($model);
        }//cán bộ ko có msngbac => ko tính toán

        $model_nhom = nhomngachluong::where('manhom', $model_nb->manhom)->first();
        if ($model_nb->manhom == 'CBCT') {
            if ($model->bac < $model_nb->bacvuotkhung) {
                $model->hesott = $model_nb->hesochenhlech;
            } else {//cán bộ được hưởng vượt khung => hàng năm tự động tăng %vk ko tăng bậc
                if ($model->vuotkhung == $model_nb->vuotkhung) {
                    $model->hesott = ($model_nb->vuotkhung * $model->heso) / 100;
                } else {
                    $model->hesott = $model->heso / 100; //truy lĩnh 1%
                }
            }
        } else {
            if ($model->bac < $model_nhom->bacvuotkhung) {
                $model->hesott = $model_nhom->hesochenhlech;
            } else {//cán bộ được hưởng vượt khung => hàng năm tự động tăng %vk ko tăng bậc
                if ($model->vuotkhung == $model_nhom->vuotkhung) {
                    $model->hesott = ($model_nhom->vuotkhung * $model->heso) / 100;
                } else {
                    $model->hesott = $model->heso / 100; //truy lĩnh 1%
                }
            }
        }

        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosotruylinh::find($id);
            //$macanbo = $model->macanbo;
            $model->delete();
            return redirect('/nghiep_vu/truy_linh/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
