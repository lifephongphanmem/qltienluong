<?php

use App\dmdonvi;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dsdonviquanly;

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

function getPhanLoaiThuyetMinh()
{
    return [
        'CANBO' => 'Thuyết minh theo cán bộ',
        'PHUCAP' => 'Thuyết minh theo phụ cấp',
    ];
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
        'XL3' => 'Xã loại 3',
        'PL1' => 'Phường loại 1',
        'PL2' => 'Phường loại 2',
        'PL3' => 'Phường loại 3',
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

function getColNhuCau()
{
    return array_column(App\dmphucap::where('tonghop', 1)->orderby('stt')->get()->toarray(), 'mapc');
    /*
    return array('heso','hesopc','vuotkhung','pcct','hesobl', 'luonghd','pcud61',
        'pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld',
        'pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang',
        'pccovu','pclt','pcd','pctr','pctnvk','pcbdhdcu','pcthni');
    */
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

function getColDuToan()
{
    return array_column(App\dmphucap::where('dutoan', 1)->orderby('stt')->get()->toarray(), 'mapc');
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

function getPLCTNhuCau()
{
    return array_column(App\dmphanloaict::where('nhucaukp', 1)->get()->toarray(), 'mact');
    /*
    return array('heso','hesopc','vuotkhung','pcct','hesobl', 'luonghd','pcud61',
        'pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld',
        'pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang',
        'pccovu','pclt','pcd','pctr','pctnvk','pcbdhdcu','pcthni');
    */
}

function getPLCTDuToan()
{
    return array_column(App\dmphanloaict::where('dutoan', 1)->get()->toarray(), 'mact');
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
    return array('1' => 'Đồng', '1000' => 'Nghìn đồng', '1000000' => 'Triệu đồng');
}

//Hàm dùng kết hợp với định dạnh số
function getDVT()
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
        'CHOGUI' => 'text-dark',
        'DAGUI' => 'text-success',
        'TRALAI' => 'text-danger',
        'CHUADAYDU' => 'text-warning',
        'CHUAGUI' => 'text-dark', //dùng cho đơn vị chủ quản - các đơn vị cấp dưới đã có dữ liệu nhưng chưa gửi đi
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
            //->where('phanloai', '<', '3')
            ->get()
            ->toarray(), 'form', 'mapc');
    }
}

