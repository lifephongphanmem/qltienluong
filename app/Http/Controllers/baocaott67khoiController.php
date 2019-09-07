<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class baocaott67khoiController extends Controller
{
    function mau2a1_khoi()
    {
        if (Session::has('admin')) {
            $macqcq = session('admin')->madv;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();

            $model_bienche = chitieubienche::where('nam', '2017')->wherein('madv', function ($qr) use ($macqcq) {
                $qr->select('madv')->from('dmdonvi')->where('macqcq', $macqcq)->distinct()->get();
            })->get();

            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);
            $model_tonghop = tonghopluong_donvi::where('thang', '07')->where('nam', '2017')
                ->where('macqcq', session('admin')->madv)->get();
            $luongcb = 0;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv', function ($qr) use ($macqcq) {
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2017')
                    ->where('macqcq', $macqcq)->distinct()->get();
            })->get();
            foreach ($model_tonghop_ct as $ct) {
                $tonghop = $model_tonghop->where('madvth', $ct->madvth)->first();
                $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE');

            //dd($model_tonghop_ct);
            $ar_I = array();
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

            $a_It = array('luong' => 0,
                'pckv' => 0,
                'pccv' => 0,
                'pctnvk' => 0,
                'pcudn' => 0,
                'pcth' => 0,
                'pctn' => 0,
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
            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($model_bangluong_ct)) {
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                }

                $ar_I[$i]['soluongduocgiao'] = 0;
                $ar_I[$i]['soluongbienche'] = 0;
                if (isset($chitiet) && count($chitiet) > 0) {
                    $ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                    $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                    $ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                    $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];

                    $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
                    $ar_I[$i]['ttbh_dv'] = $chitiet->sum('stbhxh_dv') + $chitiet->sum('stbhyt_dv')
                        + $chitiet->sum('stkpcd_dv') + $chitiet->sum('stbhtn_dv');
                    $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                    $ar_I[$i]['pckv'] = $chitiet->sum('pckv') * $luongcb;
                    $tongpc += $ar_I[$i]['pckv'];
                    $a_It['pckv'] += $ar_I[$i]['pckv'];

                    $ar_I[$i]['pccv'] = $chitiet->sum('pccv') * $luongcb;
                    $tongpc += $ar_I[$i]['pckv'];
                    $a_It['pckv'] += $ar_I[$i]['pckv'];

                    $ar_I[$i]['pctnvk'] = $chitiet->sum('pctnvk') * $luongcb;
                    $tongpc += $ar_I[$i]['pctnvk'];
                    $a_It['pctnvk'] += $ar_I[$i]['pctnvk'];

                    $ar_I[$i]['pcudn'] = $chitiet->sum('pcudn') * $luongcb;
                    $tongpc += $ar_I[$i]['pcudn'];
                    $a_It['pcudn'] += $ar_I[$i]['pcudn'];

                    $ar_I[$i]['pcth'] = $chitiet->sum('pcth') * $luongcb;
                    $tongpc += $ar_I[$i]['pcth'];
                    $a_It['pcth'] += $ar_I[$i]['pcth'];

                    $ar_I[$i]['pctn'] = $chitiet->sum('pctn') * $luongcb;
                    $tongpc += $ar_I[$i]['pctn'];
                    $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                    $ar_I[$i]['tongpc'] = $tongpc;
                    $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                } else {
                    $ar_I[$i]['luong'] = 0;
                    $ar_I[$i]['pckv'] = 0;
                    $ar_I[$i]['pccv'] = 0;
                    $ar_I[$i]['pctnvk'] = 0;
                    $ar_I[$i]['pcudn'] = 0;
                    $ar_I[$i]['pcth'] = 0;
                    $ar_I[$i]['pctn'] = 0;
                    $ar_I[$i]['pccovu'] = 0;
                    $ar_I[$i]['pcdang'] = 0;
                    $ar_I[$i]['pcthni'] = 0;
                    $ar_I[$i]['pck'] = 0;
                    $ar_I[$i]['tongpc'] = 0;
                    $ar_I[$i]['ttbh_dv'] = 0;
                }
            }
            $ar_I[11]['luong'] = $ar_I[12]['luong'] + $ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] + $ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] + $ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] + $ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] + $ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] + $ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] + $ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] + $ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] + $ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] + $ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] + $ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] + $ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] + $ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] + $ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] + $ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] + $ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] + $ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] + $ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] + $ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] + $ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] + $ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] + $ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai', 'KVXP');
            //
            if (count($model_bangluong_ct) > 0) {
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso');

                $ar_II['ttbh_dv'] = $model_bangluong_ct->sum('stbhxh_dv')
                    + $model_bangluong_ct->sum('stbhyt_dv')
                    + $model_bangluong_ct->sum('stkpcd_dv')
                    + $model_bangluong_ct->sum('stbhtn_dv');

                $ar_II['pckv'] = $model_bangluong_ct->sum('pckv');
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $model_bangluong_ct->sum('pccv');
                $tongpc += $ar_II['pckv'];
                $ar_II['pctnvk'] = $model_bangluong_ct->sum('pctnvk');
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $model_bangluong_ct->sum('pcudn');
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $model_bangluong_ct->sum('pcth');
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $model_bangluong_ct->sum('pctn');
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $model_bangluong_ct->sum('pccovu');
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $model_bangluong_ct->sum('pcdang');
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $model_bangluong_ct->sum('pcthni');
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $model_bangluong_ct->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;
            } else {
                $ar_II['luong'] = 0;
                $ar_II['pckv'] = 0;
                $ar_II['pccv'] = 0;
                $ar_II['pctnvk'] = 0;
                $ar_II['pcudn'] = 0;
                $ar_II['pcth'] = 0;
                $ar_II['pctn'] = 0;
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
            if (session('admin')->level == 'H') {
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

            return view('reports.thongtu67.khoi.mau2a1_tt68')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_It', $a_It)
                ->with('a_IIIt', $a_IIIt)
                ->with('a_IVt', $a_IVt)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_khoi()
    {
        if (Session::has('admin')) {
            $macqcq = session('admin')->madv;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            $model_tonghop = tonghopluong_donvi::where('thang', '07')->where('nam', '2017')
                ->where('macqcq', session('admin')->madv)->get();
            $luongcb = 1300000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2018 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv', function ($qr) use ($macqcq) {
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '07')->where('nam', '2017')
                    ->where('macqcq', $macqcq)->distinct()->get();
            })->get();
            foreach ($model_tonghop_ct as $ct) {
                $tonghop = $model_tonghop->where('madvth', $ct->madvth)->first();
                $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
                /*
                $ct->heso = $ct->heso / $ct->luongcoban;
                $ct->pckv = $ct->pckv / $ct->luongcoban;
                $ct->pccv = $ct->pccv / $ct->luongcoban;
                $ct->pctnvk = $ct->pctnvk / $ct->luongcoban;
                $ct->pcudn = $ct->pcudn / $ct->luongcoban;
                $ct->pcth = $ct->pcth / $ct->luongcoban;
                $ct->pctn = $ct->pctn / $ct->luongcoban;
                $ct->pccovu = $ct->pccovu / $ct->luongcoban;
                $ct->pcdang = $ct->pcdang / $ct->luongcoban;
                $ct->pcthni = $ct->pcthni / $ct->luongcoban;
                $ct->pck = $ct->pck / $ct->luongcoban;
                $ct->pcdbqh = $ct->pcdbqh / $ct->luongcoban;
                $ct->pcvk = $ct->pcvk / $ct->luongcoban;


                $ct->ttbh_dv = ($ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+$ct->stbhtn_dv)/ $ct->luongcoban;
                */
                $ct->ttbh_dv = $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE');
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
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

            $a_It = array('luong' => 0,
                'pckv' => 0,
                'pccv' => 0,
                'pctnvk' => 0,
                'pcudn' => 0,
                'pcth' => 0,
                'pctn' => 0,
                'pccovu' => 0,
                'pcdang' => 0,
                'pcthni' => 0,
                'pck' => 0,
                'tongpc' => 0,
                'ttbh_dv' => 0,
                'chenhlech' => 0
            );

            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($model_bangluong_ct)) {
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                }

                if (count($chitiet->toarray()) > 0) {
                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso' * $luongcb);
                    $a_It['luong'] += $ar_I[$i]['luong'];

                    $ar_I[$i]['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);
                    $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                    $ar_I[$i]['pckv'] = $chitiet->sum('pckv') * $luongcb;
                    $tongpc += $ar_I[$i]['pckv'];
                    $a_It['pckv'] += $ar_I[$i]['pckv'];

                    $ar_I[$i]['pccv'] = $chitiet->sum('pccv') * $luongcb;
                    $tongpc += $ar_I[$i]['pckv'];
                    $a_It['pckv'] += $ar_I[$i]['pckv'];

                    $ar_I[$i]['pctnvk'] = $chitiet->sum('pctnvk') * $luongcb;
                    $tongpc += $ar_I[$i]['pctnvk'];
                    $a_It['pctnvk'] += $ar_I[$i]['pctnvk'];

                    $ar_I[$i]['pcudn'] = $chitiet->sum('pcudn') * $luongcb;
                    $tongpc += $ar_I[$i]['pcudn'];
                    $a_It['pcudn'] += $ar_I[$i]['pcudn'];

                    $ar_I[$i]['pcth'] = $chitiet->sum('pcth') * $luongcb;
                    $tongpc += $ar_I[$i]['pcth'];
                    $a_It['pcth'] += $ar_I[$i]['pcth'];

                    $ar_I[$i]['pctn'] = $chitiet->sum('pctn') * $luongcb;
                    $tongpc += $ar_I[$i]['pctn'];
                    $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                    $ar_I[$i]['tongpc'] = $tongpc;
                    $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                    $ar_I[$i]['chenhlech'] = round(($tongpc + $ar_I[$i]['ttbh_dv'] + $ar_I[$i]['luong']) * 90000 / $luongcb);
                    $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                } else {
                    $ar_I[$i]['luong'] = 0;
                    $ar_I[$i]['pckv'] = 0;
                    $ar_I[$i]['pccv'] = 0;
                    $ar_I[$i]['pctnvk'] = 0;
                    $ar_I[$i]['pcudn'] = 0;
                    $ar_I[$i]['pcth'] = 0;
                    $ar_I[$i]['pctn'] = 0;
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
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] + $ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] + $ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] + $ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] + $ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] + $ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] + $ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] + $ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] + $ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] + $ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] + $ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] + $ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] + $ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] + $ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] + $ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] + $ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] + $ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] + $ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] + $ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] + $ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] + $ar_I[2]['ttbh_dv'];
            //dd($ar_I);

            $ar_II = array();
            if (isset($model_bangluong_ct)) {
                $chitiet = $model_bangluong_ct->where('maphanloai', 'KVXP');
            }
            //dd($chitiet);
            if (isset($chitiet)) {
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso') * $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk') * $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn') * $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth') * $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn') * $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu') * $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang') * $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni') * $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc + $ar_II['ttbh_dv'] + $ar_II['luong']) * 90000 / $luongcb);

            } else {
                $ar_II['luong'] = 0;
                $ar_II['pckv'] = 0;
                $ar_II['pccv'] = 0;
                $ar_II['pctnvk'] = 0;
                $ar_II['pcudn'] = 0;
                $ar_II['pcth'] = 0;
                $ar_II['pctn'] = 0;
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
            if (session('admin')->level == 'H') {
                if ($m_dv->capdonvi >= 3) {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[2]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[2]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                } else {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            } else {
                if ($m_dv->capdonvi >= 3) {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                } else {
                    if (isset($model_bangluong_ct)) {
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            //dd($ar_I);

            return view('reports.thongtu67.khoi.mau2a2_tt68')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_It', $a_It)
                ->with('a_IIIt', $a_IIIt)
                ->with('a_IVt', $a_IVt)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2c_khoi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
            $model_congtac = dmphanloaict::all();
            $macqcq = session('admin')->madv;
            $luongcb = 1150000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop = bangluong::where('thang', '07')->where('nam', '2017')
                ->wherein('madv', function ($q) use ($macqcq) {
                    $q->select('madv')->from('dmdonvi')->where('macqcq', $macqcq)->get();
                })->get();
            $luongcb = 1150000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop);
            $model_tonghop_ct = (new data())->getBangluong_ct_ar('07', a_unique(array_column($model_tonghop->toarray(), 'mabl')));

            /*
            $model_tonghop_ct = bangluong_ct::wherein('mabl',function($qr) use($macqcq){
                $qr->select('mabl')->from('bangluong')->where('thang','07')->where('nam','2017')
                    ->wherein('madv',function($q) use($macqcq){
                        $q->select('madv')->from('dmdonvi')->where('macqcq',$macqcq)->get();
                    })->get();
            })->get();
            */
            foreach ($model_tonghop_ct as $ct) {
                //$ct->luongcb = $model_bangluong->luongcoban;

                $congtac = $model_congtac->where('mact', $ct->mact)->first();
                $ct->macongtac = isset($congtac->macongtac) ? $congtac->macongtac : null;

                $tonghop = $model_tonghop->where('mabl', $ct->mabl)->first();
                $ct->linhvuchoatdong = $tonghop->linhvuchoatdong;//chỉ dùng cho khối HCSN
                $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac', 'BIENCHE')->where('heso', '<=', '2.34');

            //dd($model_bangluong_ct->toarray());

            $ar_I = array();
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

            $a_It = array('luong' => 0,
                'phucap' => 0,
                'soluong' => 0,
                'chenhlech' => 0
            );

            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($model_bangluong_ct)) {
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong', $ar_I[$i]['val']);
                }
                $ar_I[$i]['soluong'] = 0;
                $ar_I[$i]['chenhlech'] = 0;
                if (isset($chitiet) && count($chitiet) > 0) {
                    $ar_I[$i]['soluong'] = $chitiet->count();

                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];
                    $tongpc += $chitiet->sum('pckv') * $luongcb;
                    $tongpc += $chitiet->sum('pccv') * $luongcb;
                    $tongpc += $chitiet->sum('pctnvk') * $luongcb;
                    $tongpc += $chitiet->sum('pcudn') * $luongcb;
                    $tongpc += $chitiet->sum('pcth') * $luongcb;
                    $tongpc += $chitiet->sum('pctn') * $luongcb;
                    $tongpc += $chitiet->sum('pccovu') * $luongcb;
                    $tongpc += $chitiet->sum('pcdang') * $luongcb;
                    $tongpc += $chitiet->sum('pcthni') * $luongcb;
                    $tongpc += $chitiet->sum('pck') * $luongcb;

                    $ar_I[$i]['chenhlech'] = (($tongpc + $a_It['luong']) / $luongcb) * 60000;
                    $ar_I[$i]['phucap'] = $tongpc;
                    $a_It['phucap'] += $ar_I[$i]['phucap'];
                } else {
                    $ar_I[$i]['phucap'] = 0;
                    $ar_I[$i]['luong'] = 0;
                }
            }
            $ar_I[11]['luong'] = $ar_I[12]['luong'] + $ar_I[13]['luong'];
            $ar_I[11]['phucap'] = $ar_I[12]['phucap'] + $ar_I[13]['phucap'];
            $ar_I[11]['soluong'] = $ar_I[12]['soluong'] + $ar_I[13]['soluong'];
            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] + $ar_I[2]['luong'];
            $ar_I[0]['phucap'] = $ar_I[1]['phucap'] + $ar_I[2]['phucap'];
            $ar_I[0]['soluong'] = $ar_I[1]['soluong'] + $ar_I[2]['soluong'];
            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] + $ar_I[2]['chenhlech'];

            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluong'] = 0;
            $ar_II['chenhlech'] = 0;
            if (isset($model_bangluong_ct)) {
                $chitiet = $model_bangluong_ct->where('maphanloai', 'KVXP');
            }
            if (count($chitiet) > 0) {
                $ar_II['soluong'] = $chitiet->count();
                $ar_II['luong'] = $chitiet->sum('heso') * $luongcb;

                $tongpc = 0;
                $tongpc += $chitiet->sum('pckv') * $luongcb;
                $tongpc += $chitiet->sum('pccv') * $luongcb;
                $tongpc += $chitiet->sum('pctnvk') * $luongcb;
                $tongpc += $chitiet->sum('pcudn') * $luongcb;
                $tongpc += $chitiet->sum('pcth') * $luongcb;
                $tongpc += $chitiet->sum('pctn') * $luongcb;
                $tongpc += $chitiet->sum('pccovu') * $luongcb;
                $tongpc += $chitiet->sum('pcdang') * $luongcb;
                $tongpc += $chitiet->sum('pcthni') * $luongcb;
                $tongpc += $chitiet->sum('pck') * $luongcb;

                $ar_II['phucap'] = $tongpc;
                $ar_II['chenhlech'] = (($tongpc + $ar_II['luong']) / $luongcb) * 60000;
            } else {
                $ar_II['luong'] = 0;
                $ar_II['phucap'] = 0;
            }
            //dd($ar_II);

            return view('reports.thongtu67.khoi.mau2c_tt68')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('a_It', $a_It)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau4a_khoi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                ->where('sohieu', 'ND38_2019')->get();
            $model_donvi = dmdonvi::where('madvbc', session('admin')->madvbc)->get();
            if (count($model) == 0) {
                return view('errors.nodata');
            }
            foreach ($model as $ct) {
                $donvi = $model_donvi->where('madv', $ct->madv)->first();
                $ct->phanloainguon = $donvi->phanloainguon;
                $ct->maphanloai = $donvi->maphanloai;
            }
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt' => '1', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2017 so dự toán Thủ tướng Chính phủ giao năm 2017', 'sotien' => '0');
            $a_A[1] = array('tt' => '2', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2018 so dự toán 2017 Thủ tướng Chính phủ giao', 'sotien' => '0');
            $a_A[2] = array('tt' => '3', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017', 'sotien' => '0');
            $a_A[3] = array('tt' => '4', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2018', 'sotien' => '0');
            $a_A[4] = array('tt' => '5', 'noidung' => 'Số thu được huy động từ nguồn để lại đơn vị năm 2018:', 'sotien' => '0');
            $a_A[5] = array('tt' => 'a', 'noidung' => 'Nguồn huy động từ các đơn vị tự đảm bảo(1):', 'sotien' => '0');
            $a_A[6] = array('tt' => '', 'noidung' => '+ Học phí', 'sotien' => '0');
            $a_A[7] = array('tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0');
            $a_A[8] = array('tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0');
            $a_A[9] = array('tt' => 'b', 'noidung' => 'Nguồn huy động từ các đơn vị chưa tự đảm bảo chi thường xuyên:', 'sotien' => '0');
            $a_A[10] = array('tt' => '', 'noidung' => '+ Học phí', 'sotien' => '0');
            $a_A[11] = array('tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0');
            $a_A[12] = array('tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0');
            $a_A[13] = array('tt' => '6', 'noidung' => 'Nguồn tiết kiệm chi gắn với thực hiện các giải pháp sắp xếp tổ chức bộ máy, tinh giản biên chế, đổi mới hoạt động đơn vị sự nghiệp công lập theo Nghị quyết số 18, 19 (nếu có)', 'sotien' => '0');
            $a_A[14] = array('tt' => '', 'noidung' => '+ Từ việc tinh giản biên chế tổ chức lại bộ máy (2)', 'sotien' => '0');
            $a_A[15] = array('tt' => '', 'noidung' => '+ Từ việc sát nhập các đầu mối, cơ quan, đơn vị (2)', 'sotien' => '0');
            $a_A[16] = array('tt' => '', 'noidung' => '+ Từ việc thay đổi cơ chế tự chủ của đơn vị sư nghiệp (3)', 'sotien' => '0');
            $a_A[17] = array('tt' => '', 'noidung' => '+ Từ việc sát nhập các xã không đủ điều kiện tiêu chuẩn', 'sotien' => '0');
            $a_A[18] = array('tt' => '7', 'noidung' => 'Nguồn thực hiện cải cách tiền lương năm 2017 chưa sử dụng hết chuyển sang năm 2018', 'sotien' => '0');

            //
            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BII[1] = array('tt' => '', 'noidung' => 'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)', 'sotien' => '0');
            $a_BII[2] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BII[3] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BII[4] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 88/2018/NĐ-CP', 'sotien' => '0');
            $a_BII[5] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BII[6] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BII[7] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017', 'sotien' => '0');

            $a_BIII = array();
            $a_BIII[0] = array('tt' => '1', 'noidung' => 'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)', 'sotien' => '0');
            $a_BIII[1] = array('tt' => '2', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2018 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)', 'sotien' => '0');
            $a_BIII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2018 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon', array('CHITXDT', 'CTX'));
            $a_A[4]['sotien'] = $model_tudb->sum('hocphi');
            $a_A[5]['sotien'] = $model_tudb->sum('vienphi');
            $a_A[6]['sotien'] = $model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] = $a_A[4]['sotien'] + $a_A[5]['sotien'] + $a_A[6]['sotien'];
            //$a_BII[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi') - $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu') - $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] = $a_A[8]['sotien'] + $a_A[9]['sotien'] + $a_A[10]['sotien'];
            $a_A[2]['sotien'] = $a_A[3]['sotien'] + $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai', 'KVXP');

            $a_BII[1]['sotien'] = $model_tudb->sum('luongphucap');
            $a_BII[2]['sotien'] = $model_xp->sum('luongphucap');
            $a_BII[0]['sotien'] = $model->sum('luongphucap') - $model_xp->sum('luongphucap');

            $a_BII[3]['sotien'] = $model->sum('daibieuhdnd');
            $a_BII[4]['sotien'] = $model->sum('nghihuu');
            $a_BII[5]['sotien'] = $model->sum('canbokct');
            $a_BII[6]['sotien'] = $model->sum('uyvien');
            $a_BII[7]['sotien'] = $model->sum('boiduong');

            $a_BIII[0]['sotien'] = $model->sum('thunhapthap');
            $a_BIII[1]['sotien'] = $model->sum('diaban');
            $a_BIII[2]['sotien'] = $model->sum('tinhgiam');

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BII' => (array_sum(array_column($a_BII, 'sotien')) - $a_BII[1]['sotien']),
                'BIII' => (array_sum(array_column($a_BIII, 'sotien')))
            );
            return view('reports.thongtu67.khoi.mau4a_tt68')
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

    function mau4b_khoi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                ->where('sohieu', 'ND38_2019')->get();
            //dd($model);
            if (count($model) == 0) {
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $data = array();
            $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            for ($i = 0; $i < count($data); $i++) {
                $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                $data[$i]['nhucau'] = $solieu->sum('nhucau');
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

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
            $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
            $data[4]['khac'] = 0;
            $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];

            return view('reports.thongtu67.khoi.mau4b_tt68')
                ->with('model', $model)
                ->with('data', $data)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

}
