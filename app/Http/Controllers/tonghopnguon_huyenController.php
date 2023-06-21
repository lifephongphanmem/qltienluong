<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\dsdonviquanly;
use App\hosocanbo;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\nguonkinhphi_tinh;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_phucap;
use Illuminate\Support\Facades\Session;

class tonghopnguon_huyenController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            //$model_nguon = nguonkinhphi::where('macqcq', $madv)->where('trangthai','DAGUI')->get();
            $model_nguon_tinh = nguonkinhphi_tinh::where('madv', $madv)->get();
            $model_nguon = nguonkinhphi::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            })->where('trangthai', 'DAGUI')
                ->get();
            // dd($model_nguon);
            $model_nguon_huyen = nguonkinhphi_huyen::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            })->where('trangthai', 'DAGUI')
                ->get();
            //dd($model_nguon_huyen);
            //$model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            $model = dmthongtuquyetdinh::all();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->get();

            // $soluong = $model_donvi->count();

            foreach ($model as $dv) {
                $nam =  $dv->namdt = date('Y', strtotime($dv->ngayapdung)) ?? date('Y');
                // $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')
                //     ->where('macqcq', $madv)->where('madv', '<>', $madv)
                //     ->wherenotin('madv', function ($query) use ($madv, $nam) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->whereyear('ngaydung', '<=', $nam)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })->get();
                $a_donvicapduoi = [];
                //đơn vị nam=nam && macqcq=madv
                $model_dsql = dsdonviquanly::where('nam', $nam)->where('macqcq', $madv)->get();
                $a_donvicapduoi = array_unique(array_column($model_dsql->toarray(), 'madv'));
                //dd($a_donvicapduoi);

                //đơn vị có macqcq = madv (bang dmdonvi)
                $model_dmdv = dmdonvi::where('macqcq', $madv)
                    ->wherenotin('madv', function ($qr) use ($nam) {
                        $qr->select('madv')->from('dsdonviquanly')->where('nam', $nam)->distinct()->get();
                    }) //lọc các đơn vị đã khai báo trong dsdonviquanly
                    ->where('madv', '!=', $madv)
                    ->get();
                $a_donvicapduoi = array_unique(array_merge(array_column($model_dmdv->toarray(), 'madv'), $a_donvicapduoi));
                $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv', $a_donvicapduoi)->get();

                $soluong = count(array_diff($a_donvicapduoi, array_column($model_donvitamdung->toarray(), 'madv')));

                $nguon_huyen = $model_nguon_tinh->where('sohieu', $dv->sohieu)->first();

                if (isset($nguon_huyen)) {
                    //Đã tổng hợp dữ liệu
                    $dv->sldv = $soluong . '/' . $soluong;
                    $dv->masodv = $nguon_huyen->masodv;
                    $dv->trangthai = $nguon_huyen->trangthai;
                    //$dv->trangthai = 'DAGUI';
                } else {
                    //Chưa tổng hợp dữ liệu
                    $a_madv = array_column($model_donvi->toarray(), 'madv');
                    $m_sl = $model_nguon->where('sohieu', $dv->sohieu)->wherein('madv', $a_madv)->unique('madv');
                    $sl = $m_sl->count();
                    $sl_huyen = $model_nguon_huyen->where('sohieu', $dv->sohieu)->count();
                    // $dv->sldv = $sl+$sl_huyen . '/' . $soluong;
                    $dv->sldv = $sl + $sl_huyen . '/' . $soluong;
                    // $dv->masodv = null;
                    if ($sl == 0) {
                        $dv->trangthai = 'CHUADL';
                    } elseif ($sl < $soluong) {
                        $dv->trangthai = 'CHUADAYDU';
                    } elseif ($sl >= $soluong) {
                        $dv->trangthai = 'CHUAGUI';
                    } else {
                        $dv->trangthai = 'CHUATAO';
                    }

                    foreach ($m_sl as $val) {
                        if ($val->masot == 'TRALAI') {
                            $dv->trangthai = 'TRALAI';
                        }
                        $dv->masodv = $val->masodv;
                    }
                }
            }
            $inputs['soluong'] = $soluong;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['macqcq'] = $madv;

            return view('functions.tonghopnguon.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('inputs', $inputs)
                ->with('furl', '/chuc_nang/xem_du_lieu/nguon/huyen')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/huyen')
                ->with('furl_th', '/chuc_nang/tong_hop_nguon/huyen/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI

        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            //$model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            //dd($model->toarray());
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            $madv = $model->madv;
            $phanloai = dmdonvi::where('madv', $madv)->first()->phanloaitaikhoan;

            if ($phanloai == 'SD') {
                nguonkinhphi::where('sohieu', $model->sohieu)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
            } else {
                nguonkinhphi_khoi::where('sohieu', $model->sohieu)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
            }

            return redirect('/chuc_nang/xem_du_lieu/nguon/huyen?sohieu=' . $model->sohieu . '&trangthai=ALL&phanloai=ALL');
        } else
            return view('errors.notlogin');
    }

    function dulieu_dvql(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model = nguonkinhphi::where('madv', $inputs['madv'])->where('sohieu', $inputs['sohieu'])->first();
            if ($model == null) {
                $model = new nguonkinhphi();
                $model->madv = $inputs['madv'];
                $model->sohieu = $inputs['sohieu'];
                $model->masodv = session('admin')->madv . '_' . getdate()[0];
                $model->trangthai = 'CHOGUI';
                $model->linhvuchoatdong = 'QLNN';
                //$model->macqcq = session('admin')->macqcq;
                $model->nangcap_phucap = 1;
            }
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $model->sohieu)->first();
            return view('functions.tonghopnguon.huyen.edit')
                ->with('furl', '/chuc_nang/tong_hop_nguon/huyen/')
                ->with('model', $model)
                ->with('m_thongtu', $m_thongtu)
                ->with('a_nhucau', array_merge(getNhomNhuCauKP('KVHCSN'), getNhomNhuCauKP('KVXP')))
                ->with('a_ct', getPhanLoaiCT(false))
                ->with('nam', date_format(date_create($m_thongtu->ngayapdung), 'Y'))
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function luu_dulieu_dvql(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
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
                'quyluonggiam_2k',
                //Mẫu 2d
                'sothonbiengioi_2d',
                'sothontrongdiem_2d',
                'sothonconlai_2d',
                'sotoconlai_2d',
                //Mẫu 4a
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
                //Mẫu 2c
                'soluongqt_2c',
                'sotienqt_2c',
                'soluongcanbo_2c',
                'hesoluong_2c',
                'phucapchucvu_2c',
                'phucapvuotkhung_2c',
                'phucaptnn_2c',
            ];
            foreach ($a_truong  as $truong) {
                if (isset($inputs[$truong]))
                    $inputs[$truong] = chkDbl($inputs[$truong]);
            }

            if ($model == null) {
                nguonkinhphi::create($inputs);
            } else {
                $model->update($inputs);
            }
            return redirect('/chuc_nang/tong_hop_nguon/huyen/index');
        }
    }
    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $madv = session('admin')->madv;
            $model_nguon_huyen = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->where('madv', $madv)->first();
            // dd($model_nguon_huyen);
            //$model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])->where('macqcq', $madv)->get();
            if ($model_nguon_huyen != null) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_huyen->trangthai = 'DAGUI';
                $model_nguon_huyen->nguoilap = session('admin')->name;
                $model_nguon_huyen->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_huyen->save();
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                nguonkinhphi::where('sohieu', $inputs['sohieu'])->where('macqcq', $madv)
                    ->update(['masot' => $inputs['masodv'], 'trangthai' => 'DAGUI']);

                //nguonkinhphi_huyen::create($inputs);
                nguonkinhphi_tinh::create($inputs);
            }
            return redirect('/chuc_nang/tong_hop_nguon/huyen/index');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $model_ct = nguonkinhphi_bangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();
            if (count($model_ct) > 0) {
                $model = $model_ct->unique('macanbo');
                $model_thongtin = nguonkinhphi::where('masodv', $inputs['maso'])->first();
            }
            // dd($model);
            else {
                $check = nguonkinhphi_huyen::where('masodv', $inputs['maso'])->first();
                if (isset($check)) {
                    $model_nkp = nguonkinhphi::where('masoh', $inputs['maso'])->get();
                    $model_ct = nguonkinhphi_bangluong::wherein('masodv', a_unique(array_column($model_nkp->toarray(), 'masodv')))->orderby('stt')->get();
                    $model_thongtin = dmdonvi::where('madv', $check->madv)->first();
                    $model = $model_ct->unique('macanbo');
                }
            }
            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();

            //dd($a_ct);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $a_phucap = array();
            $col = 0;
            if (isset($model_thongtin)) {
                $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
                $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();
                foreach ($m_pc as $ct) {
                    if ($model->sum($ct['mapc']) > 0) {
                        $a_phucap[$ct['mapc']] = $ct['report'];
                        $col++;
                    }
                }

                $thongtin = array(
                    'nguoilap' => session('admin')->name,
                    'namns' => $model_thongtin->namns
                );
            } else {
                $thongtin = array(
                    'nguoilap' => session('admin')->name,
                    'namns' => ''
                );
                $m_dv = [];
            }


            if (isset($model)) {
                $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');

                foreach ($model as $ct) {
                    $bl = $model_ct->where('macanbo', $ct->macanbo);
                    foreach ($m_pc as $pc) {
                        $ma = $pc['mapc'];
                        $ma_st = 'st_' . $pc['mapc'];
                        $ct->$ma = $bl->sum($ma);
                        $ct->$ma_st = $bl->sum($ma_st);
                    }
                    $ct->tonghs = $bl->sum('tonghs');
                    $ct->ttl = $bl->sum('luongtn');
                    $ct->stbhxh_dv = $bl->sum('stbhxh_dv');
                    $ct->stbhyt_dv = $bl->sum('stbhyt_dv');
                    $ct->stkpcd_dv = $bl->sum('stkpcd_dv');
                    $ct->stbhtn_dv = $bl->sum('stbhtn_dv');
                    $ct->ttbh_dv = $bl->sum('ttbh_dv');

                    $ct->tencanbo = str_replace('(nghỉ thai sản)', '', $ct->tencanbo);
                    $ct->tencanbo = str_replace('(nghỉ hưu)', '', $ct->tencanbo);
                    $ct->tencanbo = trim($ct->tencanbo);
                }
            } else {
                $a_congtac = [];
                $model = [];
            }



            //dd($model);


            return view('reports.nguonkinhphi.huyen.bangluong_m2')
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

    public function getlydo(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::select('lydo')->where('masodv', $inputs['masodv'])->where('sohieu', $inputs['sohieu'])->first();
            return response()->json($model);
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            //dd($m_nguonkp);
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');

            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_phucap::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];
            }

            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap::orderby('stt')->get()->toarray();
            //Lấy phụ cấp
            foreach ($m_pc as $ct) {
                if ($m_chitiet->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }
            //dai dữ liêu
            $m_linhvuchoatdong = dmkhoipb::wherein('makhoipb', array_unique(array_column($m_chitiet->toArray(), 'linhvuchoatdong')))->get();

            $model_canbo = $m_chitiet->map(function ($data) {
                return collect($data->toArray())
                    ->only(['linhvuchoatdong', 'madv', 'tendv'])
                    ->all();
            });
            //dd($m_chitiet);
            $a_dsdonvi = a_unique($model_canbo->toArray());

            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $m_dv = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //$a_dv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            return view('reports.nguonkinhphi.huyen.tonghopnhucau')
                //->with('thongtin', $thongtin)
                ->with('m_linhvuchoatdong', $m_linhvuchoatdong)
                ->with('m_chitiet', $m_chitiet)
                ->with('a_dsdonvi', $a_dsdonvi)
                //->with('a_donvi', $a_dv)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau2a(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //$chitiet->madvbc = $a_madvbc[$chitiet->madv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }

            $a_pc_th = dmphucap::wherenotin('mapc', ['heso'])->get();

            $a_phucap = array();
            $col = 0;
            $a_phucap_st = array();
            foreach ($a_pc_th as $ct) {
                if ($m_chitiet->sum($ct->mapc) > 0) {
                    $mapc_st = 'st_' . $ct->mapc;
                    $a_phucap[$ct->mapc] = $ct->form;
                    $a_phucap_st[$mapc_st] = $ct->form;
                    if ($ct->mapc !== 'heso') {
                        $col++;
                    }
                }
            }
            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            //$dulieu_pI = $m_chitiet->where('maphanloai', '<>', 'KVXP');
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '2') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_I[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_I[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_I[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_I[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_I[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_I[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }

                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            //Vòng cấp độ 1
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = 0;

                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_I[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_I[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_I[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_I[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_I[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_I[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }


                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            //Vòng cấp độ 9
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '9') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;
                    $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_I[$key]['chenhlech01thang'] = 0;
                    $ar_I[$key]['chenhlech06thang'] = 0;
                    $ar_I[$key]['canbo_congtac'] = 0;
                    $ar_I[$key]['canbo_dutoan'] = 0;
                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            //
            //
            //Tính toán số liệu phần II
            $ar_II = getChuyenTrach();
            $dulieu_pII = $m_chitiet->where('nhomnhucau', 'CANBOCT');

            //Vòng cấp độ 3
            foreach ($ar_II as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_II[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_II[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_II[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_II[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                }
            }


            //Tính toán số liệu phần III
            $ar_III = getHDND();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');


            //Vòng cấp độ 3
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pIII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_III[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_III[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_III[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_III[$key]['canbo_congtac'] = $ar_III[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_III[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_III[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_III[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_III[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_III[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_III[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_III[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_III[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_III[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_III[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_III[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_III[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_III[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_III[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_III[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_III[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_III[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_III[$k]['solieu_moi']['tongcong'];

                        $ar_III[$key]['canbo_congtac'] += $ar_III[$k]['canbo_congtac'];
                        $ar_III[$key]['canbo_dutoan'] += $ar_III[$k]['canbo_dutoan'];
                    }

                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;

                    $ar_III[$key]['solieu'] = $a_solieu;
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán số liệu phần IV
            $ar_IV = getCapUy();
            $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY');

            $aIV_plct = getCapUy_plct();
            foreach ($dulieu_pIV as $key => $value) {
                if (count($aIV_plct) > 0 && !in_array($value->mact, $aIV_plct))
                    $dulieu_pIV->forget($key);
            }

            //Vòng cấp độ 3
            foreach ($ar_IV as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pIV;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_IV[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_IV[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_IV[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_IV as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_IV[$key]['canbo_congtac'] = $ar_IV[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_IV[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_IV[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_IV[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_IV[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_IV[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_IV[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_IV[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_IV[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_IV[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_IV[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_IV[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_IV[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_IV[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_IV[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_IV[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_IV[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_IV[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_IV[$k]['solieu_moi']['tongcong'];

                        $ar_IV[$key]['canbo_congtac'] += $ar_IV[$k]['canbo_congtac'];
                        $ar_IV[$key]['canbo_dutoan'] += $ar_IV[$k]['canbo_dutoan'];
                    }

                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;

                    $ar_IV[$key]['solieu'] = $a_solieu;
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_tonghop_ct);
            return view('reports.thongtu78.donvi.mau2a2')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)

                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('a_phucap', $a_phucap)
                ->with('a_phucap_st', $a_phucap_st)
                ->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2b(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            $a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');


            $ar_I = array();

            $ar_I[0] = array(
                'val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch',
                'songuoi' => $m_nguonkp->sum('tongsonguoi1'),
                'quy1' => $m_nguonkp->sum('quy1_1'),
                'quy2' => $m_nguonkp->sum('quy2_1'),

            );

            $ar_I[1] = array(
                'val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng',
                'songuoi' => $m_nguonkp->sum('tongsonguoi2'),
                'quy1' => $m_nguonkp->sum('quy1_2'),
                'quy2' => $m_nguonkp->sum('quy2_2'),
            );
            $ar_I[2] = array(
                'val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại',
                'songuoi' => $m_nguonkp->sum('tongsonguoi3'),
                'quy1' => $m_nguonkp->sum('quy1_3'),
                'quy2' => $m_nguonkp->sum('quy2_3'),
            );

            $a_It = array(
                'cb' => 0,
                'quy09' => 0,
                'quy76' => 0,
                'quytang' => 0,
                'bhyt' => 0,
                'tongquy' => 0
            );

            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2b')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
        } else
            return view('errors.notlogin');
    }

    function mau2c(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();

            $luongcb = 60000;
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_Igr[] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_Igr[] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_Igr[] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_Igr[] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_Igr[] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_Igr[] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_Igr[] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_Igr[] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            } else {
                $ar_I[] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_I[] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_I[] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_I[] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_I[] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_I[] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_I[] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_I[] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_I[] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_I[] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_I[] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_I[] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_I[] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            }

            $a_It = array(
                'dt' => 0,
                'hstl' => 0,
                'hspc' => 0,
                'cl' => 0,
                'nc' => 0
            );

            for ($i = 0; $i < count($ar_I); $i++) {

                $ar_I[$i]['dt'] = 0;
                $ar_I[$i]['hstl'] = 0;
                $ar_I[$i]['hspc'] = 0;
                $ar_I[$i]['cl'] = 0;
                $ar_I[$i]['nc'] = 0;
            }

            $ar_II = array();


            $ar_II['dt'] = 0;
            $ar_II['hstl'] = 0;
            $ar_II['hspc'] = 0;
            $ar_II['cl'] = 0;
            $ar_II['nc'] = 0;

            return view('reports.thongtu78.huyen.mau2c')
                ->with('m_dv', $m_dv)
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BHTN');
        } else
            return view('errors.notlogin');
    }

    function mau2d(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');

            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');

            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            //Số liệu cho cán bộ không chuyên trách
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];

                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            $m_chitiet = $m_chitiet->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOKCT');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
            }
            $m_nguonkp = $m_nguonkp->where('maphanloai', 'KVXP');

            $a_It = array(
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            );

            $ar_I = array();
            $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn',);
            //
            $m_ct_1 = $m_chitiet->where('phanloaixa', 'XL1');
            $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                'tdv' => $m_nguon_1->count(),
                'mk' => 20.3,
                'mk2' => 16,
                'dt' => $m_ct_1->sum('canbo_congtac'),
                'kqpc' => round($m_nguon_1->count() * 20.3 * 1.49, 5),
                'bhxh' => round($m_ct_1->sum('canbo_congtac') * 0.14 * 1.49, 5),
                'kqpct7' => round($m_nguon_1->count() * 16 * 1.8, 5),
                'tong' => 0
            ]);
            //
            $m_ct_2 = $m_chitiet->where('phanloaixa', 'XL2');
            $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                'tdv' => $m_nguon_2->count(),
                'mk' => 18.6,
                'mk2' => 13.7,
                'dt' => $m_ct_2->sum('canbo_congtac'),
                'kqpc' => round($m_nguon_2->count() * 18.6 * 1.49, 5),
                'bhxh' => round($m_ct_2->sum('canbo_congtac') * 0.14 * 1.49, 5),
                'kqpct7' => round($m_nguon_2->count() * 13.7 * 1.8, 5),
                'tong' => 0
            ]);
            //
            $m_ct_3 = $m_chitiet->where('phanloaixa', 'XL3');
            $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                'tdv' => $m_nguon_3->count(),
                'mk' => 17.6,
                'mk2' => 11.4,
                'dt' => $m_ct_3->sum('canbo_congtac'),
                'kqpc' => round($m_nguon_3->count() * 17.6 * 1.49, 5),
                'bhxh' => round($m_ct_3->sum('canbo_congtac') * 0.14 * 1.49, 5),
                'kqpct7' => round($m_nguon_3->count() * 11.4 * 1.8, 5),
                'tong' => 0
            ]);
            //Tổng phân loại xã
            $ar_I[0]['solieu'] = [
                'tdv' =>  $ar_I[1]['solieu']['tdv'] + $ar_I[2]['solieu']['tdv'] + $ar_I[3]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'dt' => $ar_I[1]['solieu']['dt'] + $ar_I[2]['solieu']['dt'] + $ar_I[3]['solieu']['dt'],
                'kqpc' => $ar_I[1]['solieu']['kqpc'] + $ar_I[2]['solieu']['kqpc'] + $ar_I[3]['solieu']['kqpc'],
                'bhxh' => $ar_I[1]['solieu']['bhxh'] + $ar_I[2]['solieu']['bhxh'] + $ar_I[3]['solieu']['bhxh'],
                'kqpct7' => $ar_I[1]['solieu']['kqpct7'] + $ar_I[2]['solieu']['kqpct7'] + $ar_I[3]['solieu']['kqpct7'],
                'tong' => 0
            ];


            $ar_I[4] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tỏ dân phố', 'solieu' => [
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            ]);

            $ar_I[5] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 5 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 5,
                'mk2' => 5,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 5 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[7] = array('val' => 'DBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn có 350 hộ gia đình trở lên,  xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[8] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => '- Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 3,
                'mk2' => 3,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[9] = array('val' => 'TK,TDP', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại', 'solieu' => [
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            ]);
            $ar_I[10] = array('val' => 'TK', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonconlai_2d'),
                'mk' => 3,
                'mk2' => 3,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[11] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                'mk' => 3,
                'mk2' => 3,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);

            $a_It = array(
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            );


            //dd($ar_I);
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau2d')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2dd(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
            }

            $m_qlnn = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN']);
            $m_sunghiep = $m_nguonkp->wherenotin('linhvuchoatdong', ['QLNN']);

            $ar_I = array();
            $ar_I[0] = array('val' => 'QLNN', 'tt' => 'I', 'noidung' => 'Quản lý nhà nước');
            $ar_I[0]['solieu'] = [
                'tongsonguoi2015' => $m_qlnn->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_qlnn->sum('tongsonguoi2017'),
                'quyluong' => $m_qlnn->sum('quyluong'),
                'soluonghientai_2dd' => $m_qlnn->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_qlnn->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_qlnn->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_qlnn->sum('quyluongtietkiem_2dd'),
            ];
            $ar_I[1] = array('val' => 'SNCL', 'tt' => 'II', 'noidung' => 'Sự nghiệp công lập');
            $ar_I[1]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[2] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[2]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[3] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[3]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[4] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[4]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[5] = array('val' => 'NGANSACH', 'tt' => '4', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            $ar_I[5]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('quyluongtietkiem_2dd'),
            ];

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau2dd')
                ->with('inputs', $inputs)
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2e(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
            }

            $ar_I = array();

            $ar_I[2] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[2]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $ar_I[3] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[3]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $ar_I[4] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[4]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $ar_I[5] = array('val' => 'NGANSACH', 'tt' => '4', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            $ar_I[5]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau2e')
                ->with('inputs', $inputs)
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('pageTitle', 'Báo cáo nguồn thực hiện CCTL tiết kiệm từ việc thay đổi cơ chế tự chủ');
        } else
            return view('errors.notlogin');
    }

    function mau2g(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_chitiet);
            $luongcb = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'HOPDONG');
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);

                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '2') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;


                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];


                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];



                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }
                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }
            //Vòng cấp độ 1
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = 0;

                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;



                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ
                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];


                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }

                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }

            //
            unset($ar_I[1]);
            $ar_I[0]['noidung'] = 'TỔNG CỘNG';
            //dd($ar_I) ;    

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2g')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)

                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2h(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $chitiet->tongluong_2h = $chitiet->hesophucap_2h + $chitiet->hesoluong_2h;
                $chitiet->chenhlech_2h = $chitiet->tonghesophucapqd244_2h - $chitiet->tonghesophucapnd61_2h;
                $chitiet->quyluong_2h = $chitiet->chenhlech_2h * 12 * 1800000;
            }

            //dd($m_nguonkp);
            $m_dshuyen = dmdonvibaocao::where('level', 'H')->get();
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_nguonkp);
            return view('reports.thongtu78.huyen.mau2h')
                ->with('m_chitiet', $m_nguonkp)
                ->with('m_dshuyen', $m_dshuyen)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2i(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $chitiet->tongluong_2i = $chitiet->hesophucap_2i + $chitiet->hesoluong_2i;
                $chitiet->chenhlech_2i = $chitiet->tongluong_2i * 0.7;
                $chitiet->quyluong_2i = $chitiet->chenhlech_2i * 12 * 1800000;
            }

            //dd($m_nguonkp);
            $m_dshuyen = dmdonvibaocao::where('level', 'H')->get();
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_nguonkp);
            return view('reports.thongtu78.huyen.mau2i')
                ->with('m_chitiet', $m_nguonkp)
                ->with('m_dshuyen', $m_dshuyen)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP THU HÚT TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2k(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }

            $luongcb = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            $m_chitiet = $m_chitiet->where('nhomnhucau', 'CANBOCT');

            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $dulieu = $m_chitiet->where('madv', $chitiet->madv);
                if ($dulieu->count() == 0) {
                    $m_nguonkp->forget($key);
                    continue;
                }

                $chitiet->tongquyluonggiam_2k = $chitiet->quyluonggiam_2k * 6;

                $chitiet->heso = $dulieu->sum('heso');
                $chitiet->st_heso = round($chitiet->heso * $luongcb);

                $chitiet->tongbh_dv = $dulieu->sum('tongbh_dv');
                $chitiet->ttbh_dv = round(($dulieu->sum('ttbh_dv') / $chenhlech) * $luongcb);

                $chitiet->tongpc = $dulieu->sum('tonghs') - $chitiet->heso;
                $chitiet->st_tongpc = round($chitiet->tongpc * $luongcb);
                $chitiet->tongcong = $chitiet->st_tongpc + $chitiet->st_heso + $chitiet->ttbh_dv;


                $chitiet->canbo_congtac = $dulieu->sum('canbo_congtac');
                $chitiet->canbo_dutoan = $dulieu->sum('canbo_dutoan');
            }
            //Tính toán số liệu phần I
            $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);
            $ar_I[1] = array('style' => '', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2k' => $m_xl1->count(),
                'qd92_2k' => 25,
                'tongqd92_2k' => 25 * $m_xl1->count(),
                'tongbienche_2k' => $m_xl1->sum('canbo_congtac'),
                'trungbinhheso_2k' => round($m_xl1->sum('st_heso') / chkDiv0($m_xl1->sum('canbo_congtac')), 6),
                'trungbinhphucap_2k' => round($m_xl1->sum('st_tongpc') / chkDiv0($m_xl1->sum('canbo_congtac')), 6),
                'trungbinhdonggop_2k' => round($m_xl1->sum('ttbh_dv') / chkDiv0($m_xl1->sum('canbo_congtac')), 6),
                'qd34_2k' => 23,
                'tongqd34_2k' => 23 * $m_xl1->count(),
                'soluonggiam_2k' => $m_xl1->sum('soluonggiam_2k'),
                'quyluonggiam_2k' => $m_xl1->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl1->sum('tongquyluonggiam_2k'),
            ];

            $ar_I[2] = array('style' => '', 'tt' => '2', 'noidung' => 'Xã loại 2',);
            $m_xl2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2]['solieu'] = [
                'soluongdonvi_2k' => $m_xl2->count(),
                'qd92_2k' => 23,
                'tongqd92_2k' => 23 * $m_xl2->count(),
                'tongbienche_2k' => $m_xl2->sum('canbo_congtac'),
                'trungbinhheso_2k' => round($m_xl2->sum('st_heso') / chkDiv0($m_xl2->sum('canbo_congtac')), 6),
                'trungbinhphucap_2k' => round($m_xl2->sum('st_tongpc') / chkDiv0($m_xl2->sum('canbo_congtac')), 6),
                'trungbinhdonggop_2k' => round($m_xl2->sum('ttbh_dv') / chkDiv0($m_xl2->sum('canbo_congtac')), 6),
                'qd34_2k' => 21,
                'tongqd34_2k' => 21 * $m_xl2->count(),
                'soluonggiam_2k' => $m_xl2->sum('soluonggiam_2k'),
                'quyluonggiam_2k' => $m_xl2->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl2->sum('tongquyluonggiam_2k'),
            ];

            $ar_I[3] = array('style' => '', 'tt' => '3', 'noidung' => 'Xã loại 3',);
            $m_xl3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3]['solieu'] = [
                'soluongdonvi_2k' => $m_xl3->count(),
                'qd92_2k' => 23,
                'tongqd92_2k' => 23 * $m_xl3->count(),
                'tongbienche_2k' => $m_xl3->sum('canbo_congtac'),
                'trungbinhheso_2k' => round($m_xl3->sum('st_heso') / chkDiv0($m_xl3->sum('canbo_congtac')), 6),
                'trungbinhphucap_2k' => round($m_xl3->sum('st_tongpc') / chkDiv0($m_xl3->sum('canbo_congtac')), 6),
                'trungbinhdonggop_2k' => round($m_xl3->sum('ttbh_dv') / chkDiv0($m_xl3->sum('canbo_congtac')), 6),
                'qd34_2k' => 21,
                'tongqd34_2k' => 21 * $m_xl3->count(),
                'soluonggiam_2k' => $m_xl3->sum('soluonggiam_2k'),
                'quyluonggiam_2k' => $m_xl3->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl3->sum('tongquyluonggiam_2k'),
            ];

            $ar_I[0]['solieu'] = [
                'soluongdonvi_2k' => $ar_I[1]['solieu']['soluongdonvi_2k'] + $ar_I[2]['solieu']['soluongdonvi_2k'] + $ar_I[3]['solieu']['soluongdonvi_2k'],
                'qd92_2k' => 0,
                'tongqd92_2k' => $ar_I[1]['solieu']['tongqd92_2k'] + $ar_I[2]['solieu']['tongqd92_2k'] + $ar_I[3]['solieu']['tongqd92_2k'],
                'tongbienche_2k' => $ar_I[1]['solieu']['tongbienche_2k'] + $ar_I[2]['solieu']['tongbienche_2k'] + $ar_I[3]['solieu']['tongbienche_2k'],
                'trungbinhheso_2k' => 0,
                'trungbinhphucap_2k' => 0,
                'trungbinhdonggop_2k' => 0,
                'qd34_2k' => 0,
                'tongqd34_2k' => $ar_I[1]['solieu']['tongqd34_2k'] + $ar_I[2]['solieu']['tongqd34_2k'] + $ar_I[3]['solieu']['tongqd34_2k'],
                'soluonggiam_2k' => $ar_I[1]['solieu']['soluonggiam_2k'] + $ar_I[2]['solieu']['soluonggiam_2k'] + $ar_I[3]['solieu']['soluonggiam_2k'],
                'quyluonggiam_2k' => $ar_I[1]['solieu']['quyluonggiam_2k'] + $ar_I[2]['solieu']['quyluonggiam_2k'] + $ar_I[3]['solieu']['quyluonggiam_2k'],
                'tongquyluonggiam_2k' => $ar_I[1]['solieu']['tongquyluonggiam_2k'] + $ar_I[2]['solieu']['tongquyluonggiam_2k'] + $ar_I[3]['solieu']['tongquyluonggiam_2k'],
            ];


            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2k')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 34/2019/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2l(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }

            $m_chitiet = $m_chitiet->where('nhomnhucau', 'CANBOKCT');

            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $dulieu = $m_chitiet->where('madv', $chitiet->madv);
                if ($dulieu->count() == 0) {
                    $m_nguonkp->forget($key);
                    continue;
                }

                $chitiet->canbo_congtac = $dulieu->sum('canbo_congtac');
                $chitiet->canbo_dutoan = $dulieu->sum('canbo_dutoan');
            }
            //Tính toán số liệu phần I
            $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);
            $ar_I[1] = array('style' => '', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2l' => $m_xl1->count(),
                'qd92_2l' => 20.3,
                'qd34_2l' => 16,
                'tongcanbo_2l' => $m_xl1->sum('canbo_congtac'),
                'tongphucap_2l' => $m_xl1->count() * 20.3 * 1800000,
                'tongdonggop_2l' => $m_xl1->sum('canbo_congtac') * 0.14 * 1800000,
                'tongquyluong_2l' => $m_xl1->count() * 16 * 1800000,
            ];
            $ar_I[1]['solieu']['quyluonggiam_2l'] = abs(round(($ar_I[1]['solieu']['tongquyluong_2l'] - $ar_I[1]['solieu']['tongdonggop_2l'] - $ar_I[1]['solieu']['tongphucap_2l']) * 6, 6));

            $ar_I[2] = array('style' => '', 'tt' => '2', 'noidung' => 'Xã loại 2',);
            $m_xl2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2]['solieu'] = [
                'soluongdonvi_2l' => $m_xl2->count(),
                'qd92_2l' => 18.6,
                'qd34_2l' => 13.7,
                'tongcanbo_2l' => $m_xl2->sum('canbo_congtac'),
                'tongphucap_2l' => $m_xl2->count() * 18.6 * 1800000,
                'tongdonggop_2l' => $m_xl2->sum('canbo_congtac') * 0.14 * 1800000,
                'tongquyluong_2l' => $m_xl2->count() * 13.7 * 1800000,
            ];
            $ar_I[2]['solieu']['quyluonggiam_2l'] = abs(round(($ar_I[2]['solieu']['tongquyluong_2l'] - $ar_I[2]['solieu']['tongdonggop_2l'] - $ar_I[2]['solieu']['tongphucap_2l']) * 6, 6));

            $ar_I[3] = array('style' => '', 'tt' => '3', 'noidung' => 'Xã loại 3',);
            $m_xl3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3]['solieu'] = [
                'soluongdonvi_2l' => $m_xl3->count(),
                'qd92_2l' => 17.6,
                'qd34_2l' => 11.4,
                'tongcanbo_2l' => $m_xl3->sum('canbo_congtac'),
                'tongphucap_2l' => $m_xl3->count() * 17.6 * 1800000,
                'tongdonggop_2l' => $m_xl3->sum('canbo_congtac') * 0.14 * 1800000,
                'tongquyluong_2l' => $m_xl3->count() * 11.4 * 1800000,
            ];
            $ar_I[3]['solieu']['quyluonggiam_2l'] = abs(round(($ar_I[3]['solieu']['tongquyluong_2l'] - $ar_I[3]['solieu']['tongdonggop_2l'] - $ar_I[3]['solieu']['tongphucap_2l']) * 6, 6));


            $ar_I[0]['solieu'] = [
                'soluongdonvi_2l' => $ar_I[1]['solieu']['soluongdonvi_2l'] + $ar_I[2]['solieu']['soluongdonvi_2l'] + $ar_I[3]['solieu']['soluongdonvi_2l'],
                'qd92_2l' => 0,
                'qd34_2l' => 0,
                'tongcanbo_2l' => $ar_I[1]['solieu']['tongcanbo_2l'] + $ar_I[2]['solieu']['tongcanbo_2l'] + $ar_I[3]['solieu']['tongcanbo_2l'],
                'tongphucap_2l' => $ar_I[1]['solieu']['tongphucap_2l'] + $ar_I[2]['solieu']['tongphucap_2l'] + $ar_I[3]['solieu']['tongphucap_2l'],
                'tongdonggop_2l' => $ar_I[1]['solieu']['tongdonggop_2l'] + $ar_I[2]['solieu']['tongdonggop_2l'] + $ar_I[3]['solieu']['tongdonggop_2l'],
                'tongquyluong_2l' => $ar_I[1]['solieu']['tongquyluong_2l'] + $ar_I[2]['solieu']['tongquyluong_2l'] + $ar_I[3]['solieu']['tongquyluong_2l'],
                'quyluonggiam_2l' => $ar_I[1]['solieu']['quyluonggiam_2l'] + $ar_I[2]['solieu']['quyluonggiam_2l'] + $ar_I[3]['solieu']['quyluonggiam_2l'],
            ];


            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2l')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 34/2019/NĐ-CP');
        } else
            return view('errors.notlogin');
    }


    function mau4a(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {

            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            foreach (nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->get() as $chitiet) {
                $m_nguonkp->add($chitiet);
            }

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');
            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');

            $m_chitiet = nguonkinhphi_phucap::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //Phần A
            $a_A = get4a_A();

            for ($capdo = 0; $capdo < 5; $capdo++) {
                foreach ($a_A as $key => $chitiet) {
                    if ($chitiet['phanloai'] == $capdo) {
                        if (!is_array($chitiet['tentruong'])) {
                            $a_A[$key]['sotien'] = $m_nguonkp->sum($chitiet['tentruong']);
                        } else {
                            foreach ($chitiet['tentruong'] as $k) {
                                $a_A[$key]['sotien'] += $a_A[$k]['sotien'];
                            }
                        }
                    }
                }
            }

            //dd($a_A);
            //Phần B
            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BII[1] = array('tt' => '', 'noidung' => 'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)', 'sotien' => '0');
            $a_BII[2] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BII[3] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BII[4] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 44/2023/NĐ-CP', 'sotien' => '0');
            $a_BII[5] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BII[6] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BII[7] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2021', 'sotien' => '0');


            $a_BII[0]['sotien'] = $m_chitiet->where('nhomnhucau', 'BIENCHE')->sum('ttl');
            $a_BII[2]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOCT')->sum('ttl');
            $a_BII[3]['sotien'] = $m_chitiet->where('nhomnhucau', 'HDND')->sum('ttl');
            $a_BII[4]['sotien'] = $m_nguonkp->sum('nghihuu_4a'); //Nhaaph thên
            $a_BII[5]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOKCT')->sum('ttl');
            $a_BII[6]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->wherein('level', ['X', 'H'])->sum('ttl');
            $a_BII[7]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->where('level', 'T')->sum('ttl');


            $a_BIII = array();
            $a_BIII[0] = array('tt' => '1', 'noidung' => 'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)', 'sotien' => '0');
            $a_BIII[1] = array('tt' => '2', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2023 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)', 'sotien' => '0');
            $a_BIII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2023 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');
            $a_BIII[3] = array('tt' => '4', 'noidung' => 'Kinh phí giảm do điều chỉnh danh sách huyện nghèo theo Quyết định số 275/QĐ-TTg ngày 07/3/2022 của Thủ tướng Chính phủ (quy định tại điểm b khoản 2 Công văn số 1044/BNV-TL ngày 11/3/2023 của Bộ Nội vụ)', 'sotien' => '0');
            $a_BIII[4] = array('tt' => 'a', 'noidung' => 'Kinh phí thu hút', 'sotien' => '0');
            $a_BIII[5] = array('tt' => 'b', 'noidung' => 'Chênh lệch kinh phí ưu đãi', 'sotien' => '0');
            $a_BIII[6] = array('tt' => '5', 'noidung' => 'Kinh phí giảm do điều chỉnh số lượng cán bộ, công chức cấp xã; mức khoán phụ cấp đối với người hoạt động không chuyên trách ở cấp xã theo Nghị định số 34/2023/NĐ-CP của Chính phủ (7)', 'sotien' => '0');


            $a_BIII[0]['sotien'] = $m_nguonkp->sum('diaban');
            $a_BIII[1]['sotien'] = $m_nguonkp->sum('tinhgiam');
            $a_BIII[2]['sotien'] = $m_nguonkp->sum('nghihuusom');
            $a_BIII[4]['sotien'] = $m_nguonkp->sum('kpthuhut');
            $a_BIII[5]['sotien'] = $m_nguonkp->sum('kpuudai');
            $a_BIII[3]['sotien'] = $a_BIII[4]['sotien'] + $a_BIII[5]['sotien'];
            $a_BIII[6]['sotien'] = 0;

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[3]['sotien']
                    + $a_A[4]['sotien'] + $a_A[5]['sotien'] + $a_A[6]['sotien'] + $a_A[15]['sotien'] + $a_A[20]['sotien'] + $a_A[21]['sotien']),
                'BI' => $m_nguonkp->sum('tongnhucau2'),
                'BI1' => $m_nguonkp->sum('tongnhucau1'),
                'BII' => (array_sum(array_column($a_BII, 'sotien')) - $a_BII[1]['sotien']),
                'BIII' => (array_sum(array_column($a_BIII, 'sotien')))
            );
            //dd($a_A);
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau4a')
                ->with('model', $m_nguonkp)
                ->with('a_A', $a_A)
                ->with('a_BII', $a_BII)
                ->with('a_BIII', $a_BIII)
                ->with('a_TC', $a_TC)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $inputs['donvitinh'] = 1;
            $madvbc = isset($inputs['madvbc']) ? $inputs['madvbc'] : session('admin')->madvbc;
            $macqcq = isset($inputs['macqcq']) ? $inputs['macqcq'] : session('admin')->madv;
            $madv = isset($inputs['madv']) ? $inputs['madv'] : session('admin')->madv;
            // dd($inputs);
            // dd(session('admin'));
            $m_dv = dmdonvi::select('tendv', 'madv')
                ->where('madvbc', $madvbc)
                ->distinct()
                ->get();
            // dd($m_dv);
            $model = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('macqcq', $macqcq)
                ->where('trangthai', 'DAGUI')
                ->get();

            $ardv = $m_dv->toArray();
            if (count($model) == 0) {
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv', $madv)->first();
            // dd($m_dv);
            $data = array();
            $group = array();
            if (isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'KVXP', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            } else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'KVXP', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            }
            $a_sunghiep = dmkhoipb::all();
            $a_sn = array('GD', 'DT', 'YTE', 'QLNN');
            $a_sunghiep = array_column($a_sunghiep->toarray(), 'makhoipb');
            for ($i = 0; $i < count($data); $i++) {
                $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                $data[$i]['nhucau'] = $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                    $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                    $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                    $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                    $solieu->sum('kpuudai');
                //$data[$i]['nhucau'] = $solieu->sum('nhucau');
                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                $data[$i]['khac'] = 0;
                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
            }
            $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
            $data[0]['khac'] = 0;
            $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];

            $data[4]['nhucau'] = $model->sum('luongphucap') + $model->sum('baohiem') +
                $model->sum('daibieuhdnd') + $model->sum('nghihuu') + $model->sum('canbokct') +
                $model->sum('uyvien') + $model->sum('boiduong') + $model->sum('diaban') +
                $model->sum('tinhgiam') + $model->sum('nghihuusom') + $model->sum('kpthuhut') +
                $model->sum('kpuudai') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
            $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
            $data[4]['khac'] = 0;
            $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
            //dd($data);
            $a_TC = array(
                'nhucau' => ($data[0]['nhucau'] + $data[3]['nhucau'] + $data[4]['nhucau'] + $data[5]['nhucau']),
                'nguonkp' => ($data[0]['nguonkp'] + $data[3]['nguonkp'] + $data[4]['nguonkp'] + $data[5]['nguonkp']),
                'tietkiem' => ($data[0]['tietkiem'] + $data[3]['tietkiem'] + $data[4]['tietkiem'] + $data[5]['tietkiem']),
                'hocphi' => ($data[0]['hocphi'] + $data[3]['hocphi'] + $data[4]['hocphi'] + $data[5]['hocphi']),
                'vienphi' => ($data[0]['vienphi'] + $data[3]['vienphi'] + $data[4]['vienphi'] + $data[5]['vienphi']),
                'khac' => ($data[0]['khac'] + $data[3]['khac'] + $data[4]['khac'] + $data[5]['khac']),
                'nguonthu' => ($data[0]['nguonthu'] + $data[3]['nguonthu'] + $data[4]['nguonthu'] + $data[5]['nguonthu'])
            );

            return view('reports.thongtu78.huyen.mau4b')
                ->with('model', $model)
                ->with('data', $data)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                ->with('a_TC', $a_TC)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }
}
