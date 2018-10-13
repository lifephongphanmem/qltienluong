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
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\ngachluong;
use App\tonghopluong_donvi;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dutoanluongController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dutoanluong::where('madv',session('admin')->madv)->orderby('namns')->get();

            return view('manage.dutoanluong.index')
                ->with('furl','/nghiep_vu/quan_ly/du_toan/')
                ->with('furl_ajax','/ajax/du_toan/')
                ->with('model',$model)
                ->with('pageTitle','Danh sách dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function get_detail(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dutoanluong_chitiet::where('masodv',$inputs['masodv'])->where('mact',$inputs['mact'])->first();
        die($model);
    }

    function store(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $inputs['madv']=session('admin')->madv;
        dutoanluong::create($inputs);
        /*
        $model = new chitieubienche();

        $model->macanbo = $inputs['macanbo'];
        $model->ngaytu = getDateTime($inputs['ngaytu']);
        $model->ngayden = getDateTime($inputs['ngayden']);
        $model->quanham = $inputs['quanham'];
        $model->chucvu = $inputs['chucvu'];

        $model->save();
        */
        $result['message'] = "Thêm mới thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function update(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dutoanluong::find($inputs['id']);
        $inputs['luongnb_dt'] = chkDbl($inputs['luongnb_dt']);
        $inputs['luonghs_dt'] = chkDbl($inputs['luonghs_dt']);
        $inputs['luongbh_dt'] = chkDbl($inputs['luongbh_dt']);
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dutoanluong::find($id);
            dutoanluong_chitiet::where('masodv',$model->masodv)->delete();
            dutoanluong_bangluong::where('masodv',$model->masodv)->delete();
            $model->delete();
            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function checkNamDT(Request $request){
        $inputs = $request->all();
        $model = dutoanluong::where('namns',$inputs['namdt'])->where('madv', session('admin')->madv)->first();
        if (isset($model)) {
            die(json_encode(array('message'=>'Năm chỉ tiêu đã tồn tại. Bạn cần nhập lại năm khác.',
                'status'=>'false')));
        } else {
            die(json_encode(array('message'=>'Năm chỉ tiêu thỏa mãn điều kiện.',
                'status'=>'true')));
        }
    }

    function checkBangLuong(Request $request){
        $inputs = $request->all();
        $model = tonghopluong_donvi::where('thang',$inputs['thang'])
            ->where('nam',$inputs['nam'])
            ->where('madv',session('admin')->madv)
            ->first();

        if (!isset($model)) {
            die(json_encode(array('message'=>'Dữ liệu tổng hợp lương không tồn tại. Bạn cần nhập bảng lương khác.',
                'status'=>'false')));
        } else {
            die(json_encode(array('message'=>'Năm chỉ tiêu thỏa mãn điều kiện.',
                'status'=>'true')));
        }
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            $gen = getGeneralConfigs();
            $masodv = session('admin')->madv . '_' . getdate()[0];
            //dd($inputs);
            //1536402868: Đại biểu hội đồng nhân dân; 1536459380: Cán bộ cấp ủy viên; 1506673695: KCT cấp xã; 1535613221: kct cấp thôn
            $a_th = array_merge(array('macanbo', 'mact', 'macvcq', 'mapb', 'ngayden'),getColTongHop());
            $m_cb_kn = hosocanbo_kiemnhiem::select($a_th)
                ->where('madv', session('admin')->madv)
                ->wherein('mact',['1536402868','1536459380','1535613221', '1506673695'])
                ->get()->keyBy('macanbo')->toarray();
            $a_th = array_merge(array('ngaysinh','tencanbo', 'tnndenngay', 'gioitinh', 'msngbac', 'bac', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv'),$a_th);
            $model = hosocanbo::select($a_th)->where('madv', session('admin')->madv)
                ->where('theodoi','<', '9')
                ->get();
            $a_hoten = array_column($model->toarray(),'tencanbo','macanbo');
            foreach($model as $cb){
                $cb->macongtac = $a_congtac[$cb->mact];
                $cb->masodv = $masodv;
                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;

                if (isset($cb->ngaysinh)) {
                    $dt_ns = date_create($cb->ngaysinh);
                    $cb->nam_ns = date_format($dt_ns, 'Y') + ($cb->gioitinh == 'Nam'? $gen['tuoinam']:$gen['tuoinu']);
                    $cb->thang_ns = date_format($dt_ns, 'm');
                } else {
                    $cb->nam_ns = null;
                    $cb->thang_ns = null;
                }

                if (isset($cb->ngayden)) {
                    $dt_luong = date_create($cb->ngayden);
                    $cb->nam_nb = date_format($dt_luong, 'Y');
                    $cb->thang_nb = date_format($dt_luong, 'm');
                } else {
                    $cb->nam_nb = null;
                    $cb->thang_nb = null;
                }

                if (isset($cb->tnndenngay)) {
                    $dt_nghe = date_create($cb->tnndenngay);
                    $cb->nam_tnn = date_format($dt_nghe, 'Y');
                    $cb->thang_tnn = date_format($dt_nghe, 'm');

                } else {
                    $cb->nam_tnn = null;
                    $cb->thang_tnn = null;

                }
            }

            $m_cb = $model->wherein('macongtac',['BIENCHE','KHONGCT'])->keyBy('macanbo')->toarray();
            $m_nh = $model->wherein('nam_ns',$inputs['namdt'])->keyBy('macanbo')->toarray();
            $m_nb = $model->wherein('nam_nb',$inputs['namdt'])->keyBy('macanbo')->toarray();
            $m_tnn = $model->wherein('nam_tnn',$inputs['namdt'])->keyBy('macanbo')->toarray();

            foreach($m_cb_kn as $key =>$val){
                $m_cb_kn[$key]['tencanbo'] =isset($a_hoten[$m_cb_kn[$key]['macanbo']])? $a_hoten[$m_cb_kn[$key]['macanbo']] : '';
                $m_cb_kn[$key]['ngaysinh'] = null;
                $m_cb_kn[$key]['tnndenngay'] = null;
                $m_cb_kn[$key]['macongtac'] = null;
                $m_cb_kn[$key]['gioitinh'] = null;
                $m_cb_kn[$key]['nam_ns'] = null;
                $m_cb_kn[$key]['thang_ns'] = null;
                $m_cb_kn[$key]['nam_nb'] = null;
                $m_cb_kn[$key]['thang_nb'] = null;
                $m_cb_kn[$key]['nam_tnn'] = null;
                $m_cb_kn[$key]['thang_tnn'] = null;
                $m_cb_kn[$key]['msngbac'] = null;
                $m_cb_kn[$key]['bac'] = null;
                $m_cb_kn[$key]['bhxh_dv'] = 0;
                $m_cb_kn[$key]['bhyt_dv'] = 0;
                $m_cb_kn[$key]['bhtn_dv'] = 0;
                $m_cb_kn[$key]['kpcd_dv'] = 0;
                $m_cb_kn[$key]['masodv'] = $masodv;
                $m_cb[$key.'_kn'] = $m_cb_kn[$key];
            }

            $a_pc = dmphucap_donvi::select('mapc','phanloai','congthuc','baohiem')
                ->where('madv', session('admin')->madv)->wherein('mapc',getColTongHop())->get()->toarray();
            $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();

            $a_thang = array(array('thang'=>'01', 'nam'=>$inputs['namdt']),
                array('thang'=>'02', 'nam'=>$inputs['namdt']),
                array('thang'=>'03', 'nam'=>$inputs['namdt']),
                array('thang'=>'04', 'nam'=>$inputs['namdt']),
                array('thang'=>'05', 'nam'=>$inputs['namdt']),
                array('thang'=>'06', 'nam'=>$inputs['namdt']),
                array('thang'=>'07', 'nam'=>$inputs['namdt']),
                array('thang'=>'08', 'nam'=>$inputs['namdt']),
                array('thang'=>'09', 'nam'=>$inputs['namdt']),
                array('thang'=>'10', 'nam'=>$inputs['namdt']),
                array('thang'=>'11', 'nam'=>$inputs['namdt']),
                array('thang'=>'12', 'nam'=>$inputs['namdt'])
            );

            //chạy tính hệ số lương, phụ cấp trc. Sau này mỗi tháng chỉ chạy cán bộ thay đổi
            foreach($m_cb as $key =>$val){
                $m_cb[$key] = $this->getHeSoPc($a_pc, $m_cb[$key],$inputs['luongcoban']);
            }
            foreach($m_nh as $key =>$val){
                $m_nh[$key] = $this->getHeSoPc_nh($a_pc, $m_nh[$key]);
            }

            foreach($m_tnn as $key =>$val){
                $m_tnn[$key]['pctnn'] = $m_tnn[$key]['pctnn'] + 1;
                $m_tnn[$key] = $this->getHeSoPc($a_pc, $m_tnn[$key],$inputs['luongcoban']);
            }

            foreach($m_nb as $key =>$val){
                if(isset($a_nhomnb[$val['msngbac']])){
                    $nhomnb = $a_nhomnb[$val['msngbac']];
                    $hesomax = $nhomnb['heso'] +  ($nhomnb['heso'] * $nhomnb['hesochenhlech']);
                    if($val['heso'] >= $hesomax){
                        $m_nb[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'] == 0 ? $nhomnb['vuotkhung'] : $m_nb[$key]['vuotkhung'] + 1;
                    }else{
                        $m_nb[$key]['heso'] += $nhomnb['hesochenhlech'];
                    }
                }
                $m_nb[$key] = $this->getHeSoPc($a_pc, $m_nb[$key],$inputs['luongcoban']);
            }
            $a_data = array();
            for($i=0;$i<count($a_thang);$i++) {
                $a_nh = a_getelement($m_nh, array('thang_nh' => $a_thang[$i]['thang']));
                if(count($a_nh) > 0){
                    foreach($a_nh as $key=>$val){
                        $m_nb[$key] = $a_nh[$key];
                    }
                }
                $a_nb = a_getelement($m_nb, array('thang_nb' => $a_thang[$i]['thang']));
                if(count($a_nb) > 0){
                    foreach($a_nb as $key=>$val){
                        $m_nb[$key] = $a_nb[$key];
                    }
                }
                $a_tnn = a_getelement($m_tnn, array('thang_tnn' => $a_thang[$i]['thang']));
                if(count($a_tnn) > 0){
                    foreach($a_tnn as $key=>$val){
                        $m_nb[$key] = $a_tnn[$key];
                    }
                }
                //lưu vào 1 mảng
                foreach($m_cb as $key =>$val){
                    $m_cb[$key]['thang'] = $a_thang[$i]['thang'];
                    $m_cb[$key]['nam'] = $a_thang[$i]['nam'];
                    $a_data[] = $m_cb[$key];
                }

                //tính toán xong lưu dữ liệu
            }
            $a_col = array('bac','bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'gioitinh', 'nam_nb','nam_ns','nam_tnn',
                'thang_nb','thang_ns','thang_tnn','ngayden','ngaysinh','tnndenngay');
            $a_data = unset_key($a_data, $a_col);
            //dd($a_data);
            //chia nhỏ thành các mảng nhỏ 100 phần tử để insert
            $a_chunk = array_chunk($a_data, 100);
            foreach($a_chunk  as $data){
                dutoanluong_bangluong::insert($data);
            }
            $m_data = a_split($a_data,array('mact'));
            $m_data = a_unique($m_data);

            $luongnb = 0;
            $luonghs = 0;
            $luongbh = 0;

            for ($i = 0; $i < count($m_data); $i++) {
                $canbo = a_getelement($m_cb,array('mact'=>$m_data[$i]['mact']));
                $dutoan = a_getelement($a_data,array('mact'=>$m_data[$i]['mact']));
                $m_data[$i]['masodv'] = $masodv;
                $soluong = count($canbo);
                $m_data[$i]['canbo_congtac'] = $soluong;
                $m_data[$i]['luongnb_dt'] = (array_sum(array_column($dutoan,'heso')) +  array_sum(array_column($dutoan,'vuotkhung'))) * $inputs['luongcoban'];
                $luongnb += $m_data[$i]['luongnb_dt'];
                $m_data[$i]['luonghs_dt'] = array_sum(array_column($dutoan,'tonghs')) * $inputs['luongcoban'] - $m_data[$i]['luongnb_dt'];
                $luonghs += $m_data[$i]['luonghs_dt'];
                $m_data[$i]['luongbh_dt'] = array_sum(array_column($dutoan,'ttbh_dv'));
                $luongbh += $m_data[$i]['luongbh_dt'];
            }
            dutoanluong_chitiet::insert($m_data);

            $inputs['masodv'] = $masodv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['luongnb_dt'] = $luongnb;
            $inputs['luonghs_dt'] = $luonghs;
            $inputs['luongbh_dt'] = $luongbh;
            $inputs['madv'] = session('admin')->madv;
            $inputs['namns'] = $inputs['namdt'];

            dutoanluong::create($inputs);

            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    /**
     * @param $a_pc
     * @param $m_cb
     * @param $i
     * @return array
     */
    public function getHeSoPc($a_pc, $m_cb, $luongcb = 0)
    {
        $stbhxh_dv = 0;
        $stbhyt_dv = 0;
        $stkpcd_dv = 0;
        $stbhtn_dv = 0;
        $m_cb['tonghs'] = 0;
        $m_cb['luongtn'] = 0;
        $m_cb['luongcoban'] = $luongcb;
        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            switch (getDbl($a_pc[$i]['phanloai'])) {
                case 0:{
                    $m_cb['tonghs'] += $m_cb[$mapc];
                    break;
                }
                case 1: {//số tiền
                    $m_cb['luongtn'] += $m_cb[$mapc];
                    break;
                }
                case 2: {//phần trăm
                    if ($mapc != 'vuotkhung') {//vượt khung đã tính ở trên
                        $heso = 0;
                        foreach (explode(',', $a_pc[$i]['congthuc']) as $cthuc) {
                            if ($cthuc != '') {
                                $heso += $m_cb[$cthuc];
                            }
                        }
                        $m_cb[$mapc] = $heso * $m_cb[$mapc] / 100;
                    }
                    $m_cb['tonghs'] += $m_cb[$mapc];
                    break;
                }
                default: {//trường hợp còn lại (ẩn,...)
                    $m_cb[$mapc] = 0;
                    break;
                }
            }
            if ($a_pc[$i]['baohiem'] == 1) {
                $stbhxh_dv += round($m_cb['bhxh_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhyt_dv += round($m_cb['bhyt_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stkpcd_dv += round($m_cb['kpcd_dv'] * $m_cb[$mapc] * $luongcb, 0);
                $stbhtn_dv += round($m_cb['bhtn_dv'] * $m_cb[$mapc] * $luongcb, 0);
            }
        }

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;
        $m_cb['luongtn'] += round($m_cb['tonghs'] * $luongcb, 0);
        $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
        return $m_cb;

    }

    public function getHeSoPc_nh($a_pc, $m_cb, $luongcb = 0)
    {
        for ($i_pc = 0; $i_pc < count($a_pc); $i_pc++) {
            $mapc = $a_pc[$i_pc]['mapc'];
            $m_cb['stbhxh_dv'] = 0;
            $m_cb['stbhyt_dv'] = 0;
            $m_cb['stkpcd_dv'] = 0;
            $m_cb['stbhtn_dv'] = 0;
            $m_cb['tonghs'] = 0;
            $m_cb['luongtn'] = 0;
            $m_cb[$mapc] = 0;
            $m_cb['luongcoban'] = $luongcb;
            $m_cb['tencanbo'] .= ' (nghỉ hưu)';
        }
        return $m_cb;
    }

    function show(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            $model_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $a_ct = array_column($model_tenct->toArray(),'tenct','mact');

            foreach($model as $ct){
                $ct->tenct = isset($a_ct[$ct->mact])? $a_ct[$ct->mact]:'';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt + $ct->luongnb;
            }
            $inputs['maphanloai'] = dmdonvi::where('madv',session('admin')->madv)->first()->maphanloai;
            $inputs['luongcb'] = getGeneralConfigs()['luongcb'];
            $inputs['heso'] = $inputs['maphanloai'] == 'MAMNON'? '2.1' : '2.34';
            return view('manage.dutoanluong.detail')
                ->with('furl', '/nghiep_vu/quan_ly/du_toan/')
                ->with('model', $model)
                ->with('model_dutoan', $model_dutoan)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Dự toán lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function update_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongnb'] = getDbl($inputs['luongnb']);
            $inputs['canbo_dutoan'] = getDbl($inputs['canbo_dutoan']);
            $inputs['canbo_congtac'] = getDbl($inputs['canbo_congtac']);
            $inputs['luongnb_dt'] = getDbl($inputs['luongnb_dt']);
            $inputs['luonghs_dt'] = getDbl($inputs['luonghs_dt']);
            $inputs['luongbh_dt'] = getDbl($inputs['luongbh_dt']);

            $model = dutoanluong_chitiet::find($inputs['id_ct']);
            $model->update($inputs);
            $this->duToan($model->masodv);
            return redirect('/nghiep_vu/quan_ly/du_toan?maso='.$model->masodv);

        } else
            return view('errors.notlogin');
    }

    function duToan($masodv){
        $m_chitiet = dutoanluong_chitiet::where('masodv',$masodv)->get();
        $inputs = array();
        $inputs['luongnb_dt'] = $m_chitiet->sum('luongnb_dt');
        $inputs['luonghs_dt'] = $m_chitiet->sum('luonghs_dt');
        $inputs['luongbh_dt'] = $m_chitiet->sum('luongbh_dt');
        dutoanluong::where('masodv',$masodv)->first()->update($inputs);
    }

    function destroy_detail($id){
        if (Session::has('admin')) {
            $model = dutoanluong_chitiet::find($id);
            $model->delete();
            $this->duToan($model->masodv);
            return redirect('/nghiep_vu/quan_ly/du_toan?maso='.$model->masodv);
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
            $model = dutoanluong::where('masodv', $inputs['masodv'])->first();

            //check đơn vị chủ quản là gửi lên huyện => chuyển trạng thái; import bản ghi vào bảng huyện
                //khối => chuyển trạng thái
            if(session('admin')->macqcq == session('admin')->madvqlkv){//đơn vị chủ quản là huyện
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                $model_huyen = dutoanluong_huyen::where('masodv', $model->masoh)->first();
                if(count($model_huyen) == 0){
                    $masoh = getdate()[0];
                    $inputs['namns'] = $model->namns;
                    $inputs['madv'] = $model->madv;
                    $inputs['masodv'] = $masoh;
                    $inputs['trangthai'] = 'DAGUI';
                    $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu dự toán lương.';
                    $inputs['nguoilap'] = session('admin')->name;
                    $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                    $inputs['macqcq'] = session('admin')->macqcq;
                    $inputs['madvbc'] = session('admin')->madvbc;
                    $model->masoh = $masoh;
                    dutoanluong_huyen::create($inputs);
                }else{
                    $model_huyen->trangthai = 'DAGUI';
                    $model_huyen->nguoilap = session('admin')->name;
                    $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
                    $model_huyen->save();
                }
            }

            $model->nguoiguidv = session('admin')->name;
            $model->ngayguidv = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            $model->save();

            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    //chưa làm
    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dutoanluong::where('mathdv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            //chưa có view
            return redirect('/chuc_nang/xem_du_lieu/index?thang=' . $model->thang . '&nam=' . $model->nam . '&trangthai=ALL');
        } else
            return view('errors.notlogin');
    }

    function getlydo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = dutoanluong::select('lydo')->where('masodv', $inputs['masodv'])->first();

        die($model);
    }


}
