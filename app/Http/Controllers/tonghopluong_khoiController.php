<?php

namespace App\Http\Controllers;

use App\dmdiabandbkk;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphongban;
use App\dmphucap;
use App\dmphucap_donvi;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_huyen_diaban;
use App\tonghopluong_khoi;
use App\tonghopluong_khoi_chitiet;
use App\tonghopluong_khoi_diaban;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\tonghopluong_tinh;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class tonghopluong_khoiController extends Controller
{
    public function soluongdv($thang,$nam)
    {
        $ngay = date("Y-m-t", strtotime($nam.'-'.$thang.'-01'));
        $madv = session('admin')->madv;
        $model_donvi = dmdonvi::select('*')
            ->where('macqcq', $madv)
            ->where('madv', '<>', $madv)
            ->wherenotin('madv', function ($query) use ($madv,$thang,$nam,$ngay) {
                $query->select('madv')->from('dmdonvi')
                    // ->where('ngaydung', '<=', $ngay)
                    ->whereMonth('ngaydung', '<=', $ngay)
                    ->whereYear('ngaydung', '<=', $ngay)
                    ->where('trangthai', 'TD')
                    ->get();
            })
            ->get();
        $kq = $model_donvi->count();
        return $kq;
    }
    function index(Request $requests)
    {
        if (Session::has('admin')) {
            //chỉ có 4 trạng thái CHUAGUI: chưa có dữ liệu từ đơn vi cấp dưới
            //CHUADAYDU: có đơn vị cấp dưới chưa gửi dữ liệu
            //DAGUI: đã gửi lên đơn vị cấp trên
            //TRALAI: đã bị gửi trả lại
            $inputs = $requests->all();
            $madv = session('admin')->madv;
            $madvbc = session('admin')->madvbc;
            $tendv = getTenDV($madv);
            //$sldvcapduoi = dmdonvi::where('macqcq', $madv)->count();
            $a_data = array(array('thang' => '01', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('1',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '02', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('2',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '03', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('3',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '04', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('4',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '05', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('5',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '06', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('6',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '07', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('7',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '08', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('8',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '09', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('9',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '10', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('10',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '11', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('11',$inputs['nam']), 'dvgui' => 0),
                            array('thang' => '12', 'mathdv' => null, 'noidung' => null, 'trangthai' => 'CHUADL', 'sldv' => $this->soluongdv('12',$inputs['nam']), 'dvgui' => 0)
            );
            /*
            $a_trangthai=array('CHUAGUI'=>'Chưa gửi tổng hợp dữ liệu','CHUADAYDU'=>'Chưa đầy đủ tổng hợp dữ liệu','CHUATAO'=>'Chưa tổng hợp dữ liệu'
                ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            */
            $a_trangthai = getStatus();

            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            /*
            $model_donvi = tonghopluong_donvi::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->distinct();
            })->get();
            */
            $model_donvi = tonghopluong_donvi::where('macqcq', $madv)
                ->where('trangthai', 'DAGUI')->get();
            //Lấy danh sách các dữ liệu đã tổng hợp theo khối
            $model_khoi = tonghopluong_khoi::where('madv', $madv)->get();
            // $model_tonghop = tonghopluong_tinh::where('madvbc', $madvbc)->where('nam', $inputs['nam'])->get();
            for ($i = 0; $i < count($a_data); $i++) {
                $a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $tonghop = $model_khoi->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam'])->first();
                // dd($tonghop);
                $thang = $a_data[$i]['thang'];
                $nam = $inputs['nam'];
                //if(session('admin')->phamvitonghop == 'KHOI'){
                    $dulieu =  tonghopluong_donvi::wherein('madv', function($query) use($madv,$thang,$nam){
                        $query->select('madv')->from('tonghopluong_donvi')->where('macqcq',$madv)->where('madv','<>',$madv)
                            ->where('thang', $thang)->where('nam', $nam)->where('trangthai','DAGUI')->get();
                    })->where('trangthai', 'DAGUI')
                        ->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam'])
                        ->get();

                    //$dulieu = $model_donvi->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam']);
                    // $dulieukhoi = tonghopluong_khoi::wherein('madv', function($query) use($madv,$thang,$nam){
                    //     $query->select('madv')->from('tonghopluong_khoi')->where('macqcq',$madv)->where('madv','<>',$madv)
                    //         ->where('thang', $thang)->where('nam', $nam)->where('trangthai','DAGUI')->get();
                    // })->where('trangthai', 'DAGUI')
                    //     ->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam'])
                    //     ->get();
               //}
                //dd(count($tonghop));
                //Kiểm tra xem đơn vị đã tổng hợp dữ liệu khối chưa
                if ($tonghop != null) {//lấy dữ liệu đã tổng hợp đưa ra kết quả
                    $a_data[$i]['noidung'] = $tonghop->noidung;
                    $a_data[$i]['mathdv'] = $tonghop->mathdv;
                    $a_data[$i]['trangthai'] = $tonghop->trangthai;
                    //$a_data[$i]['dvgui'] = $sldvcapduoi;
                    //if(session('admin')->phamvitonghop == 'KHOI')
                    // $a_data[$i]['dvgui'] = count($dulieu) + count($dulieukhoi);
                    $a_data[$i]['dvgui'] = count($dulieu) ;
                    $a_data[$i]['ngaylap'] = $tonghop->ngaylap;

                } else {//chưa tổng hợp dữ liệu
                    $a_data[$i]['noidung'] = 'Đơn vị ' . $tendv . ' tổng hợp dữ liệu từ các đơn vị cấp dưới thời điểm ' . $a_data[$i]['thang'] . '/' . $inputs['nam'];
                    $a_data[$i]['ngaylap'] = null;
                    //Kiểm tra xem đơn vị cấp dưới đã gửi dữ liệu khối chưa
                    // if (count($dulieu)+ count($dulieukhoi)== 0) {//chưa gửi
                        if (count($dulieu)== 0) {//chưa gửi
                        $a_data[$i]['trangthai'] = 'CHUADL';
                    // } elseif (count($dulieu)+ count($dulieukhoi) == $a_data[$i]['sldv']) { //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                    } elseif (count($dulieu) == $a_data[$i]['sldv']) { //kiểm tra xem có bao nhiêu đơn vị gửi / tổng số các đơn vị
                        $a_data[$i]['trangthai'] = 'CHUAGUI';
                        $a_data[$i]['dvgui'] = $a_data[$i]['sldv'];
                    } else {
                        // $a_data[$i]['dvgui'] = count($dulieu)+ count($dulieukhoi);
                        $a_data[$i]['dvgui'] = count($dulieu);
                        $a_data[$i]['trangthai'] = 'CHUADAYDU';
                    }
                }
            }
            //dd($a_data);
            return view('functions.tonghopluong.khoi.index')
                ->with('furl', '/chuc_nang/tong_hop_luong/khoi/')
                ->with('nam', $inputs['nam'])
                ->with('model', $a_data)
                ->with('a_trangthai', $a_trangthai)
                ->with('pageTitle', 'Danh sách tổng hợp lương từ đơn vị cấp dưới');
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;

            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->get();
            //dd($model_tonghop->toarray());
            $model_plth = dmdonvi::join('tonghopluong_donvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('maphanloai','mathdv')
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('tonghopluong_donvi.trangthai', 'DAGUI')->get();
            $a_plth = array_column($model_plth->toarray(),'maphanloai','mathdv');
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');

            //$model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
            ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
            ->select('maphanloai','mact',DB::raw('SUM(soluong) as soluong'),DB::raw('SUM(heso) as heso'),DB::raw('SUM(hesobl) as hesobl'),
                DB::raw('SUM(hesopc) as hesopc'),DB::raw('SUM(hesott) as hesott'),DB::raw('SUM(vuotkhung) as vuotkhung'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcct) as pcct'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pckct) as pckct'),DB::raw('SUM(tonghopluong_donvi_chitiet.pck) as pck'),DB::raw('SUM(tonghopluong_donvi_chitiet.pccv) as pccv'),DB::raw('SUM(tonghopluong_donvi_chitiet.pckv) as pckv'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pcth) as pcth'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdd) as pcdd'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdh) as pcdh'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcld) as pcld'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pcdbqh) as pcdbqh'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcudn) as pcudn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctn) as pctn'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pctnn) as pctnn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdbn) as pcdbn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcvk) as pcvk'), DB::raw('SUM(tonghopluong_donvi_chitiet.pckn) as pckn'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pcdang) as pcdang'), DB::raw('SUM(tonghopluong_donvi_chitiet.pccovu) as pccovu'), DB::raw('SUM(tonghopluong_donvi_chitiet.pclt) as pclt'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcd) as pcd'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pctr) as pctr'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctdt) as pctdt'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctnvk) as pctnvk'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.pcbdhdcu) as pcbdhdcu'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcthni) as pcthni') ,'tonghopluong_donvi_chitiet.tonghs', 'tonghopluong_donvi_chitiet.giaml',
                DB::raw('SUM(tonghopluong_donvi_chitiet.luongtn) as luongtn'), DB::raw('SUM(tonghopluong_donvi_chitiet.stbhxh_dv) as stbhxh_dv'), DB::raw('SUM(tonghopluong_donvi_chitiet.stbhyt_dv) as stbhyt_dv'),DB::raw('SUM(tonghopluong_donvi_chitiet.stkpcd_dv) as stkpcd_dv'),
                DB::raw('SUM(tonghopluong_donvi_chitiet.stbhtn_dv) as stbhtn_dv'), DB::raw('SUM(tonghopluong_donvi_chitiet.ttbh_dv) as ttbh_dv'))
            ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mact','maphanloai')
                ->orderby('maphanloai')
                ->get();
            //dd($model->toarray());
            $m_pl = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->join('dmphanloaict','dmphanloaict.mact','tonghopluong_donvi_chitiet.mact')
                ->select('maphanloai','tenct','dmphanloaict.mact')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->orderby('maphanloai')
                ->distinct()
                ->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($m_pl as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
            }
            foreach ($model as $chitiet) {
                //$chitiet->madv = $a_dv[$chitiet->mathdv];
                //$chitiet->maphanloai = $a_plth[$chitiet->mathdv];
                //$chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->luongtn - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = $ct;
                    $chitiet->$ma = $chitiet->$ct ;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });
            //dd($model->toarray());
            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($model_phanloai->toarray());
            return view('reports.tonghopluong.khoi.solieuth')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('m_pl', $m_pl)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }
    function tonghop_khoi(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thangbc'];
            $nam = $inputs['nambc'];
            $madv = $inputs['madv'];
            $checkdv = dmdonvi::where('madv',$madv)->where('phanloaitaikhoan','TH')->get();
            if(count($checkdv) > 0) {
                $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            }
            else{
                $model_donvi = dmdonvi::where('madv',$madv)->get();
            }

            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;

            if(count($checkdv) > 0) {
                $model_tonghop = tonghopluong_donvi::where('macqcq', $madv)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')->get();
            }
            else{
                $model_tonghop = tonghopluong_donvi::where('madv', $madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')->get();
            }
            $model_plth = dmdonvi::join('tonghopluong_donvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('maphanloai','mathdv')
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('tonghopluong_donvi.trangthai', 'DAGUI')->get();
            $a_plth = array_column($model_plth->toarray(),'maphanloai','mathdv');
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');

            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('tonghopluong_donvi_chitiet.*','maphanloai')
                ->where('tonghopluong_donvi.trangthai','DAGUI')
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->get();
            /*
            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('maphanloai','mact',DB::raw('SUM(soluong) as soluong'),DB::raw('SUM(heso) as heso'),DB::raw('SUM(hesobl) as hesobl'),
                    DB::raw('SUM(hesopc) as hesopc'),DB::raw('SUM(hesott) as hesott'),DB::raw('SUM(vuotkhung) as vuotkhung'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcct) as pcct'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pckct) as pckct'),DB::raw('SUM(tonghopluong_donvi_chitiet.pck) as pck'),DB::raw('SUM(tonghopluong_donvi_chitiet.pccv) as pccv'),DB::raw('SUM(tonghopluong_donvi_chitiet.pckv) as pckv'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcth) as pcth'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdd) as pcdd'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdh) as pcdh'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcld) as pcld'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcdbqh) as pcdbqh'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcudn) as pcudn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctn) as pctn'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pctnn) as pctnn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdbn) as pcdbn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcvk) as pcvk'), DB::raw('SUM(tonghopluong_donvi_chitiet.pckn) as pckn'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcdang) as pcdang'), DB::raw('SUM(tonghopluong_donvi_chitiet.pccovu) as pccovu'), DB::raw('SUM(tonghopluong_donvi_chitiet.pclt) as pclt'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcd) as pcd'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pctr) as pctr'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctdt) as pctdt'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctnvk) as pctnvk'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcbdhdcu) as pcbdhdcu'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcthni) as pcthni') ,'tonghopluong_donvi_chitiet.tonghs', 'tonghopluong_donvi_chitiet.giaml',
                    DB::raw('SUM(tonghopluong_donvi_chitiet.luongtn) as luongtn'), DB::raw('SUM(tonghopluong_donvi_chitiet.stbhxh_dv) as stbhxh_dv'), DB::raw('SUM(tonghopluong_donvi_chitiet.stbhyt_dv) as stbhyt_dv'),DB::raw('SUM(tonghopluong_donvi_chitiet.stkpcd_dv) as stkpcd_dv'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.stbhtn_dv) as stbhtn_dv'), DB::raw('SUM(tonghopluong_donvi_chitiet.ttbh_dv) as ttbh_dv'))
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mact','maphanloai')
                ->orderby('maphanloai')
                ->get();

            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('maphanloai','mact','soluong','heso','hesobl',
                    'hesopc','hesott','vuotkhung',DB::raw('SUM(tonghopluong_donvi_chitiet.pcct) as pcct'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pckct) as pckct'),DB::raw('SUM(tonghopluong_donvi_chitiet.pck) as pck'),DB::raw('SUM(tonghopluong_donvi_chitiet.pccv) as pccv'),DB::raw('SUM(tonghopluong_donvi_chitiet.pckv) as pckv'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcth) as pcth'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdd) as pcdd'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdh) as pcdh'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcld) as pcld'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcdbqh) as pcdbqh'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcudn) as pcudn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctn) as pctn'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pctnn) as pctnn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcdbn) as pcdbn'), DB::raw('SUM(tonghopluong_donvi_chitiet.pcvk) as pcvk'), DB::raw('SUM(tonghopluong_donvi_chitiet.pckn) as pckn'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcdang) as pcdang'), DB::raw('SUM(tonghopluong_donvi_chitiet.pccovu) as pccovu'), DB::raw('SUM(tonghopluong_donvi_chitiet.pclt) as pclt'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcd) as pcd'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pctr) as pctr'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctdt) as pctdt'), DB::raw('SUM(tonghopluong_donvi_chitiet.pctnvk) as pctnvk'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.pcbdhdcu) as pcbdhdcu'),DB::raw('SUM(tonghopluong_donvi_chitiet.pcthni) as pcthni') ,'tonghopluong_donvi_chitiet.tonghs', 'tonghopluong_donvi_chitiet.giaml',
                    DB::raw('SUM(tonghopluong_donvi_chitiet.luongtn) as luongtn'), DB::raw('SUM(tonghopluong_donvi_chitiet.stbhxh_dv) as stbhxh_dv'), DB::raw('SUM(tonghopluong_donvi_chitiet.stbhyt_dv) as stbhyt_dv'),DB::raw('SUM(tonghopluong_donvi_chitiet.stkpcd_dv) as stkpcd_dv'),
                    DB::raw('SUM(tonghopluong_donvi_chitiet.stbhtn_dv) as stbhtn_dv'), DB::raw('SUM(tonghopluong_donvi_chitiet.ttbh_dv) as ttbh_dv'))
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mact','maphanloai')
                ->orderby('maphanloai')
                ->get();
            */
            $m_pl = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->join('dmphanloaict','dmphanloaict.mact','tonghopluong_donvi_chitiet.mact')
                ->select('maphanloai','tenct','dmphanloaict.mact')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->orderby('maphanloai')
                ->distinct()
                ->get();
            /*
            $m_pl = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->join('dmphanloaict','dmphanloaict.mact','tonghopluong_donvi_chitiet.mact')
                ->select('maphanloai','tenct','dmphanloaict.mact','manguonkp')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->orderby('maphanloai')
                ->distinct()
                ->get();
            */
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($m_pl as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
            }
            foreach ($model as $chitiet) {
                //$chitiet->madv = $a_dv[$chitiet->mathdv];
                //$chitiet->maphanloai = $a_plth[$chitiet->mathdv];
                //$chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->luongtn - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = $ct;
                    //$chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
                    $chitiet->$ma = $chitiet->$ct;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });
            //dd($model->toarray());
            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            if(isset($inputs['excelbc'])){
                Excel::create('solieuth',function($excel) use($model,$thongtin,$m_dv,$inputs,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$col,$a_phucap,$m_pl){
                    $excel->sheet('New sheet', function($sheet) use($model,$thongtin,$m_dv,$inputs,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$col,$a_phucap,$m_pl){
                        $sheet->loadView('reports.tonghopluong.khoi.solieuth')
                            ->with('thongtin', $thongtin)
                            ->with('model', $model)
                            ->with('model_tonghop', $model_tonghop)
                            ->with('model_phanloai', $model_phanloai)
                            ->with('model_donvi', $model_donvi)
                            ->with('a_soluong', $a_soluong)
                            ->with('m_dv', $m_dv)
                            ->with('col', $col)
                            ->with('a_phucap', $a_phucap)
                            ->with('m_pl', $m_pl)
                            ->with('pageTitle','solieuth');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
                return view('reports.tonghopluong.khoi.solieuth')
                    ->with('thongtin', $thongtin)
                    ->with('model', $model)
                    ->with('model_tonghop', $model_tonghop)
                    ->with('model_phanloai', $model_phanloai)
                    ->with('model_donvi', $model_donvi)
                    ->with('a_soluong', $a_soluong)
                    ->with('m_dv', $m_dv)
                    ->with('col', $col)
                    ->with('a_phucap', $a_phucap)
                    ->with('m_pl', $m_pl)
                    ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');
            }

        } else
            return view('errors.notlogin');
    }
    function tonghop_cu(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')->get();
            $model_plth = dmdonvi::join('tonghopluong_donvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('maphanloai','mathdv')
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')->get();
            $a_plth = array_column($model_plth->toarray(),'maphanloai','mathdv');
            //dd($a_plth);

            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            //$model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();

            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->join('dmdonvi','dmdonvi.madv','tonghopluong_donvi.madv')
                ->select('maphanloai','mact','manguonkp','luongcoban','soluong','heso','hesobl','hesopc','hesott','vuotkhung','tonghopluong_donvi_chitiet.pcct'
                    , 'tonghopluong_donvi_chitiet.pckct', 'tonghopluong_donvi_chitiet.pck', 'tonghopluong_donvi_chitiet.pccv', 'tonghopluong_donvi_chitiet.pckv', 'tonghopluong_donvi_chitiet.pcth', 'tonghopluong_donvi_chitiet.pcdd', 'tonghopluong_donvi_chitiet.pcdh', 'tonghopluong_donvi_chitiet.pcld', 'tonghopluong_donvi_chitiet.pcdbqh', 'tonghopluong_donvi_chitiet.pcudn', 'tonghopluong_donvi_chitiet.pctn',
                    'tonghopluong_donvi_chitiet.pctnn', 'tonghopluong_donvi_chitiet.pcdbn', 'tonghopluong_donvi_chitiet.pcvk', 'tonghopluong_donvi_chitiet.pckn', 'tonghopluong_donvi_chitiet.pcdang', 'tonghopluong_donvi_chitiet.pccovu', 'tonghopluong_donvi_chitiet.pclt', 'tonghopluong_donvi_chitiet.pcd', 'tonghopluong_donvi_chitiet.pctr', 'tonghopluong_donvi_chitiet.pctdt', 'tonghopluong_donvi_chitiet.pctnvk',
                    'tonghopluong_donvi_chitiet.pcbdhdcu', 'tonghopluong_donvi_chitiet.pcthni', 'tonghopluong_donvi_chitiet.tonghs', 'tonghopluong_donvi_chitiet.giaml', 'tonghopluong_donvi_chitiet.luongtn', 'tonghopluong_donvi_chitiet.stbhxh_dv', 'tonghopluong_donvi_chitiet.stbhyt_dv', 'tonghopluong_donvi_chitiet.stkpcd_dv',
                    'tonghopluong_donvi_chitiet.stbhtn_dv', 'tonghopluong_donvi_chitiet.ttbh_dv')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('maphanloai','mact','luongcoban','manguonkp')
                ->get();

            //dd($model->toarray());

            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                //$chitiet->madv = $a_dv[$chitiet->mathdv];
                //$chitiet->maphanloai = $a_plth[$chitiet->mathdv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->luongtn - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct ;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });

            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($a_phucap);
            return view('reports.tonghopluong.khoi.solieuth')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function tonghop_diaban(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;

            $model_diaban_ct = tonghopluong_donvi_diaban::wherein('mathdv',function($query) use($nam, $thang, $madv){
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam',$nam)
                    ->where('thang',$thang)
                    ->where('trangthai','DAGUI')
                    ->where('macqcq',$madv)->distinct();
            })->get();
            //
            //Tính toán dữ liệu
            $a_col = getColTongHop();

            //Tính toán theo địa bàn
            $model_diaban = $model_diaban_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);

            for($i=0;$i<count($model_diaban);$i++){
                $luongct = $model_diaban_ct->where('madiaban',$model_diaban[$i]['madiaban']);
                $tonghs = 0;

                foreach($a_col as $col){
                    $model_diaban[$i][$col] = $luongct->sum($col);
                    $tonghs += chkDbl($model_diaban[$i][$col]);
                }

                $model_diaban[$i]['stbhxh_dv']=$luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv']=$luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv']=$luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv']=$luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs'] = $tonghs;
                $model_diaban[$i]['tongbh'] = $model_diaban[$i]['stbhxh_dv'] + $model_diaban[$i]['stbhyt_dv'] + $model_diaban[$i]['stkpcd_dv'] + $model_diaban[$i]['stbhtn_dv'];
            }
            //

            $model_db = array();
            $dm_diaban = dmdiabandbkk::all();
            $a_diaban = getDiaBan(false);
            for($i=0;$i<count($model_diaban);$i++){
                $diaban = $dm_diaban->where('madiaban',$model_diaban[$i]['madiaban'])->first();
                $model_diaban[$i]['tendiaban'] = $diaban->tendiaban;
                $model_diaban[$i]['phanloai'] = $a_diaban[$diaban->phanloai];

                if($model_diaban[$i]['madiaban'] != null){
                    $model_db[] = $model_diaban[$i];
                }
            }

            $m_dv = dmdonvi::where('madv',$madv)->first();

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$thang,
                'nam'=>$nam);
            return view('reports.tonghopluong.khoi.solieudiaban')
                ->with('thongtin',$thongtin)
                ->with('model',$model_db)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function tonghop_chuadaydu(Request $requests)
    {
        if (Session::has('admin')) {
            return $this->tonghop($requests);
        } else
            return view('errors.notlogin');
    }

    function detail($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_khoi_chitiet::where('mathdv', $mathdv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            //$gnr=getGeneralConfigs();

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                $chitiet->tongtl = $chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
//dd($model);
            return view('functions.tonghopluong.templates.detail')
                ->with('furl', '/chuc_nang/tong_hop_luong/khoi/')
                ->with('model', $model)
                ->with('model_thongtin', $model_thongtin)
                ->with('pageTitle', 'Chi tiết dữ liệu tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function edit_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = tonghopluong_khoi_chitiet::where('mathdv',$inputs['mathdv'])
                ->where('manguonkp',$inputs['manguonkp'])
                ->where('macongtac',$inputs['macongtac'])->first();

            $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_detail')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                //->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_khoi_chitiet::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);

            foreach(array_keys($inputs) as $key){
                if(!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/khoi/detail/ma_so='.$model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_khoi_diaban::where('mathdv',$mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv',session('admin')->madv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();

            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl= $chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh=$chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.templates.detail_diaban')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                ->with('model_thongtin',$model_thongtin)
                ->with('pageTitle','Chi tiết tổng hợp lương theo địa bàn');
        } else
            return view('errors.notlogin');
    }

    function edit_detail_diaban(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_diaban =array_column( dmdiabandbkk::where('madv',session('admin')->madv)->get()->toarray(),'tendiaban','madiaban');
            $model = tonghopluong_khoi_diaban::where('mathdv',$inputs['mathdv'])
                ->where('madiaban',$inputs['madiaban'])->first();

            $model->ttbh_dv=$model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_diaban')
                ->with('furl','/chuc_nang/tong_hop_luong/khoi/')
                ->with('model',$model)
                ->with('a_diaban',$a_diaban)
                ->with('pageTitle','Chi tiết tổng hợp lương theo địa bàn');
        } else
            return view('errors.notlogin');
    }

    function store_detail_diaban(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_khoi_diaban::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);

            foreach(array_keys($inputs) as $key){
                if(!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/khoi/detail_diaban/ma_so='.$model->mathdv);
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
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $madv = session('admin')->madv;
            $model = tonghopluong_khoi::where('nam', $nam)->where('thang', $thang)->where('madv', $madv)->first();


            if ($model != null) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model->trangthai = 'DAGUI';
                $model->nguoilap = session('admin')->name;
                $model->ngaylap = Carbon::now()->toDateTimeString();
                $model->save();
                //cập nhật cả bảng huyện khi gửi tạo đồng thời 2 bảng
                tonghopluong_huyen::where('nam', $nam)->where('thang', $thang)->where('madv', $madv)
                    ->update(['trangthai' => 'DAGUI', 'nguoilap' => session('admin')->name,'ngaylap'=> Carbon::now()->toDateTimeString()]);
                tonghopluong_donvi::where('nam', $nam)->where('thang', $thang)->where('macqcq', $madv)
                    ->update(['mathk' => $model->mathdv, 'mathh' => $model->mathdv]);
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['mathdv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['phanloai'] = 'CAPDUOI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới thời điểm ' . $inputs['thang'] . '/' . $inputs['nam'];
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                tonghopluong_donvi::where('nam', $nam)->where('thang', $thang)->where('macqcq', $madv)
                    ->update(['mathk' => $inputs['mathdv'], 'mathh' => $inputs['mathdv']]);

                tonghopluong_donvi_chitiet::wherein('mathdv', function ($query) use ($nam, $thang, $madv) {
                    $query->select('mathdv')->from('tonghopluong_donvi')->where('nam', $nam)->where('thang', $thang)->where('macqcq', $madv)->distinct();
                })->update(['mathk' => $inputs['mathdv'], 'mathh' => $inputs['mathdv']]);

                /*
                //$model_tonghop_ct->update(['mathk'=>$inputs['mathdv']]);
                tonghopluong_donvi_diaban::wherein('mathdv', function ($query) use ($nam, $thang, $madv) {
                    $query->select('mathdv')->from('tonghopluong_donvi')->where('nam', $nam)->where('thang', $thang)->where('macqcq', $madv)->distinct();
                })->update(['mathk' => $inputs['mathdv'], 'mathh' => $inputs['mathdv']]);
                */
                tonghopluong_khoi::create($inputs);
                tonghopluong_huyen::create($inputs);
            }
            return redirect('/chuc_nang/tong_hop_luong/khoi/index?nam=' . $nam);
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();
            $model->trangthai = 'TRALAI';
            $model->mathk = null;
            $model->mathh = null;
            $model->matht = null;
            $model->lydo = $inputs['lydo'];
            $model->save();
            return redirect('/chuc_nang/xem_du_lieu/khoi?thang='.$model->thang.'&nam=' . $model->nam . '&trangthai=ALL&phanloai=ALL');
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv){
        /*
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = tonghopluong_khoi_chitiet::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(),'tennguonkp','manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(),'tencongtac','macongtac');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv',$model_thongtin->madv)->first();

            foreach($model as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$model_thongtin->thang,
                'nam'=>$model_thongtin->nam);

            return view('reports.tonghopluong.khoi.solieutonghop')
                ->with('thongtin',$thongtin)
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
        */
        if (Session::has('admin')) {
            //dd($mathdv);
            $check = tonghopluong_khoi::join('dmdonvi','dmdonvi.madv','tonghopluong_khoi.madv')
                ->where('mathdv',$mathdv)->first();
            if($check != null) {
                $model = tonghopluong_donvi_chitiet::where('mathh', $mathdv)->get();
                $model_thongtin = tonghopluong_donvi::where('mathh', $mathdv)->first();
                $a_bangluong = tonghopluong_donvi_bangluong::where('mathh', $mathdv)->get()->toarray();
                $m_dv = dmdonvi:: join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')
                    ->where('mathdv',$mathdv)->first();
                $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathdv){
                    $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathdv)
                        ->get();
                })->get()->toarray(),'report','mapc');
            }
            else {
                $model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
                $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
                $a_bangluong = tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->get()->toarray();
                $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
                $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
            }
            //dd($model);
            //$dsdonvi = dmdonvi:: join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathdv)->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            //$model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                /*
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                */
                $chitiet->tongtl = $chitiet->luongtn;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                /*
                $phucap = a_getelement_equal($a_bangluong, array('mact'=>$chitiet->mact,'manguonkp'=>$chitiet->manguonkp,'tonghop'=>$chitiet->tonghop));
                //$m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
                if($chitiet->tonghop != 'TRUYLINH'){
                    foreach (getColTongHop() as $ct) {
                        $ma = 'hs'.$ct;
                        $chitiet->$ma = array_sum(array_column($phucap,$ct));
                    }
                }
                */
            }

            $a_phucap = array();
            //$a_phucap_hs = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    //$a_phucap_hs['hs'.$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            $a_tonghop = $model->map(function($data){
                return collect($data->toArray())
                    ->only(['tonghop'])
                    ->all();
            });
            //dd($model);
            return view('reports.tonghopluong.donvi.solieutonghop')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_tonghop',a_unique($a_tonghop))
                ->with('a_phucap', $a_phucap)
                //->with('a_phucap_hs', $a_phucap_hs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function printf_data_diaban($mathdv){
        if (Session::has('admin')) {
            $model = tonghopluong_khoi_diaban::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_khoi::where('mathdv',$mathdv)->first();
            $model_diaban = dmdiabandbkk::all();//nên lọc đia bàn
            $a_diaban = array('DBKK'=>'Khu vực KTXH ĐBKK','BGHD'=>'Khu vực biên giới, hải đảo',
                'DBTD'=>'Khu vực trọng điểm, phức tạp về an ninh trật tự');
            //$gnr=getGeneralConfigs();

            foreach($model as $chitiet){
                $diaban = $model_diaban->where('madiaban',$chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl = $chitiet->luongcoban * $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv=dmdonvi::where('madv',$model_thongtin->madv)->first();

            $thongtin=array('nguoilap'=>session('admin')->name,
                'thang'=>$model_thongtin->thang,
                'nam'=>$model_thongtin->nam);

            return view('reports.tonghopluong.donvi.solieudiaban')
                ->with('thongtin',$thongtin)
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function getlydo(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        $model = tonghopluong_donvi::select('lydo')->where('mathk',$inputs['mathdv'])->first();

        die($model);
    }

    function printf_bl_khoi(Request $requests)
    {
        if (Session::has('admin')) {
            $input = $requests->all();
            $mathdv = $input['mathdv'];
            $madv = $input['madv'];
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            /*
            $model = tonghopluong_donvi_bangluong::wherein('mathdv',function($query) use($mathdv,$madv){
                $query->select('mathdv')->from('tonghopluong_donvi')->where('mathh',$mathdv)->where('madv',$madv)->get();
            })->get();
            */
            $model = tonghopluong_donvi_bangluong::where('mathdv',$mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->where('madv',$madv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            //$m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
            $m_pc = getColTongHop();

            foreach ($model as $chitiet) {
                if(!isset($model_nguonkp[$chitiet->manguonkp]))
                    $chitiet->manguonkp = 12;
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                $thanhtien = 0;
                foreach (getColTongHop() as $ct) {
                    if ($chitiet->$ct > 50000) {
                        $thanhtien +=  $chitiet->$ct;
                    }
                }
                if($chitiet->ttl == 0){//trường hop dinh mức ko nhân dc với hệ số
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }else{
                    $chitiet->tongtl = $chitiet->ttl;
                }
            }
            //dd($model->toarray());

            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($a_phucap);
            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            //Lấy dữ liệu để lập
            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact','manguonkp','tennguonkp','tenct', 'tonghop'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $a_congtac = a_unique($model_congtac);

            $model_nguon = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['manguonkp', 'tennguonkp', 'tonghop'])
                    ->all();
            });
            $a_nguon = a_unique($model_nguon);
            //dd($a_nguon);
            //mới thêm
            $a_tonghop = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['tonghop'])
                    ->all();
            });
            //dd($a_tonghop);
            //dd($model);
            if(isset($input['excelbc'])){
                Excel::create('bangluongct',function($excel) use($model,$thongtin,$m_dv,$a_nguon,$a_congtac,$a_tonghop,$col,$a_phucap){
                    $excel->sheet('New sheet', function($sheet) use($model,$thongtin,$m_dv,$a_nguon,$a_congtac,$a_tonghop,$col,$a_phucap){
                        $sheet->loadView('reports.tonghopluong.khoi.bangluongct')
                            ->with('thongtin', $thongtin)
                            ->with('model', $model)
                            ->with('m_dv', $m_dv)
                            ->with('col', $col)
                            ->with('a_phucap', $a_phucap)
                            ->with('a_nguon', $a_nguon)
                            ->with('a_congtac', $a_congtac)
                            ->with('a_tonghop',a_unique($a_tonghop))
                            ->with('pageTitle','solieuth');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }else{
                return view('reports.tonghopluong.khoi.bangluongct')
                    ->with('thongtin', $thongtin)
                    ->with('model', $model)
                    ->with('m_dv', $m_dv)
                    ->with('col', $col)
                    ->with('a_phucap', $a_phucap)
                    ->with('a_nguon', $a_nguon)
                    ->with('a_congtac', $a_congtac)
                    ->with('a_tonghop',a_unique($a_tonghop))
                    ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
            }


        } else
            return view('errors.notlogin');
    }

    public function inkhoito(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = $inputs['madv'];
            $mathdv = $inputs['mathdv'];
            $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                ->select('tonghopluong_donvi_bangluong.*','thang')
                ->where('tonghopluong_donvi.mathdv', $mathdv)->where('madv',$madv)->get();

            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->where('madv',$madv)->first();
            $m_bl = tonghopluong_donvi::select('thang', 'nam', 'madv', 'ngaylap', 'phanloai')->where('mathdv', $mathdv)->first();
            $m_dv = dmdonvi::where('madv', $madv)->first();
            $model_pb = dmphongban::select('mapb', 'tenpb')
                ->where('madv',$inputs['madv'])
                ->wherein('mapb', a_unique(array_column($model->toarray(),'mapb')))->get();
            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' =>10
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            $a_ct = $model->map(function($data){
                return collect($data->toArray())
                    ->only(['mact'])
                    ->all();
            });

            $a_ct = a_unique($a_ct);
            $model_ct = dmphanloaict::wherein('mact',$a_ct)->get();
            return view('reports.tonghopluong.khoi.mautt107_pb')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_pb', $model_pb)
                ->with('a_phucap', $a_phucap)
                ->with('model_ct', $model_ct)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function tonghop_khoito(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = $inputs['madv'];
            $mathdv = $inputs['mathdv'];
            $check = tonghopluong_khoi::join('dmdonvi','dmdonvi.madv','tonghopluong_khoi.madv')
                ->where('mathdv',$mathdv)->first();
            if($check != null) {
                //$model = tonghopluong_donvi_chitiet::where('mathh', $mathdv)->get();
                $model_th = tonghopluong_donvi::where('mathh', $mathdv)->get();
                $model_thongtin = tonghopluong_donvi::where('mathh', $mathdv)->first();
                $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                    ->select('tonghopluong_donvi_bangluong.*','thang')
                    ->where('tonghopluong_donvi.mathh', $mathdv)->get();
                $m_dv = dmdonvi:: join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')
                    ->where('mathdv',$mathdv)->first();
                $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathdv){
                    $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathdv)
                        ->get();
                })->get()->toarray(),'report','mapc');
            }
            else {
                //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
                $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
                $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                    ->select('tonghopluong_donvi_bangluong.*','thang')
                    ->where('tonghopluong_donvi.mathdv', $mathdv)->get();
                $a_bangluong = tonghopluong_donvi_bangluong::where('mathdv', $mathdv)->get()->toarray();
                $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
                $m_pc = array_column(dmphucap_donvi::where('madv', $model_thongtin->madv)->get()->toarray(),'report','mapc');
            }
            $model_pb = dmphongban::select('mapb', 'tenpb')
                ->where('madv',$inputs['madv'])
                ->wherein('mapb', a_unique(array_column($model->toarray(),'mapb')))->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                $chitiet->tongtl = $chitiet->luongtn;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //$a_col = getColTongHop();
            $a_phucap = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            $a_tonghop = $model->map(function($data){
                return collect($data->toArray())
                    ->only(['tonghop'])
                    ->all();
            });

            //dd($model_nguon->toarray());
            return view('reports.tonghopluong.khoi.solieuth_pb')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_tonghop',a_unique($a_tonghop))
                ->with('a_phucap', $a_phucap)
                ->with('model_nguonkp', $model_nguonkp)
                ->with('model_ct', $model_ct)
                ->with('model_pb', $model_pb)
                //->with('a_phucap_hs', $a_phucap_hs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }
}
