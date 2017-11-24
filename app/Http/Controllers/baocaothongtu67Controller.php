<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\chitieubienche;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaict;
use App\nguonkinhphi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class baocaothongtu67Controller extends Controller
{
    function index() {
        if (Session::has('admin')) {
            $macqcq=session('admin')->madv;
            $model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();
            $model_dvbc=dmdonvibaocao::where('level','H')->get();

            return view('reports.thongtu67.index')
                ->with('model_dv', $model_dv)
                ->with('model_dvbc', $model_dvbc)
                ->with('furl','/tong_hop_bao_cao/')
                ->with('pageTitle','Báo cáo tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function mau2a1_tt67() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $ar_III = array();
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp tỉnh');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp huyện');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp xã');

            $ar_IV = array();
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp tỉnh');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp huyện');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp xã');

            return view('reports.thongtu67.mau2a1_tt67')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2a2_tt67() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Sự nghiệp giáo dục - đào tạo');
            $ar_I[]=array('tt'=>'-','noidung'=>'Giáo dục');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đào tạo');
            $ar_I[]=array('tt'=>'2','noidung'=>'Sự nghiệp y tế');
            $ar_I[]=array('tt'=>'3','noidung'=>'Sự nghiệp khoa học-công nghệ');
            $ar_I[]=array('tt'=>'4','noidung'=>'Sự nghiệp văn hóa thông tin');
            $ar_I[]=array('tt'=>'5','noidung'=>'Sự nghiệp phát thanh truyền hình');
            $ar_I[]=array('tt'=>'6','noidung'=>'Sự nghiệp thể dục - thể thao');
            $ar_I[]=array('tt'=>'7','noidung'=>'Sự nghiệp đảm bảo xã hội');
            $ar_I[]=array('tt'=>'8','noidung'=>'Sự nghiệp kinh tế');
            $ar_I[]=array('tt'=>'9','noidung'=>'Sự nghiệp môi trường');
            $ar_I[]=array('tt'=>'10','noidung'=>'Quản lý nhà nước, đảng, đoàn thể');
            $ar_I[]=array('tt'=>'-','noidung'=>' Quản lý NN');
            $ar_I[]=array('tt'=>'-','noidung'=>'Đảng, đoàn thể');

            $ar_III = array();
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp tỉnh');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp huyện');
            $ar_III[]=array('tt'=>'-','noidung'=>'Cấp xã');

            $ar_IV = array();
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp tỉnh');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp huyện');
            $ar_IV[]=array('tt'=>'-','noidung'=>'Ủy viên cấp xã');

            return view('reports.thongtu67.mau2a2_tt67')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('ar_III',$ar_III)
                ->with('ar_IV',$ar_IV)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2b_tt67() {
        if (Session::has('admin')) {
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();

            $ar_I = array();
            $ar_I[]=array('tt'=>'1','noidung'=>'Nguyên bí thư, chủ tịch');
            $ar_I[]=array('tt'=>'2','noidung'=>'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng');
            $ar_I[]=array('tt'=>'3','noidung'=>'Các chức danh còn lại');


            return view('reports.thongtu67.mau2b')
                ->with('furl','/tong_hop_bao_cao/')
                ->with('ar_I',$ar_I)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo nhu cầu kinh phí thực hiện nghị định 47/2017/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2c_tt67() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.Mau2c_BcNCCL')
                ->with('pageTitle','BÁO CÁO NHU CẦU CHÊNH LỆCH');
        } else
            return view('errors.notlogin');
    }

    function mau2d_tt67() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.Mau2d_ThKPTT')
                ->with('pageTitle','TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }
    function mau2e_tt67() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.Mau2e_ThKPTG')
                ->with('pageTitle','TỔNG HỢP KINH PHÍ TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }
    function mau2g_tt67() {
        if (Session::has('admin')) {
            return view('reports.thongtu67.Mau2g_ThPCUDTG')
                ->with('pageTitle','TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
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
                $model_bangluong_ct = bangluong_ct::where('mabl',$model_bangluong->mabl)->get();
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
                if(isset($chitiet)>0){
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
                }
            }
            //dd($ar_I);
            
            $ar_II = array();
            $ar_II['soluongduocgiao'] = isset($model_bienche->soluongduocgiao) ? $model_bienche->soluongduocgiao : 0;
            $ar_II['soluongbienche'] = isset($model_bienche->soluongbienche) ? $model_bienche->soluongbienche : 0;
            if(session('admin')->maphanloai == 'KVXP' && isset($model_bangluong_ct)){
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
                $model_bangluong_ct = bangluong_ct::where('mabl',$model_bangluong->mabl)->get();
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

    function mau2c_donvi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {

            return view('reports.thongtu67.donvi.mau2c')
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau2d_donvi()
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {

            return view('reports.thongtu67.donvi.mau2d')
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
}
