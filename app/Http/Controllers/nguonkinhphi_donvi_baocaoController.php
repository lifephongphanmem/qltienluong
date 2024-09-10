<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_nangluong;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_phucap;
use Illuminate\Support\Facades\Session;

class nguonkinhphi_donvi_baocaoController extends Controller
{


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
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model = $model->where('mapb', $inputs['mapb']);
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

            // dd($model->where('thang','07')->where('mact','1536459380')->toarray());
            $a_phongban = array_column(dmphongban::where('madv', $model_thongtin->madv)->get()->toArray(), 'tenpb', 'mapb');

            return view('reports.nguonkinhphi.donvi.bangluong')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_phongban', $a_phongban)
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
            $model_ct = nguonkinhphi_bangluong::where('masodv', $inputs['masodv']);
            $inputs['thang']  = $inputs['thang'] ?? 'ALL';
            if ($inputs['thang'] != 'ALL') {
                $model_ct = $model_ct->where('thang', $inputs['thang']);
            }
            if ($inputs['mact'] != 'ALL') {
                $model_ct = $model_ct->where('mact', $inputs['mact']);
            }
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model_ct = $model_ct->where('mapb', $inputs['mapb']);
            }
            $model_ct = $model_ct->orderby('thang')->orderby('stt')->get();

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
                $bl = $model_ct->where('macanbo', $ct->macanbo)
                    ->where('macvcq', $ct->macvcq)
                    ->where('mact', $ct->mact);
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

            $a_phongban = array_column(dmphongban::where('madv', $model_thongtin->madv)->get()->toArray(), 'tenpb', 'mapb');
            return view('reports.nguonkinhphi.donvi.bangluong_m2')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phongban', $a_phongban)
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
            // dd($model);
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

    function printf_nangluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            //$model = nguonkinhphi_nangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();

            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $model_tonghop = nguonkinhphi_bangluong::where('masodv', $inputs['masodv']);

            if ($inputs['mact'] != 'ALL') {
                $model_tonghop = $model_tonghop->where('mact', $inputs['mact']);
            }
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model_tonghop = $model_tonghop->where('mapb', $inputs['mapb']);
            }
            $model_tonghop = $model_tonghop->orderby('stt')->get();
            $model = $model_tonghop->where('thang', '07');

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($model as $key => $chitiet) {
                $canbo = $model_tonghop->where('macanbo', $chitiet->macanbo)
                    ->where('mact', $chitiet->mact)
                    ->where('macvcq', $chitiet->macvcq)->sortby('thang');

                if ($chitiet->luongtn * 6 < $canbo->sum('luongtn')) {
                    //dd($canbo->toarray());
                    //lấy thời gian nâng lương
                    foreach ($canbo as $cb) {
                        if ($chitiet->luongtn < $cb->luongtn) {
                            $chitiet->thangnangluong = $cb->thang;
                            break;
                        }
                    }

                    foreach ($m_pc as $ct) {
                        $mapc = $ct['mapc'];
                        //dd($mapc);
                        $chitiet->$mapc = $canbo->sum($mapc) - $chitiet->$mapc * 6;
                    }
                    $chitiet->tonghs = $canbo->sum('tonghs') - $chitiet->tonghs * 6;
                    $chitiet->luongtn = $canbo->sum('luongtn') - $chitiet->luongtn * 6;

                    $chitiet->stbhxh_dv = $canbo->sum('stbhxh_dv') - $chitiet->stbhxh_dv * 6;
                    $chitiet->stbhyt_dv = $canbo->sum('stbhyt_dv') - $chitiet->stbhyt_dv * 6;
                    $chitiet->stkpcd_dv = $canbo->sum('stkpcd_dv') - $chitiet->stkpcd_dv * 6;
                    $chitiet->stbhtn_dv = $canbo->sum('stbhtn_dv') - $chitiet->stbhtn_dv * 6;
                    $chitiet->ttbh_dv = $canbo->sum('ttbh_dv') - $chitiet->ttbh_dv * 6;
                } else {
                    $model->forget($key);
                }
            }
            //dd($model);

            //dd($m_pc);
            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }
            $a_phongban = array_column(dmphongban::where('madv', $model_thongtin->madv)->get()->toArray(), 'tenpb', 'mapb');
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            return view('reports.nguonkinhphi.donvi.nangluong')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phongban', $a_phongban)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghopnhucau_donvi_2a(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $m_chitiet = nguonkinhphi_01thang::where('masodv', $m_nguonkp->first()->masodv)->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->linhvuchoatdong = $m_nguonkp->first()->linhvuchoatdong;

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_nguonkp);

            $m_phucap = dmphucap_donvi::where('madv',  $m_nguonkp->first()->madv)->wherenotin('mapc', ['heso'])->get();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get(); //đưa về mảng cho dễ làm
            $a_phucap = getPhuCap2a_78();
            $array_phucap = array_keys($a_phucap); //mảng các phụ cấp 
            // dd(array_column($m_phucap->toarray(),'mapc'));
            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;
            //dd($m_nguonkp);
            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            //Mẫu chỉ lấy phân loại công tác: biên chế(mact: 1506672780) 05072024
            // $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE')->where('mact', '1506672780');
            // dd($m_chitiet);
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    // $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    //$ar_I[$key]['canbo_dutoan'] =  $ar_I[$key]['canbo_congtac'];
                    //28/6/2023: tạm thời bỏ để lấy dữ liệu giống 4a
                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
                    //$ar_I[$key]['quythuong'] = $dulieu_nguonkp->sum('quythuong_2a');
                    // 2340000 *10% * tongheso
                    $ar_I[$key]['quythuong'] = round(6 * $a_solieu['heso'] * $luongcb_moi / 10);
                    $ar_I[$key]['quythuong01thang'] = round($a_solieu['heso'] * $luongcb_moi / 10);
                }
            }
            // dd($ar_I);
            //Vòng cấp độ 2
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '2') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = $ar_I[$key]['quythuong'] = $ar_I[$key]['quythuong01thang'] = 0;
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
                        $ar_I[$key]['quythuong'] += $ar_I[$k]['quythuong'];
                        $ar_I[$key]['quythuong01thang'] += $ar_I[$k]['quythuong01thang'];
                    }

                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
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
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = $ar_I[$key]['quythuong'] = $ar_I[$key]['quythuong01thang'] = 0;

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
                        $ar_I[$key]['quythuong'] += $ar_I[$k]['quythuong'];
                        $ar_I[$key]['quythuong01thang'] += $ar_I[$k]['quythuong01thang'];
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
                    $ar_I[$key]['quythuong'] = 0;
                    $ar_I[$key]['quythuong01thang'] = 0;
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    //28/6/2023: tạm thời bỏ để lấy dữ liệu giống 4a
                    // $ar_II[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                    $ar_II[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                    $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                    //$ar_II[$key]['quythuong'] = $dulieu_nguonkp->sum('quythuong_2a');
                    // 2340000 *10% * tongheso
                    $ar_II[$key]['quythuong'] = round(6 * $a_solieu['heso'] * $luongcb_moi / 10);
                    $ar_II[$key]['quythuong01thang'] = round($a_solieu['heso'] * $luongcb_moi / 10);
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                    //28/6/2023: tạm thời bỏ để lấy dữ liệu giống 4a
                    // $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
                    $ar_III[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
                    $ar_III[$key]['quythuong'] = $ar_III[$key]['quythuong01thang'] = 0;
                }
            }
            // dd($ar_III);
            //Vòng cấp độ 2
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_III[$key]['canbo_congtac'] = $ar_III[$key]['canbo_dutoan'] = $ar_III[$key]['quythuong'] = $ar_III[$key]['quythuong01thang'] = 0;
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
                        $ar_III[$key]['quythuong'] += $ar_III[$k]['quythuong'];
                        $ar_III[$key]['quythuong01thang'] += $ar_III[$k]['quythuong01thang'];
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                        if (!in_array($pc->mapc, $array_phucap)) {
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
                    //28/6/2023: tạm thời bỏ để lấy dữ liệu giống 4a
                    // $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    // $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
                    $ar_IV[$key]['quythuong'] = 0;
                    $ar_IV[$key]['quythuong01thang'] = 0;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_IV as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_IV[$key]['canbo_congtac'] = $ar_IV[$key]['canbo_dutoan'] = $ar_IV[$key]['quythuong'] = $ar_IV[$key]['quythuong01thang'] = 0;
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
                        $ar_IV[$key]['quythuong'] += $ar_IV[$k]['quythuong'];
                        $ar_IV[$key]['quythuong01thang'] += $ar_IV[$k]['quythuong01thang'];
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
                'quythuong' => $ar_I[0]['quythuong'] + $ar_II[0]['quythuong'] + $ar_III[0]['quythuong'] + $ar_IV[0]['quythuong'],
                'quythuong01thang' => $ar_I[0]['quythuong01thang'] + $ar_II[0]['quythuong01thang'] + $ar_III[0]['quythuong01thang'] + $ar_IV[0]['quythuong01thang'],
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
            // dd($ar_III);
            //dd($m_tonghop_ct);
            //dd($inputs['mau']);
            if ($inputs['mau'] == 1) {
                $view = 'reports.thongtu78.donvi.mau2a_1';
            } else {
                $view = 'reports.thongtu78.donvi.mau2a_2';
            }
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? '1';
            //dd($ar_I);
            return view($view)
                // return view('reports.thongtu78.donvi.mau2a2')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_donvi)
                ->with('m_thongtu', $m_thongtu)
                ->with('inputs', $inputs)
                //->with('a_phucap', $a_phucap)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function tonghopnhucau_donvi_2a_2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->madv)->first();
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->sohieu)->first();
            $m_chitiet = nguonkinhphi_01thang::where('masodv', $m_nguonkp->masodv)->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->linhvuchoatdong = $m_nguonkp->linhvuchoatdong;

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_chitiet);
            $a_pc_th = dmphucap_donvi::where('madv',  $m_nguonkp->madv)->where('phanloai', '<', '3')->wherenotin('mapc', ['heso'])->get();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get(); //đưa về mảng cho dễ làm

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
            //Mẫu chỉ lấy phân loại công tác: biên chế(mact: 1506672780) 05072024
            // $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE')->where('mact', '1506672780');
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

                    //$ar_I[$key]['canbo_dutoan'] =  $ar_I[$key]['canbo_congtac'];
                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
                    //$ar_I[$key]['quythuong'] = $dulieu_chitiet->sum('quythuong_2a');//
                    // 2340000 *10% * tongheso
                    $ar_I[$key]['quythuong'] = round(6 * $a_solieu['heso'] * $luongcb_moi / 10);
                    $ar_I[$key]['quythuong01thang'] = round($a_solieu['heso'] * $luongcb_moi / 10);
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

                    $ar_I[$key]['chenhlech01thang'] = $ar_I[$key]['quythuong'] = $ar_I[$key]['quythuong01thang'] = 0;
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
                        $ar_I[$key]['chenhlech01thang'] += $ar_I[$k]['chenhlech01thang'];
                        $ar_I[$key]['quythuong'] += $ar_I[$k]['quythuong'];
                        $ar_I[$key]['quythuong01thang'] += $ar_I[$k]['quythuong01thang'];
                    }

                    // $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
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
                    $ar_I[$key]['chenhlech01thang'] = $ar_I[$key]['quythuong'] = $ar_I[$key]['quythuong01thang'] = 0;
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
                        $ar_I[$key]['chenhlech01thang'] += $ar_I[$k]['chenhlech01thang'];
                        $ar_I[$key]['quythuong'] += $ar_I[$k]['quythuong'];
                        $ar_I[$key]['quythuong01thang'] += $ar_I[$k]['quythuong01thang'];
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
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_I[$key]['chenhlech01thang'] = 0;
                    $ar_I[$key]['chenhlech06thang'] = 0;
                    $ar_I[$key]['canbo_congtac'] = 0;
                    $ar_I[$key]['canbo_dutoan'] = 0;
                    $ar_I[$key]['quythuong'] = 0;
                    $ar_I[$key]['quythuong01thang'] = 0;
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
                    //$ar_II[$key]['quythuong'] = $dulieu_nguonkp->sum('quythuong_2a');
                    // 2340000 *10% * tongheso
                    $ar_II[$key]['quythuong'] = round(6 * $a_solieu['heso'] * $luongcb_moi / 10);
                    $ar_II[$key]['quythuong01thang'] = round($a_solieu['heso'] * $luongcb_moi / 10);
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
                    $ar_III[$key]['quythuong'] =  $ar_III[$key]['quythuong01thang'] =  0; //Nhóm III và IV tính cán bộ trên nhóm I và II
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_III[$key]['canbo_congtac'] = $ar_III[$key]['canbo_dutoan'] = $ar_III[$key]['quythuong'] = $ar_III[$key]['quythuong01thang'] = 0;
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
                        $ar_III[$key]['quythuong'] += $ar_III[$k]['quythuong'];
                        $ar_III[$key]['quythuong01thang'] += $ar_III[$k]['quythuong01thang'];
                    }

                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
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
                    $ar_IV[$key]['quythuong'] = 0; //Nhóm III và IV tính cán bộ trên nhóm I và II
                    $ar_IV[$key]['quythuong01thang'] = 0;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_IV as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_IV[$key]['canbo_congtac'] = $ar_IV[$key]['canbo_dutoan'] = $ar_IV[$key]['quythuong'] = $ar_IV[$key]['quythuong01thang'] = 0;
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
                        $ar_IV[$key]['quythuong'] += $ar_IV[$k]['quythuong'];
                        $ar_IV[$key]['quythuong01thang'] += $ar_IV[$k]['quythuong01thang'];
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
                'quythuong' => $ar_I[0]['quythuong'] + $ar_II[0]['quythuong'] + $ar_III[0]['quythuong'] + $ar_IV[0]['quythuong'],
                'quythuong01thang' => $ar_I[0]['quythuong01thang'] + $ar_II[0]['quythuong01thang'] + $ar_III[0]['quythuong01thang'] + $ar_IV[0]['quythuong01thang'],
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
            // dd($ar_I);
            //dd($m_tonghop_ct);

            if ($inputs['mau'] == 1) {
                $view = 'reports.thongtu78.donvi.mau2a2_1';
            } else {
                $view = 'reports.thongtu78.donvi.mau2a2_2';
            }
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? '1';

            return view($view)
                // return view('reports.thongtu78.donvi.mau2a2_2_cu')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_donvi)
                ->with('m_thongtu', $m_thongtu)
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
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->madv)->first();
            if ($m_thongtu->masobaocao != 'ND73_2024') {
                //Các biểu thông tư cũ
                $ar_I = array();
                $ar_I[0] = array(
                    'val' => 'BT',
                    'tt' => '1',
                    'noidung' => 'Nguyên bí thư, chủ tịch',
                    'songuoi' => $m_nguonkp->tongsonguoi1,
                    'quy1' => $m_nguonkp->quy1_1,
                    'quy2' => $m_nguonkp->quy2_1,
                    'quy3' => $m_nguonkp->quy3_1,
                    'tongquy' => $m_nguonkp->quy1_tong,

                );

                $ar_I[1] = array(
                    'val' => 'P',
                    'tt' => '2',
                    'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng',
                    'songuoi' => $m_nguonkp->tongsonguoi2,
                    'quy1' => $m_nguonkp->quy1_2,
                    'quy2' => $m_nguonkp->quy2_2,
                    'quy3' => $m_nguonkp->quy3_2,
                    'tongquy' => $m_nguonkp->quy2_tong,
                );
                $ar_I[2] = array(
                    'val' => 'K',
                    'tt' => '3',
                    'noidung' => 'Các chức danh còn lại',
                    'songuoi' => $m_nguonkp->tongsonguoi3,
                    'quy1' => $m_nguonkp->quy1_3,
                    'quy2' => $m_nguonkp->quy2_3,
                    'quy3' => $m_nguonkp->quy3_3,
                    'tongquy' => $m_nguonkp->quy3_tong,
                );

                //dd($m_tonghop_ct);
                return view('reports.thongtu78.huyen.mau2b')
                    ->with('ar_I', $ar_I)
                    ->with('m_dv', $m_donvi)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
            } else {
                //chênh lêch = 540.000 * 4.5% = 24300
                $ar_I = array();
                $ar_I[0] = array(
                    'val' => 'BT',
                    'tt' => '1',
                    'noidung' => 'Nguyên bí thư, chủ tịch',
                    'songuoi' => $m_nguonkp->tongsonguoi1,
                    'quy1' => $m_nguonkp->quy1_1,
                    'quy2' => $m_nguonkp->quy2_1,
                    'trocap' => $m_nguonkp->quy2_1 - $m_nguonkp->quy1_1,
                    'baohiem' => $m_nguonkp->tongsonguoi1 * 24300,
                    'tongquy' => (($m_nguonkp->quy2_1 - $m_nguonkp->quy1_1) + ($m_nguonkp->tongsonguoi1 * 24300)) * 6,

                );

                $ar_I[1] = array(
                    'val' => 'P',
                    'tt' => '2',
                    'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng',
                    'songuoi' => $m_nguonkp->tongsonguoi2,
                    'quy1' => $m_nguonkp->quy1_2,
                    'quy2' => $m_nguonkp->quy2_2,
                    'trocap' => $m_nguonkp->quy2_2 - $m_nguonkp->quy1_2,
                    'baohiem' => $m_nguonkp->tongsonguoi2 * 24300,
                    'tongquy' => (($m_nguonkp->quy2_2 - $m_nguonkp->quy1_2) + ($m_nguonkp->tongsonguoi2 * 24300)) * 6,
                );
                $ar_I[2] = array(
                    'val' => 'K',
                    'tt' => '3',
                    'noidung' => 'Các chức danh còn lại',
                    'songuoi' => $m_nguonkp->tongsonguoi3,
                    'quy1' => $m_nguonkp->quy1_3,
                    'quy2' => $m_nguonkp->quy2_3,
                    'trocap' => $m_nguonkp->quy2_3 - $m_nguonkp->quy1_3,
                    'baohiem' => $m_nguonkp->tongsonguoi3 * 24300,
                    'tongquy' => (($m_nguonkp->quy2_3 - $m_nguonkp->quy1_3) + ($m_nguonkp->tongsonguoi3 * 24300)) * 6,
                );

                //dd($m_tonghop_ct);
                return view('reports.nghidinh73.donvi.mau2b')
                    ->with('ar_I', $ar_I)
                    ->with('m_dv', $m_donvi)
                    ->with('inputs', $inputs)
                    ->with('m_thongtu', $m_thongtu)
                    ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2c(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //dd($m_nguonkp);
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

            if ($m_thongtu->masobaocao != 'ND73_2024') {
                $ar_I = array();
                $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;');
                //
                $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
                $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                    'tdv' => $m_nguon_1->count(),
                    'mk' => 16,
                    'mk2' => 21,
                    // 'clt7' => round($m_nguon_1->count() * 16 * 310000),
                    // 'cl5t' => round($m_nguon_1->count() * 21 * 310000 * 5),
                    'clt7' => round($m_nguon_1->count() * 16 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguon_1->count() * 21 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[1]['solieu']['tong'] = $ar_I[1]['solieu']['clt7'] + $ar_I[1]['solieu']['cl5t'];
                //

                $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
                $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                    'tdv' => $m_nguon_2->count(),
                    'mk' => 13.7,
                    'mk2' => 18,
                    // 'clt7' => round($m_nguon_2->count() * 13.7 * 310000),
                    // 'cl5t' => round($m_nguon_2->count() * 18 * 310000 * 5),
                    'clt7' => round($m_nguon_2->count() * 13.7 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguon_2->count() * 18 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[2]['solieu']['tong'] = $ar_I[2]['solieu']['clt7'] + $ar_I[2]['solieu']['cl5t'];
                //

                $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
                $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                    'tdv' => $m_nguon_3->count(),
                    'mk' => 11.4,
                    'mk2' => 15,
                    // 'clt7' => round($m_nguon_3->count() * 11.4 * 310000),
                    // 'cl5t' => round($m_nguon_3->count() * 15 * 310000 * 5),
                    'clt7' => round($m_nguon_3->count() * 11.4 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguon_3->count() * 15 * $m_thongtu->chenhlech * 5),
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
                    // 'clt7' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[5]['solieu']['tong'] = $ar_I[5]['solieu']['clt7'] + $ar_I[5]['solieu']['cl5t'];

                //Thôn thuộc xã biên giới, hải đảo
                $ar_I[6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    // 'clt7' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[6]['solieu']['tong'] = $ar_I[6]['solieu']['clt7'] + $ar_I[6]['solieu']['cl5t'];

                //Tổ dân phố thuộc xã biên giới, hải đảo
                $ar_I[7] = array('val' => '', 'tt' => '', 'noidung' => '- Tổ dân phố thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => 0,
                    'mk' => 0,
                    'mk2' => 0,
                    'clt7' => 0,
                    'cl5t' => 0,
                    'tong' => 0,
                ]);

                //II.2  8 = 9 + 10 + 11 + 12
                $ar_I[8] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

                //Số hộ 350 trở lên
                $ar_I[9] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothon350hgd_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    // 'clt7' => round($m_nguonkp->sum('sothon350hgd_2d') * 5 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sothon350hgd_2d') * 5 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[9]['solieu']['tong'] = $ar_I[9]['solieu']['clt7'] + $ar_I[9]['solieu']['cl5t'];

                //500 hộ trở lên
                $ar_I[10] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sotodanpho500hgd_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    // 'clt7' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 3 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 3 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[10]['solieu']['tong'] = $ar_I[10]['solieu']['clt7'] + $ar_I[10]['solieu']['cl5t'];

                //Tổ dân phố thuộc xã trọng điểm về an ninh
                $ar_I[11] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    // 'clt7' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[11]['solieu']['tong'] = $ar_I[11]['solieu']['clt7'] + $ar_I[11]['solieu']['cl5t'];

                //Tổ dân phố chuyển từ thôn
                $ar_I[12] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sochuyentuthon350hgd_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    // 'clt7' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 3 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 3 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->chenhlech * 5),
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
                    // 'clt7' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->chenhlech * 5),
                ]);
                $ar_I[14]['solieu']['tong'] = $ar_I[14]['solieu']['clt7'] + $ar_I[14]['solieu']['cl5t'];

                //Tổ dân phố
                $ar_I[15] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                    'mk' => 3,
                    'mk2' => 4.5,
                    // 'clt7' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * 310000),
                    // 'cl5t' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * 310000 * 5),
                    'clt7' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * $m_thongtu->chenhlech),
                    'cl5t' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->chenhlech * 5),
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
                    //return view('reports.nghidinh73.donvi.mau2c')
                    ->with('m_dv', $m_donvi)
                    ->with('m_thongtu', $m_thongtu)
                    ->with('ar_I', $ar_I)
                    ->with('a_It', $a_It)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
            } else {
                $ar_I = array();
                $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;',);
                $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
                $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã, thị trấn loại I', 'solieu' => [
                    'tdv' => $m_nguon_1->count(),
                    'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL1'),
                    // 'muccu' => round($m_nguon_1->count() * 22 * $m_thongtu->muccu),
                    // 'mucapdung' => round($m_nguon_1->count() * 22 * $m_thongtu->mucapdung),
                    // 'chenhlech' => round($m_nguon_1->count() * 22 * $m_thongtu->chenhlech * 6),
                    'muccu' => round($m_nguon_1->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguon_1->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguon_1->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->chenhlech * 6),
                ]);

                $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
                $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã, thị trấn loại II', 'solieu' => [
                    'tdv' => $m_nguon_2->count(),
                    'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL2'),
                    // 'muccu' => round($m_nguon_2->count() * 20 * $m_thongtu->muccu),
                    // 'mucapdung' => round($m_nguon_2->count() * 20 * $m_thongtu->mucapdung),
                    // 'chenhlech' => round($m_nguon_2->count() * 20 * $m_thongtu->chenhlech * 6),
                    'muccu' => round($m_nguon_2->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguon_2->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguon_2->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->chenhlech * 6),
                ]);

                $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
                $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã, thị trấn loại III', 'solieu' => [
                    'tdv' => $m_nguon_3->count(),
                    'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL3'),
                    // 'muccu' => round($m_nguon_3->count() * 18 * $m_thongtu->muccu),
                    // 'mucapdung' => round($m_nguon_3->count() * 18 * $m_thongtu->mucapdung),
                    // 'chenhlech' => round($m_nguon_3->count() * 18 * $m_thongtu->chenhlech * 6),
                    'muccu' => round($m_nguon_3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguon_3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguon_3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->chenhlech * 6),
                ]);

                $m_nguon = $m_nguonkp->where('phanloaixa', 'PL1');
                $ar_I[4] = array('val' => 'PL1', 'tt' => '4', 'noidung' => 'Phường loại I', 'solieu' => [
                    'tdv' => $m_nguon->count(),
                    'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL1'),
                    // 'muccu' => round($m_nguon->count() * 23 * $m_thongtu->muccu),
                    // 'mucapdung' => round($m_nguon->count() * 23 * $m_thongtu->mucapdung),
                    // 'chenhlech' => round($m_nguon->count() * 23 * $m_thongtu->chenhlech * 6),
                    'muccu' => round($m_nguon->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguon->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguon->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->chenhlech * 6),
                ]);

                $m_nguon_PL2 = $m_nguonkp->where('phanloaixa', 'PL2');
                $ar_I[5] = array('val' => 'PL2', 'tt' => '5', 'noidung' => 'Phường loại II', 'solieu' => [
                    'tdv' => $m_nguon->count(),
                    'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL2'),
                    // 'muccu' => round($m_nguon->count() * 21 * $m_thongtu->muccu),
                    // 'mucapdung' => round($m_nguon->count() * 21 * $m_thongtu->mucapdung),
                    // 'chenhlech' => round($m_nguon->count() * 21 * $m_thongtu->chenhlech * 6),
                    'muccu' => round($m_nguon_PL2->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguon_PL2->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguon_PL2->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->chenhlech * 6),

                ]);

                $m_nguon_PL3 = $m_nguonkp->where('phanloaixa', 'PL3');
                $ar_I[6] = array('val' => 'PL3', 'tt' => '6', 'noidung' => 'Phường loại III', 'solieu' => [
                    'tdv' => $m_nguon->count(),
                    'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL3'),
                    // 'muccu' => round($m_nguon->count() * 19 * $m_thongtu->muccu),
                    // 'mucapdung' => round($m_nguon->count() * 19 * $m_thongtu->mucapdung),
                    // 'chenhlech' => round($m_nguon->count() * 19 * $m_thongtu->chenhlech * 6),
                    'muccu' => round($m_nguon_PL3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguon_PL3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguon_PL3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->chenhlech * 6),
                ]);
                //0 = 1+2+3+4+5+6
                $ar_I[0]['solieu'] = [
                    'tdv' =>  $ar_I[1]['solieu']['tdv'] + $ar_I[2]['solieu']['tdv'] + $ar_I[3]['solieu']['tdv']
                        + $ar_I[4]['solieu']['tdv'] + $ar_I[5]['solieu']['tdv'] + $ar_I[6]['solieu']['tdv'],
                    'mk' => 0,
                    'muccu' => $ar_I[1]['solieu']['muccu'] + $ar_I[2]['solieu']['muccu'] + $ar_I[3]['solieu']['muccu']
                        + $ar_I[4]['solieu']['muccu'] + $ar_I[5]['solieu']['muccu'] + $ar_I[6]['solieu']['muccu'],
                    'mucapdung' => $ar_I[1]['solieu']['mucapdung'] + $ar_I[2]['solieu']['mucapdung'] + $ar_I[3]['solieu']['mucapdung']
                        + $ar_I[4]['solieu']['mucapdung'] + $ar_I[5]['solieu']['mucapdung'] + $ar_I[6]['solieu']['mucapdung'],
                    'chenhlech' => $ar_I[1]['solieu']['chenhlech'] + $ar_I[2]['solieu']['chenhlech'] + $ar_I[3]['solieu']['chenhlech']
                        + $ar_I[4]['solieu']['chenhlech'] + $ar_I[5]['solieu']['chenhlech'] + $ar_I[6]['solieu']['chenhlech'],
                ];

                //II  7= 8+10+15
                $ar_I[7] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố', 'style' => 'font-weight:bold;');
                //Số xã biên giới, hải đảo
                $ar_I[8] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                    'mk' => 6,
                    'muccu' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->chenhlech * 6),
                ]);

                //Thôn thuộc xã biên giới, hải đảo
                $ar_I[9] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                    'mk' => 6,
                    'muccu' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->chenhlech * 6),
                ]);

                //II.2  10 = 11 + 12 + 13 + 14
                $ar_I[10] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

                //Số hộ 350 trở lên
                $ar_I[11] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothon350hgd_2d'),
                    'mk' => 6,
                    'muccu' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->chenhlech * 6),

                ]);

                //500 hộ trở lên
                $ar_I[12] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sotodanpho500hgd_2d'),
                    'mk' => 6,
                    'muccu' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->chenhlech * 6),
                ]);

                //Tổ dân phố thuộc xã trọng điểm về an ninh
                $ar_I[13] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                    'mk' => 6,
                    'muccu' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->chenhlech * 6),
                ]);

                //Tổ dân phố chuyển từ thôn
                $ar_I[14] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sochuyentuthon350hgd_2d'),
                    'mk' => 6,
                    'muccu' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->chenhlech * 6),
                ]);

                //II.2  10 = 11 + 12 + 13 + 14 (Gán số liệu)
                $ar_I[10]['solieu'] = [
                    'tdv' =>  $ar_I[11]['solieu']['tdv'] + $ar_I[12]['solieu']['tdv'] + $ar_I[13]['solieu']['tdv'] + $ar_I[14]['solieu']['tdv'],
                    'mk' => 0,
                    'muccu' => $ar_I[11]['solieu']['muccu'] + $ar_I[12]['solieu']['muccu'] + $ar_I[13]['solieu']['muccu'] + $ar_I[14]['solieu']['muccu'],
                    'mucapdung' => $ar_I[11]['solieu']['mucapdung'] + $ar_I[12]['solieu']['mucapdung'] + $ar_I[13]['solieu']['mucapdung'] + $ar_I[14]['solieu']['mucapdung'],
                    'chenhlech' => $ar_I[11]['solieu']['chenhlech'] + $ar_I[12]['solieu']['chenhlech'] + $ar_I[13]['solieu']['chenhlech'] + $ar_I[14]['solieu']['chenhlech'],
                ];

                //II.3 15 = 16 + 17
                $ar_I[15] = array('val' => 'TDBKK', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại',);

                //Thôn còn lại
                $ar_I[16] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sothonconlai_2d'),
                    'mk' => 4.5,
                    'muccu' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->chenhlech * 6),
                ]);

                //Tổ dân phố
                $ar_I[17] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                    'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                    'mk' => 4.5,
                    'muccu' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->muccu),
                    'mucapdung' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->mucapdung),
                    'chenhlech' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->chenhlech * 6),
                ]);

                //II.3 15 = 16 + 17 (gán số liệu)
                $ar_I[15]['solieu'] = [
                    'tdv' =>  $ar_I[16]['solieu']['tdv'] + $ar_I[17]['solieu']['tdv'],
                    'mk' => 0,
                    'muccu' => $ar_I[16]['solieu']['muccu'] + $ar_I[17]['solieu']['muccu'],
                    'mucapdung' => $ar_I[16]['solieu']['mucapdung'] + $ar_I[17]['solieu']['mucapdung'],
                    'chenhlech' => $ar_I[16]['solieu']['chenhlech'] + $ar_I[17]['solieu']['chenhlech'],
                ];

                //II.3 7 = 8+10+15(gán số liệu)
                $ar_I[7]['solieu'] = [
                    'tdv' =>  $ar_I[8]['solieu']['tdv'] + $ar_I[10]['solieu']['tdv'] + $ar_I[15]['solieu']['tdv'],
                    'mk' => 0,
                    'muccu' => $ar_I[8]['solieu']['muccu'] + $ar_I[10]['solieu']['muccu'] + $ar_I[15]['solieu']['muccu'],
                    'mucapdung' => $ar_I[8]['solieu']['mucapdung'] + $ar_I[10]['solieu']['mucapdung'] + $ar_I[15]['solieu']['mucapdung'],
                    'chenhlech' => $ar_I[8]['solieu']['chenhlech'] + $ar_I[10]['solieu']['chenhlech'] + $ar_I[15]['solieu']['chenhlech'],
                ];

                $a_It = array(
                    'tdv' =>  $ar_I[0]['solieu']['tdv'] + $ar_I[7]['solieu']['tdv'],
                    'mk' => 0,
                    'muccu' =>  $ar_I[0]['solieu']['muccu'] + $ar_I[7]['solieu']['muccu'],
                    'mucapdung' => $ar_I[0]['solieu']['mucapdung'] + $ar_I[7]['solieu']['mucapdung'],
                    'chenhlech' =>  $ar_I[0]['solieu']['chenhlech'] + $ar_I[7]['solieu']['chenhlech'],
                );

                //dd($ar_I);
                return view('reports.nghidinh73.donvi.mau2c')
                    ->with('m_dv', $m_donvi)
                    ->with('m_thongtu', $m_thongtu)
                    ->with('ar_I', $ar_I)
                    ->with('a_It', $a_It)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2c_2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //dd($m_nguonkp);
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
            $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;',);
            $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã, thị trấn loại I', 'solieu' => [
                'tdv' => $m_nguon_1->count(),
                'mk' => $m_nguon_1->sum('soluongcanbo_2c') > 0 ? $m_nguon_1->sum('soluongcanbo_2c') * 1.5 : getMucKhoanPhuCapXa('ND33/2024', 'XL1'),
                'sl' => $m_nguon_1->sum('soluongcanbo_2c'),
                'muccu' => 0,
                'mucapdung' => 0,
                'chenhlech' => 0,
            ]);
            $ar_I[1]['solieu']['muccu'] = round($m_nguon_1->count() * $ar_I[1]['solieu']['mk'] * $m_thongtu->muccu);
            $ar_I[1]['solieu']['mucapdung'] = round($m_nguon_1->count() * $ar_I[1]['solieu']['mk'] * $m_thongtu->mucapdung);
            $ar_I[1]['solieu']['chenhlech'] = round($m_nguon_1->count() * $ar_I[1]['solieu']['mk'] * $m_thongtu->chenhlech * 6);

            //
            $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã, thị trấn loại II', 'solieu' => [
                'tdv' => $m_nguon_2->count(),
                'mk' => $m_nguon_2->sum('soluongcanbo_2c') > 0 ? $m_nguon_2->sum('soluongcanbo_2c') * 1.5 : getMucKhoanPhuCapXa('ND33/2024', 'XL2'),
                //'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL2'),
                'sl' => $m_nguon_2->sum('soluongcanbo_2c'),
                'muccu' => 0,
                'mucapdung' => 0,
                'chenhlech' => 0,
            ]);
            $ar_I[2]['solieu']['muccu'] = round($m_nguon_2->count() * $ar_I[2]['solieu']['mk'] * $m_thongtu->muccu);
            $ar_I[2]['solieu']['mucapdung'] = round($m_nguon_2->count() * $ar_I[2]['solieu']['mk'] * $m_thongtu->mucapdung);
            $ar_I[2]['solieu']['chenhlech'] = round($m_nguon_2->count() * $ar_I[2]['solieu']['mk'] * $m_thongtu->chenhlech * 6);

            //
            $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã, thị trấn loại III', 'solieu' => [
                'tdv' => $m_nguon_3->count(),
                //'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL3'),
                'mk' => $m_nguon_3->sum('soluongcanbo_2c') > 0 ? $m_nguon_3->sum('soluongcanbo_2c') * 1.5 : getMucKhoanPhuCapXa('ND33/2024', 'XL3'),
                'sl' => $m_nguon_3->sum('soluongcanbo_2c'),
                'muccu' => round($m_nguon_3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->muccu),
                'mucapdung' => round($m_nguon_3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguon_3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->chenhlech * 6),
            ]);

            $ar_I[3]['solieu']['muccu'] = round($m_nguon_3->count() * $ar_I[3]['solieu']['mk'] * $m_thongtu->muccu);
            $ar_I[3]['solieu']['mucapdung'] = round($m_nguon_3->count() * $ar_I[3]['solieu']['mk'] * $m_thongtu->mucapdung);
            $ar_I[3]['solieu']['chenhlech'] = round($m_nguon_3->count() * $ar_I[3]['solieu']['mk'] * $m_thongtu->chenhlech * 6);
            //
            $m_nguon = $m_nguonkp->where('phanloaixa', 'PL1');
            $ar_I[4] = array('val' => 'PL1', 'tt' => '4', 'noidung' => 'Phường loại I', 'solieu' => [
                'tdv' => $m_nguon->count(),
                //'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL1'),
                'mk' => $m_nguon->sum('soluongcanbo_2c') > 0 ? $m_nguon->sum('soluongcanbo_2c') * 1.5 : getMucKhoanPhuCapXa('ND33/2024', 'XL1'),
                'sl' => $m_nguon->sum('soluongcanbo_2c'),
                'muccu' => 0,
                'mucapdung' => 0,
                'chenhlech' => 0,
            ]);
            $ar_I[4]['solieu']['muccu'] = round($m_nguon_3->count() * $ar_I[4]['solieu']['mk'] * $m_thongtu->muccu);
            $ar_I[4]['solieu']['mucapdung'] = round($m_nguon_3->count() * $ar_I[4]['solieu']['mk'] * $m_thongtu->mucapdung);
            $ar_I[4]['solieu']['chenhlech'] = round($m_nguon_3->count() * $ar_I[4]['solieu']['mk'] * $m_thongtu->chenhlech * 6);
            //
            $m_nguon_PL2 = $m_nguonkp->where('phanloaixa', 'PL2');
            $ar_I[5] = array('val' => 'PL2', 'tt' => '5', 'noidung' => 'Phường loại II', 'solieu' => [
                'tdv' => $m_nguon_PL2->count(),
                //'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL2'),
                'mk' => $m_nguon_PL2->sum('soluongcanbo_2c') > 0 ? $m_nguon_PL2->sum('soluongcanbo_2c') * 1.5 : getMucKhoanPhuCapXa('ND33/2024', 'XL2'),
                'sl' => $m_nguon_PL2->sum('soluongcanbo_2c'),
            ]);
            $ar_I[5]['solieu']['muccu'] = round($m_nguon_PL2->count() * $ar_I[5]['solieu']['mk'] * $m_thongtu->muccu);
            $ar_I[5]['solieu']['mucapdung'] = round($m_nguon_PL2->count() * $ar_I[5]['solieu']['mk'] * $m_thongtu->mucapdung);
            $ar_I[5]['solieu']['chenhlech'] = round($m_nguon_PL2->count() * $ar_I[5]['solieu']['mk'] * $m_thongtu->chenhlech * 6);
            //
            $m_nguon_PL3 = $m_nguonkp->where('phanloaixa', 'PL3');
            $ar_I[6] = array('val' => 'PL3', 'tt' => '6', 'noidung' => 'Phường loại III', 'solieu' => [
                'tdv' => $m_nguon_PL3->count(),
                //'mk' => getMucKhoanPhuCapXa('ND33/2024', 'XL3'),
                'mk' => $m_nguon_PL2->sum('soluongcanbo_2c') > 0 ? $m_nguon_PL2->sum('soluongcanbo_2c') * 1.5 : getMucKhoanPhuCapXa('ND33/2024', 'XL3'),
                'sl' => $m_nguon_PL3->sum('soluongcanbo_2c'),
                // 'muccu' => round($m_nguon_PL3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->muccu),
                // 'mucapdung' => round($m_nguon_PL3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->mucapdung),
                // 'chenhlech' => round($m_nguon_PL3->count() * getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->chenhlech * 6),
            ]);
            $ar_I[6]['solieu']['muccu'] = round($m_nguon_PL3->count() * $ar_I[6]['solieu']['mk'] * $m_thongtu->muccu);
            $ar_I[6]['solieu']['mucapdung'] = round($m_nguon_PL3->count() * $ar_I[6]['solieu']['mk'] * $m_thongtu->mucapdung);
            $ar_I[6]['solieu']['chenhlech'] = round($m_nguon_PL3->count() * $ar_I[6]['solieu']['mk'] * $m_thongtu->chenhlech * 6);

            //0 = 1+2+3+4+5+6
            $ar_I[0]['solieu'] = [
                'tdv' =>  $ar_I[1]['solieu']['tdv'] + $ar_I[2]['solieu']['tdv'] + $ar_I[3]['solieu']['tdv']
                    + $ar_I[4]['solieu']['tdv'] + $ar_I[5]['solieu']['tdv'] + $ar_I[6]['solieu']['tdv'],
                'mk' => 0,
                'sl' => 0,
                'muccu' => $ar_I[1]['solieu']['muccu'] + $ar_I[2]['solieu']['muccu'] + $ar_I[3]['solieu']['muccu']
                    + $ar_I[4]['solieu']['muccu'] + $ar_I[5]['solieu']['muccu'] + $ar_I[6]['solieu']['muccu'],
                'mucapdung' => $ar_I[1]['solieu']['mucapdung'] + $ar_I[2]['solieu']['mucapdung'] + $ar_I[3]['solieu']['mucapdung']
                    + $ar_I[4]['solieu']['mucapdung'] + $ar_I[5]['solieu']['mucapdung'] + $ar_I[6]['solieu']['mucapdung'],
                'chenhlech' => $ar_I[1]['solieu']['chenhlech'] + $ar_I[2]['solieu']['chenhlech'] + $ar_I[3]['solieu']['chenhlech']
                    + $ar_I[4]['solieu']['chenhlech'] + $ar_I[5]['solieu']['chenhlech'] + $ar_I[6]['solieu']['chenhlech'],
            ];

            //II  7= 8+10+15
            $ar_I[7] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố', 'style' => 'font-weight:bold;');
            //Số xã biên giới, hải đảo
            $ar_I[8] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 6,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->chenhlech * 6),
            ]);

            //Thôn thuộc xã biên giới, hải đảo
            $ar_I[9] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 6,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $m_thongtu->chenhlech * 6),
            ]);

            //II.2  10 = 11 + 12 + 13 + 14
            $ar_I[10] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

            //Số hộ 350 trở lên
            $ar_I[11] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothon350hgd_2d'),
                'mk' => 6,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $m_thongtu->chenhlech * 6),

            ]);

            //500 hộ trở lên
            $ar_I[12] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotodanpho500hgd_2d'),
                'mk' => 6,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $m_thongtu->chenhlech * 6),
            ]);

            //Tổ dân phố thuộc xã trọng điểm về an ninh
            $ar_I[13] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 6,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $m_thongtu->chenhlech * 6),
            ]);

            //Tổ dân phố chuyển từ thôn
            $ar_I[14] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sochuyentuthon350hgd_2d'),
                'mk' => 6,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $m_thongtu->chenhlech * 6),
            ]);

            //II.2  10 = 11 + 12 + 13 + 14 (Gán số liệu)
            $ar_I[10]['solieu'] = [
                'tdv' =>  $ar_I[11]['solieu']['tdv'] + $ar_I[12]['solieu']['tdv'] + $ar_I[13]['solieu']['tdv'] + $ar_I[14]['solieu']['tdv'],
                'mk' => 0,
                'sl' => 0,
                'muccu' => $ar_I[11]['solieu']['muccu'] + $ar_I[12]['solieu']['muccu'] + $ar_I[13]['solieu']['muccu'] + $ar_I[14]['solieu']['muccu'],
                'mucapdung' => $ar_I[11]['solieu']['mucapdung'] + $ar_I[12]['solieu']['mucapdung'] + $ar_I[13]['solieu']['mucapdung'] + $ar_I[14]['solieu']['mucapdung'],
                'chenhlech' => $ar_I[11]['solieu']['chenhlech'] + $ar_I[12]['solieu']['chenhlech'] + $ar_I[13]['solieu']['chenhlech'] + $ar_I[14]['solieu']['chenhlech'],
            ];

            //II.3 15 = 16 + 17
            $ar_I[15] = array('val' => 'TDBKK', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại',);

            //Thôn còn lại
            $ar_I[16] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonconlai_2d'),
                'mk' => 4.5,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $m_thongtu->chenhlech * 6),
            ]);

            //Tổ dân phố
            $ar_I[17] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                'mk' => 4.5,
                'sl' => 0,
                'muccu' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->muccu),
                'mucapdung' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->mucapdung),
                'chenhlech' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $m_thongtu->chenhlech * 6),
            ]);

            //II.3 15 = 16 + 17 (gán số liệu)
            $ar_I[15]['solieu'] = [
                'tdv' =>  $ar_I[16]['solieu']['tdv'] + $ar_I[17]['solieu']['tdv'],
                'mk' => 0,
                'sl' => 0,
                'muccu' => $ar_I[16]['solieu']['muccu'] + $ar_I[17]['solieu']['muccu'],
                'mucapdung' => $ar_I[16]['solieu']['mucapdung'] + $ar_I[17]['solieu']['mucapdung'],
                'chenhlech' => $ar_I[16]['solieu']['chenhlech'] + $ar_I[17]['solieu']['chenhlech'],
            ];

            //II.3 7 = 8+10+15(gán số liệu)
            $ar_I[7]['solieu'] = [
                'tdv' =>  $ar_I[8]['solieu']['tdv'] + $ar_I[10]['solieu']['tdv'] + $ar_I[15]['solieu']['tdv'],
                'mk' => 0,
                'sl' => 0,
                'muccu' => $ar_I[8]['solieu']['muccu'] + $ar_I[10]['solieu']['muccu'] + $ar_I[15]['solieu']['muccu'],
                'mucapdung' => $ar_I[8]['solieu']['mucapdung'] + $ar_I[10]['solieu']['mucapdung'] + $ar_I[15]['solieu']['mucapdung'],
                'chenhlech' => $ar_I[8]['solieu']['chenhlech'] + $ar_I[10]['solieu']['chenhlech'] + $ar_I[15]['solieu']['chenhlech'],
            ];

            $a_It = array(
                'tdv' =>  $ar_I[0]['solieu']['tdv'] + $ar_I[7]['solieu']['tdv'],
                'mk' => 0,
                'sl' => 0,
                'muccu' =>  $ar_I[0]['solieu']['muccu'] + $ar_I[7]['solieu']['muccu'],
                'mucapdung' => $ar_I[0]['solieu']['mucapdung'] + $ar_I[7]['solieu']['mucapdung'],
                'chenhlech' =>  $ar_I[0]['solieu']['chenhlech'] + $ar_I[7]['solieu']['chenhlech'],
            );

            //dd($ar_I);
            return view('reports.nghidinh73.donvi.mau2c_2')
                ->with('m_dv', $m_donvi)
                ->with('m_thongtu', $m_thongtu)
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
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;

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
            //Tính toán số liệu phần I
            $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => 'II', 'noidung' => 'XÃ',);

            $ar_I[1] = array('style' => '', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1');
            // dd($m_xl1);
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2k' => $m_xl1->count(),
                'qd34_2d' => $m_xl1->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_xl1->count() * $m_xl1->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_xl1->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_xl1->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_xl1->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_xl1->sum('tyledonggop_2d'),
                'soluongdinhbien_2d' => $m_xl1->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL1'), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL1') * $m_xl1->count(),
                'quyluonggiam_2k' => $m_xl1->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl1->sum('quyluonggiam_2k') * 5,
            ];

            $ar_I[2] = array('style' => '', 'tt' => '2', 'noidung' => 'Xã loại 2',);
            $m_xl2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2]['solieu'] = [
                'soluongdonvi_2k' => $m_xl2->count(),
                'qd34_2d' => $m_xl2->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_xl2->count() * $m_xl2->sum('soluongdinhbien_2d'),
                'soluongcanbo_2d' => $m_xl2->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_xl2->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_xl2->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_xl2->sum('tyledonggop_2d'),
                'soluongdinhbien_2d' => $m_xl2->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2'), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL2') * $m_xl2->count(),
                // 'soluongdinhbien_2d' => $m_xl2->sum('soluongdinhbien_2d'),
                // 'tongsodinhbien_2d'=>$m_xl2->count() * $m_xl2->sum('soluongdinhbien_2d'),
                'quyluonggiam_2k' => $m_xl2->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl2->sum('quyluonggiam_2k') * 5,
            ];

            $ar_I[3] = array('style' => '', 'tt' => '3', 'noidung' => 'Xã loại 3',);
            $m_xl3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3]['solieu'] = [
                'soluongdonvi_2k' => $m_xl3->count(),
                'qd34_2d' => $m_xl3->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                'tongqd34_2d' => $m_xl3->count() * $m_xl3->sum('soluongdinhbien_2d'),
                // 'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3'),
                // 'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3') * $m_xl3->count(),
                'soluongcanbo_2d' => $m_xl3->sum('soluongcanbo_2d'),
                'hesoluongbq_2d' => $m_xl3->sum('hesoluongbq_2d'),
                'hesophucapbq_2d' => $m_xl3->sum('hesophucapbq_2d'),
                'tyledonggop_2d' => $m_xl3->sum('tyledonggop_2d'),
                'soluongdinhbien_2d' => $m_xl3->count() == 0 ? 0 : getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3'), //số lượng định biên ndd33 lấy mặc định
                'tongsodinhbien_2d' => getSoLuongCanBoDinhMuc('ND33/2023/XA', 'XL3') * $m_xl3->count(),
                // 'soluongdinhbien_2d' => $m_xl3->sum('soluongdinhbien_2d'),
                // 'tongsodinhbien_2d'=>$m_xl3->count() * $m_xl3->sum('soluongdinhbien_2d'),
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
                'soluongdinhbien_2d' => $ar_I[1]['solieu']['soluongdinhbien_2d'] + $ar_I[2]['solieu']['soluongdinhbien_2d'] + $ar_I[3]['solieu']['soluongdinhbien_2d'],
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

            $ar_II[1] = array('phanloai' => 'PL1', 'style' => '', 'tt' => '1', 'noidung' => 'Phường loại 1',);
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

            $ar_II[2] = array('phanloai' => 'PL2', 'style' => '', 'tt' => '2', 'noidung' => 'Phường loại 2',);
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

            $ar_II[3] = array('phanloai' => 'PL3', 'style' => '', 'tt' => '3', 'noidung' => 'Phường loại 3',);
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
                'phanloai' => '',
                'style' => 'font-weight: bold;',
                'tt' => '',
                'noidung' => 'TỔNG SỐ',
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
            return view('reports.thongtu78.huyen.mau2d')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('m_dv', $m_donvi)
                ->with('ar_tong', $ar_tong)
                ->with('ar_tong_phuong', $ar_tong_phuong)
                ->with('a_tong', $a_tong)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2d_tt62(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? '1';
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            // $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
            $m_chitiet = nguonkinhphi_01thang::where('masodv', $m_nguonkp->first()->masodv)->where('mact', '1506672780')->get();
            //dd($m_chitiet);
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
            }
            //chênh lêch = 540.000 * 4.5% = 24300
            $ar_I = array();
            $m_solieu = $m_nguonkp->where('maphanloai', 'MAMNON');
            $ar_I[0] = array(
                'val' => 'BT',
                'tt' => '1',
                'noidung' => 'Mầm non',
                'bienchebosung' => $m_solieu->sum('soluongdinhbien_2d'),
                'bienchecomat' => $m_solieu->count() > 0 ? $m_chitiet->sum('canbo_congtac') : 0,
                'sothang' => $m_solieu->sum('soluongcanbo_2d'),
                'hesoluong' => $m_solieu->sum('hesoluongbq_2d'),
                'pckhuvuc' => $m_solieu->sum('hesophucapbq_2d'),
                'pcudn' => $m_solieu->sum('tyledonggop_2d'),
                'pcthuhut' => $m_solieu->sum('quyluonggiam_2k'),
                'pckhac' => $m_solieu->sum('soluonggiam_2k'),
            );
            //Tính toán số liệu
            $ar_I[0]['tongpc'] = round(($ar_I[0]['pckhuvuc'] + $ar_I[0]['pcudn'] + $ar_I[0]['pcthuhut'] + $ar_I[0]['pckhac']) * $ar_I[0]['hesoluong'], 3);
            $ar_I[0]['donggop'] = round($ar_I[0]['hesoluong'] * 0.235, 3);
            $ar_I[0]['tongcong'] = round($ar_I[0]['hesoluong'] + $ar_I[0]['tongpc'], 3);
            $ar_I[0]['nhucau'] = round($ar_I[0]['bienchecomat'] * $ar_I[0]['tongcong'] * 3100000, 3);

            //
            $m_solieu = $m_nguonkp->where('maphanloai', 'TIEUHOC');
            $ar_I[1] = array(
                'val' => 'BT',
                'tt' => '2',
                'noidung' => 'Tiểu học',
                'bienchebosung' => $m_solieu->sum('soluongdinhbien_2d'),
                'bienchecomat' => $m_solieu->count() > 0 ? $m_chitiet->sum('canbo_congtac') : 0,
                'sothang' => $m_solieu->sum('soluongcanbo_2d'),
                'hesoluong' => $m_solieu->sum('hesoluongbq_2d'),
                'pckhuvuc' => $m_solieu->sum('hesophucapbq_2d'),
                'pcudn' => $m_solieu->sum('tyledonggop_2d'),
                'pcthuhut' => $m_solieu->sum('quyluonggiam_2k'),
                'pckhac' => $m_solieu->sum('soluonggiam_2k'),

            );
            //Tính toán số liệu
            $ar_I[1]['tongpc'] = round(($ar_I[1]['pckhuvuc'] + $ar_I[1]['pcudn'] + $ar_I[1]['pcthuhut'] + $ar_I[1]['pckhac']) * $ar_I[1]['hesoluong'], 3);
            $ar_I[1]['donggop'] = round($ar_I[1]['hesoluong'] * 0.235, 3);
            $ar_I[1]['tongcong'] = round($ar_I[1]['hesoluong'] + $ar_I[1]['tongpc'], 3);
            $ar_I[1]['nhucau'] = round($ar_I[1]['bienchecomat'] * $ar_I[1]['tongcong'] * 3100000, 3);
            //
            $m_solieu = $m_nguonkp->where('maphanloai', 'THCS');
            $ar_I[2] = array(
                'val' => 'BT',
                'tt' => '3',
                'noidung' => 'Trung học cơ sở',
                'bienchebosung' => $m_solieu->sum('soluongdinhbien_2d'),
                'sothang' => $m_solieu->sum('soluongcanbo_2d'),
                'bienchecomat' => $m_solieu->count() > 0 ? $m_chitiet->sum('canbo_congtac') : 0,
                'hesoluong' => $m_solieu->sum('hesoluongbq_2d'),
                'pckhuvuc' => $m_solieu->sum('hesophucapbq_2d'),
                'pcudn' => $m_solieu->sum('tyledonggop_2d'),
                'pcthuhut' => $m_solieu->sum('quyluonggiam_2k'),
                'pckhac' => $m_solieu->sum('soluonggiam_2k'),

            );
            //Tính toán số liệu
            $ar_I[2]['tongpc'] = round(($ar_I[2]['pckhuvuc'] + $ar_I[2]['pcudn'] + $ar_I[2]['pcthuhut'] + $ar_I[2]['pckhac']) * $ar_I[2]['hesoluong'], 3);
            $ar_I[2]['donggop'] = round($ar_I[2]['hesoluong'] * 0.235, 3);
            $ar_I[2]['tongcong'] = round($ar_I[2]['hesoluong'] + $ar_I[2]['tongpc'], 3);
            $ar_I[2]['nhucau'] = round($ar_I[2]['bienchecomat'] * $ar_I[2]['tongcong'] * 3100000, 3);
            //
            $m_solieu = $m_nguonkp->where('maphanloai', 'THPT');
            $ar_I[3] = array(
                'val' => 'BT',
                'tt' => '4',
                'noidung' => 'Trung học phổ thông',
                'sothang' => $m_solieu->sum('soluongcanbo_2d'),
                'bienchebosung' => $m_solieu->sum('soluongdinhbien_2d'),
                'bienchecomat' => $m_solieu->count() > 0 ? $m_chitiet->sum('canbo_congtac') : 0,
                'hesoluong' => $m_solieu->sum('hesoluongbq_2d'),
                'pckhuvuc' => $m_solieu->sum('hesophucapbq_2d'),
                'pcudn' => $m_solieu->sum('tyledonggop_2d'),
                'pcthuhut' => $m_solieu->sum('quyluonggiam_2k'),
                'pckhac' => $m_solieu->sum('soluonggiam_2k'),
            );
            //Tính toán số liệu
            $ar_I[3]['tongpc'] = round(($ar_I[3]['pckhuvuc'] + $ar_I[3]['pcudn'] + $ar_I[3]['pcthuhut'] + $ar_I[3]['pckhac']) * $ar_I[3]['hesoluong'], 3);
            $ar_I[3]['donggop'] = round($ar_I[3]['hesoluong'] * 0.235, 3);
            $ar_I[3]['tongcong'] = round($ar_I[3]['hesoluong'] + $ar_I[3]['tongpc'], 3);
            $ar_I[3]['nhucau'] = round($ar_I[3]['bienchecomat'] * $ar_I[3]['tongcong'] * 3100000, 3);

            //Tổng cộng:
            $ar_Tong = [
                'bienchebosung' => $ar_I[0]['bienchebosung'] + $ar_I[1]['bienchebosung'] + $ar_I[2]['bienchebosung'] + $ar_I[3]['bienchebosung'],
                'bienchecomat' => $ar_I[0]['bienchecomat'] + $ar_I[1]['bienchecomat'] + $ar_I[2]['bienchecomat'] + $ar_I[3]['bienchecomat'],
                'tongcong' => $ar_I[0]['tongcong'] + $ar_I[1]['tongcong'] + $ar_I[2]['tongcong'] + $ar_I[3]['tongcong'],
                'hesoluong' => $ar_I[0]['hesoluong'] + $ar_I[1]['hesoluong'] + $ar_I[2]['hesoluong'] + $ar_I[3]['hesoluong'],
                'tongpc' => $ar_I[0]['tongpc'] + $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'] + $ar_I[3]['tongpc'],
                'pckhuvuc' => $ar_I[0]['pckhuvuc'] + $ar_I[1]['pckhuvuc'] + $ar_I[2]['pckhuvuc'] + $ar_I[3]['pckhuvuc'],
                'pcudn' => $ar_I[0]['pcudn'] + $ar_I[1]['pcudn'] + $ar_I[2]['pcudn'] + $ar_I[3]['pcudn'],
                'pcthuhut' => $ar_I[0]['pcthuhut'] + $ar_I[1]['pcthuhut'] + $ar_I[2]['pcthuhut'] + $ar_I[3]['pcthuhut'],
                'pckhac' => $ar_I[0]['pckhac'] + $ar_I[1]['pckhac'] + $ar_I[2]['pckhac'] + $ar_I[3]['pckhac'],
                'donggop' => $ar_I[0]['donggop'] + $ar_I[1]['donggop'] + $ar_I[2]['donggop'] + $ar_I[3]['donggop'],
                'nhucau' => $ar_I[0]['nhucau'] + $ar_I[1]['nhucau'] + $ar_I[2]['nhucau'] + $ar_I[3]['nhucau'],
            ];
            //dd($m_donvi);
            return view('reports.nghidinh73.donvi.mau2d')
                ->with('ar_I', $ar_I)
                ->with('ar_Tong', $ar_Tong)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('m_thongtu', $m_thongtu)
                ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
        } else
            return view('errors.notlogin');
    }

    function mau2dd_tt62(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? '1';
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();

            //dd($m_chitiet);
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->phanloainguon = $m_donvi->phanloainguon;
            }

            $ar_I = array();
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN', 'DDT']);
            $ar_I[0] = array(
                'val' => 'BT',
                'tt' => 'I',
                'noidung' => 'Quản lý nhà nước',
                'bienche2023' => $m_solieu->sum('tongsonguoi2015'),
                'quyluong2023' => $m_solieu->sum('quyluong'),
                'bienche2024' => $m_solieu->sum('tongsonguoi2017'),
                'quyluong2024' => $m_solieu->sum('quyluonghientai_2dd'),
                'tietkiemkhac' => $m_solieu->sum('kinhphitietkiem_2dd'),
            );
            //Tính toán số liệu
            $ar_I[0]['tietkiem01thang'] = $ar_I[0]['quyluong2024'] - $ar_I[0]['quyluong2023'];
            $ar_I[0]['tongtietkiem'] = $ar_I[0]['tietkiem01thang'] * 12 + $ar_I[0]['tietkiemkhac'];
            $ar_I[0]['kinhphigiam'] = round($ar_I[0]['tongtietkiem'] / 2, 3);
            //           
            $ar_I[1] = array(
                'val' => 'BT',
                'tt' => 'II',
                'noidung' => 'Sự nghiệp công lập',
                'bienche2023' => 0,
                'quyluong2023' => 0,
                'bienche2024' => 0,
                'quyluong2024' => 0,
                'tietkiemkhac' => 0,
                'tietkiem01thang' => 0,
                'tongtietkiem' => 0,
                'kinhphigiam' => 0,
            );
            //
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['GD', 'DT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT'])->where('phanloainguon', 'CTXMP');
            $ar_I[2] = array(
                'val' => 'BT',
                'tt' => '1',
                'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên',
                'bienche2023' => $m_solieu->sum('tongsonguoi2015'),
                'quyluong2023' => $m_solieu->sum('quyluong'),
                'bienche2024' => $m_solieu->sum('tongsonguoi2017'),
                'quyluong2024' => $m_solieu->sum('quyluonghientai_2dd'),
                'tietkiemkhac' => $m_solieu->sum('kinhphitietkiem_2dd'),

            );
            //Tính toán số liệu
            $ar_I[2]['tietkiem01thang'] = $ar_I[2]['quyluong2024'] - $ar_I[2]['quyluong2023'];
            $ar_I[2]['tongtietkiem'] = $ar_I[2]['tietkiem01thang'] * 12 + $ar_I[2]['tietkiemkhac'];
            $ar_I[2]['kinhphigiam'] = round($ar_I[2]['tongtietkiem'] / 2, 3);
            //
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['GD', 'DT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT'])->where('phanloainguon', 'CTX');
            $ar_I[3] = array(
                'val' => 'BT',
                'tt' => '2',
                'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên',
                'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên',
                'bienche2023' => $m_solieu->sum('tongsonguoi2015'),
                'quyluong2023' => $m_solieu->sum('quyluong'),
                'bienche2024' => $m_solieu->sum('tongsonguoi2017'),
                'quyluong2024' => $m_solieu->sum('quyluonghientai_2dd'),
                'tietkiemkhac' => $m_solieu->sum('kinhphitietkiem_2dd'),
            );
            //Tính toán số liệu
            $ar_I[3]['tietkiem01thang'] = $ar_I[3]['quyluong2024'] - $ar_I[3]['quyluong2023'];
            $ar_I[3]['tongtietkiem'] = $ar_I[3]['tietkiem01thang'] * 12 + $ar_I[3]['tietkiemkhac'];
            $ar_I[3]['kinhphigiam'] = round($ar_I[3]['tongtietkiem'] / 2, 3);

            //Tính phần II
            $ar_I[1]['bienche2023'] = $ar_I[2]['bienche2023'] + $ar_I[3]['bienche2023'];
            $ar_I[1]['quyluong2023'] = $ar_I[2]['quyluong2023'] + $ar_I[3]['quyluong2023'];
            $ar_I[1]['bienche2024'] = $ar_I[2]['bienche2024'] + $ar_I[3]['bienche2024'];
            $ar_I[1]['quyluong2024'] = $ar_I[2]['quyluong2024'] + $ar_I[3]['quyluong2024'];
            $ar_I[1]['tietkiemkhac'] = $ar_I[2]['tietkiemkhac'] + $ar_I[3]['tietkiemkhac'];
            $ar_I[1]['tietkiem01thang'] = $ar_I[2]['tietkiem01thang'] + $ar_I[3]['tietkiem01thang'];
            $ar_I[1]['tongtietkiem'] = $ar_I[2]['tongtietkiem'] + $ar_I[3]['tongtietkiem'];
            $ar_I[1]['kinhphigiam'] = $ar_I[2]['kinhphigiam'] + $ar_I[3]['kinhphigiam'];
            //Tổng cộng:
            $ar_Tong = [
                'bienche2023' => $ar_I[0]['bienche2023'] + $ar_I[1]['bienche2023'],
                'quyluong2023' => $ar_I[0]['quyluong2023'] + $ar_I[1]['quyluong2023'],
                'bienche2024' => $ar_I[0]['bienche2024'] + $ar_I[1]['bienche2024'],
                'quyluong2024' => $ar_I[0]['quyluong2024'] + $ar_I[1]['quyluong2024'],
                'tietkiemkhac' => $ar_I[0]['tietkiemkhac'] + $ar_I[1]['tietkiemkhac'],
                'tietkiem01thang' => $ar_I[0]['tietkiem01thang'] + $ar_I[1]['tietkiem01thang'],
                'tongtietkiem' => $ar_I[0]['tongtietkiem'] + $ar_I[1]['tongtietkiem'],
                'kinhphigiam' => $ar_I[0]['kinhphigiam'] + $ar_I[1]['kinhphigiam'],
            ];
            //dd($m_donvi);
            return view('reports.nghidinh73.donvi.mau2dd')
                ->with('ar_I', $ar_I)
                ->with('ar_Tong', $ar_Tong)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('m_thongtu', $m_thongtu)
                ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
        } else
            return view('errors.notlogin');
    }

    function mau2e_tt62(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? '1';
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();

            //dd($m_chitiet);
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->phanloainguon = $m_donvi->phanloainguon;
            }

            $ar_I = array();
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['GD', 'DT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT'])->where('phanloainguon', 'CHITXDT');
            $ar_I[0] = array(
                'val' => 'BT',
                'tt' => '1',
                'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư',
                'bienche2023' => $m_solieu->sum('tongsodonvi1'),
                'bienche2024' => $m_solieu->sum('tongsodonvi2'),
                'tietkiem01thang' => $m_solieu->sum('quy_tuchu'),
                'tietkiem2024' => $m_solieu->sum('hesoluong_2i'),
                'kinhphigiam' =>  round($m_solieu->sum('hesoluong_2i') / 2, 3),
            );
            // 
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['GD', 'DT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT'])->where('phanloainguon', 'CTX');
            $ar_I[1] = array(
                'val' => 'BT',
                'tt' => '2',
                'noidung' => 'Đơn vị đảm bảo chi thường xuyên',
                'bienche2023' => $m_solieu->sum('tongsodonvi1'),
                'bienche2024' => $m_solieu->sum('tongsodonvi2'),
                'tietkiem01thang' => $m_solieu->sum('quy_tuchu'),
                'tietkiem2024' => $m_solieu->sum('hesoluong_2i'),
                'kinhphigiam' =>  round($m_solieu->sum('hesoluong_2i') / 2, 3),
            );
            //
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['GD', 'DT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT'])->where('phanloainguon', 'CTXMP');
            $ar_I[2] = array(
                'val' => 'BT',
                'tt' => '3',
                'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên',
                'bienche2023' => $m_solieu->sum('tongsodonvi1'),
                'bienche2024' => $m_solieu->sum('tongsodonvi2'),
                'tietkiem01thang' => $m_solieu->sum('quy_tuchu'),
                'tietkiem2024' => $m_solieu->sum('hesoluong_2i'),
                'kinhphigiam' =>  round($m_solieu->sum('hesoluong_2i') / 2, 3),

            );
            //
            $m_solieu = $m_nguonkp->wherein('linhvuchoatdong', ['GD', 'DT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT'])->where('phanloainguon', 'NGANSACH');
            $ar_I[3] = array(
                'val' => 'BT',
                'tt' => '4',
                'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên',
                'bienche2023' => $m_solieu->sum('tongsodonvi1'),
                'bienche2024' => $m_solieu->sum('tongsodonvi2'),
                'tietkiem01thang' => $m_solieu->sum('quy_tuchu'),
                'tietkiem2024' => $m_solieu->sum('hesoluong_2i'),
                'kinhphigiam' =>  round($m_solieu->sum('hesoluong_2i') / 2, 3),
            );

            //Tổng cộng:
            $ar_Tong = [
                'bienche2023' => $ar_I[0]['bienche2023'] + $ar_I[1]['bienche2023'] + $ar_I[2]['bienche2023'] + $ar_I[3]['bienche2023'],
                'bienche2024' => $ar_I[0]['bienche2024'] + $ar_I[1]['bienche2024'] + $ar_I[2]['bienche2023'] + $ar_I[3]['bienche2023'],
                'tietkiem01thang' => $ar_I[0]['tietkiem01thang'] + $ar_I[1]['tietkiem01thang'] + $ar_I[2]['tietkiem01thang'] + $ar_I[3]['tietkiem01thang'],
                'tietkiem2024' => $ar_I[0]['tietkiem2024'] + $ar_I[1]['tietkiem2024'] + $ar_I[2]['tietkiem2024'] + $ar_I[3]['tietkiem2024'],
                'kinhphigiam' => $ar_I[0]['kinhphigiam'] + $ar_I[1]['kinhphigiam'] + $ar_I[2]['kinhphigiam'] + $ar_I[3]['kinhphigiam'],
            ];
            //dd($m_donvi);
            return view('reports.nghidinh73.donvi.mau2e')
                ->with('ar_I', $ar_I)
                ->with('ar_Tong', $ar_Tong)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('m_thongtu', $m_thongtu)
                ->with('pageTitle', 'BÁO CÁO NGUỒN THỰC HIỆN CCTL');
        } else
            return view('errors.notlogin');
    }

    function mau2e(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;

            //Tạm làm để lấy lương cơ bản nhân
            switch ($inputs['sohieu']) {
                case '732024nd_cp': {
                        $luongcb_cot4 = '1800000'; //lương cột 4 trên báo cáo
                        $luongcb_cot5 = '2340000'; //lương cột 5 trên báo cáo
                        break;
                    }
                default: {
                        $luongcb_cot4 = '1490000'; //lương cột 4 trên báo cáo
                        $luongcb_cot5 = '1490000'; //lương cột 5 trên báo cáo
                        break;
                    }
            }
            //dd($m_nguonkp);
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
                'quyluong34' => round($m_nguon_1->count() * 16 * $luongcb_cot4),
                'quyluong33' => round($m_nguon_1->count() * 21 * $luongcb_cot5),
            ]);
            $ar_I[1]['solieu']['tong'] = ($ar_I[1]['solieu']['quyluong33'] - $ar_I[1]['solieu']['quyluong34']) * 5;
            //

            $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                'tdv' => $m_nguon_2->count(),
                'mk' => 13.7,
                'mk2' => 18,
                'quyluong34' => round($m_nguon_2->count() * 13.7 * $luongcb_cot4),
                'quyluong33' => round($m_nguon_2->count() * 18 * $luongcb_cot5),
            ]);
            $ar_I[2]['solieu']['tong'] = ($ar_I[2]['solieu']['quyluong33'] - $ar_I[2]['solieu']['quyluong34']) * 5;
            //

            $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                'tdv' => $m_nguon_3->count(),
                'mk' => 11.4,
                'mk2' => 15,
                'quyluong34' => round($m_nguon_3->count() * 11.4 * $luongcb_cot4),
                'quyluong33' => round($m_nguon_3->count() * 15 * $luongcb_cot5),
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
                'quyluong34' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sothonbiengioi_2d') * 6 * $luongcb_cot5),
            ]);
            $ar_I[6]['solieu']['tong'] = ($ar_I[6]['solieu']['quyluong33'] - $ar_I[6]['solieu']['quyluong34']) * 5;

            //Tổ dân phố thuộc xã biên giới, hải đảo
            $ar_I[7] = array('val' => '', 'tt' => '', 'noidung' => '- Tổ dân phố thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotodanphobiengioi_2d'),
                'mk' => 0,
                'mk2' => 6,
                'quyluong34' => 0,
                'quyluong33' => round($m_nguonkp->sum('sotodanphobiengioi_2d') * 6 * $luongcb_cot5),
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
                'quyluong34' => round($m_nguonkp->sum('sothon350hgd_2d') * 5 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sothon350hgd_2d') * 6 * $luongcb_cot5),
            ]);
            $ar_I[9]['solieu']['tong'] = ($ar_I[9]['solieu']['quyluong33'] - $ar_I[9]['solieu']['quyluong34']) * 5;

            //500 hộ trở lên
            $ar_I[10] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotodanpho500hgd_2d'),
                'mk' => 3,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 3 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sotodanpho500hgd_2d') * 6 * $luongcb_cot5),
            ]);
            $ar_I[10]['solieu']['tong'] = ($ar_I[10]['solieu']['quyluong33'] - $ar_I[10]['solieu']['quyluong34']) * 5;

            //Tổ dân phố thuộc xã trọng điểm về an ninh
            $ar_I[11] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 3,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sothontrongdiem_2d') * 6 * $luongcb_cot5),
            ]);
            $ar_I[11]['solieu']['tong'] = ($ar_I[11]['solieu']['quyluong33'] - $ar_I[11]['solieu']['quyluong34']) * 5;

            //Tổ dân phố chuyển từ thôn
            $ar_I[12] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sochuyentuthon350hgd_2d'),
                'mk' => 3,
                'mk2' => 6,
                'quyluong34' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 3 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sochuyentuthon350hgd_2d') * 6 * $luongcb_cot5),
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
                'quyluong34' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sothonconlai_2d') * 4.5 * $luongcb_cot5),
            ]);
            $ar_I[14]['solieu']['tong'] = ($ar_I[14]['solieu']['quyluong33'] - $ar_I[14]['solieu']['quyluong34']) * 5;
            //Tổ dân phố
            $ar_I[15] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                'mk' => 3,
                'mk2' => 4.5,
                'quyluong34' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * $luongcb_cot4),
                'quyluong33' => round($m_nguonkp->sum('sotoconlai_2d') * 4.5 * $luongcb_cot5),
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

            //dd($a_It);
            if ($inputs['sohieu'] == '732024nd_cp') {
                $view = 'reports.nghidinh73.donvi.mau2e';
            } else {
                $view = 'reports.thongtu78.huyen.mau2e';
            }
            return view($view)
                ->with('m_dv', $m_donvi)
                ->with('m_thongtu', $m_thongtu)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM THEO NGHỊ ĐỊNH 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2g(Request $request)
    {
        //Chưa tính toán số liệu
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;
            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
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
                $model = nguonkinhphi_01thang::where('masodv', $inputs['masodv'])->first();
                $chitiet->tonghesophucap = isset($model) ? $model->pccv + $model->pcvk : 0;
                $chitiet->tongheso = (isset($model) ? $model->heso : 0) + $chitiet->tonghesophucap;
                $chitiet->heso = isset($model) ? $model->heso : 0;
                $chitiet->pccv = isset($model) ? $model->pccv : 0;
                $chitiet->pcvk = isset($model) ? $model->pcvk : 0;
            }

            // dd($m_nguonkp);


            // dd($model);
            $m_dshuyen = dmdonvibaocao::where('level', 'H')->get();

            //dd($m_nguonkp);
            return view('reports.thongtu78.huyen.mau2g')
                ->with('m_chitiet', $m_nguonkp)
                ->with('m_dshuyen', $m_dshuyen)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP THU HÚT TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau4a(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {

            $inputs = $request->all();
            // dd($inputs);
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;
            if ($m_thongtu->masobaocao != "ND73_2024") {
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
                //Số liệu đơn vị
                foreach ($m_nguonkp as $chitiet) {
                    $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                    //dd($chitiet);
                    //Tinh số liệu 2b 
                    $chitiet->nhucau2b = round($chitiet->quy1_tong + $chitiet->quy2_tong + $chitiet->quy3_tong);
                    //Tính số liệu 2d
                    if ($chitiet->maphanloai == 'KVXP') {
                        // $sotien = 1490000 * 5;
                        //Tạm làm để lấy số tiền, tính chênh lệch phân loại xã chạy cho nđ73
                        switch ($inputs['sohieu']) {
                            case '732024nd_cp': {
                                    $nd33 = getMucKhoanPhuCapXa('ND33/2023', $chitiet->phanloaixa) * 2340000;
                                    $nd34 = getMucKhoanPhuCapXa('ND34/2019', $chitiet->phanloaixa) * 1800000;
                                    $chenhlech_plxa = ($nd33 - $nd34) * 5;
                                    //Xã biên giới;
                                    $xa_nd34 = $chitiet->sothonbiengioi_2d * 5 * 1800000;
                                    $xa_nd33 = $chitiet->sothonbiengioi_2d * 6 * 2340000;
                                    $tdp_nd34 = $chitiet->sotodanphobiengioi_2d * 6 * 1800000;
                                    $tdp_nd33 = $chitiet->sotodanphobiengioi_2d * 6 * 2340000;
                                    $chenhlech_xabg = (($xa_nd33 - $xa_nd34) + ($tdp_nd33 - $tdp_nd34)) * 5;

                                    //Số xã có 350 HGD trở lên
                                    $thon350hgd_34 = $chitiet->sothon350hgd_2d * 5 * 1800000;
                                    $thon350hgd_33 = $chitiet->sothon350hgd_2d * 6 * 2340000;
                                    $thon350hgd = ($thon350hgd_33 -  $thon350hgd_34) * 5;

                                    $tdp500hgd_34 = $chitiet->sotodanpho500hgd_2d * 3 * 1800000;
                                    $tdp500hgd_33 = $chitiet->sotodanpho500hgd_2d * 6 * 2340000;
                                    $ttdp500hgd = ($tdp500hgd_33 -  $tdp500hgd_34) * 5;

                                    $thongtrongdiem_34 = $chitiet->sothontrongdiem_2d * 3 * 1800000;
                                    $thontrongdiem_33 = $chitiet->sothontrongdiem_2d * 6 * 2340000;
                                    $thontrongdiem = ($thontrongdiem_33 -  $thongtrongdiem_34) * 5;

                                    $sochuyentuthon350hgd_34 = $chitiet->sochuyentuthon350hgd_2d * 3 * 1800000;
                                    $sochuyentuthon350hgd_33 = $chitiet->sochuyentuthon350hgd_2d * 6 * 2340000;
                                    $sochuyentuthon350hgd = ($sochuyentuthon350hgd_33 -  $sochuyentuthon350hgd_34) * 5;

                                    $chenhlech_xahgd = $thon350hgd + $ttdp500hgd + $thontrongdiem + $sochuyentuthon350hgd;
                                    //Số xã còn lại
                                    $sothonconlai_34 = $chitiet->sothonconlai_2d * 3 * 1800000;
                                    $sothonconlai_33 = $chitiet->sothonconlai_2d * 4.5 * 2340000;
                                    $sothonconlai = ($sothonconlai_33 -  $sothonconlai_34) * 5;

                                    $sotoconlai_34 = $chitiet->sotoconlai_2d * 3 * 1800000;
                                    $sotoconlai_33 = $chitiet->sotoconlai_2d * 4.5 * 2340000;
                                    $sotoconlai = ($sotoconlai_33 -  $sotoconlai_34) * 5;

                                    $chenhlech_xacl = $sothonconlai + $sotoconlai;
                                    //2e
                                    $solieu_2e = $chenhlech_plxa + $chenhlech_xabg + $chenhlech_xahgd + $chenhlech_xacl;
                                    break;
                                }
                            default: {
                                    $sotien = 1490000 * 5;
                                    $chenhlech_plxa = (getMucKhoanPhuCapXa('ND33/2023', $chitiet->phanloaixa) - getMucKhoanPhuCapXa('ND34/2019', $chitiet->phanloaixa)) * $sotien;

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
                                    break;
                                }
                        }
                        $solieu_2d = round($chitiet->quyluonggiam_2k * 5);
                        //Tính số liệu 2e                    
                        //chênh lệch xã
                        // $chenhlech_plxa = (getMucKhoanPhuCapXa('ND33/2023', $chitiet->phanloaixa) - getMucKhoanPhuCapXa('ND34/2019', $chitiet->phanloaixa)) * $sotien;
                        //Xã biên giới
                        // $chenhlech_xabg = $chitiet->sothonbiengioi_2d * (6 - 5) * $sotien +
                        //     $chitiet->sotodanphobiengioi_2d * (6 - 0) * $sotien;
                        // //Số xã có 350 HGD trở lên
                        // $chenhlech_xahgd = $chitiet->sothon350hgd_2d * (6 - 5) * $sotien +
                        //     $chitiet->sotodanpho500hgd_2d * (6 - 3) * $sotien +
                        //     $chitiet->sothontrongdiem_2d * (6 - 3) * $sotien +
                        //     $chitiet->sochuyentuthon350hgd_2d * (6 - 3) * $sotien;
                        // //Số xã còn lại
                        // $chenhlech_xacl = $chitiet->sothonconlai_2d * (4.5 - 3) * $sotien +
                        //     $chitiet->sotoconlai_2d * (4.5 - 3) * $sotien;
                        // //2e
                        // $solieu_2e = $chenhlech_plxa + $chenhlech_xabg + $chenhlech_xahgd + $chenhlech_xacl;
                        $chitiet->nhucau_4a = $solieu_2d + $solieu_2e;

                        //số liệu 2c

                        if ($chitiet->phanloaixa == 'XL1') {
                            // $clt7 = round(16 * 310000);
                            // $cl5t = round(21 * 310000 * 5);
                            $clt7 = round(16 * $m_thongtu->chenhlech);
                            $cl5t = round(21 * $m_thongtu->chenhlech * 5);
                        } else if ($chitiet->phanloaixa == 'XL2') {
                            // $clt7 = round(13.7 * 310000);
                            // $cl5t = round(18 * 310000 * 5);
                            $clt7 = round(13.7 * $m_thongtu->chenhlech);
                            $cl5t = round(18 * $m_thongtu->chenhlech * 5);
                        } else if ($chitiet->phanloaixa == 'XL3') {
                            // $clt7 = round(11.4 * 310000);
                            // $cl5t = round(15 * 310000 * 5);
                            $clt7 = round(11.4 * $m_thongtu->chenhlech);
                            $cl5t = round(15 * $m_thongtu->chenhlech * 5);
                        }
                        $solieu_plxa = $clt7 + $cl5t;
                        //Số xã biên giới
                        // $solieu_xabiengioi_clt7 = round($chitiet->sothonbiengioi_2d * 5 * 310000);
                        // $solieu_xabiengioi_cl5t = round($chitiet->sothonbiengioi_2d * 6 * 310000 * 5);
                        $solieu_xabiengioi_clt7 = round($chitiet->sothonbiengioi_2d * 5 * $m_thongtu->chenhlech);
                        $solieu_xabiengioi_cl5t = round($chitiet->sothonbiengioi_2d * 6 * $m_thongtu->chenhlech * 5);
                        $solieu_xabiengioi = $solieu_xabiengioi_clt7 +  $solieu_xabiengioi_cl5t;
                        //số thôn có 350 hộ trở lên
                        // $soho_350_clt7 = round($chitiet->sothon350hgd_2d * 5 * 310000);
                        // $soho_350_cl5t = round($chitiet->sothon350hgd_2d * 6 * 310000 * 5);
                        $soho_350_clt7 = round($chitiet->sothon350hgd_2d * 5 * $m_thongtu->chenhlech);
                        $soho_350_cl5t = round($chitiet->sothon350hgd_2d * 6 * $m_thongtu->chenhlech * 5);
                        $soho_350 = $soho_350_clt7 + $soho_350_cl5t;
                        // $soho_500_clt7 = round($chitiet->sotodanpho500hgd_2d * 3 * 310000);
                        // $soho_500_cl5t = round($chitiet->sotodanpho500hgd_2d * 6 * 310000 * 5);
                        $soho_500_clt7 = round($chitiet->sotodanpho500hgd_2d * 3 * $m_thongtu->chenhlech);
                        $soho_500_cl5t = round($chitiet->sotodanpho500hgd_2d * 6 * $m_thongtu->chenhlech * 5);
                        $soho_500 = $soho_500_clt7 + $soho_500_cl5t;
                        //tổ dân phố trọng điểm an ninh
                        // $sothon_trongdiem_clt7 = round($chitiet->sothontrongdiem_2d * 3 * 310000);
                        // $sothon_trongdiem_cl5t = round($chitiet->sothontrongdiem_2d * 6 * 310000 * 5);
                        $sothon_trongdiem_clt7 = round($chitiet->sothontrongdiem_2d * 3 * $m_thongtu->chenhlech);
                        $sothon_trongdiem_cl5t = round($chitiet->sothontrongdiem_2d * 6 * $m_thongtu->chenhlech * 5);
                        $sothon_trongdiem = $sothon_trongdiem_clt7 + $sothon_trongdiem_cl5t;
                        //tổ dân phố chuyển từ thôn
                        // $sochuyentuthon_clt7 = round($chitiet->sochuyentuthon350hgd_2d * 3 * 310000);
                        // $sochuyentuthon_cl5t = round($chitiet->sochuyentuthon350hgd_2d * 6 * 310000 * 5);
                        $sochuyentuthon_clt7 = round($chitiet->sochuyentuthon350hgd_2d * 3 * $m_thongtu->chenhlech);
                        $sochuyentuthon_cl5t = round($chitiet->sochuyentuthon350hgd_2d * 6 * $m_thongtu->chenhlech * 5);
                        $sochuyentuthon = $sochuyentuthon_clt7 +  $sochuyentuthon_cl5t;
                        //Thôn còn lại
                        // $sothonconlai_clt7 = round($chitiet->sothonconlai_2d * 3 * 310000);
                        // $sothonconlai_cl5t = round($chitiet->sothonconlai_2d * 4.5 * 310000 * 5);
                        $sothonconlai_clt7 = round($chitiet->sothonconlai_2d * 3 * $m_thongtu->chenhlech);
                        $sothonconlai_cl5t = round($chitiet->sothonconlai_2d * 4.5 * $m_thongtu->chenhlech * 5);
                        $sothonconlai =  $sothonconlai_clt7 +  $sothonconlai_cl5t;
                        //tổ dân phố còn lại
                        // $sotoconlai_clt7 = round($chitiet->sotoconlai_2d * 3 * 310000);
                        // $sotoconlai_cl5t = round($chitiet->sotoconlai_2d * 4.5 * 310000 * 5);
                        $sotoconlai_clt7 = round($chitiet->sotoconlai_2d * 3 * $m_thongtu->chenhlech);
                        $sotoconlai_cl5t = round($chitiet->sotoconlai_2d * 4.5 * $m_thongtu->chenhlech * 5);
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
                // dd($m_chitiet);
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
                    'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[3]['sotien'] + $a_A[7]['sotien'] + $a_A[8]['sotien']),
                    'BI' => array_sum(array_column($a_BI, 'sotien')),
                    'BII' => array_sum(array_column($a_BII, 'sotien'))
                );
                // dd($a_A);

                return view('reports.thongtu78.huyen.mau4a')
                    ->with('model', $m_nguonkp)
                    ->with('a_A', $a_A)
                    ->with('a_BI', $a_BI)
                    ->with('a_BII', $a_BII)
                    ->with('a_TC', $a_TC)
                    ->with('m_dv', $m_donvi)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
            } else {
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
                //Số liệu đơn vị
                foreach ($m_nguonkp as $chitiet) {
                    $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                    //Tinh số liệu 2b 
                    $chitiet->nhucau2b = round($chitiet->quy1_tong + $chitiet->quy2_tong + $chitiet->quy3_tong);

                    if ($chitiet->maphanloai == 'KVXP') {
                        // $sotien = 1490000 * 5;
                        //Tạm làm để lấy số tiền, tính chênh lệch phân loại xã chạy cho nđ73
                        $nd33 = getMucKhoanPhuCapXa('ND33/2023', $chitiet->phanloaixa) * 2340000;
                        $nd34 = getMucKhoanPhuCapXa('ND34/2019', $chitiet->phanloaixa) * 1800000;
                        $chenhlech_plxa = ($nd33 - $nd34) * 5;
                        //Xã biên giới;
                        $xa_nd34 = $chitiet->sothonbiengioi_2d * 5 * 1800000;
                        $xa_nd33 = $chitiet->sothonbiengioi_2d * 6 * 2340000;
                        $tdp_nd34 = $chitiet->sotodanphobiengioi_2d * 6 * 1800000;
                        $tdp_nd33 = $chitiet->sotodanphobiengioi_2d * 6 * 2340000;
                        $chenhlech_xabg = (($xa_nd33 - $xa_nd34) + ($tdp_nd33 - $tdp_nd34)) * 5;

                        //Số xã có 350 HGD trở lên
                        $thon350hgd_34 = $chitiet->sothon350hgd_2d * 5 * 1800000;
                        $thon350hgd_33 = $chitiet->sothon350hgd_2d * 6 * 2340000;
                        $thon350hgd = ($thon350hgd_33 -  $thon350hgd_34) * 5;

                        $tdp500hgd_34 = $chitiet->sotodanpho500hgd_2d * 3 * 1800000;
                        $tdp500hgd_33 = $chitiet->sotodanpho500hgd_2d * 6 * 2340000;
                        $ttdp500hgd = ($tdp500hgd_33 -  $tdp500hgd_34) * 5;

                        $thongtrongdiem_34 = $chitiet->sothontrongdiem_2d * 3 * 1800000;
                        $thontrongdiem_33 = $chitiet->sothontrongdiem_2d * 6 * 2340000;
                        $thontrongdiem = ($thontrongdiem_33 -  $thongtrongdiem_34) * 5;

                        $sochuyentuthon350hgd_34 = $chitiet->sochuyentuthon350hgd_2d * 3 * 1800000;
                        $sochuyentuthon350hgd_33 = $chitiet->sochuyentuthon350hgd_2d * 6 * 2340000;
                        $sochuyentuthon350hgd = ($sochuyentuthon350hgd_33 -  $sochuyentuthon350hgd_34) * 5;

                        $chenhlech_xahgd = $thon350hgd + $ttdp500hgd + $thontrongdiem + $sochuyentuthon350hgd;
                        //Số xã còn lại
                        $sothonconlai_34 = $chitiet->sothonconlai_2d * 3 * 1800000;
                        $sothonconlai_33 = $chitiet->sothonconlai_2d * 4.5 * 2340000;
                        $sothonconlai = ($sothonconlai_33 -  $sothonconlai_34) * 5;

                        $sotoconlai_34 = $chitiet->sotoconlai_2d * 3 * 1800000;
                        $sotoconlai_33 = $chitiet->sotoconlai_2d * 4.5 * 2340000;
                        $sotoconlai = ($sotoconlai_33 -  $sotoconlai_34) * 5;

                        $chenhlech_xacl = $sothonconlai + $sotoconlai;
                        //2e
                        $solieu_2e = $chenhlech_plxa + $chenhlech_xabg + $chenhlech_xahgd + $chenhlech_xacl;

                        $solieu_2d = round($chitiet->quyluonggiam_2k * 5);
                        //Tính số liệu 2e                    
                        //chênh lệch xã
                        // $chenhlech_plxa = (getMucKhoanPhuCapXa('ND33/2023', $chitiet->phanloaixa) - getMucKhoanPhuCapXa('ND34/2019', $chitiet->phanloaixa)) * $sotien;
                        //Xã biên giới
                        // $chenhlech_xabg = $chitiet->sothonbiengioi_2d * (6 - 5) * $sotien +
                        //     $chitiet->sotodanphobiengioi_2d * (6 - 0) * $sotien;
                        // //Số xã có 350 HGD trở lên
                        // $chenhlech_xahgd = $chitiet->sothon350hgd_2d * (6 - 5) * $sotien +
                        //     $chitiet->sotodanpho500hgd_2d * (6 - 3) * $sotien +
                        //     $chitiet->sothontrongdiem_2d * (6 - 3) * $sotien +
                        //     $chitiet->sochuyentuthon350hgd_2d * (6 - 3) * $sotien;
                        // //Số xã còn lại
                        // $chenhlech_xacl = $chitiet->sothonconlai_2d * (4.5 - 3) * $sotien +
                        //     $chitiet->sotoconlai_2d * (4.5 - 3) * $sotien;
                        // //2e
                        // $solieu_2e = $chenhlech_plxa + $chenhlech_xabg + $chenhlech_xahgd + $chenhlech_xacl;
                        $chitiet->nhucau_4a = $solieu_2d + $solieu_2e;

                        //số liệu 2c
                        //Theo hướng dẫn cho nghị định 33/2024/NĐ-CP thì mức khoán không phân biệt xã, phường XL1 = PL1 ....
                        if ($chitiet->phanloaixa == 'XL1') {
                            $solieu_plxa = round(getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->chenhlech * 6);
                        } else if ($chitiet->phanloaixa == 'XL2') {
                            $solieu_plxa = round(getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->chenhlech * 6);
                        } else if ($chitiet->phanloaixa == 'XL3') {
                            $solieu_plxa = round(getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->chenhlech * 6);
                        } else if ($chitiet->phanloaixa == 'PL1') {
                            $solieu_plxa = round(getMucKhoanPhuCapXa('ND33/2024', 'XL1') * $m_thongtu->chenhlech * 6);
                        } else if ($chitiet->phanloaixa == 'PL2') {
                            $solieu_plxa = round(getMucKhoanPhuCapXa('ND33/2024', 'XL2') * $m_thongtu->chenhlech * 6);
                        } else {
                            $solieu_plxa = round(getMucKhoanPhuCapXa('ND33/2024', 'XL3') * $m_thongtu->chenhlech * 6);
                        }

                        //Số xã biên giới
                        $solieu_xabiengioi = round($chitiet->sothonbiengioi_2d * 6 * $m_thongtu->chenhlech * 6);
                        //số thôn có 350 hộ trở lên
                        $soho_350 = round($chitiet->sothon350hgd_2d * 6 * $m_thongtu->chenhlech * 6);
                        //số thôn có 500 hộ trở lên
                        $soho_500 = round($chitiet->sotodanpho500hgd_2d * 6 * $m_thongtu->chenhlech * 6);
                        //tổ dân phố trọng điểm an ninh
                        $sothon_trongdiem = round($chitiet->sothontrongdiem_2d * 6 * $m_thongtu->chenhlech * 6);
                        //tổ dân phố chuyển từ thôn
                        $sochuyentuthon = round($chitiet->sochuyentuthon350hgd_2d * 6 * $m_thongtu->chenhlech * 6);
                        //Thôn còn lại
                        $sothonconlai = round($chitiet->sothonconlai_2d * 4.5 * $m_thongtu->chenhlech * 6);
                        //tổ dân phố còn lại
                        $sotoconlai = round($chitiet->sotoconlai_2d * 4.5 * $m_thongtu->chenhlech * 6);

                        $thontodanpho = $solieu_xabiengioi +  $soho_350 + $soho_500 + $sothon_trongdiem +  $sochuyentuthon + $sothonconlai +  $sotoconlai;
                        $chitiet->nhucau2c = $solieu_plxa + $thontodanpho;
                    }
                }

                //Phần A
                $a_A = get4a_NĐ73_A();

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
                // dd($m_chitiet);
                //dd($a_A);
                //Phần BI
                $a_B1 = array();
                $a_B1[0] = array('tt' => '1', 'noidung' => 'Tổng nhu cầu kinh phí tăng thêm để thực hiện cải cách tiền lương theo Nghị định số 24/2023/NĐ-CP và Nghị định số 42/2023/NĐ-CP (tính đủ 12 tháng) ', 'sotien' => '0');
                $a_B1[0]['sotien'] = $m_nguonkp->sum('tongnhucau1');

                //Phần B.II
                $a_B2 = array();
                $a_B2[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
                $a_B2[1] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
                $a_B2[2] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
                $a_B2[3] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 75/2024/NĐ-CP', 'sotien' => '0');
                $a_B2[4] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
                $a_B2[5] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
                $a_B2[6] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017', 'sotien' => '0');


                $a_B2[0]['sotien'] = $m_chitiet->where('nhomnhucau', 'BIENCHE')->where('mact', '1506672780')->sum('tongnhucau');
                $a_B2[1]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOCT')->sum('tongnhucau');
                $a_B2[2]['sotien'] = $m_chitiet->where('nhomnhucau', 'HDND')->sum('tongnhucau');
                $a_B2[3]['sotien'] = $m_nguonkp->sum('nhucau2b'); //Lấy dữ liệu mẫu 2b
                // $a_BI[4]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOKCT')->sum('tongnhucau');
                $a_B2[4]['sotien'] =  $m_nguonkp->sum('nhucau2c'); //lấy dữ liệu mẫu 2c
                $a_B2[5]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->wherein('level', ['XA', 'HUYEN'])->sum('tongnhucau');
                $a_B2[6]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->where('level', 'TINH')->sum('tongnhucau');

                // dd($m_chitiet->where('nhomnhucau', 'CAPUY'));

                $a_B3 = array();
                $a_B3[0] = array('tt' => '1', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2024 theo Nghị định số 29/2023/NĐ-CP ngày 03/6/2023 của Chính phủ', 'sotien' => '0');
                $a_B3[1] = array('tt' => '2', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2024 theo Nghị định số 26/2014/NĐ-CP ngày 09/3/2015 của Chỉnh phủ', 'sotien' => '0');
                $a_B3[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí tăng thêm thực hiện chế độ thù lao đối với người đã nghỉ hưu giữ chức danh lãnh đạo Hội đặc thù', 'sotien' => '0');
                $a_B3[3] = array('tt' => '4', 'noidung' => 'Nhu cầu kinh phí tăng thêm thực hiện chế độ trợ cấp lần đầu đến nhận công tác tại vùng ĐBKK, trợ cấp 1 lần khi chuyển công tác ra khỏi vùng ĐBKK theo Nghị định số 76/2019/NĐ-CP ngày 08/10/2019 của Chính phủ', 'sotien' => '0');
                $a_B3[4] = array('tt' => '5', 'noidung' => 'Kinh phí tăng/giảm so với số liệu đã tính định mức chi thường xuyên do thực hiện Nghị định số 33/2023/NĐ-CP ngày 10/6/2023 của Chính phủ', 'sotien' => '0');
                $a_B3[5] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm chi trả chế độ cho biên chế giáo viên được giao năm học 2023-2024 theo Quyết định của Ban Tổ chức Trung ương', 'sotien' => '0');
                $a_B3[6] = array('tt' => '7', 'noidung' => 'Các khoản phụ cấp, trợ cấp khác', 'sotien' => '0');

                $a_B3[0]['sotien'] = $m_nguonkp->sum('tinhgiam');
                $a_B3[1]['sotien'] = $m_nguonkp->sum('nghihuusom');
                $a_B3[2]['sotien'] = $m_nguonkp->sum('kpuudai');
                $a_B3[3]['sotien'] = $m_nguonkp->sum('kinhphigiamxa_4a');
                $a_B3[4]['sotien'] = $m_nguonkp->sum('satnhapdaumoi_4a');
                $a_B3[5]['sotien'] = 0; //do đang lấy của Huyện nhập
                //$a_B3[5]['sotien'] = $m_chitiet_solieu->sum('tong2d'); //mẫu 2d

                $a_B3[6]['sotien'] = $m_nguonkp->sum('nhucau');
                //$a_B3[4]['sotien'] = $m_nguonkp->sum('nhucau_4a');//Lấy 2d + 2e
                // $a_BII[5]['sotien'] = $m_nguonkp->sum('nhucau2c');

                $a_TC = array(
                    'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[3]['sotien'] + $a_A[4]['sotien']
                        + $a_A[8]['sotien'] + $a_A[9]['sotien']),
                    'B1' => array_sum(array_column($a_B1, 'sotien')),
                    'B2' => array_sum(array_column($a_B2, 'sotien')),
                    'B3' => array_sum(array_column($a_B3, 'sotien'))
                );

                // dd($a_A);

                return view('reports.nghidinh73.donvi.mau4a')
                    ->with('model', $m_nguonkp)
                    ->with('a_A', $a_A)
                    ->with('a_B1', $a_B1)
                    ->with('a_B2', $a_B2)
                    ->with('a_B3', $a_B3)
                    ->with('a_TC', $a_TC)
                    ->with('m_dv', $m_donvi)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4b(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();

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

                if ($chitiet->maphanloai == 'KVXP') {
                    if ($chitiet->phanloaixa == 'XL1') {
                        // $clt7 = round(16 * 310000);
                        // $cl5t = round(21 * 310000 * 5);
                        $clt7 = round(16 * $m_thongtu->chenhlech);
                        $cl5t = round(21 * $m_thongtu->chenhlech * 5);
                    } else if ($chitiet->phanloaixa == 'XL2') {
                        // $clt7 = round(13.7 * 310000);
                        // $cl5t = round(18 * 310000 * 5);
                        $clt7 = round(13.7 * $m_thongtu->chenhlech);
                        $cl5t = round(18 * $m_thongtu->chenhlech * 5);
                    } else if ($chitiet->phanloaixa == 'XL3') {
                        // $clt7 = round(11.4 * 310000);
                        // $cl5t = round(15 * 310000 * 5);
                        $clt7 = round(11.4 * $m_thongtu->chenhlech);
                        $cl5t = round(15 * $m_thongtu->chenhlech * 5);
                    }
                    $solieu_plxa = $clt7 + $cl5t;
                    //Số xã biên giới
                    // $solieu_xabiengioi_clt7 = round($chitiet->sothonbiengioi_2d * 5 * 310000);
                    // $solieu_xabiengioi_cl5t = round($chitiet->sothonbiengioi_2d * 6 * 310000 * 5);
                    $solieu_xabiengioi_clt7 = round($chitiet->sothonbiengioi_2d * 5 * $m_thongtu->chenhlech);
                    $solieu_xabiengioi_cl5t = round($chitiet->sothonbiengioi_2d * 6 * $m_thongtu->chenhlech * 5);
                    $solieu_xabiengioi = $solieu_xabiengioi_clt7 +  $solieu_xabiengioi_cl5t;
                    //số thôn có 350 hộ trở lên
                    // $soho_350_clt7 = round($chitiet->sothon350hgd_2d * 5 * 310000);
                    // $soho_350_cl5t = round($chitiet->sothon350hgd_2d * 6 * 310000 * 5);
                    $soho_350_clt7 = round($chitiet->sothon350hgd_2d * 5 * $m_thongtu->chenhlech);
                    $soho_350_cl5t = round($chitiet->sothon350hgd_2d * 6 * $m_thongtu->chenhlech * 5);
                    $soho_350 = $soho_350_clt7 + $soho_350_cl5t;
                    // $soho_500_clt7 = round($chitiet->sotodanpho500hgd_2d * 3 * 310000);
                    // $soho_500_cl5t = round($chitiet->sotodanpho500hgd_2d * 6 * 310000 * 5);
                    $soho_500_clt7 = round($chitiet->sotodanpho500hgd_2d * 3 * $m_thongtu->chenhlech);
                    $soho_500_cl5t = round($chitiet->sotodanpho500hgd_2d * 6 * $m_thongtu->chenhlech * 5);
                    $soho_500 = $soho_500_clt7 + $soho_500_cl5t;
                    //tổ dân phố trọng điểm an ninh
                    // $sothon_trongdiem_clt7 = round($chitiet->sothontrongdiem_2d * 3 * 310000);
                    // $sothon_trongdiem_cl5t = round($chitiet->sothontrongdiem_2d * 6 * 310000 * 5);
                    $sothon_trongdiem_clt7 = round($chitiet->sothontrongdiem_2d * 3 * $m_thongtu->chenhlech);
                    $sothon_trongdiem_cl5t = round($chitiet->sothontrongdiem_2d * 6 * $m_thongtu->chenhlech * 5);
                    $sothon_trongdiem = $sothon_trongdiem_clt7 + $sothon_trongdiem_cl5t;
                    //tổ dân phố chuyển từ thôn
                    // $sochuyentuthon_clt7 = round($chitiet->sochuyentuthon350hgd_2d * 3 * 310000);
                    // $sochuyentuthon_cl5t = round($chitiet->sochuyentuthon350hgd_2d * 6 * 310000 * 5);
                    $sochuyentuthon_clt7 = round($chitiet->sochuyentuthon350hgd_2d * 3 * $m_thongtu->chenhlech);
                    $sochuyentuthon_cl5t = round($chitiet->sochuyentuthon350hgd_2d * 6 * $m_thongtu->chenhlech * 5);
                    $sochuyentuthon = $sochuyentuthon_clt7 +  $sochuyentuthon_cl5t;
                    //Thôn còn lại
                    // $sothonconlai_clt7 = round($chitiet->sothonconlai_2d * 3 * 310000);
                    // $sothonconlai_cl5t = round($chitiet->sothonconlai_2d * 4.5 * 310000 * 5);
                    $sothonconlai_clt7 = round($chitiet->sothonconlai_2d * 3 * $m_thongtu->chenhlech);
                    $sothonconlai_cl5t = round($chitiet->sothonconlai_2d * 4.5 * $m_thongtu->chenhlech * 5);
                    $sothonconlai =  $sothonconlai_clt7 +  $sothonconlai_cl5t;
                    //tổ dân phố còn lại
                    // $sotoconlai_clt7 = round($chitiet->sotoconlai_2d * 3 * 310000);
                    // $sotoconlai_cl5t = round($chitiet->sotoconlai_2d * 4.5 * 310000 * 5);
                    $sotoconlai_clt7 = round($chitiet->sotoconlai_2d * 3 * $m_thongtu->chenhlech);
                    $sotoconlai_cl5t = round($chitiet->sotoconlai_2d * 4.5 * $m_thongtu->chenhlech * 5);
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
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'GD');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'GD')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);

            $data[1]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[1]['solieu']['tongso'] = $data[1]['solieu']['tietkiem'] + $data[1]['solieu']['hocphi'] + $data[1]['solieu']['vienphi'] + $data[1]['solieu']['nguonthu'];
            //dd($data);
            //Sự nghiệp đào tạo
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'DT');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'DT')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
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

            //Sự nghiệp y tế
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'YTE');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'YTE')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            $data[3]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[3]['solieu']['tongso'] = $data[3]['solieu']['tietkiem'] + $data[3]['solieu']['hocphi'] + $data[3]['solieu']['vienphi'] + $data[3]['solieu']['nguonthu'];

            //Bao gồm: Sự nghiệp khác + đại biểu hội đồng nhân dân + cấp uỷ
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT']);
            $m_bl = $m_chitiet->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            // $m_bl2 = $m_chitiet->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            $data[4]['solieu'] = [
                // 'nhucau' => $m_bl->sum('tongnhucau') +  $m_bl2->sum('tongnhucau'),
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];

            $data[4]['solieu']['tongso'] = $data[4]['solieu']['tietkiem'] + $data[4]['solieu']['hocphi'] + $data[4]['solieu']['vienphi']
                + $data[4]['solieu']['nguonthu'];
            // dd($m_chitiet);
            //Quản lý nhà nước + Biên chế xã + Các cán bộ đã nghỉ hưu (2b)->29/8/2023: không cộng mẫu 2b vào nữa mà cộng mẫu 2c
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể',);
            $m_data = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN', 'DDT']);
            // $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            // $m_bl = $m_chitiet->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE','HDND','CAPUY','CANBOKCT']);
            $m_bl = $m_chitiet->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP')->wherein('nhomnhucau', ['CANBOCT', 'BIENCHE']);
            // $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);
            $m_bl2 = $m_chitiet->wherein('nhomnhucau', ['HDND', 'CAPUY']);
            $m_bl3 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['CANBOCT']);
            // dd($m_nguonkp->sum('nhucau2c'));
            $data[5]['solieu'] = [
                // 'nhucau' => $m_bl->sum('tongnhucau')  + $m_nguonkp->sum('nhucau2c'),
                'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau') + $m_nguonkp->sum('nhucau2c') + $m_bl3->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
            ];
            $data[5]['solieu']['tongso'] = $data[5]['solieu']['tietkiem'] + $data[5]['solieu']['hocphi'] + $data[5]['solieu']['vienphi'] + $data[5]['solieu']['nguonthu'];
            //
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã',);
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['CANBOCT']);
            // $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['CANBOCT']);

            $data[6]['solieu'] = [
                // 'nhucau' => $m_bl2->sum('tongnhucau'),
                'nhucau' => $m_bl3->sum('tongnhucau'),
                'tietkiem' => $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' =>  $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
            ];
            $data[6]['solieu']['tongso'] = $data[6]['solieu']['tietkiem'] + $data[6]['solieu']['hocphi'] + $data[6]['solieu']['vienphi'] + $data[6]['solieu']['nguonthu'];

            //dd($data);
            $inputs['donvitinh'] = 1;
            if ($inputs['sohieu'] == '732024nd_cp') {
                $view = 'reports.nghidinh73.donvi.mau4b';
            } else {
                $view = 'reports.thongtu78.huyen.mau4b';
            }
            return view($view)
                //->with('model', $model)
                // ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_thongtu', $m_thongtu)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }
}
