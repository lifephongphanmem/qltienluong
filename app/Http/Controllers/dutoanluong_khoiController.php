<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dutoanluong_khoiController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            $model_nguon = dutoanluong::where('trangthai', 'DAGUI')->where('macqcq', $madv)->get();

            $model_nguon_khoi = dutoanluong_khoi::where('madv', $madv)->get();
            $model = dutoanluong::select('namns')->where('madvbc', session('admin')->madvbc)->distinct()->get();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            // $model_donvi = dmdonvi::select('madv', 'tendv')
            //     ->wherein('madv', function ($query) use ($madv) {
            //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            //     })->get();
            // $model_donvi = dmdonvi::select('madv', 'tendv', 'macqcq', 'maphanloai', 'phanloaitaikhoan')
            // ->wherein('madv', function ($query) use ($madv) {
            //     $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            // })
            // ->wherenotin('madv', function ($query) use ($madv, $nam) {
            //     $query->select('madv')->from('dmdonvi')
            //         ->whereyear('ngaydung', '<=', $nam)
            //         ->where('trangthai', 'TD')
            //         ->get();
            // })->get();
            // $soluong = $model_donvi->count();
            // dd($model);
            foreach ($model as $dv) {
                // $nguon_khoi = $model_nguon_khoi->where('namns', $dv->namns)->first();
                $nam=$dv->namns;
                $nguon_khoi = $model_nguon->where('namns', $dv->namns)->first();
                $sl = $model_nguon->where('namns', $dv->namns)->count();
                // $model_donvi = dmdonvi::select('madv', 'tendv', 'macqcq', 'maphanloai', 'phanloaitaikhoan')
                //     ->wherein('madv', function ($query) use ($madv) {
                //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                //     })
                //     ->wherenotin('madv', function ($query) use ($madv, $nam) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->whereyear('ngaydung', '<=', $nam)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })->get();
                // $soluong = $model_donvi->count();
                $soluong = count(getDonviHuyen($nam,$madv)['m_donvi']);
                if ($nguon_khoi != null) {
                    //Đã tổng hợp dữ liệu
                    // $dv->sldv = $soluong . '/' . $soluong;
                    $dv->sldv = $sl . '/' . $soluong;
                    $dv->masodv = $nguon_khoi->masodv;
                    $dv->trangthai = $nguon_khoi->trangthai;
                    //$dv->trangthai = 'DAGUI';
                } else {
                    //Chưa tổng hợp dữ liệu
                    $sl = $model_nguon->where('namns', $dv->namns)->count();
                    $dv->sldv = $sl . '/' . $soluong;
                    $dv->masodv = null;
                    if ($sl == 0) {
                        $dv->trangthai = 'CHUADL';
                    } elseif ($sl < $soluong) {
                        $dv->trangthai = 'CHUADAYDU';
                    } elseif ($sl == $soluong) {
                        $dv->trangthai = 'CHUAGUI';
                    } else {
                        $dv->trangthai = 'CHUATAO';
                    }
                }
            }
            // dd($model);
            $a_phanloai = array_column(dmphanloaidonvi::all()->toarray(), 'tenphanloai', 'maphanloai');
            $model_tenct = dmphanloaict::wherein('mact', getPLCTDuToan())->get();
            $model_nhomct = dmphanloaicongtac::wherein('macongtac', array_unique(array_column($model_tenct->toarray(), 'macongtac')))->get();
            $m_dvbc = dmdonvibaocao::where('level', 'T')->get();
            $a_donviql = array_column(dmdonvi::wherein('madvbc', array_column($m_dvbc->toarray(), 'madvbc'))->get()->toarray(), 'tendv', 'madv');
            $a_trangthai_in = array('ALL' => '--Tất cả đơn vị--', 'CHOGUI' => 'Đơn vị chưa gửi dữ liệu', 'DAGUI' => 'Đơn vị đã gửi dữ liệu');

            return view('functions.dutoanluong.khoi.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong', $soluong)
                ->with('a_donviql', $a_donviql)
                ->with('model_tenct', $model_tenct)
                ->with('model_nhomct', $model_nhomct)
                ->with('a_trangthai_in', $a_trangthai_in)
                ->with('a_phanloai', setArrayAll($a_phanloai))
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/du_toan/khoi')
                // ->with('furl_th', '/chuc_nang/du_toan_luong/khoi/')
                ->with('furl_th', '/chuc_nang/du_toan_luong/huyen/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();

            return redirect('/chuc_nang/xem_du_lieu/du_toan/khoi?namns=' . $model->namns . '&trangthai=ALL&phanloai=ALL');
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
            $madv = session('admin')->madv;
            $model_nguon_khoi = dutoanluong_khoi::where('namns', $inputs['namns'])->where('madv', $madv)->first();
            if ($model_nguon_khoi != null) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_khoi->trangthai = 'DAGUI';
                $model_nguon_khoi->nguoilap = session('admin')->name;
                $model_nguon_khoi->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_khoi->save();
                dutoanluong_huyen::where('namns', $inputs['namns'])->where('madv', $madv)
                    ->update(['trangthai' => 'DAGUI', 'nguoilap' => session('admin')->name, 'ngaylap' => Carbon::now()->toDateTimeString()]);
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                dutoanluong::where('namns', $inputs['namns'])->where('macqcq', $madv)
                    ->update(['masok' => $inputs['masodv'], 'masoh' => $inputs['masodv']]);

                //import vào bảng nguonkinhphi_khoi do nguonkinhphi_khoi(TH) = nguonkinhphi(SD)
                dutoanluong_khoi::create($inputs);
                dutoanluong_huyen::create($inputs);
            }
            return redirect('/chuc_nang/du_toan_luong/khoi/index');
        } else
            return view('errors.notlogin');
    }

    //In dữ liệu 1 đơn vị trong khối
    function printf(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();

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

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $thongtin = array('nam' => $model_thongtin->namns);


            return view('reports.viewdata.dutoan.donvi')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết dự toán lương tại đơn vị.');
        } else
            return view('errors.notlogin');
    }

    //Tổng hợp dữ liệu trong khối
    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //lấy dự toán lương chi tiết

            if (!isset($inputs['madv'])) {
                $inputs['madv'] = session('admin')->madv;
            }
            $model = dutoanluong_chitiet::wherein('masodv', function ($query) use ($inputs) {
                $query->select('masodv')->from('dutoanluong')->wherein('madv', function ($q) use ($inputs) {
                    $q->select('madv')->from('dmdonvi')->where('macqcq', $inputs['madv'])->get();
                })->where('trangthai', 'DAGUI')->where('namns', $inputs['namns'])->get();
            })

                ->get();

            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function ($query) use ($inputs) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $inputs['madv'])->where('madv', '<>', $inputs['madv'])->get();
                })->get();

            $model_dutoan = dutoanluong::wherein('madv', function ($query) use ($inputs) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $inputs['madv'])->get();
            })->get();

            $a_nhomct = getNhomCongTac(false);
            //dd($model->toarray());
            foreach ($model as $ct) {
                $dutoan = $model_dutoan->where('masodv', $ct->masodv)->first();
                $ct->madv = $dutoan->madv ?? null;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->tencongtac = isset($a_nhomct[$ct->mact]) ? $a_nhomct[$ct->macongtac] : '';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;
            }

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
    function printfbl(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dutoanluong_bangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();
            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
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
            $model_thang = $model->sortby('thang')->map(function ($data) {
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
}
