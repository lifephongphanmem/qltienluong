<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluongphucap;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap;
use App\hosocanbo;
use App\hosotamngungtheodoi;
use App\ngachbac;
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
    function index(){
        if (Session::has('admin')) {
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc=array_column(dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
            $m_nguonkp=array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');

            $model = bangluong::where('madv',session('admin')->maxa)->get();
            foreach($model as $bl){
                $bl->tennguonkp =isset($m_nguonkp[$bl->manguonkp]) ? $m_nguonkp[$bl->manguonkp]:'';
            }
            $model = $model->sortby('nam')->sortby('thang');
            return view('manage.bangluong.index')
                ->with('furl','/chuc_nang/bang_luong/')
                ->with('furl_ajax','/ajax/bang_luong/')
                ->with('model',$model)
                ->with('luongcb',getGeneralConfigs()['luongcb'])
                ->with('m_linhvuc',$m_linhvuc)
                ->with('m_nguonkp',$m_nguonkp)
                ->with('pageTitle','Danh sách bảng lương');
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
        } else {
            //insert
            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();

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
            $model_canbo_tn = hosocanbo::select('stt','macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'hesopc', 'hesott', 'vuotkhung', 'pcudn', DB::raw("'" . $inputs['mabl'] . "' as mabl"))
                ->wherein('macanbo', $m_tamngung->toarray())
                ->where('pcudn','>','0')
                ->get();

            //dd($model_canbo_tn);
            //xem xét tạo bảng lương truy lĩnh riêng
            $model_canbo_tl = hosocanbo::where('madv', $madv)
                ->select('stt','macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'hesott', 'truylinhtungay', 'truylinhdenngay', DB::raw("'" . $inputs['mabl'] . "' as mabl"))
                ->where('hesott', '>', 0)
                ->where('theodoi', '1')
                ->get();

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
            $a_truythu = array();

            //Tạo bảng lương
            bangluong::create($inputs);

            //Tính toán lương cho cán bộ truy lĩnh
            //Xem lại có cần truy lĩnh các loại phụ cấp ko
            //ngày công mặc định là 24 ngày
            foreach ($model_canbo_tl as $cb) {

                $tungay = new Carbon($cb->truylinhtungay);
                $denngay = new Carbon($cb->truylinhdenngay);

                //tính toán sau
                $ngaycuoi = (new Carbon($cb->truylinhdenngay))->lastOfMonth()->day;
                $ngay_tu = $tungay->day;
                $thang_tu = $tungay->month;
                $nam_tu = $tungay->year;
                $ngay_den = $denngay->day;
                $thang_den = $denngay->month;
                $nam_den = $denngay->year;

                $thang_den += 12 * ($nam_den - $nam_tu);

                $thang_tl = $thang_den - $thang_tu > 0 ? ($thang_den - $thang_tu) : 1;

                //nếu tháng đến = tháng từ => +1
                $cb->hesott = $cb->hesott >= 1 ? ($cb->hesott / 100) : $cb->hesott;

                $congtac = $model_congtac->where('mact', $cb->mact)->first();
                $cb->macongtac = isset($congtac) ? $congtac->macongtac : null;

                $phanloai = $model_phanloai->where('macongtac', $cb->macongtac)
                    ->where('maphanloai', session('admin')->maphanloai)->first();
                $cb->bhxh = isset($phanloai) ? $phanloai->bhxh : 0;
                $cb->bhyt = isset($phanloai) ? $phanloai->bhyt : 0;
                $cb->kpcd = isset($phanloai) ? $phanloai->kpcd : 0;
                $cb->bhtn = isset($phanloai) ? $phanloai->bhtn : 0;
                //cán bộ lãnh đạo đơn vi + cán bộ công chức ko pai nộp bảo hiểm thất nghiệp
                if ($cb->sunghiep == 'Công chức') {
                    $cb->bhtn = 0;
                }
                $cb->bhxh_dv = isset($phanloai) ? $phanloai->bhxh_dv : 0;
                $cb->bhyt_dv = isset($phanloai) ? $phanloai->bhyt_dv : 0;
                $cb->kpcd_dv = isset($phanloai) ? $phanloai->kpcd_dv : 0;
                $cb->bhtn_dv = isset($phanloai) ? $phanloai->bhtn_dv : 0;

                $cb->tencanbo = $cb->tencanbo . ' (truy lĩnh lương)';

                $cb->ttl = $inputs['luongcoban'] * $cb->hesott * $thang_tl * $inputs['phantramhuong'] / 100;
                $luongnopbaohiem = $inputs['luongcoban'] * $cb->hesott * $inputs['phantramhuong'] / 100;

                $cb->stbhxh = $luongnopbaohiem * floatval($cb->bhxh) / 100;
                $cb->stbhyt = $luongnopbaohiem * floatval($cb->bhyt) / 100;
                $cb->stkpcd = $luongnopbaohiem * floatval($cb->kpcd) / 100;
                $cb->stbhtn = $luongnopbaohiem * floatval($cb->bhtn) / 100;
                $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                $cb->luongtn = $cb->ttl - $cb->ttbh;

                $cb->stbhxh_dv = $luongnopbaohiem * floatval($cb->bhxh_dv) / 100;
                $cb->stbhyt_dv = $luongnopbaohiem * floatval($cb->bhyt_dv) / 100;
                $cb->stkpcd_dv = $luongnopbaohiem * floatval($cb->kpcd_dv) / 100;
                $cb->stbhtn_dv = $luongnopbaohiem * floatval($cb->bhtn_dv) / 100;
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;

                //lưu vào db
                bangluong_ct::create($cb->toarray());
            }

            //Tính toán lương cho cán bộ nghỉ thai sản
            $m_donvi = dmdonvi::where('madv',session('admin')->madv)->first();

            foreach ($model_canbo_tn as $cb) {
                $cb->tencanbo = $cb->tencanbo . ' (nghỉ thai sản)';
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $heso = $cb->heso + $cb->pccv + $cb->vuotkhung;

                $pl = getDbl($m_donvi->pcudn);
                switch($pl){
                    case 0:{//hệ số
                        //giữ nguyên ko cần tính
                        break;
                    }
                    case 1:{//số tiền
                        $cb->pcudn += chkDbl($cb->pcudn);
                        break;
                    }
                    case 2:{//phần trăm
                        $cb->pcudn = $heso * $cb->pcudn / 100;
                        break;
                    }
                    default:{//trường hợp còn lại (ẩn,...)
                        $cb->pcudn = 0;
                        break;
                    }
                }

                $cb->vuotkhung = 0;
                $cb->heso = 0;
                $cb->ttl = $inputs['luongcoban'] * $cb->pcudn * $inputs['phantramhuong'] / 100;
                $cb->luongtn = $cb->ttl;

                //lưu vào db
                bangluong_ct::create($cb->toarray());
            }

            //dd($model_canbo_tl);
            //Tính toán lương cho cán bộ
            // hệ số truy lĩnh > 1 =>phân
            $a_col = getColPhuCap(); //lấy theo phụ cấp => tự tính phụ cấp vượt khung, hệ số lương

            foreach ($m_cb as $cb) {

                $congtac = $model_congtac->where('mact', $cb->mact)->first();
                $cb->macongtac = isset($congtac) ? $congtac->macongtac : null;
                $phanloai = $model_phanloai->where('macongtac', $cb->macongtac)->first();
                $cb->bhxh = isset($phanloai) ? $phanloai->bhxh : 0;
                $cb->bhyt = isset($phanloai) ? $phanloai->bhyt : 0;
                $cb->kpcd = isset($phanloai) ? $phanloai->kpcd : 0;
                $cb->bhtn = isset($phanloai) ? $phanloai->bhtn : 0;
                if ($cb->sunghiep == 'Công chức') {
                    $cb->bhtn = 0;
                }
                $cb->bhxh_dv = isset($phanloai) ? $phanloai->bhxh_dv : 0;
                $cb->bhyt_dv = isset($phanloai) ? $phanloai->bhyt_dv : 0;
                $cb->kpcd_dv = isset($phanloai) ? $phanloai->kpcd_dv : 0;
                $cb->bhtn_dv = isset($phanloai) ? $phanloai->bhtn_dv : 0;

                $a_truythu[] = $cb->macanbo;
                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $hesobaohiem = $cb->heso + $cb->pccv + $cb->vuotkhung;
                //$cb->vuotkhung = ($cb->heso + $cb->pccv) * $cb->vuotkhung / 100;
                /*
                $cb->pccovu = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pccovu / 100;
                $cb->pctnn = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pctnn / 100;
                $cb->pclt = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pclt / 100; //phụ cấp phân loại xã
                $cb->pckn = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pckn / 100;
                $cb->pcudn = ($cb->heso + $cb->pccv + $cb->vuotkhung) * $cb->pcudn / 100;
                */
                $cb->hesott = 0;//set = 0 để sau này tổng hợp lọc các cán bộ này ra để tính

                //Do đơn vị nhập cả hệ số lẫn số tiền
                //>500 => số tiền  còn lại là hệ số
                $tt = 0;
                $ths = $cb->heso + $cb->vuotkhung;
                foreach ($a_col as $col=>$val) {
                    $pl = getDbl($m_donvi->$col);
                    switch($pl){
                        case 0:{//hệ số
                            $ths += $cb->$col;
                            break;
                        }
                        case 1:{//số tiền
                            $tt += chkDbl($cb->$col);
                            break;
                        }
                        case 2:{//phần trăm
                            if($col == 'pctnvk'){
                                $cb->$col = ($cb->heso + $cb->vuotkhung) * $cb->$col / 100; //tính riêng
                            }else{
                                $cb->$col = $hesobaohiem * $cb->$col / 100;
                            }
                            $ths += $cb->$col;
                            break;
                        }
                        default:{//trường hợp còn lại (ẩn,...)
                            $cb->$col = 0;
                            break;
                        }
                    }
                }

                $cb->tonghs = $ths;
                $cb->ttl = round($inputs['luongcoban'] * $ths * $inputs['phantramhuong'] / 100
                    + $tt);
                $luongnopbaohiem = $inputs['luongcoban'] * ($cb->heso + $cb->pccv + $cb->vuotkhung + $cb->pctnn + $cb->pctnvk) * $inputs['phantramhuong'] / 100;

                $cb->stbhxh = $luongnopbaohiem * floatval($cb->bhxh) / 100;
                $cb->stbhyt = $luongnopbaohiem * floatval($cb->bhyt) / 100;
                $cb->stkpcd = $luongnopbaohiem * floatval($cb->kpcd) / 100;
                $cb->stbhtn = $luongnopbaohiem * floatval($cb->bhtn) / 100;
                $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                $cb->luongtn = $cb->ttl - $cb->ttbh;

                $cb->stbhxh_dv = $luongnopbaohiem * floatval($cb->bhxh_dv) / 100;
                $cb->stbhyt_dv = $luongnopbaohiem * floatval($cb->bhyt_dv) / 100;
                $cb->stkpcd_dv = $luongnopbaohiem * floatval($cb->kpcd_dv) / 100;
                $cb->stbhtn_dv = $luongnopbaohiem * floatval($cb->bhtn_dv) / 100;
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;


                //lưu vào db
                bangluong_ct::create($cb->toarray());
            }

            //dd($m_cb);
            $model_canbo = $model_canbo_tl->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macanbo'])
                    ->all();
            });

            hosocanbo::wherein('macanbo', $model_canbo->toarray())->update(['hesott' => 0, 'truylinhtungay' => NULL, 'truylinhdenngay' => NULL]);
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

    function show_old($mabl){
        if (Session::has('admin')) {
            $model=DB::table('bangluong_ct')
                ->join('dmchucvucq', 'bangluong_ct.macvcq', '=', 'dmchucvucq.macvcq')
                ->select('bangluong_ct.*', 'dmchucvucq.sapxep')
                ->where('bangluong_ct.mabl',$mabl)
                ->orderby('dmchucvucq.sapxep')
                ->get();
            //$model=$model->orderby('dmchucvucq.sapxep');

            $m_bl=bangluong::select('thang','nam','mabl')->where('mabl',$mabl)->first();
            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach($model as $hs){
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

            $m_nb = ngachluong::where('msngbac',$model->msngbac)->first();
            //$model_bl = bangluong::where('mabl',$model->mabl)->first();
            $model->tennb = isset($m_nb)? $m_nb->tenngachluong:'';
            $model->tencanbo = Str::upper($model->tencanbo);
            $model->luongcoban = bangluong::where('mabl',$model->mabl)->first()->luongcoban;
            $mact= $model->mact;
            $model_baohiem = dmphanloaicongtac_baohiem::where('macongtac',function($qr)use($mact){
                $qr->select('macongtac')->from('dmphanloaict')->where('mact',$mact)->get();
            })->first();
            $model->sunghiep = $model_canbo->sunghiep;
            if(count($model_baohiem)>0){
                $model->bhxh = $model_baohiem->bhxh;
                $model->bhyt = $model_baohiem->bhyt;
                $model->bhtn = $model_baohiem->bhtn;
                if($model->sunghiep == 'Công chức'){
                    $model->bhtn = 0;
                }
                $model->kpcd = $model_baohiem->kpcd;
                $model->bhxh_dv = $model_baohiem->bhxh_dv;
                $model->bhyt_dv = $model_baohiem->bhyt_dv;
                $model->bhtn_dv = $model_baohiem->bhtn_dv;
                $model->kpcd_dv = $model_baohiem->kpcd_dv;
            }else{
                $model->bhxh = 0;
                $model->bhyt = 0;
                $model->bhtn = 0;
                $model->kpcd = 0;
                $model->bhxh_dv = 0;
                $model->bhyt_dv = 0;
                $model->bhtn_dv = 0;
                $model->kpcd_dv = 0;
            }
            //dd($model);
            $a_donvi = dmdonvi::where('madv',session('admin')->madv)->first()->toarray();
            return view('manage.bangluong.chitiet')
                ->with('furl','/chuc_nang/bang_luong/')
                ->with('model',$model)
                ->with('a_phucap',getColPhuCap())
                ->with('a_donvi',$a_donvi)
                //->with('model_baohiem',$model_baohiem)
                ->with('pageTitle','Chi tiết bảng lương');
        } else
            return view('errors.notlogin');
    }

    function updatect(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();

            $model=bangluong_ct::where('macanbo',$inputs['macanbo'])->where('mabl',$inputs['mabl'])->first();
            $inputs['heso'] = chkDbl($inputs['heso']);
            $inputs['hesopc'] = chkDbl($inputs['hesopc']);
            $inputs['hesott'] = chkDbl($inputs['hesott']);
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
            $inputs['pctnvk'] = chkDbl($inputs['pctnvk']);
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
            $dmchucvucq=array_column(dmchucvucq::all('tencv', 'macvcq')->toArray(),'tencv', 'macvcq');
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
            $dmchucvucq=array_column(dmchucvucq::all('tencv', 'macvcq')->toArray(),'tencv', 'macvcq');
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
            $dmchucvucq=array_column(dmchucvucq::all('tencv', 'macvcq')->toArray(),'tencv', 'macvcq');
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

    function phucap(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model=bangluongphucap::where('macanbo',$inputs['macanbo'])
            ->where('mapc',$inputs['mapc'])
            ->where('mabl',$inputs['mabl'])->first();

        if(count($model)>0){
            $model->hesopc = $inputs['hesopc'];
            $model->baohiem =$inputs['baohiem'];
            $model->save();
        }else {
            $model = new bangluongphucap();
            $model->macanbo = $inputs['macanbo'];
            $model->mapc = $inputs['mapc'];
            $model->mabl = $inputs['mabl'];
            $model->hesopc = $inputs['hesopc'];
            $model->baohiem =$inputs['baohiem'];
            $model->save();
        }

        $model =bangluongphucap::join('dmphucap','bangluongphucap.mapc','dmphucap.mapc')
            ->select('bangluongphucap.*','dmphucap.tenpc')
            ->where('bangluongphucap.macanbo',$inputs['macanbo'])
            ->where('bangluongphucap.mabl',$inputs['mabl'])
            ->get();

        $result = $this->return_html($result, $model);

        die(json_encode($result));
    }

    function detroys_phucap(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = bangluongphucap::findOrFail($inputs['id']);
        $model->delete();
        $model =bangluongphucap::join('dmphucap','bangluongphucap.mapc','dmphucap.mapc')
            ->select('bangluongphucap.*','dmphucap.tenpc')
            ->where('bangluongphucap.macanbo',$inputs['macanbo'])
            ->where('bangluongphucap.mabl',$inputs['mabl'])
            ->get();
        $result = $this->return_html($result, $model);

        die(json_encode($result));
    }

    function get_phucap(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = bangluongphucap::find($inputs['id']);
        die($model);
    }

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

    public function importexcel(Request $request){
        if(Session::has('admin')){
            //dd($request);
            $madv=session('admin')->madv;

            $inputs=$request->all();
            $inputs['mabl']=session('admin')->madv .'_'.getdate()[0];
            $inputs['madv']=session('admin')->madv;
            $inputs['nguoilap']=session('admin')->name;
            $inputs['ngaylap']=Carbon::now()->toDateTimeString();

            $bd=$inputs['tudong'];
            $sd=9;
            //$sd=$inputs['sodong'];

            //Thêm mới bảng lương
            bangluong::create($inputs);

            $sheet=isset($inputs['sheet'])?$inputs['sheet']-1:0;
            $sheet=$sheet<0?0:$sheet;
            $filename = $madv . date('YmdHis');
            $request->file('fexcel')->move(public_path() . '/data/uploads/excels/', $filename . '.xls');
            $path = public_path() . '/data/uploads/excels/' . $filename . '.xls';

            $data = [];
            Excel::load($path, function($reader) use (&$data,$bd,$sd,$sheet) {
                //$reader->getSheet(0): là đối tượng -> dữ nguyên các cột
                //$sheet: là đã tự động lấy dòng đầu tiên làm cột để nhận dữ liệu
                $obj = $reader->getExcel();
                $sheet = $obj->getSheet($sheet);
                //$sheet = $obj->getSheet(0);
                $Row = $sheet->getHighestRow();
                $Row = $sd+$bd > $Row ? $Row : ($sd+$bd);
                $Col = $sheet->getHighestColumn();

                for ($r = $bd; $r <= $Row; $r++)
                {
                    $rowData = $sheet->rangeToArray('A' . $r . ':' . $Col . $r, NULL, TRUE, FALSE);
                    $data[] = $rowData[0];
                }
            });

            foreach($inputs as $key=>$val) {
                $ma=ord($val);
                if($ma>=65 && $ma<=90){
                    $inputs[$key]=$ma-65;
                }
                if($ma>=97 && $ma<=122){
                    $inputs[$key]=$ma-97;
                }
            }

            //Thêm mới bảng lương chi tiết
            //dd($data);
            $gnr=getGeneralConfigs();
            foreach ($data as $row) {

                $model = new bangluong_ct();
                $model->mabl = $inputs['mabl'];
                //$model->macanbo = $cb->macanbo;
                $model->tencanbo  = $row[2];
                $model->macvcq = $row[3];
                //$model->mapb = $cb->mapb;
                $model->msngbac = $row[4];


                $model->heso=$row[5];
                $model->pck=$row[7];
                $model->pccv=$row[6];
                $model->tonghs=$row[8];
                $model->ttl=$row[9];

                $model->stbhxh=$row[13];
                $model->stbhyt=$row[14];
                $model->stkpcd=$row[15];
                $model->stbhtn=$row[16];

                $model->giaml=getDbl($row[10]);
                $model->bhct=getDbl($row[11]);
                $model->ttbh=$row[17];
                $model->luongtn=$row[18];


                $model->stbhxh_dv=$model->ttl*floatval($gnr['bhxh_dv'])/100;
                $model->stbhyt_dv=$model->ttl*floatval($gnr['bhyt_dv'])/100;
                $model->stkpcd_dv=$model->ttl*floatval($gnr['kpcd_dv'])/100;
                $model->stbhtn_dv=$model->ttl*floatval($gnr['bhtn_dv'])/100;
                $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;


                $model->save();
            }

            //$inputs=$request->all();//do sau khi chạy insert chi tiết thì  $inputs bị set lại dữ liệu

            File::Delete($path);

            return redirect('/chuc_nang/bang_luong/maso='.$inputs['mabl']);
        }else
            return view('errors.notlogin');
    }

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
