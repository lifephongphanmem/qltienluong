<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\ngachluong;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_chitiet;
use App\nguonkinhphi_huyen;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class nguonkinhphiController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('madv',session('admin')->madv)->get();
            $lvhd = getLinhVucHoatDong(false);
            foreach($model as $ct){
                $ct->linhvuc = isset($lvhd[$ct->linhvuchoatdong])? $lvhd[$ct->linhvuchoatdong]:'' ;
            }
            return view('manage.nguonkinhphi.index')
                ->with('furl','/nguon_kinh_phi/')
                ->with('a_trangthai',getStatus())
                ->with('model',$model)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function create_201018(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model_ttqd = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($model_ttqd->ngayapdung);
            $nam = date_format($ngayapdung, 'Y');
            $thang = date_format($ngayapdung, 'm');
           //chênh lệch = mức chenh lệch * số tháng còn lại.
            $muc_chenhlech = ($model_ttqd->mucapdung - $model_ttqd->muccu) * (12 - $thang + 1);
            $madv = session('admin')->madv;
            //$gen = getGeneralConfigs();
            $model_congtac = dmphanloaict::all();
            $model_phanloai = dmphanloaicongtac::all();
            //tự tính toán số liệu độc lập với bảng lương (như tạo 1 bảng lương) => tính toán ra kết quả
            //Lấy tất cả cán bộ trong đơn vị
            $m_cb = hosocanbo::where('madv', $madv)
                ->select('macanbo', 'mact', 'lvhd', 'heso', 'hesopc', 'hesott', 'vuotkhung',
                    'pck', 'pccv', 'pckv', 'pcth', 'pcdh', 'pcld', 'pcudn', 'pctn', 'pctnn', 'pcdbn', 'pcvk', 'pckn', 'pccovu', 'pcdbqh', 'pctnvk', 'pcbdhdcu', 'pcdang', 'pcthni')
                ->get();

            //Dùng tìm kiếm các bộ nào phù hợp. Do lvhd là mảng nên pải lọc
            foreach($m_cb as $canbo){
                $congtac = $model_congtac->where('mact',$canbo->mact)->first();
                $canbo->macongtac = $congtac->macongtac;
                $a_lv = explode(',',$canbo->lvhd);
                if(in_array($inputs['linhvuchoatdong'],$a_lv) || $canbo->lvhd =''){
                    $canbo->lvhd = $inputs['linhvuchoatdong'];
                }
            }
            //phân loại đơn vị để lọc lĩnh vực hoạt động
            if (session('admin')->maphanloai != 'KVXP') {
                $m_cb = $m_cb->where('lvhd',$inputs['linhvuchoatdong']);
            }else{
                $inputs['linhvuchoatdong'] = 'QLNN'; //để giá trị mặc định cho đơn vị KVXP
            }

            $model_data = $m_cb->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac'])
                    ->all();
            });
            $model_data = a_unique($model_data);
            $a_col = array('heso','vuotkhung','pcct','pckct',
                'pck','pccv','pckv','pcth','pcdd','pcdh','pcld',
                'pcudn','pctn','pctnn','pcdbn','pckn','pcdang',
                'pccovu','pclt','pcd','pctr','pctnvk','pcthni');

            for($i=0;$i<count($model_data);$i++){
                $luong = $m_cb->where('macongtac',$model_data[$i]['macongtac']);
                $phanloai = $model_phanloai->where('macongtac',$model_data[$i]['macongtac'])->first();

                //cộng hesopc vào tổng hệ số để tính toán
                $tonghs = chkDbl($luong->sum('hesopc'));
                foreach ($a_col as $col) {
                    $tonghs += chkDbl($luong->sum($col));
                }

                $model_data[$i]['pcbdhdcu'] = chkDbl($luong->sum('pcbdhdcu'))* $muc_chenhlech;
                $model_data[$i]['pcdbqh'] = chkDbl($luong->sum('pcdbqh'))* $muc_chenhlech;
                //Vượt khung dùng làm trường thay thế pc Đảng ủy viên
                $model_data[$i]['pcvk'] = chkDbl($luong->sum('pcvk'))* $muc_chenhlech;
                $model_data[$i]['luonghs'] = $tonghs * $muc_chenhlech;
                $model_data[$i]['nopbh'] = $muc_chenhlech * ($phanloai->bhxh_dv + $phanloai->bhyt_dv + $phanloai->bhtn_dv + $phanloai->kpcd_dv)/100
                    * chkDbl($luong->sum('heso')) + chkDbl($luong->sum('pccv'))+ chkDbl($luong->sum('vuotkhung'));

            }

            $inputs['trangthai'] = 'CHOGUI';
            $inputs['maphanloai'] = session('admin')->maphanloai;
            $inputs['masodv'] = getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['namns'] = $nam;
            $inputs['nhucau'] = 0;
            $inputs['daibieuhdnd'] = 0;
            $inputs['uyvien'] = 0;
            $inputs['boiduong'] = 0;
            foreach($model_data as $data){
                switch($data['macongtac']){
                    case 'BIENCHE':{
                        $inputs['luongphucap'] = $data['luonghs'];
                        break;
                    }
                    case 'NGHIHUU':{
                        $inputs['nghihuu'] = $data['luonghs'];
                        break;
                    }
                    case 'KHONGCT':{
                        $inputs['canbokct'] = $data['luonghs'];
                        break;
                    }
                }
                $inputs['nhucau'] += ($data['luonghs'] +$data['pcdbqh'] + $data['pcvk'] +$data['pcbdhdcu']);

                $inputs['daibieuhdnd'] += $data['pcdbqh'];
                $inputs['uyvien'] += $data['pcvk'];
                $inputs['boiduong'] += $data['pcbdhdcu'];
            }

            //Kiểm tra nếu có rồi thì ko tạo
            $chk = nguonkinhphi::where('sohieu',$inputs['sohieu'])
                ->where('namns',$inputs['namns'])
                ->where('madv',$madv)
                ->count();

            if($chk == 0){
                nguonkinhphi::create($inputs);
                return redirect('/nguon_kinh_phi/ma_so='.$inputs['masodv']);
            }else{
                return view('errors.data_exist')
                    ->with('furl','/nguon_kinh_phi/danh_sach');
            }

        } else
            return view('errors.notlogin');
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['namdt'] = $inputs['sohieu'] == 'TT67_2017'? 2017 : 2018; //lấy năm từ ngày áp dung
            /*
            $model_ttqd = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $ngayapdung = new Carbon($model_ttqd->ngayapdung);
            $nam = date_format($ngayapdung, 'Y');
            $thang = date_format($ngayapdung, 'm');
             * */

            //Kiểm tra nếu có rồi thì ko tạo
            $chk = nguonkinhphi::where('sohieu',$inputs['sohieu'])
                ->where('namns',$inputs['namdt'])
                ->where('madv',session('admin')->madv)
                ->count();

            if($chk > 0){
                return view('errors.data_exist')
                    ->with('furl','/nguon_kinh_phi/danh_sach');
            }


            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            $gen = getGeneralConfigs();
            $a_pc = dmphucap_donvi::select('mapc','phanloai','congthuc','baohiem')
                ->where('madv', session('admin')->madv)->wherein('mapc',getColTongHop())->get()->toarray();
            $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();
            $masodv = session('admin')->madv . '_' . getdate()[0];


            $inputs['chenhlech'] = chkDbl($inputs['chenhlech']);
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
                    $cb->nam_ns =(string) date_format($dt_ns, 'Y') + ($cb->gioitinh == 'Nam'? $gen['tuoinam']:$gen['tuoinu']);
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

            $m_cb = $model->wherein('macongtac',['BIENCHE','KHONGCT','HOPDONG'])->keyBy('macanbo')->toarray();
            $m_nh = $model->where('nam_ns','<=',$inputs['namdt'])->keyBy('macanbo')->toarray();
            $m_nb = $model->where('nam_nb','<=',$inputs['namdt'])->keyBy('macanbo')->toarray();
            $m_tnn = $model->where('nam_tnn','<=',$inputs['namdt'])->keyBy('macanbo')->toarray();

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

            //chạy tính hệ số lương, phụ cấp trc. Sau này mỗi tháng chỉ chạy cán bộ thay đổi
            foreach($m_cb as $key =>$val){
                $m_cb[$key] = $this->getHeSoPc($a_pc, $m_cb[$key],$inputs['chenhlech']);
            }
            foreach($m_nh as $key =>$val){
                $m_nh[$key] = $this->getHeSoPc_nh($a_pc, $m_nh[$key]);
            }

            foreach($m_tnn as $key =>$val){
                $m_tnn[$key]['pctnn'] = $m_tnn[$key]['pctnn'] + 1;
                $m_tnn[$key] = $this->getHeSoPc($a_pc, $m_tnn[$key],$inputs['chenhlech']);
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
                $m_nb[$key] = $this->getHeSoPc($a_pc, $m_nb[$key],$inputs['chenhlech']);
            }

            $a_thang = array(
                array('thang'=>'07', 'nam'=>$inputs['namdt']),
                array('thang'=>'08', 'nam'=>$inputs['namdt']),
                array('thang'=>'09', 'nam'=>$inputs['namdt']),
                array('thang'=>'10', 'nam'=>$inputs['namdt']),
                array('thang'=>'11', 'nam'=>$inputs['namdt']),
                array('thang'=>'12', 'nam'=>$inputs['namdt'])
            );
            $a_data = array();
            $a_danghihuu = array();
            for($i=0;$i<count($a_thang);$i++) {
                $a_nh = a_getelement($m_nh, array('thang_ns' => $a_thang[$i]['thang']));
                if(count($a_nh) > 0){
                    foreach($a_nh as $key=>$val){
                        $m_cb[$key] = $a_nh[$key];
                        $a_danghihuu[] = $key;
                    }
                }
                $a_nb = a_getelement($m_nb, array('thang_nb' => $a_thang[$i]['thang']));
                if(count($a_nb) > 0){
                    foreach($a_nb as $key=>$val){
                        if(!in_array($key,$a_danghihuu)){
                            $m_cb[$key] = $a_nb[$key];
                        }
                    }
                }
                $a_tnn = a_getelement($m_tnn, array('thang_tnn' => $a_thang[$i]['thang']));
                if(count($a_tnn) > 0){
                    foreach($a_tnn as $key=>$val){
                        if(!in_array($key,$a_danghihuu)){
                            $m_cb[$key] = $a_tnn[$key];
                        }
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
                //nguonkinhphi_bangluong::insert($data);
            }
            $m_data = a_split($a_data,array('mact','macongtac'));
            $m_data = a_unique($m_data);

            for ($i = 0; $i < count($m_data); $i++) {
                $dutoan = a_getelement($a_data, array('mact' => $m_data[$i]['mact']));
                $m_data[$i]['masodv'] = $masodv;
                $m_data[$i]['nhucau'] = 0;
                $m_data[$i]['daibieuhdnd'] = 0;
                $m_data[$i]['uyvien'] = 0;
                $m_data[$i]['boiduong'] = 0;
                $m_data[$i]['luongphucap'] = 0;
                $m_data[$i]['nghihuu'] = 0;
                $m_data[$i]['canbokct'] = 0;
                $m_data[$i]['boiduong'] = array_sum(array_column($dutoan, 'pcbdhdcu')) * $inputs['chenhlech'];
                $m_data[$i]['daibieuhdnd'] = array_sum(array_column($dutoan, 'pcdbqh')) * $inputs['chenhlech'];
                //Vượt khung dùng làm trường thay thế pc Đảng ủy viên
                $m_data[$i]['uyvien'] = array_sum(array_column($dutoan, 'pcvk')) * $inputs['chenhlech'];
                $m_data[$i]['nopbh'] = array_sum(array_column($dutoan, 'ttbh_dv'));
                //dùng luongtn vì các phụ cấp tính theo số tiền đã cộng vào luongtn (ko tính vào hệ số)
                $m_data[$i]['luonghs'] = array_sum(array_column($dutoan, 'luongtn'))  - $m_data[$i]['boiduong'] - $m_data[$i]['daibieuhdnd'] - $m_data[$i]['uyvien'];
                switch($m_data[$i]['macongtac']){
                    case 'BIENCHE':{
                        $m_data[$i]['luongphucap'] = $m_data[$i]['luonghs'];
                        break;
                    }
                    case 'NGHIHUU':{
                        $m_data[$i]['nghihuu'] = $m_data[$i]['luonghs'];
                        break;
                    }
                    case 'KHONGCT':{
                        $m_data[$i]['canbokct'] = $m_data[$i]['luonghs'];
                        break;
                    }
                }
                $m_data[$i]['nhucau'] = ($m_data[$i]['luonghs'] +$m_data[$i]['uyvien'] + $m_data[$i]['daibieuhdnd'] +$m_data[$i]['boiduong']);
            }

            $inputs['trangthai'] = 'CHOGUI';
            $inputs['maphanloai'] = session('admin')->maphanloai;
            $inputs['masodv'] = $masodv;
            $inputs['madv'] = session('admin')->madv;
            //$inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['namns'] = $inputs['namdt'];
            $inputs['nhucau'] = array_sum(array_column($m_data, 'nhucau'));
            $inputs['luongphucap'] = array_sum(array_column($m_data, 'luongphucap'));
            $inputs['nghihuu'] = array_sum(array_column($m_data, 'nghihuu'));
            $inputs['canbokct'] = array_sum(array_column($m_data, 'canbokct'));
            $inputs['boiduong'] = array_sum(array_column($m_data, 'boiduong'));
            $inputs['daibieuhdnd'] = array_sum(array_column($m_data, 'daibieuhdnd'));
            $inputs['uyvien'] = array_sum(array_column($m_data, 'uyvien'));

            //lưu dữ liệu
            $a_col = array('bac','bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'gioitinh', 'nam_nb',
                'nam_ns','nam_tnn','thang_nb','thang_ns','thang_tnn','ngayden','ngaysinh','tnndenngay');
            $a_data = unset_key($a_data, $a_col);
            //chia nhỏ thành các mảng nhỏ 100 phần tử để insert
            $a_chunk = array_chunk($a_data, 100);
            foreach($a_chunk  as $data){
                nguonkinhphi_bangluong::insert($data);
            }
            $m_data = unset_key($m_data, array('luonghs','nopbh'));
            nguonkinhphi_chitiet::insert($m_data);
            nguonkinhphi::create($inputs);
            return redirect('/nguon_kinh_phi/danh_sach');

        } else
            return view('errors.notlogin');
    }

    function edit($masodv)
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('masodv', $masodv)->first();
            if (count($model) > 0) {
                $model->nhucaukp = $model->luongphucap + $model->daibieuhdnd + $model->canbokct
                    + $model->uyvien + $model->boiduong + $model->nghihuu;
                $model->nhucaupc = $model->thunhapthap + $model->diaban
                    + $model->tinhgiam + $model->nghihuusom;
            }
            return view('manage.nguonkinhphi.edit')
                ->with('furl', '/du_toan/nguon_kinh_phi/')
                ->with('model', $model)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv',$inputs['masodv'])->first();
            $inputs['luongphucap'] = chkDbl($inputs['luongphucap']);
            $inputs['daibieuhdnd'] = chkDbl($inputs['daibieuhdnd']);
            $inputs['canbokct'] = chkDbl($inputs['canbokct']);
            $inputs['uyvien'] = chkDbl($inputs['uyvien']);
            $inputs['boiduong'] = chkDbl($inputs['boiduong']);
            $inputs['nghihuu'] = chkDbl($inputs['nghihuu']);

            $inputs['tietkiem'] = chkDbl($inputs['tietkiem']);
            $inputs['hocphi'] = chkDbl($inputs['hocphi']);
            $inputs['vienphi'] = chkDbl($inputs['vienphi']);
            $inputs['nguonthu'] = chkDbl($inputs['nguonthu']);
            $inputs['nguonkp'] = chkDbl($inputs['nguonkp']);

            $inputs['thunhapthap'] = chkDbl($inputs['thunhapthap']);
            $inputs['diaban'] = chkDbl($inputs['diaban']);
            $inputs['tinhgiam'] = chkDbl($inputs['tinhgiam']);
            $inputs['nghihuusom'] = chkDbl($inputs['nghihuusom']);

            $inputs['nhucau'] = chkDbl($inputs['nhucaukp']) + chkDbl($inputs['nhucaupc']);
            //dd($inputs);
            $model->update($inputs);

            return redirect('/du_toan/nguon_kinh_phi/danh_sach');
        }else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();

            //kiểm tra xem gửi lên khối hay lên huyện
            //lên khối=> chuyển trạng thái do nguonkinhphi(SD) = nguonkinhphi_khoi(TH)
            //lên huyện => phát sinh bản ghi mới tại bảng nguonkinhphi_huyen

            //check đơn vị chủ quản là gửi lên huyện => chuyển trạng thái; import bản ghi vào bảng huyện
                //khối => chuyển trạng thái
            if(session('admin')->macqcq == session('admin')->madvqlkv){//đơn vị chủ quản là huyện
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                $model_huyen = nguonkinhphi_huyen::where('masodv', $model->masoh)->first();
                if(count($model_huyen) == 0){
                    $masoh = getdate()[0];
                    $model->masoh = $masoh;

                    $inputs['sohieu'] = $model->sohieu;
                    $inputs['madv'] = $model->madv;
                    $inputs['masodv'] = $masoh;
                    $inputs['trangthai'] = 'DAGUI';
                    $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu dự toán lương.';
                    $inputs['nguoilap'] = session('admin')->name;
                    $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                    $inputs['macqcq'] = session('admin')->macqcq;
                    $inputs['madvbc'] = session('admin')->madvbc;
                    nguonkinhphi_huyen::create($inputs);
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

            return redirect('du_toan/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function printf($masodv)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('masodv',$masodv)->first();
            if(count($model) == 0){
                $model = nguonkinhphi::where('masoh',$masodv)->first();
            }

            $m_dv = dmdonvi::where('madv',$model->madv)->first();
            $data = array();
            $data[]=array('val'=>'GDDT','tt'=>'a','noidung'=>'Sự nghiệp giáo dục - đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'GD','tt'=>'-','noidung'=>'Giáo dục','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'DT','tt'=>'-','noidung'=>'Đào tạo','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'YTE','tt'=>'b','noidung'=>'Sự nghiệp y tế','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'KHAC','tt'=>'c','noidung'=>'Sự nghiệp khác','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'QLNN','tt'=>'d','noidung'=>' Quản lý nhà nước, Đảng, đoàn thể','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            $data[]=array('val'=>'QLNN','tt'=>'-','noidung'=>'Trong đó: Cán bộ, công chức cấp xã','nhucau'=>0,'nguonkp'=>0,'tietkiem'=>0,'hocphi'=>0,'vienphi'=>0,'nguonthu'=>0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT
            $khac = false;
            for($i=0;$i<count($data);$i++){
                if($data[$i]['val'] == $model->linhvuchoatdong){
                    $data[$i]['nhucau'] = $model->nhucau;
                    $data[$i]['nguonkp'] = $model->nguonkp;
                    $data[$i]['tietkiem'] = $model->tietkiem;
                    $data[$i]['hocphi'] = $model->hocphi;
                    $data[$i]['vienphi'] = $model->vienphi;
                    $data[$i]['nguonthu'] = $model->nguonthu;

                    $khac = true;
                }
            }
            $data[0]['nhucau'] = $data[1]['nhucau']+$data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp']+ $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'] ;
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] +$data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'] ;

            if(!$khac){
                $data[4]['nhucau'] = $model->nhucau;
                $data[4]['nguonkp'] = $model->nguonkp;
                $data[4]['tietkiem'] = $model->tietkiem;
                $data[4]['hocphi'] = $model->hocphi;
                $data[4]['vienphi'] = $model->vienphi;
                $data[4]['nguonthu'] = $model->nguonthu;
            }

            return view('reports.thongtu67.donvi.mau4b')
                ->with('model',$model)
                ->with('data',$data)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
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
        $model = nguonkinhphi::findorfail($inputs['id']);
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = nguonkinhphi::find($id);
            $model->delete();
            return redirect('/du_toan/nguon_kinh_phi/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function getinfor_thongtu(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmthongtuquyetdinh::where('sohieu',$inputs['sohieu'])->first();
        die($model);
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
        $m_cb['tencanbo'] .= ' (nghỉ hưu)';
        for ($i_pc = 0; $i_pc < count($a_pc); $i_pc++) {
            $mapc = $a_pc[$i_pc]['mapc'];
            $m_cb['stbhxh_dv'] = 0;
            $m_cb['stbhyt_dv'] = 0;
            $m_cb['stkpcd_dv'] = 0;
            $m_cb['stbhtn_dv'] = 0;
            $m_cb['ttbh_dv'] = 0;
            $m_cb['tonghs'] = 0;
            $m_cb['luongtn'] = 0;
            $m_cb[$mapc] = 0;
            $m_cb['luongcoban'] = $luongcb;
        }
        return $m_cb;
    }

}
