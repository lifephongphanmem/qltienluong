<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphucap;
use App\dmphucap_donvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class dmphucapController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = dmphucap::all();//default
            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC();
            foreach ($model as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                $ct->tencongthuc = '';
                foreach ($congthuc as $bg) {
                    $ct->tencongthuc .= isset($a_ct[$bg]) ? ($a_ct[$bg] . '; ') : '';
                }
            }
            return view('system.danhmuc.phucap.index')
                ->with('model', $model)
                ->with('furl', '/danh_muc/phu_cap/')
                ->with('pageTitle', 'Danh mục phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function create()
    {
        if (Session::has('admin')) {
            return view('system.danhmuc.phucap.create')
                ->with('furl', '/danh_muc/phu_cap/')
                ->with('pageTitle', 'Thêm mới phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            dmphucap::create($inputs);
            return redirect('/danh_muc/phu_cap/index');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            dmphucap::where('mapc',$inputs['mapc'])->first()->update($inputs);
            return redirect('/danh_muc/phu_cap/index');
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model =  dmphucap::where('mapc',$inputs['maso'])->first();//default

            ///dd($model);
            return view('system.danhmuc.phucap.edit')
                ->with('model', $model)
                ->with('furl', '/danh_muc/phu_cap/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
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
        $model = dmphucap::where('mapc',$inputs['mapc'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmphucap::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/phu_cap/index');
        }else
            return view('errors.notlogin');
    }

    function index_donvi()
    {
        if (Session::has('admin')) {
            $model = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            if (count($model) == 0) {//đơn vị chưa tạo phụ cấp
                $model = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc', DB::raw(session('admin')->madv . ' as madv'))->get();
                //dành cho các đơn vị đã cập nhật danh mục phụ cấp
                if (count($m_donvi) > 0)
                    foreach ($model as $ct) {
                        $mapc = $ct->mapc;
                        $ct->phanloai =getDbl($m_donvi->$mapc);
                    }
                dmphucap_donvi::insert($model->toarray());
            }
            $model = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC();
            foreach ($model as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                $ct->tencongthuc = '';
                foreach ($congthuc as $bg) {
                    $ct->tencongthuc .= isset($a_ct[$bg]) ? ($a_ct[$bg] . '; ') : '';
                }
            }
            return view('system.danhmuc.phucap.index_donvi')
                ->with('model', $model)
                ->with('furl', '/danh_muc/phu_cap/don_vi/')
                ->with('pageTitle', 'Thông tin phân loại phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function edit_donvi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmphucap_donvi::where('mapc',$inputs['maso'])->where('madv', session('admin')->madv)->first();

            return view('system.danhmuc.phucap.edit_donvi')
                ->with('model', $model)
                ->with('furl', '/danh_muc/phu_cap/don_vi/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function update_donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            dmphucap_donvi::where('mapc',$inputs['mapc'])->where('madv', session('admin')->madv)->first()->update($inputs);
            return redirect('/danh_muc/phu_cap/don_vi');
        } else
            return view('errors.notlogin');
    }
}
