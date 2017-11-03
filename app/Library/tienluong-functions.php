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

function getPhanLoaiXa(){
    return array(''=>'Chọn phân loại xã',
        'XL1'=>'Xã loại 1',
        'XL2'=>'Xã loại 2',
        'XL3'=>'Xã loại 3');
}

function getCapDonVi(){
    return array('1'=>'Đơn vị dự toán cấp 1',
        '2'=>'Đơn vị dự toán cấp 2',
        '3'=>'Đơn vị dự toán cấp 3',
        '4'=>'Đơn vị dự toán cấp 4');
}

function getPhanLoaiNguon(){
    return array('NGANSACH'=>'Nguồn ngân sách',
        'CHITXDT'=>'Nguồn đơn vị tự bảo đảm chi thường xuyên và chi đầu tư',
        'CTX'=>'Nguồn đơn vị tự bảo đảm chi thường xuyên');
}

function getDiaBan(){
    return array(''=>'-- Chọn phân loại địa bàn --',
        'DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
        'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
}

function getColTongHop(){
    return array('heso','vuotkhung','pcct',
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
        'pcthni');;
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

function getLinhVucHoatDong(){
    $model = array_column(App\dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
    $a_kq = array(''=>'--Chọn lĩnh vực hoạt động--');
    return array_merge($a_kq,$model);
}

function getNguonKP(){
    $model = array_column(App\dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
    $a_kq = array(''=>'--Chọn nguồn kinh phí--');
    return array_merge($a_kq,$model);
}

function getNhomCongTac(){
    $model = array_column(App\dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
    $a_kq = array(''=>'--Chọn nhóm công tác--');
    return array_merge($a_kq,$model);
}

function getDonViTinh(){
    return array('1' => 'Đồng','2' => 'Nghìn đồng','3' => 'Triệu đồng');
}
?>
