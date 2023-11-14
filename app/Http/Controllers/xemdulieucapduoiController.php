<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaidonvi;
use App\tonghop_huyen;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_huyen;
use App\tonghopluong_khoi;
use App\tonghopluong_tinh;
use Illuminate\Http\Request;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class xemdulieucapduoiController extends Controller
{
    public function soluongdv($thang, $nam)
    {
        $ngay = date("Y-m-t", strtotime($nam . '-' . $thang . '-01'));
        $madv = session('admin')->madv;
        $model_donvi = dmdonvi::select('madv', 'tendv')
            ->where('macqcq', $madv)
            ->where('madv', '<>', $madv)
            ->wherenotin('madv', function ($query) use ($ngay) {
                $query->select('madv')->from('dmdonvi')
                    // ->where('ngaydung', '<=', $ngay)
                    ->whereMonth('ngaydung', '<=', $ngay)
                    ->whereYear('ngaydung', '<=', $ngay)
                    ->where('trangthai', 'TD')
                    ->get();
            })->get();
        $kq = isset($model_donvi) ? count($model_donvi) : 0;
        return $kq;
    }
    public function layDV($thang, $nam, $a_donvicapduoi, $model_donvitamdung)
    {
        $model_donvi = $model_donvitamdung->where('ngaydung', '<=', $nam . '-' . $thang . '-01');
        return array_diff($a_donvicapduoi, array_column($model_donvi->toarray(), 'madv'));
    }
    public function donvi_luong(Request $request)
    {
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $ngay = date("Y-m-t", strtotime($nam . '-' . $thang . '-01'));
            $a_trangthai = array('ALL' => 'Tất cả dữ liệu', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $a_phanloai = getPhanLoaiDonVi();
            $a_phanloai['ALL'] = 'Tất cả các đơn vị';
            //array('ALL'=>'Tất cả các đơn vị','MAMNON'=>'Trường Mầm non','TIEUHOC'=>'Trường Tiểu học', 'THCS'=>'Trường Trung học cơ sở');
            //$list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();
            $model_donvi = dmdonvi::select('madv', 'tendv', 'macqcq', 'maphanloai', 'phanloaitaikhoan')->where('macqcq', $madv)->where('madv', '<>', $madv)
                ->wherenotin('madv', function ($query) use ($madv, $thang, $nam, $ngay) {
                    $query->select('madv')->from('dmdonvi')
                        // ->where('ngaydung', '<=', $ngay)
                        ->whereMonth('ngaydung', '<=', $ngay)
                        ->whereYear('ngaydung', '<=', $ngay)
                        ->where('trangthai', 'TD')
                        ->get();
                })
                ->get();

            $model_tonghop = tonghopluong_donvi::where('macqcq', $madv)->where('madv', '<>', $madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('trangthai', 'DAGUI')->get();

            // dd(array_column($model_tonghop->toarray(),'madv','nguoigui'));
            //
            // $model_tonghopkhoi = tonghopluong_khoi::where('macqcq', $madv)
            //     ->where('thang', $inputs['thang'])
            //     ->where('nam', $inputs['nam'])
            //     ->where('trangthai', 'DAGUI')->get();

            /*
            if(!isset($inputs['trangthai']) || $inputs['trangthai']=='ALL'){
                $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')->where('macqcq', $madv)->get();
            }else{
                $trangthai = $inputs['trangthai'];

                //có thời gian nên làm chỉ cần biết đơn vi cấp dưới gửi dữ liệu chưa
                //ko cần chi tiết đơn vị đã tạo nhưng chưa gửi => mặc định là chưa gửi hết
                switch($trangthai){
                    case 'DAGUI':{
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $trangthai, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai',$trangthai)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();
                        break;
                    }
                    default :{
                       //Đơn vị đã tổng hợp dữ liệu nhưng chưa gửi
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai','<>','DAGUI')
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                         bỏ kiểm tra đơn vị chưa tạo tổng hợp
                        //Đơn vi chưa tổng hợp dữ liệu
                        $model_donvi_chuatao = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->where('macqcq',$madv)
                            ->wherenotin('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        foreach($model_donvi_chuatao as $donvi){
                            $model_donvi->add($donvi);
                        }

                        break;
                    }
                }
            }
            */
            //kiểm tra tonghopkhôi và tonghophuyện đều dc
            $model = tonghopluong_khoi::where('madv', $madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('trangthai', 'DAGUI')->first();
            // dd($model_donvi);
            //dd(count($model));
            $tralai = $model != null ? false : true;
            foreach ($model_donvi as $dv) {
                $dv->tendvcq = getTenDV($dv->macqcq);
                $tonghop = $model_tonghop->where('madv', $dv->madv)->first();
                // $tonghopkhoi = $model_tonghopkhoi->where('madv', $dv->madv)->first();
                $dv->tralai = $tralai;
                $dv->mathdv = NULL;
                $dv->ngaygui = '';
                $dv->trangthai = 'CHOGUI';
                if ($tonghop != null) {
                    $dv->mathdv = $tonghop->mathdv;
                    $dv->trangthai = $tonghop->trangthai;
                    $dv->ngaygui = $tonghop->ngaygui;
                }
                // if ($tonghopkhoi != null) {
                //     $dv->mathdv = $tonghopkhoi->mathdv;
                //     $dv->trangthai = $tonghopkhoi->trangthai;
                //     $dv->ngaygui = $tonghopkhoi->ngaygui;
                //     $dv->thang = $tonghopkhoi->thang;
                //     $dv->nam = $tonghopkhoi->nam;
                // }
            }
            if ($inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthai']);
            }
            if ($inputs['phanloai'] != 'ALL') {
                $model_donvi = $model_donvi->where('maphanloai', $inputs['phanloai']);
            }
            //dd($model_donvi->toarray());
            return view('functions.viewdata.index')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('phanloai', $inputs['phanloai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('a_phanloai', $a_phanloai)
                ->with('furl', '/chuc_nang/tong_hop_luong/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    public function index_huyen(Request $request)
    {
        if (Session::has('admin')) {

            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $ngay = date("Y-m-t", strtotime($inputs['nam'] . '-' . $inputs['thang'] . '-01'));
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
            $model_phanloai = array_column($model_phanloai->toarray(), 'tenphanloai', 'maphanloai');
            foreach ($model_phanloai as $key => $key)
                $a_phanloai[$key] = $model_phanloai[$key];
            //$a_phanloai['GD'] = 'Khối Giáo Dục';
            $a_phanloai['ALL'] = '--Chọn tất cả--';
            /*//$a_phanloai = array('DONVI' => 'Dữ liệu tổng hợp của đơn vị', 'CAPDUOI' => 'Dữ liệu tổng hợp của các đơn vị cấp dưới');
            $model_donvi = dmdonvi::select('madv', 'tendv')
                    ->wherein('madv', function($query) use($madv){
                        $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                    })->get();*/
            if (session('admin')->phamvitonghop == 'KHOI') {
                /*
                $model_donvi = tonghopluong_donvi::join('dmdonvi','tonghopluong_donvi.madv','dmdonvi.madv')
                    ->select('dmdonvi.madv', 'dmdonvi.tendv','phanloaitaikhoan')
                    ->wherein('tonghopluong_donvi.madv', function($query) use($madv){
                        $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                    })->distinct()->get();
                */
                // $model_donvi = dmdonvi::join('dmphanloaidonvi', 'dmphanloaidonvi.maphanloai', 'dmdonvi.maphanloai')
                //     ->select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'dmdonvi.maphanloai', 'tenphanloai')
                //     ->where('macqcq', $madv)->where('madv', '<>', $madv)
                //     ->wherenotin('madv', function ($query) use ($madv, $ngay) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->where('ngaydung', '<=', $ngay)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })
                //     ->distinct()->get();
                $model_donvi = dmdonvi::join('dmphanloaidonvi', 'dmphanloaidonvi.maphanloai', 'dmdonvi.maphanloai')
                    ->select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'dmdonvi.maphanloai', 'tenphanloai')
                    ->wherein('madv', getDonviHuyen($inputs['nam'], $madv)['m_donvi'])->get();

                $model_nguon = tonghopluong_donvi::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->get();
                $model_nguonkhoi = tonghopluong_khoi::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->get();
            }
            if (session('admin')->phamvitonghop == 'HUYEN') {
                /*
                $model_donvi = tonghopluong_huyen::join('dmdonvi ','tonghopluong_huyen.madv','dmdonvi.madv')
                    ->select('dmdonvi.madv', 'dmdonvi.tendv','phanloaitaikhoan')
                    ->wherein('tonghopluong_huyen.madv', function($query) use($madv){
                        $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                    })->distinct()->get();
                */
                // $model_donvi = dmdonvi::join('dmphanloaidonvi', 'dmphanloaidonvi.maphanloai', 'dmdonvi.maphanloai')
                //     ->select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'dmdonvi.maphanloai', 'tenphanloai', 'linhvuchoatdong')
                //     ->where('macqcq', $madv)->where('madv', '<>', $madv)
                //     ->wherenotin('madv', function ($query) use ($madv, $ngay) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->where('ngaydung', '<=', $ngay)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })
                //     ->distinct()->get();
                $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv',  getDonviHuyen($inputs['nam'], $madv)['a_donvicapduoi'])->get();
                $a_madv = $this->layDV($inputs['thang'], $inputs['nam'], getDonviHuyen($inputs['nam'], $madv)['a_donvicapduoi'], $model_donvitamdung);
                // dd($a_madv);
                $model_donvi = dmdonvi::join('dmphanloaidonvi', 'dmphanloaidonvi.maphanloai', 'dmdonvi.maphanloai')
                    ->select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'dmdonvi.maphanloai', 'tenphanloai', 'linhvuchoatdong')
                    ->wherein('madv', $a_madv)
                    ->get();
                /* 13.04.23 bỏ phần lấy ở huyện vì giờ chuyển trực tiếp lên huyện ko có khối
                $model_nguon = tonghopluong_huyen::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->wherein('trangthai', ['DAGUI', 'TRALAI'])
                    ->get();
                // dd($model_nguon);
                */
                $model_nguon = tonghopluong_donvi::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->get();
            }
            // dd($model_nguon);
            //dd($model_nguon->toarray());
            $model_nguon_tinh = tonghopluong_tinh::where('madv', $madv)->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])->first();
            //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
            if (isset($model_nguon_tinh) && $model_nguon_tinh->trangthai == 'DAGUI') {
                $tralai = false;
            } else {
                $tralai = true;
            }
            //dd(session('admin'));

            foreach ($model_donvi as $dv) {
                $dv->tralai = $tralai;
                $nguon = $model_nguon->where('madv', $dv->madv)->first();
                if (session('admin')->phamvitonghop == 'KHOI')
                    $nguonkhoi = $model_nguonkhoi->where('madv', $dv->madv)->first();
                // if (isset($nguon) && $nguon->trangthai == 'DAGUI' && session('admin')->phamvitonghop == 'HUYEN') {

                if (isset($nguon) && in_array($nguon->trangthai, ['DAGUI', 'TRALAI']) && session('admin')->phamvitonghop == 'HUYEN') {
                    $dv->mathdv = $nguon->mathdv;
                    $dv->mathh = $nguon->mathdv;
                    $dv->trangthai = $nguon->trangthai;
                    $dv->thang = $nguon->thang;
                    $dv->nam = $nguon->nam;
                    //if($this->linhvuc($dv->mathdv) == "")
                    //  $dv->linhvuchoatdong = $this->linhvuc($nguon->mathdv);
                } elseif (session('admin')->phamvitonghop == 'KHOI') {
                    if ((isset($nguon) && $nguon->trangthai == 'DAGUI') || (isset($nguonkhoi) && $nguonkhoi->trangthai == 'DAGUI')) {

                        $dv->mathdv = $nguon->mathdv;
                        $dv->trangthai = 'DAGUI';
                        $dv->thang = $nguonkhoi->thang;
                        $dv->nam = $nguonkhoi->nam;
                    }
                } else {
                    $dv->trangthai = 'CHOGUI';
                    $dv->mathdv = null;
                }
            }
            // dd($model_donvi->where('trangthai','TRALAI'));
            //dd($model_donvi->toarray());
            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthai']);
            }
            if (!isset($inputs['phanloai']) || $inputs['phanloai'] != 'ALL') {
                $model_donvi = $model_donvi->where('maphanloai', $inputs['phanloai']);
            }

            return view('functions.viewdata.index_huyen')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('phanloai', $inputs['phanloai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('a_phanloai', $a_phanloai)
                ->with('furl', '/chuc_nang/tong_hop_luong/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
        } else
            return view('errors.notlogin');
    }
    function linhvuc($mathdv)
    {
        $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi_bangluong.mathdv', 'tonghopluong_donvi.mathdv')
            ->join('dmkhoipb', 'tonghopluong_donvi_bangluong.linhvuchoatdong', 'dmkhoipb.makhoipb')
            ->select('tenkhoipb')
            ->where('tonghopluong_donvi.mathh', $mathdv)
            ->distinct()->get();
        $kq = "";
        foreach ($model as $ct) {
            if ($kq == "")
                $kq = $ct['tenkhoipb'];
            else
                $kq = $kq . "," . $ct['tenkhoipb'];
        }
        return $kq;
    }
    public function index_tinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = $inputs['madiaban'];
            $model_dvbc = dmdonvibaocao::where('madvbc', $madvbc)->first();
            $madvqlkv = $model_dvbc->madvcq;
            $ngay = date("Y-m-t", strtotime($inputs['nam'] . '-' . $inputs['thang'] . '-01'));
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function ($query) use ($madvqlkv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madvqlkv)->where('madv', '<>', $madvqlkv)->get();
                })->wherenotin('madv', function ($query) use ($ngay) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('ngaydung', '<=', $ngay)
                        ->where('trangthai', 'TD')
                        ->get();
                })->get();
            //dd($model_donvi);
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHUAGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $a_phanloai = array('DONVI' => 'Dữ liệu tổng hợp của đơn vị', 'CAPDUOI' => 'Dữ liệu tổng hợp của các đơn vị cấp dưới');
            //$list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)->get();
            $model_dvbc = dmdonvibaocao::where('level', 'H')->get();
            $model_dulieu = tonghopluong_donvi::where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('trangthai', 'DAGUI')
                ->where('macqcq', $madvqlkv)
                ->get();

            foreach ($model_donvi as $dv) {
                $dv->trangthai = 'CHUAGUI';
                $dulieu = $model_dulieu->where('madv', $dv->madv)->first();

                if (isset($dulieu)) {
                    $dv->phanloai = $dulieu->phanloai;
                    $dv->trangthai = $dulieu->trangthai;
                    $dv->mathdv = $dulieu->mathdv;
                    $dv->thang = $dulieu->thang;
                    $dv->nam = $dulieu->nam;
                } else {
                    $dv->mathdv = NULL;
                }
                $dv->tenphanloai = isset($a_phanloai[$dv->phanloai]) ? $a_phanloai[$dv->phanloai] : '';
            }

            //$model_donvi = $model_donvi->sortby('madv');
            $soluong = $model_dulieu->count('madv') . '/' . $model_donvi->count('madv');
            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthai']);
            }
            // dd($model_donvi);
            return view('functions.viewdata.index_tinh')
                ->with('model', $model_donvi)
                //->with('model_th', $model_tonghop)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('madvbc', $madvbc)
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_dvbc', array_column($model_dvbc->toArray(), 'tendvbc', 'madvbc'))
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong', $soluong)
                ->with('furl', '/chuc_nang/tong_hop_luong/')
                ->with('furl_ct', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function tonghop_huyen(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //dd($inputs);
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madvbc = $inputs['madiaban'];

            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv', function ($query) use ($nam, $thang, $madvbc) {
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->where('madvbc', $madvbc)->distinct();
            })->get();

            //
            //Tính toán dữ liệu
            $a_col = getColTongHop();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');

            //Lấy dữ liệu để lập
            $model_data = $model_tonghop_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac', 'manguonkp'])
                    ->all();
            });

            $model_data = a_unique($model_data);
            //dd($model_tonghop_ct->sum('pcth'));
            for ($i = 0; $i < count($model_data); $i++) {
                $luongct = $model_tonghop_ct->where('manguonkp', $model_data[$i]['manguonkp'])->where('macongtac', $model_data[$i]['macongtac']);
                $model_data[$i]['tennguonkp'] = isset($model_nguonkp[$model_data[$i]['manguonkp']]) ? $model_nguonkp[$model_data[$i]['manguonkp']] : '';
                $model_data[$i]['tencongtac'] = isset($model_phanloaict[$model_data[$i]['macongtac']]) ? $model_phanloaict[$model_data[$i]['macongtac']] : '';

                $tonghs = 0;
                foreach ($a_col as $col) {
                    $model_data[$i][$col] = $luongct->sum($col);
                    if ($col != 'heso' && $col != 'hesopc') {
                        $tonghs += chkDbl($model_data[$i][$col]);
                    }
                }


                $model_data[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');
                $model_data[$i]['tongbh'] = $model_data[$i]['stbhxh_dv'] + $model_data[$i]['stbhyt_dv'] + $model_data[$i]['stkpcd_dv'] + $model_data[$i]['stbhtn_dv'];
                $model_data[$i]['tonghs'] = $tonghs;
            }

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', function ($qr) use ($madvbc) {
                $qr->select('madvcq')->from('dmdonvibaocao')->where('madvbc', $madvbc)->get();
            })->first();

            $thongtin = array(
                'nguoilap' => $m_dv->nguoilapbieu,
                'thang' => $thang,
                'nam' => $nam
            );


            return view('reports.tonghopluong.khoi.solieutonghop')
                ->with('thongtin', $thongtin)
                ->with('model', $model_data)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết tổng hợp lương trên địa bàn');
        } else
            return view('errors.notlogin');
    }
    function danhsach(Request $request)
    {

        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $ngay = date("Y-m-t", strtotime($inputs['namds'] . '-' . $inputs['thangds'] . '-01'));
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            $a_trangthai = array('CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            if (session('admin')->phamvitonghop == 'KHOI') {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'maphanloai')
                    ->where('macqcq', $madv)->where('madv', '<>', $madv)
                    ->wherenotin('madv', function ($query) use ($madv, $ngay) {
                        $query->select('madv')->from('dmdonvi')
                            ->where('ngaydung', '<=', $ngay)
                            ->where('trangthai', 'TD')
                            ->get();
                    })
                    ->distinct()->get();
                $model_nguon = tonghopluong_donvi::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thangds'])
                    ->where('nam', $inputs['namds'])
                    ->where('trangthai', 'DAGUI')
                    ->get();
                $model_nguonkhoi = tonghopluong_khoi::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thangds'])
                    ->where('nam', $inputs['namds'])
                    ->where('trangthai', 'DAGUI')
                    ->get();
            }
            if (session('admin')->phamvitonghop == 'HUYEN') {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'maphanloai')
                    ->where('macqcq', $madv)->where('madv', '<>', $madv)
                    ->wherenotin('madv', function ($query) use ($madv, $ngay) {
                        $query->select('madv')->from('dmdonvi')
                            ->where('ngaydung', '<=', $ngay)
                            ->where('trangthai', 'TD')
                            ->get();
                    })
                    ->distinct()->get();
                $model_nguon = tonghopluong_huyen::wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->where('thang', $inputs['thangds'])
                    ->where('nam', $inputs['namds'])
                    ->where('trangthai', 'DAGUI')
                    ->get();
            }
            //dd($model_nguon->toarray());
            $model_nguon_tinh = tonghopluong_tinh::where('madv', $madv)->where('thang', $inputs['thangds'])
                ->where('nam', $inputs['namds'])->first();
            //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
            if ($model_nguon_tinh != null && $model_nguon_tinh->trangthai == 'DAGUI') {
                $tralai = false;
            } else {
                $tralai = true;
            }
            foreach ($model_donvi as $dv) {
                $dv->tralai = $tralai;
                $nguon = $model_nguon->where('madv', $dv->madv)->first();
                if (session('admin')->phamvitonghop == 'KHOI')
                    $nguonkhoi = $model_nguonkhoi->where('madv', $dv->madv)->first();
                if ($nguon != null && $nguon->trangthai == 'DAGUI' && session('admin')->phamvitonghop == 'HUYEN') {
                    $dv->mathdv = $nguon->mathdv;
                    $dv->mathh = $nguon->mathdv;
                    $dv->trangthai = 'DAGUI';
                    $dv->thang = $nguon->thang;
                    $dv->nam = $nguon->nam;
                } elseif (session('admin')->phamvitonghop == 'KHOI') {
                    if (($nguon != null && $nguon->trangthai == 'DAGUI') || ($nguonkhoi != null && $nguonkhoi->trangthai == 'DAGUI')) {
                        $dv->mathdv = $nguon->mathdv;
                        $dv->trangthai = 'DAGUI';
                        $dv->thang = $nguonkhoi->thang;
                        $dv->nam = $nguonkhoi->nam;
                    }
                } else {
                    $dv->trangthai = 'CHOGUI';
                    $dv->mathdv = null;
                }
            }
            // dd($model_donvi->toarray());
            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthai']);
            }
            //  dd($model_donvi->toarray());
            $m_dv = dmdonvi::where('madv', $madv)->first();
            if (isset($inputs['excel'])) {
                Excel::create('THluong', function ($excel) use ($model_donvi, $a_trangthai, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($model_donvi, $a_trangthai, $m_dv, $inputs) {
                        $sheet->loadView('reports.tonghopluong.huyen.danhsach')
                            ->with('model', $model_donvi)
                            ->with('thang', $inputs['thangds'])
                            ->with('nam', $inputs['namds'])
                            ->with('a_trangthai', $a_trangthai)
                            ->with('m_dv', $m_dv)
                            ->with('furl', '/chuc_nang/tong_hop_luong/')
                            ->with('pageTitle', 'THluong');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.tonghopluong.huyen.danhsach')
                    ->with('model', $model_donvi)
                    ->with('thang', $inputs['thangds'])
                    ->with('nam', $inputs['namds'])
                    ->with('a_trangthai', $a_trangthai)
                    ->with('m_dv', $m_dv)
                    ->with('furl', '/chuc_nang/tong_hop_luong/')
                    ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
            }
        } else
            return view('errors.notlogin');
    }
}
