<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\dmdiabandbkk;
use App\dmdiabandbkk_chitiet;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\hosocanbo;
use App\tonghopluong_donvi;
use App\tonghopluong_donvi_chitiet;
use App\tonghopluong_donvi_diaban;
use App\tonghopluong_huyen;
use App\tonghopluong_huyen_chitiet;
use App\tonghopluong_huyen_diaban;
use App\tonghopluong_tinh;
use App\tonghopluong_tinh_chitiet;
use App\tonghopluong_tinh_diaban;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopluong_donviController extends Controller
{
    function index(Request $requests)
    {
        if (Session::has('admin')) {
            //text-danger; text-warning; text-success
            $a_data = array(array('thang' => '01', 'mathdv' => null),
                array('thang' => '02', 'mathdv' => null),
                array('thang' => '03', 'mathdv' => null),
                array('thang' => '04', 'mathdv' => null),
                array('thang' => '05', 'mathdv' => null),
                array('thang' => '06', 'mathdv' => null),
                array('thang' => '07', 'mathdv' => null),
                array('thang' => '08', 'mathdv' => null),
                array('thang' => '09', 'mathdv' => null),
                array('thang' => '10', 'mathdv' => null),
                array('thang' => '11', 'mathdv' => null),
                array('thang' => '12', 'mathdv' => null)
            );
            /*
            $a_trangthai=array('CHUALUONG'=>'Chưa tạo bảng lương','CHUATAO'=>'Chưa tổng hợp dữ liệu'
                ,'CHOGUI'=>'Chưa gửi dữ liệu','DAGUI'=>'Đã gửi dữ liệu','TRALAI'=>'Trả lại dữ liệu');
            */
            $a_trangthai = getStatus();
            $inputs = $requests->all();
            $model = tonghopluong_donvi::where('madv', session('admin')->madv)->get();
            $model_bangluong = bangluong::where('madv', session('admin')->madv)->get();
            for ($i = 0; $i < count($a_data); $i++) {
                $a_data[$i]['maphanloai'] = session('admin')->maphanloai;
                $tonghop = $model->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam'])->first();
                $bangluong = $model_bangluong->where('thang', $a_data[$i]['thang'])->where('nam', $inputs['nam']);
                if (count($bangluong) > 0) {
                    $a_data[$i]['bangluong'] = 'ok';
                    if (count($tonghop) > 0) {
                        $a_data[$i]['noidung'] = $tonghop->noidung;
                        $a_data[$i]['mathdv'] = $tonghop->mathdv;
                        $a_data[$i]['trangthai'] = $tonghop->trangthai;

                    } else {
                        $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $a_data[$i]['thang'] . '/' . $inputs['nam'];
                        $a_data[$i]['trangthai'] = 'CHUATAO';
                    }
                } else {
                    $a_data[$i]['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $a_data[$i]['thang'] . '/' . $inputs['nam'];
                    $a_data[$i]['bangluong'] = null;
                    $a_data[$i]['trangthai'] = 'CHUALUONG';
                }
            }
            //dd($a_data);
            return view('functions.tonghopluong.donvi.index')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('nam', $inputs['nam'])
                ->with('model', $a_data)
                ->with('a_trangthai', $a_trangthai)
                ->with('pageTitle', 'Danh sách tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests)
    {
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $thang = $inputs['thang'];
            $nam = $inputs['nam'];
            $mathdv = getdate()[0];
            $madv = session('admin')->madv;

            //lấy bảng lương
            $model_bangluong = bangluong::where('nam', $nam)->where('thang', $thang)->where('madv', $madv)->get();
            //bảng lương chi tiết
            $model_bangluong_ct = bangluong_ct::wherein('mabl', function ($query) use ($nam, $thang, $madv) {
                $query->select('mabl')->from('bangluong')->where('nam', $nam)->where('thang', $thang)->where('madv', $madv);
            })->get();
            $model_congtac = dmphanloaict::all();
            $model_hoso = hosocanbo::where('madv', $madv)->get();
            //$model_diaban = dmdiabandbkk::where('madv',$madv)->get();
            /*
            $model_diaban_ct = dmdiabandbkk_chitiet::wherein('madiaban', function ($query) use ($madv) {
                $query->select('madiaban')->from('dmdiabandbkk')->where('madv', $madv)->where('phanloai', '<>', '');
            })->get();
            */
            //Lấy dữ liệu từ các bảng liên quan thêm vào bảng lương chi tiết để tính toán
            foreach ($model_bangluong_ct as $ct) {
                $hoso = $model_hoso->where('macanbo',$ct->macanbo)->first();
                $ct->mact = $hoso->mact;
                $bangluong = $model_bangluong->where('mabl', $ct->mabl)->first();
                $ct->luongcoban = $bangluong->luongcoban;
                $ct->manguonkp = $bangluong->manguonkp;
                $ct->linhvuchoatdong = $bangluong->linhvuchoatdong;//chỉ dùng cho khối HCSN

                $congtac = $model_congtac->where('mact', $ct->mact)->first();
                $ct->macongtac = $congtac->macongtac;
                /*
                $diaban_ct = $model_diaban_ct->where('macanbo', $ct->macanbo)->first();
                if (count($diaban_ct) > 0) {
                    //$diaban = $model_diaban->where('madiaban',$diaban_ct->madiaban)->first();
                    $ct->madiaban = $diaban_ct->madiaban;
                } else {
                    $ct->madiaban = null;
                }
                */
            }
            //
            //Lấy dữ liệu để lập
            $model_data = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['macongtac', 'linhvuchoatdong', 'manguonkp', 'luongcoban'])
                    ->all();
            });
            $model_data = a_unique($model_data);
            //
            //Tính toán dữ liệu
            $model_pc = dmphucap_donvi::where('madv', $madv)->where('phanloai', '<', '3')->get();
            $a_phucap = array();

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;

            }

            for ($i = 0; $i < count($model_data); $i++) {
                $luongct = $model_bangluong_ct->where('manguonkp', $model_data[$i]['manguonkp'])
                    ->where('linhvuchoatdong', $model_data[$i]['linhvuchoatdong'])
                    ->where('macongtac', $model_data[$i]['macongtac'])
                    ->where('luongcoban', $model_data[$i]['luongcoban']);

                $tonghs = 0;
                $model_data[$i]['mathdv'] = $mathdv;

                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                $model_data[$i]['hesopc'] = $luongct->sum('hesopc') * $model_data[$i]['luongcoban'];
                foreach ($model_pc as $ct) {
                    if($ct->phanloai == 1){
                        $model_data[$i][$ct->mapc] = $luongct->sum($ct->mapc);
                    }else{
                        $model_data[$i][$ct->mapc] = $luongct->sum($ct->mapc) * $model_data[$i]['luongcoban'];
                    }
                    $a_phucap[$ct->mapc] = $ct->report;
                    $tonghs += chkDbl($model_data[$i][$ct->mapc]);
                }

                $model_data[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_data[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_data[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_data[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');

                $model_data[$i]['tonghs'] = $tonghs;
            }
            /*

            //Tính toán theo địa bàn
            $model_diaban = $model_bangluong_ct->map(function ($data) {
                return collect($data->toArray())
                    ->only(['madiaban', 'luongcoban'])
                    ->all();
            });
            $model_diaban = a_unique($model_diaban);

            for ($i = 0; $i < count($model_diaban); $i++) {
                $luongct = $model_bangluong_ct->where('madiaban', $model_diaban[$i]['madiaban'])
                    ->where('luongcoban', $model_diaban[$i]['luongcoban']);

                $tonghs = 0;
                $model_diaban[$i]['mathdv'] = $mathdv;

                //hệ số phụ cấp cho cán bộ đã nghỉ hưu
                $model_diaban[$i]['hesopc'] = $luongct->sum('hesopc') * $model_diaban[$i]['luongcoban'];
                foreach ($a_col as $col) {
                    $model_diaban[$i][$col] = $luongct->sum($col) * $model_diaban[$i]['luongcoban'];
                    $tonghs += chkDbl($model_diaban[$i][$col]);
                }

                $model_diaban[$i]['stbhxh_dv'] = $luongct->sum('stbhxh_dv');
                $model_diaban[$i]['stbhyt_dv'] = $luongct->sum('stbhyt_dv');
                $model_diaban[$i]['stkpcd_dv'] = $luongct->sum('stkpcd_dv');
                $model_diaban[$i]['stbhtn_dv'] = $luongct->sum('stbhtn_dv');
                $model_diaban[$i]['tonghs'] = $tonghs;
            }
            //
                */
            //Thêm báo cáo tổng hợp
            $inputs['madv'] = session('admin')->madv;
            $inputs['mathdv'] = $mathdv;
            $inputs['trangthai'] = 'CHOGUI';
            $inputs['phanloai'] = 'DONVI';
            $inputs['noidung'] = 'Dữ liệu tổng hợp của ' . getTenDV(session('admin')->madv) . ' thời điểm ' . $inputs['thang'] . '/' . $inputs['nam'];
            $inputs['nguoilap'] = session('admin')->name;
            $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['madvbc'] = session('admin')->madvbc;
            /*
            $model_db = array();
            for ($i = 0; $i < count($model_diaban); $i++) {
                if ($model_diaban[$i]['madiaban'] != null) {
                    $model_db[] = $model_diaban[$i];
                }
            }
            */
            tonghopluong_donvi_chitiet::insert($model_data);
            //tonghopluong_donvi_diaban::insert($model_db);
            tonghopluong_donvi::create($inputs);
            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.templates.detail')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('model_thongtin', $model_thongtin)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model = tonghopluong_donvi_chitiet::where('mathdv', $inputs['mathdv'])
                ->where('manguonkp', $inputs['manguonkp'])
                ->where('macongtac', $inputs['macongtac'])->first();
            $model->tongpc = $model->tonghs - $model->heso - $model->hesopc;
            $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_detail')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                //->with('model_thongtin',$model_thongtin)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function store_detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi_chitiet::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);


            foreach (array_keys($inputs) as $key) {
                if (!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $inputs['tonghs'] = $inputs['tongpc'] + $inputs['heso'] + $inputs['hesopc'];

            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail/ma_so=' . $model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function detail_diaban($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_diaban::where('mathdv', $mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv', session('admin')->madv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $a_diaban = getDiaBan(false);

            foreach ($model as $chitiet) {
                $diaban = $model_diaban->where('madiaban', $chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            return view('functions.tonghopluong.templates.detail_diaban')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('model_thongtin', $model_thongtin)
                ->with('pageTitle', 'Chi tiết tổng hợp lương theo địa bàn tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function edit_detail_diaban(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_diaban = array_column(dmdiabandbkk::where('madv', session('admin')->madv)->get()->toarray(), 'tendiaban', 'madiaban');
            $model = tonghopluong_donvi_diaban::where('mathdv', $inputs['mathdv'])
                ->where('madiaban', $inputs['madiaban'])->first();

            $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

            return view('functions.tonghopluong.templates.edit_diaban')
                ->with('furl', '/chuc_nang/tong_hop_luong/don_vi/')
                ->with('model', $model)
                ->with('a_diaban', $a_diaban)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function store_detail_diaban(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = tonghopluong_donvi_diaban::findorfail($inputs['id']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
            unset($inputs['id']);
            unset($inputs['_token']);

            foreach (array_keys($inputs) as $key) {
                if (!strpos($key, 'st') || !strpos($key, 'pc') || !strpos($key, 'heso')) {
                    $inputs[$key] = chkDbl($inputs[$key]);
                }
            }
            $inputs['tonghs'] = $inputs['tongpc'] + $inputs['heso'] + $inputs['hesopc'];
            $model->update($inputs);

            return redirect('/chuc_nang/tong_hop_luong/don_vi/detail_diaban/ma_so=' . $model->mathdv);
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        //Kiểm tra xem đơn vị có đơn vị chủ quản => ko cần update đi đâu chỉ cần chuyên trạng thái
        //Không đơn vị chủ quản, tùy xem thuộc huyện, tỉnh để update lên bang tonghop_huyen, tonghop_tinh
        if (Session::has('admin')) {
            $inputs = $requests->all();

            if (!session('admin')->quanlykhuvuc && session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();

            //dd(session('admin')->macqcq == session('admin')->madvqlkv);
            if(session('admin')->macqcq == session('admin')->madvqlkv){//đơn vị chủ quản là huyện
                //kiểm tra xem đã có bản ghi chưa (trường hợp trả lại)
                $model_huyen = tonghopluong_huyen::where('mathdv', $model->mathh)->first();
                if(count($model_huyen) == 0){
                    $masoh = getdate()[0];
                    $model->mathh = $masoh;

                    $inputs['thang'] = $model->thang;
                    $inputs['nam'] = $model->nam;
                    $inputs['madv'] = $model->madv;
                    $inputs['mathdv'] = $masoh;
                    $inputs['trangthai'] = 'DAGUI';
                    $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu chi trả lương.';
                    $inputs['nguoilap'] = session('admin')->name;
                    $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                    $inputs['macqcq'] = session('admin')->macqcq;
                    $inputs['madvbc'] = session('admin')->madvbc;
                    tonghopluong_huyen::create($inputs);
                }else{
                    $model_huyen->trangthai = 'DAGUI';
                    $model_huyen->nguoilap = session('admin')->name;
                    $model_huyen->ngaylap = Carbon::now()->toDateTimeString();
                    $model_huyen->save();
                }
            }

            $model->nguoigui = session('admin')->name;
            $model->ngaygui = Carbon::now()->toDateTimeString();
            $model->trangthai = 'DAGUI';
            $model->save();

            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function printf_data($mathdv)
    {
        if (Session::has('admin')) {
            //dd($mathdv);
            $model = tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $model_phanloaict = array_column(dmphanloaicongtac::all()->toArray(), 'tencongtac', 'macongtac');
            //$gnr = getGeneralConfigs();

            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();

            foreach ($model as $chitiet) {
                $chitiet->tennguonkp = isset($model_nguonkp[$chitiet->manguonkp]) ? $model_nguonkp[$chitiet->manguonkp] : '';
                $chitiet->tencongtac = isset($model_phanloaict[$chitiet->macongtac]) ? $model_phanloaict[$chitiet->macongtac] : '';
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            return view('reports.tonghopluong.donvi.solieutonghop')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function printf_data_diaban($mathdv)
    {
        if (Session::has('admin')) {
            $model = tonghopluong_donvi_diaban::where('mathdv', $mathdv)->get();
            $model_diaban = dmdiabandbkk::where('madv', session('admin')->madv)->get();
            $model_thongtin = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $a_diaban = getDiaBan(false);
            //$gnr=getGeneralConfigs();

            foreach ($model as $chitiet) {
                $diaban = $model_diaban->where('madiaban', $chitiet->madiaban)->first();
                $chitiet->tendiaban = $diaban->tendiaban;
                $chitiet->phanloai = $a_diaban[$diaban->phanloai];
                $chitiet->tongtl = $chitiet->tonghs;
                $chitiet->tongbh = $chitiet->stbhxh_dv + $chitiet->stbhyt_dv + $chitiet->stkpcd_dv + $chitiet->stbhtn_dv;
            }
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();

            $thongtin = array('nguoilap' => session('admin')->name,
                'thang' => $model_thongtin->thang,
                'nam' => $model_thongtin->nam);

            return view('reports.tonghopluong.donvi.solieudiaban')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Chi tiết tổng hợp lương tại đơn vị theo địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = tonghopluong_donvi::where('mathdv', $inputs['mathdv'])->first();
            tonghopluong_donvi::where('mathdv', $inputs['mathdv'])
                ->update(['trangthai' => 'TRALAI', 'lydo' => $inputs['lydo']]);
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

        $model = tonghopluong_donvi::select('lydo')->where('mathdv', $inputs['mathdv'])->first();

        die($model);
    }

    function destroy($mathdv)
    {
        if (Session::has('admin')) {
            tonghopluong_donvi_chitiet::where('mathdv', $mathdv)->delete();
            $model = tonghopluong_donvi::where('mathdv', $mathdv)->first();
            $model->delete();
            //tonghopluong_donvi::where('mathdv',$mathdv)->delete();
            return redirect('/chuc_nang/tong_hop_luong/don_vi/index?nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }
}
