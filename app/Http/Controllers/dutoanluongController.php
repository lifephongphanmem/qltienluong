<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmchucvucq;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\dutoanluong_chitiet;
use App\hosocanbo;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dutoanluongController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dutoanluong::where('madv',session('admin')->madv)->get();

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
        $model = dutoanluong::find($inputs['id']);
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
        //Lấy số liệu chi tra lương hiện tại của đơn vị trong năm x 12
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);

            //thiếu mã đơn vị, lương cơ bản
            $m_cb = hosocanbo::where('madv', session('admin')->madv)
                ->select('stt', 'macanbo', 'macongchuc', 'sunghiep', 'tencanbo', 'mact', 'lvhd', 'macvcq', 'mapb', 'msngbac', 'heso', 'hesopc', 'hesott', 'vuotkhung',
                    'pclt', 'pcdd', 'pck', 'pccv', 'pckv', 'pcth', 'pcdh', 'pcld', 'pcudn', 'pctn', 'pctnn', 'pcdbn', 'pcvk', 'pckn', 'pccovu', 'pcdbqh', 'pctnvk', 'pcbdhdcu', 'pcdang', 'pcthni', 'pcct')
                ->where('theodoi', '1')
                ->get();

            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
            $model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();
            $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->get();

            //quy hết về tiền để tính
            foreach ($m_cb as $cb) {
                $cb->macongtac = isset($a_congtac[$cb->mact]) ? $a_congtac[$cb->mact] : null;
                $cb->bhxh_dv = 0;
                $cb->bhyt_dv = 0;
                $cb->kpcd_dv = 0;
                $cb->bhtn_dv = 0;

                $phanloai = $model_phanloai->where('macongtac', $cb->macongtac)->first();
                if (count($phanloai) > 0) {
                    $cb->bhxh_dv = floatval($phanloai->bhxh_dv) / 100;
                    $cb->bhyt_dv = floatval($phanloai->bhyt_dv) / 100;
                    $cb->kpcd_dv = floatval($phanloai->kpcd_dv) / 100;
                    $cb->bhtn_dv = floatval($phanloai->bhtn_dv) / 100;
                }

                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                $cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                foreach ($model_phucap as $ct) {
                    $mapc = $ct->mapc;

                    $ct->stbhxh_dv = 0;
                    $ct->stbhyt_dv = 0;
                    $ct->stkpcd_dv = 0;
                    $ct->stbhtn_dv = 0;
                    $sotien = 0;

                    switch (getDbl($ct->phanloai)) {
                        case 0: {//hệ số
                            $sotien = $cb->$mapc * $inputs['luongcoban'];
                            break;
                        }
                        case 1: {//số tiền
                            $sotien = $cb->$mapc;
                            break;
                        }
                        case 2: {//phần trăm
                            if ($mapc != 'vuotkhung') {//vượt khung đã tính ở trên
                                $heso = 0;
                                foreach (explode(',', $ct->congthuc) as $cthuc) {
                                    if ($cthuc != '')
                                        $heso += $cb->$cthuc;
                                }
                                $cb->$mapc = ($heso * $cb->$mapc / 100);
                                $sotien = $cb->$mapc * $inputs['luongcoban'];
                            }else{//vượt khung =>hê số * lương co bản
                                $sotien = $cb->$mapc * $inputs['luongcoban'];
                            }
                            break;
                        }
                        default: {//trường hợp còn lại (ẩn,...)
                            $cb->$mapc = 0;
                            break;
                        }
                    }
                    //đang nhầm số tiền nhân lương co bản 2 lần

                    $ct->sotien = $sotien;
                    if ($ct->baohiem == 1) {
                        $ct->stbhxh_dv = $sotien * $cb->bhxh_dv;
                        $ct->stbhyt_dv = $sotien * $cb->bhyt_dv;
                        $ct->stkpcd_dv = $sotien * $cb->kpcd_dv;
                        $ct->stbhtn_dv = $sotien * $cb->bhtn_dv;
                        $ct->ttbh_dv = $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                    }

                }
                //tính toán xong mới nhân số tiền ko nhầm công thức
                $cb->heso = $cb->heso * $inputs['luongcoban'];
                $cb->vuotkhung* $inputs['luongcoban'];
                $cb->tonghs = $model_phucap->sum('sotien') - $cb->heso - $cb->vuotkhung;

                $cb->stbhxh_dv = $model_phucap->sum('stbhxh_dv');
                $cb->stbhyt_dv = $model_phucap->sum('stbhyt_dv');
                $cb->stkpcd_dv = $model_phucap->sum('stkpcd_dv');
                $cb->stbhtn_dv = $model_phucap->sum('stbhtn_dv');
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;

            }

            //Lấy dữ liệu để lập
            $m_data = $m_cb->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac'])
                    ->all();
            });
            $m_data = a_unique($m_data);

            $masodv = session('admin')->madv . '_' . getdate()[0];
            $luongnb = 0;
            $luonghs = 0;
            $luongbh = 0;

            for ($i = 0; $i < count($m_data); $i++) {
                $canbo = $m_cb->where('macongtac', $m_data[$i]['macongtac']);
                $m_data[$i]['masodv'] = $masodv;
                $soluong = count($canbo);
                $m_data[$i]['canbo_congtac'] = $soluong;
                $m_data[$i]['canbo_dutoan'] = $soluong;
                if ($soluong) {
                    $m_data[$i]['luongnb_dt'] = round(($canbo->sum('heso') + $canbo->sum('vuotkhung')) * 12);
                    $luongnb += $m_data[$i]['luongnb_dt'];
                    $m_data[$i]['luonghs_dt'] = round($canbo->sum('tonghs') * 12);
                    $luonghs += $m_data[$i]['luonghs_dt'];
                    $m_data[$i]['luongbh_dt'] = round($canbo->sum('ttbh_dv') * 12);
                    $luongbh += $m_data[$i]['luongbh_dt'];

                    //lấy trung bình để khi tăng cán bộ thì tự nhân
                    $m_data[$i]['luongnb'] = round($m_data[$i]['luongnb_dt'] / $soluong);
                    $m_data[$i]['luonghs'] = round($m_data[$i]['luonghs_dt'] / $soluong);
                    $m_data[$i]['luongbh'] = round($m_data[$i]['luongbh_dt'] / $soluong);
                }
            }

            dutoanluong_chitiet::insert($m_data);

            $inputs['masodv'] = $masodv;
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

    function show(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $a_nhomct = getNhomCongTac(false);

            foreach($model as $ct){
                $ct->tencongtac = isset($a_nhomct[$ct->macongtac])? $a_nhomct[$ct->macongtac]:'';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt;
            }
            return view('manage.dutoanluong.detail')
                ->with('furl', '/nghiep_vu/quan_ly/du_toan/')
                ->with('model', $model)
                ->with('model_dutoan', $model_dutoan)
                ->with('pageTitle', 'Dự toán lương chi tiết');
        } else
            return view('errors.notlogin');
    }
}
