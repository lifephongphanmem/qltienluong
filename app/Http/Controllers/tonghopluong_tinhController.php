<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\tonghop_tinh;
use App\tonghopluong_huyen;
use App\tonghopluong_tinh;
use App\tonghopluong_donvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class tonghopluong_tinhController extends Controller
{
    public function soluongdv($thang, $nam, $madv)
    {
        $ngay = date("Y-m-t", strtotime($nam . '-' . $thang . '-01'));
        $model_donvi = dmdonvi::select('madv', 'tendv')
            ->where('macqcq', $madv)
            ->where('madv', '<>', $madv)
            ->wherenotin('madv', function ($query) use ($madv, $thang, $nam, $ngay) {
                $query->select('madv')->from('dmdonvi')
                    ->where('ngaydung', '<=', $ngay)
                    ->where('trangthai', 'TD')
                    ->get();
            })->get();
        $kq = $model_donvi->count();
        return $kq;
    }
    public function soluongdvgui($thang, $nam, $madv)
    {
        $model = tonghopluong_huyen::wherein('madv', function ($query) use ($madv) {
            $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
        })->where('trangthai', 'DAGUI')
            ->where('thang', $thang)->where('nam', $nam)
            ->get();
        $kq = $model->count();
        return $kq;
    }

    function danhsachdv(Request $request)
    {
        $input = $request->all();
        $model = dmdonvibaocao::select('madvbc', 'tendvbc', 'madvcq')
            ->where('level', 'H')->where('madvbc', '<>', '1563585711')->get();
        $a_data = $model->toarray();
        for ($i = 0; $i < count($a_data); $i++) {
            $a_data[$i]['01'] = $this->soluongdvgui('01', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('1', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['02'] = $this->soluongdvgui('02', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('2', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['03'] = $this->soluongdvgui('03', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('3', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['04'] = $this->soluongdvgui('04', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('4', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['05'] = $this->soluongdvgui('05', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('5', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['06'] = $this->soluongdvgui('06', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('6', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['07'] = $this->soluongdvgui('07', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('7', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['08'] = $this->soluongdvgui('08', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('8', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['09'] = $this->soluongdvgui('09', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('9', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['10'] = $this->soluongdvgui('10', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('10', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['11'] = $this->soluongdvgui('11', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('11', $input['namth'], $a_data[$i]['madvcq']);
            $a_data[$i]['12'] = $this->soluongdvgui('12', $input['namth'], $a_data[$i]['madvcq']) . "/" . $this->soluongdv('12', $input['namth'], $a_data[$i]['madvcq']);
        }
        //dd($a_data);
        return view('reports.tonghopluong.tinh.danhsachth')
            ->with('a_data', $a_data)
            ->with('nam', $input['namth'])
            ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
    }

    public function index(Request $request)
    {
        if (Session::has('admin')) {

            $a_trangthai = getStatus();
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $tendb = getTenDb($madvbc);
            $inputs['namns'] = $inputs['namns'] ?? date('Y');

            // //Lấy danh sách các dữ liệu đã tổng hợp theo huyện
            // $model_tonghop = tonghop_tinh::where('madvbc', $madvbc)->get();
            // //Danh sách các đơn vị đã gửi dữ liệu
            // $model_dulieu = tonghopluong_tinh::where('madvbc', $madvbc)->get();
            $model = dmdonvibaocao::where('level', 'H')->orderby('sapxep')->get();
            foreach ($model as $val) {
                //Lấy danh sách các dữ liệu đã tổng hợp theo huyện
                $model_tonghop = tonghop_tinh::where('madvbc', $val->madvbc)->where('thang',$inputs['thang'])->where('nam',$inputs['nam'])->first();
                //Danh sách các đơn vị đã gửi dữ liệu
                $model_dulieu = tonghopluong_tinh::where('madvbc',  $val->madvbc)->where('thang',$inputs['thang'])->where('nam',$inputs['nam'])->first();

                if(isset($model_dulieu)){
                    $val->trangthai= $model_dulieu->trangthai;
                    $val->madv= $model_dulieu->madv;
                    $val->mathdv= $model_dulieu->mathdv;
                }else{
                    $val->trangthai= 'CHOGUI';
                }

            }
            //dd($model);
            $inputs['furl_huyen'] = '/chuc_nang/tong_hop_luong/huyen/';
            $inputs['furl'] = '/chuc_nang/tong_hop_luong/tinh/';
            return view('functions.tonghopluong.tinh.index_new')               
                ->with('inputs', $inputs)              
                ->with('model', $model)
                ->with('a_trangthai', $a_trangthai)
                ->with('pageTitle', 'Danh sách tổng hợp lương toàn địa bàn');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //B1 
            $model = tonghopluong_tinh::where('mathdv', $inputs['mathdv'])->where('thang',$inputs['thang'])->where('nam',$inputs['nam'])->first();


            //xóa tonghopluong_tinh
            //update lai truong trang thai tonghopluong_huyen
            $model_huyen=tonghopluong_huyen::where('madvbc', $model->madvbc)->where('thang',$inputs['thang'])->where('nam',$inputs['nam'])
                            ->get();
            $model_dv=tonghopluong_donvi::where('madvbc', $model->madvbc)->where('thang',$inputs['thang'])->where('nam',$inputs['nam'])
                                            ->get();
            foreach($model_huyen as $val){
                $val->update(['trangthai'=>'TRALAI','lydo'=>$inputs['lydo']]);
            }
            foreach($model_dv as $val){
                $val->update(['matht'=>null]);
            }
            $model->delete();

            return redirect('/chuc_nang/tong_hop_luong/tinh/index?thang='.$inputs['thang'].'&nam='.$inputs['nam']);
        } else
        return view('errors.notlogin');
    }
}
