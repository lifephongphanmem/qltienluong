<?php

namespace App\Http\Controllers;

use App\bangluongdangky;
use App\bangluongdangky_ct;
use App\bangluongdangky_phucap;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\ngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class bangluongdangkyController extends Controller
{
    function dangky(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model = bangluongdangky::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->get();

            return view('manage.dangkyluong.index')
                ->with('furl', '/chuc_nang/dang_ky_luong/')
                ->with('model', $model)
                ->with('inputs', $inputs)
                ->with('luongcb', getGeneralConfigs()['luongcb'])
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle', 'Danh sách bảng đăng ký lương');
        } else
            return view('errors.notlogin');
    }

    //Insert + update bảng lương
    function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
        $model = bangluongdangky::where('mabl', $inputs['mabl'])->first();

        if (count($model) > 0) {//update
            $model->update($inputs);
            return redirect('/chuc_nang/dang_ky_luong/danh_sach?thang='.$model->thang.'&nam='.$model->nam);
        } else {
            //kiểm tra bảng lương cùng nguồn, lĩnh vực hoạt động, lương cơ bản =>ko cho tạo
            $model_chk = bangluongdangky::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])
                ->first();

            if(count($model_chk)>0){
                return view('errors.trungbangdangkyluong');
            }

            //insert
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;

            //Lấy tất cả cán bộ trong đơn vị
            $m_cb = hosocanbo::where('madv', $madv)
                ->select('stt', 'macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'hesopc', 'hesott', 'vuotkhung',
                    'pclt', 'pcdd', 'pck', 'pccv', 'pckv', 'pcth', 'pcdh', 'pcld', 'pcudn', 'pctn', 'pctnn', 'pcdbn', 'pcvk', 'pckn', 'pccovu', 'pcdbqh', 'pctnvk', 'pcbdhdcu', 'pcdang', 'pcthni', 'pcct')
                ->where('theodoi','<', '9')->get();

            //Lấy danh sách cán bộ kiêm nhiệm
            $model_canbo_kn = hosocanbo_kiemnhiem::where('madv', $madv)->get();

            //$model_congtac = dmphanloaict::all();
            $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->get();

            //Tạo bảng lương
            bangluongdangky::create($inputs);

            //Tính toán lương cho cán bộ kiêm nhiệm
            //$m_donvi = dmdonvi::where('madv',session('admin')->madv)->first();

            foreach ($model_canbo_kn as $cb) {
                $cb->mabl = $inputs['mabl'];
                $ths = 0;
                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;
                    $ths += $cb->$mapc;
                }

                $cb->tonghs = $ths;
                $cb->ttl = round($inputs['luongcoban'] * $ths);

                $cb->luongtn = $cb->ttl;
                $a_k = $cb->toarray();
                unset($a_k['id']);
                bangluongdangky_ct::create($a_k);
            }
            //Tính toán lương cho cán bộ
            //$a_col = getColPhuCap(); //lấy theo phụ cấp => tự tính phụ cấp vượt khung, hệ số lương
            //$a_baohiem = getPhuCapNopBH();
            $model_chucvu = dmchucvucq::where('maphanloai', session('admin')->maphanloai)
                ->wherein('madv', ['SA', session('admin')->madv])->get();

            foreach ($m_cb as $cb) {
                $cb->mabl = $inputs['mabl'];
                $cb->macongtac = null;
                $cb->bhxh = 0;
                $cb->bhyt = 0;
                $cb->kpcd = 0;
                $cb->bhtn = 0;
                $cb->bhxh_dv = 0;
                $cb->bhyt_dv = 0;
                $cb->kpcd_dv = 0;
                $cb->bhtn_dv = 0;

                $chucvu = $model_chucvu->where('macvcq', $cb->macvcq)->first();
                $phanloai = $model_phanloai->where('mact', $cb->mact)->first();

                if (count($phanloai) > 0) {
                    $cb->bhxh = floatval($phanloai->bhxh) / 100;
                    $cb->bhyt = floatval($phanloai->bhyt) / 100;
                    $cb->kpcd = floatval($phanloai->kpcd) / 100;
                    $cb->bhtn = floatval($phanloai->bhtn) / 100;
                    if ($cb->sunghiep == 'Công chức' || (count($chucvu) > 0 && $chucvu->ttdv == 1)) {
                        $cb->bhtn = 0;
                    }
                    $cb->bhxh_dv = floatval($phanloai->bhxh_dv) / 100;
                    $cb->bhyt_dv = floatval($phanloai->bhyt_dv) / 100;
                    $cb->kpcd_dv = floatval($phanloai->kpcd_dv) / 100;
                    $cb->bhtn_dv = floatval($phanloai->bhtn_dv) / 100;
                }

                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;

                $tt = 0;
                $ths = 0;

                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;
                    $ct->heso_goc = $cb->$mapc;
                    $heso = 0;
                    //gán số tiền bảo hiểm  = 0 khi tính để ko trùng với giá trị cán bộ trc
                    $ct->stbhxh = 0;
                    $ct->stbhyt = 0;
                    $ct->stkpcd = 0;
                    $ct->stbhtn = 0;
                    $ct->stbhxh_dv = 0;
                    $ct->stbhyt_dv = 0;
                    $ct->stkpcd_dv = 0;
                    $ct->stbhtn_dv = 0;
                    $pl = getDbl($ct->phanloai);

                    switch ($pl) {
                        case 0: {//hệ số
                            $ths += $cb->$mapc;
                            $sotien = $cb->$mapc * $inputs['luongcoban'];
                            break;
                        }
                        case 1: {//số tiền
                            $tt += chkDbl($cb->$mapc);
                            $sotien = chkDbl($cb->$mapc);
                            break;
                        }
                        case 2: {//phần trăm
                            if ($mapc != 'vuotkhung') {//vượt khung đã tính ở trên

                                foreach (explode(',', $ct->congthuc) as $cthuc) {
                                    if ($cthuc != '')
                                        $heso += $cb->$cthuc;
                                }

                                $cb->$mapc = $heso * $cb->$mapc / 100;
                                $ths += $cb->$mapc;
                                $sotien = $cb->$mapc * $inputs['luongcoban'];
                            }else{
                                $ths += $cb->$mapc;
                                $sotien = $cb->$mapc * $inputs['luongcoban'];
                            }
                            break;
                        }
                        default: {//trường hợp còn lại (ẩn,...)
                            $cb->$mapc = 0;
                            $sotien = 0;
                            break;
                        }
                    }

                    if ($cb->$mapc > 0) {//lưu vào bảng lương phụ cấp (chi luu số tiền >0)
                        $ct->mabl = $inputs['mabl'];
                        $ct->macanbo = $cb->macanbo;
                        $ct->tencanbo = $cb->tencanbo;
                        $ct->maso = $mapc;
                        $ct->ten = $ct->tenpc;
                        $ct->heso = $cb->$mapc;
                        $ct->sotien = round($sotien, 0);
                        if ($ct->baohiem == 1) {
                            $ct->stbhxh = $ct->sotien * $cb->bhxh;
                            $ct->stbhyt = $ct->sotien * $cb->bhyt;
                            $ct->stkpcd = $ct->sotien * $cb->kpcd;
                            $ct->stbhtn = $ct->sotien * $cb->bhtn;
                            $ct->ttbh = $ct->stbhxh + $ct->stbhyt + $ct->stkpcd + $ct->stbhtn;
                            $ct->stbhxh_dv = $ct->sotien * $cb->bhxh_dv;
                            $ct->stbhyt_dv = $ct->sotien * $cb->bhyt_dv;
                            $ct->stkpcd_dv = $ct->sotien * $cb->kpcd_dv;
                            $ct->stbhtn_dv = $ct->sotien * $cb->bhtn_dv;
                            $ct->ttbh_dv = $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                        }

                        $a_kq = $ct->toarray();
                        unset($a_kq['id']);
                        bangluongdangky_phucap::create($a_kq);
                    }
                }

                $cb->tonghs = $ths;
                $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);

                $cb->stbhxh = $model_phucap->sum('stbhxh');
                $cb->stbhyt = $model_phucap->sum('stbhyt');
                $cb->stkpcd = $model_phucap->sum('stkpcd');
                $cb->stbhtn = $model_phucap->sum('stbhtn');
                $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                $cb->luongtn = $cb->ttl - $cb->ttbh;
                $cb->stbhxh_dv = $model_phucap->sum('stbhxh_dv');
                $cb->stbhyt_dv = $model_phucap->sum('stbhyt_dv');
                $cb->stkpcd_dv = $model_phucap->sum('stkpcd_dv');
                $cb->stbhtn_dv = $model_phucap->sum('stbhtn_dv');
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;

                //lưu vào db
                bangluongdangky_ct::create($cb->toarray());
            }
        }

        return redirect('/chuc_nang/dang_ky_luong/maso=' . $inputs['mabl']);
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
        $model = bangluongdangky::where('mabl',$inputs['mabl'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = bangluongdangky::find($id);
            bangluongdangky_ct::where('mabl', $model->mabl)->delete();
            bangluongdangky_phucap::where('mabl', $model->mabl)->delete();
            $model->delete();
            return redirect('/chuc_nang/dang_ky_luong/danh_sach?thang='.$model->thang.'&nam='.$model->nam);
        } else
            return view('errors.notlogin');
    }

    function show($mabl)
    {
        if (Session::has('admin')) {
            $model = bangluongdangky_ct::where('mabl', $mabl)->get();
            $m_bl = bangluongdangky::select('thang', 'nam', 'mabl')->where('mabl', $mabl)->first();
            $dmchucvucq = dmchucvucq::all('tencv', 'macvcq')->toArray();
            $model_cb = hosocanbo::where('madv', session('admin')->madv)->get();
            foreach ($model as $hs) {
                $cb = $model_cb->where('macanbo', $hs->macanbo)->first();
                $hs->tencanbo = count($cb) > 0 ? $cb->tencanbo : '';
                $hs->tencv = getInfoChucVuCQ($hs, $dmchucvucq);
            }
            return view('manage.dangkyluong.bangluong')
                ->with('furl', '/chuc_nang/dang_ky_luong/')
                ->with('model', $model)
                ->with('m_bl', $m_bl)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluongdangky_ct::findorfail($inputs['maso']);
            $model_canbo = hosocanbo::where('macanbo',$model->macanbo)->first();
            $model_bangluong = bangluongdangky::where('mabl',$model->mabl)->first();

            $m_nb = ngachluong::where('msngbac',$model->msngbac)->first();
            //$model_bl = bangluong::where('mabl',$model->mabl)->first();
            $model->tennb = isset($m_nb)? $m_nb->tenngachluong:'';
            $model->tencanbo = Str::upper($model->tencanbo);
            $model->luongcoban = $model_bangluong->luongcoban;

            $model->bhxh = 0;
            $model->bhyt = 0;
            $model->bhtn = 0;
            $model->kpcd = 0;
            $model->bhxh_dv = 0;
            $model->bhyt_dv = 0;
            $model->bhtn_dv = 0;
            $model->kpcd_dv = 0;

            $model_baohiem = dmphanloaicongtac_baohiem::where('mact',$model->mact)->where('madv',session('admin')->madv)->first();
            $chucvu = dmchucvucq::where('macvcq',$model->macvcq)->first();
            $model->sunghiep = $model_canbo->sunghiep;

            if(count($model_baohiem)>0){
                $model->bhxh = $model_baohiem->bhxh;
                $model->bhyt = $model_baohiem->bhyt;
                $model->bhtn = $model_baohiem->bhtn;
                if($model->sunghiep == 'Công chức' || (count($chucvu) > 0 && $chucvu->ttdv == 1)){
                    $model->bhtn = 0;
                }
                $model->kpcd = $model_baohiem->kpcd;
                $model->bhxh_dv = $model_baohiem->bhxh_dv;
                $model->bhyt_dv = $model_baohiem->bhyt_dv;
                $model->bhtn_dv = $model_baohiem->bhtn_dv;
                $model->kpcd_dv = $model_baohiem->kpcd_dv;
            }
            //dd($model);
            $a_donvi = dmdonvi::where('madv',$model_bangluong->madv)->first()->toarray();

           // $a_goc = array('hesott');
            $a_goc = array('heso','vuotkhung','hesott','hesopc');
            $model_pc = dmphucap_donvi::where('madv', $model_bangluong->madv)->where('phanloai', '<', '3')
                ->wherenotin('mapc', $a_goc)->get()->toarray();

            return view('manage.dangkyluong.chitiet')
                ->with('furl','/chuc_nang/dang_ky_luong/')
                ->with('model',$model)
                ->with('a_phucap',array_column($model_pc,'form','mapc'))
                ->with('a_donvi',$a_donvi)
                //->with('model_baohiem',$model_baohiem)
                ->with('pageTitle','Chi tiết bảng lương');


        } else
            return view('errors.notlogin');
    }

    function updatect(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model=bangluongdangky_ct::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            foreach($model_pc as $pc){
                if(isset($inputs[$pc->mapc])){
                    $inputs[$pc->mapc] = chkDbl($inputs[$pc->mapc]);
                }
            }
            /*
            $inputs['heso'] = chkDbl($inputs['heso']);
            $inputs['hesopc'] = chkDbl($inputs['hesopc']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            $inputs['pccv'] = chkDbl($inputs['pccv']);
            $inputs['pckn'] = chkDbl($inputs['pckn']);
            $inputs['pckv'] = chkDbl($inputs['pckv']);
            $inputs['pccovu'] = chkDbl($inputs['pccovu']);
            $inputs['pctn'] = chkDbl($inputs['pctn']);
            $inputs['pctnn'] = chkDbl($inputs['pctnn']);
            $inputs['pcvk'] = chkDbl($inputs['pcvk']);//lưu thông tin pc đảng ủy viên
            $inputs['pcdbqh'] = chkDbl($inputs['pcdbqh']);
            $inputs['pcth'] = chkDbl($inputs['pcth']);
            $inputs['pcudn'] = chkDbl($inputs['pcudn']);
            $inputs['pcdbn'] = chkDbl($inputs['pcdbn']);
            $inputs['pcld'] = chkDbl($inputs['pcld']);
            $inputs['pcdh'] = chkDbl($inputs['pcdh']);
            $inputs['pck'] = chkDbl($inputs['pck']);
            $inputs['pcbdhdcu'] = chkDbl($inputs['pcbdhdcu']);
            $inputs['pcdang'] = chkDbl($inputs['pcdang']);
            $inputs['pcthni'] = chkDbl($inputs['pcthni']);
            $inputs['pclt'] = chkDbl($inputs['pclt']);
            $inputs['pcdd'] = chkDbl($inputs['pcdd']);
            $inputs['pcct'] = chkDbl($inputs['pcct']);
            */
            $inputs['ttl'] = chkDbl($inputs['ttl']);
            $inputs['giaml'] = chkDbl($inputs['giaml']);
            $inputs['bhct'] = chkDbl($inputs['bhct']);
            $inputs['stbhxh'] = chkDbl($inputs['stbhxh']);
            $inputs['stbhyt'] = chkDbl($inputs['stbhyt']);
            $inputs['stkpcd'] = chkDbl($inputs['stkpcd']);
            $inputs['stbhtn'] = chkDbl($inputs['stbhtn']);
            $inputs['ttbh'] = chkDbl( $inputs['ttbh']);
            $inputs['stbhxh_dv'] = chkDbl($inputs['stbhxh_dv']);
            $inputs['stbhyt_dv'] = chkDbl($inputs['stbhyt_dv']);
            $inputs['stkpcd_dv'] = chkDbl($inputs['stkpcd_dv']);
            $inputs['stbhtn_dv'] = chkDbl($inputs['stbhtn_dv']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['luongtn'] = chkDbl($inputs['luongtn']);

            //dd($inputs);
            $model->update($inputs);
            return redirect('/chuc_nang/dang_ky_luong/maso='.$model->mabl);


        } else
            return view('errors.notlogin');
    }

}
