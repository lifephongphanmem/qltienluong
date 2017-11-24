<?php

namespace App\Http\Controllers;

use App\dmdiabandbkk;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_huyen_diaban;
use App\tonghopluong_khoi;
use App\tonghopluong_khoi_chitiet;
use App\tonghopluong_khoi_diaban;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_khoiController extends Controller
{
    function index(Request $requests){
        if (Session::has('admin')) {
            //chỉ có 4 trạng thái CHUAGUI: chưa có dữ liệu từ đơn vi cấp dưới
            //CHUADAYDU: có đơn vị cấp dưới chưa gửi dữ liệu
            //DAGUI: đã gửi lên đơn vị cấp trên
            //TRALAI: đã bị gửi trả lại
            $inputs = $requests->all();
            $madv = session('admin')->madv;
            $tendv = getTenDV($madv);
            $sldvcapduoi = dmdonvi::where('macqcq',$madv)->count();

            $a_data=array(array('thang'=>'01','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'02','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'03','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'04','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'05','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'06','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'07','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'08','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'09','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'10','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'11','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0),
                array('thang'=>'12','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADL','sldv'=>$sldvcapduoi,'dvgui'=>0)
            );
            /*
            $a_trangthai=array('CHUAGUI'=>'Chưa gửi tổng hợp dữ liệu','CHUADAYDU'=>'Chưa đầy đủ tổng hợp dữ liệu','CHUATAO'=>'Chưa tổng hợp dữ liệu'
                ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            */
            $a_trangthai=getStatus();

            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = tonghopluong_donvi::wherein('madv',function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->distinct();
            })->get();
            //Lấy danh sách các dữ liệu đã tổng hợp theo khối
            $model_khoi = tonghopluong_khoi::where('madv',$madv)->get();
            for($i=0;$i<count($a_data);$i++){
                $a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $tonghop = $model_khoi->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam'])->first();
                $dulieu = $model_donvi->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam']);

                //Kiểm tra xem đơn vị đã tổng hợp dữ liệu khối chưa
                if(count($tonghop)>0){//lấy dữ liệu đã tổng hợp đưa ra kết quản
                    $a_data[$i]['noidung']=$tonghop->noidung;
                    $a_data[$i]['mathdv']=$tonghop->mathdv;
                    $a_data[$i]['trangthai']=$tonghop->trangthai;
                    $a_data[$i]['dvgui'] = $sldvcapduoi;
                }else{//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung']='Đơn vị '. $tendv .' tổng hợp dữ liệu từ các đơn vị cấp dưới thời điểm '.$a_data[$i]['thang'].'/'.$inputs['nam'];
                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    if(count($dulieu) == 0){//chưa gửi
                        $a_data[$i]['trangthai']='CHUADL';
                    }elseif(count($dulieu)== $sldvcapduoi){ //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUAGUI';
                        $a_data[$i]['dvgui'] = $sldvcapduoi;
                    }else{
                        $a_data[$i]['dvgui'] = count($dulieu);
                        $a_data[$i]['trangthai'] = 'CHUADAYDU';
                    }
                }
            }
            //dd($a_data);
            return view('functions.tonghopluong.khoi.index')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('nam',$inputs['nam'])
                ->with('model',$a_data)
                ->with('a_trangthai',$a_trangthai)
                ->with('pageTitle','Danh sách tổng hợp lương từ đơn vị cấp dưới');
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];

            $madv = session('admin')->madv;
            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('macqcq',$madv)->distinct();
            })->get();

            //Lấy dữ liệu để lập
            $model_data = $model_tonghop_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac','linhvuchoatdong','manguonkp','luongcoban'])
                    ->all();
            });
            $model_data = a_unique($model_data);
            //
            //Tính toán dữ liệu
            $a_col = getColTongHop();

            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');

            for($i=0;$i<count($model_data);$i++){
                $luongct = $model_tonghop_ct->where('manguonkp',$model_data[$i]['manguonkp'])
                    ->where('linhvuchoatdong',$model_data[$i]['linhvuchoatdong'])
                    ->where('macongtac',$model_data[$i]['macongtac']);
                $tonghs = 0;
                $model_data[$i]['tennguonkp'] = isset($model_nguonkp[$model_data[$i]['manguonkp']])? $model_nguonkp[$model_data[$i]['manguonkp']]:'';
                $model_data[$i]['tencongtac'] = isset($model_phanloaict[ $model_data[$i]['macongtac']])? $model_phanloaict[ $model_data[$i]['macongtac']]:'';
                foreach($a_col as $col){
                    $model_data[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_data[$i][$col]);
                }

                $model_data[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_data[$i]['tongbh']=$model_data[$i]['stbhxh_dv'] + $model_data[$i]['stbhyt_dv'] + $model_data[$i]['stkpcd_dv']+$model_data[$i]['stbhtn_dv'];
                $model_data[$i]['tonghs']=$tonghs;
            }
            //

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv',$madv)->first();

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
    }

    function tonghop_diaban(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;

            $model_diaban_ct = tonghopluong_donvi_diaban::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('macqcq',$madv)->distinct();
            })->get();
            //
            //Tính toán dữ liệu
            $a_col = getColTongHop();

            //Tính toán theo địa bàn
            $model_diaban = $model_diaban_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);

            for($i=0;$i<count($model_diaban);$i++){
                $luongct = $model_diaban_ct->where('madiaban',$model_diaban[$i]['madiaban']);
                $tonghs = 0;

                foreach($a_col as $col){
                    $model_diaban[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_diaban[$i][$col]);
                }

                $model_diaban[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs'] = $tonghs;
                $model_diaban[$i]['tongbh'] = $model_diaban[$i]['stbhxh_dv'] + $model_diaban[$i]['stbhyt_dv'] + $model_diaban[$i]['stkpcd_dv'] + $model_diaban[$i]['stbhtn_dv'];
            }
            //

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

            $m_dv = dmdonvi::where('madv',$madv)->first();

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
            $model = tonghopluong_khoi_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();
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
            return view('functions.tonghopluong.templates.detail')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết dữ liệu tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function edit_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = tonghopluong_khoi_chitiet::where('mathdv',$inputs['mathdv'])
                ->where('manguonkp',$inputs['manguonkp'])
                ->where('macongtac',$inputs['macongtac'])->first();

            $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_detail')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                //->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_khoi_chitiet::findorfail($inputs['id']);
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

            return redirect('/chuc_nang/tong_hop_luong/khoi/detail/ma_so='.$model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_khoi_diaban::where('mathdv',$mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv',session('admin')->madv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();

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
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
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
            $model = tonghopluong_khoi_diaban::where('mathdv',$inputs['mathdv'])
                ->where('madiaban',$inputs['madiaban'])->first();

            $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_diaban')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                ->with('a_diaban',$a_diaban)
                ->with('pageTitle','Chi tiết tổng hợp lương theo địa bàn');
        } else
            return view('errors.notlogin');
    }

    function store_detail_diaban(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_khoi_diaban::findorfail($inputs['id']);
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

            return redirect('/chuc_nang/tong_hop_luong/khoi/detail_diaban/ma_so='.$model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = getdate()[0];;
            $inputs['trangthai'] = 'DAGUI';
            $inputs['phanloai'] = 'CAPDUOI';
            $inputs['noidung']='Đơn vị '.getTenDV(session('admin')->madv) .' tổng hợp dữ liệu từ các đơn vị cấp dưới thời điểm '.$inputs['thang'].'/'.$inputs['nam'];
            $inputs['nguoilap']= session('admin')->name;
            $inputs['ngaylap']= Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;

            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;

            tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->distinct();
            })->update(['mathk'=>$inputs['mathdv'],'mathh'=>$inputs['mathdv']]);

            //$model_tonghop_ct->update(['mathk'=>$inputs['mathdv']]);
            tonghopluong_donvi_diaban::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->distinct();
            })->update(['mathk'=>$inputs['mathdv'],'mathh'=>$inputs['mathdv']]);

            tonghopluong_khoi::create($inputs);
            tonghopluong_huyen::create($inputs);

            return redirect('/chuc_nang/tong_hop_luong/khoi/index?nam='.$nam);
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv){
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = tonghopluong_khoi_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();
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
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function printf_data_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_khoi_diaban::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();
            $model_diaban = dmdiabandbkk::all();//nên lọc đia bàn
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
            //$gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl = $chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
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
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }
}
