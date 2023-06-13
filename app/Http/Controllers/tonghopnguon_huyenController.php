<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
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

            $soluong = $model_donvi->count();

            foreach ($model as $dv) {
                $nam =  $dv->namdt = date('Y', strtotime($dv->ngayapdung)) ?? date('Y');
                $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')
                    ->where('macqcq', $madv)->where('madv', '<>', $madv)
                    ->wherenotin('madv', function ($query) use ($madv, $nam) {
                        $query->select('madv')->from('dmdonvi')
                            ->whereyear('ngaydung', '<=', $nam)
                            ->where('trangthai', 'TD')
                            ->get();
                    })->get();


                $soluong = $model_donvi->count();

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

    function mau2a(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('macqcq', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->where('trangthai', 'DAGUI')->get();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            $a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            foreach ($m_chitiet as $chitiet) {
                $chitiet->level = $a_diaban[$m_donvi->madvbc];
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
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
            $dulieu_pI = $m_chitiet->where('maphanloai', '<>', 'KVXP');
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
            $dulieu_pII = $m_chitiet->where('maphanloai',  'KVXP');
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
            $aIII_plct = getHDND_plct();
            $dulieu_pIII = $m_chitiet->where('maphanloai',  'KVXP');
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
            $dulieu_pIV = $m_chitiet->where('maphanloai',  'KVXP');
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
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id', 'phanloai', 'dmdonvi.madv')
                        ->where('dmdonvi.macqcq', $madv)
                        ->where('maphanloai', 'KVXP')
                        ->get();
                } else {
                    $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id', 'phanloai', 'dmdonvi.madv')
                        ->where('dmdonvi.madv', $madv)
                        ->where('maphanloai', 'KVXP')
                        ->get();
                }
            } else {
                $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->select('dmdiabandbkk.id', 'phanloai', 'dmdonvi.madv')
                    ->where('dmdonvi.madvbc', $madvbc)
                    ->where('maphanloai', 'KVXP')
                    ->get();
            }
            if (session('admin')->username == 'khthso') {
                $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->select('dmdiabandbkk.id', 'phanloai', 'dmdonvi.madv')
                    ->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvi.maphanloai', 'KVXP')
                    ->where('dmdonvibaocao.level', 'T')
                    ->get();
            }
            $m_xa = dmdonvi::where('maphanloai', 'KVXP')->get();
            $m_dt = dmdiabandbkk_chitiet::join('dmdiabandbkk', 'dmdiabandbkk_chitiet.madiaban', '=', 'dmdiabandbkk.madiaban')
                ->select('dmdiabandbkk_chitiet.id', 'phanloai')->get();

            $model = nguonkinhphi_bangluong::where('mact', '1506673695')->get();
            $ar_I = array();
            $ar_I[] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn');
            $ar_I[] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I');
            $ar_I[] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II');
            $ar_I[] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III');
            $ar_I[] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tỏ dân phố');
            $ar_I[] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo');
            $ar_I[] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo');
            $ar_I[] = array('val' => 'DBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn có 350 hộ gia đình trở lên,  xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền');
            $ar_I[] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => '- Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền');
            $ar_I[] = array('val' => 'TK,TDP', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại');
            $ar_I[] = array('val' => 'TK', 'tt' => '', 'noidung' => '- Thôn còn lại');
            $ar_I[] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố');

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

            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($m_xa)) {
                    $chitiet = $m_xa->where('phanloaixa', $ar_I[$i]['val']);
                    $a_dv = array_column($chitiet->toarray(), 'madv');
                    $doituong = $model->wherein('madv', $a_dv);
                }

                if (isset($chitiet) > 0) {
                    $kpk = 0;
                    $kpk2 = 0;
                    $ar_I[$i]['tdv'] = $chitiet->count('id');
                    $a_It['tdv'] += $ar_I[$i]['tdv'];
                    $ar_I[$i]['dt'] = $doituong->count('id');
                    $a_It['dt'] += $ar_I[$i]['dt'];

                    if ($ar_I[$i]['val'] == "XL1") {
                        $ar_I[$i]['mk'] = "20,3";
                        $ar_I[$i]['mk2'] = "16";
                        $kpk = 20.3;
                        $kpk2 = 16;
                    } elseif ($ar_I[$i]['val'] == "XL2") {
                        $ar_I[$i]['mk'] = "18,6";
                        $kpk = 18.6;
                        $ar_I[$i]['mk2'] = "13,7";
                        $kpk2 = 13.7;
                    } elseif ($ar_I[$i]['val'] == "XL3") {
                        $ar_I[$i]['mk'] = "17,6";
                        $kpk = 17.6;
                        $ar_I[$i]['mk2'] = "11,4";
                        $kpk2 = 11.4;
                    } elseif ($ar_I[$i]['val'] == "TBGHD" || $ar_I[$i]['val'] == "TDBKK" || $ar_I[$i]['val'] == "TXL12K") {
                        $ar_I[$i]['mk'] = "5,0";
                        $kpk = 5;
                        $ar_I[$i]['mk2'] = "5,0";
                        $kpk2 = 5;
                    } elseif ($ar_I[$i]['val'] == "TK" || $ar_I[$i]['val'] == "TDP") {
                        $ar_I[$i]['mk'] = "3,0";
                        $kpk = 3;
                        $ar_I[$i]['mk2'] = "3,0";
                        $kpk2 = 3;
                    } else {
                        $ar_I[$i]['mk'] = "";
                        $ar_I[$i]['mk2'] = "";
                    }

                    $ar_I[$i]['kqpc'] = $ar_I[$i]['tdv'] * $kpk * 1.39;
                    $a_It['kqpc'] += $ar_I[$i]['kqpc'];
                    $ar_I[$i]['bhxh'] = $ar_I[$i]['dt'] * 0.14 * 1.39;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];
                    $ar_I[$i]['kqpct7'] = $ar_I[$i]['tdv'] * $kpk2 * 1.49;
                    $a_It['kqpct7'] += $ar_I[$i]['kqpct7'];
                    $ar_I[$i]['tong'] = $ar_I[$i]['kqpct7'] - $ar_I[$i]['bhxh'] - $ar_I[$i]['kqpc'];
                    $a_It['tong'] += $ar_I[$i]['tong'];
                } else {
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['mk2'] = 0;
                    $ar_I[$i]['dt'] = 0;
                    $ar_I[$i]['kqpc'] = 0;
                    $ar_I[$i]['bhxh'] = 0;
                    $ar_I[$i]['kqpct7'] = 0;
                    $ar_I[$i]['tong'] = 0;
                }
            }
            //dd($ar_I);
            return view('reports.thongtu67.Mau2d_tt46')
                ->with('m_dv', $m_dv)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
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
