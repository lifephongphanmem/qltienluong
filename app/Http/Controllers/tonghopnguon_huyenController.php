<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\nguonkinhphi_tinh;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopnguon_huyenController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            $model_nguon = nguonkinhphi_huyen::where('macqcq', $madv)->where('trangthai','DAGUI')->get();
            $model_nguon_tinh = nguonkinhphi_tinh::where('madv', $madv)->get();
            //$model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            $model = dmthongtuquyetdinh::all();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $soluong = $model_donvi->count();

            foreach($model as $dv){
                $nguon_huyen = $model_nguon_tinh->where('sohieu', $dv->sohieu)->first();
                if(count($nguon_huyen)>0){
                    //Đã tổng hợp dữ liệu
                    $dv->sldv = $soluong . '/' . $soluong;
                    $dv->masodv = $nguon_huyen->masodv;
                    $dv->trangthai = $nguon_huyen->trangthai;
                    //$dv->trangthai = 'DAGUI';
                }else{
                    //Chưa tổng hợp dữ liệu
                    $sl = $model_nguon->where('sohieu', $dv->sohieu)->count();
                    $dv->sldv = $sl . '/' . $soluong;
                    $dv->masodv = null;
                    if($sl==0){
                        $dv->trangthai = 'CHUADL';
                    }elseif($sl < $soluong){
                        $dv->trangthai = 'CHUADAYDU';
                    }else{
                        $dv->trangthai = 'CHUATAO';
                    }
                }
            }

            return view('functions.tonghopnguon.index')
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong',$soluong)
                ->with('furl_xem','/chuc_nang/xem_du_lieu/nguon/huyen')
                ->with('furl_th','/chuc_nang/tong_hop_nguon/huyen/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp nguồn kinh phí');

        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI

        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = nguonkinhphi_huyen::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            $madv = $model->madv;
            $phanloai = dmdonvi::where('madv', $madv)->first()->phanloaitaikhoan;

            if($phanloai == 'SD'){
                nguonkinhphi::where('sohieu', $model->sohieu)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo'=>$inputs['lydo']]);
            }else{
                nguonkinhphi_khoi::where('sohieu', $model->sohieu)->where('madv', $madv)
                    ->update(['trangthai' => 'TRALAI', 'lydo'=>$inputs['lydo']]);
            }

            return redirect('/chuc_nang/xem_du_lieu/nguon/huyen?sohieu=' . $model->sohieu . '&trangthai=ALL');
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
            $madv = session('admin')->madv;
            $model_nguon_huyen = nguonkinhphi_tinh::where('sohieu',$inputs['sohieu'])->where('madv', $madv)->first();
            //$model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])->where('macqcq', $madv)->get();
            if (count($model_nguon_huyen) > 0) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_huyen->trangthai = 'DAGUI';
                $model_nguon_huyen->nguoilap = session('admin')->name;
                $model_nguon_huyen->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_huyen->save();
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                nguonkinhphi::where('sohieu',$inputs['sohieu'])->where('macqcq', $madv)
                    ->update(['masot' => $inputs['masodv']]);

                //nguonkinhphi_huyen::create($inputs);
                nguonkinhphi_tinh::create($inputs);
            }
            return redirect('/chuc_nang/tong_hop_nguon/huyen/index');
        } else
            return view('errors.notlogin');
    }
}