function SapXepPhuCap($m_phucap)
{
    //dd($m_phucap);
    $a_ketqua = [];
    $a_heso = $m_phucap->where('phanloai', '0')->keyBy('mapc')->toarray();
    $a_sotien = $m_phucap->where('phanloai', '1')->keyBy('mapc')->toarray();
    $a_phantram = $m_phucap->where('phanloai', '2')->keyBy('mapc')->toarray();

    $a_ketqua = array_merge($a_heso, $a_sotien);
    $chk = 1; //Biến lưu cho trường hợp lặp vô hạn
    //lấy 
    //dd($a_ketqua);

    while (count($a_phantram) > 0) {
        foreach ($a_phantram as $key => $val) {
            $b_chk = true;
            foreach (explode(',', $a_phantram[$key]['congthuc']) as $ct) {
                if ($ct != '' && !array_key_exists($ct, $a_ketqua)) {
                    $b_chk = false;
                }
            }
            if ($b_chk) {
                $a_ketqua[$key] = $a_phantram[$key];
                unset($a_phantram[$key]);
            }
        }

        $chk++;
        if ($chk >= 100) {
            //dd($a_phantram);       
            return ['trangthai' => false, 'mapc' => array_keys($a_phantram)];
        }
    }
    //$a_ketqua = array_merge($a_ketqua, $a_phantram);
    //dd($a_ketqua);
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
        'THUYETMINH' => 'Thuyết minh chi tiết',
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
        '7' => 'Cán bộ bị kỷ luật',
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
        // '7' => 'Cán bộ bị kỷ luật',
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
    if ($nkp_df == null) {
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

function getBaoCaoDeQuy($categories, $maphanloai_goc = '', $char = '')
{
    foreach ($categories as $key => $item) {
        // Nếu là chuyên mục con thì hiển thị
        if ($item['maphanloai_nhom'] == $maphanloai_goc) {
            echo '<tr>';
            echo '<td>';
            echo $char . $item['tenphanloai_nhom'];
            echo '</td>';
            echo '</tr>';

            // Xóa chuyên mục đã lặp
            unset($categories[$key]);

            // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
            getBaoCaoDeQuy($categories, $item['maphanloai_goc'], $char . '|---');
        }
    }
}

function chkDiv0($dbl)
{
    return chkDbl($dbl) <= 0 ? 1 : chkDbl($dbl);
}


//2023.06.02 Xây dựng danh mục báo cáo nhu cầu kinh phí
function getBaoCaoNhuCauKP()
{
    return array(
        'TT67_2017' => 'Thông tư số 67/2017/TT-BTC',
        'TT68_2018' => 'Thông tư số 68/2018/TT-BTC',
        'TT46_2019' => 'Thông tư số 46/2019/TT-BTC',
        'TT78_2022' => 'Thông tư số 78/2022/TT-BTC',
    );
}

function getHCSN_2c()
{
    //tonghop: 9 => bỏ qua
    //tonghop: 0 => Tính toán số liêu theo công thức
    //tonghop: 1 => lấy các ô trong chitiet để cộng

    $ar_I[0] = array('style' => '', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'phanloai' => '1', 'chitiet' => [1, 2],);
    $ar_I[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Giáo dục', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['GD',]],);
    $ar_I[2] = array('style' => '', 'tt' => '-', 'noidung' => 'Đào tạo', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['DT',]],);
    $ar_I[3] = array('style' => '', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['YTE',]],);
    $ar_I[4] = array('style' => '', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['KHCN',]],);
    $ar_I[5] = array('style' => '', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['VHTT'],],);
    $ar_I[6] = array('style' => '', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['PTTH',]],);
    $ar_I[7] = array('style' => '', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['TDTT',]],);
    $ar_I[8] = array('style' => '', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['DBXH',]],);
    $ar_I[9] = array('style' => '', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['KT',]],);
    $ar_I[10] = array('style' => '', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['MT',]],);
    $ar_I[11] = array('style' => '', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['QLNN', 'DDT']],);
    return $ar_I;
}

function getHCSN_4b() //Chưa dùng
{
    //tonghop: 9 => bỏ qua
    //tonghop: 0 => Tính toán số liêu theo công thức
    //tonghop: 1 => lấy các ô trong chitiet để cộng

    $data = array();
    $data[] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
    $data[] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
    $data[] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
    $data[] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
    $data[] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
    $data[] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
    $data[] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);



    $ar_I[0] = array('style' => '', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'phanloai' => '1', 'chitiet' => [1, 2],);
    $ar_I[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Trong đó', 'phanloai' => '9', 'chitiet' => '',);
    $ar_I[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Giáo dục', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['GD',]],);
    $ar_I[2] = array('style' => '', 'tt' => '-', 'noidung' => 'Đào tạo', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['DT',]],);
    $ar_I[3] = array('style' => '', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['YTE',]],);
    $ar_I[4] = array('style' => '', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['KHCN',]],);
    $ar_I[5] = array('style' => '', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['VHTT'],],);
    $ar_I[6] = array('style' => '', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['PTTH',]],);
    $ar_I[7] = array('style' => '', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['TDTT',]],);
    $ar_I[8] = array('style' => '', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['DBXH',]],);
    $ar_I[9] = array('style' => '', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['KT',]],);
    $ar_I[10] = array('style' => '', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['MT',]],);
    $ar_I[11] = array('style' => '', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => ['QLNN', 'DDT']],);
    return $ar_I;
}

