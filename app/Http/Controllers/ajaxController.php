<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphucap;
use App\ngachbac;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class ajaxController extends Controller
{
    function getKieuCT(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        if (isset($inputs['phanloaict'])) {
            $m_kcts = dmphanloaict::select('kieuct')->where('phanloaict', '=', $inputs['phanloaict'])->distinct()->get();
            $result['message'] = '<select name="kieuct" id="kieuct" class="form-control" onchange="getTenCT()"><option value="all">-- Chọn kiểu công tác --</option>';
            if (count($m_kcts) > 0) {
                foreach ($m_kcts as $m_kct) {
                    $result['message'] .= '<option value="' . $m_kct->kieuct . '">' . $m_kct->kieuct . '</option>';
                }
                $result['message'] .= '</select>';
                $result['status'] = 'success';
            }
        }
        die(json_encode($result));
    }

    function getTenCT(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        if (isset($inputs['kieuct'])) {
            $m_tcts = dmphanloaict::select('tenct')->where('kieuct', '=', $inputs['kieuct'])->distinct()->get();
            $result['message'] = '<select name="tenct" id="tenct" class="form-control"><option value="all">-- Chọn tên công tác --</option>';
            if (count($m_tcts) > 0) {
                foreach ($m_tcts as $m_tct) {
                    $result['message'] .= '<option value="' . $m_tct->tenct . '">' . $m_tct->tenct . '</option>';
                }
                $result['message'] .= '</select>';
                $result['status'] = 'success';
            }
        }
        die(json_encode($result));
    }

    function getTenNB(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        if (isset($inputs['plnb'])) {
            $m_nbs = ngachbac::select('tennb')->where('plnb', '=', $inputs['plnb'])->distinct()->get();
            $result['message'] = '<select name="tennb" id="tennb" class="form-control" onchange="getBac()"><option value="all">-- Chọn tên ngạch bậc --</option>';
            if (count($m_nbs) > 0) {
                foreach ($m_nbs as $m_nb) {
                    $result['message'] .= '<option value="' . $m_nb->tennb . '">' . $m_nb->tennb . '</option>';
                }
                $result['message'] .= '</select>';
                $result['status'] = 'success';
            }
        }
        die(json_encode($result));
    }

    function getBac(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = ngachluong::where('msngbac', $inputs['msngbac'])->first();
        $bac = $inputs['bac'] > $model->baclonnhat ? $model->baclonnhat : $inputs['bac'];
        $result['message'] = '<div id="div_bac" class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Bậc lương</label>';

        $result['message'] .= '<select name="bac" id="bac" class="form-control" onchange="getHS()">';
        for ($i = 1; $i <= $model->baclonnhat; $i++) {
            $result['message'] .= '<option value="' . $i . '" '.($bac == $i ? 'selected':'').'>' . $i . '</option>';
        }
        $result['message'] .= '</select></div></div>';

        $result['status'] = 'success';
        die(json_encode($result));
    }

    function getHS(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        if ($inputs['msngbac']=='') {
            $result = array(
                'status' => 'fail',
                'message' => '0;0',
            );
            die(json_encode($result));
        }

        $bac = $inputs['bac'];
        $inputs['heso'] = chkDbl($inputs['heso']);
        $model = ngachluong::where('msngbac',$inputs['msngbac'])->first();
        //dd($model);
        $heso = $vuotkhung = 0;
        $namnl = $model->namnb;
        if($model->bacvuotkhung==0 || $model->bacvuotkhung==1){
            $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
            $heso = $heso > $model->hesolonnhat? $model->hesolonnhat : $heso;
            $vuotkhung = 0;
        }else{
            if($inputs['bac'] < $model->bacvuotkhung){
                $vuotkhung = 0;
                $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
            }elseif($inputs['bac'] == $model->bacvuotkhung){
                $vuotkhung = $model->vuotkhung;
                $heso = $model->hesolonnhat;
            }else{
                $vuotkhung = $model->vuotkhung + $model->vuotkhungchenhlech * ( $inputs['bac'] - $model->bacvuotkhung);
                $heso = $model->hesolonnhat;
            }
        }

        $result['message'] = $heso.';'.$vuotkhung.';'.$namnl.';';

        /*
         * dd($inputs);
        //mặc định nếu ko có vượt khung bacvuotkhung=0, nhưng do form nhap de giá trị bacvuotkhung=0
        if($model->bacvuotkhung==0 || $model->bacvuotkhung==1){
            $heso = $model->heso + ($bac - 1) * $model->hesochenhlech;
            $vuotkhung = $model->vuotkhung;
        }elseif($bac >= $model->bacvuotkhung){//bao gồm cả trường hợp mã ngạch ko có vượt khung
            //do bắt đầu từ bậc 1 và bắt đàu vượt khung thì heso = hệ số bậc lương trc
            $heso = $model->heso + ($model->bacvuotkhung - 2) * $model->hesochenhlech;

            $vuotkhung = $model->vuotkhung + ($bac - $model->bacvuotkhung) * $model->namnb;
        }else{
            $heso= $model->heso + ($bac - 1) * $model->hesochenhlech;
            $vuotkhung = 0;
        }
        $model_nhomngachluong = nhomngachluong::where('manhom',$manhom)->first();
        //dd($inputs);
        if($model_nhomngachluong->manhom !='CBCT'){
            $result['message'] = getLuongNgachBac($manhom,$inputs['bac']);
        }else{
            $result['message'] = getLuongNgachBac_CBCT($inputs['msngbac'],$inputs['bac']);
        }
        */
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function getMSNB(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        if (isset($inputs['msngbac'])) {
            $m_nbs = ngachbac::select('tennb', 'plnb')->where('msngbac', '=', $inputs['msngbac'])->first();

            if ($m_nbs != null) {
                $result['message'] = $m_nbs->plnb . ';' . $m_nbs->tennb;
                $result['status'] = 'success';
            }
        }
        die(json_encode($result));
    }

    function checkmadv(Request $request){
        $inputs = $request->all();
        $model = dmdonvi::where('madv',$inputs['madv'])->first();
        if (isset($model)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    function getPhuCap(Request $request)
    {
        /* Ngay 29/08/2018
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model=dmphucap::where('mapc', $inputs['mapc'])->first();
        //$model=dmphucap::where('mapc', $inputs['mapc'])->first();
        $model->status = 'success';
        die($model);
        */

    }

    function getPhanLoai(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $result['status'] = 'success';
        if($inputs['maphanloai'] == 'KVXP'){
            $model_plxa = getPhanLoaiXa();
            $result['message'] = '<div id="plxa" class="col-md-6">';
            $result['message'] .='<div class="form-group">';
            $result['message'] .='<label class="control-label">Phân loại xã phường</label>';
            $result['message'] .='<select class="form-control select2me" name="phanloaixa" id="phanloaixa" >';
            foreach($model_plxa as $phanloai){
                $result['message'] .='<option value="'.$phanloai.'">'.$phanloai.'</option>';
            }

            $result['message'] .='</seclect>';
            $result['message'] .='</div>';
            $result['message'] .='</div>';
        }else{
            $model_linhvuc = dmkhoipb::all();
            $result['message'] = '<div id="plxa" class="col-md-6">';
            $result['message'] .='<div class="form-group">';
            $result['message'] .='<label class="control-label">Lĩnh vực hoạt động</label>';
            $result['message'] .='<select class="form-control select2me" name="linhvuchoatdong" id="linhvuchoatdong" multiple="multiple">';
            foreach($model_linhvuc as $linhvuc){
                $result['message'] .='<option value="'.$linhvuc->makhoipb.'">'.$linhvuc->tenkhoipb.'</option>';
            }

            $result['message'] .='</seclect>';
            $result['message'] .='</div>';

        }

        die(json_encode($result));
    }

}
