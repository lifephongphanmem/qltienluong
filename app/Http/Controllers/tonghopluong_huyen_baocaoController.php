<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphanloaidonvi_baocao;
use App\dmphucap;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class tonghopluong_huyen_baocaoController extends Controller
{
    function TongHop_PhanLoaiDV(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $model_tonghop = tonghopluong_donvi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();
            // dd($model_tonghop);
            // $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();

            if ($model_tonghop->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['nam'])
                    ->with('furl', '/chuc_nang/tong_hop_luong/huyen/index?nam=' . $inputs['nam']);
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            //dd($m_donvi_baocao);
            $a_donvi = array_column($model_tonghop->toarray(), 'madv', 'mathdv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = tonghopluong_donvi_chitiet::where(function ($q) use ($inputs) {
                if (isset($inputs['mact'])) {
                    $q->wherein('mact', $inputs['mact']);
                }
                if (isset($inputs['linhvuchoatdong'])) {
                    $q->wherein('linhvuchoatdong', $inputs['linhvuchoatdong']);
                }
            })
                ->wherein('mathdv', array_column($model_tonghop->toarray(), 'mathdv'))->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColTongHop();
            //$a_luongcb = array_column($model_tonghop->toarray(),'luongcoban','mathdv');
            //Cho các trường hợp phụ cấp theo số tiền lấy theo lương cơ bản (đơn vi nào cá biệt pải tổng hợp lại chi trả lương)
            $ngayketxuat = Carbon::create($inputs['nam'], $inputs['thang'], 01)->toDateString();
            // dd($ngayketxuat);
            $luongcb = 1390000;
            if ($ngayketxuat < '2023-07-01' && $ngayketxuat > '2019-07-01') {
                $luongcb = 1490000;
            } else if($ngayketxuat > '2023-07-01' && $ngayketxuat < '2024-07-01' ){
                $luongcb = 1800000;
            }else{
                $luongcb = 2340000;
            }
            // dd($luongcb);


            $a_plct_dt = getPLCTDuToan();
            $m_bl = tonghopluong_donvi_bangluong::wherein('mathdv', array_column($model->toarray(), 'mathdv'))->get();
            // $m_donvi_nkp=$model->where('mact',1506672780)->where('madv',);
            // dd($m_donvi_nkp);
            //dd($ngayketxuat);
            //lấy mảng key->id của model để xét
            $a_key=array();
            foreach($model as $key=>$val)
            {

                $a_key[$val->id]=$key;
            }
            // dd($model);
            foreach ($model as $key=>$chitiet) {
                // dd($key);
                $chitiet->madv = $a_donvi[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongcoban = $luongcb;
                $chitiet->ttl = 0; //do trong bảng tonghopluong_donvi_chitiet khong có ttl
                foreach ($a_pc as $pc) {
                    if ($chitiet->$pc > 10000) {
                        //dd($chitiet);
                        $chitiet->$pc = round($chitiet->$pc / $chitiet->luongcoban, 7);
                        $chitiet->tonghs += $chitiet->$pc;
                    }
                }
                $chitiet->ttl = $chitiet->luongtn;
                $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, 7);
                $chitiet->baohiem = round(($chitiet->ttbh_dv - $chitiet->stbhtn_dv) / $chitiet->luongcoban, 7);

                $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
                $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;

                $chitiet->tongcong = $chitiet->tonghs + $chitiet->baohiem + $chitiet->bhtn_dv;
                // $chitiet->luongthang = ($chitiet->ttl / 12) / $inputs['donvitinh'];
                // $chitiet->baohiem = ($chitiet->ttbh_dv / 12) / $inputs['donvitinh'];
                //$chitiet->tongcong = ($chitiet->luongthang + $chitiet->baohiem) / $inputs['donvitinh'];
                // $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) / $inputs['donvitinh'];
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
                //Tính số lượng biên chế có mặt
                foreach ($a_plct_dt as $ct) {
                    if ($ct == $chitiet->mact) {
                        $canbo = $m_bl->where('mathdv', $chitiet->mathdv)->where('mact', $ct);
                        $a_data = a_unique(a_split($canbo->toarray(), array('mact', 'macanbo')));
                        $chitiet->soluongbienche = count($a_data);
                    }
                }
                // dd($chitiet);
                //Xét đơn vị có dùng 2 nguồn kinh phí để tạo bảng lương để cộng lại
                // $m_donvi_nkp = $model->where('mact', $chitiet->mact)->where('mathdv', $chitiet->mathdv)->where('id','<>',$chitiet->id);
                // // dd($m_donvi_nkp);
                // if (count($m_donvi_nkp) > 0) {
                //     // dd($m_donvi_nkp);
                //     //Hệ số giữ nguyên, cộng số tiền
                //     foreach ($m_donvi_nkp as $key => $ct) {
                //             // dd($a_key[$ct->id]);
                //             $ct->luongcoban = $luongcb;
                //             $ct->ttl = $ct->luongtn;
                //             $ct->bhtn_dv = round($ct->stbhtn_dv / $ct->luongcoban, 7);
                //             $ct->baohiem = round(($chitiet->ttbh_dv - $ct->stbhtn_dv) / $ct->luongcoban, 7);
                //             $ct->quyluong = $ct->ttl + $ct->ttbh_dv;

                //             $chitiet->ttl += $ct->ttl;
                //             $chitiet->bhtn_dv += $ct->bhtn_dv;
                //             $chitiet->baohiem += $ct->baohiem;
                //             $chitiet->quyluong += $ct->quyluong;
                //             $model->forget($a_key[$ct->id]);
                //     }
                // }
            }
            // dd($model);
            $model_h=new Collection();
            // dd($model_h);
            foreach($model as $val){
                $m_donvi_nkp = $model->where('madv',$val->madv)->where('mact',$val->mact)->where('mathdv', $val->mathdv);
                if(count($model_h->where('madv',$val->madv)->where('mact',$val->mact)->where('mathdv', $val->mathdv))>0){
                    continue;
                }
                if(count($m_donvi_nkp) == 1){
                    // dd($m_donvi_nkp);
                    $model_h->push($val);
                }else{
                    // dd($m_donvi_nkp);
                    // dd($val);
                    // dd($m_donvi_nkp->sum('baohiem'));
                    $val->ttl=$m_donvi_nkp->sum('ttl');
                    $val->bhtn_dv=$m_donvi_nkp->sum('bhtn_dv');
                    $val->baohiem=$m_donvi_nkp->sum('baohiem');
                    $val->quyluong=$m_donvi_nkp->sum('quyluong');
                    // dd($val);
                    $model_h->push($val);
                }

            }
            // dd($model_h);
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

            //dd($model->where('maphanloai','KVXP')->toArray());

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo

            //$m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($model);
            return view('reports.tonghopluong.huyen.SoLieuTongHop_PhanLoaiDV')
                // ->with('model', $model)
                ->with('model', $model_h)
                ->with('lamtron', 10)
                ->with('m_donvi', $m_donvi)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                //->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }
    function TongHop_PhanLoaiCT(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            $model_tonghop = tonghopluong_donvi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();
            // dd($model_tonghop);
            // $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();

            if ($model_tonghop->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['nam'])
                    ->with('furl', '/chuc_nang/tong_hop_luong/huyen/index?nam=' . $inputs['nam']);
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'))->get();
            //dd($m_donvi_baocao);
            $a_donvi = array_column($model_tonghop->toarray(), 'madv', 'mathdv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = tonghopluong_donvi_chitiet::where(function ($q) use ($inputs) {
                if (isset($inputs['mact'])) {
                    $q->wherein('mact', $inputs['mact']);
                }
                if (isset($inputs['linhvuchoatdong'])) {
                    $q->wherein('linhvuchoatdong', $inputs['linhvuchoatdong']);
                }
            })
                ->wherein('mathdv', array_column($model_tonghop->toarray(), 'mathdv'))->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColTongHop();
            //$a_luongcb = array_column($model_tonghop->toarray(),'luongcoban','mathdv');
            //Cho các trường hợp phụ cấp theo số tiền lấy theo lương cơ bản (đơn vi nào cá biệt pải tổng hợp lại chi trả lương)
            $ngayketxuat = Carbon::create($inputs['nam'], $inputs['thang'], 01)->toDateString();
            $luongcb = 1390000;
            if ($ngayketxuat < '2023-07-01' && $ngayketxuat > '2019-07-01') {
                $luongcb = 1490000;
            } else
                $luongcb = 1800000;


            $a_plct_dt = getPLCTDuToan();
            $m_bl = tonghopluong_donvi_bangluong::wherein('mathdv', array_column($model->toarray(), 'mathdv'))->get();
            // $m_donvi_nkp=$model->where('mact',1506672780)->where('madv',);
            // dd($m_donvi_nkp);
            //dd($ngayketxuat);
            //lấy mảng key->id của model để xét
            $a_key=array();
            foreach($model as $key=>$val)
            {

                $a_key[$val->id]=$key;
            }
            // dd($model);
            foreach ($model as $key=>$chitiet) {
                // dd($key);
                $chitiet->madv = $a_donvi[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongcoban = $luongcb;
                $chitiet->ttl = 0; //do trong bảng tonghopluong_donvi_chitiet khong có ttl
                foreach ($a_pc as $pc) {
                    if ($chitiet->$pc > 10000) {
                        //dd($chitiet);
                        $chitiet->$pc = round($chitiet->$pc / $chitiet->luongcoban, 7);
                        $chitiet->tonghs += $chitiet->$pc;
                    }
                }
                $chitiet->ttl = $chitiet->luongtn;
                $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, 7);
                $chitiet->baohiem = round(($chitiet->ttbh_dv - $chitiet->stbhtn_dv) / $chitiet->luongcoban, 7);

                $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
                $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;

                $chitiet->tongcong = $chitiet->tonghs + $chitiet->baohiem + $chitiet->bhtn_dv;
                // $chitiet->luongthang = ($chitiet->ttl / 12) / $inputs['donvitinh'];
                // $chitiet->baohiem = ($chitiet->ttbh_dv / 12) / $inputs['donvitinh'];
                //$chitiet->tongcong = ($chitiet->luongthang + $chitiet->baohiem) / $inputs['donvitinh'];
                // $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) / $inputs['donvitinh'];
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
                //Tính số lượng biên chế có mặt
                foreach ($a_plct_dt as $ct) {
                    if ($ct == $chitiet->mact) {
                        $canbo = $m_bl->where('mathdv', $chitiet->mathdv)->where('mact', $ct);
                        $a_data = a_unique(a_split($canbo->toarray(), array('mact', 'macanbo')));
                        $chitiet->soluongbienche = count($a_data);
                    }
                }
                // dd($chitiet);
                //Xét đơn vị có dùng 2 nguồn kinh phí để tạo bảng lương để cộng lại
                // $m_donvi_nkp = $model->where('mact', $chitiet->mact)->where('mathdv', $chitiet->mathdv)->where('id','<>',$chitiet->id);
                // // dd($m_donvi_nkp);
                // if (count($m_donvi_nkp) > 0) {
                //     // dd($m_donvi_nkp);
                //     //Hệ số giữ nguyên, cộng số tiền
                //     foreach ($m_donvi_nkp as $key => $ct) {
                //             // dd($a_key[$ct->id]);
                //             $ct->luongcoban = $luongcb;
                //             $ct->ttl = $ct->luongtn;
                //             $ct->bhtn_dv = round($ct->stbhtn_dv / $ct->luongcoban, 7);
                //             $ct->baohiem = round(($chitiet->ttbh_dv - $ct->stbhtn_dv) / $ct->luongcoban, 7);
                //             $ct->quyluong = $ct->ttl + $ct->ttbh_dv;

                //             $chitiet->ttl += $ct->ttl;
                //             $chitiet->bhtn_dv += $ct->bhtn_dv;
                //             $chitiet->baohiem += $ct->baohiem;
                //             $chitiet->quyluong += $ct->quyluong;
                //             $model->forget($a_key[$ct->id]);
                //     }
                // }
            }
            // dd($model);
            $model_h=new Collection();
            foreach($model as $val){
                $m_donvi_nkp = $model->where('madv',$val->madv)->where('mact',$val->mact)->where('mathdv', $val->mathdv);
                if(count($model_h->where('madv',$val->madv)->where('mact',$val->mact)->where('mathdv', $val->mathdv))>0){
                    continue;
                }
                if(count($m_donvi_nkp) == 1){
                    $model_h->push($val);
                }else{
                    // dd($val);
                    // dd($m_donvi_nkp->sum('baohiem'));
                    $val->ttl=$m_donvi_nkp->sum('ttl');
                    $val->bhtn_dv=$m_donvi_nkp->sum('bhtn_dv');
                    $val->baohiem=$m_donvi_nkp->sum('baohiem');
                    $val->quyluong=$m_donvi_nkp->sum('quyluong');
                    // dd($val);
                    $model_h->push($val);
                }

            }
            // dd($model_h);
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

            //dd($model->where('maphanloai','KVXP')->toArray());

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo

            //$m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($model);
            return view('reports.tonghopluong.huyen.SoLieuTongHop_PhanLoaiDV')
                // ->with('model', $model)
                ->with('model', $model_h)
                ->with('lamtron', 5)
                ->with('m_donvi', $m_donvi)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                //->with('m_dutoan', $m_dutoan)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp tiền lương và phụ cấp');
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
}