function getHCSN_vn()
{
    //tonghop: 9 => bỏ qua
    //tonghop: 0 => Tính toán số liêu theo công thức
    //tonghop: 1 => lấy các ô trong chitiet để cộng
    $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => 'I', 'noidung' => 'KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ', 'phanloai' => '2', 'chitiet' => [2, 5, 6, 7, 8, 9, 10, 11, 12, 13]);
    $ar_I[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Trong đó', 'phanloai' => '9', 'chitiet' => []);
    $ar_I[2] = array('style' => '', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'phanloai' => '1', 'chitiet' => [3, 4]);
    $ar_I[3] = array('style' => '', 'tt' => '-', 'noidung' => 'Giáo dục', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'GD',]);
    $ar_I[4] = array('style' => '', 'tt' => '-', 'noidung' => 'Đào tạo', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'DT',]);
    $ar_I[5] = array('style' => '', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'YTE',]);
    $ar_I[6] = array('style' => '', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'KHCN',]);
    $ar_I[7] = array('style' => '', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'VHTT',]);
    $ar_I[8] = array('style' => '', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'PTTH',]);
    $ar_I[9] = array('style' => '', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'TDTT',]);
    $ar_I[10] = array('style' => '', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'DBXH',]);
    $ar_I[11] = array('style' => '', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'KT',]);
    $ar_I[12] = array('style' => '', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'MT',]);
    $ar_I[13] = array('style' => '', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể', 'phanloai' => '1', 'chitiet' => [14, 15]);
    $ar_I[14] = array('style' => '', 'tt' => '-', 'noidung' => ' Quản lý NN', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'QLNN',]);
    $ar_I[15] = array('style' => '', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'DDT',],);
    return $ar_I;
}

function getHCSN_tinh()
{
    $ar_I['TONGSO'] = array(
        'stt' => '0', 'tt' => 'I', 'noidung' => 'KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ', 'phanloai' => '0',
        'chitiet' => ['GDDT', 'YTE', 'KHCN', 'VHTT', 'PTTH', 'TDTT', 'DBXH', 'KT', 'MT', 'QLNNDDT'], 'capdo' => '1', 'style' => 'font-weight: bold;font-style: italic;',
    );
    $ar_I['TRONGDO'] = array('stt' => '1', 'tt' => '-', 'noidung' => 'Trong đó', 'phanloai' => '9', 'chitiet' => [], 'capdo' => '9', 'style' => '');
    $ar_I['GDDT'] = array('stt' => '2', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'phanloai' => '1', 'chitiet' => ['GD', 'DT'], 'capdo' => '2', 'style' => '',);
    $ar_I['GD'] = array('stt' => '3', 'tt' => '-', 'noidung' => 'Giáo dục', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'GD',], 'capdo' => '3', 'style' => '',);
    $ar_I['DT'] = array('stt' => '4', 'tt' => '-', 'noidung' => 'Đào tạo', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'DT',], 'capdo' => '3', 'style' => '',);
    $ar_I['YTE'] = array('stt' => '5', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'YTE',], 'capdo' => '3', 'style' => '',);
    $ar_I['KHCN'] = array('stt' => '6', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'KHCN',], 'capdo' => '3', 'style' => '',);
    $ar_I['VHTT'] = array('stt' => '7', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'VHTT',], 'capdo' => '3', 'style' => '',);
    $ar_I['PTTH'] = array('stt' => '8', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'PTTH',], 'capdo' => '3', 'style' => '',);
    $ar_I['TDTT'] = array('stt' => '9', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'TDTT',], 'capdo' => '3', 'style' => '',);
    $ar_I['DBXH'] = array('stt' => '10', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'DBXH',], 'capdo' => '3', 'style' => '',);
    $ar_I['KT'] = array('stt' => '11', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'KT',], 'capdo' => '3', 'style' => '',);
    $ar_I['MT'] = array('stt' => '12', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'MT',], 'capdo' => '3', 'style' => '',);
    $ar_I['QLNNDDT'] = array('stt' => '13', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể', 'phanloai' => '1', 'chitiet' => ['QLNN', 'DDT'], 'capdo' => '2', 'style' => '',);
    $ar_I['QLNN'] = array('stt' => '14', 'tt' => '-', 'noidung' => ' Quản lý NN', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'QLNN',], 'capdo' => '3', 'style' => '',);
    $ar_I['DDT'] = array('stt' => '15', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể', 'phanloai' => '2', 'chitiet' => ['linhvuchoatdong' => 'DDT',], 'capdo' => '3', 'style' => '',);
    return $ar_I;
}

