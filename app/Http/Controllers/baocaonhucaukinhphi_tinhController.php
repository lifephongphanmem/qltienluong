<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaict;
use App\dmphucap;
use App\dmthongtuquyetdinh;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\nguonkinhphi;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_tinh;
use Illuminate\Support\Facades\Session;

class baocaonhucaukinhphi_tinhController  extends Controller
{
    //Nhu cầu kinh phí
    function mau2a(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            if ($inputs['madvbc'] != 'ALL') {
                $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->where('madvbc', $inputs['madvbc'])->get();
                $model_donvi_bc = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->get();
            } else {
                $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
                $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->get();
            }
            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

            // dd($inputs);
            // $m_banhanh = nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            //dd($m_nguonkp);
            //->get();


            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::where('trangthai', '<>', 'TD')->orwherenull('trangthai')->get();
            $a_dv = array_column($m_dsdv->toArray(), 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_dv);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            foreach ($m_chitiet as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //Lọc các đơn vị tạm ngưng theo dõi
                if (!in_array($chitiet->madv, $a_dv)) {
                    $m_chitiet->forget($key);
                    continue;
                }

                $chitiet->madvbc = $a_madvbc[$chitiet->madv];

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
            //dd($m_chitiet->where('linhvuchoatdong','DDT')->where('tonghs','>',1000));
            $m_phucap = dmphucap::wherenotin('mapc', ['heso'])->get();

            $a_phucap = array_keys(getPhuCap2a_78());

            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            foreach ($model_donvi_bc as $diaban) {
                //Phần 01
                $ar_I[$diaban->madvbc] = getHCSN_tinh();

                $test = true;
                $i = 0;
                while ($test) {
                    foreach ($ar_I[$diaban->madvbc] as $key => $val) {
                        switch ($val['phanloai']) {
                            case '0':
                            case '1': {
                                    //kiểm tra nếu chưa có phần từ nào thì bỏ qua
                                    foreach ($val['chitiet'] as $k) {
                                        if (!isset($ar_I[$diaban->madvbc][$k]['solieu'])) {
                                            goto thoattinhtoan;
                                        }
                                    }
                                    //Tính toán cộng các phần tử
                                    $a_solieu = [];
                                    $a_solieu_moi = [];
                                    //lấy thông tin trường trc
                                    $canbo_congtac = $canbo_dutoan = $chenhlech01thang = $chenhlech06thang = 0;
                                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;

                                    foreach ($m_phucap as $pc) {
                                        $mapc_st = 'st_' . $pc->mapc;
                                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                                    }

                                    foreach ($val['chitiet'] as $k) {
                                        //bảng lương cũ
                                        $a_solieu['heso'] += $ar_I[$diaban->madvbc][$k]['solieu']['heso'];
                                        $a_solieu['st_heso'] += $ar_I[$diaban->madvbc][$k]['solieu']['st_heso'];
                                        $a_solieu['tongbh_dv'] += $ar_I[$diaban->madvbc][$k]['solieu']['tongbh_dv'];
                                        $a_solieu['ttbh_dv'] += $ar_I[$diaban->madvbc][$k]['solieu']['ttbh_dv'];

                                        foreach ($m_phucap as $pc) {
                                            $mapc_st = 'st_' . $pc->mapc;
                                            $a_solieu[$pc->mapc] += $ar_I[$diaban->madvbc][$k]['solieu'][$pc->mapc];
                                            $a_solieu[$mapc_st] += $ar_I[$diaban->madvbc][$k]['solieu'][$mapc_st];
                                        }
                                        $a_solieu['tongpc'] += $ar_I[$diaban->madvbc][$k]['solieu']['tongpc'];
                                        $a_solieu['st_tongpc'] += $ar_I[$diaban->madvbc][$k]['solieu']['st_tongpc'];
                                        $a_solieu['tongcong'] += $ar_I[$diaban->madvbc][$k]['solieu']['tongcong'];

                                        //bang lương mới

                                        $a_solieu_moi['heso'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['heso'];
                                        $a_solieu_moi['st_heso'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['st_heso'];
                                        $a_solieu_moi['tongbh_dv'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['tongbh_dv'];
                                        $a_solieu_moi['ttbh_dv'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['ttbh_dv'];

                                        foreach ($m_phucap as $pc) {
                                            $mapc_st = 'st_' . $pc->mapc;
                                            $a_solieu_moi[$pc->mapc] += $ar_I[$diaban->madvbc][$k]['solieu_moi'][$pc->mapc];
                                            $a_solieu_moi[$mapc_st] += $ar_I[$diaban->madvbc][$k]['solieu_moi'][$mapc_st];
                                        }
                                        $a_solieu_moi['tongpc'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['tongpc'];
                                        $a_solieu_moi['st_tongpc'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['st_tongpc'];
                                        $a_solieu_moi['tongcong'] += $ar_I[$diaban->madvbc][$k]['solieu_moi']['tongcong'];

                                        $canbo_congtac += $ar_I[$diaban->madvbc][$k]['canbo_congtac'];
                                        $canbo_dutoan += $ar_I[$diaban->madvbc][$k]['canbo_dutoan'];
                                        $chenhlech01thang += $ar_I[$diaban->madvbc][$k]['chenhlech01thang'];
                                        $chenhlech06thang += $ar_I[$diaban->madvbc][$k]['chenhlech06thang'];
                                    }
                                    break;
                                }
                            case '2': {
                                    $dulieu_chitiet = $m_chitiet->where('nhomnhucau', 'BIENCHE')->where('madvbc', $diaban->madvbc);
                                    foreach ($val['chitiet'] as $k => $v) {
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

                                    $canbo_congtac = $dulieu_chitiet->sum('canbo_congtac');
                                    $canbo_dutoan = $dulieu_nguonkp->sum('sobiencheduocgiao');
                                    $chenhlech01thang = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                                    $chenhlech06thang = $chenhlech01thang * 6;
                                    break;
                                }
                            case '9': {
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
                                    $canbo_congtac =  $canbo_dutoan = $chenhlech01thang = $chenhlech06thang = 0;
                                    break;
                                }
                        }
                        //Gán số liệu
                        $ar_I[$diaban->madvbc][$key]['chenhlech01thang'] = $chenhlech01thang;
                        $ar_I[$diaban->madvbc][$key]['chenhlech06thang'] = $chenhlech06thang;
                        $ar_I[$diaban->madvbc][$key]['canbo_congtac'] = $canbo_congtac;
                        $ar_I[$diaban->madvbc][$key]['canbo_dutoan'] = $canbo_dutoan;
                        $ar_I[$diaban->madvbc][$key]['solieu'] = $a_solieu;
                        $ar_I[$diaban->madvbc][$key]['solieu_moi'] = $a_solieu_moi;
                        $i++;
                        //Khi tính xong thì thoát vòng lập
                        if ($val['phanloai'] == '0' && isset($val['solieu'])) {
                            $test = false;
                        }
                        thoattinhtoan:
                    }
                }

                //Phần 02
                $ar_II[$diaban->madvbc] = getChuyenTrach();
                $dulieu_pII = $m_chitiet->where('nhomnhucau', 'CANBOCT')->where('madvbc', $diaban->madvbc);
                $aII_plct = getChuyenTrach_plct();
                foreach ($dulieu_pII as $key => $value) {
                    if (count($aII_plct) > 0 && !in_array($value->mact, $aII_plct))
                        $dulieu_pII->forget($key);
                }

                foreach ($ar_II[$diaban->madvbc] as $key => $chitiet) {
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
                        $ar_II[$diaban->madvbc][$key]['solieu'] = $a_solieu;

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
                        $ar_II[$diaban->madvbc][$key]['solieu_moi'] = $a_solieu_moi;

                        $ar_II[$diaban->madvbc][$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                        $ar_II[$diaban->madvbc][$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');;

                        $ar_II[$diaban->madvbc][$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                        $ar_II[$diaban->madvbc][$key]['chenhlech06thang'] = $ar_II[$diaban->madvbc][$key]['chenhlech01thang'] * 6;
                    }
                }

                //Phần 03
                $ar_III[$diaban->madvbc] = getHDND();
                $aIII_plct = getHDND_plct();
                $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND')->where('madvbc', $diaban->madvbc);
                foreach ($dulieu_pIII as $key => $value) {
                    if (count($aIII_plct) > 0 && !in_array($value->mact, $aIII_plct))
                        $dulieu_pIII->forget($key);
                }

                //Vòng cấp độ 3
                foreach ($ar_III[$diaban->madvbc] as $key => $chitiet) {
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
                        $ar_III[$diaban->madvbc][$key]['solieu'] = $a_solieu;

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
                        $ar_III[$diaban->madvbc][$key]['solieu_moi'] = $a_solieu_moi;

                        $ar_III[$diaban->madvbc][$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                        $ar_III[$diaban->madvbc][$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                        //26/8/2023: tạm thời bỏ để khớp dữ liệu 4a
                        // $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                        // $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
                        $ar_III[$diaban->madvbc][$key]['chenhlech01thang'] = $dulieu_chitiet->sum('ttl') + $dulieu_chitiet->sum('ttbh_dv');
                        $ar_III[$diaban->madvbc][$key]['chenhlech06thang'] = $ar_III[$diaban->madvbc][$key]['chenhlech01thang'] * 6;
                    }
                }

                //Vòng cấp độ 2
                foreach ($ar_III[$diaban->madvbc] as $key => $chitiet) {
                    if ($chitiet['phanloai'] == '1') {
                        $a_solieu = [];
                        $a_solieu_moi = [];
                        //lấy thông tin trường trc
                        $ar_III[$diaban->madvbc][$key]['canbo_congtac'] = $ar_III[$diaban->madvbc][$key]['canbo_dutoan'] = 0;
                        $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                            = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                        $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                            = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                        }
                        $ar_III[$diaban->madvbc][$key]['chenhlech01thang'] = 0;
                        foreach ($chitiet['chitiet'] as $k) {
                            //bảng lương cũ

                            $a_solieu['heso'] += $ar_III[$diaban->madvbc][$k]['solieu']['heso'];
                            $a_solieu['st_heso'] += $ar_III[$diaban->madvbc][$k]['solieu']['st_heso'];
                            $a_solieu['tongbh_dv'] += $ar_III[$diaban->madvbc][$k]['solieu']['tongbh_dv'];
                            $a_solieu['ttbh_dv'] += $ar_III[$diaban->madvbc][$k]['solieu']['ttbh_dv'];

                            foreach ($m_phucap as $pc) {
                                $mapc_st = 'st_' . $pc->mapc;
                                $a_solieu[$pc->mapc] += $ar_III[$diaban->madvbc][$k]['solieu'][$pc->mapc];
                                $a_solieu[$mapc_st] += $ar_III[$diaban->madvbc][$k]['solieu'][$mapc_st];
                            }
                            $a_solieu['tongpc'] += $ar_III[$diaban->madvbc][$k]['solieu']['tongpc'];
                            $a_solieu['st_tongpc'] += $ar_III[$diaban->madvbc][$k]['solieu']['st_tongpc'];
                            $a_solieu['tongcong'] += $ar_III[$diaban->madvbc][$k]['solieu']['tongcong'];

                            //bang lương mới

                            $a_solieu_moi['heso'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['heso'];
                            $a_solieu_moi['st_heso'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['st_heso'];
                            $a_solieu_moi['tongbh_dv'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['tongbh_dv'];
                            $a_solieu_moi['ttbh_dv'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['ttbh_dv'];

                            foreach ($m_phucap as $pc) {
                                $mapc_st = 'st_' . $pc->mapc;
                                $a_solieu_moi[$pc->mapc] += $ar_III[$diaban->madvbc][$k]['solieu_moi'][$pc->mapc];
                                $a_solieu_moi[$mapc_st] += $ar_III[$diaban->madvbc][$k]['solieu_moi'][$mapc_st];
                            }
                            $a_solieu_moi['tongpc'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['tongpc'];
                            $a_solieu_moi['st_tongpc'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['st_tongpc'];
                            $a_solieu_moi['tongcong'] += $ar_III[$diaban->madvbc][$k]['solieu_moi']['tongcong'];

                            $ar_III[$diaban->madvbc][$key]['canbo_congtac'] += $ar_III[$diaban->madvbc][$k]['canbo_congtac'];
                            $ar_III[$diaban->madvbc][$key]['canbo_dutoan'] += $ar_III[$diaban->madvbc][$k]['canbo_dutoan'];
                            $ar_III[$diaban->madvbc][$key]['chenhlech01thang'] += $ar_III[$diaban->madvbc][$k]['chenhlech01thang']; //sửa cho khớp dữ liệu 4a (26/8/2023)
                        }

                        // $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                        $ar_III[$diaban->madvbc][$key]['chenhlech06thang'] = $ar_III[$diaban->madvbc][$key]['chenhlech01thang'] * 6;

                        $ar_III[$diaban->madvbc][$key]['solieu'] = $a_solieu;
                        $ar_III[$diaban->madvbc][$key]['solieu_moi'] = $a_solieu_moi;
                    }
                }

                //Tính toán số liệu phần IV
                $ar_IV[$diaban->madvbc] = getCapUy();
                $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY')->where('madvbc', $diaban->madvbc);
                $aIV_plct = getCapUy_plct();
                foreach ($dulieu_pIV as $key => $value) {
                    if (count($aIV_plct) > 0 && !in_array($value->mact, $aIV_plct))
                        $dulieu_pIV->forget($key);
                }
                //Vòng cấp độ 3
                foreach ($ar_IV[$diaban->madvbc] as $key => $chitiet) {
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
                        $ar_IV[$diaban->madvbc][$key]['solieu'] = $a_solieu;

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
                        $ar_IV[$diaban->madvbc][$key]['solieu_moi'] = $a_solieu_moi;

                        $ar_IV[$diaban->madvbc][$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                        $ar_IV[$diaban->madvbc][$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                        //26/8/2023: tạm thời bỏ để khớp dữ liệu 4a
                        // $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                        // $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
                        $ar_IV[$diaban->madvbc][$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                        $ar_IV[$diaban->madvbc][$key]['chenhlech06thang'] = $ar_IV[$diaban->madvbc][$key]['chenhlech01thang'] * 6;
                    }
                }

                //Vòng cấp độ 2
                foreach ($ar_IV[$diaban->madvbc] as $key => $chitiet) {
                    if ($chitiet['phanloai'] == '1') {
                        $a_solieu = [];
                        $a_solieu_moi = [];
                        //lấy thông tin trường trc
                        $ar_IV[$diaban->madvbc][$key]['canbo_congtac'] = $ar_IV[$diaban->madvbc][$key]['canbo_dutoan'] = 0;
                        $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                            = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                        $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                            = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                        }
                        $ar_IV[$diaban->madvbc][$key]['chenhlech01thang'] = 0;
                        foreach ($chitiet['chitiet'] as $k) {
                            //bảng lương cũ

                            $a_solieu['heso'] += $ar_IV[$diaban->madvbc][$k]['solieu']['heso'];
                            $a_solieu['st_heso'] += $ar_IV[$diaban->madvbc][$k]['solieu']['st_heso'];
                            $a_solieu['tongbh_dv'] += $ar_IV[$diaban->madvbc][$k]['solieu']['tongbh_dv'];
                            $a_solieu['ttbh_dv'] += $ar_IV[$diaban->madvbc][$k]['solieu']['ttbh_dv'];

                            foreach ($m_phucap as $pc) {
                                $mapc_st = 'st_' . $pc->mapc;
                                $a_solieu[$pc->mapc] += $ar_IV[$diaban->madvbc][$k]['solieu'][$pc->mapc];
                                $a_solieu[$mapc_st] += $ar_IV[$diaban->madvbc][$k]['solieu'][$mapc_st];
                            }
                            $a_solieu['tongpc'] += $ar_IV[$diaban->madvbc][$k]['solieu']['tongpc'];
                            $a_solieu['st_tongpc'] += $ar_IV[$diaban->madvbc][$k]['solieu']['st_tongpc'];
                            $a_solieu['tongcong'] += $ar_IV[$diaban->madvbc][$k]['solieu']['tongcong'];

                            //bang lương mới

                            $a_solieu_moi['heso'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['heso'];
                            $a_solieu_moi['st_heso'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['st_heso'];
                            $a_solieu_moi['tongbh_dv'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['tongbh_dv'];
                            $a_solieu_moi['ttbh_dv'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['ttbh_dv'];

                            foreach ($m_phucap as $pc) {
                                $mapc_st = 'st_' . $pc->mapc;
                                $a_solieu_moi[$pc->mapc] += $ar_IV[$diaban->madvbc][$k]['solieu_moi'][$pc->mapc];
                                $a_solieu_moi[$mapc_st] += $ar_IV[$diaban->madvbc][$k]['solieu_moi'][$mapc_st];
                            }
                            $a_solieu_moi['tongpc'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['tongpc'];
                            $a_solieu_moi['st_tongpc'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['st_tongpc'];
                            $a_solieu_moi['tongcong'] += $ar_IV[$diaban->madvbc][$k]['solieu_moi']['tongcong'];

                            $ar_IV[$diaban->madvbc][$key]['canbo_congtac'] += $ar_IV[$diaban->madvbc][$k]['canbo_congtac'];
                            $ar_IV[$diaban->madvbc][$key]['canbo_dutoan'] += $ar_IV[$diaban->madvbc][$k]['canbo_dutoan'];
                            $ar_IV[$diaban->madvbc][$key]['chenhlech01thang'] += $ar_IV[$diaban->madvbc][$k]['chenhlech01thang']; //sửa cho khớp dữ liệu 4a (26/8/2023)
                        }

                        // $ar_IV[$diaban->madvbc][$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                        $ar_IV[$diaban->madvbc][$key]['chenhlech06thang'] = $ar_IV[$diaban->madvbc][$key]['chenhlech01thang'] * 6;

                        $ar_IV[$diaban->madvbc][$key]['solieu'] = $a_solieu;
                        $ar_IV[$diaban->madvbc][$key]['solieu_moi'] = $a_solieu_moi;
                    }
                }

                //dd($ar_II);

                //dd();
                //Tính toán tổng cộng
                $a_Tong[$diaban->madvbc] = [
                    'canbo_congtac' => $ar_I[$diaban->madvbc]['TONGSO']['canbo_congtac'] + $ar_II[$diaban->madvbc][0]['canbo_congtac'] + $ar_III[$diaban->madvbc][0]['canbo_congtac'] + $ar_IV[$diaban->madvbc][0]['canbo_congtac'],
                    'canbo_dutoan' => $ar_I[$diaban->madvbc]['TONGSO']['canbo_dutoan'] + $ar_II[$diaban->madvbc][0]['canbo_dutoan'] + $ar_III[$diaban->madvbc][0]['canbo_dutoan'] + $ar_IV[$diaban->madvbc][0]['canbo_dutoan'],
                    'chenhlech01thang' => $ar_I[$diaban->madvbc]['TONGSO']['chenhlech01thang'] + $ar_II[$diaban->madvbc][0]['chenhlech01thang'] + $ar_III[$diaban->madvbc][0]['chenhlech01thang'] + $ar_IV[$diaban->madvbc][0]['chenhlech01thang'],
                    'chenhlech06thang' => $ar_I[$diaban->madvbc]['TONGSO']['chenhlech06thang'] + $ar_II[$diaban->madvbc][0]['chenhlech06thang'] + $ar_III[$diaban->madvbc][0]['chenhlech06thang'] + $ar_IV[$diaban->madvbc][0]['chenhlech06thang'],
                ];
                $a_Tong[$diaban->madvbc]['solieu'] = [
                    'tongcong' => $ar_I[$diaban->madvbc]['TONGSO']['solieu']['tongcong'] + $ar_II[$diaban->madvbc][0]['solieu']['tongcong']
                        + $ar_III[$diaban->madvbc][0]['solieu']['tongcong'] + $ar_IV[$diaban->madvbc][0]['solieu']['tongcong'],
                    'st_heso' => $ar_I[$diaban->madvbc]['TONGSO']['solieu']['st_heso'] + $ar_II[$diaban->madvbc][0]['solieu']['st_heso']
                        + $ar_III[$diaban->madvbc][0]['solieu']['st_heso'] + $ar_IV[$diaban->madvbc][0]['solieu']['st_heso'],
                    'st_tongpc' => $ar_I[$diaban->madvbc]['TONGSO']['solieu']['st_tongpc'] + $ar_II[$diaban->madvbc][0]['solieu']['st_tongpc']
                        + $ar_III[$diaban->madvbc][0]['solieu']['st_tongpc'] + $ar_IV[$diaban->madvbc][0]['solieu']['st_tongpc'],
                    'ttbh_dv' => $ar_I[$diaban->madvbc]['TONGSO']['solieu']['ttbh_dv'] + $ar_II[$diaban->madvbc][0]['solieu']['ttbh_dv']
                        + $ar_III[$diaban->madvbc][0]['solieu']['ttbh_dv'] + $ar_IV[$diaban->madvbc][0]['solieu']['ttbh_dv'],
                ];
                $a_Tong[$diaban->madvbc]['solieu_moi'] = [
                    'tongcong' => $ar_I[$diaban->madvbc]['TONGSO']['solieu_moi']['tongcong'] + $ar_II[$diaban->madvbc][0]['solieu_moi']['tongcong']
                        + $ar_III[$diaban->madvbc][0]['solieu_moi']['tongcong'] + $ar_IV[$diaban->madvbc][0]['solieu_moi']['tongcong'],
                    'st_heso' => $ar_I[$diaban->madvbc]['TONGSO']['solieu_moi']['st_heso'] + $ar_II[$diaban->madvbc][0]['solieu_moi']['st_heso']
                        + $ar_III[$diaban->madvbc][0]['solieu_moi']['st_heso'] + $ar_IV[$diaban->madvbc][0]['solieu_moi']['st_heso'],
                    'st_tongpc' => $ar_I[$diaban->madvbc]['TONGSO']['solieu_moi']['st_tongpc'] + $ar_II[$diaban->madvbc][0]['solieu_moi']['st_tongpc']
                        + $ar_III[$diaban->madvbc][0]['solieu_moi']['st_tongpc'] + $ar_IV[$diaban->madvbc][0]['solieu_moi']['st_tongpc'],
                    'ttbh_dv' => $ar_I[$diaban->madvbc]['TONGSO']['solieu_moi']['ttbh_dv'] + $ar_II[$diaban->madvbc][0]['solieu_moi']['ttbh_dv']
                        + $ar_III[$diaban->madvbc][0]['solieu_moi']['ttbh_dv'] + $ar_IV[$diaban->madvbc][0]['solieu_moi']['ttbh_dv'],
                ];
                foreach ($m_phucap as $pc) {
                    $mapc_st = 'st_' . $pc->mapc;
                    $a_Tong[$diaban->madvbc]['solieu_moi'][$mapc_st] = $ar_I[$diaban->madvbc]['TONGSO']['solieu_moi'][$mapc_st] + $ar_II[$diaban->madvbc][0]['solieu_moi'][$mapc_st]
                        + $ar_III[$diaban->madvbc][0]['solieu_moi'][$mapc_st] + $ar_IV[$diaban->madvbc][0]['solieu_moi'][$mapc_st];
                    $a_Tong[$diaban->madvbc]['solieu'][$mapc_st] = $ar_I[$diaban->madvbc]['TONGSO']['solieu'][$mapc_st] + $ar_II[$diaban->madvbc][0]['solieu'][$mapc_st]
                        + $ar_III[$diaban->madvbc][0]['solieu'][$mapc_st] + $ar_IV[$diaban->madvbc][0]['solieu'][$mapc_st];
                }
            }

            //   dd($a_Tong);
            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            return view('reports.thongtu78.tinh.mau2a')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_dv)
                ->with('m_dv', $m_dv)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('inputs', $inputs)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2a_tonghop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

            // dd($inputs);
            // $m_banhanh = nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            //dd($m_nguonkp);
            //->get();


            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::where('trangthai', '<>', 'TD')->orwherenull('trangthai')->get();
            $a_dv = array_column($m_dsdv->toArray(), 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_dv);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            foreach ($m_chitiet as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //Lọc các đơn vị tạm ngưng theo dõi
                if (!in_array($chitiet->madv, $a_dv)) {
                    $m_chitiet->forget($key);
                    continue;
                }

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
            //dd($m_chitiet);
            //dd($m_chitiet->where('linhvuchoatdong','DDT')->where('tonghs','>',1000));
            $m_phucap = dmphucap::wherenotin('mapc', ['heso'])->get();

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
            // dd($ar_I);
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

            //  dd($inputs);
            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            return view('reports.thongtu78.tinh.mau2a_tonghop')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_dv)
                ->with('m_dv', $m_dv)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('inputs', $inputs)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2b_tonghop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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
            return view('reports.thongtu78.tinh.mau2b_tonghop')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
        } else
            return view('errors.notlogin');
    }

    function mau2b(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();
            foreach ($model_donvi_bc as $diaban) {
                $dulieu = $m_nguonkp->where('madvbc', $diaban->madvbc);
                $ar_I[$diaban->madvbc] = array();
                $ar_I[$diaban->madvbc][0] = array(
                    'val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch',
                    'songuoi' => $dulieu->sum('tongsonguoi1'),
                    'quy1' => $dulieu->sum('quy1_1'),
                    'quy2' => $dulieu->sum('quy2_1'),
                    'quy3' => $dulieu->sum('quy3_1'),
                    'tongquy' => $dulieu->sum('quy1_tong'),

                );

                $ar_I[$diaban->madvbc][1] = array(
                    'val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng',
                    'songuoi' => $dulieu->sum('tongsonguoi2'),
                    'quy1' => $dulieu->sum('quy1_2'),
                    'quy2' => $dulieu->sum('quy2_2'),
                    'quy3' => $dulieu->sum('quy3_2'),
                    'tongquy' => $dulieu->sum('quy2_tong'),
                );
                $ar_I[$diaban->madvbc][2] = array(
                    'val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại',
                    'songuoi' => $dulieu->sum('tongsonguoi3'),
                    'quy1' => $dulieu->sum('quy1_3'),
                    'quy2' => $dulieu->sum('quy2_3'),
                    'quy3' => $dulieu->sum('quy3_3'),
                    'tongquy' => $dulieu->sum('quy3_tong'),
                );
            }

            // dd($ar_I);
            return view('reports.thongtu78.tinh.mau2b')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
        } else
            return view('errors.notlogin');
    }

    function mau2c_tonghop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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
            return view('reports.thongtu78.tinh.mau2c_tonghop')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2c(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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
            foreach ($model_donvi_bc as $diaban) {
                //1800000-1490000 = 310000
                $dulieu = $m_nguonkp->where('madvbc', $diaban->madvbc);
                $ar_I[$diaban->madvbc] = array();
                $ar_I[$diaban->madvbc][0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;');
                //
                $m_nguon_1 = $dulieu->where('phanloaixa', 'XL1');
                $ar_I[$diaban->madvbc][1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                    'tdv' => $m_nguon_1->count(),
                    'mk' => 16,
                    'mk2' => 21,
                    'clt7' => round($m_nguon_1->count() * 16 * 310000),
                    'cl5t' => round($m_nguon_1->count() * 21 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][1]['solieu']['tong'] = $ar_I[$diaban->madvbc][1]['solieu']['clt7'] + $ar_I[$diaban->madvbc][1]['solieu']['cl5t'];
                //

                $m_nguon_2 = $dulieu->where('phanloaixa', 'XL2');
                $ar_I[$diaban->madvbc][2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                    'tdv' => $m_nguon_2->count(),
                    'mk' => 13.7,
                    'mk2' => 18,
                    'clt7' => round($m_nguon_2->count() * 13.7 * 310000),
                    'cl5t' => round($m_nguon_2->count() * 18 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][2]['solieu']['tong'] = $ar_I[$diaban->madvbc][2]['solieu']['clt7'] + $ar_I[$diaban->madvbc][2]['solieu']['cl5t'];
                //

                $m_nguon_3 = $dulieu->where('phanloaixa', 'XL3');
                $ar_I[$diaban->madvbc][3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                    'tdv' => $m_nguon_3->count(),
                    'mk' => 11.4,
                    'mk2' => 15,
                    'clt7' => round($m_nguon_3->count() * 11.4 * 310000),
                    'cl5t' => round($m_nguon_3->count() * 15 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][3]['solieu']['tong'] = $ar_I[$diaban->madvbc][3]['solieu']['clt7'] + $ar_I[$diaban->madvbc][3]['solieu']['cl5t'];
                //Tổng phân loại xã
                $ar_I[$diaban->madvbc][0]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][1]['solieu']['tdv'] + $ar_I[$diaban->madvbc][2]['solieu']['tdv'] + $ar_I[$diaban->madvbc][3]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'clt7' => $ar_I[$diaban->madvbc][1]['solieu']['clt7'] + $ar_I[$diaban->madvbc][2]['solieu']['clt7'] + $ar_I[$diaban->madvbc][3]['solieu']['clt7'],
                    'cl5t' => $ar_I[$diaban->madvbc][1]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][2]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][3]['solieu']['cl5t'],
                    'tong' => $ar_I[$diaban->madvbc][1]['solieu']['tong'] + $ar_I[$diaban->madvbc][2]['solieu']['tong'] + $ar_I[$diaban->madvbc][3]['solieu']['tong'],
                ];

                //II = 5+8+13
                $ar_I[$diaban->madvbc][4] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố', 'style' => 'font-weight:bold;');
                //Số xã biên giới, hải đảo
                $ar_I[$diaban->madvbc][5] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $dulieu->sum('sothonbiengioi_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    'clt7' => round($dulieu->sum('sothonbiengioi_2d') * 5 * 310000),
                    'cl5t' => round($dulieu->sum('sothonbiengioi_2d') * 6 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][5]['solieu']['tong'] = $ar_I[$diaban->madvbc][5]['solieu']['clt7'] + $ar_I[$diaban->madvbc][5]['solieu']['cl5t'];

                //Thôn thuộc xã biên giới, hải đảo
                $ar_I[$diaban->madvbc][6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $dulieu->sum('sothonbiengioi_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    'clt7' => round($dulieu->sum('sothonbiengioi_2d') * 5 * 310000),
                    'cl5t' => round($dulieu->sum('sothonbiengioi_2d') * 6 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][6]['solieu']['tong'] = $ar_I[$diaban->madvbc][6]['solieu']['clt7'] + $ar_I[$diaban->madvbc][6]['solieu']['cl5t'];

                //Tổ dân phố thuộc xã biên giới, hải đảo
                $ar_I[$diaban->madvbc][7] = array('val' => '', 'tt' => '', 'noidung' => '- Tổ dân phố thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => 0, 'mk' => 0, 'mk2' => 0, 'clt7' => 0, 'cl5t' => 0, 'tong' => 0,
                ]);

                //II.2  8 = 9 + 10 + 11 + 12
                $ar_I[$diaban->madvbc][8] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

                //Số hộ 350 trở lên
                $ar_I[$diaban->madvbc][9] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $dulieu->sum('sothon350hgd_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    'clt7' => round($dulieu->sum('sothon350hgd_2d') * 5 * 310000),
                    'cl5t' => round($dulieu->sum('sothon350hgd_2d') * 6 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][9]['solieu']['tong'] = $ar_I[$diaban->madvbc][9]['solieu']['clt7'] + $ar_I[$diaban->madvbc][9]['solieu']['cl5t'];

                //500 hộ trở lên
                $ar_I[$diaban->madvbc][10] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                    'tdv' => $dulieu->sum('sotodanpho500hgd_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    'clt7' => round($dulieu->sum('sotodanpho500hgd_2d') * 3 * 310000),
                    'cl5t' => round($dulieu->sum('sotodanpho500hgd_2d') * 6 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][10]['solieu']['tong'] = $ar_I[$diaban->madvbc][10]['solieu']['clt7'] + $ar_I[$diaban->madvbc][10]['solieu']['cl5t'];

                //Tổ dân phố thuộc xã trọng điểm về an ninh
                $ar_I[$diaban->madvbc][11] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $dulieu->sum('sothontrongdiem_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    'clt7' => round($dulieu->sum('sothontrongdiem_2d') * 3 * 310000),
                    'cl5t' => round($dulieu->sum('sothontrongdiem_2d') * 6 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][11]['solieu']['tong'] = $ar_I[$diaban->madvbc][11]['solieu']['clt7'] + $ar_I[$diaban->madvbc][11]['solieu']['cl5t'];

                //Tổ dân phố chuyển từ thôn
                $ar_I[$diaban->madvbc][12] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                    'tdv' => $dulieu->sum('sochuyentuthon350hgd_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    'clt7' => round($dulieu->sum('sochuyentuthon350hgd_2d') * 3 * 310000),
                    'cl5t' => round($dulieu->sum('sochuyentuthon350hgd_2d') * 6 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][12]['solieu']['tong'] = $ar_I[$diaban->madvbc][12]['solieu']['clt7'] + $ar_I[$diaban->madvbc][12]['solieu']['cl5t'];

                //Số liệu II.2 8 = 9 + 10 + 11 + 12
                $ar_I[$diaban->madvbc][8]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][9]['solieu']['tdv'] + $ar_I[$diaban->madvbc][10]['solieu']['tdv'] + $ar_I[$diaban->madvbc][11]['solieu']['tdv'] + $ar_I[$diaban->madvbc][12]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'clt7' =>  $ar_I[$diaban->madvbc][9]['solieu']['clt7'] + $ar_I[$diaban->madvbc][10]['solieu']['clt7'] + $ar_I[$diaban->madvbc][11]['solieu']['clt7'] + $ar_I[$diaban->madvbc][12]['solieu']['clt7'],
                    'cl5t' =>  $ar_I[$diaban->madvbc][9]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][10]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][11]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][12]['solieu']['cl5t'],
                    'tong' =>  $ar_I[$diaban->madvbc][9]['solieu']['tong'] + $ar_I[$diaban->madvbc][10]['solieu']['tong'] + $ar_I[$diaban->madvbc][11]['solieu']['tong'] + $ar_I[$diaban->madvbc][12]['solieu']['tong'],
                ];

                //II.3 13 = 14 + 15
                $ar_I[$diaban->madvbc][13] = array('val' => 'TDBKK', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại',);

                //Thôn còn lại
                $ar_I[$diaban->madvbc][14] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                    'tdv' => $dulieu->sum('sothonconlai_2d'),
                    'mk' => 3,
                    'mk2' => 4.5,
                    'clt7' => round($dulieu->sum('sothonconlai_2d') * 3 * 310000),
                    'cl5t' => round($dulieu->sum('sothonconlai_2d') * 4.5 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][14]['solieu']['tong'] = $ar_I[$diaban->madvbc][14]['solieu']['clt7'] + $ar_I[$diaban->madvbc][14]['solieu']['cl5t'];

                //Tổ dân phố
                $ar_I[$diaban->madvbc][15] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                    'tdv' => $dulieu->sum('sotoconlai_2d'),
                    'mk' => 3,
                    'mk2' => 4.5,
                    'clt7' => round($dulieu->sum('sotoconlai_2d') * 3 * 310000),
                    'cl5t' => round($dulieu->sum('sotoconlai_2d') * 4.5 * 310000 * 5),
                ]);
                $ar_I[$diaban->madvbc][15]['solieu']['tong'] = $ar_I[$diaban->madvbc][15]['solieu']['clt7'] + $ar_I[$diaban->madvbc][15]['solieu']['cl5t'];
                //Số liệu II.3  13 = 14 + 15
                $ar_I[$diaban->madvbc][13]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][14]['solieu']['tdv'] + $ar_I[$diaban->madvbc][15]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'clt7' =>  $ar_I[$diaban->madvbc][14]['solieu']['clt7'] + $ar_I[$diaban->madvbc][15]['solieu']['clt7'],
                    'cl5t' =>  $ar_I[$diaban->madvbc][14]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][15]['solieu']['cl5t'],
                    'tong' =>  $ar_I[$diaban->madvbc][14]['solieu']['tong'] + $ar_I[$diaban->madvbc][15]['solieu']['tong'],
                ];

                //II = 5+8+13 
                $ar_I[$diaban->madvbc][4]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][5]['solieu']['tdv'] + $ar_I[$diaban->madvbc][8]['solieu']['tdv'] + $ar_I[$diaban->madvbc][13]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'clt7' =>  $ar_I[$diaban->madvbc][5]['solieu']['clt7'] + $ar_I[$diaban->madvbc][8]['solieu']['clt7'] + $ar_I[$diaban->madvbc][13]['solieu']['clt7'],
                    'cl5t' =>  $ar_I[$diaban->madvbc][5]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][8]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][13]['solieu']['cl5t'],
                    'tong' =>  $ar_I[$diaban->madvbc][5]['solieu']['tong'] + $ar_I[$diaban->madvbc][8]['solieu']['tong'] + $ar_I[$diaban->madvbc][13]['solieu']['tong'],
                ];

                $a_It[$diaban->madvbc] = array(
                    'tdv' =>  $ar_I[$diaban->madvbc][0]['solieu']['tdv'] + $ar_I[$diaban->madvbc][4]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'clt7' =>  $ar_I[$diaban->madvbc][0]['solieu']['clt7'] + $ar_I[$diaban->madvbc][4]['solieu']['clt7'],
                    'cl5t' =>  $ar_I[$diaban->madvbc][0]['solieu']['cl5t'] + $ar_I[$diaban->madvbc][4]['solieu']['cl5t'],
                    'tong' =>  $ar_I[$diaban->madvbc][0]['solieu']['tong'] + $ar_I[$diaban->madvbc][4]['solieu']['tong'],
                );
            }

            //dd($ar_I);
            return view('reports.thongtu78.tinh.mau2c')
                ->with('m_dv', $m_donvi)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2d_tonghop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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
            $ar_I[0] = array('phanloai' => '', 'style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);

            $ar_I[1] = array('phanloai' => 'XL1', 'style' => 'font-weight: bold;', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1')->where('soluongdinhbien_2d', '<>', 0);
            // dd($m_xl1);
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2k' => $m_xl1->count(),
                'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL1'),
                'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL1') * $m_xl1->count(),
                // 'qd34_2d' => $m_xl1->sum('soluongdinhbien_2d'),//số định biên ndd34 lấy theo nhập
                // 'tongqd34_2d' => $m_xl1->sum('soluongdinhbien_2d'),
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
                'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL2'),
                'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL2') * $m_xl2->count(),
                // 'qd34_2d' => $m_xl2->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                // 'tongqd34_2d' => $m_xl2->sum('soluongdinhbien_2d'),
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
                'qd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3'),
                'tongqd34_2d' => getSoLuongCanBoDinhMuc('ND34/2019', 'XL3') * $m_xl3->count(),
                // 'qd34_2d' => $m_xl3->sum('soluongdinhbien_2d'), //số định biên ndd34 lấy theo nhập
                // 'tongqd34_2d' => $m_xl3->sum('soluongdinhbien_2d'),
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
            $inputs['lamtron'] = session('admin')->lamtron;
            //dd($ar_I);
            return view('reports.thongtu78.tinh.mau2d_tonghop')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('ar_tong', $ar_tong)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2d(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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
            foreach ($model_donvi_bc as $diaban) {
                $dulieu =  $m_nguonkp->where('madvbc', $diaban->madvbc);
                //Tính toán số liệu phần I
                $ar_I[$diaban->madvbc][0] = array('phanloai' => '', 'style' => 'font-weight: bold;', 'tt' => '', 'noidung' => $diaban->tendvbc,);

                $ar_I[$diaban->madvbc][1] = array('phanloai' => 'XL1', 'style' => '', 'tt' => '1', 'noidung' => 'Xã loại 1',);
                $m_xl1 = $dulieu->where('phanloaixa', 'XL1')->where('soluongdinhbien_2d', '<>', 0);
                // dd($m_xl1);
                $ar_I[$diaban->madvbc][1]['solieu'] = [
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

                $ar_I[$diaban->madvbc][2] = array('phanloai' => 'XL2', 'style' => '', 'tt' => '2', 'noidung' => 'Xã loại 2',);
                $m_xl2 = $dulieu->where('phanloaixa', 'XL2')->where('soluongdinhbien_2d', '<>', 0);
                $ar_I[$diaban->madvbc][2]['solieu'] = [
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

                $ar_I[$diaban->madvbc][3] = array('phanloai' => 'XL3', 'style' => '', 'tt' => '3', 'noidung' => 'Xã loại 3',);
                $m_xl3 = $dulieu->where('phanloaixa', 'XL3')->where('soluongdinhbien_2d', '<>', 0);
                $ar_I[$diaban->madvbc][3]['solieu'] = [
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

                $ar_I[$diaban->madvbc][0]['solieu'] = [
                    'soluongdonvi_2k' => $ar_I[$diaban->madvbc][1]['solieu']['soluongdonvi_2k'] + $ar_I[$diaban->madvbc][2]['solieu']['soluongdonvi_2k'] + $ar_I[$diaban->madvbc][3]['solieu']['soluongdonvi_2k'],
                    'qd34_2d' => 0,
                    'tongqd34_2d' => $ar_I[$diaban->madvbc][1]['solieu']['tongqd34_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['tongqd34_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['tongqd34_2d'],
                    'soluongcanbo_2d' => $ar_I[$diaban->madvbc][1]['solieu']['soluongcanbo_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['soluongcanbo_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['soluongcanbo_2d'],
                    'hesoluongbq_2d' => $ar_I[$diaban->madvbc][1]['solieu']['hesoluongbq_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['hesoluongbq_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['hesoluongbq_2d'],
                    'hesophucapbq_2d' => $ar_I[$diaban->madvbc][1]['solieu']['hesophucapbq_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['hesophucapbq_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['hesophucapbq_2d'],
                    'tyledonggop_2d' => $ar_I[$diaban->madvbc][1]['solieu']['tyledonggop_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['tyledonggop_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['tyledonggop_2d'],
                    // 'soluongdinhbien_2d' => $ar_I[$diaban->madvbc][1]['solieu']['soluongdinhbien_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['soluongdinhbien_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['soluongdinhbien_2d'],
                    'soluongdinhbien_2d' => 0,
                    'tongsodinhbien_2d' => $ar_I[$diaban->madvbc][1]['solieu']['tongsodinhbien_2d'] + $ar_I[$diaban->madvbc][2]['solieu']['tongsodinhbien_2d'] + $ar_I[$diaban->madvbc][3]['solieu']['tongsodinhbien_2d'],
                    'quyluonggiam_2k' => $ar_I[$diaban->madvbc][1]['solieu']['quyluonggiam_2k'] + $ar_I[$diaban->madvbc][2]['solieu']['quyluonggiam_2k'] + $ar_I[$diaban->madvbc][3]['solieu']['quyluonggiam_2k'],
                    'tongquyluonggiam_2k' => $ar_I[$diaban->madvbc][1]['solieu']['tongquyluonggiam_2k'] + $ar_I[$diaban->madvbc][2]['solieu']['tongquyluonggiam_2k'] + $ar_I[$diaban->madvbc][3]['solieu']['tongquyluonggiam_2k'],
                ];
            }

            $inputs['lamtron'] = session('admin')->lamtron;
            //dd($ar_I);
            return view('reports.thongtu78.tinh.mau2d')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2e_tonghop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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

            return view('reports.thongtu78.tinh.mau2e')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM THEO NGHỊ ĐỊNH 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2e(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->orderby('sapxep')->get();

            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();

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
            foreach ($model_donvi_bc as $diaban) {
                $dulieu = $m_nguonkp->where('madvbc',$diaban->madvbc);
                $ar_I[$diaban->madvbc] = array();
                $ar_I[$diaban->madvbc][0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn', 'style' => 'font-weight:bold;');
                //
                $m_nguon_1 = $dulieu->where('phanloaixa', 'XL1');
                $ar_I[$diaban->madvbc][1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                    'tdv' => $m_nguon_1->count(),
                    'mk' => 16,
                    'mk2' => 21,
                    'quyluong34' => round($m_nguon_1->count() * 16 * 1490000),
                    'quyluong33' => round($m_nguon_1->count() * 21 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][1]['solieu']['tong'] = ($ar_I[$diaban->madvbc][1]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][1]['solieu']['quyluong34']) * 5;
                //

                $m_nguon_2 = $dulieu->where('phanloaixa', 'XL2');
                $ar_I[$diaban->madvbc][2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                    'tdv' => $m_nguon_2->count(),
                    'mk' => 13.7,
                    'mk2' => 18,
                    'quyluong34' => round($m_nguon_2->count() * 13.7 * 1490000),
                    'quyluong33' => round($m_nguon_2->count() * 18 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][2]['solieu']['tong'] = ($ar_I[$diaban->madvbc][2]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][2]['solieu']['quyluong34']) * 5;
                //

                $m_nguon_3 = $dulieu->where('phanloaixa', 'XL3');
                $ar_I[$diaban->madvbc][3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                    'tdv' => $m_nguon_3->count(),
                    'mk' => 11.4,
                    'mk2' => 15,
                    'quyluong34' => round($m_nguon_3->count() * 11.4 * 1490000),
                    'quyluong33' => round($m_nguon_3->count() * 15 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][3]['solieu']['tong'] = ($ar_I[$diaban->madvbc][3]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][3]['solieu']['quyluong34']) * 5;
                //Tổng phân loại xã
                $ar_I[$diaban->madvbc][0]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][1]['solieu']['tdv'] + $ar_I[$diaban->madvbc][2]['solieu']['tdv'] + $ar_I[$diaban->madvbc][3]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'quyluong33' => $ar_I[$diaban->madvbc][1]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][2]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][3]['solieu']['quyluong33'],
                    'quyluong34' => $ar_I[$diaban->madvbc][1]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][2]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][3]['solieu']['quyluong34'],
                    'tong' => $ar_I[$diaban->madvbc][1]['solieu']['tong'] + $ar_I[$diaban->madvbc][2]['solieu']['tong'] + $ar_I[$diaban->madvbc][3]['solieu']['tong'],
                ];

                //II = 5+8+13
                $ar_I[$diaban->madvbc][4] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố', 'style' => 'font-weight:bold;');

                //II.1Số xã biên giới, hải đảo (5 = 6 + 7)
                $ar_I[$diaban->madvbc][5] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo',);

                //Thôn thuộc xã biên giới, hải đảo
                $ar_I[$diaban->madvbc][6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $dulieu->sum('sothonbiengioi_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    'quyluong34' => round($dulieu->sum('sothonbiengioi_2d') * 5 * 1490000),
                    'quyluong33' => round($dulieu->sum('sothonbiengioi_2d') * 6 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][6]['solieu']['tong'] = ($ar_I[$diaban->madvbc][6]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][6]['solieu']['quyluong34']) * 5;

                //Tổ dân phố thuộc xã biên giới, hải đảo
                $ar_I[$diaban->madvbc][7] = array('val' => '', 'tt' => '', 'noidung' => '- Tổ dân phố thuộc xã biên giới, hải đảo', 'solieu' => [
                    'tdv' => $dulieu->sum('sotodanphobiengioi_2d'),
                    'mk' => 0,
                    'mk2' => 6,
                    'quyluong34' => 0,
                    'quyluong33' => round($dulieu->sum('sotodanphobiengioi_2d') * 6 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][7]['solieu']['tong'] = ($ar_I[$diaban->madvbc][7]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][7]['solieu']['quyluong34']) * 5;

                //Số liệu II.1 (5 = 6 + 7)
                $ar_I[$diaban->madvbc][5]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][6]['solieu']['tdv'] + $ar_I[$diaban->madvbc][7]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'quyluong34' =>  $ar_I[$diaban->madvbc][6]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][7]['solieu']['quyluong34'],
                    'quyluong33' =>  $ar_I[$diaban->madvbc][6]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][7]['solieu']['quyluong33'],
                    'tong' =>  $ar_I[$diaban->madvbc][6]['solieu']['tong'] + $ar_I[$diaban->madvbc][7]['solieu']['tong'],
                ];

                //II.2  8 = 9 + 10 + 11 + 12
                $ar_I[$diaban->madvbc][8] = array('val' => 'TDBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan thẩm quyền',);

                //Số hộ 350 trở lên
                $ar_I[$diaban->madvbc][9] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => 'Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $dulieu->sum('sothon350hgd_2d'),
                    'mk' => 5,
                    'mk2' => 6,
                    'quyluong34' => round($dulieu->sum('sothon350hgd_2d') * 5 * 1490000),
                    'quyluong33' => round($dulieu->sum('sothon350hgd_2d') * 6 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][9]['solieu']['tong'] = ($ar_I[$diaban->madvbc][9]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][9]['solieu']['quyluong34']) * 5;

                //500 hộ trở lên
                $ar_I[$diaban->madvbc][10] = array('val' => 'TK,TDP', 'tt' => '', 'noidung' => 'Số tổ dân phố có từ 500 hộ gia đình trở lên', 'solieu' => [
                    'tdv' => $dulieu->sum('sotodanpho500hgd_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    'quyluong34' => round($dulieu->sum('sotodanpho500hgd_2d') * 3 * 1490000),
                    'quyluong33' => round($dulieu->sum('sotodanpho500hgd_2d') * 6 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][10]['solieu']['tong'] = ($ar_I[$diaban->madvbc][10]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][10]['solieu']['quyluong34']) * 5;

                //Tổ dân phố thuộc xã trọng điểm về an ninh
                $ar_I[$diaban->madvbc][11] = array('val' => 'TK', 'tt' => '', 'noidung' => 'Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                    'tdv' => $dulieu->sum('sothontrongdiem_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    'quyluong34' => round($dulieu->sum('sothontrongdiem_2d') * 3 * 1490000),
                    'quyluong33' => round($dulieu->sum('sothontrongdiem_2d') * 6 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][11]['solieu']['tong'] = ($ar_I[$diaban->madvbc][11]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][11]['solieu']['quyluong34']) * 5;

                //Tổ dân phố chuyển từ thôn
                $ar_I[$diaban->madvbc][12] = array('val' => 'TDP', 'tt' => '', 'noidung' => 'Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã', 'solieu' => [
                    'tdv' => $dulieu->sum('sochuyentuthon350hgd_2d'),
                    'mk' => 3,
                    'mk2' => 6,
                    'quyluong34' => round($dulieu->sum('sochuyentuthon350hgd_2d') * 3 * 1490000),
                    'quyluong33' => round($dulieu->sum('sochuyentuthon350hgd_2d') * 6 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][12]['solieu']['tong'] = ($ar_I[$diaban->madvbc][12]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][12]['solieu']['quyluong34']) * 5;
                //Số liệu II.2 8 = 9 + 10 + 11 + 12
                $ar_I[$diaban->madvbc][8]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][9]['solieu']['tdv'] + $ar_I[$diaban->madvbc][10]['solieu']['tdv'] + $ar_I[$diaban->madvbc][11]['solieu']['tdv'] + $ar_I[$diaban->madvbc][12]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'quyluong34' =>  $ar_I[$diaban->madvbc][9]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][10]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][11]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][12]['solieu']['quyluong34'],
                    'quyluong33' =>  $ar_I[$diaban->madvbc][9]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][10]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][11]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][12]['solieu']['quyluong33'],
                    'tong' =>  $ar_I[$diaban->madvbc][9]['solieu']['tong'] + $ar_I[$diaban->madvbc][10]['solieu']['tong'] + $ar_I[$diaban->madvbc][11]['solieu']['tong'] + $ar_I[$diaban->madvbc][12]['solieu']['tong'],
                ];

                //II.3 13 = 14 + 15
                $ar_I[$diaban->madvbc][13] = array('val' => 'TDBKK', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại',);

                //Thôn còn lại
                $ar_I[$diaban->madvbc][14] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                    'tdv' => $dulieu->sum('sothonconlai_2d'),
                    'mk' => 3,
                    'mk2' => 4.5,
                    'quyluong34' => round($dulieu->sum('sothonconlai_2d') * 3 * 1490000),
                    'quyluong33' => round($dulieu->sum('sothonconlai_2d') * 4.5 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][14]['solieu']['tong'] = ($ar_I[$diaban->madvbc][14]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][14]['solieu']['quyluong34']) * 5;
                //Tổ dân phố
                $ar_I[$diaban->madvbc][15] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                    'tdv' => $dulieu->sum('sotoconlai_2d'),
                    'mk' => 3,
                    'mk2' => 4.5,
                    'quyluong34' => round($dulieu->sum('sotoconlai_2d') * 3 * 1490000),
                    'quyluong33' => round($dulieu->sum('sotoconlai_2d') * 4.5 * 1490000),
                ]);
                $ar_I[$diaban->madvbc][15]['solieu']['tong'] = ($ar_I[$diaban->madvbc][15]['solieu']['quyluong33'] - $ar_I[$diaban->madvbc][15]['solieu']['quyluong34']) * 5;
                //Số liệu II.3  13 = 14 + 15
                $ar_I[$diaban->madvbc][13]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][14]['solieu']['tdv'] + $ar_I[$diaban->madvbc][15]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'quyluong34' =>  $ar_I[$diaban->madvbc][14]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][15]['solieu']['quyluong34'],
                    'quyluong33' =>  $ar_I[$diaban->madvbc][14]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][15]['solieu']['quyluong33'],
                    'tong' =>  $ar_I[$diaban->madvbc][14]['solieu']['tong'] + $ar_I[$diaban->madvbc][15]['solieu']['tong'],
                ];

                //II = 5+8+13 
                $ar_I[$diaban->madvbc][4]['solieu'] = [
                    'tdv' =>  $ar_I[$diaban->madvbc][5]['solieu']['tdv'] + $ar_I[$diaban->madvbc][8]['solieu']['tdv'] + $ar_I[$diaban->madvbc][13]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'quyluong34' =>  $ar_I[$diaban->madvbc][5]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][8]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][13]['solieu']['quyluong34'],
                    'quyluong33' =>  $ar_I[$diaban->madvbc][5]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][8]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][13]['solieu']['quyluong33'],
                    'tong' =>  $ar_I[$diaban->madvbc][5]['solieu']['tong'] + $ar_I[$diaban->madvbc][8]['solieu']['tong'] + $ar_I[$diaban->madvbc][13]['solieu']['tong'],
                ];

                $a_It[$diaban->madvbc] = array(
                    'tdv' =>  $ar_I[$diaban->madvbc][0]['solieu']['tdv'] + $ar_I[$diaban->madvbc][4]['solieu']['tdv'],
                    'mk' => 0,
                    'mk2' => 0,
                    'quyluong34' =>  $ar_I[$diaban->madvbc][0]['solieu']['quyluong34'] + $ar_I[$diaban->madvbc][4]['solieu']['quyluong34'],
                    'quyluong33' =>  $ar_I[$diaban->madvbc][0]['solieu']['quyluong33'] + $ar_I[$diaban->madvbc][4]['solieu']['quyluong33'],
                    'tong' =>  $ar_I[$diaban->madvbc][0]['solieu']['tong'] + $ar_I[$diaban->madvbc][4]['solieu']['tong'],
                );
            }

            //dd($ar_I);
            return view('reports.thongtu78.tinh.mau2e')
                ->with('m_dv', $m_donvi)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM THEO NGHỊ ĐỊNH 33/2023/NĐ-CP');
        } else
            return view('errors.notlogin');
    }
}
