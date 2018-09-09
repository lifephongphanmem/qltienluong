<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dsnangluong;
use App\dsnangluong_chitiet;
use App\hosocanbo;
use App\hosoluong;
use App\hosotruylinh;
use App\ngachbac;
use App\ngachluong;
use App\nhomngachluong;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dsnangluongController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dsnangluong::where('madv',session('admin')->madv)->get();
            return view('manage.nangluong.index')
                ->with('furl','/chuc_nang/nang_luong/')
                ->with('furl_ajax','/ajax/nang_luong/')
                ->with('model',$model)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách nâng lương');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $inputs = $request->all();
        $model = dsnangluong::where('manl', $inputs['manl'])->first();

        if (count($model) > 0) {
            //update
            $model->update($inputs);
            return redirect('/chuc_nang/nang_luong/danh_sach');
        } else {

            $madv = session('admin')->madv;
            $manl = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['manl'] = $manl;
            $model_ngachluong = ngachluong::wherein('msngbac', function ($query) use ($madv) {
                $query->select('msngbac')->from('hosocanbo')->where('madv', $madv)->distinct();
            })->get();
            $model_nhomngluong = nhomngachluong::all();

            /*
            $m_canbo = hosocanbo::select('macanbo','msngbac','bac','ngaytu','ngayden','msngbac','heso','hesott','vuotkhung',DB::raw("'".$inputs['manl']. "' as manl"),
                    'pck','pccv','pckv','pcth','pcdh','pcld','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pccovu','pcdbqh','pctnvk','pcbdhdcu','pcdang','pcthni')
                ->where('ngayden', '<', $inputs['ngayxet'])
                ->where('theodoi',1)
                ->where('madv', $madv)->get();
             * */
            $m_canbo = hosocanbo::select('macanbo','msngbac','bac','ngaytu','ngayden','msngbac','heso','hesott','vuotkhung',DB::raw("'".$inputs['manl']. "' as manl"),
                    'pck','pccv','pckv','pcth','pcdh','pcld','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pccovu','pcdbqh','pctnvk','pcbdhdcu','pcdang','pcthni')
                ->where('ngayden', '<=', $inputs['ngayxet'])
                ->where('theodoi','<','9')
                ->where('madv', $madv)->get();

            //dd($model_nhomngluong->toarray());
            foreach ($m_canbo as $cb) {
                //Lấy thông tin ngạch lương
                $ngachluong = $model_ngachluong->where('msngbac',$cb->msngbac)->first();
                $b_vuotkhung = false; //biến xác định cán bộ có thuộc diện vượt khung
                if(count($ngachluong)==0){continue;}

                //Lấy thông tin nhóm ngạch lương
                if($ngachluong->manhom == 'CBCT'){
                    //nhóm ngạch lương cán bộ chuyên trách xử lý riêng do danh mục
                    $nhomngluong = $ngachluong;

                    if($cb->bac + 1 > $nhomngluong->baclonnhat){
                        $cb->bac = $nhomngluong->baclonnhat;
                    }else{
                        $cb->bac = $cb->bac + 1;
                    }

                    $hsl = getLuongNgachBac_CBCT($cb->msngbac, $cb->bac);
                }else{
                    $nhomngluong = $model_nhomngluong->where('manhom',$ngachluong->manhom)->first();

                    //nếu bậc lương  = bậc vượt khung thì mỗi năm tăng 1% và ko tăng bậc lương nữa
                    //xét biến vượt khung
                    if($cb->bac >= $nhomngluong->bacvuotkhung){
                        $b_vuotkhung = true;
                    }else{
                        if($cb->bac + 1 > $nhomngluong->baclonnhat){
                            $cb->bac = $nhomngluong->baclonnhat;
                        }else{
                            $cb->bac = $cb->bac + 1;
                        }
                        $hsl = getLuongNgachBac($ngachluong->manhom, $cb->bac);
                    }
                }
                if(count($nhomngluong) == 0){continue;}

                if($b_vuotkhung){
                    if($cb->vuotkhung < $nhomngluong->vuotkhung){
                        $cb->vuotkhung = $nhomngluong->vuotkhung;
                        $cb->hesott = ($nhomngluong->vuotkhung * $cb->heso) / 100;
                    }else{
                        $cb->vuotkhung = $cb->vuotkhung + 1;
                        $cb->hesott = $cb->heso / 100;
                    }

                    $cb->ngaytu = new Carbon($cb->ngayden);
                    //$cb->ngaytu = $date->addDay('1');
                    //$date1 = new Carbon($cb->ngayden);
                    //$cb->ngayden = $date1->addYear('1');
                    $date = new Carbon($cb->ngayden);
                    $cb->ngayden = $date->addYear('1');
                    //kiểm tra truy lĩnh nếu ngày xét = ngày nâng lương = > ko truy lĩnh
                    if($inputs['ngayxet']>$cb->ngayden) {
                        $cb->truylinhtungay = new Carbon($cb->ngayden);
                    }else{
                        $cb->truylinhtungay = null;
                    }

                    //$cb->truylinhdenngay = $inputs['ngayxet'];
                }else{
                    //Tính lại hệ số lương + phụ cấp + hệ số truy lĩnh
                    $a_hsl = explode(';',$hsl);
                    $cb->heso = $a_hsl[0];
                    $cb->vuotkhung = $a_hsl[1];

                    //$cb->ngaytu = $date->addDay('1');
                    $cb->ngaytu = new Carbon($cb->ngayden);
                    //$date1 = new Carbon($cb->ngayden);
                    //$cb->ngayden = $date1->addYear($nhomngluong->namnb)->addDay('1');
                    //kiểm tra truy lĩnh nếu ngày xét = ngày nâng lương = > ko truy lĩnh
                    if($inputs['ngayxet']>$cb->ngayden) {
                        $cb->truylinhtungay = new Carbon($cb->ngayden);
                        $cb->hesott = $nhomngluong->hesochenhlech;
                    }else{
                        $cb->truylinhtungay = null;
                    }
                    $date = new Carbon($cb->ngayden);
                    $cb->ngayden = $date->addYear($nhomngluong->namnb);
                }
            }
            //dd($m_canbo->toarray());
            $inputs['trangthai'] = 'Tạo danh sách';
            dsnangluong::create($inputs);
            dsnangluong_chitiet::insert($m_canbo->toarray());
           return redirect('/chuc_nang/nang_luong/maso='.$manl);
        }
    }

    function getNB($dmNB,$msnb,$b){
        foreach($dmNB as $dm){
            if($dm['msngbac']==$msnb&&$dm['bac']==$b){
                return $dm;
            }
        }
    }

    function show($manl){
        if (Session::has('admin')) {
            $model = dsnangluong_chitiet::where('manl',$manl)->get();
            /*
            $model=DB::table('hosoluong')
                ->join('hosocanbo', 'hosoluong.macanbo', '=', 'hosocanbo.macanbo')
                ->join('dmchucvucq', 'hosocanbo.macvcq', '=', 'dmchucvucq.macvcq')
                ->select('hosoluong.*', 'dmchucvucq.sapxep','hosocanbo.tencanbo','hosocanbo.macanbo','hosocanbo.macvcq')
                ->where('hosoluong.manl',$manl)
                ->orderby('dmchucvucq.sapxep')
                ->get();
            */
            $model_canbo=hosocanbo::select('macanbo', 'macvcq','tencanbo')->get();
            $model_dmchucvucq=dmchucvucq::select('tencv', 'macvcq','sapxep')->get();

            foreach($model as $hs){
                $canbo = $model_canbo->where('macanbo',$hs->macanbo)->first();
                if(count($canbo)>0){
                    $hs->tencanbo = $canbo->tencanbo;
                    $chucvucq = $model_dmchucvucq->where('macvcq',$canbo->macvcq)->first();
                    if(count($chucvucq)>0){
                        $hs->tencv = $chucvucq->tencv;
                        $hs->sapxep = $chucvucq->sapxep;
                    }
                }
            }
            $data = $model->sortBy('sapxep');
           $model_nangluong = dsnangluong::where('manl',$manl)->first();
            return view('manage.nangluong.nangluong')
                ->with('furl','/chuc_nang/nang_luong/')
                ->with('model',$data)
                ->with('model_nangluong',$model_nangluong)
                ->with('pageTitle','Chi tiết danh sách nâng lương');
        } else
            return view('errors.notlogin');
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dsnangluong_chitiet::where('manl',$inputs['maso'])->where('macanbo',$inputs['canbo'])->first();
            //dd($model);
            $m_plnb = nhomngachluong::select('manhom','tennhom')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong','manhom','msngbac')->distinct()->get();
            $model_canbo=hosocanbo::select('macanbo', 'macvcq','tencanbo')->where('macanbo',$model->macanbo)->first();

            if(count($model_canbo)>0){
                $model->tencanbo = $model_canbo->tencanbo;
            }
            $model_nangluong = dsnangluong::where('manl',$inputs['maso'])->first();
            return view('manage.nangluong.detail')
                ->with('furl','/chuc_nang/nang_luong/')
                ->with('model',$model)
                ->with('m_pln',$m_pln)
                ->with('m_plnb',$m_plnb)
                ->with('model_nangluong',$model_nangluong)
                ->with('pageTitle','Chi tiết danh sách nâng lương');
        } else
            return view('errors.notlogin');
    }
    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['truylinhtungay'] = getDateTime($inputs['truylinhtungay']);
            $model = dsnangluong_chitiet::where('manl',$inputs['manl'])->where('macanbo',$inputs['macanbo'])->first();
            $model->update($inputs);
           return redirect('chuc_nang/nang_luong/maso='.$model->manl);
        } else
            return view('errors.notlogin');
    }

    function nang_luong($manl){
        if (Session::has('admin')) {
           $model = dsnangluong_chitiet::where('manl',$manl)->get();

            foreach($model as $canbo) {
                $hoso = hosocanbo::where('macanbo', $canbo->macanbo)->first();
                $data = $canbo->toarray();
                unset($data['id']);
                unset($data['phanloai']);
                hosoluong::create($data);
                if (isset($canbo->truylinhtungay) && $canbo->hesott > 0) {
                    $truylinh = hosotruylinh::where('macanbo',$canbo->macanbo)->first();
                    if(count($truylinh) == 0){
                        $truylinh = new hosotruylinh();
                        $truylinh->maso = session('admin')->madv . '_' . getdate()[0];
                    }
                    $truylinh->macanbo = $canbo->macanbo;
                    $truylinh->tencanbo = $hoso->tencanbo;
                    $truylinh->ngaytu = $canbo->truylinhtungay;
                    $truylinh->madv = session('admin')->madv;
                    $truylinh->msngbac = $canbo->msngbac;
                    $truylinh->hesott = $canbo->hesott;
                    $truylinh->save();
                }
                //Lưu thông tin vào hồ sơ cán bộ
                unset($data['manl']);
                unset($data['ghichu']);
                $hoso->update($data);
            }
            dsnangluong::where('manl',$manl)->update(['trangthai'=>'Đã nâng lương']);
            return redirect('/chuc_nang/nang_luong/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dsnangluong::find($id);
            dsnangluong_chitiet::where('manl',$model->manl)->delete();
            $model->delete();
            return redirect('/chuc_nang/nang_luong/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroydt($id){
        if (Session::has('admin')) {
            $model = dsnangluong_chitiet::find($id);
            $manl= $model->manl;
            $model->delete();
            return redirect('/chuc_nang/nang_luong/maso='.$manl);
        } else
            return view('errors.notlogin');
    }

    function getinfo(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = dsnangluong::find($inputs['id']);
        die($model);
    }
}
