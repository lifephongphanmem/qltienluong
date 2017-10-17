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

        if (isset($inputs['plnb']) && isset($inputs['tennb'])) {
            $m_nbs = ngachbac::select('bac', 'msngbac')
                ->where('plnb', '=', $inputs['plnb'])
                ->where('tennb', '=', $inputs['tennb'])
                ->distinct()->get();
            $msngbac = '';
            $result['message'] = '<select name="bac" id="bac" class="form-control" onchange="getHS()"><option value="all">--Chọn bậc lương--</option>';
            if (count($m_nbs) > 0) {
                foreach ($m_nbs as $m_nb) {
                    $msngbac = $m_nb->msngbac;
                    $result['message'] .= '<option value="' . $m_nb->bac . '">' . $m_nb->bac . '</option>';
                }
                $result['message'] .= '</select>';
                //gán giá trị msngbac để tách mảng
                $result['message'] .= ';' . $msngbac;
                $result['status'] = 'success';
            }
        }
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


        $manhom = ngachluong::where('msngbac',$inputs['msngbac'])->first()->manhom;
        $model_nhomngachluong = nhomngachluong::where('manhom',$manhom)->first();
        //dd($inputs);
        if($model_nhomngachluong->manhom !='CBCT'){
            $result['message'] = getLuongNgachBac($manhom,$inputs['bac']);
        }else{
            $result['message'] = getLuongNgachBac_CBCT($inputs['msngbac'],$inputs['bac']);
        }

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

            if (count($m_nbs) > 0) {
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
        $model->status = 'success';
        die($model);
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