function getHCSN()
{
    //tonghop: 9 => bỏ qua
    //tonghop: 0 => Tính toán số liêu theo công thức
    //tonghop: 1 => lấy các ô trong chitiet để cộng
    $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => 'I', 'noidung' => 'KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ', 'phanloai' => '1', 'chitiet' => [2, 5, 6, 7, 8, 9, 10, 11, 12, 13], 'capdo' => '1');
    $ar_I[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Trong đó', 'phanloai' => '9', 'chitiet' => [], 'capdo' => '9');
    $ar_I[2] = array('style' => '', 'tt' => '1', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'phanloai' => '1', 'chitiet' => [3, 4], 'capdo' => '2');
    $ar_I[3] = array('style' => '', 'tt' => '-', 'noidung' => 'Giáo dục', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'GD',], 'capdo' => '3');
    $ar_I[4] = array('style' => '', 'tt' => '-', 'noidung' => 'Đào tạo', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'DT',], 'capdo' => '3');
    $ar_I[5] = array('style' => '', 'tt' => '2', 'noidung' => 'Sự nghiệp y tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'YTE',], 'capdo' => '3');
    $ar_I[6] = array('style' => '', 'tt' => '3', 'noidung' => 'Sự nghiệp khoa học-công nghệ', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'KHCN',], 'capdo' => '3');
    $ar_I[7] = array('style' => '', 'tt' => '4', 'noidung' => 'Sự nghiệp văn hóa thông tin', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'VHTT',], 'capdo' => '3');
    $ar_I[8] = array('style' => '', 'tt' => '5', 'noidung' => 'Sự nghiệp phát thanh truyền hình', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'PTTH',], 'capdo' => '3');
    $ar_I[9] = array('style' => '', 'tt' => '6', 'noidung' => 'Sự nghiệp thể dục - thể thao', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'TDTT',], 'capdo' => '3');
    $ar_I[10] = array('style' => '', 'tt' => '7', 'noidung' => 'Sự nghiệp đảm bảo xã hội', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'DBXH',], 'capdo' => '3');
    $ar_I[11] = array('style' => '', 'tt' => '8', 'noidung' => 'Sự nghiệp kinh tế', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'KT',], 'capdo' => '3');
    $ar_I[12] = array('style' => '', 'tt' => '9', 'noidung' => 'Sự nghiệp môi trường', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'MT',], 'capdo' => '3');
    $ar_I[13] = array('style' => '', 'tt' => '10', 'noidung' => 'Quản lý nhà nước, đảng, đoàn thể', 'phanloai' => '1', 'chitiet' => [14, 15], 'capdo' => '2');
    $ar_I[14] = array('style' => '', 'tt' => '-', 'noidung' => ' Quản lý NN', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'QLNN',], 'capdo' => '3');
    $ar_I[15] = array('style' => '', 'tt' => '-', 'noidung' => 'Đảng, đoàn thể', 'phanloai' => '0', 'chitiet' => ['linhvuchoatdong' => 'DDT',], 'capdo' => '3');
    return $ar_I;
}

function getChuyenTrach()
{
    $ar_II[0] = array('style' => 'font-weight: bold;', 'tt' => 'II', 'noidung' => 'CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ', 'phanloai' => '0', 'chitiet' => ['maphanloai' => 'KVXP']);
    return $ar_II;
}

