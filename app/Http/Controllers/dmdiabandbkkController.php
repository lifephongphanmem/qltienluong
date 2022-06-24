<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmdiabandbkk;
use App\dmdiabandbkk_chitiet;
use App\hosocanbo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dmdiabandbkkController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $model=dmdiabandbkk::where('madv',session('admin')->madv)->get();
            $a_phanloai=array();

            return view('manage.diabankk.index')
                ->with('model',$model)
                ->with('a_phanloai',$a_phanloai)
                ->with('furl','/nghiep_vu/quan_ly/dia_ban_dbkk/')
                ->with('pageTitle','Danh sách địa bàn đặc biệt khó khăn');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $inputs = $request->all();
        $model = dmdiabandbkk::where('madiaban',$inputs['madiaban'])->first();
        if($inputs['ngaytu'] !='') {
            $ngaytu = date_create(getDateToDb($inputs['ngaytu']));
            $inputs['thangtu'] = date_format($ngaytu, 'm');
            $inputs['namtu'] = date_format($ngaytu, 'Y');
        }
        //Ngày ra khỏi địa bàn khó khăn có thể chưa xác định.
        if($inputs['ngayden'] !=''){
            $ngayden = date_create(getDateToDb($inputs['ngayden']));
            $inputs['thangden']= date_format($ngayden,'m');
            $inputs['namden']= date_format($ngayden,'Y');
        }
        $inputs['ngaytu'] = getDateToDb($inputs['ngaytu']);
        $inputs['ngayden'] = getDateToDb($inputs['ngayden']);

        if($model != null){
            //update
            $model->update($inputs);
        }else{
            //insert
            $inputs['madiaban']= getdate()[0];
            dmdiabandbkk::create($inputs);
        }
        return redirect('/nghiep_vu/quan_ly/dia_ban_dbkk/index');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmdiabandbkk::findOrFail($id);
            $model->delete();
            return redirect('/nghiep_vu/quan_ly/dia_ban_dbkk/index');
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
        $model = dmdiabandbkk::where('madiaban',$inputs['madiaban'])->first();
        die($model);
    }

    public function detail($madiaban){
        if (Session::has('admin')) {
            $model_diaban= dmdiabandbkk::where('madiaban',$madiaban)->first();
            $model=dmdiabandbkk_chitiet::where('madiaban',$madiaban)->get();

            $model_cb = hosocanbo::select('macanbo','tencanbo','macvcq')->where('madv',session('admin')->madv)
                ->whereNotIn('macanbo',function($query) use($madiaban){
                    $query->select('macanbo')->from('dmdiabandbkk_chitiet')->where('madiaban',$madiaban);
                })->get();

            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach($model_cb as $hs){
                $hs->tencvcq=getInfoChucVuCQ($hs,$dmchucvucq);
            }

            $canbo = hosocanbo::where('madv',session('admin')->madv)->get();
            foreach($model as $hs){
                $cb=$canbo->where('macanbo',$hs->macanbo)->first();
                $hs->tencvcq=getInfoChucVuCQ($cb,$dmchucvucq);
                $hs->tencanbo=$cb->tencanbo;
            }

            return view('manage.diabankk.detail')
                ->with('model',$model)
                ->with('model_cb',$model_cb)
                ->with('model_diaban',$model_diaban)
                ->with('furl','/nghiep_vu/quan_ly/dia_ban_dbkk/')
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    //Insert + update ngachluong
    function store_detail(Request $request)
    {
        $inputs = $request->all();
        dmdiabandbkk_chitiet::create($inputs);
        $result = array(
            'status' => 'success',
            'message' => 'Thêm mới thành công.',
        );
        die(json_encode($result));
    }

    function destroy_detail($id){
        if (Session::has('admin')) {
            $model = dmdiabandbkk_chitiet::findOrFail($id);
            $model->delete();
            return redirect('/nghiep_vu/quan_ly/dia_ban_dbkk/ma_so='.$model->madiaban);
        }else
            return view('errors.notlogin');
    }
}
