<?php

namespace App\Http\Controllers;

//use App\bangluong_ct;
//use App\bangluong_phucap;
//use App\bangluongdangky_ct;
//use App\bangluongdangky_phucap;
use App\dmchucvucq;
use App\dmchucvud;
use App\dmdantoc;
use App\dmdonvi;
use App\dmkhoipb;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\hosocanbo_kiemnhiem_temp;
use App\hosochucvu;
//use App\hosothoicongtac;
use App\hosotinhtrangct;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class hosocanboController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            //$m_hs=hosocanbo::where('madv',session('admin')->maxa)->get();
            $m_hs = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            $a_tb = getPhanLoaiCanBo(false);
            foreach ($m_hs as $hs) {
                $hs->tenpb = isset($a_pb[$hs->mapb]) ? $a_pb[$hs->mapb] : '';
                $hs->tencvcq = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq] : '';
                $hs->tenct = isset($a_ct[$hs->mact]) ? $a_ct[$hs->mact] : '';
                $hs->tentd = isset($a_tb[$hs->theodoi]) ? $a_tb[$hs->theodoi] : '';
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();

            $model = $m_hs->sortBy('stt');
            return view('manage.hosocanbo.index')
                ->with('model', $model)
                ->with('url', '/nghiep_vu/ho_so/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle', 'Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    function create()
    {
        if (Session::has('admin')) {
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'), 'dantoc')->get()->toarray(), 'dantoc', 'maso');
            /*
            $m_pb = dmphongban::where('madv', session('admin')->madv)->get();
            //$m_pb = dmphongban::where('madv',session('admin')->madv)->get();
            $m_cvcq = dmchucvucq::where('maphanloai', session('admin')->maphanloai)
                ->wherein('madv', ['SA', session('admin')->madv])->get();
            */
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc = array_column(dmkhoipb::all()->toArray(), 'tenkhoipb', 'makhoipb');
            //$m_cvd= dmchucvud::all();
            $m_plnb = nhomngachluong::select('manhom', 'tennhom', 'heso', 'namnb')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong', 'manhom', 'msngbac', 'heso', 'namnb', 'hesolonnhat', 'bacvuotkhung')->get();

            $macanbo = session('admin')->madv . '_' . getdate()[0];

            $max_stt = getDbl((hosocanbo::where('madv', session('admin')->madv)->get()->max('stt'))) + 1;
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            $model_pc_bh = array_column($model_pc->where('baohiem', 1)->toarray(), 'tenpc', 'mapc');
            //dd($model_pc_bh);
            $model = new hosocanbo();
            $model->macanbo = $macanbo;
            return view('manage.hosocanbo.create')
                ->with('type', 'create')
                ->with('model', $model)
                ->with('macanbo', $macanbo)
                ->with('max_stt', $max_stt)
                //danh mục
                ->with('m_linhvuc', $m_linhvuc)
                ->with('model_dt', $model_dt)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('m_plnb', $m_plnb)
                ->with('m_pln', $m_pln)
                ->with('furl_kn', '/nghiep_vu/ho_so/')
                ->with('a_heso', array('heso', 'vuotkhung', 'luonghd', 'hesott'))
                ->with('a_pc_bh', $model_pc_bh)
                ->with('model_pc', $model_pc)
                ->with('pageTitle', 'Tạo hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $madv = session('admin')->madv;
            $macanbo = $insert['macanbo'];

            //có 1 số trường hợp cán bộ nhấn tạo 2 lần => trùng mã
            if (hosocanbo::where('macanbo', $macanbo)->first() != null) {
                return redirect('nghiep_vu/ho_so/danh_sach');
            }

            //Xử lý file ảnh
            //dd($request->file('anh'));
            $img = $request->file('anh');
            $filename = '';
            if (isset($img)) {
                $filename = $macanbo . '_' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
            }

            if (isset($insert['khongnopbaohiem'])) {
                $insert['khongnopbaohiem'] = implode(',', $insert['khongnopbaohiem']);
            } else {
                $insert['khongnopbaohiem'] = '';
            }

            $insert['anh'] = ($filename == '' ? '' : '/data/uploads/anh/' . $filename);
            $insert['madv'] = $madv;
            $insert['ngaybc'] = getDateTime($insert['ngaybc']);
            $insert['ngayvao'] = getDateTime($insert['ngayvao']);
            $insert['ngaysinh'] = getDateTime($insert['ngaysinh']);
            $insert['ngaytu'] = getDateTime($insert['ngaytu']);
            $insert['ngayden'] = getDateTime($insert['ngayden']);
            $insert['tnntungay'] = getDateTime($insert['tnntungay']);
            $insert['tnndenngay'] = getDateTime($insert['tnndenngay']);

            $insert['pthuong'] = chkDbl($insert['pthuong']) == 0 ? 100 : chkDbl($insert['pthuong']);
            $insert['nguoiphuthuoc'] = chkDbl($insert['nguoiphuthuoc']);
            $a_pc = dmphucap_donvi::select('mapc')->where('madv', session('admin')->madv)->get()->toarray();
            foreach ($a_pc as $pc) {
                if (isset($insert[$pc['mapc']])) {
                    $insert[$pc['mapc']] = chkDbl($insert[$pc['mapc']]);
                }
            }
            $a_bh = array('nguoiphuthuoc', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv',);
            foreach ($a_bh as $bh) {
                if (isset($insert[$bh])) {
                    $insert[$bh] = chkDbl($insert[$bh]);
                }
            }
            //dd($insert);
            hosocanbo::create($insert);

            $model_kn = hosocanbo_kiemnhiem_temp::where('macanbo', $macanbo)->get();
            foreach ($model_kn as $ct) {
                $a_kq = $ct->toarray();
                unset($a_kq['id']);
                hosocanbo_kiemnhiem::create($a_kq);
            }
            return redirect('nghiep_vu/ho_so/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function show($id)
    {
        if (Session::has('admin')) {
            //$makhoipb=getMaKhoiPB(session('admin')->madv);
            $model = hosocanbo::find($id);
            //$m_hosoct = hosotinhtrangct::where('macanbo',$model->macanbo)->where('hientai','1')->first();

            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'), 'dantoc')->get()->toarray(), 'dantoc', 'maso');
            //$m_pb= dmphongban::where('madv',session('admin')->madv)->get();
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc = array_column(dmkhoipb::all()->toArray(), 'tenkhoipb', 'makhoipb');
            $m_plnb = nhomngachluong::select('manhom', 'tennhom')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong', 'manhom', 'msngbac', 'heso', 'namnb', 'hesolonnhat', 'bacvuotkhung')->get();
            $a_linhvuc = explode(',', $model->lvhd);
            $a_nguonkp = explode(',', $model->manguonkp);
            //lấy phụ cấp ở danh mục phụ cấp đơn vị mapc => tenform
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();

            $model_kn = hosocanbo_kiemnhiem::where('macanbo', $model->macanbo)->get();
            $a_pl = getPhanLoaiKiemNhiem();
            $a_cv = getChucVuCQ(false);
            $a_plct = array_column($model_tenct->toArray(), 'tenct', 'mact');
            $nb =  $m_pln->where('msngbac', $model->msngbac)->first();
            $namnb = $nb->namnb ?? 0;
            foreach ($model_kn as $ct) {
                $ct->tenct = $a_plct[$ct->mact] ?? $ct->mact;
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
            }
            $model_pc_bh = array_column($model_pc->where('baohiem', 1)->toarray(), 'tenpc', 'mapc');
            $a_pc = implode(';', array_column($model_pc->toarray(), 'mapc'));
            //dd($a_pc);
            return view('manage.hosocanbo.edit')
                ->with('model', $model)
                ->with('type', 'edit')
                ->with('model_dt', $model_dt)
                ->with('model_kn', $model_kn)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('m_linhvuc', $m_linhvuc)
                ->with('a_linhvuc', $a_linhvuc)
                ->with('a_nguonkp', $a_nguonkp)
                ->with('a_pc', $a_pc)
                ->with('m_plnb', $m_plnb)
                ->with('m_pln', $m_pln)
                ->with('namnb', $namnb)
                ->with('furl_kn', '/nghiep_vu/ho_so/')
                ->with('a_heso', array('heso', 'vuotkhung', 'luonghd', 'hesott'))
                ->with('a_pc_bh', $model_pc_bh)
                ->with('model_pc', $model_pc->sortby('stt'))
                ->with('pageTitle', 'Sửa thông tin hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = hosocanbo::find($id);
            //
            hosocanbo_kiemnhiem::where('macanbo', $model->macanbo)->delete();
            $model->delete();
            return redirect('nghiep_vu/ho_so/danh_sach');

            /*
            $chk_bl = bangluong_ct::where('macanbo',$model->macanbo)->get()->count();
            if($chk_bl > 0){
                return view('errors.del_canbo')
                    ->with('furl', '/nghiep_vu/ho_so/danh_sach');
            }else{
                hosocanbo_kiemnhiem::where('macanbo',$model->macanbo)->delete();
                $model->delete();
                return redirect('nghiep_vu/ho_so/danh_sach');
            }
            */

            //bangluong_ct::where('macanbo',$model->macanbo)->delete();
            //bangluong_phucap::where('macanbo',$model->macanbo)->delete();
            //bangluongdangky_ct::where('macanbo',$model->macanbo)->delete();
            //bangluongdangky_phucap::where('macanbo',$model->macanbo)->delete();

        } else
            return view('errors.notlogin');
    }

    public function update(Request $request, $id)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            //dd($insert);
            $model = hosocanbo::find($id);
            //Xử lý file ảnh
            $img = $request->file('anh');
            if ($insert['bl_xoaanh'] == 'true') { //dùng chức năng xóa ảnh đại diện
                if (File::exists($model->anh)) {
                    File::Delete($model->anh);
                }
                $insert['anh'] = '';
            } else {
                if (isset($img)) {
                    //Xóa ảnh cũ
                    if (File::exists($model->anh)) {
                        File::Delete($model->anh);
                    }
                    $filename = $model->macanbo . '.' . $img->getClientOriginalExtension();
                    $img->move(public_path() . '/data/uploads/anh/', $filename);
                    $insert['anh'] = '/data/uploads/anh/' . $filename;
                }
            }

            if (isset($insert['khongnopbaohiem'])) {
                $insert['khongnopbaohiem'] = implode(',', $insert['khongnopbaohiem']);
            } else {
                $insert['khongnopbaohiem'] = '';
            }

            //dd($insert);
            $insert['ngaybc'] = getDateTime($insert['ngaybc']);
            $insert['ngayvao'] = getDateTime($insert['ngayvao']);
            $insert['ngaysinh'] = getDateTime($insert['ngaysinh']);
            $insert['ngaytu'] = getDateTime($insert['ngaytu']);
            $insert['ngayden'] = getDateTime($insert['ngayden']);
            $insert['pthuong'] = chkDbl($insert['pthuong']) == 0 ? 100 : chkDbl($insert['pthuong']);
            $insert['nguoiphuthuoc'] = chkDbl($insert['nguoiphuthuoc']);
            $insert['tnntungay'] = getDateTime($insert['tnntungay']);
            $insert['tnndenngay'] = getDateTime($insert['tnndenngay']);
            $model_pc = dmphucap_donvi::select('mapc')->where('madv', session('admin')->madv)->get()->toarray();
            foreach ($model_pc as $pc) {
                if (isset($insert[$pc['mapc']])) {
                    $insert[$pc['mapc']] = chkDbl($insert[$pc['mapc']]);
                }
            }

            $a_bh = array('nguoiphuthuoc', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'mucluongbaohiem');
            foreach ($a_bh as $bh) {
                if (isset($insert[$bh])) {
                    $insert[$bh] = chkDbl($insert[$bh]);
                }
            }
            //dd($insert);
            $model->update($insert);
            return redirect('nghiep_vu/ho_so/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function search()
    {
        if (Session::has('admin')) {
            //$m_pb=dmphongban::all('mapb','tenpb');
            $m_dt = dmdantoc::all('dantoc');
            //$m_cvcq=dmchucvucq::all('tencv', 'macvcq');
            $m_pb = getPhongBan(false);
            $m_cvcq = getChucVuCQ(false);

            return view('search.hosocanbo.index')
                ->with('m_pb', $m_pb)
                ->with('m_cvcq', $m_cvcq)
                ->with('m_dt', $m_dt)
                ->with('pageTitle', 'Tra cứu hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function result(Request $request)
    {
        if (Session::has('admin')) {
            /*
            $_sql = "select hosocanbo.id,hosocanbo.macanbo,hosocanbo.tencanbo,hosocanbo.anh,hosocanbo.macvcq,hosocanbo.mapb,hosocanbo.gioitinh,dmchucvucq.sapxep,hosocanbo.ngaysinh
                   from hosocanbo, dmchucvucq
                   Where hosocanbo.macvcq=dmchucvucq.macvcq and hosocanbo.theodoi < '9' and hosocanbo.madv ='".session('admin')->madv."'";
            */
            $_sql = "select * from hosocanbo Where theodoi < '9' and madv ='" . session('admin')->madv . "'";

            $inputs = $request->all();
            $s_dk = getConditions($inputs, array('_token'), 'hosocanbo');
            if ($s_dk != '') {
                $_sql .= ' and ' . $s_dk;
            }
            //dd($_sql);
            $model = DB::select(DB::raw($_sql));

            $m_pb = dmphongban::all('mapb', 'tenpb')->toArray();
            $m_cvcq = dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach ($model as $hs) {
                $hs->tenpb = getInfoPhongBan($hs, $m_pb);
                $hs->tencvcq = getInfoChucVuCQ($hs, $m_cvcq);
            }

            return view('search.hosocanbo.result')
                ->with('model', $model)
                ->with('pageTitle', 'Kết quả tra cứu hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    public function store_kiemnhiem(Request $request)
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
        $inputs['baohiem'] = 0;
        $inputs['madv'] = session('admin')->madv;

        $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'mapc');
        foreach ($a_pc as $pc) {
            $inputs[$pc] = chkDbl($inputs[$pc]);
        }
        $inputs['pthuong'] = chkDbl($inputs['pthuong']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));
        $model = hosocanbo_kiemnhiem::find($inputs['id']);
        unset($inputs['id']);

        //return response()->json($inputs);
        if ($model != null) {
            $model->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenct = $a_plct[$ct->mact] ?? $ct->mact;
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        return response()->json($result);
    }

    public function delete_kn(Request $request)
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
        $model = hosocanbo_kiemnhiem::find($inputs['id']);
        $model->delete();
        $model = hosocanbo_kiemnhiem::where('macanbo', $model->macanbo)->get();
        $a_plct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenct = $a_plct[$ct->mact] ?? $ct->mact;
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }

        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    function getinfor_kn(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = hosocanbo_kiemnhiem::find($inputs['id']);
        die($model);
    }

    public function store_chvu_temp(Request $request)
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
        $inputs['madv'] = session('admin')->madv;
        $inputs['heso'] = chkDbl($inputs['heso']);
        $inputs['vuotkhung'] = chkDbl($inputs['vuotkhung']);
        $inputs['pthuong'] = chkDbl($inputs['pthuong']);
        $inputs['baohiem'] = 0;
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_kct_temp(Request $request)
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
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['pclt'] = chkDbl($inputs['pclt']);
        $inputs['pckct'] = chkDbl($inputs['pckct']);
        $inputs['pcthni'] = chkDbl($inputs['pcthni']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_dbhdnd_temp(Request $request)
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
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pcdith'] = chkDbl($inputs['pcdith']);
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_qs_temp(Request $request)
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
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pctn'] = chkDbl($inputs['pctn']);
        $inputs['pcdbn'] = chkDbl($inputs['pcdbn']);
        $inputs['pcthni'] = chkDbl($inputs['pcthni']);
        $inputs['pck'] = chkDbl($inputs['pck']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_cuv_temp(Request $request)
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
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_cd_temp(Request $request)
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
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_mc_temp(Request $request)
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
        $inputs['pcdh'] = chkDbl($inputs['pcdh']);
        $inputs['pcd'] = chkDbl($inputs['pcd']);
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_tn_temp(Request $request)
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
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['manguonkp'] = (implode(',', $inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function delete_kn_temp(Request $request)
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
        $model = hosocanbo_kiemnhiem_temp::find($inputs['id']);
        $model->delete();
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $model->macanbo)->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach ($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }

        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    function getinfor_kn_temp(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = hosocanbo_kiemnhiem_temp::find($inputs['id']);
        die($model);
    }

    public function infor_excel()
    {
        if (Session::has('admin')) {
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->orderBy('stt')->get();
            return view('manage.hosocanbo.excel.information')
                ->with('model_pc', $model_pc)
                ->with('url', '/nghiep_vu/ho_so/')
                ->with('pageTitle', 'Thông tin nhận danh sách cán bộ từ file Excel');
        } else
            return view('errors.notlogin');
    }

    function create_excel(Request $request)
    {
        if (Session::has('admin')) {
            //Nguyên tắc: chuyển chức vụ, mact về tiếng việt ko dấu để tìm kiếm, lấy đầu tiên tìm đc ko gán = mặc định
            //chức vụ ko tìm dc trong mảng chính => tìm trong bảng viết tắt
            $madv = session('admin')->madv;
            $maso = getdate()[0]; //lưu mã số
            $inputs = $request->all();
            //dd($inputs);
            $a_nb = ngachluong::all()->keyBy('msngbac')->toArray();
            $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->get()->toArray(), 'mapc');
            //$a_nhomnb = nhomngachluong::all()->keyBy('manhom')->toArray();

            $a_chucvu = getChucVuCQ(false);
            foreach ($a_chucvu as $key => $val) {
                $a_chucvu[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $a_chucvu_vt = array_column(dmchucvucq::where('maphanloai', session('admin')->maphanloai)
                ->wherein('madv', ['SA', session('admin')->madv])->get()->toArray(), 'tenvt', 'macvcq');
            foreach ($a_chucvu_vt as $key => $val) {
                $a_chucvu_vt[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $a_phanloaict = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            foreach ($a_phanloaict as $key => $val) {
                $a_phanloaict[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $a_pb = getPhongBan(false);
            foreach ($a_pb as $key => $val) {
                $a_pb[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $macv_df = key($a_chucvu);  // do khi chạy các vòng for thì hàm key() trả lại ở vị trí đang dừng
            $mact_df = key($a_phanloaict);
            $filename = $madv . date('YmdHis');
            $request->file('fexcel')->move(public_path() . '/data/uploads/excels/', $filename . '.xls');
            $path = public_path() . '/data/uploads/excels/' . $filename . '.xls';

            $data = [];
            //dd();
            Excel::load($path, function ($reader) use (&$data, $inputs) {
                $obj = $reader->getExcel();
                $sheet = $obj->getSheet(0);
                $data = $sheet->toArray(null, true, true, true); // giữ lại tiêu đề A=>'val';
            });


            //dd($data);
            $j = getDbl((hosocanbo::where('madv', session('admin')->madv)->get()->max('stt'))) + 1;
            if ($inputs['macvcq'] != '') {
                $a_pb_ex = a_split($data, array($inputs['macvcq']));
            }
            $a_cv_m = array();
            $a_pb_m = array();
            //dd( '-' . chuanhoachuoi(trim($a_pb_ex[5]['H'])) . '-');

            for ($i = $inputs['tudong']; $i < ($inputs['tudong'] + $inputs['sodong']); $i++) {
                //dd($data[$i]);
                if (!isset($data[$i][$inputs['tencanbo']]) || $data[$i][$inputs['tencanbo']] == '') {
                    continue; //Tên cán bộ rỗng => thoát
                }
                $model = new hosocanbo();
                $model->stt = $j++;
                $model->madv = $madv;
                $model->macanbo = $madv . '_' . $maso++;
                $model->tencanbo = $data[$i][$inputs['tencanbo']];

                if ($inputs['ngaysinh'] != '') {
                    $model->ngaysinh = getDateToDb($data[$i][$inputs['ngaysinh']]);
                }
                if ($inputs['gioitinh'] != '') {
                    $model->gioitinh = $data[$i][$inputs['gioitinh']];
                }
                if ($inputs['lvtd'] != '') {
                    $model->lvtd = $data[$i][$inputs['lvtd']];
                }
                if ($inputs['ngaytu'] != '') {
                    $model->ngaytu = getDateToDb($data[$i][$inputs['ngaytu']]);
                }
                if ($inputs['ngayden'] != '') {
                    $model->ngayden = getDateToDb($data[$i][$inputs['ngayden']]);
                }
                if ($inputs['tnntungay'] != '') {
                    $model->tnntungay = getDateToDb($data[$i][$inputs['tnntungay']]);
                }
                if ($inputs['tnndenngay'] != '') {
                    $model->tnndenngay = getDateToDb($data[$i][$inputs['tnndenngay']]);
                }
                if ($inputs['sotk'] != '') {
                    $model->sotk = $data[$i][$inputs['sotk']];
                }
                //dd($model);
                /*
                $timestamp = strtotime($date);
                if ($timestamp !== FALSE) {
                    $model->ngaysinh = getDayVn($date) $timestamp;
                    //$timestamp = strtotime(str_replace('/', '-', $date));
                }
                */
                $model->bac = 1;
                if ($inputs['sunghiep'] != '') {
                    $sunghiep = '-' . chuanhoachuoi(trim($data[$i][$inputs['sunghiep']])) . '-';
                    switch ($sunghiep) {
                        case '-cong-chuc-': {
                                $model->sunghiep = 'Công chức';
                                break;
                            }
                        case '-vien-chuc-': {
                                $model->sunghiep = 'Viên chức';
                                break;
                            }
                        default: {
                                $model->sunghiep = 'Khác';
                                break;
                            }
                    }
                } else {
                    $model->sunghiep = 'Viên chức';
                }
                //$model->mact = 1;//default
                foreach ($a_pc as $key => $val) {
                    $col = strtoupper($inputs[$val]);
                    if ($col == '') {
                        continue;
                    }
                    $model->$val = chkDbl($data[$i][$col]);
                }

                //khối tổ công tác
                if ($inputs['mapb'] != '') {
                    $mapb = '-' . chuanhoachuoi(trim($data[$i][$inputs['mapb']])) . '-';
                    foreach ($a_pb as $key => $val) {
                        if ($val == $mapb) {
                            $model->mapb = $key;
                            break;
                        }
                        if (strpos($val, $mapb) !== false) { //mã chứa trong chuỗi => tìm tiếp
                            $model->mapb = $key;
                        }
                    }

                    if ($model->mapb == null || $model->mapb == '') {
                        if (!isset($a_pb_m[$mapb])) {
                            //tự thêm vào danh mục
                            $mapb_m = $madv . '_' . ($maso++);
                            $model->mapb = $mapb_m;
                            //xóa ký tự đăc biệt, xuống dòng
                            $tenpb = preg_replace('/([^\pL\.\ ]+)/u', ' ', strip_tags($data[$i][$inputs['mapb']]));
                            $a_pb_m[$mapb] = array('mapb' => $mapb_m, 'tenpb' => $tenpb, 'madv' => $madv);
                        } else {
                            $model->mapb = $a_pb_m[$mapb]['mapb'];
                        }
                    }
                }

                if ($inputs['mact'] != '') {
                    $mact = '-' . chuanhoachuoi(trim($data[$i][$inputs['mact']])) . '-';
                    foreach ($a_phanloaict as $key => $val) {
                        if ($val == $mact) {
                            $model->mact = $key;
                            break;
                        }
                        if (strpos($val, $mact) !== false) { //mã chứa trong chuỗi => tìm tiếp
                            $model->mact = $key;
                        }
                    }
                } else {
                    $model->mact = '';
                }

                if ($model->mact == null || $model->mact == '') { //tìm trong mảng ko dc => set mặc định
                    $model->mact = $mact_df;
                }

                if ($inputs['msngbac'] != '') {
                    $msngbac = (string) $data[$i][$inputs['msngbac']];
                    //dd($msngbac);
                    if (isset($a_nb[$msngbac])) {
                        $model->msngbac = $msngbac;
                        $bac = ($model->heso - $a_nb[$msngbac]['heso']) / $a_nb[$msngbac]['hesochenhlech'];
                        $model->bac = chkDbl($bac) + 1; //do bắt đầu từ 1
                    }
                } else {
                    $model->msngbac = '';
                }

                if ($inputs['macvcq'] != '') {
                    //xóa ký tự đăc biệt, xuống dòng
                    //$macv = preg_replace('/([^\pL\.\ ]+)/u', ' ', strip_tags($data[$i][$inputs['macvcq']]));
                    $macv = '-' . chuanhoachuoi(trim($data[$i][$inputs['macvcq']])) . '-';

                    foreach ($a_chucvu as $key => $val) {
                        if ($val == $macv) {
                            $model->macvcq = $key;
                            break;
                        }
                        if (strpos($val, $macv) !== false) {
                            $model->macvcq = $key;
                        }
                    }

                    if ($model->macvcq == null || $model->macvcq == '') {
                        //tìm trong mảng chức vụ viết tắt ko dc => set mặc định
                        foreach ($a_chucvu_vt as $key => $val) {
                            if ($val == $macv) {
                                $model->macvcq = $key;
                                break;
                            }
                        }
                    }

                    if ($model->macvcq == null || $model->macvcq == '') {
                        if (!isset($a_cv_m[$macv])) {
                            //tự thêm vào danh mục
                            $macv_m = $madv . '_' . ($maso++);
                            $model->macvcq = $macv_m;
                            //xóa ký tự đăc biệt, xuống dòng
                            $tencv = preg_replace('/([^\pL\.\ ]+)/u', ' ', strip_tags($data[$i][$inputs['macvcq']]));
                            $a_cv_m[$macv] = array('macvcq' => $macv_m, 'tencv' => $tencv, 'madv' => $madv, 'maphanloai' => session('admin')->maphanloai);
                        } else {
                            $model->macvcq = $a_cv_m[$macv]['macvcq'];
                        }
                    }
                }
                //dd($model);
                $model->save();
            }
            dmchucvucq::insert($a_cv_m);
            dmphongban::insert($a_pb_m);
            //dd($a_cv_m);
            File::Delete($path);
            return redirect('nghiep_vu/ho_so/danh_sach');
        } else
            return view('errors.notlogin');
    }

    /**
     * @param $result
     * @param $model
     * @return mixed
     */
    public function retun_html_kn($result, $model)
    {
        $result['message'] = '<div class="row" id="dskn">';
        $result['message'] .= '<div class="col-md-12">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_4">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr>';
        $result['message'] .= '<th class="text-center" style="width: 5%">STT</th>';
        $result['message'] .= '<th class="text-center">Phân loại</th>';
        $result['message'] .= '<th class="text-center">Chức vụ</br>kiêm nhiệm</th>';
        $result['message'] .= '<th class="text-center">Hệ</br>số</br>lương</th>';
        $result['message'] .= '<th class="text-center">Hệ</br>số</br>phụ</br>cấp</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>chức</br>vụ</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>trách</br>nhiệm</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>kiêm</br>nhiệm</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>đặc</br>thù</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>một</br>cửa</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>điện</br>thoại</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>đại</br>biểu</br>HDND</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>cấp</br>uỷ</th>';
        $result['message'] .= '<th class="text-center">Phụ</br>cấp</br>khác</th>';
        $result['message'] .= '<th class="text-center">Thao tác</th>';


        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';
        $result['message'] .= '<tbody>';
        if (count($model) > 0) {
            foreach ($model as $key => $value) {
                $result['message'] .= '<tr>';
                $result['message'] .= '<td style="text-align: center">' . ($key + 1) . '</td>';
                $result['message'] .= '<td>' . $value->tenct . '</td>';
                $result['message'] .= '<td>' . $value->tenchucvu . '</td>';
                $result['message'] .= '<td>' . $value->heso . '</td>';
                $result['message'] .= '<td>' . $value->hesopc . '</td>';
                $result['message'] .= '<td>' . $value->pccv . '</td>';
                $result['message'] .= '<td>' . $value->pctn . '</td>';
                $result['message'] .= '<td>' . $value->pckn . '</td>';
                $result['message'] .= '<td>' . $value->pcdbn . '</td>';
                $result['message'] .= '<td>' . $value->pcd . '</td>';
                $result['message'] .= '<td>' . $value->pcdith . '</td>';
                $result['message'] .= '<td>' . $value->pcdbqh . '</td>';
                $result['message'] .= '<td>' . $value->pcvk . '</td>';
                $result['message'] .= '<td>' . $value->pck . '</td>';
                $result['message'] .= '<td>' .
                    '<button type="button" data-target="#kiemnhiem-modal" data-toggle="modal" class="btn btn-default btn-xs mbs" onclick="edit_kn(' . $value->id . ');"><i class="fa fa-edit"></i>&nbsp;Sửa</button>' .
                    '<button type="button" class="btn btn-default btn-xs mbs" onclick="deleteRow(' . $value->id . ')" ><i class="fa fa-trash-o"></i>&nbsp;Xóa</button>'

                    . '</td>';
                $result['message'] .= '</tr>';
            }
            $result['message'] .= '</tbody>';
            $result['message'] .= '</table>';
            $result['message'] .= '</div>';
            $result['message'] .= '</div>';
            $result['status'] = 'success';
            return $result;
        }
        return $result;
    }

    function get_congtac(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->where('mact', $inputs['mact'])->first();
        die($model);
    }

    function get_chucvu_bh(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmchucvucq::where('macvcq', $inputs['macvcq'])->first();
        die($model);
    }

    public function indanhsach(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            $a_plpc = getPhanLoaiPhuCap();

            foreach ($model as $hs) {
                $hs->tenpb = isset($a_pb[$hs->mapb]) ? $a_pb[$hs->mapb] : '';
                $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq] : '';
                $hs->tenct = isset($a_ct[$hs->mact]) ? $a_ct[$hs->mact] : '';
            }

            if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
                $model = $model->where('mapb', $inputs['mapb']);
            }
            if (isset($inputs['macvcq']) && $inputs['macvcq'] != '') {
                $model = $model->where('macvcq', $inputs['macvcq']);
            }
            if (isset($inputs['mact']) && $inputs['mact'] != '') {
                $model = $model->where('mact', $inputs['mact']);
            }


            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report . '</br>(' . $a_plpc[$ct->phanloai] . ')';
                    $col++;
                }
            }
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            return view('reports.hoso.danhsach')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('col', $col)
                ->with('m_dv', $m_dv)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    public function inhoso(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            $a_plpc = getPhanLoaiPhuCap();
            //dd($inputs);
            $model = hosocanbo::where('macanbo', $inputs['maso'])->first();
            $model->tenpb = isset($a_pb[$model->mapb]) ? $a_pb[$model->mapb] : '';
            $model->tencv = isset($a_cv[$model->macvcq]) ? $a_cv[$model->macvcq] : '';
            $model->tenct = isset($a_ct[$model->mact]) ? $a_ct[$model->mact] : '';

            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->get();

            foreach ($model_pc as $ct) {
                $mapc = $ct->mapc;
                $ct->phanloai = $a_plpc[$ct->phanloai];
                $ct->heso = $model->$mapc > 0 ? $model->$mapc : 0;
            }

            $model_pc = $model_pc->where('heso', '>', 0);
            //dd($model_pc);
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();
            return view('reports.hoso.thongtincanbo')
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('model_pc', $model_pc)
                ->with('pageTitle', 'Thông tin cán bộ');
        } else
            return view('errors.notlogin');
    }
}
