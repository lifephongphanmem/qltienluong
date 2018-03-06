<?php
/**
 * Created by PhpStorm.
 * User: MLC
 * Date: 7/11/2016
 * Time: 8:45 AM
 */

function getInfoDonVI($hoso, $dmdonvi) {
    $donvi = array_column($dmdonvi, 'tendv', 'madv');
    if(array_search($hoso->madv, array_keys($donvi))===false){
        return '';
    }else{
        return $donvi[$hoso->madv];
    }
}

function getInfoPhongBan($hoso, $dmphongban) {
    $phongban = array_column($dmphongban, 'tenpb', 'mapb');
    if(array_search($hoso->mapb, array_keys($phongban))===false){
        return '';
    }else{
        return $phongban[$hoso->mapb];
    }
}

function getInfoChucVuD($hoso, $dmchucvud) {
    $chucvu = array_column($dmchucvud, 'tencv', 'macvd');
    if(array_search($hoso->macvd, array_keys($chucvu))===false){
        return '';
    }else{
        return $chucvu[$hoso->macvd];
    }
}

function getInfoChucVuCQ($hoso, $dmchucvucq) {
    $chucvu = array_column($dmchucvucq, 'tencv', 'macvcq');
    //dd(array_search($hoso->macvcq, array_keys($chucvu)));
    if(array_search($hoso->macvcq, array_keys($chucvu))===false){
        return $hoso->macvcq;
    }else{
       return $chucvu[$hoso->macvcq];
    }
}

function getInfoPLNB($hoso, $dmngachbac) {
    $ngachbac = array_column($dmngachbac, 'plnb', 'msngbac');
    if(array_search($hoso->msngbac, array_keys($ngachbac))===false){
        return '';
    }else{
        return $ngachbac[$hoso->msngbac];
    }
}

function getInfoTenNB($hoso, $dmngachbac) {
    //tìm và trả lại mảng
    $ngachbac = array_column($dmngachbac, 'tennb', 'msngbac');
    //dd(array_search($hoso->macvcq, array_keys($chucvu)));
    if(array_search($hoso->msngbac, array_keys($ngachbac))===false){
        return '';
    }else{
        return $ngachbac[$hoso->msngbac];
    }
}

function getInfoPhuCap($hoso, $dmphucap) {
    $phucap = array_column($dmphucap, 'tenpc', 'mapc');
    if(array_search($hoso->mapc, array_keys($phucap))===false){
        return '';
    }else{
        return $phucap[$hoso->mapc];
    }
}

//Lấy thông tin các phòng ban có cán bộ giao diện xã
function getPhongBanX(){
   $m_pb = App\dmphongban::select('mapb','tenpb')->wherein('mapb',function($query){
        $query->select('mapb')
            ->from('hosocanbo')
            ->where('madv',session('admin')->madv)
            ->distinct();
    })->orderby('sapxep')->get();
    return $m_pb;
}

//Lấy thông tin cán bộ giao diện xã
function getCanBoX(){
    $m_cb = \Illuminate\Support\Facades\DB::table('hosocanbo')
        ->select('hosocanbo.macanbo','hosocanbo.tencanbo','hosocanbo.mapb')
        ->where('hosocanbo.madv',session('admin')->madv)
        ->where('hosocanbo.theodoi','1')
        ->get();
    return $m_cb;
}

function getTenDV($madv){
    $model = App\dmdonvi::select('tendv')->where('madv',$madv)->first();
    return count($model)>0?Illuminate\Support\Str::upper($model->tendv):'';
}

function getTenDB($madvbc){
    $model = App\dmdonvibaocao::select('tendvbc')->where('madvbc',$madvbc)->first();
    return count($model)>0?Illuminate\Support\Str::upper($model->tendvbc):'';
}

function getTheoDoi($tenct){
    $kq=1;
    $kieuct='Biên chế';

    $model = App\dmphanloaict::where('tenct',$tenct)->first();
    if(count($model)>0){
        $kieuct=$model->kieuct;
      if($model->phanloaict != 'Đang công tác'){
          $kq=0;
      }
    }
    return array($kq, $kieuct);
}

function getMaKhoiPB($madv){
    $kq='';
    $model = App\dmdonvi::where('madv',$madv)->first();
    if(count($model)>0){
        $kq=$model->makhoipb;
    }
    return $kq;
}

