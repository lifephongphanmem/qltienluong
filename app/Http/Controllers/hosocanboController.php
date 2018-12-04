<?php

namespace App\Http\Controllers;

use App\bangluong_ct;
use App\bangluong_phucap;
use App\bangluongdangky_ct;
use App\bangluongdangky_phucap;
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
use App\hosothoicongtac;
use App\hosotinhtrangct;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class hosocanboController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            //$m_hs=hosocanbo::where('madv',session('admin')->maxa)->get();
            $m_hs=hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->get();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            foreach($m_hs as $hs){
                $hs->tenpb = isset($a_pb[$hs->mapb])?$a_pb[$hs->mapb] : '';
                $hs->tencvcq = isset($a_cv[$hs->macvcq])?$a_cv[$hs->macvcq] : '';
                $hs->tenct = isset($a_ct[$hs->mact])?$a_ct[$hs->mact] : '';
            }

            $model = $m_hs->sortBy('stt');
            return view('manage.hosocanbo.index')
                ->with('model',$model)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách cán bộ');
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
            $m_pln = ngachluong::select('tenngachluong', 'manhom', 'msngbac', 'heso', 'namnb')
                ->distinct()->get();
            foreach ($m_pln as $mangach) {
                $nhomnb = $m_plnb->where('manhom', $mangach->manhom)->first();
                if (count($nhomnb) > 0 && $mangach->manhom != 'CBCT') {
                    $mangach->heso = $nhomnb->heso;
                    $mangach->namnb = $nhomnb->namnb;
                }
            }
            $macanbo = session('admin')->madv . '_' . getdate()[0];

            $max_stt = getDbl((hosocanbo::where('madv', session('admin')->madv)->get()->max('stt'))) + 1;
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();

            return view('manage.hosocanbo.create')
                ->with('type', 'create')
                ->with('macanbo', $macanbo)
                ->with('max_stt', $max_stt)
                //danh mục
                ->with('m_linhvuc', $m_linhvuc)
                ->with('model_dt', $model_dt)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('m_plnb', $m_plnb)
                ->with('m_pln', $m_pln)
                ->with('furl_kn', '/nghiep_vu/ho_so/temp/')
                ->with('a_heso', array('heso', 'vuotkhung', 'luonghd', 'hesott'))
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

            //Xử lý file ảnh
            //dd($request->file('anh'));
            $img = $request->file('anh');
            $filename = '';
            if (isset($img)) {
                $filename = $macanbo . '_' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
            }

            $insert['anh'] = ($filename == '' ? '' : '/data/uploads/anh/' . $filename);
            $insert['madv'] = $madv;
            $insert['ngaysinh'] = getDateTime($insert['ngaysinh']);
            $insert['ngaytu'] = getDateTime($insert['ngaytu']);
            $insert['ngayden'] = getDateTime($insert['ngayden']);
            $insert['tnntungay'] = getDateTime($insert['tnntungay']);
            $insert['tnndenngay'] = getDateTime($insert['tnndenngay']);

            $insert['pthuong']=chkDbl($insert['pthuong']) == 0 ? 100 :chkDbl($insert['pthuong']) ;
            $a_pc = dmphucap_donvi::select('mapc')->where('madv', session('admin')->madv)->get()->toarray();
            foreach($a_pc as $pc){
                if(isset($insert[$pc['mapc']])){
                    $insert[$pc['mapc']] = chkDbl($insert[$pc['mapc']]);
                }
            }
            $a_bh = array('nguoiphuthuoc','bhxh','bhyt','bhtn','kpcd','bhxh_dv','bhyt_dv','bhtn_dv','kpcd_dv',);
            foreach($a_bh as $bh){
                if(isset($insert[$bh])){
                    $insert[$bh] = chkDbl($insert[$bh]);
                }
            }

            hosocanbo::create($insert);

            $model_kn = hosocanbo_kiemnhiem_temp::where('macanbo',$macanbo)->get();
            foreach($model_kn as $ct){
                $a_kq = $ct->toarray();
                unset($a_kq['id']);
                hosocanbo_kiemnhiem::create($a_kq);
            }
            return redirect('nghiep_vu/ho_so/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function show($id){
        if (Session::has('admin')) {
            //$makhoipb=getMaKhoiPB(session('admin')->madv);
            $model = hosocanbo::find($id);
            //$m_hosoct = hosotinhtrangct::where('macanbo',$model->macanbo)->where('hientai','1')->first();

            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'),'dantoc')->get()->toarray(),'dantoc','maso');
            //$m_pb= dmphongban::where('madv',session('admin')->madv)->get();
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc = array_column(dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
            $m_plnb = nhomngachluong::select('manhom','tennhom')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong','manhom','msngbac')->distinct()->get();
            $a_linhvuc = explode(',',$model->lvhd);
            $a_nguonkp = explode(',',$model->manguonkp);
            //lấy phụ cấp ở danh mục phụ cấp đơn vị mapc => tenform
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();

            $model_kn = hosocanbo_kiemnhiem::where('macanbo',$model->macanbo)->get();
            $a_pl = getPhanLoaiKiemNhiem();
            $a_cv = getChucVuCQ(false);

            foreach($model_kn as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
            }

            return view('manage.hosocanbo.edit')
                ->with('model',$model)
                ->with('type','edit')
                ->with('model_dt',$model_dt)
                ->with('model_kn',$model_kn)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('m_linhvuc',$m_linhvuc)
                ->with('a_linhvuc',$a_linhvuc)
                ->with('a_nguonkp',$a_nguonkp)
                ->with('m_plnb',$m_plnb)
                ->with('m_pln',$m_pln)
                ->with('furl_kn', '/nghiep_vu/ho_so/')
                ->with('a_heso', array('heso', 'vuotkhung', 'luonghd', 'hesott'))
                ->with('model_pc',$model_pc->sortby('stt'))
                ->with('pageTitle','Sửa thông tin hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosocanbo::find($id);
            //
            hosocanbo_kiemnhiem::where('macanbo',$model->macanbo)->delete();
            $model->delete();
            return redirect('nghiep_vu/ho_so/danh_sach');
            //
            $chk_bl = bangluong_ct::where('macanbo',$model->macanbo)->get()->count();
            if($chk_bl > 0){
                return view('errors.del_canbo')
                    ->with('furl', '/nghiep_vu/ho_so/danh_sach');
            }else{
                hosocanbo_kiemnhiem::where('macanbo',$model->macanbo)->delete();
                $model->delete();
                return redirect('nghiep_vu/ho_so/danh_sach');
            }


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
            $model = hosocanbo::find($id);
            //Xử lý file ảnh
            $img=$request->file('anh');
            if(isset($img)) {
                //Xóa ảnh cũ
                if(File::exists($model->anh))
                File::Delete($model->anh);

                $filename = $model->macanbo . '.' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
                $insert['anh']='/data/uploads/anh/'. $filename;
            }

            $insert['ngaysinh']=getDateTime($insert['ngaysinh']);
            $insert['ngaytu']=getDateTime($insert['ngaytu']);
            $insert['ngayden']=getDateTime($insert['ngayden']);
            $insert['pthuong']=chkDbl($insert['pthuong']) == 0 ? 100 :chkDbl($insert['pthuong']) ;
            $insert['tnntungay'] = getDateTime($insert['tnntungay']);
            $insert['tnndenngay'] = getDateTime($insert['tnndenngay']);
            $model_pc = dmphucap_donvi::select('mapc')->where('madv', session('admin')->madv)->get()->toarray();
            foreach($model_pc as $pc){
                if(isset($insert[$pc['mapc']])){
                    $insert[$pc['mapc']] = chkDbl($insert[$pc['mapc']]);
                }
            }

            $a_bh = array('nguoiphuthuoc','bhxh','bhyt','bhtn','kpcd','bhxh_dv','bhyt_dv','bhtn_dv','kpcd_dv',);
            foreach($a_bh as $bh){
                if(isset($insert[$bh])){
                    $insert[$bh] = chkDbl($insert[$bh]);
                }
            }

            $model->update($insert);
            return redirect('nghiep_vu/ho_so/danh_sach');
        }else
            return view('errors.notlogin');
    }

    function search(){
        if (Session::has('admin')) {
            $m_pb=dmphongban::all('mapb','tenpb');
            $m_dt=dmdantoc::all('dantoc');
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq');

            return view('search.hosocanbo.index')
                ->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                ->with('m_dt',$m_dt)
                ->with('pageTitle','Tra cứu hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function result(Request $request)
    {
        if (Session::has('admin')) {
            $_sql = "select hosocanbo.id,hosocanbo.macanbo,hosocanbo.tencanbo,hosocanbo.anh,hosocanbo.macvcq,hosocanbo.mapb,hosocanbo.gioitinh,dmchucvucq.sapxep,hosocanbo.ngaysinh
                   from hosocanbo, dmchucvucq
                   Where hosocanbo.macvcq=dmchucvucq.macvcq and hosocanbo.theodoi < '9' and hosocanbo.madv ='".session('admin')->madv."'";

            $inputs = $request->all();
            $s_dk = getConditions($inputs, array('_token'), 'hosocanbo');
            if ($s_dk != '') {
                $_sql .= ' and ' . $s_dk;
            }

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

    public function store_kct(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['pclt'] = chkDbl($inputs['pclt']);
        $inputs['pckct'] = chkDbl($inputs['pckct']);
        $inputs['pcthni'] = chkDbl($inputs['pcthni']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_dbhdnd(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['pcdith'] = chkDbl($inputs['pcdith']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_qs(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['pctdt'] = chkDbl($inputs['pctdt']);
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pctn'] = chkDbl($inputs['pctn']);
        $inputs['pcdbn'] = chkDbl($inputs['pcdbn']);
        $inputs['pcthni'] = chkDbl($inputs['pcthni']);
        $inputs['pck'] = chkDbl($inputs['pck']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_cuv(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_cd(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['pckn'] = chkDbl($inputs['pckn']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_mc(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['pcdh'] = chkDbl($inputs['pcdh']);
        $inputs['pcd'] = chkDbl($inputs['pcd']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    public function store_tn(Request $request)
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
        $inputs['baohiem'] = isset($inputs['baohiem'])?$inputs['baohiem'] : 0;
        $inputs['madv'] = session('admin')->madv;
        $inputs['hesopc'] = chkDbl($inputs['hesopc']);
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem::create($inputs);
        }
        $model = hosocanbo_kiemnhiem::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }
        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
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
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }

        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    function getinfor_kn(Request $request){
        if(!Session::has('admin')) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));

        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        $inputs['manguonkp'] = (implode(',',$inputs['manguonkp']));
        if ($inputs['id'] > 0) {
            hosocanbo_kiemnhiem_temp::find($inputs['id'])->update($inputs);
        } else {
            hosocanbo_kiemnhiem_temp::create($inputs);
        }
        $model = hosocanbo_kiemnhiem_temp::where('macanbo', $inputs['macanbo'])->get();
        $a_pl = getPhanLoaiKiemNhiem();
        $a_cv = getChucVuCQ(false);
        foreach($model as $ct) {
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
        foreach($model as $ct) {
            $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
            $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
        }

        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    function getinfor_kn_temp(Request $request){
        if(!Session::has('admin')) {
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

    public function infor_excel(){
        if(Session::has('admin')){
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->orderBy('stt')->get();
            return view('manage.hosocanbo.excel.information')
                ->with('model_pc',$model_pc)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('pageTitle','Thông tin nhận danh sách cán bộ từ file Excel');

        }else
            return view('errors.notlogin');
    }

    function create_excel(Request $request){
        if(Session::has('admin')){
            //Nguyên tắc: chuyển chức vụ, mact về tiếng việt ko dấu để tìm kiếm, lấy đầu tiên tìm đc ko gán = mặc định
            //chức vụ ko tìm dc trong mảng chính => tìm trong bảng viết tắt
            $madv=session('admin')->madv;
            $inputs=$request->all();

            $a_nb = array_column(ngachluong::all()->toArray(), 'manhom','msngbac');
            $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai','<','3')->get()->toArray(),'mapc');
            $a_nhomnb = nhomngachluong::all()->keyBy('manhom')->toArray();

            $a_chucvu = getChucVuCQ(false);
            foreach($a_chucvu as $key=>$val){
                $a_chucvu[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $a_chucvu_vt = array_column( dmchucvucq::where('maphanloai',session('admin')->maphanloai)
                ->wherein('madv',['SA',session('admin')->madv])->get()->toArray(),'tenvt','macvcq');
            foreach($a_chucvu_vt as $key=>$val){
                $a_chucvu_vt[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $a_phanloaict = array_column(dmphanloaict::all()->toArray(),'tenct','mact');
            foreach($a_phanloaict as $key=>$val) {
                $a_phanloaict[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $a_pb = getPhongBan(false);
            foreach($a_pb as $key=>$val) {
                $a_pb[$key] = '-' . chuanhoachuoi(trim($val)) . '-';
            }

            $macv_df = key($a_chucvu);  // do khi chạy các vòng for thì hàm key() trả lại ở vị trí đang dừng
            $mact_df = key($a_phanloaict);
            $filename = $madv . date('YmdHis');
            $request->file('fexcel')->move(public_path() . '/data/uploads/excels/', $filename . '.xls');
            $path = public_path() . '/data/uploads/excels/' . $filename . '.xls';

            $data = [];

            Excel::load($path, function($reader) use (&$data, $inputs) {
                $obj = $reader->getExcel();
                $sheet = $obj->getSheet(0);
                $data = $sheet->toArray(null,true,true,true);// giữ lại tiêu đề A=>'val';
            });

            $j = getDbl((hosocanbo::where('madv', session('admin')->madv)->get()->max('stt'))) + 1;
            //dd($data);
            for($i=$inputs['tudong'];$i < ($inputs['tudong'] + $inputs['sodong']); $i++){
                //dd($data[$i]);
                if (!isset($data[$i][$inputs['tencanbo']]) || $data[$i][$inputs['tencanbo']] == '') {
                    continue;//Tên cán bộ rỗng => thoát
                }
                $model = new hosocanbo();
                $model->stt = $j++;
                $model->madv = $madv;
                $model->macanbo = $madv. '_' . (getdate()[0] + $i);
                $model->tencanbo = $data[$i][$inputs['tencanbo']];
                $model->ngaysinh = getDateToDb($data[$i][$inputs['ngaysinh']]);
                $model->gioitinh = $data[$i][$inputs['gioitinh']];
                $model->lvtd = $data[$i][$inputs['lvtd']];

                $model->ngaytu =  getDateToDb($data[$i][$inputs['ngaytu']]);
                $model->ngayden =  getDateToDb($data[$i][$inputs['ngayden']]);
                $model->tnntungay =  getDateToDb($data[$i][$inputs['tnntungay']]);
                $model->tnndenngay =  getDateToDb($data[$i][$inputs['tnndenngay']]);
                $model->sotk = $data[$i][$inputs['sotk']];
                /*
                $timestamp = strtotime($date);
                if ($timestamp !== FALSE) {
                    $model->ngaysinh = getDayVn($date) $timestamp;
                    //$timestamp = strtotime(str_replace('/', '-', $date));
                }
                */
                $model->bac = 1;
                $sunghiep = '-' . chuanhoachuoi(trim($data[$i][$inputs['sunghiep']])) . '-';
                switch($sunghiep){
                    case '-cong-chuc-':{
                        $model->sunghiep = 'Công chức';
                        break;
                    }
                    case '-vien-chuc-':{
                        $model->sunghiep = 'Viên chức';
                        break;
                    }
                    default:{
                        $model->sunghiep = 'Khác';
                        break;
                    }
                }
                //$model->mact = 1;//default
                foreach ($a_pc as $key => $val) {
                    $col = strtoupper($inputs[$val]);
                    if($col == ''){continue;}
                    $model->$val = chkDbl($data[$i][$col]);
                }
                //khối tổ công tác
                $mapb = '-' . chuanhoachuoi(trim($data[$i][$inputs['mapb']])) . '-';
                foreach($a_pb as $key=>$val){
                    if($val == $mapb ) {
                        $model->mapb = $key;
                        break;
                    }
                    if( strpos( $val, $mapb ) !== false) {//mã chứa trong chuỗi => tìm tiếp
                        $model->mapb = $key;
                    }
                }

                $mact = '-' . chuanhoachuoi(trim($data[$i][$inputs['mact']])) . '-';
                foreach($a_phanloaict as $key=>$val){
                    if($val == $mact ) {
                        $model->mact = $key;
                        break;
                    }
                    if( strpos( $val, $mact ) !== false) {//mã chứa trong chuỗi => tìm tiếp
                        $model->mact = $key;
                    }
                }
                if($model->mact == null){//tìm trong mảng ko dc => set mặc định
                    $model->mact = $mact_df;
                }

                $msngbac =(string) $data[$i][$inputs['msngbac']];
                //dd($msngbac);
                if(array_key_exists($msngbac, $a_nb)){
                    $model->msngbac = $msngbac;
                    $nhom = $a_nhomnb[$a_nb[$model->msngbac]];
                    $bac = ($model->heso - $nhom['heso'])/$nhom['hesochenhlech'];
                    $model->bac = chkDbl($bac) + 1;//do bắt đầu từ 1
                }
                $macv = '-' . chuanhoachuoi(trim($data[$i][$inputs['macvcq']])) . '-';
                foreach($a_chucvu as $key=>$val) {
                    if ($val == $macv) {
                        $model->macvcq = $key;
                        break;
                    }
                    if (strpos($val, $macv) !== false) {
                        $model->macvcq = $key;
                    }
                }

                if($model->macvcq == null|| $model->macvcq == ''){//tìm trong mảng chức vụ viết tắt ko dc => set mặc định
                    $mavt = '-' . chuanhoachuoi(trim($data[$i][$inputs['macvcq']])) . '-';
                    foreach($a_chucvu_vt as $key=>$val) {
                        if ($val == $mavt) {
                            $model->macvcq = $key;
                            break;
                        }
                        if (strpos($val, $mavt) !== false) {
                            $model->macvcq = $key;
                        }
                    }

                }

                if($model->macvcq == null || $model->macvcq == ''){//tìm trong mảng chức vụ ko dc => set mặc định
                    $model->macvcq = $macv_df;
                }
                //dd($model);
                $model->save();
            }
            File::Delete($path);
            return redirect('nghiep_vu/ho_so/danh_sach');
        }else
            return view('errors.notlogin');
    }


    function create_excel_051018(Request $request){
        if(Session::has('admin')){
            $madv=session('admin')->madv;
            $inputs=$request->all();

            $filename = $madv . date('YmdHis');
            $request->file('fexcel')->move(public_path() . '/data/uploads/excels/', $filename . '.xls');
            $path = public_path() . '/data/uploads/excels/' . $filename . '.xls';

            $data = [];
            //dd ($ar_sheet);
            Excel::load($path, function($reader) use (&$data, $inputs) {
                $obj = $reader->getExcel();
                $sheet = $obj->getSheet(0);
                $data = $sheet->toArray(null,true,true,true);// giữ lại tiêu đề A=>'val';
                /*
                $Row = $sheet->getHighestRow();
                $Row = $inputs['sodong']+$inputs['tudong'] > $Row ? $Row : ($inputs['sodong']+$inputs['tudong']);
                $Col = $sheet->getHighestColumn();
                for ($r = $inputs['tudong']; $r <= $Row; $r++)
                {
                    $rowData = $sheet->rangeToArray('A' . $r . ':' . $Col . $r, NULL, TRUE, FALSE);//'0'=>'val'
                    $data[] = $rowData[0];
                }
                */
            });
            dd($data);
            //chuyển từ A=>0; B=>1,...
            foreach($inputs as $key=>$val) {
                $ma=ord($val);
                if($ma>=65 && $ma<=90){
                    $inputs[$key]=$ma-65;
                }
                if($ma>=97 && $ma<=122){
                    $inputs[$key]=$ma-97;
                }
            }

            //nhận dữ liệu vào bảng hồ sơ cán bộ
            $model_donvi = dmdonvi::where('madv',$madv)->first();
            $model_msngbac = ngachluong::all();
            $model_nhomngbac = nhomngachluong::all();
            $model_chucvu = dmchucvucq::all();

            $a_col = array(
                '0'=>'heso','1'=>'vuotkhung'
            );
            foreach(getColPhuCap() as $key=>$val){
                if($model_donvi->$key < 3){
                    $a_col[] = $key;
                }
            }
            //dd($a_col);
            $i=0; //lấy 1 số thêm vào ngày giờ vì for chạy xong trong 1s
            foreach ($data as $row) {
                if ($row[$inputs['tencanbo']] == '') {
                    //Tên cán bộ rỗng => thoát
                    continue;
                }
                $model = new hosocanbo();
                $model->madv = $madv;
                $model->macanbo = $madv. '_' . (getdate()[0] + $i++);
                $model->macongchuc = $row[$inputs['macongchuc']];
                $model->tencanbo = $row[$inputs['tencanbo']];
                $model->macvcq = $row[$inputs['macvcq']];
                $model->msngbac = $row[$inputs['msngbac']];
                $model->bac = 1;
                //Tính bậc lương (mặc định 1), lấy mã chức vụ cho cán bộ (mặc định chức vụ không xác định)
                foreach ($a_col as $key => $val) {
                    $model->$val = isset($row[$inputs[$val]]) ? chkDbl($row[$inputs[$val]]) : 0;
                }
                // Từ hệ số lương tính ra bậc lương của cán bộ
                $msngbac = $model_msngbac->where('msngbac',$model->msngbac)->first();
                if(isset($msngbac)){
                    $nhomngbac = $model_nhomngbac->where('manhom',$msngbac->manhom)->first();
                    if(isset($nhomngbac)){
                        $model->bac =(int)(($model->heso - $nhomngbac->heso)/$nhomngbac->hesochenhlech) + 1;
                    }
                }else{
                    $model->msngbac = null;
                }
                //Lấy mã chức vụ nếu có
                $chucvu = $model_chucvu->where('macvcq',$model->macvcq)->first();
                if(isset($chucvu)) {
                    $model->macvcq = isset($chucvu) ? $chucvu->macvcq : null;
                }
                $model->save();
            }

            File::Delete($path);
            return redirect('nghiep_vu/ho_so/danh_sach');
        }else
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
        $result['message'] .= '<th class="text-center">Hệ số</br>phụ cấp</th>';
        $result['message'] .= '<th class="text-center">Phụ cấp</br>trách nhiệm</th>';
        $result['message'] .= '<th class="text-center">Phụ cấp</br>kiêm nhiệm</th>';
        $result['message'] .= '<th class="text-center">Phụ cấp</br>đặc thù</th>';
        $result['message'] .= '<th class="text-center">Phụ cấp</br>khác</th>';
        $result['message'] .= '<th class="text-center">Thao tác</th>';


        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';
        $result['message'] .= '<tbody>';
        if (count($model) > 0) {
            foreach ($model as $key => $value) {
                $result['message'] .= '<tr>';
                $result['message'] .= '<td style="text-align: center">' . ($key + 1) . '</td>';
                $result['message'] .= '<td>' . $value->tenphanloai . '</td>';
                $result['message'] .= '<td>' . $value->tenchucvu . '</td>';
                $result['message'] .= '<td>' . $value->hesopc . '</td>';
                $result['message'] .= '<td>' . $value->pctn . '</td>';
                $result['message'] .= '<td>' . $value->pckn . '</td>';
                $result['message'] .= '<td>' . $value->pcdbn . '</td>';
                $result['message'] .= '<td>' . $value->pck . '</td>';
                $result['message'] .= '<td>' .
                    '<button type="button" data-target="#kiemnhiem-modal" data-toggle="modal" class="btn btn-default btn-xs mbs" onclick="edit_kn(' . $value->id . ');"><i class="fa fa-edit"></i>&nbsp;Chỉnh sửa</button>' .
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

    function get_congtac(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphanloaicongtac_baohiem::where('madv',session('admin')->madv)->where('mact',$inputs['mact'])->first();
        die($model);
    }

    function get_chucvu_bh(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmchucvucq::where('macvcq',$inputs['macvcq'])->first();
        die($model);
    }

}