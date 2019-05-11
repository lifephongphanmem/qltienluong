<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
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
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_pc = array_column(dmphucap::select('mapc', 'tenpc')->get()->toarray(), 'tenpc', 'mapc');
            //$a_phucap = array_merge(array('null' => 'Không chọn', 'heso' => 'Lương hệ số'), getColPhuCap());//Lương hệ số bao gồm cả vượt khung);
            $a_phucap = array_merge(array('null' => 'Không chọn'), $model_pc);//Lương hệ số bao gồm cả vượt khung);
            $a_nhomct = array_merge(array('null' => 'Không chọn', 'ALL' => 'Tất cả'), array_column($model_nhomct->toarray(), 'tencongtac', 'macongtac'));
            //$a_sunghiep = array('null'=> 'Không chọn','ALL' => 'Tất cả', 'Công chức' => 'Công chức', 'Viên chức' => 'Viên chức', 'Khác' => 'Khác');
            foreach ($model as $ct) {
                $ct->tennhomct = isset($a_nhomct[$ct->macongtac]) ? $a_nhomct[$ct->macongtac] : '';
                foreach(explode(',',$ct->mapc) as $val){
                    $ct->tenpc .= isset($a_phucap[$val]) ? ($a_phucap[$val].'; ') : '';
                }
            }
            return view('system.danhmuc.muctieumuc.index')
                ->with('model', $model->sortBy('tieumuc'))
                ->with('model_nhomct', $a_nhomct)
                ->with('model_phucap', $a_phucap)
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
            //kiểm tra trong mang nếu có giá trị "Không chọn" => loại bỏ nếu có 2 giá trị

            $inputs['mapc'] = implode(',',$inputs['mapc']);
            $inputs['mapc'] = strpos($inputs['mapc'],'null') !== false ? 'null' : $inputs['mapc'];
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
