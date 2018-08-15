<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmthongtuquyetdinh;
use App\hosocanbo;
use App\nguonkinhphi;
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
                ->with('furl','/du_toan/nguon_kinh_phi/')
                ->with('a_trangthai',getStatus())
                ->with('model',$model)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function create(Request $request)
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
                return redirect('/du_toan/nguon_kinh_phi/ma_so='.$inputs['masodv']);
            }else{
                return view('errors.data_exist')
                    ->with('furl','/du_toan/nguon_kinh_phi/danh_sach');
            }

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
}
