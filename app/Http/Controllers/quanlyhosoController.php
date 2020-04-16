<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\hosocanbo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class quanlyhosoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $a_sunghiep=array('ALL'=>'--Chọn sự nghiệp cán bộ--','Công chức'=>'Công chức','Viên chức'=>'Viên chức','Khác'=>'Khác');
            //$a_plcanbo=array('ALL'=>'','Tập sự'=>'Tập sự, thử việc','Chính thức'=>'Chính thức');
            $sunghiep = $inputs['sunghiep'];
            $madv = $inputs['madv'];
            $m_dv = dmdonvi::where('dmdonvi.macqcq', session('admin')->madv)->get();
            //$m_hs=hosocanbo::where('madv',session('admin')->maxa)->get();
            $m_hs = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv','dmdonvi.madv')
                ->where('theodoi','<' ,'9')
                ->where('sunghiep','like',$inputs['sunghiep'].'%')
                ->where('dmdonvi.macqcq', session('admin')->madv)
                ->orderby('tendv')
                ->get();
            if($inputs['sunghiep'] == 'ALL')
            $m_hs = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv','dmdonvi.madv')
                ->where('theodoi','<' ,'9')
                ->where('dmdonvi.macqcq', session('admin')->madv)
                ->orderby('tendv')
                ->get();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            foreach($m_hs as $hs){
                $hs->tenpb = isset($a_pb[$hs->mapb])?$a_pb[$hs->mapb] : '';
                $hs->tencvcq = isset($a_cv[$hs->macvcq])?$a_cv[$hs->macvcq] : '';
                $hs->tenct = isset($a_ct[$hs->mact])?$a_ct[$hs->mact] : '';
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();

            $model = $m_hs;
            if($inputs['madv'] != 'ALL')
                $model = $model->where('madv',$inputs['madv']);
            return view('manage.danhsachhoso.index')
                ->with('model',$model)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('a_sunghiep',$a_sunghiep)
                ->with('sunghiep',$sunghiep)
                ->with('madv',$madv)
                ->with('m_donvi',$m_dv)
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }
    function index_nangluong(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $a_nangluong=array('NB'=>'Nâng lương ngạch bậc','TNN'=>'Nâng lương thâm niên nghề');
            $a_donvi=dmdonvi::select('madv','tendv','phanloaitaikhoan')
                ->where('macqcq', session('admin')->madv)
                ->get();
            $nangluong = $inputs['nangluong'];

            $donvi = $inputs['madv'];

            $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv','heso')
                ->where('theodoi','<' ,'9')
                ->where('hosocanbo.madv',$inputs['madv'])
                ->where('dmdonvi.macqcq', session('admin')->madv)
                ->orderby('tendv')
                ->get();

            $check = $a_donvi->where('madv',$donvi)->first();
            if(isset($check) && $check->phanloaitaikhoan == 'TH'){
                $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                    ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv','heso')
                    ->where('theodoi','<' ,'9')
                    ->where('dmdonvi.macqcq',$inputs['madv'])
                    ->orderby('tendv')
                    ->get();
            }
                if($inputs['madv'] == 'ALL')
                    $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                        ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv','heso')
                        ->where('theodoi','<' ,'9')
                        ->where('dmdonvi.macqcq', session('admin')->madv)
                        ->orderby('tendv')
                        ->get();
            $date = getdate();
            foreach ($model as $ct) {
                if (isset($ct->ngayden)) {
                    $dt_luong = date_create($ct->ngayden);
                    $ct->nam_luong = date_format($dt_luong, 'Y');
                    $ct->ngaynangluong = $dt_luong->format('Y-m-d');
                } else {
                    $ct->nam_luong = null;
                    $ct->ngaynangluong = null;
                }

                if (isset($ct->tnndenngay)) {
                    $dt_luong = date_create($ct->tnndenngay);
                    $ct->nam_nghe = date_format($dt_luong, 'Y');
                    //$ct->ngaynangluong = $dt_luong->format('Y-m-d');
                } else {
                    $ct->nam_nghe = null;
                    //$ct->ngaynangluong = null;
                }
            }

            if($nangluong == 'NB')
                $m_hs = $model->where('nam_luong', $date['year']);
            if($nangluong == 'TNN')
                $m_hs = $model->where('nam_nghe', $date['year']);

            return view('manage.danhsachhoso.index_nangluong')
                ->with('m_hs',$m_hs)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('a_donvi',$a_donvi)
                ->with('donvi',$donvi)
                ->with('nangluong',$nangluong)
                ->with('a_nangluong',$a_nangluong)
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }
    function index_nghihuu(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $a_donvi=dmdonvi::select('madv','tendv')
                ->where('macqcq', session('admin')->madv)
                ->get();
            $donvi = $inputs['madv'];
            $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv')
                ->where('theodoi','<' ,'9')
                ->where('hosocanbo.madv',$inputs['madv'])
                ->where('dmdonvi.macqcq', session('admin')->madv)
                ->orderby('tendv')
                ->get();
            if($inputs['madv'] == 'ALL')
                $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                    ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv')
                    ->where('theodoi','<' ,'9')
                    ->where('dmdonvi.macqcq', session('admin')->madv)
                    ->orderby('tendv')
                    ->get();
            $date = getdate();
            $gen = getGeneralConfigs();
            foreach ($model as $ct) {
                $dt = date_create($ct->ngaysinh);

                $ct->thang = date_format($dt, 'm');
                $ct->nam = $ct->gioitinh == 'Nam' ? date_format($dt, 'Y') + $gen['tuoinam'] : date_format($dt, 'Y') + $gen['tuoinu'];
                if(isset($ct->ngaysinh)){
                    $ct->ngaynghi = $dt->modify(' +'.($ct->gioitinh == 'Nam' ?$gen['tuoinam']: $gen['tuoinu'] ).' year')->format('Y-m-d');;
                }
            }
            $m_hs = $model->where('nam','<=' ,$date['year']);
            return view('manage.danhsachhoso.index_nghihuu')
                ->with('m_hs',$m_hs)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('a_donvi',$a_donvi)
                ->with('donvi',$donvi)
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }
    function innangluong_th(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $a_nangluong=array('NB'=>'Nâng lương ngạch bậc','TNN'=>'Nâng lương thâm niên nghề');
            $a_donvi=dmdonvi::select('madv','tendv','phanloaitaikhoan')
                ->where('macqcq', session('admin')->madv)
                ->get();
            $nangluong = $inputs['nangluong'];

            $donvi = $inputs['madv'];
            $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv')
                ->where('theodoi','<' ,'9')
                ->where('hosocanbo.madv',$inputs['madv'])
                ->where('dmdonvi.macqcq', session('admin')->madv)
                ->orderby('tendv')
                ->get();
            $check = $a_donvi->where('madv',$donvi)->first();
            if(isset($check) && $check->phanloaitaikhoan == 'TH'){
                $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                    ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv','heso')
                    ->where('theodoi','<' ,'9')
                    ->where('dmdonvi.macqcq',$inputs['madv'])
                    ->orderby('tendv')
                    ->get();
            }
            if($inputs['madv'] == 'ALL')
                $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                    ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv')
                    ->where('theodoi','<' ,'9')
                    ->where('dmdonvi.macqcq', session('admin')->madv)
                    ->orderby('tendv')
                    ->get();
            $date = getdate();
            foreach ($model as $ct) {
                if (isset($ct->ngayden)) {
                    $dt_luong = date_create($ct->ngayden);
                    $ct->nam_luong = date_format($dt_luong, 'Y');
                    $ct->ngaynangluong = $dt_luong->format('Y-m-d');
                } else {
                    $ct->nam_luong = null;
                    $ct->ngaynangluong = null;
                }

                if (isset($ct->tnndenngay)) {
                    $dt_luong = date_create($ct->tnndenngay);
                    $ct->nam_nghe = date_format($dt_luong, 'Y');
                    //$ct->ngaynangluong = $dt_luong->format('Y-m-d');
                } else {
                    $ct->nam_nghe = null;
                    //$ct->ngaynangluong = null;
                }
            }
            $phanloai = "";
            if($nangluong == 'NB'){
                $m_hs = $model->where('nam_luong', $date['year']);
                $phanloai = "NGẠCH BẬC";
            }
            if($nangluong == 'TNN'){
                $m_hs = $model->where('nam_nghe', $date['year']);
                $phanloai = "THÂM NIÊN NGHỀ";
            }
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            return view('reports.quanlyhoso.danhsach')
                ->with('m_hs',$m_hs)
                ->with('m_dv',$m_dv)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('a_donvi',$a_donvi)
                ->with('donvi',$donvi)
                ->with('nangluong',$nangluong)
                ->with('phanloai',$phanloai)
                ->with('a_nangluong',$a_nangluong)
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }
    function innghihuu_th(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $a_donvi=dmdonvi::select('madv','tendv')
                ->where('macqcq', session('admin')->madv)
                ->get();
            $donvi = $inputs['madv'];
            $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv')
                ->where('theodoi','<' ,'9')
                ->where('hosocanbo.madv',$inputs['madv'])
                ->where('dmdonvi.macqcq', session('admin')->madv)
                ->orderby('tendv')
                ->get();
            if($inputs['madv'] == 'ALL')
                $model = hosocanbo::join('dmdonvi','hosocanbo.madv','dmdonvi.madv')
                    ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden','ngaysinh','mact','tendv')
                    ->where('theodoi','<' ,'9')
                    ->where('dmdonvi.macqcq', session('admin')->madv)
                    ->orderby('tendv')
                    ->get();
            $date = getdate();
            $gen = getGeneralConfigs();
            foreach ($model as $ct) {
                $dt = date_create($ct->ngaysinh);

                $ct->thang = date_format($dt, 'm');
                $ct->nam = $ct->gioitinh == 'Nam' ? date_format($dt, 'Y') + $gen['tuoinam'] : date_format($dt, 'Y') + $gen['tuoinu'];
                if(isset($ct->ngaysinh)){
                    $ct->ngaynghi = $dt->modify(' +'.($ct->gioitinh == 'Nam' ?$gen['tuoinam']: $gen['tuoinu'] ).' year')->format('Y-m-d');;
                }
            }
            $m_hs = $model->where('nam','<=' ,$date['year']);
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            return view('reports.quanlyhoso.nghihuu')
                ->with('m_hs',$m_hs)
                ->with('m_dv',$m_dv)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('a_donvi',$a_donvi)
                ->with('donvi',$donvi)
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
