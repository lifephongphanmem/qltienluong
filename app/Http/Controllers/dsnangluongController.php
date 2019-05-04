<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmphucap_donvi;
use App\dsnangluong;
use App\dsnangluong_chitiet;
use App\dsnangluong_nguon;
use App\hosocanbo;
use App\hosoluong;
use App\hosotruylinh;
use App\hosotruylinh_nguon;
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
                ->with('model',$model->sortby('ngayxet'))
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
            $a_ngachluong = ngachluong::all()->keyby('msngbac')->toarray();
            $a_canbo = hosocanbo::select('macanbo','msngbac','bac','ngaytu','ngayden','msngbac','heso','hesott','vuotkhung')
                ->where('ngayden', '<=', $inputs['ngayxet'])->where('theodoi','<','9')
                ->where('madv', $madv)->get()->keyby('macanbo')->toarray();
            $a_data = array();
            $a_data_nguon = array();
            $a_nguon_df = getNguonTruyLinh_df();
            //dd($a_nguon_df);
            foreach ($a_canbo as $key=>$val) {
                $ngayden = $a_canbo[$key]['ngayden'];
                $a_canbo[$key]['manl'] = $manl;
                //Lấy thông tin ngạch lương
                $b_vuotkhung = false; //biến xác định cán bộ có thuộc diện vượt khung
                if(!isset($a_ngachluong[$val['msngbac']])){
                    continue;
                }
                $ngachluong = $a_ngachluong[$val['msngbac']];
                if($val['heso'] < $ngachluong['hesolonnhat']){//nâng lương ngạch bậc
                    $a_canbo[$key]['heso'] += $ngachluong['hesochenhlech'];
                    $a_canbo[$key]['bac'] = $a_canbo[$key]['bac'] < $ngachluong['baclonnhat'] - 1 ? $a_canbo[$key]['bac'] + 1 : $ngachluong['baclonnhat'];

                    $a_canbo[$key]['ngaytu'] = new Carbon($ngayden);
                    $date = new Carbon($a_canbo[$key]['ngayden']);
                    $a_canbo[$key]['ngayden'] = $date->addYear($ngachluong['namnb']);
                    $a_canbo[$key]['hesott'] = $ngachluong['hesochenhlech'];
                }else{//vượt khung
                    if($a_canbo[$key]['vuotkhung'] == 0){//lần đầu
                        $a_canbo[$key]['vuotkhung'] = $ngachluong['vuotkhung'];
                        $a_canbo[$key]['hesott'] = ($a_canbo[$key]['vuotkhung'] * $a_canbo[$key]['heso']) / 100;
                    }else{
                        $a_canbo[$key]['vuotkhung'] += 1;
                        $a_canbo[$key]['hesott'] = $a_canbo[$key]['heso'] / 100;
                    }
                    $a_canbo[$key]['bac'] = $ngachluong['baclonnhat'];
                    $a_canbo[$key]['ngaytu'] = new Carbon($ngayden);
                    $date = new Carbon($a_canbo[$key]['ngayden']);
                    $a_canbo[$key]['ngayden'] = $date->addYear(1);
                }

                if($inputs['ngayxet']>$ngayden) {
                    $a_canbo[$key]['ngaytl'] = 0;
                    $a_canbo[$key]['thangtl'] = 0;

                    $a_canbo[$key]['truylinhtungay'] = new Carbon($ngayden);
                    $a_canbo[$key]['truylinhdenngay'] = new Carbon($inputs['ngayxet']);
                    list($a_canbo[$key]['thangtl'], $a_canbo[$key]['ngaytl']) = $this->getThoiGianTL($a_canbo[$key]['truylinhtungay'], $a_canbo[$key]['truylinhdenngay']);
                }else{
                    $a_canbo[$key]['ngaytl'] = 0;
                    $a_canbo[$key]['thangtl'] = 0;
                    $a_canbo[$key]['truylinhtungay'] = null;
                    $a_canbo[$key]['truylinhdenngay'] = null;
                    $a_canbo[$key]['hesott'] = 0;
                }
                $a_data[] = $a_canbo[$key];
                foreach($a_nguon_df as $k=>$v) {
                    $a_data_nguon[] = array('macanbo' => $key, 'manguonkp' => $k, 'luongcoban' => $v, 'manl' => $manl);
                }

                /*
                dd($ngachluong);
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
                        $cb->truylinhdenngay = new Carbon($inputs['ngayxet']);
                    }else{
                        $cb->truylinhtungay = null;
                        $cb->truylinhdenngay = null;
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
                        $cb->truylinhdenngay = new Carbon($inputs['ngayxet']);
                        $cb->hesott = $nhomngluong->hesochenhlech;
                    }else{
                        $cb->truylinhtungay = null;
                        $cb->truylinhdenngay = null;
                    }
                    $date = new Carbon($cb->ngayden);
                    $cb->ngayden = $date->addYear($nhomngluong->namnb);
                }
            }

            dd($a_canbo);

            //bắt đầu làm lại
            $model_ngachluong = ngachluong::wherein('msngbac', function ($query) use ($madv) {
                $query->select('msngbac')->from('hosocanbo')->where('madv', $madv)->distinct();
            })->get();
            $model_nhomngluong = nhomngachluong::all();

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
                        $cb->truylinhdenngay = new Carbon($inputs['ngayxet']);
                    }else{
                        $cb->truylinhtungay = null;
                        $cb->truylinhdenngay = null;
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
                        $cb->truylinhdenngay = new Carbon($inputs['ngayxet']);
                        $cb->hesott = $nhomngluong->hesochenhlech;
                    }else{
                        $cb->truylinhtungay = null;
                        $cb->truylinhdenngay = null;
                    }
                    $date = new Carbon($cb->ngayden);
                    $cb->ngayden = $date->addYear($nhomngluong->namnb);
                }
                */
            }
            //dd($m_canbo->toarray());
            //dd($a_data_nguon);
            dsnangluong_chitiet::insert($a_data);
            dsnangluong_nguon::insert($a_data_nguon);
            $inputs['trangthai'] = 'Tạo danh sách';
            dsnangluong::create($inputs);

           return redirect('/chuc_nang/nang_luong/maso='.$manl);
        }
    }

    function getThoiGianTL($tungay,$denngay){
        $ngaytl = 0;
        $ngaycong = 22;

        $nam_tu = $tungay->year;
        $ngay_tu = $tungay->day;
        $thang_tu = $tungay->month;

        $nam_den = $denngay->year;
        $ngay_den = $denngay->day;
        $thang_den = $denngay->month;

        $thangtl = $thang_den + 12*($nam_den - $nam_tu) - $thang_tu + 1;//cộng 1 là do 7->8 = 2 tháng (như đếm số tự nhiên)

        if($ngay_tu >1){//không pải từ đầu tháng => xét số ngày tl
            $thangtl = $thangtl - 1;
            //từ ngày xét đến cuối tháng
            //lấy ngày cuối cùng của tháng từ
            $ngay_tucuoi = Carbon::create($nam_tu, $thang_tu + 1, 0)->day;
            if($ngay_tucuoi - $ngay_tu >= $ngaycong){
                $thangtl = $thangtl + 1;
            }else{
                $ngaytl = $ngay_tucuoi - $ngay_tu;
            }
        }

        $ngay_dencuoi = Carbon::create($nam_den, $thang_den + 1, 0)->day;
        if($ngay_den < $ngay_dencuoi){
            $thangtl = $thangtl - 1;
            if( $ngay_den >= $ngaycong){
                $thangtl = $thangtl + 1;
            }else{
                $ngaytl += $ngay_den;
            }
        }

        if($ngaytl > $ngaycong){
            $ngaytl = $ngaytl - $ngaycong;
            $thangtl = $thangtl + 1;
        }

        return array($thangtl, $ngaytl);
    }

    /*
    function getNB($dmNB,$msnb,$b){
        foreach ($dmNB as $dm) {
            if ($dm['msngbac'] == $msnb && $dm['bac'] == $b) {
                return $dm;
            }
        }
    }
    */

    function show($manl){
        if (Session::has('admin')) {
            $model = dsnangluong_chitiet::where('manl', $manl)->get();
            $a_cv = getChucVuCQ(false);
            $a_cb = hosocanbo::select('macanbo', 'macvcq', 'tencanbo')
                ->where('madv', session('admin')->madv)->get()->keyby('macanbo')->toarray();

            foreach ($model as $hs) {
                if (isset($a_cb[$hs->macanbo])) {
                    $canbo = $a_cb[$hs->macanbo];
                    $hs->tencanbo = $canbo['tencanbo'];
                    $hs->tencv = isset($a_cv[$canbo['macvcq']]) ? $a_cv[$canbo['macvcq']] : '';
                }
            }

            $model_canbo = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();

            $model_nangluong = dsnangluong::where('manl', $manl)->first();
            return view('manage.nangluong.nangluong')
                ->with('furl', '/chuc_nang/nang_luong/')
                ->with('model', $model)
                ->with('model_nangluong', $model_nangluong)
                ->with('a_canbo', array_column($model_canbo->toarray(), 'tencanbo', 'macanbo'))
                ->with('pageTitle', 'Chi tiết danh sách nâng lương');
        } else
            return view('errors.notlogin');
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dsnangluong_chitiet::where('manl', $inputs['maso'])->where('macanbo', $inputs['canbo'])->first();
            $model_nkp = dsnangluong_nguon::where('manl', $inputs['maso'])->where('macanbo', $inputs['canbo'])->get();
            //dd($model);
            $m_plnb = nhomngachluong::select('manhom', 'tennhom')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong', 'manhom', 'msngbac')->distinct()->get();
            $model_canbo = hosocanbo::select('macanbo', 'macvcq', 'tencanbo')->where('macanbo', $model->macanbo)->first();

            if (count($model_canbo) > 0) {
                $model->tencanbo = $model_canbo->tencanbo;
            }
            $a_nkp = getNguonKP(false);
            $model_nangluong = dsnangluong::where('manl', $inputs['maso'])->first();

            return view('manage.nangluong.detail')
                ->with('furl', '/chuc_nang/nang_luong/')
                ->with('model', $model)
                ->with('model_nkp', $model_nkp)
                ->with('m_pln', $m_pln)
                ->with('m_plnb', $m_plnb)
                ->with('a_nkp', $a_nkp)
                ->with('model_nangluong', $model_nangluong)
                ->with('pageTitle', 'Chi tiết danh sách nâng lương');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['truylinhdenngay'] = getDateTime($inputs['truylinhdenngay']);
            $inputs['truylinhtungay'] = getDateTime($inputs['truylinhtungay']);
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            foreach($model_pc as $pc){
                if(isset($inputs[$pc->mapc])){
                    $inputs[$pc->mapc] = chkDbl($inputs[$pc->mapc]);
                }
            }

            $model = dsnangluong_chitiet::where('manl',$inputs['manl'])->where('macanbo',$inputs['macanbo'])->first();
            $model->update($inputs);
           return redirect('chuc_nang/nang_luong/maso='.$model->manl);
        } else
            return view('errors.notlogin');
    }

    function nang_luong($manl){
        if (Session::has('admin')) {
            $model = dsnangluong_chitiet::where('manl', $manl)->get();
            $model_nguon = dsnangluong_nguon::where('manl', $manl)->get();
            $ma = getdate()[0];
            foreach ($model as $canbo) {
                $ma = $ma + 1;
                $hoso = hosocanbo::where('macanbo', $canbo->macanbo)->first();
                $hoso->heso = $canbo->heso;
                $hoso->bac = $canbo->bac;
                $hoso->vuotkhung = $canbo->vuotkhung;
                $hoso->ngaytu = $canbo->ngaytu;
                $hoso->ngayden = $canbo->ngayden;
                $hoso->save();
                $data = $canbo->toarray();
                unset($data['id']);
                unset($data['phanloai']);
                //hosoluong::create($data);
                if (isset($canbo->truylinhtungay) && $canbo->hesott > 0) {
                    $truylinh = hosotruylinh::where('macanbo', $canbo->macanbo)->first();
                    if (count($truylinh) == 0) {
                        $truylinh = new hosotruylinh();
                        $truylinh->maso = session('admin')->madv . '_' . $ma;
                    }
                    $truylinh->macanbo = $canbo->macanbo;
                    $truylinh->tencanbo = $hoso->tencanbo;
                    $truylinh->ngaytu = $canbo->truylinhtungay;
                    $truylinh->ngayden = $canbo->truylinhdenngay;
                    $truylinh->thangtl = $canbo->thangtl;
                    $truylinh->ngaytl = $canbo->ngaytl;
                    $truylinh->madv = session('admin')->madv;
                    $truylinh->noidung = 'Truy lĩnh nâng lương ngạch bậc';
                    $truylinh->msngbac = $canbo->msngbac;
                    $truylinh->heso = $canbo->hesott; //hệ số truy lĩnh đều đưa vào hệ số
                    $truylinh->maphanloai = 'MSNGBAC'; //hệ số truy lĩnh đều đưa vào hệ số
                    $truylinh->save();
                    $nguon = $model_nguon->where('macanbo', $canbo->macanbo);
                    if(count($nguon)> 0){
                        foreach ($nguon as $nkp) {
                            $nguon_tl = new hosotruylinh_nguon();
                            $nguon_tl->maso = $truylinh->maso;
                            $nguon_tl->manguonkp = $nkp->manguonkp;
                            $nguon_tl->luongcoban = $nkp->luongcoban;
                            $nguon_tl->save();
                        }
                    }else{//lấy nguồn mặc định
                        foreach (getNguonTruyLinh_df() as $k=>$v) {
                            $nguon_tl = new hosotruylinh_nguon();
                            $nguon_tl->maso = $truylinh->maso;
                            $nguon_tl->manguonkp = $k;
                            $nguon_tl->luongcoban = $v;
                            $nguon_tl->save();
                        }
                    }
                }
                /*
                //Lưu thông tin vào hồ sơ cán bộ
                unset($data['manl']);
                unset($data['ghichu']);
                unset($data['manguonkp']);
                $hoso->update($data);
                */
            }

            dsnangluong::where('manl', $manl)->update(['trangthai' => 'Đã nâng lương']);
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

    function getinfor_nkp(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        //dd(hosotruylinh_nguon::find($inputs['id']));
        die(dsnangluong_nguon::find($inputs['id']));
    }

    function store_nkp(Request $request)
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
        $insert = $request->all();
        $insert['luongcoban'] = getDbl($insert['luongcoban']);

        $model_chk = dsnangluong_nguon::where('manl', $insert['manl'])
            ->where('macanbo', $insert['macanbo'])
            ->where('manguonkp', $insert['manguonkp'])->first();
        if(count($model_chk) > 0){
            $model_chk->macanbo = $insert['macanbo'];
            $model_chk->luongcoban = $insert['luongcoban'];
            $model_chk->manguonkp = $insert['manguonkp'];
            $model_chk->save();
        }else{
            $model = new dsnangluong_nguon();
            $model->manl = $insert['manl'];
            $model->macanbo = $insert['macanbo'];
            $model->manguonkp = $insert['manguonkp'];
            $model->luongcoban = $insert['luongcoban'];
            $model->save();
        }
        $model = dsnangluong_nguon::where('manl', $insert['manl'])
            ->where('macanbo', $insert['macanbo'])->get();


        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    function destroy_nkp(Request $request)
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
        $model = dsnangluong_nguon::find($inputs['id']);
        $model->delete();
        $model = dsnangluong_nguon::where('manl', $model->manl)->where('macanbo', $model->macanbo)->get();
        //$a_pl = getPhanLoaiKiemNhiem();
        $result = $this->retun_html_kn($result, $model);
        die(json_encode($result));
    }

    function retun_html_kn($result, $model)
    {
        $a_nkp = getNguonKP(false);
        $result['message'] = '<div class="row" id="dsnkp">';
        $result['message'] .= '<div class="col-md-12">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_4">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr>';
        $result['message'] .= '<th class="text-center" style="width: 5%">STT</th>';
        $result['message'] .= '<th class="text-center">Nguồn kinh phí</th>';
        $result['message'] .= '<th class="text-center" style="width: 15%">Số tiền</th>';
        $result['message'] .= '<th class="text-center" style="width: 15%">Thao tác</th>';

        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';
        $result['message'] .= '<tbody>';
        if (count($model) > 0) {
            foreach ($model as $key => $value) {
                $result['message'] .= '<tr>';
                $result['message'] .= '<td style="text-align: center">' . ($key + 1) . '</td>';
                $result['message'] .= '<td>' . (isset($a_nkp[$value->manguonkp]) ? $a_nkp[$value->manguonkp] : '') . '</td>';
                $result['message'] .= '<td>' . dinhdangso($value->luongcoban) . '</td>';
                $result['message'] .= '<td>' .
                    '<button type="button" data-target="#nguonkp-modal" data-toggle="modal" class="btn btn-default btn-xs mbs" onclick="edit_nkp(&#39;' . $value->id . '&#39;);"><i class="fa fa-edit"></i>&nbsp;Sửa</button>' .
                    '<button type="button" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal" onclick="cfDel(&#39;'.$value->id. '&#39;)" ><i class="fa fa-trash-o"></i>&nbsp;Xóa</button>'

                    . '</td>';
                $result['message'] .= '</tr>';
            }
            $result['message'] .= '</tbody>';
            $result['message'] .= '</table>';
            $result['message'] .= '</div>';
            $result['message'] .= '</div>';
            $result['status'] = 'success';
            return $result;
        }
        return $result;
    }
}