function getHDND()
{
    $ar_III = array();
    $ar_III[0] = array('style' => 'font-weight: bold;', 'tt' => 'III', 'noidung' => 'HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP', 'phanloai' => '1', 'chitiet' => [1, 2, 3]);
    $ar_III[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Cấp tỉnh', 'phanloai' => '0', 'chitiet' => ['level' => 'TINH']);
    $ar_III[2] = array('style' => '', 'tt' => '-', 'noidung' => 'Cấp huyện', 'phanloai' => '0', 'chitiet' => ['level' => 'HUYEN']);
    $ar_III[3] = array('style' => '', 'tt' => '-', 'noidung' => 'Cấp xã', 'phanloai' => '0', 'chitiet' => ['level' => 'XA']);
    return $ar_III;
}

function getCapUy()
{
    $ar_IV = array();
    $ar_IV[0] = array('style' => 'font-weight: bold;', 'tt' => 'IV', 'noidung' => 'PHỤ CẤP TRÁCH NHIỆM CẤP ỦY', 'phanloai' => '1', 'chitiet' => [1, 2, 3]);
    $ar_IV[1] = array('style' => '', 'tt' => '-', 'noidung' => 'Ủy viên cấp tỉnh', 'phanloai' => '0', 'chitiet' => ['level' => 'TINH']);
    $ar_IV[2] = array('style' => '', 'tt' => '-', 'noidung' => 'Ủy viên cấp huyện', 'phanloai' => '0', 'chitiet' => ['level' => 'HUYEN']);
    $ar_IV[3] = array('style' => '', 'tt' => '-', 'noidung' => 'Ủy viên cấp xã', 'phanloai' => '0', 'chitiet' => ['level' => 'XA']);
    return $ar_IV;
}

function getChuyenTrach_plct()
{
    return array_column(dmphanloaict::where('nhomnhucau_xp', 'CANBOCT')->get()->toarray(), 'mact');
}

function getHDND_plct()
{
    return array_column(dmphanloaict::where('nhomnhucau_xp', 'HDND')->get()->toarray(), 'mact');
}

function getCapUy_plct()
{
    return array_column(dmphanloaict::where('nhomnhucau_xp', 'CAPUY')->get()->toarray(), 'mact');
}

function getNhomNhuCauKP($phanloai = 'KVHCSN')
{
    $a_nhom['KVHCSN'] = [
        'BIENCHE' => 'CÁN BỘ BIÊN CHẾ',
        'HOPDONG' => 'CÁN BỘ HỢP ĐỒNG',
        'CHUATUYENHC' => 'CÁN BỘ CHƯA TUYỂN',
        //2023.29.06 mới thêm do đơn vị Văn phòng uỷ ban có cả 2 loại hình hoạt động
        'HDND' => 'HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP',
        'CAPUY' => 'PHỤ CẤP TRÁCH NHIỆM CẤP ỦY',

    ];
    $a_nhom['KVXP'] = [
        'CANBOCT' => 'CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ',
        'HDND' => 'HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP',
        'CAPUY' => 'PHỤ CẤP TRÁCH NHIỆM CẤP ỦY',
        'CANBOKCT' => 'CÁN BỘ KHÔNG CHUYÊN TRÁCH',
        'CHUATUYENXP' => 'CÁN BỘ CHƯA TUYỂN',
    ];
    return $a_nhom[$phanloai];
}

function get4a_A()
{
    $a_A = array();

    $a_A[0] = array(
        'tt' => '1', 'noidung' => '50% tăng/giảm thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) thực hiện 2022 so dự toán Thủ tướng Chính phủ giao năm 2022',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'thuchien1'
    );
    $a_A[1] = array(
        'tt' => '2', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) dự toán 2023 so dự toán 2022 Thủ tướng Chính phủ giao',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'dutoan'
    );
    $a_A[2] = array(
        'tt' => '3', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất, xổ số kiến thiết) dự toán 2022 so dự toán 2021 Thủ tướng Chính phủ giao',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'dutoan1'
    );
    $a_A[3] = array(
        'tt' => '4', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán năm 2021',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'tietkiem2'
    );
    $a_A[4] = array(
        'tt' => '5', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2022',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'tietkiem1'
    );
    $a_A[5] = array(
        'tt' => '6', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán tăng thêm năm 2023',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'tietkiem'
    );

    $a_A[6] = array(
        'tt' => '7', 'noidung' => 'Số thu được huy động từ nguồn để lại đơn vị năm 2023:', 'sotien' => '0', 'phanloai' => '2', 'tentruong' => [7, 11]
    );
    $a_A[7] = array(
        'tt' => 'a', 'noidung' => 'Nguồn huy động từ các đơn vị tự đảm bảo(1):',
        'sotien' => '0', 'phanloai' => '1', 'tentruong' => [8, 9, 10]
    );
    $a_A[8] = array(
        'tt' => '', 'noidung' => '+ Học phí',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongtx_hocphi_4a'
    );
    $a_A[9] = array(
        'tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongtx_vienphi_4a'
    );
    $a_A[10] = array(
        'tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongtx_khac_4a'
    );

    $a_A[11] = array(
        'tt' => 'b', 'noidung' => 'Nguồn huy động từ các đơn vị chưa tự đảm bảo chi thường xuyên:', 'sotien' => '0', 'phanloai' => '1', 'tentruong' => [12, 13, 14]
    );
    $a_A[12] = array('tt' => '', 'noidung' => '+ Học phí', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongktx_hocphi_4a');
    $a_A[13] = array('tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongktx_vienphi_4a');
    $a_A[14] = array('tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongktx_khac_4a');

    $a_A[15] = array(
        'tt' => '8', 'noidung' => 'Nguồn 50% phần ngân sách nhà nước giảm chi hỗ trợ hoạt động thường xuyên trong lĩnh vực hành chính (do tinh giản biên chế và đổi mới, sắp xếp lại bộ máy của hệ thống chính trị tinh gọn, hoạt động hiệu lực, hiệu quả) và các đơn vị sự nghiệp công lập (do thực hiện đổi mới hệ thống tổ chức và quản lý, nâng cao chất lượng và hiệu quả hoạt động của đơn vị sự nghiệp công lập) năm 2023',
        'sotien' => '0', 'phanloai' => '9', 'tentruong' => []
    );

    /*2023.23.06 Phần này lấy số liệu từ mẫu 2đ chứ ko pải cộng dồn lên
    $a_A[15] = array(
        'tt' => '8', 'noidung' => 'Nguồn 50% phần ngân sách nhà nước giảm chi hỗ trợ hoạt động thường xuyên trong lĩnh vực hành chính (do tinh giản biên chế và đổi mới, sắp xếp lại bộ máy của hệ thống chính trị tinh gọn, hoạt động hiệu lực, hiệu quả) và các đơn vị sự nghiệp công lập (do thực hiện đổi mới hệ thống tổ chức và quản lý, nâng cao chất lượng và hiệu quả hoạt động của đơn vị sự nghiệp công lập) năm 2023',
        'sotien' => '0', 'phanloai' => '1', 'tentruong' => [16, 17, 18, 19]
    );
    */
    $a_A[16] = array(
        'tt' => '', 'noidung' => '+ Từ việc tinh giản biên chế tổ chức lại bộ máy (2)',
        'sotien' => '0', 'sotien' => '0', 'phanloai' => '9', 'tentruong' => 'tinhgiambc_4a'
    );
    $a_A[17] = array(
        'tt' => '', 'noidung' => '+ Từ việc sát nhập các đầu mối, cơ quan, đơn vị (2)',
        'sotien' => '0', 'sotien' => '0', 'phanloai' => '9', 'tentruong' => 'satnhapdaumoi_4a'
    );
    $a_A[18] = array(
        'tt' => '', 'noidung' => '+ Từ việc thay đổi cơ chế tự chủ của đơn vị sư nghiệp (3)',
        'sotien' => '0', 'sotien' => '0', 'phanloai' => '9', 'tentruong' => 'thaydoicochetuchu_4a'
    );
    $a_A[19] = array(
        'tt' => '', 'noidung' => '+ Từ việc sát nhập các xã không đủ điều kiện tiêu chuẩn',
        'sotien' => '0', 'sotien' => '0', 'phanloai' => '9', 'tentruong' => 'satnhapxa_4a'
    );

    $a_A[20] = array(
        'tt' => '9', 'noidung' => 'Nguồn NSTW đã bổ sung trong dự toán 2023',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'bosung'
    );

    $a_A[21] = array(
        'tt' => '10', 'noidung' => 'Nguồn thực hiện cải cách tiền lương năm 2022 chưa sử dụng hết chuyển sang năm 2023',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'caicach'
    );

    return $a_A;
}

