<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\nguonkinhphi_tinh;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopnguon_huyenController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            $model_nguon = nguonkinhphi_huyen::where('macqcq', $madv)->where('trangthai','DAGUI')->get();
            $model_nguon_tinh = nguonkinhphi_tinh::where('madv', $madv)->get();
            //$model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            $model = dmthongtuquyetdinh::all();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $soluong = $model_donvi->count();
            foreach($model as $dv){
                $nguon_huyen = $model_nguon_tinh->where('sohieu', $dv->sohieu)->first();
                if(count($nguon_huyen)>0){
                    //Đã tổng hợp dữ liệu
                    $dv->sldv = $soluong . '/' . $soluong;
                    $dv->masodv = $nguon_huyen->masodv;
                    $dv->trangthai = $nguon_huyen->trangthai;
                    //$dv->trangthai = 'DAGUI';
                }else{
                    //Chưa tổng hợp dữ liệu
                    $sl = $model_nguon->where('sohieu', $dv->sohieu)->count();
                    $dv->sldv = $sl . '/' . $soluong;
                    $dv->masodv = null;
                    if($sl==0){
                        $dv->trangthai = 'CHUADL';
                    }elseif($sl < $soluong) {
                        $dv->trangthai = 'CHUADAYDU';
                    }
                    elseif($sl == $soluong){
                            $dv->trangthai = 'CHUAGUI';
                    }else{
                        $dv->trangthai = 'CHUATAO';
                    }
                }
            }

            return view('functions.tonghopnguon.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$soluong)
                ->with('furl_xem','/chuc_nang/xem_du_lieu/nguon/huyen')
                ->with('furl_th','/chuc_nang/tong_hop_nguon/huyen/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp nguồn kinh phí');

        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI

        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = nguonkinhphi_huyen::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            $madv = $model->madv;
            $phanloai = dmdonvi::where('madv', $madv)->first()->phanloaitaikhoan;

            if($phanloai == 'SD'){
                nguonkinhphi::where('sohieu', $model->sohieu)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo'=>$inputs['lydo']]);
            }else{
                nguonkinhphi_khoi::where('sohieu', $model->sohieu)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo'=>$inputs['lydo']]);
            }

            return redirect('/chuc_nang/xem_du_lieu/nguon/huyen?sohieu=' . $model->sohieu . '&trangthai=ALL');
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $madv = session('admin')->madv;
            $model_nguon_huyen = nguonkinhphi_tinh::where('sohieu',$inputs['sohieu'])->where('madv', $madv)->first();
            //$model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])->where('macqcq', $madv)->get();
            if (count($model_nguon_huyen) > 0) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_huyen->trangthai = 'DAGUI';
                $model_nguon_huyen->nguoilap = session('admin')->name;
                $model_nguon_huyen->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_huyen->save();
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                nguonkinhphi::where('sohieu',$inputs['sohieu'])->where('macqcq', $madv)
                    ->update(['masot' => $inputs['masodv']]);

                //nguonkinhphi_huyen::create($inputs);
                nguonkinhphi_tinh::create($inputs);
            }
            return redirect('/chuc_nang/tong_hop_nguon/huyen/index');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = nguonkinhphi_bangluong::where('masoh', $inputs['maso'])->where('thang', '07')->orderby('stt')->get();
            //dd($model);

            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = nguonkinhphi::where('masoh', $inputs['maso'])->first();
            $a_congtac = array_column(dmphanloaict::wherein('mact',a_unique(array_column($model->toarray(),'mact')))->get()->toArray(), 'tenct', 'mact');
            //dd($a_ct);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            foreach ($model as $ct) {
                foreach ($m_pc as $pc) {
                    $ma = $pc['mapc'];
                    $ma_st = 'st_'.$pc['mapc'];
                    $ct->$ma = $ct->$ma * 6;
                    $ct->$ma_st = $ct->$ma_st * 6;
                }
                $ct->tonghs = $ct->tonghs * 6;
                $ct->ttl = $ct->luongtn * 6;
                $ct->stbhxh_dv = $ct->stbhxh_dv * 6;
                $ct->stbhyt_dv = $ct->stbhyt_dv * 6;
                $ct->stkpcd_dv = $ct->stkpcd_dv * 6;
                $ct->stbhtn_dv = $ct->stbhtn_dv * 6;
                $ct->ttbh_dv = $ct->ttbh_dv * 6;
            }

            //dd($model);
            $thongtin = array('nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns);

            return view('reports.nguonkinhphi.huyen.bangluong_m2')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }
    function tonghop(Request $request){
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            //if ((Session::has('admin') && session('admin')->username == 'khthstc') || (Session::has('admin') && session('admin')->username == 'khthso') ) {
            $inputs = $request->all();
            $inputs['donvitinh'] = 1;
            $madvbc = session('admin')->madvbc;
            $m_dv = dmdonvi::select('tendv','madv')
                ->where('madvbc',$madvbc)
                ->distinct()
                ->get();
            $model = nguonkinhphi::where('madvbc',$madvbc)
                ->where('sohieu',$inputs['sohieu'])
                ->where('macqcq',session('admin')->madv)
                ->where('trangthai','DAGUI')
                ->get();
            $ardv = $m_dv->toArray();
            if(count($model) == 0){
                return view('errors.nodata');
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $data = array();

            $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0,'khac' => 0, 'nguonthu' => 0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT

                for ($i = 0; $i < count($data); $i++) {
                    $solieu = $model->where('linhvuchoatdong', $data[$i]['val']);
                    $data[$i]['nhucau'] = $solieu->sum('nhucau');
                    $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                    $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                    $data[$i]['hocphi'] = $solieu->sum('hocphi');
                    $data[$i]['vienphi'] = $solieu->sum('vienphi');
                    $data[$i]['khac'] = 0;
                    $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
                }
                $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
                $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
                $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
                $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
                $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
                $data[0]['khac'] = 0;
                $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];

                $data[4]['nhucau'] = $model->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
                $data[4]['nguonkp'] = $model->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
                $data[4]['tietkiem'] = $model->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
                $data[4]['hocphi'] = $model->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
                $data[4]['vienphi'] = $model->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
                $data[4]['khác'] = 0;
                $data[4]['nguonthu'] = $model->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];

            return view('reports.thongtu67.mau4b_tt68')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('inputs',$inputs)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }
}
