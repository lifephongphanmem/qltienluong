<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaidonvi_baocao;
use App\dmkhoipb;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmphucap;
use App\dmthongtuquyetdinh;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\dmphanloaicongtac;
use App\dmphanloaidonvi;
use App\dmnguonkinhphi;
use App\dmchucvucq;
use App\dutoanluong;
use App\dutoanluong_chitiet;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\nguonkinhphi;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_phucap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class baocaotonghop_tinhController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            // dd(session('admin'));
            $macqcq = session('admin')->madv;
            $a_thang = getThang();
            $a_phanloai = array();
            $a_phanloai[''] = '--Chọn tất cả--';
            $model_dv = dmdonvi::where('macqcq', $macqcq)->orwhere('madv', $macqcq)->get();
            $m_donvi = dmdonvi::where('phanloaitaikhoan', 'SD')->get();
            $model_dvbc = dmdonvibaocao::where('level', 'H')->get();
            $model_dvbcT = dmdonvi::join('dmdonvibaocao', 'dmdonvi.madvbc', 'dmdonvibaocao.madvbc')
                ->where('dmdonvibaocao.level', 'T')
                ->where('dmdonvi.phanloaitaikhoan', 'TH')
                ->get();
            $model_tenct = dmphanloaict::wherein('mact', getPLCTDuToan())->get();
            $model_nhomct = dmphanloaicongtac::wherein('macongtac', array_unique(array_column($model_tenct->toarray(), 'macongtac')))->get();

            $inputs['furl'] = '/tong_hop_bao_cao/';
            $inputs['furl_chiluong'] = '/chuc_nang/tong_hop_luong/huyen/';
            $inputs['furl_dutoan'] = '/chuc_nang/du_toan_luong/huyen/';
            $inputs['furl_nhucaukp'] = '/chuc_nang/tong_hop_nguon/huyen/';
            $a_thongtuqd = array_column(dmthongtuquyetdinh::orderby('ngayapdung', 'desc')->get()->toarray(), 'tenttqd', 'sohieu');

            return view('reports.baocaotonghop.tinh.index')
                ->with('model_dv', $model_dv)
                ->with('a_thang', $a_thang)
                ->with('m_donvi', $m_donvi)
                ->with('a_phanloai', $a_phanloai)
                ->with('model_dvbc', $model_dvbc)
                ->with('model_dvbcT', $model_dvbcT)
                ->with('a_thongtuqd', $a_thongtuqd)
                //->with('model_phanloaict', $model_phanloaict)
                ->with('model_tenct', $model_tenct)
                ->with('model_nhomct', $model_nhomct)
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp lương');
        } else
            return view('errors.notlogin');
    }

    function tonghopluong_tinh_CR(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $madvbc = session('admin')->madvbc;
            // $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
            $model_donvi = dmdonvibaocao::where('baocao', 1)
                ->get();

            // $model_khoipb = dmkhoipb::all();
            $model_phanloai = dmphanloaidonvi::join('dmdonvi', 'dmdonvi.maphanloai', 'dmphanloaidonvi.maphanloai')
                ->select('madv', 'dmphanloaidonvi.maphanloai', 'tenphanloai', 'linhvuchoatdong')
                ->wherein('madvbc', array_column($model_donvi->toarray(), 'madvbc'))
                ->where('dmphanloaidonvi.maphanloai', 'like', $inputs['phanloai'] . '%')
                ->get();
            if ($inputs['phanloai'] == 'GD')
                $model_phanloai = dmphanloaidonvi::join('dmdonvi', 'dmdonvi.maphanloai', 'dmphanloaidonvi.maphanloai')
                    ->select('madv', 'dmphanloaidonvi.maphanloai', 'tenphanloai', 'linhvuchoatdong')
                    ->wherein('madvbc', array_column($model_donvi->toarray(), 'madvbc'))
                    ->wherein('dmdonvi.maphanloai', getPhanLoaGD())
                    ->get();
            // dd($model_phanloai->take(10));
            $model_th = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'mact',
                    'tonghopluong_donvi.madvbc',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesobl) as hesobl'),
                    DB::raw('sum(hesott) as hesott'),
                    DB::raw('sum(hesopc) as hesopc'),
                    DB::raw('sum(vuotkhung) as vuotkhung'),
                    DB::raw('sum(pcct) as pcct'),
                    DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),
                    DB::raw('sum(pccv) as pccv'),
                    DB::raw('sum(pckv) as pckv'),
                    DB::raw('sum(pcth) as pcth'),
                    DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),
                    DB::raw('sum(pcld) as pcld'),
                    DB::raw('sum(pcdbqh) as pcdbqh'),
                    DB::raw('sum(pcudn) as pcudn'),
                    DB::raw('sum(pctn) as pctn'),
                    DB::raw('sum(pctnn) as pctnn'),
                    DB::raw('sum(pcdbn) as pcdbn'),
                    DB::raw('sum(pcvk) as pcvk'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),
                    DB::raw('sum(pclt) as pclt'),
                    DB::raw('sum(pcd) as pcd'),
                    DB::raw('sum(pctr) as pctr'),
                    DB::raw('sum(pctdt) as pctdt'),
                    DB::raw('sum(pctnvk) as pctnvk'),
                    DB::raw('sum(pcbdhdcu) as pcbdhdcu'),
                    DB::raw('sum(pcthni) as pcthni'),
                    DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),
                    DB::raw('sum(pcxaxe) as pcxaxe'),
                    DB::raw('sum(pcdith) as pcdith'),
                    DB::raw('sum(pcphth) as pcphth'),
                    DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->wherein('madvbc', array_column($model_donvi->toarray(), 'madvbc'))
                ->where('trangthai', 'DAGUI')
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(), 'madv'))
                ->groupby('mact', 'tonghopluong_donvi.madvbc')
                ->get();
            // dd($model_th);
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
            // dd($model_donvi);
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $m_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'report', 'mapc');
            $a_donvi = array_column($model_donvi->toarray(), 'madvbc');
            $a_phucap = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model_th->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            unset($a_phucap['heso']);
            $col = $col - 1;
            foreach ($model_th as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
                //$ct->soluonggiao = $model_slth->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slth->where('mact',$ct->mact)->first()->canbo_congtac;
            }
            // dd($model_th);
            /*
            $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'tonghopluong_donvi.madv',
                    'mact',
                    'linhvuchoatdong',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesobl) as hesobl'),
                    DB::raw('sum(hesott) as hesott'),
                    DB::raw('sum(hesopc) as hesopc'),
                    DB::raw('sum(vuotkhung) as vuotkhung'),
                    DB::raw('sum(pcct) as pcct'),
                    DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),
                    DB::raw('sum(pccv) as pccv'),
                    DB::raw('sum(pckv) as pckv'),
                    DB::raw('sum(pcth) as pcth'),
                    DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),
                    DB::raw('sum(pcld) as pcld'),
                    DB::raw('sum(pcdbqh) as pcdbqh'),
                    DB::raw('sum(pcudn) as pcudn'),
                    DB::raw('sum(pctn) as pctn'),
                    DB::raw('sum(pctnn) as pctnn'),
                    DB::raw('sum(pcdbn) as pcdbn'),
                    DB::raw('sum(pcvk) as pcvk'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),
                    DB::raw('sum(pclt) as pclt'),
                    DB::raw('sum(pcd) as pcd'),
                    DB::raw('sum(pctr) as pctr'),
                    DB::raw('sum(pctdt) as pctdt'),
                    DB::raw('sum(pctnvk) as pctnvk'),
                    DB::raw('sum(pcbdhdcu) as pcbdhdcu'),
                    DB::raw('sum(pcthni) as pcthni'),
                    DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),
                    DB::raw('sum(pcxaxe) as pcxaxe'),
                    DB::raw('sum(pcdith) as pcdith'),
                    DB::raw('sum(pcphth) as pcphth'),
                    DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->wherein('tonghopluong_donvi.madvbc', array_column($model_donvi->toarray(),'madvbc'))
                ->where('trangthai', 'DAGUI')
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->wherein('madv', array_column($model_phanloai->toarray(), 'madv'))
                ->groupby('tonghopluong_donvi.madv', 'mact', 'linhvuchoatdong')
                ->get();
                */
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
            // dd($model);
            // foreach ($model as $ct) {
            //     $ct->soluongcomat = $ct->soluong;
            //     if ($ct->mact == null) {
            //         $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
            //     } else {
            //         $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
            //     }
            //     $ct->tendv = $model_donvi->where('madv', $ct->madv)->first()->tendv;
            //     $ct->maphanloai = $model_donvi->where('madv', $ct->madv)->first()->maphanloai;
            //     $m = $model_phanloai->where('maphanloai', $ct->maphanloai)->first();
            //     $ct->tenphanloai = $m->tenphanloai ?? '';


            //     //$ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
            //     $m = $model_khoipb->where('makhoipb', $ct->linhvuchoatdong)->first();
            //     $ct->tenlinhvuchoatdong = $m->tenkhoipb ?? '';

            //     foreach ($a_phucap as $key => $val) {
            //         if ($ct->$key > 10000)
            //             $ct->$key = $ct->$key / $ct->luongcoban;
            //     }
            //     $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
            //     $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
            //     $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
            //     $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
            //     $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
            //     $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
            // }
            // dd($model);
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'nam' => $inputs['tunam'],
                'madvbc' => $madvbc
            );
            // dd($model_th->take(10));
            return view('reports.tonghopluong.tinh.tonghopluongCR')
                ->with('model_th', $model_th)
                ->with('model_donvi', $model_donvi)
                //->with('model_bienche_dutoan',$model_bienche_dutoan)
                //->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                //->with('modelhcsn',$modelhcsn)
                ->with('model_phanloai', $model_phanloai)
                ->with('a_phucap', $a_phucap)
                ->with('col', $col)
                ->with('nam', $inputs['tunam'])
                ->with('thang', $inputs['tuthang'])
                ->with('pageTitle', 'Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function tonghopluong_huyen_CR(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = $inputs['donvi'];
            $model_donvi = dmdonvi::where('madvbc', $madvbc)->get();
            $model_khoipb = dmkhoipb::all();
            $model_phanloai = dmphanloaidonvi::join('dmdonvi', 'dmdonvi.maphanloai', 'dmphanloaidonvi.maphanloai')
                ->select('madv', 'dmphanloaidonvi.maphanloai', 'tenphanloai', 'linhvuchoatdong')
                ->where('madvbc', $madvbc)
                ->where('dmphanloaidonvi.maphanloai', 'like', $inputs['phanloai'] . '%')
                ->get();
            if ($inputs['phanloai'] == 'GD')
                $model_phanloai = dmphanloaidonvi::join('dmdonvi', 'dmdonvi.maphanloai', 'dmphanloaidonvi.maphanloai')
                    ->select('madv', 'dmphanloaidonvi.maphanloai', 'tenphanloai', 'linhvuchoatdong')
                    ->where('madvbc', $madvbc)
                    ->wherein('dmdonvi.maphanloai', getPhanLoaGD())
                    ->get();

            $model_th = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'mact',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesobl) as hesobl'),
                    DB::raw('sum(hesott) as hesott'),
                    DB::raw('sum(hesopc) as hesopc'),
                    DB::raw('sum(vuotkhung) as vuotkhung'),
                    DB::raw('sum(pcct) as pcct'),
                    DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),
                    DB::raw('sum(pccv) as pccv'),
                    DB::raw('sum(pckv) as pckv'),
                    DB::raw('sum(pcth) as pcth'),
                    DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),
                    DB::raw('sum(pcld) as pcld'),
                    DB::raw('sum(pcdbqh) as pcdbqh'),
                    DB::raw('sum(pcudn) as pcudn'),
                    DB::raw('sum(pctn) as pctn'),
                    DB::raw('sum(pctnn) as pctnn'),
                    DB::raw('sum(pcdbn) as pcdbn'),
                    DB::raw('sum(pcvk) as pcvk'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),
                    DB::raw('sum(pclt) as pclt'),
                    DB::raw('sum(pcd) as pcd'),
                    DB::raw('sum(pctr) as pctr'),
                    DB::raw('sum(pctdt) as pctdt'),
                    DB::raw('sum(pctnvk) as pctnvk'),
                    DB::raw('sum(pcbdhdcu) as pcbdhdcu'),
                    DB::raw('sum(pcthni) as pcthni'),
                    DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),
                    DB::raw('sum(pcxaxe) as pcxaxe'),
                    DB::raw('sum(pcdith) as pcdith'),
                    DB::raw('sum(pcphth) as pcphth'),
                    DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->where('madvbc', $madvbc)
                ->where('trangthai', 'DAGUI')
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(), 'madv'))
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
            $col = $col - 1;
            foreach ($model_th as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
                //$ct->soluonggiao = $model_slth->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slth->where('mact',$ct->mact)->first()->canbo_congtac;
            }
            $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'tonghopluong_donvi.madv',
                    'mact',
                    'linhvuchoatdong',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesobl) as hesobl'),
                    DB::raw('sum(hesott) as hesott'),
                    DB::raw('sum(hesopc) as hesopc'),
                    DB::raw('sum(vuotkhung) as vuotkhung'),
                    DB::raw('sum(pcct) as pcct'),
                    DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),
                    DB::raw('sum(pccv) as pccv'),
                    DB::raw('sum(pckv) as pckv'),
                    DB::raw('sum(pcth) as pcth'),
                    DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),
                    DB::raw('sum(pcld) as pcld'),
                    DB::raw('sum(pcdbqh) as pcdbqh'),
                    DB::raw('sum(pcudn) as pcudn'),
                    DB::raw('sum(pctn) as pctn'),
                    DB::raw('sum(pctnn) as pctnn'),
                    DB::raw('sum(pcdbn) as pcdbn'),
                    DB::raw('sum(pcvk) as pcvk'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),
                    DB::raw('sum(pclt) as pclt'),
                    DB::raw('sum(pcd) as pcd'),
                    DB::raw('sum(pctr) as pctr'),
                    DB::raw('sum(pctdt) as pctdt'),
                    DB::raw('sum(pctnvk) as pctnvk'),
                    DB::raw('sum(pcbdhdcu) as pcbdhdcu'),
                    DB::raw('sum(pcthni) as pcthni'),
                    DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),
                    DB::raw('sum(pcxaxe) as pcxaxe'),
                    DB::raw('sum(pcdith) as pcdith'),
                    DB::raw('sum(pcphth) as pcphth'),
                    DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->where('tonghopluong_donvi.madvbc', $madvbc)
                ->where('trangthai', 'DAGUI')
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->wherein('madv', array_column($model_phanloai->toarray(), 'madv'))
                ->groupby('tonghopluong_donvi.madv', 'mact', 'linhvuchoatdong')
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
            foreach ($model as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv', $ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv', $ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai', $ct->maphanloai)->first();
                $ct->tenphanloai = $m->tenphanloai ?? '';


                //$ct->linhvuchoatdong = $model_donvi->where('madv',$ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb', $ct->linhvuchoatdong)->first();
                $ct->tenlinhvuchoatdong = $m->tenkhoipb ?? '';

                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
            }

            //dd($model->toarray());

            //dd($a_phucap);
            //$model_tongso = $model_th->
            //dd($model_th->toarray());

            //Tính toán Hoạt động phí HĐND

            $model_hdnd = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'mact',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso-pckn) as tongpc'),
                    DB::raw('sum(tonghs-pckn) as tonghs'),
                    DB::raw('sum(hesopc) as pccv'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(tonghopluong_donvi_bangluong.luongcoban*hesopc) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->where('tonghopluong_donvi.madvbc', $madvbc)
                ->where('trangthai', 'DAGUI')
                ->where('mact', '1536402868')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(), 'madv'))
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
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
            foreach ($model_hdnd as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_congtac;
                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
            }
            $model_kn = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'mact',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso-hesopc) as tongpc'),
                    DB::raw('sum(tonghs-hesopc) as tonghs'),
                    DB::raw('sum(pckn) as pccv'),
                    DB::raw('sum(tonghopluong_donvi_bangluong.luongcoban*pckn) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->where('madvbc', $madvbc)
                ->where('trangthai', 'DAGUI')
                ->where('mact', '1536402868')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(), 'madv'))
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->groupby('mact')
                ->get();
            foreach ($model_kn as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->soluonggiao = 0;
                $ct->soluongcomat = 0;
                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
            }
            $model_uv = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'mact',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesopc) as pccv'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(tonghopluong_donvi_bangluong.luongcoban*hesopc) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->where('tonghopluong_donvi.madvbc', $madvbc)
                ->where('trangthai', 'DAGUI')
                ->where('mact', '1536459380')
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->toarray(), 'madv'))
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->groupby('mact')
                ->get();
            foreach ($model_uv as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                //$ct->soluonggiao = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_dutoan;
                //$ct->soluongcomat = $model_slhdnd->where('mact',$ct->mact)->first()->canbo_congtac;
                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
            }

            //Tính toán phần xã phường
            $model_xp = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi.mathdv', 'tonghopluong_donvi_bangluong.mathdv')
                ->Select(
                    'tonghopluong_donvi.madv',
                    'mact',
                    DB::raw('avg(tonghopluong_donvi_bangluong.luongcoban) as luongcoban'),
                    DB::raw('count(tonghopluong_donvi_bangluong.id) as soluong'),
                    DB::raw('sum(heso) as heso'),
                    DB::raw('sum(tonghs-heso) as tongpc'),
                    DB::raw('sum(tonghs) as tonghs'),
                    DB::raw('sum(hesobl) as hesobl'),
                    DB::raw('sum(hesott) as hesott'),
                    DB::raw('sum(hesopc) as hesopc'),
                    DB::raw('sum(vuotkhung) as vuotkhung'),
                    DB::raw('sum(pcct) as pcct'),
                    DB::raw('sum(pckct) as pckct'),
                    DB::raw('sum(pck) as pck'),
                    DB::raw('sum(pccv) as pccv'),
                    DB::raw('sum(pckv) as pckv'),
                    DB::raw('sum(pcth) as pcth'),
                    DB::raw('sum(pcdd) as pcdd'),
                    DB::raw('sum(pcdh) as pcdh'),
                    DB::raw('sum(pcld) as pcld'),
                    DB::raw('sum(pcdbqh) as pcdbqh'),
                    DB::raw('sum(pcudn) as pcudn'),
                    DB::raw('sum(pctn) as pctn'),
                    DB::raw('sum(pctnn) as pctnn'),
                    DB::raw('sum(pcdbn) as pcdbn'),
                    DB::raw('sum(pcvk) as pcvk'),
                    DB::raw('sum(pckn) as pckn'),
                    DB::raw('sum(pcdang) as pcdang'),
                    DB::raw('sum(pccovu) as pccovu'),
                    DB::raw('sum(pclt) as pclt'),
                    DB::raw('sum(pcd) as pcd'),
                    DB::raw('sum(pctr) as pctr'),
                    DB::raw('sum(pctdt) as pctdt'),
                    DB::raw('sum(pctnvk) as pctnvk'),
                    DB::raw('sum(pcbdhdcu) as pcbdhdcu'),
                    DB::raw('sum(pcthni) as pcthni'),
                    DB::raw('sum(pclade) as pclade'),
                    DB::raw('sum(pcud61) as pcud61'),
                    DB::raw('sum(pcxaxe) as pcxaxe'),
                    DB::raw('sum(pcdith) as pcdith'),
                    DB::raw('sum(pcphth) as pcphth'),
                    DB::raw('sum(ttl) as ttl'),
                    DB::raw('sum(stbhxh_dv) as stbhxh_dv'),
                    DB::raw('sum(stbhyt_dv) as stbhyt_dv'),
                    DB::raw('sum(stbhtn_dv) as stbhtn_dv'),
                    DB::raw('sum(stkpcd_dv) as stkpcd_dv'),
                    DB::raw('sum(ttbh_dv) as ttbh_dv')
                )
                ->where('madvbc', $madvbc)
                ->where('trangthai', 'DAGUI')
                ->wherein('mact', ['1506672780', '1506673604', '1506673695', '1535613221'])
                ->wherein('tonghopluong_donvi.madv', array_column($model_phanloai->where('maphanloai', 'KVXP')->toarray(), 'madv'))
                ->where('nam', $inputs['tunam'])
                ->where('thang', $inputs['tuthang'])
                ->groupby('tonghopluong_donvi.madv', 'mact')
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
            foreach ($model_xp as $ct) {
                $ct->soluongcomat = $ct->soluong;
                if ($ct->mact == null) {
                    $ct->tencongtac = isset($model_phanloaict[$ct->macongtac]) ? $model_phanloaict[$ct->macongtac] : '';
                } else {
                    $ct->tencongtac = isset($model_ct[$ct->mact]) ? $model_ct[$ct->mact] : '';
                }
                $ct->tendv = $model_donvi->where('madv', $ct->madv)->first()->tendv;
                $ct->maphanloai = $model_donvi->where('madv', $ct->madv)->first()->maphanloai;
                $m = $model_phanloai->where('maphanloai', $ct->maphanloai)->first();
                $ct->tenphanloai = $m->tenphanloai ?? '';


                $ct->linhvuchoatdong = $model_donvi->where('madv', $ct->madv)->first()->linhvuchoatdong;
                $m = $model_khoipb->where('makhoipb', $ct->linhvuchoatdong)->first();
                $ct->tenlinhvuchoatdong = $m->tenkhoipb ?? '';

                foreach ($a_phucap as $key => $val) {
                    if ($ct->$key > 10000)
                        $ct->$key = $ct->$key / $ct->luongcoban;
                }
                $ct->stbhxh_dv = $ct->stbhxh_dv / $ct->luongcoban;
                $ct->stbhyt_dv = $ct->stbhyt_dv / $ct->luongcoban;
                $ct->stkpcd_dv = $ct->stkpcd_dv / $ct->luongcoban;
                $ct->stbhtn_dv = $ct->stbhtn_dv / $ct->luongcoban;
                $ct->tongcong = $ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                $ct->tongtienluong = ($ct->tonghs + $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv) * $ct->luongcoban;
            }
            //dd($model_kn->toarray());
            $model_dutoan = tonghopluong_donvi::where('nam', $inputs['tunam'])
                ->wherein('madv', function ($qr) use ($madvbc) {
                    $qr->select('madv')->from('dmdonvi')->where('madvbc', $madvbc);
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
            $m_dv = dmdonvi::where('madvbc', $madvbc)->where('phanloaitaikhoan', 'TH')->first();
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'nam' => $inputs['tunam'],
                'madvbc' => $madvbc
            );
            $a_lv = array('QLNN', 'DDT', 'DOANTHE', 'DANG', 'LVXH', 'LVCT');
            $modelqlnn = $model->wherein('linhvuchoatdong', $a_lv)
                ->wherein('madv', array_column($model_phanloai->where('maphanloai', '<>', 'KVXP')->toarray(), 'madv'));
            $a_lv = array('QLNN', 'DDT', 'DOANTHE', 'DANG', 'LVXH', 'LVCT');
            //$modelhcsn = $model->whereNOTIn('linhvuchoatdong',$a_lv)
            //   ->wherein('madv', array_column($model_phanloai->where('maphanloai','<>','KVXP')->toarray(),'madv'));
            return view('reports.tonghopluong.huyen.tonghopluongCR')
                ->with('model_dutoan', $model_dutoan)
                ->with('model_th', $model_th)
                ->with('model_donvi', $model_donvi)
                //->with('model_bienche_dutoan',$model_bienche_dutoan)
                //->with('model_bienche_truoc',$model_bienche_truoc)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                ->with('model', $model)
                ->with('modelqlnn', $modelqlnn)
                //->with('modelhcsn',$modelhcsn)
                ->with('model_hdnd', $model_hdnd)
                ->with('model_kn', $model_kn)
                ->with('model_uv', $model_uv)
                ->with('model_xp', $model_xp)
                ->with('model_phanloai', $model_phanloai)
                ->with('a_phucap', $a_phucap)
                ->with('col', $col)
                ->with('nam', $inputs['tunam'])
                ->with('thang', $inputs['tuthang'])
                ->with('pageTitle', 'Báo cáo tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    function chitraluong_ct_huyenCR(Request $request)
    {
        if (Session::has('admin')) {
            //$model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $inputs = $request->all();
            // dd($inputs);
            $madv = $inputs['donvicapduoi'];
            $thang = $inputs['tuthang'];
            $nam = $inputs['tunam'];
            $check = dmdonvi::where('madv', $madv)->where('phanloaitaikhoan', 'TH')->get();
            if (count($check) > 0) {
                if ($inputs['tuthang'] == 'ALL')
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
                else
                    $m_mathdv = tonghopluong_khoi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->first();
            } else {
                if ($inputs['tuthang'] == 'ALL')
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('nam', $nam)->where('trangthai', 'DAGUI')->get();
                else
                    $m_mathdv = tonghopluong_donvi::where('madv', $madv)->where('thang', $thang)->where('nam', $nam)->where('trangthai', 'DAGUI')->distinct()->get();
            }

            if (isset($m_mathdv)) {
                if (count($check) > 0) {
                    $mathh = array_column($m_mathdv->toArray(), 'mathdv');
                    $a_math = tonghopluong_donvi::wherein('mathh', $mathh)->get();
                    $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi_bangluong.mathdv', 'tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_bangluong.*', 'thang')
                        ->wherein('tonghopluong_donvi_bangluong.mathdv', array_column($a_math->toarray(), 'mathdv'))->get();
                    $model_thongtin = tonghopluong_donvi::wherein('mathh', $mathh)->first();
                    $m_pc = array_column(dmphucap_donvi::wherein('madv', function ($query) use ($mathh) {
                        $query->select('dmdonvi.madv')->from('dmdonvi')->join('tonghopluong_khoi', 'dmdonvi.madv', 'tonghopluong_khoi.madv')->wherein('mathdv', $mathh)
                            ->get();
                    })->get()->toarray(), 'report', 'mapc');
                } else {
                    $a_mathdv = array_column($m_mathdv->toArray(), 'mathdv');
                    $model = tonghopluong_donvi_bangluong::join('tonghopluong_donvi', 'tonghopluong_donvi_bangluong.mathdv', 'tonghopluong_donvi.mathdv')
                        ->select('tonghopluong_donvi_bangluong.*', 'thang')
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
                    if ($ct != 'heso')
                        if ($model->sum($ct) > 0) {
                            $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                            $col++;
                        }
                }
                //dd($a_phucap);
                $thongtin = array('nguoilap' => '', 'thang' => '', 'nam' => '');
                if (isset($model_thongtin))
                    $thongtin = array(
                        'nguoilap' => session('admin')->name,
                        'thang' => $model_thongtin->thang,
                        'nam' => $model_thongtin->nam
                    );

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
            } else {
                return view('errors.nodata');
            }
        } else
            return view('errors.notlogin');
    }

    function danhsachdonvi(Request $request)
    {
        $inputs = $request->all();
        $output = '';
        $m_dv = dmdonvi::select('tendv', 'madv', 'madvbc')->where('madvbc', $inputs['donvi'])->where('phanloaitaikhoan', 'SD')->get();
        if (count($m_dv) > 0) {
            foreach ($m_dv as $key => $val) {
                $output .= '<option class="baocaoct" value="' . $val->madv . '">' . $val->tendv . '</option>';
            }
        }
        return response()->json($output);
    }

    //Tổng hợp chi trả lương
    public function tonghopluong_tinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->get();
            $m_pc = array_column(dmphucap::all()->toarray(), 'report', 'mapc');
            $a_phucap = array();
            $col = 0;
            $model = tonghopluong_donvi_chitiet::wherein('mathdv', function ($query) use ($inputs) {
                $query->select('mathdv')->from('tonghopluong_donvi')->where('nam', $inputs['nam'])
                    ->where('thang', $inputs['thang'])
                    ->where('trangthai', 'DAGUI')
                    ->wherenotnull('matht')
                    ->wherein('madv', function ($query) {
                        $query->select('madv')->from('dmdonvi')->wherein('madvbc', function ($qr) {
                            $qr->select('madvbc')->from('dmdonvibaocao')->where('baocao', 1)->get();
                        })->get();
                    })
                    ->get();
            })->get();

            $a_phucap = array();
            $col = 0;
            foreach (getColTongHop() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }
            // dd(getColTongHop());
            // $col = $col - 1;
            $ngayketxuat = Carbon::create($inputs['nam'], $inputs['thang'], 01)->toDateString();
            $luongcb = 1390000;
            if ($ngayketxuat < '2023-07-01' && $ngayketxuat > '2019-07-01') {
                $luongcb = 1490000;
            } else
                $luongcb = 1800000;
            $m_tonghop = tonghopluong_donvi::where('nam', $inputs['nam'])
                ->where('thang', $inputs['thang'])
                ->where('trangthai', 'DAGUI')
                ->get();
            $a_tonghop = array_column($m_tonghop->toarray(), 'madv', 'mathdv');
            $a_dvbc = array_column($m_tonghop->toarray(), 'madvbc', 'mathdv');

            //dd($m_tonghop->toarray());
            //$a_dvbc = array_column(dmdonvi::all()->toArray(), '', '');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_tonghop[$chitiet->mathdv] ?? '';
                $chitiet->madvbc = $a_dvbc[$chitiet->mathdv] ?? '';

                $chitiet->luongcoban = $luongcb;
                $chitiet->tongtl = $chitiet->luongtn - $chitiet->giaml;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                foreach (getColTongHop() as $ct) {
                    $ma = $ct;
                    $chitiet->$ma = $chitiet->$ct;
                    if ($chitiet->$ct > 100000) {
                        $chitiet->$ct = $chitiet->luongcoban == 0 ? 0 : $chitiet->$ct / $chitiet->luongcoban;
                    }
                }
                $chitiet->soluongcomat = $chitiet->soluong;
                $chitiet->tongpc = $chitiet->tonghs - $chitiet->heso;
                $chitiet->tongcong = $chitiet->tonghs + $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //dd($model->toarray());
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            return view('reports.tonghopluong.tinh.tonghopluong')
                ->with('model', $model)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');
        } else
            return view('errors.notlogin');
    }

    public function tonghopbienche(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            // $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            $m_dutoan = dutoanluong::where('namns', $inputs['namns'])->wherenotnull('masot')->where('trangthai', 'DAGUI')
                ->where(function ($query) use ($inputs) {
                    if (isset($inputs['madv'])) {
                        $query->where('macqcq', $inputs['madv']);
                    }
                })->get();
            // dd(count($m_dutoan));
            if (count($m_dutoan) <= 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['namns'])
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            // $inputs['namns'] = $m_dutoan_huyen->namns;
            // $inputs['masodv']=$m_dutoan_huyen->masodv;
            // $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();

            //$m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', session('admin')->madvbc)->where('maphanloai_nhom', '!=', 'KVXP')->get();
            if (count($m_phanloai) <= 0) {
                $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', 1511580911)->where('maphanloai_nhom', '!=', 'KVXP')->get(); //Lấy phân loại của Vạn Ninh để in báo cáo.
            }

            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');
            // $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($m_dutoan->toarray(), 'madv'))->get();

            $a_donvi = array_column($m_dutoan->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->wherein('mact', $inputs['mact'])->get();

            //$m_chuatuyen = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->where('phanloai', 'CHUATUYEN')->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();

            foreach ($model as $chitiet) {
                foreach ($a_pc as $pc) {
                    $chitiet->$pc = $chitiet->$pc / 12;
                }
                $dutoan = $m_dutoan->where('masodv', $chitiet->masodv)->first();
                $chitiet->macqcq = $dutoan->macqcq;
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->tonghs = $chitiet->tonghs / 12;

                $chitiet->bhxh_dv = $chitiet->bhxh_dv / 12;
                $chitiet->bhyt_dv = $chitiet->bhyt_dv / 12;
                $chitiet->kpcd_dv = $chitiet->kpcd_dv / 12;
                $chitiet->bhtn_dv = $chitiet->bhtn_dv / 12;
                $chitiet->baohiem = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->kpcd_dv;
                $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                $chitiet->tongbh_dv = $chitiet->tongbh_dv / 12;
                $chitiet->tongcong = $chitiet->tonghs + $chitiet->tongbh_dv;
                $chitiet->hesotrungbinh = round($chitiet->tongcong / $chitiet->canbo_congtac, 5);
                $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) / $inputs['donvitinh'];
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
            }
            //dd($model->where('maphanloai','DAOTAO')->toArray());

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach ($a_pc as $ct) {
                if ($model->sum($ct) > 0 && $ct != 'heso') {
                    $a_phucap[$ct] = $a_tenpc[$ct];
                    $col++;
                }
            };
            $m_huyen = dmdonvibaocao::where('baocao', 1)->get();
            $a_dv = array_column($m_huyen->toarray(), 'tendvbc', 'madvcq');
            // $m_huyen= dmdonvibaocao::where('baocao',1)->orderBy('id','desc')->get();
            // dd($model);
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($m_donvi_baocao->where('maphanloai','DAOTAO'));
            $view = isset($inputs['madv']) ? 'reports.dutoanluong.tinh.tonghopbienche_ct' : 'reports.dutoanluong.tinh.tonghopbienche';
            return view($view)
                ->with('model', $model)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_huyen', $m_huyen)
                ->with('a_dv', $a_dv)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }
    function getMaNhomPhanLoai(&$chitiet, $m_phanloai)
    {
        $chitiet->maphanloai_goc1 = '';
        $chitiet->maphanloai_goc2 = '';
        $chitiet->maphanloai_goc3 = '';
        $phanloai = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai)->first();
        if ($phanloai != null) {
            $chitiet->capdo_nhom = $phanloai->capdo_nhom;
            switch ($phanloai->capdo_nhom) {
                case '1': {
                        $chitiet->maphanloai_goc1 = $phanloai->maphanloai_nhom;
                        break;
                    }
                case '2': {
                        $chitiet->maphanloai_goc1 = $phanloai->maphanloai_goc;
                        break;
                    }
                case '3': {
                        $chitiet->maphanloai_goc2 = $phanloai->maphanloai_goc;
                        //tìm gốc 1
                        $chitiet->maphanloai_goc1 = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai_goc2)->first()->maphanloai_goc;
                        break;
                    }
                case '4': {
                        //tìm cấp 3                    
                        $chitiet->maphanloai_goc3 = $phanloai->maphanloai_goc;
                        //tìm gốc 2
                        $chitiet->maphanloai_goc2 = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai_goc3)->first()->maphanloai_goc;
                        //tìm gốc 1
                        $chitiet->maphanloai_goc1 = $m_phanloai->where('maphanloai_nhom', $chitiet->maphanloai_goc2)->first()->maphanloai_goc;
                        break;
                    }
            }
        }
    }

    public function tonghophopdong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $m_dutoan = dutoanluong::where('namns', $inputs['namns'])->wherenotnull('masot')->where('trangthai', 'DAGUI')
                ->where(function ($query) use ($inputs) {
                    if (isset($inputs['madv'])) {
                        $query->where('macqcq', $inputs['madv']);
                    }
                })
                ->get();
            if (!isset($m_dutoan)) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['namns'])
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();

            // $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', session('admin')->madvbc)->where('maphanloai_nhom', '!=', 'KVXP')->get();
            if (count($m_phanloai) <= 0) {
                $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', 1511580911)->where('maphanloai_nhom', '!=', 'KVXP')->get(); //Lấy phân loại của Vạn Ninh để in báo cáo.
            }

            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');
            // $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($m_dutoan->toarray(), 'madv'))->get();
            //dd($m_donvi_baocao);
            $a_donvi = array_column($m_dutoan->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->wherein('mact', $inputs['mact'])->get();
            //$m_chuatuyen = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->where('phanloai', 'CHUATUYEN')->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();

            foreach ($model as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $dutoan = $m_dutoan->where('masodv', $chitiet->masodv)->first();
                $chitiet->macqcq = $dutoan->macqcq;
                foreach ($a_pc as $pc) {
                    $chitiet->$pc = $chitiet->$pc / 12;
                }
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongthang = ($chitiet->ttl / 12) / $inputs['donvitinh'];;
                $chitiet->baohiem = ($chitiet->ttbh_dv / 12) / $inputs['donvitinh'];;
                $chitiet->tongcong = ($chitiet->luongthang + $chitiet->baohiem) / $inputs['donvitinh'];
                $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) / $inputs['donvitinh'];
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
            }
            //dd($model->where('maphanloai','KVXP')->toArray());

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo

            //$m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            $m_huyen = dmdonvibaocao::where('baocao', 1)->get();
            $a_dv = array_column($m_huyen->toarray(), 'tendvbc', 'madvcq');
            $view = isset($inputs['madv']) ? 'reports.dutoanluong.tinh.tonghophopdong_ct' : 'reports.dutoanluong.tinh.tonghophopdong';
            // dd($model);
            return view($view)
                ->with('model', $model)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('m_donvi', $m_donvi)
                ->with('m_huyen', $m_huyen)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('a_dv', $a_dv)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    public function tonghopnhucau_tinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->get();
            $m_pc = array_column(dmphucap::all()->toarray(), 'report', 'mapc');
            $a_phucap = array();
            $col = 0;

            $model = nguonkinhphi_phucap::wherein('masodv', function ($query) use ($inputs) {
                $query->select('masodv')->from('nguonkinhphi')->where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->wherenotnull('masot')
                    ->wherein('madv', function ($query) {
                        $query->select('madv')->from('dmdonvi')->wherein('madvbc', function ($qr) {
                            $qr->select('madvbc')->from('dmdonvibaocao')->where('baocao', 1)->get();
                        })->get();
                    })
                    ->get();
            })->get();

            $a_phucap = array();
            $col = 0;
            foreach (getColNhuCau() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $m_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('trangthai', 'DAGUI')
                ->get();
            $a_tonghop = array_column($m_tonghop->toarray(), 'madv', 'masodv');
            $a_dvbc = array_column($m_tonghop->toarray(), 'madvbc', 'masodv');

            //dd($m_tonghop->toarray());
            //$a_dvbc = array_column(dmdonvi::all()->toArray(), '', '');
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_tonghop[$chitiet->masodv] ?? '';
                $chitiet->madvbc = $a_dvbc[$chitiet->masodv] ?? '';

                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
                //$chitiet->soluongcomat = $chitiet->soluong;
                $chitiet->tongpc = $chitiet->tonghs - $chitiet->heso;
                $chitiet->tongcong = $chitiet->tonghs + $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //dd($model->toarray());
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            $inputs['lamtron'] = session('admin')->lamtron ?? 3;
            return view('reports.thongtu78.tinh.tonghopnhucau')
                ->with('model', $model)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');
        } else
            return view('errors.notlogin');
    }

    public function tonghopnhucau2a_tinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->get();
            $m_pc = array_column(dmphucap::all()->toarray(), 'report', 'mapc');
            $a_phucap = array();
            $col = 0;

            $model = nguonkinhphi_01thang::wherein('masodv', function ($query) use ($inputs) {
                $query->select('masodv')->from('nguonkinhphi')->where('sohieu', $inputs['sohieu'])
                    ->where('trangthai', 'DAGUI')
                    ->wherenotnull('masot')
                    ->wherein('madv', function ($query) {
                        $query->select('madv')->from('dmdonvi')->wherein('madvbc', function ($qr) {
                            $qr->select('madvbc')->from('dmdonvibaocao')->where('baocao', 1)->get();
                        })->get();
                    })
                    ->get();
            })->get();

            $a_phucap = array();
            $col = 0;
            foreach (getColNhuCau() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $m_tonghop = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('trangthai', 'DAGUI')
                ->get();
            $a_tonghop = array_column($m_tonghop->toarray(), 'madv', 'masodv');
            $a_dvbc = array_column($m_tonghop->toarray(), 'madvbc', 'masodv');
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            $a_nhomnhucau = ['BIENCHE', 'CANBOCT', 'HDND', 'CAPUY'];            
            $a_donvi = array_column($m_tonghop->toarray(), 'madv', 'masodv');
            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //dd($a_phucap);
            foreach ($model as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                if (!in_array($chitiet->nhomnhucau, $a_nhomnhucau)) {
                    $model->forget($key);
                }
                $chitiet->madv = $a_tonghop[$chitiet->masodv] ?? '';
                $chitiet->madvbc = $a_dvbc[$chitiet->masodv] ?? '';
                foreach ($a_phucap as $mapc => $tenpc) {
                    $chitiet->$mapc = $chitiet->$mapc * 6;
                }
                $chitiet->stbhxh_dv = $chitiet->stbhxh_dv * 6;
                $chitiet->stbhyt_dv = $chitiet->stbhyt_dv * 6;
                $chitiet->stkpcd_dv = $chitiet->stkpcd_dv * 6;
                $chitiet->stbhtn_dv = $chitiet->stbhtn_dv * 6;
                $chitiet->ttl = $chitiet->ttl * 6;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $inputs['lamtron'] = session('admin')->lamtron ?? 3;
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            return view('reports.thongtu78.tinh.tonghopnhucau')
                ->with('model', $model)
                ->with('model_donvi_bc', $model_donvi_bc)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị cấp dưới');
        } else
            return view('errors.notlogin');
    }
}
