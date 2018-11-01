<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluong_phucap;
use App\dmdiabandbkk;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use App\hosocanbo;
use App\nguonkinhphi_dinhmuc;
use App\nguonkinhphi_dinhmuc_ct;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_donviController extends Controller
{
    function index(Request $requests)
    {
        if (Session::has('admin')) {
            //text-danger; text-warning; text-success
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
            /*
            $a_trangthai=array('CHUALUONG'=>'Chưa tạo bảng lương','CHUATAO'=>'Chưa tổng hợp dữ liệu'
                ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            */
            $a_trangthai = getStatus();
            $inputs = $requests->all();
            $model = tonghopluong_donvi::where('madv', session('admin')->madv)->get();
            $model_bangluong = bangluong::where('madv', session('admin')->madv)->get();
            for ($i = 0; $i < count($a_data); $i++) {
                $a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $a_data[$i]['ngaygui'] = '';
                $a_data[$i]['macqcq'] = '';
                $a_data[$i]['tendvcq'] = '';
                $tonghop = $model->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam'])->first();
                $bangluong = $model_bangluong->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam']);
                if (count($bangluong) > 0) {
                    $a_data[$i]['bangluong'] = 'ok';
                    if (count($tonghop) > 0) {
                        $a_data[$i]['noidung'] = $tonghop->noidung;
                        $a_data[$i]['mathdv'] = $tonghop->mathdv;
                        $a_data[$i]['trangthai'] = $tonghop->trangthai;
                        $a_data[$i]['ngaygui'] = $tonghop->ngaygui;
                        $a_data[$i]['macqcq'] = $tonghop->macqcq;
                        $a_data[$i]['tendvcq'] = getTenDV($a_data[$i]['macqcq']);
                        if($a_data[$i]['tendvcq'] == ''){
                            $a_data[$i]['tendvcq'] = $a_data[$i]['macqcq']; //trường hợp đơn vị gửi nhưng ko pải đơn vị trong hệ thống => đưa mã để kiểm tra
                        }
                    } else {
                        $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $a_data[$i]['thang'] . '/' . $inputs['nam'];
                        $a_data[$i]['trangthai'] = 'CHUATAO';
                    }
                } else {
                    $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $a_data[$i]['thang'] . '/' . $inputs['nam'];
                    $a_data[$i]['bangluong'] = null;
                    $a_data[$i]['trangthai'] = 'CHUALUONG';
                }

            }
            //dd($a_data);
            return view('functions.tonghopluong.donvi.index')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('nam', $inputs['nam'])
                ->with('model', $a_data)
                ->with('a_trangthai', $a_trangthai)
                ->with('pageTitle', 'Danh sách tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghop_101918(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $mathdv = getdate()[0];
            $madv = session('admin')->madv;
            //$m_hs=hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->get();
            $a_hs = array_column(hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->get()->toArray(), 'tencanbo', 'macanbo');

            //lấy bảng lương
            $model_bangluong = bangluong::where('nam', $nam)->where('thang', $thang)
                ->where('madv', $madv)->where('phanloai', 'BANGLUONG')->get();
            //bảng lương chi tiết
            $model_bangluong_ct = bangluong_ct::wherein('mabl', array_column($model_bangluong->toarray(),'mabl'))->get();
            $a_bangluong_phucap = bangluong_phucap::wherein('mabl', array_column($model_bangluong->toarray(),'mabl'))->get()->toarray();
            /*
            $model_bangluong_ct = bangluong_ct::wherein('mabl', function ($query) use ($nam, $thang, $madv) {
                $query->select('mabl')->from('bangluong')->where('nam', $nam)->where('thang', $thang)->where('madv', $madv)->where('phanloai', 'BANGLUONG');
            })->get();
            */
            $model_nguondm = nguonkinhphi_dinhmuc::where('madv',$madv)->get();
            $model_nguondm_ct = nguonkinhphi_dinhmuc_ct::wherein('maso',array_column($model_nguondm->toarray(),'maso'))->get();
            foreach($model_nguondm_ct as $ct){
                $nguondm = $model_nguondm->where('maso',$ct->maso)->first();
                $ct->manguonkp = $nguondm->manguonkp;
            }

            $model_pc = dmphucap_donvi::where('madv', $madv)->wherenotin('mapc',['hesott'])->get();
            //$model_pc = dmphucap_donvi::where('madv', $madv)->where('phanloai', '<', '3')->wherenotin('mapc',['hesott'])->get();
            $a_bh = array_column(dmphucap_thaisan::select('mapc')->where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
            $model_congtac = dmphanloaict::all();

            foreach ($model_bangluong_ct as $ct) {
                if($ct->tencanbo == '' || $ct->tencanbo == null) {
                    $ct->tencanbo = isset($a_hs[$ct->macanbo]) ? $a_hs[$ct->macanbo] : '';
                }
                $bangluong = $model_bangluong->where('mabl', $ct->mabl)->first();

                $ct->luongcoban = $bangluong->luongcoban;
                $ct->manguonkp = $bangluong->manguonkp;
                $dmnguon = $model_nguondm_ct->where('manguonkp', $ct->manguonkp);

                $a_nguon = array();
                if(count($dmnguon)>0){
                    $a_nguon = array_column($dmnguon->toarray(),'mapc');
                }
                $ct->linhvuchoatdong = $bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN
                $congtac = $model_congtac->where('mact', $ct->mact)->first();
                //do kiêm nhiệm trc chưa chọn mã công tác
                $ct->macongtac = count($congtac) > 0 ? $congtac->macongtac : null;
                //bỏ cán bộ nộp bảo hiểm thai sản vì con hs tren bang luong
                $ct->mathdv = $mathdv;

                foreach ($model_pc as $pc) {
                    $mapc = $pc->mapc;
                    if (!in_array($pc->mapc, $a_bh) && $ct->congtac == 'THAISAN') {
                        $ct->$mapc = 0;
                        continue;
                    }


                    //lấy loại phụ cấp được hưởng trong nguồn
                    if(count($a_nguon)>0){
                        if (!in_array($pc->mapc, $a_nguon)) {
                            $ct->$mapc = 0;
                        }
                    }

                    if ($pc->phanloai != 1) {
                        $ct->$mapc = $ct->$mapc * $ct->luongcoban;
                    }

                }

                //$hoso = $model_hoso->where('macanbo',$ct->macanbo)->first();
                //chưa tính trường hợp kiêm nhiệm, bảng lương chưa có mã công tác, cán bộ tạo bản luong xong xóa
                //$ct->mact = $hoso->mact;


                /*
                $diaban_ct = $model_diaban_ct->where('macanbo', $ct->macanbo)->first();
                if (count($diaban_ct) > 0) {
                    //$diaban = $model_diaban->where('madiaban',$diaban_ct->madiaban)->first();
                    $ct->madiaban = $diaban_ct->madiaban;
                } else {
                    $ct->madiaban = null;
                }
                */
            }

            //nhóm đơn vị xã phương thị trấn
            if(session('admin')->maphanloai == 'KVXP'){
                //1536402868: Đại biểu hội đồng nhân dân; 1536459380: Cán bộ cấp ủy viên; 1506673695: KCT cấp xã; 1535613221: kct cấp thôn
                //BIENCHE; KHONGCT
                $model_khac = $model_bangluong_ct->wherein('mact',['1536402868','1536459380','1535613221', '1506673695']);
                $model_bangluong_ct = $model_bangluong_ct->wherein('macongtac',['BIENCHE','KHONGCT']);
                foreach ($model_khac as $khac){
                    $model_bangluong_ct->add($khac);
                }
            }
            //
            //Lấy dữ liệu để lập
            $model_data = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'linhvuchoatdong', 'manguonkp', 'macongtac', 'luongcoban'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_data = a_unique($model_data);
            $a_th = getColTongHop();

            for ($i = 0; $i < count($model_data); $i++) {
                $luongct = $model_bangluong_ct->where('manguonkp', $model_data[$i]['manguonkp'])
                    ->where('linhvuchoatdong', $model_data[$i]['linhvuchoatdong'])
                    ->where('mact', $model_data[$i]['mact']);
                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;

                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                //$model_data[$i]['hesopc'] = $luongct->sum('hesopc') * $model_data[$i]['luongcoban'];
                foreach ($a_th as $ct) {
                    $model_data[$i][$ct] = $luongct->sum($ct);
                    $tonghs += chkDbl($model_data[$i][$ct]);
                }
                $model_data[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');
                $model_data[$i]['soluong'] = count($luongct);
                $model_data[$i]['giaml'] = $luongct->sum('giaml');
                $model_data[$i]['tonghs'] = $tonghs;
                $model_data[$i]['luongtn'] = $model_data[$i]['tonghs'] - $model_data[$i]['giaml'];
            }

            foreach ($model_bangluong_ct as $ct){
                //lấy ở bản hệ số gốc
                foreach ($model_pc as $pc) {
                    $mapc = $pc->mapc;

                    if (!in_array($pc->mapc, $a_th)) {
                        $ct->$mapc = 0;
                    }

                    //lấy loại phụ cấp được hưởng trong nguồn
                    if(count($a_nguon)>0){
                        if (!in_array($pc->mapc, $a_nguon)) {
                            $ct->$mapc = 0;
                        }
                    }

                    if ($pc->phanloai != 1) {
                        $ct->$mapc = $ct->$mapc / $ct->luongcoban;
                    }
                }
                $a_kq = $ct->toarray();
                unset($a_kq['id']);
                tonghopluong_donvi_bangluong::create($a_kq);
            }


            /*

            //Tính toán theo địa bàn
            $model_diaban = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban', 'luongcoban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);

            for ($i = 0; $i < count($model_diaban); $i++) {
                $luongct = $model_bangluong_ct->where('madiaban', $model_diaban[$i]['madiaban'])
                    ->where('luongcoban', $model_diaban[$i]['luongcoban']);

                $tonghs = 0;
                $model_diaban[$i]['mathdv'] = $mathdv;

                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                $model_diaban[$i]['hesopc'] = $luongct->sum('hesopc') * $model_diaban[$i]['luongcoban'];
                foreach ($a_col as $col) {
                    $model_diaban[$i][$col] = $luongct->sum($col) * $model_diaban[$i]['luongcoban'];
                    $tonghs += chkDbl($model_diaban[$i][$col]);
                }

                $model_diaban[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs'] = $tonghs;
            }
            //
                */
            //Thêm báo cáo tổng hợp
            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = $mathdv;
            $inputs['trangthai'] = 'CHOGUI';
            $inputs['phanloai'] = 'DONVI';
            $inputs['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $inputs['thang'] . '/' . $inputs['nam'];
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            /*
            $model_db = array();
            for ($i = 0; $i < count($model_diaban); $i++) {
                if ($model_diaban[$i]['madiaban'] != null) {
                    $model_db[] = $model_diaban[$i];
                }
            }
            */
            tonghopluong_donvi_chitiet::insert($model_data);
            //tonghopluong_donvi_diaban::insert($model_db);
            tonghopluong_donvi::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $mathdv);
        } else
            return view('errors.notlogin');
    }

    function tonghop_221018(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $mathdv = getdate()[0];
            $madv = session('admin')->madv;
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            //$a_hs = array_column(hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->get()->toArray(), 'tencanbo', 'macanbo');

            //lấy bảng lương
            $a_bangluong = bangluong::where('nam', $nam)->where('thang', $thang)
                ->where('madv', $madv)->where('phanloai', 'BANGLUONG')->get()->keyby('mabl')->toarray();

            //bảng lương chi tiết
            $model_bangluong_ct = bangluong_ct::wherein('mabl', array_column($a_bangluong,'mabl'))->get();
            $a_bangluong_phucap = bangluong_phucap::wherein('mabl', array_column($a_bangluong,'mabl'))->get()->toarray();

            //$model_nguondm = nguonkinhphi_dinhmuc::where('madv',$madv)->get();
            $a_nguondm = nguonkinhphi_dinhmuc_ct::join('nguonkinhphi_dinhmuc','nguonkinhphi_dinhmuc_ct.maso','nguonkinhphi_dinhmuc.maso')
                ->where('nguonkinhphi_dinhmuc.madv',$madv)->get()->toarray();

            $model_pc = dmphucap_donvi::where('madv', $madv)->wherenotin('mapc',['hesott'])->get();
            $a_bh = array_column(dmphucap_thaisan::select('mapc')->where('madv', session('admin')->madv)->get()->toarray(), 'mapc');

            if(session('admin')->maphanloai == 'KVXP'){
                //1536402868: Đại biểu hội đồng nhân dân; 1536459380: Cán bộ cấp ủy viên; 1506673695: KCT cấp xã; 1535613221: kct cấp thôn
                //BIENCHE; KHONGCT
                $model_khac = $model_bangluong_ct->wherein('mact',['1536402868','1536459380','1535613221', '1506673695']);
                $model_bangluong_ct = $model_bangluong_ct->wherein('macongtac',['BIENCHE','KHONGCT']);
                foreach ($model_khac as $khac){
                    $model_bangluong_ct->add($khac);
                }
            }

            //ng cứu lương thực nhận
            $a_data = array();
            foreach ($model_bangluong_ct as $ct) {
                $ct->macongtac = isset($a_congtac[$ct->mact]) ? $a_congtac[$ct->mact] : null;
                $bangluong = $a_bangluong[$ct->mabl];
                $ct->manguonkp = $bangluong['manguonkp'];
                $ct->luongcoban = $bangluong['luongcoban'];
                $ct->linhvuchoatdong = $bangluong['linhvuchoatdong'];//chỉ dùng cho khối HCSN
                $ct->mathdv = $mathdv;
                $a_nguon = array_column(a_getelement_equal($a_nguondm, array('manguonkp' => $ct->manguonkp)), 'mapc');
                foreach ($model_pc as $pc) {
                    $mapc = $pc->mapc;
                    $mapc_st = 'st_' . $pc->mapc;
                    $phucap = a_getelement_equal($a_bangluong_phucap, array('macanbo' => $ct->macanbo, 'mabl' => $ct->mabl, 'maso' => $mapc), true);
                    $ct->$mapc_st = count($phucap) > 0 ? $phucap['sotien'] : 0;
                    $ct->$mapc = count($phucap) > 0 ? $phucap['heso'] : 0;

                    //dd($phucap);
                    if (!in_array($pc->mapc, $a_bh) && $ct->congtac == 'THAISAN') {
                        $ct->$mapc = 0;
                        $ct->$mapc_st = 0;
                    }

                    if (count($a_nguon)> 0 && !in_array($pc->mapc, $a_nguon)) {
                        $ct->$mapc = 0;
                        $ct->$mapc_st = 0;
                    }
                }

            }
            //dd($model_bangluong_ct);
            //nhóm đơn vị xã phương thị trấn

            //
            //Lấy dữ liệu để lập
            $model_data = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'linhvuchoatdong', 'manguonkp', 'macongtac', 'luongcoban'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_data = a_unique($model_data);
            $a_th = getColTongHop();

            for ($i = 0; $i < count($model_data); $i++) {
                $luongct = $model_bangluong_ct->where('manguonkp', $model_data[$i]['manguonkp'])
                    ->where('linhvuchoatdong', $model_data[$i]['linhvuchoatdong'])
                    ->where('mact', $model_data[$i]['mact']);
                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;

                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                //$model_data[$i]['hesopc'] = $luongct->sum('hesopc') * $model_data[$i]['luongcoban'];
                //xem kiểm tra nguồn ở đây để chạy it for
                foreach ($a_th as $ct) {
                    $model_data[$i][$ct] = $luongct->sum('st_'.$ct);
                    $tonghs += chkDbl($model_data[$i][$ct]);
                }
                $model_data[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');
                $model_data[$i]['soluong'] = count($luongct);
                $model_data[$i]['giaml'] = $luongct->sum('giaml');
                $model_data[$i]['tonghs'] = $tonghs;
                $model_data[$i]['luongtn'] = $model_data[$i]['tonghs'] - $model_data[$i]['giaml'];
            }
            //dd($model_data);
            foreach ($model_bangluong_ct as $ct){
                $a_kq = $ct->toarray();
                unset($a_kq['id']);
                tonghopluong_donvi_bangluong::create($a_kq);
            }

            //Thêm báo cáo tổng hợp
            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = $mathdv;
            $inputs['trangthai'] = 'CHOGUI';
            $inputs['phanloai'] = 'DONVI';
            $inputs['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $inputs['thang'] . '/' . $inputs['nam'];
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;

            tonghopluong_donvi_chitiet::insert($model_data);
            tonghopluong_donvi::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $mathdv);
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $mathdv = getdate()[0];
            $madv = session('admin')->madv;
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            //$a_hs = array_column(hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->get()->toArray(), 'tencanbo', 'macanbo');

            //lấy bảng lương
            $a_bangluong = bangluong::where('nam', $nam)->where('thang', $thang)
                ->where('madv', $madv)->where('phanloai', 'BANGLUONG')->get()->keyby('mabl')->toarray();

            //bảng lương chi tiết
            $col = getColTongHop();
            $col_st = array();
            for($i=0; $i<count($col); $i++){
                $col_st[] ='st_'. $col[$i];
            }
            $a_th = array_merge(array('macanbo','tencanbo','msngbac', 'mact', 'macvcq', 'mapb', 'mabl'
                ,'congtac','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','tonghs','ttl', 'giaml','ttbh_dv'),$col);
            $a_th = array_merge($a_th,$col_st);
            $a_ct = bangluong_ct::select($a_th)->wherein('mabl', array_column($a_bangluong,'mabl'))->get()->toarray();

            //$a_bangluong_phucap = bangluong_phucap::wherein('mabl', array_column($a_bangluong,'mabl'))->get()->toarray();

            //$model_nguondm = nguonkinhphi_dinhmuc::where('madv',$madv)->get();
            $a_nguondm = nguonkinhphi_dinhmuc_ct::join('nguonkinhphi_dinhmuc','nguonkinhphi_dinhmuc_ct.maso','nguonkinhphi_dinhmuc.maso')
                ->where('nguonkinhphi_dinhmuc.madv',$madv)->get()->toarray();

            $a_pc = dmphucap_donvi::where('madv', $madv)->wherenotin('mapc',['hesott'])->get()->keyby('mapc')->toarray();
            $a_bh = array_column(dmphucap_thaisan::select('mapc')->where('madv', session('admin')->madv)->get()->toarray(), 'mapc');

            $a_data = array();
            $a_plct = array('1536402868','1536459380','1535613221', '1506673695');
            $a_plcongtac = array('BIENCHE','KHONGCT','HOPDONG');
            for($i=0; $i< count($a_ct); $i++){
                $a_ct[$i]['macongtac'] = isset($a_congtac[$a_ct[$i]['mact']]) ? $a_congtac[$a_ct[$i]['mact']] : null;
                $bangluong = $a_bangluong[$a_ct[$i]['mabl']];
                $a_ct[$i]['manguonkp'] = $bangluong['manguonkp'];
                $a_ct[$i]['luongcoban'] = $bangluong['luongcoban'];
                $a_ct[$i]['linhvuchoatdong'] = $bangluong['linhvuchoatdong'];//chỉ dùng cho khối HCSN
                $a_ct[$i]['mathdv'] = $mathdv;
                $a_nguon = array_column(a_getelement_equal($a_nguondm, array('manguonkp' => $a_ct[$i]['manguonkp'])), 'mapc');
                foreach ($a_pc as $k=>$v) {
                    $mapc = $v['mapc'];
                    $mapc_st = 'st_' . $mapc;
                    //$phucap = a_getelement_equal($a_bangluong_phucap, array('macanbo' => $a_ct[$i]['macanbo'], 'mabl' => $a_ct[$i]['mabl'], 'maso' => $mapc), true);
                    //$a_ct[$i][$mapc_st] = count($phucap) > 0 ? $phucap['sotien'] : 0;
                    //$a_ct[$i][$mapc] = count($phucap) > 0 ? $phucap['heso'] : 0;
                    if ($a_ct[$i]['congtac'] == 'THAISAN' && !in_array($mapc, $a_bh) ) {
                        $a_ct[$i][$mapc] = 0;
                        $a_ct[$i][$mapc_st] = 0;
                    }

                    if ($a_ct[$i]['congtac'] == 'KHONGLUONG') {
                        $a_ct[$i][$mapc] = 0;
                        $a_ct[$i][$mapc_st] = 0;
                    }

                    if (count($a_nguon)> 0 && !in_array($mapc, $a_nguon)) {
                        $a_ct[$i][$mapc] = 0;
                        $a_ct[$i][$mapc_st] = 0;
                    }

                }
                if(in_array($a_ct[$i]['macongtac'],$a_plcongtac) || in_array($a_ct[$i]['mact'],$a_plct)){
                    $a_data[] = $a_ct[$i];
                }
            }

            //Lấy dữ liệu để lập
            $model_data = a_split($a_data,array('mact', 'linhvuchoatdong', 'manguonkp', 'macongtac', 'luongcoban'));
            $model_data = a_unique($model_data);

            for ($i = 0; $i < count($model_data); $i++) {
                $luongct = a_getelement_equal($a_data, array('manguonkp'=> $model_data[$i]['manguonkp'],'mact'=>$model_data[$i]['mact'],'linhvuchoatdong'=>$model_data[$i]['linhvuchoatdong']));

                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;
                foreach (getColTongHop() as $ct) {
                    $model_data[$i][$ct] = array_sum(array_column($luongct,'st_'.$ct));
                    $tonghs += chkDbl($model_data[$i][$ct]);

                }
                $model_data[$i]['stbhxh_dv'] = array_sum(array_column($luongct,'stbhxh_dv'));
                $model_data[$i]['stbhyt_dv'] = array_sum(array_column($luongct,'stbhyt_dv'));
                $model_data[$i]['stkpcd_dv'] = array_sum(array_column($luongct,'stkpcd_dv'));
                $model_data[$i]['stbhtn_dv'] = array_sum(array_column($luongct,'stbhtn_dv'));
                $model_data[$i]['soluong'] = count($luongct);
                $model_data[$i]['giaml'] = array_sum(array_column($luongct,'giaml'));
                $model_data[$i]['tonghs'] = $tonghs;
                $model_data[$i]['luongtn'] = $model_data[$i]['tonghs'] - $model_data[$i]['giaml'];
            }
            //Mảng chứa các cột bỏ để chạy hàm insert
            $a_col_pc = array('id','baohiem','bhxh','baohiem','bhtn', 'kpcd', 'bhyt', 'bhct','congtac', 'mabl');
            $a_data = unset_key($a_data,$a_col_pc);
            $a_data = unset_key($a_data,$col_st);
            $a_data = unset_key($a_data,array('st_pctdt', 'pctdt','st_pcxaxe',
                'pcxaxe','st_pcdith','pcdith','st_pcphth','pcphth','pclade', 'st_pclade'));//tạm

            foreach(array_chunk($a_data, 50)  as $data){
                tonghopluong_donvi_bangluong::insert($data);
            }

            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = $mathdv;
            $inputs['trangthai'] = 'CHOGUI';
            $inputs['phanloai'] = 'DONVI';
            $inputs['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $inputs['thang'] . '/' . $inputs['nam'];
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $model_data = unset_key($model_data,array('congtac','mabl','macanbo', 'macvcq', 'mapb'));
            tonghopluong_donvi_chitiet::insert($model_data);
            tonghopluong_donvi::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }

                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.templates.detail')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('model_thongtin', $model_thongtin)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            //dùng tạm khi đồng bộ lại dữ liệu thì xóa macongtac đi
            if(isset($inputs['mact'])){
                $model = tonghopluong_donvi_chitiet::where('mathdv', $inputs['mathdv'])
                    ->where('manguonkp', $inputs['manguonkp'])
                    ->where('mact', $inputs['mact'])->first();
            }else{
                $model = tonghopluong_donvi_chitiet::where('mathdv', $inputs['mathdv'])
                    ->where('manguonkp', $inputs['manguonkp'])
                    ->where('macongtac', $inputs['macongtac'])->first();
            }


            $model->tongpc = $model->tonghs - $model->heso - $model->hesopc;
            $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_detail')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi_chitiet::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);


            foreach (array_keys($inputs) as $key) {
                if (!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $inputs['tonghs'] = $inputs['tongpc'] + $inputs['heso'] + $inputs['hesopc'];

            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail_diaban($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_diaban::where('mathdv', $mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv', session('admin')->madv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $a_diaban = getDiaBan(false);

            foreach ($model as $chitiet) {
                $diaban = $model_diaban->where('madiaban', $chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.templates.detail_diaban')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('model_thongtin', $model_thongtin)
                ->with('pageTitle', 'Chi tiết tổng hợp lương theo địa bàn tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_detail_diaban(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_diaban = array_column(dmdiabandbkk::where('madv', session('admin')->madv)->get()->toarray(), 'tendiaban', 'madiaban');
            $model = tonghopluong_donvi_diaban::where('mathdv', $inputs['mathdv'])
                ->where('madiaban', $inputs['madiaban'])->first();

            $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_diaban')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('a_diaban', $a_diaban)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function store_detail_diaban(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi_diaban::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);

            foreach (array_keys($inputs) as $key) {
                if (!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $inputs['tonghs'] = $inputs['tongpc'] + $inputs['heso'] + $inputs['hesopc'];
            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail_diaban/ma_so=' . $model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        //Kiểm tra xem đơn vị có đơn vị chủ quản => ko cần update đi đâu chỉ cần chuyên trạng thái
        //Không đơn vị chủ quản, tùy xem thuộc huyện, tỉnh để update lên bang tonghop_huyen, tonghop_tinh
        if (Session::has('admin')) {
            $inputs = $requests->all();

            if (!session('admin')->quanlykhuvuc && session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();

            //dd(session('admin')->macqcq == session('admin')->madvqlkv);
            //đơn vị chủ quản là huyện
            //thiếu trường hợp chủ quản lỗi
            //khu vực chưa có đơn vị chủ quản / chủ quản lỗi
            if(session('admin')->macqcq == session('admin')->madvqlkv){
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                //$model_huyen = tonghopluong_huyen::where('mathdv', $model->mathh)->first();
                //khi trả  lại tonghophuyen set mathh = null =>tìm theo mã + thang + năm tổng hợp
                $model_huyen = tonghopluong_huyen::where('thang', $model->thang)->where('nam', $model->nam)
                    ->where('madv', $model->madv)->first();
                if(count($model_huyen) == 0){
                    $masoh = getdate()[0];
                    $model->mathh = $masoh;

                    $inputs['thang'] = $model->thang;
                    $inputs['nam'] = $model->nam;
                    $inputs['madv'] = $model->madv;
                    $inputs['mathdv'] = $masoh;
                    $inputs['trangthai'] = 'DAGUI';
                    $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu chi trả lương.';
                    $inputs['nguoilap'] = session('admin')->name;
                    $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                    $inputs['macqcq'] = session('admin')->macqcq;
                    $inputs['madvbc'] = session('admin')->madvbc;
                    tonghopluong_huyen::create($inputs);
                }else{
                    $model->mathh = $model_huyen->mathdv;//set lại mã vào tonghopdv

                    $model_huyen->macqcq = session('admin')->macqcq;
                    $model_huyen->trangthai = 'DAGUI';
                    $model_huyen->nguoilap = session('admin')->name;
                    $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
                    $model_huyen->save();
                }
            }
            $model->macqcq = session('admin')->macqcq;
            $model->nguoigui = session('admin')->name;
            $model->ngaygui = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            $model->save();

            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $a_bangluong = tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->get()->toarray();

            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            //$model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                /*
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                */
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                $phucap = a_getelement_equal($a_bangluong, array('mact'=>$chitiet->mact,'manguonkp'=>$chitiet->manguonkp));

                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = array_sum(array_column($phucap,$ct));
                }
            }

            $a_phucap = array();
            $a_phucap_hs = array();
            $col = 0;
            $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $a_phucap_hs['hs'.$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);
            //dd($model);
            return view('reports.tonghopluong.donvi.solieutonghop')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_phucap_hs', $a_phucap_hs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function printf_bl($mathdv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $model = tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                $thanhtien = 0;
                foreach (getColTongHop() as $ct) {
                    if ($chitiet->$ct > 50000) {
                        $thanhtien +=  $chitiet->$ct;
                    }
                }
                $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
            }
            //dd($model);

           foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($a_phucap);
            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            //Lấy dữ liệu để lập
            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact','manguonkp','tennguonkp','tenct'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $a_congtac = a_unique($model_congtac);

            $model_nguon = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['manguonkp','tennguonkp'])
                    ->all();
            });
            $a_nguon = a_unique($model_nguon);

            return view('reports.tonghopluong.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_nguon', $a_nguon)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_data_diaban($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_diaban::where('mathdv', $mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv', session('admin')->madv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $a_diaban = getDiaBan(false);
            //$gnr=getGeneralConfigs();

            foreach ($model as $chitiet) {
                $diaban = $model_diaban->where('madiaban', $chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            return view('reports.tonghopluong.donvi.solieudiaban')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();
            tonghopluong_donvi::where('mathdv', $inputs['mathdv'])
                //->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
                ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo'], 'mathh' => null, 'matht' => null, 'mathk' => null, 'macqcq' => null]);
            return redirect('/chuc_nang/xem_du_lieu/index?thang=' . $model->thang . '&nam=' . $model->nam . '&trangthai=ALL');
        } else
            return view('errors.notlogin');
    }

    function getlydo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $model = tonghopluong_donvi::select('lydo')->where('mathdv', $inputs['mathdv'])->first();
        die($model);
    }

    function destroy($mathdv)
    {
        if (Session::has('admin')) {
            tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->delete();
            tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->delete();
            $model = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model->delete();
            //tonghopluong_donvi::where('mathdv',$mathdv)->delete();
            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function destroy_detail($id)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_chitiet::find($id);

            tonghopluong_donvi_bangluong::where('mathdv', $model->mathdv)
                ->where('mathdv', $model->mathdv)
                ->where('manguonkp', $model->manguonkp)
                ->where('mact', $model->mact)
                ->delete();

            $model->delete();
            //tonghopluong_donvi::where('mathdv',$mathdv)->delete();
            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $model->mathdv);
        } else
            return view('errors.notlogin');
    }
}
