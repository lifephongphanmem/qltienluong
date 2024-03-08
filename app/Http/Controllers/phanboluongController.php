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
use App\hosocanbo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\phanboluong;
use App\phanboluong_bangluong;
use App\phanboluong_chitiet;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class phanboluongController extends Controller
{

    function index(){
        if( Session::has('admin')){
            $model = phanboluong::where('madv', session('admin')->madv)->orderby('namns')->get();
            $model_bl = bangluong::where('madv', session('admin')->madv)->where('phanloai', 'BANGLUONG')->orderby('nam')->orderby('thang')->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_chitiet = phanboluong_chitiet::wherein('masodv', array_column($model->toarray(), 'masodv'))->get();
            //Do chưa tuyển chỉ tính 12 tháng chưa nhân với số lượng cán bộ
            foreach ($model_chitiet as $chitiet) {
                if ($chitiet->phanloai == 'CHUATUYEN') {
                    $chitiet->st_heso = $chitiet->st_heso * $chitiet->canbo_congtac;
                    $chitiet->ttl = $chitiet->ttl * $chitiet->canbo_congtac;
                    $chitiet->ttbh_dv = $chitiet->ttbh_dv * $chitiet->canbo_congtac;
                }
            }
            foreach ($model as $phanbo) {
                //loại dữ liệu cũ
                $phanbo->luongnb_pbl = 0;
                $phanbo->luonghs_pbl = 0;
                $phanbo->luongbh_pbl =  0;
                //TÍnh toán lại
                $chitiet = $model_chitiet->where('masodv', $phanbo->masodv);
                //dd($chitiet);
                $phanbo->luongnb_pbl = $chitiet->sum('st_heso');
                $phanbo->luonghs_pbl = $chitiet->sum('ttl') - $phanbo->luongnb_pbl;
                $phanbo->luongbh_pbl =  $chitiet->sum('ttbh_dv');
                $phanbo->tongdutoan = $phanbo->luongnb_pbl + $phanbo->luonghs_pbl + $phanbo->luongbh_pbl;
                //dd($chitiet);
            }
            $a_cqcq = array_column(dmdonvi::select('madv', 'tendv')->where('madvbc', session('admin')->madvbc)
                ->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            return view('manage.phanboluong.donvi.index')
                    ->with('furl','/phanboluong/donvi')
                    ->with('a_nkp', getNguonKP(false))
                    ->with('model', $model)
                    ->with('model_bl', $model_bl)
                    ->with('a_nkp', getNguonKP(false))
                    ->with('a_phongban', getPhongBan(false))
                    ->with('model_nhomct', $model_nhomct)
                    ->with('model_tenct', $model_tenct)
                    ->with('a_trangthai', getStatus())
                    ->with('a_cqcq', $a_cqcq)
                    ->with('pageTitle','Phân bổ lương đơn vị');
        }else{
            return view('errors.notlogin');
        }
    }
    //Tạo phân bổ lương theo bảng lương *12
    function tao_phanbo(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            if (phanboluong::where('namns', $inputs['namns'])->where('madv', session('admin')->madv)->count() > 0) {
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
            $inputs['luongnb_pbl'] = 0;
            $inputs['luonghs_pbl'] = 0;
            $inputs['luongbh_pbl'] = 0;
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
                        $inputs['luongnb_pbl'] += $dutoan[$tenpc_st];
                    } else {
                        $inputs['luonghs_pbl'] += $dutoan[$tenpc_st];
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
                $inputs['luongbh_pbl'] += $dutoan['ttbh_dv'];
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
                        $inputs['luongnb_pbl'] += $dutoan[$tenpc_st];
                    } else {
                        $inputs['luonghs_pbl'] += $dutoan[$tenpc_st];
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
                $inputs['luongbh_pbl'] += $dutoan['ttbh_dv'];
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
                phanboluong_bangluong::insert($data);
            }
            phanboluong_chitiet::insert($a_dutoan);
            phanboluong::create($inputs);
            return redirect('/phanboluong/donvi');
        } else
            return view('errors.notlogin');
    }

    function thongtin_phanbo(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = phanboluong::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->first();
            
            // if ($model != null) {
            //     return redirect('/nghiep_vu/quan_ly/du_toan?maso=' . $model->masodv);
            // }

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

            return view('manage.phanboluong.donvi.thongtin')
                ->with('furl', '/phanboluong/donvi')
                ->with('inputs', $inputs)
                ->with('m_chitieu', $m_chitieu)
                ->with('model_pc', $model_pc)
                ->with('a_pc', $a_pc)
                ->with('a_nkp', getNguonKP(false))
                ->with('a_mact', array_column(dmphanloaict::select('tenct', 'macongtac', 'mact')->get()->toarray(), 'tenct', 'mact'))
                ->with('pageTitle', 'Thông tin phân bổ lương của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = phanboluong::find($id);
            phanboluong_chitiet::where('masodv', $model->masodv)->delete();
            phanboluong_bangluong::where('masodv', $model->masodv)->delete();
            // dutoanluong_nangluong::where('masodv', $model->masodv)->delete();

            // dutoanluong_khoi::where('masodv', $model->masok)->delete();
            // dutoanluong_huyen::where('masodv', $model->masoh)->delete();
            $model->delete();
            return redirect('/phanboluong/donvi');
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
            $model = phanboluong::where('masodv', $inputs['masodv'])->first();
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
        $model = phanboluong::select('lydo')->where('masodv', $inputs['masodv'])->first();

        die($model);
    }
}
