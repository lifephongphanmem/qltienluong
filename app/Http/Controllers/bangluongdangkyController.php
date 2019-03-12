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
                ->where('madv',session('admin')->madv)
                ->first();

            if(count($model_chk)>0){
                return view('errors.trungbangdangkyluong');
            }

            //insert
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;

            $model_phucap = dmphucap_donvi::select('mapc','phanloai','congthuc','baohiem','tenpc')
                ->where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
            $a_th = array_merge(array('macanbo', 'macvcq', 'mapb', 'manguonkp','mact','baohiem'),
                array_column($model_phucap->toarray(),'mapc'));
            $a_th = array_merge(array('stt','tencanbo', 'msngbac', 'bac', 'pthuong','theodoi',
                'bhxh', 'bhyt', 'bhtn', 'kpcd','bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv'),
                $a_th);
            //Lấy tất cả cán bộ trong đơn vị
            $m_cb = hosocanbo::select($a_th)
                ->where('madv', $madv)
                ->where('theodoi','<', '9')->get();

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

            //Tạo bảng lương
            bangluongdangky::create($inputs);

            //Tính toán lương cho cán bộ kiêm nhiệm
            $m_donvi = dmdonvi::where('madv',session('admin')->madv)->first();

            foreach ($model_canbo_kn as $cb) {
                $cb->mabl = $inputs['mabl'];
                $ths = 0;
                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;
                    $ths += $cb->$mapc;
                }

                if($cb->phanloai == 'CHUCVU'){
                    $cb->heso = round($cb->heso * $cb->pthuong /100, session('admin')->lamtron);
                    $cb->st_heso = round($cb->st_heso * $cb->pthuong /100, 0);
                    $cb->vuotkhung = round($cb->vuotkhung * $cb->pthuong /100, session('admin')->lamtron);
                    $cb->st_vuotkhung = round($cb->st_vuotkhung * $cb->pthuong /100,0);
                    $ths = round($ths * $cb->pthuong /100, session('admin')->lamtron);
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


            $a_pc_coth = array('pcudn','pctnn');
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
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;

                foreach($a_pc_coth as $phca){//tính trc 1 số phụ cấp làm phụ cấp cơ sở
                    $pc = $model_phucap->where('mapc', $phca)->first();
                    if(count($pc)>0){//do 1 số nguồn ko lấy thâm niên nghề làm cơ sở
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
                                $cb->$phca = $heso * $cb->$phca / 100;
                                break;
                            }
                            default: {//trường hợp còn lại (ẩn,...)
                                $cb->$phca = 0;
                                break;
                            }
                        }
                    }
                }

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
                            if ($mapc != 'vuotkhung' && !in_array($mapc, $a_pc_coth)) {//vượt khung + ttn đã tính ở trên

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

                        $a_kq = $ct->toarray();
                        unset($a_kq['id']);
                        //bangluongdangky_phucap::create($a_kq);
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

    function tinhluong_khongdinhmuc($inputs){
        $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01');

        $a_thaisan =array_column(hosotamngungtheodoi::where('madv', $inputs['madv'])->where('maphanloai', 'THAISAN')
            ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)->get()->toarray(),'macanbo');

        $a_duongsuc = hosotamngungtheodoi::select('songaycong','songaynghi','macanbo')->where('madv', $inputs['madv'])->where('maphanloai','DUONGSUC')
            ->whereYear('ngaytu', $inputs['nam'])->whereMonth('ngaytu', $inputs['thang'])->get()->keyBy('macanbo')->toarray();

        $a_nghiphep = hosotamngungtheodoi::select('songaycong','songaynghi','macanbo')->where('madv', $inputs['madv'])->wherein('maphanloai',['NGHIPHEP','NGHIOM'])
            ->whereYear('ngaytu', $inputs['nam'])->whereMonth('ngaytu', $inputs['thang'])->get()->keyBy('macanbo')->toarray();

        /*
        $a_nghiphep = array_column(hosotamngungtheodoi::where('madv', $inputs['madv'])->wherein('maphanloai',['NGHIPHEP','NGHIOM'])
            ->whereYear('ngaytu', $inputs['nam'])->whereMonth('ngaytu', $inputs['thang'])->get()->toarray(),'songaynghi','macanbo');
        */
        $a_khongluong = array_column(hosotamngungtheodoi::where('madv', $inputs['madv'])
            ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)
            ->where('maphanloai', 'KHONGLUONG')->get()->toarray(),'macanbo');

        $a_daingay = array_column(hosotamngungtheodoi::where('madv', $inputs['madv'])
            ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)
            ->where('maphanloai', 'DAINGAY')->get()->toarray(),'macanbo');

        $model_phucap = dmphucap_donvi::select('mapc','phanloai','congthuc','baohiem','tenpc')
            ->where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
        //kiêm nhiệm
        $a_th = array_merge(array('macanbo', 'macvcq', 'mapb', 'manguonkp','mact','baohiem'),
            array_column($model_phucap->toarray(),'mapc'));

        //$m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th,array('phanloai')))->where('madv',$inputs['madv'])->wherein('manguonkp',[$inputs['manguonkp'],'',null])->get()->toArray();;
        $m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th,array('phanloai', 'pthuong')))->where('madv',$inputs['madv'])->get()->toArray();;

        //công tác
        $a_th = array_merge(array('stt','tencanbo', 'msngbac', 'bac', 'pthuong','theodoi',
            'bhxh', 'bhyt', 'bhtn', 'kpcd','bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv'),
            $a_th);
        $m_cb = hosocanbo::select($a_th)->where('madv', $inputs['madv'])->where('theodoi','<', '9')->get()->keyBy('macanbo')->toArray();
        $m_dv = dmdonvi::where('madv',$inputs['madv'])->first();
        //dd($m_cb);
        //dd($m_cb_kn);

        $a_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get()->keyBy('mact')->toArray();
        $a_nhomct = array_column(dmphanloaict::all()->toarray(), 'macongtac','mact');
        //dd($a_nhomct);
        $a_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
        $a_dd = array('pclt');//các loại phụ cấp cán bộ được điều động động đến được hưởng (chưa làm cho định mức)
        //$a_dn = array('pckv','pcudn');//các loại phụ cấp cán bộ nghỉ dài ngày đến được hưởng (chưa làm cho định mức)
        $ptdn = $m_dv->ptdaingay / 100;//cán bộ nghỉ dài ngày hưởng 50% lương
        $a_goc = array('heso','vuotkhung','pccv'); //mảng phụ cấp làm công thức tính
        $a_pc = $model_phucap->keyby('mapc')->toarray();

        $ngaycong = $m_dv->songaycong;
        $a_data_canbo = array();
        $a_data_phucap = array();
        $a_pc_coth = array('pcudn','pctnn');
        foreach ($m_cb as $key=>$val) {
            //Dùng tìm kiếm các bộ nào phù hợp. Do lvhd là mảng nên pải lọc
            /*
            $a_lv = explode(',', $canbo->lvhd);
            if (in_array($inputs['linhvuchoatdong'], $a_lv) || $canbo->lvhd == null) {
                $canbo->lvhd = $inputs['linhvuchoatdong'];
            }
            */

            $a_nguon = explode(',', $val['manguonkp']);
            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if ($val['manguonkp'] != '' && $val['manguonkp'] != null && !in_array($inputs['manguonkp'], $a_nguon)) {
                continue; //cán bộ ko thuộc nguồn quản lý => ko tính lương
            }

            $m_cb[$key]['mabl'] = $inputs['mabl'];
            $m_cb[$key]['congtac'] = 'CONGTAC';
            $m_cb[$key]['macongtac'] = isset($a_nhomct[$m_cb[$key]['mact']]) ? $a_nhomct[$m_cb[$key]['mact']] : '';
            $m_cb[$key]['heso'] = $val['heso'] * $val['pthuong'] / 100;
            $m_cb[$key]['vuotkhung'] = $val['heso'] * $val['vuotkhung'] / 100;//trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
            $m_cb[$key]['bhxh'] = floatval($val['bhxh']) / 100;
            $m_cb[$key]['bhyt'] = floatval($val['bhyt']) / 100;
            $m_cb[$key]['kpcd'] = floatval($val['kpcd']) / 100;
            $m_cb[$key]['bhtn'] = floatval($val['bhtn']) / 100;
            $m_cb[$key]['bhxh_dv'] = floatval($val['bhxh_dv']) / 100;
            $m_cb[$key]['bhyt_dv'] = floatval($val['bhyt_dv']) / 100;
            $m_cb[$key]['kpcd_dv'] = floatval($val['kpcd_dv']) / 100;
            $m_cb[$key]['bhtn_dv'] = floatval($val['bhtn_dv']) / 100;
            $m_cb[$key]['giaml'] = 0;
            //tính trc 1 số phụ cấp để làm hệ số cơ sơ
            foreach($a_pc_coth as $pc){
                if(isset($a_pc[$pc])){
                    switch ($a_pc[$pc]['phanloai']) {
                        case 0:
                        case 1: {//số tiền
                            //giữ nguyên ko cần tính
                            break;
                        }
                        case 2: {//phần trăm
                            $heso = 0;
                            foreach (explode(',', $a_pc[$pc]['congthuc']) as $ct) {
                                if ($ct != '' && $ct != $pc)
                                    $heso += $m_cb[$key][$ct];
                            }
                            $m_cb[$key][$pc] = $heso * $m_cb[$key][$pc] / 100;
                            break;
                        }
                        default: {//trường hợp còn lại (ẩn,...)
                            $m_cb[$key][$pc] = 0;
                            break;
                        }
                    }
                }
            }

            $tien = $tonghs = 0;
            //nếu cán bộ nghỉ thai sản
            $thaisan = in_array($m_cb[$key]['macanbo'],$a_thaisan) ? true : false;
            $khongluong = in_array($m_cb[$key]['macanbo'],$a_khongluong) ? true : false;
            $daingay = in_array($m_cb[$key]['macanbo'],$a_daingay) ? true : false;
            $nghi = isset($a_nghiphep[$m_cb[$key]['macanbo']]) ? true : false;
            $duongsuc = isset($a_duongsuc[$m_cb[$key]['macanbo']]) ? true : false;

            //Tính phụ cấp
            foreach ($a_pc as $k=>$v) {
                $mapc = $v['mapc'];
                $m_cb[$key]['st_'.$mapc] = 0;
                $a_pc[$k]['stbhxh'] = 0;
                $a_pc[$k]['stbhyt'] = 0;
                $a_pc[$k]['stkpcd'] = 0;
                $a_pc[$k]['stbhtn'] = 0;
                $a_pc[$k]['ttbh'] = 0;
                $a_pc[$k]['stbhxh_dv'] = 0;
                $a_pc[$k]['stbhyt_dv'] = 0;
                $a_pc[$k]['stkpcd_dv'] = 0;
                $a_pc[$k]['stbhtn_dv'] = 0;
                $a_pc[$k]['ttbh_dv'] = 0;
                $a_pc[$k]['sotien'] = 0;
                //$a_pc[$k]['heso_goc'] = $m_cb[$key][$mapc];//lưu lại hệ số gốc
                if ($m_cb[$key][$mapc] <= 0) {
                    continue;
                }
                //cán bộ được điều động đến chỉ hưởng các loại phụ cấp trong $a_dd
                if ($m_cb[$key]['theodoi'] == 4 && !in_array($mapc, $a_dd) && !in_array($mapc, $a_goc)) {
                    $m_cb[$key][$mapc] = 0;
                }

                switch ($a_pc[$k]['phanloai']) {
                    case 0: {//hệ số
                        $tonghs += $m_cb[$key][$mapc];
                        $a_pc[$k]['sotien'] = round($m_cb[$key][$mapc] * $inputs['luongcoban'],0);
                        break;
                    }
                    case 1: {//số tiền
                        $a_pc[$k]['sotien'] = chkDbl($m_cb[$key][$mapc]);
                        break;
                    }
                    case 2: {//phần trăm
                        if ($mapc != 'vuotkhung' && !in_array($mapc, $a_pc_coth)) {
                            $heso = 0;
                            foreach (explode(',', $a_pc[$k]['congthuc']) as $ct) {
                                if ($ct != '')
                                    $heso += $m_cb[$key][$ct];
                            }
                            $m_cb[$key][$mapc] = $heso * $m_cb[$key][$mapc] / 100;
                        }
                        $tonghs += $m_cb[$key][$mapc];
                        $a_pc[$k]['sotien'] = round($m_cb[$key][$mapc] * $inputs['luongcoban'],0);
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $m_cb[$key][$mapc] = 0;
                        break;
                    }
                }
                $tien += $a_pc[$k]['sotien'];
                /* dùng thai sản thay dài ngày
                 * if (!$khongluong || !$thaisan
                    || ($thaisan && in_array($mapc, $a_ts))
                    || ($daingay && in_array($mapc, $a_dn))
                )
                 */
                if (!$khongluong || !$thaisan
                    || ($thaisan && in_array($mapc, $a_ts))
                    || ($daingay && in_array($mapc, $a_ts))
                ) {//lưu vào bảng lương phụ cấp

                    /* tạm thời bỏ vì ko lưu bangluong_phucap
                    $a_pc[$k]['mabl'] = $inputs['mabl'];
                    $a_pc[$k]['luongcoban'] = $inputs['luongcoban'];
                    $a_pc[$k]['macanbo'] = $m_cb[$key]['macanbo'];
                    $a_pc[$k]['tencanbo'] = $m_cb[$key]['tencanbo'];
                    $a_pc[$k]['maso'] = $mapc;
                    $a_pc[$k]['ten'] = $a_pc[$k]['tenpc'];
                    $a_pc[$k]['heso'] = $m_cb[$key][$mapc];
                    if ($a_pc[$k]['heso'] > 10000) {//sô tiền
                        $a_pc[$k]['sotien'] = $daingay ? round($a_pc[$k]['heso'] * $ptdn, 0) : round($a_pc[$k]['heso'], 0);

                    } else {
                        $a_pc[$k]['sotien'] = $daingay ? round($inputs['luongcoban'] * $a_pc[$k]['heso'] * $ptdn, 0) : round($inputs['luongcoban'] * $a_pc[$k]['heso'], 0);
                    }
                    */
                    //$a_pc[$k]['heso'] = $m_cb[$key][$mapc];
                    $a_pc[$k]['sotien'] = $daingay ? round($a_pc[$k]['sotien'] * $ptdn, 0) : round($a_pc[$k]['sotien'], 0);
                    $m_cb[$key]['st_'.$mapc] = round($a_pc[$k]['sotien'], 0);
                    if ($m_cb[$key]['baohiem'] == 1 && $a_pc[$k]['baohiem'] == 1 && !$thaisan && !$daingay) {//nghỉ thai sản + dài ngày ko đóng bảo biểm
                        $a_pc[$k]['stbhxh'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['bhxh'], 0);
                        $a_pc[$k]['stbhyt'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['bhyt'], 0);
                        $a_pc[$k]['stkpcd'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['kpcd'], 0);
                        $a_pc[$k]['stbhtn'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['bhtn'], 0);
                        $a_pc[$k]['ttbh'] = $a_pc[$k]['stbhxh'] + $a_pc[$k]['stbhyt'] + $a_pc[$k]['stkpcd'] + $a_pc[$k]['stbhtn'];
                        $a_pc[$k]['stbhxh_dv'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['bhxh_dv'], 0);
                        $a_pc[$k]['stbhyt_dv'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['bhyt_dv'], 0);
                        $a_pc[$k]['stkpcd_dv'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['kpcd_dv'], 0);
                        $a_pc[$k]['stbhtn_dv'] = round($a_pc[$k]['sotien'] * $m_cb[$key]['bhtn_dv'], 0);
                        $a_pc[$k]['ttbh_dv'] = $a_pc[$k]['stbhxh_dv'] + $a_pc[$k]['stbhyt_dv'] + $a_pc[$k]['stkpcd_dv'] + $a_pc[$k]['stbhtn_dv'];
                    }
                    //$a_data_phucap[]=$a_pc[$k];
                }
            }
            //$ths = $ths + $heso_goc - $cb->heso;//do chỉ lương nb hưởng 85%, các hệ số hưởng %, bảo hiểm thì lấy 100% để tính
            //nếu cán bộ nghỉ thai sản (không gộp vào phần tính phụ cấp do trên bảng lương hiển thị hệ số nhưng ko có tiền)
            //$cb->tonghs = $tonghs;

            //if($m_cb[$key]['macongtac'] == 'KHONGCT') {
            if($m_cb[$key]['macongtac'] == 'KHONGCT' || $m_cb[$key]['macongtac'] == 'KHAC') { //cán bộ quân sự, đại biểu hội đồng nd đóng theo mức lương co ban
                $baohiem = $inputs['luongcoban'];
                $m_cb[$key]['stbhxh'] = round($baohiem * $m_cb[$key]['bhxh'], 0);
                $m_cb[$key]['stbhyt'] = round($baohiem * $m_cb[$key]['bhyt'], 0);
                $m_cb[$key]['stkpcd'] = round($baohiem * $m_cb[$key]['kpcd'], 0);
                $m_cb[$key]['stbhtn'] = round($baohiem * $m_cb[$key]['bhtn'], 0);
                $m_cb[$key]['ttbh'] = $m_cb[$key]['stbhxh'] + $m_cb[$key]['stbhyt']
                    + $m_cb[$key]['stkpcd'] + $m_cb[$key]['stbhtn'];
                $m_cb[$key]['stbhxh_dv'] = round($baohiem * $m_cb[$key]['bhxh_dv'], 0);
                $m_cb[$key]['stbhyt_dv'] = round($baohiem * $m_cb[$key]['bhyt_dv'], 0);
                $m_cb[$key]['stkpcd_dv'] = round($baohiem * $m_cb[$key]['kpcd_dv'], 0);
                $m_cb[$key]['stbhtn_dv'] = round($baohiem * $m_cb[$key]['bhtn_dv'], 0);
                $m_cb[$key]['ttbh_dv'] = $m_cb[$key]['stbhxh_dv'] + $m_cb[$key]['stbhyt_dv']
                    + $m_cb[$key]['stkpcd_dv'] + $m_cb[$key]['stbhtn_dv'];
            }else {
                $m_cb[$key]['stbhxh'] = array_sum(array_column($a_pc,'stbhxh'));
                $m_cb[$key]['stbhyt'] = array_sum(array_column($a_pc,'stbhyt'));
                $m_cb[$key]['stkpcd'] = array_sum(array_column($a_pc,'stkpcd'));
                $m_cb[$key]['stbhtn'] = array_sum(array_column($a_pc,'stbhtn'));
                $m_cb[$key]['ttbh'] = $m_cb[$key]['stbhxh'] + $m_cb[$key]['stbhyt']
                    + $m_cb[$key]['stkpcd'] + $m_cb[$key]['stbhtn'];
                $m_cb[$key]['stbhxh_dv'] = array_sum(array_column($a_pc,'stbhxh_dv'));
                $m_cb[$key]['stbhyt_dv'] = array_sum(array_column($a_pc,'stbhyt_dv'));
                $m_cb[$key]['stkpcd_dv'] = array_sum(array_column($a_pc,'stkpcd_dv'));
                $m_cb[$key]['stbhtn_dv'] = array_sum(array_column($a_pc,'stbhtn_dv'));
                $m_cb[$key]['ttbh_dv'] = $m_cb[$key]['stbhxh_dv'] + $m_cb[$key]['stbhyt_dv']
                    + $m_cb[$key]['stkpcd_dv'] + $m_cb[$key]['stbhtn_dv'];
            }
            //Cán bộ được điều động đến
            if($m_cb[$key]['theodoi'] == 4){
                $tien = $tonghs = 0;
                foreach($a_dd as $val){
                    if ($m_cb[$key][$val] < 10000) {//sô tiền
                        $tonghs += $m_cb[$key][$val];
                    }
                    $tien += $m_cb[$key]['st_'.$val];
                }
            }

            //Cán bộ đang điều động
            if($m_cb[$key]['theodoi'] == 3){
                foreach($a_dd as $val){
                    if($m_cb[$key][$val] < 10000){//sô tiền
                        $tonghs -= $m_cb[$key][$val];
                    }
                    $tien -= $m_cb[$key]['st_'.$val];
                }
            }

            if($daingay){
                $m_cb[$key]['tencanbo'] .= ' (nghỉ dài ngày)';
                $m_cb[$key]['congtac'] = 'DAINGAY';
                $tien = $tonghs = 0;
                //foreach($a_dn as $val){
                foreach($a_ts as $val){
                    if ($m_cb[$key][$val] < 10000) {//sô tiền
                        $tonghs += $m_cb[$key][$val];
                    }
                    $tien += $m_cb[$key]['st_'.$val];
                }
                goto tinhluong;
            }

            if($thaisan) {
                $m_cb[$key]['tencanbo'] .= ' (nghỉ thai sản)';
                $m_cb[$key]['congtac'] = 'THAISAN';
                //kiểm tra phân loại công tác
                $tien = $tonghs = 0;
                foreach ($a_ts as $val) {
                    if ($m_cb[$key][$val] < 10000) {//sô tiền
                        $tonghs += $m_cb[$key][$val];
                    }
                    $tien += $m_cb[$key]['st_'.$val];
                }
                goto tinhluong;
            }

            if($khongluong){//tính không lương rồi thoát
                $m_cb[$key]['tencanbo'] .= ' (nghỉ không lương)';
                $m_cb[$key]['stbhxh'] = 0;
                $m_cb[$key]['stbhyt'] = 0;
                $m_cb[$key]['stkpcd'] = 0;
                $m_cb[$key]['stbhtn'] = 0;
                $m_cb[$key]['ttbh'] = 0;
                $m_cb[$key]['stbhxh_dv'] = 0;
                $m_cb[$key]['stbhyt_dv'] = 0;
                $m_cb[$key]['stkpcd_dv'] = 0;
                $m_cb[$key]['stbhtn_dv'] = 0;
                $m_cb[$key]['ttbh_dv'] = 0;
                $tonghs = $tien = 0;
                $m_cb[$key]['congtac'] = 'KHONGLUONG';
                goto tinhluong;
            }

            //if($duongsuc) {} //đang làm

            if($nghi) {
                $cb_nghi = $a_nghiphep[$m_cb[$key]['macanbo']];
                $ngaycong = $cb_nghi['songaycong'] > 0 ? $cb_nghi['songaycong'] : $ngaycong;
                $m_cb[$key]['congtac'] = 'NGHIPHEP';
                $sotiencong = $inputs['luongcoban'] *
                    ($m_cb[$key]['heso'] + $m_cb[$key]['vuotkhung'] + $m_cb[$key]['pccv']
                        + $m_cb[$key]['hesobl'] + $m_cb[$key]['pctnn']);
                $tiencong = round($sotiencong / $ngaycong, 0);

                if($cb_nghi['songaynghi'] >= 15) {//nghỉ quá 15 ngày thì ko đóng bảo hiểm
                    $m_cb[$key]['stbhxh'] = 0;
                    $m_cb[$key]['stbhyt'] = 0;
                    $m_cb[$key]['stkpcd'] = 0;
                    $m_cb[$key]['stbhtn'] = 0;
                    $m_cb[$key]['ttbh'] = 0;
                    $m_cb[$key]['stbhxh_dv'] = 0;
                    $m_cb[$key]['stbhyt_dv'] = 0;
                    $m_cb[$key]['stkpcd_dv'] = 0;
                    $m_cb[$key]['stbhtn_dv'] = 0;
                    $m_cb[$key]['ttbh_dv'] = 0;
                }
                $m_cb[$key]['giaml'] = $cb_nghi['songaynghi'] >= $ngaycong ? $sotiencong : ($tiencong * $cb_nghi['songaynghi']);
            }

            tinhluong:
            $m_cb[$key]['tonghs'] = $tonghs;
            $m_cb[$key]['ttl'] = $tien;
            $m_cb[$key]['luongtn'] = $m_cb[$key]['ttl'] - $m_cb[$key]['ttbh'] - $m_cb[$key]['giaml'];
            $a_data_canbo[]= $m_cb[$key];
        }

        //Mảng chứa các cột bỏ để chạy hàm insert
        $a_col_pc = array('id','baohiem','mapc','luongcoban','tenpc');
        /*
        $a_data_phucap = unset_key($a_data_phucap,$a_col_pc);
        $a_chunk = array_chunk($a_data_phucap, 100);
        foreach(array_chunk($a_data_phucap, 100)  as $data){
            //bangluong_phucap::insert($data);
        }
        */
        $a_col_cb = array('id','bac','baohiem','macongtac','manguonkp','pthuong','theodoi');
        $a_data_canbo = unset_key($a_data_canbo,$a_col_cb);

        //dd($a_data_canbo);
        foreach(array_chunk($a_data_canbo, 50)  as $data){
            bangluong_ct::insert($data);
        }


        //Tính toán lương cho cán bộ kiêm nhiệm
        $a_kn_canbo = array();
        $a_kn_phucap = array();

        for($i=0; $i<count($m_cb_kn); $i++){
            if(!array_key_exists($m_cb_kn[$i]['macanbo'],$m_cb)){
                continue;
            }
            $a_nguon = explode(',', $m_cb_kn[$i]['manguonkp']);
            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if ($m_cb_kn[$i]['manguonkp'] != '' && !in_array($inputs['manguonkp'], $a_nguon) && $m_cb_kn[$i]['manguonkp'] != null ) {
                continue;
            }
            $canbo = $m_cb[$m_cb_kn[$i]['macanbo']];
            $m_cb_kn[$i]['tencanbo'] = $canbo['tencanbo'];
            $m_cb_kn[$i]['stt'] = $canbo['stt'];
            $m_cb_kn[$i]['mabl'] = $inputs['mabl'];
            $m_cb_kn[$i]['congtac'] = $m_cb_kn[$i]['phanloai'];
            $m_cb_kn[$i]['stbhxh'] = 0;
            $m_cb_kn[$i]['stbhyt'] = 0;
            $m_cb_kn[$i]['stkpcd'] = 0;
            $m_cb_kn[$i]['stbhtn'] = 0;
            $m_cb_kn[$i]['ttbh'] = 0;
            $m_cb_kn[$i]['stbhxh_dv'] = 0;
            $m_cb_kn[$i]['stbhyt_dv'] = 0;
            $m_cb_kn[$i]['stkpcd_dv'] = 0;
            $m_cb_kn[$i]['stbhtn_dv'] = 0;
            $m_cb_kn[$i]['ttbh_dv'] = 0;


            if(isset($a_pc['pcthni'])){
                $pctn = $a_pc['pcthni'];
                switch ($pctn['phanloai']) {
                    case 0:
                    case 1: {//số tiền
                        //giữ nguyên ko cần tính
                        break;
                    }
                    case 2: {//phần trăm
                        $heso = 0;
                        foreach (explode(',', $pctn['congthuc']) as $ct) {
                            if ($ct != '' && $ct != 'pcthni')
                                $heso += $canbo[$ct];
                        }
                        //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                        $heso += $canbo['hesopc'];
                        $m_cb_kn[$i]['pcthni'] = $heso * $m_cb_kn[$i]['pcthni'] / 100;
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $m_cb_kn[$i]['pcthni'] = 0;
                        break;
                    }
                }
            }
            $tonghs = $tien = 0;
            $canbo['pcthni'] = $m_cb_kn[$i]['pcthni']; //set vao hồ sơ cán bộ để tính công thức lương
            $canbo['pctn'] = $m_cb_kn[$i]['pctn'];

            foreach ($a_pc as $k=>$v) {
                $mapc = $v['mapc'];
                $m_cb_kn[$i]['st_'.$mapc] = 0;
                if($m_cb_kn[$i][$mapc] <= 0){
                    continue;
                }
                //$a_pc[$k]['heso_goc'] =$m_cb_kn[$i][$mapc];//lưu lại hệ số gốc

                switch ($v['phanloai']) {
                    case 0: {//hệ số
                        $tonghs += $m_cb_kn[$i][$mapc];
                        $m_cb_kn[$i]['st_'.$mapc] = round($m_cb_kn[$i][$mapc] * $inputs['luongcoban']);
                        $tien += $m_cb_kn[$i]['st_'.$mapc];
                        break;
                    }
                    case 1: {//số tiền
                        $tien += chkDbl($m_cb_kn[$i][$mapc]);
                        $m_cb_kn[$i]['st_'.$mapc] = chkDbl($m_cb_kn[$i][$mapc]);
                        break;
                    }
                    case 2: {//phần trăm
                        if($mapc != 'pcthni'){
                            $heso = 0;
                            foreach (explode(',', $v['congthuc']) as $cthuc) {
                                if ($cthuc != '')
                                    $heso += $canbo[$cthuc];
                            }

                            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                            $heso += $canbo['hesopc'];
                            $m_cb_kn[$i][$mapc] = $heso * $m_cb_kn[$i][$mapc] / 100;
                            $tonghs += $m_cb_kn[$i][$mapc];
                            $m_cb_kn[$i]['st_'.$mapc] = round($m_cb_kn[$i][$mapc] * $inputs['luongcoban']);
                            $tien += $m_cb_kn[$i]['st_'.$mapc];
                        }
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $m_cb_kn[$i][$mapc] = 0;
                        $a_pc[$k]['sotien'] = 0;
                        break;
                    }
                }
                $a_pc[$k]['mabl'] = $inputs['mabl'];
                $a_pc[$k]['luongcoban'] = $inputs['luongcoban'];
                $a_pc[$k]['macanbo'] = $m_cb[$key]['macanbo'];
                $a_pc[$k]['tencanbo'] = $m_cb[$key]['tencanbo'];
                $a_pc[$k]['maso'] = $mapc;
                $a_pc[$k]['ten'] = $a_pc[$k]['tenpc'];
                $a_pc[$k]['heso'] = $m_cb[$key][$mapc];
                //$m_cb_kn[$i]['st_'.$mapc] = $a_pc[$k]['sotien']; round($inputs['luongcoban'] * $tonghs
                //$a_pc[$k]['sotien'] = round($inputs['luongcoban'] * $tonghs + $tien, 0);

                $a_kn_phucap[] = $a_pc[$k];
            }

            //ko tính % trong công thức duyệt phụ cấp vì khi tính % sẽ sai
            if($m_cb_kn[$i]['phanloai'] == 'CHUCVU'){
                $m_cb_kn[$i]['heso'] = round($m_cb_kn[$i]['heso'] * $m_cb_kn[$i]['pthuong'] /100, session('admin')->lamtron);
                $m_cb_kn[$i]['st_heso'] = round($m_cb_kn[$i]['st_heso'] * $m_cb_kn[$i]['pthuong'] /100, 0);
                $m_cb_kn[$i]['vuotkhung'] = round($m_cb_kn[$i]['vuotkhung'] * $m_cb_kn[$i]['pthuong'] /100, session('admin')->lamtron);
                $m_cb_kn[$i]['st_vuotkhung'] = round($m_cb_kn[$i]['st_vuotkhung'] * $m_cb_kn[$i]['pthuong'] /100, 0);
                $tonghs = round($tonghs * $m_cb_kn[$i]['pthuong'] /100, session('admin')->lamtron);
                $tien = round($tien * $m_cb_kn[$i]['pthuong'] /100, 0);
            }

            $m_cb_kn[$i]['tonghs'] = $tonghs;
            $m_cb_kn[$i]['ttl'] = $tien;

            if ($m_cb_kn[$i]['baohiem'] == 1) {
                if (isset($a_phanloai[$m_cb_kn[$i]['mact']])) {//do trc nhập chưa lưu mact
                    $phanloai = $a_phanloai[$m_cb_kn[$i]['mact']];
                    $m_cb_kn[$i]['stbhxh'] = round($inputs['luongcoban'] * floatval($phanloai['bhxh']) / 100, 0);
                    $m_cb_kn[$i]['stbhyt'] = round($inputs['luongcoban'] * floatval($phanloai['bhyt']) / 100, 0);
                    $m_cb_kn[$i]['stkpcd'] = round($inputs['luongcoban'] * floatval($phanloai['kpcd']) / 100, 0);
                    $m_cb_kn[$i]['stbhtn'] = round($inputs['luongcoban'] * floatval($phanloai['bhtn']) / 100, 0);
                    $m_cb_kn[$i]['stbhxh_dv'] = round($inputs['luongcoban'] * floatval($phanloai['bhxh_dv']) / 100, 0);
                    $m_cb_kn[$i]['stbhyt_dv'] = round($inputs['luongcoban'] * floatval($phanloai['bhyt_dv']) / 100, 0);
                    $m_cb_kn[$i]['stkpcd_dv'] = round($inputs['luongcoban'] * floatval($phanloai['kpcd_dv']), 0);
                    $m_cb_kn[$i]['stbhtn_dv'] = round($inputs['luongcoban'] * floatval($phanloai['bhtn_dv']), 0);

                    $m_cb_kn[$i]['ttbh'] = $m_cb_kn[$i]['stbhxh'] + $m_cb_kn[$i]['stbhyt'] + $m_cb_kn[$i]['stkpcd'] + $m_cb_kn[$i]['stbhtn'];
                    $m_cb_kn[$i]['ttbh_dv'] = $m_cb_kn[$i]['stbhxh_dv'] + $m_cb_kn[$i]['stbhyt_dv'] + $m_cb_kn[$i]['stkpcd_dv'] + $m_cb_kn[$i]['stbhtn_dv'];
                }
            }
            $m_cb_kn[$i]['luongtn'] =  $m_cb_kn[$i]['ttl'] -  $m_cb_kn[$i]['ttbh'];
            $a_kn_canbo[] = $m_cb_kn[$i];
        }
        //Mảng chứa các cột bỏ để chạy hàm insert
        $a_col_pc = array('id','baohiem','mapc','luongcoban','tenpc');
        $a_kn_phucap = unset_key($a_kn_phucap,$a_col_pc);
        foreach(array_chunk($a_kn_phucap, 100)  as $data){
            //bangluong_phucap::insert($data);
        }

        $a_col_cbkn = array('id','bac','baohiem','macongtac','manguonkp','pthuong','theodoi','phanloai');
        $a_kn_canbo = unset_key($a_kn_canbo,$a_col_cbkn);

        foreach(array_chunk($a_kn_canbo, 50)  as $data){
            bangluong_ct::insert($data);
        }
        //dd($a_data_canbo);
        //dd($a_kn_canbo);
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
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluongdangky::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluongdangky_ct')->where('mabl',$mabl);
                })->get();

            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }

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
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluongdangky::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluongdangky_ct')->where('mabl',$mabl);
                })->get();

            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }

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

    function getBangLuong($inputs, $phanloai=0)
    {
        $mabl = $inputs['mabl'];
        $model = bangluongdangky_ct::where('mabl', $mabl)->get();
        $m_bl = bangluongdangky::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
        //$m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
        $dmchucvucq = getChucVuCQ(false);
        $sunghiep = array_column(hosocanbo::select('sunghiep', 'macanbo')->where('madv', $m_bl->madv)->get()->toArray(), 'sunghiep', 'macanbo');
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
            $hs->sunghiep = isset($sunghiep[$hs->macanbo]) ? $sunghiep[$hs->macanbo] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
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
