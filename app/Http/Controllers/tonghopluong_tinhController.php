<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\tonghop_tinh;
use App\tonghopluong_huyen;
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

    public function soluongdv($thang,$nam,$madv)
    {
        $ngay = date("Y-m-t", strtotime($nam.'-'.$thang.'-01'));
        $model_donvi = dmdonvi::select('madv', 'tendv')
            ->where('macqcq', $madv)
            ->where('madv', '<>', $madv)
            ->wherenotin('madv', function ($query) use ($madv,$thang,$nam,$ngay) {
                $query->select('madv')->from('dmdonvi')
                    ->where('ngaydung', '<=', $ngay)
                    ->where('trangthai', 'TD')
                    ->get();
            })->get();
        $kq = $model_donvi->count();
        return $kq;
    }
    public function soluongdvgui($thang,$nam,$madv)
    {
        $model = tonghopluong_huyen::wherein('madv', function($query) use($madv){
            $query->select('madv')->from('dmdonvi')->where('macqcq',$madv)->where('madv','<>',$madv)->get();
        })->where('trangthai', 'DAGUI')
            ->where('thang',$thang)->where('nam', $nam)
            ->get();
        $kq = $model->count();
        return $kq;
    }
    function danhsachdv (Request $request)
    {
        $input = $request->all();
        $model = dmdonvibaocao::select('madvbc','tendvbc','madvcq')
        ->where('level','H')->where('madvbc','<>','1563585711')->get();
        $a_data = $model->toarray();
        for ($i = 0; $i < count($a_data); $i++) {
            $a_data[$i]['01'] = $this->soluongdvgui('01',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('1',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['02'] = $this->soluongdvgui('02',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('2',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['03'] = $this->soluongdvgui('03',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('3',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['04'] = $this->soluongdvgui('04',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('4',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['05'] = $this->soluongdvgui('05',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('5',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['06'] = $this->soluongdvgui('06',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('6',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['07'] = $this->soluongdvgui('07',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('7',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['08'] = $this->soluongdvgui('08',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('8',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['09'] = $this->soluongdvgui('09',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('9',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['10'] = $this->soluongdvgui('10',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('10',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['11'] = $this->soluongdvgui('11',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('11',$input['namth'],$a_data[$i]['madvcq']);
            $a_data[$i]['12'] = $this->soluongdvgui('12',$input['namth'],$a_data[$i]['madvcq'])."/".$this->soluongdv('12',$input['namth'],$a_data[$i]['madvcq']);
        }
        //dd($a_data);
        return view('reports.tonghopluong.tinh.danhsachth')
            ->with('a_data', $a_data)
            ->with('nam', $input['namth'])
            ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
    }

}
