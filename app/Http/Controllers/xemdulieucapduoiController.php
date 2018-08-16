<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\tonghop_huyen;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_huyen;
use App\tonghopluong_tinh;
use Illuminate\Http\Request;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class xemdulieucapduoiController extends Controller
{
    public function donvi_luong(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            $madv = session('admin')->madv;
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $a_trangthai=array('ALL'=>'--Chọn trạng thái dữ liệu--','CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu');
            $list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();

            if(!isset($inputs['trangthai']) || $inputs['trangthai']=='ALL'){
                $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')->where('macqcq', $madv)->get();
            }else{
                $trangthai = $inputs['trangthai'];

                //có thời gian nên làm chỉ cần biết đơn vi cấp dưới gửi dữ liệu chưa
                //ko cần chi tiết đơn vị đã tạo nhưng chưa gửi => mặc định là chưa gửi hết
                switch($trangthai){
                    case 'DAGUI':{
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $trangthai, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai',$trangthai)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();
                        break;
                    }
                    default :{
                       //Đơn vị đã tổng hợp dữ liệu nhưng chưa gửi
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai','<>','DAGUI')
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        //Đơn vi chưa tổng hợp dữ liệu
                        $model_donvi_chuatao = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->where('macqcq',$madv)
                            ->wherenotin('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        foreach($model_donvi_chuatao as $donvi){
                            $model_donvi->add($donvi);
                        }

                        break;
                    }
                }
            }

            foreach($model_donvi as $dv) {
                $donvi = $list_donvi->where('madv', $dv->macqcq)->first();
                $dv->tendvcq = (isset($donvi) ? $donvi->tendv : NULL);
                $model_bangluong = tonghopluong_donvi::where('madv', $dv->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->first();

                if (count($model_bangluong)>0) {
                    $dv->mathdv = $model_bangluong->mathdv;
                    $model_bangluong_ct = tonghopluong_donvi_chitiet::where('mathdv', $dv->mathdv)->first();
                    //dd($model_bangluong_ct->mathk);
                    $dv->tralai = (count($model_bangluong_ct) > 0 && $model_bangluong_ct->mathk != null) ? false : true;
                    //$dv->tralai = $model_bangluong_ct->mathk != null?false:true;
                } else {
                    $dv->mathdv = NULL;
                    $dv->tralai = true;
                }
            }
            //dd($model_donvi->toarray());
            return view('functions.viewdata.index')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }

    public function index_huyen_110818(Request $request)
    {
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madvqlkv = session('admin')->madvqlkv;

            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $a_phanloai = array('DONVI' => 'Dữ liệu tổng hợp của đơn vị', 'CAPDUOI' => 'Dữ liệu tổng hợp của các đơn vị cấp dưới');
            $list_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();

            //lấy danh sách đơn vị quản lý khối
            $model_qlkhoi = dmdonvi::select('madv', 'tendv', DB::raw('"CAPDUOI" as phanloai'))
                ->wherein('madv', function ($query) use ($madvbc) {
                    $query->select('macqcq')->from('dmdonvi')->where('madvbc', $madvbc)->distinct();
                })->get();
            //danh sách đơn vị gửi dữ liệu cho đơn vị quản lý khối và đơn vị quản lý khối.
            $model_donvi = dmdonvi::select('madv', 'tendv', DB::raw('"DONVI" as phanloai'))
                ->wherein('madv', function ($query) use ($madvqlkv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madvqlkv)
                        ->orwhere('madv', $madvqlkv)->get();
                })->get();
            //Gộp danh sách đơn vị
            foreach ($model_qlkhoi as $donvi) {
                $model_donvi->add($donvi);
            }

            foreach ($model_donvi as $dv) {
                $dulieu = tonghopluong_huyen::where('madv', $dv->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('phanloai', $dv->phanloai)
                    ->first();

                $tonghop = tonghop_huyen::where('madvbc', $madvbc)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->first();
                $dv->tralai = true;
                if (isset($tonghop)) {
                    $model_bangluong_ct = tonghopluong_donvi_chitiet::where('matht', $tonghop->madvth)->first();
                    $dv->tralai = isset($model_bangluong_ct->mathh) ? false : true;
                }

                if (isset($dulieu)) {
                    $dv->phanloai = $dulieu->phanloai;
                    $dv->mathdv = $dulieu->mathdv;
                } else {
                    $dv->mathdv = NULL;
                }
                $dv->tenphanloai = isset($a_phanloai[$dv->phanloai]) ? $a_phanloai[$dv->phanloai] : '';

            }

            $model_donvi = $model_donvi->sortby('madv');
            if (isset($inputs['trangthai'])) {
                if ($inputs['trangthai'] == 'CHONHAN') {
                    $model_donvi = $model_donvi->where('mathdv', '<>', null);
                }
                if ($inputs['trangthai'] == 'CHOGUI') {
                    $model_donvi = $model_donvi->where('mathdv', null);
                }
            }

            return view('functions.viewdata.index_huyen')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('furl', '/chuc_nang/tong_hop_luong/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }

    public function index_huyen(Request $request)
    {
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;

            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $a_phanloai = array('DONVI' => 'Dữ liệu tổng hợp của đơn vị', 'CAPDUOI' => 'Dữ liệu tổng hợp của các đơn vị cấp dưới');

            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();

            $model_nguon = tonghopluong_huyen::wherein('madv', function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
            })
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('trangthai', 'DAGUI')
                ->get();

            $model_nguon_khoi = tonghopluong_tinh::where('madv', $madv)->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])->first();

            foreach ($model_donvi as $dv) {
                //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
                if(count($model_nguon_khoi)>0 && $model_nguon_khoi->trangthai == 'DAGUI'){
                    $dv->tralai = false;
                }else{
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('madv',$dv->madv)->first();
                if(count($nguon)> 0 && $nguon->trangthai == 'DAGUI'){
                    $dv->mathdv = $nguon->mathdv;
                    $dv->trangthai = 'DAGUI';
                }else{
                    $dv->trangthai = 'CHOGUI';
                    $dv->mathdv = null;
                }

            }
            //dd($model_donvi->toarray());
            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai',$inputs['trangthai']);
            }

            return view('functions.viewdata.index_huyen')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('furl', '/chuc_nang/tong_hop_luong/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }

    public function index_tinh(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            //$madv = session('admin')->madv;
            $madvbc = $inputs['madiaban'];
            //$thang = $inputs['thang'];
            //$nam = $inputs['nam'];

            $madvqlkv = dmdonvibaocao::where('madvbc',$madvbc)->first()->madvcq;

            $a_trangthai=array('ALL'=>'--Chọn trạng thái dữ liệu--','CHOGUI'=>'Chưa gửi dữ liệu','CHONHAN'=>'Đã gửi dữ liệu');
            $a_phanloai=array('DONVI'=>'Dữ liệu tổng hợp của đơn vị','CAPDUOI'=>'Dữ liệu tổng hợp của các đơn vị cấp dưới');
            //$list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)->get();
            $model_dvbc = dmdonvibaocao::all();

            //lấy danh sách đơn vị quản lý khối
            $model_qlkhoi = dmdonvi::select('madv', 'tendv', DB::raw('"CAPDUOI" as phanloai'))
                ->wherein('madv', function($query) use($madvbc){
                    $query->select('macqcq')->from('dmdonvi')->where('madvbc',$madvbc)->distinct();
                })->get();
            //danh sách đơn vị gửi dữ liệu cho đơn vị quản lý khối và đơn vị quản lý khối.
            $model_donvi = dmdonvi::select('madv', 'tendv',DB::raw('"DONVI" as phanloai'))
                ->wherein('madv', function($query) use($madvqlkv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madvqlkv)
                        ->orwhere('madv',$madvqlkv)->get();
                })->get();
            //Gộp danh sách đơn vị
            foreach($model_qlkhoi as $donvi){
                $model_donvi->add($donvi);
            }

            $model_dulieu = tonghopluong_huyen::where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('madvbc',$madvbc)
                ->get();

            $model_tonghop = tonghop_huyen::where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('madvbc',$madvbc)
                ->get();

            //dd($model_dulieu);

            foreach($model_donvi as $dv){
                $dulieu = $model_dulieu->where('madv', $dv->madv)
                    ->where('phanloai', $dv->phanloai)
                    ->first();

                $tonghop = $model_tonghop->where('madv', $dv->madv)->first();
                $dv->tralai = true;
                if(isset($tonghop)){
                    $model_bangluong_ct = tonghopluong_donvi_chitiet::where('matht', $tonghop->madvth)->first();
                    $dv->tralai =isset($model_bangluong_ct->mathh)?false:true;
                }

                if(isset($dulieu)){
                    $dv->phanloai = $dulieu->phanloai;
                    $dv->mathdv = $dulieu->mathdv;
                }else{
                    $dv->mathdv = NULL;
                }
                $dv->tenphanloai = isset($a_phanloai[$dv->phanloai]) ? $a_phanloai[$dv->phanloai]: '';

            }

            $model_donvi = $model_donvi->sortby('madv');
            if(isset($inputs['trangthai'])){
                if($inputs['trangthai']=='CHONHAN'){
                    $model_donvi = $model_donvi->where('mathdv','<>',null);
                }
                if($inputs['trangthai']=='CHOGUI'){
                    $model_donvi = $model_donvi->where('mathdv',null);
                }
            }

            return view('functions.viewdata.index_tinh')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('madvbc',$madvbc)
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_dvbc',array_column( $model_dvbc->toArray(),'tendvbc','madvbc'))
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$model_dulieu->count('madv').'/'.$model_donvi->count('madv'))
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }

    function tonghop_huyen(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            //dd($inputs);
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madvbc = $inputs['madiaban'];

            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madvbc){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('madvbc',$madvbc)->distinct();
            })->get();
            //
            //Tính toán dữ liệu
            $a_col = getColTongHop();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');

            //Lấy dữ liệu để lập
            $model_data = $model_tonghop_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac','manguonkp'])
                    ->all();
            });

            $model_data = a_unique($model_data);
            for($i=0;$i<count($model_data);$i++){
                $luongct = $model_tonghop_ct->where('manguonkp',$model_data[$i]['manguonkp'])
                    ->where('macongtac',$model_data[$i]['macongtac']);
                $model_data[$i]['tennguonkp'] = isset($model_nguonkp[$model_data[$i]['manguonkp']])? $model_nguonkp[$model_data[$i]['manguonkp']]:'';
                $model_data[$i]['tencongtac'] = isset($model_phanloaict[ $model_data[$i]['macongtac']])? $model_phanloaict[ $model_data[$i]['macongtac']]:'';

                $tonghs = 0;
                foreach($a_col as $col){
                    $model_data[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_data[$i][$col]);
                }

                $model_data[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');
                $model_data[$i]['tongbh'] = $model_data[$i]['stbhxh_dv'] + $model_data[$i]['stbhyt_dv'] + $model_data[$i]['stkpcd_dv']+$model_data[$i]['stbhtn_dv'];
                $model_data[$i]['tonghs'] = $tonghs;
            }

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv',function($qr)use($madvbc){
                $qr->select('madvcq')->from('dmdonvibaocao')->where('madvbc',$madvbc)->get();
            })->first();

            $thongtin=array('nguoilap'=>$m_dv->nguoilapbieu,
                'thang'=>$thang,
                'nam'=>$nam);


            return view('reports.tonghopluong.khoi.solieutonghop')
                ->with('thongtin',$thongtin)
                ->with('model',$model_data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương trên địa bàn');

        } else
            return view('errors.notlogin');
    }
}
