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
        ->join('dmchucvucq', 'hosocanbo.macvcq', '=', 'dmchucvucq.macvcq')
        ->select('hosocanbo.macanbo','hosocanbo.tencanbo','hosocanbo.mapb', 'dmchucvucq.sapxep')
        ->where('hosocanbo.madv',session('admin')->madv)
        ->where('hosocanbo.theodoi','1')
        ->orderby('dmchucvucq.sapxep')
        ->get();
    return $m_cb;
}

function getTenDV($madv){
    $model = App\dmdonvi::select('tendv')->where('madv',$madv)->first();
    return count($model)>0?Illuminate\Support\Str::upper($model->tendv):'';
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

?>
