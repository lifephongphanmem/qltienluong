<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdiabandbkk;
use App\dmdiabandbkk_chitiet;
use App\dmphanloaict;
use App\tonghopluong_donvi;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_donviController extends Controller
{
    function index(Request $requests){
        if (Session::has('admin')) {
            $a_data=array(array('thang'=>'01','mathdv'=>null),
                array('thang'=>'02','mathdv'=>null),
                array('thang'=>'03','mathdv'=>null),
                array('thang'=>'04','mathdv'=>null),
                array('thang'=>'05','mathdv'=>null),
                array('thang'=>'06','mathdv'=>null),
                array('thang'=>'07','mathdv'=>null),
                array('thang'=>'08','mathdv'=>null),
                array('thang'=>'09','mathdv'=>null),
                array('thang'=>'10','mathdv'=>null),
                array('thang'=>'11','mathdv'=>null),
                array('thang'=>'12','mathdv'=>null)
            );

            $inputs=$requests->all();
            $model = tonghopluong_donvi::where('madv',session('admin')->madv)->get();
            $model_bangluong = bangluong::where('madv',session('admin')->madv)->get();
            for($i=0;$i<count($a_data);$i++){
                $tonghop = $model->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam'])->first();
                $bangluong = $model_bangluong->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam']);
                if(count($bangluong)>0){
                    $a_data[$i]['bangluong']='ok';
                    if(count($tonghop)>0){
                        $a_data[$i]['noidung']=$tonghop->noidung;
                        $a_data[$i]['mathdv']=$tonghop->mathdv;

                    }else{
                        $a_data[$i]['noidung']='Dữ liệu tổng hợp của '.getTenDV(session('admin')->madv) .' thời điểm '.$a_data[$i]['thang'].'/'.$inputs['nam'];
                    }
                }else{
                    $a_data[$i]['noidung']='Dữ liệu tổng hợp của '.getTenDV(session('admin')->madv) .' thời điểm '.$a_data[$i]['thang'].'/'.$inputs['nam'];
                    $a_data[$i]['bangluong']=null;
                }

            }
                       //dd($a_data);
            return view('functions.tonghopluong.donvi.index')
                ->with('furl','/chuc_nang/tong_hop_luong/don_vi/')
                ->with('nam',$inputs['nam'])
                ->with('model',$a_data)
                ->with('pageTitle','Danh sách tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $mathdv = getdate()[0];
            //lấy bảng lương
            $model_bangluong = bangluong::where('nam',$nam)->where('thang',$thang)->get();
            //bảng lương chi tiết
            $model_bangluong_ct = bangluong_ct::wherein('mabl',function($query) use($nam, $thang){
                $query->select('mabl')->from('bangluong')->where('nam',$nam)->where('thang',$thang);
            })->get();
            $model_congtac = dmphanloaict::all();

            $madv = session('admin')->madv;
            //$model_diaban = dmdiabandbkk::where('madv',$madv)->get();
            $model_diaban_ct = dmdiabandbkk_chitiet::wherein('madiaban',function($query) use($madv){
                $query->select('madiaban')->from('dmdiabandbkk')->where('madv',$madv)->where('phanloai','<>','');
            })->get();
            //Lấy dữ liệu từ các bảng liên quan thêm vào bảng lương chi tiết để tính toán
            foreach($model_bangluong_ct as $ct){
                $bangluong = $model_bangluong->where('mabl',$ct->mabl)->first();
                $ct->manguonkp=$bangluong->manguonkp;
                $ct->linhvuchoatdong=$bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN

                $congtac = $model_congtac->where('mact',$ct->mact)->first();
                $ct->macongtac=$congtac->macongtac;

                $diaban_ct = $model_diaban_ct->where('macanbo',$ct->macanbo)->first();
                if(count($diaban_ct)>0){
                    //$diaban = $model_diaban->where('madiaban',$diaban_ct->madiaban)->first();
                    $ct->madiaban = $diaban_ct->madiaban;
                }else{
                    $ct->madiaban = null;
                }
            }
            //
            //Lấy dữ liệu để lập
            $model_data = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac','linhvuchoatdong','manguonkp'])
                    ->all();
            });
            $model_data = a_unique($model_data);
            //
            //Tính toán dữ liệu
            $a_col=array('heso','vuotkhung','pcct',
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
                'pcbdhdcu');

            for($i=0;$i<count($model_data);$i++){
                $luongct = $model_bangluong_ct->where('manguonkp',$model_data[$i]['manguonkp'])
                    ->where('linhvuchoatdong',$model_data[$i]['linhvuchoatdong'])
                    ->where('macongtac',$model_data[$i]['macongtac']);

                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;
                foreach($a_col as $col){
                    $model_data[$i][$col] = $luongct->sum($col);
                    $tonghs += $model_data[$i][$col];
                }

                $model_data[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_data[$i]['tonghs']=$tonghs;
            }
            //

            //Tính toán theo địa bàn
            $model_diaban = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);

            for($i=0;$i<count($model_diaban);$i++){
                $luongct = $model_bangluong_ct->where('madiaban',$model_diaban[$i]['madiaban']);

                $tonghs = 0;
                $model_diaban[$i]['mathdv'] = $mathdv;
                foreach($a_col as $col){
                    $model_diaban[$i][$col] = $luongct->sum($col);
                    $tonghs += $model_data[$i][$col];
                }

                $model_diaban[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs']=$tonghs;
            }
            //
            //Thêm báo cáo tổng hợp

            //Thêm theo đơn vị
            //THêm theo địa bàn

            dd($model_diaban);

        } else
            return view('errors.notlogin');
    }
}
