<?php

use App\dmphucap;
use App\dmphucap_donvi;

/**
 * Created by PhpStorm.
 * User: MLC
 * Date: 7/11/2016
 * Time: 8:45 AM
 */

function getInfoDonVI($hoso, $dmdonvi)
{
    $donvi = array_column($dmdonvi, 'tendv', 'madv');
    if (array_search($hoso->madv, array_keys($donvi)) === false) {
        return '';
    } else {
        return $donvi[$hoso->madv];
    }
}

function getInfoPhongBan($hoso, $dmphongban)
{
    $phongban = array_column($dmphongban, 'tenpb', 'mapb');
    if (array_search($hoso->mapb, array_keys($phongban)) === false) {
        return '';
    } else {
        return $phongban[$hoso->mapb];
    }
}

function getInfoChucVuD($hoso, $dmchucvud)
{
    $chucvu = array_column($dmchucvud, 'tencv', 'macvd');
    if (array_search($hoso->macvd, array_keys($chucvu)) === false) {
        return '';
    } else {
        return $chucvu[$hoso->macvd];
    }
}

function getInfoChucVuCQ($hoso, $dmchucvucq)
{
    $chucvu = array_column($dmchucvucq, 'tencv', 'macvcq');
    //dd(array_search($hoso->macvcq, array_keys($chucvu)));
    if (array_search($hoso->macvcq, array_keys($chucvu)) === false) {
        return $hoso->macvcq;
    } else {
        return $chucvu[$hoso->macvcq];
    }
}

function getInfoPLNB($hoso, $dmngachbac)
{
    $ngachbac = array_column($dmngachbac, 'plnb', 'msngbac');
    if (array_search($hoso->msngbac, array_keys($ngachbac)) === false) {
        return '';
    } else {
        return $ngachbac[$hoso->msngbac];
    }
}

function getInfoTenNB($hoso, $dmngachbac)
{
    //tìm và trả lại mảng
    $ngachbac = array_column($dmngachbac, 'tennb', 'msngbac');
    //dd(array_search($hoso->macvcq, array_keys($chucvu)));
    if (array_search($hoso->msngbac, array_keys($ngachbac)) === false) {
        return '';
    } else {
        return $ngachbac[$hoso->msngbac];
    }
}

//Lấy thông tin các phòng ban có cán bộ giao diện xã
function getPhongBanX()
{
    $m_pb = App\dmphongban::select('mapb', 'tenpb')->wherein('mapb', function ($query) {
        $query->select('mapb')
            ->from('hosocanbo')
            ->where('madv', session('admin')->madv)
            ->distinct();
    })->orderby('sapxep')->get();
    return $m_pb;
}

//Lấy thông tin cán bộ giao diện xã
function getCanBoX()
{
    $m_cb = \Illuminate\Support\Facades\DB::table('hosocanbo')
        ->select('hosocanbo.macanbo', 'hosocanbo.tencanbo', 'hosocanbo.mapb')
        ->where('hosocanbo.madv', session('admin')->madv)
        ->where('hosocanbo.theodoi', '1')
        ->get();
    return $m_cb;
}

function getTenDV($madv)
{
    $model = App\dmdonvi::select('tendv')->where('madv', $madv)->first();
    return isset($model) ? Illuminate\Support\Str::upper($model->tendv) : '';
}

function getPhanLoaiDonVi()
{
    $model = App\dmphanloaidonvi::select('tenphanloai', 'maphanloai')->get()->toarray();
    return array_column($model, 'tenphanloai', 'maphanloai');
}
function getPhanLoaGD()
{
    $model = array(
        'MAMNON',
        'TIEUHOC',
        'THCS',
        'THvaTHCS',
        'PTDTNT'
    );
    return $model;
}

function getTenDB($madvbc)
{
    $model = App\dmdonvibaocao::select('tendvbc')->where('madvbc', $madvbc)->first();
    return isset($model) ? Illuminate\Support\Str::upper($model->tendvbc) : '';
}

