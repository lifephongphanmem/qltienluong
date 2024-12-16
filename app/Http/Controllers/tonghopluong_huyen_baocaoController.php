<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphanloaidonvi_baocao;
use App\dmphucap;
use Carbon\Carbon;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_bangluong;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_khoi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class tonghopluong_huyen_baocaoController extends Controller
{
    function TongHop_PhanLoaiDV(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            //Lấy dữ liệu
            $model_tonghop = tonghopluong_donvi::where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();
            $model_tonghop_khoi = tonghopluong_khoi::where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();

            if ($inputs['thang'] != 'ALL') {
                $model_tonghop = $model_tonghop->where('thang', $inputs['thang']);
                $model_tonghop_khoi = $model_tonghop_khoi->where('thang', $inputs['thang']);
            }

            if ($model_tonghop->count() == 0 && $model_tonghop_khoi->count()) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['nam'])
                    ->with('furl', '/chuc_nang/tong_hop_luong/huyen/index?nam=' . $inputs['nam']);
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_merge(array_column($model_tonghop->toarray(), 'madv'), array_column($model_tonghop_khoi->toarray(), 'madv')))->get();
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
            })->wherein('mathdv', array_column($model_tonghop->toarray(), 'mathdv'))->get();

            $model_dulieukhoi = tonghopluong_donvi_chitiet::where(function ($q) use ($inputs) {
                if (isset($inputs['mact'])) {
                    $q->wherein('mact', $inputs['mact']);
                }
                if (isset($inputs['linhvuchoatdong'])) {
                    $q->wherein('linhvuchoatdong', $inputs['linhvuchoatdong']);
                }
            })->wherein('mathk', array_column($model_tonghop_khoi->toarray(), 'mathdv'))->get();

            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColTongHop();

            //dd($model);
            //Xử lý dữ liệu bảng tông hợp đơn vị
            foreach ($model as $key => $chitiet) {
                // dd($key);
                if ($chitiet->luongcoban == 0) {
                    $tonghop = $model_tonghop->where('mathdv', $chitiet->mathdv)->first();
                    //Hàm sử lý khi tổng hợp ko có trường lương cơ bản
                    $luongcb = 1;
                    try {
                        $ngayketxuat = Carbon::create($tonghop->nam, $tonghop->thang, 01)->toDateString();
                        if ($ngayketxuat < '2023-07-01' && $ngayketxuat > '2019-07-01') {
                            $luongcb = 1490000;
                        } else if ($ngayketxuat > '2023-07-01' && $ngayketxuat < '2024-07-01') {
                            $luongcb = 1800000;
                        } else {
                            $luongcb = 2340000;
                        }
                    } catch (\Exception $e) {
                        $luongcb = 1390000;
                    }
                    $chitiet->luongcoban = $luongcb;
                }
                $chitiet->madv = $a_donvi[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                //$chitiet->luongcoban = $luongcb;
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

                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
                $chitiet->soluongbienche = $chitiet->soluong;
            }
            //Đối tượng để dải dữ liệu
            $model_h = new Collection();          
            //Chạy trc dữ liệu khối để cập nhật lương cơ bản
            foreach ($model_dulieukhoi as $chitiet) {
                if ($chitiet->luongcoban == 0) {
                    $tonghop = $model_tonghop_khoi->where('mathdv', $chitiet->mathdv)->first();
                    //Hàm sử lý khi tổng hợp ko có trường lương cơ bản
                    $luongcb = 1;
                    try {
                        $ngayketxuat = Carbon::create($tonghop->nam, $tonghop->thang, 01)->toDateString();
                        if ($ngayketxuat < '2023-07-01' && $ngayketxuat > '2019-07-01') {
                            $luongcb = 1490000;
                        } else if ($ngayketxuat > '2023-07-01' && $ngayketxuat < '2024-07-01') {
                            $luongcb = 1800000;
                        } else {
                            $luongcb = 2340000;
                        }
                    } catch (\Exception $e) {
                        $luongcb = 1390000;
                    }
                    $chitiet->luongcoban = $luongcb;
                }
            }
            //Tính toán dữ liêu            
            foreach (a_unique(array_column($model_tonghop_khoi->toarray(), 'madv')) as $dv) {
                $m_dulieukhoi = $model_tonghop_khoi->where('madv', $dv);
                $dulieu_khoi = $model_dulieukhoi->wherein('mathk', array_column($m_dulieukhoi->toarray(), 'mathdv'));
                //dd($dulieu_khoi);
                if ($dulieu_khoi->count() > 0) {
                    $a_mact = a_unique(array_column($dulieu_khoi->toarray(), 'mact'));
                    //dd($a_mact);
                    foreach ($a_mact as $maCT) {
                        $dulieu = $dulieu_khoi->where('mact', $maCT);
                        $donvi = new tonghopluong_donvi_chitiet();
                        $donvi->luongcoban = $luongcb;
                        $donvi->madv = $dv;
                        $donvi->mact = $maCT;
                        $donvi->maphanloai = $a_pl_donvi[$donvi->madv];
                        $donvi->tenct = $a_plct[$maCT] ?? '';
                        $donvi->ttl = 0;
                        $donvi->soluongbienche = $dulieu->sum('soluong');
                        $this->getMaNhomPhanLoai($donvi, $m_phanloai);

                        $donvi->tonghs = $dulieu->sum('tonghs');
                        foreach ($a_pc as $pc) {
                            $donvi->$pc = $dulieu->sum($pc);
                            // if ($donvi->$pc > 50000) {
                            //     //dd($chitiet);
                            //     $donvi->$pc = round($dulieu->sum($pc) / $chitiet->luongcoban, 7);
                            //     $donvi->tonghs += $chitiet->$pc;
                            // }
                        }
                        $donvi->ttl = $dulieu->sum('luongtn');
                        $donvi->bhtn_dv = round($dulieu->sum('stbhtn_dv') / $donvi->luongcoban, 7);
                        $donvi->baohiem = round(($dulieu->sum('ttbh_dv') - $dulieu->sum('stbhtn_dv')) / $donvi->luongcoban, 7);

                        $donvi->quyluong = $dulieu->sum('luongtn') + $dulieu->sum('ttbh_dv');
                        $donvi->tongphucap = $donvi->tonghs - $donvi->heso;
                        $donvi->tongcong = $donvi->tonghs + $donvi->baohiem + $donvi->bhtn_dv;
                        //
                        $model_h->push($donvi);
                    }
                }
            }

            //
            foreach (a_unique(array_column($model->toarray(), 'madv')) as $dv) {
                $m_dulieu = $model->where('madv', $dv);
                if ($m_dulieu->count() > 0) {
                    $a_mact = a_unique(array_column($m_dulieu->toarray(), 'mact'));
                    foreach ($a_mact as $maCT) {
                        $dulieu = $m_dulieu->where('mact', $maCT);
                        $donvi = new tonghopluong_donvi_chitiet();
                        $donvi->luongcoban = $luongcb;
                        $donvi->madv = $dv;
                        $donvi->mact = $maCT;
                        $donvi->maphanloai = $a_pl_donvi[$donvi->madv];
                        $donvi->tenct = $a_plct[$maCT] ?? '';
                        $donvi->ttl = 0;
                        $donvi->soluongbienche = $dulieu->sum('soluong');
                        $this->getMaNhomPhanLoai($donvi, $m_phanloai);

                        $donvi->tonghs = $dulieu->sum('tonghs');
                        foreach ($a_pc as $pc) {
                            $donvi->$pc = $dulieu->sum($pc);
                        }
                        $donvi->ttl = $dulieu->sum('luongtn');
                        $donvi->bhtn_dv = round($dulieu->sum('stbhtn_dv') / $donvi->luongcoban, 7);
                        $donvi->baohiem = round(($dulieu->sum('ttbh_dv') - $dulieu->sum('stbhtn_dv')) / $donvi->luongcoban, 7);

                        $donvi->quyluong = $dulieu->sum('luongtn') + $dulieu->sum('ttbh_dv');
                        $donvi->tongphucap = $donvi->tonghs - $donvi->heso;
                        $donvi->tongcong = $donvi->tonghs + $donvi->baohiem + $donvi->bhtn_dv;
                        //
                        $model_h->push($donvi);
                    }
                }
            }           
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
            //dd($model_h);
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

    function TongHop_PhanLoaiDV_20241129(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //lấy mã đơn vị quản lý trong trường hợp gọi từ "Báo cáo tổng hợp" giao diện Tỉnh
            if (!isset($inputs['macqcq'])) {
                $inputs['macqcq'] = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->first()->madvcq;
            }
            //
            $a_cqcq = [$inputs['macqcq']];
            //lấy ds các đơn vị tổng hợp khối
            //$model_khoi =  dmdonvi::where('macqcq', $inputs['macqcq'])->where('phanloaitaikhoan', 'TH')->where('phamvitonghop', 'KHOI')->get();
            //$a_cqcq = array_merge($a_cqcq, array_column($model_khoi->toarray(), 'madv'));
            //Lấy dữ liệu
            $model_tonghop = tonghopluong_donvi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->wherein('macqcq', $a_cqcq)
                ->where('trangthai', 'DAGUI')->get();
            $model_tonghop_khoi = tonghopluong_khoi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->wherein('macqcq', $a_cqcq)
                ->where('trangthai', 'DAGUI')->get();
            dd($model_tonghop_khoi);
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
            } else if ($ngayketxuat > '2023-07-01' && $ngayketxuat < '2024-07-01') {
                $luongcb = 1800000;
            } else {
                $luongcb = 2340000;
            }
            // dd($luongcb);


            $a_plct_dt = getPLCTDuToan();
            $m_bl = tonghopluong_donvi_bangluong::select('mathdv', 'mact', 'macanbo')->wherein('mathdv', array_column($model->toarray(), 'mathdv'))->get();
            //dd($m_bl);
            // $m_donvi_nkp=$model->where('mact',1506672780)->where('madv',);
            // dd($m_donvi_nkp);
            //dd($ngayketxuat);
            //lấy mảng key->id của model để xét
            $a_key = array();
            foreach ($model as $key => $val) {
                $a_key[$val->id] = $key;
            }
            // dd($model);
            foreach ($model as $key => $chitiet) {
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
            $model_h = new Collection();
            // dd($model_h);
            foreach ($model as $val) {
                $m_donvi_nkp = $model->where('madv', $val->madv)->where('mact', $val->mact)->where('mathdv', $val->mathdv);
                if (count($model_h->where('madv', $val->madv)->where('mact', $val->mact)->where('mathdv', $val->mathdv)) > 0) {
                    continue;
                }
                if (count($m_donvi_nkp) == 1) {
                    // dd($m_donvi_nkp);
                    $model_h->push($val);
                } else {
                    // dd($m_donvi_nkp);
                    // dd($val);
                    // dd($m_donvi_nkp->sum('baohiem'));
                    $val->ttl = $m_donvi_nkp->sum('ttl');
                    $val->bhtn_dv = $m_donvi_nkp->sum('bhtn_dv');
                    $val->baohiem = $m_donvi_nkp->sum('baohiem');
                    $val->quyluong = $m_donvi_nkp->sum('quyluong');
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
            $inputs['donvitinh'] = $inputs['donvitinh'] ?? 1;
            //dd($inputs);//ipInSoTienBaoHiem
            //Lấy dữ liệu
            $model_tonghop = tonghopluong_donvi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();
            $model_tonghop_khoi = tonghopluong_khoi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();
            //dd($model_tonghop_khoi);

            if ($model_tonghop->count() == 0 && $model_tonghop_khoi->count()) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['nam'])
                    ->with('furl', '/chuc_nang/tong_hop_luong/huyen/index?nam=' . $inputs['nam']);
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();

            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_merge(array_column($model_tonghop->toarray(), 'madv'), array_column($model_tonghop_khoi->toarray(), 'madv')))->get();
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
            })->wherein('mathdv', array_column($model_tonghop->toarray(), 'mathdv'))->get();

            $model_dulieukhoi = tonghopluong_donvi_chitiet::where(function ($q) use ($inputs) {
                if (isset($inputs['mact'])) {
                    $q->wherein('mact', $inputs['mact']);
                }
                if (isset($inputs['linhvuchoatdong'])) {
                    $q->wherein('linhvuchoatdong', $inputs['linhvuchoatdong']);
                }
            })->wherein('mathk', array_column($model_tonghop_khoi->toarray(), 'mathdv'))->get();

            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColTongHop();
            //$a_luongcb = array_column($model_tonghop->toarray(),'luongcoban','mathdv');
            //Cho các trường hợp phụ cấp theo số tiền lấy theo lương cơ bản (đơn vi nào cá biệt pải tổng hợp lại chi trả lương)
            $ngayketxuat = Carbon::create($inputs['nam'], $inputs['thang'], 01)->toDateString();
            // dd($ngayketxuat);
            $luongcb = 1390000;
            if ($ngayketxuat < '2023-07-01' && $ngayketxuat > '2019-07-01') {
                $luongcb = 1490000;
            } else if ($ngayketxuat > '2023-07-01' && $ngayketxuat < '2024-07-01') {
                $luongcb = 1800000;
            } else {
                $luongcb = 2340000;
            }

            foreach ($model as $key => $chitiet) {
                // dd($key);
                $chitiet->madv = $a_donvi[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongcoban = $luongcb;
                $chitiet->ttl = 0; //do trong bảng tonghopluong_donvi_chitiet khong có ttl
                $chitiet->bhxh_dv = round($chitiet->stbhxh_dv / $chitiet->luongcoban, 7);
                $chitiet->bhyt_dv = round($chitiet->stbhyt_dv / $chitiet->luongcoban, 7);
                $chitiet->kpcd_dv = round($chitiet->stkpcd_dv / $chitiet->luongcoban, 7);
                $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, 7);

                // dd($chitiet);
                if (!empty(array_intersect($inputs['mact'], ['1689729806', '1506673585']))) {
                    //In hợp đồng
                    $chitiet->tonghs = 0; //đặt lại do tonghs co cong them ca tien vao nua 
                    foreach ($a_pc as $pc) {
                        if ($chitiet->$pc < 10000) {
                            $chitiet->tonghs += $chitiet->$pc;
                        }
                    }
                    $chitiet->tongphucap = $chitiet->tonghs;
                    $chitiet->ttl = $chitiet->luongtn;
                    $chitiet->stbhxh_dv = $chitiet->bhxh_dv * $chitiet->luongcoban;
                    $chitiet->stbhyt_dv = $chitiet->bhyt_dv * $chitiet->luongcoban;
                    $chitiet->stkpcd_dv = $chitiet->kpcd_dv * $chitiet->luongcoban;
                    $chitiet->stbhtn_dv = $chitiet->bhtn_dv * $chitiet->luongcoban;
                    $chitiet->ttbh_dv = $chitiet->stbhtn_dv + $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;
                    $chitiet->baohiem = $chitiet->stbhxh_dv +  $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;
                    $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
                } else {
                    //In biên chế
                    foreach ($a_pc as $pc) {
                        if ($chitiet->$pc > 10000) {
                            //dd($chitiet);
                            $chitiet->$pc = round($chitiet->$pc / $chitiet->luongcoban, 5);
                            $chitiet->tonghs += $chitiet->$pc;
                        }
                    }
                    $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                    $chitiet->stbhxh_dv = $chitiet->bhxh_dv * $chitiet->luongcoban;
                    $chitiet->stbhyt_dv = $chitiet->bhyt_dv * $chitiet->luongcoban;
                    $chitiet->stkpcd_dv = $chitiet->kpcd_dv * $chitiet->luongcoban;
                    $chitiet->stbhtn_dv = $chitiet->bhtn_dv * $chitiet->luongcoban;
                    $chitiet->ttbh_dv = $chitiet->stbhtn_dv + $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;
                    $chitiet->baohiem = $chitiet->bhxh_dv +  $chitiet->bhyt_dv + $chitiet->kpcd_dv;
                    $chitiet->tongcong = round($chitiet->tonghs + $chitiet->baohiem + $chitiet->bhtn_dv, 5);
                    $chitiet->quyluong = $chitiet->tongcong * $chitiet->luongcoban;
                    //2024.12.01 Tuỳ chọn in bảo hiểm theo số tiền (xem lại các hàm tính ở trên cho đỡ thừa)
                    if (!isset($inputs['ipInSoTienBaoHiem'])) {
                        $chitiet->tongcong = $chitiet->tonghs;
                        $chitiet->baohiem = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;;
                        $chitiet->bhtn_dv = $chitiet->stbhtn_dv;
                        $chitiet->quyluong = $chitiet->luongtn + $chitiet->baohiem + $chitiet->bhtn_dv;
                    }
                }
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
                $chitiet->soluongbienche = $chitiet->soluong;
            }
            //Đối tượng chứa dữ liệu trên báo cáo           
            $model_h = new Collection();

            //Xử lý dữ liệu bảng tông hợp khối
            //$model_khoi = new Collection();
            //dd();
            foreach ($model_tonghop_khoi as $chitiet) {
                $dulieu_khoi = $model_dulieukhoi->where('mathk', $chitiet->mathdv);
                if ($dulieu_khoi->count() > 0) {
                    $a_mact = a_unique(array_column($dulieu_khoi->toarray(), 'mact'));
                    //dd($a_mact);
                    foreach ($a_mact as $maCT) {
                        $dulieu = $dulieu_khoi->where('mact', $maCT);
                        $donvi = new tonghopluong_donvi_chitiet();
                        $donvi->luongcoban = $luongcb;
                        $donvi->madv = $chitiet->madv;
                        $donvi->mact = $maCT;
                        $donvi->maphanloai = $a_pl_donvi[$donvi->madv];
                        $donvi->tenct = $a_plct[$maCT] ?? '';
                        $donvi->luongcoban = $luongcb;
                        $donvi->ttl = 0;
                        $donvi->soluongbienche = $dulieu->sum('soluong');
                        $this->getMaNhomPhanLoai($donvi, $m_phanloai);

                        $donvi->tonghs = $dulieu->sum('tonghs');
                        foreach ($a_pc as $pc) {
                            $donvi->$pc = $dulieu->sum($pc);
                            // if ($donvi->$pc > 50000) {
                            //     //dd($chitiet);
                            //     $donvi->$pc = round($dulieu->sum($pc) / $chitiet->luongcoban, 7);
                            //     $donvi->tonghs += $chitiet->$pc;
                            // }
                        }
                        $donvi->ttl = $dulieu->sum('luongtn');
                        $donvi->bhtn_dv = round($dulieu->sum('stbhtn_dv') / $luongcb, 7);
                        $donvi->baohiem = round(($dulieu->sum('ttbh_dv') - $dulieu->sum('stbhtn_dv')) / $luongcb, 7);

                        $donvi->quyluong = $dulieu->sum('luongtn') + $dulieu->sum('ttbh_dv');
                        $donvi->tongphucap = $donvi->tonghs - $donvi->heso;
                        $donvi->tongcong = $donvi->tonghs + $donvi->baohiem + $donvi->bhtn_dv;
                        //2024.12.01 Tuỳ chọn in bảo hiểm theo số tiền (xem lại các hàm tính ở trên cho đỡ thừa)
                        if (!isset($inputs['ipInSoTienBaoHiem'])) {
                            $donvi->tongcong = $donvi->tonghs;
                            $donvi->baohiem = $dulieu->sum('stbhxh_dv') + $dulieu->sum('stbhyt_dv') + $dulieu->sum('stkpcd_dv');
                            $donvi->bhtn_dv = $dulieu->sum('stbhtn_dv');
                            $donvi->quyluong = $donvi->ttl + $donvi->baohiem + $donvi->bhtn_dv;
                        }
                        //
                        $model_h->push($donvi);
                    }
                }
            }

            //Gán dữ liệu            
            foreach ($model as $val) {
                $m_donvi_nkp = $model->where('madv', $val->madv)->where('mact', $val->mact)->where('mathdv', $val->mathdv);
                if (count($model_h->where('madv', $val->madv)->where('mact', $val->mact)->where('mathdv', $val->mathdv)) > 0) {
                    continue;
                }
                if (count($m_donvi_nkp) == 1) {
                    $model_h->push($val);
                } else {
                    $val->ttl = $m_donvi_nkp->sum('ttl');
                    $val->bhtn_dv = $m_donvi_nkp->sum('bhtn_dv');
                    $val->baohiem = $m_donvi_nkp->sum('baohiem');
                    $val->quyluong = $m_donvi_nkp->sum('quyluong');
                    // dd($val);
                    $model_h->push($val);
                }
            }
            // dd($model_h);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;

            if (!empty(array_intersect($inputs['mact'], ['1689729806', '1506673585']))) {
                foreach ($a_pc as $ct) {
                    if ($model->sum($ct) > 0 && $ct != 'luonghd') {
                        $a_phucap[$ct] = $a_tenpc[$ct];
                        $col++;
                    }
                }
                // dd(1234);
                $view = 'reports.tonghopluong.huyen.SoLieuTongHop_PLDV_HopDong';
            } else {
                foreach ($a_pc as $ct) {
                    if ($model->sum($ct) > 0 && $ct != 'heso') {
                        $a_phucap[$ct] = $a_tenpc[$ct];
                        $col++;
                    }
                }
                $view = 'reports.tonghopluong.huyen.SoLieuTongHop_PhanLoaiDV';
            }

            return view($view)
                ->with('model', $model_h)
                ->with('lamtron', 5)
                ->with('m_donvi', $m_donvi)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('m_phanloai', $m_phanloai)
                ->with('a_phanloai', $a_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo tổng hợp tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function TongHop_PhanLoaiCT_20241201(Request $request)
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
            $model_tonghopkhoi = tonghopluong_khoi::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->where('macqcq', $inputs['macqcq'])
                ->where('trangthai', 'DAGUI')->get();
            // dd($model_tonghopkhoi);
            // $m_dutoan_huyen = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();

            if ($model_tonghop->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu năm ' . $inputs['nam'])
                    ->with('furl', '/chuc_nang/tong_hop_luong/huyen/index?nam=' . $inputs['nam']);
            }

            $m_donvi = dmdonvi::where('madv', $inputs['macqcq'])->first();
            // dd($m_donvi);
            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', $m_donvi->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::all()->toArray(), 'maphanloai');

            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model_tonghop->toarray(), 'madv'));
            if (count($model_tonghopkhoi) > 0) {
                $m_donvi_baocao = $m_donvi_baocao->orwherein('madv', array_column($model_tonghopkhoi->toarray(), 'madv'));
            }
            $m_donvi_baocao = $m_donvi_baocao->get();
            // dd($m_donvi_baocao);
            $a_donvi = array_column($model_tonghop->toarray(), 'madv', 'mathdv') + array_column($model_tonghopkhoi->toarray(), 'madv', 'mathdv');
            $a_pl_donvi = array_column($m_donvi_baocao->toarray(), 'maphanloai', 'madv');
            $model = tonghopluong_donvi_chitiet::where(function ($q) use ($inputs) {
                if (isset($inputs['mact'])) {
                    $q->wherein('mact', $inputs['mact']);
                }
                if (isset($inputs['linhvuchoatdong'])) {
                    $q->wherein('linhvuchoatdong', $inputs['linhvuchoatdong']);
                }
            })
                ->where(function ($q) use ($model_tonghop, $model_tonghopkhoi) {
                    $q->whereIn('mathdv', array_column($model_tonghop->toarray(), 'mathdv'))
                        ->orWhereIn('mathk', array_column($model_tonghopkhoi->toarray(), 'mathdv'));
                })
                ->get();
            // $model_khoi = tonghopluong_donvi_chitiet::where(function ($q) use ($inputs) {
            //     if (isset($inputs['mact'])) {
            //         $q->wherein('mact', $inputs['mact']);
            //     }
            //     if (isset($inputs['linhvuchoatdong'])) {
            //         $q->wherein('linhvuchoatdong', $inputs['linhvuchoatdong']);
            //     }
            // })
            //     ->wherein('mathk', array_column($model_tonghopkhoi->toarray(), 'mathdv'))->get();
            // dd($model);
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColTongHop();
            //$a_luongcb = array_column($model_tonghop->toarray(),'luongcoban','mathdv');
            //Cho các trường hợp phụ cấp theo số tiền lấy theo lương cơ bản (đơn vi nào cá biệt pải tổng hợp lại chi trả lương)
            $ngayketxuat = Carbon::create($inputs['nam'], $inputs['thang'], 01)->toDateString();
            $luongcb = 1390000;

            if ($ngayketxuat < '2023-07-01' && $ngayketxuat >= '2019-07-01') {
                $luongcb = 1490000;
            } else if ($ngayketxuat > '2023-07-01' && $ngayketxuat < '2024-07-01') {
                $luongcb = 1800000;
            } else {
                $luongcb = 2340000;
            }
            // $a_plct_dt = getPLCTDuToan();
            // $m_bl = tonghopluong_donvi_bangluong::wherein('mathdv', array_column($model->toarray(), 'mathdv'))->get();
            // $m_donvi_nkp=$model->where('mact',1506672780)->where('madv',);
            // dd($m_donvi_nkp);
            //dd($ngayketxuat);
            //lấy mảng key->id của model để xét
            $a_key = array();
            foreach ($model as $key => $val) {
                $a_key[$val->id] = $key;
            }

            $model_phanloaicongtac = dmphanloaicongtac::all();
            // dd($model);
            foreach ($model as $key => $chitiet) {
                // if($chitiet->mathk){
                //     dd($a_donvi);
                //     dd($a_donvi[$chitiet->mathk]);
                // }
                // dd($key);

                $m_phanloaicongtac = $model_phanloaicongtac->where('macongtac', $chitiet->macongtac)->first();
                $chitiet->madv = $a_donvi[$chitiet->mathdv];
                $chitiet->maphanloai = $a_pl_donvi[$chitiet->madv];
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongcoban = $luongcb;
                $chitiet->ttl = 0; //do trong bảng tonghopluong_donvi_chitiet khong có ttl

                //Quy ra hệ số bảo hiểm do biên chế cần hiển thị bằng hệ số
                // $chitiet->bhxh_dv=round($chitiet->stbhxh_dv/($m_phanloaicongtac->bhxh_dv/100 * $chitiet->luongcoban),5);
                // $chitiet->bhyt_dv=round($chitiet->stbhyt_dv/($m_phanloaicongtac->bhyt_dv/100 * $chitiet->luongcoban),5);
                // $chitiet->bhtn_dv=round($chitiet->stbhtn_dv/($m_phanloaicongtac->bhtn_dv/100 * $chitiet->luongcoban),5);
                // $chitiet->kpcd_dv=round($chitiet->stkpcd_dv/($m_phanloaicongtac->kpcd_dv/100 * $chitiet->luongcoban),5);
                $chitiet->bhxh_dv = round($chitiet->stbhxh_dv / $chitiet->luongcoban, 5);
                $chitiet->bhyt_dv = round($chitiet->stbhyt_dv / $chitiet->luongcoban, 5);
                $chitiet->kpcd_dv = round($chitiet->stkpcd_dv / $chitiet->luongcoban, 5);
                $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, 5);
                // $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban,5);
                // dd($chitiet);
                if (!empty(array_intersect($inputs['mact'], ['1689729806', '1506673585']))) {
                    $chitiet->tonghs = 0; //đặt lại do tonghs co cong them ca tien vao nua 
                    foreach ($a_pc as $pc) {
                        if ($chitiet->$pc < 10000) {
                            $chitiet->tonghs += $chitiet->$pc;
                        }
                    }
                    $chitiet->tongphucap = $chitiet->tonghs;
                    // $chitiet->baohiem = ($chitiet->ttbh_dv - $chitiet->stbhtn_dv);
                    $chitiet->ttl = $chitiet->luongtn;
                    // $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
                    //Từ tiền quy ra hệ số rồi nhân lại để khớp với đơn vị
                    // $chitiet->stbhtn_dv = $chitiet->bhtn_dv * $m_phanloaicongtac->bhtn_dv/100 * $chitiet->luongcoban;
                    // $chitiet->stbhxh_dv = $chitiet->bhxh_dv * $m_phanloaicongtac->bhxh_dv/100 * $chitiet->luongcoban;
                    // $chitiet->stbhyt_dv = $chitiet->bhyt_dv * $m_phanloaicongtac->bhyt_dv/100 * $chitiet->luongcoban;
                    // $chitiet->stkpcd_dv = $chitiet->kpcd_dv * $m_phanloaicongtac->kpcd_dv/100 * $chitiet->luongcoban;
                    $chitiet->stbhxh_dv = $chitiet->bhxh_dv * $chitiet->luongcoban;
                    $chitiet->stbhyt_dv = $chitiet->bhyt_dv * $chitiet->luongcoban;
                    $chitiet->stkpcd_dv = $chitiet->kpcd_dv * $chitiet->luongcoban;
                    $chitiet->stbhtn_dv = $chitiet->bhtn_dv * $chitiet->luongcoban;
                    $chitiet->ttbh_dv = $chitiet->stbhtn_dv + $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;
                    $chitiet->baohiem = $chitiet->stbhxh_dv +  $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;
                    $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
                } else {
                    foreach ($a_pc as $pc) {
                        if ($chitiet->$pc > 10000) {
                            //dd($chitiet);
                            $chitiet->$pc = round($chitiet->$pc / $chitiet->luongcoban, 5);
                            $chitiet->tonghs += $chitiet->$pc;
                        }
                    }
                    $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                    // $chitiet->stbhtn_dv = $chitiet->bhtn_dv * $m_phanloaicongtac->bhtn_dv/100 * $chitiet->luongcoban;
                    // $chitiet->stbhxh_dv = $chitiet->bhxh_dv * $m_phanloaicongtac->bhxh_dv/100 * $chitiet->luongcoban;
                    // $chitiet->stbhyt_dv = $chitiet->bhyt_dv * $m_phanloaicongtac->bhyt_dv/100 * $chitiet->luongcoban;
                    // $chitiet->stkpcd_dv = $chitiet->kpcd_dv * $m_phanloaicongtac->kpcd_dv/100 * $chitiet->luongcoban;
                    $chitiet->stbhxh_dv = $chitiet->bhxh_dv * $chitiet->luongcoban;
                    $chitiet->stbhyt_dv = $chitiet->bhyt_dv * $chitiet->luongcoban;
                    $chitiet->stkpcd_dv = $chitiet->kpcd_dv * $chitiet->luongcoban;
                    $chitiet->stbhtn_dv = $chitiet->bhtn_dv * $chitiet->luongcoban;
                    $chitiet->ttbh_dv = $chitiet->stbhtn_dv + $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv;
                    $chitiet->baohiem = $chitiet->bhxh_dv +  $chitiet->bhyt_dv + $chitiet->kpcd_dv;
                    $chitiet->tongcong = round($chitiet->tonghs + $chitiet->baohiem + $chitiet->bhtn_dv, 5);
                    //Tính lại tổng tiền do khi quy về hệ số nhân ra sẽ bị lệch
                    //Không thể cộng hệ số bảo hiểm đơn vị vào hệ số lương để tính tổng được


                    // $chitiet->quyluong = $chitiet->tonghs * $chitiet->luongcoban + $chitiet->ttbh_dv;
                    $chitiet->quyluong = $chitiet->tongcong * $chitiet->luongcoban;
                }
                $this->getMaNhomPhanLoai($chitiet, $m_phanloai);
                $chitiet->soluongbienche = $chitiet->soluong;
                // //Tính số lượng biên chế có mặt
                // foreach ($a_plct_dt as $ct) {
                //     if ($ct == $chitiet->mact) {
                //         $canbo = $m_bl->where('mathdv', $chitiet->mathdv)->where('mact', $ct);
                //         $a_data = a_unique(a_split($canbo->toarray(), array('mact', 'macanbo')));
                //         $chitiet->soluongbienche = count($a_data);
                //     }
                // }
            }
            // dd($model->take(10));
            $model_h = new Collection();
            foreach ($model as $val) {
                $m_donvi_nkp = $model->where('madv', $val->madv)->where('mact', $val->mact);
                // dd($m_donvi_nkp);
                if (count($model_h->where('madv', $val->madv)->where('mact', $val->mact)) > 0) {
                    continue;
                }
                if (count($m_donvi_nkp) == 1) {
                    $model_h->push($val);
                } else {
                    // dd($val);
                    // dd($m_donvi_nkp->sum('baohiem'));
                    $val->ttl = $m_donvi_nkp->sum('ttl');
                    $val->soluongbienche = $m_donvi_nkp->sum('soluongbienche');
                    $val->bhtn_dv = $m_donvi_nkp->sum('bhtn_dv');
                    $val->baohiem = $m_donvi_nkp->sum('baohiem');
                    $val->quyluong = $m_donvi_nkp->sum('quyluong');
                    // dd($val);
                    $model_h->push($val);
                }
            }
            // dd($model_h);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $col = 0;


            //dd($model->where('maphanloai','KVXP')->toArray());

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo

            //$m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($model);
            // dd($inputs['mact']);
            if (!empty(array_intersect($inputs['mact'], ['1689729806', '1506673585']))) {
                foreach ($a_pc as $ct) {
                    if ($model->sum($ct) > 0 && $ct != 'luonghd') {
                        $a_phucap[$ct] = $a_tenpc[$ct];
                        $col++;
                    }
                }
                // dd(1234);
                $view = 'reports.tonghopluong.huyen.SoLieuTongHop_PLDV_HopDong';
            } else {
                foreach ($a_pc as $ct) {
                    if ($model->sum($ct) > 0 && $ct != 'heso') {
                        $a_phucap[$ct] = $a_tenpc[$ct];
                        $col++;
                    }
                }
                $view = 'reports.tonghopluong.huyen.SoLieuTongHop_PhanLoaiDV';
            }
            // return view('reports.tonghopluong.huyen.SoLieuTongHop_PhanLoaiDV')
            return view($view)
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
