<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaidonvi;
use App\dmthongtuquyetdinh;
use App\dutoanluong;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use App\dutoanluong_tinh;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class xemdulieu_dutoanController extends Controller
{
    public function index_khoi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');

            $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai','phanloaitaikhoan')
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
                })->get();
            $model_nguon = dutoanluong::wherein('madv', function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
            })->get();

            $model_nguon_khoi = dutoanluong_khoi::where('madv', $madv)->get();
            $model_tonghopkhoi = dutoanluong_khoi::where('macqcq', $madv)
                ->where('trangthai', 'DAGUI')->get();

            foreach($model_donvi as $dv){
                //kiểm tra xem đã tổng hợp thành dữ liệu khối  gửi lên huyện chưa?
                $nguon_khoi = $model_nguon_khoi->where('namns',$inputs['namns'])->first();


                if(count($nguon_khoi)>0 && $nguon_khoi->trangthai == 'DAGUI'){
                    $dv->tralai = false;
                }else{
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('namns',$inputs['namns'])->where('madv',$dv->madv)->first();
                $khoi = $model_tonghopkhoi->where('namns',$inputs['namns'])->first();
                if(count($nguon)> 0 && $nguon->trangthai == 'DAGUI'){
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                }elseif(count($khoi)> 0 && $khoi->trangthai == 'DAGUI'){
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                }
                else{
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai',$inputs['trangthai']);
            }

            return view('functions.viewdata.dutoanluong.index')
                ->with('model', $model_donvi)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('furl_th', 'chuc_nang/du_toan_luong/khoi/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/du_toan/khoi')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp dự toán lương');

        } else
            return view('errors.notlogin');
    }

    public function index_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $nam = $inputs['namns'];
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $model_donvi = dmdonvi::select('madv', 'tendv','maphanloai')
                ->where('macqcq',$madv)->where('madv','<>',$madv)
                ->wherenotin('madv', function ($query) use ($madv,$nam) {
                    $query->select('madv')->from('dmdonvi')
                        ->whereyear('ngaydung', '<=', $nam)
                        ->where('trangthai', 'TD')
                        ->get();
                })->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $model_phanloai = array_column($model_phanloai->toarray(),'tenphanloai','maphanloai');
            foreach($model_phanloai as $key=>$key)
                $a_phanloai[$key]= $model_phanloai[$key];
            $a_phanloai['ALL'] = '--Chọn tất cả--';

            $model_nguon = dutoanluong_huyen::wherein('madv', function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
            })->get();

            $model_nguon_khoi = dutoanluong_tinh::where('madv', $madv)->get();

            foreach($model_donvi as $dv){
                //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
                $nguon_khoi = $model_nguon_khoi->where('namns',$inputs['namns'])->first();
                if(isset($nguon_khoi) && $nguon_khoi->trangthai == 'DAGUI'){
                    $dv->tralai = false;
                }else{
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('namns',$inputs['namns'])->where('madv',$dv->madv)->first();
                if(isset($nguon)  && $nguon->trangthai == 'DAGUI'){
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                }else{
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai',$inputs['trangthai']);
            }
            if (!isset($inputs['phanloai']) || $inputs['phanloai'] != 'ALL') {
                $model_donvi = $model_donvi->where('maphanloai',$inputs['phanloai']);
            }

            return view('functions.viewdata.dutoanluong.huyen.index')
                ->with('model', $model_donvi)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('a_phanloai', $a_phanloai)
                ->with('furl_th', 'chuc_nang/du_toan_luong/huyen/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/du_toan/huyen')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function danhsach(Request $request){

        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $nam = $inputs['namnds'];
            $a_trangthai = array('CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $model_donvi = dmdonvi::select('madv', 'tendv','maphanloai')
                ->where('macqcq',$madv)->where('madv','<>',$madv)
                ->wherenotin('madv', function ($query) use ($madv,$nam) {
                    $query->select('madv')->from('dmdonvi')
                        ->whereyear('ngaydung', '<=', $nam)
                        ->where('trangthai', 'TD')
                        ->get();
                })->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $model_phanloai = array_column($model_phanloai->toarray(),'tenphanloai','maphanloai');
            foreach($model_phanloai as $key=>$key)
                $a_phanloai[$key]= $model_phanloai[$key];
            $a_phanloai['ALL'] = '--Chọn tất cả--';

            $model_nguon = dutoanluong_huyen::wherein('madv', function($query) use($madv){
                $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
            })->get();

            $model_nguon_khoi = dutoanluong_tinh::where('madv', $madv)->get();

            foreach($model_donvi as $dv){
                //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
                $nguon_khoi = $model_nguon_khoi->where('namns',$inputs['namnds'])->first();
                if(count($nguon_khoi)>0 && $nguon_khoi->trangthai == 'DAGUI'){
                    $dv->tralai = false;
                }else{
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('namns',$inputs['namnds'])->where('madv',$dv->madv)->first();
                if(count($nguon)> 0 && $nguon->trangthai == 'DAGUI'){
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                }else{
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthaids']) || $inputs['trangthaids'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai',$inputs['trangthaids']);
            }

            $m_dv = dmdonvi::where('madv',$madv)->first();
            if(isset($inputs['excel'])){
                Excel::create('THluong', function ($excel) use ($model_donvi, $a_trangthai, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($model_donvi, $a_trangthai, $m_dv, $inputs) {
                        $sheet->loadView('reports.dutoanluong.Huyen.danhsach')
                            ->with('model', $model_donvi)
                            ->with('a_trangthai', $a_trangthai)
                            ->with('m_dv', $m_dv)
                            ->with('furl', '/chuc_nang/tong_hop_luong/')
                            ->with('pageTitle', 'THluong');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
                return view('reports.dutoanluong.Huyen.danhsach')
                    ->with('model', $model_donvi)
                    ->with('a_trangthai', $a_trangthai)
                    ->with('m_dv', $m_dv)
                    ->with('furl', '/chuc_nang/tong_hop_luong/')
                    ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
            }


        } else
            return view('errors.notlogin');
    }
}
