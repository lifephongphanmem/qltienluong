<?php

namespace App\Http\Controllers;

use App\dmthongtuquyetdinh;
use App\dmthuetncn;
use App\dmthuetncn_ct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmthuetncnController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $model = dmthuetncn::orderby('ngayapdung')->get();
            return view('system.danhmuc.thuetncn.index')
                ->with('model', $model)
                ->with('furl', '/danh_muc/thuetncn/')
                ->with('pageTitle', 'Danh mục thông tư, quyết định');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['banthan'] = chkDbl($inputs['banthan']);
            $inputs['phuthuoc'] = chkDbl($inputs['phuthuoc']);
            $inputs['sohieu'] = str_replace("-", "_", chuanhoatruong($inputs['sohieu']));
            $inputs['sohieu'] = str_replace("*", "", $inputs['sohieu']);
            $inputs['sohieu'] = str_replace("%", "", $inputs['sohieu']);
            $model = dmthuetncn::where('sohieu', $inputs['sohieu'])->first();
            if ($model == null) {
                dmthuetncn::create($inputs);
            } else {
                $model->update($inputs);
            }
            return redirect('/danh_muc/thuetncn/index');
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
        $model = dmthuetncn::where('sohieu',$inputs['sohieu'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmthuetncn::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/thuetncn/index');
        }else
            return view('errors.notlogin');
    }

    public function detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model=dmthuetncn_ct::where('sohieu',$inputs['sohieu'])->orderby('muctu')->get();
            $m_thongtu = dmthuetncn::orderby('ngayapdung')->get();
            $inputs['furl'] =  '/danh_muc/thuetncn/';
            return view('system.danhmuc.thuetncn.detail')
                ->with('model', $model)
                ->with('a_thongtu', array_column($m_thongtu->toarray(),'tenttqd','sohieu'))
                ->with('inputs', $inputs)
                ->with('furl', '/danh_muc/thuetncn/')
                ->with('pageTitle', 'Danh mục thông tư, quyết định');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['muctu'] = chkDbl($inputs['muctu']);
            $inputs['mucden'] = chkDbl($inputs['mucden']);
            $inputs['phantram'] = chkDbl($inputs['phantram']);
            // dd($inputs);
            $model = dmthuetncn_ct::where('id', $inputs['id'])->first();
            unset($inputs['id']);
            // dd($inputs);
            if ($model == null) {
                dmthuetncn_ct::create($inputs);
            } else {
                $model->update($inputs);
            }
            return redirect('/danh_muc/thuetncn/detail?sohieu='.$inputs['sohieu']);
        } else
            return view('errors.notlogin');
    }
    function getinfo_detail(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmthuetncn_ct::where('id',$inputs['id'])->first();
        die($model);
    }

    function destroy_detail($id){
        if (Session::has('admin')) {
            $model = dmthuetncn_ct::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/thuetncn/detail?sohieu='.$model->sohieu);
        }else
            return view('errors.notlogin');
    }
}