function getTheoDoi($tenct)
{
    $kq = 1;
    $kieuct = 'Biên chế';

    $model = App\dmphanloaict::where('tenct', $tenct)->first();
    if ($model != null) {
        $kieuct = $model->kieuct;
        if ($model->phanloaict != 'Đang công tác') {
            $kq = 0;
        }
    }
    return array($kq, $kieuct);
}

function getMaKhoiPB($madv)
{
    $kq = '';
    $model = App\dmdonvi::where('madv', $madv)->first();
    if ($model != null) {
        $kq = $model->makhoipb;
    }
    return $kq;
}

function getLuongNgachBac($manhom, $bac = 1)
{
    $model = App\nhomngachluong::where('manhom', $manhom)->first();
    //$bac = $bac - 1; //do bắt đầu từ bậc 1.
    if ($model != null) {
        if ($bac > $model->baclonnhat) { //bậc lương truyền vào > bậc max trong danh mục => lỗi.
            return '0;0';
        } else {
            if ($model->bacvuotkhung == 0 || $model->bacvuotkhung == 1) {
                $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = $model->vuotkhung;
            } elseif ($bac >= $model->bacvuotkhung) { //bao gồm cả trường hợp mã ngạch ko có vượt khung
                //do bắt đầu từ bậc 1 và bắt đàu vượt khung thì heso = hệ số bậc lương trc
                $heso = $model->heso + ($model->bacvuotkhung - 2) * $model->hesochenhlech;

                $vuotkhung = $model->vuotkhung + ($bac - $model->bacvuotkhung) * $model->namnb;
            } else {
                $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = 0;
            }
        }
    } else { //Không tìm thấy mã ngạch lương
        return '0;0';
    }

    return $heso . ';' . $vuotkhung;
}

function getLuongNgachBac_CBCT($msngbac, $bac = 1)
{
    $model = App\ngachluong::where('msngbac', $msngbac)->first();
    //$bac = $bac - 1; //do bắt đầu từ bậc 1.
    if ($model != null) {
        if ($bac > $model->baclonnhat) { //bậc lương truyền vào > bậc max trong danh mục => lỗi.
            return '0;0';
        } else {
            //mặc định nếu ko có vượt khung bacvuotkhung=0, nhưng do form nhap de giá trị bacvuotkhung=0
            if ($model->bacvuotkhung == 0 || $model->bacvuotkhung == 1) {
                $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = $model->vuotkhung;
            } elseif ($bac >= $model->bacvuotkhung) { //bao gồm cả trường hợp mã ngạch ko có vượt khung
                //do bắt đầu từ bậc 1 và bắt đàu vượt khung thì heso = hệ số bậc lương trc
                $heso = $model->heso + ($model->bacvuotkhung - 2) * $model->hesochenhlech;

                $vuotkhung = $model->vuotkhung + ($bac - $model->bacvuotkhung) * $model->namnb;
            } else {
                $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = 0;
            }
        }
    } else { //Không tìm thấy mã ngạch lương
        return '0;0';
    }

    return $heso . ';' . $vuotkhung;
}

function getPhanLoaiXa($val_null = true)
{
    $model = array(
        'XL1' => 'Xã loại 1',
        'XL2' => 'Xã loại 2',
        'XL3' => 'Xã loại 3'
    );
    /*
    if($val_null){
        return array_merge( array(''=>'--Chọn phân loại xã--'),$model);
    }
    */
    return $model;
}

function getCapDonVi()
{
    return array(
        '1' => 'Đơn vị dự toán cấp 1',
        '2' => 'Đơn vị dự toán cấp 2',
        '3' => 'Đơn vị dự toán cấp 3',
        '4' => 'Đơn vị dự toán cấp 4'
    );
}

function getPhanLoaiTaiKhoan()
{
    $model = array(
        'SD' => 'Đơn vị sử dụng (nhập liệu)',
        'TH' => 'Đơn vị tổng hợp dữ liệu'
    );
    return $model;
}

