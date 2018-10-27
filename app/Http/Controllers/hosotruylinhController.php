<?php

namespace App\Http\Controllers;

use App\dmphucap_donvi;
use App\hosocanbo;
use App\hosotruylinh;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosotruylinhController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {

            $model_canbo = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();
            $model = hosotruylinh::where('madv', session('admin')->madv)->get();
            //$a = getPhanLoaiTruyLinh();
            return view('manage.truylinh.index')
                ->with('model', $model->sortby('ngaytu'))
                ->with('a_canbo', array_column($model_canbo->toarray(), 'tencanbo', 'macanbo'))
                ->with('a_pl', getPhanLoaiTruyLinh(false))
                ->with('furl', '/nghiep_vu/truy_linh/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('pageTitle', 'Danh sách cán bộ được truy lĩnh lương');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $insert['luongcoban'] = getDbl($insert['luongcoban']);
            $insert['thangtl'] = getDbl($insert['thangtl']);
            $insert['ngaytl'] = getDbl($insert['ngaytl']);
            if($insert['maso'] == 'ADD'){
                $insert['madv'] = session('admin')->madv;
                $insert['maso'] = session('admin')->madv . '_' . getdate()[0];
                hosotruylinh::create($insert);
            }else{
                //$model = hosotruylinh::where('maso',$insert['maso'])->first();
                hosotruylinh::where('maso',$insert['maso'])->first()->update($insert);
            }

            return redirect('nghiep_vu/truy_linh/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $model = hosotruylinh::where('maso', $insert['maso_edit'])->first();
            $insert['ngaytu'] = $insert['ngaytu_edit'];
            $insert['msngbac'] = $insert['msngbac_edit'];
            $insert['hesott'] = $insert['hesott_edit'];
            $insert['luongcoban'] = getDbl($insert['luongcoban']);
            $insert['thangtl'] = getDbl($insert['thangtl']);
            $insert['ngaytl'] = getDbl($insert['ngaytl']);

            $model->update($insert);
            return redirect('nghiep_vu/truy_linh/danh_sach');
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
        $model = hosotruylinh::where('maso',$inputs['maso'])->first();
        //dd($model);
        die($model);
    }

    function get_thongtin_canbo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = hosocanbo::where('macanbo', $inputs['maso'])->first();
        if (count($model) == 0) {
            die($model);
        }

        $model_nb = ngachluong::where('msngbac', $model->msngbac)->first();
        if (count($model_nb) == 0) {
            die($model);
        }//cán bộ ko có msngbac => ko tính toán

        $model_nhom = nhomngachluong::where('manhom', $model_nb->manhom)->first();
        if ($model_nb->manhom == 'CBCT') {
            if ($model->bac < $model_nb->bacvuotkhung) {
                $model->hesott = $model_nb->hesochenhlech;
            } else {//cán bộ được hưởng vượt khung => hàng năm tự động tăng %vk ko tăng bậc
                if ($model->vuotkhung == $model_nb->vuotkhung) {
                    $model->hesott = ($model_nb->vuotkhung * $model->heso) / 100;
                } else {
                    $model->hesott = $model->heso / 100; //truy lĩnh 1%
                }
            }
        } else {
            if ($model->bac < $model_nhom->bacvuotkhung) {
                $model->hesott = $model_nhom->hesochenhlech;
            } else {//cán bộ được hưởng vượt khung => hàng năm tự động tăng %vk ko tăng bậc
                if ($model->vuotkhung == $model_nhom->vuotkhung) {
                    $model->hesott = ($model_nhom->vuotkhung * $model->heso) / 100;
                } else {
                    $model->hesott = $model->heso / 100; //truy lĩnh 1%
                }
            }
        }

        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosotruylinh::find($id);
            //$macanbo = $model->macanbo;
            $model->delete();
            return redirect('/nghiep_vu/truy_linh/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_pl = getPhanLoaiTruyLinh();
            //msngbac: kiểm tra xem mã ngạch bậc: xem lấy mã số xem truy lĩnh hệ số hay vượt khung
            //tnn: tính 1% thâm niên nghề. Các hệ số ko có
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            if(isset($inputs['maso'])){
                $model = hosotruylinh::where('maso',$inputs['maso'])->first();
                $model->tentruylinh = isset($a_pl[$model->maphanloai]) ? $a_pl[$model->maphanloai] : '';

                switch($model->maphanloai){
                    case 'MSNGBAC':{
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'CHUCVU':{
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'TNN':{
                        return view('manage.truylinh.create_tnn')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }

                    case 'NGAYLV':{
                        return view('manage.truylinh.create_ngaylv')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'KHAC':{
                        return view('manage.truylinh.create_khac')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }

                    default:{
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                }
            }else{
            //thêm mới
                $model = hosocanbo::where('macanbo',$inputs['macanbo'])->first();
                $model->ngaytu = null;
                $model->ngayden = null;
                $model->maso = 'ADD';
                $model->maphanloai = $inputs['maphanloai'];
                $model->tentruylinh = isset($a_pl[$model->maphanloai]) ? $a_pl[$model->maphanloai] : '';
                switch($inputs['maphanloai']){
                    case 'MSNGBAC':{
                        $heso = 0;
                        $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();
                        $msngbac = $model->msngbac;
                        if(isset($a_nhomnb[$msngbac])) {
                            $nhomnb = $a_nhomnb[$msngbac];
                            //hesolonnhat
                            //$hesomax = $nhomnb['heso'] + ($nhomnb['heso'] * $nhomnb['hesochenhlech']);
                            $hesomax = $nhomnb['hesolonnhat'];
                            if ($model->heso >= $hesomax) {
                                $vuotkhung = $model->vuotkhung == 0 ? $nhomnb['vuotkhung'] : 1;
                                $heso = ($vuotkhung * $model->heso) / 100;
                            } else {
                                $heso = $nhomnb['hesochenhlech'];
                            }
                        }
                        foreach($model_pc as $pc){
                            if($pc->phanloai != 2){
                                $mapc = $pc->mapc;
                                $model->$mapc = 0;
                            }

                        }
                        $model->heso = $heso;
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;

                    }
                    case 'CHUCVU':{
                        foreach($model_pc as $pc){
                            if($pc->phanloai != 2){
                                $mapc = $pc->mapc;
                                $model->$mapc = 0;
                            }
                        }
                        $model->heso = 0;
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;

                    }
                    case 'TNN':{
                        $heso = ($model->heso + ($model->heso * $model->vuotkhung)/100 + $model->pccv)/100;
                        foreach($model_pc as $pc){
                            $mapc = $pc->mapc;
                            $model->$mapc = 0;
                        }
                        $model->heso = $heso;
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        $model_pc = null;
                        return view('manage.truylinh.create_tnn')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'NGAYLV':{
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        return view('manage.truylinh.create_ngaylv')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'KHAC':{
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        return view('manage.truylinh.create_khac')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }

                    default:{
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                }

            }
        } else
            return view('errors.notlogin');
    }
}
