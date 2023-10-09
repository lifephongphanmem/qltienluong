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
use App\nguonkinhphi_tinh;
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
            //dd($model_dvbc->toarray());
            return view('reports.baocaotonghop.tinh.index')
                ->with('model_dv', $model_dv)
                ->with('a_thang', $a_thang)
                ->with('m_donvi', $m_donvi)
                ->with('a_phanloai', $a_phanloai)
                ->with('a_dvbc', array_column($model_dvbc->toarray(), 'tendvbc', 'madvbc'))
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
            // dd($view);
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
            $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_dsdv = dmdonvi::where('trangthai', '<>', 'TD')->orwherenull('trangthai')->get();
            $a_dv = array_column($m_dsdv->toArray(), 'madv');
            $model = nguonkinhphi_phucap::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            //dd($model->toArray());
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
            foreach ($model as $key => $chitiet) {
                $chitiet->madv = $a_tonghop[$chitiet->masodv] ?? '';
                //Lọc các đơn vị tạm ngưng theo dõi
                if (!in_array($chitiet->madv, $a_dv)) {
                    $model->forget($key);
                    continue;
                }
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

    function tonghopnhucau(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            if ($inputs['madvbc'] != 'ALL') {
                $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->where('madvbc', $inputs['madvbc'])->get();
                $model_donvi_bc = dmdonvibaocao::where('madvbc', $inputs['madvbc'])->get();
            } else {
                $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
                $model_donvi_bc = dmdonvibaocao::where('baocao', 1)->get();
            }
            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }

            //dd($inputs);
            // $m_banhanh = nguonkinhphi::where('madv', $inputs['macqcq'])->where('sohieu', $inputs['sohieu'])->first();
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            //dd($m_nguonkp);
            //->get();


            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::where('trangthai', '<>', 'TD')->orwherenull('trangthai')->get();
            $a_dv = array_column($m_dsdv->toArray(), 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_dv);
            $m_chitiet = nguonkinhphi_phucap::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            foreach ($m_chitiet as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //Lọc các đơn vị tạm ngưng theo dõi
                if (!in_array($chitiet->madv, $a_dv)) {
                    $m_chitiet->forget($key);
                    continue;
                }
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                $chitiet->tongpc = $chitiet->tonghs - $chitiet->heso;
                $chitiet->st_tongpc = $chitiet->ttl - $chitiet->st_heso;
                $chitiet->tongcong = $chitiet->st_tongpc + $chitiet->st_heso + $chitiet->ttbh_dv;
            }
            //dd($m_chitiet->where('linhvuchoatdong', 'DDT')->wherein('nhomnhucau', ['BIENCHE'])->toarray());
            ///khanh sơn - văn phòng 1511582645
            $m_phucap = dmphucap::wherenotin('mapc', ['heso'])->get();
            $a_phucap = array_keys(getPhuCap2a_78());

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->wherein('nhomnhucau', ['BIENCHE']);
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }

                    $a_solieu = [];
                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '2') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_I[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];
                    }
                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }

            //Vòng cấp độ 1
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_I[$key]['canbo_congtac'] = $ar_I[$key]['canbo_dutoan'] = 0;

                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;


                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] =  0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ
                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_I[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];
                    }
                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }

            //Vòng cấp độ 9
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['capdo'] == '9') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] =  0;
                    }
                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }
            //

            //
            //Tính toán số liệu phần II
            $ar_II = getChuyenTrach();
            $dulieu_pII = $m_chitiet->wherein('nhomnhucau', ['CANBOCT']);
            //$aII_plct = getChuyenTrach_plct();
            // foreach ($dulieu_pII as $key => $value) {
            //     if (count($aII_plct) > 0 && !in_array($value->mact, $aII_plct))
            //         $dulieu_pII->forget($key);
            // }
            //Vòng cấp độ 3
            foreach ($ar_II as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];
                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $dulieu_chitiet->sum('st_heso');
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_II[$key]['solieu'] = $a_solieu;
                }
            }

            //Tính toán số liệu phần III
            $ar_III = getHDND();
            //$aIII_plct = getHDND_plct();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');
            // foreach ($dulieu_pIII as $key => $value) {
            //     if (count($aIII_plct) > 0 && !in_array($value->mact, $aIII_plct))
            //         $dulieu_pIII->forget($key);
            // }

            //Vòng cấp độ 3
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pIII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }

                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $a_solieu['st_heso'];
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_III[$key]['solieu'] = $a_solieu;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_III[$key]['canbo_congtac'] = $ar_III[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_III[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_III[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_III[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_III[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_III[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_III[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_III[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_III[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_III[$k]['solieu']['tongcong'];
                    }
                    $ar_III[$key]['solieu'] = $a_solieu;
                }
            }

            //Tính toán số liệu phần IV
            $ar_IV = getCapUy();
            $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY');
            // $aIV_plct = getCapUy_plct();
            // foreach ($dulieu_pIV as $key => $value) {
            //     if (count($aIV_plct) > 0 && !in_array($value->mact, $aIV_plct))
            //         $dulieu_pIV->forget($key);
            // }
            //Vòng cấp độ 3
            foreach ($ar_IV as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pIV;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = $dulieu_chitiet->sum($mapc_st);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $a_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $a_solieu['st_heso'];
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_IV[$key]['solieu'] = $a_solieu;
                }
            }

            //Vòng cấp độ 2
            foreach ($ar_IV as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_IV[$key]['canbo_congtac'] = $ar_IV[$key]['canbo_dutoan'] = 0;
                    $a_solieu['canbo_congtac'] = $a_solieu['canbo_dutoan'] = $a_solieu['heso'] = $a_solieu['st_heso'] = $a_solieu['tongpc'] = $a_solieu['st_tongpc']
                        = $a_solieu['tongbh_dv'] = $a_solieu['ttbh_dv'] = $a_solieu['tongcong'] = 0;

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_IV[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_IV[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_IV[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_IV[$k]['solieu']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu[$pc->mapc] += $ar_IV[$k]['solieu'][$pc->mapc];
                            $a_solieu[$mapc_st] += $ar_IV[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_IV[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_IV[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_IV[$k]['solieu']['tongcong'];
                    }
                    $ar_IV[$key]['solieu'] = $a_solieu;
                }
            }

            //Tính toán tổng cộng

            $a_Tong['solieu'] = [
                'tongcong' => $ar_I[0]['solieu']['tongcong'] + $ar_II[0]['solieu']['tongcong']
                    + $ar_III[0]['solieu']['tongcong'] + $ar_IV[0]['solieu']['tongcong'],
                'st_heso' => $ar_I[0]['solieu']['st_heso'] + $ar_II[0]['solieu']['st_heso']
                    + $ar_III[0]['solieu']['st_heso'] + $ar_IV[0]['solieu']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu']['st_tongpc'] + $ar_II[0]['solieu']['st_tongpc']
                    + $ar_III[0]['solieu']['st_tongpc'] + $ar_IV[0]['solieu']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu']['ttbh_dv'] + $ar_II[0]['solieu']['ttbh_dv']
                    + $ar_III[0]['solieu']['ttbh_dv'] + $ar_IV[0]['solieu']['ttbh_dv'],
            ];

            foreach ($m_phucap as $pc) {
                $mapc_st = 'st_' . $pc->mapc;
                $a_Tong['solieu'][$mapc_st] = $ar_I[0]['solieu'][$mapc_st] + $ar_II[0]['solieu'][$mapc_st]
                    + $ar_III[0]['solieu'][$mapc_st] + $ar_IV[0]['solieu'][$mapc_st];
            }


            // dd($ar_II);

            $m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
            return view('reports.thongtu78.huyen.mautonghop')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('dulieu_pI', $dulieu_pI)
                ->with('ar_II', $ar_II)
                ->with('dulieu_pII', $dulieu_pII)
                ->with('ar_III', $ar_III)
                ->with('dulieu_pIII', $dulieu_pIII)
                ->with('ar_IV', $ar_IV)
                ->with('dulieu_pIV', $dulieu_pIV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_dv)
                ->with('inputs', $inputs)
                //->with('a_phucap', $a_phucap)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
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
            $inputs['madvbc'] = $inputs['madvbc'] ?? 'ALL';
            if ($inputs['madvbc'] != 'ALL') {
                $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->where('madvbc', $inputs['madvbc'])->get();
            } else {
                $m_nguonkp_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])->get();
            }
            // dd($m_nguonkp_tinh);
            if ($m_nguonkp_tinh->count() == 0) {
                return view('errors.nodata')
                    ->with('message', 'Chưa có dữ liệu nhu cầu kinh phí của đơn vị.')
                    ->with('furl', '/tong_hop_bao_cao/danh_sach');
            }
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::wherein('masot', array_column($m_nguonkp_tinh->toarray(), 'masodv'))->get();
            $m_dsdv = dmdonvi::where('trangthai', '<>', 'TD')->orwherenull('trangthai')->get();
            $a_dv = array_column($m_dsdv->toArray(), 'madv');

            $model = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            foreach (getColNhuCau() as $ct) {
                if ($model->sum($ct) > 0) {
                    $a_phucap[$ct] = isset($m_pc[$ct]) ? $m_pc[$ct] : '';
                    $col++;
                }
            }

            $a_tonghop = array_column($m_nguonkp->toarray(), 'madv', 'masodv');
            $a_dvbc = array_column($m_nguonkp->toarray(), 'madvbc', 'masodv');
            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            $a_nhomnhucau = ['BIENCHE', 'CANBOCT', 'HDND', 'CAPUY'];
            $a_donvi = array_column($m_nguonkp->toarray(), 'madv', 'masodv');
            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //dd($a_phucap);
            foreach ($model as $key => $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //Lọc các đơn vị tạm ngưng theo dõi
                if (!in_array($chitiet->madv, $a_dv)) {
                    $model->forget($key);
                    continue;
                }
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
