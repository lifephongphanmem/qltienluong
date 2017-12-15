<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\tonghop_tinh;
use App\tonghopluong_tinh;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class tonghopluong_tinhController extends Controller
{
    function index(Request $requests){
        if (Session::has('admin')) {
            $a_trangthai = getStatus();
            $inputs = $requests->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $tendb = getTenDb($madvbc);

            //lấy danh sách đơn vị quản lý khối
            $model_qlkhoi = dmdonvi::select('madv', 'tendv', DB::raw('"CAPDUOI" as phanloai'))
                ->wherein('madv', function($query) use($madvbc){
                    $query->select('macqcq')->from('dmdonvi')->where('madvbc',$madvbc)->distinct();
                })->get();

            //danh sách đơn vị gửi dữ liệu cho đơn vị quản lý khối và đơn vị quản lý khối.
            //chức năng chỉ dành cho đơn vị quản lý khu vực => $madvqlkv = $madv
            $model_donvi = dmdonvi::select('madv', 'tendv',DB::raw('"DONVI" as phanloai'))
                ->wherein('madv', function($query) use($madv){
                    $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)
                        ->orwhere('madv',$madv)->get();
                })->get();
            //Gộp danh sách đơn vị
            foreach($model_qlkhoi as $donvi){
                $model_donvi->add($donvi);
            }
            //dd($model_donvi);
            $sldv = $model_donvi->count();

            $a_data=array(array('thang'=>'01','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'02','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'03','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'04','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'05','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'06','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'07','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'08','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'09','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'10','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'11','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0),
                array('thang'=>'12','mathdv'=>null,'noidung'=>null,'sldv'=>$sldv,'dvgui'=>0)
            );

            //Lấy danh sách các dữ liệu đã tổng hợp theo huyện
            $model_tonghop = tonghop_tinh::where('madvbc',$madvbc)->get();
            //Danh sách các đơn vị đã gửi dữ liệu
            $model_dulieu = tonghopluong_tinh::where('madvbc',$madvbc)->get();

            for($i=0;$i<count($a_data);$i++){
                //$a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $tonghop = $model_tonghop->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam'])->first();
                $dulieu = $model_dulieu->where('thang',$a_data[$i]['thang'])->where('nam',$inputs['nam']);
                //Kiểm tra xem đơn vị đã tổng hợp dữ liệu khối chưa
                if(count($tonghop)>0){//lấy dữ liệu đã tổng hợp đưa ra kết quản
                    $a_data[$i]['noidung']=$tonghop->noidung;
                    $a_data[$i]['mathdv']=$tonghop->mathdv;
                    $a_data[$i]['trangthai']=$tonghop->trangthai;
                    $a_data[$i]['dvgui'] = count($dulieu);
                }else{//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp trên địa bàn '.$tendb.' tháng '.$a_data[$i]['thang'].' năm '.$inputs['nam'];
                    $a_data[$i]['mathdv'] = null;

                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    if(count($dulieu) == 0){//chưa gửi
                        $a_data[$i]['trangthai']='CHUADL';

                    }elseif(count($dulieu)== $sldv){
                        //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUAGUI';
                        $a_data[$i]['dvgui'] = $sldv;
                    }else{
                        $a_data[$i]['trangthai'] = 'CHUADAYDU';
                        $a_data[$i]['dvgui'] = count($dulieu);
                    }
                }
            }
            //dd($a_data);
            return view('functions.tonghopluong.tinh.index')
                ->with('furl','/chuc_nang/tong_hop_luong/tinh/')
                ->with('nam',$inputs['nam'])
                ->with('model',$a_data)
                ->with('a_trangthai',$a_trangthai)
                ->with('pageTitle','Danh sách tổng hợp lương toàn địa bàn');
        } else
            return view('errors.notlogin');
    }
}
