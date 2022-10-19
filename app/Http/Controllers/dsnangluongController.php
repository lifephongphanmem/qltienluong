<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmdonvi;
use App\dmphucap_donvi;
use App\dsnangluong;
use App\dsnangluong_chitiet;
use App\dsnangluong_nguon;
use App\hosocanbo;
use App\hosoluong;
use App\hosophucap;
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
        // dd($inputs);
        $model = dsnangluong::where('manl', $inputs['manl'])->first();
        if ($model != null) {
            //update
            $model->update($inputs);
            return redirect('/chuc_nang/nang_luong/danh_sach');
        } else {
            $madv = session('admin')->madv;
            $manl = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['manl'] = $manl;
            $a_ngachluong = ngachluong::all()->keyby('msngbac')->toarray();
            $a_canbo = hosocanbo::select('macanbo','msngbac','bac','ngaytu','ngayden','msngbac','heso','vuotkhung')
                ->where('ngayden', '<=', $inputs['ngayxet'])->where('theodoi','<','9')
                ->where('madv', $madv)->get()->keyby('macanbo')->toarray();
            $a_data = array();
            $a_data_nguon = array();
            $a_nguon_df = getNguonTruyLinh_df();
            //dd($a_nguon_df);
            foreach ($a_canbo as $key=>$val) {
                if(getDayVn($a_canbo[$key]['ngaytu']) == '' || getDayVn($a_canbo[$key]['ngayden']) == ''){
                    continue;
                }
                $ngayden = $a_canbo[$key]['ngayden'];
                $a_canbo[$key]['manl'] = $manl;
                $a_canbo[$key]['phanloai'] = 'DUNGHAN';
                $a_canbo[$key]['ngaytl'] = 0;
                $a_canbo[$key]['thangtl'] = 0;
                $a_canbo[$key]['truylinhtungay'] = null;
                $a_canbo[$key]['truylinhdenngay'] = null;
                $a_canbo[$key]['hesott'] = 0;
                $a_canbo[$key]['msngbac_cu']= $val['msngbac'];
                $a_canbo[$key]['bac_cu']= $val['bac'];
                $a_canbo[$key]['heso_cu']= $val['heso'];
                $a_canbo[$key]['vuotkhung_cu']= $val['vuotkhung'];

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
                    $a_canbo[$key]['truylinhtungay'] = new Carbon($ngayden);
                    $ngayxet = new Carbon($inputs['ngayxet']);
                    if($ngayxet->day == 1){
                        $ngayxet->addDay(-1);
                    }
                    $a_canbo[$key]['truylinhdenngay'] = $ngayxet;
                    list($a_canbo[$key]['thangtl'], $a_canbo[$key]['ngaytl']) = $this->getThoiGianTL($a_canbo[$key]['truylinhtungay'], $a_canbo[$key]['truylinhdenngay']);
                }
                $a_data[] = $a_canbo[$key];
                foreach($a_nguon_df as $k=>$v) {
                    $a_data_nguon[] = array('macanbo' => $key, 'manguonkp' => $k, 'luongcoban' => $v, 'manl' => $manl);
                }
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
        $thangtl = $thangtl < 0 ? 0 : $thangtl;
        $ngaytl = $ngaytl < 0 ? 0 : $ngaytl;
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
            $model_nangluong = dsnangluong::where('manl', $manl)->first();
            $a_cv = getChucVuCQ(false);
            $a_cb = hosocanbo::select('macanbo', 'macvcq', 'tencanbo')
                ->where('madv', session('admin')->madv)->get()->keyby('macanbo')->toarray();
            //dd($model_nangluong);

            foreach ($model as $hs) {
                if (isset($a_cb[$hs->macanbo])) {
                    $canbo = $a_cb[$hs->macanbo];
                    $hs->tencanbo = $canbo['tencanbo'];
                    $hs->tencv = isset($a_cv[$canbo['macvcq']]) ? $a_cv[$canbo['macvcq']] : '';
                }
            }
            //lấy danh sách cán bộ không có trong danh sách

            $model_canbo = hosocanbo::where('madv', session('admin')->madv)
                ->wherenotnull('msngbac')
                ->where('theodoi', '<', '9')
                ->wherenotin('macanbo',array_column($model->toarray(),'macanbo'))
                ->get();

            //dd($model_canbo);

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
            $model->tencanbo = $model_canbo->tencanbo ?? '';
            
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

    function add_canbo(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nangluong = dsnangluong::where('manl', $inputs['manl'])->first();
            $model_canbo = hosocanbo::select('macanbo','msngbac','bac','ngaytu','ngayden','msngbac','heso','vuotkhung')
                ->where('macanbo', $inputs['macanbo'])->first();
            $a_nb = ngachluong::where('msngbac',$model_canbo->msngbac)->first();
            if($a_nb == null){
                //trả lại danh sách
                // return redirect('chuc_nang/nang_luong/maso='.$inputs['manl']);
                return view('thongbao.data_error')
                        ->with('message','Cán bộ chưa chọn ngạch lương')
                        ->with('furl','chuc_nang/nang_luong/maso='.$inputs['manl']);
            }

            $ngayxet = new Carbon($model_nangluong->ngayxet);
            $model_canbo->manl = $inputs['manl'];
            $model_canbo->phanloai = 'TRUOCHAN';
            $model_canbo->ngaytu = $ngayxet->toDateString('Y-m-d');
            $model_canbo->ngayden = $ngayxet->addYear($a_nb['namnb'])->toDateString('Y-m-d');
            $model_canbo->msngbac_cu= $model_canbo->msngbac;
            $model_canbo->bac_cu= $model_canbo->bac;
            $model_canbo->heso_cu= $model_canbo->heso;
            $model_canbo->vuotkhung_cu= $model_canbo->vuotkhung;

            if($model_canbo->heso < $a_nb->hesolonnhat){//nâng lương ngạch bậc
                $model_canbo['heso'] += $a_nb['hesochenhlech'];
                $model_canbo['bac'] = $model_canbo['bac'] < $a_nb['baclonnhat'] - 1 ? $model_canbo['bac'] + 1 : $a_nb['baclonnhat'];
            }else{//vượt khung
                $model_canbo['vuotkhung'] = $model_canbo['vuotkhung'] + ($model_canbo['vuotkhung'] == 0?$a_nb['vuotkhung'] : 1);
                $model_canbo['bac'] = $a_nb['baclonnhat'];
            }
            /* Nâng trc hạn ko có truy lĩnh
            foreach($a_nguon_df as $k=>$v) {
                $a_data_nguon[] = array('macanbo' => $inputs['macanbo'], 'manguonkp' => $k, 'luongcoban' => $v, 'manl' => $inputs['manl']);
            }
            dsnangluong_nguon::insert($a_data_nguon);
            */
            dsnangluong_chitiet::create($model_canbo->toarray());

            return redirect('chuc_nang/nang_luong/chi_tiet?maso='.$inputs['manl'].'&canbo='.$inputs['macanbo']);
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
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)
                ->where('mapc','<>','vuotkhung')
                ->where('phanloai',2)->get();
            $m_nkp_df = getNguonTruyLinh();
            $a_truylinh = array();
            $a_nkp = array();
            $a_phucap = array();
            foreach ($model as $canbo) {
                $ma = $ma + 1;
                $hoso = hosocanbo::where('macanbo', $canbo->macanbo)->first();
                $pl_phucap = $hoso->heso == $canbo->heso ? 'vuotkhung':'heso';

                $hoso->hesobl = $hoso->hesobl + $hoso->heso - $canbo->heso;
                $hoso->hesobl = $hoso->hesobl < 0 ? 0 : $hoso->hesobl;
                $hoso->heso = $canbo->heso;
                $hoso->bac = $canbo->bac;
                $hoso->vuotkhung = $canbo->vuotkhung;
                $hoso->ngaytu = $canbo->ngaytu;
                $hoso->ngayden = $canbo->ngayden;

                $a_tl = array();
                $a_tl['maso'] = session('admin')->madv . '_' . $ma;
                //lưu truy lĩnh
                if (isset($canbo->truylinhtungay) && $canbo->hesott > 0) {
                    //$truylinh = new hosotruylinh();
                    foreach ($model_pc as $pc) {
                        $mapc = $pc->mapc;
                        $a_tl[$mapc] = $hoso->$mapc;
                    }

                    $a_tl['macvcq'] = $canbo->macvcq;
                    $a_tl['mapb'] = $canbo->mapb;
                    $a_tl['mact'] = $canbo->mact;
                    $a_tl['macanbo'] = $canbo->macanbo;
                    $a_tl['tencanbo'] = $hoso->tencanbo;
                    $a_tl['ngaytu'] = $canbo->truylinhtungay;
                    $a_tl['ngayden'] = $canbo->truylinhdenngay;
                    $a_tl['thangtl'] = $canbo->thangtl;
                    $a_tl['ngaytl'] = $canbo->ngaytl;
                    $a_tl['madv'] = session('admin')->madv;
                    $a_tl['noidung'] = 'Truy lĩnh nâng lương ngạch bậc';
                    $a_tl['msngbac'] = $canbo->msngbac;
                    $a_tl['heso'] = $canbo->hesott; //hệ số truy lĩnh đều đưa vào hệ số
                    $a_tl['maphanloai'] = 'MSNGBAC'; //hệ số truy lĩnh đều đưa vào hệ số
                    $a_truylinh[] = $a_tl;

                    $nguon = $model_nguon->where('macanbo', $canbo->macanbo);
                    if (count($nguon) > 0) {
                        foreach ($nguon as $nkp) {
                            $a_nkp[] = array(
                                'maso' => $a_tl['maso'], 'manguonkp' => $nkp->manguonkp, 'luongcoban' => $nkp->luongcoban
                            );
                        }
                    } else {//lấy nguồn mặc định
                        foreach ($m_nkp_df as $k => $v) {
                            $a_nkp[] = array(
                                'maso' => $a_tl['maso'], 'manguonkp' => $k, 'luongcoban' => $v
                            );
                        }
                    }
                }
                //lưu hosophucap (vẫn lấy má số do theo cán bộ
                $a_phucap[] = array(
                    'maso' => $a_tl['maso'], 'maphanloai' => 'CONGTAC', 'macanbo' => $canbo->macanbo,
                    'ngaytu' => $canbo->ngaytu, 'ngayden' => $canbo->ngayden, 'macvcq' => $hoso->macvcq,
                    'mapc' => $pl_phucap, 'heso' => $pl_phucap=='heso'?$canbo->heso:$canbo->vuotkhung
                );
                $hoso->save();
                /*
                //Lưu thông tin vào hồ sơ cán bộ
                unset($data['manl']);
                unset($data['ghichu']);
                unset($data['manguonkp']);
                $hoso->update($data);
                */
            }

            hosophucap::insert($a_phucap);
            hosotruylinh_nguon::insert($a_nkp);
            hosotruylinh::insert($a_truylinh);
            dsnangluong::where('manl', $manl)->update(['trangthai' => 'Đã nâng lương']);
            return redirect('/chuc_nang/nang_luong/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dsnangluong::find($id);
            dsnangluong_chitiet::where('manl',$model->manl)->delete();
            dsnangluong_nguon::where('manl',$model->manl)->delete();
            $model->delete();
            return redirect('/chuc_nang/nang_luong/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroydt($id){
        if (Session::has('admin')) {
            $model = dsnangluong_chitiet::find($id);
            dsnangluong_nguon::where('macanbo',$model->macanbo)->where('manl',$model->manl)->delete();
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
        if($model_chk != null ){
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

    function printf_data(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nangluong = dsnangluong::where('manl', $inputs['maso'])->first();
            $model = dsnangluong_chitiet::where('manl', $inputs['maso'])->get();

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $a_cv = getChucVuCQ(false);
            $a_cb = hosocanbo::select('macanbo','tencanbo','macvcq')->where('madv', session('admin')->madv)->get()->keyBy('macanbo')->toArray();
            $a_nb = ngachluong::all()->keyby('msngbac')->toarray();

            foreach ($model as $ct) {
                if (isset($a_cb[$ct->macanbo])) {
                    $ct->tencanbo = $a_cb[$ct->macanbo]['tencanbo'];
                    $ct->macvcq = $a_cb[$ct->macanbo]['macvcq'];
                    $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                    $ct->trangthai = $model_nangluong->trangthai == 'Đã nâng lương' ? 'DANANGLUONG' : 'CHUANANGLUONG';
                    //lưu hệ số cũ (thiết kế lại để dùng chung report)
                    $ct->heso_m = $ct->heso;
                    $ct->bac_m = $ct->bac;
                    $ct->vuotkhung_m = $ct->vuotkhung;

                    if(isset($a_nb[$ct->msngbac])){
                        $ngachluong = $a_nb[$ct->msngbac];

                        if($ct->heso < $ngachluong['hesolonnhat']){//nâng lương ngạch bậc
                            $ct->heso = $ct->heso - $ngachluong['hesochenhlech'];
                            $ct->bac = $ct->bac < $ngachluong['baclonnhat'] - 1 ? $ct->bac - 1 : $ngachluong['baclonnhat'];
                            $ct->vuotkhung = 0;
                        }else{//vượt khung
                            if($ct->vuotkhung == 0){//lần đầu
                                $ct->heso = $ct->heso - $ngachluong['hesochenhlech'];
                                $ct->bac = $ct->bac - 1;
                                $ct->vuotkhung = 0;
                            }else{
                                $ct->vuotkhung = $ct->vuotkhung - 1;
                                $ct->heso;
                                $ct->bac;
                            }
                        }
                    }
                }
            }

            $a_pl = $model->map(function($data){
                return collect($data->toArray())
                    ->only(['trangthai'])
                    ->all();
            });
            //dd($model);
            return view('reports.donvi.nangluong_ngachbac')
                //->with('model',$model->sortby('ngayden')->sortby('stt'))
                ->with('model',$model->sortby('ngayden'))
                ->with('m_dv',$m_dv)
                ->with('a_pl',a_unique($a_pl))
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách cán bộ');


        } else
            return view('errors.notlogin');
    }
}
