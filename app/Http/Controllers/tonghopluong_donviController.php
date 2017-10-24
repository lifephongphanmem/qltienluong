<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdiabandbkk;
use App\dmdiabandbkk_chitiet;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_huyen_diaban;
use App\tonghopluong_tinh;
use App\tonghopluong_tinh_chitiet;
use App\tonghopluong_tinh_diaban;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_donviController extends Controller
{
    function index(Request $requests){
        if (Session::has('admin')) {
            $a_data=array(array('thang'=>'01','mathdv'=>null),
                array('thang'=>'02','mathdv'=>null),
                array('thang'=>'03','mathdv'=>null),
                array('thang'=>'04','mathdv'=>null),
                array('thang'=>'05','mathdv'=>null),
                array('thang'=>'06','mathdv'=>null),
                array('thang'=>'07','mathdv'=>null),
                array('thang'=>'08','mathdv'=>null),
                array('thang'=>'09','mathdv'=>null),
                array('thang'=>'10','mathdv'=>null),
                array('thang'=>'11','mathdv'=>null),
                array('thang'=>'12','mathdv'=>null)
            );
            $a_trangthai=array('CHUALUONG'=>'Chưa tạo bảng lương','CHUATAO'=>'Chưa tổng hợp dữ liệu'
            ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            $inputs=$requests->all();
            $model = tonghopluong_donvi::where('madv',session('admin')->madv)->get();
            $model_bangluong = bangluong::where('madv',session('admin')->madv)->get();
            for($i=0;$i<count($a_data);$i++){
                $a_data[$i]['maphanloai']=session('admin')->maphanloai;
                $tonghop = $model->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam'])->first();
                $bangluong = $model_bangluong->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam']);
                if(count($bangluong)>0){
                    $a_data[$i]['bangluong']='ok';
                    if(count($tonghop)>0){
                        $a_data[$i]['noidung']=$tonghop->noidung;
                        $a_data[$i]['mathdv']=$tonghop->mathdv;
                        $a_data[$i]['trangthai']=$tonghop->trangthai;

                    }else{
                        $a_data[$i]['noidung']='Dữ liệu tổng hợp của '.getTenDV(session('admin')->madv) .' thời điểm '.$a_data[$i]['thang'].'/'.$inputs['nam'];
                        $a_data[$i]['trangthai']='CHUATAO';
                    }
                }else{
                    $a_data[$i]['noidung']='Dữ liệu tổng hợp của '.getTenDV(session('admin')->madv) .' thời điểm '.$a_data[$i]['thang'].'/'.$inputs['nam'];
                    $a_data[$i]['bangluong']=null;
                    $a_data[$i]['trangthai']='CHUALUONG';
                }
            }
                       //dd($a_data);
            return view('functions.tonghopluong.donvi.index')
                ->with('furl','/chuc_nang/tong_hop_luong/don_vi/')
                ->with('nam',$inputs['nam'])
                ->with('model',$a_data)
                ->with('a_trangthai',$a_trangthai)
                ->with('pageTitle','Danh sách tổng hợp lương tại đơn vị');
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

            //lấy bảng lương
            $model_bangluong = bangluong::where('nam',$nam)->where('thang',$thang)->where('madv',$madv)->get();
            //bảng lương chi tiết
            $model_bangluong_ct = bangluong_ct::wherein('mabl',function($query) use($nam, $thang, $madv){
                $query->select('mabl')->from('bangluong')->where('nam',$nam)->where('thang',$thang)->where('madv',$madv);
            })->get();
            $model_congtac = dmphanloaict::all();

            //$model_diaban = dmdiabandbkk::where('madv',$madv)->get();
            $model_diaban_ct = dmdiabandbkk_chitiet::wherein('madiaban',function($query) use($madv){
                $query->select('madiaban')->from('dmdiabandbkk')->where('madv',$madv)->where('phanloai','<>','');
            })->get();
            //Lấy dữ liệu từ các bảng liên quan thêm vào bảng lương chi tiết để tính toán
            foreach($model_bangluong_ct as $ct){
                $bangluong = $model_bangluong->where('mabl',$ct->mabl)->first();
                $ct->manguonkp=$bangluong->manguonkp;
                $ct->linhvuchoatdong=$bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN

                $congtac = $model_congtac->where('mact',$ct->mact)->first();
                $ct->macongtac=$congtac->macongtac;

                $diaban_ct = $model_diaban_ct->where('macanbo',$ct->macanbo)->first();
                if(count($diaban_ct)>0){
                    //$diaban = $model_diaban->where('madiaban',$diaban_ct->madiaban)->first();
                    $ct->madiaban = $diaban_ct->madiaban;
                }else{
                    $ct->madiaban = null;
                }
            }
            //
            //Lấy dữ liệu để lập
            $model_data = $model_bangluong_ct->map(function ($data) {
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
                $luongct = $model_bangluong_ct->where('manguonkp',$model_data[$i]['manguonkp'])
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
            $model_diaban = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);

            for($i=0;$i<count($model_diaban);$i++){
                $luongct = $model_bangluong_ct->where('madiaban',$model_diaban[$i]['madiaban']);

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
            $inputs['phanloai'] = 'DONVI';
            $inputs['noidung']='Dữ liệu tổng hợp của '.getTenDV(session('admin')->madv) .' thời điểm '.$inputs['thang'].'/'.$inputs['nam'];
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
            tonghopluong_donvi_chitiet::insert($model_data);
            tonghopluong_donvi_diaban::insert($model_db);
            tonghopluong_donvi::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so='.$mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv',$mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
            $gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl=$gnr['luongcb'] * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.donvi.detail')
                ->with('furl','/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi_chitiet::where('mathdv',$inputs['mathdv'])
                ->where('manguonkp',$inputs['manguonkp'])
                ->where('macongtac',$inputs['macongtac'])->first();
            //dd($model);

            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
            $gnr=getGeneralConfigs();
/*
            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl=$gnr['luongcb'] * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
*/

            return view('functions.tonghopluong.donvi.edit_detail')
                ->with('furl','/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model',$model)
                //->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function detail_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_diaban::where('mathdv',$mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv',session('admin')->madv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv',$mathdv)->first();
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                        'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
            $gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl=$gnr['luongcb'] * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.donvi.detail_diaban')
                ->with('furl','/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        //Kiểm tra xem đơn vị có đơn vị chủ quản => ko cần update đi đâu chỉ cần chuyên trạng thái
        //Không đơn vị chủ quản, tùy xem thuộc huyện, tỉnh để update lên bang tonghop_huyen, tonghop_tinh
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();

            if (session('admin')->macqcq == session('admin')->madvqlkv) {
                //Trường hợp đơn vị báo cáo số liệu lên trực tiếp đơn vị quản lý khu vực (huyện, tỉnh)
                // => update dữ liệu lên thẳng bảng tổng hợp tỉnh, huyện
                //dd(session('admin'));
                if (session('admin')->level == 'H') {
                    //Kiểm tra xem đơn vị đã gửi dữ liệu chưa
                    $chk = tonghopluong_huyen::where('thang', $model->thang)
                        ->where('nam', $model->nam)
                        ->where('phanloai', $model->phanloai)->count();

                    if ($chk == 0) {//chưa gửi => update dữ liệu từ bảng khoi=>huyen
                        $model_chitiet = tonghopluong_donvi_chitiet::where('mathdv', $model->mathdv)->get()->toarray();
                        $chitiet = unset_key($model_chitiet, array('mathdv', 'id', 'created_at', 'updated_at'));
                        $model_diaban = tonghopluong_donvi_diaban::where('mathdv', $model->mathdv)->get()->toarray();
                        $diaban = unset_key($model_diaban, array('macongtac', 'manguonkp', 'mathdv', 'id', 'created_at', 'updated_at'));

                        if (isset($chitiet)) {
                            tonghopluong_huyen_chitiet::insert($chitiet);
                        }
                        if (isset($diaban)) {
                            tonghopluong_huyen_diaban::insert($diaban);
                        }
                        $th_khoi = new tonghopluong_huyen();
                        $th_khoi->madv = $model->madv;
                        $th_khoi->mathdv = getdate()[0];;
                        $th_khoi->trangthai = 'CHONHAN';
                        $th_khoi->phanloai = $model->phanloai;
                        $th_khoi->noidung = $model->noidung;
                        $th_khoi->nguoilap = $model->nguoilap;
                        $th_khoi->ngaylap = $model->ngaylap;
                        $th_khoi->macqcq = $model->macqcq;
                        $th_khoi->madvbc = $model->madvbc;
                        $th_khoi->thang = $model->thang;
                        $th_khoi->nam = $model->nam;
                        $th_khoi->nguoigui = session('admin')->name;
                        $th_khoi->ngaygui = Carbon::now()->toDateTimeString();
                        $th_khoi->save();

                        //Lưu thông tin vào bảng khối
                        $inputs['nguoigui'] = session('admin')->name;
                        $inputs['ngaygui'] = Carbon::now()->toDateTimeString();
                        $model->trangthai = 'DAGUI';
                        $model->save();

                    } else {//Đã gửi dữ liệu
                        $model->trangthai = 'GUILOI';
                        $model->ghichu = 'Gửi dữ liệu lỗi do đã có dữ liệu tổng hợp';
                        $model->save();
                    }

                }else{
                    //Kiểm tra xem đơn vị đã gửi dữ liệu chưa
                    $chk = tonghopluong_tinh::where('thang', $model->thang)
                        ->where('nam', $model->nam)
                        ->where('phanloai', $model->phanloai)->count();
                    if ($chk == 0) {//chưa gửi => update dữ liệu từ bảng khoi=>huyen
                        $model_chitiet = tonghopluong_donvi_chitiet::where('mathdv', $model->mathdv)->get()->toarray();
                        $chitiet = unset_key($model_chitiet, array('mathdv', 'id', 'created_at', 'updated_at'));
                        $model_diaban = tonghopluong_donvi_diaban::where('mathdv', $model->mathdv)->get()->toarray();
                        $diaban = unset_key($model_diaban, array('macongtac', 'manguonkp', 'mathdv', 'id', 'created_at', 'updated_at'));

                        if (isset($chitiet)) {
                            tonghopluong_tinh_chitiet::insert($chitiet);
                        }
                        if (isset($diaban)) {
                            tonghopluong_tinh_diaban::insert($diaban);
                        }
                        $th_khoi = new tonghopluong_tinh();
                        $th_khoi->madv = $model->madv;
                        $th_khoi->mathdv = getdate()[0];;
                        $th_khoi->trangthai = 'CHONHAN';
                        $th_khoi->phanloai = $model->phanloai;
                        $th_khoi->noidung = $model->noidung;
                        $th_khoi->nguoilap = $model->nguoilap;
                        $th_khoi->ngaylap = $model->ngaylap;
                        $th_khoi->macqcq = $model->macqcq;
                        $th_khoi->madvbc = $model->madvbc;
                        $th_khoi->thang = $model->thang;
                        $th_khoi->nam = $model->nam;
                        $th_khoi->nguoigui = session('admin')->name;
                        $th_khoi->ngaygui = Carbon::now()->toDateTimeString();
                        $th_khoi->save();

                        //Lưu thông tin vào bảng khối
                        $inputs['nguoigui'] = session('admin')->name;
                        $inputs['ngaygui'] = Carbon::now()->toDateTimeString();
                        $model->trangthai = 'DAGUI';
                        $model->save();

                    } else {//Đã gửi dữ liệu
                        $model->trangthai = 'GUILOI';
                        $model->ghichu = 'Gửi dữ liệu lỗi do đã có dữ liệu tổng hợp';
                        $model->save();
                    }
                }
            } else {
                //Trường hợp đơn vị báo cáo số liệu lên đơn vị quản lý khối
                // => update trạng thái của dữ liệu

                $inputs['nguoigui'] = session('admin')->name;
                $inputs['ngaygui'] = Carbon::now()->toDateTimeString();
                $model->trangthai = 'DAGUI';
                $model->save();
            }


            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv){
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = tonghopluong_donvi_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv',$mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
            $gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv',$model_thongtin->madv)->first();

            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $gnr['luongcb'] * $chitiet->tonghs;
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
            $model = tonghopluong_donvi_diaban::where('mathdv',$mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv',session('admin')->madv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv',$mathdv)->first();
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
            $gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl=$gnr['luongcb'] * $chitiet->tonghs;
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
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }
}
