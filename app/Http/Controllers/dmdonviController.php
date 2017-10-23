<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmkhoipb;
use App\dmphanloaidonvi;
use App\GeneralConfigs;
use App\Users;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class dmdonviController extends Controller
{
    public function index($makhoipb){
        if (Session::has('admin')) {
            if(session('admin')->level=='T'){
                $model_kpb=dmkhoipb::all();
                $m_pb=dmdonvi::all();

            }else{//quyền này chỉ chạy cho H, T nen ko cần phần X
                $makpb=getMaKhoiPB(session('admin')->madv);
                $model_kpb=dmkhoipb::where('makhoipb',$makpb)->get();
                $m_pb=dmdonvi::where('makhoipb',$makpb)->get();
            }

            if($makhoipb !='all'){
                $m_pb= $m_pb->where('makhoipb',$makhoipb);
            }
            return view('system.danhmuc.donvi.index')
                ->with('makhoipb',$makhoipb)
                ->with('model',$m_pb)
                ->with('model_kpb',$model_kpb)
                ->with('pageTitle','Danh mục đơn vị');
        } else
            return view('errors.notlogin');
    }

    public function change($madv){
        if (Session::has('admin')) {
            $model = Users::find(session('admin')->id);
            $model->madv=$madv;
            $model->save();
            session('admin')->madv=$madv;
            return redirect('danh_muc/don_vi/maso=all');
        } else
            return view('errors.notlogin');
    }

    public function information_local(){
        if (Session::has('admin')) {
            $model=dmdonvi::where('madv',session('admin')->madv)->first();
            $model_capdv = getCapDonVi();
            $model->capdutoan = isset($model_capdv[$model->capdonvi])?$model_capdv[$model->capdonvi]:'';

            return view('system.general.local.index')
                ->with('model',$model)
                ->with('url','/he_thong/don_vi/')
                ->with('pageTitle','Thông tin đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_local($madv){
        if (Session::has('admin')) {
            if(session('admin')->level=='SA'|| session('admin')->madv == $madv){
                $model=dmdonvi::where('madv',$madv)->first();

                //$makpb=getMaKhoiPB(session('admin')->maxa);
                //$model_kpb=dmkhoipb::select('makhoipb','tenkhoipb')->where('makhoipb',$makpb)->get()->toarray();
                $model_donvi = array_column(dmdonvi::select('madv','tendv')->where('madvbc',$model->madvbc)->get()->toarray(),'tendv','madv');
                $model_donvi['NULL']= 'Chọn đơn vị chủ quản';
                $a_phanloai = array_column(dmphanloaidonvi::all()->toarray(),'tenphanloai','maphanloai');
                $model_plxa = getPhanLoaiXa();
                $model_capdv = getCapDonVi();
                $model_linhvuc = array_column(dmkhoipb::all()->toarray(),'tenkhoipb','makhoipb');
//dd($model);
                return view('system.general.local.edit')
                    ->with('model',$model)
                    ->with('model_donvi',$model_donvi)
                    ->with('a_phanloai',$a_phanloai)
                    ->with('model_phanloai',$a_phanloai)
                    ->with('model_plxa',$model_plxa)
                    ->with('model_capdv',$model_capdv)
                    ->with('model_linhvuc',$model_linhvuc)
                    ->with('url','/he_thong/don_vi/')
                    ->with('pageTitle','Chỉnh sửa thông tin đơn vị');
            }else{return view('errors.noperm');}

        } else
            return view('errors.notlogin');
    }

    function update_local(Request $request, $madv){
        if (Session::has('admin')) {
            if(session('admin')->level=='SA'|| session('admin')->madv == $madv){
                $inputs=$request->all();

                $model=dmdonvi::where('madv',$madv)->first();
                $model->update($inputs);
                return redirect('/he_thong/don_vi/don_vi');
            }else{return view('errors.noperm');}

        } else
            return view('errors.notlogin');
    }

    function information_global(){
        if (Session::has('admin')) {
            $model=GeneralConfigs::first();
            return view('system.general.global.index')
                ->with('model',$model)
                ->with('url','/he_thong/don_vi/')
                ->with('pageTitle','Thông tin đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_global($id){
        if (Session::has('admin')) {
            $model=GeneralConfigs::find($id);
            return view('system.general.global.edit')
                ->with('model',$model)
                ->with('url','/he_thong/don_vi/')
                ->with('pageTitle','Chỉnh sửa thông tin chung');
        } else
            return view('errors.notlogin');
    }

    function update_global(Request $request, $id){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model=GeneralConfigs::first();
            $model->tuoinam=$inputs['tuoinam'];
            $model->tg_hetts=$inputs['tg_hetts'];
            $model->tuoinu=$inputs['tuoinu'];
            $model->tg_xetnl=$inputs['tg_xetnl'];
            $model->bhxh=$inputs['bhxh'];
            $model->bhxh_dv=$inputs['bhxh_dv'];
            $model->bhyt=$inputs['bhyt'];
            $model->bhyt_dv=$inputs['bhyt_dv'];
            $model->bhtn=$inputs['bhtn'];
            $model->bhtn_dv=$inputs['bhtn_dv'];
            $model->kpcd=$inputs['kpcd'];
            $model->kpcd_dv=$inputs['kpcd_dv'];
            $model->luongcb=$inputs['luongcb'];
            $model->save();
            return redirect('/he_thong/don_vi/chung');

        } else
            return view('errors.notlogin');
    }

    public function information_manage(){
        if (Session::has('admin')) {
            if(session('admin')->level=='T'){
                $model=dmdonvi::all();
            }else{//quyền này chỉ chạy cho H, T nen ko cần phần X
                $model=dmdonvi::where('macqcq',session('admin')->madv)->get();
            }
            return view('system.manage.index')
                ->with('model',$model)
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Danh mục đơn vị');
        } else
            return view('errors.notlogin');
    }

    public function create_manage(){
        if (Session::has('admin')) {
            $model=dmdonvi::where('level','H')->get();
            $model_kpb=array_column(dmkhoipb::select('makhoipb','tenkhoipb')->get()->toarray(),'tenkhoipb','makhoipb');
            return view('system.manage.create')
                ->with('model',$model)
                ->with('model_kpb',$model_kpb)
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Thêm mới đơn vị');
        } else
            return view('errors.notlogin');
    }

    public function store_manage(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model=new dmdonvi();
            $model->level=$inputs['level'];
            $model->madv=$inputs['madv'];
            $model->tendv=$inputs['tendv'];
            $model->diachi=$inputs['diachi'];
            $model->diadanh=$inputs['diadanh'];
            $model->makhoipb=$inputs['makhoipb'];
            $model->diadanh=$inputs['diadanh'];
            $model->macqcq=isset($inputs['macqcq'])?isset($inputs['macqcq']):NULL;
            if($model->save()){
                $model=new Users();
                $model->name=$inputs['tendv'];
                $model->level=$inputs['level'];
                $model->maxa=$inputs['madv'];
                $model->madv=$inputs['madv'];
                  if($inputs['level']=='H'){
                      $model->mahuyen=$inputs['madv'];
                  }
                $model->username=$inputs['username'];
                $model->password=md5($inputs['password']);
                $model->status="Kích hoạt";
                $model->save();
            }

            return redirect('/he_thong/quan_tri/don_vi');
        } else
            return view('errors.notlogin');
    }

    public function edit_information($madv){
        if (Session::has('admin')) {
            $model=dmdonvi::where('madv',$madv)->first();
            $model_cqcq=dmdonvi::where('level','H')->get();
            $model_kpb=array_column(dmkhoipb::select('makhoipb','tenkhoipb')->get()->toarray(),'tenkhoipb','makhoipb');
            return view('system.manage.edit_information')
                ->with('model',$model)
                ->with('model_cqcq',$model_cqcq)
                ->with('model_kpb',$model_kpb)
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Chỉnh sửa thông tin đơn vị');
        } else
            return view('errors.notlogin');
    }

    function update_information(Request $request, $madv){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model=dmdonvi::where('madv',$madv)->first();
            $model->macqcq=$inputs['macqcq']=='all'?'':$inputs['macqcq'];
            $model->tendv=$inputs['tendv'];
            $model->diachi=$inputs['diachi'];
            $model->sodt=$inputs['sodt'];
            $model->lanhdao=$inputs['lanhdao'];
            $model->diadanh=$inputs['diadanh'];
            $model->cdlanhdao=$inputs['cdlanhdao'];
            $model->nguoilapbieu=$inputs['nguoilapbieu'];
            $model->makhoipb=$inputs['makhoipb'];
            $model->diadanh=$inputs['diadanh'];
            $model->save();
            return redirect('/he_thong/quan_tri/don_vi');

        } else
            return view('errors.notlogin');
    }

    public function list_account($madv){
        if (Session::has('admin')) {
            $model_donvi=dmdonvi::where('madv',$madv)->first();
            $model=Users::where('maxa',$madv)->get();
            return view('system.manage.list_account')
                ->with('model',$model)
                ->with('model_donvi',$model_donvi)
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Danh sách tài khoản người dùng');
        } else
            return view('errors.notlogin');
    }

    public function create_account($madv){
        if (Session::has('admin')) {
            return view('system.manage.create_account')
                ->with('madv',$madv)
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Thêm mới tài khoản người dùng');
        } else
            return view('errors.notlogin');
    }

    public function store_account(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_donvi=dmdonvi::where('madv',$inputs['madv'])->first();
            $model=new Users();
            $model->level=$model_donvi->level;
            $model->maxa=$inputs['madv'];
            $model->madv=$inputs['madv'];
            $model->name=$inputs['name'];
            $model->username=$inputs['username'];
            $model->password=md5($inputs['password']);
            $model->phone=$inputs['phone'];
            $model->email=$inputs['email'];
            $model->status=$inputs['status'];
            $model->save();

            return redirect('/he_thong/quan_tri/don_vi/maso='.$inputs['madv']);
        } else
            return view('errors.notlogin');
    }

    public function edit_account($id){
        if (Session::has('admin')) {
            $model=Users::findorfail($id);
            return view('system.manage.edit_account')
                ->with('model',$model)
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Chỉnh sửa thông tin tài khoản');
        } else
            return view('errors.notlogin');
    }

    public function update_account(Request $request, $id){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model=Users::findorfail($id);
            $model->name=$inputs['name'];
            $model->username=$inputs['username'];

            if($inputs['newpass']!=''){
                $model->password= md5($inputs['newpass']);
            }
            $model->phone=$inputs['phone'];
            $model->email=$inputs['email'];
            $model->status=$inputs['status'];
            $model->save();

            return redirect('/he_thong/quan_tri/don_vi/maso='.$model->maxa);
        } else
            return view('errors.notlogin');
    }

    function destroy_account(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = Users::findOrFail($inputs['iddelete']);
            $maxa=$model->madv;
            $model->delete();
            return redirect('/he_thong/quan_tri/don_vi/maso='.$maxa);
        }else
            return view('errors.notlogin');
    }

    public function permission_list($id){
        if (Session::has('admin')) {
            $model=Users::findorfail($id);
            $permission = !empty($model->permission)  ? $model->permission : getPermissionDefault($model->level);
            return view('system.users.perms')
                ->with('model',$model)
                ->with('permission',json_decode($permission))
                ->with('url','/he_thong/quan_tri/')
                ->with('pageTitle','Phân quyền cho tài khoản');
        } else
            return view('errors.notlogin');
    }

    public function permission_update(Request $request){
        if (Session::has('admin')) {
            $update = $request->all();
            $id = $request['id'];

            $model = Users::findOrFail($id);
            //dd($model);
            if(isset($model)){

                $update['roles'] = isset($update['roles']) ? $update['roles'] : null;
                $model->permission = json_encode($update['roles']);
                $model->save();

                return redirect('he_thong/quan_tri/don_vi/maso='.$model->maxa);

            }else
                dd('Tài khoản không tồn tại');

        }else
            return view('errors.notlogin');

    }
}
