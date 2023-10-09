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
use App\dsdonviquanly;
use App\hosocanbo;
use App\nguonkinhphi_dinhmuc;
use App\nguonkinhphi_dinhmuc_ct;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_khoi;
use App\tonghopluong_tinh;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController as data;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_donviController extends Controller
{
    function index(Request $requests)
    {
        if (Session::has('admin')) {
            //text-danger; text-warning; text-success
            $a_data = array(
                array('thang' => '01', 'mathdv' => null),
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
            // dd(session('admin'));
            /*
            $a_trangthai=array('CHUALUONG'=>'Chưa tạo bảng lương','CHUATAO'=>'Chưa tổng hợp dữ liệu'
                ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            */
            $a_trangthai = getStatus();
            $inputs = $requests->all();
            $model = tonghopluong_donvi::where('madv', session('admin')->madv)->where('nam', $inputs['nam'])->get();
            $model_tinh = tonghopluong_tinh::where('nam', $inputs['nam'])->where('trangthai', 'DAGUI')->where('madvbc', session('admin')->madvbc)->get();
            // dd($model);
            // $model_huyen=tonghopluong_huyen::where()->get();
            $model_bangluong = bangluong::where('madv', session('admin')->madv)->where('nam', $inputs['nam'])->get();
            for ($i = 0; $i < count($a_data); $i++) {
                $a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $a_data[$i]['ngaygui'] = '';
                $a_data[$i]['macqcq'] = '';
                $a_data[$i]['tendvcq'] = '';
                $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $a_data[$i]['thang'] . '/' . $inputs['nam'];
                $tonghop = $model->where('thang', $a_data[$i]['thang'])->first();
                $tonghop_tinh = $model_tinh->where('thang', $a_data[$i]['thang'])->first();
                $bangluong = $model_bangluong->where('thang', $a_data[$i]['thang']);
                if (count($bangluong) > 0) {
                    $a_data[$i]['bangluong'] = 'ok';
                    if ($tonghop != null) {
                        $a_data[$i]['noidung'] = $tonghop->noidung;
                        $a_data[$i]['mathdv'] = $tonghop->mathdv;
                        $a_data[$i]['trangthai'] = $tonghop->trangthai;
                        $a_data[$i]['ngaygui'] = $tonghop->ngaygui;
                        $a_data[$i]['macqcq'] = $tonghop->macqcq;
                        $a_data[$i]['tendvcq'] = getTenDV($a_data[$i]['macqcq']);
                        if ($a_data[$i]['tendvcq'] == '') {
                            $a_data[$i]['tendvcq'] = $a_data[$i]['macqcq']; //trường hợp đơn vị gửi nhưng ko pải đơn vị trong hệ thống => đưa mã để kiểm tra
                        }

                        isset($tonghop_tinh) ? $a_data[$i]['matht'] = true : $a_data[$i]['matht'] = false;
                    } else {
                        $a_data[$i]['trangthai'] = 'CHUATAO';
                    }
                } else {
                    //do trc xóa bảng lương nhưng chưa xóa tổng hợp
                    if ($tonghop != null) {


                        $a_data[$i]['bangluong'] = 'ok';
                        $a_data[$i]['noidung'] = $tonghop->noidung;
                        $a_data[$i]['mathdv'] = $tonghop->mathdv;
                        $a_data[$i]['trangthai'] = $tonghop->trangthai;
                        $a_data[$i]['ngaygui'] = $tonghop->ngaygui;
                        $a_data[$i]['macqcq'] = $tonghop->macqcq;
                        $a_data[$i]['tendvcq'] = getTenDV($a_data[$i]['macqcq']);
                        if ($a_data[$i]['tendvcq'] == '') {
                            $a_data[$i]['tendvcq'] = $a_data[$i]['macqcq']; //trường hợp đơn vị gửi nhưng ko pải đơn vị trong hệ thống => đưa mã để kiểm tra
                        }
                        isset($tonghop_tinh) ? $a_data[$i]['matht'] = true : $a_data[$i]['matht'] = false;
                    } else {
                        $a_data[$i]['bangluong'] = null;
                        $a_data[$i]['trangthai'] = 'CHUALUONG';
                    }
                }
            }
            //dd($model);
            // dd($a_data);
            $inputs['macqcq'] = dsdonviquanly::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->first()->macqcq ?? session('admin')->macqcq;
            $madvbc = dmdonvi::where('madv', session('admin')->madv)->first()->madvbc;
            $a_donvi = array_column(dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)
                ->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            //dd($inputs);
            return view('functions.tonghopluong.donvi.index')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                //->with('nam', $inputs['nam'])
                ->with('inputs', $inputs)
                ->with('model', $a_data)
                ->with('a_trangthai', $a_trangthai)
                ->with('a_donvi', $a_donvi)
                ->with('pageTitle', 'Danh sách tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //dd($inputs);
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;
            $chk_data = tonghopluong_donvi::where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('madv', $madv)
                ->get()->count();
            if ($chk_data > 0) {
                return view('errors.trungdulieu')
                    ->with('url', '/chuc_nang/tong_hop_luong/don_vi/index?nam=' . date('Y'));
            }

            $mathdv = getdate()[0];
            //dd($mathdv);
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            //$a_hs = array_column(hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->get()->toArray(), 'tencanbo', 'macanbo');

            //lấy bảng lương
            if (isset($inputs['tonghop']) && $inputs['tonghop'] == 'TONGHOP') {
                $a_bangluong = bangluong::where('nam', $nam)->where('thang', $thang)
                    ->where('madv', $madv)->wherein('phanloai', ['BANGLUONG', 'TRUYLINH', 'TRUC'])->get()->keyby('mabl')->toarray();
            } else {
                $a_bangluong = bangluong::where('nam', $nam)->where('thang', $thang)
                    ->where('madv', $madv)->wherein('phanloai', ['BANGLUONG', 'TRUC'])->get()->keyby('mabl')->toarray();
            }
            //dd($a_bangluong);
            //bảng lương chi tiết
            $col = getColTongHop();
            $col_st = array();
            for ($i = 0; $i < count($col); $i++) {
                $col_st[] = 'st_' . $col[$i];
            }
            $a_th = array_merge(array(
                'stt', 'macanbo', 'tencanbo', 'msngbac', 'mact', 'macvcq', 'mapb', 'mabl', 'manguonkp',
                'luongcoban', 'thangtl', 'ngaytl', 'congtac', 'stbhxh_dv', 'stbhyt_dv', 'stkpcd_dv', 'stbhtn_dv', 'tonghs',
                'stbhxh', 'stbhyt', 'stkpcd', 'stbhtn', 'ttbh', 'ttbh_dv', 'ttl', 'giaml', 'ttbh_dv', 'luongtn'
            ), $col);
            $a_th = array_merge($a_th, $col_st);
            $a_ct = (new data())->getBangluong_ct_ar($thang, array_column($a_bangluong, 'mabl'), $a_th)->toarray();

            // dd($a_ct);

            //$model_nguondm = nguonkinhphi_dinhmuc::where('madv',$madv)->get();
            //            $a_nguondm = nguonkinhphi_dinhmuc_ct::join('nguonkinhphi_dinhmuc','nguonkinhphi_dinhmuc_ct.maso','nguonkinhphi_dinhmuc.maso')
            //                ->where('nguonkinhphi_dinhmuc.madv',$madv)->get()->toarray();
            //dd($a_nguondm);
            //$a_pc = dmphucap_donvi::where('madv', $madv)->wherein('mapc',getColTongHop())->get()->keyby('mapc')->toarray();
            $a_pc = getColTongHop();
            //$a_bh = array_column(dmphucap_thaisan::select('mapc')->where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
            //            $a_ts = array_column(dmphucap_donvi::where('madv', session('admin')->madv)
            //                ->where('phanloai','<','3')->where('thaisan','1')->get()->toarray(), 'mapc');

            $a_data = array();
            $a_plct = getPLCTTongHop();
            //$a_ct = $m_ct->wherein('mact',$a_plct)->toarray();

            /*
            dd($a_plct);
            $a_plct = array('1536402868','1536459380','1535613221', '1506673695');
            $a_plcongtac = array('BIENCHE','KHONGCT','HOPDONG');
            */
            for ($i = 0; $i < count($a_ct); $i++) {
                if (!in_array($a_ct[$i]['mact'], $a_plct)) {
                    continue;
                }
                //dd($a_ct[$i]);
                $a_ct[$i]['macongtac'] = isset($a_congtac[$a_ct[$i]['mact']]) ? $a_congtac[$a_ct[$i]['mact']] : null;
                $bangluong = $a_bangluong[$a_ct[$i]['mabl']];
                //$a_ct[$i]['manguonkp'] = $bangluong['manguonkp'];

                $a_ct[$i]['linhvuchoatdong'] = $bangluong['linhvuchoatdong'];
                //chỉ dùng cho khối HCSN
                $a_ct[$i]['mathdv'] = $mathdv;
                //$a_nguon = array_column(a_getelement_equal($a_nguondm, array('manguonkp' => $a_ct[$i]['manguonkp'])), 'mapc');
                $tonghs = $tongst = 0;

                if (isset($inputs['cb_ts']) && in_array($a_ct[$i]['congtac'], ['DAINGAY', 'THAISAN', 'KHONGLUONG'])) {
                    //cán bộ nghỉ thì tính lại ô st_ do khi tính lương ô st_ gán bằng 0
                    //sau đó gán vào phần giảm trừ
                    foreach ($a_pc as $mapc) {
                        $mapc_st = 'st_' . $mapc;
                        $tonghs += $a_ct[$i][$mapc];
                        if ($a_ct[$i][$mapc] > 0 && $a_ct[$i][$mapc_st] == 0) {
                            if ($a_ct[$i][$mapc] < 1000) {
                                $a_ct[$i][$mapc_st] = round($a_ct[$i][$mapc] * $a_ct[$i]['luongcoban']);
                            } else {
                                $a_ct[$i][$mapc_st] = $a_ct[$i][$mapc];
                            }
                            $a_ct[$i]['giaml'] += $a_ct[$i][$mapc_st];
                        }
                        $tongst += $a_ct[$i][$mapc_st];
                    }
                } else {
                    foreach ($a_pc as $mapc) {
                        $mapc_st = 'st_' . $mapc;
                        //kiểm tra nếu số tiền == 0 => hệ số = 0;
                        //dung hàm abs() do truy thu => số tiền âm
                        if (abs($a_ct[$i][$mapc_st]) > 0) {
                            $tongst += $a_ct[$i][$mapc_st];
                            if (abs($a_ct[$i][$mapc]) < abs($a_ct[$i][$mapc_st])) { //phụ cấp hưởng theo số tiền
                                $tonghs += $a_ct[$i][$mapc];
                            }
                        } else {
                            $a_ct[$i][$mapc] = 0;
                        }
                    }
                }

                $a_ct[$i]['tonghs'] = $tonghs;
                $a_ct[$i]['ttl'] = $tongst;
                if ($a_ct[$i]['congtac'] != 'TRUYLINH') { //bảng lương truy lĩnh có luowngcb trong chi tiết
                    //$a_ct[$i]['luongcoban'] = $bangluong['luongcoban'];
                    $a_ct[$i]['congtac'] = 'BANGLUONG'; //do trong trường congtac = CHUCVU, BIENCHE,...
                }

                $a_ct[$i]['tonghop'] = $a_ct[$i]['congtac'];
                $a_data[] = $a_ct[$i];
            }
            //dd($a_data);
            //Lấy dữ liệu để lập
            $model_data = a_split($a_data, array('congtac', 'mact', 'linhvuchoatdong', 'manguonkp', 'macongtac'));
            $model_data = a_unique($model_data);
            //dd($a_data);
            for ($i = 0; $i < count($model_data); $i++) {
                $luongct = a_getelement_equal($a_data, array(
                    'manguonkp' => $model_data[$i]['manguonkp'],
                    'mact' => $model_data[$i]['mact'],
                    'congtac' => $model_data[$i]['congtac'],
                    'linhvuchoatdong' => $model_data[$i]['linhvuchoatdong']
                ));

                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;
                $model_data[$i]['tonghop'] = $model_data[$i]['congtac'];
                foreach (getColTongHop() as $ct) {
                    $model_data[$i]['st_' . $ct] = array_sum(array_column($luongct, 'st_' . $ct));
                    $model_data[$i][$ct] = array_sum(array_column($luongct, $ct));
                    //$model_data[$i][$ct] = array_sum(array_column($luongct,'st_'.$ct));
                    if (abs($model_data[$i][$ct]) < abs($model_data[$i]['st_' . $ct]))
                        $tonghs += chkDbl($model_data[$i][$ct]);
                }
                $a_slcb = a_unique(a_split($luongct, array('macanbo', 'mact'))); //lọc cán bộ kiêm nhiệm

                $model_data[$i]['stbhxh'] = array_sum(array_column($luongct, 'stbhxh'));
                $model_data[$i]['stbhyt'] = array_sum(array_column($luongct, 'stbhyt'));
                $model_data[$i]['stkpcd'] = array_sum(array_column($luongct, 'stkpcd'));
                $model_data[$i]['stbhtn'] = array_sum(array_column($luongct, 'stbhtn'));
                $model_data[$i]['ttbh'] = array_sum(array_column($luongct, 'ttbh'));

                $model_data[$i]['stbhxh_dv'] = array_sum(array_column($luongct, 'stbhxh_dv'));
                $model_data[$i]['stbhyt_dv'] = array_sum(array_column($luongct, 'stbhyt_dv'));
                $model_data[$i]['stkpcd_dv'] = array_sum(array_column($luongct, 'stkpcd_dv'));
                $model_data[$i]['stbhtn_dv'] = array_sum(array_column($luongct, 'stbhtn_dv'));
                $model_data[$i]['ttbh_dv'] = array_sum(array_column($luongct, 'ttbh_dv'));

                $model_data[$i]['soluong'] = count($a_slcb);
                $model_data[$i]['giaml'] = array_sum(array_column($luongct, 'giaml'));
                $model_data[$i]['tonghs'] = $tonghs;
                $model_data[$i]['luongtn'] = array_sum(array_column($luongct, 'ttl'));
            }
            //dd($a_data);
            //dd($model_data);
            //Mảng chứa các cột bỏ để chạy hàm insert
            $a_col_pc = array('id', 'baohiem', 'bhxh', 'baohiem', 'bhtn', 'kpcd', 'bhyt', 'bhct', 'congtac', 'mabl');
            $a_data = unset_key($a_data, $a_col_pc);
            /*
            $a_data = unset_key($a_data,$col_st);
            $a_data = unset_key($a_data,array('st_pctdt', 'pctdt','st_pcxaxe','pctaicu','st_pctaicu',
                'pcxaxe','st_pcdith','pcdith','st_pcphth','pcphth','pclade', 'st_pclade', 'pcctp', 'st_pcctp'));//tạm
            */
            // dd($a_data);
            foreach (array_chunk($a_data, 1)  as $data) {
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
            $model_data = unset_key($model_data, array('congtac', 'mabl', 'macanbo', 'macvcq', 'mapb'));
            foreach (array_chunk($model_data, 1)  as $data) {
                tonghopluong_donvi_chitiet::insert($data);
            }
            // tonghopluong_donvi_chitiet::insert($model_data);
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
            $a_nguonkp = getNguonKP(false);
            $a_ct = getPhanLoaiCT(false);
            $a_lv = getLinhVucHoatDong(false);

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($a_nguonkp[$chitiet->manguonkp]) ? $a_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($a_ct[$chitiet->mact]) ? $a_ct[$chitiet->mact] : '';
                $chitiet->linhvuchoatdong = isset($a_lv[$chitiet->linhvuchoatdong]) ? $a_lv[$chitiet->linhvuchoatdong] : '';
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
            if (isset($inputs['mact'])) {
                $model = tonghopluong_donvi_chitiet::where('mathdv', $inputs['mathdv'])
                    ->where('manguonkp', $inputs['manguonkp'])
                    ->where('mact', $inputs['mact'])->first();
            } else {
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

    //lưu
    function senddata_03032023(Request $requests)
    {
        //Kiểm tra xem đơn vị có đơn vị chủ quản => ko cần update đi đâu chỉ cần chuyên trạng thái
        //Không đơn vị chủ quản, tùy xem thuộc huyện, tỉnh để update lên bang tonghop_huyen, tonghop_tinh
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (!session('admin')->quanlykhuvuc && session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            // dd($inputs);
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();

            //dd(session('admin')->macqcq == session('admin')->madvqlkv);
            //đơn vị chủ quản là huyện
            //thiếu trường hợp chủ quản lỗi
            //khu vực chưa có đơn vị chủ quản / chủ quản lỗi
            if (session('admin')->macqcq == session('admin')->madvqlkv) {
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                //$model_huyen = tonghopluong_huyen::where('mathdv', $model->mathh)->first();
                //khi trả  lại tonghophuyen set mathh = null =>tìm theo mã + thang + năm tổng hợp
                $model_huyen = tonghopluong_huyen::where('thang', $model->thang)->where('nam', $model->nam)
                    ->where('madv', $model->madv)->first();
                if ($model_huyen == null) {
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
                    $inputs['macqcq'] = $inputs['macqcq'];
                    $inputs['madvbc'] = session('admin')->madvbc;
                    tonghopluong_huyen::create($inputs);
                } else {
                    $model->mathh = $model_huyen->mathdv; //set lại mã vào tonghopdv

                    $model_huyen->macqcq = $inputs['macqcq'];
                    $model_huyen->trangthai = 'DAGUI';
                    $model_huyen->nguoilap = session('admin')->name;
                    $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
                    //$model->macqcq = $inputs['macqcq'];
                    $model_huyen->save();
                }
            }
            $model->macqcq = $inputs['macqcq'];
            $model->nguoigui = session('admin')->name;
            $model->ngaygui = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            $model->save();

            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if ($inputs['macqcq'] == '') {
                return view('errors.chuacqcq');
            }

            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();
            $model->macqcq = $inputs['macqcq'];
            $model->nguoigui = session('admin')->name;
            $model->ngaygui = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            //1.tự động thêm danh sách quản lý
            $chk_ql = dsdonviquanly::where('nam', $model->nam)->where('madv', $model->madv)->first();
            if ($chk_ql == null)
                dsdonviquanly::create([
                    'nam' => $model->nam,
                    'madv' => $model->madv,
                    'macqcq' => $model->macqcq,
                ]);
            //1. hết

            if (session('admin')->macqcq == session('admin')->madvqlkv) {
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                //$model_huyen = tonghopluong_huyen::where('mathdv', $model->mathh)->first();
                //khi trả  lại tonghophuyen set mathh = null =>tìm theo mã + thang + năm tổng hợp
                $model_huyen = tonghopluong_huyen::where('thang', $model->thang)->where('nam', $model->nam)
                    ->where('madv', $model->madv)->first();
                // dd($model_huyen);
                if ($model_huyen == null) {
                    $masoh = getdate()[0];
                    $model->mathh = $masoh;

                    $inputs['thang'] = $model->thang;
                    $inputs['nam'] = $model->nam;
                    $inputs['madv'] = $model->madv;
                    $inputs['mathdv'] = $masoh;
                    // $inputs['mathdv'] = $model->mathdv;
                    $inputs['trangthai'] = 'DAGUI';
                    $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu chi trả lương.';
                    $inputs['nguoilap'] = session('admin')->name;
                    $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                    $inputs['macqcq'] = $inputs['macqcq'];;
                    $inputs['madvbc'] = session('admin')->madvbc;
                    tonghopluong_huyen::create($inputs);
                } else {
                    $model->mathh = $model_huyen->mathdv; //set lại mã vào tonghopdv
                    $model_huyen->macqcq = $inputs['macqcq'];;
                    $model_huyen->trangthai = 'DAGUI';
                    $model_huyen->nguoilap = session('admin')->name;
                    $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
                    $model_huyen->save();
                }
            }
            $model->save();

            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            $check = tonghopluong_khoi::join('dmdonvi', 'dmdonvi.madv', 'tonghopluong_khoi.madv')
                ->where('mathdv', $mathdv)->first();
            if ($check != null) {
                $model = tonghopluong_donvi_chitiet::where('mathh', $mathdv)->get();
                $model_thongtin = tonghopluong_donvi::where('mathh', $mathdv)->first();
                $a_bangluong = tonghopluong_donvi_bangluong::where('mathh', $mathdv)->get()->toarray();
                $m_dv = dmdonvi::join('tonghopluong_khoi', 'dmdonvi.madv', 'tonghopluong_khoi.madv')
                    ->where('mathdv', $mathdv)->first();
                $m_pc = array_column(dmphucap_donvi::wherein('madv', function ($query) use ($mathdv) {
                    $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi', 'dmdonvi.madv', 'tonghopluong_khoi.madv')->where('mathdv', $mathdv)
                        ->get();
                })->get()->toarray(), 'report', 'mapc');
            } else {
                $model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
                $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
                $a_bangluong = tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->get()->toarray();
                $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
                $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(), 'report', 'mapc');
            }
            //dd($model);
            //$dsdonvi = dmdonvi:: join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathdv)->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            //$model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
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
                $chitiet->tongtl = $chitiet->luongtn;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                /*
                $phucap = a_getelement_equal($a_bangluong, array('mact'=>$chitiet->mact,'manguonkp'=>$chitiet->manguonkp,'tonghop'=>$chitiet->tonghop));
                //$m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
                if($chitiet->tonghop != 'TRUYLINH'){
                    foreach (getColTongHop() as $ct) {
                        $ma = 'hs'.$ct;
                        $chitiet->$ma = array_sum(array_column($phucap,$ct));
                    }
                }
                */
            }

            $a_phucap = array();
            //$a_phucap_hs = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    //$a_phucap_hs['hs'.$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            $a_tonghop = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['tonghop'])
                    ->all();
            });
            //dd($model);
            // dd($m_dv);
            return view('reports.tonghopluong.donvi.solieutonghop')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_tonghop', a_unique($a_tonghop))
                ->with('a_phucap', $a_phucap)
                //->with('a_phucap_hs', $a_phucap_hs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function printf_data_khoi($mathh)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            //$a_bangluong = tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->get()->toarray();

            //$model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model = tonghopluong_donvi_chitiet::wherein('mathdv', function ($query) use ($mathh) {
                $query->select('mathdv')->from('tonghopluong_donvi')->where('mathh', $mathh)->get();
            })->get();
            $a_bangluong = tonghopluong_donvi_bangluong::wherein('mathdv', function ($query) use ($mathh) {
                $query->select('mathdv')->from('tonghopluong_donvi')->where('mathh', $mathh)->get();
            })->get();
            $model_thongtin = tonghopluong_donvi::where('mathh', $mathh)->first();

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
                $phucap = a_getelement_equal($a_bangluong, array('mact' => $chitiet->mact, 'manguonkp' => $chitiet->manguonkp));

                foreach (getColTongHop() as $ct) {
                    $ma = 'hs' . $ct;
                    $chitiet->$ma = array_sum(array_column($phucap, $ct));
                }
            }

            $a_phucap = array();
            $a_phucap_hs = array();
            $col = 0;
            $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(), 'report', 'mapc');
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $a_phucap_hs['hs' . $ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );
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
            // dd($mathdv);
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
            $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(), 'report', 'mapc');
            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                $thanhtien = 0;
                foreach (getColTongHop() as $ct) {
                    if ($chitiet->$ct > 50000) {
                        $thanhtien += $chitiet->$ct;
                    }
                }
                if ($chitiet->ttl == 0) { //trường hop dinh mức ko nhân dc với hệ số
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                } else {
                    $chitiet->tongtl = $chitiet->ttl;
                }
            }
            //dd($model->toarray());
            // dd($model);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($a_phucap);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'manguonkp', 'tennguonkp', 'tenct', 'tonghop'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $a_congtac = a_unique($model_congtac);
            //dd($a_congtac);
            $model_nguon = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['manguonkp', 'tennguonkp', 'tonghop'])
                    ->all();
            });
            $a_nguon = a_unique($model_nguon);
            // dd($a_nguon);
            $a_tonghop = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['tonghop'])
                    ->all();
            });

            return view('reports.tonghopluong.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_nguon', $a_nguon)
                ->with('a_congtac', $a_congtac)
                ->with('a_tonghop', a_unique($a_tonghop))
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_bl_khoi($mathdv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->where('tonghopluong_donvi.mathh', $mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathh', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            //$m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
            $m_pc = array_column(dmphucap_donvi::wherein('madv', function ($query) use ($mathdv) {
                $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi', 'dmdonvi.madv', 'tonghopluong_khoi.madv')->where('mathdv', $mathdv)
                    ->get();
            })->get()->toarray(), 'report', 'mapc');

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
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'manguonkp', 'tennguonkp', 'tenct'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $a_congtac = a_unique($model_congtac);

            $model_nguon = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['manguonkp', 'tennguonkp'])
                    ->all();
            });
            $a_nguon = a_unique($model_nguon);

            return view('reports.tonghopluong.khoi.bangluong')
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

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );
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
            $model = tonghopluong_khoi::join('dmdonvi', 'dmdonvi.madv', 'tonghopluong_khoi.madv')
                ->where('mathdv', $inputs['mathdv'])->first();
            if ($model != null) {
                tonghopluong_khoi::where('mathdv', $inputs['mathdv'])
                    ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
            } else {
                $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();
                tonghopluong_donvi::where('mathdv', $inputs['mathdv'])
                    //->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
                    ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo'], 'mathh' => null, 'matht' => null, 'mathk' => null]);
            }
            return redirect('/chuc_nang/xem_du_lieu/index?thang=' . $model->thang . '&nam=' . $model->nam . '&trangthai=ALL&phanloai=ALL');
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

    function destroy(Request $request)
    { //từ mathdv -> lấy các bảng tổng hợp theo tháng, năm -> xóa hết
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_maso = tonghopluong_donvi::select('mathdv')->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('madv', $inputs['madv'])
                ->get()->toarray();

            tonghopluong_donvi::wherein('mathdv', $a_maso)->delete();
            tonghopluong_donvi_chitiet::wherein('mathdv', $a_maso)->delete();
            tonghopluong_donvi_bangluong::wherein('mathdv', $a_maso)->delete();

            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . date('Y'));
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
