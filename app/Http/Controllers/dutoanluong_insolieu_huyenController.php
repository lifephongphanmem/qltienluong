<?php

namespace App\Http\Controllers;


use App\bangluong;
use App\chitieubienche;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dsnangluong;
use App\dsnangthamnien;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use App\dutoanluong_nangluong;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\ngachluong;
use App\tonghopluong_donvi;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dutoanluong_insolieu_huyenController extends Controller
{
    //chưa dùng
    function baocaohesoluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            dd($inputs);
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

    function kinhphikhongchuyentrach(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong::where('masoh', $inputs['masodv'])->where('namns', $inputs['namns'])->where('trangthai', 'DAGUI')->get();
            $m_donvi_baocao = dmdonvi::wherein('madv', array_column($model->toarray(), 'madv'))->get();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //tính toán trc số liệu

            foreach ($model as $chitiet) {
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

    function tonghopcanboxa(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);            
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
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            //Lấy danh mục theo qd chức vụ
            $m_chucvu = dmchucvucq::wherein('mact', array_unique(array_column($model->toArray(), 'mact')))->get();
            //Kiểm tra xem chức vụ đó đã có trong chi tiết chưa => thêm vào nếu chưa có

            //Sắp sếp theo chức vụ

            $m_chucvu = dmchucvucq::wherein('madv', ['SA', 'SSA', $m_dutoan->madv])->get();
            $a_tencv = array_column($m_chucvu->toarray(), 'tencv', 'macvcq');
            $a_sapxep = array_column($m_chucvu->toarray(), 'sapxep', 'macvcq');
            foreach ($model as $ct) {
                $ct->tencv = $a_tencv[$ct->macvcq] ?? $ct->macvcq;
                $ct->sapxep = $a_sapxep[$ct->macvcq] ?? 999;
                $ct->tongphucap = $ct->tonghs - $ct->heso;
                $ct->tongcong = $ct->tonghs + $ct->tongbh_dv;
                $ct->quyluong = $ct->ttl + $ct->ttbh_dv;
            }
            //dd($col);
            return view('reports.dutoanluong.donvi.tonghopcanboxa')
                ->with('model', $model->sortby('sapxep'))
                ->with('m_chitiet', $m_chitiet)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('pageTitle', 'Báo cáo cán bộ chuyên trách, công chức cấp xã');
        } else
            return view('errors.notlogin');
    }

    function tonghopdutoan(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);            
            $m_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
            //dd($m_dutoan);
            $m_chitiet = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $model = new Collection();
            foreach (['COMAT' => 'Biên chế', 'CHUATUYEN' => 'Biên chế được duyệt nhưng chưa tuyển'] as $key => $val) {
                $chitiet = $m_chitiet->where('phanloai', $key);
                $add = new Collection();
                $add->phanloai = $key;
                $add->tenct = $val;
                $add->tonghs = $chitiet->sum('tonghs');
                $add->heso = $chitiet->sum('heso');
                $add->canbo_congtac = $chitiet->sum('canbo_congtac');
                $add->hesotrungbinh = round($add->tonghs / $add->canbo_congtac, 5);
                $add->baohiem = $chitiet->sum('bhxh_dv') + $chitiet->sum('bhyt_dv') + $chitiet->sum('kpcd_dv');
                $add->bhtn_dv = $chitiet->sum('bhtn_dv');
                $add->ttl = $chitiet->sum('ttl');
                $add->ttbh_dv = $chitiet->sum('ttbh_dv');
                $add->tongbh_dv = $chitiet->sum('tongbh_dv');
                $add->tongphucap = $add->tonghs - $add->heso;
                $add->tongcong = $add->tonghs + $add->tongbh_dv;
                $add->quyluong = $add->ttl + $add->ttbh_dv;

                foreach (getColTongHop() as $pc) {
                    $add->$pc = $chitiet->sum($pc);
                }
                $model->add($add);
            }
            //dd($model);
            $m_donvi = dmdonvi::where('madv', $m_dutoan->madv)->first();
            //$a_plct = array_column(dmphanloaict::all()->toArray(),'tenct','mact');
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso'); //do hệ số lương có cột cố định
            $model_pc = dmphucap_donvi::where('madv', $m_dutoan->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->orderby('stt')->get();
            $a_phucap = array();
            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            //dd($model);
            return view('reports.dutoanluong.donvi.tonghopdutoan')
                ->with('model', $model)
                //->with('m_chitiet', $m_chitiet)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                //->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function tonghopdutoan_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);            
            $m_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
            //dd($m_dutoan);
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($model as $chitiet) {
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->hesotrungbinh = round($chitiet->tonghs / $chitiet->canbo_congtac, 5);
                $chitiet->baohiem = $chitiet->sum('bhxh_dv') + $chitiet->sum('bhyt_dv') + $chitiet->sum('kpcd_dv');
                $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                $chitiet->tongcong = $chitiet->tonghs + $chitiet->tongbh_dv;
                $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
            }
            //dd($model);
            $m_donvi = dmdonvi::where('madv', $m_dutoan->madv)->first();

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso'); //do hệ số lương có cột cố định
            $model_pc = dmphucap_donvi::where('madv', $m_dutoan->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->orderby('stt')->get();
            $a_phucap = array();
            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            //dd($model);
            return view('reports.dutoanluong.donvi.tonghopdutoan_m2')
                ->with('model', $model)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
        } else
            return view('errors.notlogin');
    }
}
