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
    public function index(){
        if (Session::has('admin')) {
            $m_pb=dmphanloaicongtac::all();
            return view('system.danhmuc.congtac.index')
                ->with('model',$m_pb)
                ->with('furl','/danh_muc/cong_tac/')
                ->with('pageTitle','Danh mục nhóm phân loại công tác');
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
        $inputs['macongtac']=getdate()[0];
        dmphanloaicongtac::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmphanloaicongtac::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/cong_tac/index');
        }else
            return view('errors.notlogin');
    }

    function update(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphanloaicongtac::where('macongtac',$inputs['macongtac'])->first();

        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
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
        $model = dmphanloaicongtac::where('macongtac',$inputs['macongtac'])->first();
        $model_bh = dmphanloaicongtac_baohiem::where('maphanloai',session('admin')->maphanloai)->where('macongtac',$inputs['macongtac'])->first();
        if(count($model_bh)>0){
            $model->bhxh = $model_bh->bhxh;
            $model->bhyt = $model_bh->bhyt;
            $model->kpcd = $model_bh->kpcd;
            $model->bhtn = $model_bh->bhtn;
            $model->bhxh_dv = $model_bh->bhxh_dv;
            $model->bhyt_dv = $model_bh->bhyt_dv;
            $model->kpcd_dv = $model_bh->kpcd_dv;
            $model->bhtn_dv = $model_bh->bhtn_dv;
        }

        die($model);
    }

    public function detail($macongtac){
        if (Session::has('admin')) {
            $m_pb=dmphanloaict::where('macongtac',$macongtac)->get();
            return view('system.danhmuc.congtac.detail')
                ->with('model',$m_pb)
                ->with('macongtac',$macongtac)
                ->with('furl','/danh_muc/cong_tac/')
                ->with('pageTitle','Danh mục phân loại công tác');
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
        $inputs['mact']=getdate()[0];
        dmphanloaict::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy_detail($id){
        if (Session::has('admin')) {
            $model = dmphanloaict::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/cong_tac/index');
        }else
            return view('errors.notlogin');
    }

    function update_detail(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphanloaict::where('mact',$inputs['mact'])->first();
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
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
        $model = dmphanloaict::where('mact',$inputs['mact'])->first();
        die($model);
    }

    public function index_baohiem(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $maphanloai = $inputs['phanloai'];
            if(session('admin')->level=='SA' || session('admin')->level=='SSA'){
                $model_pl = dmphanloaidonvi::all();
                if($maphanloai=='SA' || $maphanloai =='SSA'){
                    $maphanloai= 'KVXP';
                }
            }else{
                $model_pl = dmphanloaidonvi::where('maphanloai',$maphanloai)->get();
            }

            $m_pb=dmphanloaicongtac::all();
            return view('system.danhmuc.mucbaohiem.index')
                ->with('model',$m_pb)
                ->with('furl','/danh_muc/bao_hiem/')
                ->with('model_pl',array_column($model_pl->toArray(),'tenphanloai','maphanloai'))
                ->with('mapl',$maphanloai)
                ->with('pageTitle','Danh mục nhóm phân loại công tác');
        } else
            return view('errors.notlogin');
    }

    function getinfo_baohiem(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = dmphanloaicongtac::where('macongtac',$inputs['macongtac'])->first();
        $model_bh = dmphanloaicongtac_baohiem::where('maphanloai',$inputs['maphanloai'])->where('macongtac',$inputs['macongtac'])->first();
        if(count($model_bh)>0){
            $model->bhxh = $model_bh->bhxh;
            $model->bhyt = $model_bh->bhyt;
            $model->kpcd = $model_bh->kpcd;
            $model->bhtn = $model_bh->bhtn;
            $model->bhxh_dv = $model_bh->bhxh_dv;
            $model->bhyt_dv = $model_bh->bhyt_dv;
            $model->kpcd_dv = $model_bh->kpcd_dv;
            $model->bhtn_dv = $model_bh->bhtn_dv;
        }

        die($model);
    }
    function update_baohiem(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model_bh = dmphanloaicongtac_baohiem::where('maphanloai',$inputs['maphanloai'])->where('macongtac',$inputs['macongtac'])->first();
        //dd($model_bh);
        if(count($model_bh)>0){
            //update
            $model_bh->update($inputs);
        }else{
            //insert
            dmphanloaicongtac_baohiem::create($inputs);
        }

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

}
