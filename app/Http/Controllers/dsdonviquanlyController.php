<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmkhoipb;
use App\dsdonviquanly;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class dsdonviquanlyController extends Controller
{
    public static $url = '/he_thong/DonViQuanLy';
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            if (!Session::has('admin')) {
                return redirect('/');
            };
            return $next($request);
        });
    }

    function danh_sach(Request $request)
    {
        $inputs = $request->all();
        $inputs['url'] = static::$url;
        $inputs['madv'] = $inputs['madv'] ?? session('admin')->madv;
        $model = dsdonviquanly::where('madv', $inputs['madv'])->orderby('nam')->get();

        //$model_donvi = dmdonvi::all();
        $madvbc = dmdonvi::where('madv', $inputs['madv'])->first()->madvbc;
        $a_donvi = array_column(dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)
            ->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
        //dd($a_donvi);
        return view('system.danhmuc.DonViQuanLy.index')
            ->with('model', $model)
            ->with('a_donvi', $a_donvi)
            ->with('inputs', $inputs)
            ->with('pageTitle', 'Thông tin đơn vị quản lý');
    }

    function ThayDoi(Request $request)
    {
        $inputs = $request->all();
        $model = dsdonviquanly::where('madv', $inputs['madv'])->where('nam', $inputs['nam'])->first();
        unset($inputs['id']);
        if ($model == null) {
            dsdonviquanly::create($inputs);
        } else {
            $model->update($inputs);
        }
        return redirect(static::$url . '/danh_sach');
    }

    function Xoa($id)
    {
        $model = dsdonviquanly::where('id', $id)->first();
        $model->delete();
        //dd($model);
        return redirect(static::$url . '/danh_sach');
    }
}
