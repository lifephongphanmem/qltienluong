<?php

namespace App\Http\Controllers;

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
            $a_data=array(array('thang'=>'01','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'02','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'03','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'04','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'05','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'06','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'07','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'08','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'09','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'10','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'11','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU'),
                array('thang'=>'12','mathdv'=>null,'noidung'=>null,'trangthai'=>'CHUADULIEU')
            );
            $a_trangthai=array('CHUAGUI'=>'Chưa gửi tổng hợp dữ liệu','CHUADAYDU'=>'Chưa đầy đủ tổng hợp dữ liệu','CHUATAO'=>'Chưa tổng hợp dữ liệu'
                ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            $inputs = $requests->all();
            $madv = session('admin')->madv;
            $tendv = getTenDV($madv);

            $sldvcapduoi = dmdonvi::where('macqcq',$madv)->count();
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
                }else{//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung']='Đơn vị '. $tendv .' tổng hợp dữ liệu từ các đơn vị cấp dưới thời điểm '.$a_data[$i]['thang'].'/'.$inputs['nam'];
                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    if(count($dulieu) == 0){//chưa gửi
                        $a_data[$i]['trangthai']='CHUAGUI';
                    }elseif(count($dulieu)== $sldvcapduoi){
                    //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUATAO';
                    }else{
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
            $mathdv = getdate()[0];
            $madv = session('admin')->madv;

            //lấy danh sách các bảng tổng họp theo đơn vị
            $model_tonghop = tonghopluong_donvi::where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->where('trangthai','DAGUI')->get();
            $inputs['luongcoban'] = isset($model_tonghop) ? $model_tonghop->first()['luongcoban'] : getGeneralConfigs()['luongcb'];

            //lấy danh sách các chi tiết số liệu tổng họp theo đơn vị
            $model_tonghop_ct = tonghopluong_donvi_chitiet::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->distinct();
            })->get();

            $model_diaban_ct = tonghopluong_donvi_diaban::wherein('madiaban',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam',$nam)->where('thang',$thang)->where('macqcq',$madv)->distinct();
            })->get();

            //Lấy dữ liệu để lập
            $model_data = $model_tonghop_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac','linhvuchoatdong','manguonkp'])
                    ->all();
            });
            $model_data = a_unique($model_data);
            //
            //Tính toán dữ liệu
            $a_col = array('heso','vuotkhung','pcct',
                'pckct',
                'pck',
                'pccv',
                'pckv',
                'pcth',
                'pcdd',
                'pcdh',
                'pcld',
                'pcdbqh',
                'pcudn',
                'pctn',
                'pctnn',
                'pcdbn',
                'pcvk',
                'pckn',
                'pcdang',
                'pccovu',
                'pclt',
                'pcd',
                'pctr',
                'pctnvk',
                'pcbdhdcu',
                'pcthni');

            for($i=0;$i<count($model_data);$i++){
                $luongct = $model_tonghop_ct->where('manguonkp',$model_data[$i]['manguonkp'])
                    ->where('linhvuchoatdong',$model_data[$i]['linhvuchoatdong'])
                    ->where('macongtac',$model_data[$i]['macongtac']);

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
            //

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
            //

            //Thêm báo cáo tổng hợp
            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = $mathdv;
            $inputs['trangthai'] = 'CHOGUI';
            $inputs['phanloai'] = 'CAPDUOI';
            $inputs['noidung']='Đơn vị '. getTenDV(session('admin')->madv) .' tổng hợp dữ liệu từ các đơn vị cấp dưới thời điểm '.$inputs['thang'].'/'.$inputs['nam'];
            $inputs['nguoilap']=session('admin')->name;
            $inputs['ngaylap']=Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;

            $model_db = array();
            for($i=0;$i<count($model_diaban);$i++){
                if($model_diaban[$i]['madiaban'] != null){
                    $model_db[] = $model_diaban[$i];
                }
            }
           
            tonghopluong_khoi_chitiet::insert($model_data);
            tonghopluong_khoi_diaban::insert($model_db);
            tonghopluong_khoi::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/khoi/detail/ma_so='.$mathdv);
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
            $gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl=$gnr['luongcb'] * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
//dd($model);
            return view('functions.tonghopluong.khoi.detail')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết dữ liệu tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model = tonghopluong_khoi::where('mathdv',$inputs['mathdv'])->first();

            //Kiểm tra xem đơn vị đã gửi dữ liệu chưa
            $chk = tonghopluong_huyen::where('thang',$model->thang)
                ->where('nam',$model->nam)
                ->where('phanloai',$model->phanloai)->count();

            if($chk == 0){//chưa gửi => update dữ liệu từ bảng khoi=>huyen
                $model_chitiet = tonghopluong_khoi_chitiet::where('mathdv',$model->mathdv)->get()->toarray();
                $chitiet = unset_key($model_chitiet, array('mathdv','id','created_at','updated_at'));
                $model_diaban = tonghopluong_khoi_diaban::where('mathdv',$model->mathdv)->get()->toarray();
                $diaban = unset_key($model_diaban, array('mathdv','id','created_at','updated_at'));

                if(isset($chitiet)){
                    tonghopluong_huyen_chitiet::insert($chitiet);
                }
                if(isset($diaban)){
                    tonghopluong_huyen_diaban::insert($diaban);
                }
                $th_khoi = new tonghopluong_huyen();
                $th_khoi->madv = $model->madv;
                $th_khoi->mathdv = getdate()[0];;
                $th_khoi->trangthai = 'CHONHAN';
                $th_khoi->phanloai = $model->phanloai;
                $th_khoi->noidung=$model->noidung;
                $th_khoi->nguoilap=$model->nguoilap;
                $th_khoi->ngaylap=$model->ngaylap;
                $th_khoi->macqcq = $model->macqcq;
                $th_khoi->madvbc = $model->madvbc;
                $th_khoi->nguoigui= session('admin')->name;
                $th_khoi->ngaygui= Carbon::now()->toDateTimeString();
                $th_khoi->save();

                //Lưu thông tin vào bảng khối
                $inputs['nguoigui']= session('admin')->name;
                $inputs['ngaygui']= Carbon::now()->toDateTimeString();
                $model->trangthai = 'DAGUI';
                $model->save();

            }else{//Đã gửi dữ liệu
                $model->trangthai = 'GUILOI';
                $model->ghichu = 'Gửi dữ liệu lỗi do đã có dữ liệu tổng hợp';
                $model->save();

            }

            return redirect('/chuc_nang/tong_hop_luong/khoi/index?nam='.$model->nam);
        } else
            return view('errors.notlogin');
    }
}
