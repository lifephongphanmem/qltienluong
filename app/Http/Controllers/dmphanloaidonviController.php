<?php

namespace App\Http\Controllers;

use App\dmphanloaidonvi;
use App\dmphucap;
use App\dmphucap_phanloai;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dmphanloaidonviController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = dmphanloaidonvi::all();
            return view('system.danhmuc.phanloaidonvi.index')
                ->with('model', $model)
                ->with('furl', '/danh_muc/pl_don_vi/')
                ->with('pageTitle', 'Danh mục phân loại đơn vị');
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
        if ($inputs['maphanloai'] == '') {
            $inputs['maphanloai'] = getdate()[0];
        }
        unset($inputs['id']);
        $model = dmphanloaidonvi::where('maphanloai', $inputs['maphanloai'])->first();
        if($model != null){
            $model->update($inputs);
        }else{
            dmphanloaidonvi::create($inputs);
        }
        
        
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dmphanloaidonvi::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/pl_don_vi/index');
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
        $model = dmphanloaidonvi::where('maphanloai', $inputs['maphanloai'])->first();
        $model->update($inputs);

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
        $model = dmphanloaidonvi::where('maphanloai', $inputs['maphanloai'])->first();
        die($model);
    }

    public function phucap(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['tenphanloai'] = getPhanLoaiDonVi()[$inputs['maso']];
            $model = dmphucap_phanloai::where('maphanloai', $inputs['maso'])->get();
            $a_pc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC();
            if (count($model) == 0) {
                $model_dmpc = dmphucap::select('mapc', 'phanloai', 'congthuc', DB::raw("'" . $inputs['maso'] . "' as maphanloai"))->get();
                dmphucap_phanloai::insert($model_dmpc->toarray());
                $model = dmphucap_phanloai::where('maphanloai', $inputs['maso'])->get();
            }

            foreach ($model as $ct) {
                $ct->tenpc = $a_pc[$ct->mapc];
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                $ct->tencongthuc = '';
                foreach ($congthuc as $bg) {
                    $ct->tencongthuc .= isset($a_ct[$bg]) ? ($a_ct[$bg] . '; ') : '';
                }
            }
            return view('system.danhmuc.phanloaidonvi.phucap')
                ->with('model', $model)
                ->with('inputs', $inputs)
                ->with('furl', '/danh_muc/pl_don_vi/')
                ->with('pageTitle', 'Danh mục phân loại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_phucap(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmphucap_phanloai::find($inputs['id']);
            $a_pc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $model->tenpc = $a_pc[$model->mapc];
            return view('system.danhmuc.phanloaidonvi.edit_phucap')
                ->with('model', $model)
                ->with('furl', '/danh_muc/pl_don_vi/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
        } else
            return view('errors.notlogin');
    }


    function update_phucap(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['congthuc'] = getDbl($inputs['phanloai']) == 2 ? $inputs['congthuc'] : '';
            dmphucap_phanloai::find($inputs['id'])->update($inputs);
            return redirect('/danh_muc/pl_don_vi?maso=' . $inputs['maphanloai']);
        } else
            return view('errors.notlogin');
    }

    function anhien(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmphucap_phanloai::find($inputs['id']);
            if ($model->phanloai == 3) {
                $model->congthuc = '';
                $model->phanloai = 0;
            } else {
                $model->phanloai = 3;
            }
            $model->save();

            return redirect('/danh_muc/pl_don_vi?maso=' . $model->maphanloai);
        } else
            return view('errors.notlogin');
    }
}
