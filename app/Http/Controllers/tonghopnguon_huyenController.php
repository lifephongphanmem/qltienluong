<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphanloaidonvi_baocao;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class tonghopnguon_huyenController extends Controller
{

    /* 2023.08.15 chưa lọc theo các t.c trên
     1. Các đơn vị có macqcq và phân loại = SD
     2. Các đơn vị có macqcq và phân loại = THKHOI
     */
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
            // dd($model_nguon->where('sohieu','tt78_2022'));
            $model_nguon_huyen = nguonkinhphi_huyen::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            })->where('trangthai', 'DAGUI')
                ->get();
            // dd($model_nguon_huyen);
            //$model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            $model = dmthongtuquyetdinh::all();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            // $model_donvi = dmdonvi::select('madv', 'tendv')
            //     ->wherein('madv', function ($query) use ($madv) {
            //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            //     })->get();

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
                // $a_donvicapduoi = [];
                // //đơn vị nam=nam && macqcq=madv
                // $model_dsql = dsdonviquanly::where('nam', $nam)->where('macqcq', $madv)->get();
                // $a_donvicapduoi = array_unique(array_column($model_dsql->toarray(), 'madv'));
                // // dd($a_donvicapduoi);
                // //đơn vị có macqcq = madv (bang dmdonvi)
                // $model_dmdv = dmdonvi::where('macqcq', $madv)
                //     ->wherenotin('madv', function ($qr) use ($nam) {
                //         $qr->select('madv')->from('dsdonviquanly')->where('nam', $nam)->distinct()->get();
                //     }) //lọc các đơn vị đã khai báo trong dsdonviquanly
                //     ->where('madv', '!=', $madv)
                //     ->get();
                // $a_donvicapduoi = array_unique(array_merge(array_column($model_dmdv->toarray(), 'madv'), $a_donvicapduoi));
                // $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv', $a_donvicapduoi)->get();
                $soluong = count(getDonviHuyen($nam,$madv)['m_donvi']);

                $nguon_huyen = $model_nguon_tinh->where('sohieu', $dv->sohieu)->first();
                $m_dv = $model_nguon->where('sohieu', $dv->sohieu);
                if (isset($nguon_huyen)) {
                    //Đã tổng hợp dữ liệu
                    // $dv->sldv = $soluong . '/' . $soluong;
                    $dv->sldv = count($m_dv) . '/' . $soluong;
                    $dv->masodv = $nguon_huyen->masodv;
                    $dv->trangthai = $nguon_huyen->trangthai;
                    //$dv->trangthai = 'DAGUI';
                } else {
                    //Chưa tổng hợp dữ liệu
                    // $a_madv = array_column($model_donvi->toarray(), 'madv');
                    $a_madv =getDonviHuyen($nam, $madv)['m_donvi'];
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

                //mẫu 2b
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
            // dd($inputs);
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
            $model_nguon_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->where('madv', $madv)->first();

            if ($model_nguon_tinh != null) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_tinh->trangthai = 'DAGUI';
                $model_nguon_tinh->nguoilap = session('admin')->name;
                $model_nguon_tinh->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_tinh->save();
                $inputs['masot'] = $model_nguon_tinh->masodv;
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                //nguonkinhphi_huyen::create($inputs);
                nguonkinhphi_tinh::create($inputs);
                $inputs['masot'] = $inputs['masodv'];
            }

            //Cập nhật lại mã số tỉnh
            nguonkinhphi::where('sohieu', $inputs['sohieu'])->where('macqcq', $madv)->where('trangthai', 'DAGUI')
                ->update(['masot' => $inputs['masot']]);
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
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
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
            //$m_chitiet = $m_chitiet->wherein('nhomnhucau', ['BIENCHE', 'HOPDONG', 'CHUATUYENHC', 'CANBOCT', 'CANBOKCT', 'CHUATUYENXP', 'HDND', 'CAPUY']);
            //$model = $m_chitiet->where('nhomnhucau', 'CANBOKCT');
            //dd( $model);
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

    function tonghop_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }

            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

            if ($m_nguonkp->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_phucap::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                $chitiet->tongpc = $chitiet->tonghs - $chitiet->heso;
                $chitiet->st_tongpc = $chitiet->ttl - $chitiet->st_heso;
                $chitiet->tongcong = $chitiet->st_tongpc + $chitiet->st_heso + $chitiet->ttbh_dv;
            }
            //dd($m_nguonkp);

            $m_phucap = dmphucap_donvi::where('madv',  $m_nguonkp->first()->madv)->wherenotin('mapc', ['heso'])->get();
            $a_phucap = array_keys(getPhuCap2a_78());

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->wherein('nhomnhucau', ['BIENCHE', 'HOPDONG', 'CHUATUYENHC']);
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }

                    $a_solieu = [];
                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_I[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];
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


                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] =  0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ
                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_I[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];
                    }
                    $ar_I[$key]['solieu'] = $a_solieu;
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] =  0;
                    }
                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }
            //

            //
            //Tính toán số liệu phần II
            $ar_II = getChuyenTrach();
            $dulieu_pII = $m_chitiet->wherein('nhomnhucau', ['CANBOCT', 'CANBOKCT', 'CHUATUYENXP']);
            //$aII_plct = getChuyenTrach_plct();
            // foreach ($dulieu_pII as $key => $value) {
            //     if (count($aII_plct) > 0 && !in_array($value->mact, $aII_plct))
            //         $dulieu_pII->forget($key);
            // }
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
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $dulieu_chitiet->sum('st_heso');
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_II[$key]['solieu'] = $a_solieu;
                }
            }

            //Tính toán số liệu phần III
            $ar_III = getHDND();
            //$aIII_plct = getHDND_plct();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');
            // foreach ($dulieu_pIII as $key => $value) {
            //     if (count($aIII_plct) > 0 && !in_array($value->mact, $aIII_plct))
            //         $dulieu_pIII->forget($key);
            // }

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
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }

                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $a_solieu['st_heso'];
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_III[$key]['solieu'] = $a_solieu;
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_III[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_III[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_III[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_III[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_III[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_III[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_III[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_III[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_III[$k]['solieu']['tongcong'];
                    }
                    $ar_III[$key]['solieu'] = $a_solieu;
                }
            }

            //Tính toán số liệu phần IV
            $ar_IV = getCapUy();
            $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY');
            // $aIV_plct = getCapUy_plct();
            // foreach ($dulieu_pIV as $key => $value) {
            //     if (count($aIV_plct) > 0 && !in_array($value->mact, $aIV_plct))
            //         $dulieu_pIV->forget($key);
            // }
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
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $a_solieu['st_heso'];
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_IV[$key]['solieu'] = $a_solieu;
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_IV[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_IV[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_IV[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_IV[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_IV[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_IV[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_IV[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_IV[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_IV[$k]['solieu']['tongcong'];
                    }
                    $ar_IV[$key]['solieu'] = $a_solieu;
                }
            }

            //Tính toán tổng cộng

            $a_Tong['solieu'] = [
                'tongcong' => $ar_I[0]['solieu']['tongcong'] + $ar_II[0]['solieu']['tongcong']
                    + $ar_III[0]['solieu']['tongcong'] + $ar_IV[0]['solieu']['tongcong'],
                'st_heso' => $ar_I[0]['solieu']['st_heso'] + $ar_II[0]['solieu']['st_heso']
                    + $ar_III[0]['solieu']['st_heso'] + $ar_IV[0]['solieu']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu']['st_tongpc'] + $ar_II[0]['solieu']['st_tongpc']
                    + $ar_III[0]['solieu']['st_tongpc'] + $ar_IV[0]['solieu']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu']['ttbh_dv'] + $ar_II[0]['solieu']['ttbh_dv']
                    + $ar_III[0]['solieu']['ttbh_dv'] + $ar_IV[0]['solieu']['ttbh_dv'],
            ];

            foreach ($m_phucap as $pc) {
                $mapc_st = 'st_' . $pc->mapc;
                $a_Tong['solieu'][$mapc_st] = $ar_I[0]['solieu'][$mapc_st] + $ar_II[0]['solieu'][$mapc_st]
                    + $ar_III[0]['solieu'][$mapc_st] + $ar_IV[0]['solieu'][$mapc_st];
            }

            //Dữ liệu các phần chi tiết để dai đơn vị
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($dulieu_pI as $chitiet) {
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? $chitiet->mact;
                foreach ($m_phucap as $pc) {
                    if (!in_array($pc->mapc, $a_phucap)) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $mapc = $pc->mapc;
                        $chitiet->pck += $chitiet->$mapc;
                        $chitiet->st_pck += $chitiet->$mapc_st;
                        $chitiet->$mapc = 0;
                        $chitiet->$mapc_st = 0;
                    }
                }
            }

            foreach ($dulieu_pII as $chitiet) {
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? $chitiet->mact;
                foreach ($m_phucap as $pc) {
                    if (!in_array($pc->mapc, $a_phucap)) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $mapc = $pc->mapc;
                        $chitiet->pck += $chitiet->$mapc;
                        $chitiet->st_pck += $chitiet->$mapc_st;
                        $chitiet->$mapc = 0;
                        $chitiet->$mapc_st = 0;
                    }
                }
            }

            foreach ($dulieu_pIII as $chitiet) {
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? $chitiet->mact;
                foreach ($m_phucap as $pc) {
                    if (!in_array($pc->mapc, $a_phucap)) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $mapc = $pc->mapc;
                        $chitiet->pck += $chitiet->$mapc;
                        $chitiet->st_pck += $chitiet->$mapc_st;
                        $chitiet->$mapc = 0;
                        $chitiet->$mapc_st = 0;
                    }
                }
            }

            foreach ($dulieu_pIV as $chitiet) {
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? $chitiet->mact;
                foreach ($m_phucap as $pc) {
                    if (!in_array($pc->mapc, $a_phucap)) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $mapc = $pc->mapc;
                        $chitiet->pck += $chitiet->$mapc;
                        $chitiet->st_pck += $chitiet->$mapc_st;
                        $chitiet->$mapc = 0;
                        $chitiet->$mapc_st = 0;
                    }
                }
            }
            // dd($ar_II);

            $m_dv = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mautonghop')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('dulieu_pI', $dulieu_pI)
                ->with('ar_II', $ar_II)
                ->with('dulieu_pII', $dulieu_pII)
                ->with('ar_III', $ar_III)
                ->with('dulieu_pIII', $dulieu_pIII)
                ->with('ar_IV', $ar_IV)
                ->with('dulieu_pIV', $dulieu_pIV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                //->with('a_phucap', $a_phucap)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function tonghop_pldv(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $model_tonghop = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            //dd($model_thongtu);
            if ($model_tonghop->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            //dd($m_donvi_baocao);
            $a_donvi = array_column($model_tonghop->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = nguonkinhphi_phucap::wherein('masodv', array_column($model_tonghop->toarray(), 'masodv'))->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColNhuCau();
            //dd($a_pc);
            $a_phucap = array_keys(getPhuCap2a_78());
            $a_phucap[] = 'heso';
            //dd($a_phucap);
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongcoban = $model_thongtu->chenhlech;
                //$chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, 7);
                //$chitiet->baohiem = round(($chitiet->ttbh_dv - $chitiet->stbhtn_dv) / $chitiet->luongcoban, 7);                
                //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                foreach ($a_pc as $pc) {
                    if (!in_array($pc, $a_phucap)) {
                        $mapc_st = 'st_' . $pc;
                        $chitiet->pck +=  $chitiet->$pc;
                        $chitiet->st_pck += $chitiet->$mapc_st;
                        $chitiet->$pc = 0;
                        $chitiet->$mapc_st = 0;
                    }
                }

                $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
                $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                $chitiet->st_tongphucap = $chitiet->ttl - $chitiet->st_heso;
                $chitiet->tongcong = $chitiet->tonghs + $chitiet->baohiem + $chitiet->bhtn_dv;
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
                //dd($chitiet);
            }

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach ($a_pc as $ct) {
                if ($model->sum($ct) > 0 && $ct != 'heso') {
                    $a_phucap[$ct] = $a_tenpc[$ct];
                    $col++;
                }
            }
            //dd($model);
            return view('reports.thongtu78.huyen.SoLieuTongHop_PhanLoaiDV')
                ->with('model', $model)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('m_donvi', $m_donvi)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                //->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function getMaNhomPhanLoai(&$chitiet, $m_phanloai)
    {
        $chitiet->maphanloai_goc1 = '';
        $chitiet->maphanloai_goc2 = '';
        $chitiet->maphanloai_goc3 = '';
        $phanloai = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai)->first();
        if ($phanloai != null) {
            $chitiet->capdo_nhom = $phanloai->capdo_nhom;
            switch ($phanloai->capdo_nhom) {
                case '1': {
                        $chitiet->maphanloai_goc1 = $phanloai->maphanloai_nhom;
                        break;
                    }
                case '2': {
                        $chitiet->maphanloai_goc1 = $phanloai->maphanloai_goc;
                        break;
                    }
                case '3': {
                        $chitiet->maphanloai_goc2 = $phanloai->maphanloai_goc;
                        //tìm gốc 1
                        $chitiet->maphanloai_goc1 = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai_goc2)->first()->maphanloai_goc;
                        break;
                    }
                case '4': {
                        //tìm cấp 3                    
                        $chitiet->maphanloai_goc3 = $phanloai->maphanloai_goc;
                        //tìm gốc 2
                        $chitiet->maphanloai_goc2 = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai_goc3)->first()->maphanloai_goc;
                        //tìm gốc 1
                        $chitiet->maphanloai_goc1 = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai_goc2)->first()->maphanloai_goc;
                        break;
                    }
            }
        }
    }

    function mau2a_pldv(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $model_tonghop = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            //dd($model_thongtu);
            if ($model_tonghop->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            //dd($m_donvi_baocao);
            $a_donvi = array_column($model_tonghop->toarray(), 'madv', 'masodv');
            $a_sobiencheduocgiao = array_column($model_tonghop->toarray(), 'sobiencheduocgiao', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = nguonkinhphi_01thang::wherein('masodv', array_column($model_tonghop->toarray(), 'masodv'))->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColNhuCau();

            $luongcb_cu = $model_thongtu->muccu;
            $luongcb_moi = $model_thongtu->mucapdung;
            $chenhlech = $model_thongtu->chenhlech;
            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            $a_nhomnhucau = ['BIENCHE', 'CANBOCT', 'HDND', 'CAPUY'];
            foreach ($model as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                if (!in_array($chitiet->nhomnhucau, $a_nhomnhucau)) {
                    $model->forget($key);
                }
                //Số lượng cán bộ biên chế đã được tạo ở nhu cầu               
                if ($chitiet->nhomnhucau == 'BIENCHE') {
                    $chitiet->canbo_dutoan = $a_sobiencheduocgiao[$chitiet->masodv] ?? $chitiet->canbo_dutoan;
                }
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);

                //Bảng lương theo mức lương cũ                
                $chitiet->ttbh_dv_cu = round(($chitiet->ttbh_dv / $chenhlech) * $luongcb_cu);
                foreach ($a_pc as $mapc) {
                    $mapc_st = 'st_' . $mapc . '_cu';
                    $chitiet->$mapc_st = round($chitiet->$mapc * $luongcb_cu);
                }
                $chitiet->tongpc_cu = $chitiet->tonghs - $chitiet->heso;
                $chitiet->st_tongpc_cu = round($chitiet->tongpc_cu * $luongcb_cu);
                $chitiet->tongcong_cu = $chitiet->st_tongpc_cu + $chitiet->st_heso_cu + $chitiet->ttbh_dv_cu;

                //Bảng lương theo mức lương mới
                $chitiet->ttbh_dv_moi = round(($chitiet->ttbh_dv / $chenhlech) * $luongcb_moi);
                foreach ($a_pc as $mapc) {
                    $mapc_st = 'st_' . $mapc . '_moi';
                    $chitiet->$mapc_st = round($chitiet->$mapc * $luongcb_moi);
                }
                $chitiet->tongpc_moi = $chitiet->tonghs - $chitiet->heso;
                $chitiet->st_tongpc_moi = round($chitiet->tongpc_moi * $luongcb_moi);
                $chitiet->tongcong_moi = $chitiet->st_tongpc_moi + $chitiet->st_heso_moi + $chitiet->ttbh_dv_moi;
                //Tính mức tăng thêm
                $chitiet->tang01thang = $chitiet->tongcong_moi - $chitiet->tongcong_cu;
                $chitiet->tang06thang = $chitiet->tang01thang * 6;
                //dd($chitiet);
            }

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach ($a_pc as $ct) {
                if ($model->sum($ct) > 0 && $ct != 'heso') {
                    $a_phucap[$ct] = $a_tenpc[$ct];
                    $col++;
                }
            }
            //dd($model->first());
            return view('reports.thongtu78.huyen.mau2a_PhanLoaiDV')
                ->with('model', $model)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('m_donvi', $m_donvi)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                //->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function mau2a(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_banhanh = nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

            if ($m_nguonkp->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

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
            //dd($m_nguonkp);
            // dd($m_chitiet);
            $m_phucap = dmphucap_donvi::where('madv',  $m_nguonkp->first()->madv)->wherenotin('mapc', ['heso'])->get();

            $a_phucap = array_keys(getPhuCap2a_78());

            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    // $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    //28/6/2023: tạm thời bỏ để lấy dữ liệu giống 4a
                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
                    $ar_I[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_I[$key]['chenhlech01thang'] = 0;
                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ
                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_I[$k]['solieu'][$pc->mapc];
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

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_I[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                        $ar_I[$key]['chenhlech01thang'] += $ar_I[$k]['chenhlech01thang'];
                    }

                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];

                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            // dd($ar_I);
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
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_I[$key]['chenhlech01thang'] = 0;
                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_I[$k]['solieu'][$pc->mapc];
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

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_I[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                        $ar_I[$key]['chenhlech01thang'] += $ar_I[$k]['chenhlech01thang']; //sửa lại cho khớp với dữ liệu 4a (26/8/2023)
                    }


                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
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
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
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
            $aII_plct = getChuyenTrach_plct();
            foreach ($dulieu_pII as $key => $value) {
                if (count($aII_plct) > 0 && !in_array($value->mact, $aII_plct))
                    $dulieu_pII->forget($key);
            }
            //Vòng cấp độ 3
            foreach ($ar_II as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_II[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_II[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');;
                    //26/8/2023: tạm thời bỏ để khớp dữ liệu 4a
                    // $ar_II[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                    $ar_II[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                    $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                }
            }


            //Tính toán số liệu phần III
            $ar_III = getHDND();
            $aIII_plct = getHDND_plct();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');
            foreach ($dulieu_pIII as $key => $value) {
                if (count($aIII_plct) > 0 && !in_array($value->mact, $aIII_plct))
                    $dulieu_pIII->forget($key);
            }

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
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }

                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }

                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_III[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_III[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    //26/8/2023: tạm thời bỏ để khớp dữ liệu 4a
                    // $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
                    $ar_III[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
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
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_III[$key]['chenhlech01thang'] = 0;
                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_III[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_III[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_III[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_III[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_III[$k]['solieu'][$pc->mapc];
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

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_III[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_III[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_III[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_III[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_III[$k]['solieu_moi']['tongcong'];

                        $ar_III[$key]['canbo_congtac'] += $ar_III[$k]['canbo_congtac'];
                        $ar_III[$key]['canbo_dutoan'] += $ar_III[$k]['canbo_dutoan'];
                        $ar_III[$key]['chenhlech01thang'] += $ar_III[$k]['chenhlech01thang']; //sửa cho khớp dữ liệu 4a (26/8/2023)
                    }

                    // $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;

                    $ar_III[$key]['solieu'] = $a_solieu;
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán số liệu phần IV
            $ar_IV = getCapUy();
            $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY');;
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
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
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

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_IV[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_IV[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    //26/8/2023: tạm thời bỏ để khớp dữ liệu 4a
                    // $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
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
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_IV[$key]['chenhlech01thang'] = 0;
                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_IV[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_IV[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_IV[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_IV[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_IV[$k]['solieu'][$pc->mapc];
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

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_IV[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_IV[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_IV[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_IV[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_IV[$k]['solieu_moi']['tongcong'];

                        $ar_IV[$key]['canbo_congtac'] += $ar_IV[$k]['canbo_congtac'];
                        $ar_IV[$key]['canbo_dutoan'] += $ar_IV[$k]['canbo_dutoan'];
                        $ar_IV[$key]['chenhlech01thang'] += $ar_IV[$k]['chenhlech01thang']; //sửa cho khớp dữ liệu 4a (26/8/2023)
                    }

                    // $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;

                    $ar_IV[$key]['solieu'] = $a_solieu;
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán tổng cộng
            $a_Tong = [
                'canbo_congtac' => $ar_I[0]['canbo_congtac'] + $ar_II[0]['canbo_congtac'] + $ar_III[0]['canbo_congtac'] + $ar_IV[0]['canbo_congtac'],
                'canbo_dutoan' => $ar_I[0]['canbo_dutoan'] + $ar_II[0]['canbo_dutoan'] + $ar_III[0]['canbo_dutoan'] + $ar_IV[0]['canbo_dutoan'],
                'chenhlech01thang' => $ar_I[0]['chenhlech01thang'] + $ar_II[0]['chenhlech01thang'] + $ar_III[0]['chenhlech01thang'] + $ar_IV[0]['chenhlech01thang'],
                'chenhlech06thang' => $ar_I[0]['chenhlech06thang'] + $ar_II[0]['chenhlech06thang'] + $ar_III[0]['chenhlech06thang'] + $ar_IV[0]['chenhlech06thang'],
            ];
            $a_Tong['solieu'] = [
                'tongcong' => $ar_I[0]['solieu']['tongcong'] + $ar_II[0]['solieu']['tongcong']
                    + $ar_III[0]['solieu']['tongcong'] + $ar_IV[0]['solieu']['tongcong'],
                'st_heso' => $ar_I[0]['solieu']['st_heso'] + $ar_II[0]['solieu']['st_heso']
                    + $ar_III[0]['solieu']['st_heso'] + $ar_IV[0]['solieu']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu']['st_tongpc'] + $ar_II[0]['solieu']['st_tongpc']
                    + $ar_III[0]['solieu']['st_tongpc'] + $ar_IV[0]['solieu']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu']['ttbh_dv'] + $ar_II[0]['solieu']['ttbh_dv']
                    + $ar_III[0]['solieu']['ttbh_dv'] + $ar_IV[0]['solieu']['ttbh_dv'],
            ];
            $a_Tong['solieu_moi'] = [
                'tongcong' => $ar_I[0]['solieu_moi']['tongcong'] + $ar_II[0]['solieu_moi']['tongcong']
                    + $ar_III[0]['solieu_moi']['tongcong'] + $ar_IV[0]['solieu_moi']['tongcong'],
                'st_heso' => $ar_I[0]['solieu_moi']['st_heso'] + $ar_II[0]['solieu_moi']['st_heso']
                    + $ar_III[0]['solieu_moi']['st_heso'] + $ar_IV[0]['solieu_moi']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu_moi']['st_tongpc'] + $ar_II[0]['solieu_moi']['st_tongpc']
                    + $ar_III[0]['solieu_moi']['st_tongpc'] + $ar_IV[0]['solieu_moi']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu_moi']['ttbh_dv'] + $ar_II[0]['solieu_moi']['ttbh_dv']
                    + $ar_III[0]['solieu_moi']['ttbh_dv'] + $ar_IV[0]['solieu_moi']['ttbh_dv'],
            ];
            foreach ($m_phucap as $pc) {
                $mapc_st = 'st_' . $pc->mapc;
                $a_Tong['solieu_moi'][$mapc_st] = $ar_I[0]['solieu_moi'][$mapc_st] + $ar_II[0]['solieu_moi'][$mapc_st]
                    + $ar_III[0]['solieu_moi'][$mapc_st] + $ar_IV[0]['solieu_moi'][$mapc_st];
                $a_Tong['solieu'][$mapc_st] = $ar_I[0]['solieu'][$mapc_st] + $ar_II[0]['solieu'][$mapc_st]
                    + $ar_III[0]['solieu'][$mapc_st] + $ar_IV[0]['solieu'][$mapc_st];
            }

            //dd($m_tonghop_ct);
            $m_dv = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.donvi.mau2a2')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_dv)
                ->with('m_dv', $m_dv)
                ->with('m_banhanh', $m_banhanh)
                //->with('a_phucap', $a_phucap)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2a_2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_banhanh = nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

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
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
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
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
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
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
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
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');;
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


            //Tính toán tổng cộng
            $a_Tong = [
                'canbo_congtac' => $ar_I[0]['canbo_congtac'] + $ar_II[0]['canbo_congtac'] + $ar_III[0]['canbo_congtac'] + $ar_IV[0]['canbo_congtac'],
                'canbo_dutoan' => $ar_I[0]['canbo_dutoan'] + $ar_II[0]['canbo_dutoan'] + $ar_III[0]['canbo_dutoan'] + $ar_IV[0]['canbo_dutoan'],
                'chenhlech01thang' => $ar_I[0]['chenhlech01thang'] + $ar_II[0]['chenhlech01thang'] + $ar_III[0]['chenhlech01thang'] + $ar_IV[0]['chenhlech01thang'],
                'chenhlech06thang' => $ar_I[0]['chenhlech06thang'] + $ar_II[0]['chenhlech06thang'] + $ar_III[0]['chenhlech06thang'] + $ar_IV[0]['chenhlech06thang'],
            ];
            $a_Tong['solieu'] = [
                'tongcong' => $ar_I[0]['solieu']['tongcong'] + $ar_II[0]['solieu']['tongcong']
                    + $ar_III[0]['solieu']['tongcong'] + $ar_IV[0]['solieu']['tongcong'],
                'st_heso' => $ar_I[0]['solieu']['st_heso'] + $ar_II[0]['solieu']['st_heso']
                    + $ar_III[0]['solieu']['st_heso'] + $ar_IV[0]['solieu']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu']['st_tongpc'] + $ar_II[0]['solieu']['st_tongpc']
                    + $ar_III[0]['solieu']['st_tongpc'] + $ar_IV[0]['solieu']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu']['ttbh_dv'] + $ar_II[0]['solieu']['ttbh_dv']
                    + $ar_III[0]['solieu']['ttbh_dv'] + $ar_IV[0]['solieu']['ttbh_dv'],
            ];
            $a_Tong['solieu_moi'] = [
                'tongcong' => $ar_I[0]['solieu_moi']['tongcong'] + $ar_II[0]['solieu_moi']['tongcong']
                    + $ar_III[0]['solieu_moi']['tongcong'] + $ar_IV[0]['solieu_moi']['tongcong'],
                'st_heso' => $ar_I[0]['solieu_moi']['st_heso'] + $ar_II[0]['solieu_moi']['st_heso']
                    + $ar_III[0]['solieu_moi']['st_heso'] + $ar_IV[0]['solieu_moi']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu_moi']['st_tongpc'] + $ar_II[0]['solieu_moi']['st_tongpc']
                    + $ar_III[0]['solieu_moi']['st_tongpc'] + $ar_IV[0]['solieu_moi']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu_moi']['ttbh_dv'] + $ar_II[0]['solieu_moi']['ttbh_dv']
                    + $ar_III[0]['solieu_moi']['ttbh_dv'] + $ar_IV[0]['solieu_moi']['ttbh_dv'],
            ];
            foreach ($a_phucap_st as $mapc => $tenpc) {
                $a_Tong['solieu_moi'][$mapc] = $ar_I[0]['solieu_moi'][$mapc] + $ar_II[0]['solieu_moi'][$mapc]
                    + $ar_III[0]['solieu_moi'][$mapc] + $ar_IV[0]['solieu_moi'][$mapc];
                $a_Tong['solieu'][$mapc] = $ar_I[0]['solieu'][$mapc] + $ar_II[0]['solieu'][$mapc]
                    + $ar_III[0]['solieu'][$mapc] + $ar_IV[0]['solieu'][$mapc];
            }
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_tonghop_ct);
            return view('reports.thongtu78.donvi.mau2a2_2')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('a_phucap', $a_phucap)
                ->with('m_banhanh', $m_banhanh)
                ->with('a_phucap_st', $a_phucap_st)
                ->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2a_vn(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_banhanh = nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');
            $a_chuong = array_column($m_dsdv->toArray(), 'machuong', 'madv');
            $a_loaikhoan = array_column($m_dsdv->toArray(), 'maloaikhoan', 'madv');
            $a_maqhns = array_column($m_dsdv->toArray(), 'maqhns', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            $a_donvibienche = [];
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
                $chitiet->machuong = $a_chuong[$chitiet->madv];
                $chitiet->maloaikhoan = $a_loaikhoan[$chitiet->madv];
                $chitiet->maqhns = $a_maqhns[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
                //lấy thông tin cán bộ dự toán cho nhóm biên chế

                if (!in_array($chitiet->madv, $a_donvibienche) && in_array($chitiet->nhomnhucau, ['BIENCHE', 'CANBOCT'])) {
                    $chitiet->canbo_dutoan = $m_nguonkp->where('masodv', $chitiet->masodv)->first()->sobiencheduocgiao ?? $chitiet->canbo_dutoan;
                    $a_donvibienche[] = $chitiet->madv;
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
            //dd($m_nguonkp->where('linhvuchoatdong', 'QLNN')->toarray());
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
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
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
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
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
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
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
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    $ar_II[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                }
            }

            //Tính toán số liệu phần III
            $ar_III = getHDND();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');
            //dd($dulieu_pIII);

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

            //Tính toán tổng cộng
            $a_Tong = [
                'canbo_congtac' => $ar_I[0]['canbo_congtac'] + $ar_II[0]['canbo_congtac'] + $ar_III[0]['canbo_congtac'] + $ar_IV[0]['canbo_congtac'],
                'canbo_dutoan' => $ar_I[0]['canbo_dutoan'] + $ar_II[0]['canbo_dutoan'] + $ar_III[0]['canbo_dutoan'] + $ar_IV[0]['canbo_dutoan'],
                'chenhlech01thang' => $ar_I[0]['chenhlech01thang'] + $ar_II[0]['chenhlech01thang'] + $ar_III[0]['chenhlech01thang'] + $ar_IV[0]['chenhlech01thang'],
                'chenhlech06thang' => $ar_I[0]['chenhlech06thang'] + $ar_II[0]['chenhlech06thang'] + $ar_III[0]['chenhlech06thang'] + $ar_IV[0]['chenhlech06thang'],
            ];
            $a_Tong['solieu'] = [
                'tongcong' => $ar_I[0]['solieu']['tongcong'] + $ar_II[0]['solieu']['tongcong']
                    + $ar_III[0]['solieu']['tongcong'] + $ar_IV[0]['solieu']['tongcong'],
                'st_heso' => $ar_I[0]['solieu']['st_heso'] + $ar_II[0]['solieu']['st_heso']
                    + $ar_III[0]['solieu']['st_heso'] + $ar_IV[0]['solieu']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu']['st_tongpc'] + $ar_II[0]['solieu']['st_tongpc']
                    + $ar_III[0]['solieu']['st_tongpc'] + $ar_IV[0]['solieu']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu']['ttbh_dv'] + $ar_II[0]['solieu']['ttbh_dv']
                    + $ar_III[0]['solieu']['ttbh_dv'] + $ar_IV[0]['solieu']['ttbh_dv'],
            ];
            $a_Tong['solieu_moi'] = [
                'tongcong' => $ar_I[0]['solieu_moi']['tongcong'] + $ar_II[0]['solieu_moi']['tongcong']
                    + $ar_III[0]['solieu_moi']['tongcong'] + $ar_IV[0]['solieu_moi']['tongcong'],
                'st_heso' => $ar_I[0]['solieu_moi']['st_heso'] + $ar_II[0]['solieu_moi']['st_heso']
                    + $ar_III[0]['solieu_moi']['st_heso'] + $ar_IV[0]['solieu_moi']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu_moi']['st_tongpc'] + $ar_II[0]['solieu_moi']['st_tongpc']
                    + $ar_III[0]['solieu_moi']['st_tongpc'] + $ar_IV[0]['solieu_moi']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu_moi']['ttbh_dv'] + $ar_II[0]['solieu_moi']['ttbh_dv']
                    + $ar_III[0]['solieu_moi']['ttbh_dv'] + $ar_IV[0]['solieu_moi']['ttbh_dv'],
            ];
            foreach ($a_phucap_st as $mapc => $tenpc) {
                $a_Tong['solieu_moi'][$mapc] = $ar_I[0]['solieu_moi'][$mapc] + $ar_II[0]['solieu_moi'][$mapc]
                    + $ar_III[0]['solieu_moi'][$mapc] + $ar_IV[0]['solieu_moi'][$mapc];
                $a_Tong['solieu'][$mapc] = $ar_I[0]['solieu'][$mapc] + $ar_II[0]['solieu'][$mapc]
                    + $ar_III[0]['solieu'][$mapc] + $ar_IV[0]['solieu'][$mapc];
            }

            //Tính lại chi tiết để dải đơn vị
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($m_chitiet as $chitiet) {
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? $chitiet->mact;
                $chitiet->tongbh_dv = $chitiet->ttbh_dv / $chenhlech; //tính toán để dùng
                $chitiet->tongpc = $chitiet->tonghs - $chitiet->heso; //tính toán để dùng
                //Tính mức lương cũ
                $chitiet->st_heso_cu =  round($chitiet->heso * $luongcb);
                $chitiet->ttbh_dv_cu = round($chitiet->tongbh_dv * $luongcb);
                $chitiet->st_tongpc_cu = round($chitiet->tongpc * $luongcb);
                $chitiet->tongcong_cu = $chitiet->st_tongpc_cu + $chitiet->st_heso_cu + $chitiet->ttbh_dv_cu;
                foreach ($a_phucap as $mapc => $tenpc) {
                    $mapc_st = 'st_' . $mapc . '_cu';
                    $chitiet->$mapc_st  = round($chitiet->$mapc * $luongcb);
                }
                //Mức lương mới
                $chitiet->st_heso_moi =  round($chitiet->heso * $luongcb_moi);
                $chitiet->ttbh_dv_moi = round($chitiet->tongbh_dv * $luongcb_moi);
                $chitiet->st_tongpc_moi = round($chitiet->tongpc * $luongcb_moi);
                $chitiet->tongcong_moi = $chitiet->st_tongpc_moi + $chitiet->st_heso_moi + $chitiet->ttbh_dv_moi;
                foreach ($a_phucap as $mapc => $tenpc) {
                    $mapc_st = 'st_' . $mapc . '_moi';
                    $chitiet->$mapc_st  = round($chitiet->$mapc * $luongcb_moi);
                }
                //Chênh lệch
                $chitiet->chenhlech01thang = $chitiet->tongcong_moi - $chitiet->tongcong_cu;
                $chitiet->chenhlech06thang = $chitiet->chenhlech01thang * 6;
            }
            //Tách nhóm giáo dục
            $m_nhomgiaoduc = $m_chitiet->where('linhvuchoatdong', 'GD');
            $m_chitiet = $m_chitiet->where('linhvuchoatdong', '<>', 'GD');
            $a_giaoduc = [
                'MAMNON' => 'Khối Trường Mầm non',
                'TIEUHOC' => 'Khối Trường Tiểu học',
                'THCS' => 'Khối Trường Trung học cơ sở',
                'THvaTHCS' => 'Khối Trường Tiểu học và Trung học cơ sở',
            ];

            foreach ($a_giaoduc as $key => $val) {
                $dulieu = $m_nhomgiaoduc->where('maphanloai', $key);
                if ($dulieu->count() == 0) {
                    continue;
                }

                foreach (array_unique(array_column($dulieu->toarray(), 'tenct', 'mact')) as $k => $v) {
                    $dulieu_ct = $dulieu->where('mact', $k);
                    $chitiet = clone $m_chitiet->first();
                    $chitiet->madv = $key;
                    $chitiet->tendv = $val;
                    $chitiet->tenct = $v;
                    $chitiet->mact = $k;
                    $chitiet->linhvuchoatdong = 'GD';
                    //Tính mức lương cũ

                    $chitiet->st_heso_cu =  $dulieu_ct->sum('st_heso_cu');
                    $chitiet->ttbh_dv_cu = $dulieu_ct->sum('ttbh_dv_cu');
                    $chitiet->st_tongpc_cu = $dulieu_ct->sum('st_tongpc_cu');
                    $chitiet->tongcong_cu = $dulieu_ct->sum('tongcong_cu');
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc . '_cu';
                        $chitiet->$mapc_st  = $dulieu_ct->sum($mapc_st);
                    }
                    //Mức lương mới
                    $chitiet->st_heso_moi =  $dulieu_ct->sum('st_heso_moi');
                    $chitiet->ttbh_dv_moi =  $dulieu_ct->sum('ttbh_dv_moi');
                    $chitiet->st_tongpc_moi =  $dulieu_ct->sum('st_tongpc_moi');
                    $chitiet->tongcong_moi =  $dulieu_ct->sum('tongcong_moi');
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc . '_moi';
                        $chitiet->$mapc_st  = $dulieu_ct->sum($mapc_st);
                    }
                    //Chênh lệch
                    $chitiet->chenhlech01thang = $dulieu_ct->sum('chenhlech01thang');
                    $chitiet->chenhlech06thang =  $dulieu_ct->sum('chenhlech06thang');
                    $chitiet->canbo_dutoan =  $dulieu_ct->sum('canbo_dutoan');
                    $chitiet->canbo_congtac =  $dulieu_ct->sum('canbo_congtac');
                    $m_chitiet->add($chitiet);
                }
            }
            // dd($m_nhomgiaoduc->toarray());
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_chitiet);
            return view('reports.thongtu78.huyen.mau2a_vn')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_donvi)
                ->with('m_chitiet', $m_chitiet->sortby('machuong'))
                ->with('inputs', $inputs)
                ->with('a_phucap', $a_phucap)
                ->with('a_phucap_st', $a_phucap_st)
                ->with('m_banhanh', $m_banhanh)
                ->with('a_nhomgd', array_keys($a_giaoduc))
                ->with('m_nhomgiaoduc', $m_nhomgiaoduc->sortby('maqhns'))
                ->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2b(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $ar_I = array();
            $ar_I[0] = array(
                'val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch',
                'songuoi' => $m_nguonkp->sum('tongsonguoi1'),
                'quy1' => $m_nguonkp->sum('quy1_1'),
                'quy2' => $m_nguonkp->sum('quy2_1'),
                'quy3' => $m_nguonkp->sum('quy3_1'),
                'tongquy' => $m_nguonkp->sum('quy1_tong'),

            );

            $ar_I[1] = array(
                'val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng',
                'songuoi' => $m_nguonkp->sum('tongsonguoi2'),
                'quy1' => $m_nguonkp->sum('quy1_2'),
                'quy2' => $m_nguonkp->sum('quy2_2'),
                'quy3' => $m_nguonkp->sum('quy3_2'),
                'tongquy' => $m_nguonkp->sum('quy2_tong'),
            );
            $ar_I[2] = array(
                'val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại',
                'songuoi' => $m_nguonkp->sum('tongsonguoi3'),
                'quy1' => $m_nguonkp->sum('quy1_3'),
                'quy2' => $m_nguonkp->sum('quy2_3'),
                'quy3' => $m_nguonkp->sum('quy3_3'),
                'tongquy' => $m_nguonkp->sum('quy3_tong'),
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
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');
            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
            }
            //chỉ lấy số liệu KVXP
            $m_nguonkp = $m_nguonkp->where('maphanloai', 'KVXP');
            //1800000-1490000 = 310000
            $ar_I = array();
            $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;');
            //
            $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                'tdv' => $m_nguon_1->count(),
                'mk' => 16,
                'mk2' => 21,
                'clt7' => round($m_nguon_1->count() * 16 * 310000),
                'cl5t' => round($m_nguon_1->count() * 21 * 310000 * 5),
            ]);
            $ar_I[1]['solieu']['tong'] = $ar_I[1]['solieu']['clt7'] + $ar_I[1]['solieu']['cl5t'];
            //

            $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                'tdv' => $m_nguon_2->count(),
                'mk' => 13.7,
                'mk2' => 18,
                'clt7' => round($m_nguon_2->count() * 13.7 * 310000),
                'cl5t' => round($m_nguon_2->count() * 18 * 310000 * 5),
            ]);
            $ar_I[2]['solieu']['tong'] = $ar_I[2]['solieu']['clt7'] + $ar_I[2]['solieu']['cl5t'];
            //

            $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                'tdv' => $m_nguon_3->count(),
                'mk' => 11.4,
                'mk2' => 15,
                'clt7' => round($m_nguon_3->count() * 11.4 * 310000),
                'cl5t' => round($m_nguon_3->count() * 15 * 310000 * 5),
            ]);
            $ar_I[3]['solieu']['tong'] = $ar_I[3]['solieu']['clt7'] + $ar_I[3]['solieu']['cl5t'];
            //Tổng phân loại xã
            $ar_I[0]['solieu'] = [
                'tdv' =>  $ar_I[1]['solieu']['tdv'] + $ar_I[2]['solieu']['tdv'] + $ar_I[3]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'clt7' => $ar_I[1]['solieu']['clt7'] + $ar_I[2]['solieu']['clt7'] + $ar_I[3]['solieu']['clt7'],
                'cl5t' => $ar_I[1]['solieu']['cl5t'] + $ar_I[2]['solieu']['cl5t'] + $ar_I[3]['solieu']['cl5t'],
                'tong' => $ar_I[1]['solieu']['tong'] + $ar_I[2]['solieu']['tong'] + $ar_I[3]['solieu']['tong'],
            ];

            //II = 5+8+13
            $ar_I[4] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố', 'style' => 'font-weight:bold;');
            //Số xã biên giới, hải đảo
            $ar_I[5] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 5,
                'mk2' => 6,
                'clt7' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 310000),
                'cl5t' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * 310000 * 5),
            ]);
            $ar_I[5]['solieu']['tong'] = $ar_I[5]['solieu']['clt7'] + $ar_I[5]['solieu']['cl5t'];

            //Thôn thuộc xã biên giới, hải đảo
            $ar_I[6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 5,
                'mk2' => 6,
                'clt7' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 310000),
                'cl5t' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * 310000 * 5),
            ]);
            $ar_I[6]['solieu']['tong'] = $ar_I[6]['solieu']['clt7'] + $ar_I[6]['solieu']['cl5t'];

            //Tổ dân phố thuộc xã biên giới, hải đảo
            $ar_I[7] = array('val' => '', 'tt' => '', 'noidung' => '- Tổ dân phố thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => 0, 'mk' => 0, 'mk2' => 0, 'clt7' => 0, 'cl5t' => 0, 'tong' => 0,
            ]);

            //II.2  8 = 9 + 10 + 11 + 12
            $ar_I[8] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

            //Số hộ 350 trở lên
            $ar_I[9] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothon350hgd_2d'),
                'mk' => 5,
                'mk2' => 6,
                'clt7' => round($m_nguonkp->sum('sothon350hgd_2d') * 5 * 310000),
                'cl5t' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * 310000 * 5),
            ]);
            $ar_I[9]['solieu']['tong'] = $ar_I[9]['solieu']['clt7'] + $ar_I[9]['solieu']['cl5t'];

            //500 hộ trở lên
            $ar_I[10] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotodanpho500hgd_2d'),
                'mk' => 3,
                'mk2' => 6,
                'clt7' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 3 * 310000),
                'cl5t' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * 310000 * 5),
            ]);
            $ar_I[10]['solieu']['tong'] = $ar_I[10]['solieu']['clt7'] + $ar_I[10]['solieu']['cl5t'];

            //Tổ dân phố thuộc xã trọng điểm về an ninh
            $ar_I[11] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 3,
                'mk2' => 6,
                'clt7' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 310000),
                'cl5t' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * 310000 * 5),
            ]);
            $ar_I[11]['solieu']['tong'] = $ar_I[11]['solieu']['clt7'] + $ar_I[11]['solieu']['cl5t'];

            //Tổ dân phố chuyển từ thôn
            $ar_I[12] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sochuyentuthon350hgd_2d'),
                'mk' => 3,
                'mk2' => 6,
                'clt7' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 3 * 310000),
                'cl5t' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * 310000 * 5),
            ]);
            $ar_I[12]['solieu']['tong'] = $ar_I[12]['solieu']['clt7'] + $ar_I[12]['solieu']['cl5t'];

            //Số liệu II.2 8 = 9 + 10 + 11 + 12
            $ar_I[8]['solieu'] = [
                'tdv' =>  $ar_I[9]['solieu']['tdv'] + $ar_I[10]['solieu']['tdv'] + $ar_I[11]['solieu']['tdv'] + $ar_I[12]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'clt7' =>  $ar_I[9]['solieu']['clt7'] + $ar_I[10]['solieu']['clt7'] + $ar_I[11]['solieu']['clt7'] + $ar_I[12]['solieu']['clt7'],
                'cl5t' =>  $ar_I[9]['solieu']['cl5t'] + $ar_I[10]['solieu']['cl5t'] + $ar_I[11]['solieu']['cl5t'] + $ar_I[12]['solieu']['cl5t'],
                'tong' =>  $ar_I[9]['solieu']['tong'] + $ar_I[10]['solieu']['tong'] + $ar_I[11]['solieu']['tong'] + $ar_I[12]['solieu']['tong'],
            ];

            //II.3 13 = 14 + 15
            $ar_I[13] = array('val' => 'TDBKK', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại',);

            //Thôn còn lại
            $ar_I[14] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonconlai_2d'),
                'mk' => 3,
                'mk2' => 4.5,
                'clt7' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * 310000),
                'cl5t' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * 310000 * 5),
            ]);
            $ar_I[14]['solieu']['tong'] = $ar_I[14]['solieu']['clt7'] + $ar_I[14]['solieu']['cl5t'];

            //Tổ dân phố
            $ar_I[15] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                'mk' => 3,
                'mk2' => 4.5,
                'clt7' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * 310000),
                'cl5t' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * 310000 * 5),
            ]);
            $ar_I[15]['solieu']['tong'] = $ar_I[15]['solieu']['clt7'] + $ar_I[15]['solieu']['cl5t'];
            //Số liệu II.3  13 = 14 + 15
            $ar_I[13]['solieu'] = [
                'tdv' =>  $ar_I[14]['solieu']['tdv'] + $ar_I[15]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'clt7' =>  $ar_I[14]['solieu']['clt7'] + $ar_I[15]['solieu']['clt7'],
                'cl5t' =>  $ar_I[14]['solieu']['cl5t'] + $ar_I[15]['solieu']['cl5t'],
                'tong' =>  $ar_I[14]['solieu']['tong'] + $ar_I[15]['solieu']['tong'],
            ];

            //II = 5+8+13 
            $ar_I[4]['solieu'] = [
                'tdv' =>  $ar_I[5]['solieu']['tdv'] + $ar_I[8]['solieu']['tdv'] + $ar_I[13]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'clt7' =>  $ar_I[5]['solieu']['clt7'] + $ar_I[8]['solieu']['clt7'] + $ar_I[13]['solieu']['clt7'],
                'cl5t' =>  $ar_I[5]['solieu']['cl5t'] + $ar_I[8]['solieu']['cl5t'] + $ar_I[13]['solieu']['cl5t'],
                'tong' =>  $ar_I[5]['solieu']['tong'] + $ar_I[8]['solieu']['tong'] + $ar_I[13]['solieu']['tong'],
            ];

            $a_It = array(
                'tdv' =>  $ar_I[0]['solieu']['tdv'] + $ar_I[4]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'clt7' =>  $ar_I[0]['solieu']['clt7'] + $ar_I[4]['solieu']['clt7'],
                'cl5t' =>  $ar_I[0]['solieu']['cl5t'] + $ar_I[4]['solieu']['cl5t'],
                'tong' =>  $ar_I[0]['solieu']['tong'] + $ar_I[4]['solieu']['tong'],
            );

            //dd($ar_I);
            return view('reports.thongtu78.huyen.mau2c')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2d(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
            }
            $m_nguonkp = $m_nguonkp->where('maphanloai', 'KVXP');
            // dd($m_nguonkp);
            //Tính toán số liệu phần I
            // $ar_I[0] = array('phanloai'=>'','style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);
            $ar_I[0] = array('phanloai' => '', 'style' => 'font-weight: bold;', 'tt' => 'II', 'noidung' => 'XÃ',);

            $ar_I[1] = array('phanloai' => 'XL1', 'style' => 'font-weight: bold;', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1')->where('soluongdinhbien_2d', '<>', 0);
            // dd($m_xl1);
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2k' => $m_xl1->count(),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL1'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL1') * $m_xl1->count(),
                'qd34_2d' => $m_xl1->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_xl1->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_xl1->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_xl1->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_xl1->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_xl1->sum('tyledonggop_2d'),
                // 'soluongdinhbien_2d' => $m_xl1->sum('soluongdinhbien_2d'),
                'soluongdinhbien_2d' => $m_xl1->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL1') * $m_xl1->count(),
                'tongsodinhbien_2d' => $m_xl1->count() * getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL1'),
                'quyluonggiam_2k' => $m_xl1->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl1->sum('quyluonggiam_2k') * 5,
            ];

            $ar_I[2] = array('phanloai' => 'XL2', 'style' => 'font-weight: bold;', 'tt' => '2', 'noidung' => 'Xã loại 2',);
            $m_xl2 = $m_nguonkp->where('phanloaixa', 'XL2')->where('soluongdinhbien_2d', '<>', 0);
            $ar_I[2]['solieu'] = [
                'soluongdonvi_2k' => $m_xl2->count(),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL2'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL2') * $m_xl2->count(),
                'qd34_2d' => $m_xl2->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_xl2->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_xl2->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_xl2->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_xl2->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_xl2->sum('tyledonggop_2d'),
                // 'soluongdinhbien_2d' => $m_xl2->sum('soluongdinhbien_2d'),
                // 'soluongdinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2'),
                // 'tongsodinhbien_2d'=>$m_xl2->count() * getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2'),
                'soluongdinhbien_2d' => $m_xl2->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2') * $m_xl2->count(), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2') * $m_xl2->count(),
                'quyluonggiam_2k' => $m_xl2->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl2->sum('quyluonggiam_2k') * 5,
            ];

            $ar_I[3] = array('phanloai' => 'XL3', 'style' => 'font-weight: bold;', 'tt' => '3', 'noidung' => 'Xã loại 3',);
            $m_xl3 = $m_nguonkp->where('phanloaixa', 'XL3')->where('soluongdinhbien_2d', '<>', 0);
            $ar_I[3]['solieu'] = [
                'soluongdonvi_2k' => $m_xl3->count(),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3') * $m_xl3->count(),
                'qd34_2d' => $m_xl3->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_xl3->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_xl3->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_xl3->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_xl3->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_xl3->sum('tyledonggop_2d'),
                // 'soluongdinhbien_2d' => $m_xl3->sum('soluongdinhbien_2d'),
                // 'soluongdinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3'),
                // 'tongsodinhbien_2d'=>$m_xl3->count() * getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3'),
                'soluongdinhbien_2d' => $m_xl3->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3') * $m_xl3->count(), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3') * $m_xl3->count(),
                'quyluonggiam_2k' => $m_xl3->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl3->sum('quyluonggiam_2k') * 5,
            ];

            $ar_I[0]['solieu'] = [
                'soluongdonvi_2k' => $ar_I[1]['solieu']['soluongdonvi_2k'] + $ar_I[2]['solieu']['soluongdonvi_2k'] + $ar_I[3]['solieu']['soluongdonvi_2k'],
                'qd34_2d' => 0,
                'tongqd34_2d' => $ar_I[1]['solieu']['tongqd34_2d'] + $ar_I[2]['solieu']['tongqd34_2d'] + $ar_I[3]['solieu']['tongqd34_2d'],
                'soluongcanbo_2d' => $ar_I[1]['solieu']['soluongcanbo_2d'] + $ar_I[2]['solieu']['soluongcanbo_2d'] + $ar_I[3]['solieu']['soluongcanbo_2d'],
                'hesoluongbq_2d' => $ar_I[1]['solieu']['hesoluongbq_2d'] + $ar_I[2]['solieu']['hesoluongbq_2d'] + $ar_I[3]['solieu']['hesoluongbq_2d'],
                'hesophucapbq_2d' => $ar_I[1]['solieu']['hesophucapbq_2d'] + $ar_I[2]['solieu']['hesophucapbq_2d'] + $ar_I[3]['solieu']['hesophucapbq_2d'],
                'tyledonggop_2d' => $ar_I[1]['solieu']['tyledonggop_2d'] + $ar_I[2]['solieu']['tyledonggop_2d'] + $ar_I[3]['solieu']['tyledonggop_2d'],
                // 'soluongdinhbien_2d' => $ar_I[1]['solieu']['soluongdinhbien_2d'] + $ar_I[2]['solieu']['soluongdinhbien_2d'] + $ar_I[3]['solieu']['soluongdinhbien_2d'],
                'soluongdinhbien_2d' => 0,
                'tongsodinhbien_2d' => $ar_I[1]['solieu']['tongsodinhbien_2d'] + $ar_I[2]['solieu']['tongsodinhbien_2d'] + $ar_I[3]['solieu']['tongsodinhbien_2d'],
                'quyluonggiam_2k' => $ar_I[1]['solieu']['quyluonggiam_2k'] + $ar_I[2]['solieu']['quyluonggiam_2k'] + $ar_I[3]['solieu']['quyluonggiam_2k'],
                'tongquyluonggiam_2k' => $ar_I[1]['solieu']['tongquyluonggiam_2k'] + $ar_I[2]['solieu']['tongquyluonggiam_2k'] + $ar_I[3]['solieu']['tongquyluonggiam_2k'],
            ];
            $ar_tong = [
                'XL1' => $m_xl1,
                'XL2' => $m_xl2,
                'XL3' => $m_xl3
            ];
            //Tính toán số liệu cho phân loại phường
            $ar_II[0] = array('phanloai' => '', 'style' => 'font-weight: bold;', 'tt' => 'I', 'noidung' => 'PHƯỜNG',);

            $ar_II[1] = array('phanloai' => 'PL1', 'style' => 'font-weight: bold;', 'tt' => '1', 'noidung' => 'Phường loại 1',);
            $m_pl1 = $m_nguonkp->where('phanloaixa', 'PL1')->where('soluongdinhbien_2d', '<>', 0);
            // dd($m_pl1);
            // dd($m_xl1);
            $ar_II[1]['solieu'] = [
                'soluongdonvi_2k' => $m_pl1->count(),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL1'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL1') * $m_xl1->count(),
                'qd34_2d' => $m_pl1->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_pl1->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_pl1->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_pl1->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_pl1->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_pl1->sum('tyledonggop_2d'),
                // 'soluongdinhbien_2d' => $m_xl1->sum('soluongdinhbien_2d'),
                'soluongdinhbien_2d' => $m_pl1->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/PHUONG', 'PL1') * $m_pl1->count(),
                'tongsodinhbien_2d' => $m_pl1->count() * getSoLuongCanBoDinhMuc('ND33/2023/PHUONG', 'PL1'),
                'quyluonggiam_2k' => $m_pl1->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_pl1->sum('quyluonggiam_2k') * 5,
            ];

            $ar_II[2] = array('phanloai' => 'PL2', 'style' => 'font-weight: bold;', 'tt' => '2', 'noidung' => 'Phường loại 2',);
            $m_pl2 = $m_nguonkp->where('phanloaixa', 'PL2')->where('soluongdinhbien_2d', '<>', 0);
            $ar_II[2]['solieu'] = [
                'soluongdonvi_2k' => $m_pl2->count(),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL2'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL2') * $m_xl2->count(),
                'qd34_2d' => $m_pl2->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_pl2->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_pl2->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_pl2->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_pl2->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_pl2->sum('tyledonggop_2d'),
                // 'soluongdinhbien_2d' => $m_xl2->sum('soluongdinhbien_2d'),
                // 'soluongdinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2'),
                // 'tongsodinhbien_2d'=>$m_xl2->count() * getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2'),
                'soluongdinhbien_2d' => $m_pl2->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/PHUONG', 'PL2') * $m_pl2->count(), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/PHUONG', 'PL2') * $m_pl2->count(),
                'quyluonggiam_2k' => $m_pl2->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_pl2->sum('quyluonggiam_2k') * 5,
            ];

            $ar_II[3] = array('phanloai' => 'PL3', 'style' => 'font-weight: bold;', 'tt' => '3', 'noidung' => 'Phường loại 3',);
            $m_pl3 = $m_nguonkp->where('phanloaixa', 'PL3')->where('soluongdinhbien_2d', '<>', 0);
            $ar_II[3]['solieu'] = [
                'soluongdonvi_2k' => $m_pl3->count(),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3') * $m_xl3->count(),
                'qd34_2d' => $m_pl3->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_pl3->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_pl3->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_pl3->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_pl3->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_pl3->sum('tyledonggop_2d'),
                // 'soluongdinhbien_2d' => $m_xl3->sum('soluongdinhbien_2d'),
                // 'soluongdinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3'),
                // 'tongsodinhbien_2d'=>$m_xl3->count() * getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3'),
                'soluongdinhbien_2d' => $m_pl3->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/PHUONG', 'PL3') * $m_pl3->count(), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/PHUONG', 'PL3') * $m_pl3->count(),
                'quyluonggiam_2k' => $m_pl3->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_pl3->sum('quyluonggiam_2k') * 5,
            ];

            $ar_II[0]['solieu'] = [
                'soluongdonvi_2k' => $ar_II[1]['solieu']['soluongdonvi_2k'] + $ar_II[2]['solieu']['soluongdonvi_2k'] + $ar_II[3]['solieu']['soluongdonvi_2k'],
                'qd34_2d' => 0,
                'tongqd34_2d' => $ar_II[1]['solieu']['tongqd34_2d'] + $ar_II[2]['solieu']['tongqd34_2d'] + $ar_II[3]['solieu']['tongqd34_2d'],
                'soluongcanbo_2d' => $ar_II[1]['solieu']['soluongcanbo_2d'] + $ar_II[2]['solieu']['soluongcanbo_2d'] + $ar_II[3]['solieu']['soluongcanbo_2d'],
                'hesoluongbq_2d' => $ar_II[1]['solieu']['hesoluongbq_2d'] + $ar_II[2]['solieu']['hesoluongbq_2d'] + $ar_II[3]['solieu']['hesoluongbq_2d'],
                'hesophucapbq_2d' => $ar_II[1]['solieu']['hesophucapbq_2d'] + $ar_II[2]['solieu']['hesophucapbq_2d'] + $ar_II[3]['solieu']['hesophucapbq_2d'],
                'tyledonggop_2d' => $ar_II[1]['solieu']['tyledonggop_2d'] + $ar_II[2]['solieu']['tyledonggop_2d'] + $ar_II[3]['solieu']['tyledonggop_2d'],
                // 'soluongdinhbien_2d' => $ar_I[1]['solieu']['soluongdinhbien_2d'] + $ar_I[2]['solieu']['soluongdinhbien_2d'] + $ar_I[3]['solieu']['soluongdinhbien_2d'],
                'soluongdinhbien_2d' => 0,
                'tongsodinhbien_2d' => $ar_II[1]['solieu']['tongsodinhbien_2d'] + $ar_II[2]['solieu']['tongsodinhbien_2d'] + $ar_II[3]['solieu']['tongsodinhbien_2d'],
                'quyluonggiam_2k' => $ar_II[1]['solieu']['quyluonggiam_2k'] + $ar_II[2]['solieu']['quyluonggiam_2k'] + $ar_II[3]['solieu']['quyluonggiam_2k'],
                'tongquyluonggiam_2k' => $ar_II[1]['solieu']['tongquyluonggiam_2k'] + $ar_II[2]['solieu']['tongquyluonggiam_2k'] + $ar_II[3]['solieu']['tongquyluonggiam_2k'],
            ];
            $ar_tong_phuong = [
                'PL1' => $m_pl1,
                'PL2' => $m_pl2,
                'PL3' => $m_pl3
            ];
            // $a_tong=array('phanloai'=>'','style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);
            $a_tong = [
                'phanloai' => '', 'style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',
                'soluongdonvi_2k' => $ar_I[0]['solieu']['soluongdonvi_2k'] + $ar_II[0]['solieu']['soluongdonvi_2k'],
                'qd34_2d' => 0,
                'tongqd34_2d' => $ar_I[0]['solieu']['tongqd34_2d'] + $ar_II[0]['solieu']['tongqd34_2d'],
                'soluongcanbo_2d' => $ar_I[0]['solieu']['soluongcanbo_2d'] + $ar_II[0]['solieu']['soluongcanbo_2d'],
                'hesoluongbq_2d' => $ar_I[0]['solieu']['hesoluongbq_2d'] + $ar_II[0]['solieu']['hesoluongbq_2d'],
                'hesophucapbq_2d' => $ar_I[0]['solieu']['hesophucapbq_2d'] + $ar_II[0]['solieu']['hesophucapbq_2d'],
                'tyledonggop_2d' => $ar_I[0]['solieu']['tyledonggop_2d'] + $ar_II[0]['solieu']['tyledonggop_2d'],
                // 'soluongdinhbien_2d' => $ar_I[1]['solieu']['soluongdinhbien_2d'] + $ar_I[2]['solieu']['soluongdinhbien_2d'] + $ar_I[3]['solieu']['soluongdinhbien_2d'],
                'soluongdinhbien_2d' => 0,
                'tongsodinhbien_2d' => $ar_I[0]['solieu']['tongsodinhbien_2d'] + $ar_II[0]['solieu']['tongsodinhbien_2d'],
                'quyluonggiam_2k' => $ar_I[0]['solieu']['quyluonggiam_2k'] + $ar_II[0]['solieu']['quyluonggiam_2k'],
                'tongquyluonggiam_2k' => $ar_I[0]['solieu']['tongquyluonggiam_2k'] + $ar_II[0]['solieu']['tongquyluonggiam_2k'],
            ];

            $inputs['lamtron'] = session('admin')->lamtron;
            // dd($ar_II);
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau2d_huyen')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('ar_tong', $ar_tong)
                ->with('ar_tong_phuong', $ar_tong_phuong)
                ->with('a_tong', $a_tong)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2e(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();
            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');
            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];
            }
            //chỉ lấy số liệu KVXP
            $m_nguonkp = $m_nguonkp->where('maphanloai', 'KVXP');

            $ar_I = array();
            $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;');
            //
            $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                'tdv' => $m_nguon_1->count(),
                'mk' => 16,
                'mk2' => 21,
                'quyluong34' => round($m_nguon_1->count() * 16 * 1490000),
                'quyluong33' => round($m_nguon_1->count() * 21 * 1490000),
            ]);
            $ar_I[1]['solieu']['tong'] = ($ar_I[1]['solieu']['quyluong33'] - $ar_I[1]['solieu']['quyluong34']) * 5;
            //

            $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                'tdv' => $m_nguon_2->count(),
                'mk' => 13.7,
                'mk2' => 18,
                'quyluong34' => round($m_nguon_2->count() * 13.7 * 1490000),
                'quyluong33' => round($m_nguon_2->count() * 18 * 1490000),
            ]);
            $ar_I[2]['solieu']['tong'] = ($ar_I[2]['solieu']['quyluong33'] - $ar_I[2]['solieu']['quyluong34']) * 5;
            //

            $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                'tdv' => $m_nguon_3->count(),
                'mk' => 11.4,
                'mk2' => 15,
                'quyluong34' => round($m_nguon_3->count() * 11.4 * 1490000),
                'quyluong33' => round($m_nguon_3->count() * 15 * 1490000),
            ]);
            $ar_I[3]['solieu']['tong'] = ($ar_I[3]['solieu']['quyluong33'] - $ar_I[3]['solieu']['quyluong34']) * 5;
            //Tổng phân loại xã
            $ar_I[0]['solieu'] = [
                'tdv' =>  $ar_I[1]['solieu']['tdv'] + $ar_I[2]['solieu']['tdv'] + $ar_I[3]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'quyluong33' => $ar_I[1]['solieu']['quyluong33'] + $ar_I[2]['solieu']['quyluong33'] + $ar_I[3]['solieu']['quyluong33'],
                'quyluong34' => $ar_I[1]['solieu']['quyluong34'] + $ar_I[2]['solieu']['quyluong34'] + $ar_I[3]['solieu']['quyluong34'],
                'tong' => $ar_I[1]['solieu']['tong'] + $ar_I[2]['solieu']['tong'] + $ar_I[3]['solieu']['tong'],
            ];

            //II = 5+8+13
            $ar_I[4] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố', 'style' => 'font-weight:bold;');

            //II.1Số xã biên giới, hải đảo (5 = 6 + 7)
            $ar_I[5] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo',);

            //Thôn thuộc xã biên giới, hải đảo
            $ar_I[6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 5,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * 1490000),
            ]);
            $ar_I[6]['solieu']['tong'] = ($ar_I[6]['solieu']['quyluong33'] - $ar_I[6]['solieu']['quyluong34']) * 5;

            //Tổ dân phố thuộc xã biên giới, hải đảo
            $ar_I[7] = array('val' => '', 'tt' => '', 'noidung' => '- Tổ dân phố thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotodanphobiengioi_2d'),
                'mk' => 0,
                'mk2' => 6,
                'quyluong34' => 0,
                'quyluong33' => round($m_nguonkp->sum('sotodanphobiengioi_2d') * 6 * 1490000),
            ]);
            $ar_I[7]['solieu']['tong'] = ($ar_I[7]['solieu']['quyluong33'] - $ar_I[7]['solieu']['quyluong34']) * 5;

            //Số liệu II.1 (5 = 6 + 7)
            $ar_I[5]['solieu'] = [
                'tdv' =>  $ar_I[6]['solieu']['tdv'] + $ar_I[7]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'quyluong34' =>  $ar_I[6]['solieu']['quyluong34'] + $ar_I[7]['solieu']['quyluong34'],
                'quyluong33' =>  $ar_I[6]['solieu']['quyluong33'] + $ar_I[7]['solieu']['quyluong33'],
                'tong' =>  $ar_I[6]['solieu']['tong'] + $ar_I[7]['solieu']['tong'],
            ];

            //II.2  8 = 9 + 10 + 11 + 12
            $ar_I[8] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

            //Số hộ 350 trở lên
            $ar_I[9] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothon350hgd_2d'),
                'mk' => 5,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sothon350hgd_2d') * 5 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * 1490000),
            ]);
            $ar_I[9]['solieu']['tong'] = ($ar_I[9]['solieu']['quyluong33'] - $ar_I[9]['solieu']['quyluong34']) * 5;

            //500 hộ trở lên
            $ar_I[10] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotodanpho500hgd_2d'),
                'mk' => 3,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 3 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * 1490000),
            ]);
            $ar_I[10]['solieu']['tong'] = ($ar_I[10]['solieu']['quyluong33'] - $ar_I[10]['solieu']['quyluong34']) * 5;

            //Tổ dân phố thuộc xã trọng điểm về an ninh
            $ar_I[11] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 3,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * 1490000),
            ]);
            $ar_I[11]['solieu']['tong'] = ($ar_I[11]['solieu']['quyluong33'] - $ar_I[11]['solieu']['quyluong34']) * 5;

            //Tổ dân phố chuyển từ thôn
            $ar_I[12] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sochuyentuthon350hgd_2d'),
                'mk' => 3,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 3 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * 1490000),
            ]);
            $ar_I[12]['solieu']['tong'] = ($ar_I[12]['solieu']['quyluong33'] - $ar_I[12]['solieu']['quyluong34']) * 5;
            //Số liệu II.2 8 = 9 + 10 + 11 + 12
            $ar_I[8]['solieu'] = [
                'tdv' =>  $ar_I[9]['solieu']['tdv'] + $ar_I[10]['solieu']['tdv'] + $ar_I[11]['solieu']['tdv'] + $ar_I[12]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'quyluong34' =>  $ar_I[9]['solieu']['quyluong34'] + $ar_I[10]['solieu']['quyluong34'] + $ar_I[11]['solieu']['quyluong34'] + $ar_I[12]['solieu']['quyluong34'],
                'quyluong33' =>  $ar_I[9]['solieu']['quyluong33'] + $ar_I[10]['solieu']['quyluong33'] + $ar_I[11]['solieu']['quyluong33'] + $ar_I[12]['solieu']['quyluong33'],
                'tong' =>  $ar_I[9]['solieu']['tong'] + $ar_I[10]['solieu']['tong'] + $ar_I[11]['solieu']['tong'] + $ar_I[12]['solieu']['tong'],
            ];

            //II.3 13 = 14 + 15
            $ar_I[13] = array('val' => 'TDBKK', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại',);

            //Thôn còn lại
            $ar_I[14] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonconlai_2d'),
                'mk' => 3,
                'mk2' => 4.5,
                'quyluong34' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * 1490000),
            ]);
            $ar_I[14]['solieu']['tong'] = ($ar_I[14]['solieu']['quyluong33'] - $ar_I[14]['solieu']['quyluong34']) * 5;
            //Tổ dân phố
            $ar_I[15] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                'mk' => 3,
                'mk2' => 4.5,
                'quyluong34' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * 1490000),
                'quyluong33' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * 1490000),
            ]);
            $ar_I[15]['solieu']['tong'] = ($ar_I[15]['solieu']['quyluong33'] - $ar_I[15]['solieu']['quyluong34']) * 5;
            //Số liệu II.3  13 = 14 + 15
            $ar_I[13]['solieu'] = [
                'tdv' =>  $ar_I[14]['solieu']['tdv'] + $ar_I[15]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'quyluong34' =>  $ar_I[14]['solieu']['quyluong34'] + $ar_I[15]['solieu']['quyluong34'],
                'quyluong33' =>  $ar_I[14]['solieu']['quyluong33'] + $ar_I[15]['solieu']['quyluong33'],
                'tong' =>  $ar_I[14]['solieu']['tong'] + $ar_I[15]['solieu']['tong'],
            ];

            //II = 5+8+13 
            $ar_I[4]['solieu'] = [
                'tdv' =>  $ar_I[5]['solieu']['tdv'] + $ar_I[8]['solieu']['tdv'] + $ar_I[13]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'quyluong34' =>  $ar_I[5]['solieu']['quyluong34'] + $ar_I[8]['solieu']['quyluong34'] + $ar_I[13]['solieu']['quyluong34'],
                'quyluong33' =>  $ar_I[5]['solieu']['quyluong33'] + $ar_I[8]['solieu']['quyluong33'] + $ar_I[13]['solieu']['quyluong33'],
                'tong' =>  $ar_I[5]['solieu']['tong'] + $ar_I[8]['solieu']['tong'] + $ar_I[13]['solieu']['tong'],
            ];

            $a_It = array(
                'tdv' =>  $ar_I[0]['solieu']['tdv'] + $ar_I[4]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'quyluong34' =>  $ar_I[0]['solieu']['quyluong34'] + $ar_I[4]['solieu']['quyluong34'],
                'quyluong33' =>  $ar_I[0]['solieu']['quyluong33'] + $ar_I[4]['solieu']['quyluong33'],
                'tong' =>  $ar_I[0]['solieu']['tong'] + $ar_I[4]['solieu']['tong'],
            );

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau2e')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM THEO NGHỊ ĐỊNH 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2g(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

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
                $chitiet->tongluong_2i = 0;
                $chitiet->chenhlech_2i = 0;
                $chitiet->quyluong_2i = 0;
                $model = nguonkinhphi_01thang::where('masodv', $chitiet->masodv)->first();
                $chitiet->tonghesophucap = isset($model) ? $model->pccv + $model->pcvk : 0;
                $chitiet->tongheso = (isset($model) ? $model->heso : 0) + $chitiet->tonghesophucap;
                $chitiet->heso = isset($model) ? $model->heso : 0;
                $chitiet->pccv = isset($model) ? $model->pccv : 0;
                $chitiet->pcvk = isset($model) ? $model->pcvk : 0;
                $chitiet->pcud61 = isset($model) ? $model->pcud61 : 0;
            }
            $m_nguonkp_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->where('pcud61', '>', 0)->get();
            foreach ($m_nguonkp_ct as $val) {
                $val->tonghesophucap = $val->pccv + $val->pcvk;
                $val->tongheso = $val->heso + $val->tonghesophucap;
                $val->chenhlech = $val->tongheso - $val->pcud61;
                $val->nhucau1t = $val->tongheso * $val->chenhlech * 1.49;
                $val->nhucau2022 = $val->nhucau1t * 12;
                $val->nhucau2023 = $val->nhucau1t * 6 + (($val->nhucau1t * 1.8) / 1.49 * 6);
            }

            // dd(array_column($m_nguonkp->toarray(),'masodv'));
            // dd($m_nguonkp);
            //dd($m_nguonkp);
            $inputs['lamtron'] = session('admin')->lamtron;
            $m_dshuyen = dmdonvibaocao::where('level', 'H')->get();
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2g')
                ->with('m_chitiet', $m_nguonkp)
                ->with('m_nguonkp_ct', $m_nguonkp_ct)
                ->with('m_dshuyen', $m_dshuyen)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau4a(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');
            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

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
                $chitiet->tongnhucau = ($chitiet->ttbh_dv + $chitiet->ttl) * 6;
            }
            // dd($m_chitiet);
            //Số liệu đơn vị
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                //dd($chitiet);
                //Tinh số liệu 2b 
                $chitiet->nhucau2b = round($chitiet->quy1_tong + $chitiet->quy2_tong + $chitiet->quy3_tong);
                //Tính số liệu 2d
                if ($chitiet->maphanloai == 'KVXP') {
                    $sotien = 1490000 * 5;
                    $solieu_2d = round($chitiet->quyluonggiam_2k * 5);
                    //Tính số liệu 2e                    
                    //chênh lệch xã
                    $chenhlech_plxa = (getMucKhoanPhuCapXa('ND33/2023', $chitiet->phanloaixa) - getMucKhoanPhuCapXa('ND34/2019', $chitiet->phanloaixa)) * $sotien;
                    //Xã biên giới
                    $chenhlech_xabg = $chitiet->sothonbiengioi_2d * (6 - 5) * $sotien +
                        $chitiet->sotodanphobiengioi_2d * (6 - 0) * $sotien;
                    //Số xã có 350 HGD trở lên
                    $chenhlech_xahgd = $chitiet->sothon350hgd_2d * (6 - 5) * $sotien +
                        $chitiet->sotodanpho500hgd_2d * (6 - 3) * $sotien +
                        $chitiet->sothontrongdiem_2d * (6 - 3) * $sotien +
                        $chitiet->sochuyentuthon350hgd_2d * (6 - 3) * $sotien;
                    //Số xã còn lại
                    $chenhlech_xacl = $chitiet->sothonconlai_2d * (4.5 - 3) * $sotien +
                        $chitiet->sotoconlai_2d * (4.5 - 3) * $sotien;
                    //2e
                    $solieu_2e = $chenhlech_plxa + $chenhlech_xabg + $chenhlech_xahgd + $chenhlech_xacl;
                    $chitiet->solieu2e = $solieu_2e;
                    $chitiet->nhucau_4a = $solieu_2d + $solieu_2e;
                    //số liệu 2c
                    $clt7 = 0;
                    $cl5t = 0;
                    if ($chitiet->phanloaixa == 'XL1') {
                        $clt7 = round(16 * 310000);
                        $cl5t = round(21 * 310000 * 5);
                    } else if ($chitiet->phanloaixa == 'XL2') {
                        $clt7 = round(13.7 * 310000);
                        $cl5t = round(18 * 310000 * 5);
                    } else if ($chitiet->phanloaixa == 'XL3') {
                        $clt7 = round(11.4 * 310000);
                        $cl5t = round(15 * 310000 * 5);
                    }
                    $solieu_plxa = $clt7 + $cl5t;
                    //Số xã biên giới
                    $solieu_xabiengioi_clt7 = round($chitiet->sothonbiengioi_2d * 5 * 310000);
                    $solieu_xabiengioi_cl5t = round($chitiet->sothonbiengioi_2d * 6 * 310000 * 5);
                    $solieu_xabiengioi = $solieu_xabiengioi_clt7 +  $solieu_xabiengioi_cl5t;
                    //số thôn có 350 hộ trở lên
                    $soho_350_clt7 = round($chitiet->sothon350hgd_2d * 5 * 310000);
                    $soho_350_cl5t = round($chitiet->sothon350hgd_2d * 6 * 310000 * 5);
                    $soho_350 = $soho_350_clt7 + $soho_350_cl5t;
                    $soho_500_clt7 = round($chitiet->sotodanpho500hgd_2d * 3 * 310000);
                    $soho_500_cl5t = round($chitiet->sotodanpho500hgd_2d * 6 * 310000 * 5);
                    $soho_500 = $soho_500_clt7 + $soho_500_cl5t;
                    //tổ dân phố trọng điểm an ninh
                    $sothon_trongdiem_clt7 = round($chitiet->sothontrongdiem_2d * 3 * 310000);
                    $sothon_trongdiem_cl5t = round($chitiet->sothontrongdiem_2d * 6 * 310000 * 5);
                    $sothon_trongdiem = $sothon_trongdiem_clt7 + $sothon_trongdiem_cl5t;
                    //tổ dân phố chuyển từ thôn
                    $sochuyentuthon_clt7 = round($chitiet->sochuyentuthon350hgd_2d * 3 * 310000);
                    $sochuyentuthon_cl5t = round($chitiet->sochuyentuthon350hgd_2d * 6 * 310000 * 5);
                    $sochuyentuthon = $sochuyentuthon_clt7 +  $sochuyentuthon_cl5t;
                    //Thôn còn lại
                    $sothonconlai_clt7 = round($chitiet->sothonconlai_2d * 3 * 310000);
                    $sothonconlai_cl5t = round($chitiet->sothonconlai_2d * 4.5 * 310000 * 5);
                    $sothonconlai =  $sothonconlai_clt7 +  $sothonconlai_cl5t;
                    //tổ dân phố còn lại
                    $sotoconlai_clt7 = round($chitiet->sotoconlai_2d * 3 * 310000);
                    $sotoconlai_cl5t = round($chitiet->sotoconlai_2d * 4.5 * 310000 * 5);
                    $sotoconlai = $sotoconlai_clt7 + $sotoconlai_cl5t;
                    $thontodanpho = $solieu_xabiengioi +  $soho_350 + $soho_500 + $sothon_trongdiem +  $sochuyentuthon + $sothonconlai +  $sotoconlai;
                    $chitiet->nhucau2c = $solieu_plxa + $thontodanpho;
                }
            }
            //Phần A
            $a_A = get4a_TT50_A();

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
            $a_BI = array();
            $a_BI[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BI[1] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BI[2] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BI[3] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 44/2023/NĐ-CP', 'sotien' => '0');
            $a_BI[4] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BI[5] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BI[6] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2021', 'sotien' => '0');


            $a_BI[0]['sotien'] = $m_chitiet->where('nhomnhucau', 'BIENCHE')->sum('tongnhucau');
            $a_BI[1]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOCT')->sum('tongnhucau');
            $a_BI[2]['sotien'] = $m_chitiet->where('nhomnhucau', 'HDND')->sum('tongnhucau');
            $a_BI[3]['sotien'] = $m_nguonkp->sum('nhucau2b'); //Lấy dữ liệu mẫu 2b
            // $a_BI[4]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOKCT')->sum('tongnhucau');
            $a_BI[4]['sotien'] =  $m_nguonkp->sum('nhucau2c'); //lấy dữ liệu mẫu 2c
            $a_BI[5]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->wherein('level', ['XA', 'HUYEN'])->sum('tongnhucau');
            $a_BI[6]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->where('level', 'TINH')->sum('tongnhucau');

            // dd($m_chitiet->where('nhomnhucau', 'CAPUY'));

            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Phụ cấp Ưu đãi nghề đối với công chức viên chức tại các cơ sở y tế', 'sotien' => '0');
            $a_BII[1] = array('tt' => '2', 'noidung' => 'Kinh phí thực hiện chính sách tinh giản biên chế năm 2023', 'sotien' => '0');
            $a_BII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2023 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');
            $a_BII[3] = array('tt' => '4', 'noidung' => 'Nhu cầu kinh phí trả thực hiện chế độ thù lao đối với người đã nghỉ hưu lanh đạo Hội đặc thù', 'sotien' => '0');
            $a_BII[4] = array('tt' => '5', 'noidung' => 'Nhu cầu kinh phí tăng thêm thực hiện chế độ trợ cấp lần đầu nhận công tác vùng ĐBKK', 'sotien' => '0');
            $a_BII[5] = array('tt' => '6', 'noidung' => 'Kinh phí tăng/giảm do thực hiện Nghị định số 33/2023/NĐ-CP', 'sotien' => '0');


            $a_BII[0]['sotien'] = $m_nguonkp->sum('kpthuhut');
            $a_BII[1]['sotien'] = $m_nguonkp->sum('tinhgiam');
            $a_BII[2]['sotien'] = $m_nguonkp->sum('nghihuusom');
            $a_BII[3]['sotien'] = $m_nguonkp->sum('kpuudai');
            $a_BII[4]['sotien'] = $m_nguonkp->sum('kinhphigiamxa_4a');
            $a_BII[5]['sotien'] = $m_nguonkp->sum('nhucau_4a');
            // $a_BII[5]['sotien'] = $m_nguonkp->sum('nhucau2c');

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[6]['sotien']),
                'BI' => array_sum(array_column($a_BI, 'sotien')),
                'BII' => array_sum(array_column($a_BII, 'sotien'))
            );
            // dd($a_A);

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            return view('reports.thongtu78.huyen.mau4a')
                ->with('model', $m_nguonkp)
                ->with('a_A', $a_A)
                ->with('a_BII', $a_BII)
                ->with('a_BI', $a_BI)
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
            $inputs = $request->all();
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $m_nguonkp = nguonkinhphi::where(function ($qr) use ($inputs) {
                $qr->where('macqcq', $inputs['macqcq'])->where('trangthai', 'DAGUI')->where('sohieu', $inputs['sohieu']);
            })->orwhere(function ($qr) use ($inputs) {
                $qr->where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu']);
            })->get();
            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

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
            $a_plnhucau = ['BIENCHE', 'CANBOCT', 'HDND', 'CAPUY']; //lọc dữ liệu cho giống 4a
            //Số liệu chi tiết
            foreach ($m_chitiet as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //$chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
                //lọc dữ liệu cho giống 4a
                if (!in_array($chitiet->nhomnhucau, $a_plnhucau)) {
                    $m_chitiet->forget($key);
                    continue;
                }
                $chitiet->tongnhucau = ($chitiet->ttbh_dv + $chitiet->ttl) * 6;
            }
            // dd($m_chitiet);
            //Số liệu đơn vị
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tinh số liệu 2b
                //BH=tongsonguoi1 * 0.1 * 4,5% (đơn vị: Triệu đồng) 
                $chitiet->nhucau2b = round(($chitiet->quy2_1 - $chitiet->quy1_1 + $chitiet->tongsonguoi1 * 450000) * 6) +
                    round(($chitiet->quy2_2 - $chitiet->quy1_2 + $chitiet->tongsonguoi2 * 450000) * 6) +
                    round(($chitiet->quy2_3 - $chitiet->quy1_3 + $chitiet->tongsonguoi3 * 450000) * 6);

                //số liệu 2c
                $clt7 = 0;
                $cl5t = 0;
                if ($chitiet->maphanloai == 'KVXP') {
                    if ($chitiet->phanloaixa == 'XL1') {
                        $clt7 = round(16 * 310000);
                        $cl5t = round(21 * 310000 * 5);
                    } else if ($chitiet->phanloaixa == 'XL2') {
                        $clt7 = round(13.7 * 310000);
                        $cl5t = round(18 * 310000 * 5);
                    } else if ($chitiet->phanloaixa == 'XL3') {
                        $clt7 = round(11.4 * 310000);
                        $cl5t = round(15 * 310000 * 5);
                    }
                    $solieu_plxa = $clt7 + $cl5t;
                    //Số xã biên giới
                    $solieu_xabiengioi_clt7 = round($chitiet->sothonbiengioi_2d * 5 * 310000);
                    $solieu_xabiengioi_cl5t = round($chitiet->sothonbiengioi_2d * 6 * 310000 * 5);
                    $solieu_xabiengioi = $solieu_xabiengioi_clt7 +  $solieu_xabiengioi_cl5t;
                    //số thôn có 350 hộ trở lên
                    $soho_350_clt7 = round($chitiet->sothon350hgd_2d * 5 * 310000);
                    $soho_350_cl5t = round($chitiet->sothon350hgd_2d * 6 * 310000 * 5);
                    $soho_350 = $soho_350_clt7 + $soho_350_cl5t;
                    $soho_500_clt7 = round($chitiet->sotodanpho500hgd_2d * 3 * 310000);
                    $soho_500_cl5t = round($chitiet->sotodanpho500hgd_2d * 6 * 310000 * 5);
                    $soho_500 = $soho_500_clt7 + $soho_500_cl5t;
                    //tổ dân phố trọng điểm an ninh
                    $sothon_trongdiem_clt7 = round($chitiet->sothontrongdiem_2d * 3 * 310000);
                    $sothon_trongdiem_cl5t = round($chitiet->sothontrongdiem_2d * 6 * 310000 * 5);
                    $sothon_trongdiem = $sothon_trongdiem_clt7 + $sothon_trongdiem_cl5t;
                    //tổ dân phố chuyển từ thôn
                    $sochuyentuthon_clt7 = round($chitiet->sochuyentuthon350hgd_2d * 3 * 310000);
                    $sochuyentuthon_cl5t = round($chitiet->sochuyentuthon350hgd_2d * 6 * 310000 * 5);
                    $sochuyentuthon = $sochuyentuthon_clt7 +  $sochuyentuthon_cl5t;
                    //Thôn còn lại
                    $sothonconlai_clt7 = round($chitiet->sothonconlai_2d * 3 * 310000);
                    $sothonconlai_cl5t = round($chitiet->sothonconlai_2d * 4.5 * 310000 * 5);
                    $sothonconlai =  $sothonconlai_clt7 +  $sothonconlai_cl5t;
                    //tổ dân phố còn lại
                    $sotoconlai_clt7 = round($chitiet->sotoconlai_2d * 3 * 310000);
                    $sotoconlai_cl5t = round($chitiet->sotoconlai_2d * 4.5 * 310000 * 5);
                    $sotoconlai = $sotoconlai_clt7 + $sotoconlai_cl5t;
                    $thontodanpho = $solieu_xabiengioi +  $soho_350 + $soho_500 + $sothon_trongdiem +  $sochuyentuthon + $sothonconlai +  $sotoconlai;
                    $chitiet->nhucau2c = $solieu_plxa + $thontodanpho;
                }
            }

            // dd($m_nguonkp);
            $data = array();
            $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            //
            $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'GD')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'GD')->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            $data[1]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[1]['solieu']['tongso'] = $data[1]['solieu']['tietkiem'] + $data[1]['solieu']['hocphi'] + $data[1]['solieu']['vienphi'] + $data[1]['solieu']['nguonthu'];
            //dd($data);
            //
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'DT')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'DT')->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            $data[2]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[2]['solieu']['tongso'] = $data[2]['solieu']['tietkiem'] + $data[2]['solieu']['hocphi'] + $data[2]['solieu']['vienphi'] + $data[2]['solieu']['nguonthu'];
            //Dòng 0
            $data[0]['solieu'] = [
                'nhucau' => $data[2]['solieu']['nhucau'] + $data[1]['solieu']['nhucau'],
                'tietkiem' => $data[2]['solieu']['tietkiem'] + $data[1]['solieu']['tietkiem'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $data[2]['solieu']['hocphi'] + $data[1]['solieu']['hocphi'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $data[2]['solieu']['vienphi'] + $data[1]['solieu']['vienphi'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $data[2]['solieu']['nguonthu'] + $data[1]['solieu']['nguonthu'], //Lấy tiết kiệm 2023 ở mẫu 4a                
                'tongso' => $data[2]['solieu']['tongso'] + $data[1]['solieu']['tongso'], //Lấy 50% tổng tiết kiệm ở mẫu 2đ

            ];
            //
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'YTE')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'YTE')->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            $data[3]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[3]['solieu']['tongso'] = $data[3]['solieu']['tietkiem'] + $data[3]['solieu']['hocphi'] + $data[3]['solieu']['vienphi'] + $data[3]['solieu']['nguonthu'];
            //
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP');
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOCT');

            $m_bl = $m_chitiet->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            // $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOCT');

            $data[4]['solieu'] = [
                // 'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau'),
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem') + $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a') + $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a') + $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a') + $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[4]['solieu']['tongso'] = $data[4]['solieu']['tietkiem'] + $data[4]['solieu']['hocphi'] + $data[4]['solieu']['vienphi']
                + $data[4]['solieu']['nguonthu'];

            //Quản lý nhà nước + Biên chế xã + Các cán bộ đã nghỉ hưu (2b)->29/8/2023: không cộng mẫu 2b vào nữa mà cộng mẫu 2c
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể',);
            $m_data = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP');
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            $m_bl = $m_chitiet->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            // $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);
            $m_bl2 = $m_chitiet->wherein('nhomnhucau', ['HDND', 'CAPUY']);
            $m_bl3 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['CANBOCT']);

            $data[5]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau') + $m_nguonkp->sum('nhucau2c') + $m_bl3->sum('tongnhucau'),
                // 'nhucau' => $m_bl2->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem') + $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a') + $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a') + $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a') + $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
            ];

            $data[5]['solieu']['tongso'] = $data[5]['solieu']['tietkiem'] + $data[5]['solieu']['hocphi'] + $data[5]['solieu']['vienphi'] + $data[5]['solieu']['nguonthu'];

            //
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã',);
            $data[6]['solieu'] = [
                'nhucau' => $m_bl3->sum('tongnhucau'),
                'tietkiem' => $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' =>  $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
            ];
            $data[6]['solieu']['tongso'] = $data[6]['solieu']['tietkiem'] + $data[6]['solieu']['hocphi'] + $data[6]['solieu']['vienphi'] + $data[6]['solieu']['nguonthu'];

            //dd($data);
            $inputs['donvitinh'] =  $inputs['donvitinh'] ?? 1;
            return view('reports.thongtu78.huyen.mau4b')
                //->with('model', $model)
                // ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }
}
