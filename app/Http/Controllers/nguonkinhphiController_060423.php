<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use App\dmthongtuquyetdinh;
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
use Illuminate\Support\Facades\Session;

class nguonkinhphiController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('madv', session('admin')->madv)->orderby('namns')->get();
            $lvhd = getLinhVucHoatDong(false);

            foreach ($model as $ct) {
                $ct->linhvuc = isset($lvhd[$ct->linhvuchoatdong]) ? $lvhd[$ct->linhvuchoatdong] : '';
            }
            //dd($model);
            $model_bl = bangluong::where('madv', session('admin')->madv)->where('phanloai', 'BANGLUONG')->orderby('nam')->orderby('thang')->get();
            $model_tt_df = dmthongtuquyetdinh::orderby('ngayapdung', 'desc')->first();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            //dd($model_tt_df);
            return view('manage.nguonkinhphi.index')
                ->with('furl', '/nguon_kinh_phi/')
                ->with('a_trangthai', getStatus())
                ->with('model', $model)
                ->with('model_bl', $model_bl)
                ->with('model_tt_df', $model_tt_df)
                ->with('a_nkp', getNguonKP(false))
                ->with('a_lvhd', $lvhd)
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
                ->where('namns', $inputs['namdt'])
                ->where('linhvuchoatdong', $inputs['linhvuchoatdong'])
                ->where('madv', session('admin')->madv)
                ->count();

            if ($chk > 0) {
                return view('errors.data_exist')
                    ->with('furl', '/nguon_kinh_phi/danh_sach');
            }

            $a_plct = getPLCTTongHop();
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            $gen = getGeneralConfigs();
            $a_pc = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem')
                ->where('madv', session('admin')->madv)->wherein('mapc', getColTongHop())->get()->toarray();
            $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();
            $masodv = session('admin')->madv . '_' . getdate()[0];
            $inputs['chenhlech'] = chkDbl($inputs['chenhlech']);

            $a_th = array_merge(array('macanbo', 'mact', 'macvcq', 'mapb', 'ngayden'), getColTongHop());
            $m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th, ['phanloai']))
                ->where('madv', session('admin')->madv)
                ->wherein('mact', $a_plct)
                //->get()->keyBy('macanbo')->toarray();
                ->get();
            $a_th = array_merge(array(
                'stt', 'ngaysinh', 'tencanbo', 'gioitinh', 'msngbac', 'bac',
                'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'ngaybc', 'ngayvao', 'lvhd', 'ngaytu', 'tnntungay', 'tnndenngay'
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

            /*
            dd($model);
            $a_cbn = hosothoicongtac::select('macanbo')->where('madv', $inputs['madv'])
                ->where(function ($qr) use ($ngaycuoithang) {
                    $qr->where('ngaynghi', '<=', $ngaycuoithang)->orWhereNull('ngaynghi');
                    //})->toSql();dd($a_cbn);
                })->get()->toarray();

            //$a_hoten = array_column($model->toarray(),'tencanbo','macanbo');

             *
             $model = hosocanbo::select($a_th)->where('madv', session('admin')->madv)
                ->where('theodoi','<', '9')
                ->get();
            $m_tamngung = hosotamngungtheodoi::where('madv', $inputs['madv'])->where('maphanloai', 'THAISAN')
                ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)->get();

            $a_khongluong = array_column(hosotamngungtheodoi::where('madv', $inputs['madv'])
                ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)
                ->where('maphanloai', 'KHONGLUONG')->get()->toarray(),'macanbo');

            $a_daingay = array_column(hosotamngungtheodoi::where('madv', $inputs['madv'])
                ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)
                ->where('maphanloai', 'DAINGAY')->get()->toarray(),'macanbo');
            */
            //$inputs['namdt']
            if (isset($inputs['thaisan'])) {
                $m_ts = hosotamngungtheodoi::where('madv', session('admin')->madv)->where('maphanloai', 'THAISAN')
                    ->whereBetween('ngayden', [Carbon::create($inputs['namdt'])->startOfYear(), Carbon::create($inputs['namdt'] + 1)->endOfYear()])
                    ->get();
            } else {
                $m_ts = new Collection();
            }
            //$a_pc_ts = array_column(dmphucap_thaisan::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
            $a_pc_ts = array_column(dmphucap_donvi::where('madv', session('admin')->madv)
                ->where('phanloai', '<', '3')->where('thaisan', '1')->get()->toarray(), 'mapc');
            //dd($a_pc_ts);
            //dd($model_thongtu->ngayapdung);
            $model = (new dataController())->getCanBo($model, $model_thongtu->ngayapdung, true, $model_thongtu->ngayapdung);
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

                if (isset($cb->ngaysinh)) {
                    $dt_ns = date_create($cb->ngaysinh);
                    $cb->nam_ns = (string) date_format($dt_ns, 'Y') + ($cb->gioitinh == 'Nam' ? $gen['tuoinam'] : $gen['tuoinu']);
                    $cb->thang_ns = date_format($dt_ns, 'm') + 1;
                    if ($cb->thang_ns > 12) {
                        $cb->thang_ns = '01';
                        $cb->nam_ns = strval($cb->nam_ns + 1);
                    }
                } else {
                    $cb->nam_ns = null;
                    $cb->thang_ns = null;
                }

                if (isset($cb->ngayden)) {
                    $dt_luong = new Carbon($cb->ngayden);
                    //                    if($dt_luong->day == 1){
                    //                        $dt_luong->addDay(-1);
                    //                    }
                    $cb->nam_nb = str_pad($dt_luong->year, 4, '0', STR_PAD_LEFT);
                    $cb->thang_nb = str_pad($dt_luong->month, 2, '0', STR_PAD_LEFT);
                } else {
                    $cb->nam_nb = null;
                    $cb->thang_nb = null;
                }

                if (isset($cb->tnndenngay)) {
                    $dt_nghe = new Carbon($cb->tnndenngay);
                    //                    if($dt_nghe->day == 1){
                    //                        $dt_nghe->addDay(-1);
                    //                    }
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
                if ($cb->luonghd != 0) {
                    $model->forget($key);
                }
            }


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

            // dd($m_nb);

            foreach ($m_cb_kn as $ct) {
                if (!isset($m_cb[$ct->macanbo])) {
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
                $a_kq = $ct->toarray();
                unset($a_kq['phanloai']);
                $m_cb[$ct->macanbo . '_' . $ct->phanloai] = $a_kq;
            }

            foreach ($m_nh as $key => $val) {
                $m_nh[$key] = $this->getHeSoPc_nh($a_pc, $m_nh[$key]);
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
                $m_nb[$key] = $this->getHeSoPc($a_pc, $m_nb[$key], $inputs['chenhlech']);
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
                    $m_tnn[$key] = $this->getHeSoPc($a_pc, $m_tnn[$key], $inputs['chenhlech'], false);
                } else {
                    $m_tnn[$key] = $this->getHeSoPc($a_pc, $m_tnn[$key], $inputs['chenhlech']);
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
                $m_cb[$key] = $this->getHeSoPc($a_pc, $m_cb[$key], $inputs['chenhlech']);
            }
            // dd($m_cb);
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
            //dd($a_luu);

            $a_data = array();
            $a_data_nl = array();
            $a_danghihuu = array();
            // dd($m_cb);
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
                    $a_data[] = $m_cb[$key];
                }
            }
            $a_dbhdnd = ['1536402868', '1536402870',];
            $a_cuv = ['1536459380', '1558600713', '1536459382', '1558945077',];

            $m_data = a_split($a_data, array('mact', 'macongtac'));
            $m_data = a_unique($m_data);

            // dd($a_data);
            //tính lại do lệnh với bảng lương
            for ($i = 0; $i < count($m_data); $i++) {
                $dutoan = a_getelement($a_data, array('mact' => $m_data[$i]['mact']));
                $m_data[$i]['linhvuchoatdong'] = $inputs['linhvuchoatdong'];
                $m_data[$i]['masodv'] = $masodv;
                $m_data[$i]['nhucau'] = 0;
                $m_data[$i]['daibieuhdnd'] = 0;
                $m_data[$i]['uyvien'] = 0;
                $m_data[$i]['boiduong'] = 0;
                $m_data[$i]['luongphucap'] = 0;
                $m_data[$i]['nghihuu'] = 0;
                $m_data[$i]['canbokct'] = 0;
                $m_data[$i]['boiduong'] = array_sum(array_column($dutoan, 'st_pcbdhdcu'));

                $m_data[$i]['daibieuhdnd'] = array_sum(array_column($dutoan, 'st_pcdbqh'));
                if (in_array($m_data[$i]['mact'], $a_dbhdnd)) {
                    $m_data[$i]['daibieuhdnd'] += array_sum(array_column($dutoan, 'st_hesopc'));
                }

                $m_data[$i]['uyvien'] = array_sum(array_column($dutoan, 'st_pcvk'));
                if (in_array($m_data[$i]['mact'], $a_cuv)) {
                    $m_data[$i]['uyvien'] += array_sum(array_column($dutoan, 'st_hesopc'));
                }

                $m_data[$i]['baohiem'] = array_sum(array_column($dutoan, 'ttbh_dv'));
                //dùng luongtn vì các phụ cấp tính theo số tiền đã cộng vào luongtn (ko tính vào hệ số)
                $m_data[$i]['luonghs'] = array_sum(array_column($dutoan, 'luongtn'))  - $m_data[$i]['boiduong'] - $m_data[$i]['daibieuhdnd'] - $m_data[$i]['uyvien'];
                switch ($m_data[$i]['macongtac']) {
                    case 'NGHIHUU': {
                            $m_data[$i]['nghihuu'] = $m_data[$i]['luonghs'];
                            break;
                        }
                    case 'KHONGCT': {
                            $m_data[$i]['canbokct'] = $m_data[$i]['luonghs'];
                            break;
                        }
                    default: { //BIENCHE, KHAC
                            $m_data[$i]['luongphucap'] = $m_data[$i]['luonghs'];
                            break;
                        }
                }
                $m_data[$i]['nhucau'] = $m_data[$i]['luonghs'] + $m_data[$i]['uyvien'] + $m_data[$i]['daibieuhdnd']
                    + $m_data[$i]['boiduong'] + $m_data[$i]['baohiem'];
            }

            // dd($m_data);

            $inputs['trangthai'] = 'CHOGUI';
            $inputs['maphanloai'] = session('admin')->maphanloai;
            $inputs['masodv'] = $masodv;
            $inputs['madv'] = session('admin')->madv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['namns'] = $inputs['namdt'];
            $inputs['nhucau'] = array_sum(array_column($m_data, 'nhucau'));
            $inputs['luongphucap'] = array_sum(array_column($m_data, 'luongphucap'));
            $inputs['nghihuu'] = array_sum(array_column($m_data, 'nghihuu'));
            $inputs['canbokct'] = array_sum(array_column($m_data, 'canbokct'));
            $inputs['boiduong'] = array_sum(array_column($m_data, 'boiduong'));
            $inputs['daibieuhdnd'] = array_sum(array_column($m_data, 'daibieuhdnd'));
            $inputs['uyvien'] = array_sum(array_column($m_data, 'uyvien'));
            $inputs['baohiem'] = array_sum(array_column($m_data, 'baohiem'));

            //lưu dữ liệu
            $a_col = array(
                'bac', 'bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'gioitinh', 'nam_nb', 'nam_ns', 'nam_tnn',
                'thang_nb', 'thang_ns', 'thang_tnn', 'ngayden', 'ngaytu', 'ngaysinh', 'tnndenngay', 'tnntungay', 'pcctp',
                'st_pcctp', 'nam_hh', 'thang_hh', 'ngaybc', 'ngayvao', 'lvhd'
            );
            // dd($a_data);
            $a_data_nl = unset_key($a_data_nl, $a_col);
            //dd($a_data_nl);
            foreach (array_chunk($a_data_nl, 10) as $data) {
                nguonkinhphi_nangluong::insert($data);
            }
            //dd($a_data);
            //chia nhỏ thành các mảng nhỏ 100 phần tử để insert
            $a_data = unset_key($a_data, $a_col);

            foreach (array_chunk($a_data, 10)  as $data) {
                nguonkinhphi_bangluong::insert($data);
            }
            $m_data = unset_key($m_data, array('luonghs', 'nopbh'));
            nguonkinhphi_chitiet::insert($m_data);
            nguonkinhphi::create($inputs);
            return redirect('/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function create_mau(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['sohieu'] = $inputs['sohieu_mau'];
            $inputs['linhvuchoatdong'] = $inputs['linhvuchoatdong_mau'];
            $inputs['chenhlech'] = chkDbl($inputs['chenhlech_mau']);
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($model_thongtu->ngayapdung);
            $inputs['namdt'] = chkDbl($model_thongtu->namdt) == 0 ? date('Y') : date_format($ngayapdung, 'Y');

            //Kiểm tra nếu có rồi thì ko tạo
            $chk = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('namns', $inputs['namdt'])
                ->where('linhvuchoatdong', $inputs['linhvuchoatdong'])
                ->where('madv', session('admin')->madv)
                ->count();

            if ($chk > 0) {
                return view('errors.data_exist')
                    ->with('furl', '/nguon_kinh_phi/danh_sach');
            }
            $masodv = session('admin')->madv . '_' . getdate()[0];
            $a_plct = getPLCTTongHop();
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');

            $model_pc = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem')
                ->where('madv', session('admin')->madv)->wherein('mapc', getColTongHop())->get();

            //->toarray();
            $a_pc_bh = array_column($model_pc->where('baohiem', '1')->toarray(), 'mapc');
            $a_pc = array();
            foreach ($model_pc as $pc) {
                $a_pc[] = $pc->mapc;
                $a_pc[] = 'st_' . $pc->mapc;
            }
            $a_th = array_merge(array(
                'macanbo', 'mact', 'macvcq', 'mapb', 'stt', 'tencanbo',
                'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv',
            ), $a_pc);

            $model = (new dataController())->getBangluong_ct_ar($inputs['thang'], [$inputs['mabl']], $a_th);

            foreach ($model as $cb) {
                $cb->trangthai = true;
                if (!in_array($cb->mact, $a_plct)) {
                    $cb->trangthai = false;
                    continue;
                }
                $cb->macongtac = $a_congtac[$cb->mact];
                $cb->masodv = $masodv;
                $cb->tonghs = 0;
                $cb->luongtn = 0;
                $cb->luongcoban = $inputs['chenhlech'];
                $tongbh = 0;
                foreach ($model_pc as $pc) {
                    $mapc = $pc->mapc;
                    $mapc_st = 'st_' . $pc->mapc;
                    if ($cb->$mapc == $cb->$mapc_st) { //số tiền == hệ số => gán 0
                        $cb->$mapc = 0;
                        $cb->$mapc_st = 0;
                    } else { //tính lại tổng hệ số do có trường hợp phụ cấp ko tổng hợp
                        $cb->$mapc_st = round($cb->$mapc * $inputs['chenhlech']);
                        $cb->tonghs += $cb->$mapc;
                        $cb->luongtn += $cb->$mapc_st;
                        if (in_array($mapc, $a_pc_bh)) {
                            $tongbh += $cb->$mapc_st;
                        }
                    }
                }
                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                //$cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $cb->stbhxh_dv = round($tongbh * $cb->bhxh_dv);
                $cb->stbhyt_dv = round($tongbh * $cb->bhyt_dv);
                $cb->stkpcd_dv = round($tongbh * $cb->kpcd_dv);
                $cb->stbhtn_dv = round($tongbh * $cb->bhtn_dv);
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
            }
            $model = $model->where('trangthai', true);
            $m_cb = $model->keyBy('macanbo')->toarray();

            $a_thang = array(
                array('thang' => '07', 'nam' => $inputs['namdt']),
                array('thang' => '08', 'nam' => $inputs['namdt']),
                array('thang' => '09', 'nam' => $inputs['namdt']),
                array('thang' => '10', 'nam' => $inputs['namdt']),
                array('thang' => '11', 'nam' => $inputs['namdt']),
                array('thang' => '12', 'nam' => $inputs['namdt'])
            );

            $a_data = array();
            for ($i = 0; $i < count($a_thang); $i++) {
                //lưu vào 1 mảng
                foreach ($m_cb as $key => $val) {
                    $m_cb[$key]['thang'] = $a_thang[$i]['thang'];
                    $m_cb[$key]['nam'] = $a_thang[$i]['nam'];
                    $a_data[] = $m_cb[$key];
                }
            }
            $a_dbhdnd = ['1536402868', '1536402870',];
            $a_cuv = ['1536459380', '1558600713', '1536459382', '1558945077',];

            $m_data = a_split($a_data, array('mact', 'macongtac'));
            $m_data = a_unique($m_data);
            //dd($a_data);
            //tính lại do lệnh với bảng lương
            for ($i = 0; $i < count($m_data); $i++) {
                $dutoan = a_getelement($a_data, array('mact' => $m_data[$i]['mact']));
                $m_data[$i]['linhvuchoatdong'] = $inputs['linhvuchoatdong'];
                $m_data[$i]['masodv'] = $masodv;
                $m_data[$i]['nhucau'] = 0;
                $m_data[$i]['daibieuhdnd'] = 0;
                $m_data[$i]['uyvien'] = 0;
                $m_data[$i]['boiduong'] = 0;
                $m_data[$i]['luongphucap'] = 0;
                $m_data[$i]['nghihuu'] = 0;
                $m_data[$i]['canbokct'] = 0;
                $m_data[$i]['boiduong'] = array_sum(array_column($dutoan, 'st_pcbdhdcu'));

                $m_data[$i]['daibieuhdnd'] = array_sum(array_column($dutoan, 'st_pcdbqh'));
                if (in_array($m_data[$i]['mact'], $a_dbhdnd)) {
                    $m_data[$i]['daibieuhdnd'] += array_sum(array_column($dutoan, 'st_hesopc'));
                }

                $m_data[$i]['uyvien'] = array_sum(array_column($dutoan, 'st_pcvk'));
                if (in_array($m_data[$i]['mact'], $a_cuv)) {
                    $m_data[$i]['uyvien'] += array_sum(array_column($dutoan, 'st_hesopc'));
                }

                $m_data[$i]['baohiem'] = array_sum(array_column($dutoan, 'ttbh_dv'));
                //dùng luongtn vì các phụ cấp tính theo số tiền đã cộng vào luongtn (ko tính vào hệ số)
                $m_data[$i]['luonghs'] = array_sum(array_column($dutoan, 'luongtn'))  - $m_data[$i]['boiduong'] - $m_data[$i]['daibieuhdnd'] - $m_data[$i]['uyvien'];
                switch ($m_data[$i]['macongtac']) {
                    case 'NGHIHUU': {
                            $m_data[$i]['nghihuu'] = $m_data[$i]['luonghs'];
                            break;
                        }
                    case 'KHONGCT': {
                            $m_data[$i]['canbokct'] = $m_data[$i]['luonghs'];
                            break;
                        }
                    default: { //BIENCHE, KHAC
                            $m_data[$i]['luongphucap'] = $m_data[$i]['luonghs'];
                            break;
                        }
                }
                $m_data[$i]['nhucau'] = $m_data[$i]['luonghs'] + $m_data[$i]['uyvien'] + $m_data[$i]['daibieuhdnd']
                    + $m_data[$i]['boiduong'] + $m_data[$i]['baohiem'];
            }

            //dd($m_data);

            $inputs['trangthai'] = 'CHOGUI';
            $inputs['maphanloai'] = session('admin')->maphanloai;
            $inputs['masodv'] = $masodv;
            $inputs['madv'] = session('admin')->madv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['namns'] = $inputs['namdt'];
            $inputs['nhucau'] = array_sum(array_column($m_data, 'nhucau'));
            $inputs['luongphucap'] = array_sum(array_column($m_data, 'luongphucap'));
            $inputs['nghihuu'] = array_sum(array_column($m_data, 'nghihuu'));
            $inputs['canbokct'] = array_sum(array_column($m_data, 'canbokct'));
            $inputs['boiduong'] = array_sum(array_column($m_data, 'boiduong'));
            $inputs['daibieuhdnd'] = array_sum(array_column($m_data, 'daibieuhdnd'));
            $inputs['uyvien'] = array_sum(array_column($m_data, 'uyvien'));
            $inputs['baohiem'] = array_sum(array_column($m_data, 'baohiem'));

            //lưu dữ liệu
            $a_col = array('bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'lvhd', 'trangthai');
            //dd($a_data);
            //chia nhỏ thành các mảng nhỏ 100 phần tử để insert
            $a_data = unset_key($a_data, $a_col);
            // dd($a_data);
            foreach (array_chunk($a_data, 100)  as $data) {
                nguonkinhphi_bangluong::insert($data);
            }

            $m_data = unset_key($m_data, array('luonghs', 'nopbh'));
            nguonkinhphi_chitiet::insert($m_data);
            nguonkinhphi::create($inputs);
            return redirect('/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['maso'])->first();
            $model_ct = nguonkinhphi_chitiet::where('masodv', $inputs['maso'])->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $model->sohieu)->first();
            // dd($model);
            if ($model != null) {
                $model->nhucaukp = $model->luongphucap + $model->daibieuhdnd + $model->canbokct
                    + $model->uyvien + $model->boiduong + $model->nghihuu + $model->baohiem;
                $model->nhucaupc = $model->thunhapthap + $model->diaban
                    + $model->tinhgiam + $model->nghihuusom
                    + $model->kpuudai + $model->kpthuhut;
            }

            return view('manage.nguonkinhphi.edit')
                ->with('furl', '/nguon_kinh_phi/')
                ->with('model', $model)
                ->with('model_ct', $model_ct)
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
            $inputs['luongphucap'] = chkDbl($inputs['luongphucap']);
            $inputs['daibieuhdnd'] = chkDbl($inputs['daibieuhdnd']);
            $inputs['canbokct'] = chkDbl($inputs['canbokct']);
            $inputs['uyvien'] = chkDbl($inputs['uyvien']);
            $inputs['boiduong'] = chkDbl($inputs['boiduong']);
            $inputs['nghihuu'] = chkDbl($inputs['nghihuu']);
            $inputs['baohiem'] = chkDbl($inputs['baohiem']);

            $inputs['tietkiem'] = chkDbl($inputs['tietkiem']);
            $inputs['hocphi'] = chkDbl($inputs['hocphi']);
            $inputs['vienphi'] = chkDbl($inputs['vienphi']);
            $inputs['nguonthu'] = chkDbl($inputs['nguonthu']);
            $inputs['nguonkp'] = chkDbl($inputs['nguonkp']);

            $inputs['thunhapthap'] = chkDbl($inputs['thunhapthap']);
            $inputs['diaban'] = chkDbl($inputs['diaban']);
            $inputs['tinhgiam'] = chkDbl($inputs['tinhgiam']);
            $inputs['nghihuusom'] = chkDbl($inputs['nghihuusom']);

            $inputs['tietkiem1'] = chkDbl($inputs['tietkiem1']);
            //$inputs['tietkiem2'] = chkDbl($inputs['tietkiem2']);
            //$inputs['thuchien1'] = chkDbl($inputs['thuchien1']);
            //$inputs['dutoan'] = chkDbl($inputs['dutoan']);
            //$inputs['dutoan1'] = chkDbl($inputs['dutoan1']);
            //$inputs['bosung'] = chkDbl($inputs['bosung']);
            //$inputs['caicach'] = chkDbl($inputs['caicach']);
            $inputs['kpthuhut'] = chkDbl($inputs['kpthuhut']);
            $inputs['kpuudai'] = chkDbl($inputs['kpuudai']);

            $inputs['nhucau'] = chkDbl($inputs['nhucaukp']) + chkDbl($inputs['nhucaupc']);
            $inputs['tongnhucau1'] = chkDbl($inputs['tongnhucau1']);
            $inputs['tongnhucau2'] = chkDbl($inputs['tongnhucau2']);

            //Mẫu 2b
            $inputs['tongsonguoi1'] = chkDbl($inputs['tongsonguoi1']);
            $inputs['tongsonguoi2'] = chkDbl($inputs['tongsonguoi2']);
            $inputs['tongsonguoi3'] = chkDbl($inputs['tongsonguoi3']);
            $inputs['quy1_1'] = chkDbl($inputs['quy1_1']);
            $inputs['quy1_2'] = chkDbl($inputs['quy1_2']);
            $inputs['quy1_3'] = chkDbl($inputs['quy1_3']);
            $inputs['quy2_1'] = chkDbl($inputs['quy2_1']);
            $inputs['quy2_2'] = chkDbl($inputs['quy2_2']);
            $inputs['quy2_3'] = chkDbl($inputs['quy2_3']);

            //Mẫu 2đ
            $inputs['tongsonguoi2015'] = chkDbl($inputs['tongsonguoi2015']);
            $inputs['tongsonguoi2017'] = chkDbl($inputs['tongsonguoi2017']);
            $inputs['quyluong'] = chkDbl($inputs['quyluong']);

            // mẫu 2e
            $inputs['tongsodonvi1'] = chkDbl($inputs['tongsodonvi1']);
            $inputs['tongsodonvi2'] = chkDbl($inputs['tongsodonvi2']);
            $inputs['quy_tuchu'] = chkDbl($inputs['quy_tuchu']);
            //dd($inputs);
            $model->update($inputs);

            return redirect('/nguon_kinh_phi/danh_sach');
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
            $model = nguonkinhphi::where('sohieu', $m_nkp->sohieu)->where('madv', session('admin')->madv)->get();
            // dd($model);
            foreach ($model as $nguon) {
                $nguon->trangthai = 'DAGUI';
                $nguon->macqcq = session('admin')->macqcq;
                $nguon->nguoiguidv = session('admin')->name;
                $nguon->ngayguidv = Carbon::now()->toDateTimeString();
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

    //bỏ vì thừa
    function printf_tt107_m3(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model_ct = nguonkinhphi_bangluong::where('masodv', $inputs['maso'])->get();
            $model_nl = nguonkinhphi_nangluong::where('masodv', $inputs['maso'])->get();
            $model = $model_ct->unique('macanbo'); //lấy ra các bản ghi đầu tiên với macanbo

            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['maso'])->first();
            $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model_ct->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');
            //dd($a_congtac);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model_ct->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            foreach ($model as $ct) {
                $bl = $model_ct->where('macanbo', $ct->macanbo);
                $nl = $model_nl->where('macanbo', $ct->macanbo);
                foreach ($m_pc as $pc) {
                    $ma = $pc['mapc'];
                    $ma_st = 'st_' . $pc['mapc'];
                    $ct->$ma = $bl->sum($ma) + $nl->sum($ma);
                    $ct->$ma_st = $bl->sum($ma_st) + $nl->sum($ma_st);
                }
                $ct->tonghs = $bl->sum('tonghs') + $nl->sum('tonghs');
                $ct->ttl = $bl->sum('ttl') + $nl->sum('ttl');
                $ct->luongtn = $bl->sum('luongtn') + $nl->sum('luongtn');
                $ct->stbhxh_dv = $bl->sum('stbhxh_dv') + $nl->sum('stbhxh_dv');
                $ct->stbhyt_dv = $bl->sum('stbhyt_dv') + $nl->sum('stbhyt_dv');
                $ct->stkpcd_dv = $bl->sum('stkpcd_dv') + $nl->sum('stkpcd_dv');
                $ct->stbhtn_dv = $bl->sum('stbhtn_dv') + $nl->sum('stbhtn_dv');
                $ct->ttbh_dv = $bl->sum('ttbh_dv') + $nl->sum('ttbh_dv');

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

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;
        $m_cb['luongtn'] = round($m_cb['tonghs'] * $luongcb);
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

    function getHeSoPc_290618($a_pc, $m_cb, $luongcb = 0, $vk = true)
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
        //tính lại vk
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

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;
        $m_cb['luongtn'] += round($m_cb['tonghs'] * $luongcb, 0);
        $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
        return $m_cb;
    }

    public function create_260822(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            // $ngayapdung = new Carbon($model_thongtu->ngayapdung);
            // $inputs['namdt'] = chkDbl($model_thongtu->namdt) == 0 ? date('Y'): date_format($ngayapdung, 'Y');
            $namns = $inputs['thang'] == 12 ? $inputs['nam'] + 1 : $inputs['nam'];
            // dd($inputs);
            //Kiểm tra nếu có rồi thì ko tạo
            $chk = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('namns', $namns)
                ->where('linhvuchoatdong', $inputs['linhvuchoatdong'])
                ->where('madv', session('admin')->madv)
                ->count();

            if ($chk > 0) {
                return view('errors.data_exist')
                    ->with('furl', '/nguon_kinh_phi/danh_sach');
            }

            $m_bl = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('manguonkp', $inputs['manguonkp'])
                ->where('phanloai', 'BANGLUONG')
                ->first();

            if ($m_bl == null) {
                return view('errors.data_error')
                    ->with('message', 'Bảng lương tháng ' . $inputs['thang'] . ' năm ' . $inputs['nam'] . ' không tồn tại. Bạn cần tạo bảng lương trước để có thể tạo nhu cầu kinh phí.')
                    ->with('furl', '/nguon_kinh_phi/danh_sach');
            }

            $m_bl1 = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang1'])
                ->where('nam', $inputs['nam1'])
                ->where('manguonkp', $inputs['manguonkp1'])
                ->where('phanloai', 'BANGLUONG')
                ->first();
            $m_bl_ct1 = (new dataController())->getBangluong_ct($m_bl1->thang ?? 'null', $m_bl1->mabl ?? 'null');

            $masodv = session('admin')->madv . '_' . getdate()[0];
            $m_bl_ct = (new dataController())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            $a_plct = dmphanloaict::where('tonghop', 1)->get();
            $a_mact = array_column($a_plct->toarray(), 'mact');
            $a_pc = array_column(dmphucap_donvi::select('mapc')
                ->where('madv', session('admin')->madv)->wherein('mapc', getColTongHop())->get()->toarray(), 'mapc');
            //thêm cán bộ chưa có từ $m_bl1 (phụ) vào $m_bl (chính)
            if ($m_bl_ct1 != null) {
                $a_canbo = array_column($m_bl_ct->toarray(), 'macanbo');
                foreach ($m_bl_ct1 as $key => $val) {
                    if (!in_array($val->macanbo, $a_canbo)) {
                        $m_bl_ct->add($val);
                    }
                }
            }
            foreach ($m_bl_ct as $key => $value) {
                if (!in_array($value->mact, $a_mact)) {
                    $m_bl_ct->pull($key);
                } else {
                    //chạy lại 1 vòng để hệ số, số tiền (do báo cáo lấy hệ số, số tiền)
                    foreach ($a_pc as $pc) {
                        $tenpc_st = 'st_' . $pc;
                        $value->$pc = round($value->$tenpc_st / $value->luongcoban, 10);
                    }
                }
            }

            //Tính lại lương theo mức lương cơ bản mới
            $a_hoten = array_column(hosocanbo::where('madv', session('admin')->madv)->get()->toarray(), 'tencanbo', 'macanbo');

            $inputs['muccu'] = getDbl($inputs['muccu']);
            $inputs['mucapdung'] = getDbl($inputs['mucapdung']);
            foreach ($m_bl_ct as $chitiet) {
                $chenhlech = round($inputs['mucapdung'] / $chitiet->luongcoban, 10);
                //thêm macongtac vào colection $m_bl_ct
                $a_plct = $a_plct->where('mact', $chitiet->mact)->first();
                $chitiet->macongtac = $a_plct->macongtac;

                $chitiet->thang = $inputs['thang'];
                $chitiet->nam = $inputs['nam'];
                //chưa xử lý cán bộ kiêm nhiệm
                if ($chitiet->tencanbo == '')
                    $chitiet->tencanbo = $a_hoten[$chitiet->macanbo] ?? '';

                if ($m_bl_ct->where('macanbo', $chitiet->macanbo)->count() > 1) {
                    $chitiet->macanbo = $chitiet->macanbo . '_' . $chitiet->id;
                }

                $chitiet->masodv = $masodv;
                $chitiet->tonghs = 0;
                $chitiet->ttl = 0;

                foreach ($a_pc as $pc) {
                    $tenpc_st = 'st_' . $pc;
                    $chitiet->$tenpc_st = round($inputs['mucapdung'] * $chitiet->$pc, 0);
                    $chitiet->tonghs += $chitiet->$pc;
                    $chitiet->ttl += $chitiet->$tenpc_st;
                }
                //quy đổi bảo hiểm thành hệ số
                $chitiet->bhxh_dv = round($chitiet->stbhxh_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->bhyt_dv = round($chitiet->stbhyt_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->kpcd_dv = round($chitiet->stkpcd_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->tongbh_dv = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->bhtn_dv + $chitiet->kpcd_dv;

                //quy đổi bảo hiểm thành số tiền theo mức lương cơ bản mới
                //tính riêng cho HD 68 do quy đổi hệ số hay bi làm tròn
                if ($chitiet->mact == '1506673585') {
                    $chitiet->stbhxh_dv = round($chitiet->stbhxh_dv *  $chenhlech, 0);
                    $chitiet->stbhyt_dv = round($chitiet->stbhyt_dv  *  $chenhlech, 0);
                    $chitiet->stbhtn_dv = round($chitiet->stbhtn_dv  *  $chenhlech, 0);
                    $chitiet->stkpcd_dv = round($chitiet->stkpcd_dv  *  $chenhlech, 0);
                } else {
                    $chitiet->stbhxh_dv = round($chitiet->bhxh_dv *  $inputs['mucapdung'], 0);
                    $chitiet->stbhyt_dv = round($chitiet->bhyt_dv  *  $inputs['mucapdung'], 0);
                    $chitiet->stbhtn_dv = round($chitiet->bhtn_dv  *  $inputs['mucapdung'], 0);
                    $chitiet->stkpcd_dv = round($chitiet->kpcd_dv  *  $inputs['mucapdung'], 0);
                }

                $chitiet->ttbh_dv = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stbhtn_dv + $chitiet->stkpcd_dv;
                //gán lại lương cơ bản theo mức mới
                $chitiet->luongcoban = $inputs['mucapdung'];
            }
            $a_m_bl_mact = array_column($m_bl_ct->toarray(), 'macongtac', 'mact');
            $a_dbhdnd = ['1536402868', '1536402870',];
            $a_cuv = ['1536459380', '1558600713', '1536459382', '1558945077',];
            $a_nhucau = [];
            //Tổng hợp lại nhu cầu cho cán bộ theo bảng lương
            foreach (array_unique(array_column($m_bl_ct->toarray(), 'mact')) as $data) {
                $nhucau = [];
                $canbo = $m_bl_ct->where('mact', $data);
                $nhucau['mact'] = $data;
                $nhucau['linhvuchoatdong'] = $inputs['linhvuchoatdong'];
                $nhucau['masodv'] = $masodv;
                $nhucau['nhucau'] = 0;
                $nhucau['daibieuhdnd'] = 0;
                $nhucau['uyvien'] = 0;
                $nhucau['boiduong'] = 0;
                $nhucau['luongphucap'] = 0;
                $nhucau['nghihuu'] = 0;
                $nhucau['canbokct'] = 0;
                // $nhucau['boiduong'] = array_sum(array_column($canbo->toarray(), 'st_pcbdhdcu'));
                // $nhucau['daibieuhdnd'] = array_sum(array_column($canbo->toarray(), 'st_pcdbqh'));
                // if (in_array($data, $a_dbhdnd)) {
                //     $nhucau['daibieuhdnd'] += array_sum(array_column($canbo->toarray(), 'st_hesopc'));
                // }

                // $nhucau['uyvien'] = array_sum(array_column($canbo->toarray(), 'st_pcvk'));
                // if (in_array($data, $a_cuv)) {
                //     $nhucau['uyvien'] += array_sum(array_column($canbo->toarray(), 'st_hesopc'));
                // }
                $thangs = $inputs['thang'] == 12 ? 12 : 12 - $inputs['thang'];
                //Tính lại tổng các phụ cấp
                foreach ($a_pc as $pc) {
                    $tenpc_st = 'st_' . $pc;
                    $nhucau[$pc] = $canbo->sum($pc) * $thangs;
                    $nhucau[$tenpc_st] = $canbo->sum($tenpc_st) * $thangs;
                }
                //Tính tổng hệ số, tổng tiền lương, tổng tiền thực nhận
                $nhucau['tonghs'] = $canbo->sum('tonghs') * $thangs;
                $nhucau['ttl'] = $canbo->sum('ttl') * $thangs;
                $nhucau['luongtn'] = $canbo->sum('luongtn') * $thangs;
                //Tính tổng hệ số bảo hiểm quy đổi
                $nhucau['bhxh_dv'] = $canbo->sum('bhxh_dv') * $thangs;
                $nhucau['bhyt_dv'] = $canbo->sum('bhyt_dv') * $thangs;
                $nhucau['bhtn_dv'] = $canbo->sum('bhtn_dv') * $thangs;
                $nhucau['kpcd_dv'] = $canbo->sum('kpcd_dv') * $thangs;
                $nhucau['tongbh_dv'] = $canbo->sum('tongbh_dv') * $thangs;
                //quy đổi bảo hiểm thành số tiền theo mức lương cơ bản
                $nhucau['stbhxh_dv'] = $canbo->sum('stbhxh_dv') * $thangs;
                $nhucau['stbhyt_dv'] = $canbo->sum('stbhyt_dv') * $thangs;
                $nhucau['stbhtn_dv'] = $canbo->sum('stbhtn_dv') * $thangs;
                $nhucau['stkpcd_dv'] = $canbo->sum('stkpcd_dv') * $thangs;
                $nhucau['ttbh_dv'] =  $canbo->sum('ttbh_dv') * $thangs;



                //Tính các cột lưu vào nguonkinhphi_chitiet
                $nhucau['baohiem'] = $nhucau['ttbh_dv'];
                $nhucau['daibieuhdnd'] = $canbo->sum('st_pcdbqh') * $thangs;
                if (in_array($data, $a_dbhdnd)) {
                    $nhucau['daibieuhdnd'] += $canbo->sum('st_hesopc') * $thangs;
                }
                $nhucau['uyvien'] = $canbo->sum('st_pcvk') * $thangs;
                if (in_array($data, $a_cuv)) {
                    $nhucau['uyvien'] += $canbo->sum('st_hesopc') * $thangs;
                }
                $nhucau['boiduong'] = $canbo->sum('st_pcbdhdcu') * $thangs;
                $nhucau['luonghs'] = $nhucau['luongtn']  - $nhucau['boiduong'] - $nhucau['daibieuhdnd'] - $nhucau['uyvien'];
                $nhucau['luongphucap'] = 0;
                $nhucau['nghihuu'] = 0;
                $nhucau['canbokct'] = 0;
                switch ($a_m_bl_mact[$data]) {
                    case 'NGHIHUU': {
                            $nhucau['nghihuu'] = $nhucau['luonghs'];
                            break;
                        }
                    case 'KHONGCT': {
                            $nhucau['canbokct'] = $nhucau['luonghs'];
                            break;
                        }
                    default: { //BIENCHE, KHAC
                            $nhucau['luongphucap'] = $nhucau['luonghs'];
                            break;
                        }
                }

                $nhucau['nhucau'] = $nhucau['luonghs'] + $nhucau['uyvien'] + $nhucau['daibieuhdnd']
                    + $nhucau['boiduong'] + $nhucau['baohiem'];
                //Lưu nhu cầu kinh phí
                $a_nhucau[] = $nhucau;
            }
            $a_data = $m_bl_ct->keyBy('macanbo')->toarray();
            // $a_th = $a_pc;
            // foreach ($a_pc as $pc) {
            //     $a_th[] = 'st_' . $pc;
            // }
            // $a_th = array_merge(array(
            //     'stt', 'macanbo', 'tencanbo', 'mact', 'macvcq', 'mapb',   'tonghs', 'ttl', 'luongcoban', 'masodv',
            //     'stbhxh_dv', 'stbhyt_dv', 'stbhtn_dv', 'stkpcd_dv', 'ttbh_dv',
            //     'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'tongbh_dv',
            // ), $a_th);

            // foreach ($a_data as $key => $val) {
            //     foreach (array_keys($a_data[$key]) as $k) {
            //         if (!in_array($k, $a_th))
            //             unset($a_data[$key][$k]);
            //     }
            // }

            $inputs['trangthai'] = 'CHOGUI';
            $inputs['maphanloai'] = session('admin')->maphanloai;
            $inputs['masodv'] = $masodv;
            $inputs['madv'] = session('admin')->madv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['namns'] = $namns;
            $inputs['nhucau'] = array_sum(array_column($a_nhucau, 'nhucau'));
            $inputs['luongphucap'] = array_sum(array_column($a_nhucau, 'luongphucap'));
            $inputs['nghihuu'] = array_sum(array_column($a_nhucau, 'nghihuu'));
            $inputs['canbokct'] = array_sum(array_column($a_nhucau, 'canbokct'));
            $inputs['boiduong'] = array_sum(array_column($a_nhucau, 'boiduong'));
            $inputs['daibieuhdnd'] = array_sum(array_column($a_nhucau, 'daibieuhdnd'));
            $inputs['uyvien'] = array_sum(array_column($a_nhucau, 'uyvien'));
            $inputs['baohiem'] = array_sum(array_column($a_nhucau, 'baohiem'));

            $a_col = array(
                'bac', 'bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'gioitinh', 'nam_nb', 'nam_ns', 'nam_tnn',
                'thang_nb', 'thang_ns', 'thang_tnn', 'ngayden', 'ngaytu', 'ngaysinh', 'tnndenngay', 'tnntungay', 'pcctp',
                'st_pcctp', 'nam_hh', 'thang_hh', 'ngaybc', 'ngayvao', 'lvhd', 'bhct', 'bhtn', 'bhxh', 'bhyt', 'congtac', 'ghichu', 'gttncn', 'hs_pccovu', 'hs_pctnn', 'hs_pcud61', 'hs_pcudn', 'hs_vuotkhung',
                'kpcd', 'luuheso', 'mabl', 'macongchuc', 'maso', 'ngaytl', 'phanloai', 'songaycong', 'songaylv', 'songaytruc', 'thangtl', 'thuetn',
                'tienthuong', 'tluong', 'tongbh_dv', 'tongngaylv', 'trichnop', 'manguonkp', 'id',

            );
            $a_data = unset_key($a_data, $a_col);
            foreach (array_chunk($a_data, 100)  as $data) {
                nguonkinhphi_bangluong::insert($data);
            }
            $a_col_nhucau = array(
                'heso', 'bhtn_dv', 'bhxh_dv', 'bhyt_dv', 'mabl', 'hesobl', 'hesopc', 'kpcd_dv', 'luonghd', 'luonghs', 'luongtn', 'pcbdhdcu', 'pccovu', 'pcct',
                'pccv', 'pcd', 'pcdang', 'pcdbn', 'pcdbqh', 'pcdd', 'pcdh', 'pcdith', 'pck', 'pckct', 'pckn', 'pckv', 'pclaunam', 'pcld', 'pclt', 'pctaicu',
                'pctdt', 'pcth', 'pcthni', 'pctn', 'pctnn', 'pctr', 'pcud61', 'pcudn', 'pcvk', 'pcxaxe', 'st_heso', 'st_hesobl', 'st_hesopc', 'st_luonghd',
                'st_pcbdhdcu', 'st_pccovu', 'st_pcct', 'st_pccv', 'st_pcd', 'st_pcdang', 'st_pcdbn', 'st_pcdbqh', 'st_pcdd', 'st_pcdh', 'st_pcdith', 'st_pck',
                'st_pckct', 'st_pckn', 'st_pckv', 'st_pclaunam', 'st_pcld', 'st_pclt', 'st_pctaicu', 'st_pctdt', 'st_pcth', 'st_pcthni', 'st_pctn', 'st_pctnn', 'st_pctr',
                'st_pcud61', 'st_pcudn', 'st_pcvk', 'st_pcxaxe', 'st_vuotkhung', 'stbhtn_dv', 'stbhxh_dv', 'stbhyt_dv', 'stkpcd_dv', 'tongbh_dv', 'tonghs', 'ttbh_dv', 'ttl', 'vuotkhung'
            );
            $a_nhucau = unset_key($a_nhucau, $a_col_nhucau);
            nguonkinhphi_chitiet::insert($a_nhucau);
            nguonkinhphi::create($inputs);
            return redirect('/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
