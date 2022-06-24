<?php

namespace App\Http\Controllers;

use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmngachluongController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $model=nhomngachluong::all();
            $a_phanloai=array('CVCC'=>'Chuyên viên cao cấp','CVC'=>'Chuyên viên chính','CV'=>'Chuyên viên','CS'=>'Cán sự','KHAC'=>'Khác');
            foreach($model as $nb) {
                $nb->phanloai= isset($a_phanloai[$nb->phanloai])?$a_phanloai[$nb->phanloai]:'';
            }
            return view('system.danhmuc.ngachluong.index')
                ->with('model',$model)
                ->with('a_phanloai',$a_phanloai)
                ->with('furl','/danh_muc/ngach_bac/')
                ->with('pageTitle','Danh mục nhóm ngạch bậc');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $inputs = $request->all();
        $model = nhomngachluong::where('manhom',$inputs['manhom'])->first();
        if($model != null){
            //update
            $model->update($inputs);
        }else{
            //insert
            nhomngachluong::create($inputs);
        }
        return redirect('/danh_muc/ngach_bac/index');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = nhomngachluong::findOrFail($id);
            return redirect('/danh_muc/ngach_bac/index');
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
        $model = nhomngachluong::where('manhom',$inputs['manhom'])->first();
        die($model);
    }

    public function detail($manhom)
    {
        if (Session::has('admin')) {
            $m_pb = ngachluong::where('manhom', $manhom)->get();
            $model_nhom = nhomngachluong::where('manhom', $manhom)->first();
            return view('system.danhmuc.ngachluong.detail')
                ->with('model', $m_pb)
                ->with('model_nhom', $model_nhom)
                ->with('manhom', $manhom)
                ->with('furl', '/danh_muc/ngach_bac/')
                ->with('pageTitle', 'Danh mục ngạch bậc lương');
        } else
            return view('errors.notlogin');
    }

    //Insert + update ngachluong
    function store_detail(Request $request)
    {
        $inputs = $request->all();
        //$model_nhom = nhomngachluong::where('manhom',$inputs['manhom'])->first();
        $model = ngachluong::where('msngbac',$inputs['msngbac'])->first();
        if($model != null){
            //update
            $model->update($inputs);
        }else{
            //insert
            ngachluong::create($inputs);
        }
        return redirect('/danh_muc/ngach_bac/ma_so='.$inputs['manhom']);
    }

    function destroy_detail($id){
        if (Session::has('admin')) {
            $model = ngachluong::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/ngach_bac/ma_so='.$model->manhom);
        }else
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
        $model = ngachluong::where('msngbac',$inputs['msngbac'])->first();
        die($model);
    }

    function danhsach(){
        if (Session::has('admin')) {
            $model = ngachluong::all();
            return view('system.danhmuc.ngachluong.danhsach')
                ->with('model', $model)
                ->with('furl', '/danh_muc/ngach_bac/')
                ->with('pageTitle', 'Danh mục ngạch bậc lương');
        } else
            return view('errors.notlogin');
    }
}