function get4a_TT50_A()
{
    $a_A = array();

    $a_A[0] = array(
        'tt' => '1', 'noidung' => '70% tăng thu NSĐP ( không thể thu tiền sử dụng đất, thu sổ số kiến thiết, thu cổ phần hóa và thoái vốn doanh nghiệp nhà nước do địa phương quản lý, thu tiền thuê đất một lần được nhà nước đầu tư trước để bồi thường, giải phóng mặt bằng và thu từ xử lý tài sản công tại cơ quan, tổ chức , đơn vị được cơ quan có thẩm quyền quyết định sử dụng để chi đầu tư theo quy định; thu tiền bảo vệ và phát triển đất trồng lúa; phí tham quan các di tích, di sản thể giới; phí sử dụng công trình kết cấu hạ tầng, công trình dịch vụ, tiện ích công cộng trong khu vực cửa khẩu; phí bảo vệ môi trường đối với khai thác khoáng sản; phí bảo vệ môi trường với nước thải; thu từ quỹ đất công ích, thu hoa lợi, công sản tại xã và thu tiền cho thuê, cho thuê mua, bán nhà thuộc sở hữu nhà nước) thực hiện 2022 so dự toán Thủ tướng Chính phủ giao năm 2022',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'thuchien1'
    );
    $a_A[1] = array(
        'tt' => '2', 'noidung' => 'Số tiết kiệm chi 10 chi thường xuyên năm 2023',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'tietkiem'
    );
    $a_A[2] = array(
        'tt' => '3', 'noidung' => 'Số thu được huy động từ nguồn để lại đơn vị 2023 ( các đơn vị chưa tự đảm bảo chi thường xuyên )', 'sotien' => '0', 'phanloai' => '1', 'tentruong' => [3, 4, 5]
    );
    $a_A[3] = array('tt' => '', 'noidung' => '+ Học phí', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongktx_hocphi_4a');
    $a_A[4] = array('tt' => '', 'noidung' => '+ Viện phí', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongktx_vienphi_4a');
    $a_A[5] = array('tt' => '', 'noidung' => '+ Nguồn thu khác', 'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'huydongktx_khac_4a');

    $a_A[6] = array(
        'tt' => '4', 'noidung' => 'Nguồn thực hiện cải cách tiền lương năm 2022 chưa sử dụng hết chuyển sang năm 2023',
        'sotien' => '0', 'phanloai' => '0', 'tentruong' => 'caicach'
    );

    return $a_A;
}


function getPhuCap2a_78()
{
    return
        [
            'pckv' => 'Phụ cấp khu vực',
            'pccv' => 'Phụ cấp chức vụ',
            'vuotkhung' => 'Phụ cấp thâm niên vượt khung',
            'pcudn' => 'Phụ cấp ưu đãi ngành',
            'pcth' => 'Phụ cấp thu hút',
            'pcthni' => 'Phụ cấp công tác lâu năm',
            'pccovu' => 'Phụ cấp công vụ',
            'pcdang' => 'Phụ cấp công tác đảng',
            'pctnn' => 'Phụ cấp thâm niên nghề',
            'pck' => 'Phụ cấp khác',
        ];
}

function getSoLuongCanBoDinhMuc($nghidinh, $phanloaixa)
{
    $a_kq = [
        'ND34/2019' => [
            'XL1' => 23,
            'XL2' => 21,
            'XL3' => 19,
        ],
        //Do theo QĐ 11/2020-UBND Tỉnh Trưởng công an xã là CA chính quy => định biên theo NĐ 34 giảm 01 cán bộ
        // 'ND34/2019' => [
        //     'XL1'=> 22,
        //     'XL2'=> 20,
        //     'XL3'=> 18,
        // ],
        'ND33/2023/XA' => [
            'XL1' => 22,
            'XL2' => 20,
            'XL3' => 18,
        ],
        'ND33/2023/PHUONG' => [
            'PL1' => 23,
            'PL2' => 21,
            'PL3' => 19,
        ]
    ];
    return $a_kq[$nghidinh][$phanloaixa] ?? 0;
}

function getMucKhoanPhuCapXa($nghidinh, $phanloaixa)
{
    $a_kq = [
        'ND34/2019' => [
            'XL1' => 16,
            'XL2' => 13.7,
            'XL3' => 11.4,
        ],
        'ND33/2023' => [
            'XL1' => 21,
            'XL2' => 18,
            'XL3' => 15,
        ],
    ];
    return $a_kq[$nghidinh][$phanloaixa] ?? 0;
}
function getNghiDinhPLXaPhuong($phanloaixa){
    $kq=in_array($phanloaixa,['XL1','XL2','XL3'])?'ND33/2023/XA':'ND33/2023/PHUONG';
    return $kq;
}

function getDonviHuyen($nam,$madv)
{
     //lấy danh sách đơn vị: đơn vị có macqcq = madv (bang dmdonvi) + đơn vị nam=nam && macqcq=madv
     $a_donvicapduoi = [];
     //đơn vị nam=nam && macqcq=madv
     $model_dsql = dsdonviquanly::where('nam', $nam)->where('macqcq', $madv)->get();
     $a_donvicapduoi = array_unique(array_column($model_dsql->toarray(), 'madv'));

     //dd($a_donvicapduoi);
     //đơn vị có macqcq = madv (bang dmdonvi)
    //  $model_dmdv = dmdonvi::where('macqcq', $madv)
    //      ->wherenotin('madv', function ($qr) use ($nam) {
    //          $qr->select('madv')->from('dsdonviquanly')->where('nam', $nam)->distinct()->get();
    //      }) //lọc các đơn vị đã khai báo trong dsdonviquanly
    //      ->where('madv', '!=', $madv) //bỏ đơn vị tổng hợp
    //      ->get();
    $model_dmdv = dmdonvi::where('macqcq', $madv)
    ->wherenotin('madv', $a_donvicapduoi) //lọc các đơn vị đã khai báo trong dsdonviquanly
    ->where('madv', '!=', $madv) //bỏ đơn vị tổng hợp
    ->get();
        //  dd($model_dmdv);
     $a_donvicapduoi = array_unique(array_merge(array_column($model_dmdv->toarray(), 'madv'), $a_donvicapduoi));
    //lấy lại madv ở dmdonvi de tranh truong hop có madv o dsdonviquanly nhưng không có ở dmdonvi
    $model_donvi=dmdonvi::select('madv')->wherein('madv',$a_donvicapduoi)->get();
    $a_donvicapduoi=array_column($model_donvi->toarray(),'madv');
    //  dd($a_donvicapduoi);
     $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv', $a_donvicapduoi)->get();
    $m_donvi=array_diff($a_donvicapduoi, array_column($model_donvitamdung->toarray(), 'madv'));
    $array=[
        'm_donvi'=>$m_donvi,
        'model_donvitamdung'=>array_column($model_donvitamdung->toarray(), 'madv'),
        'a_donvicapduoi'=>$a_donvicapduoi
    ];
    return $array;
}
