<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluong_phucap;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmkhoipb;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmtieumuc_default;
use App\hosocanbo;
use App\hosotamngungtheodoi;
use App\hosotruylinh;
use App\ngachluong;
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
    function index()
    {
        if (Session::has('admin')) {
            //khối phòng ban giờ là lĩnh vực hoạt động
            //$m_linhvuc = array_column(dmkhoipb::all()->toArray(), 'tenkhoipb', 'makhoipb');
            $m_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $a_phanloai = getPhanLoaiBangLuong();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model = bangluong::where('madv', session('admin')->madv)->get();
            foreach ($model as $bl) {
                $bl->tennguonkp = isset($m_nguonkp[$bl->manguonkp]) ? $m_nguonkp[$bl->manguonkp] : '';
                $bl->tenphanloai = isset($a_phanloai[$bl->phanloai]) ? $a_phanloai[$bl->phanloai] : 'Bảng lương cán bộ';
            }
            $model = $model->sortby('nam')->sortby('thang');
            return view('manage.bangluong.index')
                ->with('furl', '/chuc_nang/bang_luong/')
                ->with('furl_ajax', '/ajax/bang_luong/')
                ->with('model', $model)
                ->with('luongcb', getGeneralConfigs()['luongcb'])
                ->with('m_linhvuc', array_column(dmkhoipb::all()->toArray(), 'tenkhoipb', 'makhoipb'))
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
        $model = bangluong::where('mabl', $inputs['mabl'])->first();

        if (count($model) > 0) {
            //update
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            $model->update($inputs);
            return redirect('/chuc_nang/bang_luong/danh_sach');
        } else {
            //insert
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;

            $inputs['phantramhuong'] = getDbl($inputs['phantramhuong']);
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            $ngaylap = Carbon::create($inputs['nam'],$inputs['thang'],'01');
            //$ngaylap = Carbon::create('2018','04','01');
            $m_tamngung = hosotamngungtheodoi::select('macanbo')
                ->where('madv', $madv)
                ->where('maphanloai', 'THAISAN')
                ->where('ngaytu','<=',$ngaylap)
                ->where('ngayden','>=',$ngaylap)
                ->get();
            /*
            $m_cb = hosocanbo::wherein('macanbo', function($query)use($ngaylap){
                $query->select('macanbo')->from('hosotamngungtheodoi')->get();
            })->get();
            */

            //Lấy tất cả cán bộ trong đơn vị
            $m_cb = hosocanbo::where('madv', $madv)
                ->select('stt','macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'hesopc', 'hesott', 'vuotkhung', DB::raw("'" . $inputs['mabl'] . "' as mabl"),
                    'pclt', 'pcdd', 'pck', 'pccv', 'pckv', 'pcth', 'pcdh', 'pcld', 'pcudn', 'pctn', 'pctnn', 'pcdbn', 'pcvk', 'pckn', 'pccovu', 'pcdbqh', 'pctnvk', 'pcbdhdcu', 'pcdang', 'pcthni', 'pcct')
                ->where('theodoi', '1')
                ->wherenotin('macanbo', $m_tamngung->toarray())
                ->get();

            //Lấy cán bộ tạm ngưng theo dõi
            //Nếu phân loai = THAISAN && pcttn >0 =>đưa vào bảng lương
            $model_canbo_tn = hosocanbo::select('stt','macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'pccv', 'vuotkhung', 'pcudn', DB::raw("'" . $inputs['mabl'] . "' as mabl"))
                ->wherein('macanbo', $m_tamngung->toarray())
                ->where('pcudn','>','0')
                ->get();

            //dd($model_canbo_tn);

            if (isset($inputs['linhvuc']) && $inputs['linhvuc'] != 'ALL') {
                //Dùng tìm kiếm các bộ nào phù hợp. Do lvhd là mảng nên pải lọc
                foreach ($m_cb as $canbo) {
                    $a_lv = explode(',', $canbo->lvhd);
                    if (in_array($inputs['linhvuc'], $a_lv) || $canbo->lvhd == null) {
                        $canbo->lvhd = $inputs['linhvuc'];
                    }
                }
                $m_cb = $m_cb->where('lvhd', $inputs['linhvuc']);
            }

            $model_congtac = dmphanloaict::all();
            $model_phanloai = dmphanloaicongtac_baohiem::where('madv',session('admin')->madv)->get();
            if(count($model_phanloai) == 0){
                //Nếu đơn vị chưa set thông tin bảo hiểm thì lấy mặc định
                $model_phanloai = dmphanloaicongtac::all();
            }
            $model_phucap = dmphucap_donvi::where('madv',session('admin')->madv)->get();
            if(count($model_phucap) == 0){
                //Nếu đơn vị chưa set thông tin bảo hiểm thì lấy mặc định
                $model_phucap = dmphucap::all();
            }

            //Tạo bảng lương
            bangluong::create($inputs);

            //Tính toán lương cho cán bộ nghỉ thai sản
            //$m_donvi = dmdonvi::where('madv',session('admin')->madv)->first();
            foreach ($model_canbo_tn as $cb) {
                $pcudn = $model_phucap->where('mapc', 'pcudn')->first();
                $cb->tencanbo = $cb->tencanbo . ' (nghỉ thai sản)';
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;

                $heso = 0;
                foreach (explode(',', $pcudn->congthuc) as $ct) {
                    $heso += $cb->$ct;
                }
                //$heso = $cb->heso + $cb->pccv + $cb->vuotkhung;

                $pl = getDbl($pcudn->phanloai);
                switch ($pl) {
                    case 0: {//hệ số
                        //giữ nguyên ko cần tính
                        break;
                    }
                    case 1: {//số tiền
                        $cb->pcudn += chkDbl($cb->pcudn);
                        break;
                    }
                    case 2: {//phần trăm
                        $cb->pcudn = $heso * $cb->pcudn / 100;
                        break;
                    }
                    default: {//trường hợp còn lại (ẩn,...)
                        $cb->pcudn = 0;
                        break;
                    }
                }

                //đơn vị y.c giữ hệ số, chức vụ trên bảng lương nhưng ko tính lương
                //$cb->vuotkhung = 0;
                //$cb->heso = 0;
                $cb->ttl = $inputs['luongcoban'] * $cb->pcudn * $inputs['phantramhuong'] / 100;
                $cb->luongtn = $cb->ttl;

                //tính toán lưu vào bảng phụ cấp theo lương
                $ct->mabl = $inputs['mabl'];
                $ct->macanbo = $cb->macanbo;
                $ct->tencanbo = $cb->tencanbo;
                $ct->maso = $pcudn->mapc;
                $ct->ten = $pcudn->tenpc;
                $ct->heso = $cb->pcudn;
                $ct->sotien = round($cb->ttl, 0);
                $a_kq = $pcudn->toarray();
                unset($a_kq['id']);
                bangluong_phucap::create($a_kq);

                //lưu vào db
                bangluong_ct::create($cb->toarray());
            }

            //Tính toán lương cho cán bộ
            //$a_col = getColPhuCap(); //lấy theo phụ cấp => tự tính phụ cấp vượt khung, hệ số lương
            $a_baohiem = getPhuCapNopBH();
            $model_chucvu = dmchucvucq::where('maphanloai',session('admin')->maphanloai)
                ->wherein('madv',['SA',session('admin')->madv])->get();

            foreach ($m_cb as $cb) {
                $cb->macongtac = null;
                $cb->bhxh = 0;
                $cb->bhyt = 0;
                $cb->kpcd = 0;
                $cb->bhtn = 0;
                $cb->bhxh_dv = 0;
                $cb->bhyt_dv = 0;
                $cb->kpcd_dv =  0;
                $cb->bhtn_dv =  0;

                $chucvu = $model_chucvu->where('macvcq',$cb->macvcq)->first();
                $congtac = $model_congtac->where('mact', $cb->mact)->first();
                if(count($congtac)>0){$cb->macongtac = $congtac->macongtac;}

                $phanloai = $model_phanloai->where('macongtac', $cb->macongtac)->first();

                if(count($phanloai) > 0) {
                    $cb->bhxh = floatval($phanloai->bhxh)/100;
                    $cb->bhyt = floatval($phanloai->bhyt)/100;
                    $cb->kpcd = floatval($phanloai->kpcd)/100;
                    $cb->bhtn = floatval($phanloai->bhtn)/100;
                    if ($cb->sunghiep == 'Công chức' || (count($chucvu) > 0 && $chucvu->ttdv == 1)) {
                        $cb->bhtn = 0;
                    }
                    $cb->bhxh_dv = floatval($phanloai->bhxh_dv)/100;
                    $cb->bhyt_dv = floatval($phanloai->bhyt_dv)/100;
                    $cb->kpcd_dv = floatval($phanloai->kpcd_dv)/100;
                    $cb->bhtn_dv = floatval($phanloai->bhtn_dv)/100;
                }

                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                //$hesobaohiem = $cb->heso + $cb->pccv + $cb->vuotkhung;
                //$cb->vuotkhung = ($cb->heso + $cb->pccv) * $cb->vuotkhung / 100;
                /*
                $cb->pccovu = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pccovu / 100;
                $cb->pctnn = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pctnn / 100;
                $cb->pclt = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pclt / 100; //phụ cấp phân loại xã
                $cb->pckn = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pckn / 100;
                $cb->pcudn = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pcudn / 100;
                */

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

                    foreach (explode(',', $ct->congthuc) as $cthuc) {
                        $heso += $cb->$cthuc;
                    }
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
                            $cb->$mapc = $heso * $cb->$mapc / 100;
                            $ths += $cb->$mapc;
                            $sotien = $cb->$mapc * $inputs['luongcoban'];
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
                        if(in_array($mapc,$a_baohiem)){
                            $ct->stbhxh = $ct->sotien * $cb->bhxh;
                            $ct->stbhyt = $ct->sotien * $cb->bhyt;
                            $ct->stkpcd = $ct->sotien * $cb->kpcd;
                            $ct->stbhtn = $ct->sotien * $cb->bhtn;
                            $ct->ttbh = $ct->stbhxh  + $ct->stbhyt + $ct->stkpcd +$ct->stbhtn;
                            $ct->stbhxh_dv = $ct->sotien * $cb->bhxh_dv;
                            $ct->stbhyt_dv = $ct->sotien * $cb->bhyt_dv;
                            $ct->stkpcd_dv = $ct->sotien * $cb->kpcd_dv;
                            $ct->stbhtn_dv = $ct->sotien * $cb->bhtn_dv;
                            $ct->ttbh_dv = $ct->stbhxh_dv  + $ct->stbhyt_dv + $ct->stkpcd_dv +$ct->stbhtn_dv;
                        }

                        $a_kq = $ct->toarray();
                        unset($a_kq['id']);
                        bangluong_phucap::create($a_kq);
                    }
                }
                $cb->tonghs = $ths;
                $cb->ttl = round($inputs['luongcoban'] * $ths * $inputs['phantramhuong'] / 100 + $tt);

                //$luongnopbaohiem = $inputs['luongcoban'] * ($cb->heso + $cb->pccv + $cb->vuotkhung + $cb->pctnn + $cb->pctnvk) * $inputs['phantramhuong'] / 100;

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
                bangluong_ct::create($cb->toarray());
            }

            /*
            //chạy ngay trong vòng for
            bangluong::create($inputs);
            $m_cb = unset_key($m_cb->toarray(), array('lvhd', 'macongtac', 'bhxh', 'bhyt', 'kpcd', 'bhtn', 'bhxh_dv', 'bhyt_dv', 'kpcd_dv', 'bhtn_dv', 'sunghiep'));
            bangluong_ct::insert($m_cb);
            $model_canbo_tl = unset_key($model_canbo_tl->toarray(), array('lvhd', 'macongtac', 'bhxh', 'bhyt', 'kpcd', 'bhtn', 'bhxh_dv', 'bhyt_dv', 'kpcd_dv', 'bhtn_dv', 'sunghiep', 'truylinhtungay', 'truylinhdenngay'));
            bangluong_ct::insert($model_canbo_tl);
            */
        }

        return redirect('/chuc_nang/bang_luong/maso=' . $inputs['mabl']);
    }

    //
    function store_truylinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_truylinh'];
            $inputs['thang'] = $inputs['thang_truylinh'];
            $inputs['nam'] = $inputs['nam_truylinh'];
            $inputs['noidung'] = $inputs['noidung_truylinh'];
            $inputs['manguonkp'] = $inputs['manguonkp_truylinh'];
            $inputs['luongcoban'] = $inputs['luongcoban_truylinh'];
            $inputs['phanloai'] = $inputs['phanloai_truylinh'];
            $inputs['nguoilap'] = $inputs['nguoilap_truylinh'];
            $inputs['ngaylap'] = $inputs['ngaylap_truylinh'];
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
                $ngaylap = $inputs['nam'] . '-' . $inputs['thang'] . '-01';
                //$ngaylap = Carbon::create($inputs['nam'],$inputs['thang'],'01');

                $model_canbo = hosotruylinh::where('madv', $madv)
                    ->select('stt', 'macanbo', 'tencanbo', 'msngbac', 'hesott', 'ngaytu', 'ngayden', 'maso')
                    ->where('ngaytu', '<', $ngaylap)
                    ->wherenull('mabl')
                    ->get();

                $model_hoso = hosocanbo::select('sunghiep', 'mact','macanbo', 'macvcq')->where('madv',session('admin')->madv)->get();
                $model_congtac = dmphanloaict::all();
                $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
                $model_chucvu = dmchucvucq::where('maphanloai',session('admin')->maphanloai)
                    ->wherein('madv',['SA',session('admin')->madv])->get();
                if (count($model_phanloai) == 0) {//Nếu đơn vị chưa set thông tin bảo hiểm thì lấy mặc định
                    $model_phanloai = dmphanloaicongtac::all();
                }

                //Tạo bảng lương
                bangluong::create($inputs);

                foreach ($model_canbo as $cb) {
                    $hoso = $model_hoso->where('macanbo',$cb->macanbo)->first();
                    $chucvu = $model_chucvu->where('macvcq',$cb->macvcq)->first();
                    //Gán tham số mặc định
                    $cb->mabl = $inputs['mabl'];
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
                    $cb->kpcd_dv =  0;
                    $cb->bhtn_dv =  0;

                    if(count($hoso) > 0) {
                        $cb->sunghiep = $hoso->sunghiep;
                        $cb->mact = $hoso->mact;
                        $cb->macvcq = $hoso->macvcq;
                    }

                    $congtac = $model_congtac->where('mact', $cb->mact)->first();
                    if(count($congtac)>0){$cb->macongtac = $congtac->macongtac;}

                    $phanloai = $model_phanloai->where('macongtac', $cb->macongtac)->first();
                    if(count($phanloai) > 0) {
                        $cb->bhxh = floatval($phanloai->bhxh);
                        $cb->bhyt = floatval($phanloai->bhyt);
                        $cb->kpcd = floatval($phanloai->kpcd);
                        $cb->bhtn = floatval($phanloai->bhtn);
                        $cb->bhxh_dv = floatval($phanloai->bhxh_dv);
                        $cb->bhyt_dv = floatval($phanloai->bhyt_dv);
                        $cb->kpcd_dv = floatval($phanloai->kpcd_dv);
                        $cb->bhtn_dv = floatval($phanloai->bhtn_dv);
                    }
                    //cán bộ lãnh đạo đơn vi + cán bộ công chức ko pai nộp bảo hiểm thất nghiệp
                    if ($cb->sunghiep == 'Công chức' || (count($chucvu) > 0 && $chucvu->ttdv == 1)) {
                        $cb->bhtn = 0;
                    }

                    $cb->ngayden = $ngaylap;
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
                     * */

                    $cb->thangtl = $denngay->month - $tungay->month + 12 * ($denngay->year - $tungay->year);
                    $cb->ttl = $inputs['luongcoban'] * $cb->hesott * $cb->thangtl;

                    $cb->stbhxh = ($cb->ttl * $cb->bhxh) / 100;
                    $cb->stbhyt = ($cb->ttl * $cb->bhyt) / 100;
                    $cb->stkpcd = ($cb->ttl * $cb->kpcd) / 100;
                    $cb->stbhtn = ($cb->ttl * $cb->bhtn) / 100;
                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->luongtn = $cb->ttl - $cb->ttbh;

                    $cb->stbhxh_dv = ($cb->ttl * $cb->bhxh_dv) / 100;
                    $cb->stbhyt_dv = ($cb->ttl * $cb->bhyt_dv) / 100;
                    $cb->stkpcd_dv = ($cb->ttl * $cb->kpcd_dv) / 100;
                    $cb->stbhtn_dv = ($cb->ttl * $cb->bhtn_dv) / 100;
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;

                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)

                    //lưu vào db
                    bangluong_ct::create($cb->toarray());
                }

                $model_canbo = $model_canbo->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['maso'])
                        ->all();
                });
                hosotruylinh::wherein('maso', $model_canbo->toarray())->update(['ngayden' => $ngaylap, 'mabl' => $inputs['mabl']]);
            }

            return redirect('/chuc_nang/bang_luong/maso=' . $inputs['mabl']);
        } else
            return view('errors.notlogin');
    }

    function show($mabl){
        if (Session::has('admin')) {
            $model=bangluong_ct::where('mabl',$mabl)->get();

            $m_bl=bangluong::select('thang','nam','mabl')->where('mabl',$mabl)->first();
            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach($model as $hs){
                //$hs->tencv=$hs->macvcq;
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            return view('manage.bangluong.bangluong')
                ->with('furl','/chuc_nang/bang_luong/')
                ->with('model',$model)

                ->with('m_bl',$m_bl)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = bangluong::find($id);
            if(count($model) > 0 && $model->phanloai == 'TRUYLINH') {
                hosotruylinh::where('mabl', $model->mabl)
                    ->update(['mabl' => null, 'ngayden' => null]);
            }
            bangluong_ct::where('mabl', $model->mabl)->delete();
            bangluong_phucap::where('mabl', $model->mabl)->delete();
            $model->delete();
            return redirect('/chuc_nang/bang_luong/danh_sach');
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
        $model = bangluong::where('mabl',$inputs['mabl'])->first();
        die($model);
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong_ct::findorfail($inputs['maso']);
            $model_canbo = hosocanbo::where('macanbo',$model->macanbo)->first();
            $model_bangluong = bangluong::where('mabl',$model->mabl)->first();

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

            $mact= $model->mact;
            $model_baohiem = dmphanloaicongtac_baohiem::where('macongtac',function($qr)use($mact){
                $qr->select('macongtac')->from('dmphanloaict')->where('mact',$mact)->get();
            })->where('madv',session('admin')->madv)->first();
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
            $a_donvi = dmdonvi::where('madv',session('admin')->madv)->first()->toarray();

            if($model_bangluong->phanloai == 'TRUYLINH'){
                $model_truylinh = hosotruylinh::where('macanbo',$model->macanbo)->where('mabl',$model->mabl)->first();
                $model->ngaytu = $model_truylinh->ngaytu;
                $model->ngayden = $model_truylinh->ngayden;
                return view('manage.bangluong.chitiet_truylinh')
                    ->with('furl','/chuc_nang/bang_luong/')
                    ->with('model',$model)
                    ->with('a_phucap',getColPhuCap())
                    ->with('a_donvi',$a_donvi)
                    //->with('model_baohiem',$model_baohiem)
                    ->with('pageTitle','Chi tiết bảng lương');
            }else{
                return view('manage.bangluong.chitiet')
                    ->with('furl','/chuc_nang/bang_luong/')
                    ->with('model',$model)
                    ->with('a_phucap',getColPhuCap())
                    ->with('a_donvi',$a_donvi)
                    //->with('model_baohiem',$model_baohiem)
                    ->with('pageTitle','Chi tiết bảng lương');
            }

        } else
            return view('errors.notlogin');
    }

    function updatect(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();

            $model=bangluong_ct::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
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
            return redirect('/chuc_nang/bang_luong/maso='.$model->mabl);


        } else
            return view('errors.notlogin');
    }

    function updatect_truylinh(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();

            //Lưu bảng truy linh
            $model_truylinh = hosotruylinh::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
            if(count($model_truylinh) > 0){
                $model_truylinh->ngaytu = $inputs['ngaytu'];
                $model_truylinh->ngayden = $inputs['ngayden'];
                $model_truylinh->hesott = $inputs['hesott'];
                $model_truylinh->save();
            }
            //lưu bảng lương chi tiết

            $model=bangluong_ct::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
            $inputs['hesott'] = chkDbl($inputs['hesott']);
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
            return redirect('/chuc_nang/bang_luong/maso='.$model->mabl);


        } else
            return view('errors.notlogin');
    }

    public function inbangluong($mabl){
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
            $m_bl = bangluong::select('thang','nam','mabl','madv','luongcoban')->where('mabl',$mabl)->first();
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
                'thang'=>$m_bl->thang,
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
            $a_phucapbc = getColPhuCap_BaoCao();
            $a_phucap = array();
            $col = 0;
            foreach($a_phucapbc as $key=>$val){
                if($m_dv->$key <3) {
                    $a_phucap[$key] = $val;
                    $col++;
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

    function inbaohiem($mabl){
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->maxa)->first();

            $model=bangluong_ct::where('mabl',$mabl)->get();

            $m_bl=bangluong::select('thang','nam')->where('mabl',$mabl)->first();
            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach($model as $hs){
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam);
            return view('reports.bangluong.maubaohiem')
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
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
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);
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

    public function printf_mau02(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau2'];
            $inputs['mapb'] = $inputs['mapb_mau2'];
            $inputs['macvcq'] = $inputs['macvcq_mau2'];
            $inputs['mact'] = $inputs['mact_mau2'];
            $model = $this->getBangLuong($inputs,1);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);
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

    public function printf_mau03(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau3'];
            $inputs['mapb'] = $inputs['mapb_mau3'];
            $inputs['macvcq'] = $inputs['macvcq_mau3'];
            $inputs['mact'] = $inputs['mact_mau3'];
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);
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

    public function printf_mau04(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau4'];
            $inputs['mapb'] = $inputs['mapb_mau4'];
            $inputs['macvcq'] = $inputs['macvcq_mau4'];
            $inputs['mact'] = $inputs['mact_mau4'];
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);
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

    public function printf_mau05(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau5'];
            $inputs['mapb'] = $inputs['mapb_mau5'];
            $inputs['macvcq'] = $inputs['macvcq_mau5'];
            $inputs['mact'] = $inputs['mact_mau5'];
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);


            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);
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

    public function printf_mau06(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau6'];
            $inputs['mapb'] = $inputs['mapb_mau6'];
            $inputs['macvcq'] = $inputs['macvcq_mau6'];
            $inputs['mact'] = $inputs['mact_mau6'];
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $model_phucap = bangluong_phucap::where('mabl', $mabl)->get();
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $model_tm = dmtieumuc_default::all();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap);
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

    public function printf_mauds(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mauds'];
            $inputs['mapb'] = $inputs['mapb_mauds'];
            $inputs['macvcq'] = $inputs['macvcq_mauds'];
            $inputs['mact'] = $inputs['mact_mauds'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
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

    function printf_maubh(Request $request){
        if (Session::has('admin')) {

            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_maubh'];
            $inputs['mapb'] = $inputs['mapb_maubh'];
            $inputs['macvcq'] = $inputs['macvcq_maubh'];
            $inputs['mact'] = $inputs['mact_maubh'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap')->where('mabl',$mabl)->first();
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
        $a_phucapbc = getColPhuCap_BaoCao();
        $a_phucap = array();
        $col = 0;
        foreach ($a_phucapbc as $key => $val) {
            if ($m_dv->$key < 3) {
                $a_phucap[$key] = $val;
                $col++;
            }
        }
        $a_col = getColTongHop();
        $luongcb = $m_bl->luongcoban;

        foreach ($model as $hs) {
            $hs->tencv = isset($dmchucvucq[$hs->macvcq]) ? $dmchucvucq[$hs->macvcq] : '';
            $hs->sunghiep = isset($sunghiep[$hs->macanbo]) ? $sunghiep[$hs->macanbo] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            if ($phanloai == 1) {
                $ths = 0;
                foreach ($a_col as $col) {
                    $pl = getDbl($m_dv->$col);
                    switch ($pl) {
                        case 1: {//số tiền
                            //giữ nguyên ko pai làm gì
                            break;
                        }
                        default: {//trường hợp còn lại (ẩn,...)
                            $hs->$col = $hs->$col * $luongcb;
                            break;
                        }
                    }
                    $ths += $hs->$col;
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
    //bỏ
    public function return_html($result, $model)
    {
        $result['message'] = '<div class="col-md-12" id="thongtinphucap">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_3">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr>';
        $result['message'] .= '<th width="5%" style="text-align: center">STT</th>';
        $result['message'] .= '<th class="text-center">Mã số</th>';
        $result['message'] .= '<th class="text-center">Tên phụ cấp</th>';
        $result['message'] .= '<th class="text-center">Hệ số</th>';
        $result['message'] .= '<th class="text-center">Nộp bảo hiểm</th>';
        $result['message'] .= '<th class="text-center">Thao tác</th>';
        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';

        $stt=1;
        $result['message'] .= '<tbody>';
        if (count($model) > 0) {
            foreach ($model as $key => $ct) {
                $result['message'] .= '<tr>';
                $result['message'] .= '<td style="text-align: center">' . $stt++ . '</td>';
                $result['message'] .= '<td style="text-align: right">' . $ct->mapc . '</td>';
                $result['message'] .= '<td style="text-align: right">' . $ct->tenpc . '</td>';
                $result['message'] .= '<td style="text-align: right">' . $ct->hesopc . '</td>';
                $result['message'] .= '<td style="text-align: right">' . ($ct->baohiem==1?'Có nộp bảo hiểm':'Không nộp bảo hiểm') . '</td>';
                $result['message'] .= '<td>
                                    <button type="button" onclick="edit_phucap('.$ct->id.')" class="btn btn-info btn-xs mbs">
                                        <i class="fa fa-edit"></i>&nbsp;Chỉnh sửa</button>
                                    <button type="button" onclick="del_phucap('.$ct->id.')" class="btn btn-danger btn-xs mbs" data-target="#modal-delete" data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
                </td>';
                $result['message'] .= '</tr>';
            }
            $result['message'] .= '</tbody>';
            $result['message'] .= '</table>';
            $result['message'] .= '</div>';
            $result['message'] .= '</div>';
            $result['status'] = 'success';
            return $result;
        }
        return $result;
    }
    //bỏ
    public function importexcel(Request $request)
    {
        if (Session::has('admin')) {
            //dd($request);
            $madv = session('admin')->madv;

            $inputs = $request->all();
            $inputs['mabl'] = session('admin')->madv . '_' . getdate()[0];
            $inputs['madv'] = session('admin')->madv;
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();

            $bd = $inputs['tudong'];
            $sd = 9;
            //$sd=$inputs['sodong'];

            //Thêm mới bảng lương
            bangluong::create($inputs);

            $sheet = isset($inputs['sheet']) ? $inputs['sheet'] - 1 : 0;
            $sheet = $sheet < 0 ? 0 : $sheet;
            $filename = $madv . date('YmdHis');
            $request->file('fexcel')->move(public_path() . '/data/uploads/excels/', $filename . '.xls');
            $path = public_path() . '/data/uploads/excels/' . $filename . '.xls';

            $data = [];
            Excel::load($path, function ($reader) use (&$data, $bd, $sd, $sheet) {
                //$reader->getSheet(0): là đối tượng -> dữ nguyên các cột
                //$sheet: là đã tự động lấy dòng đầu tiên làm cột để nhận dữ liệu
                $obj = $reader->getExcel();
                $sheet = $obj->getSheet($sheet);
                //$sheet = $obj->getSheet(0);
                $Row = $sheet->getHighestRow();
                $Row = $sd + $bd > $Row ? $Row : ($sd + $bd);
                $Col = $sheet->getHighestColumn();

                for ($r = $bd; $r <= $Row; $r++) {
                    $rowData = $sheet->rangeToArray('A' . $r . ':' . $Col . $r, NULL, TRUE, FALSE);
                    $data[] = $rowData[0];
                }
            });

            foreach ($inputs as $key => $val) {
                $ma = ord($val);
                if ($ma >= 65 && $ma <= 90) {
                    $inputs[$key] = $ma - 65;
                }
                if ($ma >= 97 && $ma <= 122) {
                    $inputs[$key] = $ma - 97;
                }
            }

            //Thêm mới bảng lương chi tiết
            //dd($data);
            $gnr = getGeneralConfigs();
            foreach ($data as $row) {
                $model = new bangluong_ct();
                $model->mabl = $inputs['mabl'];
                //$model->macanbo = $cb->macanbo;
                $model->tencanbo = $row[2];
                $model->macvcq = $row[3];
                //$model->mapb = $cb->mapb;
                $model->msngbac = $row[4];

                $model->heso = $row[5];
                $model->pck = $row[7];
                $model->pccv = $row[6];
                $model->tonghs = $row[8];
                $model->ttl = $row[9];

                $model->stbhxh = $row[13];
                $model->stbhyt = $row[14];
                $model->stkpcd = $row[15];
                $model->stbhtn = $row[16];

                $model->giaml = getDbl($row[10]);
                $model->bhct = getDbl($row[11]);
                $model->ttbh = $row[17];
                $model->luongtn = $row[18];

                $model->stbhxh_dv = $model->ttl * floatval($gnr['bhxh_dv']) / 100;
                $model->stbhyt_dv = $model->ttl * floatval($gnr['bhyt_dv']) / 100;
                $model->stkpcd_dv = $model->ttl * floatval($gnr['kpcd_dv']) / 100;
                $model->stbhtn_dv = $model->ttl * floatval($gnr['bhtn_dv']) / 100;
                $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

                $model->save();
            }

            //$inputs=$request->all();//do sau khi chạy insert chi tiết thì  $inputs bị set lại dữ liệu

            File::Delete($path);

            return redirect('/chuc_nang/bang_luong/maso=' . $inputs['mabl']);
        } else
            return view('errors.notlogin');
    }
    //bỏ
    public function getDownload(){
        $file = public_path() . '/data/download/MauC02ahd.xlsx';
        $headers = array(
            'Content-Type: application/xls',
        );
        return Response::download($file, 'MauC02ahd.xlsx', $headers);
    }

    //<editor-fold desc="Tra cứu">
    function search(){
        if (Session::has('admin')) {
            $model_dv=dmdonvi::where('madv',session('admin')->madv)->get();
            if(session('admin')->quanlynhom){
                $model_dv=dmdonvi::where('macqcq',session('admin')->madv)->get();
            }

            if(session('admin')->quanlykhuvuc){
                $model_dv=dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            }

            return view('search.chiluong.index')
                ->with('model_dv', $model_dv)
                ->with('pageTitle','Tra cứu chi trả lương');
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
