<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap;
use App\dmtieumuc_default;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmtieumuc_defaultController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = dmtieumuc_default::all();
            $a_pc = array_merge(['null' => 'Không chọn'], array_column(dmphucap::select('mapc', 'tenpc')->get()->toarray(), 'tenpc', 'mapc'));

            $a_ct = array('null' => 'Không chọn', 'ALL' => 'Tất cả');
            foreach(getPhanLoaiCT(false) as $k=>$v){
                $a_ct[$k] = $v;
            }
            //$a_sunghiep = array('null'=> 'Không chọn','ALL' => 'Tất cả', 'Công chức' => 'Công chức', 'Viên chức' => 'Viên chức', 'Khác' => 'Khác');
            foreach ($model as $ct) {
                foreach(explode(',',$ct->mact) as $val){
                    $ct->tenct .= isset($a_ct[$val]) ? ($a_ct[$val].'; ') : '';
                }
                foreach(explode(',',$ct->mapc) as $val){
                    $ct->tenpc .= isset($a_pc[$val]) ? ($a_pc[$val].'; ') : '';
                }
            }

            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();

            return view('system.danhmuc.muctieumuc.index')
                ->with('model', $model->sortBy('tieumuc'))
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('a_pc', $a_pc)
                //->with('model_sunghiep', $a_sunghiep)
                ->with('furl', '/danh_muc/tieu_muc/')
                ->with('pageTitle', 'Danh mục mục - tiểu mục');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            if(in_array('null',$inputs['mact'])){
                $inputs['mact'] = 'null';
            }elseif(in_array('ALL',$inputs['mact'])){
                $inputs['mact'] = 'ALL';
            }else{
                $inputs['mact'] = implode(',',$inputs['mact']);
            }

            if(in_array('null',$inputs['mapc'])){
                $inputs['mapc'] = 'null';
            }else{
                $inputs['mapc'] = implode(',',$inputs['mapc']);
            }
            //kiểm tra trong mang nếu có giá trị "Không chọn" => loại bỏ nếu có 2 giá trị

            $model = dmtieumuc_default::where('tieumuc',$inputs['tieumuc'])->first();

            if(count($model) > 0){//update
                $model->update($inputs);
            }else{//add
                dmtieumuc_default::create($inputs);
            }

            return redirect('/danh_muc/tieu_muc/index');
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
        $model = dmtieumuc_default::where('tieumuc',$inputs['tieumuc'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmtieumuc_default::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/tieu_muc/index');
        }else
            return view('errors.notlogin');
    }
}