function getLuongNgachBac($manhom,$bac=1){
    $model = App\nhomngachluong::where('manhom',$manhom)->first();
    //$bac = $bac - 1; //do bắt đầu từ bậc 1.
    if(count($model)>0){
        if($bac > $model->baclonnhat){//bậc lương truyền vào > bậc max trong danh mục => lỗi.
            return '0;0';
        }else{
            if($model->bacvuotkhung==0 || $model->bacvuotkhung==1){
                $heso=$model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = $model->vuotkhung;
            }elseif($bac >= $model->bacvuotkhung){//bao gồm cả trường hợp mã ngạch ko có vượt khung
                //do bắt đầu từ bậc 1 và bắt đàu vượt khung thì heso = hệ số bậc lương trc
                $heso=$model->heso + ($model->bacvuotkhung - 2) * $model->hesochenhlech;

                $vuotkhung = $model->vuotkhung + ($bac - $model->bacvuotkhung) * $model->namnb;
            }else{
                $heso=$model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = 0;
            }
        }

    }else{//Không tìm thấy mã ngạch lương
        return '0;0';
    }

    return $heso.';'.$vuotkhung;
}

function getLuongNgachBac_CBCT($msngbac,$bac=1){
    $model = App\ngachluong::where('msngbac',$msngbac)->first();
    //$bac = $bac - 1; //do bắt đầu từ bậc 1.
    if(count($model)>0){
        if($bac>$model->baclonnhat){//bậc lương truyền vào > bậc max trong danh mục => lỗi.
            return '0;0';
        }else{
            //mặc định nếu ko có vượt khung bacvuotkhung=0, nhưng do form nhap de giá trị bacvuotkhung=0
            if($model->bacvuotkhung==0 || $model->bacvuotkhung==1){
                $heso=$model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = $model->vuotkhung;
            }elseif($bac >= $model->bacvuotkhung){//bao gồm cả trường hợp mã ngạch ko có vượt khung
                //do bắt đầu từ bậc 1 và bắt đàu vượt khung thì heso = hệ số bậc lương trc
                $heso=$model->heso + ($model->bacvuotkhung - 2) * $model->hesochenhlech;

                $vuotkhung = $model->vuotkhung + ($bac - $model->bacvuotkhung) * $model->namnb;
            }else{
                $heso=$model->heso + ($bac - 1) * $model->hesochenhlech;
                $vuotkhung = 0;
            }
        }

    }else{//Không tìm thấy mã ngạch lương
        return '0;0';
    }

    return $heso.';'.$vuotkhung;
}

function getPhanLoaiXa($val_null = true){
    $model = array(
        'XL1'=>'Xã loại 1',
        'XL2'=>'Xã loại 2',
        'XL3'=>'Xã loại 3'
    );
    if($val_null){
        return array_merge( array(''=>'--Chọn phân loại xã--'),$model);
    }
    return $model;
}

function getCapDonVi(){
    return array('1'=>'Đơn vị dự toán cấp 1',
        '2'=>'Đơn vị dự toán cấp 2',
        '3'=>'Đơn vị dự toán cấp 3',
        '4'=>'Đơn vị dự toán cấp 4');
}

function getPhanLoaiNguon(){
    //Theo Nghị định số 16/2015/NĐ-CP
    return array('NGANSACH'=>'Nguồn kinh phí do Nhà nước bảo đảm chi thường xuyên',
        'CHITXDT'=>'Nguồn kinh phí do đơn vị tự bảo đảm chi thường xuyên và chi đầu tư',
        'CTX'=>'Nguồn kinh phí do đơn vị tự bảo đảm chi thường xuyên',
        'CTXMP'=>'Nguồn kinh phí do đơn vị tự bảo đảm một phần chi thường xuyên');
}

function getDiaBan($val_null = true){
    $model = array(
        'DBKK'=>'Khu vực KTXH ĐBKK',
        'BGHD'=>'Khu vực biên giới, hải đảo',
        'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự'
    );
    if($val_null){
        return array_merge( array(''=>'--Chọn phân loại địa bàn--'),$model);
    }
    return $model;
}

function getColTongHop(){
    return array('heso','hesopc','vuotkhung','pcct',
        'pckct',
        'pck',
        'pccv',
        'pckv',
        'pcth',
        'pcdd',
        'pcdh',
        'pcld',
        'pcdbqh',
        'pcudn',
        'pctn',
        'pctnn',
        'pcdbn',
        'pcvk',
        'pckn',
        'pcdang',
        'pccovu',
        'pclt',
        'pcd',
        'pctr',
        'pctnvk',
        'pcbdhdcu',
        'pcthni');
}

function getThang(){
    return array('01' => '01','02' => '02','03' => '03',
        '04' => '04','05' => '05','06' => '06',
        '07' => '07','08' => '08','09' => '09',
        '10' => '10','11' => '11','12' => '12');
}

function getNam(){
    return array('2016' => '2016','2017' => '2017','2018' => '2018');
}

function getGeneralConfigs() {
    return \App\GeneralConfigs::all()->first()->toArray();
}


