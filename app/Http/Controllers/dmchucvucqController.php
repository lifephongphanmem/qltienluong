<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class dmchucvucqController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_pl = dmphanloaidonvi::all();
            $maphanloai = isset($inputs['maso']) ? $inputs['maso'] : $model_pl->first()->maphanloai;
            $model = dmchucvucq::where('maphanloai', $maphanloai)->where('madv', 'SA')->get();
            $a_pl = getPhanLoaiNhanVien();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $ct) {
                $ct->phanloai = isset($a_pl[$ct->ttdv]) ? $a_pl[$ct->ttdv] : '';
            }
            return view('system.danhmuc.chucvucq.index')
                ->with('model', $model->sortby('sapxep'))
                ->with('model_pl', array_column($model_pl->toArray(), 'tenphanloai', 'maphanloai'))
                ->with('a_plct', $a_plct)
                ->with('mapl', $maphanloai)
                ->with('furl', '/he_thong/chuc_vu/')
                ->with('pageTitle', 'Danh mục chức vụ');
        } else
            return view('errors.notlogin');
    }

    public function index_donvi()
    {
        if (Session::has('admin')) {
            $model_pl = dmphanloaidonvi::where('maphanloai', session('admin')->maphanloai)->get();
            //$model = dmchucvucq::wherein('madv', ['SA', session('admin')->madv])->get();
            $model = dmchucvucq::where('maphanloai', session('admin')->maphanloai)->wherein('madv', ['SA', session('admin')->madv])->get();
            $a_pl = getPhanLoaiNhanVien();
            foreach ($model as $ct) {
                $ct->phanloai = isset($a_pl[$ct->ttdv]) ? $a_pl[$ct->ttdv] : '';
            }
            //dd($model);
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            return view('system.danhmuc.chucvucq.index_donvi')
                ->with('model', $model->sortby('sapxep'))
                ->with('a_plct', $a_plct)
                ->with('model_pl', array_column($model_pl->toArray(), 'tenphanloai', 'maphanloai'))
                ->with('mapl', session('admin')->maphanloai)
                ->with('furl', '/danh_muc/chuc_vu/')
                ->with('pageTitle', 'Danh mục chức vụ');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $inputs['macvcq'] = session('admin')->madv . '_' . getdate()[0];
        $inputs['madv'] = session('admin')->level == 'SA' ? session('admin')->level : session('admin')->madv;
        dmchucvucq::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dmchucvucq::findOrFail($id);
            $model->delete();
            return redirect('/he_thong/chuc_vu/index?maso=' . $model->maphanloai);
        } else
            return view('errors.notlogin');
    }

    function destroy_donvi($id)
    {
        if (Session::has('admin')) {
            $model = dmchucvucq::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/chuc_vu/index');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmchucvucq::where('macvcq', $inputs['macvcq'])->first();

        $model->update($inputs);
        //dd($inputs);
        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function getinfo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmchucvucq::where('macvcq', $inputs['macvcq'])->first();
        die($model);
    }
}
