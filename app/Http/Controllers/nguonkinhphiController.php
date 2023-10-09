<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use App\dmthongtuquyetdinh;
use App\dsdonviquanly;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\hosotamngungtheodoi;
use App\hosothoicongtac;
use App\ngachluong;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_chitiet;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\nguonkinhphi_nangluong;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_phucap;
use Illuminate\Support\Facades\Session;

class nguonkinhphiController extends Controller
{
    function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['furl'] = '/nguon_kinh_phi/';
            $a_thongtuqd = array_column(dmthongtuquyetdinh::orderby('ngayapdung')->get()->toarray(), 'tenttqd', 'sohieu');
            $inputs['sohieu'] = $inputs['sohieu'] ?? array_key_last($a_thongtuqd);
            $model = nguonkinhphi::where('madv', session('admin')->madv)->where('sohieu', $inputs['sohieu'])->orderby('namns')->get();
            $model_phucap = nguonkinhphi_phucap::wherein('masodv', array_column($model->toarray(), 'masodv'))->get();
            $lvhd = getLinhVucHoatDong(false);
            foreach ($model as $ct) {
                $ct->linhvuc = isset($lvhd[$ct->linhvuchoatdong]) ? $lvhd[$ct->linhvuchoatdong] : '';
                $phucap = $model_phucap->where('masodv', $ct->masodv);
                $ct->nhucau = $phucap->sum('ttl') + $phucap->sum('ttbh_dv');
            }
            //dd($model);
            // $model_bl = bangluong::where('madv', session('admin')->madv)->where('phanloai', 'BANGLUONG')->orderby('nam')->orderby('thang')->get();
            $model_tt_df = dmthongtuquyetdinh::orderby('ngayapdung', 'desc')->first();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();

            $a_cqcq = array_column(dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)
                ->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            //dd(session('admin'));
            return view('manage.nguonkinhphi.index')
                //->with('furl', '/nguon_kinh_phi/')
                ->with('inputs', $inputs)
                ->with('a_trangthai', getStatus())
                ->with('model', $model)
                // ->with('model_bl', $model_bl)
                ->with('model_tt_df', $model_tt_df)
                ->with('a_phongban', getPhongBan(false))
                ->with('a_cqcq', $a_cqcq)
                ->with('a_lvhd', $lvhd)
                ->with('a_thongtuqd', $a_thongtuqd)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($model_thongtu->ngayapdung);
            $inputs['namdt'] = chkDbl($model_thongtu->namdt) == 0 ? date('Y') : date_format($ngayapdung, 'Y');
            //Kiểm tra nếu có rồi thì ko tạo
            $chk = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('linhvuchoatdong', $inputs['linhvuchoatdong'])
                ->where('madv', session('admin')->madv)
                ->count();

            if ($chk > 0) {
                return view('errors.data_exist')
                    ->with('furl', '/nguon_kinh_phi/danh_sach');
            }

            $a_plct = getPLCTNhuCau();
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            $gen = getGeneralConfigs();
            $a_pc_tonghop = getColTongHop();
            $a_pc = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem')
                ->where('madv', session('admin')->madv)->wherein('mapc', $a_pc_tonghop)->get()->toarray();
            $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();
            $masodv = session('admin')->madv . '_' . getdate()[0];
            $inputs['chenhlech'] = chkDbl($inputs['chenhlech']);

