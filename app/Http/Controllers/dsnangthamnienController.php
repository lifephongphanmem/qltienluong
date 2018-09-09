<?php

namespace App\Http\Controllers;

use App\dsnangthamnien;
use App\dsnangthamnien_ct;
use App\hosocanbo;
use App\hosotruylinh;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dsnangthamnienController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dsnangthamnien::where('madv',session('admin')->madv)->get();
            return view('manage.thamnien.index')
                ->with('furl','/chuc_nang/tham_nien/')
                //->with('furl_ajax','/ajax/nang_luong/')
                ->with('model',$model)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $inputs = $request->all();
        $model = dsnangthamnien::where('manl', $inputs['manl'])->first();

        if (count($model) > 0) {
            //update
            $model->update($inputs);
            return redirect('/chuc_nang/tham_nien/danh_sach');
        } else {

            $madv = session('admin')->madv;
            $manl = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['manl'] = $manl;


            $m_canbo = hosocanbo::select('macanbo','msngbac','heso','vuotkhung','pccv','tnntungay','tnndenngay','pctnn','macvcq')
                ->where('tnndenngay', '<=', $inputs['ngayxet'])
                ->where('theodoi','<','9')
                ->where('madv', $madv)->get();

            $inputs['trangthai'] = 'Tạo danh sách';
            dsnangthamnien::create($inputs);
            foreach ($m_canbo as $cb) {
                $cb->manl=$manl;
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $cb->pctnn++;
                $cb->ngaytu = new Carbon($cb->tnndenngay);
                $date = new Carbon($cb->tnndenngay);
                $cb->ngayden = $date->addYear('1');
                $cb->hesott = ($cb->vuotkhung + $cb->heso + $cb->pccv) / 100;
                //kiểm tra truy lĩnh nếu ngày xét = ngày nâng lương = > ko truy lĩnh
                if($inputs['ngayxet']>$cb->tnndenngay) {
                    $cb->truylinhtungay = new Carbon($cb->tnndenngay);
                }else{
                    $cb->truylinhtungay = null;
                }
                dsnangthamnien_ct::create($cb->toarray());
            }
            //dd($m_canbo->toarray());


            //dsnangthamnien_ct::insert($m_canbo->toarray());
            return redirect('/chuc_nang/tham_nien/maso='.$manl);
        }
    }

    function show($manl){
        if (Session::has('admin')) {
            $model = dsnangthamnien_ct::where('manl',$manl)->get();
            $model_canbo=hosocanbo::select('macanbo', 'macvcq','tencanbo')->get();
            $a_cv = getChucVuCQ(false);
            foreach($model as $hs){
                $canbo = $model_canbo->where('macanbo',$hs->macanbo)->first();
                if(count($canbo)>0){
                    $hs->tencv = isset($a_cv[$canbo->macvcq]) ? $a_cv[$canbo->macvcq] : '';
                    $hs->tencanbo = $canbo->tencanbo;
                    $hs->stt = $canbo->stt;

                }
            }

            $model_nangluong = dsnangthamnien::where('manl',$manl)->first();
            return view('manage.thamnien.nangluong')
                ->with('furl','/chuc_nang/tham_nien/')
                ->with('model',$model->sortBy('stt'))
                ->with('model_nangluong',$model_nangluong)
                ->with('pageTitle','Chi tiết danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dsnangthamnien_ct::where('manl',$inputs['maso'])->where('macanbo',$inputs['canbo'])->first();
            //dd($model);
            $model_canbo=hosocanbo::select('macanbo', 'macvcq','tencanbo')->where('macanbo',$model->macanbo)->first();
            if(count($model_canbo)>0){
                $model->tencanbo = $model_canbo->tencanbo;
            }
            $model_nangluong = dsnangthamnien::where('manl',$inputs['maso'])->first();
            return view('manage.thamnien.detail')
                ->with('furl','/chuc_nang/tham_nien/')
                ->with('model',$model)
                ->with('model_nangluong',$model_nangluong)
                ->with('pageTitle','Chi tiết danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['truylinhtungay'] = getDateTime($inputs['truylinhtungay']);
            $inputs['hesott'] = getDbl($inputs['hesott']);
            $model = dsnangthamnien_ct::where('manl',$inputs['manl'])->where('macanbo',$inputs['macanbo'])->first();
            $model->update($inputs);
            return redirect('chuc_nang/tham_nien/maso='.$model->manl);
        } else
            return view('errors.notlogin');
    }

    function nang_luong($manl){
        if (Session::has('admin')) {
            $model = dsnangthamnien_ct::where('manl',$manl)->get();
            foreach($model as $canbo) {
                $hoso = hosocanbo::where('macanbo', $canbo->macanbo)->first();
                $hoso->pctnn = $canbo->pctnn;
                $hoso->tnntungay = $canbo->ngaytu;
                $hoso->tnndenngay = $canbo->ngayden;
                $hoso->save();
                if (isset($canbo->truylinhtungay) && $canbo->hesott > 0) {
                    $truylinh = new hosotruylinh();
                    $truylinh->maso = session('admin')->madv . '_' . getdate()[0];
                    $truylinh->macanbo = $canbo->macanbo;
                    $truylinh->tencanbo = $hoso->tencanbo;
                    $truylinh->ngaytu = $canbo->truylinhtungay;
                    $truylinh->madv = session('admin')->madv;
                    $truylinh->noidung = 'Truy lĩnh nâng thâm niên nghề';
                    //$truylinh->msngbac = $canbo->msngbac;
                    $truylinh->hesott = $canbo->hesott;
                    $truylinh->save();
                }
                //Lưu thông tin vào hồ sơ cán bộ

            }
            dsnangthamnien::where('manl',$manl)->update(['trangthai'=>'Đã nâng lương']);
            return redirect('/chuc_nang/tham_nien/danh_sach');
        } else
            return view('errors.notlogin');
    }


    function destroy($id){
        if (Session::has('admin')) {
            $model = dsnangthamnien::find($id);
            dsnangthamnien_ct::where('manl',$model->manl)->delete();
            $model->delete();
            return redirect('/chuc_nang/tham_nien/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroydt($id){
        if (Session::has('admin')) {
            $model = dsnangthamnien_ct::find($id);
            $manl= $model->manl;
            $model->delete();
            return redirect('/chuc_nang/tham_nien/maso='.$manl);
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
        $model = dsnangthamnien::find($inputs['id']);
        die($model);
    }
}