function getLinhVucHoatDong($val_null = true){
    $model = array_column(App\dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
    if($val_null){
        return array_merge( array(''=>'--Chọn lĩnh vực hoạt động--'),$model);
    }
    return $model;
}

function getNguonKP($val_null = true){
    $model = array_column(App\dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
    if($val_null){
        return array_merge(array(''=>'--Chọn nguồn kinh phí--'),$model);
    }
    return $model;
}

function getNhomCongTac($val_null = true){
    $model = array_column(App\dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
    if($val_null){
        $a_kq = array(''=>'--Chọn nhóm công tác--');
        return array_merge($a_kq,$model);
    }
    return $model;

}

function getThongTuQD($val_null = true){
    $model = array_column(App\dmthongtuquyetdinh::all()->toArray(),'tenttqd','sohieu');
    if($val_null){
        $a_kq = array(''=>'--Chọn thông tư quyết định--');
        return array_merge($a_kq,$model);
    }
    return $model;

}

function getDonViTinh(){
    return array('1' => 'Đồng','2' => 'Nghìn đồng','3' => 'Triệu đồng');
}

function getTextStatus($status){
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
    );
    return isset($a_trangthai[$status]) ? $a_trangthai[$status] : '';
}

function getStatus(){
    return array(
        'CHUALUONG' => 'Chưa tạo bảng lương',
        'CHUATAO' => 'Dữ liệu chưa khởi tạo',
        'CHOGUI' => 'Dữ liệu chờ gửi',
        'DAGUI' => 'Dữ liệu đã gửi',
        'TRALAI' => 'Dữ liệu bị trả lại',
        'CHUADAYDU' => 'Dữ liệu chưa đầy đủ',
        'CHUAGUI' => 'Dữ liệu chờ gửi',
        'CHUADL' => 'Dữ liệu chưa được gửi lên',
        'GUILOI' => 'Dữ liệu bị lỗi'
    );
}

function getPhanLoaiPhuCap(){
    return array('0' => 'Hệ số','1' => 'Số tiền','2' => 'Phần trăm','3' => 'Ẩn');
}

function getColPhuCap(){
    return array(
        'pccovu'=>'Công vụ',
        'pctnvk'=>'Thâm niên vượt khung',
        'pctnn'=>'Thâm niên nghề',
        'pccv'=>'Chức vụ',
        'pcthni'=>'Lâu năm',
        'pckn'=>'Kiêm nhiệm',
        'pctn'=>'Trách nhiệm',
        'pcudn'=>'Ưu đãi ngành',
        'pckv'=>'Khu vực',
        'pcth'=>'Thu hút',
        'pcdbn'=>'Đặc biệt(đặc thù)',
        'pcld'=>'Lưu động',
        'pcdh'=>'Độc hại',
        'pcdbqh'=>'Đại biểu HĐND',
        'pcvk'=>'Cấp ủy viên',
        'pcbdhdcu'=>'Bồi dưỡng HĐCU',
        'pcdang'=>'Công tác Đảng',
        'pclt'=>'Phân loại xã',
        'pcdd'=>'Đắt đỏ',
        'pck'=>'Phụ cấp khác'
        //'pcct'=>'',
        //'pckct'=>'',
        //'pcd'=>'',
        //'pctr'=>'',
    );
}

function getColPhuCap_BaoCao()
{
    return array(
        'pckv' => 'Phụ cấp</br>khu vực',
        'pccv' => 'Phụ cấp</br>chức vụ',
        'pctnvk' => 'Phụ cấp</br>thâm niên</br>vượt khung',
        'pcudn' => 'Phụ cấp</br>ưu đãi</br>ngành',
        'pcth' => 'Phụ cấp</br>thu hút',
        'pcthni' => 'Phụ cấp</br>công tác</br>lâu năm',
        'pccovu' => 'Phụ cấp</br>công vụ',
        'pcdang' => 'Phụ cấp</br>công tác</br>Đảng',
        'pctnn' => 'Phụ cấp</br>thâm niên</br>nghề',
        'pctn' => 'Phụ cấp</br>trách nhiệm',
        'pckn' => 'Phụ cấp</br>kiêm nhiệm',
        'pclt' => 'Phụ cấp</br>phân loại</br>xã',
        'pcdd' => 'Phụ cấp</br>đắt đỏ',
        'pcdbqh'=>'Phụ cấp</br>Đại biểu</br>HĐND',
        'pcvk'=>'Phụ cấp</br>cấp ủy</br>viên',
        'pcbdhdcu'=>'Phụ cấp</br>bồi dưỡng</br>HĐCU',
        'pcdbn'=>'Phụ cấp</br>đặc biệt</br>(đặc thù)',
        'pcld'=>'Phụ cấp</br>lưu động',
        'pcdh'=>'Phụ cấp</br>độc hại',
        'pck' => 'Phụ cấp</br>khác'
    );
}
?>