            $a_th = array_merge(array('macanbo', 'mact', 'macvcq', 'mapb', 'ngayden', 'pthuong'), $a_pc_tonghop);
            $m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th, ['phanloai']))
                ->where('madv', session('admin')->madv)
                ->wherein('mact', $a_plct)
                //->get()->keyBy('macanbo')->toarray();
                ->get();
            $a_th = array_merge(array(
                'stt', 'ngaysinh', 'tencanbo', 'gioitinh', 'msngbac', 'bac', 'bhxh_dv', 'bhyt_dv',
                'bhtn_dv', 'kpcd_dv', 'ngaybc', 'ngayvao', 'lvhd', 'ngaytu', 'tnntungay', 'tnndenngay', 'mucluongbaohiem'
            ), $a_th);

            $model = hosocanbo::select($a_th)->where('madv', session('admin')->madv)
                ->where('theodoi', '<', '9')
                ->get();

            $m_thoict = hosothoicongtac::where('madv', session('admin')->madv)
                ->wherebetween('ngaynghi', [$model_thongtu->ngayapdung, $inputs['namdt'] . '-12-31'])->get();
            $model_nghihuu = hosocanbo::select($a_th)->wherein('macanbo', array_column($m_thoict->toarray(), 'macanbo'))->get();

            foreach ($model_nghihuu as $ct) {
                $nghihuu = $m_thoict->where('macanbo', $ct->macanbo)->first();
                $ct->ngayvao = $nghihuu->ngaynghi;
                $model->add($ct);
            }
            if (isset($inputs['thaisan'])) {
                $m_ts = hosotamngungtheodoi::where('madv', session('admin')->madv)->where('maphanloai', 'THAISAN')
                    ->whereBetween('ngayden', [Carbon::create($inputs['namdt'])->startOfYear(), Carbon::create($inputs['namdt'] + 1)->endOfYear()])
                    ->get();
            } else {
                $m_ts = new Collection();
            }
            if (isset($inputs['kyluat'])) {
                $m_kl = hosotamngungtheodoi::where('madv', session('admin')->madv)->where('maphanloai', 'KYLUAT')
                    ->whereBetween('ngayden', [Carbon::create($inputs['namdt'])->startOfYear(), Carbon::create($inputs['namdt'] + 1)->endOfYear()])
                    ->get();
            } else {
                $m_kl = new Collection();
            }
            // dd($m_kl);
            //$a_pc_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
            $a_pc_ts = array_column(dmphucap_donvi::where('madv', session('admin')->madv)
                ->where('phanloai', '<', '3')->where('thaisan', '1')->get()->toarray(), 'mapc');
            //dd($a_pc_ts);           
            $model = (new dataController())->getCanBo($model, $model_thongtu->ngayapdung, isset($inputs['nangluong']), $model_thongtu->ngayapdung);
            // dd($model);
            foreach ($model as $key => $cb) {
                //xét thời hạn hợp đồng của cán bộ: nếu "ngayvao" > $model_thongtu->ngayapdung => gán lĩnh vực hoạt động = null để lọc theo lĩnh vực bỏ qua cán bộ
                if (getDayVn($cb->ngayvao) != '' && $cb->ngayvao <= $model_thongtu->ngayapdung) {
                    $cb->lvhd = null;
                    continue;
                }
                $cb->macongtac = $a_congtac[$cb->mact];
                $cb->masodv = $masodv;
                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                //$cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;

                $a_lv = explode(',', $cb->lvhd);
                if (in_array($inputs['linhvuchoatdong'], $a_lv) || $cb->lvhd == null) {
                    $cb->lvhd = $inputs['linhvuchoatdong'];
                }
                //dd($cb->lvhd);
                if (isset($cb->ngaysinh)) {
                    $dt_ns = date_create($cb->ngaysinh);
                    $cb->nam_ns = (string) date_format($dt_ns, 'Y') + ($cb->gioitinh == 'Nam' ? $gen['tuoinam'] : $gen['tuoinu']);
                    $cb->thang_ns = date_format($dt_ns, 'm') + 1 + ($cb->gioitinh == 'Nam' ? $gen['thangnam'] : $gen['thangnu']);
                    //dd($cb->thang_ns);
                    if ($cb->thang_ns > 12) {
                        $cb->thang_ns = str_pad($cb->thang_ns - 12, 2, '0', STR_PAD_LEFT);
                        $cb->nam_ns = strval($cb->nam_ns + 1);
                    }
                } else {
                    $cb->nam_ns = null;
                    $cb->thang_ns = null;
                }

                if (isset($cb->ngayden)) {
                    $dt_luong = new Carbon($cb->ngayden);
                    $cb->nam_nb = str_pad($dt_luong->year, 4, '0', STR_PAD_LEFT);
                    $cb->thang_nb = str_pad($dt_luong->month, 2, '0', STR_PAD_LEFT);
                } else {
                    $cb->nam_nb = null;
                    $cb->thang_nb = null;
                }

                if (isset($cb->tnndenngay)) {
                    $dt_nghe = new Carbon($cb->tnndenngay);
                    $cb->nam_tnn = str_pad($dt_nghe->year, 4, '0', STR_PAD_LEFT);
                    $cb->thang_tnn = str_pad($dt_nghe->month, 2, '0', STR_PAD_LEFT);
                } else {
                    $cb->nam_tnn = null;
                    $cb->thang_tnn = null;
                }

                if (getDayVn($cb->ngayvao) != '') {
                    $ngayxet = new Carbon($cb->ngayvao);
                    if ($ngayxet->day == 1) {
                        $ngayxet->addDay(-1);
                    }
                    $cb->nam_hh = str_pad($ngayxet->year, 4, '0', STR_PAD_LEFT);
                    $cb->thang_hh = str_pad($ngayxet->month, 2, '0', STR_PAD_LEFT);
                } else {
                    $cb->nam_hh = null;
                    $cb->thang_hh = null;
                }
                //trường hợp cán bộ chỉ có tiền lương theo số tiền (tonghs = 0) =>bỏ
                // if ($cb->luonghd != 0) {
                //     $model->forget($key);
                // }
            }

            //Tách kiêm nhiệm
            if (isset($inputs['tachkiemnhiem'])) {
                if (session('admin')->maphanloai == 'KVXP') {
                    $a_nhomplct_hnnd = dmphanloaict::where('nhomnhucau_xp', 'HDND')->get();
                    $a_nhomplct_capuy = dmphanloaict::where('nhomnhucau_xp', 'CAPUY')->get();
                } else {
                    $a_nhomplct_hnnd = dmphanloaict::where('nhomnhucau_hc', 'HDND')->get();
                    $a_nhomplct_capuy = dmphanloaict::where('nhomnhucau_hc', 'CAPUY')->get();
                }

                foreach ($model as $key => $ct) {
                    //tách hội đồng nhân dân
                    if ($ct->pcdbqh > 0 && $a_nhomplct_hnnd->where('mact', $ct->mact)->count() == 0) {

                        $model_pcdbqh = new hosocanbo();
                        $model_pcdbqh->macvcq = $ct->macvcq;
                        $model_pcdbqh->mapb = $ct->mapb;
                        $model_pcdbqh->stt = $ct->stt;
                        $model_pcdbqh->msngbac = $ct->msngbac;
                        $model_pcdbqh->bac = $ct->bac;
                        $model_pcdbqh->masodv = $masodv;

                        $model_pcdbqh->mact = $a_nhomplct_hnnd->first()->mact;
                        $model_pcdbqh->macongtac = $a_nhomplct_hnnd->first()->macongtac;
                        $model_pcdbqh->lvhd = $inputs['linhvuchoatdong'];
                        $model_pcdbqh->macanbo = $ct->macanbo . '_hdnd';
                        $model_pcdbqh->tencanbo = $ct->tencanbo;
                        $model_pcdbqh->mucluongbaohiem = 0;
                        $model_pcdbqh->ngaybc = null;
                        $model_pcdbqh->ngayvao = null;
                        $model_pcdbqh->ngaysinh = null;
                        $model_pcdbqh->tnndenngay = null;

                        //thêm cho đủ trường
                        for ($i = 0; $i < count($a_pc); $i++) {
                            $mapc = $a_pc[$i]['mapc'];
                            $mapc_st = 'st_' . $mapc;
                            $model_pcdbqh->$mapc = 0;
                            $model_pcdbqh->$mapc_st = 0;
                        }

                        $model_pcdbqh->bhxh_dv = 0;
                        $model_pcdbqh->bhyt_dv = 0;
                        $model_pcdbqh->bhtn_dv = 0;
                        $model_pcdbqh->kpcd_dv = 0;

                        //Gán lại phụ cấp
                        $model_pcdbqh->pcdbqh = $ct->pcdbqh;
                        $ct->pcdbqh = 0;
                        $model->add($model_pcdbqh);
                        //dd(array_keys($model_pcdbqh->toarray()));
                    }

                    //tách cấp uỷ
                    if ($ct->pcvk > 0 && $a_nhomplct_capuy->where('mact', $ct->mact)->count() == 0) {
                        $model_capuy = new hosocanbo();

                        $model_capuy->macvcq = $ct->macvcq;
                        $model_capuy->mapb = $ct->mapb;
                        $model_capuy->stt = $ct->stt;
                        $model_capuy->msngbac = $ct->msngbac;
                        $model_capuy->bac = $ct->bac;
                        $model_capuy->masodv = $masodv;

                        $model_capuy->mact = $a_nhomplct_capuy->first()->mact;
                        $model_capuy->macongtac = $a_nhomplct_capuy->first()->macongtac;
                        $model_capuy->lvhd = $inputs['linhvuchoatdong'];
                        $model_capuy->macanbo = $ct->macanbo . '_capuy';
                        $model_capuy->tencanbo = $ct->tencanbo;
                        $model_capuy->mucluongbaohiem = 0;
                        $model_capuy->ngaybc = null;
                        $model_capuy->ngayvao = null;
                        $model_capuy->ngaysinh = null;
                        $model_capuy->tnndenngay = null;

                        //thêm cho đủ trường
                        for ($i = 0; $i < count($a_pc); $i++) {
                            $mapc = $a_pc[$i]['mapc'];
                            $mapc_st = 'st_' . $mapc;
                            $model_capuy->$mapc = 0;
                            $model_capuy->$mapc_st = 0;
                        }

                        $model_capuy->bhxh_dv = 0;
                        $model_capuy->bhyt_dv = 0;
                        $model_capuy->bhtn_dv = 0;
                        $model_capuy->kpcd_dv = 0;

                        //Gán lại phụ cấp
                        $model_capuy->pcvk = $ct->pcvk;
                        $ct->pcvk = 0;
                        $model->add($model_capuy);
                    }
                }
            }
            // dd($model);

            $model = $model->wherein('mact', $a_plct)->where('lvhd', $inputs['linhvuchoatdong']);
            //lấy danh sách cán bộ chưa nâng lương từ tháng 01-06 => tự nâng lương

            $m_cb = $model->keyBy('macanbo')->toarray();

            //làm tùy chọn tính nghỉ hưu
            $m_hh = $model->where('ngayvao', '>=', $model_thongtu->ngayapdung)->where('ngayvao', '<=', $inputs['namdt'] . '-12-31')->keyBy('macanbo')->toarray();

            $m_nh = $model->where('nam_ns', '<>', '')->where('nam_ns', '<=', $inputs['namdt'])->keyBy('macanbo')->toarray();
            if (isset($inputs['nangluong'])) {
                $m_nb = $model->where('nam_nb', '<>', '')->where('nam_nb', '=', $inputs['namdt'])->keyBy('macanbo')->toarray();
                $m_tnn = $model->where('nam_tnn', '<>', '')->where('nam_tnn', '=', $inputs['namdt'])->keyBy('macanbo')->toarray();
            } else {
                $m_nb = array();
                $m_tnn = array();
            }

            foreach ($m_cb_kn as $key => $ct) {
                if (!isset($m_cb[$ct->macanbo])) {
                    $m_cb_kn->forget($key);
                    continue;
                }
                $ct->macongtac = $a_congtac[$ct->mact];
                // dd($ct);
                $canbo = $m_cb[$ct->macanbo];
                $ct->tencanbo = $canbo['tencanbo'];
                $ct->stt = $canbo['stt'];
                $ct->msngbac = $canbo['msngbac'];
                $ct->ngaybc = $canbo['ngaybc'];
                $ct->ngayvao = $canbo['ngayvao'];
                $ct->mucluongbaohiem = 0;
                $ct->ngaysinh = null;
                $ct->tnndenngay = null;
                //$ct->macongtac = null;
                $ct->gioitinh = null;
                $ct->nam_ns = null;
                $ct->thang_ns = null;
                $ct->nam_nb = null;
                $ct->thang_nb = null;
                $ct->nam_tnn = null;
                $ct->thang_tnn = null;
                $ct->msngbac = null;
                $ct->bac = null;
                $ct->bhxh_dv = 0;
                $ct->bhyt_dv = 0;
                $ct->bhtn_dv = 0;
                $ct->kpcd_dv = 0;
                $ct->masodv = $masodv;
                $ct->lvhd = $canbo['lvhd'];
                // $a_kq = $ct->toarray();
                // unset($a_kq['phanloai']);
                // $m_cb[$ct->macanbo . '_' . $i++] = $a_kq;
            }

            foreach ($m_nh as $key => $val) {
                $canbo = $this->getHeSoPc_nh($a_pc, $m_nh[$key]);
                if ($canbo['tonghs'] > 0) {
                    $m_nh[$key] = $canbo;
                } else {
                    unset($m_nh[$key]);
                }
            }

            foreach ($m_nb as $key => $val) {
                if (isset($inputs['thaisan'])) {
                    //kiểm tra xem tháng đó có nâng lương có nghỉ ts ko nếu có tháng nâng lương thành tháng ngay sau ngày nghỉ
                    $ngaylap = Carbon::create($val['nam_nb'], $val['thang_nb'], '01');
                    $a_ts = $m_ts->where('macanbo', $key)->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap);
                    if (count($a_ts) > 0) {
                        $dt_luong = date_create($a_ts->first()->ngayden);
                        $m_nb[$key]['thang_nb'] = date_format($dt_luong, 'm') + 1;
                        //dd($m_nb[$key]);
                    }
                }

                if (isset($a_nhomnb[$val['msngbac']])) {
                    $nhomnb = $a_nhomnb[$val['msngbac']];
                    $hesomax = $nhomnb['hesolonnhat'];
                    if ($val['heso'] >= $hesomax) {
                        //$m_nb[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'] == 0 ? $nhomnb['vuotkhung'] : $m_nb[$key]['vuotkhung'] + 1;
                        $m_nb[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'] == 0 ? 5 : $m_nb[$key]['vuotkhung'] + 1;
                    } else {
                        $m_nb[$key]['heso'] += $nhomnb['hesochenhlech'];
                    }
                }

                //kiểm tra xem thời gian nâng lương của cán bộ từ tháng 01-06 => tự nâng
                //tính lại do vượt khung đã tính từ trc sang hệ số => tính lại hệ số vk thì sai
                //                if(date_create($val['ngayden']) <= date_create($inputs['namdt'].'-06-30')){
                //                    $m_cb[$key]['heso'] = $m_nb[$key]['heso'];
                //                    $m_cb[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'];
                //                }

                if (isset($m_tnn[$key]) && $m_tnn[$key]['thang_tnn'] < $m_nb[$key]['thang_nb']) {
                    $m_nb[$key]['pctnn'] = $m_nb[$key]['pctnn'] == 0 ? 5 : $m_nb[$key]['pctnn'] + 1;
                    //dd($m_tnn[$key]);
                }
                $canbo =  $this->getHeSoPc($a_pc, $m_nb[$key], $inputs['chenhlech']);
                if ($canbo['tonghs'] > 0) {
                    $m_nb[$key] = $canbo;
                } else {
                    unset($m_nb[$key]);
                }
            }
            // dd($m_tnn);
            foreach ($m_tnn as $key => $val) {
                if (isset($inputs['thaisan'])) {
                    //kiểm tra xem tháng đó có nâng lương có nghỉ ts ko nếu có tháng nâng lương thành tháng ngay sau ngày nghỉ
                    $ngaylap = Carbon::create($val['nam_tnn'], $val['thang_tnn'], '01');
                    $a_ts = $m_ts->where('macanbo', $key)->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap);
                    if (count($a_ts) > 0) {
                        $dt_luong = date_create($a_ts->first()->ngayden);
                        $m_tnn[$key]['thang_tnn'] = date_format($dt_luong, 'm');
                    }
                }

                $m_tnn[$key]['pctnn'] = $m_tnn[$key]['pctnn'] == 0 ? 5 : $m_tnn[$key]['pctnn'] + 1;
                //nếu tăng tnn bằng hoặc sau nb => set lai heso, vuotkhung
                if (isset($m_nb[$key]) && $m_tnn[$key]['thang_tnn'] >= $m_nb[$key]['thang_nb']) {
                    $m_tnn[$key]['heso'] = $m_nb[$key]['heso'];
                    $m_tnn[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'];

                    $canbo =  $this->getHeSoPc($a_pc, $m_tnn[$key], $inputs['chenhlech'], false);
                    if ($canbo['tonghs'] > 0) {
                        $m_tnn[$key] = $canbo;
                    } else {
                        unset($m_tnn[$key]);
                    }
                } else {
                    $canbo = $this->getHeSoPc($a_pc, $m_tnn[$key], $inputs['chenhlech']);
                    if ($canbo['tonghs'] > 0) {
                        $m_tnn[$key] = $canbo;
                    } else {
                        unset($m_tnn[$key]);
                    }
                }

                //kiểm tra xem thời gian nâng lương của cán bộ từ tháng 01-06 => tự nâng
                //                if(date_create($val['ngayden']) <= date_create($inputs['namdt'].'-06-30')){
                //                    if(isset($m_cb[$key]['pctnn'])){
                //                        $m_cb[$key]['pctnn'] = $m_tnn[$key]['pctnn'];
                //                    }
                //                }
            }

            //chạy tính hệ số lương, phụ cấp trc. Sau này mỗi tháng chỉ chạy cán bộ thay đổi
            //chạy sau các nâng lương để tính cán bộ chưa nâng lương trc tháng 06
            foreach ($m_cb as $key => $val) {
                $canbo = $this->getHeSoPc($a_pc, $m_cb[$key], $inputs['chenhlech']);
                if ($canbo['tonghs'] > 0) {
                    $m_cb[$key] = $canbo;
                } else {
                    unset($m_cb[$key]);
                }
            }
            // dd($m_cb);
            //TÍnh lương cho phu cấp kiêm nhiệm
            $i = 1;
            foreach ($m_cb_kn as $val) {
                if (!isset($m_cb[$val->macanbo])) { //cán bộ đã bị xoá trong ds cán bộ
                    continue;
                }
                $a_kq = $this->getHeSoPc_kiemnhiem($a_pc, $m_cb[$val->macanbo], $val, $inputs['chenhlech'])->toarray();
                unset($a_kq['phanloai']);

                if ($a_kq['tonghs'] > 0)
                    $m_cb[$ct->macanbo . '_' . $i++] = $a_kq;
            }
            //Lọc lại các cán bộ có tonghs = 0;


            //dd($m_cb['1565583148_1669774144_1']);
            $a_thang = array(
                array('thang' => '07', 'nam' => $inputs['namdt']),
                array('thang' => '08', 'nam' => $inputs['namdt']),
                array('thang' => '09', 'nam' => $inputs['namdt']),
                array('thang' => '10', 'nam' => $inputs['namdt']),
                array('thang' => '11', 'nam' => $inputs['namdt']),
                array('thang' => '12', 'nam' => $inputs['namdt'])
            );

            //lưu lại mảng thông tin cũ do đã tách riêng: nâng lương ngạch bậc và nâng lương thâm niên
            //nâng tnn: 02; nâng nb: 06 => mảng chênh lệch 'NGACHBAC' lấy thông tin cũ để tính
            $a_luu = $m_cb;
            // dd($a_luu);

            $a_data = array();
            $a_data_nl = array();
            $a_danghihuu = array();

            for ($i = 0; $i < count($a_thang); $i++) {
                $a_nh = a_getelement($m_nh, array('thang_ns' => $a_thang[$i]['thang']));
                if (count($a_nh) > 0) { //
                    foreach ($a_nh as $key => $val) {
                        if (!isset($inputs['nghihuu'])) {
                            $m_cb[$key] = $a_nh[$key];
                        } else {
                            $m_cb[$key]['tencanbo'] .= ' (nghỉ hưu)';
                        }
                        $a_danghihuu[] = $key;
                    }
                }

                $a_nb = a_getelement($m_nb, array('thang_nb' => $a_thang[$i]['thang']));

                if (count($a_nb) > 0) {
                    foreach ($a_nb as $key => $val) {
                        if (!in_array($key, $a_danghihuu)) {
                            if ($a_thang[$i]['thang'] != '07') { //nâng lương vào tháng 07 => ko tính chênh lệch nâng lương
                                $a_data_nl[] = $this->getHeSoPc_Sub($a_pc, $a_nb[$key], $a_luu[$key], 'NGACHBAC', $a_thang[$i]['thang'], $a_thang[$i]['nam']);
                            }
                            $m_cb[$key] = $a_nb[$key];
                        }
                    }
                }
                $a_tnn = a_getelement($m_tnn, array('thang_tnn' => $a_thang[$i]['thang']));
                if (count($a_tnn) > 0) {
                    foreach ($a_tnn as $key => $val) {
                        if (!in_array($key, $a_danghihuu)) {
                            if ($a_thang[$i]['thang'] != '07') { //nâng lương vào tháng 01 => ko tính chênh lệch nâng lương
                                $a_data_nl[] = $this->getHeSoPc_Sub($a_pc, $a_tnn[$key], $m_cb[$key], 'THAMNIENNGHE', $a_thang[$i]['thang'], $a_thang[$i]['nam']);
                            }
                            $m_cb[$key] = $a_tnn[$key];
                        }
                    }
                }
                // dd($m_cb);
                $ngaylap = Carbon::create($a_thang[$i]['nam'], $a_thang[$i]['thang'], '01');
                //lưu vào 1 mảng
                foreach ($m_cb as $key => $val) {
                    //kiem tra can bo het han lao dong => bo wa
                    if (isset($m_hh[$key]) && $m_hh[$key]['thang_hh'] < $a_thang[$i]['thang']) {
                        continue;
                    }
                    //nếu cán bộ chưa đến hạn công tác =>bỏ qua
                    if ($m_cb[$key]['ngaybc'] > $ngaylap) {
                        continue;
                    }

                    $m_cb[$key]['thang'] = $a_thang[$i]['thang'];
                    $m_cb[$key]['nam'] = $a_thang[$i]['nam'];
                    if (isset($inputs['thaisan'])) {
                        //kiểm tra nghỉ thai san không
                        $a_ts = $m_ts->where('macanbo', $key)->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap);
                        if (count($a_ts) > 0) { //cán bộ nghỉ ts
                            $a_cb = $m_cb[$key];
                            //dd($this->getHeSoPc_ts($a_cb,$a_pc,$a_pc_ts));
                            $a_data[] = $this->getHeSoPc_ts($a_cb, $a_pc, $a_pc_ts);
                            continue;
                        }
                    }
                    if(isset($inputs['kyluat'])){
                        //kiểm tra cán bộ bị kỷ luật
                        $a_kl = $m_kl->where('macanbo', $key)->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap);
                        // dd($a_kl);
                        if (count($a_kl) > 0) { //cán bộ bị kỷ luật
                            $a_cb = $m_cb[$key];
                            //dd($this->getHeSoPc_ts($a_cb,$a_pc,$a_pc_ts));
                            $a_data[] = $this->getHeSoPc_kl($a_pc,$a_cb, $inputs['chenhlech']);
                            continue;
                        }
                    }
                    $a_data[] = $m_cb[$key];
                }
            }
            //dd($a_data_nl);
            // $a_dbhdnd = ['1536402868', '1536402870',];
            // $a_cuv = ['1536459380', '1558600713', '1536459382', '1558945077',];

            $m_data = a_split_key($a_data, array('mact', 'macongtac'), 'mact');
            // dd( $a_data);
            $m_data_phucap = a_unique($m_data);
            $m_data_01thang = a_unique($m_data);
            //$m_data = a_unique($m_data);            
            // dd($m_data_phucap);
            //Tính lại tổng phụ cấp
            $a_col_khac = ["stbhxh_dv", "stbhyt_dv", "stkpcd_dv", "stbhtn_dv", "ttbh_dv", "tonghs"];
            for ($i = 0; $i < count($m_data_phucap); $i++) {
                $m_data_phucap[$i]['masodv'] = $masodv;
                $dutoan = a_getelement($a_data, array('mact' => $m_data_phucap[$i]['mact']));
                $m_data_phucap[$i]['canbo_congtac'] = count(array_unique(array_column($dutoan, 'macanbo')));
                $m_data_phucap[$i]['canbo_dutoan'] = $m_data_phucap[$i]['canbo_congtac'];
                $m_data_phucap[$i]['ttl'] = array_sum(array_column($dutoan, "luongtn"));
                foreach ($a_pc_tonghop as $pc) {
                    $mapc_st = 'st_' . $pc;
                    $m_data_phucap[$i][$pc] = round(array_sum(array_column($dutoan, $pc)), 5);
                    $m_data_phucap[$i][$mapc_st] = round(array_sum(array_column($dutoan, $mapc_st)), 5);
                }
                foreach ($a_col_khac as $col) {
                    $m_data_phucap[$i][$col] = round(array_sum(array_column($dutoan, $col)), 5);
                }
            }


            //Tổng hợp 01 tháng để tính
            for ($i = 0; $i < count($m_data_01thang); $i++) {
                $m_data_01thang[$i]['masodv'] = $masodv;
                $dutoan = a_getelement($a_data, array('mact' => $m_data_01thang[$i]['mact'], 'thang' => '07'));
                //dd();
                $m_data_01thang[$i]['canbo_congtac'] = count(array_unique(array_column($dutoan, 'macanbo')));
                // $m_data_01thang[$i]['canbo_congtac'] = count($dutoan);
                $m_data_01thang[$i]['canbo_dutoan'] = $m_data_01thang[$i]['canbo_congtac'];
                $m_data_01thang[$i]['ttl'] = array_sum(array_column($dutoan, "luongtn"));
                foreach ($a_pc_tonghop as $pc) {
                    $mapc_st = 'st_' . $pc;
                    $m_data_01thang[$i][$pc] = round(array_sum(array_column($dutoan, $pc)), 5);
                    $m_data_01thang[$i][$mapc_st] = round(array_sum(array_column($dutoan, $mapc_st)), 5);
                }
                foreach ($a_col_khac as $col) {
                    $m_data_01thang[$i][$col] = round(array_sum(array_column($dutoan, $col)), 5);
                }
                //Tính lại hệ số bảo hiểm lấy tương đối
                $m_data_01thang[$i]['bhxh_dv'] = round($m_data_01thang[$i]['stbhxh_dv'] / $inputs['chenhlech'], 7);
                $m_data_01thang[$i]['bhyt_dv'] = round($m_data_01thang[$i]['stbhyt_dv'] / $inputs['chenhlech'], 7);
                $m_data_01thang[$i]['bhtn_dv'] = round($m_data_01thang[$i]['stbhtn_dv'] / $inputs['chenhlech'], 7);
                $m_data_01thang[$i]['kpcd_dv'] = round($m_data_01thang[$i]['stkpcd_dv'] / $inputs['chenhlech'], 7);
                $m_data_01thang[$i]['tongbh_dv'] = $m_data_01thang[$i]['bhxh_dv'] + $m_data_01thang[$i]['bhyt_dv'] + $m_data_01thang[$i]['bhtn_dv'] + $m_data_01thang[$i]['kpcd_dv'];
            }
            //dd($m_data_01thang);
            $inputs['sobiencheduocgiao'] = 0;
            if (session('admin')->maphanloai == 'KVXP') {
                $a_nhomplct = array_column(dmphanloaict::all()->toArray(), 'nhomnhucau_xp', 'mact');
                foreach ($m_data_01thang as $ct) {
                    if (($a_nhomplct[$ct['mact']] ?? '') == 'CANBOCT') {
                        $inputs['sobiencheduocgiao'] += $ct['canbo_congtac'];
                    }
                }
            } else {
                $a_nhomplct = array_column(dmphanloaict::all()->toArray(), 'nhomnhucau_hc', 'mact');
                foreach ($m_data_01thang as $ct) {
                    if (($a_nhomplct[$ct['mact']] ?? '') == 'BIENCHE') {
                        $inputs['sobiencheduocgiao'] += $ct['canbo_congtac'];
                    }
                }
            }

            $inputs['trangthai'] = 'CHOGUI';
            $inputs['maphanloai'] = session('admin')->maphanloai;
            $inputs['masodv'] = $masodv;
            $inputs['madv'] = session('admin')->madv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['namns'] = $inputs['namdt'];

            //2023.06.06 Xem còn dùng ko
            // $inputs['nhucau'] = array_sum(array_column($m_data, 'nhucau'));
            // $inputs['luongphucap'] = array_sum(array_column($m_data, 'luongphucap'));
            // $inputs['nghihuu'] = array_sum(array_column($m_data, 'nghihuu'));
            // $inputs['canbokct'] = array_sum(array_column($m_data, 'canbokct'));
            // $inputs['boiduong'] = array_sum(array_column($m_data, 'boiduong'));
            // $inputs['daibieuhdnd'] = array_sum(array_column($m_data, 'daibieuhdnd'));
            // $inputs['uyvien'] = array_sum(array_column($m_data, 'uyvien'));
            // $inputs['baohiem'] = array_sum(array_column($m_data, 'baohiem'));

            //lưu dữ liệu
            $a_col = array(
                'bac', 'bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'gioitinh', 'nam_nb', 'nam_ns', 'nam_tnn',
                'thang_nb', 'thang_ns', 'thang_tnn', 'ngayden', 'ngaytu', 'ngaysinh', 'tnndenngay', 'tnntungay', 'pcctp',
                'st_pcctp', 'nam_hh', 'thang_hh', 'ngaybc', 'ngayvao', 'lvhd', 'mucluongbaohiem', 'pthuong'
            );
            //dd($m_data_phucap);
            $a_data_nl = unset_key($a_data_nl, $a_col);
            //dd($a_data_nl);
            //2023.07.01 bỏ phần nâng luong
            // foreach (array_chunk($a_data_nl, 10) as $data) {
            //     nguonkinhphi_nangluong::insert($data);
            // }
            // dd($a_data);
            //chia nhỏ thành các mảng nhỏ 100 phần tử để insert
            $a_data = unset_key($a_data, $a_col);
            //dd($a_data[101]);
            foreach (array_chunk($a_data, 20)  as $data) {
                nguonkinhphi_bangluong::insert($data);
            }
            $m_data = unset_key($m_data, array('luonghs', 'nopbh'));
            nguonkinhphi_chitiet::insert($m_data);
            nguonkinhphi_phucap::insert($m_data_phucap);
            nguonkinhphi_01thang::insert($m_data_01thang);
            $inputs['nangcap_phucap'] = true;
            nguonkinhphi::create($inputs);
            return redirect('/nguon_kinh_phi/danh_sach?sohieu=' . $inputs['sohieu']);
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['maso'])->first();

            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $model->sohieu)->first();
            //dd($model);
            if ($model != null) {
                $model->nhucaukp = $model->luongphucap + $model->daibieuhdnd + $model->canbokct
                    + $model->uyvien + $model->boiduong + $model->nghihuu + $model->baohiem;
                $model->nhucaupc = $model->thunhapthap + $model->diaban
                    + $model->tinhgiam + $model->nghihuusom
                    + $model->kpuudai + $model->kpthuhut;
            }

            //Mẫu 2a
            $model_2a = nguonkinhphi_01thang::where('masodv', $inputs['maso'])->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            foreach ($model_2a as $chitiet) {
                if (session('admin')->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //Mẫu 2c
            //dd(session('admin'));
            //lấy ds phu cap

            //Tinh toán số liệu
            //Mẫu 2đ (2dd)
            //'soluonghientai_2dd', //lấy số lượng cán bộ hiện tại
            //'quyluonghientai_2dd', //lấy tll trong nguonkinhphi_phucap nhan chia số tiền theo thông tư

            //2h

            //Tính tổng số lượng cán bộ biên chế được giao cho các mẫu ko có chi tiết
            if ($model->sobiencheduocgiao == 0) {
                if (session('admin')->maphanloai == 'KVXP') {
                    $model->sobiencheduocgiao = $model_2a->where('nhomnhucau', 'CANBOCT')->sum('canbo_congtac');
                } else {
                    $model->sobiencheduocgiao = $model_2a->where('nhomnhucau', 'BIENCHE')->sum('canbo_congtac');
                }
            }
            //dd(session('admin') );
            return view('manage.nguonkinhphi.edit')
                ->with('furl', '/nguon_kinh_phi/')
                ->with('model', $model)
                ->with('inputs', $inputs)
                ->with('model_2a', $model_2a)
                ->with('m_thongtu', $m_thongtu)
                ->with('a_nhucau', array_merge(getNhomNhuCauKP('KVHCSN'), getNhomNhuCauKP('KVXP')))
                ->with('a_ct', getPhanLoaiCT(false))
                ->with('nam', date_format(date_create($m_thongtu->ngayapdung), 'Y'))
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();

            //Sau xây dựng các trường trong thông tư
            $a_solieu = [
                '2b' => 13950,
                '2d' => 1490000,
                '2d_ndcu' => 'ND34/2019',
                '2d_nd33' => getNghiDinhPLXaPhuong(session('admin')->phanloaixa)
            ];
            $a_truong = [
                'bosung',
                'caicach',
                'kpthuhut',
                'kpuudai',
                'luongchuyentrach', //thừa
                'luongkhongchuyentrach', //thừa
                'tongnhucau1', //tổng nhu cầu kinh trước 1 năm
                'tongnhucau2', //tổng nhu cầu kinh trước 2 năm
                //Báo cáo nhu cầu kinh phí mẫu Vạn Ninh
                'tongsonguoi1', //mẫu 2b
                'quy1_1', //mẫu 2b
                'quy1_2', //mẫu 2b
                'quy1_3', //mẫu 2b
                'tongsonguoi2', //mẫu 2b
                'quy2_1', //mẫu 2b
                'quy2_2', //mẫu 2b
                'quy2_3', //mẫu 2b
                'tongsonguoi3', //mẫu 2b
                'quy3_1', //mẫu 2b
                'quy3_2', //mẫu 2b
                'quy3_3', //mẫu 2b
                //2c thông tư 50
                'sotodanphobiengioi_2d',
                'sothon350hgd_2d',
                'sotodanpho500hgd_2d',
                'sochuyentuthon350hgd_2d',
                'sothonbiengioi_tong',
                'sotodanphobiengioi_tong',
                'sothon350hgd__tong',
                'sotodanpho500hgd_tong',
                'sothontrongdiem_tong',
                'sochuyentuthon350hgd_tong',
                'sothonconlai_tong',
                'sotoconlai_tong',
                'sothonbiengioi_2d',
                'sothontrongdiem_2d',
                'sothonconlai_2d',
                'sotoconlai_2d',

                //mẫu 2d tt50
                'soluongcanbo_2d',
                'hesoluongbq_2d',
                'hesophucapbq_2d',
                'tyledonggop_2d',
                'soluongdinhbien_2d',
                'quyluonggiam_2k',

                'tongsonguoi2015', //mẫu 2đ
                'tongsonguoi2017', //mẫu 2đ
                'quyluong', //mẫu 2đ
                'tongsodonvi1', //mẫu 2e
                'tongsodonvi2', //mẫu 2e
                'quy_tuchu', //mẫu 2e
                'nangcap_phucap', //trường để xác định xem đã tổng hợp phụ cấp ra bảng: nguonkinhphi_phucap
                //Mẫu 2đ
                'soluonghientai_2dd',
                'quyluonghientai_2dd',
                'kinhphitietkiem_2dd',
                'quyluongtietkiem_2dd',
                //Mẫu 2h
                'soluonghientai_2h',
                'hesoluong_2h',
                'hesophucap_2h',
                'tonghesophucapnd61_2h',
                'tonghesophucapqd244_2h',
                //Mẫu 2i
                'soluonghientai_2i',
                'hesoluong_2i',
                'hesophucap_2i',
                //Mẫu 2k
                'soluonggiam_2k',

                //Mẫu 4a
                'nhucau',
                'kinhphigiamxa_4a',
                'tinhgiam',
                'nghihuusom',
                'tietkiem',
                'tinhgiambc_4a',
                'nghihuu_4a',
                'boiduong_4a',
                'satnhapdaumoi_4a',
                'thaydoicochetuchu_4a',
                'satnhapxa_4a',
                'tietkiem1', //trước 1 năm
                'tietkiem2', //trước 2 năm
                'thuchien1', //trước 1 năm
                'dutoan',
                'dutoan1', //trước 1 năm
                'huydongtx_hocphi_4a',
                'huydongtx_vienphi_4a',
                'huydongtx_khac_4a',
                'huydongktx_hocphi_4a',
                'huydongktx_vienphi_4a',
                'huydongktx_khac_4a',

            ];
            // dd($inputs);
            foreach ($a_truong  as $truong) {
                if (isset($inputs[$truong]))
                    $inputs[$truong] = chkDbl($inputs[$truong]);
            }
            //Tính toán số liệu mẫu 2b (310000*4.5% = 13950)
            $inputs['quy1_tong'] = round(($inputs['quy3_1'] - $inputs['quy1_1'] + $inputs['quy2_1'] - $inputs['quy1_1'] + $inputs['tongsonguoi1'] * $a_solieu['2b']) * 6);
            $inputs['quy2_tong'] = round(($inputs['quy3_2'] - $inputs['quy1_2'] + $inputs['quy2_2'] - $inputs['quy1_2'] + $inputs['tongsonguoi2'] * $a_solieu['2b']) * 6);
            $inputs['quy3_tong'] = round(($inputs['quy3_3'] - $inputs['quy1_3'] + $inputs['quy2_3'] - $inputs['quy1_3'] + $inputs['tongsonguoi3'] * $a_solieu['2b']) * 6);


            //Tính toán số liệu mẫu 2d
            //Nếu xã không nhập soluongdinhbien thì lấy max theo nđ33
            // $inputs['soluongdinhbien_2d']=$inputs['soluongdinhbien_2d'] != 0?$inputs['soluongdinhbien_2d']:getSoLuongCanBoDinhMuc('ND33/2023/XA', session('admin')->phanloaixa);
            // $inputs['soluongdinhbien_2d']=getSoLuongCanBoDinhMuc('ND33/2023/XA', session('admin')->phanloaixa);

            // Thay đổi số lượng định biên ndd34 cho nhập và số lương định biên nđ 33 thì lấy mặc định

            // $inputs['quyluonggiam_2k'] = round((getSoLuongCanBoDinhMuc($a_solieu['2d_ndcu'], session('admin')->phanloaixa) - $inputs['soluongdinhbien_2d'])
            //     * ($inputs['hesoluongbq_2d'] + $inputs['hesophucapbq_2d'] + $inputs['tyledonggop_2d']) * $a_solieu['2d']);
            $inputs['quyluonggiam_2k'] = round(($inputs['soluongdinhbien_2d'] - getSoLuongCanBoDinhMuc($a_solieu['2d_nd33'], session('admin')->phanloaixa))
                * ($inputs['hesoluongbq_2d'] + $inputs['hesophucapbq_2d'] + $inputs['tyledonggop_2d']) * $a_solieu['2d']);

            $model->update($inputs);
            if ($inputs['huyen'] == 1) {
                return redirect('chuc_nang/xem_du_lieu/nguon/huyen?sohieu=' . $model->sohieu . '&trangthai=ALL&phanloai=ALL');
            } else {
                return redirect('/nguon_kinh_phi/danh_sach');
            }
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }

            $m_nkp = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            //1.tự động thêm danh sách quản lý
            $chk_ql = dsdonviquanly::where('nam', $m_nkp->namns)->where('madv', $m_nkp->madv)->first();
            if ($chk_ql == null)
                dsdonviquanly::create([
                    'nam' => $m_nkp->namns,
                    'madv' => $m_nkp->madv,
                    'macqcq' => $m_nkp->macqcq,
                ]);
            //1. hết
            $model = nguonkinhphi::where('sohieu', $m_nkp->sohieu)->where('madv', session('admin')->madv)->get();
            // dd($model);
            foreach ($model as $nguon) {
                $nguon->trangthai = 'DAGUI';
                $nguon->macqcq = session('admin')->macqcq;
                $nguon->nguoiguidv = session('admin')->name;
                $nguon->ngayguidv = Carbon::now()->toDateTimeString();
                $model->macqcq = $inputs['macqcq'];
                $nguon->save();
            }


            //kiểm tra xem gửi lên khối hay lên huyện
            //lên khối=> chuyển trạng thái do nguonkinhphi(SD) = nguonkinhphi_khoi(TH)
            //lên huyện => phát sinh bản ghi mới tại bảng nguonkinhphi_huyen

            //check đơn vị chủ quản là gửi lên huyện => chuyển trạng thái; import bản ghi vào bảng huyện
            //khối => chuyển trạng thái
            /* bỏ do tuyến huyện làm riêng
            if(session('admin')->macqcq == session('admin')->madvqlkv){//đơn vị chủ quản là huyện
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                $model_huyen = nguonkinhphi_huyen::where('masodv', $model->masoh)->first();
                if(count($model_huyen) == 0){
                    $masoh = getdate()[0];
                    $model->masoh = $masoh;

                    $inputs['sohieu'] = $model->sohieu;
                    $inputs['madv'] = $model->madv;
                    $inputs['masodv'] = $masoh;
                    $inputs['trangthai'] = 'DAGUI';
                    $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu dự toán lương.';
                    $inputs['nguoilap'] = session('admin')->name;
                    $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                    $inputs['macqcq'] = session('admin')->macqcq;
                    $inputs['madvbc'] = session('admin')->madvbc;
                    nguonkinhphi_huyen::create($inputs);
                }else{
                    $model_huyen->trangthai = 'DAGUI';
                    $model_huyen->nguoilap = session('admin')->name;
                    $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
                    $model_huyen->save();
                }
            }
            */
            //$model->macqcq = session('admin')->macqcq;
            //$model->nguoiguidv = session('admin')->name;
            //$model->ngayguidv = Carbon::now()->toDateTimeString();
            //$model->trangthai = 'DAGUI';
            //$model->save();

            return redirect('/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function getlydo(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::select('lydo')->where('masodv', $inputs['masodv'])->first();
            die($model);
        } else
            return view('errors.notlogin');
    }

    function printf($masodv)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('masodv', $masodv)->first();
            if ($model == null) {
                $model = nguonkinhphi::where('masoh', $masodv)->first();
            }
            //lấy thông tư tổng hợp nguồn
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $model->sohieu)->first();
            //dd($model_thongtu);
            $m_dv = dmdonvi::where('madv', $model->madv)->first();
            $data = array();
            $data[] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT
            $khac = false;
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['val'] == $model->linhvuchoatdong) {
                    $data[$i]['nhucau'] = $model->nhucau;
                    $data[$i]['nguonkp'] = $model->nguonkp;
                    $data[$i]['tietkiem'] = $model->tietkiem;
                    $data[$i]['hocphi'] = $model->hocphi;
                    $data[$i]['vienphi'] = $model->vienphi;
                    $data[$i]['nguonthu'] = $model->nguonthu;

                    $khac = true;
                }
            }
            $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'];
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'];

            if (!$khac) {
                $data[4]['nhucau'] = $model->nhucau;
                $data[4]['nguonkp'] = $model->nguonkp;
                $data[4]['tietkiem'] = $model->tietkiem;
                $data[4]['hocphi'] = $model->hocphi;
                $data[4]['vienphi'] = $model->vienphi;
                $data[4]['nguonthu'] = $model->nguonthu;
            }

            return view('reports.thongtu67.donvi.mau4b')
                ->with('model', $model)
                ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function getinfo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = nguonkinhphi::findorfail($inputs['id']);
        die($model);
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi::find($id);
            nguonkinhphi_chitiet::where('masodv', $model->masodv)->delete();
            nguonkinhphi_bangluong::where('masodv', $model->masodv)->delete();
            nguonkinhphi_nangluong::where('masodv', $model->masodv)->delete();
            nguonkinhphi_01thang::where('masodv', $model->masodv)->delete();
            nguonkinhphi_phucap::where('masodv', $model->masodv)->delete();
            nguonkinhphi_khoi::where('masodv', $model->masok)->delete();
            nguonkinhphi_huyen::where('masodv', $model->masoh)->delete();
            $model->delete();
            return redirect('/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function getinfor_thongtu(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
        die($model);
    }

    function printf_data(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['maso'])->first();

            //lấy thông tư tổng hợp nguồn
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $model->sohieu)->first();
            //dd($model_thongtu);
            $m_dv = dmdonvi::where('madv', $model->madv)->first();
            $data = array();
            $data[] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT
            $khac = false;
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['val'] == $model->linhvuchoatdong) {
                    $data[$i]['nhucau'] = $model->nhucau;
                    $data[$i]['nguonkp'] = $model->nguonkp;
                    $data[$i]['tietkiem'] = $model->tietkiem;
                    $data[$i]['hocphi'] = $model->hocphi;
                    $data[$i]['vienphi'] = $model->vienphi;
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = $model->nguonthu;

                    $khac = true;
                }
            }
            $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'];
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
            $data[0]['khac'] = 0;
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'];

            if (!$khac) {
                $data[4]['nhucau'] = $model->nhucau;
                $data[4]['nguonkp'] = $model->nguonkp;
                $data[4]['tietkiem'] = $model->tietkiem;
                $data[4]['hocphi'] = $model->hocphi;
                $data[4]['vienphi'] = $model->vienphi;
                $data[4]['khac'] = 0;
                $data[4]['nguonthu'] = $model->nguonthu;
            }
            $inputs['donvitinh'] = 1;
            return view('reports.thongtu67.donvi.mau4b_tt46')
                ->with('model', $model)
                ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = nguonkinhphi_bangluong::where('masodv', $inputs['masodv']);
            if ($inputs['thang'] != 'ALL') {
                $model = $model->where('thang', $inputs['thang']);
            }
            if ($inputs['mact'] != 'ALL') {
                $model = $model->where('mact', $inputs['mact']);
            }
            $model = $model->orderby('thang')->orderby('stt')->get();
            //dd($model);
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_thang = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_thang = a_unique($model_thang);

            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang', 'mact'])
                    ->all();
            });
            $model_congtac = a_unique($model_congtac);

            // dd($model);
            return view('reports.nguonkinhphi.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_ct', $a_ct)
                ->with('model_thang', $model_thang)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            if ($inputs['mact'] != 'ALL') {
                $model_ct = nguonkinhphi_bangluong::where('masodv', $inputs['masodv'])
                    ->where('mact', $inputs['mact'])
                    ->orderby('stt')->get();
            } else {
                $model_ct = nguonkinhphi_bangluong::where('masodv', $inputs['masodv'])->orderby('stt')->get();
            }

            $model = $model_ct->where('thang', $model_ct->min('thang'));
            //dd($model);

            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');
            //dd($a_ct);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            foreach ($model as $ct) {
                $bl = $model_ct->where('macanbo', $ct->macanbo);
                foreach ($m_pc as $pc) {
                    $ma = $pc['mapc'];
                    $ma_st = 'st_' . $pc['mapc'];
                    $ct->$ma = $bl->sum($ma);
                    $ct->$ma_st = $bl->sum($ma_st);
                }
                $ct->tonghs = $bl->sum('tonghs');
                $ct->luongtn = $bl->sum('luongtn');
                $ct->stbhxh_dv = $bl->sum('stbhxh_dv');
                $ct->stbhyt_dv = $bl->sum('stbhyt_dv');
                $ct->stkpcd_dv = $bl->sum('stkpcd_dv');
                $ct->stbhtn_dv = $bl->sum('stbhtn_dv');
                $ct->ttbh_dv = $bl->sum('ttbh_dv');

                $ct->tencanbo = str_replace('(nghỉ thai sản)', '', $ct->tencanbo);
                $ct->tencanbo = str_replace('(nghỉ hưu)', '', $ct->tencanbo);
                $ct->tencanbo = trim($ct->tencanbo);
            }

            //dd($model);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );

            return view('reports.nguonkinhphi.donvi.bangluong_m2')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghopnhucau_donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            $model = nguonkinhphi_phucap::where('masodv', $inputs['masodv'])->get();
            //Lấy phụ cấp
            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }
            //Lấy mã công tác
            $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');


            //dd($model);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );

            return view('reports.nguonkinhphi.donvi.tonghopnhucau')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    //bỏ
    function printf_nangluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = nguonkinhphi_nangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();
            //dd($model);
            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['maso'])->first();
            $a_pl = getPhanLoaiNangLuong();
            dd();
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();
            //dd($m_pc);
            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_thang = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_thang = a_unique($model_thang);

            $model_phanloai = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['maphanloai'])
                    ->all();
            });
            $model_phanloai = a_unique($model_phanloai);

            return view('reports.nguonkinhphi.donvi.nangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_pl', $a_pl)
                ->with('model_thang', $model_thang)
                ->with('model_phanloai', $model_phanloai)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    /**
     * @param $a_pc
     * @param $m_cb
     * @param $i
     * @return array
     */
    function getHeSoPc($a_pc, $m_cb, $luongcb = 0, $vk = true)
    {
        $stbhxh_dv = 0;
        $stbhyt_dv = 0;
        $stkpcd_dv = 0;
        $stbhtn_dv = 0;
        $m_cb['tonghs'] = 0;
        $m_cb['luongtn'] = 0;
        $m_cb['luongcoban'] = $luongcb;
        if ($vk) {
            $m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);
        }
        //$m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);

        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            //Tính tỉ lệ hưởng lương cho cán bộ bị kỷ luật 03102023;
            // if($m_cb['theodoi'] == 7){
            //     $m_cb[$mapc] = round($m_cb[$mapc] * $m_cb['pthuong'] / 100, session('admin')->lamtron);
            // }           
            switch (getDbl($a_pc[$i]['phanloai'])) {
                case 0: {
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                case 1: { //số tiền (không tính chênh lệch)
                        //$m_cb['luongtn'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = $m_cb[$mapc] = 0;
                        break;
                    }
                case 2: { //phần trăm
                        if ($mapc != 'vuotkhung') { //vượt khung đã tính ở trên
                            $heso = 0;
                            foreach (explode(',', $a_pc[$i]['congthuc']) as $cthuc) {
                                if ($cthuc != '') {
                                    $heso += $m_cb[$cthuc];
                                }
                            }
                            $m_cb[$mapc] = round($heso * $m_cb[$mapc] / 100, session('admin')->lamtron);
                        }
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                default: { //trường hợp còn lại (ẩn,...)
                        $m_cb[$mapc] = 0;
                        $m_cb[$mapc_st] = 0;
                        break;
                    }
            }
            if ($a_pc[$i]['baohiem'] == 1) {
                $stbhxh_dv += round($m_cb['bhxh_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhyt_dv += round($m_cb['bhyt_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stkpcd_dv += round($m_cb['kpcd_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhtn_dv += round($m_cb['bhtn_dv'] * $m_cb[$mapc] * $luongcb, 0);
            }
        }
        $m_cb['luongtn'] = round($m_cb['tonghs'] * $luongcb);
        //trường hợp đặc biêt mức lương khoán 
        if ($m_cb['mucluongbaohiem'] > 0) {
            $stbhxh_dv = round($m_cb['bhxh_dv'] * $m_cb['luongtn'], 0);
            $stbhyt_dv = round($m_cb['bhyt_dv'] * $m_cb['luongtn'], 0);
            $stkpcd_dv = round($m_cb['kpcd_dv'] * $m_cb['luongtn'], 0);
            $stbhtn_dv = round($m_cb['bhtn_dv'] * $m_cb['luongtn'], 0);
        }

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;

        $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
        return $m_cb;
    }

    //Cán bộ kiêm nhiệm các phụ cấp tính % theo các hệ số của thông tin lương chính
    function getHeSoPc_kiemnhiem($a_pc, $canbo, $m_cb, $luongcb = 0, $vk = true)
    {
        //dd($m_cb);
        $stbhxh_dv = 0;
        $stbhyt_dv = 0;
        $stkpcd_dv = 0;
        $stbhtn_dv = 0;
        $m_cb['tonghs'] = 0;
        $m_cb['luongtn'] = 0;
        $m_cb['luongcoban'] = $luongcb;
        if ($vk) {
            $m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);
        }
        //$m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);

        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            switch (getDbl($a_pc[$i]['phanloai'])) {
                case 0: {
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                case 1: { //số tiền (không tính chênh lệch)
                        //$m_cb['luongtn'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = $m_cb[$mapc] = 0;
                        break;
                    }
                case 2: { //phần trăm
                        if ($mapc != 'vuotkhung') { //vượt khung đã tính ở trên
                            $heso = 0;
                            foreach (explode(',', $a_pc[$i]['congthuc']) as $cthuc) {
                                if ($cthuc != '') {
                                    $heso += $canbo[$cthuc]; //Lấy hệ số cơ sở của thông tin lương chính
                                }
                            }
                            $m_cb[$mapc] = round($heso * $m_cb[$mapc] / 100, session('admin')->lamtron);
                        }
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                default: { //trường hợp còn lại (ẩn,...)
                        $m_cb[$mapc] = 0;
                        $m_cb[$mapc_st] = 0;
                        break;
                    }
            }
            if ($a_pc[$i]['baohiem'] == 1) {
                $stbhxh_dv += round($m_cb['bhxh_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhyt_dv += round($m_cb['bhyt_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stkpcd_dv += round($m_cb['kpcd_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhtn_dv += round($m_cb['bhtn_dv'] * $m_cb[$mapc] * $luongcb, 0);
            }
        }

        if ($m_cb['pthuong'] < 100) {
            $m_cb['tonghs'] = 0;
            //$tien = 0;
            for ($i = 0; $i < count($a_pc); $i++) {
                $mapc = $a_pc[$i]['mapc'];
                $mapc_st = 'st_' . $mapc;

                $m_cb[$mapc] = round($m_cb[$mapc] * $m_cb['pthuong'] / 100, session('admin')->lamtron);
                $m_cb[$mapc_st] = round($m_cb[$mapc_st] * $m_cb['pthuong'] / 100, 0);

                $m_cb['tonghs'] += $m_cb[$mapc];
                //$tien += $m_cb[$mapc_st];
            }
        }

        $m_cb['luongtn'] = round($m_cb['tonghs'] * $luongcb);
        //trường hợp đặc biêt mức lương khoán 
        if ($m_cb['mucluongbaohiem'] > 0) {
            $stbhxh_dv = round($m_cb['bhxh_dv'] * $m_cb['luongtn'], 0);
            $stbhyt_dv = round($m_cb['bhyt_dv'] * $m_cb['luongtn'], 0);
            $stkpcd_dv = round($m_cb['kpcd_dv'] * $m_cb['luongtn'], 0);
            $stbhtn_dv = round($m_cb['bhtn_dv'] * $m_cb['luongtn'], 0);
        }

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;

        $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;

        return $m_cb;
    }

    function getHeSoPc_nh($a_pc, $m_cb, $luongcb = 0)
    {
        $m_cb['tencanbo'] .= ' (nghỉ hưu)';
        for ($i_pc = 0; $i_pc < count($a_pc); $i_pc++) {
            $mapc = $a_pc[$i_pc]['mapc'];
            $mapc_st = 'st_' . $mapc;
            $m_cb['stbhxh_dv'] = 0;
            $m_cb['stbhyt_dv'] = 0;
            $m_cb['stkpcd_dv'] = 0;
            $m_cb['stbhtn_dv'] = 0;
            $m_cb['ttbh_dv'] = 0;
            $m_cb['tonghs'] = 0;
            $m_cb['luongtn'] = 0;
            $m_cb[$mapc] = 0;
            $m_cb[$mapc_st] = 0;
            $m_cb['luongcoban'] = $luongcb;
        }
        return $m_cb;
    }

    function getHeSoPc_Sub($a_pc, $m_cb, $m_cb_cu, $phanloai, $thang, $nam, $a_pcts = array())
    {
        $m_cb['maphanloai'] = $phanloai;
        $m_cb['thang'] = $thang;
        $m_cb['nam'] = $nam;
        $thang = 12 - $thang + 1;

        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            $m_cb[$mapc] = ($m_cb[$mapc] - $m_cb_cu[$mapc]) * $thang;
            $m_cb[$mapc_st] = ($m_cb[$mapc_st] - $m_cb_cu[$mapc_st]) * $thang;
        }

        $m_cb['tonghs'] = ($m_cb['tonghs'] - $m_cb_cu['tonghs']) * $thang;
        $m_cb['luongtn'] = ($m_cb['luongtn'] - $m_cb_cu['luongtn']) * $thang;

        $m_cb['stbhxh_dv'] = ($m_cb['stbhxh_dv'] - $m_cb_cu['stbhxh_dv']) * $thang;
        $m_cb['stbhyt_dv'] = ($m_cb['stbhyt_dv'] - $m_cb_cu['stbhyt_dv']) * $thang;
        $m_cb['stkpcd_dv'] = ($m_cb['stkpcd_dv'] - $m_cb_cu['stkpcd_dv']) * $thang;
        $m_cb['stbhtn_dv'] = ($m_cb['stbhtn_dv'] - $m_cb_cu['stbhtn_dv']) * $thang;
        $m_cb['ttbh_dv'] = ($m_cb['ttbh_dv'] - $m_cb_cu['ttbh_dv']) * $thang;
        return $m_cb;
    }

    function getHeSoPc_ts($m_cb, $a_pc, $a_pc_ts)
    {
        //mặc định ko đóng bảo hiểm
        $m_cb['stbhxh_dv'] = 0;
        $m_cb['stbhyt_dv'] = 0;
        $m_cb['stkpcd_dv'] = 0;
        $m_cb['stbhtn_dv'] = 0;
        $m_cb['ttbh_dv'] = 0;
        $m_cb['tencanbo'] .= ' (nghỉ thai sản)';
        $tonghs = 0;
        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            if (in_array($mapc, $a_pc_ts)) {
                $tonghs += $m_cb[$mapc];
            } else {
                $m_cb[$mapc] = $m_cb[$mapc_st] = 0;
            }
        }
        $m_cb['tonghs'] = $tonghs;
        $m_cb['luongtn'] = round($m_cb['tonghs'] * $m_cb['luongcoban'], 0);
        return $m_cb;
    }
    function getHeSoPc_kl($a_pc, $m_cb, $luongcb = 0, $vk = true)
    {
       
        $stbhxh_dv = 0;
        $stbhyt_dv = 0;
        $stkpcd_dv = 0;
        $stbhtn_dv = 0;
        $m_cb['tonghs'] = 0;
        $m_cb['luongtn'] = 0;
        $m_cb['luongcoban'] = $luongcb;
        $m_cb['tencanbo'] .= ' (kỷ luật)';
        if ($vk) {
            $m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);
        }
        // $m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);

        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            //Tính tỉ lệ hưởng lương cho cán bộ bị kỷ luật 03102023;

                $m_cb[$mapc] = round($m_cb[$mapc] * 0.5, session('admin')->lamtron);
         
            switch (getDbl($a_pc[$i]['phanloai'])) {
                case 0: {
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                case 1: { //số tiền (không tính chênh lệch)
                        //$m_cb['luongtn'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = $m_cb[$mapc] = 0;
                        break;
                    }
                case 2: { //phần trăm
                        if ($mapc != 'vuotkhung') { //vượt khung đã tính ở trên
                            $heso = 0;
                            foreach (explode(',', $a_pc[$i]['congthuc']) as $cthuc) {
                                if ($cthuc != '') {
                                    $heso += $m_cb[$cthuc];
                                }
                            }
                            $m_cb[$mapc] = round($heso * $m_cb[$mapc] / 100, session('admin')->lamtron);
                        }
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                default: { //trường hợp còn lại (ẩn,...)
                        $m_cb[$mapc] = 0;
                        $m_cb[$mapc_st] = 0;
                        break;
                    }
            }
            if ($a_pc[$i]['baohiem'] == 1) {
                $stbhxh_dv += round($m_cb['bhxh_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhyt_dv += round($m_cb['bhyt_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stkpcd_dv += round($m_cb['kpcd_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhtn_dv += round($m_cb['bhtn_dv'] * $m_cb[$mapc] * $luongcb, 0);
            }
        }
        $m_cb['luongtn'] = round($m_cb['tonghs'] * $luongcb);
        //trường hợp đặc biêt mức lương khoán 
        if ($m_cb['mucluongbaohiem'] > 0) {
            $stbhxh_dv = round($m_cb['bhxh_dv'] * $m_cb['luongtn'], 0);
            $stbhyt_dv = round($m_cb['bhyt_dv'] * $m_cb['luongtn'], 0);
            $stkpcd_dv = round($m_cb['kpcd_dv'] * $m_cb['luongtn'], 0);
            $stbhtn_dv = round($m_cb['bhtn_dv'] * $m_cb['luongtn'], 0);
        }

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;

        $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
        return $m_cb;
    }
}
