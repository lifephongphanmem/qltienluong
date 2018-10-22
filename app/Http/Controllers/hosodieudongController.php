<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\hosodieudong;
use App\hosothoicongtac;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosodieudongController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            //lấy danh sách cán bộ luân chuyển trong đơn

            //lấy cán bộ đơn vị # chuyển đến (trạng thái, phân loại)
            $model = hosodieudong::where('madv', session('admin')->madv)->get();
            $model_canbo = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();
            $a_pl = getPhanLoaiLuanChuyen();
            $a_tt = getStatus();

            //tuywf theo phan laoi mà có chức năng tương ứng
            // + được điều động, lc dến => nhận cán bộ
            /*
            //$m_pbm=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvm=dmchucvucq::all('tencv', 'macvcq')->toArray();
            $m_dvm=dmdonvi::select('tendv', 'madv')->where('madvbc',session('admin')->madvbc)->get()->toArray();


            */
            foreach($model as $ct){
                //$hs->tenpb=getInfoPhongBan($hs,$m_pbm);
                $ct->tenphanloai = isset($a_pl[$ct->maphanloai])? $a_pl[$ct->maphanloai] : '';
                $ct->tentrangthai = isset($a_tt[$ct->trangthai])? $a_tt[$ct->trangthai] : '';
                $ct->tendv_dd=getTenDV($ct->madv_dd);
            }

            return view('manage.dieudong.index')
                ->with('furl', '/nghiep_vu/dieu_dong/')
                ->with('a_canbo', array_column($model_canbo->toarray(), 'tencanbo', 'macanbo'))
                ->with('a_pl', $a_pl)
                ->with('model', $model)
                ->with('pageTitle', 'Danh sách hồ sơ điều động cán bộ');
        } else
            return view('errors.notlogin');
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            if(isset($inputs['maso'])){
                $model = hosodieudong::where('maso',$inputs['maso'])->first();
                //dd($model);
            }else{
                $model = hosocanbo::where('macanbo',$inputs['macanbo'])->first();
                $model->maso = 'ADD';
            }

            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_diaban = dmdonvibaocao::where('level','H')->get();
            $model_donvi = dmdonvi::where('phanloaitaikhoan','SD')->get();
            $a_phanloai = getPhanLoaiLuanChuyen();
            return view('manage.dieudong.create')
                ->with('furl', '/nghiep_vu/dieu_dong/')
                ->with('inputs',$inputs)
                ->with('model',$model)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('model_diaban', $model_diaban)
                ->with('model_donvi', $model_donvi)
                ->with('a_heso', array('hesott'))
                ->with('a_phanloai', $a_phanloai)
                //->with('a_heso', array('heso', 'vuotkhung', 'hesopc', 'hesott'))
                ->with('model_pc', $model_pc)
                ->with('pageTitle', 'Thêm mới cán bộ luân chuyển, điều động');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            //DONVIKHAC: nếu đơn vị ngoài hệ thống => cập nhật vào thông tin cán bộ
            //DONVI: trong hệ thống => trạng thái chuyển về chờ nhận (trạng thái, phân loai)

            //if(madv_dd == DONVIKHAC)
            $insert['ngaylc'] = getDateTime($insert['ngaylc']);
            $insert['ngaylctu'] = getDateTime($insert['ngaylctu']);
            $insert['ngaylcden'] = getDateTime($insert['ngaylcden']);
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            foreach($model_pc as $pc){
                if(isset($insert[$pc->mapc])){
                    $insert[$pc->mapc] = chkDbl($insert[$pc->mapc]);
                }
            }

            //do dùng chung store + update => code 2 lần
            if($insert['maso'] == 'ADD'){
                $insert['madv'] = session('admin')->madv;
                $insert['maso'] = session('admin')->madv . '_' . getdate()[0];
                $insert['trangthai'] = 'CHONHAN';
                hosodieudong::create($insert);
                //chuyển ra ngoài hệ thống => tự cập nhật vào bảng hồ sơ cán bộ
                if($insert['madv_dd'] == 'DONVIKHAC'){
                    if($insert['maphanloai'] == 'DIEUDONG'){
                        hosocanbo::where('macanbo',$insert['macanbo'])->update(['theodoi'=>'3']);
                    }

                    if($insert['maphanloai'] == 'LUANCHUYEN') {
                        hosocanbo::where('macanbo', $insert['macanbo'])->update(['theodoi' => '9']);
                        $insert['lydo'] = 'Luân chuyển cán bộ';
                        $insert['ngaynghi'] = $insert['ngaylc'];
                        hosothoicongtac::create($insert);
                    }
                    hosodieudong::where('maso',$insert['maso'])->update(['trangthai'=>'DACHUYEN']);
                }
            }else{
                // kiểm tra mã đơn vị mới : //DONVIKHAC: nếu đơn vị ngoài hệ thống => cập nhật vào thông tin cán bộ
                //DONVI: trong hệ thống => trạng thái chuyển về chờ nhận

                //dd($model);
                hosodieudong::where('maso',$insert['maso'])->first()->update($insert);
            }

            return redirect('/nghiep_vu/dieu_dong/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosodieudong::find($id);
            hosocanbo::where('macanbo',$model->macanbo)->update(['theodoi'=>'1']);
            if($model->maphanloai == 'LUANCHUYEN'){
                hosothoicongtac::where('macanbo',$model->macanbo)->delete();
            }
            //$macanbo = $model->macanbo;
            $model->delete();
            return redirect('/nghiep_vu/dieu_dong/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
