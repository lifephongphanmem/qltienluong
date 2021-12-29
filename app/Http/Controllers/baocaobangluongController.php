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
use App\dmthongtuquyetdinh;
use App\dsnangluong;
use App\dsnangthamnien;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\hosocanbo;
use App\ngachluong;
use App\nguonkinhphi_bangluong;
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
use Illuminate\Support\Collection;
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
            $a_thang = getThang();
            $a_phanloai = array();
            $a_phanloai[''] = '--Chọn tất cả--';
            if(session('admin')->phamvitonghop == 'KHOI') {
                $model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
                $model_phanloaict = dmphanloaict::All();
                $model_phanloai = array_column($model_phanloai->toarray(), 'tenphanloai', 'maphanloai');
                foreach ($model_phanloai as $key => $key)
                    $a_phanloai[$key] = $model_phanloai[$key];
            }
            if(session('admin')->phamvitonghop == 'HUYEN') {
                //$model_donvi = dmdonvi::where('macqcq', session('admin')->madv)->get();
                $model_donvi = dmdonvi::where('madvbc', session('admin')->madvbc)->where('phanloaitaikhoan','<>','TH')->get();
                if(session('admin')->caphanhchinh == 'T' && session('admin')->phanloaitaikhoan == 'TH')
                    $model_donvi = dmdonvi::where('tendv','<>','Phần mềm Cuộc Sống')->orderby('tendv')->get();
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
                $model_phanloaict = dmphanloaict::All();
                if (session('admin')->level = 'H')
                    $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
                $model_phanloai = array_column($model_phanloai->toarray(), 'tenphanloai', 'maphanloai');
                foreach ($model_phanloai as $key => $key)
                    $a_phanloai[$key] = $model_phanloai[$key];
                $a_phanloai['GD'] = 'Khối Giáo Dục';
                $a_thang['ALL'] = "--Chọn tất cả--";
            }
            $model_thongtu = dmthongtuquyetdinh::all();
            return view('reports.index_th')
                ->with('furl','/bao_cao/bang_luong/')
                ->with('model_phanloai',$model_phanloai)
                ->with('a_thang',$a_thang)
                ->with('a_phanloai',$a_phanloai)
                ->with('model_dv',$model_donvi)
                ->with('model_phanloaict',$model_phanloaict)
                ->with('model_thongtu',$model_thongtu)
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

    function chitratheonkp(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $tuthang = $inputs['tuthang'];
            $tunam = $inputs['tunam'];
            $denthang = $inputs['denthang'];
            //$dennam = $inputs['dennam'];
            //dd($inputs);
            $m_tonghop = tonghopluong_donvi::whereBetween('thang', array($tuthang, $denthang))
                ->where('nam', $tunam)->where('madv', session('admin')->madv)
                ->orderby('thang')->get();

            $m_chitiet = tonghopluong_donvi_chitiet::wherein('mathdv', a_unique(array_column($m_tonghop->toarray(), 'mathdv')))->get();
            //dd($model_tonghop_chitiet);
            $a_phucap_tonghop = getColTongHop();
            $a_phucap = array();
            $a_pc = array();
            $col = 0;
            $a_dmpc = array_column(dmphucap::where('tonghop', 1)->get()->toarray(), 'tenpc', 'mapc');
            $model = new Collection();
            $a_nguonkp = getNguonKP();
            foreach ($m_tonghop as $tonghop){
                foreach ($a_nguonkp as $mankp=>$tennkp){
                    $add = new tonghopluong_donvi_chitiet();
                    $add->mathdv = $tonghop->mathdv;
                    $add->tennguonkp = $tennkp;
                    $chitiet = $m_chitiet->where('mathdv',$tonghop->mathdv)->where('manguonkp',$mankp);
                    if($chitiet->count() == 0){
                        continue;
                    }
                    foreach ($a_phucap_tonghop as $mapc){
                        $mapc_st = 'st_'.$mapc;
                        $add->$mapc_st = $chitiet->sum($mapc_st);
                        if ($add->$mapc_st > 0 && !isset($a_pc[$mapc])) {
                            $a_pc[$mapc] = $mapc;
                        }
                    }

                    $add->stbhxh_dv = $chitiet->sum('stbhxh_dv');
                    $add->stbhyt_dv = $chitiet->sum('stbhyt_dv');
                    $add->stkpcd_dv = $chitiet->sum('stkpcd_dv');
                    $add->stbhtn_dv = $chitiet->sum('stbhtn_dv');
                    $add->ttbh_dv = $chitiet->sum('ttbh_dv');
                    $add->ttl = $chitiet->sum('luongtn');
                    $add->tongcong = $add->ttbh_dv + $add->ttl;
                    $model->push($add);
                }
            }

            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $thongtin = array('nguoilap' => session('admin')->name,
                'tu' => $inputs['tuthang'] . '/' . $inputs['tunam'],
                'den' => $inputs['denthang'] . '/' . $inputs['tunam']);
            //Tách riêng ra để chạy nếu ko sẽ ko sắp đc theo thứ tự
            foreach ($a_phucap_tonghop as $mapc){
                $mapc_st = 'st_'.$mapc;
                if (isset($a_pc[$mapc])) {
                    $a_phucap[$mapc_st] = isset($a_dmpc[$mapc]) ? $a_dmpc[$mapc] : '';
                    $col++;
                }
            }
            //dd($a_pc);
            return view('reports.mauchung.donvi.chitratheonkp')
                ->with('model', $m_tonghop)
                ->with('model_chitiet', $model)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Báo cáo chi trả lương');
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
                ->where('nam',$tunam)->where('madv',session('admin')->madv)->orderby('thang')->get();

            $model_tonghop_chitiet = tonghopluong_donvi_chitiet::wherein('mathdv',a_unique(array_column($model_tonghop->toarray(),'mathdv'))) ->get();
            //dd($model_tonghop_chitiet);
            $a_phucap = array();
            $col = 0;
            $a_dmpc = array_column(dmphucap::where('tonghop',1)->get()->toarray(),'tenpc','mapc');

            foreach (getColTongHop() as $ct) {
                if ($model_tonghop_chitiet->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($a_dmpc[$ct]) ? $a_dmpc[$ct] : '';
                    $col++;
                }
            }

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
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
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
            if($thang != 'ALL')
                $ngay = date("Y-m-d", strtotime($nam.'-'.$thang.'-01'));
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->where('phanloaitaikhoan','<>','TH')->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            if($maphanloai == 'GD')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',getPhanLoaGD())->get();
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
            if($thang == 'ALL'){
                $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                    ->where('nam', $nam)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query) use ($maphanloai) {
                        $query->select('madv')->from('dmdonvi')
                            ->where('maphanloai','like', $maphanloai.'%')
                            ->get();
                    })->get();
                if($maphanloai == 'GD')
                    $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                        ->where('nam', $nam)
                        ->where('trangthai', 'DAGUI')
                        ->wherein('madv', function ($query) {
                            $query->select('madv')->from('dmdonvi')
                                ->wherein('maphanloai', function ($query) {
                                    $query->select('maphanloai')->from('dmphanloaidonvi')
                                        ->wherein('maphanloai',getPhanLoaGD())
                                        ->get();
                                })
                                ->get();
                        })
                        ->get();
            }
            if($thang != 'ALL' && $maphanloai == 'GD')
                $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query){
                        $query->select('madv')->from('dmdonvi')
                            ->wherein('maphanloai', function ($query) {
                                $query->select('maphanloai')->from('dmphanloaidonvi')
                                    ->wherein('maphanloai',getPhanLoaGD())
                                    ->get();
                            })
                            ->get();
                    })
                    ->get();

            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_donvi->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi_chitiet.mathdv','tonghopluong_donvi.mathdv')
                ->select('tonghopluong_donvi_chitiet.*','thang')
                //->where('tonghopluong_donvi_chitiet.mact','like',$inputs['phanloai'].'%')
                ->wherein('tonghopluong_donvi_chitiet.mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
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
                    if($chitiet->luongcoban > 0)
                        $chitiet->$ma = $chitiet->$ct ;
                    else
                        $chitiet->$ma = 0;
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
            $a_thang = array_column($model->sortBy('thang')->toArray(), 'thang', 'thang');
            if($thang != 'ALL')
                $model_donvi = dmdonvi::where('madvbc',$madvbc)->where('phanloaitaikhoan','<>','TH')->where('ngaydung','>',$ngay)->orwherenull('ngaydung')->where('madvbc',$madvbc)->where('phanloaitaikhoan','<>','TH')->get();
            else
                $model_donvi = dmdonvi::where('madvbc',$madvbc)->where('phanloaitaikhoan','<>','TH')->whereyear('ngaydung','>=',$nam)->orwherenull('ngaydung')->where('madvbc',$madvbc)->where('phanloaitaikhoan','<>','TH')->get();

            return view('reports.tonghopluong.khoi.solieu')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('model_tonghop', $model_tonghop)
                ->with('model_phanloai', $model_phanloai)
                ->with('model_donvi', $model_donvi)
                ->with('a_soluong', $a_soluong)
                ->with('a_thang', $a_thang)
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
            if($thang == 'ALL')
                $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                    ->where('nam', $nam)
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
            $a_plgd = array('MAMNON','TIEUHOC','THCS','THvaTHCS');
            $a_plth = array('TIEUHOC','THvaTHCS');
            $a_plthcs = array('THCS','THvaTHCS');
            if($maphanloai == 'GD')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plgd)->get();
            if($maphanloai == 'TIEUHOC')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plth)->get();
            if($maphanloai == 'THCS')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plthcs)->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $a_pldvgd = array_column(dmdonvi::select('madv')->wherein('maphanloai',$a_plgd)->get()->toarray(),'madv');
            $a_pldvth = array_column(dmdonvi::select('madv')->wherein('maphanloai',$a_plth)->get()->toarray(),'madv');
            $a_pldvthcs = array_column(dmdonvi::select('madv')->wherein('maphanloai',$a_plthcs)->get()->toarray(),'madv');
            $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })
                ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                ->where('nam', $nam)
                ->where('thang', $thang)
                ->where('trangthai', 'DAGUI')
                ->wherein('madv', function ($query) use ($maphanloai) {
                    $query->select('madv')->from('dmdonvi')
                        ->where('maphanloai','like', $maphanloai.'%')
                        ->get();
                })->get();
            if($maphanloai == 'GD')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvgd)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvgd)
                    ->get();
            if($maphanloai == 'TIEUHOC')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)->where('thang', $thang)->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvth)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)->where('thang', $thang)->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvth)->get();
            if($maphanloai == 'THCS')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvthcs)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvthcs)
                    ->get();
            //dd($model_tonghop->toarray());
            $model_dmdv = dmdonvi::where('macqcq',$madv)
                ->wherein('madv',array_column($model_tonghop->toarray(),'madv'))->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_dmdv->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_chitiet::where('mact','like',$inputs['phanloaict'].'%')
            ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
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
                    $chitiet->$ma = $chitiet->$ct ;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });
            $a_soluong = a_unique($model_data);
            //dd($model->toarray());
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
            $a_plgd = array('MAMNON','TIEUHOC','THCS','THvaTHCS');
            $a_plth = array('TIEUHOC','THvaTHCS');
            $a_plthcs = array('THCS','THvaTHCS');
            if($maphanloai == 'GD')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plgd)->get();
            if($maphanloai == 'TIEUHOC')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plth)->get();
            if($maphanloai == 'THCS')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plthcs)->get();
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $a_phucap = array();
            $col = 0;
            $a_pldvgd = array_column(dmdonvi::select('madv')->wherein('maphanloai',$a_plgd)->get()->toarray(),'madv');
            $a_pldvth = array_column(dmdonvi::select('madv')->wherein('maphanloai',$a_plth)->get()->toarray(),'madv');
            $a_pldvthcs = array_column(dmdonvi::select('madv')->wherein('maphanloai',$a_plthcs)->get()->toarray(),'madv');
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
            if($maphanloai == 'GD')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvgd)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvgd)
                    ->get();
            if($maphanloai == 'TIEUHOC')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)->where('thang', $thang)->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvth)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)->where('thang', $thang)->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvth)->get();
            if($maphanloai == 'THCS')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvthcs)
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', $a_pldvthcs)
                    ->get();
            //dd($model_tonghop->toarray());
            $model_dmdv = dmdonvi::where('macqcq',$madv)
                ->wherein('madv',array_column($model_tonghop->toarray(),'madv'))->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_dmdv->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_bangluong::where('mact','like',$inputs['phanloaict'].'%')
                ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
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
                    $chitiet->$ma = $chitiet->$ct ;
                }
            }
            $model_data = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['mact', 'soluong', 'madv', 'maphanloai'])
                    ->all();
            });
            $a_soluong = a_unique($model_data);
            //dd($model->toarray());
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
            if($maphanloai == 'GD')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',getPhanLoaGD())->get();
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
            if($thang == 'ALL'){
                $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                    ->where('nam', $nam)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query) use ($maphanloai) {
                        $query->select('madv')->from('dmdonvi')
                            ->where('maphanloai','like', $maphanloai.'%')
                            ->get();
                    })->get();
                if($maphanloai == 'GD')
                    $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                        ->where('nam', $nam)
                        ->where('trangthai', 'DAGUI')
                        ->wherein('madv', function ($query) {
                            $query->select('madv')->from('dmdonvi')
                                ->wherein('maphanloai', function ($query) {
                                    $query->select('maphanloai')->from('dmphanloaidonvi')
                                        ->wherein('maphanloai',getPhanLoaGD())
                                        ->get();
                                })
                                ->get();
                        })
                        ->get();
            }
            if($thang != 'ALL' && $maphanloai == 'GD')
                $model_tonghop = tonghopluong_donvi::where('madvbc',$madvbc)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query){
                        $query->select('madv')->from('dmdonvi')
                            ->wherein('maphanloai', function ($query) {
                                $query->select('maphanloai')->from('dmphanloaidonvi')
                                    ->wherein('maphanloai',getPhanLoaGD())
                                    ->get();
                            })
                            ->get();
                    })
                    ->get();
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
                //$chitiet->hesoluong = $chitiet->heso/$a_luongcb[$chitiet->mathdv];
                $chitiet->hesoluong = $chitiet->heso;

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
                //$chitiet->hesoluong = $chitiet->heso/$a_luongcb[$chitiet->mathdv];
                $chitiet->hesoluong = $chitiet->heso;

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
            //dd($inputs);
            $madv = $inputs['donvi'];
            $thang = $inputs['tuthang'];
            $nam = $inputs['nam'];
            $check = dmdonvi::where('madv', $madv)->where('phanloaitaikhoan','TH')->get();
            if(count($check)>0) {
                if ($inputs['tuthang'] == 'ALL')
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
                else
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
            }
            else {
                if ($inputs['tuthang'] == 'ALL')
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->get();
                else
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->distinct()->get();
            }
            if(isset($m_mathdv)) {
                if(count($check)>0) {
                    //$mathh = array_column($m_mathdv->toArray(), 'mathdv');
                    $mathh =$m_mathdv->mathdv;
                    $a_math = tonghopluong_donvi::where('mathh', $mathh)->get();
                    $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_bangluong.*','thang')
                    ->wherein('tonghopluong_donvi_bangluong.mathdv',array_column($a_math->toarray(),'mathdv'))->get();
                    $model_thongtin = tonghopluong_donvi::where('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathh){
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->where('mathdv',$mathh)
                            ->get();
                    })->get()->toarray(),'report','mapc');
                }else{
                    $a_mathdv = array_column($m_mathdv->toArray(), 'mathdv');
                    $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_bangluong.*','thang')
                        ->wherein('tonghopluong_donvi_bangluong.mathdv', $a_mathdv)->get();
                    $model_thongtin = tonghopluong_donvi::wherein('mathdv', $a_mathdv)->first();
                    $m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');
                }

                //$model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                //$model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
                $model_chucvu = array_column(dmchucvucq::all()->toArray(), 'tencv', 'macvcq');
                $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                $a_thang = array_column($model->toArray(), 'thang', 'thang');
                //$gnr = getGeneralConfigs();

                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                    $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                    $chitiet->tencv = isset($model_chucvu[$chitiet->macvcq]) ? $model_chucvu[$chitiet->macvcq] : '';
                    $thanhtien = 0;
                    foreach (getColTongHop() as $ct) {
                        if ($chitiet->$ct > 50000) {
                            $thanhtien += $chitiet->$ct;
                        }
                    }
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }
                //dd($model->toarray());

                foreach (getColTongHop() as $ct) {
                    if ($model->sum($ct) > 0) {
                        $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                        $col++;
                    }
                }
                //dd($a_phucap);
                $thongtin = array('nguoilap'=>'','thang'=>'','nam'=>'');
                if(isset($model_thongtin))
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
                    ->with('a_thang', $a_thang)
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
    function chitraluong_ct_huyenCR(Request $request) {
        if (Session::has('admin')) {
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            $madv = $inputs['donvi'];
            $thang = $inputs['tuthang'];
            $nam = $inputs['nam'];
            $check = dmdonvi::where('madv', $madv)->where('phanloaitaikhoan','TH')->get();
            if(count($check)>0) {
                if ($inputs['tuthang'] == 'ALL')
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
                else
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
            }
            else {
                if ($inputs['tuthang'] == 'ALL')
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->get();
                else
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->distinct()->get();
            }

            if(isset($m_mathdv)) {
                if(count($check)>0) {
                    $mathh = array_column($m_mathdv->toArray(), 'mathdv');
                    $a_math = tonghopluong_donvi::wherein('mathh', $mathh)->get();
                    $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_bangluong.*','thang')
                    ->wherein('tonghopluong_donvi_bangluong.mathdv',array_column($a_math->toarray(),'mathdv'))->get();
                    $model_thongtin = tonghopluong_donvi::wherein('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function($query) use($mathh){
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi','dmdonvi.madv','tonghopluong_khoi.madv')->wherein('mathdv',$mathh)
                            ->get();
                    })->get()->toarray(),'report','mapc');
                }else{
                    $a_mathdv = array_column($m_mathdv->toArray(), 'mathdv');
                    $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi_bangluong.mathdv','tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_bangluong.*','thang')
                        ->wherein('tonghopluong_donvi_bangluong.mathdv', $a_mathdv)->get();
                    $model_thongtin = tonghopluong_donvi::wherein('mathdv', $a_mathdv)->first();
                    $m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');
                }
                //dd($model);
                //$model = tonghopluong_donvi_bangluong::where('mathdv', $m_mathdv->mathdv)->get();
                //$model_thongtin = tonghopluong_donvi::where('mathdv', $m_mathdv->mathdv)->first();
                $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
                $model_chucvu = array_column(dmchucvucq::all()->toArray(), 'tencv', 'macvcq');
                $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                $a_thang = array_column($model->toArray(), 'thang', 'thang');
                //$gnr = getGeneralConfigs();

                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                    $chitiet->tenct = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                    $chitiet->tencv = isset($model_chucvu[$chitiet->macvcq]) ? $model_chucvu[$chitiet->macvcq] : '';
                    $thanhtien = 0;
                    foreach (getColTongHop() as $ct) {
                        if ($chitiet->$ct > 50000) {
                            $thanhtien += $chitiet->$ct;
                        }
                    }
                    $chitiet->tongtl = $chitiet->tonghs * $chitiet->luongcoban + $thanhtien;
                }
                //dd($model->toarray());

                foreach (getColTongHop() as $ct) {
                    if($ct != 'heso')
                        if ($model->sum($ct) > 0) {
                            $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                            $col++;
                        }
                }
                //dd($a_phucap);
                $thongtin = array('nguoilap'=>'','thang'=>'','nam'=>'');
               if(isset($model_thongtin))
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
                return view('reports.tonghopluong.huyen.bangluongCR')
                    ->with('thongtin', $thongtin)
                    ->with('model', $model)
                    ->with('m_dv', $m_dv)
                    ->with('a_thang', $a_thang)
                    ->with('col', $col)
                    ->with('a_phucap', $a_phucap)
                    ->with('a_nguon', $a_nguon)
                    ->with('a_congtac', $a_congtac)
                    ->with('inputs', $inputs)
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
            if(count($check)>0) {
                if ($inputs['thang'] == 'ALL')
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
                else
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
            }
            else {
                if ($inputs['thang'] == 'ALL')
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
                else
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
            }
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
            $thang = $inputs['tuthang'];
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

    function chitraluong_ct_pl(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $madv = session('admin')->madv;
            $maphanloai = $inputs['phanloai'];
            $model_donvi = dmdonvi::where('macqcq',$madv)->get();
            $model_phanloai = dmphanloaidonvi::where('maphanloai','like',$maphanloai.'%')->get();
            $a_plgd = array('MAMNON','TIEUHOC','THCS','THvaTHCS');
            $a_plth = array('TIEUHOC','THvaTHCS');
            $a_plthcs = array('THCS','THvaTHCS');
            if($maphanloai == 'GD')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plgd)->get();
            if($maphanloai == 'TIEUHOC')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plth)->get();
            if($maphanloai == 'THCS')
                $model_phanloai = dmphanloaidonvi::wherein('maphanloai',$a_plthcs)->get();

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
            if($maphanloai == 'GD')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query) use ($a_plgd) {
                        $query->select('madv')->from('dmdonvi')
                            ->wherein('maphanloai',$a_plgd)
                            ->get();
                    })
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            if($maphanloai == 'TIEUHOC')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query) use ($a_plth) {
                        $query->select('madv')->from('dmdonvi')
                            ->wherein('maphanloai',$a_plth)
                            ->get();
                    })
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            if($maphanloai == 'THCS')
                $model_tonghop = tonghopluong_donvi::where('macqcq',$madv)
                    ->where('nam', $nam)
                    ->where('thang', $thang)
                    ->where('trangthai', 'DAGUI')
                    ->wherein('madv', function ($query) use ($a_plthcs) {
                        $query->select('madv')->from('dmdonvi')
                            ->wherein('maphanloai',$a_plthcs)
                            ->get();
                    })
                    ->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            $model_dmdv = dmdonvi::where('macqcq',$madv)
                ->wherein('madv',array_column($model_tonghop->toarray(),'madv'))->orWhereIn('macqcq', array_column($model_donvi->toarray(),'madv'))->get();
            $a_dv = array_column($model_tonghop->toarray(),'madv','mathdv');
            $a_pl = array_column($model_dmdv->toarray(),'maphanloai','madv');
            $model = tonghopluong_donvi_bangluong::where('mact','like',$inputs['phanloaict'].'%')
            ->wherein('mathdv', array_column($model_tonghop->toarray(),'mathdv'))->get();
            /*
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


                //$gnr = getGeneralConfigs();
                */
            $m_pc = array_column(dmphucap::all()->toarray(),'report','mapc');
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
                //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
                $m_dv = dmdonvi::where('madv', $madv)->first();
                $a_phucap = array();
                $col = 0;
                //$m_pc = array_column(dmphucap_donvi::where('madv', $madv)->get()->toarray(), 'report', 'mapc');

                foreach ($model as $chitiet) {
                    $chitiet->madv = $a_dv[$chitiet->mathdv];
                    $chitiet->tendv = $model_donvi->where('madv',$chitiet->madv)->first()->tendv;

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
                    'thang' => $inputs['tuthang'],
                    'nam' => $inputs['tunam']);


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
                $model_dv = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['madv','tendv','manguonkp','mact','tennguonkp','tenct'])
                        ->all();
                });
                $a_dv = a_unique($model_dv);

                return view('reports.tonghopluong.khoi.bangluong_pl')
                    ->with('thongtin', $thongtin)
                    ->with('model', $model)
                    ->with('m_dv', $m_dv)
                    ->with('col', $col)
                    ->with('a_phucap', $a_phucap)
                    ->with('a_nguon', $a_nguon)
                    ->with('a_congtac', $a_congtac)
                    ->with('model_dmdv', $model_dmdv)
                    ->with('a_dv', $a_dv)
                    ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');

        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_khoim_excel(Request $request) {
        if (Session::has('admin')) {
            //dd($mathdv);
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            $madv = $inputs['donvi'];
            $thang = $inputs['tuthang'];
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
                        return view('reports.tonghopluong.khoi.bangluong')
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

            //$model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->where('phanloaitaikhoan','<>','TH')->get();

            if($inputs['phanloai'] != "GD" && $inputs['phanloai'] != '')
                $model_donvi = $model_donvi->where('maphanloai',$inputs['phanloai']);
            if(isset($inputs['phanloai']) && $inputs['phanloai'] == "GD")
                $model_donvi = $model_donvi->wherein('maphanloai ',getPhanLoaGD());
            $model_dutoan=dutoanluong::where('namns',$inputs['namns'])
                ->where('trangthai','DAGUI')
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
            $m_slcb = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
            ->select('canbo_congtac','madv')->wherein('dutoanluong_chitiet.masodv', array_column($model_dutoan->toarray(),'masodv'))->get();
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'namns'=>$inputs['namns'],
                'madvbc'=>$madvbc);
            return view('reports.mauchung.huyen.dutoanluong')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_donvi',$model_donvi)
                ->with('model_bienche_dutoan',$model_bienche_dutoan)
                ->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('m_slcb',$m_slcb)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('pageTitle','Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }
    function dutoanluong_huyen_CR(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_khoipb = dmkhoipb::all();
            $model_phanloai = dmphanloaidonvi::join('dmdonvi','dmdonvi.maphanloai','dmphanloaidonvi.maphanloai')
                ->select('madv','dmphanloaidonvi.maphanloai','tenphanloai','linhvuchoatdong')
                ->where('madvbc',$madvbc)
                ->where('dmphanloaidonvi.maphanloai','like',$inputs['phanloai'].'%')
                ->get();
            if($inputs['phanloai'] == 'GD')
                $model_phanloai = dmphanloaidonvi::join('dmdonvi','dmdonvi.maphanloai','dmphanloaidonvi.maphanloai')
                    ->select('madv','dmphanloaidonvi.maphanloai','tenphanloai','linhvuchoatdong')
                    ->where('madvbc',$madvbc)
                    ->wherein('dmdonvi.maphanloai',getPhanLoaGD())
                    ->get();
            $model_th = dutoanluong_bangluong::join('dutoanluong','dutoanluong.masodv','dutoanluong_bangluong.masodv')
                ->Select('mact','madv','macongtac',DB::raw('avg(dutoanluong_bangluong.luongcoban) as luongcoban'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(luongtn) as luongtn'),
                DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('mact')
                ->get();
            $model_slth = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('mact','madv',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('mact')
                ->get();
            $modelctbc = chitieubienche::select('mact','madv','soluongduocgiao')
                ->where('nam',$inputs['namns'])
                ->wherein('madv', array_column($model_th->toarray(),'madv'))
                ->get();
            //dd($modelctbc->toarray());
            //dd($model_soluong->toarray());
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $m_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'report', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model_th->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            unset($a_phucap['heso']);
            $col = $col -1;
            foreach($model_th as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $m = $model_slth->where('mact',$ct->mact)->first();
                $ct->soluonggiao = $modelctbc->where('mact',$ct->mact)->sum('soluongduocgiao');
                if(isset($m)){
                    $ct->soluongcomat = $model_slth->where('mact',$ct->mact)->first()->canbo_congtac;
                }else{
                    $ct->soluongcomat = 0;
                }
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;

            }
            //dd($model_th->toarray());
            $model = dutoanluong_bangluong::join('dutoanluong','dutoanluong.masodv','dutoanluong_bangluong.masodv')
                ->Select('dutoanluong.madv','macongtac','mact',DB::raw('avg(dutoanluong_bangluong.luongcoban) as luongcoban'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(luongtn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('dutoanluong.madv','mact')
                ->get();
            $model_sl = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('dutoanluong.madv','mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('dutoanluong.madv','mact')
                ->get();
            foreach($model as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv',$ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv',$ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai',$ct->maphanloai)->first();
                if(count((array)$m) > 0)
                    $ct->tenphanloai = $m->tenphanloai;
                else
                    $ct->tenphanloai = "";

                $ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb',$ct->linhvuchoatdong)->first();
                if(count((array)$m) > 0)
                    $ct->tenlinhvuchoatdong = $m->tenkhoipb;
                else
                    $ct->tenlinhvuchoatdong = "";
                $m = $model_sl->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                $msl = $modelctbc->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                $ct->soluonggiao = isset($msl)?$msl->soluongduocgiao:0;
                if(count((array)$m) > 0)
                {
                    $ct->soluongcomat = $m->canbo_congtac;
                }
                else
                {
                    $ct->soluongcomat = 0;
                }
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }

            //dd($model->toarray());
            //dd($col);
            //$model_tongso = $model_th->
            //dd($model_th->toarray());

            //Tính toán Hoạt động phí HĐND

            $model_hdnd = dutoanluong_bangluong::join('dutoanluong','dutoanluong.masodv','dutoanluong_bangluong.masodv')
                ->Select('mact','macongtac',DB::raw('avg(dutoanluong_bangluong.luongcoban) as luongcoban'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso-pckn) as tongpc'),DB::raw('sum(tonghs-pckn) as tonghs')
                    ,DB::raw('sum(hesopc) as pccv'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(dutoanluong_bangluong.luongcoban*hesopc) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact')
                ->get();

            $model_slhdnd = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact')
                ->get();
            foreach($model_hdnd as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $m = $model_slhdnd->where('mact','1536402868')->first();
                $msl = $modelctbc->where('mact','1536402868')->first();
                $ct->soluonggiao = isset($msl)?$msl->soluongduocgiao:0;
                if(isset($m)){

                    $ct->soluongcomat = $model_slhdnd->where('mact','1536402868')->first()->canbo_congtac;
                }else{
                    $ct->soluongcomat = 0;
                }
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;

            }
            $model_kn = dutoanluong_bangluong::join('dutoanluong','dutoanluong.masodv','dutoanluong_bangluong.masodv')
                ->Select('mact',DB::raw('avg(dutoanluong_bangluong.luongcoban) as luongcoban'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso-hesopc) as tongpc'),DB::raw('sum(tonghs-hesopc) as tonghs')
                   ,DB::raw('sum(pckn) as pccv'),DB::raw('sum(dutoanluong_bangluong.luongcoban*pckn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact')
                ->get();
            foreach($model_kn as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->soluonggiao = 0;
                $ct->soluongcomat = 0;
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }
            $model_uv = dutoanluong_bangluong::join('dutoanluong','dutoanluong.masodv','dutoanluong_bangluong.masodv')
                ->Select('mact',DB::raw('avg(dutoanluong_bangluong.luongcoban) as luongcoban'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs')
                    ,DB::raw('sum(hesopc) as pccv'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(dutoanluong_bangluong.luongcoban*hesopc) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536459380')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact')
                ->get();
            foreach($model_uv as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $m = $model_slhdnd->where('mact','1536459380')->first();
                $msl = $modelctbc->where('mact','1536459380')->first();
                $ct->soluonggiao = isset($msl)?$msl->soluongduocgiao:0;
                if(isset($m)){
                    $ct->soluongcomat = $model_slhdnd->where('mact','1536459380')->first()->canbo_congtac;
                }else{
                    $ct->soluongcomat = 0;
                }
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }

            //Tính toán phần xã phường
            $model_xp = dutoanluong_bangluong::join('dutoanluong','dutoanluong.masodv','dutoanluong_bangluong.masodv')
                ->Select('dutoanluong.madv','mact',DB::raw('avg(dutoanluong_bangluong.luongcoban) as luongcoban'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(luongtn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->wherein('mact',['1506672780','1506673604','1506673695','1535613221'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('dutoanluong.madv','mact')
                ->get();
            $model_slxp = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('dutoanluong.madv','mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->wherein('mact',['1506673604','1506673695','1535613221','1506672780'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('dutoanluong.madv','mact')
                ->get();
            foreach($model_xp as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv',$ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv',$ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai',$ct->maphanloai)->first();
                if(count((array)$m) > 0)
                    $ct->tenphanloai = $m->tenphanloai;
                else
                    $ct->tenphanloai = "";

                $ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb',$ct->linhvuchoatdong)->first();
                if(count((array)$m) > 0)
                    $ct->tenlinhvuchoatdong = $m->tenkhoipb;
                else
                    $ct->tenlinhvuchoatdong = "";
                $m = $model_slxp->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                $msl = $modelctbc->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                $ct->soluonggiao = isset($msl)?$msl->soluongduocgiao:0;
                if(count((array)$m) > 0)
                {
                    $ct->soluongcomat = $m->canbo_congtac;
                }
                else
                {
                    $ct->soluongcomat = 0;
                }
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }
            //dd($model_slxp->toarray());
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
            $a_lv = array('QLNN','DDT','DOANTHE','DANG','LVXH','LVCT');
            $modelqlnn = $model->wherein('linhvuchoatdong',$a_lv)
                ->wherein('madv', array_column($model_phanloai->where('maphanloai','<>','KVXP')->toarray(),'madv'));
            $a_lv = array('QLNN','DDT','DOANTHE','DANG','LVXH','LVCT');
            $model_thbc= $model_th->groupby('macongtac');
            $model_hcsn = $model->where('maphanloai','<>','KVXP')
            ->where('linhvuchoatdong','<>','GD')
            ->where('linhvuchoatdong','<>','QLNN')
            ->where('linhvuchoatdong','<>','DDT')
            ->where('linhvuchoatdong','<>','DOANTHE')
            ->where('linhvuchoatdong','<>','DANG')
            ->where('linhvuchoatdong','<>','LVXH')
            ->where('linhvuchoatdong','<>','LVCT');
            //->groupby('tencongtac');
            //dd($model_hcsn->toarray());
            $a_plct = dmphanloaicongtac::wherein('macongtac', array_column($model_th->toarray(),'macongtac'))->get();
            return view('reports.dutoanluong.Huyen.dutoanCR')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_th',$model_th)
                ->with('model_donvi',$model_donvi)
                ->with('model_bienche_dutoan',$model_bienche_dutoan)
                ->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('model',$model)
                ->with('modelqlnn',$modelqlnn)
                ->with('model_hdnd',$model_hdnd)
                ->with('model_kn',$model_kn)
                ->with('model_uv',$model_uv)
                ->with('model_xp',$model_xp)
                ->with('model_phanloai',$model_phanloai)
                ->with('a_phucap',$a_phucap)
                ->with('col',$col)
                ->with('nam',$inputs['namns'])
                ->with('modelctbc',$modelctbc)
                ->with('model_thbc',$model_thbc)
                ->with('model_hcsn',$model_hcsn)
                ->with('a_plct',$a_plct)
                ->with('pageTitle','Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function tonghopluong_huyen_CR(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_khoipb = dmkhoipb::all();
            $model_phanloai = dmphanloaidonvi::join('dmdonvi','dmdonvi.maphanloai','dmphanloaidonvi.maphanloai')
                ->select('madv','dmphanloaidonvi.maphanloai','tenphanloai','linhvuchoatdong')
                ->where('madvbc',$madvbc)
                ->where('dmphanloaidonvi.maphanloai','like',$inputs['phanloai'].'%')
                ->get();
            if($inputs['phanloai'] == 'GD')
                $model_phanloai = dmphanloaidonvi::join('dmdonvi','dmdonvi.maphanloai','dmphanloaidonvi.maphanloai')
                    ->select('madv','dmphanloaidonvi.maphanloai','tenphanloai','linhvuchoatdong')
                    ->where('madvbc',$madvbc)
                    ->wherein('dmdonvi.maphanloai',getPhanLoaGD())
                    ->get();
            $model_th = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_bangluong.mathdv')
                ->Select('mact',DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('mact')
                ->get();
            /*
            $model_slth = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_chitiet.mathdv')
                ->select('mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('mact')
                ->get();
            */
            //dd($model_soluong->toarray());

            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $m_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'report', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model_th->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            unset($a_phucap['heso']);
            $col = $col -1;
            foreach($model_th as $ct)
            {
                $ct->soluongcomat = $ct->soluong;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
                //$ct->soluonggiao = $model_slth->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slth->where('mact',$ct->mact)->first()->canbo_congtac;
            }
            $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_bangluong.mathdv')
                ->Select('tonghopluong_donvi.madv','mact','linhvuchoatdong',DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('tonghopluong_donvi.madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('tonghopluong_donvi.madv','mact','linhvuchoatdong')
                ->get();
            /*
            $model_sl = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_chitiet.mathdv')
                ->select('tonghopluong_donvi.madv','mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('tonghopluong_donvi.madvbc',$madvbc)
                ->where('tonghopluong_donvi.trangthai','DAGUI')
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('tonghopluong_donvi.madv','mact')
                ->get();
            */
            foreach($model as $ct)
            {
                $ct->soluongcomat = $ct->soluong;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv',$ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv',$ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai',$ct->maphanloai)->first();
                if(count((array)$m) > 0)
                    $ct->tenphanloai = $m->tenphanloai;
                else
                    $ct->tenphanloai = "";

                //$ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb',$ct->linhvuchoatdong)->first();
                if(count((array)$m) > 0)
                    $ct->tenlinhvuchoatdong = $m->tenkhoipb;
                else
                    $ct->tenlinhvuchoatdong = "";
                /*
                $m = $model_sl->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                if(count($m) > 0)
                {
                    $ct->soluonggiao = $m->canbo_dutoan;
                    $ct->soluongcomat = $m->canbo_congtac;
                }
                else
                {
                    $ct->soluonggiao = 0;
                    $ct->soluongcomat = 0;
                }
                */
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }

            //dd($model->toarray());

            //dd($a_phucap);
            //$model_tongso = $model_th->
            //dd($model_th->toarray());

            //Tính toán Hoạt động phí HĐND

            $model_hdnd = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_bangluong.mathdv')
                ->Select('mact',DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso-pckn) as tongpc'),DB::raw('sum(tonghs-pckn) as tonghs')
                    ,DB::raw('sum(hesopc) as pccv'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(tonghopluong_donvi_bangluong.luongcoban*hesopc) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('tonghopluong_donvi.madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->groupby('mact')
                ->get();
            /*
            $model_slhdnd = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_chitiet.mathdv')
                ->select('mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('tonghopluong_donvi.madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->groupby('mact')
                ->get();
            */
            foreach($model_hdnd as $ct)
            {
                $ct->soluongcomat = $ct->soluong;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_congtac;
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }
            $model_kn = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_bangluong.mathdv')
                ->Select('mact',DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso-hesopc) as tongpc'),DB::raw('sum(tonghs-hesopc) as tonghs')
                    ,DB::raw('sum(pckn) as pccv'),DB::raw('sum(tonghopluong_donvi_bangluong.luongcoban*pckn) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->groupby('mact')
                ->get();
            foreach($model_kn as $ct)
            {
                $ct->soluongcomat = $ct->soluong;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->soluonggiao = 0;
                $ct->soluongcomat = 0;
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }
            $model_uv = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_bangluong.mathdv')
                ->Select('mact',DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs')
                    ,DB::raw('sum(hesopc) as pccv'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(tonghopluong_donvi_bangluong.luongcoban*hesopc) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('tonghopluong_donvi.madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536459380')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->groupby('mact')
                ->get();
            foreach($model_uv as $ct)
            {
                $ct->soluongcomat = $ct->soluong;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_congtac;
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }

            //Tính toán phần xã phường
            $model_xp = tonghopluong_donvi_bangluong::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_bangluong.mathdv')
                ->Select('tonghopluong_donvi.madv','mact',DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->wherein('mact',['1506672780','1506673604','1506673695','1535613221'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->where('maphanloai','KVXP')->toarray(),'madv'))
                ->where('nam',$inputs['tunam'])
                ->where('thang',$inputs['tuthang'])
                ->groupby('tonghopluong_donvi.madv','mact')
                ->get();
            /*
            $model_slxp = tonghopluong_donvi_chitiet::join('tonghopluong_donvi','tonghopluong_donvi.mathdv','tonghopluong_donvi_chitiet.mathdv')
                ->select('dutoanluong.madv','mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->wherein('mact',['1506673604','1506673695','1535613221'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('tonghopluong_donvi.madv','mact')
                ->get();
            */
            foreach($model_xp as $ct)
            {
                $ct->soluongcomat = $ct->soluong;
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv',$ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv',$ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai',$ct->maphanloai)->first();
                if(count((array)$m) > 0)
                    $ct->tenphanloai = $m->tenphanloai;
                else
                    $ct->tenphanloai = "";

                $ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb',$ct->linhvuchoatdong)->first();
                if(count((array)$m) > 0)
                    $ct->tenlinhvuchoatdong = $m->tenkhoipb;
                else
                    $ct->tenlinhvuchoatdong = "";
                /*
                $m = $model_slxp->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                if(count($m) > 0)
                {
                    $ct->soluonggiao = $m->canbo_dutoan;
                    $ct->soluongcomat = $m->canbo_congtac;
                }
                else
                {
                    $ct->soluonggiao = 0;
                    $ct->soluongcomat = 0;
                }
                */
                foreach($a_phucap as $key=>$val) {
                    if($ct->$key > 10000)
                        $ct->$key = $ct->$key/$ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv/$ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv/$ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv/$ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv/$ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv+ $ct->stbhtn_dv)*$ct->luongcoban;
            }
            //dd($model_kn->toarray());
            $model_dutoan=tonghopluong_donvi::where('nam',$inputs['tunam'])
                ->wherein('madv',function($qr)use($madvbc){
                    $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc);
                })->get();
            /*
            $model_bienche_dutoan=chitieubienche::where('nam',$inputs['tunam'])
                ->wherein('madv',function($qr)use($madvbc){
                    $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc);
                })->get();
            $model_bienche_truoc=chitieubienche::where('nam',$inputs['namns'] - 1)
                ->wherein('madv',function($qr)use($madvbc){
                    $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc);
                })->get();
            */
            $m_dv=dmdonvi::where('madv',session('admin')->madv)->first();
            $thongtin=array('nguoilap'=>session('admin')->name,
                'nam'=>$inputs['tunam'],
                'madvbc'=>$madvbc);
            $a_lv = array('QLNN','DDT','DOANTHE','DANG','LVXH','LVCT');
            $modelqlnn = $model->wherein('linhvuchoatdong',$a_lv)
                ->wherein('madv', array_column($model_phanloai->where('maphanloai','<>','KVXP')->toarray(),'madv'));
            $a_lv = array('QLNN','DDT','DOANTHE','DANG','LVXH','LVCT');
            //$modelhcsn = $model->whereNOTIn('linhvuchoatdong',$a_lv)
            //   ->wherein('madv', array_column($model_phanloai->where('maphanloai','<>','KVXP')->toarray(),'madv'));
            return view('reports.tonghopluong.huyen.tonghopluongCR')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_th',$model_th)
                ->with('model_donvi',$model_donvi)
                //->with('model_bienche_dutoan',$model_bienche_dutoan)
                //->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('model',$model)
                ->with('modelqlnn',$modelqlnn)
                //->with('modelhcsn',$modelhcsn)
                ->with('model_hdnd',$model_hdnd)
                ->with('model_kn',$model_kn)
                ->with('model_uv',$model_uv)
                ->with('model_xp',$model_xp)
                ->with('model_phanloai',$model_phanloai)
                ->with('a_phucap',$a_phucap)
                ->with('col',$col)
                ->with('nam',$inputs['tunam'])
                ->with('thang',$inputs['tuthang'])
                ->with('pageTitle','Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function nguonkinhphi_huyen_CR(Request $request) {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = session('admin')->madvbc;
            $model_donvi = dmdonvi::where('madvbc',$madvbc)->get();
            $model_khoipb = dmkhoipb::all();
            $model_phanloai = dmphanloaidonvi::join('dmdonvi','dmdonvi.maphanloai','dmphanloaidonvi.maphanloai')
                ->select('madv','dmphanloaidonvi.maphanloai','tenphanloai','linhvuchoatdong')
                ->where('madvbc',$madvbc)
                ->where('dmphanloaidonvi.maphanloai','like',$inputs['phanloai'].'%')
                ->get();
            if($inputs['phanloai'] == 'GD')
                $model_phanloai = dmphanloaidonvi::join('dmdonvi','dmdonvi.maphanloai','dmphanloaidonvi.maphanloai')
                    ->select('madv','dmphanloaidonvi.maphanloai','tenphanloai','linhvuchoatdong')
                    ->where('madvbc',$madvbc)
                    ->wherein('dmdonvi.maphanloai',getPhanLoaGD())
                    ->get();
            $model_th = nguonkinhphi_bangluong::join('nguonkinhphi','nguonkinhphi.masodv','nguonkinhphi_bangluong.masodv')
                ->Select('mact','nguonkinhphi_bangluong.linhvuchoatdong',DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(luongtn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('mact','nguonkinhphi_bangluong.linhvuchoatdong')
                ->get();
            /*
            $model_slth = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('mact')
                ->get();
            */
            //dd($model_soluong->toarray());
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $m_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'report', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach($model_th as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slth->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slth->where('mact',$ct->mact)->first()->canbo_congtac;
            }
            $model = nguonkinhphi_bangluong::join('nguonkinhphi','nguonkinhphi.masodv','nguonkinhphi_bangluong.masodv')
                ->Select('nguonkinhphi.madv','nguonkinhphi_bangluong.linhvuchoatdong','mact',DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(luongtn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('nguonkinhphi.madv','nguonkinhphi_bangluong.linhvuchoatdong','mact')
                ->get();
            /*
            $model_sl = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('dutoanluong.madv','mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('namns',$inputs['namns'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->groupby('dutoanluong.madv','mact')
                ->get();
            */
            foreach($model as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv',$ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv',$ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai',$ct->maphanloai)->first();
                if(count($m) > 0)
                    $ct->tenphanloai = $m->tenphanloai;
                else
                    $ct->tenphanloai = "";

                $ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb',$ct->linhvuchoatdong)->first();
                if(count($m) > 0)
                    $ct->tenlinhvuchoatdong = $m->tenkhoipb;
                else
                    $ct->tenlinhvuchoatdong = "";
                /*
                $m = $model_sl->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                if(count($m) > 0)
                {
                    $ct->soluonggiao = $m->canbo_dutoan;
                    $ct->soluongcomat = $m->canbo_congtac;
                }
                else
                {
                    $ct->soluonggiao = 0;
                    $ct->soluongcomat = 0;
                }
                */
            }

            //dd($model->toarray());
            foreach (getColTongHop() as $ct) {
                if ($model_th->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            //dd($col);
            //dd($model_th->toarray());

            //Tính toán Hoạt động phí HĐND

            $model_hdnd = nguonkinhphi_bangluong::join('nguonkinhphi','nguonkinhphi.masodv','nguonkinhphi_bangluong.masodv')
                ->Select('mact','nguonkinhphi_bangluong.linhvuchoatdong',DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso-pckn) as tongpc'),DB::raw('sum(tonghs-pckn) as tonghs')
                    ,DB::raw('sum(hesopc) as pccv'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(nguonkinhphi_bangluong.luongcoban*hesopc) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact','nguonkinhphi_bangluong.linhvuchoatdong')
                ->get();
            /*
            $model_slhdnd = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact')
                ->get();
            */
            foreach($model_hdnd as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_congtac;
            }
            $model_kn = nguonkinhphi_bangluong::join('nguonkinhphi','nguonkinhphi.masodv','nguonkinhphi_bangluong.masodv')
                ->Select('mact','nguonkinhphi_bangluong.linhvuchoatdong',DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso-hesopc) as tongpc'),DB::raw('sum(tonghs-hesopc) as tonghs')
                    ,DB::raw('sum(pckn) as pccv'),DB::raw('sum(nguonkinhphi_bangluong.luongcoban*pckn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536402868')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact','nguonkinhphi_bangluong.linhvuchoatdong')
                ->get();
            foreach($model_kn as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->soluonggiao = 0;
                $ct->soluongcomat = 0;
            }
            $model_uv = nguonkinhphi_bangluong::join('nguonkinhphi','nguonkinhphi.masodv','nguonkinhphi_bangluong.masodv')
                ->Select('mact','nguonkinhphi_bangluong.linhvuchoatdong',DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs')
                    ,DB::raw('sum(hesopc) as pccv'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(nguonkinhphi_bangluong.luongcoban*hesopc) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->where('mact','1536459380')
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('mact','nguonkinhphi_bangluong.linhvuchoatdong')
                ->get();
            foreach($model_uv as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_congtac;
            }

            //Tính toán phần xã phường
            $model_xp = nguonkinhphi_bangluong::join('nguonkinhphi','nguonkinhphi.masodv','nguonkinhphi_bangluong.masodv')
                ->Select('nguonkinhphi.madv','nguonkinhphi_bangluong.linhvuchoatdong','mact',DB::raw('sum(heso) as heso'),DB::raw('sum(tonghs-heso) as tongpc'),DB::raw('sum(tonghs) as tonghs'),DB::raw('sum(hesobl) as hesobl')
                    ,DB::raw('sum(hesott) as hesott'),DB::raw('sum(hesopc) as hesopc'),DB::raw('sum(vuotkhung) as vuotkhung'),DB::raw('sum(pcct) as pcct'),DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),DB::raw('sum(pccv) as pccv'),DB::raw('sum(pckv) as pckv'),DB::raw('sum(pcth) as pcth'),DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),DB::raw('sum(pcld) as pcld'),DB::raw('sum(pcdbqh) as pcdbqh'),DB::raw('sum(pcudn) as pcudn'),DB::raw('sum(pctn) as pctn')
                    ,DB::raw('sum(pctnn) as pctnn'),DB::raw('sum(pcdbn) as pcdbn'),DB::raw('sum(pcvk) as pcvk'),DB::raw('sum(pckn) as pckn'),DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),DB::raw('sum(pclt) as pclt'),DB::raw('sum(pcd) as pcd'),DB::raw('sum(pctr) as pctr'),DB::raw('sum(pctdt) as pctdt')
                    ,DB::raw('sum(pctnvk) as pctnvk'),DB::raw('sum(pcbdhdcu) as pcbdhdcu'),DB::raw('sum(pcthni) as pcthni'),DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),DB::raw('sum(pcxaxe) as pcxaxe'),DB::raw('sum(pcdith) as pcdith'),DB::raw('sum(pcphth) as pcphth'),DB::raw('sum(luongtn) as luongtn'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),DB::raw('sum(stbhyt_dv) as stbhyt_dv'),DB::raw('sum(stbhtn_dv) as stbhtn_dv'),DB::raw('sum(stkpcd_dv) as stkpcd_dv')
                    ,DB::raw('sum(ttbh_dv) as ttbh_dv'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->wherein('mact',['1506672780','1506673604','1506673695','1535613221'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('nguonkinhphi.madv','nguonkinhphi_bangluong.linhvuchoatdong','mact')
                ->get();
            /*
            $model_slxp = dutoanluong_chitiet::join('dutoanluong','dutoanluong.masodv','dutoanluong_chitiet.masodv')
                ->select('dutoanluong.madv','mact',DB::raw('sum(canbo_congtac) as canbo_congtac'),DB::raw('sum(canbo_dutoan) as canbo_dutoan'))
                ->where('madvbc',$madvbc)
                ->where('trangthai','DAGUI')
                ->wherein('mact',['1506673604','1506673695','1535613221'])
                ->wherein('madv', array_column($model_phanloai->toarray(),'madv'))
                ->where('namns',$inputs['namns'])
                ->groupby('dutoanluong.madv','mact')
                ->get();
            */
            foreach($model_xp as $ct)
            {
                if($ct->mact == null){
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                }else{
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv',$ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv',$ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai',$ct->maphanloai)->first();
                if(count($m) > 0)
                    $ct->tenphanloai = $m->tenphanloai;
                else
                    $ct->tenphanloai = "";

                $ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb',$ct->linhvuchoatdong)->first();
                if(count($m) > 0)
                    $ct->tenlinhvuchoatdong = $m->tenkhoipb;
                else
                    $ct->tenlinhvuchoatdong = "";
                /*
                $m = $model_slxp->where('madv',$ct->madv)->where('mact',$ct->mact)->first();
                if(count($m) > 0)
                {
                    $ct->soluonggiao = $m->canbo_dutoan;
                    $ct->soluongcomat = $m->canbo_congtac;
                }
                else
                {
                    $ct->soluonggiao = 0;
                    $ct->soluongcomat = 0;
                }
                */
            }
            //dd($model_kn->toarray());
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
            return view('reports.nguonkinhphi.huyen.nguonkinhphiCR')
                ->with('model_dutoan',$model_dutoan)
                ->with('model_th',$model_th)
                ->with('model_donvi',$model_donvi)
                ->with('model_bienche_dutoan',$model_bienche_dutoan)
                ->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin',$thongtin)
                ->with('m_dv',$m_dv)
                ->with('model',$model)
                ->with('model_hdnd',$model_hdnd)
                ->with('model_kn',$model_kn)
                ->with('model_uv',$model_uv)
                ->with('model_xp',$model_xp)
                ->with('model_phanloai',$model_phanloai)
                ->with('a_phucap',$a_phucap)
                ->with('col',$col)
                ->with('nam',$inputs['namns'])
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
                    $model_nangluong = dsnangthamnien::join('dsnangthamnien_chitiet', 'dsnangthamnien_chitiet.manl', '=', 'dsnangthamnien.manl')
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

    function dsnangluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $a_cv = getChucVuCQ(false);
            $m_cb = hosocanbo::select('macanbo', 'tencanbo', 'macvcq', 'msngbac', 'ngaytu', 'ngayden', 'tnntungay', 'tnndenngay', 'bac', 'heso', 'vuotkhung', 'pctnn')
                ->where('madv', session('admin')->madv)
                ->where('theodoi', '<', '9');
                //->get()->keyBy('macanbo')->toArray();
            //dd($m_cb);
            if ($inputs['phanloai'] == 'TNN') {
                if(isset($inputs['indanangluong'])){
                    $m_cb->where(function ($qr) use ($inputs){
                        $qr->wherebetween('tnndenngay', [$inputs['ngaytu'], $inputs['ngayden']])
                            ->orwherebetween('tnntungay', [$inputs['ngaytu'], $inputs['ngayden']]);
                    });
                }else{
                    $m_cb->wherebetween('tnndenngay', [$inputs['ngaytu'], $inputs['ngayden']]);
                }
            }else {
                if (isset($inputs['indanangluong'])) {
                    $m_cb->wherenotnull('msngbac')
                        ->where(function ($qr) use ($inputs) {
                            $qr->wherebetween('ngayden', [$inputs['ngaytu'], $inputs['ngayden']])
                                ->orwherebetween('ngaytu', [$inputs['ngaytu'], $inputs['ngayden']]);
                        });
                } else {
                    $m_cb->wherenotnull('msngbac')
                        ->wherebetween('ngayden', [$inputs['ngaytu'], $inputs['ngayden']]);
                }
            }
            $model = $m_cb->get();
            //dd($model);
            if ($inputs['phanloai'] == 'TNN') {
                foreach ($model as $key => $ct) {
                    $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                    if ($ct->tnndenngay <= $inputs['ngayden']) {
                        $ct->trangthai = 'CHUANANGLUONG';
                        $ct->pctnn_m = $ct->pctnn == 0 ? 5 : $ct->pctnn + 1;
                    } else {
                        if($ct->pctnn == 5){
                            $model->forget($key);
                            continue;
                        }
                        $ct->trangthai = 'DANANGLUONG';
                        $ct->pctnn_m = $ct->pctnn;
                        $ct->pctnn = $ct->pctnn_m - 1 == 5 ? 0 : $ct->pctnn_m - 1;
                        $ct->tnndenngay = $ct->tnntungay;
                    }
                }

                $a_pl = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['trangthai'])
                        ->all();
                });
                //dd($a_pl);
                return view('reports.donvi.nangluong_tnn')
                    ->with('model', $model->sortby('tnndenngay'))
                    ->with('m_dv', $m_dv)
                    ->with('a_pl', a_unique($a_pl))
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách cán bộ');

            } else {
                $a_nb = ngachluong::all()->keyby('msngbac')->toarray();

                foreach ($model as $key=>$ct) {
                    $ct->tencv = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                    if ($ct->ngayden <= $inputs['ngayden']) {
                        $ct->trangthai = 'CHUANANGLUONG';
                        $ct->bac_m = 0;
                        $ct->heso_m = 0;
                        $ct->vuotkhung_m = 0;
                        if (isset($a_nb[$ct->msngbac])) {
                            $ngachluong = $a_nb[$ct->msngbac];
                            if ($ct->heso < $ngachluong['hesolonnhat']) {//nâng lương ngạch bậc
                                $ct->heso_m = $ct->heso + $ngachluong['hesochenhlech'];
                                $ct->bac_m = $ct->bac < $ngachluong['baclonnhat'] ? $ct->bac + 1 : $ngachluong['baclonnhat'];
                            } else {//vượt khung
                                if ($ct->vuotkhung == 0) {//lần đầu
                                    $ct->vuotkhung_m = $ngachluong['vuotkhung'];
                                } else {
                                    $ct->vuotkhung_m = $ct->vuotkhung + 1;
                                }
                            }
                        }

                    } else {
                        if($ct->bac == 1){
                            $model->forget($key);
                            continue;
                        }
                        $ct->trangthai = 'DANANGLUONG';
                        $ct->bac_m = $ct->bac;
                        $ct->heso_m = $ct->heso;
                        $ct->vuotkhung_m = $ct->vuotkhung;
                        $ct->ngayden = $ct->ngaytu;

                        if (isset($a_nb[$ct->msngbac])) {
                            $ngachluong = $a_nb[$ct->msngbac];
                            if ($ct->heso < $ngachluong['hesolonnhat']) {//nâng lương ngạch bậc
                                $ct->heso = $ct->heso - $ngachluong['hesochenhlech'];
                                $ct->bac = $ct->bac - 1;
                                $ct->vuotkhung = 0;
                            } else {//vượt khung
                                $ct->vuotkhung = $ct->vuotkhung == $ngachluong['vuotkhung'] ? 0 : $ct->vuotkhung - 1;
                                $ct->heso_m = $ct->heso;
                                $ct->bac_m = $ct->bac;
                            }
                        }
                    }
                }

                //dd($model);
                $a_pl = $model->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['trangthai'])
                        ->all();
                });

                return view('reports.donvi.nangluong_ngachbac')
                    //->with('model',$model->sortby('ngayden')->sortby('stt'))
                    ->with('model', $model->sortby('ngayden'))
                    ->with('m_dv', $m_dv)
                    ->with('a_pl', a_unique($a_pl))
                    ->with('inputs', $inputs)
                    ->with('pageTitle', 'Danh sách cán bộ');
            }
        } else
            return view('errors.notlogin');
    }
}
