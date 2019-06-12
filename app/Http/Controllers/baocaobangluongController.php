<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluongdangky;
use App\bangluongdangky_ct;
use App\chitieubienche;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dsnangluong;
use App\dsnangthamnien;
use App\dutoanluong;
use App\hosocanbo;
use App\ngachluong;
use App\tonghop_huyen;
use App\tonghop_huyen_chitiet;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_khoi;
use App\tonghopluong_khoi_chitiet;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController as data;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class baocaobangluongController extends Controller
{
    function index() {
        if (Session::has('admin')) {
            //$macqcq=session('admin')->madv;
            //$model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();
            //$model_dvbc=dmdonvibaocao::where('level','H')->get();
            return view('reports.index')
                ->with('furl','/bao_cao/bang_luong/')
                //->with('model_dv',$model_dv)
                //->with('model_dvbc', $model_dvbc)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function index_th() {
        if (Session::has('admin')) {
            //$macqcq=session('admin')->madv;
            //$model_dv=dmdonvi::where('macqcq',$macqcq)->orwhere('madv',$macqcq)->get();
            //$model_dvbc=dmdonvibaocao::where('level','H')->get();
            //$madvbc = session('admin')->madvbc;
            //$model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            //$model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();
            if(session('admin')->phamvitonghop = 'KHOI')
            {
                $model_donvi = dmdonvi::where('madvbc',session('admin')->madvbc)->get();
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
                $model_phanloaict = dmphanloaict::All();
            }
            if(session('admin')->phamvitonghop == 'HUYEN')
            {
                $model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',array_column($model_donvi->toarray(),'maphanloai'))->get();
                $model_phanloaict = dmphanloaict::All();
            }
            return view('reports.index_th')
                ->with('furl','/bao_cao/bang_luong/')
                ->with('model_phanloai',$model_phanloai)
                ->with('model_dv',$model_donvi)
                ->with('model_phanloaict',$model_phanloaict)
                //->with('model_dvbc', $model_dvbc)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function mauc02ahd(Request $request)
    {
        if (Session::has('admin')) {

            $inputs = $request->all();

            $model_bangluong = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])->first();

            $request['mabl_mau1'] = $model_bangluong->mabl;
            $request['mapb_mau1'] = '';
            $request['macvcq_mau1'] = '';
            $request['mact_mau1'] = '';
            $request['cochu'] = '10';

            if (!isset($model_bangluong)) {
                return view('errors.nodata');
            }
            $bl = new bangluongController();
            return $bl->printf_mau01($request);


        } else {
            return view('errors.notlogin');
        }

    }

    function mauc02ahd_mau2(Request $request){
        if (Session::has('admin')) {

            $inputs=$request->all();

            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();

            $request['mabl_mau2'] = $model_bangluong->mabl;
            $request['mapb_mau2'] = '';
            $request['macvcq_mau2'] = '';
            $request['mact_mau2'] = '';
            $request['cochu'] = '10';

            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }

            $bl=new bangluongController();
            return $bl->printf_mau02($request);


        } else{return view('errors.notlogin');}

    }

    function mauc02ahd_mau3(Request $request){
        if (Session::has('admin')) {

            $inputs=$request->all();

            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();
            $request['mabl_mau3'] = $model_bangluong->mabl;
            $request['mapb_mau3'] = '';
            $request['macvcq_mau3'] = '';
            $request['mact_mau3'] = '';
            $request['cochu'] = '10';
            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }

            $bl=new bangluongController();
            return $bl->printf_mau03($request);


        } else{return view('errors.notlogin');}

    }

    function mauc02x(Request $request){
        if (Session::has('admin')) {

            $inputs=$request->all();

            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();

            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }
            $bl=new bangluongController();
            return $bl->inbangluong($model_bangluong->mabl);


        } else{return view('errors.notlogin');}

    }

    function maubaohiem(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $model_bangluong = bangluong::where('madv',session('admin')->madv)
                ->where('thang',$inputs['thang'])
                ->where('nam',$inputs['nam'])->first();

            if(!isset($model_bangluong)){
                return view('errors.nodata');
            }
            $request['mabl_maubh'] = $model_bangluong->mabl;
            $request['mapb_maubh'] = '';
            $request['macvcq_maubh'] = '';
            $request['mact_maubh'] = '';
            $request['cochu'] = '10';

            $bl=new bangluongController();
            return $bl->printf_maubh($request);

        } else{return view('errors.notlogin');}

    }

    function mauc02ahd_th(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $macqcq = session('admin')->madv;
            $model_dv = dmdonvi::where('macqcq', $macqcq)->orwhere('madv', $macqcq)->get();

            $makpb = array();
            $mabl = array();
            foreach ($model_dv as $donvi) {
                $model_bangluong = bangluong::where('madv', $donvi->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])->first();

                $donvi->mabl = !isset($model_bangluong->mabl) ? NULL : $model_bangluong->mabl;
                $makpb[] = $donvi->makhoipb;
                $mabl[] = $donvi->mabl;
            }

            //$model_bangluong_ct = bangluong_ct::wherein('mabl', $mabl)->get();
            $model_bangluong_ct = (new data())->getBangluong_ct_ar($inputs['thang'],$mabl);
            $model_khoipb = dmkhoipb::wherein('makhoipb', $makpb)->get();
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();

            $dmchucvucq = dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach ($model_bangluong_ct as $hs) {
                $hs->tencv = getInfoChucVuCQ($hs, $dmchucvucq);
            }
            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $inputs['thang'],
                'nam' => $inputs['nam']);


            return view('reports.bangluong.maubangluong_tonghop')
                ->with('model_bangluong_ct', $model_bangluong_ct)
                ->with('model_kpb', $model_khoipb)
                ->with('thongtin', $thongtin)
                ->with('model_dv', $model_dv)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo bảng lương');
        } else
            return view('errors.notlogin');
    }

    function dangkyluong(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            //$dennam = $inputs['dennam'];

            $model_tonghop = bangluongdangky::whereBetween('thang', array($tuthang, $denthang))
                ->where('nam', $tunam)->where('madv', session('admin')->madv)->orderby('thang')->get();

            $model = bangluongdangky_ct::wherein('mabl', a_unique(array_column($model_tonghop->toarray(), 'mabl')))->get();
            $a_ct = array_column(dmphanloaict::wherein('mact',a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', session('admin')->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            $model_thang = $model_tonghop->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang','mabl'])
                    ->all();
            });
            //dd($model_thang);
            $model_thang = a_unique($model_thang);
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();

            return view('reports.mauchung.donvi.dangkyluong')
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_ct', $a_ct)
                ->with('model_thang', $model_thang)
                ->with('pageTitle', 'Tổng hợp đăng ký lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            //$dennam = $inputs['dennam'];

            $model_tonghop = tonghopluong_donvi::whereBetween('thang', array($tuthang,$denthang))
                ->where('nam',$tunam)
                ->where('madv',session('admin')->madv)->get();

            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr)use($tuthang,$denthang,$tunam){
                $qr->select('mathdv')->from('tonghopluong_donvi')->whereBetween('thang', array($tuthang,$denthang))
                    ->where('nam',$tunam)
                    ->where('madv',session('admin')->madv);
            }) ->get();

            $model_nguonkp = getNguonKP();
            $model_phanloaict = getNhomCongTac();
            foreach($model_tonghop_chitiet as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'tu'=>$inputs['tuthang'].'/'.$inputs['tunam'],
                'den'=>$inputs['denthang'].'/'.$inputs['tunam']);


            return view('reports.mauchung.donvi.chitraluong')
                ->with('model_tonghop',$model_tonghop)
                ->with('model_tonghop_chitiet',$model_tonghop_chitiet)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_th_khoi(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            //$dennam = $inputs['dennam'];
            //$model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();

            $model_tonghop = tonghopluong_khoi::whereBetween('thang', array($tuthang,$denthang))
                ->where('nam',$tunam)
                ->where('madv',session('admin')->madv)->get();

            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathk',function($qr)use($tuthang,$denthang,$tunam){
                $qr->select('mathdv')->from('tonghopluong_khoi')->whereBetween('thang', array($tuthang,$denthang))
                    ->where('nam',$tunam)
                    ->where('madv',session('admin')->madv);
            }) ->get();
            //dd($model_tonghop_chitiet);
            $model_nguonkp = getNguonKP();
            $model_phanloaict = getNhomCongTac();
            foreach($model_tonghop_chitiet as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //Lấy dữ liệu để lập
            $model_data = $model_tonghop_chitiet->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac','manguonkp','tennguonkp','tencongtac'])
                    ->all();
            });
            $model_data = a_unique($model_data);

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'tu'=>$inputs['tuthang'].'/'.$inputs['tunam'],
                'den'=>$inputs['denthang'].'/'.$inputs['tunam']);

            $model_tonghop =  $model_tonghop->sortby('nam')->sortby('thang');

            return view('reports.mauchung.khoi.chitraluong')
                ->with('model_data',$model_data)
                ->with('model_tonghop',$model_tonghop)
                ->with('model_tonghop_chitiet',$model_tonghop_chitiet)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_khoi(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            $a_data = array();

            for($i = (int)$tuthang;$i<=$denthang;$i++){
                if($i<10){
                    $a_data[] = array('thang'=>'0'.$i, 'nam'=>$tunam);
                }else{
                    $a_data[] = array('thang'=>$i, 'nam'=>$tunam);
                }
            }
            $model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();

            $model_tonghop = tonghopluong_donvi::wherein('mathk',function($qr)use($tuthang,$denthang,$tunam){
                $qr->select('mathdv')->from('tonghopluong_khoi')->whereBetween('thang', array($tuthang,$denthang))
                    ->where('nam',$tunam)
                    //->where('trangthai','DAGUI')
                    ->where('madv',session('admin')->madv);
            }) ->get();
            //dd($model_tonghop);
            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathk',function($qr)use($tuthang,$denthang,$tunam){
                $qr->select('mathdv')->from('tonghopluong_khoi')->whereBetween('thang', array($tuthang,$denthang))
                    ->where('nam',$tunam)
                    //->where('trangthai','DAGUI')
                    ->where('madv',session('admin')->madv);
            }) ->get();
