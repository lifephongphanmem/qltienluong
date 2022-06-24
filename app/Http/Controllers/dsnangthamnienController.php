<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dsnangthamnien;
use App\dsnangthamnien_ct;
use App\dsnangthamnien_nguon;
use App\hosocanbo;
use App\hosophucap;
use App\hosotruylinh;
use App\hosotruylinh_nguon;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dsnangthamnienController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dsnangthamnien::where('madv',session('admin')->madv)->get();
            return view('manage.thamnien.index')
                ->with('furl','/chuc_nang/tham_nien/')
                //->with('furl_ajax','/ajax/nang_luong/')
                ->with('model',$model)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $inputs = $request->all();
        $model = dsnangthamnien::where('manl', $inputs['manl'])->first();

        if ($model != null) {
            //update
            $model->update($inputs);
            return redirect('/chuc_nang/tham_nien/danh_sach');
        } else {
            $madv = session('admin')->madv;
            $manl = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['manl'] = $manl;

            $m_canbo = hosocanbo::select('macanbo','msngbac','heso','vuotkhung','pccv','tnntungay','tnndenngay','pctnn')
                ->where('tnndenngay', '<=', $inputs['ngayxet'])
                ->where('theodoi','<','9')
                ->where('madv', $madv)->get();

            $a_data = array();
            $a_data_nguon = array();
            $a_nguon_df = getNguonTruyLinh_df();

            $inputs['trangthai'] = 'Tạo danh sách';

            foreach ($m_canbo as $cb) {
                if(getDayVn($cb->tnndenngay) == '' || getDayVn($cb->tnntungay) == ''){
                    continue;
                }
                $cb->manl = $manl;
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $cb->pctnn = $cb->pctnn == 0 ? 5 : $cb->pctnn + 1;

                $date = new Carbon($cb->tnndenngay);
                $cb->ngaytu = $date->toDateString();
                $cb->ngayden = $date->addYear('1')->toDateString();

                $cb->heso = ($cb->vuotkhung + $cb->heso + $cb->pccv) / 100;
                //kiểm tra truy lĩnh nếu ngày xét = ngày nâng lương = > ko truy lĩnh
                if ($inputs['ngayxet'] > $cb->tnndenngay) {
                    $cb->truylinhtungay = (new Carbon($cb->tnndenngay))->toDateString();
                    $ngayxet = new Carbon($inputs['ngayxet']);
                    if($ngayxet->day == 1){
                        $ngayxet->addDay(-1);
                    }
                    $cb->truylinhdenngay = $ngayxet->toDateString();

                    list($cb['thangtl'], $cb['ngaytl']) = $this->getThoiGianTL($cb['truylinhtungay'], $cb['truylinhdenngay']);
                } else {
                    $cb['ngaytl'] = 0;
                    $cb['thangtl'] = 0;
                    $cb->truylinhtungay = null;
                    $cb->truylinhdenngay = null;
                }
                foreach($a_nguon_df as $k=>$v) {
                    $a_data_nguon[] = array('macanbo' => $cb->macanbo, 'manguonkp' => $k, 'luongcoban' => $v, 'manl' => $manl);
                }
                $a_data[] = $cb->toarray();
            }
            $a_data = unset_key($a_data,array('tnndenngay','tnntungay'));

            dsnangthamnien_ct::insert($a_data);
            dsnangthamnien_nguon::insert($a_data_nguon);
            dsnangthamnien::create($inputs);
            //dsnangthamnien_ct::insert($m_canbo->toarray());
            return redirect('/chuc_nang/tham_nien/maso='.$manl);
        }
    }

    function getThoiGianTL($tungay,$denngay){
        $ngaytl = 0;
        $ngaycong = 22;
        $tungay = new Carbon($tungay);
        $denngay = new Carbon($denngay);

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

    function show($manl){
        if (Session::has('admin')) {
            $model = dsnangthamnien_ct::where('manl',$manl)->get();            
            $a_cv = getChucVuCQ(false);
            $a_cb = hosocanbo::select('macanbo', 'macvcq', 'tencanbo', 'stt')
                ->where('madv', session('admin')->madv)->get()->keyby('macanbo')->toarray();

            foreach ($model as $hs) {
                if (isset($a_cb[$hs->macanbo])) {
                    $canbo = $a_cb[$hs->macanbo];
                    $hs->tencanbo = $canbo['tencanbo'];
                    $hs->stt = $canbo['stt'];
                    $hs->tencv = isset($a_cv[$canbo['macvcq']]) ? $a_cv[$canbo['macvcq']] : '';
                }
            }
            $model_nangluong = dsnangthamnien::where('manl',$manl)->first();
            $model_canbo = hosocanbo::where('madv', session('admin')->madv)
                ->wherenotnull('msngbac')
                ->where('theodoi', '<', '9')
                ->wherenotin('macanbo',array_column($model->toarray(),'macanbo'))
                ->get();


            return view('manage.thamnien.nangluong')
                ->with('furl','/chuc_nang/tham_nien/')
                ->with('model',$model->sortBy('stt'))
                ->with('model_nangluong',$model_nangluong)
                ->with('a_canbo', array_column($model_canbo->toarray(), 'tencanbo', 'macanbo'))
                ->with('pageTitle','Chi tiết danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }

    function detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dsnangthamnien_ct::where('manl', $inputs['maso'])->where('macanbo', $inputs['canbo'])->first();
            $model_nguon = dsnangthamnien_nguon::where('manl', $inputs['maso'])->where('macanbo', $inputs['canbo'])->get();
            //dd($model);
            $model_canbo = hosocanbo::select('macanbo', 'macvcq', 'tencanbo')->where('macanbo', $model->macanbo)->first();            
            $model->tencanbo = $model_canbo->tencanbo ?? '';
            
            $model_nangluong = dsnangthamnien::where('manl', $inputs['maso'])->first();
            return view('manage.thamnien.detail')
                ->with('furl', '/chuc_nang/tham_nien/')
                ->with('model', $model)
                ->with('model_nangluong', $model_nangluong)
                ->with('model_nkp', $model_nguon)
                ->with('a_nkp', getNguonKP(false))
                ->with('pageTitle', 'Chi tiết danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['truylinhtungay'] = getDateTime($inputs['truylinhtungay']);
            $inputs['truylinhdenngay'] = getDateTime($inputs['truylinhdenngay']);
            $inputs['heso'] = chkDbl($inputs['heso']);
            $model = dsnangthamnien_ct::where('manl',$inputs['manl'])->where('macanbo',$inputs['macanbo'])->first();
            $model->update($inputs);
            return redirect('chuc_nang/tham_nien/maso='.$model->manl);
        } else
            return view('errors.notlogin');
    }

    function add_canbo(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nangluong = dsnangthamnien::where('manl', $inputs['manl'])->first();
            $model_canbo = hosocanbo::select('macanbo','msngbac','heso','vuotkhung','pccv','tnntungay','tnndenngay','pctnn')
                ->where('macanbo', $inputs['macanbo'])->first();

            $a_data_nguon = array();
            $a_nguon_df = getNguonTruyLinh_df();

            $model_canbo->manl = $model_nangluong->manl;
            $model_canbo->vuotkhung = $model_canbo->heso * $model_canbo->vuotkhung / 100;
            $model_canbo->pctnn = $model_canbo->pctnn == 0 ? 5 : $model_canbo->pctnn + 1;

            $date = new Carbon($model_canbo->tnndenngay);
            $model_canbo->ngaytu = $date->toDateString();
            $model_canbo->ngayden = $date->addYear('1')->toDateString();

            //kiểm tra truy lĩnh nếu ngày xét = ngày nâng lương = > ko truy lĩnh
            if ($model_nangluong->ngayxet > $model_canbo->tnndenngay) {
                $model_canbo->truylinhtungay = (new Carbon($model_canbo->tnndenngay))->toDateString();
                $model_canbo->truylinhdenngay = (new Carbon($model_nangluong->ngayxet))->toDateString();
                //lưu hệ số truy lĩnh
                $model_canbo->heso = ($model_canbo->vuotkhung + $model_canbo->heso + $model_canbo->pccv) * $model_canbo->pctnn/ 100;
                list($model_canbo['thangtl'], $model_canbo['ngaytl']) = $this->getThoiGianTL($model_canbo['truylinhtungay'], $model_canbo['truylinhdenngay']);
                foreach($a_nguon_df as $k=>$v) {
                    $a_data_nguon[] = array('macanbo' => $model_canbo->macanbo, 'manguonkp' => $k, 'luongcoban' => $v, 'manl' => $model_nangluong->manl);
                }

                dsnangthamnien_nguon::insert($a_data_nguon);
            } else {
                $model_canbo['ngaytl'] = 0;
                $model_canbo['thangtl'] = 0;
                $model_canbo->truylinhtungay = null;
                $model_canbo->truylinhdenngay = null;
            }

            dsnangthamnien_ct::create($model_canbo->toarray());

            return redirect('/chuc_nang/tham_nien/chi_tiet?maso='.$inputs['manl'].'&canbo='.$inputs['macanbo']);
        } else
            return view('errors.notlogin');
    }

    function nang_luong($manl){
        if (Session::has('admin')) {
            $model = dsnangthamnien_ct::where('manl',$manl)->get();
            $model_nguon = dsnangthamnien_nguon::where('manl', $manl)->get();
            $ma = getdate()[0];
            $m_nkp_df = getNguonTruyLinh();
            $a_truylinh = array();
            $a_nkp = array();
            $a_phucap = array();

            foreach($model as $canbo) {
                $ma = $ma + 1;
                $hoso = hosocanbo::where('macanbo', $canbo->macanbo)->first();
                $hoso->pctnn = $canbo->pctnn;
                $hoso->tnntungay = $canbo->ngaytu;
                $hoso->tnndenngay = $canbo->ngayden;
                $hoso->save();
                $a_tl = array();
                $a_tl['maso'] = session('admin')->madv . '_' . $ma;

                if (isset($canbo->truylinhtungay) && $canbo->heso > 0) {
                    //$truylinh = new hosotruylinh();
                    $a_tl['mact'] = $canbo->mact;
                    $a_tl['macvcq'] = $canbo->macvcq;
                    $a_tl['mapb'] = $canbo->mapb;
                    $a_tl['stt'] = $canbo->stt;
                    $a_tl['macanbo'] = $canbo->macanbo;
                    $a_tl['tencanbo'] = $hoso->tencanbo;
                    $a_tl['ngaytu'] = $canbo->truylinhtungay;
                    $a_tl['ngayden'] = $canbo->truylinhdenngay;
                    $a_tl['madv'] = session('admin')->madv;
                    $a_tl['noidung'] = 'Truy lĩnh nâng thâm niên nghề';
                    $a_tl['heso'] = $canbo->heso;
                    $a_tl['thangtl'] = $canbo->thangtl;
                    $a_tl['ngaytl'] = $canbo->ngaytl;
                    $a_tl['maphanloai'] = 'TNN';
                    $a_truylinh[] = $a_tl;
                    //$truylinh->save();

                    $nguon = $model_nguon->where('macanbo', $canbo->macanbo);
                    if(count($nguon)> 0){
                        foreach ($nguon as $nkp) {
                            $a_nkp[] = array(
                                'maso' => $a_tl['maso'], 'manguonkp' => $nkp->manguonkp, 'luongcoban' => $nkp->luongcoban
                            );
                        }
                    }else{//lấy nguồn mặc định
                        foreach ($m_nkp_df as $k=>$v) {
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
                    'mapc' => 'pctnn', 'heso' => $canbo->pctnn
                );
            }

            hosophucap::insert($a_phucap);
            hosotruylinh_nguon::insert($a_nkp);
            hosotruylinh::insert($a_truylinh);
            dsnangthamnien::where('manl',$manl)->update(['trangthai'=>'Đã nâng lương']);
            return redirect('/chuc_nang/tham_nien/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dsnangthamnien::find($id);
            dsnangthamnien_ct::where('manl',$model->manl)->delete();
            $model->delete();
            return redirect('/chuc_nang/tham_nien/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroydt($id){
        if (Session::has('admin')) {
            $model = dsnangthamnien_ct::find($id);
            $manl= $model->manl;
            dsnangthamnien_nguon::where('macanbo',$model->macanbo)->where('manl',$model->manl)->delete();
            $model->delete();
            return redirect('/chuc_nang/tham_nien/maso='.$manl);
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
        $model = dsnangthamnien::find($inputs['id']);
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
        die(dsnangthamnien_nguon::find($inputs['id']));
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

        $model_chk = dsnangthamnien_nguon::where('manl', $insert['manl'])
            ->where('macanbo', $insert['macanbo'])
            ->where('manguonkp', $insert['manguonkp'])->first();
        if($model_chk != null){
            $model_chk->macanbo = $insert['macanbo'];
            $model_chk->luongcoban = $insert['luongcoban'];
            $model_chk->manguonkp = $insert['manguonkp'];
            $model_chk->save();
        }else{
            $model = new dsnangthamnien_nguon();
            $model->manl = $insert['manl'];
            $model->macanbo = $insert['macanbo'];
            $model->manguonkp = $insert['manguonkp'];
            $model->luongcoban = $insert['luongcoban'];
            $model->save();
        }
        $model = dsnangthamnien_nguon::where('manl', $insert['manl'])
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
        $model = dsnangthamnien_nguon::find($inputs['id']);
        $model->delete();
        $model = dsnangthamnien_nguon::where('manl', $model->manl)->where('macanbo', $model->macanbo)->get();
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

            $model_nangluong = dsnangthamnien::where('manl', $inputs['maso'])->first();
            $model = dsnangthamnien_ct::where('manl', $inputs['maso'])->get();

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $a_cv = getChucVuCQ(false);
            $a_cb = hosocanbo::select('macanbo','tencanbo','macvcq')->where('madv', session('admin')->madv)->get()->keyBy('macanbo')->toArray();
            foreach ($model as $ct) {
                if (isset($a_cb[$ct->macanbo])) {
                    $ct->tencanbo = $a_cb[$ct->macanbo]['tencanbo'];
                    $ct->macvcq = $a_cb[$ct->macanbo]['macvcq'];
                }
                $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $ct->tnndenngay = $ct->ngaytu;
                $ct->trangthai = $model_nangluong->trangthai == 'Đã nâng lương' ? 'DANANGLUONG' : 'CHUANANGLUONG';
                $ct->pctnn_m = $ct->pctnn;//cho in trong báo cáo
                $ct->pctnn = $ct->pctnn == 5 ? 0 : $ct->pctnn - 1;
            }

            $a_pl = $model->map(function($data){
                return collect($data->toArray())
                    ->only(['trangthai'])
                    ->all();
            });
            //dd($model);
            return view('reports.donvi.nangluong_tnn')
                ->with('model', $model->sortby('tnndenngay'))
                ->with('inputs', $inputs)
                ->with('a_pl',a_unique($a_pl))
                ->with('m_dv',$m_dv)
                ->with('pageTitle', 'Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }
}
