<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dutoanluong;
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

    function create(Request $request){
        //Lấy số liệu chi tra lương toàn đơn vị trong năm
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            //Lấy số liệu 1 tháng * 12
            $model_luong = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr)use($thang, $nam){
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('thang',$thang)
                    ->where('nam',$nam)
                    ->where('madv',session('admin')->madv);
            })->get();
            $luongnb_dt = 0;
            $luonghs_dt = 0;
            $luongbh_dt = 0;
            foreach($model_luong as $luong){
                $luongnb_dt += $luong->luongcoban *($luong->heso + $luong->hesopc);
                $luonghs_dt += $luong->luongcoban *($luong->tonghs - $luong->heso);
                $luongbh_dt += $luong->stbhxh_dv +$luong->stbhyt_dv + $luong->stkpcd_dv + $luong->stbhtn_dv;
            }
            $inputs['luongnb_dt']= $luongnb_dt * 12;
            $inputs['luonghs_dt']= $luonghs_dt * 12;
            $inputs['luongbh_dt']= $luongbh_dt * 12;

            //Lấy số liệu chi trả lương của năm đó
            $namthucte = $inputs['namdt'] - 1;
            $model_luong_thucte = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr)use($namthucte){
                $qr->select('mathdv')->from('tonghopluong_donvi')->where('nam',$namthucte)
                    ->where('madv',session('admin')->madv);
            })->get();
            $luongnb = 0;
            $luonghs = 0;
            $luongbh = 0;
            foreach($model_luong_thucte as $luong){
                $luongnb += $luong->luongcoban *($luong->heso + $luong->hesopc);
                $luonghs += $luong->luongcoban *($luong->tonghs - $luong->heso);
                $luongbh += $luong->stbhxh_dv +$luong->stbhyt_dv + $luong->stkpcd_dv + $luong->stbhtn_dv;
            }
            $inputs['luongnb']= $luongnb;
            $inputs['luonghs']= $luonghs;
            $inputs['luongbh']= $luongbh;
            $inputs['madv']=session('admin')->madv;
            $inputs['namns'] = $inputs['namdt'];
            dutoanluong::create($inputs);

            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
