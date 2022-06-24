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
use App\Http\Controllers\dataController as data;

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

        if ($model != null) {//update
            $model->update($inputs);
            return redirect('/chuc_nang/dang_ky_luong/danh_sach?thang='.$model->thang.'&nam='.$model->nam);
        } else {
            //kiểm tra bảng lương cùng nguồn, lĩnh vực hoạt động, lương cơ bản =>ko cho tạo
            $model_chk = bangluongdangky::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])
                ->where('madv', session('admin')->madv)
                ->first();

            if ($model_chk != null) {
                return view('errors.trungbangdangkyluong');
            }

            //insert
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;

            $model_phucap = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'tenpc')
                ->where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
            $a_th = array_merge(array('macanbo', 'macvcq', 'mapb', 'manguonkp', 'mact', 'baohiem'),
                array_column($model_phucap->toarray(), 'mapc'));
            $a_th = array_merge(array('stt', 'tencanbo', 'msngbac', 'bac', 'pthuong', 'theodoi',
                'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv'),
                $a_th);
            //Lấy tất cả cán bộ trong đơn vị
            $m_cb = hosocanbo::select($a_th)
                ->where('madv', $madv)
                ->where('theodoi', '<', '9')->get();

            /*
            $m_cb = hosocanbo::where('madv', $madv)
                ->select('stt', 'macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'hesobl', 'hesopc', 'hesott', 'vuotkhung',
                    'pclt', 'pcdd', 'pck', 'pccv', 'pckv', 'pcth', 'pcdh', 'pcld', 'pcudn', 'pctn', 'pctnn', 'pcdbn', 'pcvk', 'pckn', 'pccovu', 'pcdbqh', 'pctnvk', 'pcbdhdcu', 'pcdang', 'pcthni', 'pcct')
                ->where('theodoi','<', '9')->get();
            $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            $model_chucvu = dmchucvucq::where('maphanloai', session('admin')->maphanloai)
                ->wherein('madv', ['SA', session('admin')->madv])->get();
             //$model_congtac = dmphanloaict::all();
            */
            //Lấy danh sách cán bộ kiêm nhiệm
            $model_canbo_kn = hosocanbo_kiemnhiem::where('madv', $madv)->get();
            //dd($model_canbo_kn);
            //Tạo bảng lương
            bangluongdangky::create($inputs);

            //Tính toán lương cho cán bộ kiêm nhiệm
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();

            $a_cb = array_column($m_cb->toarray(),'tencanbo','macanbo');
            foreach ($model_canbo_kn as $cb) {
                $cb->mabl = $inputs['mabl'];
                $cb->tencanbo = isset($a_cb[$cb->macanbo])? $a_cb[$cb->macanbo]:'';
                $ths = 0;
                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;
                    $ths += $cb->$mapc;
                }

                if ($cb->phanloai == 'CHUCVU') {
                    $cb->heso = round($cb->heso * $cb->pthuong / 100, session('admin')->lamtron);
                    $cb->st_heso = round($cb->st_heso * $cb->pthuong / 100, 0);
                    $cb->vuotkhung = round($cb->vuotkhung * $cb->pthuong / 100, session('admin')->lamtron);
                    $cb->st_vuotkhung = round($cb->st_vuotkhung * $cb->pthuong / 100, 0);
                    $ths = round($ths * $cb->pthuong / 100, session('admin')->lamtron);
                }

                $cb->tonghs = $ths;
                $cb->ttl = round($inputs['luongcoban'] * $ths);

                $cb->luongtn = $cb->ttl;
                $a_k = $cb->toarray();
                unset($a_k['id']);
                //dd($a_k);
                bangluongdangky_ct::create($a_k);
            }
            //Tính toán lương cho cán bộ
            //$a_col = getColPhuCap(); //lấy theo phụ cấp => tự tính phụ cấp vượt khung, hệ số lương
            //$a_baohiem = getPhuCapNopBH();


            $a_pc_coth = array('pcudn', 'pctnn' ,'pctaicu');
            foreach ($m_cb as $cb) {
                $cb->mabl = $inputs['mabl'];
                $cb->macongtac = null;
                $cb->bhxh = floatval($cb->bhxh) / 100;
                $cb->bhyt = floatval($cb->bhyt) / 100;
                $cb->kpcd = floatval($cb->kpcd) / 100;
                $cb->bhtn = floatval($cb->bhtn) / 100;
                $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;
                //lưu lại hệ số gốc 1 số loại pc
                $cb->hs_vuotkhung = $cb->vuotkhung;
                $cb->hs_pctnn = $cb->pctnn;
                $cb->hs_pccovu = $cb->pccovu;
                $cb->hs_pcud61 = $cb->pcud61;
                $cb->hs_pcudn = $cb->pcudn;
                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $cb->vuotkhung =round($cb->heso * $cb->vuotkhung / 100, session('admin')->lamtron);

                foreach ($a_pc_coth as $phca) {//tính trc 1 số phụ cấp làm phụ cấp cơ sở
                    $pc = $model_phucap->where('mapc', $phca)->first();
                    if ($pc != null) {//do 1 số nguồn ko lấy thâm niên nghề làm cơ sở
                        $pl = getDbl($pc->phanloai);
                        switch ($pl) {
                            case 0:
                            case 1: {//số tiền
                                //giữ nguyên ko cần tính
                                break;
                            }
                            case 2: {//phần trăm
                                $heso = 0;
                                foreach (explode(',', $pc->congthuc) as $ct) {
                                    if ($ct != '' && $ct != $phca)
                                        $heso += $cb->$ct;
                                }
                                $cb->$phca = round($heso * $cb->$phca / 100, session('admin')->lamtron);
                                break;
                            }
                            default: {//trường hợp còn lại (ẩn,...)
                                $cb->$phca = 0;
                                break;
                            }
                        }
                    }
                }

                $ttien = 0;
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
                            $sotien = round($cb->$mapc * $inputs['luongcoban']);
                            break;
                        }
                        case 1: {//số tiền
                            //$ttien += chkDbl($cb->$mapc);
                            $sotien = chkDbl($cb->$mapc);
                            break;
                        }
                        case 2: {//phần trăm
                            if ($mapc != 'vuotkhung' && !in_array($mapc, $a_pc_coth)) {//vượt khung + ttn đã tính ở trên
                                foreach (explode(',', $ct->congthuc) as $cthuc) {
                                    if ($cthuc != '')
                                        $heso += $cb->$cthuc;
                                }
                                $cb->$mapc = round($heso * $cb->$mapc / 100, session('admin')->lamtron);
                            }
                            $ths += $cb->$mapc;
                            $sotien = round($cb->$mapc * $inputs['luongcoban']);

                            break;
                        }
                        default: {//trường hợp còn lại (ẩn,...)
                            $cb->$mapc = 0;
                            $sotien = 0;
                            break;
                        }
                    }
                    $ttien += $sotien;
                    if ($cb->$mapc > 0) {//lưu vào bảng lương phụ cấp (chi luu số tiền >0)
                        $ct->mabl = $inputs['mabl'];
                        $ct->macanbo = $cb->macanbo;
                        $ct->tencanbo = $cb->tencanbo;
                        $ct->maso = $mapc;
                        $ct->ten = $ct->tenpc;
                        $ct->heso = $cb->$mapc;
                        $ct->sotien = round($sotien, 0);
                        if ($ct->baohiem == 1) {
                            $ct->stbhxh = round($ct->sotien * $cb->bhxh, 0);
                            $ct->stbhyt = round($ct->sotien * $cb->bhyt, 0);
                            $ct->stkpcd = round($ct->sotien * $cb->kpcd, 0);
                            $ct->stbhtn = round($ct->sotien * $cb->bhtn, 0);
                            $ct->ttbh = $ct->stbhxh + $ct->stbhyt + $ct->stkpcd + $ct->stbhtn;
                            $ct->stbhxh_dv = round($ct->sotien * $cb->bhxh_dv, 0);
                            $ct->stbhyt_dv = round($ct->sotien * $cb->bhyt_dv, 0);
                            $ct->stkpcd_dv = round($ct->sotien * $cb->kpcd_dv, 0);
                            $ct->stbhtn_dv = round($ct->sotien * $cb->bhtn_dv, 0);
                            $ct->ttbh_dv = $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                        }
                        /*
                        $a_kq = $ct->toarray();
                        unset($a_kq['id']);
                        bangluongdangky_phucap::create($a_kq);
                        */
                    }
                }

                $cb->tonghs = $ths;
                $cb->ttl = $ttien;

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
                $hs->tencanbo = $cb->tencanbo ?? '';
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

            if($model_baohiem != null){
                $model->bhxh = $model_baohiem->bhxh;
                $model->bhyt = $model_baohiem->bhyt;
                $model->bhtn = $model_baohiem->bhtn;
                if($model->sunghiep == 'Công chức' || ($chucvu != null && $chucvu->ttdv == 1)){
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
            $model_pc = dmphucap_donvi::where('madv', $model_bangluong->madv)//->where('phanloai', '<', '3')
                ->wherenotin('mapc', $a_goc)->get();
            //dd(array_column($model_pc,'form','mapc'));
            //dd($model_pc);
            return view('manage.dangkyluong.chitiet')
                ->with('furl','/chuc_nang/dang_ky_luong/')
                ->with('model',$model)
                ->with('model_pc',$model_pc)
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

    public function printf_mau01(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau1'];
            $inputs['mapb'] = $inputs['mapb_mau1'];
            $inputs['macvcq'] = $inputs['macvcq_mau1'];
            $inputs['mact'] = $inputs['mact_mau1'];
            //$inputs['cochu'] = $inputs['cochu_mau1'];
            $model = $this->getBangLuong($inputs);
            //$model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluongdangky::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluongdangky_ct')->where('mabl',$mabl);
                })->get();

            
            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = getColPhuCap_BaoCao();
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            //dd($thongtin);
            return view('reports.bangluong.donvi.maubangluong_dk')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            $model = $this->getBangLuong($inputs);
            //$model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluongdangky::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluongdangky_ct')->where('mabl',$mabl);
                })->get();
            
            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            return view('reports.bangluong.donvi.mautt107_dk')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_m2(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            $model = $this->getBangLuong($inputs);
            //$model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluongdangky::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluongdangky_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            return view('reports.bangluong.donvi.mautt107_dk_m2')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function getBangLuong($inputs, $phanloai=0)
    {
        $mabl = $inputs['mabl'];
        $model = bangluongdangky_ct::where('mabl', $mabl)->get();
        $m_bl = bangluongdangky::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
        //$m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
        $dmchucvucq = getChucVuCQ(false);

        $m_cb = hosocanbo::select('tencanbo','sunghiep', 'macanbo')->where('madv', $m_bl->madv)->get();
        $a_sn = array_column($m_cb->toArray(), 'sunghiep', 'macanbo');
        $a_cb = array_column($m_cb->toArray(), 'tencanbo', 'macanbo');
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');

        //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
        //$a_phucapbc = getColPhuCap_BaoCao();
        //$a_goc = array('heso','vuotkhung','hesott');
        $a_goc = array();
        $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
        $a_phucap = array();
        $col = 0;
        foreach ($model_pc as $ct) {
            $a_phucap[$ct->mapc] = $ct->report;
            $col++;
        }
        //$a_col = getColTongHop();
        $luongcb = $m_bl->luongcoban;

        foreach ($model as $hs) {
            $hs->tencv = isset($dmchucvucq[$hs->macvcq]) ? $dmchucvucq[$hs->macvcq] : '';
            $hs->sunghiep = isset($a_sn[$hs->macanbo]) ? $a_sn[$hs->macanbo] : '';
            if($hs->tencanbo == ''){
                $hs->tencanbo = isset($a_cb[$hs->macanbo]) ? $a_cb[$hs->macanbo] : '';
            }
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            /*
            if ($phanloai == 1) {
                $ths = 0;
                foreach ($model_pc as $col) {
                    $mapc = $col->mapc;
                    //$pl = getDbl($m_dv->$col);
                    $pl = getDbl($col->phanloai);

                    switch ($pl) {
                        case 1: {//số tiền
                            //giữ nguyên ko pai làm gì
                            break;
                        }
                        default: {//trường hợp còn lại (ẩn,...)
                            $hs->$mapc = $hs->$mapc * $luongcb;
                            break;
                        }
                    }
                    $ths += $hs->$mapc;
                    $hs->tonghs = $ths;
                }
            }
            */
        }

        if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
            $model = $model->where('mapb', $inputs['mapb']);
        }
        if (isset($inputs['macvcq']) && $inputs['macvcq'] != '') {
            $model = $model->where('macvcq', $inputs['macvcq']);
        }
        if (isset($inputs['mact']) && $inputs['mact'] != '') {
            $model = $model->where('mact', $inputs['mact']);
        }
        return $model;
    }
}