function getPhanLoaiDinhMuc()
{
    $model = array(
        'HANHCHINH' => 'Khối hành chính sự nghiệp',
        'YTE' => 'Khối y tế',
    );
    return $model;
}

function getPhamViTongHop()
{
    $model = array(
        'KHOI' => 'Khối; Sở, ban ngành',
        'HUYEN' => 'Toàn huyện; Tất cả các sở, ban ngành'
    );
    return $model;
}

function getNhomDonVi()
{
    $model = array(
        'H' => 'Đơn vị nhập liệu, tổng hợp dữ liệu',
        'T' => 'Đơn vị tổng hợp, thống kê dữ liệu toàn tỉnh'
    );
    return $model;
}

function getPhanLoaiNguon()
{
    //Theo Nghị định số 16/2015/NĐ-CP
    return array(
        'NGANSACH' => 'Nguồn kinh phí do Nhà nước bảo đảm chi thường xuyên',
        'CHITXDT' => 'Nguồn kinh phí do đơn vị tự bảo đảm chi thường xuyên và chi đầu tư',
        'CTX' => 'Nguồn kinh phí do đơn vị tự bảo đảm chi thường xuyên',
        'CTXMP' => 'Nguồn kinh phí do đơn vị tự bảo đảm một phần chi thường xuyên'
    );
}

function getDiaBan($val_null = true)
{
    $model = array(
        'DBKK' => 'Khu vực KTXH ĐBKK',
        'BGHD' => 'Khu vực biên giới, hải đảo',
        'DBTD' => 'Khu vực trọng điểm, phức tạp về an ninh trật tự'
    );
    if ($val_null) {
        return array_merge(array('' => '--Chọn phân loại địa bàn--'), $model);
    }
    return $model;
}

function getColTongHop()
{
    return array_column(App\dmphucap::where('tonghop', 1)->orderby('stt')->get()->toarray(), 'mapc');
    /*
    return array('heso','hesopc','vuotkhung','pcct','hesobl', 'luonghd','pcud61',
        'pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld',
        'pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang',
        'pccovu','pclt','pcd','pctr','pctnvk','pcbdhdcu','pcthni');
    */
}

function getPLCTTongHop()
{
    return array_column(App\dmphanloaict::where('tonghop', 1)->get()->toarray(), 'mact');
    /*
    return array('heso','hesopc','vuotkhung','pcct','hesobl', 'luonghd','pcud61',
        'pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld',
        'pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang',
        'pccovu','pclt','pcd','pctr','pctnvk','pcbdhdcu','pcthni');
    */
}

function getThang()
{
    return array(
        '01' => '01', '02' => '02', '03' => '03',
        '04' => '04', '05' => '05', '06' => '06',
        '07' => '07', '08' => '08', '09' => '09',
        '10' => '10', '11' => '11', '12' => '12'
    );
}

function getThangBC()
{
    return array(
        'ALL' => 'Tất cả các tháng',
        '01' => '01', '02' => '02', '03' => '03',
        '04' => '04', '05' => '05', '06' => '06',
        '07' => '07', '08' => '08', '09' => '09',
        '10' => '10', '11' => '11', '12' => '12'
    );
}

function getThangBC_nhucau()
{
    return array(
        'ALL' => 'Tất cả các tháng',
        '07' => '07', '08' => '08', '09' => '09',
        '10' => '10', '11' => '11', '12' => '12'
    );
}

function getNamTL()
{
    $a_tl = array();
    for ($i = 2010; $i <= date('Y') + 4; $i++) {
        $a_tl[$i] = $i;
    }
    return $a_tl;
}

function getNam($all = false)
{
    $a_kq = array();
    if ($all) {
        $a_kq['ALL'] = '--Tất cả các năm--';
    }
    for ($i = date('Y') - 4; $i <= date('Y') + 2; $i++) {
        $a_kq[$i] = $i;
    }
    return $a_kq;
}

function getGeneralConfigs()
{
    return \App\GeneralConfigs::all()->first()->toArray();
}


