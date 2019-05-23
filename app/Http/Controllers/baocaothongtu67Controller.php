<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\chitieubienche;
use App\dmdiabandbkk;
use App\dmdiabandbkk_chitiet;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaict;
use App\hosocanbo;
use App\nguonkinhphi;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_huyen;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController as data;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class baocaothongtu67Controller extends Controller
{
    function index() {
        if (Session::has('admin')) {
            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();
            $model_dvbc=dmdonvibaocao::where('level','H')->orwhere('level','T')->get();
            $model_dvbcT=dmdonvi::join('dmdonvibaocao','dmdonvi.madvbc','dmdonvibaocao.madvbc')
                ->where('dmdonvibaocao.level','T')
                ->where('dmdonvi.phanloaitaikhoan','TH' )
                ->get();
            return view('reports.thongtu67.index')
                ->with('model_dv', $model_dv)
                ->with('model_dvbc', $model_dvbc)
                ->with('model_dvbcT', $model_dvbcT)
                ->with('furl','/tong_hop_bao_cao/')
                ->with('pageTitle','Báo cáo tổng hợp lương');
        } else
            return view('errors.notlogin');
    }
    function index_huyen() {
        if (Session::has('admin')) {
            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();
            $model_dvbcT=dmdonvi::join('dmdonvibaocao','dmdonvi.madvbc','dmdonvibaocao.madvbc')
                ->where('dmdonvibaocao.level','T')
                ->where('dmdonvi.phanloaitaikhoan','TH' )
                ->get();
            return view('reports.thongtu67.index')
                ->with('model_dv', $model_dv)
                ->with('model_dvbcT', $model_dvbcT)
                ->with('furl','/tong_hop_bao_cao/')
                ->with('pageTitle','Báo cáo tổng hợp lương');
        } else
            return view('errors.notlogin');
    }
    //Tính bảng lương của toàn tỉnh

    function mau2a1_tt67(Request $request)
    {
        //Test trên huyện nên sau này sửa lại leve "T"
        //if ((Session::has('admin') && session('admin')->quanlykhuvuc == true) || (Session::has('admin') && session('admin')->username == 'khthso') ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('madvbc', 'like', $inputs['madv'] . '%')->get();

            $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madvbc) {
                $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
            })->get();

            foreach ($model_bienche as $bienche) {
                $bienche->maphanloai = $model_donvi->where('madv', $bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            $model_tonghop = tonghopluong_donvi::where('thang', '08')->where('nam', '2018')
                ->where('madvbc', 'like', $inputs['madv'] . '%')->get();

            $luongcb = 0.935;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                    $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                        ->distinct()->get();
                })->get();
            if (isset($inputs['inchitiet'])) {
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->select('tonghopluong_donvi_chitiet.linhvuchoatdong','tonghopluong_donvi_chitiet.heso','tonghopluong_donvi_chitiet.pckv','tonghopluong_donvi_chitiet.pccv',
                        'tonghopluong_donvi_chitiet.pctnvk','tonghopluong_donvi_chitiet.pcudn','pcth','pctn','pccovu','pcdang',
                        'pcthni','pck','nguoigui','madv','tonghopluong_donvi.mathh','tonghopluong_donvi.mathdv')
                    ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('tonghopluong_donvi_chitiet.macongtac', 'BIENCHE')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })
                    ->groupby('linhvuchoatdong','nguoigui','madv','mathh','mathdv')
                    ->get();
                dd($model_tonghop_ct->toarray());
            }

            if (session('admin')->username == 'khthso') {
                $model_bienche = chitieubienche::join('dmdonvi', 'dmdonvi.madv', '=', 'chitieubienche.madv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'dmdonvi.madvbc')
                    ->where('chitieubienche.nam', '2018')->where('dmdonvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao', 'dmdonvibaocao.madvbc', '=', 'tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc', 'like', $inputs['madv'] . '%')
                    ->where('dmdonvibaocao.level', 'T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                            ->distinct()->get();
                    })->get();
            }


            foreach ($model_tonghop_ct as $ct) {
                $tonghop = $model_tonghop->where('mathdv', $ct->mathdv)->first();
                //dd($model_tonghop_ct->toarray());
                $a_th = array_column($tonghop->toarray(), 'mathdv', 'maphanloai');
                $ct->maphanloai = $model_donvi->where('madv', $tonghop->madv)->first()->maphanloai;
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

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai', '<>', 'KVXP');

            //dd($model_bangluong_ct);
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
                    $ar_I[$i]['pctn'] = 0;
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
                            $ar_I[$i]['luong'] = $thongtinchitiet['heso'];
                            $a_It['luong'] += $ar_I[$i]['luong'];

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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'];
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                            $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);

                            $ar_I[$luugr]['luong'] += $ar_I[$i]['luong'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
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

                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
                $ar_I[$gddt]['pccovu'] = $ar_I[$giaoduc]['pccovu'] + $ar_I[$daotao]['pccovu'];
                $ar_I[$gddt]['pcdang'] = $ar_I[$giaoduc]['pcdang'] + $ar_I[$daotao]['pcdang'];
                $ar_I[$gddt]['pcthni'] = $ar_I[$giaoduc]['pcthni'] + $ar_I[$daotao]['pcthni'];
                $ar_I[$gddt]['pck'] = $ar_I[$giaoduc]['pck'] + $ar_I[$daotao]['pck'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];

                $ar_I[$qlnnddt]['luong'] = $ar_I[$qlnn]['luong'] + $ar_I[$ddt]['luong'];
                $ar_I[$qlnnddt]['pckv'] = $ar_I[$qlnn]['pckv'] + $ar_I[$ddt]['pckv'];
                $ar_I[$qlnnddt]['pccv'] = $ar_I[$qlnn]['pccv'] + $ar_I[$ddt]['pccv'];
                $ar_I[$qlnnddt]['pctnvk'] = $ar_I[$qlnn]['pctnvk'] + $ar_I[$ddt]['pctnvk'];
                $ar_I[$qlnnddt]['pcudn'] = $ar_I[$qlnn]['pcudn'] + $ar_I[$ddt]['pcudn'];
                $ar_I[$qlnnddt]['pcth'] = $ar_I[$qlnn]['pcth'] + $ar_I[$ddt]['pcth'];
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
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
                        $ar_I[$i]['luong'] = $chitiet->sum('heso');
                        $a_It['luong'] += $ar_I[$i]['luong'];

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

                        $ar_I[$i]['pctn'] = $chitiet->sum('pctn');
                        $tongpc += $ar_I[$i]['pctn'];
                        $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                        $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);
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
                $ar_II['luong'] = $model_bangluong_ct->sum('heso');

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

                $ar_II['ttbh_dv'] = $model_bangluong_ct->sum('ttbh_dv');
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
            if (isset($inputs['excel'])) {
                Excel::create('Mau2a1_TT68', function ($excel) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($ar_I, $ar_II, $ar_III, $ar_IV, $a_It, $a_IIIt, $a_IVt, $m_dv, $inputs) {
                        $sheet->loadView('reports.thongtu67.mau2a1_tt68')
                            ->with('ar_I', $ar_I)
                            ->with('ar_II', $ar_II)
                            ->with('ar_III', $ar_III)
                            ->with('ar_IV', $ar_IV)
                            ->with('a_It', $a_It)
                            ->with('a_IIIt', $a_IIIt)
                            ->with('a_IVt', $a_IVt)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'Mau2a1_TT68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.thongtu67.mau2a1_tt68')
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

    function mau2a1_tt67excel(Request $request) {
        //Test trên huyện nên sau này sửa lại leve "T"
        //if ((Session::has('admin') && session('admin')->quanlykhuvuc == true) || (Session::has('admin') && session('admin')->username == 'khthso') ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('madvbc','like',$inputs['madv'].'%')->get();

            $model_bienche = chitieubienche::where('nam','2018')->wherein('madv',function($qr)use($madvbc){
                $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc)->distinct()->get();
            })->get();

            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                ->where('madvbc','like',$inputs['madv'].'%')->get();

            $luongcb = 0.935;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                    $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                        ->distinct()->get();
                })->get();

            if(session('admin')->username == 'khthso')
            {
                $model_bienche = chitieubienche::join('dmdonvi','dmdonvi.madv','=','chitieubienche.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->where('chitieubienche.nam','2018')->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }


            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('mathdv',$ct->mathdv)->first();
                //dd($model_tonghop_ct->toarray());
                $a_th =  array_column($tonghop->toarray(),'mathdv','maphanloai');
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
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
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv +$ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;

            }

            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>','KVXP');

            //dd($model_bangluong_ct);
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
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
            }
            else {
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
                'soluongbienche'=> 0,
            );
            //thiếu chỉ tiêu biên chế
            if(isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt =0;
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
                    $ar_I[$i]['pctn'] = 0;
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
                            $ar_I[$i]['luong'] = $thongtinchitiet['heso'];
                            $a_It['luong'] += $ar_I[$i]['luong'];

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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'];
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                            $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);

                            $ar_I[$luugr]['luong'] += $ar_I[$i]['luong'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
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

                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
                $ar_I[$gddt]['pccovu'] = $ar_I[$giaoduc]['pccovu'] + $ar_I[$daotao]['pccovu'];
                $ar_I[$gddt]['pcdang'] = $ar_I[$giaoduc]['pcdang'] + $ar_I[$daotao]['pcdang'];
                $ar_I[$gddt]['pcthni'] = $ar_I[$giaoduc]['pcthni'] + $ar_I[$daotao]['pcthni'];
                $ar_I[$gddt]['pck'] = $ar_I[$giaoduc]['pck'] + $ar_I[$daotao]['pck'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];

                $ar_I[$qlnnddt]['luong'] = $ar_I[$qlnn]['luong'] + $ar_I[$ddt]['luong'];
                $ar_I[$qlnnddt]['pckv'] = $ar_I[$qlnn]['pckv'] + $ar_I[$ddt]['pckv'];
                $ar_I[$qlnnddt]['pccv'] = $ar_I[$qlnn]['pccv'] + $ar_I[$ddt]['pccv'];
                $ar_I[$qlnnddt]['pctnvk'] = $ar_I[$qlnn]['pctnvk'] + $ar_I[$ddt]['pctnvk'];
                $ar_I[$qlnnddt]['pcudn'] = $ar_I[$qlnn]['pcudn'] + $ar_I[$ddt]['pcudn'];
                $ar_I[$qlnnddt]['pcth'] = $ar_I[$qlnn]['pcth'] + $ar_I[$ddt]['pcth'];
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];

            }
            else {
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
                        $ar_I[$i]['luong'] = $chitiet->sum('heso');
                        $a_It['luong'] += $ar_I[$i]['luong'];

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

                        $ar_I[$i]['pctn'] = $chitiet->sum('pctn');
                        $tongpc += $ar_I[$i]['pctn'];
                        $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                        $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);
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
            }
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai','KVXP');
            //dd($model_bangluong_ct);
            //
            if(count($model_bangluong_ct)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso');

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

                $ar_II['ttbh_dv'] =$model_bangluong_ct->sum('ttbh_dv') ;
            }
            else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0);
            $a_IVt = array('tongso'=>0);
            //dd(session('admin')->maphanloai);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi > 2){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];

                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }
            Excel::create('Mau2a1_TT67',function($excel) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau2a1_tt67excel')
                        ->with('ar_I',$ar_I)
                        ->with('ar_II',$ar_II)
                        ->with('ar_III',$ar_III)
                        ->with('ar_IV',$ar_IV)
                        ->with('a_It',$a_It)
                        ->with('a_IIIt',$a_IIIt)
                        ->with('a_IVt',$a_IVt)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2a1_TT67');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_tt67(Request $request) {
        //if (Session::has('admin') && session('admin')->quanlykhuvuc == true) {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('madvbc','like',$inputs['madv'].'%')->get();
            $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                ->where('madvbc','like',$inputs['madv'].'%')->get();
            $luongcb = 1;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                    $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                        ->distinct()->get();
                })->get();

            if(session('admin')->username == 'khthso')
            {
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }

            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('mathdv',$ct->mathdv)->first();
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
                $ct->ttbh_dv = ($ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+$ct->stbhtn_dv);
            }

            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>', 'KVXP');
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
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
            if(isset($inputs['inchitiet'])) {
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
                    $ar_I[$i]['pctn'] = 0;
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
                    $d= 1;
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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'] * $luongcb;
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            $ar_I[$luugr]['chenhlech'] += $ar_I[$i]['chenhlech'];

                        }
                    }else {
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
                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
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
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                $ar_I[$qlnnddt]['chenhlech'] = $ar_I[$qlnn]['chenhlech'] + $ar_I[$ddt]['chenhlech'];
            }
            else {
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
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

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
            if(isset($chitiet)){
                $tongpc = 0;
                $ar_II['luong'] = $chitiet->sum('heso')* $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv')* $luongcb;
                $tongpc += $ar_II['pccv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk')* $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn')* $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth')* $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn')* $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu')* $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang')* $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni')* $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/1390000);

            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi >= 3){

                    $ar_III[2]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[2]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                    $ar_IV[2]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[2]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000;


                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{

                    $ar_III[1]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[1]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000/ 1390000;
                    $ar_IV[1]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[1]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000/ 1390000;


                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            //dd($ar_I);
            if(isset($inputs['excel'])){
                Excel::create('mau2a2',function($excel) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                        $sheet->loadView('reports.thongtu67.mau2a2_tt68')
                            ->with('ar_I',$ar_I)
                            ->with('ar_II',$ar_II)
                            ->with('ar_III',$ar_III)
                            ->with('ar_IV',$ar_IV)
                            ->with('a_It',$a_It)
                            ->with('a_IIIt',$a_IIIt)
                            ->with('a_IVt',$a_IVt)
                            ->with('m_dv',$m_dv)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','Mau2a2');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }
            else{
                return view('reports.thongtu67.mau2a2_tt67')
                    ->with('furl','/tong_hop_bao_cao/')
                    ->with('ar_I',$ar_I)
                    ->with('ar_II',$ar_II)
                    ->with('ar_III',$ar_III)
                    ->with('ar_IV',$ar_IV)
                    ->with('a_It',$a_It)
                    ->with('a_IIIt',$a_IIIt)
                    ->with('a_IVt',$a_IVt)
                    ->with('m_dv',$m_dv)
                    ->with('inputs',$inputs)
                    ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 72/2018/NĐ-CP');
            }

        } else
            return view('errors.notlogin');
    }

    function mau2a2_tt67excel(Request $request) {
        //if (Session::has('admin') && session('admin')->quanlykhuvuc == true) {

        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('madvbc','like',$inputs['madv'].'%')->get();
            $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                ->where('madvbc','like',$inputs['madv'].'%')->get();
            $luongcb = 1;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                    $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                        ->distinct()->get();
                })->get();

            if(session('admin')->username == 'khthso')
            {
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }

            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('mathdv',$ct->mathdv)->first();
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
                $ct->ttbh_dv = ($ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+$ct->stbhtn_dv);
            }

            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>', 'KVXP');
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
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
            if(isset($inputs['inchitiet'])) {
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
                    $ar_I[$i]['pctn'] = 0;
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
                    $d= 1;
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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'] * $luongcb;
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            $ar_I[$luugr]['chenhlech'] += $ar_I[$i]['chenhlech'];

                        }
                    }else {
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
                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
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
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                $ar_I[$qlnnddt]['chenhlech'] = $ar_I[$qlnn]['chenhlech'] + $ar_I[$ddt]['chenhlech'];
            }
            else {
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
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

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
            if(isset($chitiet)){
                $tongpc = 0;
                $ar_II['luong'] = $chitiet->sum('heso')* $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv')* $luongcb;
                $tongpc += $ar_II['pccv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk')* $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn')* $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth')* $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn')* $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu')* $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang')* $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni')* $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/1390000);

            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi >= 3){

                    $ar_III[2]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[2]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                    $ar_IV[2]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[2]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000;


                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{

                    $ar_III[1]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[1]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000/ 1390000;
                    $ar_IV[1]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[1]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000/ 1390000;


                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            //dd($ar_I);
            Excel::create('mau2a2',function($excel) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau2a2excel')
                        ->with('ar_I',$ar_I)
                        ->with('ar_II',$ar_II)
                        ->with('ar_III',$ar_III)
                        ->with('ar_IV',$ar_IV)
                        ->with('a_It',$a_It)
                        ->with('a_IIIt',$a_IIIt)
                        ->with('a_IVt',$a_IVt)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2a2');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2b_tt67(Request $request) {
        if (Session::has('admin')  ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso')) {
            $inputs=$request->all();
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                ->where('dmphanloaict.macongtac','NGHIHUU')
                ->get();
            $ar_I = array();
            $ar_I[]=array('val'=>'BT','tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('val'=>'P','tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('val'=>'K','tt'=>'3','noidung'=>'Các chức danh còn lại');

            $a_It = array('cb' => 0,
                'quy09' => 0,
                'quy76' => 0,
                'quytang' => 0,
                'bhyt' => 0,
                'tongquy' => 0
            );

            for($i=0;$i<count($ar_I);$i++)
            {
                if(isset($m_hscb)){
                    $chitiet = $m_hscb->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
            }
            if(isset($inputs['excel'])){
                Excel::create('mau2b_tt68',function($excel) use($ar_I,$a_It,$m_dv){
                    $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$m_dv){
                        $sheet->loadView('reports.thongtu67.mau2b_tt68')
                            ->with('ar_I',$ar_I)
                            ->with('a_It',$a_It)
                            ->with('m_dv',$m_dv)
                            ->with('pageTitle','mau2b_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
                return view('reports.thongtu67.mau2b_tt68')
                    ->with('furl','/tong_hop_bao_cao/')
                    ->with('ar_I',$ar_I)
                    ->with('m_dv',$m_dv)
                    ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 72/2018/NĐ-CP');
            }

        } else
            return view('errors.notlogin');
    }

    function mau2b_tt67excel(Request $request) {
        if (Session::has('admin')  ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso')) {
            $inputs=$request->all();
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                ->where('dmphanloaict.macongtac','NGHIHUU')
                ->get();
            $ar_I = array();
            $ar_I[]=array('val'=>'BT','tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('val'=>'P','tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('val'=>'K','tt'=>'3','noidung'=>'Các chức danh còn lại');

            $a_It = array('cb' => 0,
                'quy09' => 0,
                'quy76' => 0,
                'quytang' => 0,
                'bhyt' => 0,
                'tongquy' => 0
            );

            for($i=0;$i<count($ar_I);$i++)
            {
                if(isset($m_hscb)){
                    $chitiet = $m_hscb->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
            }
            Excel::create('mau2b',function($excel) use($ar_I,$a_It,$m_dv){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$m_dv){
                    $sheet->loadView('reports.thongtu67.mau2bexcel')
                        ->with('ar_I',$ar_I)
                        ->with('a_It',$a_It)
                        ->with('m_dv',$m_dv)
                        ->with('pageTitle','Mau2b');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2c_tt67(Request $request) {
        //if (Session::has('admin') && session('admin')->quanlykhuvuc == true) {
        if (Session::has('admin')  ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs=$request->all();
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                ->select('linhvuchoatdong', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                    'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                    'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                    'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                ->where('heso','<=', 2.34)
                ->get();
            if(session('admin')->username == 'khthso')
            {
                $m_cb =hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('macanbo','linhvuchoatdong', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                        'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn', 'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('hosocanbo.heso','<=', 2.34)
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $luongcb = 60000;
            $ar_I = array();
            $ar_Igr= array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }

            $a_It = array('dt' => 0,
                'hstl' => 0,
                'hspc' => 0,
                'cl' => 0,
                'nc' => 0
            );
            if(isset($inputs['inchitiet'])) {
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
                        ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                        ->where('heso','<=', 2.34)
                        ->groupby('dmdonvi.tendv')
                        ->get();
                    $tendv = $ten_dv->toArray();
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        foreach($tendv as $tendvchitiet) {
                            $chitietct = $m_cb->where('tendv', $tendvchitiet['tendv'])->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                            $d= 1;
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
                    }
                     else {
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
            }
            else
            {
                for($i=0;$i<count($ar_I);$i++){
                    if(isset($m_cb)){
                        $chitiet = $m_cb->where('linhvuchoatdong',$ar_I[$i]['val']);
                    }

                    if(isset($chitiet)>0){
                        $tongcb = 0;
                        $tonghs = 0;
                        $tongpc = 0;
                        $ar_I[$i]['dt'] = $chitiet->count('heso');
                        $a_It['dt'] += $ar_I[$i]['dt'];

                        $ar_I[$i]['hstl'] = $chitiet->sum('heso');
                        $a_It['hstl'] += $ar_I[$i]['hstl'];

                        $tongpc +=  $chitiet->sum('pckv');
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
                        $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl'])*$luongcb);
                        $a_It['cl'] += $ar_I[$i]['cl'];
                        $ar_I[$i]['nc'] = $ar_I[$i]['cl']*6;
                        $a_It['nc'] += $ar_I[$i]['nc'];

                    }else{
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
            }
            if(isset($inputs['excel'])){
                Excel::create('Mau2c_tt68',function($excel) use($ar_I,$a_It,$ar_II,$m_dv,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$ar_II,$m_dv,$inputs){
                        $sheet->loadView('reports.thongtu67.Mau2c_tt68')
                            ->with('ar_I',$ar_I)
                            ->with('a_It',$a_It)
                            ->with('ar_II',$ar_II)
                            ->with('m_dv',$m_dv)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','Mau2c_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }   else{
                return view('reports.thongtu67.Mau2c_tt68')
                    ->with('m_dv',$m_dv)
                    ->with('ar_I',$ar_I)
                    ->with('a_It',$a_It)
                    ->with('inputs',$inputs)
                    ->with('pageTitle','BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BHTN');
            }

        } else
            return view('errors.notlogin');
    }

    function mau2c_tt67excel(Request $request) {
        //if (Session::has('admin') && session('admin')->quanlykhuvuc == true) {
        if (Session::has('admin')  ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs=$request->all();
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                ->select('linhvuchoatdong', 'heso','dmdonvi.maphanloai', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                    'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                    'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                    'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                ->where('heso','<=', 2.34)
                ->get();
            if(session('admin')->username == 'khthso')
            {
                $m_cb =hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('macanbo','linhvuchoatdong', 'heso','dmdonvi.maphanloai', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                        'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn', 'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('hosocanbo.heso','<=', 2.34)
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $luongcb = 60000;
            $ar_I = array();
            $ar_Igr= array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }

            $a_It = array('dt' => 0,
                'hstl' => 0,
                'hspc' => 0,
                'cl' => 0,
                'nc' => 0
            );
            if(isset($inputs['inchitiet'])) {
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
                        ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                        ->where('heso','<=', 2.34)
                        ->groupby('dmdonvi.tendv')
                        ->get();
                    $tendv = $ten_dv->toArray();
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        foreach($tendv as $tendvchitiet) {
                            $chitietct = $m_cb->where('tendv', $tendvchitiet['tendv'])->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                            $d= 1;
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
                    }
                     else {
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
            }
            else
            {
                for($i=0;$i<count($ar_I);$i++){
                    if(isset($m_cb)){
                        $chitiet = $m_cb->where('linhvuchoatdong',$ar_I[$i]['val']);
                    }

                    if(isset($chitiet)>0){
                        $tongcb = 0;
                        $tonghs = 0;
                        $tongpc = 0;
                        $ar_I[$i]['dt'] = $chitiet->count('heso');
                        $a_It['dt'] += $ar_I[$i]['dt'];

                        $ar_I[$i]['hstl'] = $chitiet->sum('heso');
                        $a_It['hstl'] += $ar_I[$i]['hstl'];

                        $tongpc +=  $chitiet->sum('pckv');
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
                        $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl'])*$luongcb);
                        $a_It['cl'] += $ar_I[$i]['cl'];
                        $ar_I[$i]['nc'] = $ar_I[$i]['cl']*6;
                        $a_It['nc'] += $ar_I[$i]['nc'];

                    }else{
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
            }
            $ar_II = array();

            $chitiet = $m_cb->where('maphanloai','KVXP');
            //
            if(count($chitiet)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['dt'] = $chitiet->count('heso');
                $ar_II['hstl'] = $chitiet->sum('heso');

                $tongpc +=  $chitiet->sum('pckv');
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
                $ar_II['cl'] = round(($tongpc + $ar_II['hstl'])*$luongcb);
                $ar_II['nc'] = $ar_II['cl']*6;
            }
            else{
                $ar_II['dt'] = 0;
                $ar_II['hstl'] = 0;
                $ar_II['hspc'] = 0;
                $ar_II['cl'] = 0;
                $ar_II['nc'] = 0;
            }
            Excel::create('Mau2c_BcNCCL',function($excel) use($ar_I,$a_It,$ar_II,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$ar_II,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.Mau2c_BcNCCLexcel')
                        ->with('ar_I',$ar_I)
                        ->with('a_It',$a_It)
                        ->with('ar_II',$ar_II)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2c_BcNCCL');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2d_tt67(Request $request) {
        //if (Session::has('admin') && session('admin')->quanlykhuvuc == true) {
        if (Session::has('admin')  ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs=$request->all();
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                ->select('dmdiabandbkk.id','phanloai')
                ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                ->where('maphanloai','KVXP')
                ->get();
            if(session('admin')->username == 'khthso')
            {
                $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('dmdiabandbkk.id','phanloai')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvi.maphanloai','KVXP')
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $m_dt = dmdiabandbkk_chitiet::join('dmdiabandbkk','dmdiabandbkk_chitiet.madiaban' ,'=', 'dmdiabandbkk.madiaban')
                ->select('dmdiabandbkk_chitiet.id','phanloai')->get();

            $ar_I = array();
            $ar_I[]=array('val'=>'XL1;XL2;XL3','tt'=>'I','noidung'=>'Xã, phường, thị trấn');
            $ar_I[]=array('val'=>'XL1','tt'=>'1','noidung'=>'Xã loại I');
            $ar_I[]=array('val'=>'XL2','tt'=>'2','noidung'=>'Xã loại II');
            $ar_I[]=array('val'=>'XL3','tt'=>'3','noidung'=>'Xã loại III');
            $ar_I[]=array('val'=>'DBKK;BGHD;DBTD','tt'=>'II','noidung'=>'Thôn, tỏ dân phố');
            $ar_I[]=array('val'=>'BGHD','tt'=>'1','noidung'=>'Số xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'BGHD','tt'=>'','noidung'=>'- Thôn thuộc xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'DBKK','tt'=>'2','noidung'=>'Số xã khó khăn theo Quyết định 1049/QĐ-TTg ngày 26/6/2014');
            $ar_I[]=array('val'=>'DBKK','tt'=>'','noidung'=>'- Thôn thuộc xã khó khăn theo Quyết định 1049/QĐ-TTg');
            $ar_I[]=array('val'=>'XL12K','tt'=>'3','noidung'=>'Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)');
            $ar_I[]=array('val'=>'TXL12K','tt'=>'','noidung'=>'- Thôn thuộc xã loại I, loại II');
            $ar_I[]=array('val'=>'DBTD','tt'=>'4','noidung'=>'Số xã trọng điểm, phức tạp về an ninh trật tự');
            $ar_I[]=array('val'=>'DBTD','tt'=>'','noidung'=>'- Số thôn thuộc xã trọng điểm, phức tạp về an ninh');
            $ar_I[]=array('val'=>'TK,TDP','tt'=>'5','noidung'=>'Số xã, phường, thị trấn còn lại');
            $ar_I[]=array('val'=>'TK','tt'=>'','noidung'=>'- Thôn còn lại');
            $ar_I[]=array('val'=>'TDP','tt'=>'','noidung'=>'- Tổ dân phố');

            $a_It = array('tdv' => 0,
                'mk' => 0,
                'kp' => 0,
                'bhxh' => 0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($m_thon)){
                    $chitiet = $m_thon->where('phanloai',$ar_I[$i]['val']);
                }

                if(isset($chitiet)>0){
                    $tongdv = 0;
                    $tongkp = 0;
                    $tongbh = 0;
                    $kpk = 0;
                    $ar_I[$i]['tdv'] = $chitiet->count('id');
                    $a_It['tdv'] += $ar_I[$i]['tdv'];
                    if($ar_I[$i]['val'] == "XL1")
                    {
                        $ar_I[$i]['mk'] = "20,3";
                        $kpk = 20.3;
                    }
                    elseif($ar_I[$i]['val'] == "XL2")
                    {
                        $ar_I[$i]['mk'] = "18,6";
                        $kpk = 18.6;
                    }
                    elseif($ar_I[$i]['val'] == "XL3")
                    {
                        $ar_I[$i]['mk'] = "17,6";
                        $kpk = 17.6;
                    }

                    elseif($ar_I[$i]['val'] == "TBGHD" || $ar_I[$i]['val'] == "TDBKK" || $ar_I[$i]['val'] == "TXL12K" )
                    {
                        $ar_I[$i]['mk'] = "5,0";
                        $kpk = 5;
                    }
                    elseif($ar_I[$i]['val'] == "TK" || $ar_I[$i]['val'] == "TDP")
                    {
                        $ar_I[$i]['mk'] = "3,0";
                        $kpk = 3;
                    }
                    else
                        $ar_I[$i]['mk'] = "";
                    $ar_I[$i]['kp'] = $a_It['tdv']* $kpk * 6 * 900000;
                    $a_It['kp'] += $ar_I[$i]['kp'];
                    $ar_I[$i]['bhxh'] = 0;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];

                }else{
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['kp'] = 0;
                    $ar_I[$i]['bh'] = 0;
                }
            }
            //dd($ar_I);
            if(isset($inputs['excel'])) {
                Excel::create('Mau2d_tt68', function ($excel) use ($ar_I, $a_It, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($ar_I, $a_It, $m_dv, $inputs) {
                        $sheet->loadView('reports.thongtu67.Mau2d_tt68')
                            ->with('ar_I', $ar_I)
                            ->with('a_It', $a_It)
                            ->with('m_dv', $m_dv)
                            ->with('inputs', $inputs)
                            ->with('pageTitle', 'Mau2d_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
                return view('reports.thongtu67.Mau2d_tt68')
                    ->with('m_dv',$m_dv)
                    ->with('ar_I',$ar_I)
                    ->with('a_It',$a_It)
                    ->with('inputs',$inputs)
                    ->with('pageTitle','TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
            }

        } else
            return view('errors.notlogin');
    }

    function mau2d_tt67excel(Request $request) {
        //if (Session::has('admin') && session('admin')->quanlykhuvuc == true) {
        if (Session::has('admin')  ) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs=$request->all();
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                ->select('dmdiabandbkk.id','phanloai')
                ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                ->where('maphanloai','KVXP')
                ->get();
            if(session('admin')->username == 'khthso')
            {
                $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('dmdiabandbkk.id','phanloai')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvi.maphanloai','KVXP')
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $m_dt = dmdiabandbkk_chitiet::join('dmdiabandbkk','dmdiabandbkk_chitiet.madiaban' ,'=', 'dmdiabandbkk.madiaban')
                ->select('dmdiabandbkk_chitiet.id','phanloai')->get();

            $ar_I = array();
            $ar_I[]=array('val'=>'XL1;XL2;XL3','tt'=>'I','noidung'=>'Xã, phường, thị trấn');
            $ar_I[]=array('val'=>'XL1','tt'=>'1','noidung'=>'Xã loại I');
            $ar_I[]=array('val'=>'XL2','tt'=>'2','noidung'=>'Xã loại II');
            $ar_I[]=array('val'=>'XL3','tt'=>'3','noidung'=>'Xã loại III');
            $ar_I[]=array('val'=>'DBKK;BGHD;DBTD','tt'=>'II','noidung'=>'Thôn, tỏ dân phố');
            $ar_I[]=array('val'=>'BGHD','tt'=>'1','noidung'=>'Số xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'BGHD','tt'=>'','noidung'=>'- Thôn thuộc xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'DBKK','tt'=>'2','noidung'=>'Số xã khó khăn theo Quyết định 1049/QĐ-TTg ngày 26/6/2014');
            $ar_I[]=array('val'=>'DBKK','tt'=>'','noidung'=>'- Thôn thuộc xã khó khăn theo Quyết định 1049/QĐ-TTg');
            $ar_I[]=array('val'=>'XL12K','tt'=>'3','noidung'=>'Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)');
            $ar_I[]=array('val'=>'TXL12K','tt'=>'','noidung'=>'- Thôn thuộc xã loại I, loại II');
            $ar_I[]=array('val'=>'DBTD','tt'=>'4','noidung'=>'Số xã trọng điểm, phức tạp về an ninh trật tự');
            $ar_I[]=array('val'=>'DBTD','tt'=>'','noidung'=>'- Số thôn thuộc xã trọng điểm, phức tạp về an ninh');
            $ar_I[]=array('val'=>'TK,TDP','tt'=>'5','noidung'=>'Số xã, phường, thị trấn còn lại');
            $ar_I[]=array('val'=>'TK','tt'=>'','noidung'=>'- Thôn còn lại');
            $ar_I[]=array('val'=>'TDP','tt'=>'','noidung'=>'- Tổ dân phố');

            $a_It = array('tdv' => 0,
                'mk' => 0,
                'kp' => 0,
                'bhxh' => 0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($m_thon)){
                    $chitiet = $m_thon->where('phanloai',$ar_I[$i]['val']);
                }

                if(isset($chitiet)>0){
                    $tongdv = 0;
                    $tongkp = 0;
                    $tongbh = 0;
                    $kpk = 0;
                    $ar_I[$i]['tdv'] = $chitiet->count('id');
                    $a_It['tdv'] += $ar_I[$i]['tdv'];
                    if($ar_I[$i]['val'] == "XL1")
                    {
                        $ar_I[$i]['mk'] = "20,3";
                        $kpk = 20.3;
                    }
                    elseif($ar_I[$i]['val'] == "XL2")
                    {
                        $ar_I[$i]['mk'] = "18,6";
                        $kpk = 18.6;
                    }
                    elseif($ar_I[$i]['val'] == "XL3")
                    {
                        $ar_I[$i]['mk'] = "17,6";
                        $kpk = 17.6;
                    }

                    elseif($ar_I[$i]['val'] == "TBGHD" || $ar_I[$i]['val'] == "TDBKK" || $ar_I[$i]['val'] == "TXL12K" )
                    {
                        $ar_I[$i]['mk'] = "5,0";
                        $kpk = 5;
                    }
                    elseif($ar_I[$i]['val'] == "TK" || $ar_I[$i]['val'] == "TDP")
                    {
                        $ar_I[$i]['mk'] = "3,0";
                        $kpk = 3;
                    }
                    else
                        $ar_I[$i]['mk'] = "";
                    $ar_I[$i]['kp'] = $a_It['tdv']* $kpk * 6 * 900000;
                    $a_It['kp'] += $ar_I[$i]['kp'];
                    $ar_I[$i]['bhxh'] = 0;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];

                }else{
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['kp'] = 0;
                    $ar_I[$i]['bh'] = 0;
                }
            }
            //dd($ar_I);
            Excel::create('Mau2d_ThKPTT',function($excel) use($ar_I,$a_It,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.Mau2d_ThKPTTexcel')
                        ->with('ar_I',$ar_I)
                        ->with('a_It',$a_It)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2d_ThKPTT');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2đ_tt67(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ar_I = array();
            $ar_I[]=array('val'=>'QLNN','tt'=>'I','noidung'=>'Quản lý nhà nước');
            $ar_I[]=array('val'=>'SNCL','tt'=>'II','noidung'=>'Sự nghiệp công lập');
            $ar_I[]=array('val'=>'CTXDT','tt'=>'1','noidung'=>'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[]=array('val'=>'CTX','tt'=>'2','noidung'=>'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[]=array('val'=>'MPCTX','tt'=>'3','noidung'=>'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[]=array('val'=>'NNCTX','tt'=>'4','noidung'=>'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            if(isset($inputs['excel'])){
                Excel::create('Mau2dd_tt68',function($excel) use($ar_I,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($ar_I,$inputs){
                        $sheet->loadView('reports.thongtu67.Mau2dd_tt68')
                            ->with('ar_I',$ar_I)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','Mau2dd_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }
            else{
                return view('reports.thongtu67.Mau2dd_tt68')
                    ->with('inputs',$inputs)
                    ->with('ar_I',$ar_I)
                    ->with('pageTitle','TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2e_tt67(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ar_I = array();
            $ar_I[]=array('val'=>'CTXDT','tt'=>'1','noidung'=>'Đơn vị đảm bảo chi thường xuyên và chi đầu tư');
            $ar_I[]=array('val'=>'CTX','tt'=>'2','noidung'=>'Đơn vị đảm bảo chi thường xuyên');
            $ar_I[]=array('val'=>'MPCTX','tt'=>'3','noidung'=>'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[]=array('val'=>'NNCTX','tt'=>'4','noidung'=>'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            if(isset($inputs['excel'])){
                Excel::create('Mau2e_tt68',function($excel) use($ar_I,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($ar_I,$inputs){
                        $sheet->loadView('reports.thongtu67.Mau2e_tt68')
                            ->with('ar_I',$ar_I)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','Mau2e_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }
            else {
                return view('reports.thongtu67.Mau2e_tt68')
                    ->with('inputs', $inputs)
                    ->with('ar_I', $ar_I)
                    ->with('pageTitle', 'Báo cáo nguồn thực hiện CCTL tiết kiệm từ việc thay đổi cơ chế tự chủ');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2e_tt67excel(Request $request) {
        if (Session::has('admin')  ) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_dsh = dmdonvibaocao::where('level','H')->distinct()->get();
            $m_xa = dmdiabandbkk::join('dmdonvi','dmdonvi.madv', '=', 'dmdiabandbkk.madv')
                ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')->get();
            $ar_h = array();
            $tt = 0;
            foreach ($m_dsh as $h) {
                for ($i = 0; $i < count($m_dsh); $i++) {
                    $tt++;
                    $ar_h[$i]['tt'] = $tt;
                    $ar_h[$i]['noidung'] = $h['tendvbc'];
                }
            }
            Excel::create('Mau2e_ThKPTG',function($excel) use($ar_h){
                $excel->sheet('New sheet', function($sheet) use($ar_h){
                    $sheet->loadView('reports.thongtu67.Mau2e_ThKPTGexcel')
                        ->with('ar_h',$ar_h)
                        ->with('pageTitle','Mau2e_ThKPTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2g_tt67(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            $ar_I[1] = array('val' => 'GD', 'tt' => '', 'noidung' => '- Giáo dục');
            $ar_I[2] = array('val' => 'DT', 'tt' => '', 'noidung' => '- Đào tạo');
            $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
            $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
            $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
            $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
            $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
            $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
            $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
            $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
            $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
            if(isset($inputs['excel'])){
                Excel::create('Mau2g_tt68',function($excel) use($ar_I,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($ar_I,$inputs){
                        $sheet->loadView('reports.thongtu67.Mau2g_tt68')
                            ->with('ar_I',$ar_I)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','Mau2g_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }
            else {
                return view('reports.thongtu67.Mau2g_tt68')
                    ->with('ar_I', $ar_I)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'BÁO CÁO QUỸ TIỀN LƯƠNG, PHỤ CẤP ĐỐI VỚI LAO ĐỘNG THEO HỢP ĐỒNG KHU VỰC HÀNH CHÍNH VÀ ĐƠN VỊ SỰ NGHIỆP');
            }
        } else
            return view('errors.notlogin');
    }

    function mau2g_tt67excel() {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            Excel::create('Mau2g_ThPCUDTG',function($excel) use($m_dv){
                $excel->sheet('New sheet', function($sheet) use($m_dv){
                    $sheet->loadView('reports.thongtu67.Mau2g_ThPCUDTGexcel')
                        ->with('m_dv',$m_dv)
                        ->with('pageTitle','Mau2g_ThPCUDTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2h_tt67() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.Mau2h_ThPCTHTG')
                ->with('pageTitle','TỔNG HỢP PHỤ CẤP THU HÚT TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2h_tt67excel() {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            Excel::create('Mau2h_ThPCTHTG',function($excel) use($m_dv){
                $excel->sheet('New sheet', function($sheet) use($m_dv){
                    $sheet->loadView('reports.thongtu67.Mau2h_ThPCTHTGexcel')
                        ->with('m_dv',$m_dv)
                        ->with('pageTitle','Mau2h_ThPCTHTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function maubckpbhtn() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.BcNCKPTHBHTN')
                ->with('pageTitle','BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BẢO HIỂM THẤT NGHIỆP THEO NGHỊ ĐỊNH 28/2015/ND');
        } else
            return view('errors.notlogin');
    }

    function mau4a_tt67(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
        ///if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            if(count($model) == 0){
                return view('errors.nodata');
            }
            //foreach($model as $ct){
            //    $donvi = $model_donvi->where('madv',$ct->madv)->first();
            //    $ct->phanloainguon = $donvi->phanloainguon;
            //    $ct->maphanloai = $donvi->maphanloai;
            //}
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2017 so dự toán Thủ tướng Chính phủ giao năm 2017','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2018 so dự toán 2017 Thủ tướng Chính phủ giao','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'4','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2018','sotien'=>'0');
            $a_A[4] = array('tt'=>'5','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2018:','sotien'=>'0');
            $a_A[5] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo(1):','sotien'=>'0');
            $a_A[6] = array('tt'=>'','noidung'=>'+ Học phí','sotien'=>'0');
            $a_A[7] = array('tt'=>'','noidung'=>'+ Viện phí','sotien'=>'0');
            $a_A[8] = array('tt'=>'','noidung'=>'+ Nguồn thu khác','sotien'=>'0');
            $a_A[9] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo chi thường xuyên:','sotien'=>'0');
            $a_A[10] = array('tt'=>'','noidung'=>'+ Học phí','sotien'=>'0');
            $a_A[11] = array('tt'=>'','noidung'=>'+ Viện phí','sotien'=>'0');
            $a_A[12] = array('tt'=>'','noidung'=>'+ Nguồn thu khác','sotien'=>'0');
            $a_A[13] = array('tt'=>'6','noidung'=>'Nguồn tiết kiệm chi gắn với thực hiện các giải pháp sắp xếp tổ chức bộ máy, tinh giản biên chế, đổi mới hoạt động đơn vị sự nghiệp công lập theo Nghị quyết số 18, 19 (nếu có)','sotien'=>'0');
            $a_A[14] = array('tt'=>'','noidung'=>'+ Từ việc tinh giản biên chế tổ chức lại bộ máy (2)','sotien'=>'0');
            $a_A[15] = array('tt'=>'','noidung'=>'+ Từ việc sát nhập các đầu mối, cơ quan, đơn vị (2)','sotien'=>'0');
            $a_A[16] = array('tt'=>'','noidung'=>'+ Từ việc thay đổi cơ chế tự chủ của đơn vị sư nghiệp (3)','sotien'=>'0');
            $a_A[17] = array('tt'=>'','noidung'=>'+ Từ việc sát nhập các xã không đủ điều kiện tiêu chuẩn','sotien'=>'0');
            $a_A[18] = array('tt'=>'7','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2017 chưa sử dụng hết chuyển sang năm 2018','sotien'=>'0');

            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BII[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)','sotien'=>'0');
            $a_BII[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BII[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BII[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 88/2018/NĐ-CP','sotien'=>'0');
            $a_BII[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BII[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BII[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017','sotien'=>'0');

            $a_BIII = array();
            $a_BIII[0] = array('tt'=>'1','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)','sotien'=>'0');
            $a_BIII[1] = array('tt'=>'2','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2018 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BIII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2018 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon',array('CHITXDT','CTX'));
            $a_A[4]['sotien'] =$model_tudb->sum('hocphi');
            $a_A[5]['sotien'] =$model_tudb->sum('vienphi');
            $a_A[6]['sotien'] =$model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi')- $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu')- $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai','KVXP');

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
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')) - $a_BII[1]['sotien']),
                'BIII'=>(array_sum(array_column($a_BIII,'sotien')))
            );
            if(isset($inputs['excel'])){
                Excel::create('mau4a_tt68',function($excel) use($model,$a_A,$a_BII,$a_BIII,$a_TC,$m_dv,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($model,$a_A,$a_BII,$a_BIII,$a_TC,$m_dv,$inputs){
                        $sheet->loadView('reports.thongtu67.mau4a_tt68')
                            ->with('model',$model)
                            ->with('a_A',$a_A)
                            ->with('a_BII',$a_BII)
                            ->with('a_BIII',$a_BIII)
                            ->with('a_TC',$a_TC)
                            ->with('m_dv',$m_dv)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','mau4a_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
            return view('reports.thongtu67.mau4a_tt68')
                ->with('model',$model)
                ->with('a_A',$a_A)
                ->with('a_BII',$a_BII)
                ->with('a_BIII',$a_BIII)
                ->with('a_TC',$a_TC)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4a_tt67excel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
        ///if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            if(count($model) == 0){
                return view('errors.nodata');
            }
            //foreach($model as $ct){
            //    $donvi = $model_donvi->where('madv',$ct->madv)->first();
            //    $ct->phanloainguon = $donvi->phanloainguon;
            //    $ct->maphanloai = $donvi->maphanloai;
            //}
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2016 so dự toán Thủ tướng Chính phủ giao năm 2016','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo','sotien'=>'0');
            $a_A[4] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[5] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[6] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[7] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo','sotien'=>'0');
            $a_A[8] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[9] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[10] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[11] = array('tt'=>'4','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2016 chưa sử dụng hết chuyển sang 2017','sotien'=>'0');

            //
            $a_BI = array();
            $a_BI[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BI[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo ','sotien'=>'0');
            $a_BI[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BI[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BI[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 76/2017/NĐ-CP','sotien'=>'0');
            $a_BI[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BI[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BI[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 3115-QĐ/VVPTW','sotien'=>'0');

            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Kinh phí hỗ trợ chênh lệch tiền lương cho người có thu nhập thấp (NĐ17/2015/NĐ-CP) và mức lương 1,21 (6 tháng)','sotien'=>'0');
            $a_BII[1] = array('tt'=>'2','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ','sotien'=>'0');
            $a_BII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2017 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BII[3] = array('tt'=>'4','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2017 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon',array('CHITXDT','CTX'));
            $a_A[4]['sotien'] =$model_tudb->sum('hocphi');
            $a_A[5]['sotien'] =$model_tudb->sum('vienphi');
            $a_A[6]['sotien'] =$model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi')- $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu')- $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai','KVXP');

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
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BI'=>(array_sum(array_column($a_BI,'sotien')) - $a_BI[1]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')))
            );
            Excel::create('Mau2h_ThPCTHTG',function($excel) use($model,$a_A,$a_BI,$a_BII,$a_TC,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($model,$a_A,$a_BI,$a_BII,$a_TC,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.Mau2h_ThPCTHTGexcel')
                        ->with('model',$model)
                        ->with('a_A',$a_A)
                        ->with('a_BI',$a_BI)
                        ->with('a_BII',$a_BII)
                        ->with('a_TC',$a_TC)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2h_ThPCTHTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau4b_tt67(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $m_dv = dmdonvi::select('tendv','madv')
                ->where('madvbc','like',$inputs['madv'].'%')
                ->distinct()
                ->get();
            $ardv = $m_dv->toArray();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        //$luugr = 0;
                        $luugr = $i;
                        foreach ($ardv as $chitietdv) {
                            $solieu = $model->where('madv', $chitietdv['madv'])->where('linhvuchoatdong', $group[$j]['val']);
                            $d =1;
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
                                $data[$i]['khac'] = 0;
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
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
                $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
                $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
                $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
                $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
                $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
                $data[$gddt]['khac'] = 0;
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];

                /*
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
                */
            }
            else {
                for ($i = 0; $i < count($data); $i++) {
                    $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                    $data[$i]['nhucau'] = $solieu->sum('nhucau');
                    $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                    $data[$i]['hocphi'] = $solieu->sum('hocphi');
                    $data[$i]['vienphi'] = $solieu->sum('vienphi');
                    $data[$i]['khac'] =0;
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
            }
            if(isset($inputs['excel'])){
                Excel::create('mau4b_tt68',function($excel) use($model,$data,$m_dv,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($model,$data,$m_dv,$inputs){
                        $sheet->loadView('reports.thongtu67.mau4b_tt68')
                            ->with('model',$model)
                            ->with('data',$data)
                            ->with('m_dv',$m_dv)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','mau4b_tt68');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
            return view('reports.thongtu67.mau4b_tt68')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4b_tt67excel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
        //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $m_dv = dmdonvi::select('tendv','madv')
                ->where('madvbc','like',$inputs['madv'].'%')
                ->distinct()
                ->get();
            $ardv = $m_dv->toArray();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                            $d =1;
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

                /*
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
                */
            }
            else {
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
            Excel::create('mau4b',function($excel) use($model,$data,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($model,$data,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau4bexcel')
                        ->with('model',$model)
                        ->with('data',$data)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','mau4b');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau4b_tt67bs(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $m_dv = dmdonvi::select('tendv','madv')
                ->where('madvbc','like',$inputs['madv'].'%')
                ->distinct()
                ->get();
            $ardv = $m_dv->toArray();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $data[$i]['bosung'] = 0;
                    $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        //$luugr = 0;
                        $luugr = $i;
                        foreach ($ardv as $chitietdv) {
                            $solieu = $model->where('madv', $chitietdv['madv'])->where('linhvuchoatdong', $group[$j]['val']);
                            $d =1;
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
                                $data[$i]['khac'] = 0;
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                                $data[$i]['bosung'] = $solieu->sum('nhucau') - $solieu->sum('nguonkp');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                $data[$luugr]['khac'] += 0;
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
                $data[$gddt]['khac'] = 0;
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
                $data[$gddt]['bosung'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'] - $data[$giaoduc]['nguonthu'] - $data[$daotao]['nguonthu'];

                /*
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
                */
            }
            else {
                for ($i = 0; $i < count($data); $i++) {
                    $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                    $data[$i]['nhucau'] = $solieu->sum('nhucau');
                    $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                    $data[$i]['hocphi'] = $solieu->sum('hocphi');
                    $data[$i]['vienphi'] = $solieu->sum('vienphi');
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                    $data[$i]['bosung'] = $solieu->sum('nhucau') - $solieu->sum('nguonkp');
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['khac'] = 0;
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];
                $data[0]['bosung'] = $data[1]['nhucau'] + $data[2]['nhucau'] - $data[1]['nguonkp'] - $data[2]['nguonkp'];

                $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
                $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
                $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
                $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
                $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
                $data[4]['khac'] = 0;
                $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
                $data[4]['bosung'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'] - ($model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp']);
            }
            if(isset($inpust['excel'])){
                Excel::create('mau4b_tt68bosung',function($excel) use($model,$data,$m_dv,$inputs){
                    $excel->sheet('New sheet', function($sheet) use($model,$data,$m_dv,$inputs){
                        $sheet->loadView('reports.thongtu67.mau4b_tt68bosung')
                            ->with('model',$model)
                            ->with('data',$data)
                            ->with('m_dv',$m_dv)
                            ->with('inputs',$inputs)
                            ->with('pageTitle','mau4b_tt68bosung');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else {
                return view('reports.thongtu67.mau4b_tt68bosung')
                    ->with('model', $model)
                    ->with('data', $data)
                    ->with('m_dv', $m_dv)
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
            }
        } else
            return view('errors.notlogin');
    }

    function mau4b_tt67bsexcel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $m_dv = dmdonvi::select('tendv','madv')
                ->where('madvbc','like',$inputs['madv'].'%')
                ->distinct()
                ->get();
            $ardv = $m_dv->toArray();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                            $d =1;
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

                /*
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
                */
            }
            else {
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
            Excel::create('mau4b_bosung',function($excel) use($model,$data,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($model,$data,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau4b_bosungexcel')
                        ->with('model',$model)
                        ->with('data',$data)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','mau4b_bosung');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    //Cần tính toán lại 2 biểu này do
    //bảng lương bao gồm cả cán bộ ko chuyên trách, cán bộ hợp đồng =>sai lòi
    //chưa tính trương họp 1 tháng đơn vị có nhiều bảng lương
    function mau2a1_donvi() {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_bangluong = bangluong::where('thang','07')->where('nam','2017')
                ->where('madv',session('admin')->madv)->first();
            $model_bienche = chitieubienche::where('nam','2017')->where('madv',session('admin')->madv)->first();
            $luongcb = 0;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            if(isset($model_bangluong)){
                $luongcb = $model_bangluong->luongcoban;
                $model_congtac = dmphanloaict::all();
                //$model_bangluong_ct = bangluong_ct::where('mabl',$model_bangluong->mabl)->get();
                $model_bangluong_ct = (new data())->getBangluong_ct($model_bangluong->thang,$model_bangluong->mabl);
                foreach($model_bangluong_ct as $ct){
                    //$ct->luongcb = $model_bangluong->luongcoban;
                    $ct->linhvuchoatdong=$model_bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN
                    $congtac = $model_congtac->where('mact',$ct->mact)->first();
                    $ct->macongtac=isset($congtac->macongtac) ? $congtac->macongtac : null;
                }
                $model_bangluong_ct = $model_bangluong_ct->where('macongtac','BIENCHE');
            }
    //dd($model_bangluong->toarray());
            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

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
                'soluongbienche'=> 0,
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
                $ar_I[$i]['soluongduocgiao'] = 0;
                $ar_I[$i]['soluongbienche'] = 0;

                if(isset($chitiet) && count($chitiet)>0){

                    $ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                    $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                    $ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                    $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];

                    $ar_I[$i]['ttbh_dv'] = $chitiet->sum('ttbh_dv');
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
                }else{
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
                    $ar_I[$i]['soluongduocgiao'] = 0;
                    $ar_I[$i]['soluongbienche'] = 0;
                }
            }
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] +$ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] +$ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] +$ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] +$ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] +$ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] +$ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] +$ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] +$ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] +$ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] +$ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] +$ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] +$ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] +$ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] +$ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] +$ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] +$ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] +$ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] +$ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] +$ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] +$ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] +$ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] +$ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] +$ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] +$ar_I[2]['ttbh_dv'];
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = 0;
            $ar_II['soluongbienche'] = 0;
            if(session('admin')->maphanloai == 'KVXP' && isset($model_bangluong_ct)){
                $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso') * $luongcb;
                $ar_II['ttbh_dv'] = $model_bangluong_ct->sum('ttbh_dv');

                $ar_II['pckv'] = $model_bangluong_ct->sum('pckv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $model_bangluong_ct->sum('pccv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pctnvk'] = $model_bangluong_ct->sum('pctnvk') * $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $model_bangluong_ct->sum('pcudn') * $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $model_bangluong_ct->sum('pcth') * $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $model_bangluong_ct->sum('pctn') * $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $model_bangluong_ct->sum('pccovu') * $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $model_bangluong_ct->sum('pcdang') * $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $model_bangluong_ct->sum('pcthni') * $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $model_bangluong_ct->sum('pck') * $luongcb;
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;
            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0);
            $a_IVt = array('tongso'=>0);
            //dd(session('admin')->maphanloai);
            if(session('admin')->level=='H'){
                if($m_dv->capdonvi > 2){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];

                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }else{
                        if(isset($model_bangluong_ct)){
                            $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                            $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }

            return view('reports.thongtu67.donvi.mau2a1')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_donvi() {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_bangluong = bangluong::where('thang','07')->where('nam','2017')
                ->where('madv',session('admin')->madv)->first();
            $model_bienche = chitieubienche::where('nam','2017')->where('madv',session('admin')->madv)->first();
            $luongcb = 1300000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            if(isset($model_bangluong)){
                $model_congtac = dmphanloaict::all();
                //$model_bangluong_ct = bangluong_ct::where('mabl',$model_bangluong->mabl)->get();
                $model_bangluong_ct = (new data())->getBangluong_ct($model_bangluong->thang,$model_bangluong->mabl);
                foreach($model_bangluong_ct as $ct){
                    //$ct->luongcb = $model_bangluong->luongcoban;
                    $ct->linhvuchoatdong=$model_bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN
                    $congtac = $model_congtac->where('mact',$ct->mact)->first();
                    $ct->macongtac=isset($congtac->macongtac) ? $congtac->macongtac : null;
                }
                $model_bangluong_ct = $model_bangluong_ct->where('macongtac','BIENCHE');
            }
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

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

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
                if(isset($chitiet)>0){
                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];

                    $ar_I[$i]['ttbh_dv'] = round(($chitiet->sum('ttbh_dv') /1210000 ) * $luongcb);
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
                    $ar_I[$i]['chenhlech'] = round(($tongpc +$ar_I[$i]['ttbh_dv'] +$ar_I[$i]['luong'])*90000/1300000);
                    $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                }else{
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
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] +$ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] +$ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] +$ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] +$ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] +$ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] +$ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] +$ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] +$ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] +$ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] +$ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] +$ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] +$ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] +$ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] +$ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] +$ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] +$ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] +$ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] +$ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] +$ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] +$ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] +$ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] +$ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] +$ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] +$ar_I[2]['ttbh_dv'];
            //dd($ar_I);

            $ar_II = array();
           if(session('admin')->maphanloai == 'KVXP' && isset($model_bangluong_ct)){
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso') * $luongcb;
                $ar_II['ttbh_dv'] = round(($model_bangluong_ct->sum('ttbh_dv') /1210000 ) * $luongcb);

                $ar_II['pckv'] = $model_bangluong_ct->sum('pckv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $model_bangluong_ct->sum('pccv') * $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pctnvk'] = $model_bangluong_ct->sum('pctnvk') * $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $model_bangluong_ct->sum('pcudn') * $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $model_bangluong_ct->sum('pcth') * $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $model_bangluong_ct->sum('pctn') * $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $model_bangluong_ct->sum('pccovu') * $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $model_bangluong_ct->sum('pcdang') * $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $model_bangluong_ct->sum('pcthni') * $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $model_bangluong_ct->sum('pck') * $luongcb;
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

               $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/1300000);

           }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='H'){
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[2]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[2]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
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
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
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

            return view('reports.thongtu67.donvi.mau2a2')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2b_donvi() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('tt'=>'3','noidung'=>'Các chức danh còn lại');

            return view('reports.thongtu67.mau2b_tt68')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2c_donvi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_bangluong = bangluong::where('thang','07')->where('nam','2017')
                ->where('madv',session('admin')->madv)->first();
            $model_bienche = chitieubienche::where('nam','2017')->where('madv',session('admin')->madv)->first();
            $luongcb = 1150000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            if(isset($model_bangluong)){
                //$luongcb = $model_bangluong->luongcoban;
                $model_congtac = dmphanloaict::all();
                //$model_bangluong_ct = bangluong_ct::where('mabl',$model_bangluong->mabl)->get();
                $model_bangluong_ct = (new data())->getBangluong_ct($model_bangluong->thang,$model_bangluong->mabl);
                foreach($model_bangluong_ct as $ct){
                    //$ct->luongcb = $model_bangluong->luongcoban;
                    $ct->linhvuchoatdong=$model_bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN
                    $congtac = $model_congtac->where('mact',$ct->mact)->first();
                    $ct->macongtac=isset($congtac->macongtac) ? $congtac->macongtac : null;
                }
                $model_bangluong_ct = $model_bangluong_ct->where('macongtac','BIENCHE')->where('heso','<=','2.34');
            }
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $a_It = array('luong' => 0,
                'phucap' => 0,
                'soluong' => 0,
                'chenhlech'=>0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
                $ar_I[$i]['soluong'] = 0;
                $ar_I[$i]['chenhlech'] = 0;
                if(isset($chitiet) && count($chitiet)>0){
                    $ar_I[$i]['soluong'] = $chitiet->count();

                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];
                    $tongpc += $chitiet->sum('pckv')* $luongcb;
                    $tongpc += $chitiet->sum('pccv') * $luongcb;
                    $tongpc += $chitiet->sum('pctnvk') * $luongcb;
                    $tongpc += $chitiet->sum('pcudn') * $luongcb;
                    $tongpc += $chitiet->sum('pcth') * $luongcb;
                    $tongpc += $chitiet->sum('pctn') * $luongcb;
                    $tongpc += $chitiet->sum('pccovu') * $luongcb;
                    $tongpc += $chitiet->sum('pcdang') * $luongcb;
                    $tongpc += $chitiet->sum('pcthni') * $luongcb;
                    $tongpc += $chitiet->sum('pck') * $luongcb;

                    $ar_I[$i]['chenhlech'] = (($tongpc + $a_It['luong'])/$luongcb)*60000;
                    $ar_I[$i]['phucap'] = $tongpc;
                    $a_It['phucap'] += $ar_I[$i]['phucap'];
                }else{
                    $ar_I[$i]['phucap'] = 0;
                    $ar_I[$i]['luong'] = 0;
                }
            }
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['phucap'] = $ar_I[12]['phucap'] +$ar_I[13]['phucap'];
            $ar_I[11]['soluong'] = $ar_I[12]['soluong'] +$ar_I[13]['soluong'];
            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] +$ar_I[13]['chenhlech'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['phucap'] = $ar_I[1]['phucap'] +$ar_I[2]['phucap'];
            $ar_I[0]['soluong'] = $ar_I[1]['soluong'] +$ar_I[2]['soluong'];
            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] +$ar_I[2]['chenhlech'];

            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluong'] = 0;
            $ar_II['chenhlech'] = 0;
            if(session('admin')->maphanloai == 'KVXP' && isset($model_bangluong_ct)){
                $ar_II['soluong'] = $model_bangluong_ct->count();
                $ar_II['luong'] = $model_bangluong_ct->sum('heso') * $luongcb;

                $tongpc = 0;
                $tongpc += $model_bangluong_ct->sum('pckv') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pccv') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pctnvk') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pcudn') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pcth') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pctn') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pccovu') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pcdang') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pcthni') * $luongcb;
                $tongpc += $model_bangluong_ct->sum('pck') * $luongcb;
                $ar_II['phucap'] = $tongpc;
                $ar_II['chenhlech'] = (($tongpc + $ar_II['luong'])/$luongcb)*60000;
            }else{
                $ar_II['luong'] = 0;
                $ar_II['phucap'] = 0;
            }
            //dd($ar_II);

            return view('reports.thongtu67.donvi.mau2c')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('a_It',$a_It)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2d_donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $inputs['donvitinh'] = 2;
            //dd($inputs);
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_thon = dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                ->select('dmdiabandbkk.id', 'phanloai')
                ->where('maphanloai', 'KVXP')
                ->get();

            $ar_I = array();
            $ar_I[] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn');
            $ar_I[] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I');
            $ar_I[] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II');
            $ar_I[] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III');
            $ar_I[] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tổ dân phố');
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

            $a_It = array('tdv' => 0,
                'mk' => 0,
                'kp' => 0,
                'bhxh' => 0
            );

            for ($i = 0; $i < count($ar_I); $i++) {
                if (isset($m_thon)) {
                    $chitiet = $m_thon->where('phanloai', $ar_I[$i]['val']);
                }

                if (isset($chitiet) > 0) {
                    $tongdv = 0;
                    $tongkp = 0;
                    $tongbh = 0;
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
                    $ar_I[$i]['kp'] = $a_It['tdv'] * $kpk * 6 * 0.09;
                    $a_It['kp'] += $ar_I[$i]['kp'];
                    $ar_I[$i]['bhxh'] = 0;
                    //$ar_I[$i]['bh'] = $m_dt->where('phanloai',$ar_I[$i]['val'])*0.14*0.09*6;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];

                } else {
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['kp'] = 0;
                    $ar_I[$i]['bh'] = 0;
                }
            }

            return view('reports.thongtu67.Mau2d_tt68m')
                ->with('m_dv', $m_dv)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2e_donvi() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('tt'=>'3','noidung'=>'Các chức danh còn lại');

            return view('reports.thongtu67.Mau2e_ThKPTG')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2g_donvi() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('tt'=>'3','noidung'=>'Các chức danh còn lại');

            return view('reports.thongtu67.Mau2g_ThPCUDTG')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2h_donvi() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('tt'=>'3','noidung'=>'Các chức danh còn lại');

            return view('reports.thongtu67.Mau2h_ThPCTHTG')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau4a_donvi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('madv',session('admin')->madv)
                ->where('sohieu','TT67_2017')->first();

            if(count($model) == 0){
                return view('errors.nodata');
            }
            $m_dv = dmdonvi::where('madv',$model->madv)->first();


            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2016 so dự toán Thủ tướng Chính phủ giao năm 2016','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo','sotien'=>'0');
            $a_A[4] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[5] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[6] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[7] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo','sotien'=>'0');
            $a_A[8] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[9] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[10] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[11] = array('tt'=>'4','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2016 chưa sử dụng hết chuyển sang 2017','sotien'=>'0');

            //
            $a_BI = array();
            $a_BI[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BI[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo ','sotien'=>'0');
            $a_BI[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BI[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BI[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 76/2017/NĐ-CP','sotien'=>'0');
            $a_BI[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BI[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BI[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 3115-QĐ/VVPTW','sotien'=>'0');

            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Kinh phí hỗ trợ chênh lệch tiền lương cho người có thu nhập thấp (NĐ17/2015/NĐ-CP) và mức lương 1,21 (6 tháng)','sotien'=>'0');
            $a_BII[1] = array('tt'=>'2','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ','sotien'=>'0');
            $a_BII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2017 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BII[3] = array('tt'=>'4','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2017 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');

            //Tính toán
            $a_A[1]['sotien'] = $model->tietkiem;
            if($m_dv->phanloainguon == 'CHITXDT' || $m_dv->phanloainguon == 'CTX'){
                $a_A[4]['sotien'] =$model->hocphi;
                $a_A[5]['sotien'] =$model->vienphi;
                $a_A[6]['sotien'] =$model->nguonthu;
                $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
                $a_BI[1]['sotien'] = $model->luongphucap;
            }else{
                $a_A[8]['sotien'] =$model->hocphi;
                $a_A[9]['sotien'] =$model->vienphi;
                $a_A[10]['sotien'] =$model->nguonthu;
                $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            }
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];
            if($m_dv->maphanloai == 'KVXP'){
                $a_BI[1]['sotien'] == 0; //vì đã tính toán ơ trên
                $a_BI[2]['sotien'] = $model->luongphucap;
            }else{
                $a_BI[0]['sotien'] = $model->luongphucap;
            }
            $a_BI[3]['sotien'] = $model->daibieuhdnd;
            $a_BI[4]['sotien'] = $model->nghihuu;
            $a_BI[5]['sotien'] = $model->canbokct;
            $a_BI[6]['sotien'] = $model->uyvien;
            $a_BI[7]['sotien'] = $model->boiduong;

            $a_BII[0]['sotien'] = $model->thunhapthap;
            $a_BII[1]['sotien'] = $model->diaban;
            $a_BII[2]['sotien'] = $model->tinhgiam;
            $a_BII[3]['sotien'] = $model->nghihuusom;

            $a_TC = array(
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BI'=>(array_sum(array_column($a_BI,'sotien')) - $a_BI[1]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')))
            );

            return view('reports.thongtu67.donvi.mau4a')
                ->with('model',$model)
                ->with('a_A',$a_A)
                ->with('a_BI',$a_BI)
                ->with('a_BII',$a_BII)
                ->with('a_TC',$a_TC)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b_donvi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('madv',session('admin')->madv)
                ->where('sohieu','TT67_2017')->first();

            if(count($model) == 0){
                return view('errors.nodata');
            }
            $m_dv = dmdonvi::where('madv',$model->madv)->first();

            $data = array();
            $data[]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT
            $khac = false;
            for($i=0;$i<count($data);$i++){
                if($data[$i]['val'] == $model->linhvuchoatdong){
                    $data[$i]['nhucau'] = $model->nhucau;
                    $data[$i]['nguonkp'] = $model->nguonkp;
                    $data[$i]['tietkiem'] = $model->tietkiem;
                    $data[$i]['hocphi'] = $model->hocphi;
                    $data[$i]['vienphi'] = $model->vienphi;
                    $data[$i]['nguonthu'] = $model->nguonthu;

                    $khac = true;
                }
            }
            $data[0]['nhucau'] = $data[1]['nhucau']+$data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;

            if(!$khac){
                $data[4]['nhucau'] = $model->nhucau;
                $data[4]['nguonkp'] = $model->nguonkp;
                $data[4]['tietkiem'] = $model->tietkiem;
                $data[4]['hocphi'] = $model->hocphi;
                $data[4]['vienphi'] = $model->vienphi;
                $data[4]['nguonthu'] = $model->nguonthu;
            }

            return view('reports.thongtu67.donvi.mau4b')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau2a1_khoi() {
        if (Session::has('admin')) {
            $macqcq = session('admin')->madv;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();

            $model_bienche = chitieubienche::where('nam','2017')->wherein('madv',function($qr)use($macqcq){
                $qr->select('madv')->from('dmdonvi')->where('macqcq',$macqcq)->distinct()->get();
            })->get();

            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);
            $model_tonghop = tonghopluong_donvi::where('thang','07')->where('nam','2017')
                ->where('macqcq',session('admin')->madv)->get();
            $luongcb = 0;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr) use($macqcq){
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','07')->where('nam','2017')
                    ->where('macqcq',$macqcq)->distinct()->get();
            })->get();
            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('madvth',$ct->madvth)->first();
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE');

            //dd($model_tonghop_ct);
            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

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
                'soluongbienche'=> 0,
            );
            //thiếu chỉ tiêu biên chế
            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }

                $ar_I[$i]['soluongduocgiao'] = 0;
                $ar_I[$i]['soluongbienche'] = 0;
                if(isset($chitiet) && count($chitiet)>0){
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
                }else{
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
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] +$ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] +$ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] +$ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] +$ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] +$ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] +$ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] +$ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] +$ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] +$ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] +$ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] +$ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] +$ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] +$ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] +$ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] +$ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] +$ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] +$ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] +$ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] +$ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] +$ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] +$ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] +$ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] +$ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] +$ar_I[2]['ttbh_dv'];
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai','KVXP');
            //
            if(count($model_bangluong_ct)>0){
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
            }
            else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0);
            $a_IVt = array('tongso'=>0);
            //dd(session('admin')->maphanloai);
            if(session('admin')->level=='H'){
                if($m_dv->capdonvi > 2){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];

                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }

            return view('reports.thongtu67.khoi.mau2a1_tt68')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_khoi() {
        if (Session::has('admin')) {
            $macqcq = session('admin')->madv;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();
            $model_tonghop = tonghopluong_donvi::where('thang','07')->where('nam','2017')
                ->where('macqcq',session('admin')->madv)->get();
            $luongcb = 1300000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2018 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr) use($macqcq){
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','07')->where('nam','2017')
                    ->where('macqcq',$macqcq)->distinct()->get();
            })->get();
            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('madvth',$ct->madvth)->first();
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;

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
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE');
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

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

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }

                if(count($chitiet->toarray())>0){
                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso' * $luongcb);
                    $a_It['luong'] += $ar_I[$i]['luong'];

                    $ar_I[$i]['ttbh_dv'] = round($chitiet->sum('ttbh_dv')  * $luongcb);
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
                    $ar_I[$i]['chenhlech'] = round(($tongpc +$ar_I[$i]['ttbh_dv'] +$ar_I[$i]['luong'])*90000/ $luongcb);
                    $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                }else{
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
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] +$ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] +$ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] +$ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] +$ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] +$ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] +$ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] +$ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] +$ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] +$ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] +$ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] +$ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] +$ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] +$ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] +$ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] +$ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] +$ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] +$ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] +$ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] +$ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] +$ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] +$ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] +$ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] +$ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] +$ar_I[2]['ttbh_dv'];
            //dd($ar_I);

            $ar_II = array();
            if(isset($model_bangluong_ct)){
                $chitiet = $model_bangluong_ct->where('maphanloai', 'KVXP');
            }
            //dd($chitiet);
            if(isset($chitiet)){
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso')* $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk')* $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn')* $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth')* $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn')* $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu')* $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang')* $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni')* $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/$luongcb);

            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='H'){
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[2]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[2]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
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
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
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
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
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
            $model_tonghop_ct = (new data())->getBangluong_ct_ar('07',a_unique(array_column($model_tonghop->toarray(),'mabl')));

            /*
            $model_tonghop_ct = bangluong_ct::wherein('mabl',function($qr) use($macqcq){
                $qr->select('mabl')->from('bangluong')->where('thang','07')->where('nam','2017')
                    ->wherein('madv',function($q) use($macqcq){
                        $q->select('madv')->from('dmdonvi')->where('macqcq',$macqcq)->get();
                    })->get();
            })->get();
            */
            foreach($model_tonghop_ct as $ct){
                //$ct->luongcb = $model_bangluong->luongcoban;

                $congtac = $model_congtac->where('mact',$ct->mact)->first();
                $ct->macongtac=isset($congtac->macongtac) ? $congtac->macongtac : null;

                $tonghop = $model_tonghop->where('mabl',$ct->mabl)->first();
                $ct->linhvuchoatdong=$tonghop->linhvuchoatdong;//chỉ dùng cho khối HCSN
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('heso','<=','2.34');

            //dd($model_bangluong_ct->toarray());

            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $a_It = array('luong' => 0,
                'phucap' => 0,
                'soluong' => 0,
                'chenhlech'=>0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
                $ar_I[$i]['soluong'] = 0;
                $ar_I[$i]['chenhlech'] = 0;
                if(isset($chitiet) && count($chitiet)>0){
                    $ar_I[$i]['soluong'] = $chitiet->count();

                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];
                    $tongpc += $chitiet->sum('pckv')* $luongcb;
                    $tongpc += $chitiet->sum('pccv') * $luongcb;
                    $tongpc += $chitiet->sum('pctnvk') * $luongcb;
                    $tongpc += $chitiet->sum('pcudn') * $luongcb;
                    $tongpc += $chitiet->sum('pcth') * $luongcb;
                    $tongpc += $chitiet->sum('pctn') * $luongcb;
                    $tongpc += $chitiet->sum('pccovu') * $luongcb;
                    $tongpc += $chitiet->sum('pcdang') * $luongcb;
                    $tongpc += $chitiet->sum('pcthni') * $luongcb;
                    $tongpc += $chitiet->sum('pck') * $luongcb;

                    $ar_I[$i]['chenhlech'] = (($tongpc + $a_It['luong'])/$luongcb)*60000;
                    $ar_I[$i]['phucap'] = $tongpc;
                    $a_It['phucap'] += $ar_I[$i]['phucap'];
                }else{
                    $ar_I[$i]['phucap'] = 0;
                    $ar_I[$i]['luong'] = 0;
                }
            }
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['phucap'] = $ar_I[12]['phucap'] +$ar_I[13]['phucap'];
            $ar_I[11]['soluong'] = $ar_I[12]['soluong'] +$ar_I[13]['soluong'];
            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] +$ar_I[13]['chenhlech'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['phucap'] = $ar_I[1]['phucap'] +$ar_I[2]['phucap'];
            $ar_I[0]['soluong'] = $ar_I[1]['soluong'] +$ar_I[2]['soluong'];
            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] +$ar_I[2]['chenhlech'];

            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluong'] = 0;
            $ar_II['chenhlech'] = 0;
            if(isset($model_bangluong_ct)){
                $chitiet = $model_bangluong_ct->where('maphanloai','KVXP');
            }
            if(count($chitiet)>0){
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
                $ar_II['chenhlech'] = (($tongpc + $ar_II['luong'])/$luongcb)*60000;
            }else{
                $ar_II['luong'] = 0;
                $ar_II['phucap'] = 0;
            }
            //dd($ar_II);

            return view('reports.thongtu67.khoi.mau2c_tt68')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('a_It',$a_It)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau4a_khoi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('macqcq',session('admin')->madv)
                ->where('sohieu','TT67_2017')->get();
            $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            if(count($model) == 0){
                return view('errors.nodata');
            }
            foreach($model as $ct){
                $donvi = $model_donvi->where('madv',$ct->madv)->first();
                $ct->phanloainguon = $donvi->phanloainguon;
                $ct->maphanloai = $donvi->maphanloai;
            }
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2017 so dự toán Thủ tướng Chính phủ giao năm 2017','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2018 so dự toán 2017 Thủ tướng Chính phủ giao','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'4','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2018','sotien'=>'0');
            $a_A[4] = array('tt'=>'5','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2018:','sotien'=>'0');
            $a_A[5] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo(1):','sotien'=>'0');
            $a_A[6] = array('tt'=>'','noidung'=>'+ Học phí','sotien'=>'0');
            $a_A[7] = array('tt'=>'','noidung'=>'+ Viện phí','sotien'=>'0');
            $a_A[8] = array('tt'=>'','noidung'=>'+ Nguồn thu khác','sotien'=>'0');
            $a_A[9] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo chi thường xuyên:','sotien'=>'0');
            $a_A[10] = array('tt'=>'','noidung'=>'+ Học phí','sotien'=>'0');
            $a_A[11] = array('tt'=>'','noidung'=>'+ Viện phí','sotien'=>'0');
            $a_A[12] = array('tt'=>'','noidung'=>'+ Nguồn thu khác','sotien'=>'0');
            $a_A[13] = array('tt'=>'6','noidung'=>'Nguồn tiết kiệm chi gắn với thực hiện các giải pháp sắp xếp tổ chức bộ máy, tinh giản biên chế, đổi mới hoạt động đơn vị sự nghiệp công lập theo Nghị quyết số 18, 19 (nếu có)','sotien'=>'0');
            $a_A[14] = array('tt'=>'','noidung'=>'+ Từ việc tinh giản biên chế tổ chức lại bộ máy (2)','sotien'=>'0');
            $a_A[15] = array('tt'=>'','noidung'=>'+ Từ việc sát nhập các đầu mối, cơ quan, đơn vị (2)','sotien'=>'0');
            $a_A[16] = array('tt'=>'','noidung'=>'+ Từ việc thay đổi cơ chế tự chủ của đơn vị sư nghiệp (3)','sotien'=>'0');
            $a_A[17] = array('tt'=>'','noidung'=>'+ Từ việc sát nhập các xã không đủ điều kiện tiêu chuẩn','sotien'=>'0');
            $a_A[18] = array('tt'=>'7','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2017 chưa sử dụng hết chuyển sang năm 2018','sotien'=>'0');

            //
            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BII[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)','sotien'=>'0');
            $a_BII[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BII[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BII[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 88/2018/NĐ-CP','sotien'=>'0');
            $a_BII[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BII[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BII[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017','sotien'=>'0');

            $a_BIII = array();
            $a_BIII[0] = array('tt'=>'1','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)','sotien'=>'0');
            $a_BIII[1] = array('tt'=>'2','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2018 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BIII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2018 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon',array('CHITXDT','CTX'));
            $a_A[4]['sotien'] =$model_tudb->sum('hocphi');
            $a_A[5]['sotien'] =$model_tudb->sum('vienphi');
            $a_A[6]['sotien'] =$model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
            //$a_BII[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi')- $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu')- $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai','KVXP');

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
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')) - $a_BII[1]['sotien']),
                'BIII'=>(array_sum(array_column($a_BIII,'sotien')))
            );
            return view('reports.thongtu67.khoi.mau4a_tt68')
                ->with('model',$model)
                ->with('a_A',$a_A)
                ->with('a_BII',$a_BII)
                ->with('a_BIII',$a_BIII)
                ->with('a_TC',$a_TC)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b_khoi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('macqcq',session('admin')->madv)
                ->where('sohieu','TT67_2017')->get();
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $data[0]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            $data[1]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            $data[2]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            $data[3]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            $data[4]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            $data[5]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            $data[6]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'khac'=>0,'nguonthu'=>0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            for($i=0;$i<count($data);$i++){
                $solieu =  $model->where('linhvuchoatdong',$data[$i]['val']);
                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                $data[$i]['khac'] = 0;
                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
            }
            $data[0]['nhucau'] = $data[1]['nhucau']+$data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['khac'] = 0;
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
            $data[4]['nguonkp'] = $model->sum('nguonkp')-  $data[0]['nguonkp']- $data[5]['nguonkp']- $data[3]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem']- $data[5]['tietkiem']- $data[3]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi']- $data[5]['hocphi']- $data[3]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi']- $data[5]['vienphi']- $data[3]['vienphi'];
            $data[4]['khac'] = 0;
            $data[4]['nguonthu'] = $model->sum('nguonthu')- $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];

            return view('reports.thongtu67.khoi.mau4b_tt68')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

        function mau2a1_huyen(Request $request) {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $model_donvi = dmdonvi::where('macqcq',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madv) {
                        $qr->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct()->get();
                    })->get();
                }

                else
                {
                    $model_donvi = dmdonvi::where('madv',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->where('madv', $madv)->get();
                }
            }
            else {
                $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
                })->get();
            }
            //dd($model_donvi->toarray());
            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            if($inputs['madv'] !="")
            {
                if(count($chekdv) > 0) {
                    $model_tonghop = tonghopluong_huyen::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.macqcq', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                }
                else {
                    $model_tonghop = tonghopluong_donvi::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.madv', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                }
            }
            else
            {
                $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                    ->where('madvbc',$madvbc)->get();
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->select('tonghopluong_donvi_chitiet.linhvuchoatdong','tonghopluong_donvi_chitiet.heso','tonghopluong_donvi_chitiet.pckv','tonghopluong_donvi_chitiet.pccv',
                        'tonghopluong_donvi_chitiet.pctnvk','tonghopluong_donvi_chitiet.pcudn','pcth','pctn','pccovu','pcdang',
                        'pcthni','pck','nguoigui','madv','tonghopluong_donvi.mathh','tonghopluong_donvi.mathdv','macongtac')
                    ->where('tonghopluong_donvi.madvbc',$madvbc)
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })
                    ->groupby('linhvuchoatdong','nguoigui','madv','mathh','mathdv','macongtac')
                    ->get();
                if(isset($inputs['inheso'])) {
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_chitiet.linhvuchoatdong',DB::raw('heso/luongcoban as heso'),DB::raw('pckv/luongcoban as pckv'),DB::raw('pccv/luongcoban as pccv'),
                            DB::raw('pctnvk/luongcoban as pctnvk'),DB::raw('pcudn/luongcoban as pcudn'),DB::raw('pcth/luongcoban as pcth'),DB::raw('pctn/luongcoban as pctn'),DB::raw('pccovu/luongcoban as pccovu'),DB::raw('pcdang/luongcoban as pcdang'),
                            DB::raw('pcthni/luongcoban as pcthni'),DB::raw('pck/luongcoban as pck'),'nguoigui','madv','tonghopluong_donvi.mathh','tonghopluong_donvi.mathdv','macongtac')
                        ->where('tonghopluong_donvi.madvbc',$madvbc)
                        ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                                ->distinct()->get();
                        })
                        ->groupby('linhvuchoatdong','nguoigui','madv','mathh','mathdv','macongtac')
                        ->get();
                }
            }
            $luongcb = 0.935;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop_ct->toarray());

            if(session('admin')->username == 'khthso')
            {
                $model_bienche = chitieubienche::join('dmdonvi','dmdonvi.madv','=','chitieubienche.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->where('chitieubienche.nam','2018')->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            foreach($model_tonghop_ct as $ct){
                if($inputs['madv'] !="" && count($chekdv) > 0){
                    $tonghop = $model_tonghop->where('mathdv',$ct->mathh)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                }
                else {
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
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv +$ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;

            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>','KVXP');

            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
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
            }
            else {
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
                'soluongbienche'=> 0,
            );
            //thiếu chỉ tiêu biên chế
            if(isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt =0;
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
                    $ar_I[$i]['pctn'] = 0;
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
                            $ar_I[$i]['luong'] = $thongtinchitiet['heso'];
                            $a_It['luong'] += $ar_I[$i]['luong'];

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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'];
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                            $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);

                            $ar_I[$luugr]['luong'] += $ar_I[$i]['luong'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
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

                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
                $ar_I[$gddt]['pccovu'] = $ar_I[$giaoduc]['pccovu'] + $ar_I[$daotao]['pccovu'];
                $ar_I[$gddt]['pcdang'] = $ar_I[$giaoduc]['pcdang'] + $ar_I[$daotao]['pcdang'];
                $ar_I[$gddt]['pcthni'] = $ar_I[$giaoduc]['pcthni'] + $ar_I[$daotao]['pcthni'];
                $ar_I[$gddt]['pck'] = $ar_I[$giaoduc]['pck'] + $ar_I[$daotao]['pck'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];

                $ar_I[$qlnnddt]['luong'] = $ar_I[$qlnn]['luong'] + $ar_I[$ddt]['luong'];
                $ar_I[$qlnnddt]['pckv'] = $ar_I[$qlnn]['pckv'] + $ar_I[$ddt]['pckv'];
                $ar_I[$qlnnddt]['pccv'] = $ar_I[$qlnn]['pccv'] + $ar_I[$ddt]['pccv'];
                $ar_I[$qlnnddt]['pctnvk'] = $ar_I[$qlnn]['pctnvk'] + $ar_I[$ddt]['pctnvk'];
                $ar_I[$qlnnddt]['pcudn'] = $ar_I[$qlnn]['pcudn'] + $ar_I[$ddt]['pcudn'];
                $ar_I[$qlnnddt]['pcth'] = $ar_I[$qlnn]['pcth'] + $ar_I[$ddt]['pcth'];
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];

            }
            else {
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
                        $ar_I[$i]['luong'] = $chitiet->sum('heso');
                        $a_It['luong'] += $ar_I[$i]['luong'];

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

                        $ar_I[$i]['pctn'] = $chitiet->sum('pctn');
                        $tongpc += $ar_I[$i]['pctn'];
                        $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                        $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);
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
            }
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai','KVXP');
            //dd($model_bangluong_ct);
            //
            if(count($model_bangluong_ct)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso');

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

                $ar_II['ttbh_dv'] =$model_bangluong_ct->sum('ttbh_dv') ;
            }
            else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0);
            $a_IVt = array('tongso'=>0);
            //dd(session('admin')->maphanloai);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi > 2){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];

                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }

            return view('reports.thongtu67.mau2a1_tt68')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a1_huyen_excel(Request $request) {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $model_donvi = dmdonvi::where('macqcq',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madv) {
                        $qr->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct()->get();
                    })->get();
                }

                else
                {
                    $model_donvi = dmdonvi::where('madv',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->where('madv', $madv)->get();
                }
            }
            else {
                $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
                })->get();
            }
            //dd($model_donvi->toarray());
            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            if($inputs['madv'] !="")
            {
                if(count($chekdv) > 0) {
                    $model_tonghop = tonghopluong_huyen::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.macqcq', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                }
                else {
                    $model_tonghop = tonghopluong_donvi::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.madv', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                    dd($model_tonghop_ct->toarray());
                }
            }
            else
            {
                $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                    ->where('madvbc',$madvbc)->get();
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->where('tonghopluong_donvi.madvbc',$madvbc)
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }

            $luongcb = 0.935;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop_ct->toarray());

            if(session('admin')->username == 'khthso')
            {
                $model_bienche = chitieubienche::join('dmdonvi','dmdonvi.madv','=','chitieubienche.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->where('chitieubienche.nam','2018')->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            foreach($model_tonghop_ct as $ct){
                if($inputs['madv'] !="" && count($chekdv) > 0){
                    $tonghop = $model_tonghop->where('mathdv',$ct->mathh)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                }
                else {
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
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv +$ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;

            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>','KVXP');

            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
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
            }
            else {
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
                'soluongbienche'=> 0,
            );
            //thiếu chỉ tiêu biên chế
            if(isset($inputs['inchitiet'])) {
                $gddt = 0;
                $daotao = 0;
                $giaoduc = 0;
                $qlnnddt = 0;
                $qlnn = 0;
                $ddt =0;
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
                    $ar_I[$i]['pctn'] = 0;
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
                            $ar_I[$i]['luong'] = $thongtinchitiet['heso'];
                            $a_It['luong'] += $ar_I[$i]['luong'];

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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'];
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                            $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                            $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);

                            $ar_I[$luugr]['luong'] += $ar_I[$i]['luong'];
                            $ar_I[$luugr]['pckv'] += $ar_I[$i]['pckv'];
                            $ar_I[$luugr]['pccv'] += $ar_I[$i]['pccv'];
                            $ar_I[$luugr]['pctnvk'] += $ar_I[$i]['pctnvk'];
                            $ar_I[$luugr]['pcudn'] += $ar_I[$i]['pcudn'];
                            $ar_I[$luugr]['pcth'] += $ar_I[$i]['pcth'];
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
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

                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
                $ar_I[$gddt]['pccovu'] = $ar_I[$giaoduc]['pccovu'] + $ar_I[$daotao]['pccovu'];
                $ar_I[$gddt]['pcdang'] = $ar_I[$giaoduc]['pcdang'] + $ar_I[$daotao]['pcdang'];
                $ar_I[$gddt]['pcthni'] = $ar_I[$giaoduc]['pcthni'] + $ar_I[$daotao]['pcthni'];
                $ar_I[$gddt]['pck'] = $ar_I[$giaoduc]['pck'] + $ar_I[$daotao]['pck'];
                $ar_I[$gddt]['tongpc'] = $ar_I[$giaoduc]['tongpc'] + $ar_I[$daotao]['tongpc'];
                $ar_I[$gddt]['ttbh_dv'] = $ar_I[$giaoduc]['ttbh_dv'] + $ar_I[$daotao]['ttbh_dv'];

                $ar_I[$qlnnddt]['luong'] = $ar_I[$qlnn]['luong'] + $ar_I[$ddt]['luong'];
                $ar_I[$qlnnddt]['pckv'] = $ar_I[$qlnn]['pckv'] + $ar_I[$ddt]['pckv'];
                $ar_I[$qlnnddt]['pccv'] = $ar_I[$qlnn]['pccv'] + $ar_I[$ddt]['pccv'];
                $ar_I[$qlnnddt]['pctnvk'] = $ar_I[$qlnn]['pctnvk'] + $ar_I[$ddt]['pctnvk'];
                $ar_I[$qlnnddt]['pcudn'] = $ar_I[$qlnn]['pcudn'] + $ar_I[$ddt]['pcudn'];
                $ar_I[$qlnnddt]['pcth'] = $ar_I[$qlnn]['pcth'] + $ar_I[$ddt]['pcth'];
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];

            }
            else {
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
                        $ar_I[$i]['luong'] = $chitiet->sum('heso');
                        $a_It['luong'] += $ar_I[$i]['luong'];

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

                        $ar_I[$i]['pctn'] = $chitiet->sum('pctn');
                        $tongpc += $ar_I[$i]['pctn'];
                        $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                        $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv']) * 0.24);
                        $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                        //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);
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
            }
            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai','KVXP');
            //dd($model_bangluong_ct);
            //
            if(count($model_bangluong_ct)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso');

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

                $ar_II['ttbh_dv'] =$model_bangluong_ct->sum('ttbh_dv') ;
            }
            else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0);
            $a_IVt = array('tongso'=>0);
            //dd(session('admin')->maphanloai);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi > 2){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];

                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }
            Excel::create('Mau2a1_TT67',function($excel) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau2a1_tt67excel')
                        ->with('ar_I',$ar_I)
                        ->with('ar_II',$ar_II)
                        ->with('ar_III',$ar_III)
                        ->with('ar_IV',$ar_IV)
                        ->with('a_It',$a_It)
                        ->with('a_IIIt',$a_IIIt)
                        ->with('a_IVt',$a_IVt)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2a1_TT67');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2a1_huyencu(Request $request) {

        if (Session::has('admin')) {
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();

            $model_bienche = chitieubienche::where('nam','2018')->wherein('madv',function($qr)use($madvbc){
                $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc)->distinct()->get();
            })->get();

            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                ->where('madvbc',$madvbc)->get();

            $luongcb = 0.935;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr){
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                    ->where('macqcq',session('admin')->madv)->get();
            })->get();

            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('mathdv',$ct->mathdv)->first();
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
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
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv +$ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;
                /*
                $ct->heso = ($ct->heso / $ct->luongcoban)* $luongcb;
                $ct->pckv = ($ct->pckv / $ct->luongcoban)* $luongcb;
                $ct->pccv = ($ct->pccv / $ct->luongcoban)* $luongcb;
                $ct->pctnvk = ($ct->pctnvk / $ct->luongcoban)* $luongcb;
                $ct->pcudn = ($ct->pcudn / $ct->luongcoban)* $luongcb;
                $ct->pcth = ($ct->pcth / $ct->luongcoban)* $luongcb;
                $ct->pctn = ($ct->pctn / $ct->luongcoban)* $luongcb;
                $ct->pccovu = ($ct->pccovu / $ct->luongcoban)* $luongcb;
                $ct->pcdang = ($ct->pcdang / $ct->luongcoban)* $luongcb;
                $ct->pcthni = ($ct->pcthni / $ct->luongcoban)* $luongcb;
                $ct->pck = ($ct->pck / $ct->luongcoban)* $luongcb;
                $ct->pcdbqh = ($ct->pcdbqh / $ct->luongcoban)* $luongcb;
                $ct->pcvk = ($ct->pcvk / $ct->luongcoban)* $luongcb;
                */
            }

            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>','KVXP');

            //dd($model_bangluong_ct);
            $ar_I = array();
            $ar_I[0]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[1]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[2]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[3]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[4]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[5]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[6]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[7]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[8]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[9]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[10]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[11]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[12]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[13]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

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
                'soluongbienche'=> 0,
            );
            //thiếu chỉ tiêu biên chế
            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }

                $ar_I[$i]['soluongduocgiao'] = 0;
                $ar_I[$i]['soluongbienche'] = 0;
                if(isset($chitiet) && count($chitiet)>0){
                    //$ar_I[$i]['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
                    $a_It['soluongduocgiao'] += $ar_I[$i]['soluongduocgiao'];

                    //$ar_I[$i]['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
                    $a_It['soluongbienche'] += $ar_I[$i]['soluongbienche'];
                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso');
                    $a_It['luong'] += $ar_I[$i]['luong'];

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

                    $ar_I[$i]['pctn'] = $chitiet->sum('pctn');
                    $tongpc += $ar_I[$i]['pctn'];
                    $a_It['pctn'] += $ar_I[$i]['pctn'];

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

                    $ar_I[$i]['ttbh_dv'] = round(($ar_I[$i]['luong'] + $ar_I[$i]['pccv'])*0.24);
                    $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                    //$ar_II['ttbh_dv'] =round(($ar_II['luong'] + $ar_II['pccv'])*0.24);
                }else{
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

            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] +$ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] +$ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] +$ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] +$ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] +$ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] +$ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] +$ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] +$ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] +$ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] +$ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] +$ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] +$ar_I[13]['ttbh_dv'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] +$ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] +$ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] +$ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] +$ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] +$ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] +$ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] +$ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] +$ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] +$ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] +$ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] +$ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] +$ar_I[2]['ttbh_dv'];

            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;

            $model_bangluong_ct = $model_tonghop_ct->where('maphanloai','KVXP');
            //dd($model_bangluong_ct);
            //
            if(count($model_bangluong_ct)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['luong'] = $model_bangluong_ct->sum('heso');

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

                $ar_II['ttbh_dv'] =$model_bangluong_ct->sum('ttbh_dv') ;
            }
            else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0);
            $a_IVt = array('tongso'=>0);
            //dd(session('admin')->maphanloai);
            if(session('admin')->level=='H'){
                if($m_dv->capdonvi > 2){
                    if(isset($model_bangluong_ct)){
                        $ar_III[2]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[2]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];

                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }
                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh');
                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk');
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                }
            }

            return view('reports.thongtu67.huyen.mau2a1')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_huyen(Request $request) {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $model_donvi = dmdonvi::where('macqcq',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madv) {
                        $qr->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct()->get();
                    })->get();
                }

                else
                {
                    $model_donvi = dmdonvi::where('madv',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->where('madv', $madv)->get();
                }
            }
            else {
                $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
                })->get();
            }
            //dd($model_donvi->toarray());
            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            if($inputs['madv'] !="")
            {
                if(count($chekdv) > 0) {
                    $model_tonghop = tonghopluong_huyen::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')

                        ->where('tonghopluong_donvi.macqcq', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })
                        ->get();
                }
                else {
                    $model_tonghop = tonghopluong_donvi::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.madv', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                }
            }
            else
            {
                $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                    ->where('madvbc',$madvbc)->get();
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->select('tonghopluong_donvi_chitiet.linhvuchoatdong','tonghopluong_donvi_chitiet.heso','tonghopluong_donvi_chitiet.pckv','tonghopluong_donvi_chitiet.pccv',
                        'tonghopluong_donvi_chitiet.pctnvk','tonghopluong_donvi_chitiet.pcudn','pcth','pctn','pccovu','pcdang',
                        'pcthni','pck','nguoigui','madv','tonghopluong_donvi.mathh','tonghopluong_donvi.mathdv','macongtac')
                    ->where('tonghopluong_donvi.madvbc',$madvbc)
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })
                    ->groupby('linhvuchoatdong','nguoigui','madv','mathh','mathdv','macongtac')
                    ->get();
            }

            $luongcb = 1;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop_ct->toarray());

            if(session('admin')->username == 'khthso')
            {
                $model_bienche = chitieubienche::join('dmdonvi','dmdonvi.madv','=','chitieubienche.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->where('chitieubienche.nam','2018')->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            foreach($model_tonghop_ct as $ct){
                if($inputs['madv'] !="" && count($chekdv) > 0){
                    $tonghop = $model_tonghop->where('mathdv',$ct->mathh)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                }
                else {
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
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv +$ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;

            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>','KVXP');

            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
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
            if(isset($inputs['inchitiet'])) {
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
                    $ar_I[$i]['pctn'] = 0;
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
                    $d= 1;
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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'] * $luongcb;
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            $ar_I[$luugr]['chenhlech'] += $ar_I[$i]['chenhlech'];

                        }
                    }else {
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
                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
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
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                $ar_I[$qlnnddt]['chenhlech'] = $ar_I[$qlnn]['chenhlech'] + $ar_I[$ddt]['chenhlech'];
            }
            else {
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
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

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
            if(isset($chitiet)){
                $tongpc = 0;
                $ar_II['luong'] = $chitiet->sum('heso')* $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv')* $luongcb;
                $tongpc += $ar_II['pccv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk')* $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn')* $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth')* $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn')* $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu')* $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang')* $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni')* $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/1390000);

            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi >= 3){

                    $ar_III[2]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[2]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                    $ar_IV[2]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[2]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000;


                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{

                    $ar_III[1]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[1]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000/ 1390000;
                    $ar_IV[1]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[1]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000/ 1390000;


                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            //dd($ar_I);

            return view('reports.thongtu67.mau2a2_tt68')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');

    }

    function mau2a2_huyen_excel(Request $request) {
        if (Session::has('admin')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $model_donvi = dmdonvi::where('macqcq',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madv) {
                        $qr->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct()->get();
                    })->get();
                }

                else
                {
                    $model_donvi = dmdonvi::where('madv',$madv)->get();
                    $model_bienche = chitieubienche::where('nam', '2018')->where('madv', $madv)->get();
                }
            }
            else {
                $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
                $model_bienche = chitieubienche::where('nam', '2018')->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->distinct()->get();
                })->get();
            }
            //dd($model_donvi->toarray());
            foreach($model_bienche as $bienche){
                $bienche->maphanloai = $model_donvi->where('madv',$bienche->madv)->first()->maphanloai;
            }
            //dd($model_bienche);

            if($inputs['madv'] !="")
            {
                if(count($chekdv) > 0) {
                    $model_tonghop = tonghopluong_huyen::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.macqcq', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                }
                else {
                    $model_tonghop = tonghopluong_donvi::where('thang', '08')->where('nam', '2018')
                        ->where('madv', $inputs['madv'])->get();
                    $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                        ->where('tonghopluong_donvi.madv', $inputs['madv'])
                        ->wherein('tonghopluong_donvi_chitiet.mathdv', function ($qr) {
                            $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang', '08')->where('nam', '2018')->where('trangthai', 'DAGUI')
                                ->distinct()->get();
                        })->get();
                    dd($model_tonghop_ct->toarray());
                }
            }
            else
            {
                $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                    ->where('madvbc',$madvbc)->get();
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->where('tonghopluong_donvi.madvbc',$madvbc)
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }

            $luongcb = 1;
            //$luongcb = 1390000;

            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop_ct->toarray());

            if(session('admin')->username == 'khthso')
            {
                $model_bienche = chitieubienche::join('dmdonvi','dmdonvi.madv','=','chitieubienche.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->where('chitieubienche.nam','2018')->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')->get();
                //$luongcb = 1210000; tạm thời bỏ vì bang lương đã nhân lcb
                $luongcb = 0.935;
                //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
                $model_tonghop_ct = tonghopluong_donvi_chitiet::join('tonghopluong_donvi', 'tonghopluong_donvi_chitiet.mathdv', '=', 'tonghopluong_donvi.mathdv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','tonghopluong_donvi.madvbc')
                    ->where('tonghopluong_donvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->wherein('tonghopluong_donvi_chitiet.mathdv',function($qr){
                        $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                            ->distinct()->get();
                    })->get();
            }
            foreach($model_tonghop_ct as $ct){
                if($inputs['madv'] !="" && count($chekdv) > 0){
                    $tonghop = $model_tonghop->where('mathdv',$ct->mathh)->first();
                    $ct->maphanloai = $tonghop->maphanloai;
                }
                else {
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
                $ct->ttbh_dv = ($ct->stbhxh_dv + $ct->stbhyt_dv +$ct->stkpcd_dv + $ct->stbhtn_dv) * $luongcb;

            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>','KVXP');

            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
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
            if(isset($inputs['inchitiet'])) {
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
                    $ar_I[$i]['pctn'] = 0;
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
                    $d= 1;
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

                            $ar_I[$i]['pctn'] = $thongtinchitiet['pctn'] * $luongcb;
                            $tongpc += $ar_I[$i]['pctn'];
                            $a_It['pctn'] += $ar_I[$i]['pctn'];

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
                            $ar_I[$luugr]['pctn'] += $ar_I[$i]['pctn'];
                            $ar_I[$luugr]['pccovu'] += $ar_I[$i]['pccovu'];
                            $ar_I[$luugr]['pcdang'] += $ar_I[$i]['pcdang'];
                            $ar_I[$luugr]['pcthni'] += $ar_I[$i]['pcthni'];
                            $ar_I[$luugr]['pck'] += $ar_I[$i]['pck'];
                            $ar_I[$luugr]['tongpc'] += $ar_I[$i]['tongpc'];
                            $ar_I[$luugr]['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];
                            $ar_I[$luugr]['chenhlech'] += $ar_I[$i]['chenhlech'];

                        }
                    }else {
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
                $ar_I[$gddt]['luong'] = $ar_I[$giaoduc]['luong'] + $ar_I[$daotao]['luong'];
                $ar_I[$gddt]['pckv'] = $ar_I[$giaoduc]['pckv'] + $ar_I[$daotao]['pckv'];
                $ar_I[$gddt]['pccv'] = $ar_I[$giaoduc]['pccv'] + $ar_I[$daotao]['pccv'];
                $ar_I[$gddt]['pctnvk'] = $ar_I[$giaoduc]['pctnvk'] + $ar_I[$daotao]['pctnvk'];
                $ar_I[$gddt]['pcudn'] = $ar_I[$giaoduc]['pcudn'] + $ar_I[$daotao]['pcudn'];
                $ar_I[$gddt]['pcth'] = $ar_I[$giaoduc]['pcth'] + $ar_I[$daotao]['pcth'];
                $ar_I[$gddt]['pctn'] = $ar_I[$giaoduc]['pctn'] + $ar_I[$daotao]['pctn'];
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
                $ar_I[$qlnnddt]['pctn'] = $ar_I[$qlnn]['pctn'] + $ar_I[$ddt]['pctn'];
                $ar_I[$qlnnddt]['pccovu'] = $ar_I[$qlnn]['pccovu'] + $ar_I[$ddt]['pccovu'];
                $ar_I[$qlnnddt]['pcdang'] = $ar_I[$qlnn]['pcdang'] + $ar_I[$ddt]['pcdang'];
                $ar_I[$qlnnddt]['pcthni'] = $ar_I[$qlnn]['pcthni'] + $ar_I[$ddt]['pcthni'];
                $ar_I[$qlnnddt]['pck'] = $ar_I[$qlnn]['pck'] + $ar_I[$ddt]['pck'];
                $ar_I[$qlnnddt]['tongpc'] = $ar_I[$qlnn]['tongpc'] + $ar_I[$ddt]['tongpc'];
                $ar_I[$qlnnddt]['ttbh_dv'] = $ar_I[$qlnn]['ttbh_dv'] + $ar_I[$ddt]['ttbh_dv'];
                $ar_I[$qlnnddt]['chenhlech'] = $ar_I[$qlnn]['chenhlech'] + $ar_I[$ddt]['chenhlech'];
            }
            else {
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
                $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] + $ar_I[13]['chenhlech'];

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
            if(isset($chitiet)){
                $tongpc = 0;
                $ar_II['luong'] = $chitiet->sum('heso')* $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv')* $luongcb;
                $tongpc += $ar_II['pccv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk')* $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn')* $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth')* $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn')* $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu')* $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang')* $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni')* $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/1390000);

            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='T'){
                if($m_dv->capdonvi >= 3){

                    $ar_III[2]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[2]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                    $ar_IV[2]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[2]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000;


                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{

                    $ar_III[1]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                    $ar_III[1]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000/ 1390000;
                    $ar_IV[1]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                    $ar_IV[1]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000/ 1390000;


                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            Excel::create('mau2a2',function($excel) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$ar_II,$ar_III,$ar_IV,$a_It,$a_IIIt,$a_IVt,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau2a2excel')
                        ->with('ar_I',$ar_I)
                        ->with('ar_II',$ar_II)
                        ->with('ar_III',$ar_III)
                        ->with('ar_IV',$ar_IV)
                        ->with('a_It',$a_It)
                        ->with('a_IIIt',$a_IIIt)
                        ->with('a_IVt',$a_IVt)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2a2');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');

    }

    function mau2a2_huyencu() {
        if (Session::has('admin')) {
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_tonghop = tonghopluong_donvi::where('thang','08')->where('nam','2018')
                ->where('madvbc',$madvbc)->get();
            $luongcb = 1;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr){
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang','08')->where('nam','2018')->where('trangthai','DAGUI')
                    ->where('macqcq',session('admin')->madv)->get();
            })->get();

            foreach($model_tonghop_ct as $ct){
                $tonghop = $model_tonghop->where('mathdv',$ct->mathdv)->first();
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
                $ct->ttbh_dv = ($ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+$ct->stbhtn_dv);
            }

            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('maphanloai','<>', 'KVXP');
            //dd($model_bangluong_ct->toarray());
            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

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

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
                if(isset($chitiet)>0){
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

                    $ar_I[$i]['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);
                    $a_It['ttbh_dv'] += $ar_I[$i]['ttbh_dv'];

                    $ar_I[$i]['tongpc'] = $tongpc;
                    $a_It['tongpc'] += $ar_I[$i]['tongpc'];
                    $ar_I[$i]['chenhlech'] = round(($tongpc +$ar_I[$i]['ttbh_dv'] +$ar_I[$i]['luong'])*90000/ 1390000);
                    $a_It['chenhlech'] += $ar_I[$i]['chenhlech'];

                }else{
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
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['pckv'] = $ar_I[12]['pckv'] +$ar_I[13]['pckv'];
            $ar_I[11]['pccv'] = $ar_I[12]['pccv'] +$ar_I[13]['pccv'];
            $ar_I[11]['pctnvk'] = $ar_I[12]['pctnvk'] +$ar_I[13]['pctnvk'];
            $ar_I[11]['pcudn'] = $ar_I[12]['pcudn'] +$ar_I[13]['pcudn'];
            $ar_I[11]['pcth'] = $ar_I[12]['pcth'] +$ar_I[13]['pcth'];
            $ar_I[11]['pctn'] = $ar_I[12]['pctn'] +$ar_I[13]['pctn'];
            $ar_I[11]['pccovu'] = $ar_I[12]['pccovu'] +$ar_I[13]['pccovu'];
            $ar_I[11]['pcdang'] = $ar_I[12]['pcdang'] +$ar_I[13]['pcdang'];
            $ar_I[11]['pcthni'] = $ar_I[12]['pcthni'] +$ar_I[13]['pcthni'];
            $ar_I[11]['pck'] = $ar_I[12]['pck'] +$ar_I[13]['pck'];
            $ar_I[11]['tongpc'] = $ar_I[12]['tongpc'] +$ar_I[13]['tongpc'];
            $ar_I[11]['ttbh_dv'] = $ar_I[12]['ttbh_dv'] +$ar_I[13]['ttbh_dv'];
            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] +$ar_I[13]['chenhlech'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['pckv'] = $ar_I[1]['pckv'] +$ar_I[2]['pckv'];
            $ar_I[0]['pccv'] = $ar_I[1]['pccv'] +$ar_I[2]['pccv'];
            $ar_I[0]['pctnvk'] = $ar_I[1]['pctnvk'] +$ar_I[2]['pctnvk'];
            $ar_I[0]['pcudn'] = $ar_I[1]['pcudn'] +$ar_I[2]['pcudn'];
            $ar_I[0]['pcth'] = $ar_I[1]['pcth'] +$ar_I[2]['pcth'];
            $ar_I[0]['pctn'] = $ar_I[1]['pctn'] +$ar_I[2]['pctn'];
            $ar_I[0]['pccovu'] = $ar_I[1]['pccovu'] +$ar_I[2]['pccovu'];
            $ar_I[0]['pcdang'] = $ar_I[1]['pcdang'] +$ar_I[2]['pcdang'];
            $ar_I[0]['pcthni'] = $ar_I[1]['pcthni'] +$ar_I[2]['pcthni'];
            $ar_I[0]['pck'] = $ar_I[1]['pck'] +$ar_I[2]['pck'];
            $ar_I[0]['tongpc'] = $ar_I[1]['tongpc'] +$ar_I[2]['tongpc'];
            $ar_I[0]['ttbh_dv'] = $ar_I[1]['ttbh_dv'] +$ar_I[2]['ttbh_dv'];
            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] +$ar_I[2]['chenhlech'];
            //dd($ar_I);

            $ar_II = array();
            /*
            if(isset($model_bangluong_ct)){
                $chitiet = $model_bangluong_ct->where('maphanloai', 'KVXP');
            }
            */
            $chitiet = $model_tonghop_ct->where('maphanloai', 'KVXP');
            //dd($chitiet);
            if(isset($chitiet)){
                $tongpc = 0;
                $ar_II['luong'] = $chitiet->sum('heso')* $luongcb;
                $ar_II['ttbh_dv'] = round($chitiet->sum('ttbh_dv') * $luongcb);

                $ar_II['pckv'] = $chitiet->sum('pckv')* $luongcb;
                $tongpc += $ar_II['pckv'];
                $ar_II['pccv'] = $chitiet->sum('pccv')* $luongcb;
                $tongpc += $ar_II['pccv'];
                $ar_II['pctnvk'] = $chitiet->sum('pctnvk')* $luongcb;
                $tongpc += $ar_II['pctnvk'];
                $ar_II['pcudn'] = $chitiet->sum('pcudn')* $luongcb;
                $tongpc += $ar_II['pcudn'];
                $ar_II['pcth'] = $chitiet->sum('pcth')* $luongcb;
                $tongpc += $ar_II['pcth'];
                $ar_II['pctn'] = $chitiet->sum('pctn')* $luongcb;
                $tongpc += $ar_II['pctn'];
                $ar_II['pccovu'] = $chitiet->sum('pccovu')* $luongcb;
                $tongpc += $ar_II['pccovu'];
                $ar_II['pcdang'] = $chitiet->sum('pcdang')* $luongcb;
                $tongpc += $ar_II['pcdang'];
                $ar_II['pcthni'] = $chitiet->sum('pcthni')* $luongcb;
                $tongpc += $ar_II['pcthni'];
                $ar_II['pck'] = $chitiet->sum('pck');
                $tongpc += $ar_II['pck'];
                $ar_II['tongpc'] = $tongpc;

                $ar_II['chenhlech'] = round(($tongpc +$ar_II['ttbh_dv'] +$ar_II['luong'])*90000/1390000);

            }else{
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
            $ar_III[]=array('val'=>'T','tt'=>'-','noidung'=>'Cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'H','tt'=>'-','noidung'=>'Cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_III[]=array('val'=>'X','tt'=>'-','noidung'=>'Cấp xã','tongso'=>'0','chenhlech'=>'0');

            $ar_IV = array();
            $ar_IV[]=array('val'=>'T','tt'=>'-','noidung'=>'Ủy viên cấp tỉnh','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'H','tt'=>'-','noidung'=>'Ủy viên cấp huyện','tongso'=>'0','chenhlech'=>'0');
            $ar_IV[]=array('val'=>'X','tt'=>'-','noidung'=>'Ủy viên cấp xã','tongso'=>'0','chenhlech'=>'0');
            //hỏi chi tiết xem đơn vi cấp xã có pai là dự toán cấp 3,4
            //huyên cấp ??
            //tỉnh cấp ??

            $a_IIIt = array('tongso'=>0,'chenhlech'=>0);
            $a_IVt = array('tongso'=>0,'chenhlech'=>0);
            if(session('admin')->level=='H'){
                if($m_dv->capdonvi >= 3){

                        $ar_III[2]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[2]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000 / 1390000;
                        $ar_IV[2]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                        $ar_IV[2]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000;


                    $a_IIIt['tongso'] += $ar_III[2]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[2]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[2]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[2]['chenhlech'];
                }else{

                        $ar_III[1]['tongso'] = $model_tonghop_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_tonghop_ct->sum('pcdbqh') * 90000/ 1390000;
                        $ar_IV[1]['tongso'] = $model_tonghop_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_tonghop_ct->sum('pcvk') * 90000/ 1390000;


                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];
                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }
            }else{
                if($m_dv->capdonvi >= 3){
                    if(isset($model_bangluong_ct)){
                        $ar_III[1]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[1]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[1]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[1]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[1]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[1]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[1]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[1]['chenhlech'];
                }else{
                    if(isset($model_bangluong_ct)){
                        $ar_III[0]['tongso'] = $model_bangluong_ct->sum('pcdbqh') * $luongcb;
                        $ar_III[0]['chenhlech'] = $model_bangluong_ct->sum('pcdbqh') * 90000/ 1390000;

                        $ar_IV[0]['tongso'] = $model_bangluong_ct->sum('pcvk') * $luongcb;
                        $ar_IV[0]['chenhlech'] = $model_bangluong_ct->sum('pcvk') * 90000/ 1390000;
                    }

                    $a_IIIt['tongso'] += $ar_III[0]['tongso'];
                    $a_IIIt['chenhlech'] += $ar_III[0]['chenhlech'];

                    $a_IVt['tongso'] += $ar_IV[0]['tongso'];
                    $a_IVt['chenhlech'] += $ar_IV[0]['chenhlech'];
                }
            }
            //dd($ar_I);

            return view('reports.thongtu67.huyen.mau2a2')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('a_It',$a_It)
                ->with('a_IIIt',$a_IIIt)
                ->with('a_IVt',$a_IVt)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2b_huyen(Request $request) {
        if (Session::has('admin')  ) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                ->where('dmphanloaict.macongtac','NGHIHUU')
                ->get();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                        ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                        ->join('dmdonvi','dmdonvi.madv','hosocanbo.madv')
                        ->where('dmphanloaict.macongtac','NGHIHUU')
                        ->where('dmdonvi.macqcq',$madv)
                        ->get();
                }

                else
                {
                    $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                        ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                        ->where('dmphanloaict.macongtac','NGHIHUU')
                        ->where('hosocanbo.madv',$madv)
                        ->get();
                }
            }
            else {
                $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                    ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                    ->join('dmdonvi','dmdonvi.madv','hosocanbo.madv')
                    ->where('dmphanloaict.macongtac','NGHIHUU')
                    ->where('dmdonvi.madvbc',$madvbc)
                    ->get();
            }
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[0]=array('val'=>'BT','tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
                $ar_Igr[1]=array('val'=>'P','tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_Igr[2]=array('val'=>'K','tt'=>'3','noidung'=>'Các chức danh còn lại');
            }
            else {
                $ar_I[0]=array('val'=>'BT','tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
                $ar_I[1]=array('val'=>'P','tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_I[2]=array('val'=>'K','tt'=>'3','noidung'=>'Các chức danh còn lại');
            }
            $a_It = array('cb' => 0,
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
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('$a_It',$a_It)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2b_huyen_excel(Request $request) {
        if (Session::has('admin')  ) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso')) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                ->where('dmphanloaict.macongtac','NGHIHUU')
                ->get();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                        ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                        ->join('dmdonvi','dmdonvi.madv','hosocanbo.madv')
                        ->where('dmphanloaict.macongtac','NGHIHUU')
                        ->where('dmdonvi.macqcq',$madv)
                        ->get();
                }

                else
                {
                    $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                        ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                        ->where('dmphanloaict.macongtac','NGHIHUU')
                        ->where('hosocanbo.madv',$madv)
                        ->get();
                }
            }
            else {
                $m_hscb = hosocanbo::join('dmphanloaict','dmphanloaict.mact','=','hosocanbo.mact')
                    ->join('dmchucvucq','dmchucvucq.macvcq','=','hosocanbo.macvcq')
                    ->join('dmdonvi','dmdonvi.madv','hosocanbo.madv')
                    ->where('dmphanloaict.macongtac','NGHIHUU')
                    ->where('dmdonvi.madvbc',$madvbc)
                    ->get();
            }
            $ar_I = array();
            $ar_Igr = array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[0]=array('val'=>'BT','tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
                $ar_Igr[1]=array('val'=>'P','tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_Igr[2]=array('val'=>'K','tt'=>'3','noidung'=>'Các chức danh còn lại');
            }
            else {
                $ar_I[0]=array('val'=>'BT','tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
                $ar_I[1]=array('val'=>'P','tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
                $ar_I[2]=array('val'=>'K','tt'=>'3','noidung'=>'Các chức danh còn lại');
            }
            $a_It = array('cb' => 0,
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

            Excel::create('mau2b',function($excel) use($ar_I,$a_It,$m_dv){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$m_dv){
                    $sheet->loadView('reports.thongtu67.mau2bexcel')
                        ->with('ar_I',$ar_I)
                        ->with('a_It',$a_It)
                        ->with('m_dv',$m_dv)
                        ->with('pageTitle','Mau2b');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau4a_huyencu()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('madvbc',session('admin')->madvbc)
                ->where('sohieu','TT67_2017')->get();
            $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            if(count($model) == 0){
                return view('errors.nodata');
            }
            foreach($model as $ct){
                $donvi = $model_donvi->where('madv',$ct->madv)->first();
                $ct->phanloainguon = $donvi->phanloainguon;
                $ct->maphanloai = $donvi->maphanloai;
            }
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2016 so dự toán Thủ tướng Chính phủ giao năm 2016','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo','sotien'=>'0');
            $a_A[4] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[5] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[6] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[7] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo','sotien'=>'0');
            $a_A[8] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[9] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[10] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[11] = array('tt'=>'4','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2016 chưa sử dụng hết chuyển sang 2017','sotien'=>'0');

            //
            $a_BI = array();
            $a_BI[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BI[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo ','sotien'=>'0');
            $a_BI[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BI[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BI[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 76/2017/NĐ-CP','sotien'=>'0');
            $a_BI[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BI[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BI[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 3115-QĐ/VVPTW','sotien'=>'0');

            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Kinh phí hỗ trợ chênh lệch tiền lương cho người có thu nhập thấp (NĐ17/2015/NĐ-CP) và mức lương 1,21 (6 tháng)','sotien'=>'0');
            $a_BII[1] = array('tt'=>'2','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ','sotien'=>'0');
            $a_BII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2017 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BII[3] = array('tt'=>'4','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2017 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon',array('CHITXDT','CTX'));
            $a_A[4]['sotien'] =$model_tudb->sum('hocphi');
            $a_A[5]['sotien'] =$model_tudb->sum('vienphi');
            $a_A[6]['sotien'] =$model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi')- $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu')- $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai','KVXP');

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
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BI'=>(array_sum(array_column($a_BI,'sotien')) - $a_BI[1]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')))
            );

            return view('reports.thongtu67.donvi.mau4a')
                ->with('model',$model)
                ->with('a_A',$a_A)
                ->with('a_BI',$a_BI)
                ->with('a_BII',$a_BII)
                ->with('a_TC',$a_TC)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b_huyencu()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('madvbc',session('admin')->madvbc)
                ->where('sohieu','TT67_2017')->get();
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $data[0]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[1]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[2]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[3]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[4]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[5]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[6]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            for($i=0;$i<count($data);$i++){
                $solieu =  $model->where('linhvuchoatdong',$data[$i]['val']);
                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
            }
            $data[0]['nhucau'] = $data[1]['nhucau']+$data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;

            $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
            $data[4]['nguonkp'] = $model->sum('nguonkp')-  $data[0]['nguonkp']- $data[5]['nguonkp']- $data[3]['nguonkp'];
            $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem']- $data[5]['tietkiem']- $data[3]['tietkiem'];
            $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi']- $data[5]['hocphi']- $data[3]['hocphi'];
            $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi']- $data[5]['vienphi']- $data[3]['vienphi'];
            $data[4]['nguonthu'] = $model->sum('nguonthu')- $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];

            return view('reports.thongtu67.huyen.mau4b')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau2c_huyencu()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madvbc', session('admin')->madvbc)->first();
            $model_donvi = dmdonvi::where('madvbc', session('admin')->madvbc)->get();
            $model_congtac = dmphanloaict::all();
            $madvbc = session('admin')->madvbc;
            $luongcb = 1150000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả

            $model_tonghop = bangluong::where('thang', '07')->where('nam', '2017')
                ->wherein('madv', function ($q) use ($madvbc) {
                    $q->select('madv')->from('dmdonvi')->where('madvbc', $madvbc)->get();
                })->get();
            $luongcb = 1150000;
            //nếu đơn vị đã tạo bảng lương tháng 07/2017 =>xuất kết quả
            //dd($model_tonghop);

            /*
            $model_tonghop_ct = bangluong_ct::wherein('mabl',function($qr) use($madvbc){
                $qr->select('mabl')->from('bangluong')->where('thang','07')->where('nam','2017')
                    ->wherein('madv',function($q) use($madvbc){
                        $q->select('madv')->from('dmdonvi')->where('madvbc',$madvbc)->get();
                    })->get();
            })->get();
            */
            $model_tonghop_ct = (new data())->getBangluong_ct_ar('07',a_unique(array_column($model_tonghop->toarray(),'mabl')));
            foreach($model_tonghop_ct as $ct){
                //$ct->luongcb = $model_bangluong->luongcoban;

                $congtac = $model_congtac->where('mact',$ct->mact)->first();
                $ct->macongtac=isset($congtac->macongtac) ? $congtac->macongtac : null;

                $tonghop = $model_tonghop->where('mabl',$ct->mabl)->first();
                $ct->linhvuchoatdong=$tonghop->linhvuchoatdong;//chỉ dùng cho khối HCSN
                $ct->maphanloai = $model_donvi->where('madv',$tonghop->madv)->first()->maphanloai;
            }
            $model_bangluong_ct = $model_tonghop_ct->where('macongtac','BIENCHE')->where('heso','<=','2.34');

            //dd($model_bangluong_ct->toarray());

            $ar_I = array();
            $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $a_It = array('luong' => 0,
                'phucap' => 0,
                'soluong' => 0,
                'chenhlech'=>0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($model_bangluong_ct)){
                    $chitiet = $model_bangluong_ct->where('linhvuchoatdong',$ar_I[$i]['val']);
                }
                $ar_I[$i]['soluong'] = 0;
                $ar_I[$i]['chenhlech'] = 0;
                if(isset($chitiet) && count($chitiet)>0){
                    $ar_I[$i]['soluong'] = $chitiet->count();

                    $tongpc = 0;
                    $ar_I[$i]['luong'] = $chitiet->sum('heso') * $luongcb;
                    $a_It['luong'] += $ar_I[$i]['luong'];
                    $tongpc += $chitiet->sum('pckv')* $luongcb;
                    $tongpc += $chitiet->sum('pccv') * $luongcb;
                    $tongpc += $chitiet->sum('pctnvk') * $luongcb;
                    $tongpc += $chitiet->sum('pcudn') * $luongcb;
                    $tongpc += $chitiet->sum('pcth') * $luongcb;
                    $tongpc += $chitiet->sum('pctn') * $luongcb;
                    $tongpc += $chitiet->sum('pccovu') * $luongcb;
                    $tongpc += $chitiet->sum('pcdang') * $luongcb;
                    $tongpc += $chitiet->sum('pcthni') * $luongcb;
                    $tongpc += $chitiet->sum('pck') * $luongcb;

                    $ar_I[$i]['chenhlech'] = (($tongpc + $a_It['luong'])/$luongcb)*60000;
                    $ar_I[$i]['phucap'] = $tongpc;
                    $a_It['phucap'] += $ar_I[$i]['phucap'];
                }else{
                    $ar_I[$i]['phucap'] = 0;
                    $ar_I[$i]['luong'] = 0;
                }
            }
            $ar_I[11]['luong'] = $ar_I[12]['luong'] +$ar_I[13]['luong'];
            $ar_I[11]['phucap'] = $ar_I[12]['phucap'] +$ar_I[13]['phucap'];
            $ar_I[11]['soluong'] = $ar_I[12]['soluong'] +$ar_I[13]['soluong'];
            $ar_I[11]['chenhlech'] = $ar_I[12]['chenhlech'] +$ar_I[13]['chenhlech'];

            $ar_I[0]['luong'] = $ar_I[1]['luong'] +$ar_I[2]['luong'];
            $ar_I[0]['phucap'] = $ar_I[1]['phucap'] +$ar_I[2]['phucap'];
            $ar_I[0]['soluong'] = $ar_I[1]['soluong'] +$ar_I[2]['soluong'];
            $ar_I[0]['chenhlech'] = $ar_I[1]['chenhlech'] +$ar_I[2]['chenhlech'];

            //dd($ar_I);

            $ar_II = array();
            $ar_II['soluong'] = 0;
            $ar_II['chenhlech'] = 0;
            if(isset($model_bangluong_ct)){
                $chitiet = $model_bangluong_ct->where('maphanloai','KVXP');
            }
            if(count($chitiet)>0){
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
                $ar_II['chenhlech'] = (($tongpc + $ar_II['luong'])/$luongcb)*60000;
            }else{
                $ar_II['luong'] = 0;
                $ar_II['phucap'] = 0;
            }
            //dd($ar_II);

            return view('reports.thongtu67.huyen.mau2c')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('a_It',$a_It)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu chênh lệch');
        } else
            return view('errors.notlogin');
    }

    function mau2c_huyen(Request $request) {
        if (Session::has('admin')  ) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select('linhvuchoatdong', 'heso', 'dmdonvi.maphanloai','hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                            'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                            'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                        ->where('dmdonvi.macqcq',$madv)
                        ->where('heso','<=', 2.34)
                        ->get();
                }

                else
                {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select('linhvuchoatdong','dmdonvi.maphanloai', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                            'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                            'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                        ->where('dmdonvi.madv',$madv)
                        ->where('heso','<=', 2.34)
                        ->get();
                }
            }
            else {
                $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->select('linhvuchoatdong','dmdonvi.maphanloai', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                        'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                        'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                    ->where('dmdonvi.madvbc',$madvbc)
                    ->where('heso','<=', 2.34)
                    ->get();
            }


            if(session('admin')->username == 'khthso')
            {
                $m_cb =hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('macanbo','dmdonvi.maphanloai','linhvuchoatdong', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                        'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn', 'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('hosocanbo.heso','<=', 2.34)
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $luongcb = 60000;
            $ar_I = array();
            $ar_Igr= array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }

            $a_It = array('dt' => 0,
                'hstl' => 0,
                'hspc' => 0,
                'cl' => 0,
                'nc' => 0
            );
            if(isset($inputs['inchitiet'])) {
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
                        ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                        ->where('heso','<=', 2.34)
                        ->groupby('dmdonvi.tendv')
                        ->get();
                    $tendv = $ten_dv->toArray();
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        foreach($tendv as $tendvchitiet) {
                            $chitietct = $m_cb->where('tendv', $tendvchitiet['tendv'])->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                            $d= 1;
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
                    }
                    else {
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
            }
            else
            {
                for($i=0;$i<count($ar_I);$i++){
                    if(isset($m_cb)){
                        $chitiet = $m_cb->where('linhvuchoatdong',$ar_I[$i]['val']);
                    }
                    if(isset($chitiet)>0){
                        $tongcb = 0;
                        $tonghs = 0;
                        $tongpc = 0;
                        $ar_I[$i]['dt'] = $chitiet->count('heso');

                        $a_It['dt'] += $ar_I[$i]['dt'];

                        $ar_I[$i]['hstl'] = $chitiet->sum('heso');
                        $a_It['hstl'] += $ar_I[$i]['hstl'];

                        $tongpc +=  $chitiet->sum('pckv');
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
                        $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl'])*$luongcb);
                        $a_It['cl'] += $ar_I[$i]['cl'];
                        $ar_I[$i]['nc'] = $ar_I[$i]['cl']*6;
                        $a_It['nc'] += $ar_I[$i]['nc'];

                    }else{
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
            }
            $ar_II = array();

            $chitiet = $m_cb->where('maphanloai','KVXP');
            //
            if(count($chitiet)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['dt'] = $chitiet->count('heso');
                $ar_II['hstl'] = $chitiet->sum('heso');

                $tongpc +=  $chitiet->sum('pckv');
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
                $ar_II['cl'] = round(($tongpc + $ar_II['hstl'])*$luongcb);
                $ar_II['nc'] = $ar_II['cl']*6;
            }
            else{
                $ar_II['dt'] = 0;
                $ar_II['hstl'] = 0;
                $ar_II['hspc'] = 0;
                $ar_II['cl'] = 0;
                $ar_II['nc'] = 0;
            }
            return view('reports.thongtu67.Mau2c_tt68')
                ->with('m_dv',$m_dv)
                ->with('ar_I',$ar_I)
                ->with('ar_II',$ar_II)
                ->with('a_It',$a_It)
                ->with('inputs',$inputs)
                ->with('pageTitle','BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BHTN');
        } else
            return view('errors.notlogin');
    }

    function mau2c_huyen_excel(Request $request) {
        if (Session::has('admin')  ) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select('linhvuchoatdong', 'heso', 'dmdonvi.maphanloai','hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                            'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                            'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                        ->where('dmdonvi.macqcq',$madv)
                        ->where('heso','<=', 2.34)
                        ->get();
                }

                else
                {
                    $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                        ->select('linhvuchoatdong','dmdonvi.maphanloai', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                            'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                            'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                            'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                        ->where('dmdonvi.madv',$madv)
                        ->where('heso','<=', 2.34)
                        ->get();
                }
            }
            else {
                $m_cb = hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->select('linhvuchoatdong','dmdonvi.maphanloai', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                        'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn',
                        'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni','dmdonvi.tendv')
                    ->where('dmdonvi.madvbc',$madvbc)
                    ->where('heso','<=', 2.34)
                    ->get();
            }


            if(session('admin')->username == 'khthso')
            {
                $m_cb =hosocanbo::join('dmdonvi', 'hosocanbo.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('macanbo','dmdonvi.maphanloai','linhvuchoatdong', 'heso', 'hosocanbo.pck', 'hosocanbo.pccv', 'hosocanbo.pckv', 'hosocanbo.pcth', 'hosocanbo.pcdh',
                        'hosocanbo.pcld', 'hosocanbo.pcudn', 'hosocanbo.pctn', 'hosocanbo.pctnn', 'hosocanbo.pcdbn', 'hosocanbo.pcvk', 'hosocanbo.pckn', 'hosocanbo.pccovu', 'hosocanbo.pcdbqh',
                        'hosocanbo.pctnvk', 'hosocanbo.pcbdhdcu', 'hosocanbo.pcdang', 'hosocanbo.pcthni')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('hosocanbo.heso','<=', 2.34)
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $luongcb = 60000;
            $ar_I = array();
            $ar_Igr= array();
            if(isset($inputs['inchitiet'])) {
                $ar_Igr[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_Igr[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_Igr[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_Igr[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_Igr[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_Igr[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_Igr[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_Igr[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_Igr[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_Igr[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_Igr[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_Igr[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_Igr[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_Igr[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }
            else {
                $ar_I[]=array('val'=>'GD;DT','tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
                $ar_I[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục');
                $ar_I[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo');
                $ar_I[]=array('val'=>'YTE','tt'=>'2','noidung'=>'Sự nghiệp y tế');
                $ar_I[]=array('val'=>'KHCN','tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
                $ar_I[]=array('val'=>'VHTT','tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
                $ar_I[]=array('val'=>'PTTH','tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
                $ar_I[]=array('val'=>'TDTT','tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
                $ar_I[]=array('val'=>'DBXH','tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
                $ar_I[]=array('val'=>'KT','tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
                $ar_I[]=array('val'=>'MT','tt'=>'9','noidung'=>'Sự nghiệp môi trường');
                $ar_I[]=array('val'=>'QLNN;DDT','tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
                $ar_I[]=array('val'=>'QLNN','tt'=>'-','noidung'=>' Quản lý NN');
                $ar_I[]=array('val'=>'DDT','tt'=>'-','noidung'=>'Đảng, đoàn thể');
            }

            $a_It = array('dt' => 0,
                'hstl' => 0,
                'hspc' => 0,
                'cl' => 0,
                'nc' => 0
            );
            if(isset($inputs['inchitiet'])) {
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
                        ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                        ->where('heso','<=', 2.34)
                        ->groupby('dmdonvi.tendv')
                        ->get();
                    $tendv = $ten_dv->toArray();
                    if (isset($chitiet) && count($chitiet) > 0) {
                        //$thongtin = $chitiet->toArray();
                        foreach($tendv as $tendvchitiet) {
                            $chitietct = $m_cb->where('tendv', $tendvchitiet['tendv'])->where('linhvuchoatdong', $ar_Igr[$j]['val']);
                            $d= 1;
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
                    }
                    else {
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
            }
            else
            {
                for($i=0;$i<count($ar_I);$i++){
                    if(isset($m_cb)){
                        $chitiet = $m_cb->where('linhvuchoatdong',$ar_I[$i]['val']);
                    }
                    if(isset($chitiet)>0){
                        $tongcb = 0;
                        $tonghs = 0;
                        $tongpc = 0;
                        $ar_I[$i]['dt'] = $chitiet->count('heso');

                        $a_It['dt'] += $ar_I[$i]['dt'];

                        $ar_I[$i]['hstl'] = $chitiet->sum('heso');
                        $a_It['hstl'] += $ar_I[$i]['hstl'];

                        $tongpc +=  $chitiet->sum('pckv');
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
                        $ar_I[$i]['cl'] = round(($tongpc + $ar_I[$i]['hstl'])*$luongcb);
                        $a_It['cl'] += $ar_I[$i]['cl'];
                        $ar_I[$i]['nc'] = $ar_I[$i]['cl']*6;
                        $a_It['nc'] += $ar_I[$i]['nc'];

                    }else{
                        $ar_I[$i]['dt'] = 0;
                        $ar_I[$i]['hstl'] = 0;
                        $ar_I[$i]['hspc'] = 0;
                        $ar_I[$i]['cl'] = 0;
                        $ar_I[$i]['nc'] = 0;
                    }
                }
            }
            $ar_II = array();

            $chitiet = $m_cb->where('maphanloai','KVXP');
            //
            if(count($chitiet)>0){
                //dd($model_tonghop_ct);
                $tongpc = 0;
                $ar_II['dt'] = $chitiet->count('heso');
                $ar_II['hstl'] = $chitiet->sum('heso');

                $tongpc +=  $chitiet->sum('pckv');
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
                $ar_II['cl'] = round(($tongpc + $ar_II['hstl'])*$luongcb);
                $ar_II['nc'] = $ar_II['cl']*6;
            }
            else{
                $ar_II['dt'] = 0;
                $ar_II['hstl'] = 0;
                $ar_II['hspc'] = 0;
                $ar_II['cl'] = 0;
                $ar_II['nc'] = 0;
            }

            Excel::create('Mau2c_BcNCCL',function($excel) use($ar_I,$a_It,$ar_II,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$ar_II,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.Mau2c_BcNCCLexcel')
                        ->with('ar_I',$ar_I)
                        ->with('a_It',$a_It)
                        ->with('ar_II',$ar_II)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2c_BcNCCL');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2d_huyen(Request $request) {
        if (Session::has('admin')  ) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id','phanloai')
                        ->where('dmdonvi.macqcq',$madv)
                        ->where('maphanloai','KVXP')
                        ->get();
                }

                else
                {
                    $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id','phanloai')
                        ->where('dmdonvi.madv',$madv)
                        ->where('maphanloai','KVXP')
                        ->get();
                }
            }
            else {
                $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->select('dmdiabandbkk.id','phanloai')
                    ->where('dmdonvi.madvbc',$madvbc)
                    ->where('maphanloai','KVXP')
                    ->get();
            }
            if(session('admin')->username == 'khthso')
            {
                $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('dmdiabandbkk.id','phanloai')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvi.maphanloai','KVXP')
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $m_dt = dmdiabandbkk_chitiet::join('dmdiabandbkk','dmdiabandbkk_chitiet.madiaban' ,'=', 'dmdiabandbkk.madiaban')
                ->select('dmdiabandbkk_chitiet.id','phanloai')->get();

            $ar_I = array();
            $ar_I[]=array('val'=>'XL1;XL2;XL3','tt'=>'I','noidung'=>'Xã, phường, thị trấn');
            $ar_I[]=array('val'=>'XL1','tt'=>'1','noidung'=>'Xã loại I');
            $ar_I[]=array('val'=>'XL2','tt'=>'2','noidung'=>'Xã loại II');
            $ar_I[]=array('val'=>'XL3','tt'=>'3','noidung'=>'Xã loại III');
            $ar_I[]=array('val'=>'DBKK;BGHD;DBTD','tt'=>'II','noidung'=>'Thôn, tỏ dân phố');
            $ar_I[]=array('val'=>'BGHD','tt'=>'1','noidung'=>'Số xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'BGHD','tt'=>'','noidung'=>'- Thôn thuộc xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'DBKK','tt'=>'2','noidung'=>'Số xã khó khăn theo Quyết định 1049/QĐ-TTg ngày 26/6/2014');
            $ar_I[]=array('val'=>'DBKK','tt'=>'','noidung'=>'- Thôn thuộc xã khó khăn theo Quyết định 1049/QĐ-TTg');
            $ar_I[]=array('val'=>'XL12K','tt'=>'3','noidung'=>'Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)');
            $ar_I[]=array('val'=>'TXL12K','tt'=>'','noidung'=>'- Thôn thuộc xã loại I, loại II');
            $ar_I[]=array('val'=>'DBTD','tt'=>'4','noidung'=>'Số xã trọng điểm, phức tạp về an ninh trật tự');
            $ar_I[]=array('val'=>'DBTD','tt'=>'','noidung'=>'- Số thôn thuộc xã trọng điểm, phức tạp về an ninh');
            $ar_I[]=array('val'=>'TK,TDP','tt'=>'5','noidung'=>'Số xã, phường, thị trấn còn lại');
            $ar_I[]=array('val'=>'TK','tt'=>'','noidung'=>'- Thôn còn lại');
            $ar_I[]=array('val'=>'TDP','tt'=>'','noidung'=>'- Tổ dân phố');

            $a_It = array('tdv' => 0,
                'mk' => 0,
                'kp' => 0,
                'bhxh' => 0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($m_thon)){
                    $chitiet = $m_thon->where('phanloai',$ar_I[$i]['val']);
                }

                if(isset($chitiet)>0){
                    $kpk = 0;
                    $ar_I[$i]['tdv'] = $chitiet->count('id');
                    $a_It['tdv'] += $ar_I[$i]['tdv'];
                    if($ar_I[$i]['val'] == "XL1")
                    {
                        $ar_I[$i]['mk'] = "20,3";
                        $kpk = 20.3;
                    }
                    elseif($ar_I[$i]['val'] == "XL2")
                    {
                        $ar_I[$i]['mk'] = "18,6";
                        $kpk = 18.6;
                    }
                    elseif($ar_I[$i]['val'] == "XL3")
                    {
                        $ar_I[$i]['mk'] = "17,6";
                        $kpk = 17.6;
                    }

                    elseif($ar_I[$i]['val'] == "TBGHD" || $ar_I[$i]['val'] == "TDBKK" || $ar_I[$i]['val'] == "TXL12K" )
                    {
                        $ar_I[$i]['mk'] = "5,0";
                        $kpk = 5;
                    }
                    elseif($ar_I[$i]['val'] == "TK" || $ar_I[$i]['val'] == "TDP")
                    {
                        $ar_I[$i]['mk'] = "3,0";
                        $kpk = 3;
                    }
                    else
                        $ar_I[$i]['mk'] = "";
                    $ar_I[$i]['kp'] = $a_It['tdv']* $kpk * 6 * 900000;
                    $a_It['kp'] += $ar_I[$i]['kp'];
                    $ar_I[$i]['bhxh'] = 0;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];

                }else{
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['kp'] = 0;
                    $ar_I[$i]['bh'] = 0;
                }
            }
            //dd($ar_I);

            return view('reports.thongtu67.Mau2d_tt68m')
                ->with('m_dv',$m_dv)
                ->with('ar_I',$ar_I)
                ->with('a_It',$a_It)
                ->with('inputs',$inputs)
                ->with('pageTitle','TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2d_huyen_excel(Request $request) {
        if (Session::has('admin')  ) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0)
                {
                    $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id','phanloai')
                        ->where('dmdonvi.macqcq',$madv)
                        ->where('maphanloai','KVXP')
                        ->get();
                }

                else
                {
                    $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                        ->select('dmdiabandbkk.id','phanloai')
                        ->where('dmdonvi.madv',$madv)
                        ->where('maphanloai','KVXP')
                        ->get();
                }
            }
            else {
                $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->select('dmdiabandbkk.id','phanloai')
                    ->where('dmdonvi.madvbc',$madvbc)
                    ->where('maphanloai','KVXP')
                    ->get();
            }
            if(session('admin')->username == 'khthso')
            {
                $m_thon =dmdiabandbkk::join('dmdonvi', 'dmdiabandbkk.madv', '=', 'dmdonvi.madv')
                    ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')
                    ->select('dmdiabandbkk.id','phanloai')
                    ->where('dmdonvi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvi.maphanloai','KVXP')
                    ->where('dmdonvibaocao.level','T')
                    ->get();
            }
            $m_dt = dmdiabandbkk_chitiet::join('dmdiabandbkk','dmdiabandbkk_chitiet.madiaban' ,'=', 'dmdiabandbkk.madiaban')
                ->select('dmdiabandbkk_chitiet.id','phanloai')->get();

            $ar_I = array();
            $ar_I[]=array('val'=>'XL1;XL2;XL3','tt'=>'I','noidung'=>'Xã, phường, thị trấn');
            $ar_I[]=array('val'=>'XL1','tt'=>'1','noidung'=>'Xã loại I');
            $ar_I[]=array('val'=>'XL2','tt'=>'2','noidung'=>'Xã loại II');
            $ar_I[]=array('val'=>'XL3','tt'=>'3','noidung'=>'Xã loại III');
            $ar_I[]=array('val'=>'DBKK;BGHD;DBTD','tt'=>'II','noidung'=>'Thôn, tỏ dân phố');
            $ar_I[]=array('val'=>'BGHD','tt'=>'1','noidung'=>'Số xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'BGHD','tt'=>'','noidung'=>'- Thôn thuộc xã biên giới, hải đảo');
            $ar_I[]=array('val'=>'DBKK','tt'=>'2','noidung'=>'Số xã khó khăn theo Quyết định 1049/QĐ-TTg ngày 26/6/2014');
            $ar_I[]=array('val'=>'DBKK','tt'=>'','noidung'=>'- Thôn thuộc xã khó khăn theo Quyết định 1049/QĐ-TTg');
            $ar_I[]=array('val'=>'XL12K','tt'=>'3','noidung'=>'Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)');
            $ar_I[]=array('val'=>'TXL12K','tt'=>'','noidung'=>'- Thôn thuộc xã loại I, loại II');
            $ar_I[]=array('val'=>'DBTD','tt'=>'4','noidung'=>'Số xã trọng điểm, phức tạp về an ninh trật tự');
            $ar_I[]=array('val'=>'DBTD','tt'=>'','noidung'=>'- Số thôn thuộc xã trọng điểm, phức tạp về an ninh');
            $ar_I[]=array('val'=>'TK,TDP','tt'=>'5','noidung'=>'Số xã, phường, thị trấn còn lại');
            $ar_I[]=array('val'=>'TK','tt'=>'','noidung'=>'- Thôn còn lại');
            $ar_I[]=array('val'=>'TDP','tt'=>'','noidung'=>'- Tổ dân phố');

            $a_It = array('tdv' => 0,
                'mk' => 0,
                'kp' => 0,
                'bhxh' => 0
            );

            for($i=0;$i<count($ar_I);$i++){
                if(isset($m_thon)){
                    $chitiet = $m_thon->where('phanloai',$ar_I[$i]['val']);
                }

                if(isset($chitiet)>0){
                    $kpk = 0;
                    $ar_I[$i]['tdv'] = $chitiet->count('id');
                    $a_It['tdv'] += $ar_I[$i]['tdv'];
                    if($ar_I[$i]['val'] == "XL1")
                    {
                        $ar_I[$i]['mk'] = "20,3";
                        $kpk = 20.3;
                    }
                    elseif($ar_I[$i]['val'] == "XL2")
                    {
                        $ar_I[$i]['mk'] = "18,6";
                        $kpk = 18.6;
                    }
                    elseif($ar_I[$i]['val'] == "XL3")
                    {
                        $ar_I[$i]['mk'] = "17,6";
                        $kpk = 17.6;
                    }

                    elseif($ar_I[$i]['val'] == "TBGHD" || $ar_I[$i]['val'] == "TDBKK" || $ar_I[$i]['val'] == "TXL12K" )
                    {
                        $ar_I[$i]['mk'] = "5,0";
                        $kpk = 5;
                    }
                    elseif($ar_I[$i]['val'] == "TK" || $ar_I[$i]['val'] == "TDP")
                    {
                        $ar_I[$i]['mk'] = "3,0";
                        $kpk = 3;
                    }
                    else
                        $ar_I[$i]['mk'] = "";
                    $ar_I[$i]['kp'] = $a_It['tdv']* $kpk * 6 * 900000;
                    $a_It['kp'] += $ar_I[$i]['kp'];
                    $ar_I[$i]['bhxh'] = 0;
                    $a_It['bhxh'] += $ar_I[$i]['bhxh'];

                }else{
                    $ar_I[$i]['tdv'] = 0;
                    $ar_I[$i]['mk'] = 0;
                    $ar_I[$i]['kp'] = 0;
                    $ar_I[$i]['bh'] = 0;
                }
            }
            Excel::create('Mau2d_ThKPTT',function($excel) use($ar_I,$a_It,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($ar_I,$a_It,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.Mau2d_ThKPTTexcel')
                        ->with('ar_I',$ar_I)
                        ->with('a_It',$a_It)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2d_ThKPTT');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2đ_huyen(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ar_I = array();
            $ar_I[]=array('val'=>'QLNN','tt'=>'I','noidung'=>'Quản lý nhà nước');
            $ar_I[]=array('val'=>'SNCL','tt'=>'II','noidung'=>'Sự nghiệp công lập');
            $ar_I[]=array('val'=>'CTXDT','tt'=>'1','noidung'=>'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[]=array('val'=>'CTX','tt'=>'2','noidung'=>'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[]=array('val'=>'MPCTX','tt'=>'3','noidung'=>'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[]=array('val'=>'NNCTX','tt'=>'4','noidung'=>'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            return view('reports.thongtu67.Mau2dd_tt68')
                ->with('inputs',$inputs)
                ->with('ar_I',$ar_I)
                ->with('pageTitle','TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2e_huyen(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ar_I = array();
            $ar_I[]=array('val'=>'CTXDT','tt'=>'1','noidung'=>'Đơn vị đảm bảo chi thường xuyên và chi đầu tư');
            $ar_I[]=array('val'=>'CTX','tt'=>'2','noidung'=>'Đơn vị đảm bảo chi thường xuyên');
            $ar_I[]=array('val'=>'MPCTX','tt'=>'3','noidung'=>'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[]=array('val'=>'NNCTX','tt'=>'4','noidung'=>'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            return view('reports.thongtu67.Mau2e_tt68')
                ->with('inputs',$inputs)
                ->with('ar_I',$ar_I)
                ->with('pageTitle','TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2e_huyen_excel(Request $request) {
        if (Session::has('admin')  ) {
            $inputs=$request->all();
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $m_dsh = dmdonvibaocao::where('level','H')->distinct()->get();
            $m_xa = dmdiabandbkk::join('dmdonvi','dmdonvi.madv', '=', 'dmdiabandbkk.madv')
                ->join('dmdonvibaocao','dmdonvibaocao.madvbc','=','dmdonvi.madvbc')->get();
            $ar_h = array();
            $tt = 0;
            foreach ($m_dsh as $h) {
                for ($i = 0; $i < count($m_dsh); $i++) {
                    $tt++;
                    $ar_h[$i]['tt'] = $tt;
                    $ar_h[$i]['noidung'] = $h['tendvbc'];
                }
            }
            Excel::create('Mau2e_ThKPTG',function($excel) use($ar_h){
                $excel->sheet('New sheet', function($sheet) use($ar_h){
                    $sheet->loadView('reports.thongtu67.Mau2e_ThKPTGexcel')
                        ->with('ar_h',$ar_h)
                        ->with('pageTitle','Mau2e_ThKPTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2g_huyen(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ar_I[0] = array('val' => 'GD;DT', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            $ar_I[1] = array('val' => 'GD', 'tt' => '', 'noidung' => '- Giáo dục');
            $ar_I[2] = array('val' => 'DT', 'tt' => '', 'noidung' => '- Đào tạo');
            $ar_I[3] = array('val' => 'YTE', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế');
            $ar_I[4] = array('val' => 'KHCN', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ');
            $ar_I[5] = array('val' => 'VHTT', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin');
            $ar_I[6] = array('val' => 'PTTH', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình');
            $ar_I[7] = array('val' => 'TDTT', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao');
            $ar_I[8] = array('val' => 'DBXH', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội');
            $ar_I[9] = array('val' => 'KT', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế');
            $ar_I[10] = array('val' => 'MT', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường');
            $ar_I[11] = array('val' => 'QLNN;DDT', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể');
            return view('reports.thongtu67.Mau2g_tt68')
                ->with('ar_I',$ar_I)
                ->with('inputs',$inputs)
                ->with('pageTitle','BÁO CÁO QUỸ TIỀN LƯƠNG, PHỤ CẤP ĐỐI VỚI LAO ĐỘNG THEO HỢP ĐỒNG KHU VỰC HÀNH CHÍNH VÀ ĐƠN VỊ SỰ NGHIỆP');
        } else
            return view('errors.notlogin');
    }

    function mau2g_huyen_excel() {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            Excel::create('Mau2g_ThPCUDTG',function($excel) use($m_dv){
                $excel->sheet('New sheet', function($sheet) use($m_dv){
                    $sheet->loadView('reports.thongtu67.Mau2g_ThPCUDTGexcel')
                        ->with('m_dv',$m_dv)
                        ->with('pageTitle','Mau2g_ThPCUDTG');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function mau2h_huyen() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.Mau2h_ThPCTHTG')
                ->with('pageTitle','TỔNG HỢP PHỤ CẤP THU HÚT TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2h_huyen_excel() {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            Excel::create('Mau2h_ThPCTHTG',function($excel) use($m_dv){
                $excel->sheet('New sheet', function($sheet) use($m_dv){
                    $sheet->loadView('reports.thongtu67.Mau2h_ThPCTHTGexcel')
                        ->with('m_dv',$m_dv)
                        ->with('pageTitle','Mau2h_ThPCTHTG');
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
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            if(count($model) == 0){
                return view('errors.nodata');
            }
            //foreach($model as $ct){
            //    $donvi = $model_donvi->where('madv',$ct->madv)->first();
            //    $ct->phanloainguon = $donvi->phanloainguon;
            //    $ct->maphanloai = $donvi->maphanloai;
            //}
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2017 so dự toán Thủ tướng Chính phủ giao năm 2017','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2018 so dự toán 2017 Thủ tướng Chính phủ giao','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'4','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2018','sotien'=>'0');
            $a_A[4] = array('tt'=>'5','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2018:','sotien'=>'0');
            $a_A[5] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo(1):','sotien'=>'0');
            $a_A[6] = array('tt'=>'','noidung'=>'+ Học phí','sotien'=>'0');
            $a_A[7] = array('tt'=>'','noidung'=>'+ Viện phí','sotien'=>'0');
            $a_A[8] = array('tt'=>'','noidung'=>'+ Nguồn thu khác','sotien'=>'0');
            $a_A[9] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo chi thường xuyên:','sotien'=>'0');
            $a_A[10] = array('tt'=>'','noidung'=>'+ Học phí','sotien'=>'0');
            $a_A[11] = array('tt'=>'','noidung'=>'+ Viện phí','sotien'=>'0');
            $a_A[12] = array('tt'=>'','noidung'=>'+ Nguồn thu khác','sotien'=>'0');
            $a_A[13] = array('tt'=>'6','noidung'=>'Nguồn tiết kiệm chi gắn với thực hiện các giải pháp sắp xếp tổ chức bộ máy, tinh giản biên chế, đổi mới hoạt động đơn vị sự nghiệp công lập theo Nghị quyết số 18, 19 (nếu có)','sotien'=>'0');
            $a_A[14] = array('tt'=>'','noidung'=>'+ Từ việc tinh giản biên chế tổ chức lại bộ máy (2)','sotien'=>'0');
            $a_A[15] = array('tt'=>'','noidung'=>'+ Từ việc sát nhập các đầu mối, cơ quan, đơn vị (2)','sotien'=>'0');
            $a_A[16] = array('tt'=>'','noidung'=>'+ Từ việc thay đổi cơ chế tự chủ của đơn vị sư nghiệp (3)','sotien'=>'0');
            $a_A[17] = array('tt'=>'','noidung'=>'+ Từ việc sát nhập các xã không đủ điều kiện tiêu chuẩn','sotien'=>'0');
            $a_A[18] = array('tt'=>'7','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2017 chưa sử dụng hết chuyển sang năm 2018','sotien'=>'0');

            //
            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BII[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo (5)','sotien'=>'0');
            $a_BII[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BII[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BII[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 88/2018/NĐ-CP','sotien'=>'0');
            $a_BII[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BII[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BII[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2017','sotien'=>'0');

            $a_BIII = array();
            $a_BIII[0] = array('tt'=>'1','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ tính đủ 12 tháng (6)','sotien'=>'0');
            $a_BIII[1] = array('tt'=>'2','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2018 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BIII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2018 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');


            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon',array('CHITXDT','CTX'));
            $a_A[4]['sotien'] =$model_tudb->sum('hocphi');
            $a_A[5]['sotien'] =$model_tudb->sum('vienphi');
            $a_A[6]['sotien'] =$model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi')- $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu')- $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai','KVXP');

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
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')) - $a_BII[1]['sotien']),
                'BIII'=>(array_sum(array_column($a_BIII,'sotien')))
            );

            return view('reports.thongtu67.mau4a_tt68')
                ->with('model',$model)
                ->with('a_A',$a_A)
                ->with('a_BII',$a_BII)
                ->with('a_BIII',$a_BIII)
                ->with('a_TC',$a_TC)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4a_huyen_excel(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            ///if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
            if(count($model) == 0){
                return view('errors.nodata');
            }
            //foreach($model as $ct){
            //    $donvi = $model_donvi->where('madv',$ct->madv)->first();
            //    $ct->phanloainguon = $donvi->phanloainguon;
            //    $ct->maphanloai = $donvi->maphanloai;
            //}
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt'=>'1','noidung'=>'50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2016 so dự toán Thủ tướng Chính phủ giao năm 2016','sotien'=>'0');
            $a_A[1] = array('tt'=>'2','noidung'=>'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017','sotien'=>'0');
            $a_A[2] = array('tt'=>'3','noidung'=>'Số thu được huy động từ nguồn để lại đơn vị năm 2017','sotien'=>'0');
            $a_A[3] = array('tt'=>'a','noidung'=>'Nguồn huy động từ các đơn vị tự đảm bảo','sotien'=>'0');
            $a_A[4] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[5] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[6] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[7] = array('tt'=>'b','noidung'=>'Nguồn huy động từ các đơn vị chưa tự đảm bảo','sotien'=>'0');
            $a_A[8] = array('tt'=>'+','noidung'=>'Học phí','sotien'=>'0');
            $a_A[9] = array('tt'=>'+','noidung'=>'Viện phí','sotien'=>'0');
            $a_A[10] = array('tt'=>'+','noidung'=>'Nguồn thu khác','sotien'=>'0');
            $a_A[11] = array('tt'=>'4','noidung'=>'Nguồn thực hiện cải cách tiền lương năm 2016 chưa sử dụng hết chuyển sang 2017','sotien'=>'0');

            //
            $a_BI = array();
            $a_BI[0] = array('tt'=>'1','noidung'=>'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ','sotien'=>'0');
            $a_BI[1] = array('tt'=>'','noidung'=>'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo ','sotien'=>'0');
            $a_BI[2] = array('tt'=>'2','noidung'=>'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã','sotien'=>'0');
            $a_BI[3] = array('tt'=>'3','noidung'=>'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp','sotien'=>'0');
            $a_BI[4] = array('tt'=>'4','noidung'=>'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 76/2017/NĐ-CP','sotien'=>'0');
            $a_BI[5] = array('tt'=>'5','noidung'=>'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố','sotien'=>'0');
            $a_BI[6] = array('tt'=>'6','noidung'=>'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008','sotien'=>'0');
            $a_BI[7] = array('tt'=>'7','noidung'=>'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 3115-QĐ/VVPTW','sotien'=>'0');

            $a_BII = array();
            $a_BII[0] = array('tt'=>'1','noidung'=>'Kinh phí hỗ trợ chênh lệch tiền lương cho người có thu nhập thấp (NĐ17/2015/NĐ-CP) và mức lương 1,21 (6 tháng)','sotien'=>'0');
            $a_BII[1] = array('tt'=>'2','noidung'=>'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ','sotien'=>'0');
            $a_BII[2] = array('tt'=>'3','noidung'=>'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2017 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)','sotien'=>'0');
            $a_BII[3] = array('tt'=>'4','noidung'=>'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2017 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015','sotien'=>'0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon',array('CHITXDT','CTX'));
            $a_A[4]['sotien'] =$model_tudb->sum('hocphi');
            $a_A[5]['sotien'] =$model_tudb->sum('vienphi');
            $a_A[6]['sotien'] =$model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien']+  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi')- $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu')- $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien']+  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai','KVXP');

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
                'A'=>($a_A[0]['sotien'] + $a_A[1]['sotien'] +$a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BI'=>(array_sum(array_column($a_BI,'sotien')) - $a_BI[1]['sotien']),
                'BII'=>(array_sum(array_column($a_BII,'sotien')))
            );
            Excel::create('Mau2h_ThPCTHTG',function($excel) use($model,$a_A,$a_BI,$a_BII,$a_TC,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($model,$a_A,$a_BI,$a_BII,$a_TC,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.Mau2h_ThPCTHTGexcel')
                        ->with('model',$model)
                        ->with('a_A',$a_A)
                        ->with('a_BI',$a_BI)
                        ->with('a_BII',$a_BII)
                        ->with('a_TC',$a_TC)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','Mau2h_ThPCTHTG');
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
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
                else
                {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
            }
            else {
                $m_dv = dmdonvi::select('tendv','madv')
                    ->where('madvbc',$madvbc)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('madvbc',$madvbc)
                    ->where('sohieu','TT67_2017')->get();
            }
            $ardv = $m_dv->toArray();
            /*
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            */
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        //$luugr = 0;
                        $luugr = $i;
                        foreach ($ardv as $chitietdv) {
                            $solieu = $model->where('madv', $chitietdv['madv'])->where('linhvuchoatdong', $group[$j]['val']);
                            $d =1;
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
                                $data[$i]['khac'] = 0;
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
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
                $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
                $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
                $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
                $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
                $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
                $data[$gddt]['khac'] = 0;
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];

                /*
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
                */
            }
            else {
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
                $data[4]['khác'] = 0;
                $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
            }

            return view('reports.thongtu67.mau4b_tt68')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
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
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
                else
                {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
            }
            else {
                $m_dv = dmdonvi::select('tendv','madv')
                    ->where('madvbc',$madvbc)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('madvbc',$madvbc)
                    ->where('sohieu','TT67_2017')->get();
            }
            $ardv = $m_dv->toArray();
            /*
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            */
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
            }
            else {
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
            Excel::create('mau4b',function($excel) use($model,$data,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($model,$data,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau4bexcel')
                        ->with('model',$model)
                        ->with('data',$data)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','mau4b');
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
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
                else
                {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
            }
            else {
                $m_dv = dmdonvi::select('tendv','madv')
                    ->where('madvbc',$madvbc)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('madvbc',$madvbc)
                    ->where('sohieu','TT67_2017')->get();
            }
            $ardv = $m_dv->toArray();
            /*
            $model = nguonkinhphi::where('madvbc','like',$inputs['madv'].'%')
                ->where('sohieu','TT67_2017')->get();
            */
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = 0;
                    $data[$i]['bosung'] = 0;
                    $dulieu = $model->where('linhvuchoatdong', $group[$j]['val']);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        //$luugr = 0;
                        $luugr = $i;
                        foreach ($ardv as $chitietdv) {
                            $solieu = $model->where('madv', $chitietdv['madv'])->where('linhvuchoatdong', $group[$j]['val']);
                            $d =1;
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
                                $data[$i]['khac'] = 0;
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                                $data[$i]['bosung'] = $solieu->sum('nhucau') - $solieu->sum('nguonkp');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                $data[$luugr]['khac'] += 0;
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
                $data[$gddt]['khac'] = 0;
                $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
                $data[$gddt]['bosung'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau']-$data[$giaoduc]['nguonkp'] - $data[$daotao]['nguonkp'];

                /*
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
                */
            }
            else {
                for ($i = 0; $i < count($data); $i++) {
                    $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                    $data[$i]['nhucau'] = $solieu->sum('nhucau');
                    $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                    $data[$i]['hocphi'] = $solieu->sum('hocphi');
                    $data[$i]['vienphi'] = $solieu->sum('vienphi');
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                    $data[$i]['bosung'] = $solieu->sum('nhucau')-$solieu->sum('nguonkp');
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['khac'] = 0;
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];
                $data[0]['bosung'] = $data[1]['nhucau'] + $data[2]['nhucau']-$data[1]['nguonkp'] - $data[2]['nguonkp'];

                $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
                $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
                $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
                $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
                $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
                $data[4]['khác'] = 0;
                $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
                $data[4]['bosung'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau']-($model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp']);
            }

            return view('reports.thongtu67.mau4b_tt68bosung')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
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
            if($inputs['madv'] != "")
            {
                $madv = $inputs['madv'];
                $chekdv = dmdonvi::where('madv', $inputs['madv'])->where('phanloaitaikhoan','TH')->get();
                if(count($chekdv) > 0) {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('macqcq', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('macqcq',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
                else
                {
                    $m_dv = dmdonvi::select('tendv', 'madv')
                        ->where('madv', $madv)
                        ->distinct()
                        ->get();
                    $model = nguonkinhphi::where('madv',$madv)
                        ->where('sohieu','TT67_2017')->get();
                }
            }
            else {
                $m_dv = dmdonvi::select('tendv','madv')
                    ->where('madvbc',$madvbc)
                    ->distinct()
                    ->get();
                $model = nguonkinhphi::where('madvbc',$madvbc)
                    ->where('sohieu','TT67_2017')->get();
            }
            $ardv = $m_dv->toArray();
            if(session('admin')->username == 'khthso')
            {
                $model = nguonkinhphi::join('dmdonvibaocao','dmdonvibaocao.madvbc','=','nguonkinhphi.madvbc')
                    ->where('nguonkinhphi.madvbc','like',$inputs['madv'].'%')
                    ->where('dmdonvibaocao.level','T')
                    ->where('nguonkinhphi.sohieu','TT67_2017')->get();
            }
            //dd($model);
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();
            $group = array();
            if(isset($inputs['inchitiet'])) {
                $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $group[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            else {
                $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
                $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            }
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

            if(isset($inputs['inchitiet'])) {
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
                            $d =1;
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
            }
            else {
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
            Excel::create('mau4b_bosung',function($excel) use($model,$data,$m_dv,$inputs){
                $excel->sheet('New sheet', function($sheet) use($model,$data,$m_dv,$inputs){
                    $sheet->loadView('reports.thongtu67.mau4b_bosungexcel')
                        ->with('model',$model)
                        ->with('data',$data)
                        ->with('m_dv',$m_dv)
                        ->with('inputs',$inputs)
                        ->with('pageTitle','mau4b_bosung');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

}

