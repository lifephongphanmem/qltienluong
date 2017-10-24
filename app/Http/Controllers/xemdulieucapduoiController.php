<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\tonghopluong_donvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class xemdulieucapduoiController extends Controller
{
    public function donvi_luong(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            $madv = session('admin')->madv;
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $a_trangthai=array('ALL'=>'--Chọn trạng thái dữ liệu--','CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu');
            $list_donvi= dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)->get();

            if(!isset($inputs['trangthai']) || $inputs['trangthai']=='ALL'){
                $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')->where('macqcq', $madv)->get();
            }else{
                $trangthai = $inputs['trangthai'];

                switch($trangthai){
                    case 'DAGUI':{
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $trangthai, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai',$trangthai)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();
                        break;
                    }
                    default :{
                       //Đơn vị đã tổng hợp dữ liệu nhưng chưa gửi
                        $model_donvi = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->wherein('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('trangthai','<>','DAGUI')
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        //Đơn vi chưa tổng hợp dữ liệu
                        $model_donvi_chuatao = dmdonvi::select('madv', 'tendv','macqcq','maphanloai')
                            ->where('macqcq',$madv)
                            ->wherenotin('madv', function($query) use ($madv, $thang, $nam){
                                $query->select('madv')->from('tonghopluong_donvi')
                                    ->where('macqcq',$madv)
                                    ->where('thang', $thang)
                                    ->where('nam', $nam);
                            })->get();

                        foreach($model_donvi_chuatao as $donvi){
                            $model_donvi->add($donvi);
                        }

                        break;
                    }
                }
            }

            foreach($model_donvi as $dv){
                $donvi = $list_donvi->where('madv',$dv->macqcq)->first();
                $dv->tendvcq =(isset($donvi)?$donvi->tendv:NULL);
                $model_bangluong = tonghopluong_donvi::where('madv', $dv->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->where('trangthai', 'DAGUI')
                    ->first();

                $dv->mathdv = (isset($model_bangluong)?$model_bangluong->mathdv:NULL);
            }
            //dd($model_donvi->toarray());
            return view('functions.viewdata.index')
                ->with('model', $model_donvi)
                ->with('thang', $inputs['thang'])
                ->with('nam', $inputs['nam'])
                ->with('trangthai', $inputs['trangthai'])
                ->with('a_trangthai', $a_trangthai)
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }
}
