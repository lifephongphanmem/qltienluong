<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\hosotruc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosotrucController extends Controller
{
    function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['furl'] = '/nghiep_vu/truc/';
            $inputs['furl_ajax'] = '/ajax/truc/';
            $model = hosotruc::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->get();

            $m_cb = hosocanbo::select('macanbo', 'macvcq', 'tencanbo')
                ->wherenotin('macanbo',array_column($model->toarray(),'macanbo'))
                ->where('madv', session('admin')->madv)->orderby('stt')->get();
            //dd($a_cb);
            $m_bl = bangluong::where('phanloai','TRUC')
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('madv', session('admin')->madv)
                ->get();

            $inputs['trangthai'] = count($m_bl)> 0 ? false : true;
            return view('manage.truc.index')
                ->with('inputs', $inputs)
                ->with('m_cb', $m_cb)
                ->with('a_cv', getChucVuCQ(false))
                ->with('model', $model)
                ->with('pageTitle', 'Danh sách cán bộ trực công tác');
        } else
            return view('errors.notlogin');
    }

    function create(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['trangthai'] = 'ADD';
            $inputs['furl'] = '/nghiep_vu/truc/';
            $model = hosocanbo::where('macanbo',$inputs['macanbo'])->first();
            $model->songaycong = session('admin')->songaycong;
            $model->songaytruc = session('admin')->songaycong;
            $model->thang = $inputs['thang'];
            $model->nam = $inputs['nam'];
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)
                ->wherein('mapc',['heso','vuotkhung','pccv','pcdh','pctn','pcudn','pcud61','pcld','pclade'])
                ->get();

            return view('manage.truc.create')
                ->with('inputs',$inputs)
                ->with('model',$model)
                ->with('model_pc',$model_pc)
                ->with('a_heso', array('heso','vuotkhung','pccv'))
                ->with('pageTitle', 'Thêm mới cán bộ trực');

        } else
            return view('errors.notlogin');
    }

    function copy(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = hosotruc::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang_sao'])
                ->where('nam', $inputs['nam_sao'])
                ->get();
            $a_data = array();

            $model_chk = hosotruc::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->get()->keyby('macanbo')->toarray();
            $maso = getdate()[0];
            foreach($model as $ct){
                if(isset($model_chk[$ct->macanbo])){
                    continue;
                }
                $a_data[] = array(
                    'maso' => $maso++,
                    'macanbo'=>$ct->macanbo,
                    'tencanbo'=>$ct->tencanbo,
                    'heso'=>$ct->heso,
                    'madv'=>$ct->madv,
                    'vuotkhung'=>$ct->vuotkhung,
                    'pccv'=>$ct->pccv,
                    'pcdh'=>$ct->pcdh,
                    'pctn'=>$ct->pctn,
                    'pcudn'=>$ct->pcudn,
                    'pcud61'=>$ct->pcud61,
                    'pcld'=>$ct->pcld,
                    'pclade'=>$ct->pclade,
                    'songaycong'=>$ct->songaycong,
                    'songaytruc'=>$ct->songaytruc,
//                    'songaycong'=>$inputs['ngaycong_sao'],
//                    'songaytruc'=>$inputs['ngaycong_sao'],
                    'thang'=>$inputs['thang'],
                    'nam'=>$inputs['nam'],
                );
            }
            hosotruc::insert($a_data);
            return redirect('/nghiep_vu/truc/danh_sach?thang='.$inputs['thang'].'&nam='.$inputs['nam']);

        } else
            return view('errors.notlogin');
    }

    function edit(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['trangthai'] = 'EDIT';
            $inputs['furl'] = '/nghiep_vu/truc/';

            $model = hosotruc::where('macanbo',$inputs['macanbo'])->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])
                ->first();

            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)
                ->wherein('mapc',['heso','vuotkhung','pccv','pcdh','pctn','pcudn','pcud61','pcld','pclade'])
                ->get();

            return view('manage.truc.create')
                ->with('inputs',$inputs)
                ->with('model',$model)
                ->with('model_pc',$model_pc)
                ->with('a_heso', array('heso','vuotkhung','pccv'))
                ->with('pageTitle', 'Thông tin cán bộ trực');

        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['songaycong'] = chkDbl($inputs['songaycong']) < 1 ? 1 : chkDbl($inputs['songaycong']);
            $inputs['songaytruc'] = chkDbl($inputs['songaytruc']);
            $a_pc = ['heso','vuotkhung','pccv','pcdh','pctn','pcudn','pcud61','pcld','pclade'];
            foreach ($a_pc as $pc){
                $inputs[$pc] = chkDbl($inputs[$pc]);
            }
            //dd($inputs);
            if ($inputs['trangthai'] == 'ADD') {
                $inputs['maso'] = getdate()[0];
                hosotruc::create($inputs);
            } else {
                hosotruc::where('macanbo',$inputs['macanbo'])
                    ->where('thang',$inputs['thang'])
                    ->where('nam',$inputs['nam'])
                    ->first()->update($inputs);
            }
            return redirect('/nghiep_vu/truc/danh_sach?thang='.$inputs['thang'].'&nam='.$inputs['nam']);
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
        $model = hosotruc::find($inputs['id']);
        $model->status = 'success';
        die(json_encode($model));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosotruc::find($id);
            $model->delete();
            return redirect('/nghiep_vu/truc/danh_sach?thang='.$model->thang.'&nam='.$model->nam);
        } else
            return view('errors.notlogin');
    }


}
