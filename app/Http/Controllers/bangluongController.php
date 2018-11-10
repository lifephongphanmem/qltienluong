<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluong_phucap;
use App\bangluong_truc;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use App\dmtieumuc_default;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\hosotamngungtheodoi;
use App\hosotruc;
use App\hosotruylinh;
use App\ngachluong;
use App\nguonkinhphi_dinhmuc;
use App\nguonkinhphi_dinhmuc_ct;
use App\tonghopluong_donvi;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class bangluongController extends Controller
{
    function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $nam = isset($inputs['nam'])? $inputs['nam'] : date('Y');
            $a_trangthai = getStatus();
            $model_bangluong = bangluong::where('madv', session('admin')->madv)->get();
            $a_data = array(array('thang' => '01', 'mathdv' => null),
                array('thang' => '02', 'mathdv' => null),
                array('thang' => '03', 'mathdv' => null),
                array('thang' => '04', 'mathdv' => null),
                array('thang' => '05', 'mathdv' => null),
                array('thang' => '06', 'mathdv' => null),
                array('thang' => '07', 'mathdv' => null),
                array('thang' => '08', 'mathdv' => null),
                array('thang' => '09', 'mathdv' => null),
                array('thang' => '10', 'mathdv' => null),
                array('thang' => '11', 'mathdv' => null),
                array('thang' => '12', 'mathdv' => null)
            );
            for ($i = 0; $i < count($a_data); $i++) {
                $a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $bangluong = $model_bangluong->where('thang', $a_data[$i]['thang'])->where('nam', $nam);
                if (count($bangluong) > 0) {
                    $a_data[$i]['trangthai'] = 'BANGLUONG';
                } else {
                    $a_data[$i]['trangthai'] = 'CHUALUONG';
                }
            }

            return view('manage.bangluong.danhsach')
                ->with('furl', '/chuc_nang/bang_luong/')
                ->with('model', $a_data)
                ->with('nam', $nam)
                ->with('a_trangthai', $a_trangthai)
                ->with('pageTitle', 'Danh sách bảng lương');
        } else
            return view('errors.notlogin');
    }

    function chitra(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['manguonkp']= '13';
            $inputs['luongcb']= getGeneralConfigs()['luongcb'];
            $inputs['furl']= '/chuc_nang/bang_luong/';
            $inputs['furl_ajax']= '/ajax/bang_luong/';
            $inputs['dinhmuc']= 0;

            $dinhmuc = nguonkinhphi_dinhmuc::where('manguonkp',$inputs['manguonkp'])->where('madv',session('admin')->madv)->first();
            $maso = count($dinhmuc)> 0 ? $dinhmuc->maso : '';
            $dinhmuc_ct = nguonkinhphi_dinhmuc_ct::where('maso',$maso)->get();

            if(count($dinhmuc_ct)>0){
                $inputs['dinhmuc']= 1;
                $inputs['luongcb']= $dinhmuc->luongcoban;
            }

            $m_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $a_phanloai = getPhanLoaiBangLuong();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model = bangluong::where('madv', session('admin')->madv)->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->get();
            $model_tonghop = tonghopluong_donvi::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->first();
            $thaotac = count($model_tonghop)> 0 ? false :true;
            $inputs['thaotac'] = $thaotac;
            foreach ($model as $bl) {
                $bl->thaotac = $thaotac;
                $bl->tennguonkp = isset($m_nguonkp[$bl->manguonkp]) ? $m_nguonkp[$bl->manguonkp] : '';
                $bl->tenphanloai = isset($a_phanloai[$bl->phanloai]) ? $a_phanloai[$bl->phanloai] : 'Bảng lương cán bộ';
            }
            //$model = $model->sortby('nam')->sortby('thang');
            //dd($model);
            //dd($inputs);
            return view('manage.bangluong.index')
                //->with('furl', '/chuc_nang/bang_luong/')
                //->with('furl_ajax', '/ajax/bang_luong/')
                ->with('model', $model)
                ->with('inputs', $inputs)
                ->with('m_linhvuc', getLinhVucHoatDong(false))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('m_nguonkp', $m_nguonkp)
                ->with('pageTitle', 'Danh sách bảng lương');
        } else
            return view('errors.notlogin');
    }

    //Insert + update bảng lương
    function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
        $model = bangluong::where('mabl', $inputs['mabl'])->first();
        //dd($inputs);
        if (count($model) > 0) {
            //update
            $model->update($inputs);
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
        } else {
            //kiểm tra bảng lương cùng nguồn, lĩnh vực hoạt động, lương cơ bản =>ko cho tạo
            $model_chk = bangluong::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])
                ->where('phanloai', 'BANGLUONG')
                ->where('manguonkp', $inputs['manguonkp'])
                ->where('madv',session('admin')->madv)
                ->first();

            if(count($model_chk)>0){
                return view('errors.trungbangluong');
            }
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['phanloai'] = 'BANGLUONG';
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);

            if(boolval($inputs['dinhmuc'])){
                $this->tinhluong_dinhmuc($inputs);
            }else{
                $this->tinhluong_khongdinhmuc($inputs);
            }

        }
        //Tạo bảng lương
        bangluong::create($inputs);
        return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'].'&mapb=');
    }

    function tinhluong_dinhmuc($inputs){
        $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01');
        $m_tamngung = hosotamngungtheodoi::where('madv', $inputs['madv'])->where('maphanloai', 'THAISAN')->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)->get();
        $m_nghiphep = hosotamngungtheodoi::where('madv', $inputs['madv'])->wherein('maphanloai',['NGHIPHEP','NGHIOM'])->whereYear('ngaytu', $inputs['nam'])->whereMonth('ngaytu', $inputs['thang'])->get();

        $m_cb = hosocanbo::where('madv', $inputs['madv'])->where('theodoi','<', '9')->get();
        $m_cbkn = hosocanbo::where('madv', $inputs['madv'])->where('theodoi','<', '9')->get();

        //Lấy danh sách cán bộ kiêm nhiệm
        $model_canbo_kn = hosocanbo_kiemnhiem::where('madv',session('admin')->madv)->wherein('manguonkp',[$inputs['manguonkp'],''])->get();

        foreach ($m_cb as $canbo) {
            //Dùng tìm kiếm các bộ nào phù hợp. Do lvhd là mảng nên pải lọc
            /*
            $a_lv = explode(',', $canbo->lvhd);
            if (in_array($inputs['linhvuchoatdong'], $a_lv) || $canbo->lvhd == null) {
                $canbo->lvhd = $inputs['linhvuchoatdong'];
            }
            */
            $a_nguon = explode(',', $canbo->manguonkp);
            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if (in_array($inputs['manguonkp'], $a_nguon) || $canbo->manguonkp == null || $canbo->manguonkp == '') {
                $canbo->manguonkp = $inputs['manguonkp'];
            }
        }
        //$m_cb = $m_cb->where('lvhd', $inputs['linhvuc']);
        $m_cb = $m_cb->where('manguonkp', $inputs['manguonkp']);
        $a_ct = array_column(dmphanloaict::all()->toArray(),'macongtac','mact');
        $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();

        //Không tính truy lĩnh
        $a_goc = array('heso','vuotkhung','pccv'); //mảng phụ cấp làm công thức tính
        //=> lấy phụ cấp theo nguồn chứ ko pải phụ cấp toàn hệ thống
        $m_dm = nguonkinhphi_dinhmuc::where('madv', session('admin')->madv)->where('manguonkp', $inputs['manguonkp'])->first();
        $model_dimhmuc = nguonkinhphi_dinhmuc_ct::where('maso',$m_dm->maso)->get();
        /*
        $model_dimhmuc = nguonkinhphi_dinhmuc_ct::join('nguonkinhphi_dinhmuc','nguonkinhphi_dinhmuc.maso','=','nguonkinhphi_dinhmuc_ct.maso')
            ->where('madv', session('admin')->madv)
            ->where('manguonkp', $inputs['manguonkp'])
            ->select('nguonkinhphi_dinhmuc_ct.*')->get();
        */
        //dd($model_dimhmuc);
        $a_nguonpc = array_column($model_dimhmuc->toarray(), 'mapc');
        $a_mucluong = array_column($model_dimhmuc->toarray(),'luongcoban', 'mapc');
        $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai','<','3')->get();

        foreach($model_phucap as $pc){
            $pc->luongcoban = isset($a_mucluong[$pc->mapc])? $a_mucluong[$pc->mapc] : 0;
        }

        $a_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');

        $a_data_canbo = array();
        $a_data_phucap = array();
        $a_pc_coth = array('pcudn','pctnn');
        $ngaycong = dmdonvi::where('madv',$inputs['madv'])->first()->songaycong;
        foreach ($m_cb as $cb) {
            $cb->mabl = $inputs['mabl'];
            $cb->congtac = 'CONGTAC';
            $cb->bhxh = floatval($cb->bhxh) / 100;
            $cb->bhyt = floatval($cb->bhyt) / 100;
            $cb->kpcd = floatval($cb->kpcd) / 100;
            $cb->bhtn = floatval($cb->bhtn) / 100;
            $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
            $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
            $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
            $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;
            $cb->macongtac = isset($a_ct[$cb->mact])? $a_ct[$cb->mact] : '' ;

            //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
            //$heso_goc = $cb->heso * $cb->pthuong / 100;
            $cb->heso = $cb->heso * $cb->pthuong / 100;
            $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
            foreach($a_pc_coth as $phca){//tính trc 1 số phụ cấp làm phụ cấp cơ sở
                $pctnn = $model_phucap->where('mapc', $phca)->first();
                if(count($pctnn)>0){//do 1 số nguồn ko lấy thâm niên nghề làm cơ sở
                    $pl = getDbl($pctnn->phanloai);
                    switch ($pl) {
                        case 0:
                        case 1: {//số tiền
                            //giữ nguyên ko cần tính
                            break;
                        }
                        case 2: {//phần trăm
                            $heso = 0;
                            foreach (explode(',', $pctnn->congthuc) as $ct) {
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

            $tt = $ths = 0;
            //nếu cán bộ nghỉ thai sản
            $thaisan = count($m_tamngung->where('macanbo',$cb->macanbo))>0? true : false;

            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                $mapc_st ='st_'.$mapc;
                $cb->$mapc_st = 0;
                //gán số tiền bảo hiểm  = 0 khi tính để ko trùng với giá trị cán bộ trc
                $ct->stbhxh = 0;
                $ct->stbhyt = 0;
                $ct->stkpcd = 0;
                $ct->stbhtn = 0;
                $ct->stbhxh_dv = 0;
                $ct->stbhyt_dv = 0;
                $ct->stkpcd_dv = 0;
                $ct->stbhtn_dv = 0;
                $ct->sotien = 0;
                if($cb->$mapc <= 0){
                    continue;
                }
                //kiểm tra phụ cấp =>ko trong nhóm tính
                //ko trong nhóm phụ cấp làm cơ sơ = >set = 0
                if(!in_array($mapc, $a_nguonpc)) {
                    if (!in_array($mapc, $a_goc)) {
                        $cb->$mapc = 0;
                    }
                    continue;
                }

                $ct->heso_goc = $cb->$mapc;
                $pl = getDbl($ct->phanloai);

                switch ($pl) {
                    case 0: {//hệ số
                        $ths += $cb->$mapc;
                        $sotien = $cb->$mapc * $ct->luongcoban;
                        break;
                    }
                    case 1: {//số tiền
                        $tt += chkDbl($cb->$mapc);
                        $sotien = chkDbl($cb->$mapc);
                        break;
                    }
                    case 2: {//phần trăm
                        $heso = 0;
                        foreach (explode(',', $ct->congthuc) as $cthuc) {
                            if ($cthuc != '')
                                $heso += $cb->$cthuc;
                        }

                        if ($mapc != 'vuotkhung' && !in_array($mapc, $a_pc_coth)) {//vượt khung đã tính ở trên
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                        }
                        $ths += $cb->$mapc;
                        $sotien = $cb->$mapc * $ct->luongcoban;
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $cb->$mapc = 0;
                        $sotien = 0;
                        break;
                    }
                }

                if (!$thaisan ||($thaisan && in_array($mapc,$a_ts)) ) {//lưu vào bảng lương phụ cấp (chi luu số tiền >0)
                    $ct->mabl = $inputs['mabl'];
                    //$ct->macanbo = $cb->macanbo;
                    //$ct->tencanbo = $cb->tencanbo;
                    //$ct->maso = $mapc;
                    //$ct->ten = $ct->tenpc;
                    //$ct->heso = $cb->$mapc;
                    $ct->sotien = round($sotien, 0);
                    $cb->$mapc_st = $ct->sotien;
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

                    //$a_kq = $ct->toarray();
                    //unset($a_kq['id']);
                    //bangluong_phucap::create($a_kq);
                }
            }
            //$ths = $ths + $heso_goc - $cb->heso;//do chỉ lương nb hưởng 85%, các hệ số hưởng %, bảo hiểm thì lấy 100% để tính
            $cb->tonghs = $ths;
            //nếu cán bộ nghỉ thai sản
            if($thaisan){
                $cb->tencanbo = $cb->tencanbo . '(nghỉ thai sản)';
                $cb->congtac = 'THAISAN';
            }

            $cb->ttl = $model_phucap->sum('sotien'); //do mức lương cơ bản đi theo phụ cấp =>ko thể lấy tổng hệ số * lương cơ bản
            //$cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
            //kiểm tra cán bộ ko chuyên trách thì tự động lấy lương cơ bản * % bảo hiểm
            if($cb->baohiem && $cb->macongtac == 'KHONGCT') {
                $baohiem = $inputs['luongcoban'];
                //$baohiem = ($cb->hesopc < 1 ? 1 : $cb->hesopc) * ($inputs['luongcoban']);
                $cb->stbhxh = round($baohiem * $cb->bhxh, 0);
                $cb->stbhyt = round($baohiem * $cb->bhyt, 0);
                //$cb->stkpcd = round($cb->hesopc * $inputs['luongcoban'] * $cb->kpcd, 0);
                $cb->stkpcd = round($baohiem * $cb->kpcd, 0);
                $cb->stbhtn = round($baohiem * $cb->bhtn, 0);
                $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                $cb->stbhxh_dv = round($baohiem * $cb->bhxh_dv, 0);
                $cb->stbhyt_dv = round($baohiem * $cb->bhyt_dv, 0);
                $cb->stkpcd_dv = round($baohiem * $cb->kpcd_dv, 0);
                //$cb->stkpcd_dv = round($cb->hesopc * $inputs['luongcoban'] * $cb->kpcd_dv, 0);
                $cb->stbhtn_dv = round($baohiem * $cb->bhtn_dv, 0);
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
            }elseif($cb->baohiem){
                $cb->stbhxh = $model_phucap->sum('stbhxh');
                $cb->stbhyt = $model_phucap->sum('stbhyt');
                $cb->stkpcd = $model_phucap->sum('stkpcd');
                $cb->stbhtn = $model_phucap->sum('stbhtn');
                $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                $cb->stbhxh_dv = $model_phucap->sum('stbhxh_dv');
                $cb->stbhyt_dv = $model_phucap->sum('stbhyt_dv');
                $cb->stkpcd_dv = $model_phucap->sum('stkpcd_dv');
                $cb->stbhtn_dv = $model_phucap->sum('stbhtn_dv');
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
            }

            //if(count($a_nguonpc) > 0 && $inputs['manguonkp'] == '12'){//chưa xử lý dc
            if($m_dm->baohiem == 0){//xem để lồng lên tren đõ 1 vong for
                $cb->stbhxh = 0;
                $cb->stbhyt = 0;
                $cb->stkpcd = 0;
                $cb->stbhtn = 0;
                $cb->ttbh = 0;
                $cb->stbhxh_dv = 0;
                $cb->stbhyt_dv = 0;
                $cb->stkpcd_dv = 0;
                $cb->stbhtn_dv = 0;
                $cb->ttbh_dv = 0;
            }

            //nếu cán bộ nghỉ phép
            //ngày công = lương co + chuc vu + ....
            $nghi = $m_nghiphep->where('macanbo', $cb->macanbo)->first();
            if (count($nghi) > 0) {
                $cb->congtac = 'NGHIPHEP';
                $sotiencong = $model_phucap->wherein('maso',['heso','vuotkhung','pccv','hesobl','pctnn'])->sum('sotien');
                //$sotiencong = $inputs['luongcoban'] * ($cb->heso + $cb->vuotkhung + $cb->pccv + $cb->hesobl + $cb->pctnn);
                $tiencong = round($sotiencong / $ngaycong, 0);
                if($nghi->songaynghi >= 15){//nghỉ quá 15 ngày thì ko đóng bảo hiểm
                    $cb->stbhxh = 0;
                    $cb->stbhyt = 0;
                    $cb->stkpcd = 0;
                    $cb->stbhtn = 0;
                    $cb->ttbh = 0;
                    $cb->stbhxh_dv = 0;
                    $cb->stbhyt_dv = 0;
                    $cb->stkpcd_dv = 0;
                    $cb->stbhtn_dv = 0;
                    $cb->ttbh_dv = 0;
                }
                $cb->giaml = $nghi->songaynghi >= $ngaycong ? $sotiencong : ($tiencong * $nghi->songaynghi);
            }

            $cb->luongtn = $cb->ttl - $cb->ttbh - $cb->giaml;

            $kq = $cb->toarray();
            unset($kq['id']);
            bangluong_ct::create($kq);
        }

        //Tính toán lương cho cán bộ kiêm nhiệm
        //$m_donvi = dmdonvi::where('madv',$madv)->first();
        foreach ($model_canbo_kn as $cb) {
            //trong kiêm nhiệm: thâm niên lấy  % lương hệ số
            //đặc thù tính
            //lấy thông tin ở bảng hồ sơ cán bộ để lấy thông tin lương, phụ cấp
            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
            $canbo = $m_cbkn->where('macanbo',$cb->macanbo)->first();
            //$canbo = $m_cb->where('macanbo',$cb->macanbo)->first(); không dùng được do khi lọc nguồn bỏ mất cán bộ này
            if(count($canbo) == 0){
                continue;
            }

            $cb->mabl = $inputs['mabl'];


            //tính thâm niên
            $pctn = $model_phucap->where('mapc', 'pcthni')->first();
            $pl = getDbl($pctn->phanloai);
            switch ($pl) {
                case 0:
                case 1: {//số tiền
                    //giữ nguyên ko cần tính
                    break;
                }
                case 2: {//phần trăm
                    $heso = 0;
                    foreach (explode(',', $pctn->congthuc) as $ct) {
                        if ($ct != '' && $ct != 'pcthni')
                            $heso += $canbo->$ct;
                    }
                    //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                    $heso += $canbo->hesopc;
                    $cb->pcthni = $heso * $cb->pcthni / 100;
                    break;
                }
                default: {//trường hợp còn lại (ẩn,...)
                    $cb->pcthni = 0;
                    break;
                }
            }

            //
            $canbo->pcthni = $cb->pcthni; //set vao hồ sơ cán bộ để tính công thức lương
            $canbo->pctn = $cb->pctn;
            $ths = 0;
            $tt = 0;
            //lương cơ bản gán theo loại phụ cấp => tính tiền luôn
            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                if($cb->$mapc <= 0){
                    continue;
                }

                $pl = getDbl($ct->phanloai);

                switch ($pl) {
                    case 0: {//hệ số
                        $ths += $cb->$mapc;
                        $tt += round($cb->$mapc * $ct->luongcoban, 0);
                        break;
                    }
                    case 1: {//số tiền
                        $tt += chkDbl($cb->$mapc);
                        break;
                    }
                    case 2: {//phần trăm
                        if($mapc != 'pcthni'){
                            $heso = 0;
                            if ($pl == 2) {
                                foreach (explode(',', $ct->congthuc) as $cthuc) {
                                    if ($cthuc != '')
                                        $heso += $canbo->$cthuc;
                                }
                            }
                            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                            $heso += $canbo->hesopc;
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                            $ths += $cb->$mapc;
                            $tt += round($cb->$mapc * $ct->luongcoban, 0);
                        }
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $cb->$mapc = 0;
                        break;
                    }
                }
            }

            $cb->tonghs = $ths;
            $cb->ttl = $tt;
            if ($cb->baohiem) {
                $phanloai = $model_phanloai->where('mact', $cb->mact)->first();
                if (count($phanloai) > 0) {//do trc nhập chưa lưu mact
                    $cb->stbhxh = round($inputs['luongcoban'] * floatval($phanloai->bhxh) / 100, 0);
                    $cb->stbhyt = round($inputs['luongcoban'] * floatval($phanloai->bhyt) / 100, 0);
                    $cb->stkpcd = round($inputs['luongcoban'] * floatval($phanloai->kpcd) / 100, 0);
                    $cb->stbhtn = round($inputs['luongcoban'] * floatval($phanloai->bhtn) / 100, 0);
                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->stbhxh_dv = round($inputs['luongcoban'] * floatval($phanloai->bhxh_dv) / 100, 0);
                    $cb->stbhyt_dv = round($inputs['luongcoban'] * floatval($phanloai->bhyt_dv) / 100, 0);
                    $cb->stkpcd_dv = round($inputs['luongcoban'] * floatval($phanloai->kpcd_dv), 0);
                    $cb->stbhtn_dv = round($inputs['luongcoban'] * floatval($phanloai->bhtn_dv), 0);
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                }
            }
            $cb->luongtn = $cb->ttl - $cb->ttbh;
            $a_k = $cb->toarray();
            unset($a_k['id']);
            bangluong_ct::create($a_k);
        }
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

        $m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th,array('phanloai')))->where('madv',$inputs['madv'])->wherein('manguonkp',[$inputs['manguonkp'],'',null])->get()->toArray();;

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
                        $a_pc[$k]['sotien'] = round($m_cb[$key][$mapc] * $inputs['luongcoban']);
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
                        $a_pc[$k]['sotien'] = round($m_cb[$key][$mapc] * $inputs['luongcoban']);
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
                    $m_cb[$key]['st_'.$mapc] = $a_pc[$k]['sotien'];
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
        $a_data_phucap = unset_key($a_data_phucap,$a_col_pc);
        //$a_chunk = array_chunk($a_data_phucap, 100);
        foreach(array_chunk($a_data_phucap, 100)  as $data){
            //bangluong_phucap::insert($data);
        }
        $a_col_cb = array('id','bac','baohiem','macongtac','manguonkp','pthuong','theodoi');
        $a_data_canbo = unset_key($a_data_canbo,$a_col_cb);
        foreach(array_chunk($a_data_canbo, 50)  as $data){
            bangluong_ct::insert($data);
        }
        //dd($a_data_canbo);

        //Tính toán lương cho cán bộ kiêm nhiệm
        $a_kn_canbo = array();
        $a_kn_phucap = array();
        for($i=0; $i<count($m_cb_kn); $i++){
            if(!array_key_exists($m_cb_kn[$i]['macanbo'],$m_cb)){
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

    function store_truylinh_221018(Request $request)
    {
        if (Session::has('admin')) {
            //lương cơ bản và nguồn lấy trong chi tiết truy lĩnh
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_truylinh'];
            $inputs['thang'] = $inputs['thang_truylinh'];
            $inputs['nam'] = $inputs['nam_truylinh'];
            $inputs['noidung'] = $inputs['noidung_truylinh'];
            //$inputs['manguonkp'] = $inputs['manguonkp_truylinh'];
            //$inputs['luongcoban'] = $inputs['luongcoban_truylinh'];
            $inputs['phanloai'] = $inputs['phanloai_truylinh'];
            $inputs['nguoilap'] = $inputs['nguoilap_truylinh'];
            $inputs['ngaylap'] = $inputs['ngaylap_truylinh'];
            $inputs['phantramhuong'] = 100;

            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if (count($model) > 0) {
                //$inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/danh_sach');
            } else {
                //insert
                $madv = session('admin')->madv;
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;

                //$inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $ngaylap = $inputs['nam'] . '-' . $inputs['thang'] . '-01';
                //$ngaylap = Carbon::create($inputs['nam'],$inputs['thang'],'01');

                $model_canbo = hosotruylinh::where('madv', $madv)
                    //->select('stt', 'macanbo', 'tencanbo', 'msngbac', 'hesott', 'ngaytu', 'ngayden', 'maso')
                    ->where('ngayden', '<', $ngaylap)
                    ->wherenull('mabl')
                    ->get();

                $model_hoso = hosocanbo::select('sunghiep', 'mact','macanbo', 'macvcq')->where('madv',session('admin')->madv)->get();
                //$model_congtac = dmphanloaict::all();
                $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
                $model_chucvu = dmchucvucq::where('maphanloai',session('admin')->maphanloai)
                    ->wherein('madv',['SA',session('admin')->madv])->get();

                $a_goc = array('hesott');
                $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', $a_goc)->get();
                //Tạo bảng lương
                bangluong::create($inputs);
                $ngaycong = dmdonvi::where('madv',$madv)->first()->songaycong;
                foreach ($model_canbo as $cb) {
                    $hoso = $model_hoso->where('macanbo', $cb->macanbo)->first();
                    $chucvu = $model_chucvu->where('macvcq', $cb->macvcq)->first();
                    if (count($hoso) == 0) {
                        continue;
                    }
                    //Gán tham số mặc định
                    $cb->mabl = $inputs['mabl'];
                    $cb->vuotkhung = 0;//đơn vị tạo trước update
                    $cb->sunghiep = null;
                    $cb->mact = null;
                    $cb->macvcq = null;
                    $cb->macongtac = null;
                    $cb->bhxh = 0;
                    $cb->bhyt = 0;
                    $cb->kpcd = 0;
                    $cb->bhtn = 0;
                    $cb->bhxh_dv = 0;
                    $cb->bhyt_dv = 0;
                    $cb->kpcd_dv = 0;
                    $cb->bhtn_dv = 0;
                    $cb->sunghiep = $hoso->sunghiep;
                    $cb->mact = $hoso->mact;
                    $cb->macvcq = $hoso->macvcq;

                    $phanloai = $model_phanloai->where('mact', $cb->mact)->first();
                    if (count($phanloai) > 0) {
                        $cb->bhxh = floatval($phanloai->bhxh)/100;
                        $cb->bhyt = floatval($phanloai->bhyt)/100;
                        $cb->kpcd = floatval($phanloai->kpcd)/100;
                        $cb->bhtn = floatval($phanloai->bhtn)/100;
                        $cb->bhxh_dv = floatval($phanloai->bhxh_dv)/100;
                        $cb->bhyt_dv = floatval($phanloai->bhyt_dv)/100;
                        $cb->kpcd_dv = floatval($phanloai->kpcd_dv)/100;
                        $cb->bhtn_dv = floatval($phanloai->bhtn_dv)/100;
                    }
                    //cán bộ lãnh đạo đơn vi + cán bộ công chức ko pai nộp bảo hiểm thất nghiệp
                    if ($cb->sunghiep == 'Công chức' || (count($chucvu) > 0 && $chucvu->ttdv == 1)) {
                        $cb->bhtn = 0;
                        $cb->bhtn_dv = 0;
                    }

                    if (getDateTime($cb->ngayden) == null) {
                        $cb->ngayden = $ngaylap;
                    }
                    $tungay = new Carbon($cb->ngaytu);
                    $denngay = new Carbon($cb->ngayden);

                    /*Phục vụ tính truy lĩnh theo ngày, ngày công mặc định là 24 ngày
                    $ngaycuoi = (new Carbon($cb->ngayden))->lastOfMonth()->day;
                    $ngay_tu = $tungay->day;
                    $thang_tu = $tungay->month;
                    $nam_tu = $tungay->year;
                    $ngay_den = $denngay->day;
                    $thang_den = $denngay->month;
                    $nam_den = $denngay->year;
                    $thang_den += 12 * ($nam_den - $nam_tu);
                    $thang_tl = $thang_den - $thang_tu > 0 ? ($thang_den - $thang_tu) : 1;
                     *

                    $cb->thangtl = $denngay->month - $tungay->month + 12 * ($denngay->year - $tungay->year);
                    $cb->thangtl = $cb->thangtl > 0 ? $cb->thangtl : 1;
                    $cb->ngaytl = 0;
                    */
                    $ths = 0;
                    foreach ($model_phucap as $ct) {
                        $mapc = $ct->mapc;

                        $cb->congtac = 'TRUYLINH';
                        $pl = getDbl($ct->phanloai);

                        switch ($pl) {
                            case 0: {//hệ số
                                $ths += $cb->$mapc;
                                break;
                            }
                            case 2: {//phần trăm
                                $cb->$mapc = ($cb->heso * $cb->$mapc) / 100;
                                $ths += $cb->$mapc;
                                break;
                            }
                            default: {//trường hợp còn lại (ẩn,...)
                                $cb->$mapc = 0;
                                break;
                            }
                        }
                    }
                    $cb->tonghs = $ths;
                    $thangtl = $cb->luongcoban * $ths;
                    $ngaytl =round(($cb->luongcoban * $ths)/$ngaycong,0);
                    if($cb->ngaytl>15) {
                        $baohiem = $cb->luongcoban * ($cb->heso + $cb->pctnn) * $cb->thangtl
                            + round(($cb->luongcoban * ($cb->heso + $cb->pctnn) * $cb->ngaytl) / $ngaycong, 0);
                    }else {
                        $baohiem = $cb->luongcoban * ($cb->heso + $cb->pctnn) * $cb->thangtl;
                    }


                    $cb->ttl = round($thangtl * $cb->thangtl + $cb->ngaytl * $ngaytl,0);

                    $cb->stbhxh = round($baohiem * $cb->bhxh, 0);
                    $cb->stbhyt = round($baohiem * $cb->bhyt, 0);
                    $cb->stkpcd = round($baohiem * $cb->kpcd, 0);
                    $cb->stbhtn = round($baohiem * $cb->bhtn, 0);
                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->luongtn = $cb->ttl - $cb->ttbh;
                    $cb->stbhxh_dv = round($baohiem * $cb->bhxh_dv, 0);
                    $cb->stbhyt_dv = round($baohiem * $cb->bhyt_dv, 0);
                    $cb->stkpcd_dv = round($baohiem * $cb->kpcd_dv, 0);
                    $cb->stbhtn_dv = round($baohiem * $cb->bhtn_dv, 0);
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    $kq = $cb->toarray();
                    unset($kq['id']);
                    //lưu vào db
                    bangluong_ct::create($kq);
                }

                $model_canbo = $model_canbo->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['maso'])
                        ->all();
                });
                hosotruylinh::wherein('maso', $model_canbo->toarray())->update(['mabl' => $inputs['mabl']]);
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'].'&mapb=');
        } else
            return view('errors.notlogin');
    }

    function store_truylinh(Request $request)
    {//chưa tính theo định mức
        if (Session::has('admin')) {
            //lương cơ bản và nguồn lấy trong chi tiết truy lĩnh
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_truylinh'];
            $inputs['thang'] = $inputs['thang_truylinh'];
            $inputs['nam'] = $inputs['nam_truylinh'];
            $inputs['noidung'] = $inputs['noidung_truylinh'];
            //$inputs['manguonkp'] = $inputs['manguonkp_truylinh'];
            //$inputs['luongcoban'] = $inputs['luongcoban_truylinh'];
            $inputs['phanloai'] = $inputs['phanloai_truylinh'];
            $inputs['nguoilap'] = $inputs['nguoilap_truylinh'];
            $inputs['ngaylap'] = $inputs['ngaylap_truylinh'];
            $inputs['phantramhuong'] = 100;

            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if (count($model) > 0) {
                //$inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/danh_sach');
            } else {
                //insert
                $madv = session('admin')->madv;
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;
                $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01')->addMonth(1)->addDay(-1);
                //$ngaylap = $inputs['nam'] . '-' . $inputs['thang'] . '-01';

                $model_canbo = hosotruylinh::where('madv', $madv)
                    //->select('stt', 'macanbo', 'tencanbo', 'msngbac', 'hesott', 'ngaytu', 'ngayden', 'maso')
                    ->where('ngayden', '<=', $ngaylap)->wherenull('mabl')->get();
                //dd($model_canbo);
                $a_hoso = hosocanbo::select('mapb', 'mact', 'macanbo', 'macvcq', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv')
                    ->where('madv', session('admin')->madv)->get()->keyby('macanbo')->toarray();

                $a_goc = array('heso','vuotkhung','pccv');
                $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', array('hesott'))->get();
                //Tạo bảng lương

                $ngaycong = session('admin')->songaycong;
                //$ngaycong = dmdonvi::where('madv', $madv)->first()->songaycong;

                foreach ($model_canbo as $cb) {
                    if (!isset($a_hoso[$cb->macanbo])) {
                        continue;
                    }
                    $hoso = $a_hoso[$cb->macanbo];

                    //Gán tham số mặc định
                    $cb->mabl = $inputs['mabl'];
                    $cb->vuotkhung = 0;//đơn vị tạo trước update
                    $cb->sunghiep = null;
                    $cb->mact = $hoso['mact'];
                    $cb->macvcq = $hoso['macvcq'];
                    $cb->mapb = $hoso['macvcq'];
                    $cb->bhxh = floatval($hoso['bhxh']) / 100;
                    $cb->bhyt = floatval($hoso['bhyt']) / 100;
                    $cb->kpcd = floatval($hoso['kpcd']) / 100;
                    $cb->bhtn = floatval($hoso['bhtn']) / 100;
                    $cb->bhxh_dv = floatval($hoso['bhxh_dv']) / 100;
                    $cb->bhyt_dv = floatval($hoso['bhyt_dv']) / 100;
                    $cb->kpcd_dv = floatval($hoso['kpcd_dv']) / 100;
                    $cb->bhtn_dv = floatval($hoso['bhtn_dv']) / 100;
                    $cb->congtac = 'TRUYLINH';

                    if (getDateTime($cb->ngayden) == null) {
                        $cb->ngayden = $ngaylap;
                    }
                    $cb->vuotkhung = ($cb->heso * $cb->vuotkhung) / 100;
                    $tonghs = $tien = 0;

                    foreach ($model_phucap as $ct) {
                        $mapc = $ct->mapc;
                        $ct->stbhxh = 0;
                        $ct->stbhyt = 0;
                        $ct->stkpcd = 0;
                        $ct->stbhtn = 0;
                        $ct->stbhxh_dv = 0;
                        $ct->stbhyt_dv = 0;
                        $ct->stkpcd_dv = 0;
                        $ct->stbhtn_dv = 0;
                        switch (getDbl($ct->phanloai)) {
                            case 0: {//hệ số
                                $tonghs += $cb->$mapc;
                                break;
                            }
                            case 1: {//hệ số
                                $tien += chkDbl($cb->$mapc);
                                break;
                            }
                            case 2: {//phần trăm
                                $heso = 0;
                                if ($mapc != 'vuotkhung') {//vượt khung đã tính ở trên
                                    foreach (explode(',', $ct->congthuc) as $cthuc) {
                                        if ($cthuc != '')
                                            $heso += $cb->$cthuc;
                                    }
                                }

                                $cb->$mapc = ($heso * $cb->$mapc) / 100;
                                $tonghs += $cb->$mapc;
                                break;
                            }
                            default: {//trường hợp còn lại (ẩn,...)
                                $cb->$mapc = 0;
                                break;
                            }
                        }
                        $ct->sotien = $cb->$mapc * $cb->luongcoban;
                        //tính bảo hiểm
                        if ($ct->baohiem == 1 &&
                            ($cb->maphanloai != 'KHAC' || ($cb->maphanloai == 'KHAC' && !in_array($mapc,$a_goc)))) {
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
                    }

                    //phân loại truy lĩnh khác : hệ số cơ sở set = 0;heso, vuotkhung, pccv
                    if($cb->maphanloai == 'KHAC'){
                        $tonghs = $tonghs - $cb->heso - $cb->vuotkhung - $cb->pccv;
                    }
                    $cb->tonghs = $tonghs;
                    $thangtl = $cb->luongcoban * $tonghs + $tien;
                    $ngaytl = round($thangtl / $ngaycong, 0);
                    //1 tháng
                    $stbhxh = $model_phucap->sum('stbhxh');
                    $stbhyt = $model_phucap->sum('stbhyt');
                    $stkpcd = $model_phucap->sum('stkpcd');
                    $stbhtn = $model_phucap->sum('stbhtn');
                    $stbhxh_dv = $model_phucap->sum('stbhxh_dv');
                    $stbhyt_dv = $model_phucap->sum('stbhyt_dv');
                    $stkpcd_dv = $model_phucap->sum('stkpcd_dv');
                    $stbhtn_dv = $model_phucap->sum('stbhtn_dv');

                    if ($cb->ngaytl >= $ngaycong/2) {
                        $cb->stbhxh = round(($stbhxh * $cb->ngaytl) / $ngaycong + $stbhxh * $cb->thangtl, 0);
                        $cb->stbhyt = round(($stbhyt * $cb->ngaytl) / $ngaycong + $stbhyt * $cb->thangtl, 0);
                        $cb->stkpcd = round(($stkpcd * $cb->ngaytl) / $ngaycong + $stkpcd * $cb->thangtl, 0);
                        $cb->stbhtn = round(($stbhtn * $cb->ngaytl) / $ngaycong + $stbhtn * $cb->thangtl, 0);

                        $cb->stbhxh_dv = round(($stbhxh_dv * $cb->ngaytl) / $ngaycong + $stbhxh_dv * $cb->thangtl, 0);
                        $cb->stbhyt_dv = round(($stbhyt_dv * $cb->ngaytl) / $ngaycong + $stbhyt_dv * $cb->thangtl, 0);
                        $cb->stkpcd_dv = round(($stkpcd_dv * $cb->ngaytl) / $ngaycong + $stkpcd_dv * $cb->thangtl, 0);
                        $cb->stbhtn_dv = round(($stbhtn_dv * $cb->ngaytl) / $ngaycong + $stbhtn_dv * $cb->thangtl, 0);
                    } else{
                        $cb->stbhxh = round($stbhxh * $cb->thangtl, 0);
                        $cb->stbhyt = round($stbhyt * $cb->thangtl, 0);
                        $cb->stkpcd = round($stkpcd * $cb->thangtl, 0);
                        $cb->stbhtn = round($stbhtn * $cb->thangtl, 0);

                        $cb->stbhxh_dv = round($stbhxh_dv * $cb->thangtl, 0);
                        $cb->stbhyt_dv = round($stbhyt_dv * $cb->thangtl, 0);
                        $cb->stkpcd_dv = round($stkpcd_dv * $cb->thangtl, 0);
                        $cb->stbhtn_dv = round($stbhtn_dv * $cb->thangtl, 0);
                    }

                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                    $cb->ttl = round($thangtl * $cb->thangtl + $cb->ngaytl * $ngaytl, 0);
                    $cb->luongtn = $cb->ttl - $cb->ttbh;
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    //ưng mỗi phân loại set về hệ số: chucvu => pccv=heso; tnn: pctnn=heso
                    if($cb->maphanloai == 'CHUCVU'){
                        $cb->pccv = $cb->heso;
                        $cb->heso = 0;
                    }

                    if($cb->maphanloai == 'TNN'){
                        $cb->pctnn = $cb->heso;
                        $cb->heso = 0;
                    }

                    $kq = $cb->toarray();
                    unset($kq['id']);
                    //lưu vào db
                    //$this->createBangLuong($inputs['thang'],$kq);
                    bangluong_ct::create($kq);
                }

                $model_canbo = $model_canbo->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['maso'])
                        ->all();
                });
                bangluong::create($inputs);
                hosotruylinh::wherein('maso', $model_canbo->toarray())->update(['mabl' => $inputs['mabl']]);
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'].'&mapb=');
        } else
            return view('errors.notlogin');
    }

    function store_truc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_truc'];
            $inputs['thang'] = $inputs['thang_truc'];
            $inputs['nam'] = $inputs['nam_truc'];
            $inputs['noidung'] = $inputs['noidung_truc'];
            $inputs['luongcoban'] = $inputs['luongcoban_truc'];
            $inputs['phanloai'] = $inputs['phanloai_truc'];
            $inputs['nguoilap'] = $inputs['nguoilap_truc'];
            $inputs['ngaylap'] = $inputs['ngaylap_truc'];
            $inputs['songay'] = getDbl($inputs['songay_truc']);
            $inputs['phantramhuong'] = 100;

            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if (count($model) > 0) {
                $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/danh_sach');
            } else {
                //insert
                $madv = session('admin')->madv;
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;

                $inputs['luongcoban'] = getDbl($inputs['luongcoban']);

                //$ngaylap = Carbon::create($inputs['nam'],$inputs['thang'],'01');

                $model_canbo = hosotruc::where('madv', $madv)->get();
                bangluong::create($inputs);
                foreach ($model_canbo as $cb) {
                    //Gán tham số mặc định
                    $cb->mabl = $inputs['mabl'];
                    $cb->songay = $inputs['songay'];
                    $cb->ttl =  $inputs['luongcoban'] * $cb->songay * $cb->heso;
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    $kq = $cb->toarray();
                    unset($kq['id']);
                    //lưu vào db
                    bangluong_truc::create($kq);
                }
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'].'&mapb=');
        } else
            return view('errors.notlogin');
    }

    function show(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $m_bl = bangluong::select('thang', 'nam', 'mabl','phanloai')->where('mabl', $inputs['mabl'])->first();
            if($m_bl->phanloai == 'TRUC'){
                $model = bangluong_truc::where('mabl', $inputs['mabl'])->get();
                return view('manage.bangluong.bangluong_truc')
                    ->with('furl', '/chuc_nang/bang_luong/')
                    ->with('model', $model)
                    ->with('m_bl', $m_bl)
                    ->with('pageTitle', 'Bảng lương chi tiết');
            }else{
                //$model = $this->getBL($m_bl->thang, $m_bl->mabl);
                $model = bangluong_ct::where('mabl',$inputs['mabl'])->get();
            }
            if($inputs['mapb'] != ''){
                $model = $model->where('mapb',$inputs['mapb']);
            }
            //getPhongBan()

            /*
             * $dmchucvucq = dmchucvucq::all('tencv', 'macvcq')->toArray();
            $model_cb = hosocanbo::where('madv', session('admin')->madv)->get();
            foreach ($model as $hs) {
                $cb = $model_cb->where('macanbo', $hs->macanbo)->first();
                $hs->tencanbo = count($cb) > 0 ? $cb->tencanbo : '';
                $hs->tencv = getInfoChucVuCQ($hs, $dmchucvucq);
            }
            */
            //dd(getChucVuCQ(false));
            return view('manage.bangluong.bangluong')
                ->with('furl', '/chuc_nang/bang_luong/')
                ->with('model', $model)
                ->with('m_bl', $m_bl)
                ->with('a_cv', getChucVuCQ(false))
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = bangluong::find($id);
            if(count($model) > 0 && $model->phanloai == 'TRUYLINH') {
                hosotruylinh::where('mabl', $model->mabl)
                    ->update(['mabl' => null]);
            }
            bangluong_ct::where('mabl', $model->mabl)->delete();
            //bangluong_phucap::where('mabl', $model->mabl)->delete();
            //bangluong_truc::where('mabl', $model->mabl)->delete();
            $model->delete();
            return redirect('/chuc_nang/bang_luong/chi_tra?thang='.$model->thang.'&nam='. $model->nam);
        } else
            return view('errors.notlogin');
    }

    function destroy_ct($id){
        if (Session::has('admin')) {
            $model = bangluong_ct::find($id);
            $model->delete();
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb);
            //return redirect('/chuc_nang/bang_luong/maso='.$model->mabl);
        } else
            return view('errors.notlogin');
    }

    function destroy_truc($id){
        if (Session::has('admin')) {
            $model = bangluong_truc::find($id);
            $model->delete();
            return redirect('/chuc_nang/bang_luong/maso='.$model->mabl);
        } else
            return view('errors.notlogin');
    }

    function getinfor_nguonkp(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $inputs['luongcb']= getGeneralConfigs()['luongcb'];
        $inputs['dinhmuc']= 0;
        $dinhmuc = nguonkinhphi_dinhmuc::where('manguonkp',$inputs['manguonkp'])->where('madv',session('admin')->madv)->first();
        $maso = count($dinhmuc)> 0 ? $dinhmuc->maso : '';
        $dinhmuc_ct = nguonkinhphi_dinhmuc_ct::where('maso',$maso)->get();
        if(count($dinhmuc_ct)>0){
            $inputs['dinhmuc']= 1;
            $inputs['luongcb']= $dinhmuc->luongcoban;
        }

        die(json_encode($inputs));
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
        $model = bangluong::where('mabl',$inputs['mabl'])->first();
        die($model);
    }

    function get_chitiet(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $inputs['st'] = 'st_'.$inputs['mapc'];
        $model_luong = bangluong_ct::find($inputs['id']);
        $model = dmphucap::where('mapc',$inputs['mapc'])->first();
        $model->heso = $model_luong->$inputs['mapc'];
        $model->sotien = $model_luong->$inputs['st'];
        $model->luongcoban = $model_luong->$inputs['mapc'];
        die($model);
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong_ct::findorfail($inputs['maso']);
            $model_bangluong = bangluong::where('mabl',$model->mabl)->first();

            $m_nb = ngachluong::where('msngbac',$model->msngbac)->first();
            //$model_bl = bangluong::where('mabl',$model->mabl)->first();
            $model->tennb = isset($m_nb)? $m_nb->tenngachluong:'';
            $model->tencanbo = Str::upper($model->tencanbo);
            /*
            $model_canbo = hosocanbo::where('macanbo',$model->macanbo)->first();
            $model->bhxh = $model_canbo->bhxh;
            $model->bhyt = $model_canbo->bhyt;
            $model->bhtn = $model_canbo->bhtn;
            $model->kpcd = $model_canbo->kpcd;
            $model->bhxh_dv = $model_canbo->bhxh_dv;
            $model->bhyt_dv = $model_canbo->bhyt_dv;
            $model->bhtn_dv = $model_canbo->bhtn_dv;
            $model->kpcd_dv = $model_canbo->kpcd_dv;
            */
            //$a_donvi = dmdonvi::where('madv',session('admin')->madv)->first()->toarray();

            //$a_goc = array('heso','vuotkhung','hesott','hesopc');
            $model_pc = dmphucap_donvi::where('madv', $model_bangluong->madv)->where('phanloai', '<', '3')
                ->get();
            //dd($model_pc);

            if($model_bangluong->phanloai == 'TRUYLINH'){
                $model_truylinh = hosotruylinh::where('macanbo',$model->macanbo)->where('mabl',$model->mabl)->first();
                $model->ngaytu = $model_truylinh->ngaytu;
                $model->ngayden = $model_truylinh->ngayden;

                foreach($model_pc as $pc){
                    $mapc = $pc->mapc;
                    $mapc_st ='st_'. $mapc;
                    $pc->heso = $model->$mapc;
                    $pc->sotien = $model->$mapc_st;
                    $pc->macanbo = $model->macanbo;
                    $pc->mabl = $model->mabl;
                }

                return view('manage.bangluong.chitiet_truylinh')
                    ->with('furl','/chuc_nang/bang_luong/')
                    ->with('model',$model)
                    ->with('model_pc',$model_pc->sortby('stt'))
                    ->with('pageTitle','Chi tiết bảng lương');
            }else{
                $model->luongcoban = $model_bangluong->luongcoban;
                //dd($model);
                foreach($model_pc as $pc){
                    $mapc = $pc->mapc;
                    $mapc_st ='st_'. $mapc;
                    $pc->heso = $model->$mapc;
                    $pc->sotien = $model->$mapc_st;
                    $pc->macanbo = $model->macanbo;
                    $pc->mabl = $model->mabl;
                }

                return view('manage.bangluong.chitiet')
                    ->with('furl','/chuc_nang/bang_luong/')
                    ->with('model',$model)
                    ->with('model_pc',$model_pc->sortby('stt'))
                    ->with('pageTitle','Chi tiết bảng lương');
            }

        } else
            return view('errors.notlogin');
    }

    function updatect(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model=bangluong_ct::findorfail($inputs['id']);
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            foreach($model_pc as $pc){
                if(isset($inputs[$pc->mapc])){
                    $inputs[$pc->mapc] = chkDbl($inputs[$pc->mapc]);
                }
            }

            $inputs['ttl'] = chkDbl($inputs['ttl']);
            $inputs['giaml'] = chkDbl($inputs['giaml']);
            $inputs['thuetn'] = chkDbl($inputs['thuetn']);
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
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb);


        } else
            return view('errors.notlogin');
    }

    function updatect_truylinh(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            /*
            //Lưu bảng truy linh
            $model_truylinh = hosotruylinh::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
            if(count($model_truylinh) > 0){
                $model_truylinh->ngaytu = $inputs['ngaytu'];
                $model_truylinh->ngayden = $inputs['ngayden'];
                $model_truylinh->hesott = $inputs['hesott'];
                $model_truylinh->save();
            }
            //lưu bảng lương chi tiết
            */
            $model=bangluong_ct::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            foreach($model_pc as $pc){
                if(isset($inputs[$pc->mapc])){
                    $inputs[$pc->mapc] = chkDbl($inputs[$pc->mapc]);
                }
            }
            //$inputs['hesott'] = chkDbl($inputs['hesott']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttl'] = chkDbl($inputs['ttl']);
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
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb);


        } else
            return view('errors.notlogin');
    }

    function update_chitiet(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();

            $model = bangluong_ct::findorfail($inputs['id_hs']);
            $mapc = $inputs['mapc'];
            $mapc_st ='st_'. $inputs['mapc'];

            $inputs['heso'] = chkDbl($inputs['heso']);
            $inputs['luongcb'] = chkDbl($inputs['luongcb']);
            $inputs['sotien'] = chkDbl($inputs['sotien']);
            //dd($inputs);
            //Tính lương mới
            $sotien_cl = $inputs['sotien'] - $model->$mapc_st;
            $heso_cl = $inputs['heso'] - $model->$mapc;
            $model->$mapc_st = $inputs['sotien'];
            $model->$mapc = $inputs['heso'];
            //Tính lại bao hiểm
            if($model->congtac != 'THAISAN' && $model->congtac != 'DAINGAY' && $model->congtac != 'KHONGLUONG' ){
                $baohiem = $model->st_heso + $model->st_vuotkhung + $model->st_pccv + $model->st_pctnn;
                $model->stbhxh = round($model->bhxh * $baohiem, 0);
                $model->stbhyt = round($model->bhyt * $baohiem, 0);
                $model->stkpcd = round($model->kpcd * $baohiem, 0);
                $model->stbhtn = round($model->bhtn * $baohiem, 0);
                $model->ttbh = $model->stbhxh + $model->stbhyt + $model->stkpcd + $model->stbhtn;
                $model->stbhxh_dv = round($model->bhxh_dv * $baohiem, 0);
                $model->stbhyt_dv = round($model->bhyt_dv * $baohiem, 0);
                $model->stkpcd_dv = round($model->kpcd_dv * $baohiem, 0);
                $model->stbhtn_dv = round($model->bhtn_dv * $baohiem, 0);
                $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;
            }

            $model->tonghs += $heso_cl;
            $model->ttl += $sotien_cl;
            $model->luongtn = $model->ttl -  $model->ttbh - $model->giaml - $model->thuetn + $model->bhct;
            //dd($model);
            $model->save();
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb);


        } else
            return view('errors.notlogin');
    }

    public function inbangluong($mabl){
        if (Session::has('admin')) {
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $m_bl = bangluong::select('thang','nam','mabl','madv','nguoilap','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $mabl = $m_bl->mabl;
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            //dd($model_congtac);
            //$model_congtac = dmphanloaict::select('mact','tenct')->get();
            $dmchucvucq=array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(),'tenvt', 'macvcq');
            //dd($dmchucvucq);
            //dd($model);
            foreach($model as $hs){
                $hs->tencv = isset($dmchucvucq[$hs->macvcq])? $dmchucvucq[$hs->macvcq] : '';
            }

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,'phanloai'=>$m_bl->phanloai,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'cochu'=>11);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucapbc = getColPhuCap_BaoCao();
            $a_phucap = array();
            $col = 0;
            foreach($a_phucapbc as $key=>$val){
                if($m_dv->$key <3) {
                    $a_phucap[$key] = $val;
                    $col++;
                }
            }

            return view('reports.bangluong.donvi.maubangluong')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function inbangluong_sotien($mabl){
        if (Session::has('admin')) {
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $m_bl = bangluong::select('thang','nam','mabl','madv','luongcoban','phanloai')->where('mabl',$mabl)->first();
            $mabl = $m_bl->mabl;
            $luongcb = $m_bl->luongcoban;
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            //dd($model_congtac);
            //$model_congtac = dmphanloaict::select('mact','tenct')->get();
            $dmchucvucq=array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(),'tenvt', 'macvcq');
            $a_col = getColTongHop();
            foreach($model as $hs){
                $hs->tencv = isset($dmchucvucq[$hs->macvcq])? $dmchucvucq[$hs->macvcq] : '';
                $ths = 0;
                foreach ($a_col as $col){
                    if(chkDbl($hs->$col)< 500){
                        $hs->$col = $hs->$col * $luongcb;
                    }
                    $ths += $hs->$col;
                    $hs->tonghs = $ths;
                }
            }
            //dd($m_bl);
            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,'phanloai'=>$m_bl->phanloai,
                'nam'=>$m_bl->nam);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucapbc = getColPhuCap_BaoCao();
            $a_phucap = array();
            $col = 0;
            foreach($a_phucapbc as $key=>$val){
                if($m_dv->$key <3) {
                    $a_phucap[$key] = $val;
                    $col++;
                }
            }

            return view('reports.bangluong.donvi.maubangluong_sotien')
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('col',$col)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function inbangluongmau3($mabl){
        if (Session::has('admin')) {
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $m_bl = bangluong::select('thang','nam','mabl','madv')->where('mabl',$mabl)->first();
            $mabl = $m_bl->mabl;
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            //dd($model_congtac);
            //$model_congtac = dmphanloaict::select('mact','tenct')->get();
            $dmchucvucq=array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(),'tenvt', 'macvcq');
            //dd($dmchucvucq);
            $model_cb =hosocanbo::where('madv',$m_bl->madv)->get();
            foreach($model as $hs){
                $cb =$model_cb->where('macanbo',$hs->macanbo)->first();
                $hs->tencv = isset($dmchucvucq[$hs->macvcq])? $dmchucvucq[$hs->macvcq] : '';
                if(count($cb)>0 &&  $hs->heso>0){
                    $hs->vk = $cb->vuotkhung;
                    $hs->tnn = $cb->pctnn;
                }else{
                    $hs->vk = 0;
                    $hs->tnn = 0;
                }
            }

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam);
            //dd($model);
            return view('reports.bangluong.donvi.maulangson')
                ->with('model',$model->sortby('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    //Mẫu group theo khối/tổ công tác
    public function inbangluongmau4($mabl){
        if (Session::has('admin')) {
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $m_bl = bangluong::select('thang','nam','mabl','madv')->where('mabl',$mabl)->first();
            $mabl = $m_bl->mabl;
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            //dd($model_congtac);
            //$model_congtac = dmphanloaict::select('mact','tenct')->get();
            $dmchucvucq=array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(),'tenvt', 'macvcq');
            //dd($dmchucvucq);
            foreach($model as $hs){
                $hs->tencv = isset($dmchucvucq[$hs->macvcq])? $dmchucvucq[$hs->macvcq] : '';
            }
            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }

            return view('reports.bangluong.donvi.maubangluong_phongban')
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

    function inbaohiem($mabl){
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->maxa)->first();

            $model=bangluong_ct::where('mabl',$mabl)->get();

            $m_bl=bangluong::select('thang','nam')->where('mabl',$mabl)->first();
            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            foreach($model as $hs){
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'cochu'=>12);
            return view('reports.bangluong.maubaohiem')
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('pageTitle','Bảng trích nộp bảo hiểm chi tiết');
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
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
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
            return view('reports.bangluong.donvi.maubangluong')
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

    public function printf_mau01_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau1'];
            $inputs['mapb'] = $inputs['mapb_mau1'];
            $inputs['macvcq'] = $inputs['macvcq_mau1'];
            $inputs['mact'] = $inputs['mact_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
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
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }

            Excel::create('BANGLUONG_01',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.maubangluong_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
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
            return view('reports.bangluong.donvi.mautt107')
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

    public function printf_mautt107_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
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

            Excel::create('BANGLUONG_107',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.mautt107_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_pb(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107_pb'];
            /*
            //lưu mã pb lại vì chưa lọc theo phòng ban
            $mapb = $inputs['mapb'];
            */
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong_moi($inputs);

            $model_pb = dmphongban::select('mapb', 'tenpb')
                ->wherein('mapb', function ($query) use ($mabl) {
                    $query->select('mapb')->from('bangluong_ct')->where('mabl', $mabl);
                })->get();

            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu']
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            return view('reports.bangluong.donvi.mautt107_pb')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_pb', $model_pb)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau02_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
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

            Excel::create('BANGLUONG_02',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.mautt107')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mau03(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau3'];
            $inputs['mapb'] = $inputs['mapb_mau3'];
            $inputs['macvcq'] = $inputs['macvcq_mau3'];
            $inputs['mact'] = $inputs['mact_mau3'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            return view('reports.bangluong.donvi.maulangson')
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

    public function printf_mau03_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau3'];
            $inputs['mapb'] = $inputs['mapb_mau3'];
            $inputs['macvcq'] = $inputs['macvcq_mau3'];
            $inputs['mact'] = $inputs['mact_mau3'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }

            Excel::create('BANGLUONG_03',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.maulangson_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mau04(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau4'];
            $inputs['mapb'] = $inputs['mapb_mau4'];
            $inputs['macvcq'] = $inputs['macvcq_mau4'];
            $inputs['mact'] = $inputs['mact_mau4'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }
            return view('reports.bangluong.donvi.maubangluong_phongban')
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

    public function printf_mau04_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau4'];
            $inputs['mapb'] = $inputs['mapb_mau4'];
            $inputs['macvcq'] = $inputs['macvcq_mau4'];
            $inputs['mact'] = $inputs['mact_mau4'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }
            Excel::create('BANGLUONG_04',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.maubangluong_phongban_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mau05(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau5'];
            $inputs['mapb'] = $inputs['mapb_mau5'];
            $inputs['macvcq'] = $inputs['macvcq_mau5'];
            $inputs['mact'] = $inputs['mact_mau5'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                if(count($hoso)>0 &&  $ct->heso>0){
                    $ct->vk = $hoso->vuotkhung;
                    $ct->tnn = $hoso->pctnn;
                }else{
                    $ct->vk = 0;
                    $ct->tnn = 0;
                }
                if ($ct->phanloai == 'KHONGCT') {
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }
            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }

            return view('reports.bangluong.donvi.mau05')
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

    public function printf_mau05_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau5'];
            $inputs['mapb'] = $inputs['mapb_mau5'];
            $inputs['macvcq'] = $inputs['macvcq_mau5'];
            $inputs['mact'] = $inputs['mact_mau5'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
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
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            Excel::create('BANGLUONG_05',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.mau05_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    //$sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                    //$sheet->setAllBorders('thin');

                    $sheet->setWidth('C', 10);
                    $sheet->setWidth('D', 30);
                    $sheet->setWidth('E', 15);

                    /*
                    $sheet->mergeCells('Q20:R20');
                    $sheet->setMergeColumn(array(
                        'columns' => ['Q'],
                        'rows' => [
                            [20, 21]
                        ]
                    ));
                    */

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mau06(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau6'];
            $inputs['mapb'] = $inputs['mapb_mau6'];
            $inputs['macvcq'] = $inputs['macvcq_mau6'];
            $inputs['mact'] = $inputs['mact_mau6'];
            //$model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);

            $mabl = $inputs['mabl'];
            //$model_phucap = bangluong_phucap::where('mabl', $mabl)->get();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong_moi($inputs);
            //dd($model);

            $model_tm = dmtieumuc_default::all();
            $model_congtac = dmphanloaict::join('bangluong_ct', 'bangluong_ct.mact', '=', 'dmphanloaict.mact')
                ->select('dmphanloaict.mact', 'dmphanloaict.tenct')
                ->where('bangluong_ct.mabl', $mabl)
                ->distinct()->get();

            $a_pb = getPhongBan();
            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'mapb' => $inputs['mapb'],
                'tenpb' => $a_pb[$inputs['mapb']],
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_goc = array('heso','vuotkhung','hesott');
            $a_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->orderby('stt')
                ->get()->keyby('mapc')->toarray();

            $a_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');

            $a_phucap = array();
            $col = 0;

            foreach ($a_pc as $key => $val) {
                if ($model->sum($val['mapc']) > 0) {
                    $a_phucap[$val['mapc']] = $val['report'];
                    $col++;
                }
            }
            $a_bh = a_getelement_equal($a_pc, array('baohiem' => 1));
            $a_pc_tm = array();

            foreach ($model as $ct) {
                //chưa tính trường hợp nghỉ ts
                foreach ($a_bh as $k => $v) {
                    $mapc = $v['mapc'];
                    $mapc_st = 'st_' . $mapc;
                    $a_bh[$k]['macanbo'] = $ct->macanbo;
                    $a_bh[$k]['stbhxh'] = 0;
                    $a_bh[$k]['stbhyt'] = 0;
                    $a_bh[$k]['stkpcd'] = 0;
                    $a_bh[$k]['stbhtn'] = 0;
                    $a_bh[$k]['ttbh'] = 0;
                    $a_bh[$k]['stbhxh_dv'] = 0;
                    $a_bh[$k]['stbhyt_dv'] = 0;
                    $a_bh[$k]['stkpcd_dv'] = 0;
                    $a_bh[$k]['stbhtn_dv'] = 0;
                    $a_bh[$k]['ttbh_dv'] = 0;
                    if ($mapc == 'vuotkhung' || ($ct->congtac == 'THAISAN' && !in_array($mapc,$a_ts))) {
                        continue;
                    }

                    if ($mapc == 'heso') {
                        $a_bh[$k]['heso'] = $ct->heso + $ct->vuotkhung;
                        $a_bh[$k]['sotien'] = $ct->st_heso + $ct->st_vuotkhung;
                    } else {
                        $a_bh[$k]['heso'] = $ct->$mapc;
                        $a_bh[$k]['sotien'] = $ct->$mapc_st;
                    }

                    $a_bh[$k]['stbhxh'] = round($a_bh[$k]['sotien'] * $ct->bhxh, 0);
                    $a_bh[$k]['stbhyt'] = round($a_bh[$k]['sotien'] * $ct->bhyt, 0);
                    $a_bh[$k]['stkpcd'] = round($a_bh[$k]['sotien'] * $ct->kpcd, 0);
                    $a_bh[$k]['stbhtn'] = round($a_bh[$k]['sotien'] * $ct->bhtn, 0);
                    $a_bh[$k]['ttbh'] = $a_bh[$k]['stbhxh'] + $a_bh[$k]['stbhyt'] + $a_bh[$k]['stkpcd'] + $a_bh[$k]['stbhtn'];
                    $a_bh[$k]['stbhxh_dv'] = round($a_bh[$k]['sotien'] * $ct->bhxh_dv, 0);
                    $a_bh[$k]['stbhyt_dv'] = round($a_bh[$k]['sotien'] * $ct->bhyt_dv, 0);
                    $a_bh[$k]['stkpcd_dv'] = round($a_bh[$k]['sotien'] * $ct->kpcd_dv, 0);
                    $a_bh[$k]['stbhtn_dv'] = round($a_bh[$k]['sotien'] * $ct->bhtn_dv, 0);
                    $a_bh[$k]['ttbh_dv'] = $a_bh[$k]['stbhxh_dv'] + $a_bh[$k]['stbhyt_dv'] + $a_bh[$k]['stkpcd_dv'] + $a_bh[$k]['stbhtn_dv'];
                    $a_pc_tm[] = $a_bh[$k];
                }
            }
            //dd($a_pc_tm);
            //dd($model_tm);
            foreach ($model_tm as $tm) {
                $m_tinhtoan = $model;
                //check sự nghiệp
                if ($tm->sunghiep != 'ALL' && $tm->sunghiep != 'null') {
                    $m_tinhtoan = $m_tinhtoan->where('sunghiep', $tm->sunghiep);
                }

                //check mã công tác
                if ($tm->macongtac != 'ALL' && $tm->macongtac != 'null') {
                    $m_tinhtoan = $m_tinhtoan->where('macongtac', $tm->macongtac);
                }
                $tm->heso = 0;
                $tm->sotien = 0;
                $tm->stbhxh = 0;
                $tm->stbhyt = 0;
                $tm->stkpcd = 0;
                $tm->stbhtn = 0;
                $tm->ttbh = 0;
                $tm->stbhxh_dv = 0;
                $tm->stbhyt_dv = 0;
                $tm->stkpcd_dv = 0;
                $tm->stbhtn_dv = 0;
                $tm->ttbh_dv = 0;

                $a_canbo = (array_column($m_tinhtoan->toarray(), 'macanbo'));
                foreach ($a_canbo as $cb) {
                    $heso = a_getelement_equal($a_pc_tm, array('macanbo' => $cb, 'mapc' => $tm->mapc), true);
                    if (count($heso) > 0) {
                        $tm->heso += $heso['heso'];
                        $tm->sotien += $heso['sotien'];
                        $tm->stbhxh += $heso['stbhxh'];
                        $tm->stbhyt += $heso['stbhyt'];
                        $tm->stkpcd += $heso['stkpcd'];
                        $tm->stbhtn += $heso['stbhtn'];
                        $tm->ttbh += $heso['ttbh'];
                        $tm->stbhxh_dv += $heso['stbhxh_dv'];
                        $tm->stbhyt_dv += $heso['stbhyt_dv'];
                        $tm->stkpcd_dv += $heso['stkpcd_dv'];
                        $tm->stbhtn_dv += $heso['stbhtn_dv'];
                        $tm->ttbh_dv += $heso['ttbh_dv'];
                    }
                }
                //check loại phụ cấp
            }
            $model_tm = $model_tm->where('sotien', '>', 0);

            $a_muc = $model_tm->map(function ($data) {
                return collect($data->toArray())
                    ->only(['muc'])
                    ->all();
            });
            $a_muc = a_unique($a_muc);
            //dd($a_muc);
            return view('reports.bangluong.donvi.mau06')
                ->with('model', $model->sortBy('stt'))
                ->with('model_tm', $model_tm)
                ->with('a_muc', $a_muc)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau06_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau6'];
            $inputs['mapb'] = $inputs['mapb_mau6'];
            $inputs['macvcq'] = $inputs['macvcq_mau6'];
            $inputs['mact'] = $inputs['mact_mau6'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $model_phucap = bangluong_phucap::where('mabl', $mabl)->get();
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            $model_tm = dmtieumuc_default::all();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }
            foreach($model_pc as $ct){
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }

            foreach($model_tm as $tm) {
                $m_tinhtoan = $model;
                //check sự nghiệp
                if ($tm->sunghiep != 'ALL' && $tm->sunghiep != 'null') {
                    $m_tinhtoan = $m_tinhtoan->where('sunghiep', $tm->sunghiep);
                }

                //check mã công tác
                if ($tm->macongtac != 'ALL' && $tm->macongtac != 'null') {
                    $m_tinhtoan = $m_tinhtoan->where('macongtac', $tm->macongtac);
                }
                $a_canbo = (array_column($m_tinhtoan->toarray(), 'macanbo'));
                //check loại phụ cấp
                if($tm->mapc == 'heso'){
                    $phucap = $model_phucap->wherein('macanbo', $a_canbo)->wherein('maso', ['heso','vuotkhung']);
                }else{
                    $phucap = $model_phucap->wherein('macanbo', $a_canbo)->wherein('maso', $tm->mapc);
                }

                $tm->heso = $phucap->sum('heso');
                $tm->sotien = $phucap->sum('sotien');
                $tm->stbhxh = $phucap->sum('stbhxh');
                $tm->stbhyt = $phucap->sum('stbhyt');
                $tm->stkpcd = $phucap->sum('stkpcd');
                $tm->stbhtn = $phucap->sum('stbhtn');
                $tm->ttbh = $phucap->sum('ttbh');
                $tm->stbhxh_dv = $phucap->sum('stbhxh_dv');
                $tm->stbhyt_dv = $phucap->sum('stbhyt_dv');
                $tm->stkpcd_dv = $phucap->sum('stkpcd_dv');
                $tm->stbhtn_dv = $phucap->sum('stbhtn_dv');
                $tm->ttbh_dv = $phucap->sum('ttbh_dv');
            }

            Excel::create('BANGLUONG_06',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap, $model_tm){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap, $model_tm){
                    $sheet->loadView('reports.bangluong.donvi.mau06_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_tm',$model_tm->where('sotien','>',0))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mau07(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau7'];
            $inputs['mapb'] = $inputs['mapb_mau7'];
            $inputs['macvcq'] = $inputs['macvcq_mau7'];
            $inputs['mact'] = $inputs['mact_mau7'];
            //$inputs['cochu'] = $inputs['cochu_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //$data_phucap = bangluong_phucap::where('mabl', $inputs['mabl'])->get()->toarray();
            //$model_st = $this->getBangLuong($inputs,1)->wherein('phanloai', ['CVCHINH','KHONGCT']);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap','luongcoban')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', function ($query) use ($mabl) {
                    $query->select('mact')->from('bangluong_ct')->where('mabl', $mabl);
                })->get();
            //$model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();

            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'luongcb' => $m_bl->luongcoban);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = getColPhuCap_BaoCao();
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $a_phucap_st = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $a_phucap_st['st_'.$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            //dd($model);
            return view('reports.bangluong.donvi.mau07')
                ->with('model', $model->sortBy('stt'))
                //->with('model_st', $model_st)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('a_phucap_st', $a_phucap_st)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau07_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau7'];
            $inputs['mapb'] = $inputs['mapb_mau7'];
            $inputs['macvcq'] = $inputs['macvcq_mau7'];
            $inputs['mact'] = $inputs['mact_mau7'];
            //$inputs['cochu'] = $inputs['cochu_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', function ($query) use ($mabl) {
                    $query->select('mact')->from('bangluong_ct')->where('mabl', $mabl);
                })->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }
            }

            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'luongcb' => $m_bl->luongcoban);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = getColPhuCap_BaoCao();
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {

                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            Excel::create('BANGLUONG_07',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.mau07_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

            //dd($thongtin);
        } else
            return view('errors.notlogin');
    }

    public function printf_mau08(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau8'];
            $inputs['mapb'] = $inputs['mapb_mau8'];
            $inputs['macvcq'] = $inputs['macvcq_mau8'];
            $inputs['mact'] = $inputs['mact_mau8'];
            //$inputs['cochu'] = $inputs['cochu_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            $model_st = $this->getBangLuong($inputs,1)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap','luongcoban')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', function ($query) use ($mabl) {
                    $query->select('mact')->from('bangluong_ct')->where('mabl', $mabl);
                })->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $model_ts =array_column( dmphucap_thaisan::select('mapc')->where('madv', $m_bl->madv)->get()->toarray(),'mapc');
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = count($hoso) > 0 ? $hoso->tencanbo : null;
                }

                if ($ct->congtac == 'THAISAN') {
                    foreach ($model_pc as $pc) {
                        if (!in_array($pc->mapc,$model_ts)) {
                            $mapc = $pc->mapc;
                            $ct->tonghs = $ct->tonghs - $ct->$mapc;
                            $ct->$mapc = 0;
                        }
                    }

                }
            }

            foreach ($model_st as $ct) {
                if ($ct->congtac == 'THAISAN') {
                    foreach ($model_pc as $pc) {
                        if (!in_array($pc->mapc,$model_ts)) {
                            $mapc = $pc->mapc;
                            $ct->tonghs = $ct->tonghs - $ct->$mapc;
                            $ct->$mapc = 0;
                        }
                    }

                }
            }

            //dd($model->where('congtac','<>','CONGTAC'));
            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'luongcb' => $m_bl->luongcoban);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = get            ColPhuCap_BaoCao();

            //dd($model_st);
            return view('reports.bangluong.donvi.mau08')
                ->with('model', $model->sortBy('stt'))
                ->with('model_st', $model_st)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauds(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mauds'];
            $inputs['mapb'] = $inputs['mapb_mauds'];
            $inputs['macvcq'] = $inputs['macvcq_mauds'];
            $inputs['mact'] = $inputs['mact_mauds'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            foreach($model as $ct) {
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
            }
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);

            return view('reports.bangluong.donvi.maudschitra')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauds_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mauds'];
            $inputs['mapb'] = $inputs['mapb_mauds'];
            $inputs['macvcq'] = $inputs['macvcq_mauds'];
            $inputs['mact'] = $inputs['mact_mauds'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            foreach($model as $ct) {
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
            }
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);

            Excel::create('DSCHITRA',function($excel) use($m_dv,$thongtin,$model,$model_congtac){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$model_congtac){
                    $sheet->loadView('reports.bangluong.donvi.maudschitra')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('model_congtac',$model_congtac)
                        ->with('pageTitle','DS CHI TRA');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');

    }

    function printf_maubh(Request $request){
        if (Session::has('admin')) {

            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maubh'];
            $inputs['mapb'] = $inputs['mapb_maubh'];
            $inputs['macvcq'] = $inputs['macvcq_maubh'];
            $inputs['mact'] = $inputs['mact_maubh'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $model = $this->getBangLuong($inputs);
            //$model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();


            //dd($request->all());

            $m_dv=dmdonvi::where('madv',$m_bl->madv)->first();

            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach($model as $hs){
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'cochu'=>$inputs['cochu']);

            return view('reports.bangluong.maubaohiem')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('pageTitle','Bảng trích nộp bảo hiểm chi tiết');
        } else
            return view('errors.notlogin');
    }

    //chưa làm xuất bh ra excel
    function printf_maubh_excel(Request $request){
        if (Session::has('admin')) {

            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maubh'];
            $inputs['mapb'] = $inputs['mapb_maubh'];
            $inputs['macvcq'] = $inputs['macvcq_maubh'];
            $inputs['mact'] = $inputs['mact_maubh'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $model = $this->getBangLuong($inputs);
            //$model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();


            //dd($request->all());

            $m_dv=dmdonvi::where('madv',$m_bl->madv)->first();

            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach($model as $hs){
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam);
            return view('reports.bangluong.maubaohiem')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('pageTitle','Bảng trích nộp bảo hiểm chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maudbhdnd(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maudbhdnd'];
            //$inputs['mapb'] = $inputs['mapb_maudbhdnd'];
            //$inputs['macvcq'] = $inputs['macvcq_maudbhdnd'];
            //$inputs['mact'] = $inputs['mact_maudbhdnd'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->where('mact','1536402868')->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);
            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->sotk = $hoso->sotk;
                $ct->lvtd = $hoso->lvtd;
                $ct->hspc = $ct->pcdbqh + $ct->hesopc;
                $ct->sotienpc = $ct->hspc * $ct->luongcb;
                $ct->sotienkn = $ct->pckn * $ct->luongcb;
                $ct->sotien = $ct->sotienpc + $ct->sotienkn;
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.maudbhdnd')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maudbhdnd_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maudbhdnd'];
            //$inputs['mapb'] = $inputs['mapb_maudbhdnd'];
            //$inputs['macvcq'] = $inputs['macvcq_maudbhdnd'];
            //$inputs['mact'] = $inputs['mact_maudbhdnd'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();

            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
                $ct->lvtd = count($hoso) > 0 ? $hoso->lvtd : null;
                $ct->hspc = count($hoso) > 0 ? $hoso->pcdbqh : null;
                $ct->sotien = $ct->hspc * $ct->luongcb;
            }
            $model = $model->where('hspc','>',0);

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            Excel::create('PC ĐBHĐND',function($excel) use($m_dv,$thongtin,$model){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model){
                    $sheet->loadView('reports.bangluong.donvi.maudbhdnd')
                        ->with('model',$model->sortBy('stt'))
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('pageTitle','DS CHI TRA');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_maublpc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maublpc'];
            $inputs['mapb'] = $inputs['mapb_maublpc'];
            $inputs['macvcq'] = $inputs['macvcq_maublpc'];
            $inputs['mact'] = $inputs['mact_maublpc'];
            $model = $this->getBangLuong($inputs);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();

            //$model = bangluong_ct::where('mabl', $mabl)->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_chucvu = getChucVuCQ();

            $tencv = isset($a_chucvu[$inputs['macvcq']]) ? $a_chucvu[$inputs['macvcq']]:'';
            $tencv = strlen($inputs['macvcq'])==0? 'Tất cả các chức vụ':$tencv;

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->lvtd = $hoso->lvtd;
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
                $ct->lvtd = count($hoso) > 0 ? $hoso->lvtd : null;
                $ct->hspc = $ct->phanloai == 'KHAC'? $ct->hesopc : $ct->heso;
                $ct->sotien = $ct->hspc * $ct->luongcb;
            }

            //$model = $model->where('heso', '>', 0);
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            //dd($model);
            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'tencv' => Str::upper($tencv));

            return view('reports.bangluong.donvi.maublpc')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maublpc_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maublpc'];
            $inputs['mapb'] = $inputs['mapb_maublpc'];
            $inputs['macvcq'] = $inputs['macvcq_maublpc'];
            $inputs['mact'] = $inputs['mact_maublpc'];
            $model = $this->getBangLuong($inputs);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();

            //$model = bangluong_ct::where('mabl', $mabl)->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_chucvu = getChucVuCQ();

            $tencv = isset($a_chucvu[$inputs['macvcq']]) ? $a_chucvu[$inputs['macvcq']]:'';
            $tencv = strlen($inputs['macvcq'])==0? 'Tất cả các chức vụ':$tencv;

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
                $ct->lvtd = count($hoso) > 0 ? $hoso->lvtd : null;
                $ct->sotien = $ct->heso * $ct->luongcb;
            }
            $model = $model->where('heso', '>', 0);
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'tencv' => Str::upper($tencv));

            Excel::create('BANGLUONG',function($excel) use($m_dv,$thongtin,$model){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model){
                    $sheet->loadView('reports.bangluong.donvi.maublpc')
                        ->with('model', $model->sortBy('stt'))
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle','DS CHI TRA');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_maubchd(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maubchd'];
            //$inputs['mapb'] = $inputs['mapb_maudbhdnd'];
            //$inputs['macvcq'] = $inputs['macvcq_maudbhdnd'];
            //$inputs['mact'] = $inputs['mact_maudbhdnd'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->where('mact','1536459380')->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq])? $a_cv[$hoso->macvcq]:'';
                $ct->chucvukn = isset($a_cv[$ct->macvcq])? $a_cv[$ct->macvcq]:'';
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
                $ct->lvtd = count($hoso) > 0 ? $hoso->lvtd : null;

                if($ct->phanloai == 'CAPUY'){
                    $ct->pcvk = $ct->hesopc;
                    //$ct->pckn = $ct->hesopc;
                }
                $ct->sotien = ($ct->pcvk + $ct->pckn) * $ct->luongcb;
            }

            $model_cvc = $model->where('pcvk','>',0)->where('phanloai','CVCHINH');
            $model_kn = $model->where('hesopc','>',0)->where('phanloai','CAPUY');
            foreach($model_kn as $ct){
                $model_cvc->add($ct);
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.maubchd')
                ->with('model',$model_cvc->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maubchd_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maubchd'];
            //$inputs['mapb'] = $inputs['mapb_maudbhdnd'];
            //$inputs['macvcq'] = $inputs['macvcq_maudbhdnd'];
            //$inputs['mact'] = $inputs['mact_maudbhdnd'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq])? $a_cv[$hoso->macvcq]:'';
                $ct->chucvukn = isset($a_cv[$ct->macvcq])? $a_cv[$ct->macvcq]:'';
                $ct->sotk = count($hoso) > 0 ? $hoso->sotk : null;
                $ct->lvtd = count($hoso) > 0 ? $hoso->lvtd : null;

                if($ct->phanloai == 'CAPUY'){
                    $ct->pcvk = $ct->hesopc;
                    //$ct->pckn = $ct->hesopc;
                }
                $ct->sotien = ($ct->pcvk + $ct->pckn) * $ct->luongcb;
            }

            $model_cvc = $model->where('pcvk','>',0)->where('phanloai','CVCHINH');
            $model_kn = $model->where('hesopc','>',0)->where('phanloai','CAPUY');
            foreach($model_kn as $ct){
                $model_cvc->add($ct);
            }
            $model = $model_cvc;
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            Excel::create('BCH DANGUY',function($excel) use($m_dv,$thongtin,$model){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model){
                    $sheet->loadView('reports.bangluong.donvi.maubchd')
                        ->with('model', $model->sortBy('stt'))
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle','DS CHI TRA');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauqs(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mauqs'];
            $inputs['mapb']= $inputs['mapb_mauqs'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->where('mact','1536402878')->get();
            if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
                $model = $model->where('mapb', $inputs['mapb']);
            }

            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);


            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $ct->st_pck = $ct->pck * $ct->luongcb;
                $ct->st_pcdbn = $ct->pcdbn * $ct->luongcb;
                $ct->st_pctn = $ct->pctn * $ct->luongcb;
                $ct->st_pcthni = $ct->pcthni * $ct->luongcb;
                $ct->sotien = $ct->st_pck + $ct->st_pcdbn + $ct->st_pctn + $ct->st_pcthni;
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.mauqs')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauqs_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mauqs'];
            $inputs['mapb']= $inputs['mapb_mauqs'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->get();
            if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
                $model = $model->where('mapb', $inputs['mapb']);
            }

            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);


            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $ct->st_hesopc = $ct->hesopc * $ct->luongcb;
                $ct->st_pcdbn = $ct->pcdbn * $ct->luongcb;
                $ct->st_pctn = $ct->pctn * $ct->luongcb;
                $ct->st_pcthni = $ct->pcthni * $ct->luongcb;
                $ct->sotien = $ct->st_hesopc + $ct->st_pcdbn + $ct->st_pctn + $ct->st_pcthni;
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            Excel::create('QUANSU',function($excel) use($m_dv,$thongtin,$model){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model){
                    $sheet->loadView('reports.bangluong.donvi.mauqs_excel')
                        ->with('model', $model->sortBy('stt'))
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle','DS CHI TRA');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');


        } else
            return view('errors.notlogin');
    }

    public function printf_maucd(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->where('mact','1536402895')->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq])? $a_cv[$hoso->macvcq]:'';
                $ct->chucvukn = isset($a_cv[$ct->macvcq])? $a_cv[$ct->macvcq]:'';
                //do khi nhap trong chuc vu chinh (nhap vap hesopc)
                $ct->sotienkn = ($ct->pckn + $ct->hesopc) * $ct->luongcb;
                $ct->sotienk = $ct->pck * $ct->luongcb;
                $ct->sotien = ($ct->pck + $ct->pckn + + $ct->hesopc) * $ct->luongcb;
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.maucd')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maumc(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->where('mact','1536459160')->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq])? $a_cv[$hoso->macvcq]:'';
                $ct->chucvukn = isset($a_cv[$ct->macvcq])? $a_cv[$ct->macvcq]:'';

                $ct->sotiendh = $ct->pcdh * $ct->luongcb;
                $ct->sotienk = $ct->pck * $ct->luongcb;
                $ct->sotien = $ct->sotiendh + $ct->pcd;
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.maumc')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautruc(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_truc::where('mabl',$mabl)->get();
            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
            }
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.mautruc')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautinhnguyen(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model = bangluong_ct::where('mabl',$mabl)->where('mact','1537427170')->get();
            $model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq])? $a_cv[$hoso->macvcq]:'';
                $ct->chucvukn = isset($a_cv[$ct->macvcq])? $a_cv[$ct->macvcq]:'';

                $ct->sotien = $ct->hesopc * $ct->luongcb;
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.mautinhnguyen')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    //Phân loai = 0: hệ sô; 1: số tiền
    function getBangLuong($inputs, $phanloai=0)
    {
        $mabl = $inputs['mabl'];
        $model = bangluong_ct::where('mabl', $mabl)->get();
        $m_bl = bangluong::select('madv')->where('mabl', $mabl)->first();
        $m_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
        $a_ht = array_column($m_hoso->toarray(),'tencanbo','macanbo');
        $dmchucvucq = array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(), 'tenvt', 'macvcq');
        $sunghiep = array_column($m_hoso->toarray(), 'sunghiep', 'macanbo');
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');

        foreach ($model as $hs) {
            $hs->tencv = isset($dmchucvucq[$hs->macvcq]) ? $dmchucvucq[$hs->macvcq] : '';
            $hs->sunghiep = isset($sunghiep[$hs->macanbo]) ? $sunghiep[$hs->macanbo] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            if($hs->tencanbo == ''){
                $hs->tencanbo = isset($a_ht[$hs->macanbo]) ? $a_ht[$hs->macanbo] : ''; //kiêm nhiệm chưa có tên cán bộ
            }
           // $hs->tencanbo = isset($a_ht[$hs->macanbo]) ? $a_ht[$hs->macanbo] : ''; //kiêm nhiệm chưa có tên cán bộ
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

    function getBangLuong_moi($inputs){
        $model = bangluong_ct::where('mabl', $inputs['mabl'])->get();
        $m_hoso = hosocanbo::where('madv', $inputs['madv'])->get();

        $a_ht = array_column($m_hoso->toarray(),'tencanbo','macanbo');
        $dmchucvucq = array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(), 'tenvt', 'macvcq');
        $sunghiep = array_column($m_hoso->toarray(), 'sunghiep', 'macanbo');
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');

        foreach ($model as $hs) {
            $hs->tencv = isset($dmchucvucq[$hs->macvcq]) ? $dmchucvucq[$hs->macvcq] : '';
            $hs->sunghiep = isset($sunghiep[$hs->macanbo]) ? $sunghiep[$hs->macanbo] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            if($hs->tencanbo == ''){
                $hs->tencanbo = isset($a_ht[$hs->macanbo]) ? $a_ht[$hs->macanbo] : ''; //kiêm nhiệm chưa có tên cán bộ
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

    function storeBangLuong($thang, $data){
        switch($thang){
            case '01':{
                \App\bangluong_ct_01::insert($data);
                break;
            }
            case '02':{
                \App\bangluong_ct_02::insert($data);
                break;
            }
            case '03':{
                \App\bangluong_ct_03::insert($data);
                break;
            }
            case '04':{
                \App\bangluong_ct_04::insert($data);
                break;
            }
            case '05':{
                \App\bangluong_ct_05::insert($data);
                break;
            }
            case '06':{
                \App\bangluong_ct_06::insert($data);
                break;
            }
            case '07':{
                \App\bangluong_ct_07::insert($data);
                break;
            }
            case '08':{
                \App\bangluong_ct_08::insert($data);
                break;
            }
            case '09':{
                \App\bangluong_ct_09::insert($data);
                break;
            }
            case '10':{
                \App\bangluong_ct_10::insert($data);
                break;
            }
            case '11':{
                \App\bangluong_ct_11::insert($data);
                break;
            }
            case '12':{
                \App\bangluong_ct_12::insert($data);
                break;
            }
        }
    }

    function createBangLuong($thang, $data){
        switch($thang){
            case '01':{
                \App\bangluong_ct_01::create($data);
                break;
            }
            case '02':{
                \App\bangluong_ct_02::create($data);
                break;
            }
            case '03':{
                \App\bangluong_ct_03::create($data);
                break;
            }
            case '04':{
                \App\bangluong_ct_04::create($data);
                break;
            }
            case '05':{
                \App\bangluong_ct_05::create($data);
                break;
            }
            case '06':{
                \App\bangluong_ct_06::create($data);
                break;
            }
            case '07':{
                \App\bangluong_ct_07::create($data);
                break;
            }
            case '08':{
                \App\bangluong_ct_08::create($data);
                break;
            }
            case '09':{
                \App\bangluong_ct_09::create($data);
                break;
            }
            case '10':{
                \App\bangluong_ct_10::create($data);
                break;
            }
            case '11':{
                \App\bangluong_ct_11::create($data);
                break;
            }
            case '12':{
                \App\bangluong_ct_12::create($data);
                break;
            }
        }
    }

    function getBL($thang, $mabl){
        switch($thang){
            case '01':{
                return  \App\bangluong_ct_01::where('mabl',$mabl)->get();
                break;
            }
            case '02':{
                return \App\bangluong_ct_02::where('mabl',$mabl)->get();
                break;
            }
            case '03':{
                return \App\bangluong_ct_03::where('mabl',$mabl)->get();
                break;
            }
            case '04':{
                return \App\bangluong_ct_04::where('mabl',$mabl)->get();
                break;
            }
            case '05':{
                return \App\bangluong_ct_05::where('mabl',$mabl)->get();
                break;
            }
            case '06':{
                return \App\bangluong_ct_06::where('mabl',$mabl)->get();
                break;
            }
            case '07':{
                return \App\bangluong_ct_07::where('mabl',$mabl)->get();
                break;
            }
            case '08':{
                return \App\bangluong_ct_08::where('mabl',$mabl)->get();
                break;
            }
            case '09':{
                return \App\bangluong_ct_09::where('mabl',$mabl)->get();
                break;
            }
            case '10':{
                return \App\bangluong_ct_10::where('mabl',$mabl)->get();
                break;
            }
            case '11':{
                return \App\bangluong_ct_11::where('mabl',$mabl)->get();
                break;
            }
            case '12':{
                return \App\bangluong_ct_12::where('mabl',$mabl)->get();
                break;
            }
        }
    }
    //<editor-fold desc="Tra cứu">
    function search()
    {
        if (Session::has('admin')) {
            $model_dv = dmdonvi::where('madv', session('admin')->madv)->get();
            if (session('admin')->quanlynhom) {
                $model_dv = dmdonvi::where('macqcq', session('admin')->madv)->get();
            }

            if (session('admin')->quanlykhuvuc) {
                $model_dv = dmdonvi::where('madvbc', session('admin')->madvbc)->get();
            }

            return view('search.chiluong.index')
                ->with('model_dv', $model_dv)
                ->with('pageTitle', 'Tra cứu chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function result(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model=bangluong::where('thang',$inputs['thang'])->where('nam',$inputs['nam'])->where('madv',$inputs['madv'])->get();

            return view('search.chiluong.result')
                ->with('model',$model)
                ->with('pageTitle','Kết quả tra cứu chi trả lương của cán bộ');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>
}
