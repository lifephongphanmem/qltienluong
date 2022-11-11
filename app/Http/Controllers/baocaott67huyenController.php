<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\chitieubienche;
use App\dmdiabandbkk;
use App\dmdiabandbkk_chitiet;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\hosocanbo;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_huyen_baocao;
use App\nguonkinhphi_huyen_baocao_chitiet;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_huyen;
use App\dmphanloaidonvi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController as data;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class baocaott67huyenController extends Controller
{
    function mau2a1_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $luongcb = $model_thongtu->muccu / $model_thongtu->mucapdung;
            $ngayapdung = new Carbon($model_thongtu->ngayapdung);
            $nam = date_format($ngayapdung, 'Y');
            $thang = date_format($ngayapdung, 'm');
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {

                    $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('macqcq', $inputs['madv'])->get();
                    $model_donvi = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                    $model_bienche = chitieubienche::where('nam', $nam)->wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                } else {
                    $model_donvi = dmdonvi::where('madv', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', $nam)->where('madv', $madv)->get();
                    $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('madv', $inputs['madv'])->get();
                }
            } else {
                $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq', session('admin')->madv)->get();
                $model_donvi = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                $model_bienche = chitieubienche::where('nam', $nam)->wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            }
            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }

            if (session('admin')->phamvitonghop == "KHOI") {
                $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq', session('admin')->madv)->get();
            }
            $model_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($model_tonghop->toarray(), 'masodv'))
                ->where('nam', $nam)->where('thang', $thang)->get();
            if (session('admin')->username == 'khthso') {
                $model_bienche = chitieubienche::join('dmdonvi', 'dmdonvi.madv', '=', 'chitieubienche.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->where('chitieubienche.nam', '2019')->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 1390000 / 1490000;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            $a_linhvuc = array_column($model_tonghop->toarray(), 'linhvuchoatdong', 'masodv');
            $a_phucap = getColTongHop();
            $a_pchien = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pck');
            $a_pc = array_diff($a_phucap, $a_pchien);
            foreach ($model_tonghop_ct as $ct) {
                if ($inputs['madv'] != "" && count($chekdv) > 0) {
                    $tonghop = $model_tonghop->where('masodv', $ct->masodv)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                } else {
                    $tonghop = $model_tonghop->where('masodv', $ct->masodv)->first();
                    $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
                }
                $ct->madv = $model_tonghop->where('masodv', $ct->masodv)->first()->madv;
                $ct->tendv = $model_donvi->where('madv', $ct->madv)->first()->tendv;
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                foreach ($a_pc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->pck += $ct->$pc;
                        $ct->st_pck += round($ct->$pc * $model_thongtu->muccu);
                    } else {
                        $ct->st_pck += $ct->$pc_st;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->$pc_st = round($ct->$pc * $model_thongtu->muccu);
                    } else {
                        //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                        $ct->$pc = 0;
                    }
                }
                $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            }
            $model_tonghop_ct = $model_tonghop_ct->wherein('mact', getMaCongTacNhuCau());
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE')->where('maphanloai', '<>', 'KVXP');
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_Igr[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_Igr[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_Igr[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_Igr[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_Igr[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_Igr[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_Igr[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_Igr[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            } else {
                $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_I[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_I[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_I[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            }
            $a_It = array(
                'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
                'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
                'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'pcdbqh' => 0, 'tongpc' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0,
            );
            //thiếu chỉ tiêu biên chế
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    foreach ($a_pchien as $pc) {
                        $ar_I[$i][$pc] = 0;
                    }
                    $ar_I[$i]['tongpc'] = 0;
                    $ar_I[$i]['ttbh_dv'] = 0;
                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                        $m_dvct = $model_donvi->wherein('madv', a_unique(array_column($chitiet->toarray(), 'madv')));
                    }
                    $d = 1;
                    $luugr = $i;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        $m_dvct = a_unique(array_column($m_dvct->toarray(), 'madv'));
                        foreach ($m_dvct as $dv) {
                            $thongtin = $chitiet->where('madv', $dv);
                            //foreach ($thongtin as $ttchitiet) {
                            $d++;
                            $i += $d;
                            $ar_I[$i]['tt'] = '+';
                            $ar_I[$i]['noidung'] = $model_donvi->where('madv', $dv)->first()->tendv;
                            $ar_I[$i]['soluongduocgiao'] = $model_bienche->where('linhvuchoatdong', $ar_Igr[$j]['val'])->where('madv', $dv)->sum('soluongduocgiao');
                            $ar_I[$i]['soluongbienche'] = count($thongtin);
                            $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                            $tongpc = 0;
                            foreach ($a_pchien as $pc) {
                                $pc_st = 'st_' . $pc;
                                $ar_I[$i][$pc] = $thongtin->sum($pc_st);
                                $a_It[$pc] += $ar_I[$i][$pc];
                                $tongpc += $thongtin->sum($pc_st);
                            }

                            $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                            $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$i]['ttbh_dv'] = $thongtin->sum('ttbh_dv');
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                            foreach ($a_pchien as $pc) {
                                $ar_I[$luugr][$pc] += $ar_I[$i][$pc];
                            }
                            /*
                                $ar_I[$luugr]['heso'] += $ar_I[$i]['heso'];
                                $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                                $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                                $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                                $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                                $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                                $ar_I[$luugr]['pctnn'] += $ar_I[$i]['pctnn'];
                                $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                                $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                                $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                                $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                                */
                            $ar_I[$luugr]['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $ar_I[$luugr]['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //}
                        }
                    } else {
                        $ar_I[$i]['soluongduocgiao'] = 0;
                        $ar_I[$i]['soluongbienche'] = 0;
                        foreach ($a_pchien as $pc) {
                            $ar_I[$i][$pc] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $ar_I[$gddt][$pc] = $ar_I[$giaoduc][$pc] + $ar_I[$daotao][$pc];
                }
                $ar_I[$gddt]['soluongduocgiao'] = $ar_I[$giaoduc]['soluongduocgiao'] + $ar_I[$daotao]['soluongduocgiao'];
                $ar_I[$gddt]['soluongbienche'] = $ar_I[$giaoduc]['soluongbienche'] + $ar_I[$daotao]['soluongbienche'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];
                foreach ($a_pchien as $pc) {
                    $ar_I[$qlnnddt][$pc] = $ar_I[$qlnn][$pc] + $ar_I[$ddt][$pc];
                }
                $ar_I[$qlnnddt]['soluongduocgiao'] = $ar_I[$qlnn]['soluongduocgiao'] + $ar_I[$ddt]['soluongduocgiao'];
                $ar_I[$qlnnddt]['soluongbienche'] = $ar_I[$qlnn]['soluongbienche'] + $ar_I[$ddt]['soluongbienche'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }

                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        $ar_I[$i]['soluongduocgiao'] = $model_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                        $ar_I[$i]['soluongbienche'] = count($chitiet);
                        $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                        $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                        $tongpc = 0;
                        foreach ($a_pchien as $pc) {
                            $pc_st = 'st_' . $pc;
                            $ar_I[$i][$pc] = $chitiet->sum($pc_st);
                            $a_It[$pc] += $ar_I[$i][$pc];
                            $tongpc += $chitiet->sum($pc_st);
                        }
                        $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                        $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                        $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                    } else {
                        foreach ($a_pchien as $pc) {
                            $ar_I[$i][$pc] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $ar_I[11][$pc] = $ar_I[12][$pc] + $ar_I[13][$pc];
                    $ar_I[0][$pc] = $ar_I[1][$pc] + $ar_I[2][$pc];
                }
                $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
                $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
            }
            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai', 'KVXP');
            if (count($model_bangluong_ct) > 0) {
                $tongpc = 0;
                foreach ($a_pchien as $pc) {
                    $pc_st = 'st_' . $pc;
                    $ar_II[$pc] = $model_bangluong_ct->sum($pc_st);
                    $tongpc += $model_bangluong_ct->sum($pc_st);
                }
                $ar_II['tongpc'] = $tongpc;
                $ar_II['ttbh_dv'] = $model_bangluong_ct->sum('ttbh_dv');
            } else {

                foreach ($a_pchien as $pc) {
                    $ar_II[$pc] = 0;
                }
                $ar_II['tongpc'] = 0;
                $ar_II['ttbh_dv'] = 0;
            }
            //dd($ar_II);
            $ar_III = array();
            $ar_III[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Cấp tỉnh', 'tongso' => '0');
            $ar_III[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Cấp huyện', 'tongso' => '0');
            $ar_III[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Cấp xã', 'tongso' => '0');

            $ar_IV = array();
            $ar_IV[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Ủy viên cấp tỉnh', 'tongso' => '0');
            $ar_IV[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Ủy viên cấp huyện', 'tongso' => '0');
            $ar_IV[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Ủy viên cấp xã', 'tongso' => '0');

            $a_IIIt = array('tongso' => 0);
            $a_IVt = array('tongso' => 0);
            for ($i = 0; $i < count($ar_III); $i++) {
                $chitiet = $model_bangluong_ct->where('caphanhchinh', $ar_III[$i]['val']);
                //if($ar_III[$i]['val'] == $model_donvi->caphanhchinh){
                $ar_III[$i]['tongso'] = $chitiet->sum('pcdbqh');
                //}
                $a_IIIt['tongso'] += $ar_III[$i]['tongso'];
            }

            for ($i = 0; $i < count($ar_IV); $i++) {
                $chitiet = $model_bangluong_ct->where('caphanhchinh', $ar_III[$i]['val']);
                //if($ar_IV[$i]['val'] == $model_donvi->caphanhchinh){
                $ar_IV[$i]['tongso'] = $chitiet->sum('pcvk');
                //}
                $a_IVt['tongso'] += $ar_IV[$i]['tongso'];
            }
            if (isset($inputs['excel'])) {
                Excel::create('Mau2a1_TT46', function ($excel) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                        $sheet->loadView('reports.thongtu67.mau2a1_tt46')
                            ->with('ar_I', $ar_I)
                            ->with('ar_II', $ar_II)
                            ->with('ar_III', $ar_III)
                            ->with('ar_IV', $ar_IV)
                            ->with('a_It', $a_It)
                            ->with('a_IIIt', $a_IIIt)
                            ->with('a_IVt', $a_IVt)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'Mau2a1_TT46');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.thongtu67.mau2a1_tt46')
                    ->with('furl', '/tong_hop_bao_cao/')
                    ->with('ar_I', $ar_I)
                    ->with('ar_II', $ar_II)
                    ->with('ar_III', $ar_III)
                    ->with('ar_IV', $ar_IV)
                    ->with('a_It', $a_It)
                    ->with('a_IIIt', $a_IIIt)
                    ->with('a_IVt', $a_IVt)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2a1_huyen_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $model_donvi = dmdonvi::where('macqcq', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2019')->wherein('madv', function ($qr) use ($madv) {
                        $qr->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct()->get();
                    })->get();
                } else {
                    $model_donvi = dmdonvi::where('madv', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2019')->where('madv', $madv)->get();
                }
            } else {
                $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $model_bienche = chitieubienche::where('nam', '2019')->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
                })->get();
            }
            //dd($model_donvi->toarray());
            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            if ($inputs['madv'] != "") {
                if (count($chekdv) > 0) {
                    $model_tonghop = tonghopluong_huyen::where('thang', '07')->where('nam', '2019')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.macqcq', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                } else {
                    $model_tonghop = tonghopluong_donvi::where('thang', '07')->where('nam', '2019')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.madv', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                    dd($model_tonghop_ct->toarray());
                }
            } else {
                $model_tonghop = tonghopluong_donvi::where('thang', '07')->where('nam', '2019')
                    ->where('madvbc', $madvbc)->get();
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->where('tonghopluong_donvi.madvbc', $madvbc)
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }

            $luongcb = 0.935;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop_ct->toarray());

            if (session('admin')->username == 'khthso') {
                $model_bienche = chitieubienche::join('dmdonvi', 'dmdonvi.madv', '=', 'chitieubienche.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->where('chitieubienche.nam', '2019')->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            foreach ($model_tonghop_ct as $ct) {
                if ($inputs['madv'] != "" && count($chekdv) > 0) {
                    $tonghop = $model_tonghop->where('mathdv', $ct->mathh)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                } else {
                    $tonghop = $model_tonghop->where('mathdv', $ct->mathdv)->first();
                    $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
                }
                //$a_th =  array_column($tonghop->toarray(),'mathdv','maphanloai');
                //$ct->maphanloai =  $a_th[$ct->mathdv];
                $ct->heso = $ct->heso * $luongcb;
                $ct->pckv = $ct->pckv * $luongcb;
                $ct->pccv = $ct->pccv * $luongcb;
                $ct->pctnvk = $ct->pctnvk * $luongcb;
                $ct->pcudn = $ct->pcudn * $luongcb;
                $ct->pcth = $ct->pcth * $luongcb;
                $ct->pctn = $ct->pctn * $luongcb;
                $ct->pccovu = $ct->pccovu * $luongcb;
                $ct->pcdang = $ct->pcdang * $luongcb;
                $ct->pcthni = $ct->pcthni * $luongcb;
                $ct->pck = $ct->pck * $luongcb;
                $ct->pcdbqh = $ct->pcdbqh * $luongcb;
                $ct->pcvk = $ct->pcvk * $luongcb;
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE')->where('maphanloai', '<>', 'KVXP');

            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_Igr[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_Igr[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_Igr[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_Igr[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_Igr[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_Igr[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_Igr[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_Igr[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            } else {
                $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_I[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_I[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_I[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            }
            $a_It = array(
                'heso' => 0,
                'pckv' => 0,
                'pccv' => 0,
                'pctnvk' => 0,
                'pcudn' => 0,
                'pcth' => 0,
                'pctnn' => 0,
                'pccovu' => 0,
                'pcdang' => 0,
                'pcthni' => 0,
                'pck' => 0,
                'tongpc' => 0,
                'ttbh_dv' => 0,
                'soluongduocgiao' => 0,
                'soluongbienche' => 0,
            );
            //thiếu chỉ tiêu biên chế
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    $ar_I[$i]['heso'] = 0;
                    $ar_I[$i]['pckv'] = 0;
                    $ar_I[$i]['pccv'] = 0;
                    $ar_I[$i]['pctnvk'] = 0;
                    $ar_I[$i]['pcudn'] = 0;
                    $ar_I[$i]['pcth'] = 0;
                    $ar_I[$i]['pctnn'] = 0;
                    $ar_I[$i]['pccovu'] = 0;
                    $ar_I[$i]['pcdang'] = 0;
                    $ar_I[$i]['pcthni'] = 0;
                    $ar_I[$i]['pck'] = 0;
                    $ar_I[$i]['tongpc'] = 0;
                    $ar_I[$i]['ttbh_dv'] = 0;
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                    }
                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    $d = 1;
                    $luugr = $i;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        $thongtin = $chitiet->toArray();
                        //dd($thongtin);

                        foreach ($thongtin as $thongtinchitiet) {
                            $d++;
                            $i += $d;
                            $ar_I[$i]['tt'] = '+';
                            $ar_I[$i]['noidung'] = $thongtinchitiet['nguoigui'];
                            $ar_I[$i]['soluongduocgiao'] = 0;
                            $ar_I[$i]['soluongbienche'] = 0;
                            $ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                            $a_It['soluongduocgiao'] += $ar_I[$i - $d]['soluongduocgiao'];

                            $ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                            $a_It['soluongbienche'] += $ar_I[$i - $d]['soluongbienche'];
                            $tongpc = 0;
                            $ar_I[$i]['heso'] = $thongtinchitiet['heso'];
                            $a_It['heso'] += $ar_I[$i]['heso'];

                            $ar_I[$i]['pckv'] = $thongtinchitiet['pckv'];
                            $tongpc += $ar_I[$i]['pckv'];
                            $a_It['pckv'] += $ar_I[$i]['pckv'];

                            $ar_I[$i]['pccv'] = $thongtinchitiet['pccv'];
                            $tongpc += $ar_I[$i]['pccv'];
                            $a_It['pccv'] += $ar_I[$i]['pccv'];

                            $ar_I[$i]['pctnvk'] = $thongtinchitiet['pctnvk'];
                            $tongpc += $ar_I[$i]['pctnvk'];
                            $a_It['pctnvk'] += $ar_I[$i]['pctnvk'];

                            $ar_I[$i]['pcudn'] = $thongtinchitiet['pcudn'];
                            $tongpc += $ar_I[$i]['pcudn'];
                            $a_It['pcudn'] += $ar_I[$i]['pcudn'];

                            $ar_I[$i]['pcth'] = $thongtinchitiet['pcth'];
                            $tongpc += $ar_I[$i]['pcth'];
                            $a_It['pcth'] += $ar_I[$i]['pcth'];

                            $ar_I[$i]['pctnn'] = $thongtinchitiet['pctnn'];
                            $tongpc += $ar_I[$i]['pctnn'];
                            $a_It['pctnn'] += $ar_I[$i]['pctnn'];

                            $ar_I[$i]['pccovu'] = $thongtinchitiet['pccovu'];
                            $tongpc += $ar_I[$i]['pccovu'];
                            $a_It['pccovu'] += $ar_I[$i]['pccovu'];

                            $ar_I[$i]['pcdang'] = $thongtinchitiet['pcdang'];
                            $tongpc += $ar_I[$i]['pcdang'];
                            $a_It['pcdang'] += $ar_I[$i]['pcdang'];

                            $ar_I[$i]['pcthni'] = $thongtinchitiet['pcthni'];
                            $tongpc += $ar_I[$i]['pcthni'];
                            $a_It['pcthni'] += $ar_I[$i]['pcthni'];

                            $ar_I[$i]['pck'] = $thongtinchitiet['pck'];
                            $tongpc += $ar_I[$i]['pck'];
                            $a_It['pck'] += $ar_I[$i]['pck'];

                            $ar_I[$i]['tongpc'] = $tongpc;
                            $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                            $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['heso'] + $ar_I[$i]['pccv']) * 0.24);
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //$ar_II['ttbh_dv'] =round(($ar_II['heso'] + $ar_II['pccv'])*0.24);

                            $ar_I[$luugr]['heso'] += $ar_I[$i]['heso'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctnn'] += $ar_I[$i]['pctnn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        }
                    } else {
                        $ar_I[$i]['soluongduocgiao'] = 0;
                        $ar_I[$i]['soluongbienche'] = 0;
                        $ar_I[$i]['heso'] = 0;
                        $ar_I[$i]['pckv'] = 0;
                        $ar_I[$i]['pccv'] = 0;
                        $ar_I[$i]['pctnvk'] = 0;
                        $ar_I[$i]['pcudn'] = 0;
                        $ar_I[$i]['pcth'] = 0;
                        $ar_I[$i]['pctnn'] = 0;
                        $ar_I[$i]['pccovu'] = 0;
                        $ar_I[$i]['pcdang'] = 0;
                        $ar_I[$i]['pcthni'] = 0;
                        $ar_I[$i]['pck'] = 0;
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                    }
                }

                $ar_I[$gddt]['heso'] = $ar_I[$giaoduc]['heso'] + $ar_I[$daotao]['heso'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctnn'] = $ar_I[$giaoduc]['pctnn'] + $ar_I[$daotao]['pctnn'];
                $ar_I[$gddt]['pccovu'] = $ar_I[$giaoduc]['pccovu'] + $ar_I[$daotao]['pccovu'];
                $ar_I[$gddt]['pcdang'] = $ar_I[$giaoduc]['pcdang'] + $ar_I[$daotao]['pcdang'];
                $ar_I[$gddt]['pcthni'] = $ar_I[$giaoduc]['pcthni'] + $ar_I[$daotao]['pcthni'];
                $ar_I[$gddt]['pck'] = $ar_I[$giaoduc]['pck'] + $ar_I[$daotao]['pck'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];

                $ar_I[$qlnnddt]['heso'] = $ar_I[$qlnn]['heso'] + $ar_I[$ddt]['heso'];
                $ar_I[$qlnnddt]['pckv'] = $ar_I[$qlnn]['pckv'] + $ar_I[$ddt]['pckv'];
                $ar_I[$qlnnddt]['pccv'] = $ar_I[$qlnn]['pccv'] + $ar_I[$ddt]['pccv'];
                $ar_I[$qlnnddt]['pctnvk'] = $ar_I[$qlnn]['pctnvk'] + $ar_I[$ddt]['pctnvk'];
                $ar_I[$qlnnddt]['pcudn'] = $ar_I[$qlnn]['pcudn'] + $ar_I[$ddt]['pcudn'];
                $ar_I[$qlnnddt]['pcth'] = $ar_I[$qlnn]['pcth'] + $ar_I[$ddt]['pcth'];
                $ar_I[$qlnnddt]['pctnn'] = $ar_I[$qlnn]['pctnn'] + $ar_I[$ddt]['pctnn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }

                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                        $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                        //$ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                        $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                        $tongpc = 0;
                        $ar_I[$i]['heso'] = $chitiet->sum('heso');
                        $a_It['heso'] += $ar_I[$i]['heso'];

                        $ar_I[$i]['pckv'] = $chitiet->sum('pckv');
                        $tongpc += $ar_I[$i]['pckv'];
                        $a_It['pckv'] += $ar_I[$i]['pckv'];

                        $ar_I[$i]['pccv'] = $chitiet->sum('pccv');
                        $tongpc += $ar_I[$i]['pccv'];
                        $a_It['pccv'] += $ar_I[$i]['pccv'];

                        $ar_I[$i]['pctnvk'] = $chitiet->sum('pctnvk');
                        $tongpc += $ar_I[$i]['pctnvk'];
                        $a_It['pctnvk'] += $ar_I[$i]['pctnvk'];

                        $ar_I[$i]['pcudn'] = $chitiet->sum('pcudn');
                        $tongpc += $ar_I[$i]['pcudn'];
                        $a_It['pcudn'] += $ar_I[$i]['pcudn'];

                        $ar_I[$i]['pcth'] = $chitiet->sum('pcth');
                        $tongpc += $ar_I[$i]['pcth'];
                        $a_It['pcth'] += $ar_I[$i]['pcth'];

                        $ar_I[$i]['pctnn'] = $chitiet->sum('pctnn');
                        $tongpc += $ar_I[$i]['pctnn'];
                        $a_It['pctnn'] += $ar_I[$i]['pctnn'];

                        $ar_I[$i]['pccovu'] = $chitiet->sum('pccovu');
                        $tongpc += $ar_I[$i]['pccovu'];
                        $a_It['pccovu'] += $ar_I[$i]['pccovu'];

                        $ar_I[$i]['pcdang'] = $chitiet->sum('pcdang');
                        $tongpc += $ar_I[$i]['pcdang'];
                        $a_It['pcdang'] += $ar_I[$i]['pcdang'];

                        $ar_I[$i]['pcthni'] = $chitiet->sum('pcthni');
                        $tongpc += $ar_I[$i]['pcthni'];
                        $a_It['pcthni'] += $ar_I[$i]['pcthni'];

                        $ar_I[$i]['pck'] = $chitiet->sum('pck');
                        $tongpc += $ar_I[$i]['pck'];
                        $a_It['pck'] += $ar_I[$i]['pck'];

                        $ar_I[$i]['tongpc'] = $tongpc;
                        $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                        $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['heso'] + $ar_I[$i]['pccv']) * 0.24);
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        //$ar_II['ttbh_dv'] =round(($ar_II['heso'] + $ar_II['pccv'])*0.24);
                    } else {
                        $ar_I[$i]['heso'] = 0;
                        $ar_I[$i]['pckv'] = 0;
                        $ar_I[$i]['pccv'] = 0;
                        $ar_I[$i]['pctnvk'] = 0;
                        $ar_I[$i]['pcudn'] = 0;
                        $ar_I[$i]['pcth'] = 0;
                        $ar_I[$i]['pctnn'] = 0;
                        $ar_I[$i]['pccovu'] = 0;
                        $ar_I[$i]['pcdang'] = 0;
                        $ar_I[$i]['pcthni'] = 0;
                        $ar_I[$i]['pck'] = 0;
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                    }
                }

                $ar_I[11]['heso'] = $ar_I[12]['heso'] + $ar_I[13]['heso'];
                $ar_I[11]['pckv'] = $ar_I[12]['pckv'] + $ar_I[13]['pckv'];
                $ar_I[11]['pccv'] = $ar_I[12]['pccv'] + $ar_I[13]['pccv'];
                $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] + $ar_I[13]['pctnvk'];
                $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] + $ar_I[13]['pcudn'];
                $ar_I[11]['pcth'] = $ar_I[12]['pcth'] + $ar_I[13]['pcth'];
                $ar_I[11]['pctnn'] = $ar_I[12]['pctnn'] + $ar_I[13]['pctnn'];
                $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] + $ar_I[13]['pccovu'];
                $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] + $ar_I[13]['pcdang'];
                $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] + $ar_I[13]['pcthni'];
                $ar_I[11]['pck'] = $ar_I[12]['pck'] + $ar_I[13]['pck'];
                $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];

                $ar_I[0]['heso'] = $ar_I[1]['heso'] + $ar_I[2]['heso'];
                $ar_I[0]['pckv'] = $ar_I[1]['pckv'] + $ar_I[2]['pckv'];
                $ar_I[0]['pccv'] = $ar_I[1]['pccv'] + $ar_I[2]['pccv'];
                $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] + $ar_I[2]['pctnvk'];
                $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] + $ar_I[2]['pcudn'];
                $ar_I[0]['pcth'] = $ar_I[1]['pcth'] + $ar_I[2]['pcth'];
                $ar_I[0]['pctnn'] = $ar_I[1]['pctnn'] + $ar_I[2]['pctnn'];
                $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] + $ar_I[2]['pccovu'];
                $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] + $ar_I[2]['pcdang'];
                $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] + $ar_I[2]['pcthni'];
                $ar_I[0]['pck'] = $ar_I[1]['pck'] + $ar_I[2]['pck'];
                $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
            }
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai', 'KVXP');
            //dd($model_bangluong_ct);
            //
            if (count($model_bangluong_ct) > 0) {
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['heso'] = $model_bangluong_ct->sum('heso');

                $ar_II['pckv'] = $model_bangluong_ct->sum('pckv');
                $tongpc += $ar_II['pckv'];

                $ar_II['pccv'] = $model_bangluong_ct->sum('pccv');
                $tongpc += $ar_II['pccv'];

                $ar_II['pctnvk'] = $model_bangluong_ct->sum('pctnvk');
                $tongpc += $ar_II['pctnvk'];

                $ar_II['pcudn'] = $model_bangluong_ct->sum('pcudn');
                $tongpc += $ar_II['pcudn'];

                $ar_II['pcth'] = $model_bangluong_ct->sum('pcth');
                $tongpc += $ar_II['pcth'];

                $ar_II['pctnn'] = $model_bangluong_ct->sum('pctnn');
                $tongpc += $ar_II['pctnn'];

                $ar_II['pccovu'] = $model_bangluong_ct->sum('pccovu');
                $tongpc += $ar_II['pccovu'];

                $ar_II['pcdang'] = $model_bangluong_ct->sum('pcdang');
                $tongpc += $ar_II['pcdang'];

                $ar_II['pcthni'] = $model_bangluong_ct->sum('pcthni');
                $tongpc += $ar_II['pcthni'];

                $ar_II['pck'] = $model_bangluong_ct->sum('pck');
                $tongpc += $ar_II['pck'];

                $ar_II['tongpc'] = $tongpc;

                $ar_II['ttbh_dv'] = $model_bangluong_ct->sum('ttbh_dv');
            } else {
                $ar_II['heso'] = 0;
                $ar_II['pckv'] = 0;
                $ar_II['pccv'] = 0;
                $ar_II['pctnvk'] = 0;
                $ar_II['pcudn'] = 0;
                $ar_II['pcth'] = 0;
                $ar_II['pctnn'] = 0;
                $ar_II['pccovu'] = 0;
                $ar_II['pcdang'] = 0;
                $ar_II['pcthni'] = 0;
                $ar_II['pck'] = 0;
                $ar_II['tongpc'] = 0;
                $ar_II['ttbh_dv'] = 0;
            }
            //dd($ar_II);

            //căn cứ vào cấp dự toán để xác định đơn vị cấp xã, huyện, tỉnh
            //chỉ có cột tổng cộng
            $ar_III = array();
            $ar_III[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Cấp tỉnh', 'tongso' => '0');
            $ar_III[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Cấp huyện', 'tongso' => '0');
            $ar_III[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Cấp xã', 'tongso' => '0');

            $ar_IV = array();
            $ar_IV[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Ủy viên cấp tỉnh', 'tongso' => '0');
            $ar_IV[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Ủy viên cấp huyện', 'tongso' => '0');
            $ar_IV[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Ủy viên cấp xã', 'tongso' => '0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso' => 0);
            $a_IVt = array('tongso' => 0);
            //dd(session('admin')->maphanloai);
            if (session('admin')->level == 'T') {
                if ($m_dv->capdonvi > 2) {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                } else {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            } else {
                if ($m_dv->capdonvi >= 3) {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                } else {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }
            Excel::create('Mau2a1_TT67', function ($excel) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.mau2a1_tt67excel')
                        ->with('ar_I', $ar_I)
                        ->with('ar_II', $ar_II)
                        ->with('ar_III', $ar_III)
                        ->with('ar_IV', $ar_IV)
                        ->with('a_It', $a_It)
                        ->with('a_IIIt', $a_IIIt)
                        ->with('a_IVt', $a_IVt)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'Mau2a1_TT67');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $luongcb = $model_thongtu->muccu / $model_thongtu->mucapdung;
            $ngayapdung = new Carbon($model_thongtu->ngayapdung);
            $nam = date_format($ngayapdung, 'Y');
            $thang = date_format($ngayapdung, 'm');
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {

                    $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('macqcq', $inputs['madv'])->get();
                    $model_donvi = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                    $model_bienche = chitieubienche::where('nam', $nam)->wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                } else {
                    $model_donvi = dmdonvi::where('madv', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', $nam)->where('madv', $madv)->get();
                    $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('madv', $inputs['madv'])->get();
                }
            } else {
                $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('madvbc', $madvbc)->get();
                $model_donvi = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                $model_bienche = chitieubienche::where('nam', $nam)->wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            }
            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }

            if (session('admin')->phamvitonghop == "KHOI") {
                $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq', session('admin')->madv)->get();
            }
            $model_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($model_tonghop->toarray(), 'masodv'))
                ->where('nam', $nam)->where('thang', $thang)->get();
            if (session('admin')->username == 'khthso') {
                $model_bienche = chitieubienche::join('dmdonvi', 'dmdonvi.madv', '=', 'chitieubienche.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->where('chitieubienche.nam', '2019')->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 1390000 / 1490000;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            $a_linhvuc = array_column($model_tonghop->toarray(), 'linhvuchoatdong', 'masodv');
            $a_phucap = getColTongHop();
            $a_pchien = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pck');
            $a_pc = array_diff($a_phucap, $a_pchien);
            //dd($model_tonghop);
            foreach ($model_tonghop_ct as $ct) {
                if ($inputs['madv'] != "" && count($chekdv) > 0) {
                    $tonghop = $model_tonghop->where('masodv', $ct->masodv)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                } else {
                    $tonghop = $model_tonghop->where('masodv', $ct->masodv)->first();
                    $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
                }
                $ct->madv = $model_tonghop->where('masodv', $ct->masodv)->first()->madv;
                $ct->tendv = $model_donvi->where('madv', $ct->madv)->first()->tendv;
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                $ct->caphanhchinh = $model_donvi->where('madv', $ct->madv)->first()->caphanhchinh;
                foreach ($a_pc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->pck += $ct->$pc;
                        $ct->st_pck += round($ct->$pc * $model_thongtu->mucapdung);
                    } else {
                        $ct->st_pck += $ct->$pc_st;
                    }
                }

                foreach ($a_pchien as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->$pc_st = round($ct->$pc * $model_thongtu->mucapdung);
                    } else {
                        //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                        $ct->$pc = 0;
                    }
                }
                $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            }
            $model_tonghop_ct = $model_tonghop_ct->wherein('mact', getMaCongTacNhuCau());
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE')->where('maphanloai', '<>', 'KVXP');
            //dd($model_tonghop_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_Igr[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_Igr[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_Igr[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_Igr[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_Igr[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_Igr[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_Igr[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_Igr[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            } else {
                $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_I[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_I[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_I[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            }
            $a_It = array(
                'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
                'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
                'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'pcdbqh' => 0, 'tongpc' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0, 'chenhlech' => 0,
            );
            //thiếu chỉ tiêu biên chế
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    foreach ($a_pchien as $pc) {
                        $ar_I[$i][$pc] = 0;
                    }
                    $ar_I[$i]['tongpc'] = 0;
                    $ar_I[$i]['ttbh_dv'] = 0;
                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    $ar_I[$i]['chenhlech'] = 0;
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                        $m_dvct = $model_donvi->wherein('madv', a_unique(array_column($chitiet->toarray(), 'madv')));
                    }
                    $d = 1;
                    $luugr = $i;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        $m_dvct = a_unique(array_column($m_dvct->toarray(), 'madv'));
                        foreach ($m_dvct as $dv) {
                            $thongtin = $chitiet->where('madv', $dv);
                            //foreach ($thongtin as $ttchitiet) {
                            $d++;
                            $i += $d;
                            $ar_I[$i]['tt'] = '+';
                            $ar_I[$i]['noidung'] = $model_donvi->where('madv', $dv)->first()->tendv;
                            $ar_I[$i]['soluongduocgiao'] = $model_bienche->where('linhvuchoatdong', $ar_Igr[$j]['val'])->where('madv', $dv)->sum('soluongduocgiao');
                            $ar_I[$i]['soluongbienche'] = count($thongtin);
                            $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                            $tongpc = 0;
                            $tonghs = 0;
                            foreach ($a_pchien as $pc) {
                                $pc_st = 'st_' . $pc;
                                $ar_I[$i][$pc] = $thongtin->sum($pc_st);
                                $a_It[$pc] += $ar_I[$i][$pc];
                                $tongpc += $thongtin->sum($pc_st);
                                $tonghs += $thongtin->sum($pc);;
                            }

                            $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                            $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$i]['ttbh_dv'] = $thongtin->sum('ttbh_dv');
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                            // $ar_I[$i]['chenhlech'] = round($tonghs * $model_thongtu->chenhlech
                            //     + ($ar_I[$i]['ttbh_dv'] / $model_thongtu->mucapdung) * $model_thongtu->chenhlech);
                            $ar_I[$i]['chenhlech'] = round(($chitiet->sum('luongtn') + $chitiet->sum('ttbh_dv')));
                            $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                            foreach ($a_pchien as $pc) {
                                $ar_I[$luugr][$pc] = $ar_I[$i][$pc];
                            }
                            /*
                            $ar_I[$luugr]['heso'] += $ar_I[$i]['heso'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctnn'] += $ar_I[$i]['pctnn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            */
                            $ar_I[$luugr]['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $ar_I[$luugr]['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            $ar_I[$luugr]['chenhlech'] += $ar_I[$i]['chenhlech'];
                            //}
                        }
                    } else {
                        $ar_I[$i]['soluongduocgiao'] = 0;
                        $ar_I[$i]['soluongbienche'] = 0;
                        foreach ($a_pchien as $pc) {
                            $ar_I[$i][$pc] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                        $ar_I[$i]['chenhlech'] = 0;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $ar_I[$gddt][$pc] = $ar_I[$giaoduc][$pc] + $ar_I[$daotao][$pc];
                }
                $ar_I[$gddt]['soluongduocgiao'] = $ar_I[$giaoduc]['soluongduocgiao'] + $ar_I[$daotao]['soluongduocgiao'];
                $ar_I[$gddt]['soluongbienche'] = $ar_I[$giaoduc]['soluongbienche'] + $ar_I[$daotao]['soluongbienche'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];
                $ar_I[$gddt]['chenhlech'] = $ar_I[$giaoduc]['chenhlech'] + $ar_I[$daotao]['chenhlech'];

                foreach ($a_pchien as $pc) {
                    $ar_I[$qlnnddt][$pc] = $ar_I[$qlnn][$pc] + $ar_I[$ddt][$pc];
                }
                $ar_I[$qlnnddt]['soluongduocgiao'] = $ar_I[$qlnn]['soluongduocgiao'] + $ar_I[$ddt]['soluongduocgiao'];
                $ar_I[$qlnnddt]['soluongbienche'] = $ar_I[$qlnn]['soluongbienche'] + $ar_I[$ddt]['soluongbienche'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                $ar_I[$qlnnddt]['chenhlech'] = $ar_I[$qlnn]['chenhlech'] + $ar_I[$ddt]['chenhlech'];
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }

                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        $ar_I[$i]['soluongduocgiao'] = $model_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                        $ar_I[$i]['soluongbienche'] = count($chitiet);
                        $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                        $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                        $tongpc = 0;
                        $tonghs = 0;
                        foreach ($a_pchien as $pc) {
                            $pc_st = 'st_' . $pc;
                            $ar_I[$i][$pc] = $chitiet->sum($pc_st);
                            $a_It[$pc] += $ar_I[$i][$pc];
                            $tongpc += $chitiet->sum($pc_st);
                            $tonghs += $chitiet->sum($pc);
                        }
                        $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                        $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                        $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        $ar_I[$i]['chenhlech'] = round($tonghs * $model_thongtu->chenhlech
                            + ($ar_I[$i]['ttbh_dv'] / $model_thongtu->mucapdung) * $model_thongtu->chenhlech);
                        $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];
                    } else {
                        foreach ($a_pchien as $pc) {
                            $ar_I[$i][$pc] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                        $ar_I[$i]['chenhlech'] = 0;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $ar_I[11][$pc] = $ar_I[12][$pc] + $ar_I[13][$pc];
                    $ar_I[0][$pc] = $ar_I[1][$pc] + $ar_I[2][$pc];
                }
                $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];
                $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
                $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] + $ar_I[2]['chenhlech'];
            }
            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai', 'KVXP');
            if (count($model_bangluong_ct) > 0) {
                $tongpc = 0;
                $tonghs = 0;
                foreach ($a_pchien as $pc) {
                    $pc_st = 'st_' . $pc;
                    $ar_II[$pc] = $model_bangluong_ct->sum($pc_st);
                    $tongpc += $model_bangluong_ct->sum($pc_st);
                    $tonghs += $model_bangluong_ct->sum($pc);
                }
                $ar_II['tongpc'] = $tongpc;
                $ar_II['ttbh_dv'] = $model_bangluong_ct->sum('ttbh_dv');
                $ar_II['chenhlech'] = round($tonghs * $model_thongtu->chenhlech
                    + ($ar_II['ttbh_dv'] / $model_thongtu->mucapdung) * $model_thongtu->chenhlech);
            } else {

                foreach ($a_pchien as $pc) {
                    $ar_II[$pc] = 0;
                }
                $ar_II['tongpc'] = 0;
                $ar_II['ttbh_dv'] = 0;
                $ar_II['chenhlech'] = 0;
            }
            //dd($ar_II);

            //căn cứ vào cấp dự toán để xác định đơn vị cấp xã, huyện, tỉnh
            //chỉ có cột tổng cộng
            $ar_III = array();
            $ar_III[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Cấp tỉnh', 'tongso' => '0');
            $ar_III[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Cấp huyện', 'tongso' => '0');
            $ar_III[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Cấp xã', 'tongso' => '0');

            $ar_IV = array();
            $ar_IV[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Ủy viên cấp tỉnh', 'tongso' => '0');
            $ar_IV[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Ủy viên cấp huyện', 'tongso' => '0');
            $ar_IV[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Ủy viên cấp xã', 'tongso' => '0');

            $a_IIIt = array('tongso' => 0, 'chenhlech' => 0);
            $a_IVt = array('tongso' => 0, 'chenhlech' => 0);
            //dd($model_donvi->toarray());
            for ($i = 0; $i < count($ar_III); $i++) {
                $chitiet = $model_bangluong_ct->where('caphanhchinh', $ar_III[$i]['val']);
                //if($ar_III[$i]['val'] == $model_donvi->caphanhchinh){
                $ar_III[$i]['tongso'] = $chitiet->sum('pcdbqh');
                $ar_III[$i]['chenhlech'] = round(($ar_III[$i]['tongso'] / $model_thongtu->mucapdung) * $model_thongtu->chenhlech);
                //}
                $a_IIIt['tongso'] += $ar_III[$i]['tongso'];
                $a_IIIt['chenhlech'] += $ar_III[$i]['chenhlech'];
            }

            for ($i = 0; $i < count($ar_IV); $i++) {
                $chitiet = $model_bangluong_ct->where('caphanhchinh', $ar_III[$i]['val']);
                //if($ar_IV[$i]['val'] == $model_donvi->caphanhchinh){
                $ar_IV[$i]['tongso'] = $model_bangluong_ct->sum('pcvk');
                $ar_IV[$i]['chenhlech'] = round(($ar_IV[$i]['tongso'] / $model_thongtu->mucapdung) * $model_thongtu->chenhlech);
                // }
                $a_IVt['tongso'] += $ar_IV[$i]['tongso'];
                $a_IVt['chenhlech'] += $ar_IV[$i]['chenhlech'];
            }
            if (isset($inputs['excel']))
                Excel::create('mau2a2_tt46', function ($excel) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                        $sheet->loadView('reports.thongtu67.mau2a2_tt46')
                            ->with('ar_I', $ar_I)
                            ->with('ar_II', $ar_II)
                            ->with('ar_III', $ar_III)
                            ->with('ar_IV', $ar_IV)
                            ->with('a_It', $a_It)
                            ->with('a_IIIt', $a_IIIt)
                            ->with('a_IVt', $a_IVt)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'mau2a2_tt46');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            else
                return view('reports.thongtu67.mau2a2_tt46')
                    ->with('furl', '/tong_hop_bao_cao/')
                    ->with('ar_I', $ar_I)
                    ->with('ar_II', $ar_II)
                    ->with('ar_III', $ar_III)
                    ->with('ar_IV', $ar_IV)
                    ->with('a_It', $a_It)
                    ->with('a_IIIt', $a_IIIt)
                    ->with('a_IVt', $a_IVt)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 38/2019/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_huyen_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $model_donvi = dmdonvi::where('macqcq', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2019')->wherein('madv', function ($qr) use ($madv) {
                        $qr->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct()->get();
                    })->get();
                } else {
                    $model_donvi = dmdonvi::where('madv', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2019')->where('madv', $madv)->get();
                }
            } else {
                $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $model_bienche = chitieubienche::where('nam', '2019')->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
                })->get();
            }
            //dd($model_donvi->toarray());
            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            if ($inputs['madv'] != "") {
                if (count($chekdv) > 0) {
                    $model_tonghop = tonghopluong_huyen::where('thang', '07')->where('nam', '2019')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.macqcq', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                } else {
                    $model_tonghop = tonghopluong_donvi::where('thang', '07')->where('nam', '2019')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.madv', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                    dd($model_tonghop_ct->toarray());
                }
            } else {
                $model_tonghop = tonghopluong_donvi::where('thang', '07')->where('nam', '2019')
                    ->where('madvbc', $madvbc)->get();
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->where('tonghopluong_donvi.madvbc', $madvbc)
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }

            $luongcb = 1;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop_ct->toarray());

            if (session('admin')->username == 'khthso') {
                $model_bienche = chitieubienche::join('dmdonvi', 'dmdonvi.madv', '=', 'chitieubienche.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->where('chitieubienche.nam', '2019')->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2019')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            foreach ($model_tonghop_ct as $ct) {
                if ($inputs['madv'] != "" && count($chekdv) > 0) {
                    $tonghop = $model_tonghop->where('mathdv', $ct->mathh)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                } else {
                    $tonghop = $model_tonghop->where('mathdv', $ct->mathdv)->first();
                    $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
                }
                //$a_th =  array_column($tonghop->toarray(),'mathdv','maphanloai');
                //$ct->maphanloai =  $a_th[$ct->mathdv];
                $ct->heso = $ct->heso * $luongcb;
                $ct->pckv = $ct->pckv * $luongcb;
                $ct->pccv = $ct->pccv * $luongcb;
                $ct->pctnvk = $ct->pctnvk * $luongcb;
                $ct->pcudn = $ct->pcudn * $luongcb;
                $ct->pcth = $ct->pcth * $luongcb;
                $ct->pctn = $ct->pctn * $luongcb;
                $ct->pccovu = $ct->pccovu * $luongcb;
                $ct->pcdang = $ct->pcdang * $luongcb;
                $ct->pcthni = $ct->pcthni * $luongcb;
                $ct->pck = $ct->pck * $luongcb;
                $ct->pcdbqh = $ct->pcdbqh * $luongcb;
                $ct->pcvk = $ct->pcvk * $luongcb;
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE')->where('maphanloai', '<>', 'KVXP');

            //dd($model_bangluong_ct->toarray());
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
                'luong' => 0,
                'pckv' => 0,
                'pccv' => 0,
                'pctnvk' => 0,
                'pcudn' => 0,
                'pcth' => 0,
                'pctnn' => 0,
                'pccovu' => 0,
                'pcdang' => 0,
                'pcthni' => 0,
                'pck' => 0,
                'tongpc' => 0,
                'ttbh_dv' => 0,
                'chenhlech' => 0
            );
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    $ar_I[$i]['luong'] = 0;
                    $ar_I[$i]['pckv'] = 0;
                    $ar_I[$i]['pccv'] = 0;
                    $ar_I[$i]['pctnvk'] = 0;
                    $ar_I[$i]['pcudn'] = 0;
                    $ar_I[$i]['pcth'] = 0;
                    $ar_I[$i]['pctnn'] = 0;
                    $ar_I[$i]['pccovu'] = 0;
                    $ar_I[$i]['pcdang'] = 0;
                    $ar_I[$i]['pcthni'] = 0;
                    $ar_I[$i]['pck'] = 0;
                    $ar_I[$i]['tongpc'] = 0;
                    $ar_I[$i]['ttbh_dv'] = 0;
                    $ar_I[$i]['chenhlech'] = 0;
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                    }
                    $d = 1;
                    $luugr = $i;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        $thongtin = $chitiet->toArray();
                        foreach ($thongtin as $thongtinchitiet) {
                            $d++;
                            $i += $d;
                            $ar_I[$i]['tt'] = '+';
                            $ar_I[$i]['noidung'] = $thongtinchitiet['nguoigui'];
                            $tongpc = 0;
                            $ar_I[$i]['luong'] = $thongtinchitiet['heso'] * $luongcb;
                            $a_It['luong'] += $ar_I[$i]['luong'];

                            $ar_I[$i]['pckv'] = $thongtinchitiet['pckv'] * $luongcb;
                            $tongpc += $ar_I[$i]['pckv'];
                            $a_It['pckv'] += $ar_I[$i]['pckv'];

                            $ar_I[$i]['pccv'] = $thongtinchitiet['pccv'] * $luongcb;
                            $tongpc += $ar_I[$i]['pccv'];
                            $a_It['pccv'] += $ar_I[$i]['pccv'];

                            $ar_I[$i]['pctnvk'] = $thongtinchitiet['pctnvk'] * $luongcb;
                            $tongpc += $ar_I[$i]['pctnvk'];
                            $a_It['pctnvk'] += $ar_I[$i]['pctnvk'];

                            $ar_I[$i]['pcudn'] = $thongtinchitiet['pcudn'] * $luongcb;
                            $tongpc += $ar_I[$i]['pcudn'];
                            $a_It['pcudn'] += $ar_I[$i]['pcudn'];

                            $ar_I[$i]['pcth'] = $thongtinchitiet['pcth'] * $luongcb;
                            $tongpc += $ar_I[$i]['pcth'];
                            $a_It['pcth'] += $ar_I[$i]['pcth'];

                            $ar_I[$i]['pctnn'] = $thongtinchitiet['pctnn'] * $luongcb;
                            $tongpc += $ar_I[$i]['pctnn'];
                            $a_It['pctnn'] += $ar_I[$i]['pctnn'];

                            $ar_I[$i]['pccovu'] = $thongtinchitiet['pccovu'] * $luongcb;
                            $tongpc += $ar_I[$i]['pccovu'];
                            $a_It['pccovu'] += $ar_I[$i]['pccovu'];

                            $ar_I[$i]['pcdang'] = $thongtinchitiet['pcdang'] * $luongcb;
                            $tongpc += $ar_I[$i]['pcdang'];
                            $a_It['pcdang'] += $ar_I[$i]['pcdang'];

                            $ar_I[$i]['pcthni'] = $thongtinchitiet['pcthni'] * $luongcb;
                            $tongpc += $ar_I[$i]['pcthni'];
                            $a_It['pcthni'] += $ar_I[$i]['pcthni'];

                            $ar_I[$i]['pck'] = $thongtinchitiet['pck'] * $luongcb;
                            $tongpc += $ar_I[$i]['pck'];
                            $a_It['pck'] += $ar_I[$i]['pck'];

                            $ar_I[$i]['ttbh_dv'] = round($thongtinchitiet['ttbh_dv'] * $luongcb);
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                            $ar_I[$i]['tongpc'] = $tongpc;
                            $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$i]['chenhlech'] = round(($tongpc + $ar_I[$i]['ttbh_dv'] + $ar_I[$i]['luong']) * 90000 / 1390000);
                            $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                            $ar_I[$luugr]['luong'] += $ar_I[$i]['luong'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctnn'] += $ar_I[$i]['pctnn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            $ar_I[$luugr]['chenhlech'] += $ar_I[$i]['chenhlech'];
                        }
                    } else {
                        $ar_I[$i]['luong'] = 0;
                        $ar_I[$i]['pckv'] = 0;
                        $ar_I[$i]['pccv'] = 0;
                        $ar_I[$i]['pctnvk'] = 0;
                        $ar_I[$i]['pcudn'] = 0;
                        $ar_I[$i]['pcth'] = 0;
                        $ar_I[$i]['pctnn'] = 0;
                        $ar_I[$i]['pccovu'] = 0;
                        $ar_I[$i]['pcdang'] = 0;
                        $ar_I[$i]['pcthni'] = 0;
                        $ar_I[$i]['pck'] = 0;
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                        $ar_I[$i]['chenhlech'] = 0;
                    }
                }
                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctnn'] = $ar_I[$giaoduc]['pctnn'] + $ar_I[$daotao]['pctnn'];
                $ar_I[$gddt]['pccovu'] = $ar_I[$giaoduc]['pccovu'] + $ar_I[$daotao]['pccovu'];
                $ar_I[$gddt]['pcdang'] = $ar_I[$giaoduc]['pcdang'] + $ar_I[$daotao]['pcdang'];
                $ar_I[$gddt]['pcthni'] = $ar_I[$giaoduc]['pcthni'] + $ar_I[$daotao]['pcthni'];
                $ar_I[$gddt]['pck'] = $ar_I[$giaoduc]['pck'] + $ar_I[$daotao]['pck'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];
                $ar_I[$gddt]['chenhlech'] = $ar_I[$giaoduc]['chenhlech'] + $ar_I[$daotao]['chenhlech'];

                $ar_I[$qlnnddt]['luong'] = $ar_I[$qlnn]['luong'] + $ar_I[$ddt]['luong'];
                $ar_I[$qlnnddt]['pckv'] = $ar_I[$qlnn]['pckv'] + $ar_I[$ddt]['pckv'];
                $ar_I[$qlnnddt]['pccv'] = $ar_I[$qlnn]['pccv'] + $ar_I[$ddt]['pccv'];
                $ar_I[$qlnnddt]['pctnvk'] = $ar_I[$qlnn]['pctnvk'] + $ar_I[$ddt]['pctnvk'];
                $ar_I[$qlnnddt]['pcudn'] = $ar_I[$qlnn]['pcudn'] + $ar_I[$ddt]['pcudn'];
                $ar_I[$qlnnddt]['pcth'] = $ar_I[$qlnn]['pcth'] + $ar_I[$ddt]['pcth'];
                $ar_I[$qlnnddt]['pctnn'] = $ar_I[$qlnn]['pctnn'] + $ar_I[$ddt]['pctnn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                $ar_I[$qlnnddt]['chenhlech'] = $ar_I[$qlnn]['chenhlech'] + $ar_I[$ddt]['chenhlech'];
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }
                    if (isset($chitiet) > 0) {
                        $tongpc = 0;
                        $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                        $a_It['luong'] += $ar_I[$i]['luong'];


                        $ar_I[$i]['pckv'] = $chitiet->sum('pckv') * $luongcb;
                        $tongpc += $ar_I[$i]['pckv'];
                        $a_It['pckv'] += $ar_I[$i]['pckv'];

                        $ar_I[$i]['pccv'] = $chitiet->sum('pccv') * $luongcb;
                        $tongpc += $ar_I[$i]['pccv'];
                        $a_It['pccv'] += $ar_I[$i]['pccv'];

                        $ar_I[$i]['pctnvk'] = $chitiet->sum('pctnvk') * $luongcb;
                        $tongpc += $ar_I[$i]['pctnvk'];
                        $a_It['pctnvk'] += $ar_I[$i]['pctnvk'];

                        $ar_I[$i]['pcudn'] = $chitiet->sum('pcudn') * $luongcb;
                        $tongpc += $ar_I[$i]['pcudn'];
                        $a_It['pcudn'] += $ar_I[$i]['pcudn'];

                        $ar_I[$i]['pcth'] = $chitiet->sum('pcth') * $luongcb;
                        $tongpc += $ar_I[$i]['pcth'];
                        $a_It['pcth'] += $ar_I[$i]['pcth'];

                        $ar_I[$i]['pctnn'] = $chitiet->sum('pctnn') * $luongcb;
                        $tongpc += $ar_I[$i]['pctnn'];
                        $a_It['pctnn'] += $ar_I[$i]['pctnn'];

                        $ar_I[$i]['pccovu'] = $chitiet->sum('pccovu') * $luongcb;
                        $tongpc += $ar_I[$i]['pccovu'];
                        $a_It['pccovu'] += $ar_I[$i]['pccovu'];

                        $ar_I[$i]['pcdang'] = $chitiet->sum('pcdang') * $luongcb;
                        $tongpc += $ar_I[$i]['pcdang'];
                        $a_It['pcdang'] += $ar_I[$i]['pcdang'];

                        $ar_I[$i]['pcthni'] = $chitiet->sum('pcthni') * $luongcb;
                        $tongpc += $ar_I[$i]['pcthni'];
                        $a_It['pcthni'] += $ar_I[$i]['pcthni'];

                        $ar_I[$i]['pck'] = $chitiet->sum('pck') * $luongcb;
                        $tongpc += $ar_I[$i]['pck'];
                        $a_It['pck'] += $ar_I[$i]['pck'];

                        $ar_I[$i]['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                        $ar_I[$i]['tongpc'] = $tongpc;
                        $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                        $ar_I[$i]['chenhlech'] = round(($tongpc + $ar_I[$i]['ttbh_dv'] + $ar_I[$i]['luong']) * 90000 / 1390000);
                        $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];
                    } else {
                        $ar_I[$i]['luong'] = 0;
                        $ar_I[$i]['pckv'] = 0;
                        $ar_I[$i]['pccv'] = 0;
                        $ar_I[$i]['pctnvk'] = 0;
                        $ar_I[$i]['pcudn'] = 0;
                        $ar_I[$i]['pcth'] = 0;
                        $ar_I[$i]['pctnn'] = 0;
                        $ar_I[$i]['pccovu'] = 0;
                        $ar_I[$i]['pcdang'] = 0;
                        $ar_I[$i]['pcthni'] = 0;
                        $ar_I[$i]['pck'] = 0;
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                        $ar_I[$i]['chenhlech'] = 0;
                    }
                }
                $ar_I[11]['luong'] = $ar_I[12]['luong'] + $ar_I[13]['luong'];
                $ar_I[11]['pckv'] = $ar_I[12]['pckv'] + $ar_I[13]['pckv'];
                $ar_I[11]['pccv'] = $ar_I[12]['pccv'] + $ar_I[13]['pccv'];
                $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] + $ar_I[13]['pctnvk'];
                $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] + $ar_I[13]['pcudn'];
                $ar_I[11]['pcth'] = $ar_I[12]['pcth'] + $ar_I[13]['pcth'];
                $ar_I[11]['pctnn'] = $ar_I[12]['pctnn'] + $ar_I[13]['pctnn'];
                $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] + $ar_I[13]['pccovu'];
                $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] + $ar_I[13]['pcdang'];
                $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] + $ar_I[13]['pcthni'];
                $ar_I[11]['pck'] = $ar_I[12]['pck'] + $ar_I[13]['pck'];
                $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

                $ar_I[0]['luong'] = $ar_I[1]['luong'] + $ar_I[2]['luong'];
                $ar_I[0]['pckv'] = $ar_I[1]['pckv'] + $ar_I[2]['pckv'];
                $ar_I[0]['pccv'] = $ar_I[1]['pccv'] + $ar_I[2]['pccv'];
                $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] + $ar_I[2]['pctnvk'];
                $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] + $ar_I[2]['pcudn'];
                $ar_I[0]['pcth'] = $ar_I[1]['pcth'] + $ar_I[2]['pcth'];
                $ar_I[0]['pctnn'] = $ar_I[1]['pctnn'] + $ar_I[2]['pctnn'];
                $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] + $ar_I[2]['pccovu'];
                $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] + $ar_I[2]['pcdang'];
                $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] + $ar_I[2]['pcthni'];
                $ar_I[0]['pck'] = $ar_I[1]['pck'] + $ar_I[2]['pck'];
                $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
                $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] + $ar_I[2]['chenhlech'];
                //dd($ar_I);
            }
            $ar_II = array();
            /*
            if(isset($model_bangluong_ct)){
                $chitiet = $model_bangluong_ct->where('maphanloai', 'KVXP');
            }
            */
            $chitiet = $model_tonghop_ct->where('maphanloai', 'KVXP');
            //dd($chitiet);
            if (isset($chitiet)) {
                $tongpc = 0;
                $ar_II['luong'] = $chitiet->sum('heso') * $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv') * $luongcb;
                $tongpc += $ar_II['pccv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk') * $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn') * $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth') * $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctnn'] = $chitiet->sum('pctnn') * $luongcb;
                $tongpc += $ar_II['pctnn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu') * $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang') * $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni') * $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc + $ar_II['ttbh_dv'] + $ar_II['luong']) * 90000 / 1390000);
            } else {
                $ar_II['luong'] = 0;
                $ar_II['pckv'] = 0;
                $ar_II['pccv'] = 0;
                $ar_II['pctnvk'] = 0;
                $ar_II['pcudn'] = 0;
                $ar_II['pcth'] = 0;
                $ar_II['pctnn'] = 0;
                $ar_II['pccovu'] = 0;
                $ar_II['pcdang'] = 0;
                $ar_II['pcthni'] = 0;
                $ar_II['pck'] = 0;
                $ar_II['tongpc'] = 0;
                $ar_II['ttbh_dv'] = 0;
                $ar_II['chenhlech'] = 0;
            }
            //dd($ar_II);

            //căn cứ vào cấp dự toán để xác định đơn vị cấp xã, huyện, tỉnh
            //chỉ có cột tổng cộng
            $ar_III = array();
            $ar_III[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Cấp tỉnh', 'tongso' => '0', 'chenhlech' => '0');
            $ar_III[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Cấp huyện', 'tongso' => '0', 'chenhlech' => '0');
            $ar_III[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Cấp xã', 'tongso' => '0', 'chenhlech' => '0');

            $ar_IV = array();
            $ar_IV[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Ủy viên cấp tỉnh', 'tongso' => '0', 'chenhlech' => '0');
            $ar_IV[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Ủy viên cấp huyện', 'tongso' => '0', 'chenhlech' => '0');
            $ar_IV[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Ủy viên cấp xã', 'tongso' => '0', 'chenhlech' => '0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso' => 0, 'chenhlech' => 0);
            $a_IVt = array('tongso' => 0, 'chenhlech' => 0);
            if (session('admin')->level == 'T') {
                if ($m_dv->capdonvi >= 3) {

                    $ar_III[2]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[2]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                    $ar_IV[2]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[2]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000;


                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                } else {

                    $ar_III[1]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[1]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                    $ar_IV[1]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[1]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000 / 1390000;


                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            } else {
                if ($m_dv->capdonvi >= 3) {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000 / 1390000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000 / 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                } else {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000 / 1390000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000 / 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            Excel::create('mau2a2', function ($excel) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.mau2a2excel')
                        ->with('ar_I', $ar_I)
                        ->with('ar_II', $ar_II)
                        ->with('ar_III', $ar_III)
                        ->with('ar_IV', $ar_IV)
                        ->with('a_It', $a_It)
                        ->with('a_IIIt', $a_IIIt)
                        ->with('a_IVt', $a_IVt)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'Mau2a2');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2b_huyen(Request $request)
    {
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                ->where('dmphanloaict.macongtac', 'NGHIHUU')
                ->get();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                        ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                        ->join('dmdonvi', 'dmdonvi.madv', 'hosocanbo.madv')
                        ->where('dmphanloaict.macongtac', 'NGHIHUU')
                        ->where('dmdonvi.macqcq', $madv)
                        ->get();
                } else {
                    $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                        ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                        ->where('dmphanloaict.macongtac', 'NGHIHUU')
                        ->where('hosocanbo.madv', $madv)
                        ->get();
                }
            } else {
                $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                    ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                    ->join('dmdonvi', 'dmdonvi.madv', 'hosocanbo.madv')
                    ->where('dmphanloaict.macongtac', 'NGHIHUU')
                    ->where('dmdonvi.madvbc', $madvbc)
                    ->get();
            }
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[0] = array('val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch');
                $ar_Igr[1] = array('val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_Igr[2] = array('val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại');
            } else {
                $ar_I[0] = array('val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch');
                $ar_I[1] = array('val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_I[2] = array('val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại');
            }
            $a_It = array(
                'cb' => 0,
                'quy09' => 0,
                'quy76' => 0,
                'quytang' => 0,
                'bhyt' => 0,
                'tongquy' => 0
            );
            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($m_hscb)) {
                    //$chitiet = $m_hscb->where('linhvuchoatdong', $ar_Igr[$i]['val']);

                }
                /*
                $ar_I[$i]['tt'] = $ar_I[$i]['tt'];
                $ar_I[$i]['noidung'] = $ar_Igr[$i]['noidung'];
                $ar_I[$i]['cb'] = 0;
                $ar_I[$i]['quy09'] = 0;
                $ar_I[$i]['quy76'] = 0;
                $ar_I[$i]['quytang'] = 0;
                $ar_I[$i]['bhyt'] = 0;
                $ar_I[$i]['tongquy'] = 0;
                */
            }


            return view('reports.thongtu67.mau2b_tt68')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('$a_It', $a_It)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2b_huyen_excel(Request $request)
    {
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                ->where('dmphanloaict.macongtac', 'NGHIHUU')
                ->get();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                        ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                        ->join('dmdonvi', 'dmdonvi.madv', 'hosocanbo.madv')
                        ->where('dmphanloaict.macongtac', 'NGHIHUU')
                        ->where('dmdonvi.macqcq', $madv)
                        ->get();
                } else {
                    $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                        ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                        ->where('dmphanloaict.macongtac', 'NGHIHUU')
                        ->where('hosocanbo.madv', $madv)
                        ->get();
                }
            } else {
                $m_hscb = hosocanbo::join('dmphanloaict', 'dmphanloaict.mact', '=', 'hosocanbo.mact')
                    ->join('dmchucvucq', 'dmchucvucq.macvcq', '=', 'hosocanbo.macvcq')
                    ->join('dmdonvi', 'dmdonvi.madv', 'hosocanbo.madv')
                    ->where('dmphanloaict.macongtac', 'NGHIHUU')
                    ->where('dmdonvi.madvbc', $madvbc)
                    ->get();
            }
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[0] = array('val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch');
                $ar_Igr[1] = array('val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_Igr[2] = array('val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại');
            } else {
                $ar_I[0] = array('val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch');
                $ar_I[1] = array('val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_I[2] = array('val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại');
            }
            $a_It = array(
                'cb' => 0,
                'quy09' => 0,
                'quy76' => 0,
                'quytang' => 0,
                'bhyt' => 0,
                'tongquy' => 0
            );
            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($m_hscb)) {
                    //$chitiet = $m_hscb->where('linhvuchoatdong', $ar_Igr[$i]['val']);

                }
                /*
                $ar_I[$i]['tt'] = $ar_I[$i]['tt'];
                $ar_I[$i]['noidung'] = $ar_Igr[$i]['noidung'];
                $ar_I[$i]['cb'] = 0;
                $ar_I[$i]['quy09'] = 0;
                $ar_I[$i]['quy76'] = 0;
                $ar_I[$i]['quytang'] = 0;
                $ar_I[$i]['bhyt'] = 0;
                $ar_I[$i]['tongquy'] = 0;
                */
            }

            Excel::create('mau2b', function ($excel) use ($ar_I, $a_It, $m_dv) {
                $excel->sheet('New sheet', function ($sheet) use ($ar_I, $a_It, $m_dv) {
                    $sheet->loadView('reports.thongtu67.mau2bexcel')
                        ->with('ar_I', $ar_I)
                        ->with('a_It', $a_It)
                        ->with('m_dv', $m_dv)
                        ->with('pageTitle', 'Mau2b');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2c_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select(
                            'linhvuchoatdong',
                            'heso',
                            'dmdonvi.maphanloai',
                            'hosocanbo.pck',
                            'hosocanbo.pccv',
                            'hosocanbo.pckv',
                            'hosocanbo.pcth',
                            'hosocanbo.pcdh',
                            'hosocanbo.pcld',
                            'hosocanbo.pcudn',
                            'hosocanbo.pctn',
                            'hosocanbo.pctnn',
                            'hosocanbo.pcdbn',
                            'hosocanbo.pcvk',
                            'hosocanbo.pckn',
                            'hosocanbo.pccovu',
                            'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk',
                            'hosocanbo.pcbdhdcu',
                            'hosocanbo.pcdang',
                            'hosocanbo.pcthni',
                            'dmdonvi.tendv'
                        )
                        ->where('dmdonvi.macqcq', $madv)
                        ->get();
                } else {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select(
                            'linhvuchoatdong',
                            'dmdonvi.maphanloai',
                            'heso',
                            'hosocanbo.pck',
                            'hosocanbo.pccv',
                            'hosocanbo.pckv',
                            'hosocanbo.pcth',
                            'hosocanbo.pcdh',
                            'hosocanbo.pcld',
                            'hosocanbo.pcudn',
                            'hosocanbo.pctn',
                            'hosocanbo.pctnn',
                            'hosocanbo.pcdbn',
                            'hosocanbo.pcvk',
                            'hosocanbo.pckn',
                            'hosocanbo.pccovu',
                            'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk',
                            'hosocanbo.pcbdhdcu',
                            'hosocanbo.pcdang',
                            'hosocanbo.pcthni',
                            'dmdonvi.tendv'
                        )
                        ->where('dmdonvi.madv', $madv)
                        ->get();
                }
            } else {
                $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->select(
                        'linhvuchoatdong',
                        'dmdonvi.maphanloai',
                        'heso',
                        'hosocanbo.pck',
                        'hosocanbo.pccv',
                        'hosocanbo.pckv',
                        'hosocanbo.pcth',
                        'hosocanbo.pcdh',
                        'hosocanbo.pcld',
                        'hosocanbo.pcudn',
                        'hosocanbo.pctn',
                        'hosocanbo.pctnn',
                        'hosocanbo.pcdbn',
                        'hosocanbo.pcvk',
                        'hosocanbo.pckn',
                        'hosocanbo.pccovu',
                        'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk',
                        'hosocanbo.pcbdhdcu',
                        'hosocanbo.pcdang',
                        'hosocanbo.pcthni',
                        'dmdonvi.tendv'
                    )
                    ->where('dmdonvi.madvbc', $madvbc)
                    ->get();
            }


            if (session('admin')->username == 'khthso') {
                $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->select(
                        'macanbo',
                        'dmdonvi.maphanloai',
                        'linhvuchoatdong',
                        'heso',
                        'hosocanbo.pck',
                        'hosocanbo.pccv',
                        'hosocanbo.pckv',
                        'hosocanbo.pcth',
                        'hosocanbo.pcdh',
                        'hosocanbo.pcld',
                        'hosocanbo.pcudn',
                        'hosocanbo.pctn',
                        'hosocanbo.pctnn',
                        'hosocanbo.pcdbn',
                        'hosocanbo.pcvk',
                        'hosocanbo.pckn',
                        'hosocanbo.pccovu',
                        'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk',
                        'hosocanbo.pcbdhdcu',
                        'hosocanbo.pcdang',
                        'hosocanbo.pcthni'
                    )
                    ->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->get();
            }
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
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    $ar_I[$i]['dt'] = 0;
                    $ar_I[$i]['hstl'] = 0;
                    $ar_I[$i]['hspc'] = 0;
                    $ar_I[$i]['cl'] = 0;
                    $ar_I[$i]['nc'] = 0;
                    if (isset($m_cb)) {
                        $chitiet = $m_cb->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                    }
                    $d = 1;
                    $luugr = $i;
                    $ten_dv = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select('dmdonvi.tendv')
                        ->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                        ->where('heso', '<=', 2.34)
                        ->groupby('dmdonvi.tendv')
                        ->get();
                    $tendv = $ten_dv->toArray();
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        foreach ($tendv as $tendvchitiet) {
                            $chitietct = $m_cb->where('tendv', $tendvchitiet['tendv'])->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                            $d = 1;
                            if (isset($chitietct) && count($chitietct) > 0) {
                                $d++;
                                $i += $d;
                                //dd($chitietct);
                                $tongpc = 0;
                                $ar_I[$i]['tt'] = '+';
                                $ar_I[$i]['noidung'] = $tendvchitiet['tendv'];
                                $ar_I[$i]['dt'] = $chitietct->count('heso');
                                $a_It['dt'] += $ar_I[$i]['dt'];

                                $ar_I[$i]['hstl'] = $chitietct->sum('heso');
                                $a_It['hstl'] += $ar_I[$i]['hstl'];

                                $tongpc += $chitietct->sum('pckv');
                                $tongpc += $chitietct->sum('pccv');
                                $tongpc += $chitietct->sum('pctnvk');
                                $tongpc += $chitietct->sum('pcudn');
                                $tongpc += $chitietct->sum('pcth');
                                $tongpc += $chitietct->sum('pctn');
                                $tongpc += $chitietct->sum('pccovu');
                                $tongpc += $chitietct->sum('pcdang');
                                $tongpc += $chitietct->sum('pcthni');
                                $tongpc += $chitietct->sum('pck');

                                $ar_I[$i]['hspc'] = $tongpc;
                                $a_It['hspc'] += $ar_I[$i]['hspc'];
                                $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl']) * $luongcb);
                                $a_It['cl'] += $ar_I[$i]['cl'];
                                $ar_I[$i]['nc'] = $ar_I[$i]['cl'] * 6;
                                $a_It['nc'] += $ar_I[$i]['nc'];

                                $ar_I[$luugr]['dt'] += $ar_I[$i]['dt'];
                                $ar_I[$luugr]['hstl'] += $ar_I[$i]['hstl'];
                                $ar_I[$luugr]['hspc'] += $ar_I[$i]['hspc'];
                                $ar_I[$luugr]['cl'] += $ar_I[$i]['cl'];
                                $ar_I[$luugr]['nc'] += $ar_I[$i]['nc'];
                            }
                        }
                    } else {
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
                $ar_I[$gddt]['dt'] = $ar_I[$giaoduc]['dt'] + $ar_I[$daotao]['dt'];
                $ar_I[$gddt]['hstl'] = $ar_I[$giaoduc]['hstl'] + $ar_I[$daotao]['hstl'];
                $ar_I[$gddt]['hspc'] = $ar_I[$giaoduc]['hspc'] + $ar_I[$daotao]['hspc'];
                $ar_I[$gddt]['cl'] = $ar_I[$giaoduc]['cl'] + $ar_I[$daotao]['cl'];
                $ar_I[$gddt]['nc'] = $ar_I[$giaoduc]['nc'] + $ar_I[$daotao]['nc'];

                $ar_I[$qlnnddt]['dt'] = $ar_I[$qlnn]['dt'] + $ar_I[$ddt]['dt'];
                $ar_I[$qlnnddt]['hstl'] = $ar_I[$qlnn]['hstl'] + $ar_I[$ddt]['hstl'];
                $ar_I[$qlnnddt]['hspc'] = $ar_I[$qlnn]['hspc'] + $ar_I[$ddt]['hspc'];
                $ar_I[$qlnnddt]['cl'] = $ar_I[$qlnn]['cl'] + $ar_I[$ddt]['cl'];
                $ar_I[$qlnnddt]['nc'] = $ar_I[$qlnn]['nc'] + $ar_I[$ddt]['nc'];
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($m_cb)) {
                        $chitiet = $m_cb->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }
                    if (isset($chitiet) > 0) {
                        $tongcb = 0;
                        $tonghs = 0;
                        $tongpc = 0;
                        $ar_I[$i]['dt'] = $chitiet->count('heso');

                        $a_It['dt'] += $ar_I[$i]['dt'];

                        $ar_I[$i]['hstl'] = $chitiet->sum('heso');
                        $a_It['hstl'] += $ar_I[$i]['hstl'];

                        $tongpc += $chitiet->sum('pckv');
                        $tongpc += $chitiet->sum('pccv');
                        $tongpc += $chitiet->sum('pctnvk');
                        $tongpc += $chitiet->sum('pcudn');
                        $tongpc += $chitiet->sum('pcth');
                        $tongpc += $chitiet->sum('pctn');
                        $tongpc += $chitiet->sum('pccovu');
                        $tongpc += $chitiet->sum('pcdang');
                        $tongpc += $chitiet->sum('pcthni');
                        $tongpc += $chitiet->sum('pck');

                        $ar_I[$i]['hspc'] = $tongpc;
                        $a_It['hspc'] += $ar_I[$i]['hspc'];
                        $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl']) * $luongcb);
                        $a_It['cl'] += $ar_I[$i]['cl'];
                        $ar_I[$i]['nc'] = $ar_I[$i]['cl'] * 6;
                        $a_It['nc'] += $ar_I[$i]['nc'];
                    } else {
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
            }
            $ar_II = array();

            $chitiet = $m_cb->where('maphanloai', 'KVXP');
            //
            if (count($chitiet) > 0) {
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['dt'] = $chitiet->count('heso');
                $ar_II['hstl'] = $chitiet->sum('heso');

                $tongpc += $chitiet->sum('pckv');
                $tongpc += $chitiet->sum('pccv');
                $tongpc += $chitiet->sum('pctnvk');
                $tongpc += $chitiet->sum('pcudn');
                $tongpc += $chitiet->sum('pcth');
                $tongpc += $chitiet->sum('pctn');
                $tongpc += $chitiet->sum('pccovu');
                $tongpc += $chitiet->sum('pcdang');
                $tongpc += $chitiet->sum('pcthni');
                $tongpc += $chitiet->sum('pck');

                $ar_II['hspc'] = $tongpc;
                $ar_II['cl'] = round(($tongpc + $ar_II['hstl']) * $luongcb);
                $ar_II['nc'] = $ar_II['cl'] * 6;
            } else {
                $ar_II['dt'] = 0;
                $ar_II['hstl'] = 0;
                $ar_II['hspc'] = 0;
                $ar_II['cl'] = 0;
                $ar_II['nc'] = 0;
            }
            return view('reports.thongtu67.Mau2c_tt46')
                ->with('m_dv', $m_dv)
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BHTN');
        } else
            return view('errors.notlogin');
    }

    function mau2c_huyen_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select(
                            'linhvuchoatdong',
                            'heso',
                            'dmdonvi.maphanloai',
                            'hosocanbo.pck',
                            'hosocanbo.pccv',
                            'hosocanbo.pckv',
                            'hosocanbo.pcth',
                            'hosocanbo.pcdh',
                            'hosocanbo.pcld',
                            'hosocanbo.pcudn',
                            'hosocanbo.pctn',
                            'hosocanbo.pctnn',
                            'hosocanbo.pcdbn',
                            'hosocanbo.pcvk',
                            'hosocanbo.pckn',
                            'hosocanbo.pccovu',
                            'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk',
                            'hosocanbo.pcbdhdcu',
                            'hosocanbo.pcdang',
                            'hosocanbo.pcthni',
                            'dmdonvi.tendv'
                        )
                        ->where('dmdonvi.macqcq', $madv)
                        ->where('heso', '<=', 2.34)
                        ->get();
                } else {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select(
                            'linhvuchoatdong',
                            'dmdonvi.maphanloai',
                            'heso',
                            'hosocanbo.pck',
                            'hosocanbo.pccv',
                            'hosocanbo.pckv',
                            'hosocanbo.pcth',
                            'hosocanbo.pcdh',
                            'hosocanbo.pcld',
                            'hosocanbo.pcudn',
                            'hosocanbo.pctn',
                            'hosocanbo.pctnn',
                            'hosocanbo.pcdbn',
                            'hosocanbo.pcvk',
                            'hosocanbo.pckn',
                            'hosocanbo.pccovu',
                            'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk',
                            'hosocanbo.pcbdhdcu',
                            'hosocanbo.pcdang',
                            'hosocanbo.pcthni',
                            'dmdonvi.tendv'
                        )
                        ->where('dmdonvi.madv', $madv)
                        ->where('heso', '<=', 2.34)
                        ->get();
                }
            } else {
                $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->select(
                        'linhvuchoatdong',
                        'dmdonvi.maphanloai',
                        'heso',
                        'hosocanbo.pck',
                        'hosocanbo.pccv',
                        'hosocanbo.pckv',
                        'hosocanbo.pcth',
                        'hosocanbo.pcdh',
                        'hosocanbo.pcld',
                        'hosocanbo.pcudn',
                        'hosocanbo.pctn',
                        'hosocanbo.pctnn',
                        'hosocanbo.pcdbn',
                        'hosocanbo.pcvk',
                        'hosocanbo.pckn',
                        'hosocanbo.pccovu',
                        'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk',
                        'hosocanbo.pcbdhdcu',
                        'hosocanbo.pcdang',
                        'hosocanbo.pcthni',
                        'dmdonvi.tendv'
                    )
                    ->where('dmdonvi.madvbc', $madvbc)
                    ->where('heso', '<=', 2.34)
                    ->get();
            }


            if (session('admin')->username == 'khthso') {
                $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->select(
                        'macanbo',
                        'dmdonvi.maphanloai',
                        'linhvuchoatdong',
                        'heso',
                        'hosocanbo.pck',
                        'hosocanbo.pccv',
                        'hosocanbo.pckv',
                        'hosocanbo.pcth',
                        'hosocanbo.pcdh',
                        'hosocanbo.pcld',
                        'hosocanbo.pcudn',
                        'hosocanbo.pctn',
                        'hosocanbo.pctnn',
                        'hosocanbo.pcdbn',
                        'hosocanbo.pcvk',
                        'hosocanbo.pckn',
                        'hosocanbo.pccovu',
                        'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk',
                        'hosocanbo.pcbdhdcu',
                        'hosocanbo.pcdang',
                        'hosocanbo.pcthni'
                    )
                    ->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('hosocanbo.heso', '<=', 2.34)
                    ->where('dmdonvibaocao.level', 'T')
                    ->get();
            }
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
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    $ar_I[$i]['dt'] = 0;
                    $ar_I[$i]['hstl'] = 0;
                    $ar_I[$i]['hspc'] = 0;
                    $ar_I[$i]['cl'] = 0;
                    $ar_I[$i]['nc'] = 0;
                    if (isset($m_cb)) {
                        $chitiet = $m_cb->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                    }
                    $d = 1;
                    $luugr = $i;
                    $ten_dv = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select('dmdonvi.tendv')
                        ->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                        ->where('heso', '<=', 2.34)
                        ->groupby('dmdonvi.tendv')
                        ->get();
                    $tendv = $ten_dv->toArray();
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        foreach ($tendv as $tendvchitiet) {
                            $chitietct = $m_cb->where('tendv', $tendvchitiet['tendv'])->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                            $d = 1;
                            if (isset($chitietct) && count($chitietct) > 0) {
                                $d++;
                                $i += $d;
                                //dd($chitietct);
                                $tongpc = 0;
                                $ar_I[$i]['tt'] = '+';
                                $ar_I[$i]['noidung'] = $tendvchitiet['tendv'];
                                $ar_I[$i]['dt'] = $chitietct->count('heso');
                                $a_It['dt'] += $ar_I[$i]['dt'];

                                $ar_I[$i]['hstl'] = $chitietct->sum('heso');
                                $a_It['hstl'] += $ar_I[$i]['hstl'];

                                $tongpc += $chitietct->sum('pckv');
                                $tongpc += $chitietct->sum('pccv');
                                $tongpc += $chitietct->sum('pctnvk');
                                $tongpc += $chitietct->sum('pcudn');
                                $tongpc += $chitietct->sum('pcth');
                                $tongpc += $chitietct->sum('pctn');
                                $tongpc += $chitietct->sum('pccovu');
                                $tongpc += $chitietct->sum('pcdang');
                                $tongpc += $chitietct->sum('pcthni');
                                $tongpc += $chitietct->sum('pck');

                                $ar_I[$i]['hspc'] = $tongpc;
                                $a_It['hspc'] += $ar_I[$i]['hspc'];
                                $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl']) * $luongcb);
                                $a_It['cl'] += $ar_I[$i]['cl'];
                                $ar_I[$i]['nc'] = $ar_I[$i]['cl'] * 6;
                                $a_It['nc'] += $ar_I[$i]['nc'];

                                $ar_I[$luugr]['dt'] += $ar_I[$i]['dt'];
                                $ar_I[$luugr]['hstl'] += $ar_I[$i]['hstl'];
                                $ar_I[$luugr]['hspc'] += $ar_I[$i]['hspc'];
                                $ar_I[$luugr]['cl'] += $ar_I[$i]['cl'];
                                $ar_I[$luugr]['nc'] += $ar_I[$i]['nc'];
                            }
                        }
                    } else {
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
                $ar_I[$gddt]['dt'] = $ar_I[$giaoduc]['dt'] + $ar_I[$daotao]['dt'];
                $ar_I[$gddt]['hstl'] = $ar_I[$giaoduc]['hstl'] + $ar_I[$daotao]['hstl'];
                $ar_I[$gddt]['hspc'] = $ar_I[$giaoduc]['hspc'] + $ar_I[$daotao]['hspc'];
                $ar_I[$gddt]['cl'] = $ar_I[$giaoduc]['cl'] + $ar_I[$daotao]['cl'];
                $ar_I[$gddt]['nc'] = $ar_I[$giaoduc]['nc'] + $ar_I[$daotao]['nc'];

                $ar_I[$qlnnddt]['dt'] = $ar_I[$qlnn]['dt'] + $ar_I[$ddt]['dt'];
                $ar_I[$qlnnddt]['hstl'] = $ar_I[$qlnn]['hstl'] + $ar_I[$ddt]['hstl'];
                $ar_I[$qlnnddt]['hspc'] = $ar_I[$qlnn]['hspc'] + $ar_I[$ddt]['hspc'];
                $ar_I[$qlnnddt]['cl'] = $ar_I[$qlnn]['cl'] + $ar_I[$ddt]['cl'];
                $ar_I[$qlnnddt]['nc'] = $ar_I[$qlnn]['nc'] + $ar_I[$ddt]['nc'];
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($m_cb)) {
                        $chitiet = $m_cb->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }
                    if (isset($chitiet) > 0) {
                        $tongcb = 0;
                        $tonghs = 0;
                        $tongpc = 0;
                        $ar_I[$i]['dt'] = $chitiet->count('heso');

                        $a_It['dt'] += $ar_I[$i]['dt'];

                        $ar_I[$i]['hstl'] = $chitiet->sum('heso');
                        $a_It['hstl'] += $ar_I[$i]['hstl'];

                        $tongpc += $chitiet->sum('pckv');
                        $tongpc += $chitiet->sum('pccv');
                        $tongpc += $chitiet->sum('pctnvk');
                        $tongpc += $chitiet->sum('pcudn');
                        $tongpc += $chitiet->sum('pcth');
                        $tongpc += $chitiet->sum('pctn');
                        $tongpc += $chitiet->sum('pccovu');
                        $tongpc += $chitiet->sum('pcdang');
                        $tongpc += $chitiet->sum('pcthni');
                        $tongpc += $chitiet->sum('pck');

                        $ar_I[$i]['hspc'] = $tongpc;
                        $a_It['hspc'] += $ar_I[$i]['hspc'];
                        $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl']) * $luongcb);
                        $a_It['cl'] += $ar_I[$i]['cl'];
                        $ar_I[$i]['nc'] = $ar_I[$i]['cl'] * 6;
                        $a_It['nc'] += $ar_I[$i]['nc'];
                    } else {
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
            }
            $ar_II = array();

            $chitiet = $m_cb->where('maphanloai', 'KVXP');
            //
            if (count($chitiet) > 0) {
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['dt'] = $chitiet->count('heso');
                $ar_II['hstl'] = $chitiet->sum('heso');

                $tongpc += $chitiet->sum('pckv');
                $tongpc += $chitiet->sum('pccv');
                $tongpc += $chitiet->sum('pctnvk');
                $tongpc += $chitiet->sum('pcudn');
                $tongpc += $chitiet->sum('pcth');
                $tongpc += $chitiet->sum('pctn');
                $tongpc += $chitiet->sum('pccovu');
                $tongpc += $chitiet->sum('pcdang');
                $tongpc += $chitiet->sum('pcthni');
                $tongpc += $chitiet->sum('pck');

                $ar_II['hspc'] = $tongpc;
                $ar_II['cl'] = round(($tongpc + $ar_II['hstl']) * $luongcb);
                $ar_II['nc'] = $ar_II['cl'] * 6;
            } else {
                $ar_II['dt'] = 0;
                $ar_II['hstl'] = 0;
                $ar_II['hspc'] = 0;
                $ar_II['cl'] = 0;
                $ar_II['nc'] = 0;
            }

            Excel::create('Mau2c_BcNCCL', function ($excel) use ($ar_I, $a_It, $ar_II, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($ar_I, $a_It, $ar_II, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.Mau2c_BcNCCLexcel')
                        ->with('ar_I', $ar_I)
                        ->with('a_It', $a_It)
                        ->with('ar_II', $ar_II)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'Mau2c_BcNCCL');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2d_huyen(Request $request)
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

    function mau2d_huyen_excel(Request $request)
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
                        ->select('dmdiabandbkk.id', 'phanloai')
                        ->where('dmdonvi.macqcq', $madv)
                        ->where('maphanloai', 'KVXP')
                        ->get();
                } else {
                    $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id', 'phanloai')
                        ->where('dmdonvi.madv', $madv)
                        ->where('maphanloai', 'KVXP')
                        ->get();
                }
            } else {
                $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->select('dmdiabandbkk.id', 'phanloai')
                    ->where('dmdonvi.madvbc', $madvbc)
                    ->where('maphanloai', 'KVXP')
                    ->get();
            }
            if (session('admin')->username == 'khthso') {
                $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->select('dmdiabandbkk.id', 'phanloai')
                    ->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvi.maphanloai', 'KVXP')
                    ->where('dmdonvibaocao.level', 'T')
                    ->get();
            }
            $m_dt = dmdiabandbkk_chitiet::join('dmdiabandbkk', 'dmdiabandbkk_chitiet.madiaban', '=', 'dmdiabandbkk.madiaban')
                ->select('dmdiabandbkk_chitiet.id', 'phanloai')->get();

            $ar_I = array();
            $ar_I[] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn');
            $ar_I[] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I');
            $ar_I[] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II');
            $ar_I[] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III');
            $ar_I[] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tỏ dân phố');
            $ar_I[] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo');
            $ar_I[] = array('val' => 'BGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo');
            $ar_I[] = array('val' => 'DBKK', 'tt' => '2', 'noidung' => 'Số xã khó khăn theo Quyết định 1049/QĐ-TTg ngày 26/6/2014');
            $ar_I[] = array('val' => 'DBKK', 'tt' => '', 'noidung' => '- Thôn thuộc xã khó khăn theo Quyết định 1049/QĐ-TTg');
            $ar_I[] = array('val' => 'XL12K', 'tt' => '3', 'noidung' => 'Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)');
            $ar_I[] = array('val' => 'TXL12K', 'tt' => '', 'noidung' => '- Thôn thuộc xã loại I, loại II');
            $ar_I[] = array('val' => 'DBTD', 'tt' => '4', 'noidung' => 'Số xã trọng điểm, phức tạp về an ninh trật tự');
            $ar_I[] = array('val' => 'DBTD', 'tt' => '', 'noidung' => '- Số thôn thuộc xã trọng điểm, phức tạp về an ninh');
            $ar_I[] = array('val' => 'TK,TDP', 'tt' => '5', 'noidung' => 'Số xã, phường, thị trấn còn lại');
            $ar_I[] = array('val' => 'TK', 'tt' => '', 'noidung' => '- Thôn còn lại');
            $ar_I[] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố');

            $a_It = array(
                'tdv' => 0,
                'mk' => 0,
                'kp' => 0,
                'bhxh' => 0
            );

            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($m_thon)) {
                    $chitiet = $m_thon->where('phanloai', $ar_I[$i]['val']);
                }

                if (isset($chitiet) > 0) {
                    $kpk = 0;
                    $ar_I[$i]['tdv'] = $chitiet->count('id');
                    $a_It['tdv'] += $ar_I[$i]['tdv'];
                    if ($ar_I[$i]['val'] == "XL1") {
                        $ar_I[$i]['mk'] = "20,3";
                        $kpk = 20.3;
                    } elseif ($ar_I[$i]['val'] == "XL2") {
                        $ar_I[$i]['mk'] = "18,6";
                        $kpk = 18.6;
                    } elseif ($ar_I[$i]['val'] == "XL3") {
                        $ar_I[$i]['mk'] = "17,6";
                        $kpk = 17.6;
                    } elseif ($ar_I[$i]['val'] == "TBGHD" || $ar_I[$i]['val'] == "TDBKK" || $ar_I[$i]['val'] == "TXL12K") {
                        $ar_I[$i]['mk'] = "5,0";
                        $kpk = 5;
                    } elseif ($ar_I[$i]['val'] == "TK" || $ar_I[$i]['val'] == "TDP") {
                        $ar_I[$i]['mk'] = "3,0";
                        $kpk = 3;
                    } else
                        $ar_I[$i]['mk'] = "";
                    $ar_I[$i]['kp'] = $a_It['tdv'] * $kpk * 6 * 900000;
                    $a_It['kp'] += $ar_I[$i]['kp'];
                    $ar_I[$i]['bhxh'] = 0;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];
                } else {
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['kp'] = 0;
                    $ar_I[$i]['bh'] = 0;
                }
            }
            Excel::create('Mau2d_ThKPTT', function ($excel) use ($ar_I, $a_It, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($ar_I, $a_It, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.Mau2d_ThKPTTexcel')
                        ->with('ar_I', $ar_I)
                        ->with('a_It', $a_It)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'Mau2d_ThKPTT');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2đ_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->get();
            $ar_I = array();
            $ar_I[0] = array('val' => 'QLNN', 'tt' => 'I', 'noidung' => 'Quản lý nhà nước');
            $ar_I[1] = array('val' => 'SNCL', 'tt' => 'II', 'noidung' => 'Sự nghiệp công lập');
            $ar_I[2] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[3] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[4] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[5] = array('val' => 'NGANSACH', 'tt' => '4', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            $model_th15 = tonghopluong_donvi::select('mathdv', 'madv', 'thang', 'nam')
                ->where('trangthai', 'DAGUI')
                ->where('thang', '12')->where('nam', '2015')
                ->wherein('madv', array_column($model_donvi->toarray(), 'madv'))
                ->distinct()->get();
            $model_th17 = tonghopluong_donvi::select('mathdv', 'madv', 'thang', 'nam')
                ->where('trangthai', 'DAGUI')
                ->where('thang', '07')->where('nam', '2017')
                ->wherein('madv', array_column($model_donvi->toarray(), 'madv'))
                ->distinct()->get();
            $model_th19 = tonghopluong_donvi::select('mathdv', 'madv', 'thang', 'nam')
                ->where('trangthai', 'DAGUI')
                ->where('thang', '07')->where('nam', '2019')
                ->wherein('madv', array_column($model_donvi->toarray(), 'madv'))
                ->distinct()->get();
            $modelsl15 = tonghopluong_donvi_chitiet::select('soluong', 'linhvuchoatdong', 'mathdv')
                ->wherein('mathdv', array_column($model_th15->toarray(), 'mathdv'))
                ->get();
            $model17 = tonghopluong_donvi_chitiet::select('soluong', 'linhvuchoatdong', 'luongtn', 'mathdv')
                ->wherein('mathdv', array_column($model_th17->toarray(), 'mathdv'))
                ->get();
            $model19 = tonghopluong_donvi_chitiet::select('soluong', 'linhvuchoatdong', 'luongtn', 'mathdv')
                ->wherein('mathdv', array_column($model_th19->toarray(), 'mathdv'))
                ->get();
            foreach ($modelsl15 as $ct) {
                $ct->madv = $model_th15->where('mathdv', $ct->mathdv)->first()->madv;
                $ct->phanloainguon = $model_donvi->where('madv', $ct->madv)->first()->phanloainguon;
                $ct->thang = $model_th15->where('mathdv', $ct->mathdv)->first()->thang;
                $ct->nam = $model_th15->where('mathdv', $ct->mathdv)->first()->nam;
            }
            foreach ($model17 as $ct) {
                $ct->madv = $model_th17->where('mathdv', $ct->mathdv)->first()->madv;
                $ct->phanloainguon = $model_donvi->where('madv', $ct->madv)->first()->phanloainguon;
                $ct->thang = $model_th17->where('mathdv', $ct->mathdv)->first()->thang;
                $ct->nam = $model_th17->where('mathdv', $ct->mathdv)->first()->nam;
            }
            foreach ($model19 as $ct) {
                $ct->madv = $model_th19->where('mathdv', $ct->mathdv)->first()->madv;
                $ct->phanloainguon = $model_donvi->where('madv', $ct->madv)->first()->phanloainguon;
                $ct->thang = $model_th19->where('mathdv', $ct->mathdv)->first()->thang;
                $ct->nam = $model_th19->where('mathdv', $ct->mathdv)->first()->nam;
            }
            $ar_I[0]['dt15'] = 0;
            $ar_I[0]['dt17'] = 0;
            $ar_I[0]['luong17'] = 0;
            $ar_I[0]['dt19'] = 0;
            $ar_I[0]['luong19'] = 0;
            $ar_I[0]['pcthang'] = 0;
            $ar_I[0]['tkthang'] = 0;
            $ar_I[0]['luong'] = 0;

            $chitiet15 = $modelsl15->where('linhvuchoatdong', 'QLNN');
            $chitiet17 = $model17->where('linhvuchoatdong', 'QLNN');
            $chitiet19 = $model19->where('linhvuchoatdong', 'QLNN');

            $ar_I[0]['dt15'] = $chitiet15->where('thang', '05')->where('nam', '2015')->sum('soluong');
            $ar_I[0]['dt17'] = $chitiet17->where('thang', '07')->where('nam', '2017')->sum('soluong');
            $ar_I[0]['luong17'] = $chitiet17->where('thang', '07')->where('nam', '2017')->sum('luongtn');
            $ar_I[0]['dt19'] = $chitiet19->where('thang', '07')->where('nam', '2019')->sum('soluong');
            $ar_I[0]['luong19'] = $chitiet19->where('thang', '07')->where('nam', '2019')->sum('luongtn') * 1390000 / 1490000;
            $ar_I[0]['pcthang'] = $ar_I[0]['luong19'] - $ar_I[0]['luong17'];
            $ar_I[0]['tkthang'] = $ar_I[0]['luong19'] - $ar_I[0]['luong17'];
            $ar_I[0]['luong'] = ($ar_I[0]['luong19'] - $ar_I[0]['luong17']) * 6;
            for ($i = 2; $i < count($ar_I); $i++) {
                $chitiet15 = $modelsl15->where('linhvuchoatdong', '<>', 'QLNN')->where('phanloainguon', $ar_I[$i]['val']);
                $chitiet17 = $model17->where('linhvuchoatdong', '<>', 'QLNN')->where('phanloainguon', $ar_I[$i]['val']);
                $chitiet19 = $model19->where('linhvuchoatdong', '<>', 'QLNN')->where('phanloainguon', $ar_I[$i]['val']);

                $ar_I[$i]['dt15'] = $chitiet15->where('thang', '05')->where('nam', '2015')->sum('soluong');
                $ar_I[$i]['dt17'] = $chitiet17->where('thang', '07')->where('nam', '2017')->sum('soluong');
                $ar_I[$i]['luong17'] = $chitiet17->where('thang', '07')->where('nam', '2017')->sum('luongtn');
                $ar_I[$i]['dt19'] = $chitiet19->where('thang', '07')->where('nam', '2019')->sum('soluong');
                $ar_I[$i]['luong19'] = $chitiet19->where('thang', '07')->where('nam', '2019')->sum('luongtn') * 1390000 / 1490000;
                $ar_I[$i]['pcthang'] = $ar_I[$i]['luong19'] - $ar_I[$i]['luong17'];
                $ar_I[$i]['tkthang'] = $ar_I[$i]['luong19'] - $ar_I[$i]['luong17'];
                $ar_I[$i]['luong'] = ($ar_I[$i]['luong19'] - $ar_I[$i]['luong17']) * 6;
            }
            $ar_I[1]['dt15'] = $ar_I[2]['dt15'] + $ar_I[3]['dt15'] + $ar_I[4]['dt15'] + $ar_I[5]['dt15'];
            $ar_I[1]['dt17'] = $ar_I[2]['dt17'] + $ar_I[3]['dt17'] + $ar_I[4]['dt17'] + $ar_I[5]['dt17'];
            $ar_I[1]['luong17'] = $ar_I[2]['luong17'] + $ar_I[3]['luong17'] + $ar_I[4]['luong17'] + $ar_I[5]['luong17'];
            $ar_I[1]['dt19'] = $ar_I[2]['dt19'] + $ar_I[3]['dt19'] + $ar_I[4]['dt19'] + $ar_I[5]['dt19'];
            $ar_I[1]['luong19'] = $ar_I[2]['luong19'] + $ar_I[3]['luong19'] + $ar_I[4]['luong19'] + $ar_I[5]['luong19'];
            $ar_I[1]['pcthang'] = $ar_I[2]['pcthang'] + $ar_I[3]['pcthang'] + $ar_I[4]['pcthang'] + $ar_I[5]['pcthang'];
            $ar_I[1]['tkthang'] = $ar_I[2]['tkthang'] + $ar_I[3]['tkthang'] + $ar_I[4]['tkthang'] + $ar_I[5]['tkthang'];
            $ar_I[1]['luong'] = $ar_I[2]['luong'] + $ar_I[3]['luong'] + $ar_I[4]['luong'] + $ar_I[5]['luong'];
            //dd($ar_I);
            if (isset($inputs['excel'])) {
                Excel::create('Mau2dd_tt46', function ($excel) use ($ar_I, $inputs, $m_dv) {
                    $excel->sheet('New sheet', function ($sheet) use ($ar_I, $inputs, $m_dv) {
                        $sheet->loadView('reports.thongtu67.Mau2dd_tt46')
                            ->with('ar_I', $ar_I)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'Mau2dd_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {

                return view('reports.thongtu67.Mau2dd_tt46')
                    ->with('inputs', $inputs)
                    ->with('m_dv', $m_dv)
                    ->with('ar_I', $ar_I)
                    ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2e_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            if ($inputs['madv'] != '') {
                $m_donvi = dmdonvi::where('madv', $inputs['madv'])->get();
                $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            } else {
                $m_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            }
            // dd($m_dv);
            // $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');
            if ($inputs['madv'] != null) {
                $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('madv', $inputs['madv'])->get();
            } else {
                $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('madvbc', $madvbc)->get();
            }
            $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
                ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
            $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
            $a_pc_th = getColTongHop();
            $a_pc = array_diff($a_pc_th, $a_pc_goc);
            foreach ($m_tonghop_ct as $ct) {
                foreach ($m_donvi as $dv) {
                    $ct->phanloainguon = $dv->phanloainguon;
                    $ct->phanloaidonvi = $dv->maphanloai;
                }
            }
            $pldv = dmphanloaidonvi::all();
            $a_qlnn = ['MAMNON', 'TIEUHOC', 'THCS', 'THvaTHCS', 'PTDTNT'];
            $a_sunghiep = array_column($pldv->wherenotin('maphanloai', $a_qlnn)->toarray(), 'maphanloai');
            $m_sunghiep = $m_tonghop_ct->wherein('phanloaidonvi', $a_sunghiep);
            // $ar_I = array();
            $ar_I[0] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư');
            $ar_I[1] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên');
            $ar_I[2] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[3] = array('val' => 'NGANSACH', 'tt' => '4', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');

            $a_It = array(
                'tongsodonvi1' => 0, 'tongsodonvi2' => 0, 'quy_tuchu' => 0, 'kp_tk' => 0, 'tang' => 0, 'giam' => 0
            );

            for ($i = 0; $i < 4; $i++) {
                $chitiet = $m_sunghiep->where('phanloainguon', $ar_I[$i]['val']);
                // $ar_I[$i]['soluongbienche'] = count($chitiet);
                if (count($chitiet) != 0) {
                    $ar_I[$i]['tongsodonvi1'] = $_tonghop->sum('tongsodonvi1');
                    $ar_I[$i]['tongsodonvi2'] = $_tonghop->sum('tongsodonvi2');
                    $ar_I[$i]['quy_tuchu'] = $_tonghop->sum('quy_tuchu');
                    $ar_I[$i]['kp_tk'] = $ar_I[$i]['quy_tuchu'] * 12;
                } else {
                    $ar_I[$i]['tongsodonvi1'] = 0;
                    $ar_I[$i]['tongsodonvi2'] = 0;
                    $ar_I[$i]['quy_tuchu'] = 0;
                    $ar_I[$i]['kp_tk'] = 0;
                }
                $ar_I[$i]['tang'] = 0;
                $ar_I[$i]['giam'] = 0;
                $result = $ar_I[$i]['tongsodonvi2'] - $ar_I[$i]['tongsodonvi1'];
                if ($result > 0) {
                    $ar_I[$i]['tang'] = $result;
                } else if ($result < 0) {
                    $ar_I[$i]['giam'] = abs($result);
                }

                $a_It['tongsodonvi1'] += $ar_I[$i]['tongsodonvi1'];
                $a_It['tongsodonvi2'] += $ar_I[$i]['tongsodonvi2'];
                $a_It['quy_tuchu'] += $ar_I[$i]['quy_tuchu'];
                $a_It['kp_tk'] += $ar_I[$i]['kp_tk'];
                $a_It['tang'] += $ar_I[$i]['tang'];
                $a_It['giam'] += $ar_I[$i]['giam'];
            }
            // dd($inputs);
            return view('reports.thongtu67.Mau2e_tt46')
                ->with('inputs', $inputs)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THAY ĐỔI CƠ CHÉ TỰ CHỦ TRONG NĂM 2019');
        } else
            return view('errors.notlogin');
    }

    function mau2e_huyen_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_dsh = dmdonvibaocao::where('level', 'H')->distinct()->get();
            $m_xa = dmdiabandbkk::join('dmdonvi', 'dmdonvi.madv', '=', 'dmdiabandbkk.madv')
                ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')->get();
            $ar_h = array();
            $tt = 0;
            foreach ($m_dsh as $h) {
                for ($i = 0; $i < count($m_dsh); $i++) {
                    $tt++;
                    $ar_h[$i]['tt'] = $tt;
                    $ar_h[$i]['noidung'] = $h['tendvbc'];
                }
            }
            Excel::create('Mau2e_ThKPTG', function ($excel) use ($ar_h) {
                $excel->sheet('New sheet', function ($sheet) use ($ar_h) {
                    $sheet->loadView('reports.thongtu67.Mau2e_ThKPTGexcel')
                        ->with('ar_h', $ar_h)
                        ->with('pageTitle', 'Mau2e_ThKPTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2g_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $luongcb = $model_thongtu->muccu / $model_thongtu->mucapdung;
            $ngayapdung = new Carbon($model_thongtu->ngayapdung);
            $nam = date_format($ngayapdung, 'Y');
            $thang = date_format($ngayapdung, 'm');
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {

                    $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('macqcq', $inputs['madv'])->get();
                    $model_donvi = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                    $model_bienche = chitieubienche::where('nam', $nam)->wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                } else {
                    $model_donvi = dmdonvi::where('madv', $madv)->get();
                    $model_bienche = chitieubienche::where('nam', $nam)->where('madv', $madv)->get();
                    $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('madv', $inputs['madv'])->get();
                }
            } else {
                $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('madvbc', $madvbc)->get();
                $model_donvi = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
                $model_bienche = chitieubienche::where('nam', $nam)->wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            }
            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }

            if (session('admin')->phamvitonghop == "KHOI") {
                $model_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq', session('admin')->madv)->get();
            }
            $model_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($model_tonghop->toarray(), 'masodv'))
                ->where('nam', $nam)->where('thang', $thang)->get();

            $a_linhvuc = array_column($model_tonghop->toarray(), 'linhvuchoatdong', 'masodv');
            $a_phucap = getColTongHop();
            $a_pchien = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pck');
            $a_pc = array_diff($a_phucap, $a_pchien);
            foreach ($model_tonghop_ct as $ct) {
                if ($inputs['madv'] != "" && count($chekdv) > 0) {
                    $tonghop = $model_tonghop->where('masodv', $ct->masodv)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                } else {
                    $tonghop = $model_tonghop->where('masodv', $ct->masodv)->first();
                    $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
                }
                $ct->madv = $model_tonghop->where('masodv', $ct->masodv)->first()->madv;
                $ct->tendv = $model_donvi->where('madv', $ct->madv)->first()->tendv;
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                foreach ($a_pc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->pck += $ct->$pc;
                        $ct->st_pck += round($ct->$pc * $model_thongtu->muccu);
                    } else {
                        $ct->st_pck += $ct->$pc_st;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->$pc_st = round($ct->$pc * $model_thongtu->muccu);
                    } else {
                        //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                        $ct->$pc = 0;
                    }
                }
                $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            }
            $a_pl = array_diff(getPLCTTongHop(), getMaCongTacNhuCau());
            $model_tonghop_ct = $model_tonghop_ct->wherein('mact', $a_pl);
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE')->where('maphanloai', '<>', 'KVXP');
            $ar_I = array();
            $ar_Igr = array();
            if (isset($inputs['inchitiet'])) {
                $ar_Igr[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_Igr[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_Igr[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_Igr[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_Igr[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_Igr[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_Igr[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_Igr[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_Igr[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            } else {
                $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
                $ar_I[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
                $ar_I[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
                $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
                $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
                $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
                $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
                $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
                $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
                $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
                $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
                $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
                $ar_I[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            }
            $a_It = array(
                'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
                'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
                'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'pcdbqh' => 0, 'tongpc' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0,
            );
            //thiếu chỉ tiêu biên chế
            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt = 0;
                $i = 0;
                for ($j = 0; $j < count($ar_Igr); $j++) {
                    $i++;
                    if ($ar_Igr[$j]['val'] == 'GD;DT')
                        $gddt = $i;
                    if ($ar_Igr[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($ar_Igr[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN;DDT')
                        $qlnnddt = $i;
                    if ($ar_Igr[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($ar_Igr[$j]['val'] == 'DDT')
                        $ddt = $i;
                    $ar_I[$i]['tt'] = $ar_Igr[$j]['tt'];
                    $ar_I[$i]['noidung'] = $ar_Igr[$j]['noidung'];
                    foreach ($a_pchien as $pc) {
                        $ar_I[$i][$pc] = 0;
                    }
                    $ar_I[$i]['tongpc'] = 0;
                    $ar_I[$i]['ttbh_dv'] = 0;
                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                        $m_dvct = $model_donvi->wherein('madv', a_unique(array_column($chitiet->toarray(), 'madv')));
                    }
                    $d = 1;
                    $luugr = $i;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        $m_dvct = a_unique(array_column($m_dvct->toarray(), 'madv'));
                        foreach ($m_dvct as $dv) {
                            $thongtin = $chitiet->where('madv', $dv);
                            //foreach ($thongtin as $ttchitiet) {
                            $d++;
                            $i += $d;
                            $ar_I[$i]['tt'] = '+';
                            $ar_I[$i]['noidung'] = $model_donvi->where('madv', $dv)->first()->tendv;
                            $ar_I[$i]['soluongduocgiao'] = $model_bienche->where('linhvuchoatdong', $ar_Igr[$j]['val'])->where('madv', $dv)->sum('soluongduocgiao');
                            $ar_I[$i]['soluongbienche'] = count($thongtin);
                            $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                            $tongpc = 0;
                            foreach ($a_pchien as $pc) {
                                $pc_st = 'st_' . $pc;
                                $ar_I[$i][$pc] = $thongtin->sum($pc_st);
                                $a_It[$pc] += $ar_I[$i][$pc];
                                $tongpc += $thongtin->sum($pc_st);
                            }

                            $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                            $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$i]['ttbh_dv'] = $thongtin->sum('ttbh_dv');
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                            foreach ($a_pchien as $pc) {
                                $ar_I[$luugr][$pc] += $ar_I[$i][$pc];
                            }
                            $ar_I[$luugr]['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $ar_I[$luugr]['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //}
                        }
                    } else {
                        $ar_I[$i]['soluongduocgiao'] = 0;
                        $ar_I[$i]['soluongbienche'] = 0;
                        foreach ($a_pchien as $pc) {
                            $ar_I[$i][$pc] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $ar_I[$gddt][$pc] = $ar_I[$giaoduc][$pc] + $ar_I[$daotao][$pc];
                }
                $ar_I[$gddt]['soluongduocgiao'] = $ar_I[$giaoduc]['soluongduocgiao'] + $ar_I[$daotao]['soluongduocgiao'];
                $ar_I[$gddt]['soluongbienche'] = $ar_I[$giaoduc]['soluongbienche'] + $ar_I[$daotao]['soluongbienche'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];
                foreach ($a_pchien as $pc) {
                    $ar_I[$qlnnddt][$pc] = $ar_I[$qlnn][$pc] + $ar_I[$ddt][$pc];
                }
                $ar_I[$qlnnddt]['soluongduocgiao'] = $ar_I[$qlnn]['soluongduocgiao'] + $ar_I[$ddt]['soluongduocgiao'];
                $ar_I[$qlnnddt]['soluongbienche'] = $ar_I[$qlnn]['soluongbienche'] + $ar_I[$ddt]['soluongbienche'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($model_bangluong_ct)) {
                        $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                    }

                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                    if (isset($chitiet) && count($chitiet) > 0) {
                        $ar_I[$i]['soluongduocgiao'] = $model_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                        $ar_I[$i]['soluongbienche'] = count($chitiet);
                        $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                        $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                        $tongpc = 0;
                        foreach ($a_pchien as $pc) {
                            $pc_st = 'st_' . $pc;
                            $ar_I[$i][$pc] = $chitiet->sum($pc_st);
                            $a_It[$pc] += $ar_I[$i][$pc];
                            $tongpc += $chitiet->sum($pc_st);
                        }
                        $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                        $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                        $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                    } else {
                        foreach ($a_pchien as $pc) {
                            $ar_I[$i][$pc] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                    }
                }
                foreach ($a_pchien as $pc) {
                    $ar_I[11][$pc] = $ar_I[12][$pc] + $ar_I[13][$pc];
                    $ar_I[0][$pc] = $ar_I[1][$pc] + $ar_I[2][$pc];
                }
                $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
                $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
                $ar_I[0]['soluongbienche'] = $ar_I[1]['soluongbienche'] + $ar_I[2]['soluongbienche'];
            }
            if (isset($inputs['excel'])) {
                Excel::create('Mau2g_TT46', function ($excel) use ($ar_I, $a_It, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($ar_I, $a_It, $m_dv, $inputs) {
                        $sheet->loadView('reports.thongtu67.mau2g_tt46')
                            ->with('ar_I', $ar_I)
                            ->with('a_It', $a_It)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'Mau2g_TT46');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.thongtu67.mau2g_tt46')
                    ->with('furl', '/tong_hop_bao_cao/')
                    ->with('ar_I', $ar_I)
                    ->with('a_It', $a_It)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2g_huyen_excel()
    {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            Excel::create('Mau2g_ThPCUDTG', function ($excel) use ($m_dv) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv) {
                    $sheet->loadView('reports.thongtu67.Mau2g_ThPCUDTGexcel')
                        ->with('m_dv', $m_dv)
                        ->with('pageTitle', 'Mau2g_ThPCUDTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2h_huyen()
    {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            return view('reports.thongtu67.Mau2h_ThPCTHTG')
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP THU HÚT TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2h_huyen_excel()
    {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            Excel::create('Mau2h_ThPCTHTG', function ($excel) use ($m_dv) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv) {
                    $sheet->loadView('reports.thongtu67.Mau2h_ThPCTHTGexcel')
                        ->with('m_dv', $m_dv)
                        ->with('pageTitle', 'Mau2h_ThPCTHTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau4a_huyen(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            ///if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            // dd($inputs);
            $model_thongtu = dmthongtuquyetdinh::select('sohieu', 'namdt')->distinct()->get();
            if ($inputs['madv'] != '') {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                // dd($chekdv);
                if (count($chekdv) > 0) {
                    // $model = nguonkinhphi::where('macqcq', $inputs['madv'])
                    //     ->where('trangthai','DAGUI')
                    //     ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))->get();
                    $model = nguonkinhphi::where('macqcq', $inputs['madv'])
                        ->where('trangthai', 'DAGUI')
                        ->where('sohieu', $inputs['sohieu'])->get();
                } else {
                    // $model = nguonkinhphi::where('madv', $inputs['madv'])
                    //     ->where('trangthai','DAGUI')
                    //     ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))->get();
                    $model = nguonkinhphi::where('madv', $inputs['madv'])
                        ->where('trangthai', 'DAGUI')
                        ->where('sohieu', $inputs['sohieu'])->get();
                }
            } else {
                // $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                //     ->where('trangthai','DAGUI')
                //     ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))->get();
                $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                    ->where('trangthai', 'DAGUI')
                    ->where('sohieu', $inputs['sohieu'])->get();
            }
            // dd($model);
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            if ($inputs['madv'] != '') {
                $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            } else {
                $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            }

            $model_nguon = nguonkinhphi_huyen_baocao::where('madv', session('admin')->madv)
                ->where('trangthai', 'DAGUI')
                ->wherein('sohieu', array_column($model_thongtu->where('namdt', '2019')->toarray(), 'sohieu'))->get();
            if (count($model) == 0 && count($model_nguon) == 0) {
                return view('errors.nodata');
            }
            $a_A = array();
            $a_A[0] = array('tt' => '1', 'noidung' => '50% tăng/giảm thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) thực hiện 2018 so dự toán Thủ tướng Chính phủ giao năm 2018', 'sotien' => '0');
            $a_A[1] = array('tt' => '2', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) dự toán 2019 so dự toán 2018 Thủ tướng Chính phủ giao', 'sotien' => '0');
            $a_A[2] = array('tt' => '3', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) dự toán 2018 so dự toán 2017 Thủ tướng Chính phủ giao', 'sotien' => '0');
            $a_A[3] = array('tt' => '4', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017', 'sotien' => '0');
            $a_A[4] = array('tt' => '5', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2018', 'sotien' => '0');
            $a_A[5] = array('tt' => '6', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2019', 'sotien' => '0');
            $a_A[6] = array('tt' => '7', 'noidung' => 'Số thu được huy động từ nguồn để lại đơn vị năm 2019:', 'sotien' => '0');
            $a_A[7] = array('tt' => 'a', 'noidung' => 'Nguồn huy động từ các đơn vị tự đảm bảo(1):', 'sotien' => '0');
            $a_A[8] = array('tt' => '', 'noidung' => '+ Học phí', 'sotien' => '0');
            $a_A[9] = array('tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0');
            $a_A[10] = array('tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0');
            $a_A[11] = array('tt' => 'b', 'noidung' => 'Nguồn huy động từ các đơn vị chưa tự đảm bảo chi thường xuyên:', 'sotien' => '0');
            $a_A[12] = array('tt' => '', 'noidung' => '+ Học phí', 'sotien' => '0');
            $a_A[13] = array('tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0');
            $a_A[14] = array('tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0');
            $a_A[15] = array('tt' => '8', 'noidung' => 'Nguồn 50% phần ngân sách nhà nước giảm chi hỗ trợ hoạt động thường xuyên trong lĩnh vực hành chính (do tinh giản biên chế và đổi mới, sắp xếp lại bộ máy của hệ thống chính trị tinh gọn, hoạt động hiệu lực, hiệu quả) và các đơn vị sự nghiệp công lập (do thực hiện đổi mới hệ thống tổ chức và quản lý, nâng cao chất lượng và hiệu quả hoạt động của đơn vị sự nghiệp công lập) năm 2019', 'sotien' => '0');
            $a_A[16] = array('tt' => '', 'noidung' => '+ Từ việc tinh giản biên chế tổ chức lại bộ máy (2)', 'sotien' => '0');
            $a_A[17] = array('tt' => '', 'noidung' => '+ Từ việc sát nhập các đầu mối, cơ quan, đơn vị (2)', 'sotien' => '0');
            $a_A[18] = array('tt' => '', 'noidung' => '+ Từ việc thay đổi cơ chế tự chủ của đơn vị sư nghiệp (3)', 'sotien' => '0');
            $a_A[19] = array('tt' => '', 'noidung' => '+ Từ việc sát nhập các xã không đủ điều kiện tiêu chuẩn', 'sotien' => '0');
            $a_A[20] = array('tt' => '9', 'noidung' => 'Nguồn NSTW đã bổ sung trong dự toán 2019', 'sotien' => '0');
            $a_A[21] = array('tt' => '10', 'noidung' => 'Nguồn thực hiện cải cách tiền lương năm 2018 chưa sử dụng hết chuyển sang năm 2019', 'sotien' => '0');

            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BII[1] = array('tt' => '', 'noidung' => 'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)', 'sotien' => '0');
            $a_BII[2] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BII[3] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BII[4] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 44/2019/NĐ-CP', 'sotien' => '0');
            $a_BII[5] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BII[6] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BII[7] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017', 'sotien' => '0');

            $a_BIII = array();
            $a_BIII[0] = array('tt' => '1', 'noidung' => 'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)', 'sotien' => '0');
            $a_BIII[1] = array('tt' => '2', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2019 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)', 'sotien' => '0');
            $a_BIII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2019 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');
            $a_BIII[3] = array('tt' => '4', 'noidung' => 'Kinh phí giảm do điều chỉnh danh sách huyện nghèo theo Quyết định số 275/QĐ-TTg ngày 07/3/2018 của Thủ tướng Chính phủ (quy định tại điểm b khoản 2 Công văn số 1044/BNV-TL ngày 11/3/2019 của Bộ Nội vụ)', 'sotien' => '0');
            $a_BIII[4] = array('tt' => 'a', 'noidung' => 'Kinh phí thu hút', 'sotien' => '0');
            $a_BIII[5] = array('tt' => 'b', 'noidung' => 'Chênh lệch kinh phí ưu đãi', 'sotien' => '0');
            $a_BIII[6] = array('tt' => '5', 'noidung' => 'Kinh phí giảm do điều chỉnh số lượng cán bộ, công chức cấp xã; mức khoán phụ cấp đối với người hoạt động không chuyên trách ở cấp xã theo Nghị định số 34/2019/NĐ-CP của Chính phủ (7)', 'sotien' => '0');

            $a_C = array();
            $a_C[0] = array('tt' => '1', 'noidung' => 'Phần thiếu nguồn ngân sách trung ương hỗ trợ', 'sotien' => '0');
            $a_C[1] = array('tt' => '2', 'noidung' => 'Nguồn thực hiện cải cách tiền lương còn dư', 'sotien' => '0');

            //Tính toán
            // $a_A[0]['sotien'] = $model->sum('thuchien');
            // $a_A[1]['sotien'] = $model->sum('dutoan19');
            // $a_A[2]['sotien'] = $model->sum('dutoan18');
            // $a_A[3]['sotien'] = $model->sum('tietkiem17');
            // $a_A[4]['sotien'] = $model->sum('tietkiem18');
            // $a_A[5]['sotien'] = $model->sum('tietkiem19');

            $a_A[0]['sotien'] = $model->sum('thuchien1');
            $a_A[1]['sotien'] = $model->sum('dutoan');
            $a_A[2]['sotien'] = $model->sum('dutoan1');

            $a_A[3]['sotien'] = $model->sum('tietkiem2');
            $a_A[4]['sotien'] = $model->sum('tietkiem1');
            $a_A[5]['sotien'] = $model->sum('tietkiem');
            //Tự đảm bảo
            $model_tudb = $model->wherein('phanloainguon', array('CHITXDT', 'CTX'));
            // $a_A[8]['sotien'] = $model->sum('dbhocphi');
            // $a_A[9]['sotien'] = $model->sum('dbvienphi');
            // $a_A[10]['sotien'] = $model->sum('dbkhac');
            // $a_A[7]['sotien'] = $a_A[8]['sotien'] + $a_A[9]['sotien'] + $a_A[10]['sotien'];
            $a_A[8]['sotien'] = $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien'] +  $a_A[10]['sotien'];

            // $a_A[12]['sotien'] = $model->sum('kdbhocphi');
            // $a_A[13]['sotien'] = $model->sum('kdbvienphi');
            // $a_A[14]['sotien'] = $model->sum('kdbkhac');
            // $a_A[11]['sotien'] = $a_A[12]['sotien'] + $a_A[13]['sotien'] + $a_A[14]['sotien'];
            $a_A[12]['sotien'] = $model->sum('hocphi') - $a_A[8]['sotien'];
            $a_A[13]['sotien'] = $model->sum('vienphi') - $a_A[9]['sotien'];
            $a_A[14]['sotien'] = $model->sum('nguonthu') - $a_A[10]['sotien'];
            $a_A[11]['sotien'] =  $a_A[12]['sotien'] +  $a_A[13]['sotien'] +  $a_A[14]['sotien'];
            $a_A[6]['sotien'] = $a_A[7]['sotien'] + $a_A[11]['sotien'];

            $a_A[15]['sotien'] = $model->sum('tietkiemchi');
            $a_A[20]['sotien'] = $model->sum('bosung');
            $a_A[21]['sotien'] = $model->sum('caicach');
            //Tổng nhu cầu năm 2017
            $model_nc2017 = nguonkinhphi::where('madvbc', 'like', $inputs['madv'] . '%')
                ->wherein('sohieu', array_column($model_thongtu->where('namdt', '2017')->toarray(), 'sohieu'))->get();
            //Tổng nhu cầu năm 2018
            $model_nc2018 = nguonkinhphi::where('madvbc', 'like', $inputs['madv'] . '%')
                ->wherein('sohieu', array_column($model_thongtu->where('namdt', '2018')->toarray(), 'sohieu'))->get();

            $model_xp = $model->where('maphanloai', 'KVXP');
            $a_BII[1]['sotien'] = $model_tudb->sum('luongphucap') + $model_tudb->sum('baohiem');
            $a_BII[2]['sotien'] = $model_xp->sum('luongphucap') + $model_xp->sum('baohiem');
            $a_BII[0]['sotien'] = $model->sum('luongphucap') + $model->sum('baohiem') - $model_xp->sum('luongphucap') - $model_xp->sum('baohiem');
            $a_BII[3]['sotien'] = $model->sum('daibieuhdnd');
            $a_BII[4]['sotien'] = $model->sum('nghihuu');
            $a_BII[5]['sotien'] = $model->sum('canbokct');
            $a_BII[6]['sotien'] = $model->sum('uyvien');
            $a_BII[7]['sotien'] = $model->sum('boiduong');

            $a_BIII[0]['sotien'] = $model->sum('diaban');
            $a_BIII[1]['sotien'] = $model->sum('tinhgiam');
            $a_BIII[2]['sotien'] = $model->sum('nghihuusom');
            $a_BIII[4]['sotien'] = $model->sum('kpthuhut');
            $a_BIII[5]['sotien'] = $model->sum('kpuudai');
            $a_BIII[3]['sotien'] = $a_BIII[4]['sotien'] + $a_BIII[5]['sotien'];
            $a_BIII[6]['sotien'] = 0;

            $a_C[0]['sotien'] = 0;
            $a_C[1]['sotien'] = 0;

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[3]['sotien']
                    + $a_A[4]['sotien'] + $a_A[5]['sotien'] + $a_A[6]['sotien'] + $a_A[15]['sotien'] + $a_A[20]['sotien'] + $a_A[21]['sotien']),
                'BI' => $model->sum('tongnhucau1'),
                'BI1' => $model->sum('tongnhucau2'),
                'BII' => (array_sum(array_column($a_BII, 'sotien')) - $a_BII[1]['sotien']),
                'BIII' => (array_sum(array_column($a_BIII, 'sotien')))
            );
            if (isset($inputs['excel'])) {
                Excel::create('mau4a_tt46', function ($excel) use ($model, $a_A, $a_BII, $a_BIII, $a_TC, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($model, $a_A, $a_BII, $a_BIII, $a_TC, $m_dv, $inputs) {
                        $sheet->loadView('reports.thongtu67.mau4a_tt46')
                            ->with('model', $model)
                            ->with('a_A', $a_A)
                            ->with('a_BII', $a_BII)
                            ->with('a_BIII', $a_BIII)
                            ->with('a_TC', $a_TC)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'mau4a_tt46');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.thongtu67.mau4a_tt46')
                    ->with('model', $model)
                    ->with('a_A', $a_A)
                    ->with('a_BII', $a_BII)
                    ->with('a_BIII', $a_BIII)
                    ->with('a_TC', $a_TC)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4a_huyen_excel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            ///if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('madvbc', 'like', $inputs['madv'] . '%')
                ->where('sohieu', 'ND38_2019')->get();
            if (session('admin')->username == 'khthso') {
                $model = nguonkinhphi::join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->where('nguonkinhphi.sohieu', 'ND38_2019')->get();
            }
            $model_donvi = dmdonvi::where('madvbc', session('admin')->madvbc)->get();
            if (count($model) == 0) {
                return view('errors.nodata');
            }
            //foreach($model as $ct){
            //    $donvi = $model_donvi->where('madv',$ct->madv)->first();
            //    $ct->phanloainguon = $donvi->phanloainguon;
            //    $ct->maphanloai = $donvi->maphanloai;
            //}
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt' => '1', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2016 so dự toán Thủ tướng Chính phủ giao năm 2016', 'sotien' => '0');
            $a_A[1] = array('tt' => '2', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017', 'sotien' => '0');
            $a_A[2] = array('tt' => '3', 'noidung' => 'Số thu được huy động từ nguồn để lại đơn vị năm 2017', 'sotien' => '0');
            $a_A[3] = array('tt' => 'a', 'noidung' => 'Nguồn huy động từ các đơn vị tự đảm bảo', 'sotien' => '0');
            $a_A[4] = array('tt' => '+', 'noidung' => 'Học phí', 'sotien' => '0');
            $a_A[5] = array('tt' => '+', 'noidung' => 'Viện phí', 'sotien' => '0');
            $a_A[6] = array('tt' => '+', 'noidung' => 'Nguồn thu khác', 'sotien' => '0');
            $a_A[7] = array('tt' => 'b', 'noidung' => 'Nguồn huy động từ các đơn vị chưa tự đảm bảo', 'sotien' => '0');
            $a_A[8] = array('tt' => '+', 'noidung' => 'Học phí', 'sotien' => '0');
            $a_A[9] = array('tt' => '+', 'noidung' => 'Viện phí', 'sotien' => '0');
            $a_A[10] = array('tt' => '+', 'noidung' => 'Nguồn thu khác', 'sotien' => '0');
            $a_A[11] = array('tt' => '4', 'noidung' => 'Nguồn thực hiện cải cách tiền lương năm 2016 chưa sử dụng hết chuyển sang 2017', 'sotien' => '0');

            //
            $a_BI = array();
            $a_BI[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BI[1] = array('tt' => '', 'noidung' => 'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo ', 'sotien' => '0');
            $a_BI[2] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BI[3] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BI[4] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 76/2017/NĐ-CP', 'sotien' => '0');
            $a_BI[5] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BI[6] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BI[7] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 3115-QĐ/VVPTW', 'sotien' => '0');

            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Kinh phí hỗ trợ chênh lệch tiền lương cho người có thu nhập thấp (NĐ17/2015/NĐ-CP) và mức lương 1,21 (6 tháng)', 'sotien' => '0');
            $a_BII[1] = array('tt' => '2', 'noidung' => 'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ', 'sotien' => '0');
            $a_BII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2017 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)', 'sotien' => '0');
            $a_BII[3] = array('tt' => '4', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2017 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon', array('CHITXDT', 'CTX'));
            $a_A[4]['sotien'] = $model_tudb->sum('hocphi');
            $a_A[5]['sotien'] = $model_tudb->sum('vienphi');
            $a_A[6]['sotien'] = $model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien'] +  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi') - $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu') - $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien'] +  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai', 'KVXP');

            $a_BI[1]['sotien'] = $model_tudb->sum('luongphucap');
            $a_BI[2]['sotien'] = $model_xp->sum('luongphucap');
            $a_BI[0]['sotien'] = $model->sum('luongphucap') - $model_xp->sum('luongphucap');

            $a_BI[3]['sotien'] = $model->sum('daibieuhdnd');
            $a_BI[4]['sotien'] = $model->sum('nghihuu');
            $a_BI[5]['sotien'] = $model->sum('canbokct');
            $a_BI[6]['sotien'] = $model->sum('uyvien');
            $a_BI[7]['sotien'] = $model->sum('boiduong');

            $a_BII[0]['sotien'] = $model->sum('thunhapthap');
            $a_BII[1]['sotien'] = $model->sum('diaban');
            $a_BII[2]['sotien'] = $model->sum('tinhgiam');
            $a_BII[3]['sotien'] = $model->sum('nghihuusom');

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BI' => (array_sum(array_column($a_BI, 'sotien')) - $a_BI[1]['sotien']),
                'BII' => (array_sum(array_column($a_BII, 'sotien')))
            );
            Excel::create('Mau2h_ThPCTHTG', function ($excel) use ($model, $a_A, $a_BI, $a_BII, $a_TC, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($model, $a_A, $a_BI, $a_BII, $a_TC, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.Mau2h_ThPCTHTGexcel')
                        ->with('model', $model)
                        ->with('a_A', $a_A)
                        ->with('a_BI', $a_BI)
                        ->with('a_BII', $a_BII)
                        ->with('a_TC', $a_TC)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'Mau2h_ThPCTHTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau4b_huyen(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $model_thongtu = dmthongtuquyetdinh::select('sohieu', 'namdt')->distinct()->get();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq', $madv)
                        ->where('macqcq', session('admin')->madv)
                        ->where('trangthai', 'DAGUI')
                        // ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))
                        ->where('sohieu', $inputs['sohieu'])
                        ->get();
                    // dd($m_dv);
                } else {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->first();
                    // ->distinct()
                    // ->get();
                    $model = nguonkinhphi::where('madv', $madv)
                        ->where('macqcq', session('admin')->madv)
                        ->where('trangthai', 'DAGUI')
                        // ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))
                        ->where('sohieu', $inputs['sohieu'])
                        ->get();
                    // dd($m_dv);
                }
            } else {
                $m_dv = dmdonvi::select('tendv', 'madv')
                    ->where('madv', session('admin')->madv)
                    ->first();
                // ->distinct()
                // ->get();
                $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                    ->where('trangthai', 'DAGUI')
                    // ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))
                    ->where('sohieu', $inputs['sohieu'])
                    ->get();
            }
            $ardv = $m_dv->toArray();

            $a_th = array_column(nguonkinhphi_huyen_baocao::where('trangthai', 'DAGUI')
                ->where('madv', session('admin')->madv)->get()->toarray(), 'masodv');
            $model_nguon = nguonkinhphi_huyen_baocao_chitiet::where('madv', session('admin')->madv)
                ->wherein('masodv', $a_th)
                // ->wherein('sohieu',array_column($model_thongtu->where('namdt','2019')->toarray(),'sohieu'))->get();
                ->where('sohieu', $inputs['sohieu'])->get();
            if (count($model) == 0 && count($model_nguon) == 0) {
                return view('errors.nodata');
            }
            // $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
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

            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnn = 0;
                $yte = 0;
                $snkhac = 0;
                $i = 0;
                for ($j = 0; $j < count($group); $j++) {
                    $i++;
                    if ($group[$j]['val'] == 'GDDT')
                        $gddt = $i;
                    if ($group[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($group[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($group[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($group[$j]['val'] == 'YTE')
                        $yte = $i;
                    if ($group[$j]['val'] == 'KHAC')
                        $snkhac = $i;
                    $data[$i]['tt'] = $group[$j]['tt'];
                    $data[$i]['noidung'] = $group[$j]['noidung'];
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $luugr = $i;
                    if ($group[$j]['val'] == 'KHAC') {
                        foreach ($a_sunghiep as $sn) {
                            if (!in_array($sn, $a_sn))
                                $dulieu = $model->where('linhvuchoatdong', $sn);
                            if (isset($dulieu) && count($dulieu) > 0) {
                                foreach ($ardv as $chitietdv) {
                                    $solieu = $dulieu->where('madv', $chitietdv['madv']);
                                    $d = 1;
                                    if (isset($solieu) && count($solieu) > 0) {
                                        $d++;
                                        $i += $d;
                                        $data[$i]['tt'] = '+';
                                        $data[$i]['noidung'] = $chitietdv['tendv'];
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

                                        $data[$luugr]['nhucau'] += $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                                            $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                                            $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                                            $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                                            $solieu->sum('kpuudai');

                                        //$data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                        $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                        $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                        $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                        $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                        $data[$luugr]['khac'] += 0;
                                        $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                                    }
                                }
                            }
                        }
                    } else {
                        $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                        if (isset($dulieu) && count($dulieu) > 0) {
                            //$luugr = 0;
                            $luugr = $i;
                            foreach ($ardv as $chitietdv) {
                                $solieu = $dulieu->where('madv', $chitietdv['madv']);
                                $d = 1;
                                if (isset($solieu) && count($solieu) > 0) {
                                    $d++;
                                    $i += $d;
                                    $data[$i]['tt'] = '+';
                                    $data[$i]['noidung'] = $chitietdv['tendv'];
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

                                    $data[$luugr]['nhucau'] += $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                                        $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                                        $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                                        $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                                        $solieu->sum('kpuudai');

                                    //$data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                    $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                    $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                    $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                    $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                    $data[$luugr]['khac'] += 0;
                                    $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                                }
                            }
                        }
                    }
                }
                $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
                $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
                $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
                $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
                $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
                $data[$gddt]['khac'] = 0;
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
            } else {
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]['val'] == 'KHAC') {
                        foreach ($a_sunghiep as $sn) {
                            if (!in_array($sn, $a_sn))
                                $solieu = $model->where('linhvuchoatdong', $sn);
                            $solieu_nguon = $model_nguon->where('linhvuchoatdong', $sn);
                            if (isset($dulieu) && count($dulieu) > 0) {
                                $data[$i]['nhucau'] = $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                                    $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                                    $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                                    $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                                    $solieu->sum('kpuudai');
                                //$data[$i]['nhucau'] = $solieu->sum('nhucau');
                                $data[$i]['tietkiem'] = $solieu_nguon->sum('tietkiem');
                                $data[$i]['hocphi'] = $solieu_nguon->sum('dbhocphi') + $solieu_nguon->sum('kdbhocphi');
                                $data[$i]['vienphi'] = $solieu_nguon->sum('dbvienphi') + $solieu_nguon->sum('kdbvienphi');
                                $data[$i]['khac'] = $solieu_nguon->sum('dbkhac') + $solieu_nguon->sum('kdbkhac');
                                $data[$i]['nguonthu'] = $solieu_nguon->sum('tietkiemchi');
                                $data[$i]['nguonkp'] = $data[$i]['hocphi'] + $data[$i]['vienphi'] + $data[$i]['khac'] + $data[$i]['nguonthu'];
                            }
                        }
                    } else {
                        $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                        $solieu_nguon = $model_nguon->where('linhvuchoatdong', $data[$i]['val']);
                        $data[$i]['nhucau'] = $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                            $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                            $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                            $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                            $solieu->sum('kpuudai');
                        //$data[$i]['nhucau'] = $solieu->sum('nhucau');
                        // $data[$i]['tietkiem'] = $solieu_nguon->sum('tietkiem');
                        // $data[$i]['hocphi'] = $solieu_nguon->sum('dbhocphi') + $solieu_nguon->sum('kdbhocphi');
                        // $data[$i]['vienphi'] = $solieu_nguon->sum('dbvienphi') + $solieu_nguon->sum('kdbvienphi');
                        // $data[$i]['khac'] = $solieu_nguon->sum('dbkhac') + $solieu_nguon->sum('kdbkhac');
                        // $data[$i]['nguonthu'] = $solieu_nguon->sum('tietkiemchi');
                        // $data[$i]['nguonkp'] = $data[$i]['tietkiem'] + $data[$i]['hocphi'] + $data[$i]['vienphi'] + $data[$i]['khac'] + $data[$i]['nguonthu'];

                        $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                        $data[$i]['hocphi'] = $solieu->sum('hocphi');
                        $data[$i]['vienphi'] = $solieu->sum('vienphi');
                        $data[$i]['khac'] = $solieu->sum('nguonthu');
                        $data[$i]['nguonthu'] = $solieu->sum('tietkiem1');
                        $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    }
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['khac'] = $data[1]['khac'] + $data[2]['khac'];
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];
            }
            //dd($data);
            if (isset($inputs['inchitiet'])) {
                $a_TC = array(
                    'nhucau' => ($data[$gddt]['nhucau'] + $data[$yte]['nhucau'] + $data[$snkhac]['nhucau'] + $data[$qlnn]['nhucau']),
                    'nguonkp' => ($data[$gddt]['nguonkp'] + $data[$yte]['nguonkp'] + $data[$snkhac]['nguonkp'] + $data[$qlnn]['nguonkp']),
                    'tietkiem' => ($data[$gddt]['tietkiem'] + $data[$yte]['tietkiem'] + $data[$snkhac]['tietkiem'] + $data[$qlnn]['tietkiem']),
                    'hocphi' => ($data[$gddt]['hocphi'] + $data[$yte]['hocphi'] + $data[$snkhac]['hocphi'] + $data[$qlnn]['hocphi']),
                    'vienphi' => ($data[$gddt]['vienphi'] + $data[$yte]['vienphi'] + $data[$snkhac]['vienphi'] + $data[$qlnn]['vienphi']),
                    'khac' => ($data[$gddt]['khac'] + $data[$yte]['khac'] + $data[$snkhac]['khac'] + $data[$qlnn]['khac']),
                    'nguonthu' => ($data[$gddt]['nguonthu'] + $data[$yte]['nguonthu'] + $data[$snkhac]['nguonthu'] + $data[$qlnn]['nguonthu'])
                );
            } else {
                $a_TC = array(
                    'nhucau' => ($data[0]['nhucau'] + $data[3]['nhucau'] + $data[4]['nhucau'] + $data[5]['nhucau']),
                    'nguonkp' => ($data[0]['nguonkp'] + $data[3]['nguonkp'] + $data[4]['nguonkp'] + $data[5]['nguonkp']),
                    'tietkiem' => ($data[0]['tietkiem'] + $data[3]['tietkiem'] + $data[4]['tietkiem'] + $data[5]['tietkiem']),
                    'hocphi' => ($data[0]['hocphi'] + $data[3]['hocphi'] + $data[4]['hocphi'] + $data[5]['hocphi']),
                    'vienphi' => ($data[0]['vienphi'] + $data[3]['vienphi'] + $data[4]['vienphi'] + $data[5]['vienphi']),
                    'khac' => ($data[0]['khac'] + $data[3]['khac'] + $data[4]['khac'] + $data[5]['khac']),
                    'nguonthu' => ($data[0]['nguonthu'] + $data[3]['nguonthu'] + $data[4]['nguonthu'] + $data[5]['nguonthu'])
                );
            }
            // dd($m_dv);
            if (isset($inputs['excel'])) {
                Excel::create('mau4b_tt46', function ($excel) use ($model, $data, $m_dv, $inputs, $a_TC) {
                    $excel->sheet('New sheet', function ($sheet) use ($model, $data, $m_dv, $inputs, $a_TC) {
                        $sheet->loadView('reports.thongtu46.huyen.mau4b_tt46')
                            ->with('model', $model)
                            ->with('data', $data)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('a_TC', $a_TC)
                            ->with('pageTitle', 'mau4b_tt46');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.thongtu46.huyen.mau4b_tt46')
                    ->with('model', $model)
                    ->with('data', $data)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('a_TC', $a_TC)
                    ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4b_huyen_excel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq', $madv)
                        ->where('sohieu', 'ND38_2019')->get();
                } else {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv', $madv)
                        ->where('sohieu', 'ND38_2019')->get();
                }
            } else {
                $m_dv = dmdonvi::select('tendv', 'madv')
                    ->where('madvbc', $madvbc)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('madvbc', $madvbc)
                    ->where('sohieu', 'ND38_2019')->get();
            }
            $ardv = $m_dv->toArray();
            /*
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','ND38_2019')->get();
            */
            if (session('admin')->username == 'khthso') {
                $model = nguonkinhphi::join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->where('nguonkinhphi.sohieu', 'ND38_2019')->get();
            }
            //dd($model);
            if (count($model) == 0) {
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $data = array();
            $group = array();
            if (isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            } else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $i = 0;
                for ($j = 0; $j < count($group); $j++) {
                    $i++;
                    if ($group[$j]['val'] == 'GDDT')
                        $gddt = $i;
                    if ($group[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($group[$j]['val'] == 'DT')
                        $daotao = $i;
                    $data[$i]['tt'] = $group[$j]['tt'];
                    $data[$i]['noidung'] = $group[$j]['noidung'];
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        //$luugr = 0;
                        $luugr = $i;
                        foreach ($ardv as $chitietdv) {
                            $solieu = $model->where('madv', $chitietdv['madv'])->where('linhvuchoatdong', $group[$j]['val']);
                            $d = 1;
                            if (isset($solieu) && count($solieu) > 0) {
                                //dd($solieu);
                                $d++;
                                $i += $d;
                                $data[$i]['tt'] = '+';
                                $data[$i]['noidung'] = $chitietdv['tendv'];
                                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                            }
                        }
                    }
                }
                $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
                $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
                $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
                $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
                $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
            } else {
                for ($i = 0; $i < count($data); $i++) {
                    $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                    $data[$i]['nhucau'] = $solieu->sum('nhucau');
                    $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                    $data[$i]['hocphi'] = $solieu->sum('hocphi');
                    $data[$i]['vienphi'] = $solieu->sum('vienphi');
                    $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];

                $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
                $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
                $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
                $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
                $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
                $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
            }
            Excel::create('mau4b', function ($excel) use ($model, $data, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($model, $data, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.mau4bexcel')
                        ->with('model', $model)
                        ->with('data', $data)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'mau4b');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau4bbs_huyen(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $model_thongtu = dmthongtuquyetdinh::select('sohieu', 'namdt')->distinct()->get();
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq', $madv)
                        ->where('macqcq', session('admin')->madv)
                        ->where('trangthai', 'DAGUI')
                        ->wherein('sohieu', array_column($model_thongtu->where('namdt', '2019')->toarray(), 'sohieu'))
                        ->get();
                } else {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv', $madv)
                        ->where('macqcq', session('admin')->madv)
                        ->where('trangthai', 'DAGUI')
                        ->wherein('sohieu', array_column($model_thongtu->where('namdt', '2019')->toarray(), 'sohieu'))
                        ->get();
                }
            } else {
                $m_dv = dmdonvi::select('tendv', 'madv')
                    ->where('macqcq', session('admin')->madv)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('sohieu', array_column($model_thongtu->where('namdt', '2019')->toarray(), 'sohieu'))
                    ->get();
            }
            $ardv = $m_dv->toArray();
            if (count($model) == 0) {
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
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

            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnn = 0;
                $yte = 0;
                $snkhac = 0;
                $i = 0;
                for ($j = 0; $j < count($group); $j++) {
                    $i++;
                    if ($group[$j]['val'] == 'GDDT')
                        $gddt = $i;
                    if ($group[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($group[$j]['val'] == 'DT')
                        $daotao = $i;
                    if ($group[$j]['val'] == 'QLNN')
                        $qlnn = $i;
                    if ($group[$j]['val'] == 'YTE')
                        $yte = $i;
                    if ($group[$j]['val'] == 'KHAC')
                        $snkhac = $i;
                    $data[$i]['tt'] = $group[$j]['tt'];
                    $data[$i]['noidung'] = $group[$j]['noidung'];
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $data[$i]['bosung'] = 0;
                    $luugr = $i;
                    if ($group[$j]['val'] == 'KHAC') {
                        foreach ($a_sunghiep as $sn) {
                            if (!in_array($sn, $a_sn))
                                $dulieu = $model->where('linhvuchoatdong', $sn);
                            if (isset($dulieu) && count($dulieu) > 0) {
                                foreach ($ardv as $chitietdv) {
                                    $solieu = $dulieu->where('madv', $chitietdv['madv']);
                                    $d = 1;
                                    if (isset($solieu) && count($solieu) > 0) {
                                        $d++;
                                        $i += $d;
                                        $data[$i]['tt'] = '+';
                                        $data[$i]['noidung'] = $chitietdv['tendv'];
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
                                        $data[$i]['bosung'] = $data[$i]['nhucau'] - $data[$i]['nguonkp'];

                                        $data[$luugr]['nhucau'] += $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                                            $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                                            $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                                            $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                                            $solieu->sum('kpuudai');

                                        //$data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                        $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                        $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                        $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                        $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                        $data[$luugr]['khac'] += 0;
                                        $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                                        $data[$luugr]['bosung'] += $data[$luugr]['nhucau'] - $data[$luugr]['nguonkp'];
                                    }
                                }
                            }
                        }
                    } else {
                        $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                        if (isset($dulieu) && count($dulieu) > 0) {
                            //$luugr = 0;
                            $luugr = $i;
                            foreach ($ardv as $chitietdv) {
                                $solieu = $dulieu->where('madv', $chitietdv['madv']);
                                $d = 1;
                                if (isset($solieu) && count($solieu) > 0) {
                                    $d++;
                                    $i += $d;
                                    $data[$i]['tt'] = '+';
                                    $data[$i]['noidung'] = $chitietdv['tendv'];
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
                                    $data[$i]['bosung'] =  $data[$i]['nhucau'] - $data[$i]['nguonkp'];

                                    $data[$luugr]['nhucau'] += $solieu->sum('luongphucap') + $solieu->sum('baohiem') +
                                        $solieu->sum('daibieuhdnd') + $solieu->sum('nghihuu') + $solieu->sum('canbokct') +
                                        $solieu->sum('uyvien') + $solieu->sum('boiduong') + $solieu->sum('diaban') +
                                        $solieu->sum('tinhgiam') + $solieu->sum('nghihuusom') + $solieu->sum('kpthuhut') +
                                        $solieu->sum('kpuudai');

                                    //$data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                    $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                    $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                    $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                    $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                    $data[$luugr]['khac'] += 0;
                                    $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                                    $data[$luugr]['bosung'] += $data[$luugr]['nhucau'] - $data[$luugr]['nguonkp'];
                                }
                            }
                        }
                    }
                }
                $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
                $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
                $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
                $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
                $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
                $data[$gddt]['khac'] = 0;
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
                $data[$gddt]['bosung'] = $data[$giaoduc]['bosung'] + $data[$daotao]['bosung'];
            } else {
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
                    $data[$i]['bosung'] = $data[$i]['nhucau'] - $data[$i]['nguonkp'];
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['khac'] = 0;
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];
                $data[0]['bosung'] = $data[1]['bosung'] + $data[2]['bosung'];

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
                $data[4]['bosung'] = $data[4]['nhucau'] - $data[4]['nguonkp'];
            }
            //dd($data);
            if (isset($inputs['inchitiet'])) {
                $a_TC = array(
                    'nhucau' => ($data[$gddt]['nhucau'] + $data[$yte]['nhucau'] + $data[$snkhac]['nhucau'] + $data[$qlnn]['nhucau']),
                    'nguonkp' => ($data[$gddt]['nguonkp'] + $data[$yte]['nguonkp'] + $data[$snkhac]['nguonkp'] + $data[$qlnn]['nguonkp']),
                    'tietkiem' => ($data[$gddt]['tietkiem'] + $data[$yte]['tietkiem'] + $data[$snkhac]['tietkiem'] + $data[$qlnn]['tietkiem']),
                    'hocphi' => ($data[$gddt]['hocphi'] + $data[$yte]['hocphi'] + $data[$snkhac]['hocphi'] + $data[$qlnn]['hocphi']),
                    'vienphi' => ($data[$gddt]['vienphi'] + $data[$yte]['vienphi'] + $data[$snkhac]['vienphi'] + $data[$qlnn]['vienphi']),
                    'khac' => 0,
                    'nguonthu' => ($data[$gddt]['nguonthu'] + $data[$yte]['nguonthu'] + $data[$snkhac]['nguonthu'] + $data[$qlnn]['nguonthu']),
                    'bosung' => ($data[$gddt]['bosung'] + $data[$yte]['bosung'] + $data[$snkhac]['bosung'] + $data[$qlnn]['bosung'])
                );
            } else {
                $a_TC = array(
                    'nhucau' => ($data[0]['nhucau'] + $data[3]['nhucau'] + $data[4]['nhucau'] + $data[5]['nhucau']),
                    'nguonkp' => ($data[0]['nguonkp'] + $data[3]['nguonkp'] + $data[4]['nguonkp'] + $data[5]['nguonkp']),
                    'tietkiem' => ($data[0]['tietkiem'] + $data[3]['tietkiem'] + $data[4]['tietkiem'] + $data[5]['tietkiem']),
                    'hocphi' => ($data[0]['hocphi'] + $data[3]['hocphi'] + $data[4]['hocphi'] + $data[5]['hocphi']),
                    'vienphi' => ($data[0]['vienphi'] + $data[3]['vienphi'] + $data[4]['vienphi'] + $data[5]['vienphi']),
                    'khac' => ($data[0]['khac'] + $data[3]['khac'] + $data[4]['khac'] + $data[5]['khac']),
                    'nguonthu' => ($data[0]['nguonthu'] + $data[3]['nguonthu'] + $data[4]['nguonthu'] + $data[5]['nguonthu']),
                    'bosung' => ($data[0]['bosung'] + $data[3]['bosung'] + $data[4]['bosung'] + $data[5]['bosung'])
                );
            }
            if (isset($inputs['excel'])) {
                Excel::create('mau4b_tt46bosung', function ($excel) use ($model, $data, $m_dv, $inputs, $a_TC) {
                    $excel->sheet('New sheet', function ($sheet) use ($model, $data, $m_dv, $inputs, $a_TC) {
                        $sheet->loadView('reports.thongtu46.huyen.mau4b_tt46bosung')
                            ->with('model', $model)
                            ->with('data', $data)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('a_TC', $a_TC)
                            ->with('pageTitle', 'mau4b_tt46bosung');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.thongtu46.huyen.mau4b_tt46bosung')
                    ->with('model', $model)
                    ->with('data', $data)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('a_TC', $a_TC)
                    ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4bbs_huyen_excel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            if ($inputs['madv'] != "") {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                if (count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq', $madv)
                        ->where('sohieu', 'ND38_2019')->get();
                } else {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv', $madv)
                        ->where('sohieu', 'ND38_2019')->get();
                }
            } else {
                $m_dv = dmdonvi::select('tendv', 'madv')
                    ->where('madvbc', $madvbc)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('madvbc', $madvbc)
                    ->where('sohieu', 'ND38_2019')->get();
            }
            $ardv = $m_dv->toArray();
            if (session('admin')->username == 'khthso') {
                $model = nguonkinhphi::join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->where('nguonkinhphi.sohieu', 'ND38_2019')->get();
            }
            //dd($model);
            if (count($model) == 0) {
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $data = array();
            $group = array();
            if (isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            } else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if (isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $i = 0;
                for ($j = 0; $j < count($group); $j++) {
                    $i++;
                    if ($group[$j]['val'] == 'GDDT')
                        $gddt = $i;
                    if ($group[$j]['val'] == 'GD')
                        $giaoduc = $i;
                    if ($group[$j]['val'] == 'DT')
                        $daotao = $i;
                    $data[$i]['tt'] = $group[$j]['tt'];
                    $data[$i]['noidung'] = $group[$j]['noidung'];
                    $data[$i]['nhucau'] = 0;
                    $data[$i]['nguonkp'] = 0;
                    $data[$i]['tietkiem'] = 0;
                    $data[$i]['hocphi'] = 0;
                    $data[$i]['vienphi'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $data[$i]['bosung'] = 0;
                    $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        //$luugr = 0;
                        $luugr = $i;
                        foreach ($ardv as $chitietdv) {
                            $solieu = $model->where('madv', $chitietdv['madv'])->where('linhvuchoatdong', $group[$j]['val']);
                            $d = 1;
                            if (isset($solieu) && count($solieu) > 0) {
                                //dd($solieu);
                                $d++;
                                $i += $d;
                                $data[$i]['tt'] = '+';
                                $data[$i]['noidung'] = $chitietdv['tendv'];
                                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                                $data[$i]['bosung'] = $solieu->sum('nhucau') - $solieu->sum('nguonkp');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                                $data[$luugr]['bosung'] += $solieu->sum('nhucau') - $solieu->sum('nguonkp');
                            }
                        }
                    }
                }
                $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
                $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
                $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
                $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
                $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
                $data[$gddt]['bosung'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'] - $data[$giaoduc]['nguonthu'] - $data[$daotao]['nguonthu'];
            } else {
                for ($i = 0; $i < count($data); $i++) {
                    $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                    $data[$i]['nhucau'] = $solieu->sum('nhucau');
                    $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                    $data[$i]['hocphi'] = $solieu->sum('hocphi');
                    $data[$i]['vienphi'] = $solieu->sum('vienphi');
                    $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                    $data[$i]['bosung'] = $solieu->sum('nhucau') - $solieu->sum('nguonkp');
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];
                $data[0]['bosung'] = $data[1]['nhucau'] + $data[2]['nhucau'] - $data[1]['nguonkp'] - $data[2]['nguonkp'];

                $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
                $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
                $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
                $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
                $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
                $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
                $data[4]['bosung'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'] - ($model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp']);
            }
            Excel::create('mau4b_bosung', function ($excel) use ($model, $data, $m_dv, $inputs) {
                $excel->sheet('New sheet', function ($sheet) use ($model, $data, $m_dv, $inputs) {
                    $sheet->loadView('reports.thongtu67.mau4b_bosungexcel')
                        ->with('model', $model)
                        ->with('data', $data)
                        ->with('m_dv', $m_dv)
                        ->with('inputs', $inputs)
                        ->with('pageTitle', 'mau4b_bosung');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2a_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            if ($inputs['madv'] != null) {
                $m_donvi = dmdonvi::where('madv', $inputs['madv'])->first();
            } else {
                $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            }

            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');

            // $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
            if ($inputs['madv'] != null) {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan', 'TH')->get();
                // $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                //     ->where('madv', $inputs['madv'])->get();
                if (count($chekdv) > 0) {

                    $$_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('macqcq', $inputs['madv'])->get();
                    $model_donvi = dmdonvi::wherein('madv', array_column($_tonghop->toarray(), 'madv'))->get();
                    $m_bienche = chitieubienche::where('nam',  $inputs['nam'])->wherein('madv', array_column($_tonghop->toarray(), 'madv'))->get();
                } else {
                    $model_donvi = dmdonvi::where('madv', $madv)->get();
                    $m_bienche = chitieubienche::where('nam',  $inputs['nam'])->where('madv', $madv)->get();
                    $$_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                        ->where('trangthai', 'DAGUI')
                        ->where('madv', $inputs['madv'])->get();
                }
                // $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                //     ->where('trangthai', 'DAGUI')
                //     ->where('madv', $inputs['madv'])->get();
            } else {
                $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->where('madvbc', session('admin')->madvbc)->get();
                $model_donvi = dmdonvi::wherein('madv', array_column($_tonghop->toarray(), 'madv'))->get();
                $m_bienche = chitieubienche::where('nam', $inputs['nam'])->wherein('madv', array_column($_tonghop->toarray(), 'madv'))->get();
            }
            $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');
            //dd($_tonghop);
            $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
                ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
            $canbo = hosocanbo::wherein('macanbo', array_column($m_tonghop_ct->toarray(), 'macanbo'))->get();
            $a_pc_goc = array('heso');
            // $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
            $a_pc_th = dmphucap_donvi::where('madv',  session('admin')->madv)->where('phanloai', '<', '3')->get();
            // $a_pc_th=getColTongHop();

            $a_phucap = array();
            $col = 0;
            foreach ($a_pc_th as $ct) {
                if ($m_tonghop_ct->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->form;
                    if ($ct->mapc !== 'heso') {
                        $col++;
                    }
                }
            }
            // dd($a_phucap);
            // $a_pc = array_diff($a_pc_th, $a_pc_goc);
            foreach ($m_tonghop_ct as $ct) {
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                // foreach ($a_pc as $pc) {
                //     $pc_st = 'st_' . $pc;
                //     if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                //         $ct->pck += $ct->$pc;
                //         $ct->st_pck += round($ct->$pc * $m_thongtu->mucapdung);
                //     } else {
                //         $ct->st_pck += $ct->$pc_st;
                //     }
                // }
                // foreach ($a_pc_goc as $pc) {
                //     $pc_st = 'st_' . $pc;
                //     if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                //         $ct->$pc_st = round($ct->$pc * $m_thongtu->mucapdung);
                //     }
                //      else {
                //         //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                //         $ct->$pc = 0;
                //     }
                // }
                // foreach($a_phucap as $key=>$pc){
                //     $pc_st='st_'.$key;
                //     $ct->$pc_st=
                // }
                //Lấy % bảo hiểm
                $cb = $canbo->where('macanbo', $ct->macanbo)->first();
                $ct->sunghiep = $cb != '' ? $cb->sunghiep : '';
                // $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);

                $ct->ttbh_dv_hs = round($ct->ttbh_dv / $ct->luongcoban, 2);
            }

            $m_tonghop_ct = $m_tonghop_ct->wherein('sunghiep', ['Công chức', 'Viên chức']);
            // dd($m_tonghop_ct);
            $ar_I = array();
            $ar_Igr = array();
            // if (isset($inputs['inchitiet'])) {
            //     $ar_Igr[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            //     $ar_Igr[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
            //     $ar_Igr[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
            //     $ar_Igr[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
            //     $ar_Igr[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
            //     $ar_Igr[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
            //     $ar_Igr[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
            //     $ar_Igr[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
            //     $ar_Igr[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
            //     $ar_Igr[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
            //     $ar_Igr[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
            //     $ar_Igr[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
            //     $ar_Igr[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
            //     $ar_Igr[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            // } else {
            //$m_tonghop_ct = $m_tonghop_ct->wherein('sunghiep', ['Công chức', 'Viên chức']);
            //dd($m_tonghop_ct);

            $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            $ar_I[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục');
            $ar_I[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo');
            $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
            $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
            $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
            $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
            $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
            $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
            $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
            $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
            $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[12] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => ' Quản lý NN');
            $ar_I[13] = array('val' => 'DDT', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể');
            // }
            $a_It = array();
            foreach ($a_phucap as $key => $val) {
                $pc_st = 'st_' . $key;
                $arr_pc = array(
                    $key => 0, $pc_st => 0
                );

                $a_It = array_merge($a_It, $arr_pc);
            }

            $arr = array(
                'tongpc' => 0, 'chenhlech' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0, 'tbh_dv', 'congchuc' => 0, 'vienchuc' => 0, 'soluongcongchuc' => 0, 'soluongvienchuc' => 0
            );
            $a_It = array_merge($a_It, $arr);
            $a_phucap = array_diff($a_phucap, ['pcdbqh', 'pcvk']); //bỏ 2 loại phụ cấp này ra do tính ở III và IV

            if (isset($inputs['inchitiet'])) {

                for ($i = 0; $i < count($ar_I); $i++) {
                    if (isset($m_tonghop_ct)) {
                        $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                        $m_dvct = $model_donvi->wherein('madv', a_unique(array_column($chitiet->toarray(), 'madv')));
                    };
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        $m_dvct = a_unique(array_column($m_dvct->toarray(), 'madv'));
                        foreach ($m_dvct as $dv) {
                            $thongtin = $chitiet->where('madv', $dv);
                            $ar_I[$i]['tt'] = '+';
                            $ar_I[$i]['noidung'] = $model_donvi->where('madv', $dv)->first()->tendv;
                            $ar_I[$i]['soluongduocgiao'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                            $ar_I[$i]['soluongcongchuc'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongcongchuc');
                            $ar_I[$i]['soluongvienchuc'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongvienchuc');
                            $ar_I[$i]['soluongbienche'] = count($thongtin);

                            $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];
                            $a_It['soluongcongchuc'] += $ar_I[$i]['soluongcongchuc'];
                            $a_It['soluongvienchuc'] += $ar_I[$i]['soluongvienchuc'];
                            $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];

                            $congchuc = $thongtin->where('sunghiep', 'Công chức');
                            $vienchuc = $thongtin->where('sunghiep', 'Viên chức');
                            $ar_I[$i]['congchuc'] = count($congchuc);
                            $ar_I[$i]['vienchuc'] = count($vienchuc);
                            $a_It['congchuc'] += $ar_I[$i]['congchuc'];
                            $a_It['vienchuc'] += $ar_I[$i]['vienchuc'];

                            $tongpc = 0;
                            $tonghs = 0;
                            foreach ($a_phucap as $key => $pc) {
                                $pc_st = 'st_' . $key;
                                $ar_I[$i][$key] = isset($inputs['inheso']) ? $thongtin->sum($key) : $thongtin->sum($pc_st);
                                $a_It[$key] += $ar_I[$i][$key];
                                $tongpc += isset($inputs['inheso']) ? $thongtin->sum($key) : $thongtin->sum($pc_st);
                                $tonghs += $thongtin->sum($key);
                            }

                            $ar_I[$i]['tongpc'] = $a_phucap != [] ? $tongpc - $ar_I[$i]['heso'] : 0;
                            $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                            if (isset($inputs['inheso'])) {
                                $ar_I[$i]['ttbh_dv'] = count($thongtin) > 0 ? $thongtin->sum('ttbh_dv_hs') : 0;
                            } else {
                                $ar_I[$i]['ttbh_dv'] =  count($thongtin) > 0 ? $thongtin->sum('ttbh_dv') : 0;
                            }
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                            if (isset($inputs['inheso'])) {
                                // $ar_I[$i]['chenhlech'] = round(($ar_I[$i]['heso'] + $ar_I[$i]['tongpc'] + $ar_I[$i]['ttbh_dv']) * 100000);
                                $ar_I[$i]['chenhlech'] = round(($thongtin->sum('luongtn') + $thongtin->sum('ttbh_dv')));
                            } else {
                                $ar_I[$i]['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
                                    + ($thongtin->sum('ttbh_dv') / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                            }
                            $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                            foreach ($a_phucap as $key => $pc) {
                                $pc_st = 'st_' . $key;
                                $ar_I[11][$key] = $ar_I[12][$key] + $ar_I[13][$key];
                                $ar_I[0][$key] = $ar_I[1][$key] + $ar_I[2][$key];
                            }

                            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
                            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];
                            $ar_I[11]['soluongduocgiao'] = $ar_I[12]['soluongduocgiao'] + $ar_I[13]['soluongduocgiao'];
                            $ar_I[11]['soluongbienche'] = $ar_I[12]['soluongbienche'] + $ar_I[13]['soluongbienche'];
                            $ar_I[11]['congchuc'] = $ar_I[12]['congchuc'] + $ar_I[13]['congchuc'];
                            $ar_I[11]['soluongcongchuc'] = $ar_I[12]['soluongcongchuc'] + $ar_I[13]['soluongcongchuc'];
                            $ar_I[11]['vienchuc'] = $ar_I[12]['vienchuc'] + $ar_I[13]['vienchuc'];
                            $ar_I[11]['soluongvienchuc'] = $ar_I[12]['soluongvienchuc'] + $ar_I[13]['soluongvienchuc'];

                            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
                            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] + $ar_I[2]['chenhlech'];
                            $ar_I[0]['soluongduocgiao'] = $ar_I[1]['soluongduocgiao'] + $ar_I[2]['soluongduocgiao'];
                            $ar_I[0]['soluongbienche'] = $ar_I[1]['soluongbienche'] + $ar_I[2]['soluongbienche'];
                            $ar_I[0]['congchuc'] = $ar_I[1]['congchuc'] + $ar_I[2]['congchuc'];
                            $ar_I[0]['soluongcongchuc'] = $ar_I[1]['soluongcongchuc'] + $ar_I[2]['soluongcongchuc'];
                            $ar_I[0]['vienchuc'] = $ar_I[1]['vienchuc'] + $ar_I[2]['vienchuc'];
                            $ar_I[0]['soluongvienchuc'] = $ar_I[1]['soluongvienchuc'] + $ar_I[2]['soluongvienchuc'];
                        }
                    } else {
                        $ar_I[$i]['soluongduocgiao'] = 0;
                        $ar_I[$i]['soluongcongchuc'] = 0;
                        $ar_I[$i]['soluongvienchuc'] = 0;
                        $ar_I[$i]['congchuc'] = 0;
                        $ar_I[$i]['vienchuc'] = 0;
                        $ar_I[$i]['soluongbienche'] = 0;
                        foreach ($a_phucap as $key => $pc) {
                            $ar_I[$i][$key] = 0;
                        }
                        $ar_I[$i]['tongpc'] = 0;
                        $ar_I[$i]['ttbh_dv'] = 0;
                        $ar_I[$i]['chenhlech'] = 0;
                    }
                }
            } else {
                for ($i = 0; $i < count($ar_I); $i++) {
                    $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);

                    $ar_I[$i]['soluongduocgiao'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                    $ar_I[$i]['soluongcongchuc'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongcongchuc');
                    $ar_I[$i]['soluongvienchuc'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongvienchuc');
                    $ar_I[$i]['soluongbienche'] = count($chitiet);

                    //$ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                    $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                    //$ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                    $a_It['soluongcongchuc'] += $ar_I[$i]['soluongcongchuc'];
                    $a_It['soluongvienchuc'] += $ar_I[$i]['soluongvienchuc'];
                    $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];




                    $tongpc = 0;
                    $tonghs = 0;
                    foreach ($a_phucap as $key => $pc) {
                        $pc_st = 'st_' . $key;
                        $ar_I[$i][$key] = isset($inputs['inheso']) ? $chitiet->sum($key) : $chitiet->sum($pc_st);
                        $a_It[$key] += $ar_I[$i][$key];
                        $tongpc += isset($inputs['inheso']) ? $chitiet->sum($key) : $chitiet->sum($pc_st);
                        $tonghs += $chitiet->sum($key);
                    }
                    $ar_I[$i]['tongpc'] = $a_phucap != [] ? $tongpc - $ar_I[$i]['heso'] : 0;
                    $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                    if (isset($inputs['inheso'])) {
                        $ar_I[$i]['ttbh_dv'] = count($chitiet) > 0 ? $chitiet->sum('ttbh_dv_hs') : 0;
                    } else {
                        $ar_I[$i]['ttbh_dv'] =  count($chitiet) > 0 ? $chitiet->sum('ttbh_dv') : 0;
                    }
                    $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                    if (isset($inputs['inheso'])) {
                        // $ar_I[$i]['chenhlech'] = round(($ar_I[$i]['heso'] + $ar_I[$i]['tongpc'] + $ar_I[$i]['ttbh_dv']) * 100000);
                        $ar_I[$i]['chenhlech'] = round(($chitiet->sum('luongtn') + $chitiet->sum('ttbh_dv')));
                    } else {
                        $ar_I[$i]['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
                            + ($chitiet->sum('ttbh_dv') / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                    }
                    $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                    //Tính số lượng cb công chức, viên chức
                    $congchuc = $chitiet->where('sunghiep', 'Công chức');
                    $vienchuc = $chitiet->where('sunghiep', 'Viên chức');
                    $ar_I[$i]['congchuc'] = count($congchuc);
                    $ar_I[$i]['vienchuc'] = count($vienchuc);
                    $a_It['congchuc'] += $ar_I[$i]['congchuc'];
                    $a_It['vienchuc'] += $ar_I[$i]['vienchuc'];
                }
                // dd($ar_I);
                // dd($a_It);
                foreach ($a_phucap as $key => $pc) {
                    $pc_st = 'st_' . $key;
                    $ar_I[11][$key] = $ar_I[12][$key] + $ar_I[13][$key];
                    $ar_I[0][$key] = $ar_I[1][$key] + $ar_I[2][$key];
                }

                $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
                $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];
                $ar_I[11]['soluongduocgiao'] = $ar_I[12]['soluongduocgiao'] + $ar_I[13]['soluongduocgiao'];
                $ar_I[11]['soluongbienche'] = $ar_I[12]['soluongbienche'] + $ar_I[13]['soluongbienche'];
                $ar_I[11]['congchuc'] = $ar_I[12]['congchuc'] + $ar_I[13]['congchuc'];
                $ar_I[11]['soluongcongchuc'] = $ar_I[12]['soluongcongchuc'] + $ar_I[13]['soluongcongchuc'];
                $ar_I[11]['vienchuc'] = $ar_I[12]['vienchuc'] + $ar_I[13]['vienchuc'];
                $ar_I[11]['soluongvienchuc'] = $ar_I[12]['soluongvienchuc'] + $ar_I[13]['soluongvienchuc'];


                $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
                $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
                $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] + $ar_I[2]['chenhlech'];
                $ar_I[0]['soluongduocgiao'] = $ar_I[1]['soluongduocgiao'] + $ar_I[2]['soluongduocgiao'];
                $ar_I[0]['soluongbienche'] = $ar_I[1]['soluongbienche'] + $ar_I[2]['soluongbienche'];
                $ar_I[0]['congchuc'] = $ar_I[1]['congchuc'] + $ar_I[2]['congchuc'];
                $ar_I[0]['soluongcongchuc'] = $ar_I[1]['soluongcongchuc'] + $ar_I[2]['soluongcongchuc'];
                $ar_I[0]['vienchuc'] = $ar_I[1]['vienchuc'] + $ar_I[2]['vienchuc'];
                $ar_I[0]['soluongvienchuc'] = $ar_I[1]['soluongvienchuc'] + $ar_I[2]['soluongvienchuc'];

                $ar_I[$i]['chenhlech'] = round(($chitiet->sum('luongtn') + $chitiet->sum('ttbh_dv')));
                // 17.10.2022
                // if (isset($inputs['inheso'])) {
                //     // $ar_I[$i]['chenhlech'] = round(($ar_I[$i]['heso'] + $ar_I[$i]['tongpc'] + $ar_I[$i]['ttbh_dv']) * 100000);
                //     $ar_I[$i]['chenhlech'] = round(($chitiet->sum('luongtn') + $chitiet->sum('ttbh_dv')));
                // } else {
                //     $ar_I[$i]['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
                //         + ($chitiet->sum('ttbh_dv') / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                // }
                $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                //Tính số lượng cb công chức, viên chức
                $congchuc = $chitiet->where('sunghiep', 'Công chức');
                $vienchuc = $chitiet->where('sunghiep', 'Viên chức');
                $ar_I[$i]['congchuc'] = count($congchuc);
                $ar_I[$i]['vienchuc'] = count($vienchuc);
                $a_It['congchuc'] += $ar_I[$i]['congchuc'];
                $a_It['vienchuc'] += $ar_I[$i]['vienchuc'];
            }
            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($m_tonghop_ct->soluongduocgiao) ? $m_tonghop_ct->soluongduocgiao : 0;
            $ar_II['soluongcongchuc'] = isset($m_tonghop_ct->soluongcongchuc) ? $m_tonghop_ct->soluongcongchuc : 0;
            $ar_II['soluongvienchuc'] = isset($m_tonghop_ct->soluongvienchuc) ? $m_tonghop_ct->soluongvienchuc : 0;
            $ar_II['soluongbienche'] = isset($m_bienche->soluongbienche) ? $m_bienche->soluongbienche : 0;

            $m_xaphuong = $m_tonghop_ct->where('maphanloai', 'KVXP');

            $tongpc = $tonghs = 0;
            foreach ($a_phucap as $key => $pc) {
                $pc_st = 'st_' . $key;
                $ar_II[$key] = isset($inputs['heso']) ? $m_xaphuong->sum($key) : $m_xaphuong->sum($pc_st);
                $tongpc += $ar_II[$key];
                $tonghs += $m_xaphuong->sum($key);
            }

            $ar_II['tongpc'] = $a_phucap != [] ? $tongpc - $ar_II['heso'] : 0;
            if (isset($inputs['inheso'])) {
                $ar_II['ttbh_dv'] = count($m_xaphuong) > 0 ? $m_xaphuong->sum('ttbh_dv_hs') : 0;

                // $ar_I[$i]['ttbh_dv'] = $chitiet == [] ? 0 : $chitiet->sum('ttbh_dv');

            } else {
                $ar_II['ttbh_dv'] = count($m_xaphuong) > 0 ? $m_xaphuong->sum('ttbh_dv') : 0;
            }

            // $ar_II['ttbh_dv'] = $m_xaphuong->sum('ttbh_dv');

            if (isset($inputs['inheso'])) {
                $ar_II['chenhlech'] = round(($tonghs + $ar_II['ttbh_dv']) * 100000);
            } else {
                $ar_II['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
                    + ($ar_II['ttbh_dv'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
            }
            // $ar_II['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
            //     + ($ar_II['ttbh_dv'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
            //dd($ar_II);

            //căn cứ vào cấp dự toán để xác định đơn vị cấp xã, huyện, tỉnh
            //chỉ có cột tổng cộng
            $ar_III = array();
            $ar_III[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Cấp tỉnh', 'tongso' => '0', 'chenhlech' => '0');
            $ar_III[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Cấp huyện', 'tongso' => '0', 'chenhlech' => '0');
            $ar_III[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Cấp xã', 'tongso' => '0', 'chenhlech' => '0');

            $ar_IV = array();
            $ar_IV[] = array('val' => 'T', 'tt' => '-', 'noidung' => 'Ủy viên cấp tỉnh', 'tongso' => '0', 'chenhlech' => '0');
            $ar_IV[] = array('val' => 'H', 'tt' => '-', 'noidung' => 'Ủy viên cấp huyện', 'tongso' => '0', 'chenhlech' => '0');
            $ar_IV[] = array('val' => 'X', 'tt' => '-', 'noidung' => 'Ủy viên cấp xã', 'tongso' => '0', 'chenhlech' => '0');

            $a_IIIt = array('tongso' => 0, 'chenhlech' => '0');
            $a_IVt = array('tongso' => 0, 'chenhlech' => '0');

            for ($i = 0; $i < count($ar_III); $i++) {
                if ($ar_III[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_III[$i]['tongso'] = count($m_xaphuong) > 0 ? $m_xaphuong->sum('pcdbqh') : 0;
                    $ar_III[$i]['chenhlech'] = round(($ar_III['tongso'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                }
                $a_IIIt['tongso'] += $ar_III[$i]['tongso'];
                $a_IIIt['chenhlech'] += $ar_III[$i]['chenhlech'];
            }

            for ($i = 0; $i < count($ar_IV); $i++) {
                if ($ar_IV[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_IV[$i]['tongso'] = count($m_xaphuong) > 0 ? $m_xaphuong->sum('pcvk') : 0;
                    $ar_IV[$i]['chenhlech'] = round(($ar_IV['tongso'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                }
                $a_IVt['tongso'] += $ar_IV[$i]['tongso'];
                $a_IVt['chenhlech'] += $ar_IV[$i]['chenhlech'];
            }
            unset($ar_I[14]);
            //dd($m_tonghop_ct);
            return view('reports.thongtu46.donvi.mau2a2_tt46_kh')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_It', $a_It)
                ->with('a_IIIt', $a_IIIt)
                ->with('a_IVt', $a_IVt)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('a_phucap', $a_phucap)
                ->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }
}
