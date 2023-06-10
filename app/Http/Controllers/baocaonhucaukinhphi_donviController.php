<?php

namespace App\Http\Controllers;

use App\chitieubienche;
use App\dmdonvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_chitiet;
use App\nguonkinhphi_bangluong;
use App\dmnguonkinhphi;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

use function Symfony\Component\VarDumper\Dumper\esc;

//use App\Http\Controllers\Controller;

class baocaonhucaukinhphi_donviController extends Controller
{
    function mau2a1(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');
            $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
            $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('madv', session('admin')->madv)->get();
            $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');

            $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
                ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
            //$m_tonghop_bl = tonghopluong_donvi_bangluong::wherein('mathdv', array_column($_tonghop->toarray(),'mathdv'))->get();
            //dd($m_tonghop_ct);
            $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
            $a_pc_th = getColTongHop();
            $a_pc = array_diff($a_pc_th, $a_pc_goc);

            foreach ($m_tonghop_ct as $ct) {
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                foreach ($a_pc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->pck += $ct->$pc;
                        $ct->st_pck += round($ct->$pc * $m_thongtu->muccu);
                    } else {
                        $ct->st_pck += $ct->$pc_st;
                    }
                }
                foreach ($a_pc_goc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->$pc_st = round($ct->$pc * $m_thongtu->muccu);
                    } else {
                        //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                        $ct->$pc = 0;
                    }
                }
                $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            }
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

            $a_It = array(
                'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
                'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
                'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'tongpc' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0,
            );

            $a_pc_goc = array_diff($a_pc_goc, ['pcdbqh', 'pcvk']); //bỏ 2 loại phụ cấp này ra do tính ở III và IV
            for ($i = 0; $i < count($ar_I); $i++) {
                $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);

                $ar_I[$i]['soluongduocgiao'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                $ar_I[$i]['soluongbienche'] = count($chitiet);

                //$ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                //$ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                $tongpc = 0;
                foreach ($a_pc_goc as $pc) {
                    $pc_st = 'st_' . $pc;
                    $ar_I[$i][$pc] = $chitiet->sum($pc_st);
                    $a_It[$pc] += $ar_I[$i][$pc];
                    $tongpc += $chitiet->sum($pc_st);
                }

                $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
                $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
            }
            //dd($ar_I);
            foreach ($a_pc_goc as $pc) {
                $ar_I[11][$pc] = $ar_I[12][$pc] + $ar_I[13][$pc];
                $ar_I[0][$pc] = $ar_I[1][$pc] + $ar_I[2][$pc];
            }

            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];

            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($m_tonghop_ct->soluongduocgiao) ? $m_tonghop_ct->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($m_bienche->soluongbienche) ? $m_bienche->soluongbienche : 0;

            $m_xaphuong = $m_tonghop_ct->where('maphanloai', 'KVXP');

            $tongpc = 0;
            foreach ($a_pc_goc as $pc) {
                $pc_st = 'st_' . $pc;
                $ar_II[$pc] = $m_xaphuong->sum($pc_st);
            }

            $ar_II['tongpc'] = $tongpc - $ar_II['heso'];
            $ar_II['ttbh_dv'] = $m_xaphuong->sum('ttbh_dv');

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

            $a_IIIt = array('tongso' => 0);
            $a_IVt = array('tongso' => 0);

            for ($i = 0; $i < count($ar_III); $i++) {
                if ($ar_III[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_III[$i]['tongso'] = $m_xaphuong->sum('pcdbqh');
                }
                $a_IIIt['tongso'] += $ar_III[$i]['tongso'];
            }

            for ($i = 0; $i < count($ar_IV); $i++) {
                if ($ar_IV[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_IV[$i]['tongso'] = $m_xaphuong->sum('pcvk');
                }
                $a_IVt['tongso'] += $ar_IV[$i]['tongso'];
            }
            //dd($m_tonghop_ct);
            return view('reports.thongtu46.donvi.mau2a1_tt46')
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
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2a2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');

            $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
            $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('madv', session('admin')->madv)->get();
            $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');

            $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
                ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();

            $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
            $a_pc_th = getColTongHop();
            $a_pc = array_diff($a_pc_th, $a_pc_goc);

            foreach ($m_tonghop_ct as $ct) {
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                foreach ($a_pc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->pck += $ct->$pc;
                        $ct->st_pck += round($ct->$pc * $m_thongtu->mucapdung);
                    } else {
                        $ct->st_pck += $ct->$pc_st;
                    }
                }
                foreach ($a_pc_goc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->$pc_st = round($ct->$pc * $m_thongtu->mucapdung);
                    } else {
                        //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                        $ct->$pc = 0;
                    }
                }
                $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            }
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

            $a_It = array(
                'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
                'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
                'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'tongpc' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0,
                'chenhlech' => 0
            );

            $a_pc_goc = array_diff($a_pc_goc, ['pcdbqh', 'pcvk']); //bỏ 2 loại phụ cấp này ra do tính ở III và IV
            for ($i = 0; $i < count($ar_I); $i++) {
                $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);

                $ar_I[$i]['soluongduocgiao'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                $ar_I[$i]['soluongbienche'] = count($chitiet);

                //$ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                //$ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                $tongpc = 0;
                $tonghs = 0;
                foreach ($a_pc_goc as $pc) {
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

                $ar_I[$i]['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
                    + ($ar_I[$i]['ttbh_dv'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];
            }
            //dd($ar_I);
            foreach ($a_pc_goc as $pc) {
                $ar_I[11][$pc] = $ar_I[12][$pc] + $ar_I[13][$pc];
                $ar_I[0][$pc] = $ar_I[1][$pc] + $ar_I[2][$pc];
            }

            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];
            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] + $ar_I[2]['chenhlech'];

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($m_tonghop_ct->soluongduocgiao) ? $m_tonghop_ct->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($m_bienche->soluongbienche) ? $m_bienche->soluongbienche : 0;

            $m_xaphuong = $m_tonghop_ct->where('maphanloai', 'KVXP');

            $tongpc = $tonghs = 0;
            foreach ($a_pc_goc as $pc) {
                $pc_st = 'st_' . $pc;
                $ar_II[$pc] = $m_xaphuong->sum($pc_st);
                $tongpc += $ar_II[$pc];
                $tonghs += $m_xaphuong->sum($pc);
            }

            $ar_II['tongpc'] = $tongpc - $ar_II['heso'];
            $ar_II['ttbh_dv'] = $m_xaphuong->sum('ttbh_dv');
            $ar_II['chenhlech'] = round($tonghs * $m_thongtu->chenhlech
                + ($ar_II['ttbh_dv'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
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
                    $ar_III[$i]['tongso'] = $m_xaphuong->sum('pcdbqh');
                    $ar_III[$i]['chenhlech'] = round(($ar_III['tongso'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                }
                $a_IIIt['tongso'] += $ar_III[$i]['tongso'];
                $a_IIIt['chenhlech'] += $ar_III[$i]['chenhlech'];
            }

            for ($i = 0; $i < count($ar_IV); $i++) {
                if ($ar_IV[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_IV[$i]['tongso'] = $m_xaphuong->sum('pcvk');
                    $ar_IV[$i]['chenhlech'] = round(($ar_IV['tongso'] / $m_thongtu->mucapdung) * $m_thongtu->chenhlech);
                }
                $a_IVt['tongso'] += $ar_IV[$i]['tongso'];
                $a_IVt['chenhlech'] += $ar_IV[$i]['chenhlech'];
            }
            //dd($m_tonghop_ct);
            return view('reports.thongtu46.donvi.mau2a2_tt46')
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
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau4a(Request $request)
    {
        if (Session::has('admin')) {
            ///if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');

            $model = nguonkinhphi::where('madv', session('admin')->madv)->where('sohieu', $inputs['sohieu'])->get();
            // dd($model);
            //$model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $a_A = array();
            $a_A[0] = array('tt' => '1', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) thực hiện 2018 so dự toán Thủ tướng Chính phủ giao năm 2018', 'sotien' => '0');
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

            //Tính toán
            $a_A[0]['sotien'] = $model->sum('thuchien1');
            $a_A[1]['sotien'] = $model->sum('dutoan');
            $a_A[2]['sotien'] = $model->sum('dutoan1');

            $a_A[3]['sotien'] = $model->sum('tietkiem2');
            $a_A[4]['sotien'] = $model->sum('tietkiem1');
            $a_A[5]['sotien'] = $model->sum('tietkiem');

            //$a_BI[1]['sotien'] = $model->luongphucap;
            $model_tudb = $model->wherein('phanloainguon', array('CHITXDT', 'CTX'));
            $a_A[8]['sotien'] = $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien'] +  $a_A[10]['sotien'];
            $a_A[12]['sotien'] = $model->sum('hocphi') - $a_A[8]['sotien'];
            $a_A[13]['sotien'] = $model->sum('vienphi') - $a_A[9]['sotien'];
            $a_A[14]['sotien'] = $model->sum('nguonthu') - $a_A[10]['sotien'];
            $a_A[11]['sotien'] =  $a_A[12]['sotien'] +  $a_A[13]['sotien'] +  $a_A[14]['sotien'];
            $a_A[6]['sotien'] =  $a_A[7]['sotien'] +  $a_A[11]['sotien'];
            $a_A[20]['sotien'] = $model->sum('bosung');
            $a_A[21]['sotien'] = $model->sum('caicach');

            //phần II
            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BII[1] = array('tt' => '', 'noidung' => 'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)', 'sotien' => '0');
            $a_BII[2] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BII[3] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BII[4] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 44/2019/NĐ-CP', 'sotien' => '0');
            $a_BII[5] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BII[6] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BII[7] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017', 'sotien' => '0');
            // if (session('admin')->maphanloai == 'KVXP') {
            //     $a_BII[0]['sotien'] = 0;
            //     $a_BII[2]['sotien'] = $model->sum('luongphucap') + $model->sum('baohiem');
            // } else {
            //     $a_BII[0]['sotien'] = $model->sum('luongphucap') + $model->sum('baohiem');
            //     $a_BII[2]['sotien'] = 0;
            // }
            $model_xp = $model->where('maphanloai', 'KVXP');
            $a_BII[2]['sotien'] = $model_xp->sum('luongphucap') + $model_xp->sum('baohiem');
            $a_BII[0]['sotien'] = $model->sum('luongphucap') + $model->sum('baohiem') - $model_xp->sum('luongphucap') - $model_xp->sum('baohiem');
            $a_BII[1]['sotien'] = $model_tudb->sum('luongphucap') + $model_tudb->sum('baohiem');

            $a_BII[3]['sotien'] = $model->sum('daibieuhdnd');
            $a_BII[4]['sotien'] = $model->sum('nghihuu');
            $a_BII[5]['sotien'] = $model->sum('canbokct');
            $a_BII[6]['sotien'] = $model->sum('uyvien');
            $a_BII[7]['sotien'] = $model->sum('boiduong');
            //phần III
            $a_BIII = array();
            $a_BIII[0] = array('tt' => '1', 'noidung' => 'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)', 'sotien' => '0');
            $a_BIII[1] = array('tt' => '2', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2019 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)', 'sotien' => '0');
            $a_BIII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2019 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');
            $a_BIII[3] = array('tt' => '4', 'noidung' => 'Kinh phí giảm do điều chỉnh danh sách huyện nghèo theo Quyết định số 275/QĐ-TTg ngày 07/3/2018 của Thủ tướng Chính phủ (quy định tại điểm b khoản 2 Công văn số 1044/BNV-TL ngày 11/3/2019 của Bộ Nội vụ)', 'sotien' => '0');
            $a_BIII[4] = array('tt' => 'a', 'noidung' => 'Kinh phí thu hút', 'sotien' => '0');
            $a_BIII[5] = array('tt' => 'b', 'noidung' => 'Chênh lệch kinh phí ưu đãi', 'sotien' => '0');
            $a_BIII[6] = array('tt' => '5', 'noidung' => 'Kinh phí giảm do điều chỉnh số lượng cán bộ, công chức cấp xã; mức khoán phụ cấp đối với người hoạt động không chuyên trách ở cấp xã theo Nghị định số 34/2019/NĐ-CP của Chính phủ (7)', 'sotien' => '0');

            $a_BIII[0]['sotien'] = $model->sum('thunhapthap');
            $a_BIII[1]['sotien'] = $model->sum('diaban');
            $a_BIII[2]['sotien'] = $model->sum('tinhgiam');
            $a_BIII[4]['sotien'] = $model->sum('kpthuhut');
            $a_BIII[5]['sotien'] = $model->sum('kpuudai');
            $a_BIII[3]['sotien'] = $a_BIII[4] + $a_BIII[5];
            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[3]['sotien']
                    + $a_A[4]['sotien'] + $a_A[5]['sotien'] + $a_A[6]['sotien'] + $a_A[20]['sotien']
                    + $a_A[21]['sotien']),
                'BII' => (array_sum(array_column($a_BII, 'sotien')) - $a_BII[1]['sotien']),
                'BII1' => $model->sum('tongnhucau1'),
                'BII2' => $model->sum('tongnhucau2'),
                'BIII' => (array_sum(array_column($a_BIII, 'sotien')))
            );

            return view('reports.thongtu46.donvi.mau4a_tt46')
                ->with('model', $model)
                ->with('a_A', $a_A)
                ->with('a_BII', $a_BII)
                ->with('a_BIII', $a_BIII)
                ->with('a_TC', $a_TC)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu',$inputs['sohieu'])->first();
            $model = nguonkinhphi::where('madv', session('admin')->madv)
                ->where('sohieu', $inputs['sohieu'])->get();
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $data = array();

            $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);

            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT
            for ($i = 0; $i < count($data); $i++) {
                $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                $data[$i]['nguonthu'] = $solieu->sum('tietkiem1');

                // $data[$i]['khac'] = $solieu->sum('thuchien1') + $solieu->sum('dutoan') + $solieu->sum('dutoan1')
                //     + $solieu->sum('bosung') + $solieu->sum('caicach');
                $data[$i]['khac'] = $solieu->sum('nguonthu');
            }

            $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];
            $data[0]['khac'] = $data[1]['khac'] + $data[2]['khac'];

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
            $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
            // $data[4]['tietkiem'] = $model->sum('tietkiem') + $model->sum('tietkiem1') + $model->sum('tietkiem2')
            //     - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
            $data[4]['tietkiem'] = $model->sum('tietkiem')
                - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
            $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
            $data[4]['khac'] = 0;

            $inputs = array();
            $inputs['donvitinh'] = 1;
            return view('reports.thongtu46.donvi.mau4b_tt46')
                ->with('model', $model)
                ->with('data', $data)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau2a1_kh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');
            $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
            $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('madv', session('admin')->madv)->get();
            $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');

            $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
                ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
            //$m_tonghop_bl = tonghopluong_donvi_bangluong::wherein('mathdv', array_column($_tonghop->toarray(),'mathdv'))->get();
            //dd($m_tonghop_ct);
            $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
            $a_pc_th = getColTongHop();
            $a_pc = array_diff($a_pc_th, $a_pc_goc);

            foreach ($m_tonghop_ct as $ct) {
                $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
                foreach ($a_pc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->pck += $ct->$pc;
                        $ct->st_pck += round($ct->$pc * $m_thongtu->muccu);
                    } else {
                        $ct->st_pck += $ct->$pc_st;
                    }
                }
                foreach ($a_pc_goc as $pc) {
                    $pc_st = 'st_' . $pc;
                    if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                        $ct->$pc_st = round($ct->$pc * $m_thongtu->muccu);
                    } else {
                        //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                        $ct->$pc = 0;
                    }
                }
                $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            }
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

            $a_It = array(
                'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
                'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
                'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'tongpc' => 0,
                'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0,
            );

            $a_pc_goc = array_diff($a_pc_goc, ['pcdbqh', 'pcvk']); //bỏ 2 loại phụ cấp này ra do tính ở III và IV
            for ($i = 0; $i < count($ar_I); $i++) {
                $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);

                $ar_I[$i]['soluongduocgiao'] = $m_bienche->where('linhvuchoatdong', $ar_I[$i]['val'])->sum('soluongduocgiao');
                $ar_I[$i]['soluongbienche'] = count($chitiet);

                //$ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                //$ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                $tongpc = 0;
                foreach ($a_pc_goc as $pc) {
                    $pc_st = 'st_' . $pc;
                    $ar_I[$i][$pc] = $chitiet->sum($pc_st);
                    $a_It[$pc] += $ar_I[$i][$pc];
                    $tongpc += $chitiet->sum($pc_st);
                }

                $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
                $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
                $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
            }
            //dd($ar_I);
            foreach ($a_pc_goc as $pc) {
                $ar_I[11][$pc] = $ar_I[12][$pc] + $ar_I[13][$pc];
                $ar_I[0][$pc] = $ar_I[1][$pc] + $ar_I[2][$pc];
            }

            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];

            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($m_tonghop_ct->soluongduocgiao) ? $m_tonghop_ct->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($m_bienche->soluongbienche) ? $m_bienche->soluongbienche : 0;

            $m_xaphuong = $m_tonghop_ct->where('maphanloai', 'KVXP');

            $tongpc = 0;
            foreach ($a_pc_goc as $pc) {
                $pc_st = 'st_' . $pc;
                $ar_II[$pc] = $m_xaphuong->sum($pc_st);
            }

            $ar_II['tongpc'] = $tongpc - $ar_II['heso'];
            $ar_II['ttbh_dv'] = $m_xaphuong->sum('ttbh_dv');

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

            $a_IIIt = array('tongso' => 0);
            $a_IVt = array('tongso' => 0);

            for ($i = 0; $i < count($ar_III); $i++) {
                if ($ar_III[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_III[$i]['tongso'] = $m_xaphuong->sum('pcdbqh');
                }
                $a_IIIt['tongso'] += $ar_III[$i]['tongso'];
            }

            for ($i = 0; $i < count($ar_IV); $i++) {
                if ($ar_IV[$i]['val'] == $m_donvi->caphanhchinh) {
                    $ar_IV[$i]['tongso'] = $m_xaphuong->sum('pcvk');
                }
                $a_IVt['tongso'] += $ar_IV[$i]['tongso'];
            }
            //dd($m_tonghop_ct);
            return view('reports.thongtu46.donvi.mau2a1_tt46_kh')
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
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_kh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($m_thongtu->ngayapdung);
            $inputs['nam'] = date_format($ngayapdung, 'Y');
            $inputs['thang'] = date_format($ngayapdung, 'm');

            $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
            $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('madv', session('admin')->madv)->get();
            $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');

            $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
                ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
            $canbo = hosocanbo::wherein('macanbo', array_column($m_tonghop_ct->toarray(), 'macanbo'))->get();
            $a_pc_th = dmphucap_donvi::where('madv',  session('admin')->madv)->where('phanloai', '<', '3')->get();
            // $a_pc_th=getColTongHop();
            //dd($m_tonghop_ct);    
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
            //dd($m_tonghop_ct->toarray());
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
            //dd($m_tonghop_ct);
            //$m_tonghop_ct = $m_tonghop_ct->wherein('sunghiep', ['Công chức', 'Viên chức']);
            //dd($m_tonghop_ct->toarray());
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
            for ($i = 0; $i < count($ar_I); $i++) {
                $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                // if( $ar_I[$i]['val'] == 'QLNN'){
                //     dd($chitiet);
                // }
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
                    $ar_I[$i][$key] = isset($inputs['innoidung']) ? $chitiet->sum($key) : $chitiet->sum($pc_st);
                    $a_It[$key] += $ar_I[$i][$key];
                    $tongpc += isset($inputs['innoidung']) ? $chitiet->sum($key) : $chitiet->sum($pc_st);
                    $tonghs += $chitiet->sum($key);
                }
                $ar_I[$i]['tongpc'] = $a_phucap != [] ? $tongpc - $ar_I[$i]['heso'] : 0;
                $a_It['tongpc'] += $ar_I[$i]['tongpc'];

                if (isset($inputs['innoidung'])) {
                    $ar_I[$i]['ttbh_dv'] = count($chitiet) > 0 ? $chitiet->sum('ttbh_dv_hs') : 0;
                } else {
                    $ar_I[$i]['ttbh_dv'] =  count($chitiet) > 0 ? $chitiet->sum('ttbh_dv') : 0;
                }
                $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                $ar_I[$i]['chenhlech'] = round(($chitiet->sum('luongtn') + $chitiet->sum('ttbh_dv')));
                //17.10.2022
                // if (isset($inputs['innoidung'])) {
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

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($m_tonghop_ct->soluongduocgiao) ? $m_tonghop_ct->soluongduocgiao : 0;
            $ar_II['soluongcongchuc'] = isset($m_tonghop_ct->soluongcongchuc) ? $m_tonghop_ct->soluongcongchuc : 0;
            $ar_II['soluongvienchuc'] = isset($m_tonghop_ct->soluongvienchuc) ? $m_tonghop_ct->soluongvienchuc : 0;
            $ar_II['soluongbienche'] = isset($m_bienche->soluongbienche) ? $m_bienche->soluongbienche : 0;

            $m_xaphuong = $m_tonghop_ct->where('maphanloai', 'KVXP');

            $tongpc = $tonghs = 0;
            foreach ($a_phucap as $key => $pc) {
                $pc_st = 'st_' . $key;
                $ar_II[$key] = isset($inputs['innoidung']) ? $m_xaphuong->sum($key) : $m_xaphuong->sum($pc_st);
                $tongpc += $ar_II[$key];
                $tonghs += $m_xaphuong->sum($key);
            }

            $ar_II['tongpc'] = $a_phucap != [] ? $tongpc - $ar_II['heso'] : 0;
            if (isset($inputs['innoidung'])) {
                $ar_II['ttbh_dv'] = count($m_xaphuong) > 0 ? $m_xaphuong->sum('ttbh_dv_hs') : 0;

                // $ar_I[$i]['ttbh_dv'] = $chitiet == [] ? 0 : $chitiet->sum('ttbh_dv');

            } else {
                $ar_II['ttbh_dv'] = count($m_xaphuong) > 0 ? $m_xaphuong->sum('ttbh_dv') : 0;
            }

            // $ar_II['ttbh_dv'] = $m_xaphuong->sum('ttbh_dv');

            if (isset($inputs['innoidung'])) {
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


    function mau2b_donvi(Request $request)
    {
        $inputs = $request->all();
        $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
        $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
        $ngayapdung = new Carbon($m_thongtu->ngayapdung);
        $inputs['nam'] = date_format($ngayapdung, 'Y');
        $inputs['thang'] = date_format($ngayapdung, 'm');


        $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
            ->where('madv', session('admin')->madv)->get();
        $ar_I = array();
        $ar_I[] = array('tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch');
        $ar_I[] = array('tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
        $ar_I[] = array('tt' => '3', 'noidung' => 'Các chức danh còn lại');
        $j = 1;
        $a_It = array(
            'tongsonguoi' => 0, 'quy1' => 0, 'quy2' => 0,
            'quy1thangtangthem' => 0, 'bhyttangthem' => 0, 'tong' => 0,

        );
        for ($i = 0; $i < count($ar_I); $i++) {
            $tongsonguoi = 'tongsonguoi' . $j;
            $quy1 = 'quy1_' . $j;
            $quy2 = 'quy2_' . $j;
            $ar_I[$i]['tongsonguoi' . $j] = $_tonghop->sum($tongsonguoi);
            $ar_I[$i]['quy1_' . $j] = $_tonghop->sum($quy1);
            $ar_I[$i]['quy2_' . $j] = $_tonghop->sum($quy2);
            $ar_I[$i]['quy1thangtangthem'] = $ar_I[$i]['quy2_' . $j] - $ar_I[$i]['quy1_' . $j];
            if ($ar_I[$i]['quy1thangtangthem'] < 0) {
                $ar_I[$i]['quy1thangtangthem'] = 0;
            } else {
                $ar_I[$i]['quy1thangtangthem'] = $ar_I[$i]['quy1thangtangthem'];
            }
            $ar_I[$i]['bhyttangthem'] = $ar_I[$i]['tongsonguoi' . $j] * 100000 * (4.5 / 100);
            $ar_I[$i]['tong'] = ($ar_I[$i]['quy1thangtangthem'] + $ar_I[$i]['bhyttangthem']) * 6;



            //Tính tổng
            $a_It['tongsonguoi'] +=  $ar_I[$i]['tongsonguoi' . $j];
            $a_It['quy1'] +=  $ar_I[$i]['quy1_' . $j];
            $a_It['quy2'] +=  $ar_I[$i]['quy2_' . $j];
            $a_It['quy1thangtangthem'] +=  $ar_I[$i]['quy1thangtangthem'];
            $a_It['bhyttangthem'] +=  $ar_I[$i]['bhyttangthem'];
            $a_It['tong'] +=  $ar_I[$i]['tong'];
            $j++;
        }
        return view('reports.thongtu46.donvi.mau2b_tt46')
            ->with('furl', '/tong_hop_bao_cao/')
            ->with('ar_I', $ar_I)
            ->with('a_It', $a_It)
            ->with('m_dv', $m_donvi)
            ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
    }

    function mau2đ_donvi(Request $request)
    {
        $inputs = $request->all();
        $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();

        $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
        $ngayapdung = new Carbon($m_thongtu->ngayapdung);
        $inputs['nam'] = date_format($ngayapdung, 'Y');
        $inputs['thang'] = date_format($ngayapdung, 'm');
        // $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
        $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
            ->where('madv', session('admin')->madv)->get();

        $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
            ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
        // dd($m_tonghop_ct);
        $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');

        foreach ($m_tonghop_ct as $ct) {
            $ct->phanloainguon = $m_donvi->phanloainguon;
            $ct->phanloaidonvi = $m_donvi->maphanloai;
        }
        $pldv = dmphanloaidonvi::all();
        $a_qlnn = ['MAMNON', 'TIEUHOC', 'THCS', 'THvaTHCS', 'PTDTNT'];
        $a_sunghiep = array_column($pldv->wherenotin('maphanloai', $a_qlnn)->toarray(), 'maphanloai');
        $m_qlnn = $m_tonghop_ct->wherein('phanloaidonvi', $a_qlnn);
        $m_sunghiep = $m_tonghop_ct->wherein('phanloaidonvi', $a_sunghiep);


        // Tính cho khối quản lý nhà nước
        $dm_qlnn = $pldv->wherein('maphanloai', $a_qlnn);
        foreach ($dm_qlnn as $dm) {
            $chitiet = $m_qlnn->where('phanloaidonvi', $dm->maphanloai);
            $dm->soluongbienche = count($chitiet);
            if (count($chitiet) != 0) {
                $dm->tongsonguoi2015 = $_tonghop->sum('tongsonguoi2015');
                $dm->tongsonguoi2017 = $_tonghop->sum('tongsonguoi2017');
                $dm->quyluong2017 = $_tonghop->sum('quyluong2017');
                $dm->quyluong =  $chitiet->sum('luongtn');
            } else {
                $dm->tongsonguoi2015 = 0;
                $dm->tongsonguoi2017 = 0;
                $dm->quyluong2017 = 0;
                $dm->quyluong =  0;
            }

            $dm->quy_tk_1thang =  $dm->quyluong - $dm->quyluong2017;
            $dm->dinhmuc_1thang_2017 = $dm->tongsonguoi2017 == 0 ? '0' : $dm->quyluong2017 / $dm->tongsonguoi2017;
            $dm->dinhmuc_1thang_2019 = $dm->soluongbienche == 0 ? '0' : $dm->quyluong / $dm->soluongbienche;
            $dm->kp_tk_1th = ($dm->dinhmuc_1thang_2019 - $dm->dinhmuc_1thang_2017) > 0 ? $dm->dinhmuc_1thang_2019 - $dm->dinhmuc_1thang_2017 : 0;
            $dm->tong = $dm->dinhmuc_1thang_2019 == 0 ? '0' : $dm->dinhmuc_1thang_2019 * 6;
        }
        // dd($model);
        // Khối sự nghiệp công lập
        $ar_I[0] = array('val' => 'CHITXDT', 'tt' => '', 'noidung' => 'Đơn vị đảnm bảo chi thường xuyên và chi đầu tư');
        $ar_I[1] = array('val' => 'CTX', 'tt' => '', 'noidung' => 'Đơn vị đảnm bảo chi thường xuyên');
        $ar_I[2] = array('val' => 'CTXMP', 'tt' => '', 'noidung' => 'Đơn vị đảnm bảo một phần chi thường xuyên');
        $ar_I[3] = array('val' => 'NGANSACH', 'tt' => '', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');

        $a_It = array(
            'tongsonguoi2015' => 0, 'tongsonguoi2017' => 0, 'quyluong2017' => 0, 'quyluong' => 0, 'soluongbienche' => 0,
            'quy_tk_1thang' => 0, 'dinhmuc_1thang_2017' => 0, 'dinhmuc_1thang_2019' => 0, 'kp_tk_1th' => 0, 'tong' => 0
        );

        for ($i = 0; $i < count($ar_I); $i++) {
            $chitiet = $m_sunghiep->where('phanloainguon', $ar_I[$i]['val']);
            $ar_I[$i]['soluongbienche'] = count($chitiet);
            if (count($chitiet) != 0) {
                $ar_I[$i]['tongsonguoi2015'] = $_tonghop->sum('tongsonguoi2015');
                $ar_I[$i]['tongsonguoi2017'] = $_tonghop->sum('tongsonguoi2017');
                $ar_I[$i]['quyluong2017'] = $_tonghop->sum('quyluong');
                $ar_I[$i]['quyluong'] = $chitiet->sum('luongtn');
            } else {
                $ar_I[$i]['tongsonguoi2015'] = 0;
                $ar_I[$i]['tongsonguoi2017'] = 0;
                $ar_I[$i]['quyluong2017'] = 0;
                $ar_I[$i]['quyluong'] = 0;
            }
            $ar_I[$i]['quy_tk_1thang'] = $ar_I[$i]['quyluong'] - $ar_I[$i]['quyluong2017'] > 0 ? $ar_I[$i]['quyluong'] - $ar_I[$i]['quyluong2017'] : 0;
            $ar_I[$i]['dinhmuc_1thang_2017'] =  $ar_I[$i]['tongsonguoi2017'] == 0 ? '0' : $ar_I[$i]['quyluong2017'] / $ar_I[$i]['tongsonguoi2017'];
            $ar_I[$i]['dinhmuc_1thang_2019'] =  $ar_I[$i]['soluongbienche'] == 0 ? '0' : $ar_I[$i]['quyluong'] / $ar_I[$i]['soluongbienche'];
            $ar_I[$i]['kp_tk_1th'] =  $ar_I[$i]['dinhmuc_1thang_2019'] - $ar_I[$i]['dinhmuc_1thang_2017'] > 0 ? $ar_I[$i]['dinhmuc_1thang_2019'] - $ar_I[$i]['dinhmuc_1thang_2017'] : 0;
            $ar_I[$i]['tong'] = $ar_I[$i]['dinhmuc_1thang_2019'] * 6;

            $a_It['tongsonguoi2015'] += $ar_I[$i]['tongsonguoi2015'];
            $a_It['tongsonguoi2017'] += $ar_I[$i]['tongsonguoi2017'];
            $a_It['quyluong2017'] += $ar_I[$i]['quyluong2017'];
            $a_It['quyluong'] += $ar_I[$i]['quyluong'];
            $a_It['quy_tk_1thang'] += $ar_I[$i]['quy_tk_1thang'];
            $a_It['dinhmuc_1thang_2017'] += $ar_I[$i]['dinhmuc_1thang_2017'];
            $a_It['dinhmuc_1thang_2019'] += $ar_I[$i]['dinhmuc_1thang_2019'];
            $a_It['kp_tk_1th'] += $ar_I[$i]['kp_tk_1th'];
            $a_It['tong'] += $ar_I[$i]['tong'];
            $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
        }

        return view('reports.thongtu46.donvi.mau2đ_tt46')
            ->with('furl', '/tong_hop_bao_cao/')
            ->with('m_dv', $m_donvi)
            ->with('ar_I', $ar_I)
            ->with('a_It', $a_It)
            ->with('dm_qlnn', $dm_qlnn)
            ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
    }

    function mau2e_donvi(Request $request)
    {
        $inputs = $request->all();
        $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
        $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
        $ngayapdung = new Carbon($m_thongtu->ngayapdung);
        $inputs['nam'] = date_format($ngayapdung, 'Y');
        $inputs['thang'] = date_format($ngayapdung, 'm');
        // $m_bienche = chitieubienche::where('nam', $inputs['nam'])->where('madv', session('admin')->madv)->get();
        $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
            ->where('madv', session('admin')->madv)->get();
        $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');
        $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
            ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->get();
        $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
        $a_pc_th = getColTongHop();
        $a_pc = array_diff($a_pc_th, $a_pc_goc);
        foreach ($m_tonghop_ct as $ct) {
            $ct->phanloainguon = $m_donvi->phanloainguon;
            $ct->phanloaidonvi = $m_donvi->maphanloai;
        }
        $pldv = dmphanloaidonvi::all();
        $a_qlnn = ['MAMNON', 'TIEUHOC', 'THCS', 'THvaTHCS', 'PTDTNT'];
        $a_sunghiep = array_column($pldv->wherenotin('maphanloai', $a_qlnn)->toarray(), 'maphanloai');
        $m_sunghiep = $m_tonghop_ct->wherein('phanloaidonvi', $a_sunghiep);
        // dd($model);
        $ar_I[0] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảnm bảo chi thường xuyên và chi đầu tư');
        $ar_I[1] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảnm bảo chi thường xuyên');
        $ar_I[2] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảnm bảo một phần chi thường xuyên');
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
        // dd($ar_I);
        return view('reports.thongtu46.donvi.mau2e_tt46')
            ->with('furl', '/tong_hop_bao_cao/')
            ->with('m_dv', $m_donvi)
            ->with('ar_I', $ar_I)
            ->with('a_It', $a_It)
            ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
    }
    function mau2g_donvi(Request $request)
    {
        $inputs = $request->all();
        $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
        $m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
        $ngayapdung = new Carbon($m_thongtu->ngayapdung);
        $inputs['nam'] = date_format($ngayapdung, 'Y');
        $inputs['thang'] = date_format($ngayapdung, 'm');

        $_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
            ->where('madv', session('admin')->madv)->get();
        $a_linhvuc = array_column($_tonghop->toarray(), 'linhvuchoatdong', 'masodv');
        $m_ct = dmphanloaict::where('tenct', 'like', 'Hợp đồng%')->get();
        $a_ct = array_column($m_ct->toarray(), 'mact');

        $m_tonghop_ct = nguonkinhphi_bangluong::wherein('masodv', array_column($_tonghop->toarray(), 'masodv'))
            ->where('nam', $inputs['nam'])->where('thang', $inputs['thang'])->wherein('mact', $a_ct)->get();
        $a_pc_goc = array('heso', 'vuotkhung', 'pckv', 'pccv', 'pcudn', 'pcth', 'pctnn', 'pccovu', 'pcdang', 'pcthni', 'pcdbqh', 'pcvk', 'pck');
        $a_pc_th = getColTongHop();
        $a_pc = array_diff($a_pc_th, $a_pc_goc);

        foreach ($m_tonghop_ct as $ct) {
            $ct->linhvuchoatdong = $a_linhvuc[$ct->masodv];
            foreach ($a_pc as $pc) {
                $pc_st = 'st_' . $pc;
                if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                    $ct->pck += $ct->$pc;
                    $ct->st_pck += round($ct->$pc * $m_thongtu->mucapdung);
                } else {
                    $ct->st_pck += $ct->$pc_st;
                }
            }
            foreach ($a_pc_goc as $pc) {
                $pc_st = 'st_' . $pc;
                if ($ct->$pc < $ct->$pc_st) { //hệ số < số tiền => theo dõi khác số tiền
                    $ct->$pc_st = round($ct->$pc * $m_thongtu->mucapdung);
                } else {
                    //gán hệ số phụ cấp theo dõi theo số tiên = 0 để sau tính tổng hệ số phụ cấp theo hàm sum()
                    $ct->$pc = 0;
                }
            }
            //Lấy % bảo hiểm
            $ct->ttbh_dv = round(($ct->st_heso + $ct->st_vuotkhung + $ct->st_pccv + $ct->st_pctnn + $ct->st_hesopc) * 23.5 / 100);
            $ct->ttbh_dv_hs = round($ct->ttbh_dv / $ct->luongcoban, 2);
        }
        $ar_I[0] = array('val' => 'QLNN', 'tt' => '1', 'noidung' => ' Quản lý NN');
        $ar_I[1] = array('val' => 'GD;DT', 'tt' => '2', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
        $ar_I[2] = array('val' => 'GD', 'tt' => '', 'noidung' => 'Giáo dục');
        $ar_I[3] = array('val' => 'DT', 'tt' => '', 'noidung' => 'Đào tạo');
        $ar_I[4] = array('val' => 'YTE', 'tt' => '3', 'noidung' => 'Sự nghiệp y tế');
        $ar_I[5] = array('val' => 'KT', 'tt' => '4', 'noidung' => 'Sự nghiệp kinh tế');
        $ar_I[6] = array('val' => '', 'tt' => '5', 'noidung' => 'Sự nghiệp khác');

        $a_It = array(
            'heso' => 0, 'pckv' => 0, 'pccv' => 0, 'vuotkhung' => 0,
            'pcudn' => 0, 'pcth' => 0, 'pctnn' => 0, 'pccovu' => 0,
            'pcdang' => 0, 'pcthni' => 0, 'pck' => 0, 'tongpc' => 0, 'pcdbqh' => 0, 'pcvk' => 0,
            'ttbh_dv' => 0, 'soluongduocgiao' => 0, 'soluongbienche' => 0,
            'chenhlech' => 0, 'st_heso' => 0, 'st_pckv' => 0, 'st_pccv' => 0, 'st_vuotkhung' => 0,
            'st_pcudn' => 0, 'st_pcth' => 0, 'st_pctnn' => 0, 'st_pccovu' => 0,
            'st_pcdang' => 0, 'st_pcthni' => 0, 'st_pck' => 0, 'tbh_dv',
        );

        for ($i = 0; $i < count($ar_I); $i++) {
            if ($ar_I[$i]['val'] == '') {
                $chitiet = $m_tonghop_ct->wherenotin('linhvuchoatdong', ['QLNN', 'GD;DT', 'GD', 'DT', 'YTE', 'KT']);
            } else {
                $chitiet = $m_tonghop_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
            }

            $ar_I[$i]['soluongbienche'] = count($chitiet);
            $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
            $tongpc = 0;
            $tonghs = 0;
            foreach ($a_pc_goc as $pc) {
                $pc_st = 'st_' . $pc;

                $ar_I[$i][$pc] = isset($inputs['innoidung']) ? $chitiet->sum($pc) : $chitiet->sum($pc_st);
                $a_It[$pc] += $ar_I[$i][$pc];
                $tongpc += isset($inputs['innoidung']) ? $chitiet->sum($pc) : $chitiet->sum($pc_st);
                $tonghs += $chitiet->sum($pc);
            }

            $ar_I[$i]['tongpc'] = $tongpc - $ar_I[$i]['heso'];
            $a_It['tongpc'] += $ar_I[$i]['tongpc'];
            if (isset($inputs['innoidung'])) {
                $ar_I[$i]['ttbh_dv'] = $chitiet == [] ? 0 : $chitiet->sum('ttbh_dv_hs');
            } else {
                $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
            }
            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
            $ar_I[$i]['chenhlech'] = round(($ar_I[$i]['tongpc'] + $ar_I[$i]['heso'] + $chitiet->sum('ttbh_dv')) * 0.1);
            $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];
        }
        return view('reports.thongtu46.donvi.mau2g_tt46')
            ->with('furl', '/tong_hop_bao_cao/')
            ->with('m_dv', $m_donvi)
            ->with('ar_I', $ar_I)
            ->with('a_It', $a_It)
            ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
    }
}
