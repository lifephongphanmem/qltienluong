<?php

namespace App\Http\Controllers;


use App\dmdonvi;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphanloaidonvi_baocao;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class dutoanluong_insolieu_huyenController extends Controller
{
    //chưa dùng
    function baocaohesoluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
            //dd($m_dutoan);
            $model = dutoanluong_bangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();
            $m_chitiet = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $m_donvi = dmdonvi::where('madv', $m_dutoan->madv)->first();
            $model_congtac = dmphanloaict::wherein('mact', array_unique(array_column($model->toArray(), 'mact')))->get();
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso'); //do hệ số lương có cột cố định
            $model_pc = dmphucap_donvi::where('madv', $m_dutoan->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->orderby('stt')->get();
            $a_phucap = array();
            $col = 0;
            $a_plct = array_column($model_congtac->toarray(), 'tenct', 'mact');
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            foreach ($model as $ct) {
                $ct->tongphucap = $ct->tonghs - $ct->heso;
                $ct->tongcong = $ct->tonghs + $ct->tongbh_dv;
                $ct->quyluong = ($ct->ttl + $ct->ttbh_dv) * 12;
            }
            //dd($col);
            return view('reports.dutoanluong.donvi.baocaohesoluong')
                ->with('model', $model)
                ->with('m_chitiet', $m_chitiet)
                ->with('col', $col)
                ->with('a_plct', $a_plct)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('pageTitle', 'Báo cáo hệ số lương tại đơn vị');
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

    function danhsachdonvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            // $m_dutoan = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            if(isset($inputs['tonghopkhoi'])){
                $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
                // $m_dutoan = dutoanluong::where('macqcq', $m_donvi->madv)->where('namns',$inputs['namns'])->where('trangthai', 'DAGUI')->get();
                $madv = session('admin')->madv;
                $nam = $inputs['namns'];
            }else{
                $m_dutoan = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
                $madv = $m_dutoan->madv;
                $nam = $m_dutoan->namns;
            }

            $model = dmdonvi::select('madv', 'tendv', 'maphanloai')
                ->where('macqcq', $madv)
                ->where('madv', '<>', $madv)
                ->wherenotin('madv', function ($query) use ($nam) {
                    $query->select('madv')->from('dmdonvi')
                        ->whereyear('ngaydung', '<=', $nam)
                        ->where('trangthai', 'TD')
                        ->get();
                })->get();
            $model_dutoan = dutoanluong::where('macqcq', $madv)->where('namns', $nam)->where('trangthai', 'DAGUI')->get();
            foreach ($model as $donvi) {
                $donvi->trangthai = 'CHOGUI';
                $dutoan = $model_dutoan->where('madv', $donvi->madv)->first();
                if ($dutoan != null) {
                    $donvi->trangthai = 'DAGUI';
                    $donvi->dutoan = ($dutoan->luongnb_dt + $dutoan->luonghs_dt + $dutoan->luongbh_dt) / $inputs['donvitinh'];
                }
            }
            if ($inputs['trangthai'] != 'ALL') {
                $model = $model->where('trangthai', $inputs['trangthai']);
            }
            $m_phanloai = dmphanloaidonvi::all();
            $m_donvi = dmdonvi::where('madv', $madv)->first();

            return view('reports.dutoanluong.Huyen.danhsachdonvi')
                ->with('model', $model)
                ->with('m_phanloai', $m_phanloai)
                ->with('m_donvi', $m_donvi)
                // ->with('m_dutoan', $m_dutoan)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách đơn vị cấp dưới');
        } else
            return view('errors.notlogin');
    }

    function kinhphikhongchuyentrach(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //Trường hợp được gọi từ chức năng "Báo cáo tổng hợp"
            if (isset($inputs['macqcq']) &&  $inputs['macqcq'] != '') {
                $m_dutoan_huyen = dutoanluong_huyen::where('madv', $inputs['macqcq'])->where('namns', $inputs['namns'])->first();
                if ($m_dutoan_huyen != null)
                    $inputs['masodv'] = $m_dutoan_huyen->masodv;
                else
                    $inputs['masodv'] = '';
            }
            //
            $model = dutoanluong::where('masoh', $inputs['masodv'])->where('namns', $inputs['namns'])->where('trangthai', 'DAGUI')->get();
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model->toarray(), 'madv'))->get();
            // dd($m_donvi_baocao);
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //tính toán trc số liệu

            foreach ($model as $k => $chitiet) {
                $m_dv = $m_donvi_baocao->where('madv', $chitiet->madv)->first();
                if ($m_dv->maphanloai != 'KVXP') {
                    $model->forget($k);
                }
                $chitiet->sotienphanloaixa = ($chitiet->phanloaixa_heso * 12 * $chitiet->luongcoban) / $inputs['donvitinh'];

                $chitiet->sotienxabiengioi = ($chitiet->sothonxabiengioi * $chitiet->sothonxabiengioi_heso * 12 * $chitiet->luongcoban) / $inputs['donvitinh'];
                $chitiet->sotienxakhokhan = ($chitiet->sothonxakhokhan * $chitiet->sothonxakhokhan_heso * 12 * $chitiet->luongcoban) / $inputs['donvitinh'];
                $chitiet->sotienxatrongdiem = ($chitiet->sothonxatrongdiem * $chitiet->sothonxatrongdiem_heso * 12 * $chitiet->luongcoban) / $inputs['donvitinh'];
                $chitiet->sotienxakhac = ($chitiet->sothonxakhac * $chitiet->sothonxakhac_heso * 12 * $chitiet->luongcoban) / $inputs['donvitinh'];
                $chitiet->sotienxaloai1 = ($chitiet->sothonxaloai1 * $chitiet->sothonxaloai1_heso * 12 * $chitiet->luongcoban) / $inputs['donvitinh'];
                $chitiet->tongsotienthon = $chitiet->sotienxabiengioi + $chitiet->sotienxakhokhan
                    + $chitiet->sotienxatrongdiem + $chitiet->sotienxakhac + $chitiet->sotienxaloai1;

                $chitiet->tongsothon = $chitiet->sothonxabiengioi + $chitiet->sothonxakhokhan + $chitiet->sothonxatrongdiem + $chitiet->sothonxakhac + $chitiet->sothonxaloai1;
            }
            return view('reports.dutoanluong.Huyen.kinhphikhongchuyentrach')
                ->with('model', $model)
                ->with('m_donvi', $m_donvi)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('pageTitle', 'Tổng hợp kinh phí thực hiện chế độ phụ cấp cho cán bộ không chuyên trách');
        } else
            return view('errors.notlogin');
    }

    function tonghopcanbohdnd(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            //Trường hợp được gọi từ chức năng "Báo cáo tổng hợp"
            if (isset($inputs['macqcq']) &&  $inputs['macqcq'] != '') {
                $m_dutoan_huyen = dutoanluong_huyen::where('madv', $inputs['macqcq'])->where('namns', $inputs['namns'])->first();
                if ($m_dutoan_huyen != null)
                    $inputs['masodv'] = $m_dutoan_huyen->masodv;
                else
                    $inputs['masodv'] = '';
            }
            //
            $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();

            //$m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');
            $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
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
            }
            return view('reports.dutoanluong.Huyen.tonghopcanbohdnd')
                ->with('model', $model)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_phucap', $a_phucap)
                ->with('a_plct', $a_plct)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function tonghopbienche(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //Trường hợp được gọi từ chức năng "Báo cáo tổng hợp"
            if (isset($inputs['macqcq']) &&  $inputs['macqcq'] != '') {
                $m_dutoan_huyen = dutoanluong_huyen::where('madv', $inputs['macqcq'])->where('namns', $inputs['namns'])->first();
                if ($m_dutoan_huyen != null)
                    $inputs['masodv'] = $m_dutoan_huyen->masodv;
                else
                    $inputs['masodv'] = '';
            }
            //

            $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            // dd($m_dutoan_huyen);
            if(isset($inputs['tonghopkhoi'])){
                $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
                $m_dutoan = dutoanluong::where('macqcq', $m_donvi->madv)->where('namns',$inputs['namns'])->where('trangthai', 'DAGUI')->get();
            }else{
                $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
                $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            }
            // dd($m_donvi);
            //$m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            // dd($m_phanloai);
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');
            // $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($m_dutoan->toarray(), 'madv'))->get();
            $a_donvi = array_column($m_dutoan->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->wherein('mact', $inputs['mact'])->get();
            //$m_chuatuyen = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->where('phanloai', 'CHUATUYEN')->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();
            //dd($model->toArray());
            foreach ($model as $chitiet) {
                //Chia làm 02 nhóm COMAT và CHUATUYEN
                if ($chitiet->phanloai == 'COMAT') {
                    //COMAT => chia 12
                    foreach ($a_pc as $pc) {
                        $chitiet->$pc = $chitiet->$pc / 12;
                    }
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
                } else {
                    //CHUATUYEN => nhân với số lượng cán bộ

                    foreach ($a_pc as $pc) {
                        $chitiet->$pc = ($chitiet->$pc / 12) * $chitiet->canbo_congtac;
                    }
                    $chitiet->madv = $a_donvi[$chitiet->masodv];
                    $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                    $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                    $chitiet->tonghs = ($chitiet->tonghs / 12) * $chitiet->canbo_congtac;

                    $chitiet->bhxh_dv = $chitiet->bhxh_dv / 12 * $chitiet->canbo_congtac;
                    $chitiet->bhyt_dv = $chitiet->bhyt_dv / 12 * $chitiet->canbo_congtac;
                    $chitiet->kpcd_dv = $chitiet->kpcd_dv / 12 * $chitiet->canbo_congtac;
                    $chitiet->bhtn_dv = $chitiet->bhtn_dv / 12 * $chitiet->canbo_congtac;
                    $chitiet->baohiem = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->kpcd_dv;
                    $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                    $chitiet->tongbh_dv = $chitiet->tongbh_dv / 12 * $chitiet->canbo_congtac;
                    $chitiet->tongcong = $chitiet->tonghs + $chitiet->tongbh_dv;
                    $chitiet->hesotrungbinh = $chitiet->tongcong;
                    $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) * $chitiet->canbo_congtac / $inputs['donvitinh'];
                }

                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
            }
            //dd($model->toArray());
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach ($a_pc as $ct) {
                if ($model->sum($ct) > 0 && $ct != 'heso') {
                    $a_phucap[$ct] = $a_tenpc[$ct];
                    $col++;
                }
            }
            // $model->where('maphanloai_goc3', $phanloai3->maphanloai_nhom)KVHCSN
            //dd($model->where('maphanloai_goc3', 'KVHCSN')->toarray());
            // $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($m_donvi_baocao->where('maphanloai','DAOTAO'));
            return view('reports.dutoanluong.Huyen.tonghopbienche')
                ->with('model', $model)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function tonghopbienche_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_phanloai = dmphanloaidonvi::all();
            // $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            $m_dutoan_huyen = dutoanluong_huyen::where('namns', $inputs['namns'])->where('madv', session('admin')->madv)->first();
            if (!isset($m_dutoan_huyen)&&!isset($inputs['tonghopkhoi'])) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['namns'])
                    ->with('furl', '/bao_cao/bang_luong/tong_hop');
            }


            // $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
            // $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            if(isset($inputs['tonghopkhoi'])){
                $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
                $m_dutoan = dutoanluong::where('macqcq', $m_donvi->madv)->where('namns',$inputs['namns'])->where('trangthai', 'DAGUI')->get();
            }else{
                $inputs['namns'] = $m_dutoan_huyen->namns;
                $inputs['masodv'] = $m_dutoan_huyen->masodv;
                $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
                $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            }
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($m_dutoan->toarray(), 'madv'))->get();
            //dd($m_donvi_baocao);
            $a_donvi = array_column($m_dutoan->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->wherein('mact', $inputs['mact'])->get();
            //$m_chuatuyen = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->where('phanloai', 'CHUATUYEN')->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();
            // foreach ($m_chuatuyen as $chitiet) {
            //     // foreach ($a_pc as $pc) {
            //     //     $chitiet->$pc = $chitiet->$pc / 12;
            //     // }
            //     // $chitiet->madv = $a_donvi[$chitiet->masodv];
            //     // $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
            //     // $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
            //     // $chitiet->tonghs = $chitiet->tonghs / 12;

            //     // $chitiet->bhxh_dv = $chitiet->bhxh_dv / 12;
            //     // $chitiet->bhyt_dv = $chitiet->bhyt_dv / 12;
            //     // $chitiet->kpcd_dv = $chitiet->kpcd_dv / 12;
            //     // $chitiet->baohiem = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->kpcd_dv;                
            //     // $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
            //     // $chitiet->tongbh_dv = $chitiet->tongbh_dv / 12;
            //     // $chitiet->tongcong = $chitiet->tonghs + $chitiet->tongbh_dv;
            //     // $chitiet->hesotrungbinh = round($chitiet->tongcong / $chitiet->canbo_congtac, 5);
            //     // $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv)/$inputs['donvitinh'];
            //     //thêm vào model để in báo cáo

            //     $model->add($chitiet);
            // }
            //tính toán lại
            //Chia làm 02 nhóm COMAT và CHUATUYEN

            foreach ($model as $chitiet) {
                if ($chitiet->phanloai == 'COMAT') {
                    //COMAT => chia 12
                    foreach ($a_pc as $pc) {
                        $chitiet->$pc = $chitiet->$pc / 12;
                    }
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

                    //2023.08.04 tạm thời bỏ do chưa rõ chức năng
                    // if ($model->where('madv', $chitiet->madv)->where('phanloai', 'CHUATUYEN')->count() == 0) {
                    //     $new =  new dutoanluong_chitiet();
                    //     $new->madv =  $chitiet->madv;
                    //     $new->maphanloai = $chitiet->madv;
                    //     $new->tenct = 'Biên chế chưa tuyển';
                    //     $model->add($new);
                    // }
                } else {
                    //CHUATUYEN => nhân với số lượng cán bộ
                    foreach ($a_pc as $pc) {
                        $chitiet->$pc = $chitiet->$pc / 12 * $chitiet->canbo_dutoan;
                    }
                    $chitiet->madv = $a_donvi[$chitiet->masodv];
                    $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                    $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                    $chitiet->tonghs = $chitiet->tonghs / 12 * $chitiet->canbo_dutoan;

                    $chitiet->bhxh_dv = $chitiet->bhxh_dv / 12 * $chitiet->canbo_dutoan;
                    $chitiet->bhyt_dv = $chitiet->bhyt_dv / 12 * $chitiet->canbo_dutoan;
                    $chitiet->kpcd_dv = $chitiet->kpcd_dv / 12 * $chitiet->canbo_dutoan;
                    $chitiet->bhtn_dv = $chitiet->bhtn_dv / 12 * $chitiet->canbo_dutoan;
                    $chitiet->baohiem = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->kpcd_dv;
                    $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                    $chitiet->tongbh_dv = $chitiet->tongbh_dv / 12 * $chitiet->canbo_dutoan;
                    $chitiet->tongcong = $chitiet->tonghs + $chitiet->tongbh_dv;
                    $chitiet->hesotrungbinh = $chitiet->tongcong;
                    $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) * $chitiet->canbo_dutoan / $inputs['donvitinh'];
                }
            }


            //dd($model);

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach ($a_pc as $ct) {
                if ($model->sum($ct) > 0 && $ct != 'heso') {
                    $a_phucap[$ct] = $a_tenpc[$ct];
                    $col++;
                }
            }
            //$m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($m_donvi_baocao);
            return view('reports.dutoanluong.Huyen.tonghopbienche_m2')
                ->with('model', $model)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function tonghophopdong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //Trường hợp được gọi từ chức năng "Báo cáo tổng hợp"
            if (isset($inputs['macqcq']) &&  $inputs['macqcq'] != '') {
                $m_dutoan_huyen = dutoanluong_huyen::where('madv', $inputs['macqcq'])->where('namns', $inputs['namns'])->first();
                if ($m_dutoan_huyen != null)
                    $inputs['masodv'] = $m_dutoan_huyen->masodv;
                else
                    $inputs['masodv'] = '';
            }
            //
            $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            // $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
            if(isset($inputs['tonghopkhoi'])){
                $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
                $m_dutoan = dutoanluong::where('macqcq', $m_donvi->madv)->where('namns',$inputs['namns'])->where('trangthai', 'DAGUI')->get();
            }else{
                $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
                $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            }

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
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

            return view('reports.dutoanluong.Huyen.tonghophopdong')
                ->with('model', $model)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function tonghophopdong_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_phanloai = dmphanloaidonvi::all();
            $m_dutoan_huyen = dutoanluong_huyen::where('namns', $inputs['namns'])->where('madv', session('admin')->madv)->first();
            if (!isset($m_dutoan_huyen) && !isset($inputs['tonghopkhoi'])) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['namns'])
                    ->with('furl', '/bao_cao/bang_luong/tong_hop');
            }
            if(isset($inputs['tonghopkhoi'])){
                $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
                $m_dutoan = dutoanluong::where('macqcq', $m_donvi->madv)->where('namns',$inputs['namns'])->where('trangthai', 'DAGUI')->get();
            }else{
                $inputs['namns'] = $m_dutoan_huyen->namns;
                $inputs['masodv'] = $m_dutoan_huyen->masodv;
                $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
                $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            }
            // $inputs['masodv'] = $m_dutoan_huyen->masodv;
            // $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->where('trangthai', 'DAGUI')->get();
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($m_dutoan->toarray(), 'madv'))->get();

            $a_donvi = array_column($m_dutoan->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->wherein('mact', $inputs['mact'])->get();

            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();
            foreach ($model as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                foreach ($a_pc as $pc) {
                    $chitiet->$pc = $chitiet->$pc / 12;
                }
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongthang = ($chitiet->ttl / 12) / $inputs['donvitinh'];;
                $chitiet->baohiem = ($chitiet->ttbh_dv / 12) / $inputs['donvitinh'];;
                $chitiet->tongcong = ($chitiet->luongthang + $chitiet->baohiem) / $inputs['donvitinh'];
                $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) / $inputs['donvitinh'];
            }


            //dd($model);
            // $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;
            foreach ($a_pc as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = $a_tenpc[$ct];
                    $col++;
                }
            }

            //dd($model);
            return view('reports.dutoanluong.Huyen.tonghophopdong_m2')
                ->with('model', $model)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function tonghopcanboxa(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //Trường hợp được gọi từ chức năng "Báo cáo tổng hợp"
            if (isset($inputs['macqcq']) &&  $inputs['macqcq'] != '') {
                $m_dutoan_huyen = dutoanluong_huyen::where('madv', $inputs['macqcq'])->where('namns', $inputs['namns'])->first();
                if ($m_dutoan_huyen != null)
                    $inputs['masodv'] = $m_dutoan_huyen->masodv;
                else
                    $inputs['masodv'] = '';
            }
            //
            $m_phanloai = dmphanloaidonvi::all();
            $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            if(isset($inputs['tonghopkhoi'])){
                $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
                // $m_dutoan = dutoanluong::where('macqcq', $m_donvi->madv)->where('namns',$inputs['namns'])->where('trangthai', 'DAGUI')->get();
            }else{
                $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
                // $m_donvi_baocao = dmdonvi::where('madvbc', $m_donvi->madvbc)->where('maphanloai', 'KVXP')->get();
                // $a_donvi_baocao = array_column($m_donvi_baocao->toarray(), 'tendv', 'madv');
                // $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->wherein('madv', array_keys($a_donvi_baocao))->where('trangthai', 'DAGUI')->get();
            }
            // $m_donvi = dmdonvi::where('madv', $m_dutoan_huyen->madv)->first();
            $m_donvi_baocao = dmdonvi::where('madvbc', $m_donvi->madvbc)->where('maphanloai', 'KVXP')->get();
            $a_donvi_baocao = array_column($m_donvi_baocao->toarray(), 'tendv', 'madv');
            $m_dutoan = dutoanluong::where('masoh', $inputs['masodv'])->wherein('madv', array_keys($a_donvi_baocao))->where('trangthai', 'DAGUI')->get();


            $a_donvi = array_column($m_dutoan->toarray(), 'madv', 'masodv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model_chitiet = dutoanluong_chitiet::wherein('masodv', array_column($m_dutoan->toarray(), 'masodv'))->wherein('mact', $inputs['mact'])->get();

            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$a_pc = getColDuToan();
            foreach ($model_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                // $chitiet->ttl = $chitiet->ttl / $inputs['donvitinh'];
                // $chitiet->ttbh_dv = $chitiet->ttbh_dv / $inputs['donvitinh'];
                // $chitiet->tongcong = $chitiet->ttl + $chitiet->ttbh_dv;
            }
            $m_phucap =  dmphucap::where('dutoan', 1)->orderby('stt')->get();
            $model_comat = new Collection();
            $model_bienche = new Collection();
            //$model_noidung = new dmphucap();

            foreach ($m_phucap as $phucap) {
                $mapc = $phucap->mapc;
                $mapc_st = 'st_' . $phucap->mapc;
                $mapc_tong = 'tong_' . $phucap->mapc;
                $mapc_tongst = 'tongst_' . $phucap->mapc;

                if ($model_chitiet->sum($mapc) <= 0) {
                    continue;
                }
                $add_comat = new dmphucap();
                $add_comat->mapc = $phucap->mapc;
                $add_comat->tenpc = $phucap->tenpc;
                $add_comat->$mapc_tong = 0;
                $add_comat->$mapc_tongst = 0;

                $add_bienche = new dmphucap();
                $add_bienche->mapc = $phucap->mapc;
                $add_bienche->tenpc = $phucap->tenpc;
                $add_bienche->$mapc_tong = 0;
                $add_bienche->$mapc_tongst = 0;

                foreach ($a_donvi_baocao  as $key => $val) {
                    $st = 'st_' . $key;
                    //$sl = 'sl_' . $key;
                    $add_bienche->$key = $model_chitiet->where('madv', $key)->sum($mapc);
                    $add_bienche->$st = $model_chitiet->where('madv', $key)->sum($mapc_st) / $inputs['donvitinh'];
                    $add_bienche->$mapc_tong +=  $add_bienche->$key;
                    $add_bienche->$mapc_tongst += $add_bienche->$st;

                    $add_comat->$key = $model_chitiet->where('phanloai', 'COMAT')->where('madv', $key)->sum($mapc);
                    $add_comat->$st = $model_chitiet->where('phanloai', 'COMAT')->where('madv', $key)->sum($mapc_st) / $inputs['donvitinh'];
                    $add_comat->$mapc_tong += $add_comat->$key;
                    $add_comat->$mapc_tongst += $add_comat->$st;
                }

                $add_bienche->tonghs =  $add_bienche->$mapc_tong;
                $add_bienche->tongtl = $add_bienche->$mapc_tongst;
                $add_comat->tonghs = $add_comat->$mapc_tong;
                $add_comat->tongtl = $add_comat->$mapc_tongst;

                $model_comat->add($add_comat);
                $model_bienche->add($add_bienche);
                //$model_noidung->add($add_noidung);
            }

            //thêm bảo hiểm
            $add_comat = new dmphucap();
            $add_comat->mapc = 'tongbh_dv';
            $add_comat->tenpc = 'Các khoản đóng góp';
            $add_bienche = new dmphucap();
            $add_bienche->mapc = 'tongbh_dv';
            $add_bienche->tenpc = 'Các khoản đóng góp';
            foreach ($a_donvi_baocao  as $key => $val) {
                $st = 'st_' . $key;
                $add_bienche->$key = $model_chitiet->where('madv', $key)->sum('tongbh_dv');
                $add_bienche->$st = $model_chitiet->where('madv', $key)->sum('ttbh_dv') / $inputs['donvitinh'];
                $add_bienche->tong_tongbh_dv = $add_bienche->$key;

                $add_comat->$key = $model_chitiet->where('phanloai', 'COMAT')->where('madv', $key)->sum('tongbh_dv');
                $add_comat->$st = $model_chitiet->where('phanloai', 'COMAT')->where('madv', $key)->sum('ttbh_dv') / $inputs['donvitinh'];
                $add_comat->tong_tongbh_dv = $add_comat->$key;
            }
            $add_bienche->tonghs =  $model_chitiet->sum('tongbh_dv');
            $add_bienche->tongtl = $model_chitiet->sum('ttbh_dv') / $inputs['donvitinh'];
            $add_comat->tonghs = $model_chitiet->where('phanloai', 'COMAT')->sum('tongbh_dv');
            $add_comat->tongtl = $model_chitiet->where('phanloai', 'COMAT')->sum('ttbh_dv') / $inputs['donvitinh'];

            $model_comat->add($add_comat);
            $model_bienche->add($add_bienche);

            $model_sl_bienche = new Collection();
            $model_sl_bienche->tongsl = 0;
            $model_sl_comat = new Collection();
            $model_sl_comat->tongsl = 0;

            foreach ($a_donvi_baocao  as $key => $val) {
                $model_sl_bienche->$key = $model_chitiet->where('madv', $key)->sum('canbo_congtac');
                $model_sl_bienche->tongsl += $model_sl_bienche->$key;
                $model_sl_comat->$key = $model_chitiet->where('phanloai', 'COMAT')->where('madv', $key)->sum('canbo_congtac');
                $model_sl_comat->tongsl += $model_sl_comat->$key;
            }



            //dd($model_bienche);
            //dd($model_chitiet->where('phanloai','CHUATUYEN'));
            return view('reports.dutoanluong.Huyen.tonghopcanboxa')
                ->with('model_comat', $model_comat)
                ->with('model_bienche', $model_bienche)
                ->with('model_sl_bienche', $model_sl_bienche)
                ->with('model_sl_comat', $model_sl_comat)
                ->with('col', count($a_donvi_baocao))
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_donvi_baocao', $a_donvi_baocao)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp cán bộ chuyên trách, công chức xã');
        } else
            return view('errors.notlogin');
    }
}
