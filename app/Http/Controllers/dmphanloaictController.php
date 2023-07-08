<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class dmphanloaictController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $m_pb = dmphanloaicongtac::all();
            return view('system.danhmuc.congtac.index')
                ->with('model', $m_pb)
                ->with('furl', '/danh_muc/cong_tac/')
                ->with('pageTitle', 'Danh mục nhóm phân loại công tác');
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

        $inputs['macongtac'] = getdate()[0];
        dmphanloaicongtac::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dmphanloaicongtac::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/cong_tac/index');
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
        $model = dmphanloaicongtac::where('macongtac', $inputs['macongtac'])->first();
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
        $model = dmphanloaicongtac::where('macongtac', $inputs['macongtac'])->first();

        die($model);
    }

    public function xemdulieu()
    {
        if (Session::has('admin')) {
            $a_nhomct = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $m_pb = dmphanloaict::all();
            //dd($m_pb);
            return view('system.danhmuc.congtac.xemdulieu')
                ->with('model', $m_pb)
                ->with('a_nhomct', $a_nhomct)
                ->with('pageTitle', 'Danh mục phân loại công tác');
        } else
            return view('errors.notlogin');
    }

    public function detail($macongtac)
    {
        if (Session::has('admin')) {
            $m_pb = dmphanloaict::where('macongtac', $macongtac)->get();
            $m_nhom = dmphanloaicongtac::where('macongtac', $macongtac)->first();
            $a_nhom = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $a_nhucau = array_merge(getNhomNhuCauKP('KVHCSN'), getNhomNhuCauKP('KVXP'));
            //dd($m_pb);
            return view('system.danhmuc.congtac.detail')
                ->with('model', $m_pb)
                ->with('m_nhom', $m_nhom)
                ->with('a_nhom', $a_nhom)
                ->with('a_nhucau', $a_nhucau)
                ->with('macongtac', $macongtac)
                ->with('furl', '/danh_muc/cong_tac/')
                ->with('pageTitle', 'Danh mục phân loại công tác');
        } else
            return view('errors.notlogin');
    }


    function store_detail(Request $request)
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
        $model_chk = dmphanloaict::all();
        foreach ($model_chk as $ct) {
            if (chuanhoatruong(trim($ct->tenct)) == chuanhoatruong(trim($inputs['tenct']))) {
                $result = array(
                    'status' => 'fail',
                    'message' => 'Phân loại công tác này đã có trong phần mềm.',
                );
                die(json_encode($result));
            }
        }

        $inputs['mact'] = getdate()[0];
        dmphanloaict::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy_detail($id)
    {
        if (Session::has('admin')) {
            $model = dmphanloaict::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/cong_tac/index');
        } else
            return view('errors.notlogin');
    }

    function update_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = dmphanloaict::where('mact', $inputs['mact'])->first();
            if ($model == null) {
                $model_chk = dmphanloaict::all();
                foreach ($model_chk as $ct) {
                    if (chuanhoatruong(trim($ct->tenct)) == chuanhoatruong(trim($inputs['tenct']))) {
                        return view('errors.data_error.blade')
                            ->with('message', 'Phân loại công tác này đã tồn tại trên hệ thống')
                            ->with('furl', '/');
                    }
                }
                $inputs['mact'] = getdate()[0];
                dmphanloaict::create($inputs);
            } else {
                $model->update($inputs);
            }

            return redirect('/danh_muc/cong_tac/ma_so=' . $inputs['macongtac']);
        } else
            return view('errors.notlogin');
    }

    function getinfo_detail(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphanloaict::where('mact', $inputs['mact'])->first();
        die($model);
    }
}