function getLinhVucHoatDong($val_null = true)
{
    $model = array_column(App\dmkhoipb::all()->toArray(), 'tenkhoipb', 'makhoipb');
    if ($val_null) {
        $a_kq = array('' => '--Chọn lĩnh vực hoạt động--');
        foreach ($model as $key => $val) {
            $a_kq[$key] = $val;
        }
        return $a_kq;
        //return array_merge( array(''=>'--Chọn lĩnh vực hoạt động--'),$model);
    }
    return $model;
}

function getNguonKP($val_null = true)
{
    $model = array_column(App\dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');

    if ($val_null) {
        $a_kq = array('' => '--Chọn nguồn kinh phí--');
        foreach ($model as $key => $val) {
            $a_kq[$key] = $val;
        }
        return $a_kq;
    }
    return $model;
}

function getNhomCongTac($val_null = true)
{
    $model = array_column(App\dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
    if ($val_null) {
        $a_kq = array('' => '--Chọn nhóm công tác--');
        return array_merge($a_kq, $model);
    }
    return $model;
}

function getThongTuQD($val_null = true)
{
    $model = array_column(App\dmthongtuquyetdinh::all()->toArray(), 'tenttqd', 'sohieu');
    if ($val_null) {
        $a_kq = array('' => '--Chọn thông tư quyết định--');
        return array_merge($a_kq, $model);
    }
    return $model;
}

function getPhongBan($val_null = true)
{
    $model = array_column(App\dmphongban::where('madv', session('admin')->madv)->get()->toArray(), 'tenpb', 'mapb');
    if ($val_null) {
        $a_kq = array('' => '-- Chọn khối/tổ công tác --');
        return array_merge($a_kq, $model);
    }
    return $model;
}

function getNgachLuong()
{
    return array_column(App\ngachluong::all()->toArray(), 'tenngachluong', 'msngbac');
}

function getPhanLoaiCT($val_null = true)
{
    $model = array_column(App\dmphanloaict::all()->toArray(), 'tenct', 'mact');
    if ($val_null) {
        $a_kq = array('' => '-- Chọn phân loại công tác --');
        foreach ($model as $key => $val) {
            $a_kq[$key] = $val;
        }
        return $a_kq;
    }
    return $model;
}

function getChucVuCQ($val_null = true)
{
    /*bỏ phân loại do phát sinh nhiều maphanloai mới
    if(session('admin')->level=='SA' || session('admin')->level=='SSA'){
        $model = App\dmchucvucq::where('maphanloai',session('admin')->maphanloai)->get();
    }else{
        $model = App\dmchucvucq::where('maphanloai',session('admin')->maphanloai)
        ->wherein('madv',['SA',session('admin')->madv])->get();
    }
    $model = array_column($model->toArray(),'tencv','macvcq');
    */
    $model = array_column(App\dmchucvucq::wherein('madv', ['SA', session('admin')->madv])
        ->get()->toArray(), 'tencv', 'macvcq');

    //$model = array_column(App\dmchucvucq::where('maphanloai',session('admin')->maphanloai)->get()->toArray(),'tencv','macvcq');
    if ($val_null) {
        $a_kq = array('' => '-- Chọn chức vụ --');
        //dd($model);
        foreach ($model as $key => $val) {
            $a_kq[$key] = $val;
        }
        //return array_merge($a_kq,$model);
        return $a_kq;
    }
    return $model;
}

function getDonViTinh()
{
    return array('1' => 'Đồng', '2' => 'Nghìn đồng', '3' => 'Triệu đồng');
}

function getTextStatus($status)
{
    //text-danger; text-warning; text-success; text-info
    $a_trangthai = array(
        'CHUALUONG' => 'text-danger',
        'CHUATAO' => 'text-danger',
        'CHUADL' => 'text-danger', //dùng cho đơn vị chủ quản - chưa có đơn vị cấp dưới nào gửi dữ liệu
        'CHOGUI' => 'text-info',
        'DAGUI' => 'text-success',
        'TRALAI' => 'text-danger',
        'CHUADAYDU' => 'text-warning',
        'CHUAGUI' => 'text-info', //dùng cho đơn vị chủ quản - các đơn vị cấp dưới đã có dữ liệu nhưng chưa gửi đi
        'GUILOI' => 'text-danger',
        'BANGLUONG' => 'text-success',
    );
    return isset($a_trangthai[$status]) ? $a_trangthai[$status] : '';
}

function getStatus()
{
    return array(
        'CHUALUONG' => 'Chưa tạo bảng lương',
        'CHONHAN' => 'Dữ liệu chờ nhận',
        'CHUATAO' => 'Dữ liệu chưa khởi tạo',
        'CHOGUI' => 'Dữ liệu chờ gửi',
        'DAGUI' => 'Dữ liệu đã gửi',
        'TRALAI' => 'Dữ liệu bị trả lại',
        'CHUADAYDU' => 'Dữ liệu chưa đầy đủ',
        'CHUAGUI' => 'Dữ liệu chờ gửi',
        'CHUADL' => 'Dữ liệu chưa được gửi lên',
        'GUILOI' => 'Dữ liệu bị lỗi',
        'BANGLUONG' => 'Đã có chi trả lương',
        'DACHUYEN' => 'Đã được nhận hồ sơ',
    );
}

function getPhanLoaiPhuCap()
{
    return array('0' => 'Hệ số', '1' => 'Số tiền', '2' => 'Phần trăm', '3' => 'Ẩn');
}

function getCongThucTinhPC($admin = true)
{
    $a_pc = array(
        'heso',
        'vuotkhung',
        'pccv',
        'hesobl',
        'pcthni',
        'pctn',
        'pcudn',
        'pctaicu',
    );
    if ($admin) {
        return array_column(dmphucap::wherein('mapc', $a_pc)->get()->toarray(), 'form', 'mapc');
    } else {
        $model = dmphucap_donvi::where('madv', session('admin')->madv)->where('pccoso', '1')->get();
        if (count($model) > 0) {
            $a_pc = array_column($model->toarray(), 'mapc');
        }
        return array_column(dmphucap_donvi::where('madv', session('admin')->madv)
            ->wherein('mapc', $a_pc)
            ->where('phanloai', '<', '3')->get()->toarray(), 'form', 'mapc');
    }
}

function SapXepPhuCap($m_phucap)
{
    $a_ketqua = [];
    $a_heso = $m_phucap->where('phanloai', '0')->keyBy('mapc')->toarray();
    $a_sotien = $m_phucap->where('phanloai', '1')->keyBy('mapc')->toarray();
    $a_phantram = $m_phucap->where('phanloai', '2')->keyBy('mapc')->toarray();
    $a_ketqua = array_merge($a_heso, $a_sotien);
    $i = 1; //Biến lưu cho trường hợp lặp vô hạn
    //lấy 
    while(count($a_phantram)>0){
        $mapc = array_key_first($a_phantram);
        $b_chk = true;
        foreach(explode(',', $a_phantram[$mapc]['congthuc']) as $ct){
            if ($ct != '' && !array_key_exists($ct, $a_ketqua)){
                $b_chk = false;
            }
        }
        //dd($b_chk);
        if($b_chk){
            $a_ketqua[$mapc] = $a_phantram[$mapc];
            unset($a_phantram[$mapc]);
        }
        //dd($a_phantram);
        $i++;
        if($i>=100){
            return [];            
        }
    }
    //$a_ketqua = array_merge($a_ketqua, $a_phantram);
    return $a_ketqua;
}

function getColPhuCap()
{ //xem bỏ đi vì có danh mục phụ cấp
    return array(
        'pccovu' => 'Công vụ',
        'pctnn' => 'Thâm niên nghề',
        'pccv' => 'Chức vụ',
        'pcthni' => 'Lâu năm',
        'pckn' => 'Kiêm nhiệm',
        'pctn' => 'Trách nhiệm',
        'pcudn' => 'Ưu đãi ngành',
        'pcct' => 'Ghép lớp',
        'pckv' => 'Khu vực',
        'pcth' => 'Thu hút',
        'pcdbn' => 'Đặc biệt(đặc thù)',
        'pcld' => 'Lưu động',
        'pcdh' => 'Độc hại',
        'pcdbqh' => 'Đại biểu HĐND',
        'pcvk' => 'Cấp ủy viên',
        'pcbdhdcu' => 'Bồi dưỡng HĐCU',
        'pcdang' => 'Công tác Đảng',
        'pclt' => 'Phân loại xã',
        'pcdd' => 'Đắt đỏ',
        'pcct' => 'Ghép lớp',
        'pck' => 'Phụ cấp khác'
        //'pctnvk'=>'Thâm niên vượt khung',
        //'pckct'=>'',
        //'pcd'=>'',
        //'pctr'=>'',
    );
}

function getColPhuCap_BaoCao()
{
    return array(
        'pckv' =>   'Phụ cấp</br>khu vực',
        'pccv' =>   'Phụ cấp</br>chức vụ',
        //'pctnvk' => 'Phụ cấp</br>thâm niên</br>vượt khung',
        'pcudn' =>  'Phụ cấp</br>ưu đãi</br>ngành',
        'pcth' =>   'Phụ cấp</br>thu hút',
        'pcthni' => 'Phụ cấp</br>công tác</br>lâu năm',
        'pccovu' => 'Phụ cấp</br>công vụ',
        'pcdang' => 'Phụ cấp</br>công tác</br>Đảng',
        'pctnn' =>  'Phụ cấp</br>thâm niên</br>nghề',
        'pcct' =>    'Phụ cấp</br>ghép lớp',
        'pctn' =>   'Phụ cấp</br>trách nhiệm',
        'pckn' =>   'Phụ cấp</br>kiêm nhiệm',
        'pclt' =>   'Phụ cấp</br>phân loại</br>xã',
        'pcdd' =>   'Phụ cấp</br>đắt đỏ',
        'pcdbqh' =>  'Phụ cấp</br>đại biểu</br>HĐND',
        'pcvk' =>    'Phụ cấp</br>cấp ủy</br>viên',
        'pcbdhdcu' => 'Phụ cấp</br>bồi dưỡng</br>HĐCU',
        'pcdbn' =>   'Phụ cấp</br>đặc biệt</br>(đặc thù)',
        'pcld' =>    'Phụ cấp</br>lưu động',
        'pcdh' =>    'Phụ cấp</br>độc hại',
        'pck' =>    'Phụ cấp</br>khác'
    );
}

function getColPhuCap_Excel()
{
    return array(
        'pckv' => array('infor' => 'Khu vực', 'col' => null),
        'pccv' => array('infor' => 'Chức vụ', 'col' => null),
        //'pctnvk' => array('infor'=> 'Thâm niên vượt khung', 'col'=>null),
        'pcudn' => array('infor' => 'Ưu đãi ngành', 'col' => null),
        'pcth' => array('infor' => 'Thu hút', 'col' => null),
        'pcthni' => array('infor' => 'Công tác lâu năm', 'col' => null),
        'pccovu' => array('infor' => 'Công vụ', 'col' => null),
        'pcdang' => array('infor' => 'Công tác Đảng', 'col' => null),
        'pctnn' => array('infor' => 'Thâm niên nghề', 'col' => null),
        'pcct' => array('infor' => 'Ghép lớp', 'col' => null),
        'pctn' => array('infor' => 'Trách nhiệm', 'col' => null),
        'pckn' => array('infor' => 'Kiêm nhiệm', 'col' => null),
        'pclt' => array('infor' => 'Khân loại xã', 'col' => null),
        'pcdd' => array('infor' => 'Đắt đỏ', 'col' => null),
        'pcdbqh' => array('infor' => 'Đại biểu HĐND', 'col' => null),
        'pcvk' => array('infor' => 'Cấp ủy viên', 'col' => null),
        'pcbdhdcu' => array('infor' => 'Bồi dưỡng HĐCU', 'col' => null),
        'pcdbn' => array('infor' => 'Đặc biệt (đặc thù)', 'col' => null),
        'pcld' => array('infor' => 'Lưu động', 'col' => null),
        'pcdh' => array('infor' => 'Độc hại', 'col' => null),
        'pck' => array('infor' => 'Phụ cấp khác', 'col' => null)
    );
}

function getPhanLoaiTamNgungTheoDoi()
{
    return array(
        'THAISAN' => 'Nghỉ thai sản',
        'DUONGSUC' => 'Dưỡng sức sau sinh',
        'DINHCHI' => 'Đình chỉ công tác',
        'KYLUAT' => 'Kỷ luật',
        'NGHIPHEP' => 'Nghỉ phép',
        'NGHIOM' => 'Nghỉ ốm',
        'DAINGAY' => 'Nghỉ dài ngày',
        'KHONGLUONG' => 'Nghỉ không lương',
    );
}

function getTronSo()
{
    $a_kq = array();
    for ($i = -3; $i < 8; $i++) {
        $a_kq[$i] = $i;
    }
    return $a_kq;
}

function getPhanLoaiBangLuong()
{
    return array(
        'BANGLUONG' => 'Bảng chi trả lương',
        'TRUYLINH' => 'Bảng chi trả truy lĩnh',
        'TRUC' => 'Bảng chi trả theo ngày công làm việc',
        'CTPHI' => 'Bảng chi trả công tác phí',
        'TRICHNOP' => 'Bảng trích nộp lương',
        'KHAC' => 'Bảng chi trả khác',
    );
}

function getPhuCapNopBH()
{
    return array('heso', 'pccv', 'vuotkhung', 'pctnn', 'pctnvk');
}

function getCoChu()
{
    return array('8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12');
}

function getPhanLoaiKiemNhiem($danhmuc = false)
{
    if ($danhmuc) {
        return array(
            'CONGTAC' => 'Chức vụ chính',
            'CHUCVU' => 'Kiêm nhiệm chức vụ',
            'KHONGCT' => 'Không chuyên trách',
            'QUANSU' => 'Kiêm nhiệm quân sự',
            'DBHDND' => 'Kiêm nhiệm đại biểu HĐND',
            'CAPUY' => 'Kiêm nhiệm cấp ủy viên',
            'CONGDONG' => 'Kiêm nhiệm cộng đồng',
            'MOTCUA' => 'Kiêm nhiệm một cửa',
            'TINHNGUYEN' => 'Đội thanh niên tình nguyện',
        );
    } else {
        return array(
            'CHUCVU' => 'Kiêm nhiệm chức vụ',
            'KHONGCT' => 'Không chuyên trách',
            'QUANSU' => 'Kiêm nhiệm quân sự',
            'DBHDND' => 'Kiêm nhiệm đại biểu HĐND',
            'CAPUY' => 'Kiêm nhiệm cấp ủy viên',
            'CONGDONG' => 'Kiêm nhiệm cộng đồng',
            'MOTCUA' => 'Kiêm nhiệm một cửa',
            'TINHNGUYEN' => 'Đội thanh niên tình nguyện',
        );
    }
}

function getPhanLoaiNhanVien()
{
    return array(
        '0' => 'Cán bộ (Nhân viên)',
        '1' => 'Thủ trưởng (lãnh đạo)'
    );
}

function getPhanLoaiNangLuong()
{
    return array(
        'THAMNIENNGHE' => 'Nâng lương thâm niên nghề',
        'NGACHBAC' => 'Nâng lương ngạch bậc'
    );
}


function getPhanLoaiCanBo()
{
    return array(
        '1' => 'Cán bộ đang công tác',
        '2' => 'Cán bộ tạm ngưng theo dõi',
        '3' => 'Cán bộ đang điều động',
        '4' => 'Cán bộ được điều động',
        '9' => 'Cán bộ đã thôi công tác'
    );
}

function getPhanLoaiCanBo_CongTac()
{
    return array(
        '1' => 'Cán bộ đang công tác',
        '2' => 'Cán bộ đang đi công tác, đi học',
        '3' => 'Cán bộ đang điều động',
        '4' => 'Cán bộ được điều động đến',
        '5' => 'Cán bộ công tác tại vùng cao, biên giới, hải đảo',
        '6' => 'Cán bộ đang tập sự, thử việc',
    );
}

function getPhanLoaiLuanChuyen()
{
    return array(
        'DIEUDONG' => 'Điều động cán bộ',
        'LUANCHUYEN' => 'Luân chuyển cán bộ',
        'DIEUDONGDEN' => 'Được điều động đến',
        'LUANCHUYENDEN' => 'Được luân chuyển đến',
    );
}

function getPhanLoaiThoiCongTac()
{
    return array(
        'NGHIHUU' => 'Nghỉ hưu',
        'NGHIVIEC' => 'Xin nghỉ việc',
        'BUOCNGHI' => 'Buộc thôi việc',
        'DIEUDONG' => 'Hết thời gian điều động',
        'LUANCHUYEN' => 'Luân chuyển cán bộ',
        'KHAC' => 'Khác'
    );
}

function getNopBaoHiem()
{
    return array(
        '0' => 'Không nộp bảo hiểm',
        '1' => 'Có nộp bảo hiểm',
    );
}

function getPhanLoaiTruyLinh()
{
    return array(
        'MSNGBAC' => 'Truy lĩnh lương ngạch bậc; vượt khung',
        'CHUCVU' => 'Truy lĩnh hệ số chức vụ',
        'TNN' => 'Truy lĩnh thâm niên nghề',
        'NGAYLV' => 'Truy lĩnh ngày công làm việc', //chưa nghiên cứu chức năng này
        'KHAC' => 'Truy lĩnh khác',
    );
}

function getTangGiamLuong()
{
    return array(
        'TANG' => 'Tăng lương (khen thưởng)',
        'GIAM' => 'Giảm lương (đóng góp, trích nộp)',
        //'KPCD' => 'Trừ kinh phí công đoàn',
    );
}

function getKieuTangGiamLuong()
{
    return array(
        'SOTIEN' => 'Theo số tiền',
        'NGAYLV' => 'Theo ngày công',
    );
}

//Nguồn truy lĩnh sau lấy trong db
function getNguonTruyLinh()
{
    $model = App\dmnguonkinhphi::all();
    $nkp_df = $model->where('macdinh', 1)->first();
    if (!isset($nkp_df)) {
        $nkp_df = $model->first();
    }
    return array(
        $nkp_df->manguonkp => session('admin')->luongcoban,
    );
}

function getNguonTruyLinh_df()
{
    $model = App\dmnguonkinhphi::all()->first();
    $nkp_df = $model->where('macdinh', 1)->first();
    if (count($nkp_df) == 0) {
        $nkp_df = $model->first();
    }
    return array(
        $nkp_df->manguonkp => session('admin')->luongcoban,
    );
}

function getPhanLoaiChiTietNangLuong()
{
    return array(
        'DUNGHAN' => 'Nâng lương đúng thời hạn',
        'TRUOCHAN' => 'Nâng lương trước thời hạn',
    );
}

function getPhanLoaiChiTietChiLuong()
{
    return array(
        'TRUC' => 'Trực công tác',
        'CTPHI' => 'Công tác phí',
        'XAXE' => 'Xăng xe, điện thoại',
        'THUONG' => 'Chi thưởng',
        'KHAC' => 'Chi khác',
    );
}

function getMaCongTacNhuCau()
{
    return array('1506672780', '1506673604');
}

function setArrayAll($array, $noidung = 'Tất cả')
{
    $a_kq = ['ALL' => $noidung];
    foreach ($array as $k => $v) {
        $a_kq[(string)$k] = $v;
    }
    return $a_kq;
}
