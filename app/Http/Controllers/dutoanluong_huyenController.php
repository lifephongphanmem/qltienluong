<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use App\dutoanluong_tinh;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dutoanluong_huyenController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            $model_nguon = dutoanluong_huyen::where('macqcq', $madv)->where('trangthai','DAGUI')->get();
            $model_nguon_tinh = dutoanluong_tinh::where('madv', $madv)->get();
            //$model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            $model = dutoanluong::select('namns')->where('madvbc', session('admin')->madvbc)->distinct()->get();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $soluong = $model_donvi->count();

            foreach($model as $dv){
                $nguon_huyen = $model_nguon_tinh->where('namns', $dv->namns)->first();
                if(count($nguon_huyen)>0){
                    //Đã tổng hợp dữ liệu
                    $dv->sldv = $soluong . '/' . $soluong;
                    $dv->masodv = $nguon_huyen->masodv;
                    $dv->trangthai = $nguon_huyen->trangthai;
                    //$dv->trangthai = 'DAGUI';
                }else{
                    //Chưa tổng hợp dữ liệu
                    $sl = $model_nguon->where('namns', $dv->namns)->count();
                    $dv->sldv = $sl . '/' . $soluong;
                    $dv->masodv = null;
                    if($sl==0){
                        $dv->trangthai = 'CHUADL';
                    }elseif($sl < $soluong){
                        $dv->trangthai = 'CHUADAYDU';
                    }else{
                        $dv->trangthai = 'CHUATAO';
                    }
                }
            }

            return view('functions.dutoanluong.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$soluong)
                ->with('furl_xem','/chuc_nang/xem_du_lieu/du_toan/huyen')
                ->with('furl_th','/chuc_nang/du_toan_luong/huyen/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp dự toán lương');

        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dutoanluong_huyen => madv => phanloaitaikhoan => dutoanluong / dutoanluong_khoi
            $model = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            $madv = $model->madv;
            $phanloai = dmdonvi::where('madv', $madv)->first()->phanloaitaikhoan;

            if($phanloai == 'SD'){
                dutoanluong::where('namns', $model->namns)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo'=>$inputs['lydo']]);
            }else{
                dutoanluong_khoi::where('namns', $model->namns)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo'=>$inputs['lydo']]);
            }

            return redirect('/chuc_nang/xem_du_lieu/du_toan/huyen?namns=' . $model->namns . '&trangthai=ALL');
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

            $model_nguon_huyen = dutoanluong_tinh::where('namns',$inputs['namns'])->where('madv', $madv)->first();
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

                dutoanluong::where('namns',$inputs['namns'])->where('macqcq', $madv)
                    ->update(['masot' => $inputs['masodv']]);

                //nguonkinhphi_huyen::create($inputs);
                dutoanluong_tinh::create($inputs);
            }
            return redirect('/chuc_nang/du_toan_luong/huyen/index');
        } else
            return view('errors.notlogin');
    }

    //In dữ liệu 1 đơn vị trong huyen
    function printf(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model_huyen = dutoanluong_huyen::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong::where('masoh', $inputs['maso'])->first();
            $requests['madv'] = $model_huyen->madv;
            $requests['namns'] = $model_huyen->namns;
            $phanloai = dmdonvi::where('madv', $model_huyen->madv)->first()->phanloaitaikhoan;
            $bl = new dutoanluong_khoiController();

            if($phanloai == 'SD'){
                $requests['maso'] = $model->masodv;
                return $bl->printf($requests);
            }else{
                return $bl->tonghop($requests);
            }
        } else
            return view('errors.notlogin');
    }

    //Tổng hợp dữ liệu trong huyen
    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //lấy dự toán lương chi tiết
            //lấy dữ liệu chi tiết: thêm phân loại SD $ TH -> SD:madv; TH:macqcq (làm theo huong nay - chua lam)
            if(!isset($inputs['madv'])){
                $inputs['madv'] = session('admin')->madv;
            }

            if(!isset($inputs['madvbc'])){
                $inputs['madvbc'] = session('admin')->madvbc;
            }

            //chú ý trùng dữ liẹuu
            $model = dutoanluong_chitiet::wherein('masodv', function($query) use ($inputs){
                $query->select('masodv')->from('dutoanluong')->where('madvbc', $inputs['madvbc'])->whereNull('masok')->where('namns',$inputs['namns'])->get();
                })->get();

            $model_th = dutoanluong_chitiet::wherein('masodv', function($query) use ($inputs){
                $query->select('masodv')->from('dutoanluong')->wherein('masok', function($q) use ($inputs){
                    $q->select('masodv')->from('dutoanluong_khoi')->where('madvbc', $inputs['madvbc'])->where('namns',$inputs['namns'])->get();
                })->get();})->get();

            foreach ($model_th as $donvi) {
                //$model->add($donvi);
            }

            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use ($inputs){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$inputs['madv'])->where('madv','<>',$inputs['madv'])->get();
                })->get();

            $model_dutoan = dutoanluong::wherein('madv', function($query) use ($inputs){
                $query->select('madv')->from('dmdonvi')->where('madvbc', $inputs['madvbc'])->get();
            })->get();

            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_nhomct = getNhomCongTac(false);

            foreach($model as $ct) {
                $dutoan = $model_dutoan->where('masodv', $ct->masodv)->first();

                $ct->madv = count($dutoan) > 0 ? $dutoan->madv : null;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->tencongtac = isset($a_nhomct[$ct->macongtac]) ? $a_nhomct[$ct->macongtac] : '';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;
            }
            //dd($model->toarray());
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            $thongtin = array('nam' => $inputs['namns']);

            return view('reports.viewdata.dutoan.khoi')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_donvi', $model_donvi)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Tổng hợp dự toán lương.');
        } else
            return view('errors.notlogin');
    }
    function chitietbl(Request $requests)
    {
        if (Session::has('admin')) {
            //dd($masodv);
            $inputs = $requests->all();
            $masodv = $inputs['maso'];
            $model = dutoanluong_bangluong::wherein('masodv',function($query) use($masodv){
                $query->select('masodv')->from('dutoanluong')->where('masoh',$masodv)->get();
            } )->orderby('thang')->get();
            $model_thongtin = dutoanluong::where('masoh', $masodv)->first();
            //$model = dutoanluong_bangluong::wherein('masodv', array_column($model_thongtin->toarray(),'masodv'))->get();
            //$model_thongtin = dutoanluong::where('masoh', $masodv)->first();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

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

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            //Lấy dữ liệu để lập
            $model_thang = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_thang = a_unique($model_thang);

            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang','mact'])
                    ->all();
            });
            $model_congtac = a_unique($model_congtac);


            return view('reports.dutoanluong.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_ct', $a_ct)
                ->with('model_thang', $model_thang)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }
}
