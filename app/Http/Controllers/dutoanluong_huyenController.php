<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphucap_donvi;
use App\dsdonviquanly;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use App\dutoanluong_nangluong;
use App\dutoanluong_tinh;
use App\hosocanbo;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dutoanluong_huyenController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong_huyen::where('madv', session('admin')->madv)->get();
// dd($model);
            $madv = session('admin')->madv;
            // $model_nguon = dutoanluong_huyen::where('macqcq', $madv)->where('trangthai', 'DAGUI')->get();
            // $model_nguon_tinh = dutoanluong_tinh::where('madv', $madv)->get();
            //$model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            //$model = dutoanluong::select('namns')->where('madvbc', session('admin')->madvbc)->distinct()->get();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên

            // $model_donvi = dmdonvi::select('madv', 'tendv')
            //     ->wherein('madv', function ($query) use ($madv) {
            //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            //     })->get();
            // $soluong = $model_donvi->count();
            //đơn vị có macqcq = madv (bang dmdonvi)

            foreach ($model as $dv) {
                $nam = $dv->namns;
                //lấy danh sách đơn vị: đơn vị có macqcq = madv (bang dmdonvi) + đơn vị nam=nam && macqcq=madv
                // $a_donvicapduoi = [];
                // //đơn vị nam=nam && macqcq=madv
                // $model_dsql = dsdonviquanly::where('nam', $nam)->where('macqcq', $madv)->get();
                // $a_donvicapduoi = array_unique(array_column($model_dsql->toarray(), 'madv'));
                // //dd($a_donvicapduoi);

                // //đơn vị có macqcq = madv (bang dmdonvi)
                // $model_dmdv = dmdonvi::where('macqcq', $madv)
                //     ->wherenotin('madv', function ($qr) use ($nam) {
                //         $qr->select('madv')->from('dsdonviquanly')->where('nam', $nam)->distinct()->get();
                //     }) //lọc các đơn vị đã khai báo trong dsdonviquanly
                //     ->where('madv', '!=', $madv) //bỏ đơn vị tổng hợp
                //     ->get();
                // $a_donvicapduoi = array_unique(array_merge(array_column($model_dmdv->toarray(), 'madv'), $a_donvicapduoi));
                // $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv', $a_donvicapduoi)->get();
                // // dd($model_donvitamdung);
                // // $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')
                // //     ->where('macqcq', $madv)->where('madv', '<>', $madv)
                // //     ->wherenotin('madv', function ($query) use ($nam) {
                // //         $query->select('madv')->from('dmdonvi')
                // //             ->whereyear('ngaydung', '<=', $nam)
                // //             ->where('trangthai', 'TD')
                // //             ->get();
                // //     })->get();
                // $dv->soluong = $model_donvi->count();
                $dv->soluong = count(getDonviHuyen($nam,$madv)['m_donvi']);
                $dvgui= dutoanluong::where('macqcq', $madv)->where('namns', $nam)->where('trangthai', 'DAGUI')->wherenotin('madv', getDonviHuyen($nam,$madv)['model_donvitamdung'])->get();
                // $dv->dagui = dutoanluong::where('macqcq', $madv)->where('namns', $nam)->where('trangthai', 'DAGUI')->wherenotin('madv', getDonviHuyen($nam,$madv)['model_donvitamdung'])->count();
                $dv->dagui = count(array_unique(array_column($dvgui->toarray(),'madv')));
            }

            $model_tenct = dmphanloaict::wherein('mact', getPLCTDuToan())->get();
            $model_nhomct = dmphanloaicongtac::wherein('macongtac', array_unique(array_column($model_tenct->toarray(), 'macongtac')))->get();
            $a_trangthai_in = array('ALL' => '--Tất cả đơn vị--', 'CHOGUI' => 'Đơn vị chưa gửi dữ liệu', 'DAGUI' => 'Đơn vị đã gửi dữ liệu');
            $a_phanloai = array_column(dmphanloaidonvi::all()->toarray(), 'tenphanloai', 'maphanloai');

            $m_dvbc = dmdonvibaocao::where('level', 'T')->get();
            $a_donviql = array_column(dmdonvi::wherein('madvbc', array_column($m_dvbc->toarray(), 'madvbc'))->get()->toarray(), 'tendv', 'madv');

            return view('functions.dutoanluong.huyen.index')
                ->with('model', $model->sortby('namns'))
                ->with('model_tenct', $model_tenct)
                ->with('model_nhomct', $model_nhomct)
                ->with('a_trangthai', $a_trangthai)
                ->with('a_trangthai_in', $a_trangthai_in)
                ->with('a_phanloai', setArrayAll($a_phanloai))
                ->with('a_donviql', $a_donviql)
                // ->with('soluong', $soluong)
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/du_toan/huyen')
                ->with('furl_th', '/chuc_nang/du_toan_luong/huyen/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    public function tao_du_toan(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong_huyen::where('madv', $inputs['madv'])->where('namns', $inputs['namns'])->first();
            if ($model != null) {
                $inputs['masodv'] = $model->masodv;
            } else {
                $inputs['trangthai'] = 'CHUAGUI';
                $inputs['masodv'] = $inputs['madv'] . '_' . getdate()[0];
                $inputs['ngaylap'] = date('Y-m-d');
                dutoanluong_huyen::create($inputs);
            }
            dutoanluong::where('macqcq', $inputs['madv'])->where('namns', $inputs['namns'])->update(['masoh' => $inputs['masodv']]);

            return redirect('/chuc_nang/du_toan_luong/huyen/index');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->masoh = null;
            $model->save();
            return redirect('/chuc_nang/xem_du_lieu/du_toan/huyen?namns=' . $model->namns . '&trangthai=ALL&phanloai=ALL');
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            // dd($inputs);
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }

            $model = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();

            $chk_tinh = dutoanluong_tinh::where('madv', $model->macqcq)->where('namns', $model->namns)->first();
            if ($chk_tinh != null)
                dutoanluong::where('masoh', $inputs['masodv'])->update(['masot' => $chk_tinh->masodv]);

            $model->nguoigui = session('admin')->name;
            $model->macqcq = $inputs['macqcq'];
            $model->ngaygui = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            $model->save();

            $masot = $model->madv . '_' . getdate()[0];
            dutoanluong::where('macqcq', $model->madv)->where('namns', $inputs['namns'])->update(['masot' => $masot]);
            return redirect('/chuc_nang/du_toan_luong/huyen/index');
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

        $model = dutoanluong_huyen::select('lydo')->where('masodv', $inputs['masodv'])->first();

        die($model);
    }
    //In dữ liệu 1 đơn vị trong huyen
    function printf(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model_huyen = dutoanluong_huyen::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong::where('masoh', $inputs['maso'])->first();
            $requests['madv'] = $model_huyen->madv;
            $requests['namns'] = $model_huyen->namns;
            $phanloai = dmdonvi::where('madv', $model_huyen->madv)->first()->phanloaitaikhoan;
            $bl = new dutoanluong_khoiController();

            if ($phanloai == 'SD') {
                $requests['maso'] = $model->masodv;
                return $bl->printf($requests);
            } else {
                return $bl->tonghop($requests);
            }
        } else
            return view('errors.notlogin');
    }

    //Tổng hợp dữ liệu trong huyen
    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //lấy dự toán lương chi tiết
            //lấy dữ liệu chi tiết: thêm phân loại SD $ TH -> SD:madv; TH:macqcq (làm theo huong nay - chua lam)
            if (!isset($inputs['madv'])) {
                $inputs['madv'] = session('admin')->madv;
            }

            if (!isset($inputs['madvbc'])) {
                $inputs['madvbc'] = session('admin')->madvbc;
            }
            //chú ý trùng dữ liẹuu
            $model = dutoanluong_chitiet::wherein('masodv', function ($query) use ($inputs) {
                $query->select('masodv')->from('dutoanluong')->where('madvbc', $inputs['madvbc'])
                    ->whereNull('masok')->where('namns', $inputs['namns'])->where('trangthai', 'DAGUI')->get();
            })->get();

            $model_th = dutoanluong_chitiet::wherein('masodv', function ($query) use ($inputs) {
                $query->select('masodv')->from('dutoanluong')->wherein('masok', function ($q) use ($inputs) {
                    $q->select('masodv')->from('dutoanluong_khoi')->where('madvbc', $inputs['madvbc'])->where('namns', $inputs['namns'])->get();
                })->where('trangthai', 'DAGUI')->get();
            })->get();
            /*
            foreach ($model_th as $donvi) {
                //$model->add($donvi);
            }
            */
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function ($query) use ($inputs) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $inputs['madv'])->where('madv', '<>', $inputs['madv'])->get();
                })->get();

            $model_dutoan = dutoanluong::wherein('madv', function ($query) use ($inputs) {
                $query->select('madv')->from('dmdonvi')->where('madvbc', $inputs['madvbc'])->get();
            })->get();

            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_nhomct = getNhomCongTac(false);

            foreach ($model as $ct) {
                $dutoan = $model_dutoan->where('masodv', $ct->masodv)->first();

                $ct->madv = $dutoan->madv ?? null;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->tencongtac = isset($a_nhomct[$ct->macongtac]) ? $a_nhomct[$ct->macongtac] : '';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;
            }
            //dd($model->toarray());
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            $thongtin = array('nam' => $inputs['namns']);

            return view('reports.viewdata.dutoan.khoi')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_donvi', $model_donvi)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Tổng hợp dự toán lương.');
        } else
            return view('errors.notlogin');
    }
    function chitietbl(Request $requests)
    {
        if (Session::has('admin')) {
            //dd($masodv);
            $inputs = $requests->all();
            $masodv = $inputs['mabl_pl'];
            //$mact = $inputs['mact'];
            $model = dutoanluong_bangluong::wherein('masodv', function ($query) use ($masodv) {
                $query->select('masodv')->from('dutoanluong')->where('masoh', $masodv)->get();
            })->orderby('thang')->get();
            if (isset($inputs['mact']) && $inputs['mact'] != '') {
                $model = $model->where('mact', $inputs['mact']);
            }
            if (isset($inputs['thang']) && $inputs['thang'] != '') {
                $model = $model->where('thang', $inputs['thang']);
            }
            $model_thongtin = dutoanluong::where('masoh', $masodv)->first();
            //$model = dutoanluong_bangluong::wherein('masodv', array_column($model_thongtin->toarray(),'masodv'))->get();
            //$model_thongtin = dutoanluong::where('masoh', $masodv)->first();
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


            return view('reports.viewdata.dutoan.bangluong')
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
    function chitietblCR(Request $requests)
    {
        if (Session::has('admin')) {
            //dd($masodv);
            $inputs = $requests->all();
            $masodv = $inputs['mabl_th'];
            /*
            $model = dutoanluong_bangluong::wherein('masodv',function($query) use($masodv){
                $query->select('masodv')->from('dutoanluong')->where('masoh',$masodv)->get();
            })->orderby('thang')->get();
            */
            $model = dutoanluong_bangluong::join('dutoanluong', 'dutoanluong.masodv', 'dutoanluong_bangluong.masodv')
                ->Select(
                    'msngbac',
                    'mact',
                    'macanbo',
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesobl) as hesobl'),
                    DB::raw('sum(hesott) as hesott'),
                    DB::raw('sum(hesopc) as hesopc'),
                    DB::raw('sum(vuotkhung) as vuotkhung'),
                    DB::raw('sum(pcct) as pcct'),
                    DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),
                    DB::raw('sum(pccv) as pccv'),
                    DB::raw('sum(pckv) as pckv'),
                    DB::raw('sum(pcth) as pcth'),
                    DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),
                    DB::raw('sum(pcld) as pcld'),
                    DB::raw('sum(pcdbqh) as pcdbqh'),
                    DB::raw('sum(pcudn) as pcudn'),
                    DB::raw('sum(pctn) as pctn'),
                    DB::raw('sum(pctnn) as pctnn'),
                    DB::raw('sum(pcdbn) as pcdbn'),
                    DB::raw('sum(pcvk) as pcvk'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),
                    DB::raw('sum(pclt) as pclt'),
                    DB::raw('sum(pcd) as pcd'),
                    DB::raw('sum(pctr) as pctr'),
                    DB::raw('sum(pctdt) as pctdt'),
                    DB::raw('sum(pctnvk) as pctnvk'),
                    DB::raw('sum(pcbdhdcu) as pcbdhdcu'),
                    DB::raw('sum(pcthni) as pcthni'),
                    DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),
                    DB::raw('sum(pcxaxe) as pcxaxe'),
                    DB::raw('sum(pcdith) as pcdith'),
                    DB::raw('sum(pcphth) as pcphth'),
                    DB::raw('sum(luongtn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                //->where('dutoanluong.masoh',$masodv)
                ->where('dutoanluong.namns', $inputs['namns_th'])
                ->where('dutoanluong.madv', $inputs['madv_th'])
                ->where('trangthai', 'DAGUI')
                ->groupby('msngbac', 'mact', 'macanbo')
                ->get();
            if (isset($inputs['mact']) && $inputs['mact'] != '') {
                $model = $model->where('mact', $inputs['mact']);
            }
            $model_canbo = hosocanbo::wherein('madv', function ($query) use ($masodv) {
                $query->select('madv')->from('dutoanluong')->where('masoh', $masodv)->get();
            })->get();
            $model_thongtin = dutoanluong::where('masoh', $masodv)->first();
            //$model = dutoanluong_bangluong::wherein('masodv', array_column($model_thongtin->toarray(),'masodv'))->get();
            //$model_thongtin = dutoanluong::where('masoh', $masodv)->first();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();
            foreach ($model as $ct) {
                $m_tencb = $model_canbo->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = "";
                if (isset($m_tencb))
                    $ct->tencanbo = $m_tencb->tencanbo;
            }
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
                    ->only(['mact'])
                    ->all();
            });
            $model_congtac = a_unique($model_congtac);


            return view('reports.viewdata.dutoan.bangluongCR')
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
    function tonghopCR(Request $requests)
    {
        if (Session::has('admin')) {
            ini_set('memory_limit', '-1');
            $inputs = $requests->all();
            $namns = $inputs['namns'];
            $madv = session('admin')->madv;
            $model = dutoanluong_bangluong::join('dutoanluong', 'dutoanluong.masodv', 'dutoanluong_bangluong.masodv')
                ->select('dutoanluong_bangluong.*', 'dutoanluong.madv')
                ->where('dutoanluong.macqcq', $madv)
                ->where('dutoanluong.namns', $namns)
                ->where('dutoanluong.trangthai', 'DAGUI')
                ->orderby('dutoanluong_bangluong.thang')->get();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            //$model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $model_dutoan = dutoanluong::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->get();
            })->get();
            $model_soluong = dutoanluong_chitiet::join('dutoanluong', 'dutoanluong.masodv', 'dutoanluong_chitiet.masodv')
                ->select('canbo_congtac', 'canbo_dutoan', 'madv', 'mact')
                ->where('dutoanluong.macqcq', $madv)
                ->where('dutoanluong.namns', $namns)
                ->where('dutoanluong.trangthai', 'DAGUI')
                ->get();
            //dd($model_soluong->toarray());
            $m_phanloai = dmphanloaidonvi::all();
            $m_dv = dmdonvi::where('macqcq', $madv)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model as $ct) {
                $ct->phanloai = $m_dv->where('madv', $ct->madv)->first()->maphanloai;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($a_ct[$ct->mact]) ? $a_ct[$ct->mact] : '';
                }
            }
            //dd($model->toarray());
            $m_pc = dmphucap_donvi::where('madv', $madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }
            $m_donvi = dmdonvi::select('madv')->where('macqcq', $madv)->get();
            //dd($model_phanloaict);
            //dd($model->where('mact','1506672780')->groupby('mact')->toarray());
            $thongtin = dmdonvi::where('madv', $madv)->first();

            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'madv'])
                    ->all();
            });
            //dd($a_ct);
            $model_congtac = a_unique($model_congtac);


            return view('reports.viewdata.dutoan.tonghopCR')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('m_donvi', $m_donvi)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_ct', $a_ct)
                ->with('model_congtac', $model_congtac)
                ->with('model_soluong', $model_soluong)
                ->with('m_phanloai', $m_phanloai)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }
    function tonghopct(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //$model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong_chitiet::join('dutoanluong', 'dutoanluong.masodv', 'dutoanluong_chitiet.masodv')
                ->select('dutoanluong_chitiet.*')
                ->where('dutoanluong.madv', $inputs['madv'])
                ->where('dutoanluong.namns', $inputs['namns'])->get();

            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

            $a_nhomct = getNhomCongTac(false);
            foreach ($model as $ct) {
                //$ct->tencongtac = isset($a_nhomct[$ct->macongtac])? $a_nhomct[$ct->macongtac]:'';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;

                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
            }
            //dd($model->toarray());
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            //$thongtin = array('nam' => $model_thongtin->namns);


            return view('reports.viewdata.dutoan.tonghopct')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết dự toán lương tại đơn vị.');
        } else
            return view('errors.notlogin');
    }
    function nangluongth(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $masodv = $inputs['maso'];
            $model = dutoanluong_nangluong::wherein('masodv', function ($query) use ($masodv) {
                $query->select('masodv')->from('dutoanluong')->where('masoh', $masodv)->get();
            })->orderby('stt')->get();
            $model_thongtin = dutoanluong::where('masoh', $inputs['maso'])->first();
            $a_pl = getPhanLoaiNangLuong();
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
            $model_thang = a_unique($model_thang);

            $model_phanloai = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['maphanloai'])
                    ->all();
            });
            $model_phanloai = a_unique($model_phanloai);

            return view('reports.dutoanluong.donvi.nangluong')
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
}