//dd($model_tonghop_chitiet);
            $model_nguonkp = getNguonKP();
            $model_phanloaict = getNhomCongTac();
            foreach($model_tonghop_chitiet as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'tu'=>$inputs['tuthang'].'/'.$inputs['tunam'],
                'den'=>$inputs['denthang'].'/'.$inputs['tunam']);


            return view('reports.mauchung.khoi.chitraluong_chitiet')
                ->with('model_data',$a_data)
                ->with('model_donvi',$model_donvi)
                ->with('model_tonghop',$model_tonghop)
                ->with('model_tonghop_chitiet',$model_tonghop_chitiet)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function dutoanluong(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_dutoan=dutoanluong::where('namns',$inputs['namns'])
                ->where('madv',session('admin')->madv)->first();
            if(!isset($model_dutoan)){
                $model_dutoan = new dutoanluong();
                $model_dutoan->namns = $inputs['namns'];
                $model_dutoan->luongnb = 0;
                $model_dutoan->luonghs = 0;
                $model_dutoan->luongbh = 0;
                $model_dutoan->luongnb_dt = 0;
                $model_dutoan->luonghs_dt = 0;
                $model_dutoan->luongbh_dt = 0;
            }
            $model_bienche_dutoan=chitieubienche::where('nam',$inputs['namns'])
                ->where('madv',session('admin')->madv)->first();
            if(!isset($model_bienche_dutoan)){
                $model_bienche_dutoan = new chitieubienche();
                $model_bienche_dutoan->nam = $inputs['namns'];
                $model_bienche_dutoan->soluongduocgiao = 0;
                $model_bienche_dutoan->soluongbienche = 0;
            }

            $model_bienche_truoc=chitieubienche::where('nam',$inputs['namns'] - 1)
                ->where('madv',session('admin')->madv)->first();
            if(!isset($model_bienche_truoc)){
                $model_bienche_truoc = new chitieubienche();
                $model_bienche_truoc->nam = $inputs['namns'];
                $model_bienche_truoc->soluongduocgiao = 0;
                $model_bienche_truoc->soluongbienche = 0;
            }
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'namns'=>$inputs['namns']);


            return view('reports.mauchung.donvi.dutoanluong')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_bienche_dutoan',$model_bienche_dutoan)
                ->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function dutoanluong_khoi(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;

            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_dutoan=dutoanluong::where('namns',$inputs['namns'])
                ->wherein('madv',function($qr)use($madv){
                    $qr->select('madv')->from('dmdonvi')->where('macqcq',$madv);
                })->get();
            $model_bienche_dutoan=chitieubienche::where('nam',$inputs['namns'])
                ->wherein('madv',function($qr)use($madv){
                    $qr->select('madv')->from('dmdonvi')->where('macqcq',$madv);
                })->get();
            $model_bienche_truoc=chitieubienche::where('nam',$inputs['namns'] - 1)
                ->wherein('madv',function($qr)use($madv){
                    $qr->select('madv')->from('dmdonvi')->where('macqcq',$madv);
                })->get();

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'namns'=>$inputs['namns']);

            return view('reports.mauchung.khoi.dutoanluong')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_donvi',$model_donvi)
                ->with('model_bienche_dutoan',$model_bienche_dutoan)
                ->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_th_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            /*
            $model = tonghopluong_donvi_chitiet::select('luongcoban','mathdv','macongtac','mact','soluong','heso','hesobl','hesopc','hesott','vuotkhung'
                ,'pcct','pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld','pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang','pccovu'
                ,'pclt','pcd','pctr','pctdt','pctnvk','pcbdhdcu','pcthni','tonghs','giaml','luongtn','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','ttbh_dv')
                -> wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mathdv','macongtac','mact')
                ->get();
            */
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
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
            return view('reports.tonghopluong.khoi.solieu')
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

    function chitraluong_th_huyen_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            /*
            $model = tonghopluong_donvi_chitiet::select('luongcoban','mathdv','macongtac','mact','soluong','heso','hesobl','hesopc','hesott','vuotkhung'
                ,'pcct','pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld','pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang','pccovu'
                ,'pclt','pcd','pctr','pctdt','pctnvk','pcbdhdcu','pcthni','tonghs','giaml','luongtn','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','ttbh_dv')
                -> wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mathdv','macongtac','mact')
                ->get();
            */
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
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
            Excel::create('BangLuongTH',function($excel) use($thongtin,$model,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$m_dv,$col,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($thongtin,$model,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$m_dv,$col,$a_phucap){
                    $sheet->loadView('reports.tonghopluong.khoi.solieuexcel')
                        ->with('thongtin', $thongtin)
                        ->with('model', $model)
                        ->with('model_tonghop', $model_tonghop)
                        ->with('model_phanloai', $model_phanloai)
                        ->with('model_donvi', $model_donvi)
                        ->with('a_soluong', $a_soluong)
                        ->with('m_dv', $m_dv)
                        ->with('col', $col)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle','Bảng lương tổng hợp');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_th_khoim(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            /*
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')->get();
*/
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })
                ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            //dd($model_tonghop->toarray());
            $model_dmdv = dmdonvi::where('macqcq',$madv)
                ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_dmdv->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::where('mact','like',$inputs['phanloaict'].'%')-> wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });
            //  dd($model->toarray());
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
            return view('reports.tonghopluong.khoi.solieu')
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

    function chitraluong_th_khoim_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::where('mact','like',$inputs['phanloaict'].'%')-> wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct / $chitiet->luongcoban;
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
            Excel::create('solieu',function($excel) use($thongtin,$model,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$m_dv,$col,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($thongtin,$model,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$m_dv,$col,$a_phucap){
                    $sheet->loadView('reports.tonghopluong.khoi.solieuexcel')
                        ->with('thongtin', $thongtin)
                        ->with('model', $model)
                        ->with('model_tonghop', $model_tonghop)
                        ->with('model_phanloai', $model_phanloai)
                        ->with('model_donvi', $model_donvi)
                        ->with('a_soluong', $a_soluong)
                        ->with('m_dv', $m_dv)
                        ->with('col', $col)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle','Bảng lương tổng hợp');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_th_huyen_cu(Request $request)
    {
        //lấy từng tháng năm = > ra số liệu tổng hợp
        //group theo tháng, năm
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            $madvbc = session('admin')->madvbc;



            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathdv', function ($query) use ($inputs) {
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam', $inputs['tunam'])
                    ->whereBetween('thang', array($inputs['tuthang'], $inputs['denthang']))
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq', session('admin')->madv)->get();
            })->get();

            $model_tonghop = tonghopluong_donvi::select('mathdv','thang','nam')
                ->where('nam', $inputs['tunam'])
                ->whereBetween('thang', array($inputs['tuthang'], $inputs['denthang']))
                ->where('trangthai', 'DAGUI')
                ->where('macqcq', session('admin')->madv)->get();

            $model_nguonkp = getNguonKP(false);
            $model_phanloaict = getNhomCongTac(false);

            foreach ($model_tonghop_chitiet as $chitiet) {
                $tonghop = $model_tonghop->where('mathdv', $chitiet->mathdv)->first();
                if (count($tonghop) > 0) {
                    $chitiet->thang = $tonghop->thang;
                    $chitiet->nam = $tonghop->nam;
                } else {
                    $chitiet->thang = 0;
                    $chitiet->nam = 0;
                }

                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $thongtin = array('nguoilap' => session('admin')->name,
                'tu' => $inputs['tuthang'] . '/' . $inputs['tunam'],
                'den' => $inputs['denthang'] . '/' . $inputs['tunam'],
                'madvbc' => $madvbc);

            //Lấy dữ liệu để lập
            $model_dulieu = $model_tonghop_chitiet->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac', 'manguonkp', 'tennguonkp', 'tencongtac'])
                    ->all();
            });
            $model_dulieu = a_unique($model_dulieu);
            //dd($model_dulieu);
            $model_tonghop = $model_tonghop->sortby('nam')->sortby('thang');
            $a_data = $model_tonghop->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang', 'nam'])
                    ->all();
            });
            $a_data = a_unique($a_data);

            return view('reports.mauchung.huyen.chitraluong_chitiet')
                ->with('model_data', $a_data)
                ->with('model_dulieu', $model_dulieu)
                //->with('model_tonghop',$model_tonghop)
                ->with('model_tonghop_chitiet', $model_tonghop_chitiet)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function baocaohesoluong_khoi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::where('mapc','<>','heso')->get()->toarray(),'report','mapc');
            //dd($m_pc);
            $a_phucap = array();
            $col = 0;
            $a_bienche = array_column(chitieubienche::all()->toarray(),'madv','soluongduocgiao');
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            //dd($model_tonghop->toarray());
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::select('mathdv','macongtac','mact','soluong','heso','hesobl','hesopc','hesott','vuotkhung'
                ,'pcct','pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld','pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang','pccovu'
                ,'pclt','pcd','pctr','pctdt','pctnvk','pcbdhdcu','pcthni','tonghs','giaml','luongtn','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','ttbh_dv')
                -> wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mathdv','macongtac','mact')
                ->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->biencheduocgiao = $this->bienchegiao($chitiet->madv,$nam);
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                //$chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct;
                }
            }
            //dd($model->toarray());
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai','biencheduocgiao'])
                    ->all();
            });
            /*
            $m_tong=array('tencongtac'=> $chitiet->tencongtac,
                'bienche'=>$Tbienche,
                'hopdong68'=>$Thopdong68,
                'khac'=>$Tkhac);
            */
            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0 && $ct != "heso") {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            return view('reports.tonghopluong.khoi.BcHesoluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('a_pl', $a_pl)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function baocaohesoluong_khoi_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::where('mapc','<>','heso')->get()->toarray(),'report','mapc');
            //dd($m_pc);
            $a_phucap = array();
            $col = 0;
            $a_bienche = array_column(chitieubienche::all()->toarray(),'madv','soluongduocgiao');
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            //dd($model_tonghop->toarray());
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::select('mathdv','macongtac','mact','soluong','heso','hesobl','hesopc','hesott','vuotkhung'
                ,'pcct','pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld','pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang','pccovu'
                ,'pclt','pcd','pctr','pctdt','pctnvk','pcbdhdcu','pcthni','tonghs','giaml','luongtn','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','ttbh_dv')
                -> wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mathdv','macongtac','mact')
                ->get();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->biencheduocgiao = $this->bienchegiao($chitiet->madv,$nam);
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                //$chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct;
                }
            }
            //dd($model->toarray());
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai','biencheduocgiao'])
                    ->all();
            });
            /*
            $m_tong=array('tencongtac'=> $chitiet->tencongtac,
                'bienche'=>$Tbienche,
                'hopdong68'=>$Thopdong68,
                'khac'=>$Tkhac);
            */
            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0 && $ct != "heso") {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            Excel::create('BcHesoluong',function($excel) use($thongtin,$model,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$a_pl, $m_dv, $col, $a_phucap){
                $excel->sheet('New sheet', function($sheet) use($thongtin,$model,$model_tonghop,$model_phanloai,$model_donvi,$a_soluong,$a_pl, $m_dv, $col, $a_phucap){
                    return view('reports.tonghopluong.khoi.BcHesoluongexcel')
                        ->with('thongtin', $thongtin)
                        ->with('model', $model)
                        ->with('model_tonghop', $model_tonghop)
                        ->with('model_phanloai', $model_phanloai)
                        ->with('model_donvi', $model_donvi)
                        ->with('a_soluong', $a_soluong)
                        ->with('a_pl', $a_pl)
                        ->with('m_dv', $m_dv)
                        ->with('col', $col)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle','BcHesoluong');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function bienchegiao($madv,$nam)
    {
        $bienchegiao = 0;
        $checkdv = dmdonvi::where('madv',$madv)->where('phanloaitaikhoan','TH')->get();
        if(count($checkdv) > 0)
        {

            $a_bienche = chitieubienche::select('soluongduocgiao','nam')
                ->where('nam',$nam)
                ->wherein('madv',function($query) use($madv){
                $query->select('madv')->from('dmdonvi')
                    ->where('madvbc',$madv)
                    ->get();
            })->groupby('nam')
                ->get();
            $bienchegiao = $a_bienche->sum('soluongduocgiao');
        }
        else
        {
            $a_bienche = chitieubienche::select('soluongduocgiao','nam')
                ->where('nam',$nam)
                ->where('madv',$madv)
                ->groupby('nam')
                ->get();
            $bienchegiao = $a_bienche->sum('soluongduocgiao');
        }
        return $bienchegiao;
    }

    function baocaohesoluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::where('mapc','<>','heso')->get()->toarray(),'report','mapc');
            //dd($model_phanloai);
            $a_phucap = array();
            $col = 0;
            $a_bienche = array_column(chitieubienche::all()->toarray(),'madv','soluongduocgiao');
            $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            //dd($model_tonghop->toarray());
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::select('mathdv','macongtac','mact','soluong','heso','hesobl','hesopc','hesott','vuotkhung'
                ,'pcct','pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld','pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang','pccovu'
                ,'pclt','pcd','pctr','pctdt','pctnvk','pcbdhdcu','pcthni','tonghs','giaml','luongtn','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','ttbh_dv')
                ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mathdv','macongtac','mact')
                ->get();
            $a_luongcb = array_column(tonghopluong_donvi_chitiet::select('mathdv','luongcoban')
                ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->distinct()->get()->toarray(),'luongcoban','mathdv');
            //dd($a_luongcb);
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->biencheduocgiao = $this->bienchegiao($chitiet->madv,$nam);
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                //$chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct;
                }
                $chitiet->hesoluong = $chitiet->heso/$a_luongcb[$chitiet->mathdv];

            }
            // dd($model->toarray());
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai','biencheduocgiao'])
                    ->all();
            });
            /*
            $m_tong=array('tencongtac'=> $chitiet->tencongtac,
                'bienche'=>$Tbienche,
                'hopdong68'=>$Thopdong68,
                'khac'=>$Tkhac);
            */
            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0 && $ct != "heso") {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            return view('reports.tonghopluong.khoi.BcHesoluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('a_pl', $a_pl)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');

        } else
            return view('errors.notlogin');
    }

    function baocaohesoluongexcel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $m_pc = array_column(dmphucap::where('mapc','<>','heso')->get()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $a_bienche = array_column(chitieubienche::all()->toarray(),'madv','soluongduocgiao');
            $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            //dd($model_tonghop->toarray());
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::select('mathdv','macongtac','mact','soluong','heso','hesobl','hesopc','hesott','vuotkhung'
                ,'pcct','pckct','pck','pccv','pckv','pcth','pcdd','pcdh','pcld','pcdbqh','pcudn','pctn','pctnn','pcdbn','pcvk','pckn','pcdang','pccovu'
                ,'pclt','pcd','pctr','pctdt','pctnvk','pcbdhdcu','pcthni','tonghs','giaml','luongtn','stbhxh_dv','stbhyt_dv','stkpcd_dv','stbhtn_dv','ttbh_dv')
                ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->groupby('mathdv','macongtac','mact')
                ->get();
            $a_luongcb = array_column(tonghopluong_donvi_chitiet::select('mathdv','luongcoban')
                ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))
                ->distinct()->get()->toarray(),'luongcoban','mathdv');
            //dd($a_luongcb);
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_dv[$chitiet->mathdv];
                $chitiet->biencheduocgiao = $this->bienchegiao($chitiet->madv,$nam);
                $chitiet->maphanloai = $a_pl[$chitiet->madv];
                //$chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = 'hs'.$ct;
                    $chitiet->$ma = $chitiet->$ct;
                }
                $chitiet->hesoluong = $chitiet->heso/$a_luongcb[$chitiet->mathdv];

            }
            // dd($model->toarray());
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai','biencheduocgiao'])
                    ->all();
            });
            /*
            $m_tong=array('tencongtac'=> $chitiet->tencongtac,
                'bienche'=>$Tbienche,
                'hopdong68'=>$Thopdong68,
                'khac'=>$Tkhac);
            */
            $a_soluong = a_unique($model_data);
            //dd($a_soluong);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $thang,
                'nam' => $nam);
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0 && $ct != "heso") {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            Excel::create('BcHesoluong',function($excel) use($thongtin, $model,$model_tonghop, $model_phanloai, $model_donvi, $a_soluong, $a_pl,$m_dv,$col,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($thongtin, $model,$model_tonghop, $model_phanloai, $model_donvi, $a_soluong, $a_pl,$m_dv,$col,$a_phucap){
                    $sheet->loadView('reports.tonghopluong.khoi.BcHesoluongexcel')
                        ->with('thongtin', $thongtin)
                        ->with('model', $model)
                        ->with('model_tonghop', $model_tonghop)
                        ->with('model_phanloai', $model_phanloai)
                        ->with('model_donvi', $model_donvi)
                        ->with('a_soluong', $a_soluong)
                        ->with('a_pl', $a_pl)
                        ->with('m_dv', $m_dv)
                        ->with('col', $col)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle','BcHesoluong');
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_th_huyen_210818(Request $request)
    {
        //lấy từng tháng năm = > ra số liệu tổng hợp
        //group theo tháng, năm
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            $madvbc = session('admin')->madvbc;

            $model_tonghop = tonghopluong_huyen::whereBetween('thang', array($tuthang, $denthang))
                ->where('nam', $tunam)
                ->where('macqcq', session('admin')->madv)->get();
            //dd($model_tonghop->toarray());

            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathdv', function ($query) use ($inputs) {
                $query->select('mathdv')->from('tonghopluong_donvi')
                    ->where('nam', $inputs['tunam'])
                    ->whereBetween('thang', array($inputs['tuthang'], $inputs['denthang']))
                    ->where('trangthai', 'DAGUI')
                    ->where('macqcq', session('admin')->madv)->get();
            })->get();

            $model_tonghop_donvi = tonghopluong_donvi::select('mathdv','thang','nam')
                ->where('nam', $inputs['tunam'])
                ->whereBetween('thang', array($inputs['tuthang'], $inputs['denthang']))
                ->where('trangthai', 'DAGUI')
                ->where('macqcq', session('admin')->madv)->get();


            //dd($model_tonghop_donvi);
            $model_nguonkp = getNguonKP(false);
            $model_phanloaict = getNhomCongTac(false);

            foreach ($model_tonghop_chitiet as $chitiet) {
                $tonghop = $model_tonghop->where('mathdv', $chitiet->mathh)->first();
                if (count($tonghop) > 0) {
                    $chitiet->thang = $tonghop->thang;
                    $chitiet->nam = $tonghop->nam;
                } else {
                    $chitiet->thang = 0;
                    $chitiet->nam = 0;
                }

                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $thongtin = array('nguoilap' => session('admin')->name,
                'tu' => $inputs['tuthang'] . '/' . $inputs['tunam'],
                'den' => $inputs['denthang'] . '/' . $inputs['tunam'],
                'madvbc' => $madvbc);

            //Lấy dữ liệu để lập
            $model_dulieu = $model_tonghop_chitiet->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac', 'manguonkp', 'tennguonkp', 'tencongtac'])
                    ->all();
            });
            $model_dulieu = a_unique($model_dulieu);
            //dd($model_dulieu);
            $model_tonghop = $model_tonghop->sortby('nam')->sortby('thang');
            $a_data = $model_tonghop->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang', 'nam'])
                    ->all();
            });
            $a_data = a_unique($a_data);

            return view('reports.mauchung.huyen.chitraluong_chitiet')
                ->with('model_data', $a_data)
                ->with('model_dulieu', $model_dulieu)
                //->with('model_tonghop',$model_tonghop)
                ->with('model_tonghop_chitiet', $model_tonghop_chitiet)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_huyen(Request $request) {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            $madv = $inputs['donvi'];
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $check = dmdonvi::where('madv', $madv)->where('phanloaitaikhoan','TH')->get();
            if(count($check)>0)
                $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            else
                $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            if(isset($m_mathdv)) {
                if(count($check)>0) {
                    $mathh = $m_mathdv->mathdv;
                    $a_math = tonghopluong_donvi::where('mathh', $mathh)->get();
                    $model = tonghopluong_donvi_bangluong::wherein('mathdv',array_column($a_math->toarray(),'mathdv'))->get();
                    $model_thongtin = tonghopluong_donvi::where('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathh){
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathh)
                            ->get();
                    })->get()->toarray(),'report','mapc');
                }else{
                    $model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                    $model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                    $m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');
                }

                //$model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                //$model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
                $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                //$gnr = getGeneralConfigs();

                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                    $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                    $thanhtien = 0;
                    foreach (getColTongHop() as $ct) {
                        if ($chitiet->$ct > 50000) {
                            $thanhtien += $chitiet->$ct;
                        }
                    }
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }
                //dd($model);

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
                        ->only(['mact', 'manguonkp', 'tennguonkp', 'tenct'])
                        ->all();
                });
                //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
                $a_congtac = a_unique($model_congtac);

                $model_nguon = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['manguonkp', 'tennguonkp'])
                        ->all();
                });
                $a_nguon = a_unique($model_nguon);

                return view('reports.tonghopluong.huyen.bangluong')
                    ->with('thongtin', $thongtin)
                    ->with('model', $model)
                    ->with('m_dv', $m_dv)
                    ->with('col', $col)
                    ->with('a_phucap', $a_phucap)
                    ->with('a_nguon', $a_nguon)
                    ->with('a_congtac', $a_congtac)
                    ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
            }
            else {
                return view('errors.nodata');
            }
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_huyen_excel(Request $request) {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            $madv = $inputs['donvi'];
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $check = dmdonvi::where('madv', $madv)->where('phanloaitaikhoan','TH')->get();
            if(count($check)>0)
                $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            else
                $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            if(isset($m_mathdv)) {
                if(count($check)>0) {
                    $mathh = $m_mathdv->mathdv;
                    $a_math = tonghopluong_donvi::where('mathh', $mathh)->get();
                    $model = tonghopluong_donvi_bangluong::wherein('mathdv',array_column($a_math->toarray(),'mathdv'))->get();
                    $model_thongtin = tonghopluong_donvi::where('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathh){
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathh)
                            ->get();
                    })->get()->toarray(),'report','mapc');
                }else{
                    $model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                    $model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                    $m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');
                }

                //$model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                //$model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
                $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                //$gnr = getGeneralConfigs();

                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                    $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                    $thanhtien = 0;
                    foreach (getColTongHop() as $ct) {
                        if ($chitiet->$ct > 50000) {
                            $thanhtien += $chitiet->$ct;
                        }
                    }
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }
                //dd($model);

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
                        ->only(['mact', 'manguonkp', 'tennguonkp', 'tenct'])
                        ->all();
                });
                //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
                $a_congtac = a_unique($model_congtac);

                $model_nguon = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['manguonkp', 'tennguonkp'])
                        ->all();
                });
                $a_nguon = a_unique($model_nguon);
                Excel::create('bangluong',function($excel) use($thongtin, $model, $m_dv, $col, $a_phucap, $a_nguon, $a_congtac){
                    $excel->sheet('New sheet', function($sheet) use($thongtin, $model, $m_dv, $col, $a_phucap, $a_nguon, $a_congtac){
                        $sheet->loadView('reports.tonghopluong.donvi.bangluongexcel')
                            ->with('thongtin', $thongtin)
                            ->with('model', $model)
                            ->with('m_dv', $m_dv)
                            ->with('col', $col)
                            ->with('a_phucap', $a_phucap)
                            ->with('a_nguon', $a_nguon)
                            ->with('a_congtac', $a_congtac)
                            ->with('pageTitle','bangluong');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }
            else {
                return view('errors.nodata');
            }
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_khoim(Request $request) {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            $madv = $inputs['donvi'];
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $check = dmdonvi::where('madv',$madv)->where('phanloaitaikhoan','TH')->first();
            if(count($check)>0)
                $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            else
                $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            if(isset($m_mathdv)) {
                if(count($check)>0) {
                    $mathh = $m_mathdv->mathdv;
                    $model = tonghopluong_donvi_bangluong::where('mathh', $mathh)->get();
                    $model_thongtin = tonghopluong_donvi::where('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathh){
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathh)
                            ->get();
                    })->get()->toarray(),'report','mapc');
                }else{
                    $model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                    $model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                    $m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');
                }

                $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
                $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                //$gnr = getGeneralConfigs();

                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                    $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                    $thanhtien = 0;
                    foreach (getColTongHop() as $ct) {
                        if ($chitiet->$ct > 50000) {
                            $thanhtien += $chitiet->$ct;
                        }
                    }
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }
                //dd($model);

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
                        ->only(['mact', 'manguonkp', 'tennguonkp', 'tenct'])
                        ->all();
                });
                //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
                $a_congtac = a_unique($model_congtac);

                $model_nguon = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['manguonkp', 'tennguonkp'])
                        ->all();
                });
                $a_nguon = a_unique($model_nguon);

                return view('reports.tonghopluong.khoi.bangluong')
                    ->with('thongtin', $thongtin)
                    ->with('model', $model)
                    ->with('m_dv', $m_dv)
                    ->with('col', $col)
                    ->with('a_phucap', $a_phucap)
                    ->with('a_nguon', $a_nguon)
                    ->with('a_congtac', $a_congtac)
                    ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
            }
            else {
                return view('errors.nodata');
            }
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_khoim_excel(Request $request) {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            $madv = $inputs['donvi'];
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $check = dmdonvi::where('madv',$madv)->where('phanloaitaikhoan','TH')->first();
            if(count($check)>0)
                $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            else
                $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang',$thang)->where('nam',$nam)->where('trangthai', 'DAGUI')->first();
            if(isset($m_mathdv)) {
                if(count($check)>0) {
                    $mathh = $m_mathdv->mathdv;
                    $model = tonghopluong_donvi_bangluong::where('mathh', $mathh)->get();
                    $model_thongtin = tonghopluong_donvi::where('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathh){
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathh)
                            ->get();
                    })->get()->toarray(),'report','mapc');
                }else{
                    $model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                    $model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                    $m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');
                }

                $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
                $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                //$gnr = getGeneralConfigs();

                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                    $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                    $thanhtien = 0;
                    foreach (getColTongHop() as $ct) {
                        if ($chitiet->$ct > 50000) {
                            $thanhtien += $chitiet->$ct;
                        }
                    }
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }
                //dd($model);

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
                        ->only(['mact', 'manguonkp', 'tennguonkp', 'tenct'])
                        ->all();
                });
                //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
                $a_congtac = a_unique($model_congtac);

                $model_nguon = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['manguonkp', 'tennguonkp'])
                        ->all();
                });
                $a_nguon = a_unique($model_nguon);
                Excel::create('bangluong',function($excel) use($thongtin,$model,$m_dv,$col,$a_phucap,$a_nguon,$a_congtac){
                    $excel->sheet('New sheet', function($sheet) use($thongtin,$model,$m_dv,$col,$a_phucap,$a_nguon,$a_congtac){
                        return view('reports.tonghopluong.donvi.bangluongexcel')
                            ->with('thongtin', $thongtin)
                            ->with('model', $model)
                            ->with('m_dv', $m_dv)
                            ->with('col', $col)
                            ->with('a_phucap', $a_phucap)
                            ->with('a_nguon', $a_nguon)
                            ->with('a_congtac', $a_congtac)
                            ->with('pageTitle','bangluong');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            }
            else {
                return view('errors.nodata');
            }
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_huyen_cu(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            $a_data = array();

            for($i = (int)$tuthang;$i<=$denthang;$i++){
                if($i<10){
                    $a_data[] = array('thang'=>'0'.$i, 'nam'=>$tunam);
                }else{
                    $a_data[] = array('thang'=>$i, 'nam'=>$tunam);
                }
            }
            $model_donvi = dmdonvi::where('macqcq',session('admin')->madv)->get();

            $model_tonghop = tonghopluong_donvi::whereBetween('thang', array($tuthang,$denthang))
                ->where('nam',$tunam)
                ->where('macqcq',session('admin')->madv)->get();

            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathdv',function($qr)use($tuthang,$denthang,$tunam){
                $qr->select('mathdv')->from('tonghopluong_donvi')->whereBetween('thang', array($tuthang,$denthang))
                    ->where('nam',$tunam)
                    ->where('trangthai','DAGUI')
                    ->where('macqcq',session('admin')->madv);
            }) ->get();

            $model_nguonkp = getNguonKP(false);
            $model_phanloaict = getNhomCongTac(false);
            foreach($model_tonghop_chitiet as $chitiet){
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp])? $model_nguonkp[$chitiet->manguonkp]:'';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac])? $model_phanloaict[$chitiet->macongtac]:'';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'tu'=>$inputs['tuthang'].'/'.$inputs['tunam'],
                'den'=>$inputs['denthang'].'/'.$inputs['tunam']);


            return view('reports.mauchung.khoi.chitraluong_chitiet')
                ->with('model_data',$a_data)
                ->with('model_donvi',$model_donvi)
                ->with('model_tonghop',$model_tonghop)
                ->with('model_tonghop_chitiet',$model_tonghop_chitiet)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function dutoanluong_huyen(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;

            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_dutoan=dutoanluong::where('namns',$inputs['namns'])
                ->wherein('madv',function($qr)use($madvbc){
                    $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc);
                })->get();
            $model_bienche_dutoan=chitieubienche::where('nam',$inputs['namns'])
                ->wherein('madv',function($qr)use($madvbc){
                    $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc);
                })->get();
            $model_bienche_truoc=chitieubienche::where('nam',$inputs['namns'] - 1)
                ->wherein('madv',function($qr)use($madvbc){
                    $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc);
                })->get();

            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'namns'=>$inputs['namns'],
                'madvbc'=>$madvbc);

            return view('reports.mauchung.huyen.dutoanluong')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_donvi',$model_donvi)
                ->with('model_bienche_dutoan',$model_bienche_dutoan)
                ->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function nangluong(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_dv = dmdonvi::where('madv',session('admin')->madv)->first();
            $a_cv = getChucVuCQ(false);
            $a_cb = hosocanbo::select('macanbo','tencanbo','macvcq')->where('madv', $m_dv->madv)->get()->keyBy('macanbo')->toArray();
            //dd($m_cb);
            //dd($inputs);
            if($inputs['phanloai'] == 'TNN') {
                $model = hosocanbo::select(DB::raw("'CHUANANGLUONG'" . ' as trangthai'), 'stt', 'macanbo', 'tencanbo', 'macvcq', 'msngbac', 'sunghiep', 'gioitinh', 'tnntungay', 'tnndenngay', 'ngaytu', 'ngayden', 'heso', 'pctnn', 'vuotkhung', 'bac')
                    ->wherebetween('tnndenngay', [$inputs['ngaytu'], $inputs['ngayden']])
                    ->where('madv', session('admin')->madv)
                    ->where('theodoi', '<', '9')
                    ->get();

                foreach ($model as $ct) {
                    $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                    $ct->pctnn_m = $ct->pctnn == 0 ? 5 : $ct->pctnn + 1;
                }

                if (isset($inputs['indanangluong'])) {
                    $model_nangluong = dsnangthamnien::join('dsnangthamnien_chitiet', 'dsnangthamnien_ct.manl', '=', 'dsnangthamnien.manl')
                        ->where('madv', session('admin')->madv)
                        ->where('trangthai', 'Đã nâng lương')
                        ->wherebetween('ngayxet', [$inputs['ngaytu'], $inputs['ngayden']])->get();

                    foreach ($model_nangluong as $ct) {
                        if (isset($a_cb[$ct->macanbo])) {
                            $ct->tencanbo = $a_cb[$ct->macanbo]['tencanbo'];
                            $ct->macvcq = $a_cb[$ct->macanbo]['macvcq'];
                            $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                            $ct->pctnn_m = $ct->pctnn - 1;
                            $ct->pctnn = $ct->pctnn_m - 1 == 5 ? 0 : $ct->pctnn_m - 1;
                            $ct->tnndenngay = $ct->ngaytu;
                            $ct->trangthai = "DANANGLUONG";
                            $model->add($ct);
                        }
                    }
                }

                $a_pl = $model->map(function($data){
                    return collect($data->toArray())
                        ->only(['trangthai'])
                        ->all();
                });
                //dd($a_pl);
                return view('reports.donvi.nangluong_tnn')
                    ->with('model', $model->sortby('tnndenngay'))
                    ->with('m_dv', $m_dv)
                    ->with('a_pl',a_unique($a_pl))
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách cán bộ');

            }else{
                $model = hosocanbo::select(DB::raw("'CHUANANGLUONG'".' as trangthai'),'stt','macanbo', 'tencanbo','macvcq', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden', 'heso','pctnn','vuotkhung','bac')
                    ->wherebetween('ngayden',[$inputs['ngaytu'],$inputs['ngayden']])
                    ->wherenotnull('msngbac')
                    ->where('madv', session('admin')->madv)
                    ->where('theodoi','<' ,'9')
                    ->get();

                $a_nb = ngachluong::all()->keyby('msngbac')->toarray();
                //dd($a_nb);
                foreach($model as $ct){
                    $ct->tencv = isset($a_cv[$ct->macvcq])? $a_cv[$ct->macvcq]:'';
                    $ct->bac_m = $ct->bac;
                    $ct->heso_m = $ct->heso;
                    $ct->vuotkhung_m = $ct->vuotkhung;
                    if(isset($a_nb[$ct->msngbac])){
                        $ngachluong = $a_nb[$ct->msngbac];
                        if($ct->heso < $ngachluong['hesolonnhat']){//nâng lương ngạch bậc
                            $ct->heso_m = $ct->heso + $ngachluong['hesochenhlech'];
                            $ct->bac_m = $ct->bac < $ngachluong['baclonnhat'] - 1 ? $ct->bac + 1 : $ngachluong['baclonnhat'];
                        }else{//vượt khung
                            if($ct->vuotkhung == 0){//lần đầu
                                $ct->vuotkhung_m = $ngachluong['vuotkhung'];
                            }else{
                                $ct->vuotkhung_m = $ct->vuotkhung + 1;
                            }
                        }
                    }
                }

                if (isset($inputs['indanangluong'])) {
                    $model_nangluong = dsnangluong::join('dsnangluong_chitiet', 'dsnangluong_chitiet.manl', '=', 'dsnangluong.manl')
                        ->where('madv', session('admin')->madv)
                        ->where('trangthai', 'Đã nâng lương')
                        ->wherebetween('ngayxet', [$inputs['ngaytu'], $inputs['ngayden']])->get();
                    //dd($model_nangluong);
                    foreach ($model_nangluong as $ct) {
                        if (isset($a_cb[$ct->macanbo])) {
                            $ct->tencanbo = $a_cb[$ct->macanbo]['tencanbo'];
                            $ct->macvcq = $a_cb[$ct->macanbo]['macvcq'];
                            $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                            $ct->trangthai = "DANANGLUONG";

                            if(isset($a_nb[$ct->msngbac])){
                                $ngachluong = $a_nb[$ct->msngbac];
                                if($ct->heso < $ngachluong['hesolonnhat']){//nâng lương ngạch bậc
                                    $ct->heso_m = $ct->heso + $ngachluong['hesochenhlech'];
                                    $ct->bac_m = $ct->bac < $ngachluong['baclonnhat'] - 1 ? $ct->bac + 1 : $ngachluong['baclonnhat'];
                                    $ct->vuotkhung_m = 0;
                                }else{//vượt khung
                                    if($ct->vuotkhung == 0){//lần đầu
                                        $ct->vuotkhung_m = $ngachluong['vuotkhung'];
                                    }else{
                                        $ct->vuotkhung_m = $ct->vuotkhung + 1;
                                    }
                                    $ct->heso_m = $ct->heso;
                                    $ct->bac_m = $ct->bac;
                                }
                            }

                            $model->add($ct);
                        }
                    }
                }
                //dd($model);
                $a_pl = $model->map(function($data){
                    return collect($data->toArray())
                        ->only(['trangthai'])
                        ->all();
                });

                return view('reports.donvi.nangluong_ngachbac')
                    //->with('model',$model->sortby('ngayden')->sortby('stt'))
                    ->with('model',$model->sortby('ngayden'))
                    ->with('m_dv',$m_dv)
                    ->with('a_pl',a_unique($a_pl))
                    ->with('inputs',$inputs)
                    ->with('pageTitle','Danh sách cán bộ');
            }
        } else
            return view('errors.notlogin');
    }
}
