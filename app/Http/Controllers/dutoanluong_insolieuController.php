<?php

namespace App\Http\Controllers;


use App\dmchucvucq;
use App\dmdonvi;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class dutoanluong_insolieuController extends Controller
{
    function bangluongbienche(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);            
            $m_dutoan = dutoanluong::where('masodv', $inputs['masodv'])->first();
            //dd($m_dutoan);
            $model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->wherein('mact', $inputs['mact']);
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model = $model->where('mapb', $inputs['mapb']);
            }
            $model = $model->orderby('stt')->get();

            $m_chitiet = dutoanluong_chitiet::where('masodv', $inputs['masodv'])->wherein('mact', array_unique(array_column($model->toarray(), 'mact')))->get();
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

            //Xử lý lại mã cán bộ do trong lúc tạo dự toán đã gán hậu tố vào các cán bộ kiêm nhiệm
            //Cán bộ cùng mact + macanbo tính là 01 ng
            foreach ($model as $ct) {
                $a_macanbo = explode('_', $ct->macanbo);
                if (count($a_macanbo) > 1) {
                    $ct->macanbo = $a_macanbo[0] . '_' . $a_macanbo[1];
                }
                $ct->tongphucap = $ct->tonghs - $ct->heso;
                $ct->tongcong = $ct->tonghs + $ct->tongbh_dv;
                $ct->quyluong = ($ct->ttl + $ct->ttbh_dv) * 12;
            }
            //dd($m_chitiet->toarray());
            $a_phongban = array_column(dmphongban::where('madv', $m_dutoan->madv)->get()->toArray(), 'tenpb', 'mapb');
            return view('reports.dutoanluong.donvi.bangluongbienche')
                ->with('model', $model)
                ->with('m_chitiet', $m_chitiet)
                ->with('col', $col)
                ->with('a_plct', $a_plct)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('a_phongban', $a_phongban)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo hệ số lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function bangluonghopdong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_dutoan = dutoanluong::where('masodv', $inputs['masodv'])->first();
            //dd($m_dutoan);
            $model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->wherein('mact', $inputs['mact']);
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model = $model->where('mapb', $inputs['mapb']);
            }
            $model = $model->orderby('stt')->get();
            $m_chitiet = dutoanluong_chitiet::where('masodv', $inputs['masodv'])->wherein('mact', $inputs['mact'])->get();
            //dd($m_chitiet);
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
                $ct->tongcong = $ct->ttl + $ct->ttbh_dv;
                $ct->quyluong = $ct->tongcong * 12;
            }
            // dd($model);
            $a_phongban = array_column(dmphongban::where('madv', $m_dutoan->madv)->get()->toArray(), 'tenpb', 'mapb');
            return view('reports.dutoanluong.donvi.bangluonghopdong')
                ->with('model', $model)
                ->with('m_chitiet', $m_chitiet)
                ->with('col', $col)
                ->with('a_plct', $a_plct)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('m_donvi', $m_donvi)
                ->with('m_dutoan', $m_dutoan)
                ->with('a_phongban', $a_phongban)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Báo cáo hệ số lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function kinhphikhongchuyentrach(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            if (isset($inputs['madv'])) {
                $maphanloai = dmdonvi::where('madv', $inputs['madv'])->first()->maphanloai;
            } else {
                $maphanloai = session('admin')->maphanloai;
            }
            // dd(session('admin'));
            // dd($inputs); 
            if ($maphanloai == 'KVXP') {
                $model = dutoanluong::where('masodv', $inputs['maso'])->first();
            } else {
                $model = [];
            }
            // dd($model);
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($model);
            return view('reports.dutoanluong.donvi.kinhphikhongchuyentrach')
                ->with('model', $model)
                ->with('m_donvi', $m_donvi)
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
            $m_donvi = dmdonvi::where('madv', $m_dutoan->madv)->first();
            // dd($m_dutoan);
            $model = dutoanluong_bangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();
            $model_congtac = dmphanloaict::wherein('mact', array_unique(array_column($model->toArray(), 'mact')))->get();

            // dd($model);
            $m_chitiet = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();

            // dd($m_donvi);

            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso'); //do hệ số lương có cột cố định
            $model_pc = dmphucap_donvi::where('madv', $m_dutoan->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->orderby('stt')->get();
            $a_phucap = array();
            $col = 0;
            if (isset($model)) {
                foreach ($model_pc as $ct) {
                    if ($model->sum($ct->mapc) > 0) {
                        $a_phucap[$ct->mapc] = $ct->report;
                        $col++;
                    }
                }
            }


            //Lấy danh mục theo qd chức vụ
            $m_chucvu = dmchucvucq::wherein('mact', array_unique(array_column($model->toArray(), 'mact')))->get();
            //Kiểm tra xem chức vụ đó đã có trong chi tiết chưa => thêm vào nếu chưa có

            //Sắp sếp theo chức vụ

            $m_chucvu = dmchucvucq::wherein('madv', ['SA', 'SSA', $m_dutoan->madv])->get();
            $a_tencv = array_column($m_chucvu->toarray(), 'tencv', 'macvcq');
            $a_sapxep = array_column($m_chucvu->toarray(), 'sapxep', 'macvcq');
            if ($m_donvi->maphanloai == 'KVXP') {
                $model = $model;
            } else {
                $model = [];
            }
            foreach ($model as $ct) {
                $ct->tencv = $a_tencv[$ct->macvcq] ?? $ct->macvcq;
                $ct->sapxep = $a_sapxep[$ct->macvcq] ?? 999;
                $ct->tongphucap = $ct->tonghs - $ct->heso;
                $ct->tongcong = $ct->tonghs + $ct->tongbh_dv;
                $ct->quyluong = $ct->ttl + $ct->ttbh_dv;
            }


            //dd($col);
            return view('reports.dutoanluong.donvi.tonghopcanboxa')
                ->with('model', $model)
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

    // //bỏ
    // function tonghopdutoan(Request $request)
    // {
    //     if (Session::has('admin')) {
    //         $inputs = $request->all();
    //         //dd($inputs);            
    //         $m_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
    //         //dd($m_dutoan);
    //         $m_chitiet = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
    //         $model = new Collection();
    //         foreach (['COMAT' => 'Biên chế', 'CHUATUYEN' => 'Biên chế được duyệt nhưng chưa tuyển'] as $key => $val) {
    //             $chitiet = $m_chitiet->where('phanloai', $key);
    //             $add = new Collection();
    //             $add->phanloai = $key;
    //             $add->tenct = $val;
    //             $add->tonghs = $chitiet->sum('tonghs') / 12;
    //             $add->heso = $chitiet->sum('heso') / 12;
    //             $add->canbo_congtac = $chitiet->sum('canbo_congtac');
    //             $add->canbo_dutoan = $chitiet->sum('canbo_dutoan');                
    //             $add->baohiem = ($chitiet->sum('bhxh_dv') + $chitiet->sum('bhyt_dv') + $chitiet->sum('kpcd_dv')) / 12;
    //             $add->bhtn_dv = $chitiet->sum('bhtn_dv') / 12;
    //             $add->ttl = $chitiet->sum('ttl');
    //             $add->ttbh_dv = $chitiet->sum('ttbh_dv');
    //             $add->quyluong = $add->ttl + $add->ttbh_dv;
    //             $add->ttl = $add->ttl / 12;
    //             $add->ttbh_dv = $add->ttbh_dv / 12;
    //             $add->tongbh_dv = $chitiet->sum('tongbh_dv') / 12;
    //             $add->tongphucap = $add->tonghs - $add->heso;
    //             $add->tongcong = $add->tonghs + $add->tongbh_dv;
    //             $add->hesotrungbinh = round($add->sum('tongcong') / $add->sum('canbo_congtac'), 5);

    //             foreach (getColTongHop() as $pc) {
    //                 $add->$pc = $chitiet->sum($pc) / 12;
    //             }
    //             $model->add($add);
    //         }
    //         //dd($model);
    //         $m_donvi = dmdonvi::where('madv', $m_dutoan->madv)->first();
    //         //$a_plct = array_column(dmphanloaict::all()->toArray(),'tenct','mact');
    //         //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
    //         $a_goc = array('heso'); //do hệ số lương có cột cố định
    //         $model_pc = dmphucap_donvi::where('madv', $m_dutoan->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->orderby('stt')->get();
    //         $a_phucap = array();
    //         $col = 0;
    //         foreach ($model_pc as $ct) {
    //             if ($model->sum($ct->mapc) > 0) {
    //                 $a_phucap[$ct->mapc] = $ct->report;
    //                 $col++;
    //             }
    //         }

    //         //dd($model);
    //         return view('reports.dutoanluong.donvi.tonghopdutoan')
    //             ->with('model', $model)
    //             //->with('m_chitiet', $m_chitiet)
    //             ->with('col', $col)
    //             ->with('lamtron', session('admin')->lamtron ?? 3)
    //             //->with('model_congtac', $model_congtac)
    //             ->with('a_phucap', $a_phucap)
    //             ->with('m_donvi', $m_donvi)
    //             ->with('m_dutoan', $m_dutoan)
    //             ->with('pageTitle', 'Báo cáo tổng hợp biên chế hệ số tiền lương và phụ cấp');
    //     } else
    //         return view('errors.notlogin');
    // }

    function tonghopbienche(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $m_dutoan = dutoanluong::where('masodv', $inputs['masodv'])->first();
            //dd($inputs);
            $model = dutoanluong_chitiet::where('masodv', $inputs['masodv'])->wherein('mact', $inputs['mact'])->where('phanloai', '<>', 'CHUATUYEN')->get();

            $m_chuatuyen = dutoanluong_chitiet::where('masodv', $inputs['masodv'])->wherein('mact', $inputs['mact'])->where('phanloai', 'CHUATUYEN')->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();
            foreach ($model as $chitiet) {
                foreach ($a_pc as $pc) {
                    $chitiet->$pc = $chitiet->$pc / 12;
                }
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
                $chitiet->quyluong = $chitiet->ttl + $chitiet->ttbh_dv;
            }

            foreach ($m_chuatuyen as $chitiet) {
                foreach ($a_pc as $pc) {
                    $chitiet->$pc = ($chitiet->$pc / 12) * $chitiet->canbo_congtac;
                }
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->tonghs = ($chitiet->tonghs / 12) * $chitiet->canbo_congtac;

                $chitiet->bhxh_dv = ($chitiet->bhxh_dv / 12) * $chitiet->canbo_congtac;
                $chitiet->bhyt_dv = ($chitiet->bhyt_dv / 12) * $chitiet->canbo_congtac;
                $chitiet->kpcd_dv = ($chitiet->kpcd_dv / 12) * $chitiet->canbo_congtac;
                $chitiet->bhtn_dv = ($chitiet->bhtn_dv / 12) * $chitiet->canbo_congtac;
                $chitiet->baohiem = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->kpcd_dv;
                $chitiet->tongphucap = $chitiet->tonghs - $chitiet->heso;
                $chitiet->tongbh_dv = ($chitiet->tongbh_dv / 12) * $chitiet->canbo_congtac;
                $chitiet->tongcong = $chitiet->tonghs + $chitiet->tongbh_dv;
                $chitiet->hesotrungbinh = round($chitiet->tongcong / $chitiet->canbo_congtac, 5);
                $chitiet->quyluong = ($chitiet->ttl + $chitiet->ttbh_dv) *  $chitiet->canbo_congtac;
                //thêm vào model để in báo cáo
                $model->add($chitiet);
            }
            //dd($m_chuatuyen);
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

            //dd($a_phucap);
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

    function tonghophopdong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_dutoan = dutoanluong::where('masodv', $inputs['masodv'])->first();
            //dd($inputs);
            $model = dutoanluong_chitiet::where('masodv', $inputs['masodv'])->where('mact', $inputs['mact'])->get();
            $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pc = getColDuToan();
            foreach ($model as $chitiet) {
                foreach ($a_pc as $pc) {
                    $chitiet->$pc = $chitiet->$pc / 12;
                }
                $chitiet->tenct = $a_plct[$chitiet->mact] ?? '';
                $chitiet->luongthang = $chitiet->ttl / 12;
                $chitiet->baohiem = $chitiet->ttbh_dv / 12;
                $chitiet->tongcong = $chitiet->luongthang + $chitiet->baohiem;
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
            return view('reports.dutoanluong.donvi.tonghophopdong')
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
