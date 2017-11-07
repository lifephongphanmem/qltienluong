<?php

namespace App\Http\Controllers;

use App\dmdiabandbkk;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\tonghop_huyen;
use App\tonghop_huyen_chitiet;
use App\tonghop_huyen_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_huyen_diaban;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class tonghopluong_huyenController extends Controller
{
    function index(Request $requests){
        if (Session::has('admin')) {
            $a_data=array(array('thang'=>'01','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'02','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'03','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'04','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'05','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'06','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'07','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'08','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'09','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'10','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'11','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI'),
                array('thang'=>'12','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHOGUI')
            );

            $a_trangthai=array('CHOGUI'=>'Chưa gửi dữ liệu',
                'DAGUI'=>'Đã gửi dữ liệu',
                'TRALAI'=>'Trả lại dữ liệu',
                'CHUADAYDU'=>'Chưa đầy đủ tổng hợp dữ liệu');

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
            $sldv = $model_donvi->count();

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
                }else{//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp trên địa bàn '.$tendb.' tháng '.$a_data[$i]['thang'].' năm '.$inputs['nam'];
                    $a_data[$i]['mathdv'] = null;

                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    if(count($dulieu) == 0){//chưa gửi
                        $a_data[$i]['trangthai']='CHOGUI';
                    }elseif(count($dulieu)== $sldv){
                        //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUATAO';
                    }else{
                        $a_data[$i]['trangthai'] = 'CHUADAYDU';
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

    function printf_data($mathdv){
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = tonghopluong_huyen_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_huyen::where('mathdv',$mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv',$model_thongtin->madv)->first();

            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$model_thongtin->thang,
                'nam'=>$model_thongtin->nam);

            return view('reports.tonghopluong.donvi.solieutonghop')
                ->with('thongtin',$thongtin)
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function printf_data_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_huyen_diaban::where('mathdv',$mathdv)->get();
            $model_diaban = dmdiabandbkk::all();
            $model_thongtin = tonghopluong_huyen::where('mathdv',$mathdv)->first();
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
            //$gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl=$chitiet->luongcoban * $chitiet->tonghs;
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

    function tonghop(Request $requests){
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


}