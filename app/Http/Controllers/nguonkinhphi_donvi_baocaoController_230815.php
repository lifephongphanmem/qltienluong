<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_nangluong;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_phucap;
use Illuminate\Support\Facades\Session;

class nguonkinhphi_donvi_baocaoController extends Controller
{
    //Lưu lại do theo thông tư 50 bỏ 1 số biểu mẫu theo thông tư 46 cũ
    function printf($masodv)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $model = nguonkinhphi::where('masodv', $masodv)->first();
            if ($model == null) {
                $model = nguonkinhphi::where('masoh', $masodv)->first();
            }
            //lấy thông tư tổng hợp nguồn
            $model_thongtu = dmthongtuquyetdinh::where('sohieu', $model->sohieu)->first();
            //dd($model_thongtu);
            $m_dv = dmdonvi::where('madv', $model->madv)->first();
            $data = array();
            $data[] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            $data[] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
            //Thiếu trường hợp 'Sự nghiệp khác' và GDDT
            $khac = false;
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['val'] == $model->linhvuchoatdong) {
                    $data[$i]['nhucau'] = $model->nhucau;
                    $data[$i]['nguonkp'] = $model->nguonkp;
                    $data[$i]['tietkiem'] = $model->tietkiem;
                    $data[$i]['hocphi'] = $model->hocphi;
                    $data[$i]['vienphi'] = $model->vienphi;
                    $data[$i]['nguonthu'] = $model->nguonthu;

                    $khac = true;
                }
            }
            $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
            $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
            $data[0]['tietkiem'] = $data[1]['tietkiem']  + $data[2]['tietkiem'];
            $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
            $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
            $data[0]['nguonthu'] = $data[1]['nguonthu']  + $data[2]['nguonthu'];

            if (!$khac) {
                $data[4]['nhucau'] = $model->nhucau;
                $data[4]['nguonkp'] = $model->nguonkp;
                $data[4]['tietkiem'] = $model->tietkiem;
                $data[4]['hocphi'] = $model->hocphi;
                $data[4]['vienphi'] = $model->vienphi;
                $data[4]['nguonthu'] = $model->nguonthu;
            }

            return view('reports.thongtu67.donvi.mau4b')
                ->with('model', $model)
                ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }


    function printf_tt107(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = nguonkinhphi_bangluong::where('masodv', $inputs['masodv']);
            if ($inputs['thang'] != 'ALL') {
                $model = $model->where('thang', $inputs['thang']);
            }
            if ($inputs['mact'] != 'ALL') {
                $model = $model->where('mact', $inputs['mact']);
            }
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model = $model->where('mapb', $inputs['mapb']);
            }

            $model = $model->orderby('thang')->orderby('stt')->get();
            //dd($model);
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
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

            // dd($model);
            $a_phongban = array_column(dmphongban::where('madv', $model_thongtin->madv)->get()->toArray(), 'tenpb', 'mapb');

            return view('reports.nguonkinhphi.donvi.bangluong')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_phongban', $a_phongban)
                ->with('a_ct', $a_ct)
                ->with('model_thang', $model_thang)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model_ct = nguonkinhphi_bangluong::where('masodv', $inputs['masodv']);
            $inputs['thang']  = $inputs['thang'] ?? 'ALL';
            if ($inputs['thang'] != 'ALL') {
                $model_ct = $model_ct->where('thang', $inputs['thang']);
            }
            if ($inputs['mact'] != 'ALL') {
                $model_ct = $model_ct->where('mact', $inputs['mact']);
            }
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model_ct = $model_ct->where('mapb', $inputs['mapb']);
            }
            $model_ct = $model_ct->orderby('thang')->orderby('stt')->get();

            $model = $model_ct->where('thang', $model_ct->min('thang'));
            //dd($model);

            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
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
                $bl = $model_ct->where('macanbo', $ct->macanbo)
                    ->where('macvcq', $ct->macvcq)
                    ->where('mact', $ct->mact);
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

            $a_phongban = array_column(dmphongban::where('madv', $model_thongtin->madv)->get()->toArray(), 'tenpb', 'mapb');
            return view('reports.nguonkinhphi.donvi.bangluong_m2')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phongban', $a_phongban)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghopnhucau_donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            $model = nguonkinhphi_phucap::where('masodv', $inputs['masodv'])->get();
            //Lấy phụ cấp
            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }
            //Lấy mã công tác
            $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');


            //dd($model);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );

            return view('reports.nguonkinhphi.donvi.tonghopnhucau')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function printf_nangluong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            //$model = nguonkinhphi_nangluong::where('masodv', $inputs['maso'])->orderby('stt')->get();

            $model_thongtin = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $model_tonghop = nguonkinhphi_bangluong::where('masodv', $inputs['masodv']);

            if ($inputs['mact'] != 'ALL') {
                $model_tonghop = $model_tonghop->where('mact', $inputs['mact']);
            }
            $inputs['mapb']  = $inputs['mapb'] ?? 'ALL';
            if ($inputs['mapb'] != 'ALL') {
                $model_tonghop = $model_tonghop->where('mapb', $inputs['mapb']);
            }
            $model_tonghop = $model_tonghop->orderby('stt')->get();
            $model = $model_tonghop->where('thang', '07');

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($model as $key => $chitiet) {
                $canbo = $model_tonghop->where('macanbo', $chitiet->macanbo)
                    ->where('mact', $chitiet->mact)
                    ->where('macvcq', $chitiet->macvcq)->sortby('thang');

                if ($chitiet->luongtn * 6 < $canbo->sum('luongtn')) {
                    //dd($canbo->toarray());
                    //lấy thời gian nâng lương
                    foreach ($canbo as $cb) {
                        if ($chitiet->luongtn < $cb->luongtn) {
                            $chitiet->thangnangluong = $cb->thang;
                            break;
                        }
                    }

                    foreach ($m_pc as $ct) {
                        $mapc = $ct['mapc'];
                        //dd($mapc);
                        $chitiet->$mapc = $canbo->sum($mapc) - $chitiet->$mapc * 6;
                    }
                    $chitiet->tonghs = $canbo->sum('tonghs') - $chitiet->tonghs * 6;
                    $chitiet->luongtn = $canbo->sum('luongtn') - $chitiet->luongtn * 6;

                    $chitiet->stbhxh_dv = $canbo->sum('stbhxh_dv') - $chitiet->stbhxh_dv * 6;
                    $chitiet->stbhyt_dv = $canbo->sum('stbhyt_dv') - $chitiet->stbhyt_dv * 6;
                    $chitiet->stkpcd_dv = $canbo->sum('stkpcd_dv') - $chitiet->stkpcd_dv * 6;
                    $chitiet->stbhtn_dv = $canbo->sum('stbhtn_dv') - $chitiet->stbhtn_dv * 6;
                    $chitiet->ttbh_dv = $canbo->sum('ttbh_dv') - $chitiet->ttbh_dv * 6;
                } else {
                    $model->forget($key);
                }
            }
            //dd($model);

            //dd($m_pc);
            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }
            $a_phongban = array_column(dmphongban::where('madv', $model_thongtin->madv)->get()->toArray(), 'tenpb', 'mapb');
            $a_congtac = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            return view('reports.nguonkinhphi.donvi.nangluong')
                ->with('inputs', $inputs)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phongban', $a_phongban)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghopnhucau_donvi_2a(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $m_chitiet = nguonkinhphi_01thang::where('masodv', $m_nguonkp->first()->masodv)->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->linhvuchoatdong = $m_nguonkp->first()->linhvuchoatdong;

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_nguonkp);

            $m_phucap = dmphucap_donvi::where('madv',  $m_nguonkp->first()->madv)->wherenotin('mapc', ['heso'])->get();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get(); //đưa về mảng cho dễ làm
            $a_phucap = getPhuCap2a_78();
            $array_phucap = array_keys($a_phucap); //mảng các phụ cấp 
            // dd(array_column($m_phucap->toarray(),'mapc'));
            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;
            //dd($m_nguonkp);
            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    // $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
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

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_I[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_I[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_I[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_I[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_I[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }

                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
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

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_I[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_I[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_I[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_I[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_I[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }


                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
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
                    $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_I[$key]['chenhlech01thang'] = 0;
                    $ar_I[$key]['chenhlech06thang'] = 0;
                    $ar_I[$key]['canbo_congtac'] = 0;
                    $ar_I[$key]['canbo_dutoan'] = 0;
                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            //


            //
            //Tính toán số liệu phần II
            $ar_II = getChuyenTrach();
            $dulieu_pII = $m_chitiet->where('nhomnhucau', 'CANBOCT');
            $aII_plct = getChuyenTrach_plct();
            foreach ($dulieu_pII as $key => $value) {
                if (count($aII_plct) > 0 && !in_array($value->mact, $aII_plct))
                    $dulieu_pII->forget($key);
            }
            //Vòng cấp độ 3
            foreach ($ar_II as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_II[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_II[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_II[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');;
                    $ar_II[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                }
            }


            //Tính toán số liệu phần III
            $ar_III = getHDND();
            $aIII_plct = getHDND_plct();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');
            foreach ($dulieu_pIII as $key => $value) {
                if (count($aIII_plct) > 0 && !in_array($value->mact, $aIII_plct))
                    $dulieu_pIII->forget($key);
            }

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
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }

                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_III[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }

                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }

                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_III[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_III[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
                }
            }
            // dd($ar_III);
            //Vòng cấp độ 2
            foreach ($ar_III as $key => $chitiet) {
                if ($chitiet['phanloai'] == '1') {
                    $a_solieu = [];
                    $a_solieu_moi = [];
                    //lấy thông tin trường trc
                    $ar_III[$key]['canbo_congtac'] = $ar_III[$key]['canbo_dutoan'] = 0;
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

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_III[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_III[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_III[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_III[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_III[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_III[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_III[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_III[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_III[$k]['solieu_moi']['tongcong'];

                        $ar_III[$key]['canbo_congtac'] += $ar_III[$k]['canbo_congtac'];
                        $ar_III[$key]['canbo_dutoan'] += $ar_III[$k]['canbo_dutoan'];
                    }

                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;

                    $ar_III[$key]['solieu'] = $a_solieu;
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán số liệu phần IV
            $ar_IV = getCapUy();
            $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY');;
            $aIV_plct = getCapUy_plct();
            foreach ($dulieu_pIV as $key => $value) {
                if (count($aIV_plct) > 0 && !in_array($value->mact, $aIV_plct))
                    $dulieu_pIV->forget($key);
            }
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
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$pc->mapc] * $luongcb);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu['pck'] += $a_solieu[$pc->mapc];
                            $a_solieu['st_pck'] += $a_solieu[$mapc_st];
                            $a_solieu[$pc->mapc] = 0;
                            $a_solieu[$mapc_st] = 0;
                        }
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_IV[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu_moi[$pc->mapc] = $dulieu_chitiet->sum($pc->mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$pc->mapc] * $luongcb_moi);
                    }
                    //Ở ngoài nhóm phụ cấp => đưa hết vào pck
                    foreach ($m_phucap as $pc) {
                        if (!in_array($pc->mapc, $array_phucap)) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi['pck'] += $a_solieu_moi[$pc->mapc];
                            $a_solieu_moi['st_pck'] += $a_solieu_moi[$mapc_st];
                            $a_solieu_moi[$pc->mapc] = 0;
                            $a_solieu_moi[$mapc_st] = 0;
                        }
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_IV[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_IV[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
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

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($m_phucap as $pc) {
                        $mapc_st = 'st_' . $pc->mapc;
                        $a_solieu[$pc->mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$pc->mapc] = $a_solieu_moi[$mapc_st] = 0;
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

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_IV[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_IV[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_IV[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_IV[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($m_phucap as $pc) {
                            $mapc_st = 'st_' . $pc->mapc;
                            $a_solieu_moi[$pc->mapc] += $ar_IV[$k]['solieu_moi'][$pc->mapc];
                            $a_solieu_moi[$mapc_st] += $ar_IV[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_IV[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_IV[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_IV[$k]['solieu_moi']['tongcong'];

                        $ar_IV[$key]['canbo_congtac'] += $ar_IV[$k]['canbo_congtac'];
                        $ar_IV[$key]['canbo_dutoan'] += $ar_IV[$k]['canbo_dutoan'];
                    }

                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;

                    $ar_IV[$key]['solieu'] = $a_solieu;
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán tổng cộng
            $a_Tong = [
                'canbo_congtac' => $ar_I[0]['canbo_congtac'] + $ar_II[0]['canbo_congtac'] + $ar_III[0]['canbo_congtac'] + $ar_IV[0]['canbo_congtac'],
                'canbo_dutoan' => $ar_I[0]['canbo_dutoan'] + $ar_II[0]['canbo_dutoan'] + $ar_III[0]['canbo_dutoan'] + $ar_IV[0]['canbo_dutoan'],
                'chenhlech01thang' => $ar_I[0]['chenhlech01thang'] + $ar_II[0]['chenhlech01thang'] + $ar_III[0]['chenhlech01thang'] + $ar_IV[0]['chenhlech01thang'],
                'chenhlech06thang' => $ar_I[0]['chenhlech06thang'] + $ar_II[0]['chenhlech06thang'] + $ar_III[0]['chenhlech06thang'] + $ar_IV[0]['chenhlech06thang'],
            ];
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
            $a_Tong['solieu_moi'] = [
                'tongcong' => $ar_I[0]['solieu_moi']['tongcong'] + $ar_II[0]['solieu_moi']['tongcong']
                    + $ar_III[0]['solieu_moi']['tongcong'] + $ar_IV[0]['solieu_moi']['tongcong'],
                'st_heso' => $ar_I[0]['solieu_moi']['st_heso'] + $ar_II[0]['solieu_moi']['st_heso']
                    + $ar_III[0]['solieu_moi']['st_heso'] + $ar_IV[0]['solieu_moi']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu_moi']['st_tongpc'] + $ar_II[0]['solieu_moi']['st_tongpc']
                    + $ar_III[0]['solieu_moi']['st_tongpc'] + $ar_IV[0]['solieu_moi']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu_moi']['ttbh_dv'] + $ar_II[0]['solieu_moi']['ttbh_dv']
                    + $ar_III[0]['solieu_moi']['ttbh_dv'] + $ar_IV[0]['solieu_moi']['ttbh_dv'],
            ];
            foreach ($m_phucap as $pc) {
                $mapc_st = 'st_' . $pc->mapc;
                $a_Tong['solieu_moi'][$mapc_st] = $ar_I[0]['solieu_moi'][$mapc_st] + $ar_II[0]['solieu_moi'][$mapc_st]
                    + $ar_III[0]['solieu_moi'][$mapc_st] + $ar_IV[0]['solieu_moi'][$mapc_st];
                $a_Tong['solieu'][$mapc_st] = $ar_I[0]['solieu'][$mapc_st] + $ar_II[0]['solieu'][$mapc_st]
                    + $ar_III[0]['solieu'][$mapc_st] + $ar_IV[0]['solieu'][$mapc_st];
            }
            // dd($ar_III);
            //dd($m_tonghop_ct);

            if ($inputs['mau'] == 1) {
                $view = 'reports.thongtu78.donvi.mau2a_1';
            } else {
                $view = 'reports.thongtu78.donvi.mau2a_2';
            }
            return view($view)
                // return view('reports.thongtu78.donvi.mau2a2')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                //->with('a_phucap', $a_phucap)
                ->with('a_phucap', getPhuCap2a_78())
                //->with('a_phucap_st', $a_phucap_st)
                //->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function tonghopnhucau_donvi_2a_2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->madv)->first();
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->sohieu)->first();
            $m_chitiet = nguonkinhphi_01thang::where('masodv', $m_nguonkp->masodv)->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->level = $m_donvi->caphanhchinh;
                $chitiet->maphanloai = $m_donvi->maphanloai;
                $chitiet->linhvuchoatdong = $m_nguonkp->linhvuchoatdong;

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_chitiet);
            $a_pc_th = dmphucap_donvi::where('madv',  $m_nguonkp->madv)->where('phanloai', '<', '3')->wherenotin('mapc', ['heso'])->get();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get(); //đưa về mảng cho dễ làm

            $a_phucap = array();
            $col = 0;
            $a_phucap_st = array();
            foreach ($a_pc_th as $ct) {
                if ($m_chitiet->sum($ct->mapc) > 0) {
                    $mapc_st = 'st_' . $ct->mapc;
                    $a_phucap[$ct->mapc] = $ct->form;
                    $a_phucap_st[$mapc_st] = $ct->form;
                    if ($ct->mapc !== 'heso') {
                        $col++;
                    }
                }
            }
            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'BIENCHE');
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;
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
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_I[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_I[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_I[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_I[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_I[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_I[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }

                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
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

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_I[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_I[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_I[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_I[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_I[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_I[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_I[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_I[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_I[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_I[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_I[$k]['solieu_moi']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }


                    $ar_I[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_I[$key]['chenhlech06thang'] = $ar_I[$key]['chenhlech01thang'] * 6;

                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
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
                    $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }
                    $ar_I[$key]['chenhlech01thang'] = 0;
                    $ar_I[$key]['chenhlech06thang'] = 0;
                    $ar_I[$key]['canbo_congtac'] = 0;
                    $ar_I[$key]['canbo_dutoan'] = 0;
                    $ar_I[$key]['solieu'] = $a_solieu;
                    $ar_I[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }
            //


            //
            //Tính toán số liệu phần II
            $ar_II = getChuyenTrach();
            $dulieu_pII = $m_chitiet->where('nhomnhucau', 'CANBOCT');
            $aII_plct = getChuyenTrach_plct();
            foreach ($dulieu_pII as $key => $value) {
                if (count($aII_plct) > 0 && !in_array($value->mact, $aII_plct))
                    $dulieu_pII->forget($key);
            }
            //Vòng cấp độ 3
            foreach ($ar_II as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pII;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                        $dulieu_nguonkp = $m_nguonkp->wherein('masodv', array_unique(array_column($dulieu_chitiet->toarray(), 'masodv')));
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_II[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_II[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_II[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_II[$key]['canbo_dutoan'] = $dulieu_nguonkp->sum('sobiencheduocgiao');
                    $ar_II[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_II[$key]['chenhlech06thang'] = $ar_II[$key]['chenhlech01thang'] * 6;
                }
            }


            //Tính toán số liệu phần III
            $ar_III = getHDND();
            $aIII_plct = getHDND_plct();
            $dulieu_pIII = $m_chitiet->where('nhomnhucau',  'HDND');
            foreach ($dulieu_pIII as $key => $value) {
                if (count($aIII_plct) > 0 && !in_array($value->mact, $aIII_plct))
                    $dulieu_pIII->forget($key);
            }

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
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_III[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_III[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_III[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;
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

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_III[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_III[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_III[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_III[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_III[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_III[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_III[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_III[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_III[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_III[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_III[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_III[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_III[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_III[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_III[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_III[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_III[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_III[$k]['solieu_moi']['tongcong'];

                        $ar_III[$key]['canbo_congtac'] += $ar_III[$k]['canbo_congtac'];
                        $ar_III[$key]['canbo_dutoan'] += $ar_III[$k]['canbo_dutoan'];
                    }

                    $ar_III[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_III[$key]['chenhlech06thang'] = $ar_III[$key]['chenhlech01thang'] * 6;

                    $ar_III[$key]['solieu'] = $a_solieu;
                    $ar_III[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán số liệu phần IV
            $ar_IV = getCapUy();
            $dulieu_pIV = $m_chitiet->where('nhomnhucau',  'CAPUY');;
            $aIV_plct = getCapUy_plct();
            foreach ($dulieu_pIV as $key => $value) {
                if (count($aIV_plct) > 0 && !in_array($value->mact, $aIV_plct))
                    $dulieu_pIV->forget($key);
            }
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
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);
                    //dd($a_solieu);
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu[$mapc_st] = round($a_solieu[$mapc] * $luongcb);
                    }
                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_IV[$key]['solieu'] = $a_solieu;

                    //tính lại bảng lương theo số tiền mới
                    $a_solieu_moi = [];

                    $a_solieu_moi['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_heso'] = round($a_solieu_moi['heso'] * $luongcb_moi);

                    $a_solieu_moi['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu_moi['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb_moi);

                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu_moi[$mapc] = $dulieu_chitiet->sum($mapc);
                        $a_solieu_moi[$mapc_st] = round($a_solieu_moi[$mapc] * $luongcb_moi);
                    }
                    $a_solieu_moi['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu_moi['st_tongpc'] = round($a_solieu_moi['tongpc'] * $luongcb_moi);
                    $a_solieu_moi['tongcong'] = $a_solieu_moi['st_tongpc'] + $a_solieu_moi['st_heso'] + $a_solieu_moi['ttbh_dv'];
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;

                    $ar_IV[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_IV[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;
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

                    $a_solieu_moi['canbo_congtac'] = $a_solieu_moi['canbo_dutoan'] = $a_solieu_moi['heso'] = $a_solieu_moi['st_heso'] = $a_solieu_moi['tongpc'] = $a_solieu_moi['st_tongpc']
                        = $a_solieu_moi['tongbh_dv'] = $a_solieu_moi['ttbh_dv'] = $a_solieu_moi['tongcong'] = 0;
                    foreach ($a_phucap as $mapc => $tenpc) {
                        $mapc_st = 'st_' . $mapc;
                        $a_solieu[$mapc] = $a_solieu[$mapc_st] = $a_solieu_moi[$mapc] = $a_solieu_moi[$mapc_st] = 0;
                    }

                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_IV[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_IV[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_IV[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_IV[$k]['solieu']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu[$mapc] += $ar_IV[$k]['solieu'][$mapc];
                            $a_solieu[$mapc_st] += $ar_IV[$k]['solieu'][$mapc_st];
                        }
                        $a_solieu['tongpc'] += $ar_IV[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_IV[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_IV[$k]['solieu']['tongcong'];

                        //bang lương mới

                        $a_solieu_moi['heso'] += $ar_IV[$k]['solieu_moi']['heso'];
                        $a_solieu_moi['st_heso'] += $ar_IV[$k]['solieu_moi']['st_heso'];
                        $a_solieu_moi['tongbh_dv'] += $ar_IV[$k]['solieu_moi']['tongbh_dv'];
                        $a_solieu_moi['ttbh_dv'] += $ar_IV[$k]['solieu_moi']['ttbh_dv'];

                        foreach ($a_phucap as $mapc => $tenpc) {
                            $mapc_st = 'st_' . $mapc;
                            $a_solieu_moi[$mapc] += $ar_IV[$k]['solieu_moi'][$mapc];
                            $a_solieu_moi[$mapc_st] += $ar_IV[$k]['solieu_moi'][$mapc_st];
                        }
                        $a_solieu_moi['tongpc'] += $ar_IV[$k]['solieu_moi']['tongpc'];
                        $a_solieu_moi['st_tongpc'] += $ar_IV[$k]['solieu_moi']['st_tongpc'];
                        $a_solieu_moi['tongcong'] += $ar_IV[$k]['solieu_moi']['tongcong'];

                        $ar_IV[$key]['canbo_congtac'] += $ar_IV[$k]['canbo_congtac'];
                        $ar_IV[$key]['canbo_dutoan'] += $ar_IV[$k]['canbo_dutoan'];
                    }

                    $ar_IV[$key]['chenhlech01thang'] = $a_solieu_moi['tongcong'] - $a_solieu['tongcong'];
                    $ar_IV[$key]['chenhlech06thang'] = $ar_IV[$key]['chenhlech01thang'] * 6;

                    $ar_IV[$key]['solieu'] = $a_solieu;
                    $ar_IV[$key]['solieu_moi'] = $a_solieu_moi;
                }
            }

            //Tính toán tổng cộng
            $a_Tong = [
                'canbo_congtac' => $ar_I[0]['canbo_congtac'] + $ar_II[0]['canbo_congtac'] + $ar_III[0]['canbo_congtac'] + $ar_IV[0]['canbo_congtac'],
                'canbo_dutoan' => $ar_I[0]['canbo_dutoan'] + $ar_II[0]['canbo_dutoan'] + $ar_III[0]['canbo_dutoan'] + $ar_IV[0]['canbo_dutoan'],
                'chenhlech01thang' => $ar_I[0]['chenhlech01thang'] + $ar_II[0]['chenhlech01thang'] + $ar_III[0]['chenhlech01thang'] + $ar_IV[0]['chenhlech01thang'],
                'chenhlech06thang' => $ar_I[0]['chenhlech06thang'] + $ar_II[0]['chenhlech06thang'] + $ar_III[0]['chenhlech06thang'] + $ar_IV[0]['chenhlech06thang'],
            ];
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
            $a_Tong['solieu_moi'] = [
                'tongcong' => $ar_I[0]['solieu_moi']['tongcong'] + $ar_II[0]['solieu_moi']['tongcong']
                    + $ar_III[0]['solieu_moi']['tongcong'] + $ar_IV[0]['solieu_moi']['tongcong'],
                'st_heso' => $ar_I[0]['solieu_moi']['st_heso'] + $ar_II[0]['solieu_moi']['st_heso']
                    + $ar_III[0]['solieu_moi']['st_heso'] + $ar_IV[0]['solieu_moi']['st_heso'],
                'st_tongpc' => $ar_I[0]['solieu_moi']['st_tongpc'] + $ar_II[0]['solieu_moi']['st_tongpc']
                    + $ar_III[0]['solieu_moi']['st_tongpc'] + $ar_IV[0]['solieu_moi']['st_tongpc'],
                'ttbh_dv' => $ar_I[0]['solieu_moi']['ttbh_dv'] + $ar_II[0]['solieu_moi']['ttbh_dv']
                    + $ar_III[0]['solieu_moi']['ttbh_dv'] + $ar_IV[0]['solieu_moi']['ttbh_dv'],
            ];
            foreach ($a_phucap_st as $mapc => $tenpc) {
                $a_Tong['solieu_moi'][$mapc] = $ar_I[0]['solieu_moi'][$mapc] + $ar_II[0]['solieu_moi'][$mapc]
                    + $ar_III[0]['solieu_moi'][$mapc] + $ar_IV[0]['solieu_moi'][$mapc];
                $a_Tong['solieu'][$mapc] = $ar_I[0]['solieu'][$mapc] + $ar_II[0]['solieu'][$mapc]
                    + $ar_III[0]['solieu'][$mapc] + $ar_IV[0]['solieu'][$mapc];
            }
            // dd($ar_I);
            //dd($m_tonghop_ct);

            if ($inputs['mau'] == 1) {
                $view = 'reports.thongtu78.donvi.mau2a2_1';
            } else {
                $view = 'reports.thongtu78.donvi.mau2a2_2';
            }
            return view($view)
                // return view('reports.thongtu78.donvi.mau2a2_2_cu')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('ar_II', $ar_II)
                ->with('ar_III', $ar_III)
                ->with('ar_IV', $ar_IV)
                ->with('a_Tong', $a_Tong)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('a_phucap', $a_phucap)
                ->with('a_phucap_st', $a_phucap_st)
                ->with('col', $col)
                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2b(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->madv)->first();

            $ar_I = array();
            $ar_I[0] = array(
                'val' => 'BT', 'tt' => '1', 'noidung' => 'Nguyên bí thư, chủ tịch',
                'songuoi' => $m_nguonkp->tongsonguoi1,
                'quy1' => $m_nguonkp->quy1_1,
                'quy2' => $m_nguonkp->quy2_1,
                'quy3' => $m_nguonkp->quy3_1,
                'tongquy' => $m_nguonkp->quy1_tong,

            );

            $ar_I[1] = array(
                'val' => 'P', 'tt' => '2', 'noidung' => 'Nguyên Phó bí thư, phó chủ tịch, Thường trực Đảng ủy, Ủy viên, Thư ký UBND Thư ký HĐND, xã đội trưởng',
                'songuoi' => $m_nguonkp->tongsonguoi2,
                'quy1' => $m_nguonkp->quy1_2,
                'quy2' => $m_nguonkp->quy2_2,
                'quy3' => $m_nguonkp->quy3_2,
                'tongquy' => $m_nguonkp->quy2_tong,
            );
            $ar_I[2] = array(
                'val' => 'K', 'tt' => '3', 'noidung' => 'Các chức danh còn lại',
                'songuoi' => $m_nguonkp->tongsonguoi3,
                'quy1' => $m_nguonkp->quy1_3,
                'quy2' => $m_nguonkp->quy2_3,
                'quy3' => $m_nguonkp->quy3_3,
                'tongquy' => $m_nguonkp->quy3_tong,
            );

            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2b')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Bảng tổng quỹ trợ cấp tăng thêm cho cán bộ đã nghỉ hưu');
        } else
            return view('errors.notlogin');
    }

    function mau2c(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $luongcb = $m_thongtu->muccu;
            $luongcb_moi = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;
            $inputs['donvitinh'] = 1;
            //$ar_I = array();
            //$ar_Igr = array();
            $ar_I = getHCSN_2c();

            $m_bangluong = nguonkinhphi_bangluong::where('thang', '07')
                ->where('stbhtn_dv', '>', '0')->get();
            //dd($m_nguonkp);
            foreach ($m_nguonkp as $key => $chitiet) {
                $dulieu = $m_bangluong->where('masodv', $chitiet->masodv);
                $chitiet->soluongcanbo_2c = $dulieu->count();
                $chitiet->hesoluong_2c = $dulieu->sum('heso');
                $chitiet->phucapchucvu_2c = $dulieu->sum('pccv');
                $chitiet->phucapvuotkhung_2c = $dulieu->sum('vuotkhung');
                $chitiet->phucaptnn_2c = $dulieu->sum('pctnn');
                if ($chitiet->hesoluong_2c <= 0) {
                    $m_nguonkp->forget($key);
                }
            }

            for ($i = 0; $i < 3; $i++) {
                foreach ($ar_I as $key => $chitiet) {
                    if ($chitiet['phanloai'] != $i) {
                        continue; //để chạy đúng phân loại từ thấp đến cao
                    }
                    switch ($chitiet['phanloai']) {
                        case '0': {
                                $tentruong = array_key_last($chitiet['chitiet']);
                                $dulieu = $m_nguonkp->wherein($tentruong, $chitiet['chitiet'][$tentruong]);
                                $hesoluong = $dulieu->sum('hesoluong_2c');
                                $phucapchucvu = $dulieu->sum('phucapchucvu_2c');
                                $phucapvuotkhung = $dulieu->sum('phucapvuotkhung_2c');
                                $phucaptnn = $dulieu->sum('phucaptnn_2c');
                                $tongphucap = $phucapchucvu + $phucapvuotkhung + $phucaptnn;
                                $tongluong = $hesoluong + $tongphucap;
                                $baohiem = round($tongluong / 100, 5);
                                $tongcong = $tongluong  + $baohiem;
                                $ar_I[$key]['solieu'] = [
                                    'sobiencheduocgiao' => $dulieu->sum('sobiencheduocgiao'),
                                    'soluongqt_2c' => $dulieu->sum('soluongqt_2c'),
                                    'sotienqt_2c' => $dulieu->sum('sotienqt_2c'),
                                    'soluongcanbo_2c' => $dulieu->sum('soluongcanbo_2c'),
                                    //Mức lương cũ
                                    'hesoluong_cu' => round($hesoluong * $luongcb),
                                    'phucapchucvu_cu' => round($phucapchucvu * $luongcb),
                                    'phucapvuotkhung_cu' => round($phucapvuotkhung * $luongcb),
                                    'phucaptnn_cu' => round($phucaptnn * $luongcb),
                                    'tongphucap_cu' => round($tongphucap * $luongcb),
                                    'tongluong_cu' => round($tongluong * $luongcb),
                                    'baohiem_cu' => round($baohiem * $luongcb),
                                    'tongcong_cu' => round($tongcong * $luongcb),
                                    //Mức lương mới
                                    'hesoluong_moi' => round($hesoluong * $luongcb_moi),
                                    'phucapchucvu_moi' => round($phucapchucvu * $luongcb_moi),
                                    'phucapvuotkhung_moi' => round($phucapvuotkhung * $luongcb_moi),
                                    'phucaptnn_moi' => round($phucaptnn * $luongcb_moi),
                                    'tongphucap_moi' => round($tongphucap * $luongcb_moi),
                                    'tongluong_moi' => round($tongluong * $luongcb_moi),
                                    'baohiem_moi' => round($baohiem * $luongcb_moi),
                                    'tongcong_moi' => round($tongcong * $luongcb_moi),

                                    'chenhlech' => round($baohiem * $chenhlech),
                                    'tongchenhlech' => round($baohiem * $chenhlech * 6),
                                ];
                                break;
                            }

                        case '1': {
                                $a_solieu = [
                                    'sobiencheduocgiao' => 0,
                                    'soluongqt_2c' => 0,
                                    'sotienqt_2c' => 0,
                                    'soluongcanbo_2c' => 0,
                                    //Mức lương cũ
                                    'hesoluong_cu' => 0,
                                    'phucapchucvu_cu' => 0,
                                    'phucapvuotkhung_cu' => 0,
                                    'phucaptnn_cu' => 0,
                                    'tongphucap_cu' => 0,
                                    'tongluong_cu' => 0,
                                    'baohiem_cu' => 0,
                                    'tongcong_cu' => 0,
                                    //Mức lương mới
                                    'hesoluong_moi' => 0,
                                    'phucapchucvu_moi' => 0,
                                    'phucapvuotkhung_moi' => 0,
                                    'phucaptnn_moi' => 0,
                                    'tongphucap_moi' => 0,
                                    'tongluong_moi' => 0,
                                    'baohiem_moi' => 0,
                                    'tongcong_moi' => 0,
                                    'chenhlech' => 0,
                                    'tongchenhlech' => 0,
                                ];
                                foreach ($chitiet['chitiet'] as $ct) {
                                    foreach (array_keys($a_solieu) as $k) {
                                        $a_solieu[$k] += $ar_I[$ct]['solieu'][$k];
                                    }
                                }
                                $ar_I[$key]['solieu'] = $a_solieu;
                                break;
                            }
                    }
                }
            }

            //Tính tổng cộng
            $a_Tong = [
                'soluongqt_2c' => 0,
                'soluongqt_2c' => 0,
                'sotienqt_2c' => 0,
                'sobiencheduocgiao' => 0,
                'soluongcanbo_2c' => 0,
                'tongluong_cu' => 0,
                'hesoluong_cu' => 0,
                'tongphucap_cu' => 0,
                'phucapchucvu_cu' => 0,
                'phucapvuotkhung_cu' => 0,
                'phucaptnn_cu' => 0,
                'baohiem_cu' => 0,
                'tongluong_moi' => 0,
                'hesoluong_moi' => 0,
                'tongphucap_moi' => 0,
                'phucapchucvu_moi' => 0,
                'phucapvuotkhung_moi' => 0,
                'phucaptnn_moi' => 0,
                'baohiem_moi' => 0,
                'chenhlech' => 0,
                'tongchenhlech' => 0,
            ];
            foreach ($ar_I as $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    foreach (array_keys($a_Tong) as $col) {
                        $a_Tong[$col] += $chitiet['solieu'][$col];
                    }
                }
            }

            return view('reports.thongtu78.huyen.mau2c')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_Tong', $a_Tong)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN BHTN');
        } else
            return view('errors.notlogin');
    }

    function mau2d(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');

            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');

            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            //Số liệu cho cán bộ không chuyên trách
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];

                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            $m_chitiet = $m_chitiet->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOKCT');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
            }
            $m_nguonkp = $m_nguonkp->where('maphanloai', 'KVXP');

            $a_It = array(
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            );

            $ar_I = array();
            $ar_I[0] = array('val' => 'XL1;XL2;XL3', 'tt' => 'I', 'noidung' => 'Xã, phường, thị trấn',);
            //
            $m_ct_1 = $m_chitiet->where('phanloaixa', 'XL1');
            $m_nguon_1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1] = array('val' => 'XL1', 'tt' => '1', 'noidung' => 'Xã loại I', 'solieu' => [
                'tdv' => $m_nguon_1->count(),
                'mk' => 20.3,
                'mk2' => 16,
                'dt' => $m_ct_1->sum('canbo_congtac'),
                'kqpc' => round($m_nguon_1->count() * 20.3 * 1.49, 5),
                'bhxh' => round($m_ct_1->sum('canbo_congtac') * 0.14 * 1.49, 5),
                'kqpct7' => round($m_nguon_1->count() * 16 * 1.8, 5),
                'tong' => 0
            ]);
            //
            $m_ct_2 = $m_chitiet->where('phanloaixa', 'XL2');
            $m_nguon_2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2] = array('val' => 'XL2', 'tt' => '2', 'noidung' => 'Xã loại II', 'solieu' => [
                'tdv' => $m_nguon_2->count(),
                'mk' => 18.6,
                'mk2' => 13.7,
                'dt' => $m_ct_2->sum('canbo_congtac'),
                'kqpc' => round($m_nguon_2->count() * 18.6 * 1.49, 5),
                'bhxh' => round($m_ct_2->sum('canbo_congtac') * 0.14 * 1.49, 5),
                'kqpct7' => round($m_nguon_2->count() * 13.7 * 1.8, 5),
                'tong' => 0
            ]);
            //
            $m_ct_3 = $m_chitiet->where('phanloaixa', 'XL3');
            $m_nguon_3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3] = array('val' => 'XL3', 'tt' => '3', 'noidung' => 'Xã loại III', 'solieu' => [
                'tdv' => $m_nguon_3->count(),
                'mk' => 17.6,
                'mk2' => 11.4,
                'dt' => $m_ct_3->sum('canbo_congtac'),
                'kqpc' => round($m_nguon_3->count() * 17.6 * 1.49, 5),
                'bhxh' => round($m_ct_3->sum('canbo_congtac') * 0.14 * 1.49, 5),
                'kqpct7' => round($m_nguon_3->count() * 11.4 * 1.8, 5),
                'tong' => 0
            ]);
            //Tổng phân loại xã
            $ar_I[0]['solieu'] = [
                'tdv' =>  $ar_I[1]['solieu']['tdv'] + $ar_I[2]['solieu']['tdv'] + $ar_I[3]['solieu']['tdv'],
                'mk' => 0,
                'mk2' => 0,
                'dt' => $ar_I[1]['solieu']['dt'] + $ar_I[2]['solieu']['dt'] + $ar_I[3]['solieu']['dt'],
                'kqpc' => $ar_I[1]['solieu']['kqpc'] + $ar_I[2]['solieu']['kqpc'] + $ar_I[3]['solieu']['kqpc'],
                'bhxh' => $ar_I[1]['solieu']['bhxh'] + $ar_I[2]['solieu']['bhxh'] + $ar_I[3]['solieu']['bhxh'],
                'kqpct7' => $ar_I[1]['solieu']['kqpct7'] + $ar_I[2]['solieu']['kqpct7'] + $ar_I[3]['solieu']['kqpct7'],
                'tong' => 0
            ];


            $ar_I[4] = array('val' => 'DBKK;BGHD;DBTD', 'tt' => 'II', 'noidung' => 'Thôn, tỏ dân phố', 'solieu' => [
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            ]);

            $ar_I[5] = array('val' => 'BGHD', 'tt' => '1', 'noidung' => 'Số xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 5 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[6] = array('val' => 'TBGHD', 'tt' => '', 'noidung' => '- Thôn thuộc xã biên giới, hải đảo', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonbiengioi_2d'),
                'mk' => 5,
                'mk2' => 5,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothonbiengioi_2d') * 5 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 5 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[7] = array('val' => 'DBKK', 'tt' => '2', 'noidung' => 'Số xã có thôn có 350 hộ gia đình trở lên,  xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[8] = array('val' => 'TDBKK', 'tt' => '', 'noidung' => '- Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothontrongdiem_2d'),
                'mk' => 3,
                'mk2' => 3,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothontrongdiem_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[9] = array('val' => 'TK,TDP', 'tt' => '3', 'noidung' => 'Số xã, phường, thị trấn còn lại', 'solieu' => [
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            ]);
            $ar_I[10] = array('val' => 'TK', 'tt' => '', 'noidung' => '- Thôn còn lại', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sothonconlai_2d'),
                'mk' => 3,
                'mk2' => 3,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sothonconlai_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);
            $ar_I[11] = array('val' => 'TDP', 'tt' => '', 'noidung' => '- Tổ dân phố', 'solieu' => [
                'tdv' => $m_nguonkp->sum('sotoconlai_2d'),
                'mk' => 3,
                'mk2' => 3,
                'dt' => 0,
                'kqpc' => round($m_nguonkp->sum('sotoconlai_2d') * 3 * 1.49, 5),
                'bhxh' => 0,
                'kqpct7' => round($m_nguon_3->count() * 3 * 1.8, 5),
                'tong' => 0
            ]);

            $a_It = array(
                'tdv' => 0,
                'mk' => 0,
                'mk2' => 0,
                'dt' => 0,
                'kqpc' => 0,
                'bhxh' => 0,
                'kqpct7' => 0,
                'tong' => 0
            );


            //dd($ar_I);
            return view('reports.thongtu78.huyen.mau2d')
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('a_It', $a_It)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP KINH PHÍ TĂNG THÊM ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH');
        } else
            return view('errors.notlogin');
    }

    function mau2dd(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
            }

            $m_qlnn = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN']);
            $m_sunghiep = $m_nguonkp->wherenotin('linhvuchoatdong', ['QLNN']);

            $ar_I = array();
            $ar_I[0] = array('val' => 'QLNN', 'tt' => 'I', 'noidung' => 'Quản lý nhà nước');
            $ar_I[0]['solieu'] = [
                'tongsonguoi2015' => $m_qlnn->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_qlnn->sum('tongsonguoi2017'),
                'quyluong' => $m_qlnn->sum('quyluong'),
                'soluonghientai_2dd' => $m_qlnn->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_qlnn->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_qlnn->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_qlnn->sum('quyluongtietkiem_2dd'),
            ];
            $ar_I[1] = array('val' => 'SNCL', 'tt' => 'II', 'noidung' => 'Sự nghiệp công lập');
            $ar_I[1]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[2] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[2]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CHITXDT')->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[3] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[3]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTX')->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[4] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[4]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'CTXMP')->sum('quyluongtietkiem_2dd'),
            ];

            $ar_I[5] = array('val' => 'NGANSACH', 'tt' => '4', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            $ar_I[5]['solieu'] = [
                'tongsonguoi2015' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('tongsonguoi2015'),
                'tongsonguoi2017' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('tongsonguoi2017'),
                'quyluong' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('quyluong'),
                'soluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('soluonghientai_2dd'),
                'quyluonghientai_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('quyluonghientai_2dd'),
                'kinhphitietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('kinhphitietkiem_2dd'),
                'quyluongtietkiem_2dd' => $m_sunghiep->where('phanloainguon', 'NGANSACH')->sum('quyluongtietkiem_2dd'),
            ];


            return view('reports.thongtu78.huyen.mau2dd')
                ->with('inputs', $inputs)
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2e(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$m_thongtu = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
            }

            $ar_I = array();

            $ar_I[2] = array('val' => 'CHITXDT', 'tt' => '1', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên và chi đầu tư (2)');
            $ar_I[2]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $ar_I[3] = array('val' => 'CTX', 'tt' => '2', 'noidung' => 'Đơn vị đảm bảo chi thường xuyên (2)');
            $ar_I[3]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $ar_I[4] = array('val' => 'CTXMP', 'tt' => '3', 'noidung' => 'Đơn vị đảm bảo một phần chi thường xuyên');
            $ar_I[4]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];

            $ar_I[5] = array('val' => 'NGANSACH', 'tt' => '4', 'noidung' => 'Đơn vị được nhà nước đảm bảo chi thường xuyên');
            $ar_I[5]['solieu'] = [
                'tongsodonvi1' => 0,
                'tongsodonvi2' => 0,
                'tang' => 0,
                'giam' => 0,
                'quy_tuchu' => 0,
                'kp_tk' => 0,
            ];


            return view('reports.thongtu78.huyen.mau2e')
                ->with('inputs', $inputs)
                ->with('m_dv', $m_donvi)
                ->with('ar_I', $ar_I)
                ->with('pageTitle', 'Báo cáo nguồn thực hiện CCTL tiết kiệm từ việc thay đổi cơ chế tự chủ');
        } else
            return view('errors.notlogin');
    }

    function mau2g_20230809(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_phucap::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_chitiet);
            // $luongcb = $m_thongtu->mucapdung;
            // $chenhlech = $m_thongtu->chenhlech;

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'HOPDONG');
            //dd($dulieu_pI);
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = $dulieu_chitiet->sum('st_heso');

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = $dulieu_chitiet->sum('ttbh_dv');

                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $a_solieu['heso'];
                    $a_solieu['st_tongpc'] = $dulieu_chitiet->sum('ttl') - $a_solieu['st_heso'];
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac') / 6;
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan') / 6;
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


                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];


                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];



                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
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



                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ
                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];


                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }

                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }

            //
            unset($ar_I[1]);
            $ar_I[0]['noidung'] = 'TỔNG CỘNG';
            //dd($ar_I) ;    


            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2g')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)

                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2g(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            //$a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            //$a_diaban = array_column(dmdonvibaocao::all()->toArray(), 'level', 'madvbc');
            //dd($a_donvi);
            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');

            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }
            //dd($m_chitiet);
            $luongcb = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            //Tính toán số liệu phần I
            $ar_I = getHCSN();
            $dulieu_pI = $m_chitiet->where('nhomnhucau', 'HOPDONG');
            // dd($dulieu_pI);
            //Vòng cấp độ 3
            foreach ($ar_I as $key => $chitiet) {
                if ($chitiet['phanloai'] == '0') {
                    $dulieu_chitiet = $dulieu_pI;
                    foreach ($chitiet['chitiet'] as $k => $v) {
                        $dulieu_chitiet  = $dulieu_chitiet->where($k, $v);
                    }
                    //Tính bảng lương theo số tiền cũ
                    $a_solieu = [];

                    $a_solieu['heso'] = $dulieu_chitiet->sum('heso');
                    $a_solieu['st_heso'] = round($a_solieu['heso'] * $luongcb);

                    $a_solieu['tongbh_dv'] = $dulieu_chitiet->sum('tongbh_dv');
                    $a_solieu['ttbh_dv'] = round(($dulieu_chitiet->sum('ttbh_dv') / $chenhlech) * $luongcb);

                    $a_solieu['tongpc'] = $dulieu_chitiet->sum('tonghs') - $dulieu_chitiet->sum('heso');
                    $a_solieu['st_tongpc'] = round($a_solieu['tongpc'] * $luongcb);
                    $a_solieu['tongcong'] = $a_solieu['st_tongpc'] + $a_solieu['st_heso'] + $a_solieu['ttbh_dv'];
                    $ar_I[$key]['solieu'] = $a_solieu;

                    $ar_I[$key]['canbo_congtac'] = $dulieu_chitiet->sum('canbo_congtac');
                    $ar_I[$key]['canbo_dutoan'] = $dulieu_chitiet->sum('canbo_dutoan');
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


                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ

                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];


                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];



                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
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



                    foreach ($chitiet['chitiet'] as $k) {
                        //bảng lương cũ
                        $a_solieu['heso'] += $ar_I[$k]['solieu']['heso'];
                        $a_solieu['st_heso'] += $ar_I[$k]['solieu']['st_heso'];
                        $a_solieu['tongbh_dv'] += $ar_I[$k]['solieu']['tongbh_dv'];
                        $a_solieu['ttbh_dv'] += $ar_I[$k]['solieu']['ttbh_dv'];


                        $a_solieu['tongpc'] += $ar_I[$k]['solieu']['tongpc'];
                        $a_solieu['st_tongpc'] += $ar_I[$k]['solieu']['st_tongpc'];
                        $a_solieu['tongcong'] += $ar_I[$k]['solieu']['tongcong'];

                        $ar_I[$key]['canbo_congtac'] += $ar_I[$k]['canbo_congtac'];
                        $ar_I[$key]['canbo_dutoan'] += $ar_I[$k]['canbo_dutoan'];
                    }

                    $ar_I[$key]['solieu'] = $a_solieu;
                }
            }

            //
            unset($ar_I[1]);
            $ar_I[0]['noidung'] = 'TỔNG CỘNG';
            //dd($ar_I) ;    


            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2g')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)

                ->with('pageTitle', 'Báo cáo nhu cầu kinh phí');
        } else
            return view('errors.notlogin');
    }

    function mau2h(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $chitiet->tongluong_2h = $chitiet->hesophucap_2h + $chitiet->hesoluong_2h;
                $chitiet->chenhlech_2h = $chitiet->tonghesophucapqd244_2h - $chitiet->tonghesophucapnd61_2h;
                $chitiet->quyluong_2h = $chitiet->chenhlech_2h * 12 * 1800000;
            }

            //dd($m_nguonkp);
            $m_dshuyen = dmdonvibaocao::where('level', 'H')->get();

            //dd($m_nguonkp);
            return view('reports.thongtu78.huyen.mau2h')
                ->with('m_chitiet', $m_nguonkp)
                ->with('m_dshuyen', $m_dshuyen)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP ƯU ĐÃI TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2i(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            //Số liệu cho các thôn, xã 
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $chitiet->tongluong_2i = $chitiet->hesophucap_2i + $chitiet->hesoluong_2i;
                $chitiet->chenhlech_2i = $chitiet->tongluong_2i * 0.7;
                $chitiet->quyluong_2i = $chitiet->chenhlech_2i * 12 * 1800000;
            }

            //dd($m_nguonkp);
            $m_dshuyen = dmdonvibaocao::where('level', 'H')->get();

            //dd($m_nguonkp);
            return view('reports.thongtu78.huyen.mau2i')
                ->with('m_chitiet', $m_nguonkp)
                ->with('m_dshuyen', $m_dshuyen)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'TỔNG HỢP PHỤ CẤP THU HÚT TĂNG, GIẢM DO ĐIỀU CHỈNH ĐỊA BÀN VÙNG KINH TẾ XÃ HỘI ĐẶC BIỆT KHÓ KHĂN');
        } else
            return view('errors.notlogin');
    }

    function mau2k(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }

            $luongcb = $m_thongtu->mucapdung;
            $chenhlech = $m_thongtu->chenhlech;

            $m_chitiet = $m_chitiet->where('nhomnhucau', 'CANBOCT');

            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $dulieu = $m_chitiet->where('madv', $chitiet->madv);
                if ($dulieu->count() == 0) {
                    $m_nguonkp->forget($key);
                    continue;
                }

                $chitiet->tongquyluonggiam_2k = $chitiet->quyluonggiam_2k * 6;

                $chitiet->heso = $dulieu->sum('heso');
                $chitiet->st_heso = round($chitiet->heso * $luongcb);

                $chitiet->tongbh_dv = $dulieu->sum('tongbh_dv');
                $chitiet->ttbh_dv = round(($dulieu->sum('ttbh_dv') / $chenhlech) * $luongcb);

                $chitiet->tongpc = $dulieu->sum('tonghs') - $chitiet->heso;
                $chitiet->st_tongpc = round($chitiet->tongpc * $luongcb);
                $chitiet->tongcong = $chitiet->st_tongpc + $chitiet->st_heso + $chitiet->ttbh_dv;


                $chitiet->canbo_congtac = $dulieu->sum('canbo_congtac');
                $chitiet->canbo_dutoan = $dulieu->sum('canbo_dutoan');
            }
            //Tính toán số liệu phần I
            $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);
            $ar_I[1] = array('style' => '', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2k' => $m_xl1->count(),
                'qd92_2k' => 25,
                'tongqd92_2k' => 25 * $m_xl1->count(),
                'tongbienche_2k' => $m_xl1->sum('canbo_congtac'),
                'trungbinhheso_2k' => round($m_xl1->sum('st_heso') / chkDiv0($m_xl1->sum('canbo_congtac')), 6),
                'trungbinhphucap_2k' => round($m_xl1->sum('st_tongpc') / chkDiv0($m_xl1->sum('canbo_congtac')), 6),
                'trungbinhdonggop_2k' => round($m_xl1->sum('ttbh_dv') / chkDiv0($m_xl1->sum('canbo_congtac')), 6),
                'qd34_2k' => 23,
                'tongqd34_2k' => 23 * $m_xl1->count(),
                'soluonggiam_2k' => $m_xl1->sum('soluonggiam_2k'),
                'quyluonggiam_2k' => $m_xl1->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl1->sum('tongquyluonggiam_2k'),
            ];

            $ar_I[2] = array('style' => '', 'tt' => '2', 'noidung' => 'Xã loại 2',);
            $m_xl2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2]['solieu'] = [
                'soluongdonvi_2k' => $m_xl2->count(),
                'qd92_2k' => 23,
                'tongqd92_2k' => 23 * $m_xl2->count(),
                'tongbienche_2k' => $m_xl2->sum('canbo_congtac'),
                'trungbinhheso_2k' => round($m_xl2->sum('st_heso') / chkDiv0($m_xl2->sum('canbo_congtac')), 6),
                'trungbinhphucap_2k' => round($m_xl2->sum('st_tongpc') / chkDiv0($m_xl2->sum('canbo_congtac')), 6),
                'trungbinhdonggop_2k' => round($m_xl2->sum('ttbh_dv') / chkDiv0($m_xl2->sum('canbo_congtac')), 6),
                'qd34_2k' => 21,
                'tongqd34_2k' => 21 * $m_xl2->count(),
                'soluonggiam_2k' => $m_xl2->sum('soluonggiam_2k'),
                'quyluonggiam_2k' => $m_xl2->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl2->sum('tongquyluonggiam_2k'),
            ];

            $ar_I[3] = array('style' => '', 'tt' => '3', 'noidung' => 'Xã loại 3',);
            $m_xl3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3]['solieu'] = [
                'soluongdonvi_2k' => $m_xl3->count(),
                'qd92_2k' => 23,
                'tongqd92_2k' => 23 * $m_xl3->count(),
                'tongbienche_2k' => $m_xl3->sum('canbo_congtac'),
                'trungbinhheso_2k' => round($m_xl3->sum('st_heso') / chkDiv0($m_xl3->sum('canbo_congtac')), 6),
                'trungbinhphucap_2k' => round($m_xl3->sum('st_tongpc') / chkDiv0($m_xl3->sum('canbo_congtac')), 6),
                'trungbinhdonggop_2k' => round($m_xl3->sum('ttbh_dv') / chkDiv0($m_xl3->sum('canbo_congtac')), 6),
                'qd34_2k' => 21,
                'tongqd34_2k' => 21 * $m_xl3->count(),
                'soluonggiam_2k' => $m_xl3->sum('soluonggiam_2k'),
                'quyluonggiam_2k' => $m_xl3->sum('quyluonggiam_2k'),
                'tongquyluonggiam_2k' => $m_xl3->sum('tongquyluonggiam_2k'),
            ];

            $ar_I[0]['solieu'] = [
                'soluongdonvi_2k' => $ar_I[1]['solieu']['soluongdonvi_2k'] + $ar_I[2]['solieu']['soluongdonvi_2k'] + $ar_I[3]['solieu']['soluongdonvi_2k'],
                'qd92_2k' => 0,
                'tongqd92_2k' => $ar_I[1]['solieu']['tongqd92_2k'] + $ar_I[2]['solieu']['tongqd92_2k'] + $ar_I[3]['solieu']['tongqd92_2k'],
                'tongbienche_2k' => $ar_I[1]['solieu']['tongbienche_2k'] + $ar_I[2]['solieu']['tongbienche_2k'] + $ar_I[3]['solieu']['tongbienche_2k'],
                'trungbinhheso_2k' => 0,
                'trungbinhphucap_2k' => 0,
                'trungbinhdonggop_2k' => 0,
                'qd34_2k' => 0,
                'tongqd34_2k' => $ar_I[1]['solieu']['tongqd34_2k'] + $ar_I[2]['solieu']['tongqd34_2k'] + $ar_I[3]['solieu']['tongqd34_2k'],
                'soluonggiam_2k' => $ar_I[1]['solieu']['soluonggiam_2k'] + $ar_I[2]['solieu']['soluonggiam_2k'] + $ar_I[3]['solieu']['soluonggiam_2k'],
                'quyluonggiam_2k' => $ar_I[1]['solieu']['quyluonggiam_2k'] + $ar_I[2]['solieu']['quyluonggiam_2k'] + $ar_I[3]['solieu']['quyluonggiam_2k'],
                'tongquyluonggiam_2k' => $ar_I[1]['solieu']['tongquyluonggiam_2k'] + $ar_I[2]['solieu']['tongquyluonggiam_2k'] + $ar_I[3]['solieu']['tongquyluonggiam_2k'],
            ];



            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2k')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 34/2019/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau2l(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();
            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }
            }

            $m_chitiet = $m_chitiet->where('nhomnhucau', 'CANBOKCT');

            //Số liệu đơn vị
            foreach ($m_nguonkp as $key => $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tính toán số liêu
                $dulieu = $m_chitiet->where('madv', $chitiet->madv);
                if ($dulieu->count() == 0) {
                    $m_nguonkp->forget($key);
                    continue;
                }

                $chitiet->canbo_congtac = $dulieu->sum('canbo_congtac');
                $chitiet->canbo_dutoan = $dulieu->sum('canbo_dutoan');
            }
            //Tính toán số liệu phần I
            $ar_I[0] = array('style' => 'font-weight: bold;', 'tt' => '', 'noidung' => 'TỔNG SỐ',);
            $ar_I[1] = array('style' => '', 'tt' => '1', 'noidung' => 'Xã loại 1',);
            $m_xl1 = $m_nguonkp->where('phanloaixa', 'XL1');
            $ar_I[1]['solieu'] = [
                'soluongdonvi_2l' => $m_xl1->count(),
                'qd92_2l' => 20.3,
                'qd34_2l' => 16,
                'tongcanbo_2l' => $m_xl1->sum('canbo_congtac'),
                'tongphucap_2l' => $m_xl1->count() * 20.3 * 1800000,
                'tongdonggop_2l' => $m_xl1->sum('canbo_congtac') * 0.14 * 1800000,
                'tongquyluong_2l' => $m_xl1->count() * 16 * 1800000,
            ];
            $ar_I[1]['solieu']['quyluonggiam_2l'] = abs(round(($ar_I[1]['solieu']['tongquyluong_2l'] - $ar_I[1]['solieu']['tongdonggop_2l'] - $ar_I[1]['solieu']['tongphucap_2l']) * 6, 6));

            $ar_I[2] = array('style' => '', 'tt' => '2', 'noidung' => 'Xã loại 2',);
            $m_xl2 = $m_nguonkp->where('phanloaixa', 'XL2');
            $ar_I[2]['solieu'] = [
                'soluongdonvi_2l' => $m_xl2->count(),
                'qd92_2l' => 18.6,
                'qd34_2l' => 13.7,
                'tongcanbo_2l' => $m_xl2->sum('canbo_congtac'),
                'tongphucap_2l' => $m_xl2->count() * 18.6 * 1800000,
                'tongdonggop_2l' => $m_xl2->sum('canbo_congtac') * 0.14 * 1800000,
                'tongquyluong_2l' => $m_xl2->count() * 13.7 * 1800000,
            ];
            $ar_I[2]['solieu']['quyluonggiam_2l'] = abs(round(($ar_I[2]['solieu']['tongquyluong_2l'] - $ar_I[2]['solieu']['tongdonggop_2l'] - $ar_I[2]['solieu']['tongphucap_2l']) * 6, 6));

            $ar_I[3] = array('style' => '', 'tt' => '3', 'noidung' => 'Xã loại 3',);
            $m_xl3 = $m_nguonkp->where('phanloaixa', 'XL3');
            $ar_I[3]['solieu'] = [
                'soluongdonvi_2l' => $m_xl3->count(),
                'qd92_2l' => 17.6,
                'qd34_2l' => 11.4,
                'tongcanbo_2l' => $m_xl3->sum('canbo_congtac'),
                'tongphucap_2l' => $m_xl3->count() * 17.6 * 1800000,
                'tongdonggop_2l' => $m_xl3->sum('canbo_congtac') * 0.14 * 1800000,
                'tongquyluong_2l' => $m_xl3->count() * 11.4 * 1800000,
            ];
            $ar_I[3]['solieu']['quyluonggiam_2l'] = abs(round(($ar_I[3]['solieu']['tongquyluong_2l'] - $ar_I[3]['solieu']['tongdonggop_2l'] - $ar_I[3]['solieu']['tongphucap_2l']) * 6, 6));


            $ar_I[0]['solieu'] = [
                'soluongdonvi_2l' => $ar_I[1]['solieu']['soluongdonvi_2l'] + $ar_I[2]['solieu']['soluongdonvi_2l'] + $ar_I[3]['solieu']['soluongdonvi_2l'],
                'qd92_2l' => 0,
                'qd34_2l' => 0,
                'tongcanbo_2l' => $ar_I[1]['solieu']['tongcanbo_2l'] + $ar_I[2]['solieu']['tongcanbo_2l'] + $ar_I[3]['solieu']['tongcanbo_2l'],
                'tongphucap_2l' => $ar_I[1]['solieu']['tongphucap_2l'] + $ar_I[2]['solieu']['tongphucap_2l'] + $ar_I[3]['solieu']['tongphucap_2l'],
                'tongdonggop_2l' => $ar_I[1]['solieu']['tongdonggop_2l'] + $ar_I[2]['solieu']['tongdonggop_2l'] + $ar_I[3]['solieu']['tongdonggop_2l'],
                'tongquyluong_2l' => $ar_I[1]['solieu']['tongquyluong_2l'] + $ar_I[2]['solieu']['tongquyluong_2l'] + $ar_I[3]['solieu']['tongquyluong_2l'],
                'quyluonggiam_2l' => $ar_I[1]['solieu']['quyluonggiam_2l'] + $ar_I[2]['solieu']['quyluonggiam_2l'] + $ar_I[3]['solieu']['quyluonggiam_2l'],
            ];



            //dd($m_tonghop_ct);
            return view('reports.thongtu78.huyen.mau2l')
                ->with('furl', '/tong_hop_bao_cao/')
                ->with('ar_I', $ar_I)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Tổng hợp kinh phí giảm  theo nghị định số 34/2019/NĐ-CP');
        } else
            return view('errors.notlogin');
    }

    function mau4a(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {

            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');
            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloainguon = array_column($m_dsdv->toArray(), 'phanloainguon', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];

                $chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                $chitiet->tongnhucau = ($chitiet->ttbh_dv + $chitiet->ttl) * 6;
            }

            //Số liệu đơn vị
            foreach ($m_nguonkp as $chitiet) {
                //Tinh số liệu 2b
                //BH=tongsonguoi1 * 0.1 * 4,5% (đơn vị: Triệu đồng) 
                $chitiet->nhucau2b = round(($chitiet->quy2_1 - $chitiet->quy1_1 + $chitiet->tongsonguoi1 * 450000) * 6) +
                    round(($chitiet->quy2_2 - $chitiet->quy1_2 + $chitiet->tongsonguoi2 * 450000) * 6) +
                    round(($chitiet->quy2_3 - $chitiet->quy1_3 + $chitiet->tongsonguoi3 * 450000) * 6);
            }

            //Phần A
            $a_A = get4a_TT50_A();

            for ($capdo = 0; $capdo < 5; $capdo++) {
                foreach ($a_A as $key => $chitiet) {
                    if ($chitiet['phanloai'] == $capdo) {
                        if (!is_array($chitiet['tentruong'])) {
                            $a_A[$key]['sotien'] = $m_nguonkp->sum($chitiet['tentruong']);
                        } else {
                            foreach ($chitiet['tentruong'] as $k) {
                                $a_A[$key]['sotien'] += $a_A[$k]['sotien'];
                            }
                        }
                    }
                }
            }

            //dd($a_A);
            //Phần B
            $a_BI = array();
            $a_BI[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BI[1] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BI[2] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BI[3] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 44/2023/NĐ-CP', 'sotien' => '0');
            $a_BI[4] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BI[5] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BI[6] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 09-QĐ/VVPTW ngày 22/9/2021', 'sotien' => '0');


            $a_BI[0]['sotien'] = $m_chitiet->where('nhomnhucau', 'BIENCHE')->sum('tongnhucau');
            $a_BI[1]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOCT')->sum('tongnhucau');
            $a_BI[2]['sotien'] = $m_chitiet->where('nhomnhucau', 'HDND')->sum('tongnhucau');
            $a_BI[3]['sotien'] = $m_nguonkp->sum('nhucau2b'); //Lấy dữ liệu mẫu 2b
            $a_BI[4]['sotien'] = $m_chitiet->where('nhomnhucau', 'CANBOKCT')->sum('tongnhucau');
            $a_BI[5]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->wherein('level', ['XA', 'HUYEN'])->sum('tongnhucau');
            $a_BI[6]['sotien'] = $m_chitiet->where('nhomnhucau', 'CAPUY')->where('level', 'TINH')->sum('tongnhucau');

            // dd($m_chitiet->where('nhomnhucau', 'CAPUY'));

            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Phụ cấp Ưu đãi nghề đối với công chức viên chức tại các cơ sở y tế', 'sotien' => '0');
            $a_BII[1] = array('tt' => '2', 'noidung' => 'Kinh phí thực hiện chính sách tinh giản biên chế năm 2023', 'sotien' => '0');
            $a_BII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2023 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');
            $a_BII[3] = array('tt' => '4', 'noidung' => 'Nhu cầu kinh phí trả thực hiện chế độ thù lao đối với người đã nghỉ hưu lanh đạo Hội đặc thù', 'sotien' => '0');
            $a_BII[4] = array('tt' => '5', 'noidung' => 'Nhu cầu kinh phí tăng thêm thực hiện chế độ trợ cấp lần đầu nhận công tác vùng ĐBKK', 'sotien' => '0');
            $a_BII[5] = array('tt' => '6', 'noidung' => 'Kinh phí tăng/giảm do thực hiện Nghị định số 33/2023/NĐ-CP', 'sotien' => '0');


            $a_BII[0]['sotien'] = $m_nguonkp->sum('kpthuhut');
            $a_BII[1]['sotien'] = $m_nguonkp->sum('tinhgiam');
            $a_BII[2]['sotien'] = $m_nguonkp->sum('nghihuusom');
            $a_BII[3]['sotien'] = $m_nguonkp->sum('kpuudai');
            $a_BII[4]['sotien'] = $m_nguonkp->sum('kinhphigiamxa_4a');
            $a_BII[5]['sotien'] = $m_nguonkp->sum('nhucau');

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[6]['sotien']),
                'BI' => array_sum(array_column($a_BI, 'sotien')),
                'BII' => array_sum(array_column($a_BII, 'sotien'))
            );
            // dd($a_A);

            return view('reports.thongtu78.huyen.mau4a')
                ->with('model', $m_nguonkp)
                ->with('a_A', $a_A)
                ->with('a_BI', $a_BI)
                ->with('a_BII', $a_BII)
                ->with('a_TC', $a_TC)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //$chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                $chitiet->tongnhucau = ($chitiet->ttbh_dv + $chitiet->ttl) * 6;
            }

            //Số liệu đơn vị
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tinh số liệu 2b
                //BH=tongsonguoi1 * 0.1 * 4,5% (đơn vị: Triệu đồng) 
                $chitiet->nhucau2b = round(($chitiet->quy2_1 - $chitiet->quy1_1 + $chitiet->tongsonguoi1 * 450000) * 6) +
                    round(($chitiet->quy2_2 - $chitiet->quy1_2 + $chitiet->tongsonguoi2 * 450000) * 6) +
                    round(($chitiet->quy2_3 - $chitiet->quy1_3 + $chitiet->tongsonguoi3 * 450000) * 6);
            }
            // dd($m_nguonkp);
            $data = array();
            $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            //
            $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'GD')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'GD')->where('maphanloai', '<>', 'KVXP');

            $data[1]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[1]['solieu']['tongso'] = $data[1]['solieu']['tietkiem'] + $data[1]['solieu']['hocphi'] + $data[1]['solieu']['vienphi'] + $data[1]['solieu']['nguonthu'];
            //dd($data);
            //
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'DT')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'DT')->where('maphanloai', '<>', 'KVXP');
            $data[2]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[2]['solieu']['tongso'] = $data[2]['solieu']['tietkiem'] + $data[2]['solieu']['hocphi'] + $data[2]['solieu']['vienphi'] + $data[2]['solieu']['nguonthu'];
            //Dòng 0
            $data[0]['solieu'] = [
                'nhucau' => $data[2]['solieu']['nhucau'] + $data[1]['solieu']['nhucau'],
                'tietkiem' => $data[2]['solieu']['tietkiem'] + $data[1]['solieu']['tietkiem'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $data[2]['solieu']['hocphi'] + $data[1]['solieu']['hocphi'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $data[2]['solieu']['vienphi'] + $data[1]['solieu']['vienphi'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $data[2]['solieu']['nguonthu'] + $data[1]['solieu']['nguonthu'], //Lấy tiết kiệm 2023 ở mẫu 4a                
                'tongso' => $data[2]['solieu']['tongso'] + $data[1]['solieu']['tongso'], //Lấy 50% tổng tiết kiệm ở mẫu 2đ

            ];
            //
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'YTE')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'YTE')->where('maphanloai', '<>', 'KVXP');
            $data[3]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[3]['solieu']['tongso'] = $data[3]['solieu']['tietkiem'] + $data[3]['solieu']['hocphi'] + $data[3]['solieu']['vienphi'] + $data[3]['solieu']['nguonthu'];
            //
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP');
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOCT');

            $m_bl = $m_chitiet->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP');
            $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOCT');

            $data[4]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem') + $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a') + $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a') + $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a') + $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a

            ];
            $data[4]['solieu']['tongso'] = $data[4]['solieu']['tietkiem'] + $data[4]['solieu']['hocphi'] + $data[4]['solieu']['vienphi']
                + $data[4]['solieu']['nguonthu'];

            //Quản lý nhà nước + Biên chế xã + Các cán bộ đã nghỉ hưu (2b)
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể',);
            $m_data = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP');
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            $m_bl = $m_chitiet->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP');
            $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            $data[5]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau') + $m_nguonkp->sum('nhucau2b'),
                'tietkiem' => $m_data->sum('tietkiem') + $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongktx_hocphi_4a') + $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongktx_vienphi_4a') + $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongktx_khac_4a') + $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
            ];

            $data[5]['solieu']['tongso'] = $data[5]['solieu']['tietkiem'] + $data[5]['solieu']['hocphi'] + $data[5]['solieu']['vienphi'] + $data[5]['solieu']['nguonthu'];
            //
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã',);
            $data[6]['solieu'] = [
                'nhucau' => $m_bl2->sum('tongnhucau'),
                'tietkiem' => $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data2->sum('huydongktx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' =>  $m_data2->sum('huydongktx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data2->sum('huydongktx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
            ];
            $data[6]['solieu']['tongso'] = $data[6]['solieu']['tietkiem'] + $data[6]['solieu']['hocphi'] + $data[6]['solieu']['vienphi'] + $data[6]['solieu']['nguonthu'];

            //dd($data);
            $inputs['donvitinh'] = 1;
            return view('reports.thongtu78.huyen.mau4b')
                //->with('model', $model)
                // ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function mau4b_20230809(Request $request)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_nguonkp = nguonkinhphi::where('masodv', $inputs['masodv'])->get();
            $m_donvi = dmdonvi::where('madv', $m_nguonkp->first()->madv)->first();
            $inputs['donvitinh'] = 1;

            // $m_thongtu = dmthongtuquyetdinh::where('sohieu', $m_nguonkp->first()->sohieu)->first();

            $a_linhvuc = array_column($m_nguonkp->toarray(), 'linhvuchoatdong', 'masodv');
            $a_donvi =  array_column($m_nguonkp->toarray(), 'madv', 'masodv');

            $m_dsdv = dmdonvi::all();
            $a_level = array_column($m_dsdv->toArray(), 'caphanhchinh', 'madv');
            $a_phanloai = array_column($m_dsdv->toArray(), 'maphanloai', 'madv');
            $a_phanloaixa = array_column($m_dsdv->toArray(), 'phanloaixa', 'madv');
            $a_madvbc = array_column($m_dsdv->toArray(), 'madvbc', 'madv');
            $a_thongtindv = array_column($m_dsdv->toArray(), 'tendv', 'madv');

            $m_chitiet = nguonkinhphi_01thang::wherein('masodv', array_column($m_nguonkp->toarray(), 'masodv'))->get();

            $m_plct = dmphanloaict::all();
            $a_nhomplct_hc = array_column($m_plct->toArray(), 'nhomnhucau_hc', 'mact');
            $a_nhomplct_xp = array_column($m_plct->toArray(), 'nhomnhucau_xp', 'mact');
            //Số liệu chi tiết
            foreach ($m_chitiet as $chitiet) {
                $chitiet->madv = $a_donvi[$chitiet->masodv];
                //$chitiet->phanloainguon = $a_phanloainguon[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->linhvuchoatdong = $a_linhvuc[$chitiet->masodv];
                $chitiet->level = $a_level[$chitiet->madv];

                if ($chitiet->maphanloai == 'KVXP') {
                    $chitiet->nhomnhucau = $a_nhomplct_xp[$chitiet->mact];
                } else {
                    $chitiet->nhomnhucau = $a_nhomplct_hc[$chitiet->mact];
                }

                $chitiet->tongnhucau = ($chitiet->ttbh_dv + $chitiet->ttl) * 6;
            }

            //Số liệu đơn vị
            foreach ($m_nguonkp as $chitiet) {
                $chitiet->phanloaixa = $a_phanloaixa[$chitiet->madv];
                $chitiet->maphanloai = $a_phanloai[$chitiet->madv];
                $chitiet->level = $a_level[$chitiet->madv];
                $chitiet->madvbc = $a_madvbc[$chitiet->madv];
                $chitiet->tendv = $a_thongtindv[$chitiet->madv];

                //Tinh số liệu 2b
                //BH=tongsonguoi1 * 0.1 * 4,5% (đơn vị: Triệu đồng) 
                $chitiet->nhucau2b = round(($chitiet->quy2_1 - $chitiet->quy1_1 + $chitiet->tongsonguoi1 * 450000) * 6) +
                    round(($chitiet->quy2_2 - $chitiet->quy1_2 + $chitiet->tongsonguoi2 * 450000) * 6) +
                    round(($chitiet->quy2_3 - $chitiet->quy1_3 + $chitiet->tongsonguoi3 * 450000) * 6);
            }
            // dd($m_nguonkp);
            $data = array();
            $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo');
            //
            $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'GD')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'GD')->where('maphanloai', '<>', 'KVXP');

            $data[1]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongtx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongtx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongtx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => round($m_data->sum('quyluongtietkiem_2dd') / 2), //Lấy 50% tổng tiết kiệm ở mẫu 2đ
            ];
            $data[1]['solieu']['tongso'] = $data[1]['solieu']['tietkiem'] + $data[1]['solieu']['hocphi'] + $data[1]['solieu']['vienphi'] + $data[1]['solieu']['nguonthu'] + $data[1]['solieu']['quyluongtietkiem'];
            //dd($data);
            //
            $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo',);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'DT')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'DT')->where('maphanloai', '<>', 'KVXP');
            $data[2]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongtx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongtx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongtx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => round($m_data->sum('quyluongtietkiem_2dd') / 2), //Lấy 50% tổng tiết kiệm ở mẫu 2đ
            ];
            $data[2]['solieu']['tongso'] = $data[2]['solieu']['tietkiem'] + $data[2]['solieu']['hocphi'] + $data[2]['solieu']['vienphi'] + $data[2]['solieu']['nguonthu'] + $data[2]['solieu']['quyluongtietkiem'];
            //Dòng 0
            $data[0]['solieu'] = [
                'nhucau' => $data[2]['solieu']['nhucau'] + $data[1]['solieu']['nhucau'],
                'tietkiem' => $data[2]['solieu']['tietkiem'] + $data[1]['solieu']['tietkiem'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $data[2]['solieu']['hocphi'] + $data[1]['solieu']['hocphi'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $data[2]['solieu']['vienphi'] + $data[1]['solieu']['vienphi'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $data[2]['solieu']['nguonthu'] + $data[1]['solieu']['nguonthu'], //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => $data[2]['solieu']['quyluongtietkiem'] + $data[1]['solieu']['quyluongtietkiem'], //Lấy 50% tổng tiết kiệm ở mẫu 2đ
                'tongso' => $data[2]['solieu']['tongso'] + $data[1]['solieu']['tongso'], //Lấy 50% tổng tiết kiệm ở mẫu 2đ

            ];
            //
            $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->where('linhvuchoatdong', 'YTE')->where('maphanloai', '<>', 'KVXP');
            $m_bl = $m_chitiet->where('linhvuchoatdong', 'YTE')->where('maphanloai', '<>', 'KVXP');
            $data[3]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongtx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongtx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongtx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => round($m_data->sum('quyluongtietkiem_2dd') / 2), //Lấy 50% tổng tiết kiệm ở mẫu 2đ
            ];
            $data[3]['solieu']['tongso'] = $data[3]['solieu']['tietkiem'] + $data[3]['solieu']['hocphi'] + $data[3]['solieu']['vienphi'] + $data[3]['solieu']['nguonthu'] + $data[3]['solieu']['quyluongtietkiem'];
            //
            $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'khac' => 0, 'nguonthu' => 0);
            $m_data = $m_nguonkp->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP');
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            $m_bl = $m_chitiet->wherein('linhvuchoatdong', ['QLNN', 'DDT'])->where('maphanloai', '<>', 'KVXP');
            $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->wherein('nhomnhucau', ['HDND', 'CAPUY']);

            $data[4]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau'),
                'tietkiem' => $m_data->sum('tietkiem') + $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongtx_hocphi_4a') + $m_data2->sum('huydongtx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongtx_vienphi_4a') + $m_data2->sum('huydongtx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongtx_khac_4a') + $m_data2->sum('huydongtx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => round(($m_data->sum('quyluongtietkiem_2dd') + $m_data2->sum('quyluongtietkiem_2dd')) / 2), //Lấy 50% tổng tiết kiệm ở mẫu 2đ
            ];
            $data[4]['solieu']['tongso'] = $data[4]['solieu']['tietkiem'] + $data[4]['solieu']['hocphi'] + $data[4]['solieu']['vienphi']
                + $data[4]['solieu']['nguonthu'] + $data[4]['solieu']['quyluongtietkiem'];

            //Quản lý nhà nước + Biên chế xã + Các cán bộ đã nghỉ hưu (2b)
            $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể',);
            $m_data = $m_nguonkp->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP');
            $m_data2 = $m_nguonkp->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOCT');

            $m_bl = $m_chitiet->wherenotin('linhvuchoatdong', ['QLNN', 'DDT', 'YTE', 'GD', 'DT'])->where('maphanloai', '<>', 'KVXP');
            $m_bl2 = $m_chitiet->where('maphanloai', 'KVXP')->where('nhomnhucau', 'CANBOCT');
            $data[5]['solieu'] = [
                'nhucau' => $m_bl->sum('tongnhucau') + $m_bl2->sum('tongnhucau') + $m_nguonkp->sum('nhucau2b'),
                'tietkiem' => $m_data->sum('tietkiem') + $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data->sum('huydongtx_hocphi_4a') + $m_data2->sum('huydongtx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' => $m_data->sum('huydongtx_vienphi_4a') + $m_data2->sum('huydongtx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data->sum('huydongtx_khac_4a') + $m_data2->sum('huydongtx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => round(($m_data->sum('quyluongtietkiem_2dd') + $m_data2->sum('quyluongtietkiem_2dd')) / 2), //Lấy 50% tổng tiết kiệm ở mẫu 2đ
            ];

            $data[5]['solieu']['tongso'] = $data[5]['solieu']['tietkiem'] + $data[5]['solieu']['hocphi'] + $data[5]['solieu']['vienphi'] + $data[5]['solieu']['nguonthu'] + $data[5]['solieu']['quyluongtietkiem'];
            //
            $data[6] = array('val' => 'QLNN', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã',);
            $data[6]['solieu'] = [
                'nhucau' => $m_bl2->sum('tongnhucau'),
                'tietkiem' => $m_data2->sum('tietkiem'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'hocphi' => $m_data2->sum('huydongtx_hocphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'vienphi' =>  $m_data2->sum('huydongtx_vienphi_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'nguonthu' => $m_data2->sum('huydongtx_khac_4a'), //Lấy tiết kiệm 2023 ở mẫu 4a
                'quyluongtietkiem' => round($m_data2->sum('quyluongtietkiem_2dd') / 2), //Lấy 50% tổng tiết kiệm ở mẫu 2đ
            ];
            $data[6]['solieu']['tongso'] = $data[6]['solieu']['tietkiem'] + $data[6]['solieu']['hocphi'] + $data[6]['solieu']['vienphi'] + $data[6]['solieu']['nguonthu'] + $data[6]['solieu']['quyluongtietkiem'];

            //dd($data);
            $inputs['donvitinh'] = 1;
            return view('reports.thongtu78.huyen.mau4b')
                //->with('model', $model)
                // ->with('model_thongtu', $model_thongtu)
                ->with('data', $data)
                ->with('m_dv', $m_donvi)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }
}
