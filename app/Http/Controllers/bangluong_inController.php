<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluong_phucap;
use App\bangluong_truc;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use App\dmtieumuc_default;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\hosotamngungtheodoi;
use App\nguonkinhphi_dinhmuc_ct;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class bangluong_inController extends Controller
{
    function test(){
        $a = 100000.6897;
        $b = dinhdangsothapphan($a,5);
        dd($b);
    }
    //Insert + update bảng lương
    function store_280918(Request $request)
    {
        $inputs = $request->all();
        $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
        $model = bangluong::where('mabl', $inputs['mabl'])->first();

        if (count($model) > 0) {
            //update

            $model->update($inputs);
            return redirect('/chuc_nang/bang_luong/danh_sach');
        } else {
            //kiểm tra bảng lương cùng nguồn, lĩnh vực hoạt động, lương cơ bản =>ko cho tạo
            $model_chk = bangluong::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])
                //->where('luongcoban', $inputs['luongcoban'])
                ->where('phanloai', 'BANGLUONG')
                ->where('manguonkp', $inputs['manguonkp'])
                ->where('madv',session('admin')->madv)
                ->first();

            if(count($model_chk)>0){
                return view('errors.trungbangluong');
            }

            //insert
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['phanloai'] = 'BANGLUONG';
            //$inputs['luongcoban'] = (getDbl($inputs['luongcoban']) * getDbl($inputs['phantramhuong'])) / 100;
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);

            $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01');
            //$ngaylap = Carbon::create('2018','04','01');
            $m_tamngung = hosotamngungtheodoi::select('macanbo')
                ->where('madv', $madv)->where('maphanloai', 'THAISAN')
                ->where('ngaytu', '<=', $ngaylap)
                ->where('ngayden', '>=', $ngaylap)
                ->get();

            $m_nghiphep = hosotamngungtheodoi::where('madv', $madv)
                ->where('maphanloai','<>', 'THAISAN')
                ->whereYear('ngaytu', $inputs['nam'])
                ->whereMonth('ngaytu', $inputs['thang'])
                ->get();

            //Lấy tất cả cán bộ trong đơn vị
            /*
            $m_cb = hosocanbo::where('madv', $madv)
                ->select('stt', 'macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'hesobl', 'hesopc', 'hesott', 'vuotkhung', DB::raw("'" . $inputs['mabl'] . "' as mabl"),
                    'pclt', 'pcdd', 'pck', 'pccv', 'pckv', 'pcth', 'pcdh', 'pcld', 'pcudn', 'pctn', 'pctnn', 'pcdbn', 'pcvk', 'pckn', 'pccovu', 'pcdbqh', 'pctnvk', 'pcbdhdcu', 'pcdang', 'pcthni', 'pcct', 'pckct')
                ->where('theodoi','<', '9')->get();
            */
            $m_cb = hosocanbo::where('madv', $madv)->where('theodoi','<', '9')->get();
            $m_cbkn = hosocanbo::where('madv', $madv)->where('theodoi','<', '9')->get();
            //Lấy danh sách cán bộ kiêm nhiệm
            $model_canbo_kn = hosocanbo_kiemnhiem::wherein('macanbo', function($qr){
                $qr->select('macanbo')->from('hosocanbo')->where('madv',session('admin')->madv)->where('theodoi','<', '9')->get();
            })->wherein('manguonkp',[$inputs['manguonkp'],''])->get();
            //dd($model_canbo_kn->where('macanbo','1511582352_1536502779')->toarray());

            foreach ($m_cb as $canbo) {
                //Dùng tìm kiếm các bộ nào phù hợp. Do lvhd là mảng nên pải lọc
                /*
                $a_lv = explode(',', $canbo->lvhd);

                if (in_array($inputs['linhvuc'], $a_lv) || $canbo->lvhd == null) {
                    $canbo->lvhd = $inputs['linhvuc'];
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

            $model_congtac = dmphanloaict::all();
            $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            //Không tính truy lĩnh
            $a_goc = array('heso','vuotkhung','pccv'); //mảng phụ cấp làm công thức tính
            $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
            //$model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
            $a_ts =array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
            $manguonkp = $inputs['manguonkp'];

            $a_nguonpc =array_column(nguonkinhphi_dinhmuc_ct::wherein('maso',function($qr) use ($manguonkp){
                $qr->select('maso')->from('nguonkinhphi_dinhmuc')->where('madv', session('admin')->madv)->where('manguonkp', $manguonkp)->get();
            })->get()->toarray(), 'mapc');

            //Tạo bảng lương
            bangluong::create($inputs);

            //dd($model_canbo_kn);
            //Tính toán lương cho cán bộ
            //$a_col = getColPhuCap(); //lấy theo phụ cấp => tự tính phụ cấp vượt khung, hệ số lương
            //$a_baohiem = getPhuCapNopBH();
            $model_chucvu = dmchucvucq::where('maphanloai', session('admin')->maphanloai)->wherein('madv', ['SA', session('admin')->madv])->get();

            $ngaycong = dmdonvi::where('madv',$madv)->first()->songaycong;
            foreach ($m_cb as $cb) {
                $cb->mabl = $inputs['mabl'];
                $cb->congtac = 'CONGTAC';
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
                $congtac = $model_congtac->where('mact', $cb->mact)->first();
                if (count($congtac) > 0) {
                    $cb->macongtac = $congtac->macongtac;
                }

                $phanloai = $model_phanloai->where('mact', $cb->mact)->first();
                if (count($phanloai) > 0) {
                    $cb->bhxh = floatval($phanloai->bhxh) / 100;
                    $cb->bhyt = floatval($phanloai->bhyt) / 100;
                    $cb->kpcd = floatval($phanloai->kpcd) / 100;
                    $cb->bhtn = floatval($phanloai->bhtn) / 100;

                    $cb->bhxh_dv = floatval($phanloai->bhxh_dv) / 100;
                    $cb->bhyt_dv = floatval($phanloai->bhyt_dv) / 100;
                    $cb->kpcd_dv = floatval($phanloai->kpcd_dv) / 100;
                    $cb->bhtn_dv = floatval($phanloai->bhtn_dv) / 100;

                    if ($cb->sunghiep == 'Công chức' || (count($chucvu) > 0 && $chucvu->ttdv == 1)) {
                        $cb->bhtn = 0;
                        $cb->bhtn_dv = 0;
                    }
                }

                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $heso_goc = $cb->heso * $cb->pthuong / 100;
                //$cb->heso = $cb->heso; //(tập sự hưởng theo %)
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;

                //tính thâm niên nghề
                $pctnn = $model_phucap->where('mapc', 'pctnn')->first();
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
                            if ($ct != '' && $ct != 'pctnn')
                                $heso += $cb->$ct;
                        }
                        $cb->pctnn = $heso * $cb->pctnn / 100;
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $cb->pctnn = 0;
                        break;
                    }
                }

                $tt = 0;
                $ths = 0;
                //nếu cán bộ nghỉ thai sản
                $thaisan = count($m_tamngung->where('macanbo',$cb->macanbo))>0? true : false;

                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;
                    //gán số tiền bảo hiểm  = 0 khi tính để ko trùng với giá trị cán bộ trc
                    $ct->stbhxh = 0;
                    $ct->stbhyt = 0;
                    $ct->stkpcd = 0;
                    $ct->stbhtn = 0;
                    $ct->stbhxh_dv = 0;
                    $ct->stbhyt_dv = 0;
                    $ct->stkpcd_dv = 0;
                    $ct->stbhtn_dv = 0;
                    if($cb->$mapc <= 0){
                        continue;
                    }

                    if(count($a_nguonpc) > 0 && !in_array($mapc, $a_nguonpc) && !in_array($mapc, $a_goc)){
                        $cb->$mapc = 0;
                    }

                    $ct->heso_goc = $cb->$mapc;
                    $heso = 0;

                    $pl = getDbl($ct->phanloai);
                    if ($pl == 2) {
                        foreach (explode(',', $ct->congthuc) as $cthuc) {
                            if ($cthuc != '')
                                $heso += $cb->$cthuc;
                        }
                    }

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
                            if ($mapc != 'vuotkhung' && $mapc != 'pctnn') {//vượt khung đã tính ở trên
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

                    if (!$thaisan ||($thaisan && in_array($mapc,$a_ts)) ) {//lưu vào bảng lương phụ cấp (chi luu số tiền >0)
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
                        bangluong_phucap::create($a_kq);
                    }
                }
                $ths = $ths + $heso_goc - $cb->heso;//do chỉ lương nb hưởng 85%, các hệ số hưởng %, bảo hiểm thì lấy 100% để tính
                $cb->tonghs = $ths;
                //nếu cán bộ nghỉ thai sản
                if($thaisan){
                    $cb->tencanbo = $cb->tencanbo . '(nghỉ thai sản)';
                    $hesots = 0;
                    $ttts = 0;
                    foreach($a_ts as $val){
                        if($cb->$val > 10000){//sô tiền
                            $ttts += $cb->$val;
                        }else{
                            $hesots += $cb->$val;
                        }
                    }
                    if(count($a_nguonpc) > 0){
                        $hesots = 0;
                        $ttts = 0;
                        foreach($a_nguonpc as $val){
                            if($cb->$val > 10000){//sô tiền
                                $ttts += $cb->$val;
                            }else{
                                $hesots += $cb->$val;
                            }
                        }
                    }
                    $cb->tonghs = $hesots;
                    $cb->ttl = round($inputs['luongcoban'] * $hesots + $ttts);
                    $cb->congtac = 'THAISAN';
                }else {
                    if(count($a_nguonpc) > 0){
                        //dd($a_nguonpc);
                        $ths = 0;
                        $tt = 0;
                        foreach($a_nguonpc as $val){
                            if($cb->$val > 10000){//sô tiền
                                $tt += $cb->$val;
                            }else{
                                $ths += $cb->$val;
                            }
                        }
                        $cb->tonghs = $ths;
                    }
                    $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
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

                    if(count($a_nguonpc) > 0 && $manguonkp == '12'){//chưa xử lý dc
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
                        $sotiencong = $inputs['luongcoban'] * ($cb->heso + $cb->vuotkhung + $cb->pccv + $cb->hesobl + $cb->pctnn);
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
                $ths = 0;
                $tt = 0;

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
                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;
                    if($cb->$mapc <= 0){
                        continue;
                    }

                    $pl = getDbl($ct->phanloai);

                    switch ($pl) {
                        case 0: {//hệ số
                            $ths += $cb->$mapc;
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
                $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
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
        $model_dimhmuc = nguonkinhphi_dinhmuc_ct::wherein('maso',function($qr) use ($inputs){
            $qr->select('maso')->from('nguonkinhphi_dinhmuc')->where('madv', session('admin')->madv)->where('manguonkp', $inputs['manguonkp'])->get();
        })->get();
        $a_nguonpc = array_column($model_dimhmuc->toarray(), 'mapc');
        $a_mucluong = array_column($model_dimhmuc->toarray(),'luongcoban', 'mapc');
        $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->get();
        foreach($model_phucap as $pc){
            $pc->luongcoban = isset($a_mucluong[$pc->mapc])? $a_mucluong[$pc->mapc] : 0;
        }

        $a_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
        $a_cv = array_column(dmchucvucq::where('maphanloai', session('admin')->maphanloai)
            ->wherein('madv', ['SA', session('admin')->madv])->get()->toarray(),'ttdv','macvcq');

        $ngaycong = dmdonvi::where('madv',$inputs['madv'])->first()->songaycong;
        foreach ($m_cb as $cb) {
            $cb->mabl = $inputs['mabl'];
            $cb->congtac = 'CONGTAC';
            $cb->macongtac = null;
            $cb->bhxh = 0;
            $cb->bhyt = 0;
            $cb->kpcd = 0;
            $cb->bhtn = 0;
            $cb->bhxh_dv = 0;
            $cb->bhyt_dv = 0;
            $cb->kpcd_dv = 0;
            $cb->bhtn_dv = 0;
            $cb->macongtac = isset($a_ct[$cb->mact])? $a_ct[$cb->mact] : '' ;

            $phanloai = $model_phanloai->where('mact', $cb->mact)->first();
            if (count($phanloai) > 0) {
                $cb->bhxh = floatval($phanloai->bhxh) / 100;
                $cb->bhyt = floatval($phanloai->bhyt) / 100;
                $cb->kpcd = floatval($phanloai->kpcd) / 100;
                $cb->bhtn = floatval($phanloai->bhtn) / 100;

                $cb->bhxh_dv = floatval($phanloai->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($phanloai->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($phanloai->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($phanloai->bhtn_dv) / 100;

                if ($cb->sunghiep == 'Công chức' || (isset($a_cv[$cb->macvcq])  && $a_cv[$cb->macvcq] == 1)) {
                    $cb->bhtn = 0;
                    $cb->bhtn_dv = 0;
                }
            }

            //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
            //$heso_goc = $cb->heso * $cb->pthuong / 100;
            $cb->heso = $cb->heso * $cb->pthuong / 100;
            $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;

            //tính thâm niên nghề
            $pctnn = $model_phucap->where('mapc', 'pctnn')->first();
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
                            if ($ct != '' && $ct != 'pctnn')
                                $heso += $cb->$ct;
                        }
                        $cb->pctnn = $heso * $cb->pctnn / 100;
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $cb->pctnn = 0;
                        break;
                    }
                }
            }

            $tt = 0;
            $ths = 0;
            //nếu cán bộ nghỉ thai sản
            $thaisan = count($m_tamngung->where('macanbo',$cb->macanbo))>0? true : false;

            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                //gán số tiền bảo hiểm  = 0 khi tính để ko trùng với giá trị cán bộ trc
                $ct->stbhxh = 0;
                $ct->stbhyt = 0;
                $ct->stkpcd = 0;
                $ct->stbhtn = 0;
                $ct->stbhxh_dv = 0;
                $ct->stbhyt_dv = 0;
                $ct->stkpcd_dv = 0;
                $ct->stbhtn_dv = 0;
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
                $heso = 0;

                $pl = getDbl($ct->phanloai);
                if ($pl == 2) {
                    foreach (explode(',', $ct->congthuc) as $cthuc) {
                        if ($cthuc != '')
                            $heso += $cb->$cthuc;
                    }
                }

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
                        if ($mapc != 'vuotkhung' && $mapc != 'pctnn') {//vượt khung đã tính ở trên
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                            $ths += $cb->$mapc;
                            $sotien = $cb->$mapc * $ct->luongcoban;
                        }else{
                            $ths += $cb->$mapc;
                            $sotien = $cb->$mapc * $ct->luongcoban;
                        }
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
                    bangluong_phucap::create($a_kq);
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

            if(count($a_nguonpc) > 0 && $inputs['manguonkp'] == '12'){//chưa xử lý dc
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
        $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
        //$model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();
        $a_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
        //các loại phụ cấp cán bộ được điều động động đến được hưởng (chưa làm cho định mức)
        $a_dd = array('pclt');
        $a_goc = array('heso','vuotkhung','pccv'); //mảng phụ cấp làm công thức tính

        //$manguonkp = $inputs['manguonkp'];

        //dd($model_canbo_kn);
        //Tính toán lương cho cán bộ
        //$a_col = getColPhuCap(); //lấy theo phụ cấp => tự tính phụ cấp vượt khung, hệ số lương
        //$a_baohiem = getPhuCapNopBH();
        $a_cv = array_column(dmchucvucq::where('maphanloai', session('admin')->maphanloai)
            ->wherein('madv', ['SA', session('admin')->madv])->get()->toarray(),'ttdv','macvcq');

        $ngaycong = dmdonvi::where('madv',$inputs['madv'])->first()->songaycong;
        foreach ($m_cb as $cb) {
            $cb->mabl = $inputs['mabl'];
            $cb->congtac = 'CONGTAC';
            $cb->macongtac = null;
            $cb->bhxh = 0;
            $cb->bhyt = 0;
            $cb->kpcd = 0;
            $cb->bhtn = 0;
            $cb->bhxh_dv = 0;
            $cb->bhyt_dv = 0;
            $cb->kpcd_dv = 0;
            $cb->bhtn_dv = 0;
            $cb->macongtac = isset($a_ct[$cb->mact])? $a_ct[$cb->mact] : '' ;

            $phanloai = $model_phanloai->where('mact', $cb->mact)->first();
            if (count($phanloai) > 0) {
                $cb->bhxh = floatval($phanloai->bhxh) / 100;
                $cb->bhyt = floatval($phanloai->bhyt) / 100;
                $cb->kpcd = floatval($phanloai->kpcd) / 100;
                $cb->bhtn = floatval($phanloai->bhtn) / 100;

                $cb->bhxh_dv = floatval($phanloai->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($phanloai->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($phanloai->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($phanloai->bhtn_dv) / 100;

                if ($cb->sunghiep == 'Công chức' || (isset($a_cv[$cb->macvcq])  && $a_cv[$cb->macvcq] == 1)) {
                    $cb->bhtn = 0;
                    $cb->bhtn_dv = 0;
                }
            }

            //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
            //$heso_goc = $cb->heso * $cb->pthuong / 100;
            $cb->heso = $cb->heso * $cb->pthuong / 100;
            $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;

            //tính thâm niên nghề
            $pctnn = $model_phucap->where('mapc', 'pctnn')->first();
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
                        if ($ct != '' && $ct != 'pctnn')
                            $heso += $cb->$ct;
                    }
                    $cb->pctnn = $heso * $cb->pctnn / 100;
                    break;
                }
                default: {//trường hợp còn lại (ẩn,...)
                    $cb->pctnn = 0;
                    break;
                }
            }

            $tt = 0;
            $ths = 0;
            //nếu cán bộ nghỉ thai sản
            $thaisan = count($m_tamngung->where('macanbo',$cb->macanbo))>0? true : false;

            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                //gán số tiền bảo hiểm  = 0 khi tính để ko trùng với giá trị cán bộ trc
                $ct->stbhxh = 0;
                $ct->stbhyt = 0;
                $ct->stkpcd = 0;
                $ct->stbhtn = 0;
                $ct->stbhxh_dv = 0;
                $ct->stbhyt_dv = 0;
                $ct->stkpcd_dv = 0;
                $ct->stbhtn_dv = 0;
                if ($cb->$mapc <= 0) {
                    continue;
                }

                //cán bộ được điều động đến
                if ($cb->theodoi == 4 && !in_array($mapc, $a_dd) && !in_array($mapc, $a_goc)) {
                    $cb->$mapc = 0;
                }

                $ct->heso_goc = $cb->$mapc;
                $heso = 0;

                $pl = getDbl($ct->phanloai);
                if ($pl == 2) {
                    foreach (explode(',', $ct->congthuc) as $cthuc) {
                        if ($cthuc != '')
                            $heso += $cb->$cthuc;
                    }
                }

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
                        if ($mapc != 'vuotkhung' && $mapc != 'pctnn') {//vượt khung đã tính ở trên
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                            $ths += $cb->$mapc;
                            $sotien = $cb->$mapc * $inputs['luongcoban'];
                        } else {
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

                if (!$thaisan || ($thaisan && in_array($mapc, $a_ts))) {//lưu vào bảng lương phụ cấp (chi luu số tiền >0)
                    $ct->mabl = $inputs['mabl'];
                    $ct->luongcoban = $inputs['luongcoban'];
                    $ct->macanbo = $cb->macanbo;
                    $ct->tencanbo = $cb->tencanbo;
                    $ct->maso = $mapc;
                    $ct->ten = $ct->tenpc;
                    $ct->heso = $cb->$mapc;
                    $ct->sotien = round($sotien, 0);
                    //if ($ct->baohiem == 1) {
                    if ($ct->baohiem == 1 && $cb->baohiem == 1 && $cb->theodoi != 4) {
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
                    bangluong_phucap::create($a_kq);
                }
            }
            //$ths = $ths + $heso_goc - $cb->heso;//do chỉ lương nb hưởng 85%, các hệ số hưởng %, bảo hiểm thì lấy 100% để tính
            $cb->tonghs = $ths;
            //nếu cán bộ nghỉ thai sản
            if($thaisan){
                $cb->tencanbo = $cb->tencanbo . '(nghỉ thai sản)';
                $hesots = 0;
                $ttts = 0;
                //thai sản
                foreach($a_ts as $val){
                    if($cb->$val > 10000){//sô tiền
                        $ttts += $cb->$val;
                    }else{
                        $hesots += $cb->$val;
                    }
                }
                //điều động
                if($cb->theodoi == 4){
                    $hesots = 0;
                    $ttts = 0;
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $ttts += $cb->$val;
                        }else{
                            $hesots += $cb->$val;
                        }
                    }
                }

                // được điều động đến
                if($cb->theodoi == 3){
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $ttts -= $cb->$val;
                        }else{
                            $hesots -= $cb->$val;
                        }
                    }
                }

                $cb->tonghs = $hesots;
                $cb->ttl = round($inputs['luongcoban'] * $hesots + $ttts);
                $cb->congtac = 'THAISAN';
            }else {
                //điều động
                if($cb->theodoi == 4){
                    $ths = 0;
                    $tt = 0;
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $tt += $cb->$val;
                        }else{
                            $ths += $cb->$val;
                        }
                    }
                }
                // được điều động đến
                if($cb->theodoi == 3){
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $tt -= $cb->$val;
                        }else{
                            $ths -= $cb->$val;
                        }
                    }
                }
                $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
                //kiểm tra cán bộ ko chuyên trách thì tự động lấy lương cơ bản * % bảo hiểm

                if($cb->macongtac == 'KHONGCT') {
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
                }else{
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

                //nếu cán bộ nghỉ phép
                //ngày công = lương co + chuc vu + ....
                $nghi = $m_nghiphep->where('macanbo', $cb->macanbo)->first();
                if (count($nghi) > 0) {
                    $cb->congtac = 'NGHIPHEP';
                    $sotiencong = $inputs['luongcoban'] * ($cb->heso + $cb->vuotkhung + $cb->pccv + $cb->hesobl + $cb->pctnn);
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

            }
            $cb->luongtn = $cb->ttl - $cb->ttbh - $cb->giaml;

            $kq = $cb->toarray();
            unset($kq['id']);
            bangluong_ct::create($kq);
        }

        //Tính toán lương cho cán bộ kiêm nhiệm
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
            $ths = 0;
            $tt = 0;

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
            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                if($cb->$mapc <= 0){
                    continue;
                }

                $pl = getDbl($ct->phanloai);

                switch ($pl) {
                    case 0: {//hệ số
                        $ths += $cb->$mapc;
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
            $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
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

    function tinhluong_khongdinhmuc_16_10($inputs){
        $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01');
        $m_tamngung = hosotamngungtheodoi::where('madv', $inputs['madv'])->where('maphanloai', 'THAISAN')->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)->get();
        $m_nghiphep = hosotamngungtheodoi::where('madv', $inputs['madv'])->wherein('maphanloai',['NGHIPHEP','NGHIOM'])->whereYear('ngaytu', $inputs['nam'])->whereMonth('ngaytu', $inputs['thang'])->get();
        $m_khongluong = hosotamngungtheodoi::where('madv', $inputs['madv'])->where('maphanloai', 'KHONGLUONG')->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)->get();

        $m_cb = hosocanbo::where('madv', $inputs['madv'])->where('theodoi','<', '9')->get();
        $a_cbkn = hosocanbo::where('madv', $inputs['madv'])->where('theodoi','<', '9')->get()->keyBy('macanbo')->toArray();


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
        $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
        $model_phucap = dmphucap_donvi::select('mapc','phanloai','congthuc','baohiem','tenpc')
            ->where('madv', session('admin')->madv)->wherenotin('mapc', ['hesott'])->get();

        $a_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
        //các loại phụ cấp cán bộ được điều động động đến được hưởng (chưa làm cho định mức)
        $a_dd = array('pclt');
        $a_goc = array('heso','vuotkhung','pccv'); //mảng phụ cấp làm công thức tính

        //Mảng chứa các cột bỏ để chạy hàm insert
        $a_col_pc = array('id','baohiem','mapc','luongcoban','tenpc');
        $ngaycong = dmdonvi::where('madv',$inputs['madv'])->first()->songaycong;
        foreach ($m_cb as $cb) {
            $cb->mabl = $inputs['mabl'];
            $cb->congtac = 'CONGTAC';
            //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
            //$heso_goc = $cb->heso * $cb->pthuong / 100;
            $cb->heso = $cb->heso * $cb->pthuong / 100;
            $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
            $cb->bhxh = floatval($cb->bhxh) / 100;
            $cb->bhyt = floatval($cb->bhyt) / 100;
            $cb->kpcd = floatval($cb->kpcd) / 100;
            $cb->bhtn = floatval($cb->bhtn) / 100;
            $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
            $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
            $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
            $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;
            //tính thâm niên nghề
            $pctnn = $model_phucap->where('mapc', 'pctnn')->first();
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
                        if ($ct != '' && $ct != 'pctnn')
                            $heso += $cb->$ct;
                    }
                    $cb->pctnn = $heso * $cb->pctnn / 100;
                    break;
                }
                default: {//trường hợp còn lại (ẩn,...)
                    $cb->pctnn = 0;
                    break;
                }
            }

            $tt = 0;
            $ths = 0;
            //nếu cán bộ nghỉ thai sản
            $thaisan = count($m_tamngung->where('macanbo',$cb->macanbo))>0? true : false;
            $khongluong = count($m_khongluong->where('macanbo',$cb->macanbo))>0? true : false;
            $a_luupc = array();

            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                //gán số tiền bảo hiểm  = 0 khi tính để ko trùng với giá trị cán bộ trc
                $ct->stbhxh = 0;
                $ct->stbhyt = 0;
                $ct->stkpcd = 0;
                $ct->stbhtn = 0;
                $ct->ttbh = 0;
                $ct->stbhxh_dv = 0;
                $ct->stbhyt_dv = 0;
                $ct->stkpcd_dv = 0;
                $ct->stbhtn_dv = 0;
                $ct->ttbh_dv = 0;
                if ($cb->$mapc <= 0) {
                    continue;
                }

                //cán bộ được điều động đến
                if ($cb->theodoi == 4 && !in_array($mapc, $a_dd) && !in_array($mapc, $a_goc)) {
                    $cb->$mapc = 0;
                }

                $ct->heso_goc = $cb->$mapc;
                $heso = 0;

                $pl = getDbl($ct->phanloai);
                if ($pl == 2) {
                    foreach (explode(',', $ct->congthuc) as $cthuc) {
                        if ($cthuc != '')
                            $heso += $cb->$cthuc;
                    }
                }

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
                        if ($mapc != 'vuotkhung' && $mapc != 'pctnn') {//vượt khung đã tính ở trên
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                            $ths += $cb->$mapc;
                            $sotien = $cb->$mapc * $inputs['luongcoban'];
                        } else {
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

                if (!$khongluong || !$thaisan || ($thaisan && in_array($mapc, $a_ts))) {//lưu vào bảng lương phụ cấp (chi luu số tiền >0)
                    $ct->mabl = $inputs['mabl'];
                    $ct->luongcoban = $inputs['luongcoban'];
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

                    //$a_kq = $ct->toarray();
                    //unset($a_kq['id']);
                    $a_luupc[]= $ct->toarray();
                    //bangluong_phucap::create($a_kq); //test chay tren mảng xem có nhanh hơn chay tưng cán bộ ko

                }
            }

            $a_luupc = unset_key($a_luupc,$a_col_pc);
            bangluong_phucap::insert($a_luupc);
            if($khongluong){//tính không lương rồi thoát
                $cb->tencanbo = $cb->tencanbo . ' (nghỉ không lương)';
                $cb->tonghs = 0;
                $cb->ttl = 0;
                $kq = $cb->toarray();
                unset($kq['id']);
                //dd($kq);
                bangluong_ct::create($kq);
                continue;
            }
            //$ths = $ths + $heso_goc - $cb->heso;//do chỉ lương nb hưởng 85%, các hệ số hưởng %, bảo hiểm thì lấy 100% để tính
            $cb->tonghs = $ths;
            //nếu cán bộ nghỉ thai sản
            if($thaisan){
                $cb->tencanbo = $cb->tencanbo . ' (nghỉ thai sản)';
                $hesots = 0;
                $ttts = 0;
                //thai sản
                foreach($a_ts as $val){
                    if($cb->$val > 10000){//sô tiền
                        $ttts += $cb->$val;
                    }else{
                        $hesots += $cb->$val;
                    }
                }
                //điều động
                if($cb->theodoi == 4){
                    $hesots = 0;
                    $ttts = 0;
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $ttts += $cb->$val;
                        }else{
                            $hesots += $cb->$val;
                        }
                    }
                }

                // được điều động đến
                if($cb->theodoi == 3){
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $ttts -= $cb->$val;
                        }else{
                            $hesots -= $cb->$val;
                        }
                    }
                }

                $cb->tonghs = $hesots;
                $cb->ttl = round($inputs['luongcoban'] * $hesots + $ttts);
                $cb->congtac = 'THAISAN';
            }else {
                //điều động
                if($cb->theodoi == 4){
                    $ths = 0;
                    $tt = 0;
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $tt += $cb->$val;
                        }else{
                            $ths += $cb->$val;
                        }
                    }
                }
                // được điều động đến
                if($cb->theodoi == 3){
                    foreach($a_dd as $val){
                        if($cb->$val > 10000){//sô tiền
                            $tt -= $cb->$val;
                        }else{
                            $ths -= $cb->$val;
                        }
                    }
                }
                $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
                //kiểm tra cán bộ ko chuyên trách thì tự động lấy lương cơ bản * % bảo hiểm

                if($cb->macongtac == 'KHONGCT') {
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
                }else{
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

                //nếu cán bộ nghỉ phép
                //ngày công = lương co + chuc vu + ....
                $nghi = $m_nghiphep->where('macanbo', $cb->macanbo)->first();
                if (count($nghi) > 0) {
                    $cb->congtac = 'NGHIPHEP';
                    $sotiencong = $inputs['luongcoban'] * ($cb->heso + $cb->vuotkhung + $cb->pccv + $cb->hesobl + $cb->pctnn);
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

            }
            $cb->luongtn = $cb->ttl - $cb->ttbh - $cb->giaml;

            $kq = $cb->toarray();
            unset($kq['id']);
            bangluong_ct::create($kq);
        }

        //Tính toán lương cho cán bộ kiêm nhiệm
        foreach ($model_canbo_kn as $cb) {
            //trong kiêm nhiệm: thâm niên lấy  % lương hệ số
            //đặc thù tính
            //lấy thông tin ở bảng hồ sơ cán bộ để lấy thông tin lương, phụ cấp
            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)

            //$canbo = $m_cb->where('macanbo',$cb->macanbo)->first(); không dùng được do khi lọc nguồn bỏ mất cán bộ này
            if(!array_key_exists($cb->macanbo,$a_cbkn)){
                continue;
            }

            $canbo = $a_cbkn[$cb->macanbo];

            $cb->mabl = $inputs['mabl'];
            $ths = 0;
            $tt = 0;

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
                            $heso += $canbo[$ct];
                    }
                    //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                    $heso += $canbo['hesopc'];
                    $cb->pcthni = $heso * $cb->pcthni / 100;
                    break;
                }
                default: {//trường hợp còn lại (ẩn,...)
                    $cb->pcthni = 0;
                    break;
                }
            }

            //
            $canbo['pcthni'] = $cb->pcthni; //set vao hồ sơ cán bộ để tính công thức lương
            $canbo['pctn'] = $cb->pctn;
            foreach ($model_phucap as $ct) {
                $mapc = $ct->mapc;
                if($cb->$mapc <= 0){
                    continue;
                }

                $pl = getDbl($ct->phanloai);

                switch ($pl) {
                    case 0: {//hệ số
                        $ths += $cb->$mapc;
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
                                        $heso += $canbo[$cthuc];
                                }
                            }
                            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                            $heso += $canbo['hesopc'];
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                            $ths += $cb->$mapc;
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
            $cb->ttl = round($inputs['luongcoban'] * $ths + $tt);
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

    public function printf_mau06(Request $request){
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
            $a_goc = array('heso','vuotkhung','hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

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
            //dd($m_bl);
            //dd($model_tm->where('sotien','>',0));
            return view('reports.bangluong.donvi.mau06')
                ->with('model',$model->sortBy('stt'))
                ->with('model_tm',$model_tm->where('sotien','>',0))
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
            //dd($model_st);
            return view('reports.bangluong.donvi.mau07')
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
            return view('reports.bangluong.donvi.mau07')
                ->with('model', $model->sortBy('stt'))
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

                $ct->sotienkn = $ct->pckn * $ct->luongcb;
                $ct->sotienk = $ct->pck * $ct->luongcb;
                $ct->sotien = ($ct->pck + $ct->pckn) * $ct->luongcb;
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
        $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
        $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
        $dmchucvucq = array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(), 'tenvt', 'macvcq');
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
