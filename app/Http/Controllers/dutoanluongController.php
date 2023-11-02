<?php

namespace App\Http\Controllers;


use App\bangluong;
use App\chitieubienche;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dsdonviquanly;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dutoanluongController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = dutoanluong::where('madv', session('admin')->madv)->orderby('namns')->get();
            $model_bl = bangluong::where('madv', session('admin')->madv)->where('phanloai', 'BANGLUONG')->orderby('nam')->orderby('thang')->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_chitiet = dutoanluong_chitiet::wherein('masodv', array_column($model->toarray(), 'masodv'))->get();
            //Do chưa tuyển chỉ tính 12 tháng chưa nhân với số lượng cán bộ
            foreach ($model_chitiet as $chitiet) {
                if ($chitiet->phanloai == 'CHUATUYEN') {
                    $chitiet->st_heso = $chitiet->st_heso * $chitiet->canbo_congtac;
                    $chitiet->ttl = $chitiet->ttl * $chitiet->canbo_congtac;
                    $chitiet->ttbh_dv = $chitiet->ttbh_dv * $chitiet->canbo_congtac;
                }
            }
            foreach ($model as $dutoan) {
                //loại dữ liệu cũ
                $dutoan->luongnb_dt = 0;
                $dutoan->luonghs_dt = 0;
                $dutoan->luongbh_dt =  0;
                //TÍnh toán lại
                $chitiet = $model_chitiet->where('masodv', $dutoan->masodv);
                //dd($chitiet);
                $dutoan->luongnb_dt = $chitiet->sum('st_heso');
                $dutoan->luonghs_dt = $chitiet->sum('ttl') - $dutoan->luongnb_dt;
                $dutoan->luongbh_dt =  $chitiet->sum('ttbh_dv');
                $dutoan->tongdutoan = $dutoan->luongnb_dt + $dutoan->luonghs_dt + $dutoan->luongbh_dt;
                //dd($chitiet);
            }
            $a_cqcq = array_column(dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)
                ->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            return view('manage.dutoanluong.index')
                ->with('furl', '/nghiep_vu/quan_ly/du_toan/')
                ->with('furl_ajax', '/ajax/du_toan/')
                ->with('model', $model)
                ->with('model_bl', $model_bl)
                ->with('a_nkp', getNguonKP(false))
                ->with('a_phongban', getPhongBan(false))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('a_trangthai', getStatus())
                ->with('a_cqcq', $a_cqcq)
                ->with('pageTitle', 'Danh sách dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function thongtin_dutoan(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = dutoanluong::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->first();
            
            if ($model != null) {
                return redirect('/nghiep_vu/quan_ly/du_toan?maso=' . $model->masodv);
            }

            $m_bl = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('manguonkp', $inputs['manguonkp'])
                ->where('phanloai', 'BANGLUONG')
                ->first();

            $m_bl1 = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang1'])
                ->where('nam', $inputs['nam1'])
                ->where('manguonkp', $inputs['manguonkp1'])
                ->where('phanloai', 'BANGLUONG')
                ->first();
            if ($m_bl == null ) {
                    $message='Bảng lương tháng ' . $inputs['thang'] . ' năm ' . $inputs['nam'] .' không tồn tại. Bạn cần tạo bảng lương trước để có thể tạo dự toán.';              
                return view('errors.data_error')
                    ->with('message', $message)
                    ->with('furl', '/nghiep_vu/quan_ly/du_toan/danh_sach');
            }
            // $m_bl_ct = (new dataController())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            $m_bl_ct = (new dataController())->getBangluong_ct($m_bl->thang ?? 'null', $m_bl->mabl ?? 'null');
            $m_bl_ct1 = (new dataController())->getBangluong_ct($m_bl1->thang ?? 'null', $m_bl1->mabl ?? 'null');

            if ($m_bl_ct1 != null) {
                //kiểm tra cán bộ đã có trong bảng lương => tự động xóa trong bảng lương 1
                //$a_canbo = array_column($m_bl_ct->toarray(), 'macanbo');
                foreach ($m_bl_ct1 as $key => $val) {
                    $canbo = $m_bl_ct->where('macanbo', $val->macanbo)->where('mact', $val->mact)->where('macvcq', $val->macvcq);
                    if ($canbo->count() == 0) {
                        $m_bl_ct->add($val);
                    }
                }
            }

            // dd($m_bl_ct);
            $a_plct_bl = array_unique(array_column($m_bl_ct->toarray(), 'mact'));
            //xóa các chỉ tiêu cũ do có thể có 1 số plct thừa
            chitieubienche::where('madv', session('admin')->madv)->where('nam', $inputs['namns'])->delete();
            $a_plct = getPLCTDuToan();
            //dd($m_bl_ct->toarray());
            foreach ($a_plct as $plct) {
                if (!in_array($plct, $a_plct_bl)) {
                    continue;
                }
                //Cán bộ kiêm nhiệm có cùng mã phân loại công tác với lương chính => chỉ tính là 01 cán bộ
                $canbo = $m_bl_ct->where('mact', $plct);
                $a_data = a_unique(a_split($canbo->toarray(), array('mact', 'macanbo')));
                //dd($a_data);
                $chitieu = new chitieubienche();
                $chitieu->mact = $plct;
                $chitieu->madv = session('admin')->madv;
                $chitieu->nam = $inputs['namns'];
                $chitieu->soluongduocgiao = count($a_data);
                $chitieu->soluongbienche = $chitieu->soluongduocgiao;
                $chitieu->soluongtuyenthem = 0;
                $chitieu->mact_tuyenthem = session('admin')->mact_tuyenthem;
                $chitieu->heso = '2.34';
                $chitieu->save();
            }
            //dd(session('admin'));
            //$model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            //$model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $m_chitieu = chitieubienche::where('madv', session('admin')->madv)->where('nam', $inputs['namns'])->get();
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)
                ->wherein('mapc', getColDuToan())->get();
            $a_pc = [];

            foreach ($model_pc as $pc) {
                if ($m_chitieu->sum($pc->mapc) > 0) {
                    $a_pc[$pc->mapc] = $pc->tenpc;
                }
            }

            return view('manage.dutoanluong.thongtin')
                ->with('furl', '/nghiep_vu/quan_ly/du_toan/')
                ->with('inputs', $inputs)
                ->with('m_chitieu', $m_chitieu)
                ->with('model_pc', $model_pc)
                ->with('a_pc', $a_pc)
                ->with('a_nkp', getNguonKP(false))
                ->with('a_mact', array_column(dmphanloaict::select('tenct', 'macongtac', 'mact')->get()->toarray(), 'tenct', 'mact'))
                ->with('pageTitle', 'Thông tin dự toán lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function getchitieu(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = chitieubienche::find($inputs['id']);
        die($model);
    }

    public function updchitieu(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        //dd($inputs);
        //return response()->json($inputs);
        $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
        $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
        foreach ($model_pc as $pc) {
            $inputs[$pc->mapc] = chkDbl($inputs[$pc->mapc] ?? 0);
        }
        $inputs['soluongbienche'] = chkDbl($inputs['soluongbienche']);
        $inputs['soluongtuyenthem'] = chkDbl($inputs['soluongtuyenthem']);
        $inputs['soluongduocgiao'] = $inputs['soluongbienche'] + $inputs['soluongtuyenthem'];
        $model_chitieu = chitieubienche::find($inputs['id']);
        unset($inputs['id']);
        $model_chitieu->update($inputs);

        $model = chitieubienche::where('madv', $model_chitieu->madv)
            ->where('nam', $model_chitieu->nam)
            ->get();

        $a_pc = [];

        foreach ($model_pc as $pc) {
            if ($model->sum($pc->mapc) > 0) {
                $a_pc[$pc->mapc] = $pc->tenpc;
            }
        }

        $result['message'] = '<div class="row" id="dschitieu">';
        $result['message'] .= '<div class="col-md-12">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_4">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr class="text-center">';
        $result['message'] .= '<th rowspan="2" style="width: 5%">STT</th>';
        $result['message'] .= '<th rowspan="2">Phân loại công tác</th>';
        $result['message'] .= '<th rowspan="2">Số<br>lượng<br>được<br>giao</th>';
        $result['message'] .= '<th rowspan="2">Số<br>lượng<br>có<br>mặt</th>';
        $result['message'] .= '<th rowspan="2">Số<br>lượng<br>tuyển<br>thêm</th>';
        $result['message'] .= '<th colspan="' . count($a_pc) . '">Hệ số lương và phụ cấp</th>';
        $result['message'] .= '<th rowspan="2" style="width: 5%">Thao tác</th>';
        $result['message'] .= '</tr>';
        $result['message'] .= '<tr class="text-center">';
        foreach ($a_pc as $mapc => $tenpc) {
            $result['message'] .= '<th style="width: 5%">' . $tenpc . '</th>';
        }

        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';
        $result['message'] .= '<tbody>';
        $i = 1;
        $a_plct = array_column(dmphanloaict::select('tenct', 'mact')->get()->toarray(), 'tenct', 'mact');
        foreach ($model as $key => $value) {
            $result['message'] .= '<tr>';
            $result['message'] .= '<td class="text-center">' . $i++ . '</td>';
            $result['message'] .= '<td>' . ($a_plct[$value->mact] ?? '') . '</td>';
            $result['message'] .= '<td class="text-center">' . dinhdangso($value->soluongduocgiao) . '</td>';
            $result['message'] .= '<td class="text-center">' . dinhdangso($value->soluongbienche) . '</td>';
            $result['message'] .= '<td class="text-center">' . dinhdangso($value->soluongtuyenthem) . '</td>';
            foreach ($a_pc as $mapc => $tenpc) {
                $result['message'] .= '<td>' . dinhdangsothapphan($value->$mapc, 3) . '</td>';
            }
            $result['message'] .= '<td class="text-center">';
            $result['message'] .= '<button type="button" onclick="setChiTieu(&#39;' . $value->id . '&#39;,)" class="btn btn-default btn-xs mbs"';
            $result['message'] .= ' data-target="#chitiet-modal" data-toggle="modal">';
            $result['message'] .= ' <i class="fa fa-edit"></i>&nbsp; Sửa</button>';
            $result['message'] .= '</td>';
            $result['message'] .= '</tr>';
        }
        $result['message'] .= '</tbody>';
        $result['message'] .= '</table>';
        $result['message'] .= '</div>';
        $result['message'] .= '</div>';
        $result['status'] = 'success';

        return response()->json($result);
    }

    //Tạo dự toán theo bảng lương *12
    function tao_dutoan(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            if (dutoanluong::where('namns', $inputs['namns'])->where('madv', session('admin')->madv)->count() > 0) {
                return view('errors.data_error')
                    ->with('message', 'Dự toán năm ' . $inputs['namdt'] . ' đã tồn tại.')
                    ->with('furl', '/nghiep_vu/quan_ly/du_toan/danh_sach');
            }

            $m_bl = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])
                ->where('nam', $inputs['nam'])
                ->where('manguonkp', $inputs['manguonkp'])
                ->where('phanloai', 'BANGLUONG')
                ->first();
                // dd($m_bl);
            if ($m_bl == null) {
                return view('errors.data_error')
                    ->with('message', 'Bảng lương tháng ' . $inputs['thang'] . ' năm ' . $inputs['nam'] . ' không tồn tại. Bạn cần tạo bảng lương trước để có thể tạo dự toán.')
                    ->with('furl', '/nghiep_vu/quan_ly/du_toan/danh_sach');
            }

            $m_bl1 = bangluong::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang1'])
                ->where('nam', $inputs['nam1'])
                ->where('manguonkp', $inputs['manguonkp1'])
                ->where('phanloai', 'BANGLUONG')
                ->first();
            $m_bl_ct1 = (new dataController())->getBangluong_ct($m_bl1->thang ?? 'null', $m_bl1->mabl ?? 'null');

            $masodv = session('admin')->madv . '_' . getdate()[0];
            $m_bl_ct = (new dataController())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            $a_plct = getPLCTDuToan();
            $a_pc = getColDuToan();

            //thêm cán bộ chưa có từ $m_bl1 (phụ) vào $m_bl (chính)
            // dd($m_bl_ct);
            if ($m_bl_ct1 != null) {
                $i = 1;
                foreach ($m_bl_ct1 as $key => $val) {
                    $canbo = $m_bl_ct->where('macanbo', $val->macanbo)->where('mact', $val->mact)->where('macvcq', $val->macvcq);
                    if ($canbo->count() == 0) {
                        $val->macanbo = $val->macanbo . '_' . $i++;
                        $m_bl_ct->add($val);
                    }
                }
            }

            //dd($m_bl_ct);
            $a_baohiem = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get()->keyBy('mact')->toarray();
            // dd($a_baohiem);
            $model_phucap = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'tenpc', 'thaisan', 'nghiom', 'dieudong', 'thuetn', 'tapsu')
                ->where('madv', session('admin')->madv)
                ->wherein('mapc', $a_pc)->get();
            $a_pc_bh = array_column($model_phucap->where('baohiem', 1)->toarray(), 'mapc');
            //dd($m_bl_ct);
            foreach ($m_bl_ct as $key => $value) {
                if (!in_array($value->mact, $a_plct)) {
                    $m_bl_ct->pull($key);
                } else {
                    if (in_array($value->congtac, ['DAINGAY', 'THAISAN', 'KHONGLUONG'])) {
                        $value->tencanbo = ''; //Gán tên rỗng để sau cập nhập lại tên
                        //cán bộ nghỉ thì tính lại ô st_ do khi tính lương ô st_ gán bằng 0
                        //sau đó gán vào phần giảm trừ
                        $value->tonghs = 0;
                        $value->ttl = 0;
                        $sotienbaohiem = 0;
                        foreach ($a_pc as $mapc) {
                            $mapc_st = 'st_' . $mapc;
                            //chia lại phụ cấp lưu theo số tiền
                            if ($value->$mapc > 1000) {
                                $value->$mapc_st = $value->$mapc;
                                $value->$mapc = round($value->$mapc_st / $value->luongcoban, 10);
                            } else {
                                $value->$mapc_st =  round($value->$mapc * $value->luongcoban);
                            }
                            $value->tonghs += $value->$mapc;
                            $value->ttl += $value->$mapc_st;
                            if (in_array($mapc, $a_pc_bh))
                                $sotienbaohiem += $value->$mapc_st;
                        }
                        $baohiem = $a_baohiem[$value['mact']];
                        $value['bhxh_dv'] = round(floatval($baohiem['bhxh_dv']) / 100, 5);
                        $value['bhyt_dv'] = round(floatval($baohiem['bhyt_dv']) / 100, 5);
                        $value['bhtn_dv'] = round(floatval($baohiem['bhtn_dv']) / 100, 5);
                        $value['kpcd_dv'] = round(floatval($baohiem['kpcd_dv']) / 100, 5);
                        $value['tongbh_dv'] = $value->bhxh_dv + $value->bhyt_dv + $value->bhtn_dv + $value->kpcd_dv;

                        $value['stbhxh_dv'] = round($value['bhxh_dv'] * $sotienbaohiem, 0);
                        $value['stbhyt_dv'] = round($value['bhyt_dv']  * $sotienbaohiem, 0);
                        $value['stbhtn_dv'] = round($value['bhtn_dv'] * $sotienbaohiem, 0);
                        $value['stkpcd_dv'] = round($value['kpcd_dv'] * $sotienbaohiem, 0);
                        $value->ttbh_dv = $value->stbhxh_dv + $value->stbhyt_dv + $value->stbhtn_dv + $value->stkpcd_dv;


                        // dd($value);
                    } else {
                        //chạy lại 1 vòng để hệ số, số tiền (do báo cáo lấy hệ số, số tiền)
                        foreach ($a_pc as $pc) {
                            $tenpc_st = 'st_' . $pc;
                            $value->$pc = round($value->$tenpc_st / $value->luongcoban, 10);
                        }
                    }
                }
            }
            //dd($m_bl_ct->toarray());
            //dd($m_bl_ct->where('mact','1506672780'));
            //Tính lại lương theo mức lương cơ bản mới
            $a_hoten = array_column(hosocanbo::where('madv', session('admin')->madv)->get()->toarray(), 'tencanbo', 'macanbo');
            //dd($a_hoten);
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            foreach ($m_bl_ct as $chitiet) {
                $chitiet->macanbo_goc = $chitiet->macanbo;
                if ($chitiet->mact == '1506673585') {
                    //do hợp đồng 68 lương cố định
                    //gán lại lương cơ bản theo mức mới
                    $chitiet->luongcoban = $inputs['luongcoban'];
                    $chitiet->masodv = $masodv;
                    $chitiet->tongbh_dv = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->bhtn_dv + $chitiet->kpcd_dv;
                    //dd($chitiet);
                    continue;
                }
                $chenhlech = round($inputs['luongcoban'] / $chitiet->luongcoban, 10);
                //chưa xử lý cán bộ kiêm nhiệm
                if ($chitiet->tencanbo == '')
                    $chitiet->tencanbo = $a_hoten[$chitiet->macanbo] ?? '';

                if ($m_bl_ct->where('macanbo', $chitiet->macanbo)->count() > 1) {
                    $chitiet->macanbo = $chitiet->macanbo . '_' . $chitiet->id;
                }

                $chitiet->masodv = $masodv;
                $chitiet->tonghs = 0;
                $chitiet->ttl = 0;

                foreach ($a_pc as $pc) {
                    $tenpc_st = 'st_' . $pc;
                    $chitiet->$tenpc_st = round($inputs['luongcoban'] * $chitiet->$pc, 0);
                    $chitiet->tonghs += $chitiet->$pc;
                    $chitiet->ttl += $chitiet->$tenpc_st;
                }
                //quy đổi bảo hiểm thành hệ số
                $chitiet->bhxh_dv = round($chitiet->stbhxh_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->bhyt_dv = round($chitiet->stbhyt_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->bhtn_dv = round($chitiet->stbhtn_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->kpcd_dv = round($chitiet->stkpcd_dv / $chitiet->luongcoban, session('admin')->lamtron);
                $chitiet->tongbh_dv = $chitiet->bhxh_dv + $chitiet->bhyt_dv + $chitiet->bhtn_dv + $chitiet->kpcd_dv;

                //quy đổi bảo hiểm thành số tiền theo mức lương cơ bản mới
                //tính riêng cho HD 68 do quy đổi hệ số hay bi làm tròn
                if ($chitiet->mact == '1506673585') {
                    $chitiet->stbhxh_dv = round($chitiet->stbhxh_dv *  $chenhlech, 0);
                    $chitiet->stbhyt_dv = round($chitiet->stbhyt_dv  *  $chenhlech, 0);
                    $chitiet->stbhtn_dv = round($chitiet->stbhtn_dv  *  $chenhlech, 0);
                    $chitiet->stkpcd_dv = round($chitiet->stkpcd_dv  *  $chenhlech, 0);
                } else {
                    $chitiet->stbhxh_dv = round($chitiet->bhxh_dv *  $inputs['luongcoban'], 0);
                    $chitiet->stbhyt_dv = round($chitiet->bhyt_dv  *  $inputs['luongcoban'], 0);
                    $chitiet->stbhtn_dv = round($chitiet->bhtn_dv  *  $inputs['luongcoban'], 0);
                    $chitiet->stkpcd_dv = round($chitiet->kpcd_dv  *  $inputs['luongcoban'], 0);
                }

                $chitiet->ttbh_dv = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stbhtn_dv + $chitiet->stkpcd_dv;
                //gán lại lương cơ bản theo mức mới
                $chitiet->luongcoban = $inputs['luongcoban'];
            }

            //dự toán cho cán bộ chưa tuyển
            $m_chitieu = chitieubienche::select(array_merge(array('soluongtuyenthem', 'soluongduocgiao', 'soluongbienche', 'mact', 'mact_tuyenthem'), $a_pc))
                ->where('madv', session('admin')->madv)
                ->where('nam', $inputs['namns'])->get();
            // dd($m_chitieu);
            $a_chitieu = $m_chitieu->keyBy('mact')->toarray();
            //dd($model_phucap->toarray());
            foreach ($m_chitieu as $key => $val) {
                $val['soluongtuyenthem'] = chkDbl($val['soluongtuyenthem']);
                if ($val['soluongtuyenthem'] <= 0) {
                    $m_chitieu->pull($key);
                }
                $val['stt'] = 999;
                $val['macanbo'] = $val['mact_tuyenthem'] . '_' . $val['soluongtuyenthem'];
                $val['macvcq'] = '';
                $val['mapb'] = '';
                $val['masodv'] = $masodv;
                $val['tencanbo'] = 'Cán bộ chưa tuyển';
                $val['congtac'] = 'CHUATUYEN';
                $val['mact'] = $val['mact_tuyenthem'];
                $baohiem = $a_baohiem[$val['mact']];

                $val->tonghs = 0;
                $val->ttl = 0;
                $sotienbaohiem = 0;
                foreach ($model_phucap as $pc) {
                    $mapc = $pc->mapc;
                    $mapc_st = 'st_' . $mapc;
                    $pl = getDbl($pc->phanloai);
                    switch ($pl) {
                        case 0: { //hệ số
                                $val->$mapc_st = round($val->$mapc * $inputs['luongcoban'], 0);
                                $val->ttl += $val->$mapc_st;
                                $val->tonghs += $val->$mapc;
                                break;
                            }
                        case 1: { //số tiền
                                $val->$mapc_st = $val->$mapc;
                                $val->$mapc = round($val->$mapc_st / $inputs['luongcoban'], 5);
                                $val->ttl += $val->$mapc_st;
                                $val->tonghs += $val->$mapc;
                                break;
                            }
                        case 2: { //phần trăm
                                if ($mapc != 'vuotkhung') {
                                    $heso = 0;
                                    foreach (explode(',', $pc->congthuc) as $cthuc) {
                                        if ($cthuc != '')
                                            $heso += $val->$cthuc;
                                    }
                                    $val->$mapc = round($heso * $val->$mapc / 100, session('admin')->lamtron);
                                }

                                $val->$mapc_st = round($val->$mapc * $inputs['luongcoban'], 0);
                                $val->ttl += $val->$mapc_st;
                                $val->tonghs += $val->$mapc;
                                break;
                            }
                        default: { //trường hợp còn lại (ẩn,...)
                                break;
                            }
                    }
                    if (in_array($mapc, $a_pc_bh))
                        $sotienbaohiem += $val->$mapc_st;
                    // $a_pc_bh
                }


                $val['stbhxh_dv'] = round((floatval($baohiem['bhxh_dv']) / 100) * $sotienbaohiem, 0);
                $val['stbhyt_dv'] = round((floatval($baohiem['bhyt_dv']) / 100) * $sotienbaohiem, 0);
                $val['stbhtn_dv'] = round((floatval($baohiem['bhtn_dv']) / 100) * $sotienbaohiem, 0);
                $val['stkpcd_dv'] = round((floatval($baohiem['kpcd_dv']) / 100) * $sotienbaohiem, 0);
                $val->ttbh_dv = $val->stbhxh_dv + $val->stbhyt_dv + $val->stbhtn_dv + $val->stkpcd_dv;

                $val['bhxh_dv'] = round($val['stbhxh_dv'] / $inputs['luongcoban'], 5);
                $val['bhyt_dv'] = round($val['stbhyt_dv'] / $inputs['luongcoban'], 5);
                $val['bhtn_dv'] = round($val['stbhtn_dv'] / $inputs['luongcoban'], 5);
                $val['kpcd_dv'] = round($val['stkpcd_dv'] / $inputs['luongcoban'], 5);
                $val['tongbh_dv'] = $val->bhxh_dv + $val->bhyt_dv + $val->bhtn_dv + $val->kpcd_dv;
                //gán lại lương cơ bản
                $val->luongcoban = $inputs['luongcoban'];
            }

            //dd($m_chitieu);
            //Mảng lưu thông tin dự toán chi tiết
            $inputs['luongnb_dt'] = 0;
            $inputs['luonghs_dt'] = 0;
            $inputs['luongbh_dt'] = 0;
            $a_dutoan = [];
            //dd($m_bl_ct->toarray());
            //Tổng hợp lại dự toán cho cán bộ theo bảng lương
            foreach (array_unique(array_column($m_bl_ct->toarray(), 'mact')) as $data) {
                $dutoan = [];
                $canbo = $m_bl_ct->where('mact', $data);
                $dutoan['phanloai'] = 'COMAT';
                $dutoan['mact'] = $data;
                $dutoan['masodv'] = $masodv;
                //Do 2 cán bộ trong cùng 1 pân loại công tác => tính làm 01 cán bộ
                $dutoan['canbo_congtac'] = count(array_unique(array_column($canbo->toarray(), 'macanbo_goc')));
                $dutoan['canbo_dutoan'] =  $a_chitieu[$data]['soluongduocgiao'] ?? $dutoan['canbo_congtac'];
                //Tính lại tổng các phụ cấp
                foreach ($a_pc as $pc) {
                    $tenpc_st = 'st_' . $pc;
                    $dutoan[$pc] = $canbo->sum($pc) * 12;
                    $dutoan[$tenpc_st] = $canbo->sum($tenpc_st) * 12;
                    //lưu tổng số vào bảng "dutoanluong"
                    if (in_array($pc, ['heso'])) {
                        $inputs['luongnb_dt'] += $dutoan[$tenpc_st];
                    } else {
                        $inputs['luonghs_dt'] += $dutoan[$tenpc_st];
                    }
                }
                //Tính tổng hệ số, tổng tiền lương
                $dutoan['tonghs'] = $canbo->sum('tonghs') * 12;
                $dutoan['ttl'] = $canbo->sum('ttl') * 12;
                //Tính tổng hệ số bảo hiểm quy đổi
                $dutoan['bhxh_dv'] = $canbo->sum('bhxh_dv') * 12;
                $dutoan['bhyt_dv'] = $canbo->sum('bhyt_dv') * 12;
                $dutoan['bhtn_dv'] = $canbo->sum('bhtn_dv') * 12;
                $dutoan['kpcd_dv'] = $canbo->sum('kpcd_dv') * 12;
                $dutoan['tongbh_dv'] = $canbo->sum('tongbh_dv') * 12;
                //quy đổi bảo hiểm thành số tiền theo mức lương cơ bản
                $dutoan['stbhxh_dv'] = $canbo->sum('stbhxh_dv') * 12;
                $dutoan['stbhyt_dv'] = $canbo->sum('stbhyt_dv') * 12;
                $dutoan['stbhtn_dv'] = $canbo->sum('stbhtn_dv') * 12;
                $dutoan['stkpcd_dv'] = $canbo->sum('stkpcd_dv') * 12;
                $dutoan['ttbh_dv'] =  $canbo->sum('ttbh_dv') * 12;
                //lưu tổng số vào bảng "dutoanluong"
                $inputs['luongbh_dt'] += $dutoan['ttbh_dv'];
                //Lưu dự toán
                $a_dutoan[] = $dutoan;
            }
            //dd($inputs);   
            //Tổng hợp cán bộ chưa tuyển
            foreach (array_unique(array_column($m_chitieu->toarray(), 'mact')) as $data) {
                $dutoan = [];
                $canbo = $m_chitieu->where('mact', $data);
                $dutoan['phanloai'] = 'CHUATUYEN';
                $dutoan['mact'] = $data;
                $dutoan['masodv'] = $masodv;
                $dutoan['canbo_congtac'] = $canbo->sum('soluongtuyenthem'); //trường hợp 2 phân loại cùng chọn 01 mact_chuatuyen                
                $dutoan['canbo_dutoan'] =  $dutoan['canbo_congtac'];
                //Tính lại tổng các phụ cấp
                foreach ($a_pc as $pc) {
                    $tenpc_st = 'st_' . $pc;
                    $dutoan[$pc] = $canbo->sum($pc) * 12;
                    $dutoan[$tenpc_st] = $canbo->sum($tenpc_st) * 12;
                    //lưu tổng số vào bảng "dutoanluong"
                    if (in_array($pc, ['heso'])) {
                        $inputs['luongnb_dt'] += $dutoan[$tenpc_st];
                    } else {
                        $inputs['luonghs_dt'] += $dutoan[$tenpc_st];
                    }
                }
                //Tính tổng hệ số, tổng tiền lương
                $dutoan['tonghs'] = $canbo->sum('tonghs') * 12;
                $dutoan['ttl'] = $canbo->sum('ttl') * 12;
                //Tính tổng hệ số bảo hiểm quy đổi
                $dutoan['bhxh_dv'] = $canbo->sum('bhxh_dv') * 12;
                $dutoan['bhyt_dv'] = $canbo->sum('bhyt_dv') * 12;
                $dutoan['bhtn_dv'] = $canbo->sum('bhtn_dv') * 12;
                $dutoan['kpcd_dv'] = $canbo->sum('kpcd_dv') * 12;
                $dutoan['tongbh_dv'] = $canbo->sum('tongbh_dv') * 12;
                //quy đổi bảo hiểm thành số tiền theo mức lương cơ bản
                $dutoan['stbhxh_dv'] = $canbo->sum('stbhxh_dv') * 12;
                $dutoan['stbhyt_dv'] = $canbo->sum('stbhyt_dv') * 12;
                $dutoan['stbhtn_dv'] = $canbo->sum('stbhtn_dv') * 12;
                $dutoan['stkpcd_dv'] = $canbo->sum('stkpcd_dv') * 12;
                $dutoan['ttbh_dv'] =  $canbo->sum('ttbh_dv') * 12;
                //lưu tổng số vào bảng "dutoanluong"
                $inputs['luongbh_dt'] += $dutoan['ttbh_dv'];
                //Lưu dự toán
                $a_dutoan[] = $dutoan;
            }
            //dd($a_dutoan);
            $a_data = $m_bl_ct->keyBy('macanbo')->toarray();
            $a_th = $a_pc;
            foreach ($a_pc as $pc) {
                $a_th[] = 'st_' . $pc;
            }
            $a_th = array_merge(array(
                'stt', 'macanbo', 'tencanbo', 'mact', 'macvcq', 'mapb',   'tonghs', 'ttl', 'luongcoban', 'masodv',
                'stbhxh_dv', 'stbhyt_dv', 'stbhtn_dv', 'stkpcd_dv', 'ttbh_dv',
                'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'tongbh_dv'
            ), $a_th);

            foreach ($a_data as $key => $val) {
                foreach (array_keys($a_data[$key]) as $k) {
                    if (!in_array($k, $a_th))
                        unset($a_data[$key][$k]);
                }
            }

            //dd($m_data);
            $inputs['masodv'] = $masodv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['madv'] = session('admin')->madv;
            $inputs['namns'] = $inputs['namns'];
            $inputs['phanloai'] = 'DUTOAN';

            $inputs['sothonxabiengioi'] = chkDbl($inputs['sothonxabiengioi']);
            $inputs['sothonxakhokhan'] = chkDbl($inputs['sothonxakhokhan']);
            $inputs['sothonxatrongdiem'] = chkDbl($inputs['sothonxatrongdiem']);
            $inputs['sothonxakhac'] = chkDbl($inputs['sothonxakhac']);
            $inputs['sothonxaloai1'] = chkDbl($inputs['sothonxaloai1']);

            $inputs['sothonxabiengioi_heso'] = chkDbl($inputs['sothonxabiengioi_heso']);
            $inputs['sothonxakhokhan_heso'] = chkDbl($inputs['sothonxakhokhan_heso']);
            $inputs['sothonxatrongdiem_heso'] = chkDbl($inputs['sothonxatrongdiem_heso']);
            $inputs['sothonxakhac_heso'] = chkDbl($inputs['sothonxakhac_heso']);
            $inputs['sothonxaloai1_heso'] = chkDbl($inputs['sothonxaloai1_heso']);
            $inputs['phanloaixa_heso'] = chkDbl($inputs['phanloaixa_heso']);
            // dd($a_data);
            foreach (array_chunk($a_data, 50) as $data) {
                dutoanluong_bangluong::insert($data);
            }
            dutoanluong_chitiet::insert($a_dutoan);
            dutoanluong::create($inputs);
            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }


    function kinhphiKoCT(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong::where('masodv', $inputs['masodv'])->first();
            $inputs['sothonxabiengioi'] = chkDbl($inputs['sothonxabiengioi']);
            $inputs['sothonxakhokhan'] = chkDbl($inputs['sothonxakhokhan']);
            $inputs['sothonxatrongdiem'] = chkDbl($inputs['sothonxatrongdiem']);
            $inputs['sothonxakhac'] = chkDbl($inputs['sothonxakhac']);
            $inputs['sothonxaloai1'] = chkDbl($inputs['sothonxaloai1']);

            $inputs['sothonxabiengioi_heso'] = chkDbl($inputs['sothonxabiengioi_heso']);
            $inputs['sothonxakhokhan_heso'] = chkDbl($inputs['sothonxakhokhan_heso']);
            $inputs['sothonxatrongdiem_heso'] = chkDbl($inputs['sothonxatrongdiem_heso']);
            $inputs['sothonxakhac_heso'] = chkDbl($inputs['sothonxakhac_heso']);
            $inputs['sothonxaloai1_heso'] = chkDbl($inputs['sothonxaloai1_heso']);
            $inputs['phanloaixa_heso'] = chkDbl($inputs['phanloaixa_heso']);
            $model->update($inputs);
            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function get_detail(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dutoanluong_chitiet::where('masodv', $inputs['masodv'])->where('mact', $inputs['mact'])->first();
        die($model);
    }

    //kiểm tra xem còn dùng ko
    function store(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $inputs['madv'] = session('admin')->madv;
        dutoanluong::create($inputs);
        /*
        $model = new chitieubienche();

        $model->macanbo = $inputs['macanbo'];
        $model->ngaytu = getDateTime($inputs['ngaytu']);
        $model->ngayden = getDateTime($inputs['ngayden']);
        $model->quanham = $inputs['quanham'];
        $model->chucvu = $inputs['chucvu'];

        $model->save();
        */
        $result['message'] = "Thêm mới thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function update(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dutoanluong::find($inputs['id']);
        $inputs['luongnb_dt'] = chkDbl($inputs['luongnb_dt']);
        $inputs['luonghs_dt'] = chkDbl($inputs['luonghs_dt']);
        $inputs['luongbh_dt'] = chkDbl($inputs['luongbh_dt']);
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dutoanluong::find($id);
            dutoanluong_chitiet::where('masodv', $model->masodv)->delete();
            dutoanluong_bangluong::where('masodv', $model->masodv)->delete();
            dutoanluong_nangluong::where('masodv', $model->masodv)->delete();

            dutoanluong_khoi::where('masodv', $model->masok)->delete();
            dutoanluong_huyen::where('masodv', $model->masoh)->delete();
            $model->delete();
            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function checkNamDT(Request $request)
    {
        $inputs = $request->all();
        $model = dutoanluong::where('namns', $inputs['namdt'])->where('madv', session('admin')->madv)->first();
        if (isset($model)) {
            die(json_encode(array(
                'message' => 'Năm chỉ tiêu đã tồn tại. Bạn cần nhập lại năm khác.',
                'status' => 'false'
            )));
        } else {
            die(json_encode(array(
                'message' => 'Năm chỉ tiêu thỏa mãn điều kiện.',
                'status' => 'true'
            )));
        }
    }

    //kiểm tra xem còn dùng ko
    function checkBangLuong(Request $request)
    {
        $inputs = $request->all();
        $model = tonghopluong_donvi::where('thang', $inputs['thang'])
            ->where('nam', $inputs['nam'])
            ->where('madv', session('admin')->madv)
            ->first();

        if (!isset($model)) {
            die(json_encode(array(
                'message' => 'Dữ liệu tổng hợp lương không tồn tại. Bạn cần nhập bảng lương khác.',
                'status' => 'false'
            )));
        } else {
            die(json_encode(array(
                'message' => 'Năm chỉ tiêu thỏa mãn điều kiện.',
                'status' => 'true'
            )));
        }
    }

    // 2 mảng chứa thông tin 01 để lưu trữ; o1 để tính toán
    // trong qa trình tính toán tìm xem trong tháng trc có thay đổi số tiền ko => xem có nâng lương ko
    // lưu trong nâng lương
    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            if (dutoanluong::where('namns', $inputs['namdt'])->where('madv', session('admin')->madv)->count() > 0) {
                return view('errors.data_error')
                    ->with('message', 'Dự toán năm ' . $inputs['namdt'] . ' đã tồn tại.')
                    ->with('furl', '/nghiep_vu/quan_ly/du_toan/danh_sach');
            }
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');

            $gen = getGeneralConfigs();
            $masodv = session('admin')->madv . '_' . getdate()[0];
            //dd($inputs);
            $a_th = array_merge(array('macanbo', 'mact', 'macvcq', 'mapb', 'ngayden'), getColTongHop());
            $a_plct = getPLCTTongHop();

            //không lên đổi ra mảng do 1 cán bộ kiêm nhiêm nhiều chức vụ
            $m_cb_kn = hosocanbo_kiemnhiem::select($a_th)
                ->where('madv', session('admin')->madv)
                ->wherein('mact', $a_plct)
                ->get();
            //                ->get()->keyBy('macanbo')->toarray();

            $a_th = array_merge(array(
                'ngaysinh', 'tencanbo', 'stt', 'gioitinh', 'msngbac', 'bac', 'ngayvao', 'ngaybc',
                'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'ngaytu', 'ngayden', 'tnntungay', 'tnndenngay', 'theodoi'
            ), $a_th);
            $model = hosocanbo::select($a_th)->where('madv', session('admin')->madv)
                ->where('theodoi', '<', '9')
                ->get();
            //$a_hoten = array_column($model->toarray(), 'tencanbo', 'macanbo');
            //thời điểm xét duyệt
            $thoidiem = Carbon::create($inputs['namdt'], $inputs['thang'] + 1, '01')->addDay(-1)->toDateString();
            //dd($model);
            $model = (new dataController())->getCanBo($model, $inputs['namdt'] . '-' . $inputs['thang'] . '-01', true, $thoidiem);
            foreach ($model as $cb) {
                //xét thời hạn hợp đồng của cán bộ: nếu "ngayvao" > ngaytu => gán mact = null để lọc bỏ qua cán bộ
                if (getDayVn($cb->ngayvao) != '' && $cb->ngayvao < $inputs['namdt'] . '-' . $inputs['thang'] . '-01') {
                    $cb->mact = null;
                    continue;
                }
                $cb->congtac = 'CONGTAC';
                $cb->macongtac = $a_congtac[$cb->mact];
                $cb->masodv = $masodv;
                //trong bảng danh mục là % vượt khung => sang bảng lương chuyển thành hệ số
                //$cb->vuotkhung = $cb->heso * $cb->vuotkhung / 100;
                $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;

                if (isset($cb->ngaysinh)) {
                    $dt_ns = date_create($cb->ngaysinh);
                    if ($cb->gioitinh == 'Nam') {
                        $cb->nam_ns = strval(date_format($dt_ns, 'Y') + $gen['tuoinam']);
                        $cb->thang_ns = convert2str(date_format($dt_ns, 'm') + $gen['thangnam'] + 1);
                    } else {
                        $cb->nam_ns = strval(date_format($dt_ns, 'Y') + $gen['tuoinu']);
                        $cb->thang_ns = convert2str(date_format($dt_ns, 'm') + $gen['thangnu'] + 1);
                    }
                    if ($cb->thang_ns > 12) {
                        $cb->thang_ns = '01';
                        $cb->nam_ns = strval($cb->nam_ns + 1);
                    }
                } else {
                    $cb->nam_ns = null;
                    $cb->thang_ns = null;
                }

                if (isset($cb->ngayden)) {
                    $dt_luong = date_create($cb->ngayden);
                    $cb->nam_nb = date_format($dt_luong, 'Y');
                    $cb->thang_nb = date_format($dt_luong, 'm');
                } else {
                    $cb->nam_nb = null;
                    $cb->thang_nb = null;
                }

                if (isset($cb->tnndenngay)) {
                    $dt_nghe = date_create($cb->tnndenngay);
                    $cb->nam_tnn = date_format($dt_nghe, 'Y');
                    $cb->thang_tnn = date_format($dt_nghe, 'm');
                } else {
                    $cb->nam_tnn = null;
                    $cb->thang_tnn = null;
                }

                if (getDayVn($cb->ngayvao) != '') {
                    $dt_hh = date_create($cb->ngayvao);
                    $cb->nam_hh = date_format($dt_hh, 'Y');
                    $cb->thang_hh = date_format($dt_hh, 'm');
                } else {
                    $cb->nam_hh = null;
                    $cb->thang_hh = null;
                }
            }
            //lọc danh sach cán bộ
            //$model = $model->wherein('macongtac', ['BIENCHE', 'KHONGCT']);
            $model = $model->wherein('mact', $a_plct);

            $m_cb = $model->keyBy('macanbo')->toarray();
            //dd($m_cb);
            $m_hh = $model->where('ngayvao', '>=', $inputs['namdt'] . '-' . $inputs['thang'] . '-01')->where('ngayvao', '<=', $inputs['namdt'] . '-12-31')->keyBy('macanbo')->toarray();
            $m_nh = $model->where('nam_ns', '<>', '')->where('nam_ns', '<=', $inputs['namdt'])->keyBy('macanbo')->toarray();
            $m_nb = $model->where('nam_nb', '<>', '')->where('nam_nb', '=', $inputs['namdt'])->keyBy('macanbo')->toarray();
            $m_tnn = $model->where('nam_tnn', '<>', '')->where('nam_tnn', '=', $inputs['namdt'])->keyBy('macanbo')->toarray();
            //dd($m_nh);
            $iKn = 1;
            foreach ($m_cb_kn as $kn) {
                //lọc kiêm nhiệm của cán bộ đã thôi công tác
                if (!isset($m_cb[$kn->macanbo])) {
                    continue;
                }
                $kn->congtac = 'CONGTAC';
                $canbo = $m_cb[$kn->macanbo];
                $kn->tencanbo = $canbo['tencanbo'];
                $kn->stt = $canbo['stt'];
                $kn->msngbac = $canbo['msngbac'];
                $kn->theodoi = $canbo['theodoi'];

                $kn->ngayvao = null;
                $kn->ngaybc = null;
                $kn->ngaysinh = null;
                $kn->tnndenngay = null;
                $kn->macongtac = null;
                $kn->gioitinh = null;
                $kn->nam_ns = null;
                $kn->thang_ns = null;
                $kn->nam_nb = null;
                $kn->thang_nb = null;
                $kn->nam_tnn = null;
                $kn->thang_tnn = null;
                $kn->msngbac = null;
                $kn->bac = null;
                $kn->bhxh_dv = 0;
                $kn->bhyt_dv = 0;
                $kn->bhtn_dv = 0;
                $kn->kpcd_dv = 0;
                $kn->masodv = $masodv;
                $m_cb[$kn->macanbo . '_kn' . $iKn] = $kn->toarray();
                $iKn++;
            }

            $m_pc = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'dieudong')
                ->where('madv', session('admin')->madv)->wherein('mapc', getColTongHop())->get();

            $a_pc = $m_pc->toarray();
            $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();

            $a_thang = array();
            for ($i = $inputs['thang']; $i < 13; $i++) {
                $a_thang[] = array('thang' => str_pad($i, 2, '0', STR_PAD_LEFT), 'nam' => $inputs['namdt']);
            }

            //chạy tính hệ số lương, phụ cấp trc. Sau này mỗi tháng chỉ chạy cán bộ thay đổi
            foreach ($m_cb as $key => $val) {
                $m_cb[$key] = $this->getHeSoPc($a_pc, $m_cb[$key], $inputs['luongcoban']);
                //                if($key=='1511583374_1586190329'){
                //                    dd($m_cb[$key]);
                //                }
            }
            foreach ($m_nh as $key => $val) {
                $m_nh[$key] = $this->getHeSoPc_nh($a_pc, $m_nh[$key]);
            }
            //dd($thoidiem);
            foreach ($m_nb as $key => $val) {
                //kiểm tra xem tháng đó có nâng lương có nghỉ ts ko nếu có tháng nâng lương thành tháng ngay sau ngày nghỉ
                if (isset($a_nhomnb[$val['msngbac']])) {
                    $nhomnb = $a_nhomnb[$val['msngbac']];
                    //$hesomax = $nhomnb['heso'] +  ($nhomnb['heso'] * $nhomnb['hesochenhlech']);
                    $hesomax = $nhomnb['hesolonnhat'];
                    if ($val['heso'] >= $hesomax) {
                        $m_nb[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'] == 0 ? $nhomnb['vuotkhung'] : $m_nb[$key]['vuotkhung'] + 1;
                    } else {
                        $m_nb[$key]['heso'] += $nhomnb['hesochenhlech'];
                    }
                }
                //kiểm tra xem cán bộ đc nâng lương trc thời điêm xét ko
                // nếu có => xet thời điểm nâng lương là thời điểm xét.
                //                if($val['ngayden'] <= $thoidiem){
                //                    $m_nb[$key]['thang_nb'] = $inputs['thang'];
                //                }

                if (isset($m_tnn[$key]) && $m_tnn[$key]['thang_tnn'] < $m_nb[$key]['thang_nb']) {
                    $m_nb[$key]['pctnn'] = $m_nb[$key]['pctnn'] == 0 ? 5 : $m_nb[$key]['pctnn'] + 1;
                }
                $m_nb[$key] = $this->getHeSoPc($a_pc, $m_nb[$key], $inputs['luongcoban']);
            }
            //dd($m_nb);
            foreach ($m_tnn as $key => $val) {
                $m_tnn[$key]['pctnn'] = $m_tnn[$key]['pctnn'] == 0 ? 5 : $m_tnn[$key]['pctnn'] + 1;
                //nếu tăng tnn bằng hoặc sau nb => set lai heso, vuotkhung
                if (isset($m_nb[$key]) && $m_tnn[$key]['thang_tnn'] >= $m_nb[$key]['thang_nb']) {
                    $m_tnn[$key]['heso'] = $m_nb[$key]['heso'];
                    $m_tnn[$key]['vuotkhung'] = $m_nb[$key]['vuotkhung'];
                    $m_tnn[$key] = $this->getHeSoPc($a_pc, $m_tnn[$key], $inputs['luongcoban'], false);
                } else {
                    $m_tnn[$key] = $this->getHeSoPc($a_pc, $m_tnn[$key], $inputs['luongcoban']);
                }
                //kiểm tra xem cán bộ đc nâng lương trc thời điêm xét ko
                // nếu có => xet thời điểm nâng lương là thời điểm xét.
                //                if($val['tnndenngay'] <= $thoidiem){
                //                    $m_tnn[$key]['thang_tnn'] = $inputs['thang'];
                //                }
            }


            //dd($m_tnn);
            //bắt đầu tính lương
            //cán bộ đã nghỉ hưu thì các thông tin # bỏ qua
            //nghỉ hưu vào tháng 08, trong thông tin cán bộ  tháng 12 tăng lương => bỏ qua thông tin tăng lương

            //lưu lại mảng thông tin cũ do đã tách riêng: nâng lương ngạch bậc và nâng lương thâm niên
            //nâng tnn: 02; nâng nb: 06 => mảng chênh lệch 'NGACHBAC' lấy thông tin cũ để tính
            $a_luu = $m_cb;

            $a_data = array();
            $a_data_nl = array();
            $a_danghihuu = array();
            $m_cb_goc = $m_cb; //lưu bản gốc để tính riêng nâng thâm niên nghề


            //dự toán cho cán bộ chưa tuyển
            $m_chitieu = chitieubienche::select(array_merge(array('soluongtuyenthem', 'mact', 'mact_tuyenthem'), getColTongHop()))
                ->where('madv', session('admin')->madv)
                ->where('nam', $inputs['namdt'])->get();

            $a_baohiem = dmphanloaicongtac_baohiem::wherein('mact', array_column($m_chitieu->toarray(), 'mact'))
                ->where('madv', session('admin')->madv)->get()->keyBy('mact')->toarray();
            $a_chitieu = $m_chitieu->keyBy('mact')->toarray();

            foreach ($a_chitieu as $key => $val) {
                if (chkDbl($val['soluongtuyenthem']) <= 0) {
                    continue;
                }
                $baohiem = $a_baohiem[$val['mact']];
                if (isset($inputs['baohiem'])) {
                    $a_chitieu[$key]['bhxh_dv'] = floatval($baohiem['bhxh_dv']) / 100;
                    $a_chitieu[$key]['bhyt_dv'] = floatval($baohiem['bhyt_dv']) / 100;
                    $a_chitieu[$key]['kpcd_dv'] = floatval($baohiem['kpcd_dv']) / 100;
                    $a_chitieu[$key]['bhtn_dv'] = floatval($baohiem['bhtn_dv']) / 100;
                } else {
                    $a_chitieu[$key]['bhxh_dv'] = 0;
                    $a_chitieu[$key]['bhyt_dv'] = 0;
                    $a_chitieu[$key]['kpcd_dv'] = 0;
                    $a_chitieu[$key]['bhtn_dv'] = 0;
                }
                $a_chitieu[$key]['stt'] = 999;
                $a_chitieu[$key]['msngbac'] = '';
                $a_chitieu[$key]['macanbo'] = $val['mact'] . '_' . $val['soluongtuyenthem'];
                $a_chitieu[$key]['macvcq'] = '';
                $a_chitieu[$key]['mapb'] = '';
                $a_chitieu[$key]['theodoi'] = '1';
                $a_chitieu[$key]['masodv'] = $masodv;
                $a_chitieu[$key]['tencanbo'] = 'Cán bộ chưa tuyển';
                $a_chitieu[$key]['congtac'] = 'CONGTAC';
                $a_chitieu[$key]['macongtac'] = $a_congtac[$val['mact']];
                $a_chitieu[$key]['congtac'] = 'CONGTAC';
                $a_chitieu[$key]['ngaybc'] = null;
                $a_chitieu[$key]['mact'] = $a_chitieu[$key]['mact_tuyenthem'];
                foreach ($a_pc as $pc) {
                    $a_chitieu[$key][$pc['mapc']] = $val[$pc['mapc']] * chkDbl($val['soluongtuyenthem']);
                }
                $a_chitieu[$key] = $this->getHeSoPc($a_pc, $a_chitieu[$key], $inputs['luongcoban']);
                //                $a_col = ['id', 'madv','linhvuchoatdong','soluongduocgiao','soluongbienche','soluongkhongchuyentrach',
                //                    'soluonguyvien','soluongdaibieuhdnd','soluongtuyenthem','ghichu','mact_tuyenthem'];
                //                $a_chitieu[$key] = unset_key($a_chitieu[$key], $a_col);
                $m_cb[$key] = $a_chitieu[$key];
            }

            for ($i = 0; $i < count($a_thang); $i++) {
                $a_nh = a_getelement($m_nh, array('thang_ns' => $a_thang[$i]['thang']));
                if (count($a_nh) > 0) { //
                    foreach ($a_nh as $key => $val) {
                        if (!isset($inputs['nghihuu'])) {
                            $m_cb[$key] = $a_nh[$key];
                        } else {
                            $m_cb[$key]['tencanbo'] .= ' (nghỉ hưu)';
                        }
                        $a_danghihuu[] = $key;
                    }
                }


                $a_nb = a_getelement($m_nb, array('thang_nb' => $a_thang[$i]['thang']));
                if (count($a_nb) > 0) {
                    foreach ($a_nb as $key => $val) {
                        if (!in_array($key, $a_danghihuu)) {
                            if ($a_thang[$i]['thang'] != '01') { //nâng lương vào tháng 01 => ko tính chênh lệch nâng lương
                                $a_data_nl[] = $this->getHeSoPc_Sub($a_pc, $a_nb[$key], $a_luu[$key], 'NGACHBAC', $a_thang[$i]['thang'], $a_thang[$i]['nam']);
                            }
                            $m_cb[$key] = $a_nb[$key];
                        }
                    }
                }
                $a_tnn = a_getelement($m_tnn, array('thang_tnn' => $a_thang[$i]['thang']));
                if (count($a_tnn) > 0) {
                    foreach ($a_tnn as $key => $val) {
                        if (!in_array($key, $a_danghihuu)) {
                            $m_cb[$key] = $a_tnn[$key];
                        }
                    }
                }
                //lưu vào 1 mảng
                foreach ($m_cb as $key => $val) {
                    //kiem tra can bo het han lao dong => bo wa
                    if (isset($m_hh[$key]) && $m_hh[$key]['thang_hh'] < $a_thang[$i]['thang']) {
                        continue;
                    }
                    //nếu cán bộ chưa đến hạn công tác =>bỏ qua
                    if ($m_cb[$key]['ngaybc'] > $a_thang[$i]['nam'] . '-' . $a_thang[$i]['nam'] . '-01') {
                        continue;
                    }

                    $m_cb[$key]['thang'] = $a_thang[$i]['thang'];
                    $m_cb[$key]['nam'] = $a_thang[$i]['nam'];
                    $a_data[] = $m_cb[$key];
                }
                //tính toán xong lưu dữ liệu
            }

            //tính nâng lương thâm niên
            //dd($m_tnn);
            foreach ($m_tnn as $key => $val) {
                if ($val['thang_tnn'] == '01') {
                    continue;
                }
                $a_tnn = array();
                $a_goc = $m_cb_goc[$key];
                $a_tt = $val;

                //trường hợp nâng lương ngạch bậc trc tnn thì duyệt từ tháng nb đến trc tnn để công vào
                if (isset($m_nb[$key]) && $m_nb[$key]['thang_nb'] < $val['thang_tnn']) {
                    for ($i = $m_nb[$key]['thang_nb']; $i < $val['thang_tnn']; $i++) {
                        $a_tnn[] = $this->getSubNangLuong($a_pc, $m_nb[$key], $a_goc);
                    }
                }

                for ($i = $val['thang_tnn']; $i <= 12; $i++) {
                    //chưa kiểm tra xem tháng này cán bộ nghỉ hưu ko
                    if (
                        isset($m_nb[$key]) && $m_nb[$key]['thang_nb'] > $val['thang_tnn']
                        && $m_nb[$key]['thang_nb'] == str_pad($i, 2, '0', STR_PAD_LEFT)
                    ) {
                        //tính lại chỉ lấy nâng thâm niên mà trừ, tính lại bảo hiểm
                        $a_tt = $m_nb[$key];
                    }
                    $a_tnn[] = $this->getSubNangLuong($a_pc, $a_tt, $a_goc);
                }

                $a_goc['maphanloai'] = 'THAMNIENNGHE';
                $a_goc['thang'] = $val['thang_tnn'];
                $a_goc['nam'] = $inputs['namdt'];

                //ko dùng do có trường hợp đơn vị ko theo doi pctnn
                //$a_goc['pctnn'] = array_sum(array_column($a_tnn, 'pctnn'));
                //$a_goc['st_pctnn'] = array_sum(array_column($a_tnn, 'st_pctnn'));

                for ($i = 0; $i < count($a_pc); $i++) {
                    $mapc = $a_pc[$i]['mapc'];
                    $mapc_st = 'st_' . $mapc;
                    if ($mapc == 'pctnn') {
                        $a_goc[$mapc] = array_sum(array_column($a_tnn, $mapc));
                        $a_goc[$mapc_st] = array_sum(array_column($a_tnn, $mapc_st));
                    } else {
                        $a_goc[$mapc] = $a_goc[$mapc_st] = 0;
                    }
                }
                $a_goc['tonghs'] = array_sum(array_column($a_tnn, 'pctnn'));
                $a_goc['luongtn'] = array_sum(array_column($a_tnn, 'st_pctnn'));
                $a_goc['stbhxh_dv'] = round($a_goc['bhxh_dv'] * $a_goc['st_pctnn'], 0);
                $a_goc['stbhyt_dv'] = round($a_goc['bhyt_dv'] * $a_goc['st_pctnn'], 0);
                $a_goc['stkpcd_dv'] = round($a_goc['kpcd_dv'] * $a_goc['st_pctnn'], 0);
                $a_goc['stbhtn_dv'] = round($a_goc['bhtn_dv'] * $a_goc['st_pctnn'], 0);
                $a_goc['ttbh_dv'] = $a_goc['stbhxh_dv'] + $a_goc['stbhyt_dv'] + $a_goc['stkpcd_dv'] + $a_goc['stbhtn_dv'];

                //dd($a_goc);
                $a_data_nl[] = $a_goc;
            }

            //Lưu dữ liệu
            $a_col = array(
                'bac', 'bhxh_dv', 'bhtn_dv', 'kpcd_dv', 'bhyt_dv', 'gioitinh', 'nam_nb', 'nam_ns',
                'thang_nb', 'thang_ns', 'thang_tnn', 'ngayden', 'ngaysinh', 'tnndenngay',
                'ngaytu', 'tnntungay', 'nam_tnn', 'nam_hh', 'thang_hh', 'ngaybc', 'ngayvao',
                'soluongtuyenthem', 'mact_tuyenthem',
            );
            $a_data_nl = unset_key($a_data_nl, $a_col);
            $a_data = unset_key($a_data, $a_col);
            //dd($a_data);
            foreach (array_chunk($a_data_nl, 100) as $data) {
                dutoanluong_nangluong::insert($data);
            }

            foreach (array_chunk($a_data, 50) as $data) {
                dutoanluong_bangluong::insert($data);
            }

            //tổng hợp chi tiết dự toán
            $m_data = a_split($a_data, array('mact'));
            $m_data = a_unique($m_data);
            $luongnb = 0;
            $luonghs = 0;
            $luongbh = 0;
            for ($i = 0; $i < count($m_data); $i++) {
                $canbo = a_getelement($m_cb, array('mact' => $m_data[$i]['mact']));
                $dutoan = a_getelement($a_data, array('mact' => $m_data[$i]['mact']));
                $m_data[$i]['masodv'] = $masodv;
                $soluong = count($canbo);
                $m_data[$i]['canbo_congtac'] = $soluong;
                $m_data[$i]['canbo_dutoan'] = 0;
                $m_data[$i]['luongbh'] = $m_data[$i]['luongnb'] = 0;
                $m_data[$i]['luongnb_dt'] = (array_sum(array_column($dutoan, 'heso')) + array_sum(array_column($dutoan, 'vuotkhung'))) * $inputs['luongcoban'];
                $luongnb += ($m_data[$i]['luongnb_dt'] + $m_data[$i]['luongnb']);
                //dùng luongtn vì các phụ cấp tính theo số tiền đã cộng vào luongtn (ko tính vào hệ số)
                $m_data[$i]['luonghs_dt'] = chkDbl(array_sum(array_column($dutoan, 'luongtn'))) - chkDbl($m_data[$i]['luongnb_dt']);
                $luonghs += $m_data[$i]['luonghs_dt'];
                $m_data[$i]['luongbh_dt'] = array_sum(array_column($dutoan, 'ttbh_dv'));
                $luongbh += $m_data[$i]['luongbh_dt'] + $m_data[$i]['luongbh'];
            }
            dutoanluong_chitiet::insert($m_data);
            //dd($m_data);
            $inputs['masodv'] = $masodv;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['luongnb_dt'] = $luongnb;
            $inputs['luonghs_dt'] = $luonghs;
            $inputs['luongbh_dt'] = $luongbh;
            $inputs['madv'] = session('admin')->madv;
            $inputs['namns'] = $inputs['namdt'];

            dutoanluong::create($inputs);
            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    /**
     * @param $a_pc
     * @param $m_cb
     * @param $luongcb: mức lương cơ bản
     * @param $vk: có tính vượt khung ko
     * @return array
     */
    //chưa lưu số tiền
    public function getHeSoPc($a_pc, $m_cb, $luongcb = 0, $vk = true)
    {
        $stbhxh_dv = 0;
        $stbhyt_dv = 0;
        $stkpcd_dv = 0;
        $stbhtn_dv = 0;
        $m_cb['tonghs'] = 0;
        $m_cb['luongtn'] = 0;
        $m_cb['luongcoban'] = $luongcb;
        if ($vk) {
            $m_cb['vuotkhung'] = round(($m_cb['heso'] * $m_cb['vuotkhung']) / 100, session('admin')->lamtron);
        }
        //dd($m_cb);
        for ($i = 0; $i < count($a_pc); $i++) {
            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            switch (getDbl($a_pc[$i]['phanloai'])) {
                case 0: {
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                case 1: { //số tiền
                        $m_cb['luongtn'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = $m_cb[$mapc];
                        break;
                    }
                case 2: { //phần trăm
                        if ($mapc != 'vuotkhung') { //vượt khung đã tính ở trên
                            $heso = 0;
                            foreach (explode(',', $a_pc[$i]['congthuc']) as $cthuc) {
                                if ($cthuc != '') {
                                    $heso += $m_cb[$cthuc];
                                }
                            }
                            $m_cb[$mapc] = round($heso * $m_cb[$mapc] / 100, session('admin')->lamtron);
                        }
                        $m_cb['tonghs'] += $m_cb[$mapc];
                        $m_cb[$mapc_st] = round($m_cb[$mapc] * $luongcb);
                        break;
                    }
                default: { //trường hợp còn lại (ẩn,...)
                        $m_cb[$mapc] = 0;
                        $m_cb[$mapc_st] = 0;
                        break;
                    }
            }

            if ($a_pc[$i]['baohiem'] == 1) {
                $stbhxh_dv += round($m_cb['bhxh_dv'] * $m_cb[$mapc_st], 0);
                $stbhyt_dv += round($m_cb['bhyt_dv'] * $m_cb[$mapc_st], 0);
                $stkpcd_dv += round($m_cb['kpcd_dv'] * $m_cb[$mapc_st], 0);
                $stbhtn_dv += round($m_cb['bhtn_dv'] * $m_cb[$mapc_st], 0);
            }
        }
        if ($m_cb['theodoi'] == '4') {
            $m_cb['luongtn'] = 0;
            $m_cb['tonghs'] = 0;
            for ($i = 0; $i < count($a_pc); $i++) {
                $mapc = $a_pc[$i]['mapc'];
                $mapc_st = 'st_' . $mapc;
                if ($a_pc[$i]['dieudong'] != '1') {
                    $m_cb[$mapc] = 0;
                    $m_cb[$mapc_st] = 0;
                }
                switch (getDbl($a_pc[$i]['phanloai'])) {
                    case 1: { //số tiền
                            $m_cb['luongtn'] += $m_cb[$mapc_st];
                            break;
                        }

                    default: { //trường hợp còn lại (ẩn,...)
                            $m_cb['tonghs'] += $m_cb[$mapc];
                            $m_cb['luongtn'] += $m_cb[$mapc_st];
                            break;
                        }
                }
            }
            $m_cb['stbhxh_dv'] = 0;
            $m_cb['stbhyt_dv'] = 0;
            $m_cb['stkpcd_dv'] = 0;
            $m_cb['stbhtn_dv'] = 0;
            $m_cb['ttbh_dv'] = 0;
        } else {
            $m_cb['stbhxh_dv'] = $stbhxh_dv;
            $m_cb['stbhyt_dv'] = $stbhyt_dv;
            $m_cb['stkpcd_dv'] = $stkpcd_dv;
            $m_cb['stbhtn_dv'] = $stbhtn_dv;
            $m_cb['luongtn'] += round($m_cb['tonghs'] * $luongcb, 0);
            $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
        }
        unset($m_cb['theodoi']);
        return $m_cb;
    }

    /*
     * chỉ tính được nâng lương ngạch bậc, do trong tham số truyền vào đã tính bảo hiểm (có thể cả TNN)
     * nên pải tính lại bảo hiểm của cá nhân
     * */
    public function getHeSoPc_Sub($a_pc, $m_cb, $m_cb_cu, $phanloai, $thang, $nam)
    {
        //dd($a_pc);
        $m_cb['maphanloai'] = $phanloai;
        $m_cb['thang'] = $thang;
        $m_cb['nam'] = $nam;
        $thang = 12 - $thang + 1;

        $stbhxh_dv = 0;
        $stbhyt_dv = 0;
        $stkpcd_dv = 0;
        $stbhtn_dv = 0;
        $m_cb['tonghs'] = 0;
        $m_cb['luongtn'] = 0;

        for ($i = 0; $i < count($a_pc); $i++) {
            if ($a_pc[$i]['mapc'] == 'pctnn') {
                $m_cb['pctnn'] = 0;
                $m_cb['st_pctnn'] = 0;
                continue;
            }

            $mapc = $a_pc[$i]['mapc'];
            $mapc_st = 'st_' . $mapc;
            $m_cb[$mapc] = ($m_cb[$mapc] - $m_cb_cu[$mapc]) * $thang;
            $m_cb[$mapc_st] = ($m_cb[$mapc_st] - $m_cb_cu[$mapc_st]) * $thang;
            $m_cb['tonghs'] += $m_cb[$mapc];
            $m_cb['luongtn'] += $m_cb[$mapc_st];

            if ($a_pc[$i]['baohiem'] == 1) {
                $stbhxh_dv += round($m_cb['bhxh_dv'] * $m_cb[$mapc_st], 0);
                $stbhyt_dv += round($m_cb['bhyt_dv'] * $m_cb[$mapc_st], 0);
                $stkpcd_dv += round($m_cb['kpcd_dv'] * $m_cb[$mapc_st], 0);
                $stbhtn_dv += round($m_cb['bhtn_dv'] * $m_cb[$mapc_st], 0);
            }
        }

        $m_cb['stbhxh_dv'] = $stbhxh_dv;
        $m_cb['stbhyt_dv'] = $stbhyt_dv;
        $m_cb['stkpcd_dv'] = $stkpcd_dv;
        $m_cb['stbhtn_dv'] = $stbhtn_dv;
        $m_cb['ttbh_dv'] = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
        return $m_cb;
    }

    //chỉ tính nâng thâm niên nghề
    public function getSubNangLuong($a_pc, $m_cb, $m_cb_cu)
    {
        for ($i = 0; $i < count($a_pc); $i++) {
            if ($a_pc[$i]['mapc'] == 'pctnn') {
                $m_cb['pctnn'] = $m_cb['pctnn'] - $m_cb_cu['pctnn'];
                $m_cb['st_pctnn'] = $m_cb['st_pctnn'] - $m_cb_cu['st_pctnn'];
            } else {
                $mapc = $a_pc[$i]['mapc'];
                $mapc_st = 'st_' . $mapc;
                $m_cb[$mapc] = 0;
                $m_cb[$mapc_st] = 0;
            }
        }
        return $m_cb;
    }

    public function getHeSoPc_nh($a_pc, $m_cb, $luongcb = 0)
    {
        //$m_cb['tencanbo'] .= ' (nghỉ hưu)';
        for ($i_pc = 0; $i_pc < count($a_pc); $i_pc++) {
            $mapc = $a_pc[$i_pc]['mapc'];
            $mapc_st = 'st_' . $mapc;
            $m_cb['stbhxh_dv'] = 0;
            $m_cb['stbhyt_dv'] = 0;
            $m_cb['stkpcd_dv'] = 0;
            $m_cb['stbhtn_dv'] = 0;
            $m_cb['ttbh_dv'] = 0;
            $m_cb['tonghs'] = 0;
            $m_cb['luongtn'] = 0;
            $m_cb[$mapc] = 0;
            $m_cb[$mapc_st] = 0;
            $m_cb['luongcoban'] = $luongcb;
        }
        unset($m_cb['theodoi']);
        return $m_cb;
    }

    function show(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_dutoan = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $a_ct = array_column($model_tenct->toArray(), 'tenct', 'mact');

            foreach ($model as $ct) {
                $ct->tenct = isset($a_ct[$ct->mact]) ? $a_ct[$ct->mact] : '';
                $ct->tongcong = $ct->luongnb_dt + $ct->luonghs_dt + $ct->luongbh_dt + $ct->luongnb;
            }
            $inputs['maphanloai'] = dmdonvi::where('madv', session('admin')->madv)->first()->maphanloai;
            $inputs['luongcb'] = getGeneralConfigs()['luongcb'];
            $inputs['heso'] = $inputs['maphanloai'] == 'MAMNON' ? '2.1' : '2.34';
            return view('manage.dutoanluong.detail')
                ->with('furl', '/nghiep_vu/quan_ly/du_toan/')
                ->with('model', $model)
                ->with('model_dutoan', $model_dutoan)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Dự toán lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function update_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongnb'] = getDbl($inputs['luongnb']);
            $inputs['canbo_dutoan'] = getDbl($inputs['canbo_dutoan']);
            $inputs['canbo_congtac'] = getDbl($inputs['canbo_congtac']);
            $inputs['luongnb_dt'] = getDbl($inputs['luongnb_dt']);
            $inputs['luonghs_dt'] = getDbl($inputs['luonghs_dt']);
            $inputs['luongbh_dt'] = getDbl($inputs['luongbh_dt']);

            $model = dutoanluong_chitiet::find($inputs['id_ct']);
            $model->update($inputs);
            $this->duToan($model->masodv);
            return redirect('/nghiep_vu/quan_ly/du_toan?maso=' . $model->masodv);
        } else
            return view('errors.notlogin');
    }

    function duToan($masodv)
    {
        $m_chitiet = dutoanluong_chitiet::where('masodv', $masodv)->get();
        $inputs = array();
        $inputs['luongnb_dt'] = $m_chitiet->sum('luongnb_dt');
        $inputs['luonghs_dt'] = $m_chitiet->sum('luonghs_dt');
        $inputs['luongbh_dt'] = $m_chitiet->sum('luongbh_dt');
        dutoanluong::where('masodv', $masodv)->first()->update($inputs);
    }

    function destroy_detail($id)
    {
        if (Session::has('admin')) {
            $model = dutoanluong_chitiet::find($id);
            $model->delete();
            $this->duToan($model->masodv);
            return redirect('/nghiep_vu/quan_ly/du_toan?maso=' . $model->masodv);
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $model = dutoanluong::where('masodv', $inputs['masodv'])->first();
            //1.tự động thêm danh sách quản lý
            $chk_ql = dsdonviquanly::where('nam', $model->namns)->where('madv', $model->madv)->first();
            if ($chk_ql == null)
                dsdonviquanly::create([
                    'nam' => $model->namns,
                    'madv' => $model->madv,
                    'macqcq' => $model->macqcq,
                ]);
            //Xem lại tính năng (07/07/2022)
            // //check đơn vị chủ quản là gửi lên huyện => chuyển trạng thái; import bản ghi vào bảng huyện khối => chuyển trạng thái
            // if (session('admin')->macqcq == session('admin')->madvqlkv) { //đơn vị chủ quản là huyện
            //     //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
            //     $model_huyen = dutoanluong_huyen::where('masodv', $model->masoh)->first();
            //     if ($model_huyen == null) {
            //         $masoh = getdate()[0];
            //         $inputs['namns'] = $model->namns;
            //         $inputs['madv'] = $model->madv;
            //         $inputs['masodv'] = $masoh;
            //         $inputs['trangthai'] = 'DAGUI';
            //         $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu dự toán lương.';
            //         $inputs['nguoilap'] = session('admin')->name;
            //         $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
            //         $inputs['macqcq'] = session('admin')->macqcq;
            //         $inputs['madvbc'] = session('admin')->madvbc;

            //         $model->masoh = $masoh;
            //         dutoanluong_huyen::create($inputs);
            //     } else {
            //         $model_huyen->trangthai = 'DAGUI';
            //         $model_huyen->nguoilap = session('admin')->name;
            //         $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
            //         $model_huyen->save();
            //     }
            // }
            //KT-Xem lại tính năng (07/07/2022)


            //Kiểm tra xem nếu trên huyện đã có dự toán thì tự động gán mã vào dự toán đơn vị
            $chk_huyen = dutoanluong_huyen::where('madv', $model->macqcq)->where('namns', $model->namns)->first();
            if ($chk_huyen != null)
                $model->masoh = $chk_huyen->masodv;
            $model->nguoiguidv = session('admin')->name;
            $model->ngayguidv = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            $model->macqcq = $inputs['macqcq'];
            //dd($model);
            $model->save();

            //thêm đơn vị quản lý

            return redirect('/nghiep_vu/quan_ly/du_toan/danh_sach');
        } else
            return view('errors.notlogin');
    }

    //chưa làm trả lại
    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dutoanluong::where('mathdv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();
            //chưa có view
            return redirect('/chuc_nang/xem_du_lieu/index?thang=' . $model->thang . '&nam=' . $model->nam . '&trangthai=ALL');
        } else
            return view('errors.notlogin');
    }

    function getlydo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = dutoanluong::select('lydo')->where('masodv', $inputs['masodv'])->first();

        die($model);
    }

    function printf_data(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
            $model_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$gnr = getGeneralConfigs();
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                //                if($chitiet->mact == null){
                //                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                //                }else{
                //                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                //                }
                //                $chitiet->tongtl = $chitiet->tonghs - $chitiet->giaml;
                //                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;

            }
            //dd($model);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );
            //dd($model);
            return view('reports.dutoanluong.donvi.tonghop')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_data_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dutoanluong_chitiet::where('masodv', $inputs['maso'])->get();
            $model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
            $a_ct = getPhanLoaiCT(false);
            $model_bl = dutoanluong_bangluong::where('masodv', $inputs['maso'])->orderby('thang')->get();
            //dd($model_bl->first());

            if ($model->sum('canbo_dutoan') > 0) {
                $ct = clone $model->first();
                $ct->canbo_congtac = $model->sum('canbo_dutoan');
                $ct->canbo_dutoan = 0;
                $ct->mact = "1561606077";
                $ct->luongnb = 0;
                $ct->luonghs = 0;
                $ct->luongbh = 0;
                $ct->luongnb_dt = 0;
                $ct->luonghs_dt = 0;
                $ct->luongbh_dt = 0;
                $model->add($ct);
            }

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();
            //lương co ban trong bang lương chi tiết
            foreach ($model as $ct) {
                //$ct->tennguonkp = isset($model_nguonkp[$ct->manguonkp]) ? $model_nguonkp[$ct->manguonkp] : '';
                $ct->tencongtac = isset($a_ct[$ct->mact]) ? $a_ct[$ct->mact] : '';
                $bangluong = $model_bl->where('mact', $ct->mact);

                foreach ($m_pc as $pc) {
                    $mapc = $pc['mapc'];
                    $mapc_st = 'st_' . $pc['mapc'];
                    $ct->$mapc = $bangluong->sum($mapc);
                    $ct->$mapc_st = $bangluong->sum($mapc_st);
                    if ($ct->$mapc > 0) {
                        $a_phucap[$mapc] = $pc['report'];
                    }
                }

                /*
                if($chitiet->mact == null){
                    $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                }else{
                    $chitiet->tencongtac = isset($model_ct[$chitiet->mact]) ? $model_ct[$chitiet->mact] : '';
                }
                */
                //dd($bangluong->toarray());
                $ct->ttl = $bangluong->sum('luongtn');
                $ct->stbhxh_dv = $bangluong->sum('stbhxh_dv');
                $ct->stbhyt_dv = $bangluong->sum('stbhyt_dv');
                $ct->stkpcd_dv = $bangluong->sum('stkpcd_dv');
                $ct->stbhtn_dv = $bangluong->sum('stbhtn_dv');
                $ct->tongbh = $bangluong->sum('ttbh_dv');
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );
            //dd($model);
            return view('reports.dutoanluong.donvi.tonghop_m2')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('a_phucap', $a_phucap)
                ->with('col', count($a_phucap))
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            if ($inputs['mact'] != 'ALL') {
                $model_ct = dutoanluong_bangluong::where('masodv', $inputs['masodv'])
                    ->where('mact', $inputs['mact'])
                    ->orderby('stt')->get();
            } else {
                $model_ct = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('stt')->get();
            }

            //tính lại do cán bộ kiêm nhiệm nên các mã sẽ trùng nhau
            //==> lấy tháng nhỏ nhất
            $model = $model_ct->where('thang', $model_ct->min('thang'));

            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = dutoanluong::where('masodv', $inputs['masodv'])->first();
            $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');
            //dd($a_ct);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            foreach ($model as $ct) {
                $bl = $model_ct->where('macanbo', $ct->macanbo)->where('mact', $ct->mact);
                foreach ($m_pc as $pc) {
                    $ma = $pc['mapc'];
                    $ma_st = 'st_' . $pc['mapc'];
                    $ct->$ma = $bl->sum($ma);
                    $ct->$ma_st = $bl->sum($ma_st);
                }
                $ct->tonghs = $bl->sum('tonghs');
                $ct->luongtn = $bl->sum('luongtn');
                $ct->stbhxh_dv = $bl->sum('stbhxh_dv');
                $ct->stbhyt_dv = $bl->sum('stbhyt_dv');
                $ct->stkpcd_dv = $bl->sum('stkpcd_dv');
                $ct->stbhtn_dv = $bl->sum('stbhtn_dv');
                $ct->ttbh_dv = $bl->sum('ttbh_dv');

                $ct->tencanbo = str_replace('(nghỉ thai sản)', '', $ct->tencanbo);
                $ct->tencanbo = str_replace('(nghỉ hưu)', '', $ct->tencanbo);
                $ct->tencanbo = trim($ct->tencanbo);
            }

            //dd($model);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );

            return view('reports.dutoanluong.donvi.bangluong_m2')
                ->with('thongtin', $thongtin)
                ->with('model', $model->sortby('stt'))
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_bl($masodv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = dutoanluong_bangluong::where('masodv', $masodv)->orderby('thang')->orderby('stt')->get();
            $model_thongtin = dutoanluong::where('masodv', $masodv)->first();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_thang = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_thang = a_unique($model_thang);

            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang', 'mact'])
                    ->all();
            });
            $model_congtac = a_unique($model_congtac);

            return view('reports.dutoanluong.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_ct', $a_ct)
                ->with('model_thang', $model_thang)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong_bangluong::where('masodv', $inputs['masodv']);

            if ($inputs['thang'] != 'ALL') {
                $model = $model->where('thang', $inputs['thang']);
            }
            if ($inputs['mact'] != 'ALL') {
                $model = $model->where('mact', $inputs['mact']);
            }

            $model = $model->orderby('thang')->orderby('stt')->get();
            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = dutoanluong::where('masodv', $inputs['masodv'])->first();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_thang = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_thang = a_unique($model_thang);

            $model_congtac = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang', 'mact'])
                    ->all();
            });
            $model_congtac = a_unique($model_congtac);


            return view('reports.dutoanluong.donvi.bangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_ct', $a_ct)
                ->with('model_thang', $model_thang)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_nangluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dutoanluong_nangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();
            //dd($model);
            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = dutoanluong::where('masodv', $inputs['maso'])->first();
            $a_pl = getPhanLoaiNangLuong();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();
            //dd($m_pc);
            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam
            );

            //Lấy dữ liệu để lập
            $model_thang = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['thang'])
                    ->all();
            });
            //group mact đã bao gồm macongtac; manguonkp bao gồm luongcoban
            $model_thang = a_unique($model_thang);

            $model_phanloai = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['maphanloai'])
                    ->all();
            });
            $model_phanloai = a_unique($model_phanloai);

            return view('reports.dutoanluong.donvi.nangluong')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_pl', $a_pl)
                ->with('model_thang', $model_thang)
                ->with('model_phanloai', $model_phanloai)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }
}
