<?php

namespace App\Http\Controllers;

use App\hosocanbo;
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
            //dd($model);
            //$a_phanloai = getPhanLoaiTamNgungTheoDoi();
            $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->get()->toarray(), 'tencanbo', 'macanbo');
            foreach ($model as $hs) {
                //$hs->phanloai = isset($a_phanloai[$hs->maphanloai]) ? $a_phanloai[$hs->maphanloai] : "";
                $hs->tencanbo = isset($a_canbo[$hs->macanbo]) ? $a_canbo[$hs->macanbo] : "";
            }

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
}
