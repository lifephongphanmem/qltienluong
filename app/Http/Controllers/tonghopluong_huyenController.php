<?php

namespace App\Http\Controllers;

use App\dmdiabandbkk;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphucap;
use App\dmphucap_donvi;
use App\tonghop_huyen;
use App\tonghop_huyen_chitiet;
use App\tonghop_huyen_diaban;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_huyen_diaban;
use App\tonghopluong_khoi;
use App\tonghopluong_tinh;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class tonghopluong_huyenController extends Controller
{
    function index_110818(Request $requests){
        if (Session::has('admin')) {
            /*
            $a_trangthai=array('CHOGUI'=>'Chưa gửi dữ liệu',
                'DAGUI'=>'Đã gửi dữ liệu',
                'TRALAI'=>'Trả lại dữ liệu',
                'CHUADAYDU'=>'Chưa đầy đủ tổng hợp dữ liệu');
            */
            $a_trangthai = getStatus();
            $inputs = $requests->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $tendb = getTenDb($madvbc);

            //lấy danh sách đơn vị quản lý khối
            $model_qlkhoi = dmdonvi::select('madv', 'tendv', DB::raw('"CAPDUOI" as phanloai'))
                ->wherein('madv', function($query) use($madvbc){
                    $query->select('macqcq')->from('dmdonvi')->where('madvbc',$madvbc)->distinct();
                })->get();

            //danh sách đơn vị gửi dữ liệu cho đơn vị quản lý khối và đơn vị quản lý khối.
            //chức năng chỉ dành cho đơn vị quản lý khu vực => $madvqlkv = $madv
            $model_donvi = dmdonvi::select('madv', 'tendv',DB::raw('"DONVI" as phanloai'))
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)
                        ->orwhere('madv',$madv)->get();
                })->get();
            //Gộp danh sách đơn vị
            foreach($model_qlkhoi as $donvi){
                $model_donvi->add($donvi);
            }
            //dd($model_donvi);
            $sldv = $model_donvi->count();

            //Danh sách đơn vi = macqcq&(SD;TH&KHOI); bỏ đi TH&HUYEN


            $a_data=array(array('thang'=>'01','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'02','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'03','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'04','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'05','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'06','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'07','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'08','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'09','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'10','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'11','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'12','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0)
            );

            //Lấy danh sách các dữ liệu đã tổng hợp theo huyện
            $model_tonghop = tonghop_huyen::where('madvbc',$madvbc)->get();
            //Danh sách các đơn vị đã gửi dữ liệu
            $model_dulieu = tonghopluong_huyen::where('madvbc',$madvbc)->get();

            for($i=0;$i<count($a_data);$i++){
                //$a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $tonghop = $model_tonghop->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam'])->first();
                $dulieu = $model_dulieu->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam']);
                //Kiểm tra xem đơn vị đã tổng hợp dữ liệu khối chưa
                if(count($tonghop)>0){//lấy dữ liệu đã tổng hợp đưa ra kết quản
                    $a_data[$i]['noidung']=$tonghop->noidung;
                    $a_data[$i]['mathdv']=$tonghop->mathdv;
                    $a_data[$i]['trangthai']=$tonghop->trangthai;
                    $a_data[$i]['dvgui'] = count($dulieu);
                }else{//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp trên địa bàn '.$tendb.' tháng '.$a_data[$i]['thang'].' năm '.$inputs['nam'];
                    $a_data[$i]['mathdv'] = null;

                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    if(count($dulieu) == 0){//chưa gửi
                        $a_data[$i]['trangthai']='CHUADL';

                    }elseif(count($dulieu)== $sldv){
                        //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUAGUI';
                        $a_data[$i]['dvgui'] = $sldv;
                    }else{
                        $a_data[$i]['trangthai'] = 'CHUADAYDU';
                        $a_data[$i]['dvgui'] = count($dulieu);
                    }
                }
            }
            //dd($a_data);
            return view('functions.tonghopluong.huyen.index')
                ->with('furl','/chuc_nang/tong_hop_luong/huyen/')
                ->with('nam',$inputs['nam'])
                ->with('model',$a_data)
                ->with('a_trangthai',$a_trangthai)
                ->with('pageTitle','Danh sách tổng hợp lương toàn địa bàn');
        } else
            return view('errors.notlogin');
    }

    function index(Request $requests)
    {
        if (Session::has('admin')) {
            $a_trangthai = getStatus();
            $inputs = $requests->all();
            $madv = session('admin')->madv;

            $madvbc = session('admin')->madvbc;
            $tendb = getTenDb($madvbc);

            //Danh sách đơn vi = macqcq&(SD;TH&KHOI); bỏ đi TH&HUYEN
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->get();
            //dd($model_donvi->toarray());
            $sldv = $model_donvi->count();

            $a_data = array(array('thang' => '01', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '02', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '03', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '04', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '05', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '06', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '07', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '08', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '09', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '10', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '11', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0),
                array('thang' => '12', 'mathdv' => null, 'noidung' => null, 'sldv' => $sldv, 'dvgui' => 0)
            );
            if(session('admin')->phamvitonghop == 'HUYEN')
                $model_nguon = tonghopluong_huyen::wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->where('trangthai', 'DAGUI')
                    ->get();
            if(session('admin')->phamvitonghop == 'KHOI')
            {
                $model_nguon = tonghopluong_donvi::wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->where('trangthai', 'DAGUI')
                    ->get();
                $model_nguonkhoi = tonghopluong_khoi::wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                }) ->where('trangthai', 'DAGUI')
                    ->get();
            }
            //Lấy danh sách các dữ liệu đã tổng hợp theo huyện
            $model_tonghop = tonghopluong_tinh::where('madvbc', $madvbc)->get();
            //Danh sách các đơn vị đã gửi dữ liệu
            //$model_dulieu = tonghopluong_huyen::where('madvbc',$madvbc)->get();
            //$model_dulieu = tonghopluong_huyen::where('macqcq', $madv)->where('trangthai','DAGUI')->get();
            $model_dulieu = tonghopluong_donvi::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            })->where('trangthai','DAGUI')->get();

            //dd($model_dulieu);
            for ($i = 0; $i < count($a_data); $i++) {
                //$a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $tonghop = $model_tonghop->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam'])->first();
                if(session('admin')->phamvitonghop == 'HUYEN')
                    $dulieu = $model_nguon->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam']);
                if(session('admin')->phamvitonghop == 'KHOI'){
                    $dulieu = $model_nguon->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam']);
                    $dulieukhoi = $model_nguonkhoi->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam']);
                }
                //dd($dulieu);
                //Kiểm tra xem đơn vị đã tổng hợp dữ liệu khối chưa
                if (count($tonghop) > 0) {//lấy dữ liệu đã tổng hợp đưa ra kết quản
                    $a_data[$i]['noidung'] = $tonghop->noidung;
                    $a_data[$i]['mathdv'] = $tonghop->mathdv;
                    $a_data[$i]['trangthai'] = $tonghop->trangthai;
                    if(session('admin')->phamvitonghop == 'HUYEN')
                        $a_data[$i]['dvgui'] = count($dulieu);
                    if(session('admin')->phamvitonghop == 'KHOI'){
                        $a_data[$i]['dvgui'] = count($dulieu) + count($dulieukhoi);
                    }
                } else {//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp trên địa bàn ' . $tendb . ' tháng ' . $a_data[$i]['thang'] . ' năm ' . $inputs['nam'];
                    $a_data[$i]['mathdv'] = null;

                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    if (count($dulieu) == 0) {//chưa gửi
                        $a_data[$i]['trangthai'] = 'CHUADL';

                    } elseif (count($dulieu) == $sldv) {
                        //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUAGUI';
                        $a_data[$i]['dvgui'] = $sldv;
                    } else {
                        $a_data[$i]['trangthai'] = 'CHUADAYDU';
                        $a_data[$i]['dvgui'] = count($dulieu);
                    }
                }
            }
            //dd($a_data);
            return view('functions.tonghopluong.huyen.index')
                ->with('furl', '/chuc_nang/tong_hop_luong/huyen/')
                ->with('nam', $inputs['nam'])
                ->with('model', $a_data)
                ->with('a_trangthai', $a_trangthai)
                ->with('pageTitle', 'Danh sách tổng hợp lương toàn địa bàn');
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv){
        $model = tonghopluong_donvi::where('mathdv',$mathdv)->orwhere('mathh',$mathdv)->first();
        $in = new tonghopluong_donviController();
        return $in->printf_data_khoi($model->mathh);
    }

    function printf_data_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_diaban::where('mathh',$mathdv)->get();
            $model_diaban = dmdiabandbkk::all();
            $model_thongtin = tonghopluong_huyen::where('mathdv',$mathdv)->first();
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
            //$gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl=$chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv=dmdonvi::where('madv',$model_thongtin->madv)->first();

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$model_thongtin->thang,
                'nam'=>$model_thongtin->nam);

            return view('reports.tonghopluong.donvi.solieudiaban')
                ->with('thongtin',$thongtin)
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function tonghop_cu(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $mathdv = getdate()[0];
            $madv = session('admin')->madv;

            //lấy danh sách các bảng tổng họp theo đơn vị
            $model_tonghop = tonghopluong_huyen::where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->get();

            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_huyen_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_huyen')->where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->distinct();
            })->get();
            //dd($model_tonghop_ct->toarray());

            $model_diaban_ct = tonghopluong_huyen_diaban::wherein('madiaban',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_huyen')->where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->distinct();
            })->get();


            //
            //Tính toán dữ liệu
            $a_col = getColTongHop();

            //Lấy dữ liệu để lập
            $model_data = $model_tonghop_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac','linhvuchoatdong','manguonkp','luongcoban'])
                    ->all();
            });
            $model_data = a_unique($model_data);
            for($i=0;$i<count($model_data);$i++){
                $luongct = $model_tonghop_ct->where('manguonkp',$model_data[$i]['manguonkp'])
                        ->where('linhvuchoatdong',$model_data[$i]['linhvuchoatdong'])
                        ->where('macongtac',$model_data[$i]['macongtac'])
                        ->where('luongcoban',$model_data[$i]['luongcoban']);

                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;
                //lưu hệ số truy thu nhưng ko tính toán trong báo cáo tổng hợp
                $model_data[$i]['hesott']=$luongct->sum('hesott');
                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                $model_data[$i]['hesopc']=$luongct->sum('hesopc');
                foreach($a_col as $col){
                    $model_data[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_data[$i][$col]);
                }

                $model_data[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_data[$i]['tonghs']=$tonghs;
            }

            //Tính toán theo địa bàn
            $model_diaban = $model_diaban_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban','luongcoban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);
            for($i=0;$i<count($model_diaban);$i++){
                $luongct = $model_diaban_ct->where('madiaban',$model_diaban[$i]['madiaban'])
                    ->where('luongcoban',$model_diaban[$i]['luongcoban']);;

                $tonghs = 0;
                $model_diaban[$i]['mathdv'] = $mathdv;
                //lưu hệ số truy thu nhưng ko tính toán trong báo cáo tổng hợp
                $model_diaban[$i]['hesott']=$luongct->sum('hesott');
                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                $model_diaban[$i]['hesopc']=$luongct->sum('hesopc');
                foreach($a_col as $col){
                    $model_diaban[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_diaban[$i][$col]);
                }

                $model_diaban[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs']=$tonghs;
            }

            //Thêm báo cáo tổng hợp
            $inputs['madv'] = $madv;
            $inputs['mathdv'] = $mathdv;
            $inputs['trangthai'] = 'CHOGUI';
            $inputs['phanloai'] = 'CAPHUYEN';
            $inputs['noidung'] = 'Dữ liệu tổng hợp của '.getTenDB(session('admin')->madvbc).' thời điểm '.$inputs['thang'].'/'.$inputs['nam'];
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;

            $model_db = array();
            for($i=0;$i<count($model_diaban);$i++){
                if($model_diaban[$i]['madiaban'] != null){
                    $model_db[] = $model_diaban[$i];
                }
            }

            tonghop_huyen_chitiet::insert($model_data);
            tonghop_huyen_diaban::insert($model_db);
            tonghop_huyen::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/huyen/detail/ma_so='.$mathdv);
        } else
            return view('errors.notlogin');
    }
    function printf_bl_huyen($mathdv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $model = tonghopluong_donvi_bangluong::wherein('mathdv',function($query) use($mathdv){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('mathh',$mathdv)->get();
            })->get();
            $model_thongtin = tonghopluong_huyen::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                $thanhtien = 0;
                foreach (getColTongHop() as $ct) {
                    if ($chitiet->$ct > 50000) {
                        $thanhtien +=  $chitiet->$ct;
                    }
                }
                if($chitiet->ttl == 0){//trường hop dinh mức ko nhân dc với hệ số
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }else{
                    $chitiet->tongtl = $chitiet->ttl;
                }
            }
            //dd($model);

            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($a_phucap);
            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            //Lấy dữ liệu để lập
            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact','manguonkp','tennguonkp','tenct'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $a_congtac = a_unique($model_congtac);

            $model_nguon = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['manguonkp','tennguonkp'])
                    ->all();
            });
            $a_nguon = a_unique($model_nguon);

            return view('reports.tonghopluong.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_nguon', $a_nguon)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }
    function tonghop(Request $requests){
        /*
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            /*
             lấy macqcq vì sợ dv th khối chưa gửi dữ liệu
            $madvbc = session('admin')->madvbc;

            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madvbc){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('madvbc',$madvbc)->distinct();
            })->get();
             * * (bỏ)

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use ($inputs){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam', $inputs['nam'])
                    ->where('thang',$inputs['thang'])
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq',session('admin')->madv)->get();
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
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$thang,
                'nam'=>$nam);

            return view('reports.tonghopluong.khoi.solieutonghop')
                ->with('thongtin',$thongtin)
                ->with('model',$model_data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
        */
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->where('phanloaitaikhoan','SD')->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });

            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            return view('reports.tonghopluong.huyen.solieu')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function chitiet_khoi(Request $requests){
        /*
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            /*
             lấy macqcq vì sợ dv th khối chưa gửi dữ liệu
            $madvbc = session('admin')->madvbc;

            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madvbc){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('madvbc',$madvbc)->distinct();
            })->get();
             * * (bỏ)

            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use ($inputs){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam', $inputs['nam'])
                    ->where('thang',$inputs['thang'])
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq',session('admin')->madv)->get();
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
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$thang,
                'nam'=>$nam);

            return view('reports.tonghopluong.khoi.solieutonghop')
                ->with('thongtin',$thongtin)
                ->with('model',$model_data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
        */
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = $inputs['madv'];
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            //dd($model_donvi);
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });

            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            return view('reports.tonghopluong.huyen.solieu')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function tonghop_huyen(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->where('phanloaitaikhoan','SD')->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('dmdonvi.madv','maphanloai','mact','manguonkp','luongcoban','soluong','heso','hesobl','hesopc','hesott','vuotkhung','tonghopluong_donvi_chitiet.pcct'
                    , 'tonghopluong_donvi_chitiet.pckct', 'tonghopluong_donvi_chitiet.pck', 'tonghopluong_donvi_chitiet.pccv', 'tonghopluong_donvi_chitiet.pckv',
                    'tonghopluong_donvi_chitiet.pcth', 'tonghopluong_donvi_chitiet.pcdd', 'tonghopluong_donvi_chitiet.pcdh', 'tonghopluong_donvi_chitiet.pcld',
                    'tonghopluong_donvi_chitiet.pcdbqh', 'tonghopluong_donvi_chitiet.pcudn', 'tonghopluong_donvi_chitiet.pctn',
                    'tonghopluong_donvi_chitiet.pctnn', 'tonghopluong_donvi_chitiet.pcdbn', 'tonghopluong_donvi_chitiet.pcvk', 'tonghopluong_donvi_chitiet.pckn',
                    'tonghopluong_donvi_chitiet.pcdang', 'tonghopluong_donvi_chitiet.pccovu', 'tonghopluong_donvi_chitiet.pclt', 'tonghopluong_donvi_chitiet.pcd',
                    'tonghopluong_donvi_chitiet.pctr', 'tonghopluong_donvi_chitiet.pctdt', 'tonghopluong_donvi_chitiet.pctnvk',
                    'tonghopluong_donvi_chitiet.pcbdhdcu', 'tonghopluong_donvi_chitiet.pcthni', 'tonghopluong_donvi_chitiet.tonghs', 'tonghopluong_donvi_chitiet.giaml',
                    'tonghopluong_donvi_chitiet.luongtn', 'tonghopluong_donvi_chitiet.stbhxh_dv', 'tonghopluong_donvi_chitiet.stbhyt_dv', 'tonghopluong_donvi_chitiet.stkpcd_dv',
                    'tonghopluong_donvi_chitiet.stbhtn_dv', 'tonghopluong_donvi_chitiet.ttbh_dv')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mact','maphanloai','dmdonvi.madv','luongcoban','manguonkp')
                ->get();
            $m_pl = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->join('dmphanloaict','dmphanloaict.mact','tonghopluong_donvi_chitiet.mact')
                ->select('maphanloai','tenct','dmphanloaict.mact','manguonkp')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->orderby('maphanloai')
                ->distinct()
                ->get();
            //$model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($m_pl as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
            }
            foreach ($model as $chitiet) {
                //$chitiet->madv = $a_dv[$chitiet->mathdv];
                //$chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });

            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            return view('reports.tonghopluong.khoi.solieuth')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('m_dv', $m_dv)
                ->with('m_pl', $m_pl)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function tonghop_diaban(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madvbc = session('admin')->madvbc;

            $model_diaban_ct = tonghopluong_donvi_diaban::wherein('mathdv',function($query) use($nam, $thang, $madvbc){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('madvbc',$madvbc)->distinct();
            })->get();

            //Tính toán dữ liệu
            $a_col = getColTongHop();

            //Tính toán theo địa bàn
            $model_diaban = $model_diaban_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban','luongcoban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);
            for($i=0;$i<count($model_diaban);$i++){
                $luongct = $model_diaban_ct->where('madiaban',$model_diaban[$i]['madiaban'])
                    ->where('luongcoban',$model_diaban[$i]['luongcoban']);;

                $tonghs = 0;

                foreach($a_col as $col){
                    $model_diaban[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_diaban[$i][$col]);
                }

                $model_diaban[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs']=$tonghs;
                $model_diaban[$i]['tongbh'] = $model_diaban[$i]['stbhxh_dv'] + $model_diaban[$i]['stbhyt_dv'] + $model_diaban[$i]['stkpcd_dv'] + $model_diaban[$i]['stbhtn_dv'];
            }


            $model_db = array();
            $dm_diaban = dmdiabandbkk::all();
            $a_diaban = getDiaBan(false);
            for($i=0;$i<count($model_diaban);$i++){
                $diaban = $dm_diaban->where('madiaban',$model_diaban[$i]['madiaban'])->first();
                $model_diaban[$i]['tendiaban'] = $diaban->tendiaban;
                $model_diaban[$i]['phanloai'] = $a_diaban[$diaban->phanloai];

                if($model_diaban[$i]['madiaban'] != null){
                    $model_db[] = $model_diaban[$i];
                }
            }

            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$thang,
                'nam'=>$nam);
            return view('reports.tonghopluong.khoi.solieudiaban')
                ->with('thongtin',$thongtin)
                ->with('model',$model_db)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function tonghop_chuadaydu(Request $requests){
        if (Session::has('admin')) {
            return $this->tonghop($requests);
        } else
            return view('errors.notlogin');
    }

    function detail($mathdv){
        if (Session::has('admin')) {
            $model = tonghop_huyen_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghop_huyen::where('mathdv',$mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
            //$gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl=$chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
//dd($model);
            return view('functions.tonghopluong.huyen.detail')
                ->with('furl','/chuc_nang/tong_hop_luong/huyen/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết dữ liệu tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function edit_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = tonghop_huyen_chitiet::where('mathdv',$inputs['mathdv'])
                ->where('manguonkp',$inputs['manguonkp'])
                ->where('macongtac',$inputs['macongtac'])->first();

            $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_detail')
                ->with('furl','/chuc_nang/tong_hop_luong/huyen/')
                ->with('model',$model)
                //->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghop_huyen_chitiet::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);

            foreach(array_keys($inputs) as $key){
                if(!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/huyen/detail/ma_so='.$model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghop_huyen_diaban::where('mathdv',$mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv',session('admin')->madv)->get();
            $model_thongtin = tonghop_huyen::where('mathdv',$mathdv)->first();
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');


            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl= $chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.templates.detail_diaban')
                ->with('furl','/chuc_nang/tong_hop_luong/huyen/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương theo địa bàn');
        } else
            return view('errors.notlogin');
    }

    function edit_detail_diaban(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_diaban =array_column( dmdiabandbkk::where('madv',session('admin')->madv)->get()->toarray(),'tendiaban','madiaban');
            $model = tonghop_huyen_diaban::where('mathdv',$inputs['mathdv'])
                ->where('madiaban',$inputs['madiaban'])->first();

            $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_diaban')
                ->with('furl','/chuc_nang/tong_hop_luong/huyen/')
                ->with('model',$model)
                ->with('a_diaban',$a_diaban)
                ->with('pageTitle','Chi tiết tổng hợp lương theo địa bàn');
        } else
            return view('errors.notlogin');
    }

    function store_detail_diaban(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghop_huyen_diaban::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);

            foreach(array_keys($inputs) as $key){
                if(!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/huyen/detail_diaban/ma_so='.$model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if(session('admin')->quanlykhuvuc && session('admin')->macqcq == ''){
                return view('errors.chuacqcq');
            }
            $madvbc = session('admin')->madvbc;
            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = getdate()[0];;
            $inputs['trangthai'] = 'DAGUI';
            $inputs['phanloai'] = 'TONGHOP';
            $inputs['noidung']='Đơn vị '.getTenDV(session('admin')->madv) .' tổng hợp dữ liệu trên địa bàn '.getTenDB($madvbc).' thời điểm '.$inputs['thang'].'/'.$inputs['nam'];
            $inputs['nguoilap']= session('admin')->name;
            $inputs['ngaylap']= Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;

            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;

            tonghopluong_donvi::where('nam',$nam)->where('thang',$thang)->where('madvbc',$madvbc)
                ->update(['mathh'=>$inputs['mathdv'],'matht'=>$inputs['mathdv']]);

            tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madvbc){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam',$nam)->where('thang',$thang)->where('madvbc',$madvbc)->distinct();
            })->update(['mathh'=>$inputs['mathdv'],'matht'=>$inputs['mathdv']]);

            //$model_tonghop_ct->update(['mathk'=>$inputs['mathdv']]);
            tonghopluong_donvi_diaban::wherein('mathdv',function($query) use($nam, $thang, $madvbc){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam',$nam)->where('thang',$thang)->where('madvbc',$madvbc)->distinct();
            })->update(['mathh'=>$inputs['mathdv'],'matht'=>$inputs['mathdv']]);



            tonghopluong_tinh::create($inputs);
            tonghop_huyen::create($inputs);

            return redirect('/chuc_nang/tong_hop_luong/huyen/index?nam='.$nam);
        } else
            return view('errors.notlogin');
    }

    function tralai(Request $request)
    {
        //xem dữ liệu khối or đơn vị
        //đơn vị =>trả, xóa bang tonghopluong_huyen
        //khối =>trả, xóa bang tonghopluong_huyen, update trường mathh = null;

        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = tonghopluong_huyen::where('mathdv', $inputs['mathdv'])->first();
            //dd($model);
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            $madv = $model->madv;
            $phanloai = dmdonvi::where('madv', $madv)->first()->phanloaitaikhoan;

            if ($phanloai == 'SD') {
                tonghopluong_donvi::where('mathh', $inputs['mathdv'])
                    ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo'], 'mathh' => null, 'matht' => null, 'mathk' => null]);
                //thừa mã khối
            } else {
                //do lúc chuyển tạo mã khối và ma huyện giống nhau
                //hoặc lấy theo tháng, năm, mã khối, phân loại
                //nên tạo trường lý do ko nên lấy ở bảng đơn vị
                tonghopluong_donvi::where('mathk', $inputs['mathdv'])
                    ->update(['mathh' => null, 'matht' => null, 'mathk' => null ]);
                tonghopluong_khoi::where('mathdv', $inputs['mathdv'])
                    ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
            }

            return redirect('/chuc_nang/xem_du_lieu/huyen?thang=' . $model->thang . '&nam=' . $model->nam . '&trangthai=ALL');
        } else
            return view('errors.notlogin');
    }

    function getlydo(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        $model = tonghopluong_donvi::select('lydo')->where('mathdv',$inputs['mathdv'])->first();

        die($model);
    }

}
