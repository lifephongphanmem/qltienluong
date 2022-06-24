<?php

namespace App\Http\Controllers;

use App\dmdantoc;
use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\hosocanbo_kiemnhiem;
use App\hosocanbo_kiemnhiem_temp;
use App\hosocongtac;
use App\hosodaotao;
use App\hosollvt;
use App\hosoluong;
use App\hosonhansu;
use App\hosoquanhegd;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class hosonhansuController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $m_hs = hosonhansu::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();
            $a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            foreach ($m_hs as $hs) {
                $hs->tenpb = isset($a_pb[$hs->mapb]) ? $a_pb[$hs->mapb] : '';
                $hs->tencvcq = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq] : '';
                $hs->tenct = isset($a_ct[$hs->mact]) ? $a_ct[$hs->mact] : '';
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();

            $model = $m_hs->sortBy('stt');
            return view('manage.hosonhansu.index')
                ->with('model', $model)
                ->with('url', '/nghiep_vu/nhan_su/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle', 'Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    function create(){
        if (Session::has('admin')) {
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'), 'dantoc')->get()->toarray(), 'dantoc', 'maso');

            $m_linhvuc = getLinhVucHoatDong(false);
            //$m_cvd= dmchucvud::all();
            $m_plnb = nhomngachluong::select('manhom', 'tennhom', 'heso', 'namnb')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong','manhom','msngbac','heso','namnb','hesolonnhat','bacvuotkhung')->get();
            
            $macanbo = session('admin')->madv . '_' . getdate()[0];

            $max_stt = getDbl((hosonhansu::where('madv', session('admin')->madv)->get()->max('stt'))) + 1;
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            $model_pc_bh = array_column( $model_pc->where('baohiem',1)->toarray(),'tenpc','mapc');
            //dd($model_pc_bh);
            return view('manage.hosonhansu.create')
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
            if(hosonhansu::where('macanbo',$macanbo)->first() != null){
                return redirect('nghiep_vu/nhan_su/danh_sach');
            }

            //Xử lý file ảnh
            //dd($request->file('anh'));
            $img = $request->file('anh');
            $filename = '';
            if (isset($img)) {
                $filename = $macanbo . '_' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
            }

            if(isset($inputs['khongnopbaohiem'])){
                $inputs['khongnopbaohiem'] = implode(',', $inputs['khongnopbaohiem']);
            }else{
                $inputs['khongnopbaohiem'] = '';
            }
            //dd($inputs);
            $insert['anh'] = ($filename == '' ? '' : '/data/uploads/anh/' . $filename);
            $insert['madv'] = $madv;
            $insert['ngaybc'] = getDateTime($insert['ngaybc']);
            $insert['ngaysinh'] = getDateTime($insert['ngaysinh']);
            $insert['ngaytu'] = getDateTime($insert['ngaytu']);
            $insert['ngayden'] = getDateTime($insert['ngayden']);
            $insert['tnntungay'] = getDateTime($insert['tnntungay']);
            $insert['tnndenngay'] = getDateTime($insert['tnndenngay']);

            $insert['ngaycap'] = getDateTime($insert['ngaycap']);
            $insert['ngaytd'] = getDateTime($insert['ngaytd']);
            $insert['ngayvao'] = getDateTime($insert['ngayvao']);
            $insert['ngayctctxh'] = getDateTime($insert['ngayctctxh']);
            $insert['ngayvd'] = getDateTime($insert['ngayvd']);
            $insert['ngayvdct'] = getDateTime($insert['ngayvdct']);

            $insert['pthuong']= chkDbl($insert['pthuong']) == 0 ? 100 :chkDbl($insert['pthuong']) ;
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

            hosonhansu::create($insert);

            $model_kn = hosocanbo_kiemnhiem_temp::where('macanbo',$macanbo)->get();
            foreach($model_kn as $ct){
                $a_kq = $ct->toarray();
                unset($a_kq['id']);
                hosocanbo_kiemnhiem::create($a_kq);
            }
            return redirect('nghiep_vu/nhan_su/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function show($id){
        if (Session::has('admin')) {
            $model = hosonhansu::find($id);
            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'),'dantoc')->get()->toarray(),'dantoc','maso');

            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc = getLinhVucHoatDong(false);
            $m_plnb = nhomngachluong::select('manhom','tennhom')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong','manhom','msngbac','heso','namnb','hesolonnhat','bacvuotkhung')->get();
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
            $model_pc_bh = array_column( $model_pc->where('baohiem',1)->toarray(),'tenpc','mapc');
            return view('manage.hosonhansu.edit')
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
                ->with('furl_kn', '/nghiep_vu/nhan_su/')
                ->with('a_heso', array('heso', 'vuotkhung', 'luonghd', 'hesott'))
                ->with('a_pc_bh', $model_pc_bh)
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
            return redirect('nghiep_vu/nhan_su/danh_sach');

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
            $model = hosonhansu::find($id);
            //Xử lý file ảnh
            $img = $request->file('anh');
            if ($insert['bl_xoaanh'] == 'true') {//dùng chức năng xóa ảnh đại diện
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

            if(isset($insert['khongnopbaohiem'])){
                $insert['khongnopbaohiem'] = implode(',', $insert['khongnopbaohiem']);
            }else{
                $insert['khongnopbaohiem'] = '';
            }

            //dd($insert);
            $insert['ngaybc'] = getDateTime($insert['ngaybc']);
            $insert['ngaysinh'] = getDateTime($insert['ngaysinh']);
            $insert['ngaytu'] = getDateTime($insert['ngaytu']);
            $insert['ngayden'] = getDateTime($insert['ngayden']);
            $insert['pthuong'] = chkDbl($insert['pthuong']) == 0 ? 100 : chkDbl($insert['pthuong']);
            $insert['tnntungay'] = getDateTime($insert['tnntungay']);
            $insert['tnndenngay'] = getDateTime($insert['tnndenngay']);

            $insert['ngaycap'] = getDateTime($insert['ngaycap']);
            $insert['ngaytd'] = getDateTime($insert['ngaytd']);
            $insert['ngayvao'] = getDateTime($insert['ngayvao']);
            $insert['ngayctctxh'] = getDateTime($insert['ngayctctxh']);
            $insert['ngayvd'] = getDateTime($insert['ngayvd']);
            $insert['ngayvdct'] = getDateTime($insert['ngayvdct']);

            $model_pc = dmphucap_donvi::select('mapc')->where('madv', session('admin')->madv)->get()->toarray();
            foreach ($model_pc as $pc) {
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

            $model->update($insert);
            return redirect('nghiep_vu/nhan_su/danh_sach');
        } else
            return view('errors.notlogin');
    }

    //đang làm
    function syll($id){
        if (Session::has('admin')) {
            $model = hosonhansu::find($id);
            $macanbo = $model->macanbo;

            $a_ct = getPhanLoaiCT(false);
            $a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            $a_nb = getNgachLuong();

            $model->tenviethoa = Str::upper($model->tencanbo);
            $model->tenpb = isset($a_pb[$model->mapb]) ? $a_pb[$model->mapb] : '';
            $model->tencvcq = isset($a_cv[$model->macvcq]) ? $a_cv[$model->macvcq] : '';
            $model->tenct = isset($a_ct[$model->mact]) ? $a_ct[$model->mact] : '';
            $model->tennb = isset($a_nb[$model->msngbac]) ? $a_nb[$model->msngbac] : '';

            $m_llvt = hosollvt::where('macanbo', $macanbo)->first();

            $m_daotao = hosodaotao::where('macanbo', $macanbo)->orderby('ngaytu')->get();
            $m_congtac = hosocongtac::where('macanbo', $macanbo)->orderby('ngaytu')->get();
            $m_qhbt = hosoquanhegd::join('dmquanhegd', 'hosoquanhegd.quanhe', '=', 'dmquanhegd.quanhe')
                ->where('hosoquanhegd.macanbo', $macanbo)
                ->where('hosoquanhegd.phanloai', 'Bản thân')
                ->orderby('dmquanhegd.sapxep')->get();
            $m_qhvc = hosoquanhegd::join('dmquanhegd', 'hosoquanhegd.quanhe', '=', 'dmquanhegd.quanhe')
                ->where('hosoquanhegd.macanbo', $macanbo)
                ->where('hosoquanhegd.phanloai', 'Vợ chồng')
                ->orderby('dmquanhegd.sapxep')->get();

            $luong = hosoluong::select('ngaytu', DB::raw('CONCAT(msngbac, "/", bac) AS ngachbac'), 'heso')->where('macanbo', $macanbo)->orderby('ngaytu')->get()->toarray();

            $thang = array_column($luong, 'ngaytu');
            $msngbac = array_column($luong, 'ngachbac');
            $heso = array_column($luong, 'heso');

            $m_luong = array();
            $m_luong[] = $thang;
            $m_luong[] = $msngbac;
            $m_luong[] = $heso;

            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($model);
            return view('reports.QD02.soyeulylich')
                ->with('model', $model)
                ->with('m_llvt', $m_llvt)
                ->with('m_daotao', $m_daotao)
                ->with('m_congtac', $m_congtac)
                ->with('m_qhbt', $m_qhbt)
                ->with('m_qhvc', $m_qhvc)
                ->with('m_luong', $m_luong)
                ->with('m_donvi', $m_donvi)
                ->with('pageTitle', 'Sơ yếu lý lịch');
        } else
            return view('errors.notlogin');
    }

    function tomtatts($id){
        if (Session::has('admin')) {
            $model = hosonhansu::find($id);
            $macanbo = $model->macanbo;
            $a_pb = getPhongBan(false);//dmphongban::all('mapb','tenpb')->toArray();
            $a_cv = getChucVuCQ(false);
            $a_nb = getNgachLuong();

            $model->tenpb = isset($a_pb[$model->mapb]) ? $a_pb[$model->mapb] : '';
            $model->tencvcq = isset($a_cv[$model->macvcq]) ? $a_cv[$model->macvcq] : '';
            $model->tenviethoa = Str::upper($model->tencanbo);
            $model->tennb = isset($a_nb[$model->msngbac]) ? $a_nb[$model->msngbac] : '';

            $m_llvt = hosollvt::where('macanbo', $macanbo)->first();
            $m_congtac = hosocongtac::where('macanbo', $macanbo)->orderby('ngaytu')->get();

            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //dd($m_congtac);
            return view('reports.QD02.tomtattieusu')
                ->with('model', $model)
                ->with('m_llvt', $m_llvt)
                ->with('m_congtac', $m_congtac)
                ->with('m_donvi', $m_donvi)
                ->with('pageTitle', 'Tóm tắt tiểu sử');
        } else
            return view('errors.notlogin');
    }

    function bsll(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $ngaytu=$inputs['ngaytu'];
            $ngayden=$inputs['ngayden'];

            $model=hosocanbo::find($inputs['idct']);
            $macanbo=$model->macanbo;
            $m_pb=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            $m_nb=ngachbac::select('tennb', 'msngbac')->distinct()->get()->toArray();

            $model->tenpb=getInfoPhongBan($model,$m_pb);
            $model->tencvcq=getInfoChucVuCQ($model,$m_cvcq);
            $model->tenviethoa=Str::upper($model->tencanbo);
            $model->tennb=getInfoTenNB($model,$m_nb);

            $m_cv=hosochucvu::where('macanbo',$macanbo)->where('ngayden','>=',$ngaytu)->get();
            foreach($m_cv as $cv){
                $cv->tencvcq=getInfoChucVuCQ($cv,$m_cvcq);
            }
            //Chỉ lấy bằng cấp nào đã học xong trong khoảng thời gian kết xuất
            $m_daotao=hosodaotao::where('macanbo',$macanbo)->wherebetween('ngayden',array($ngaytu,$ngayden))->orderby('ngaytu')->get();
            $m_kt=hosokhenthuong::where('macanbo',$macanbo)->wherebetween('ngaythang',array($ngaytu,$ngayden))->orderby('ngaythang')->get();
            $m_kl=hosokyluat::where('macanbo',$macanbo)->wherebetween('ngaythang',array($ngaytu,$ngayden))->orderby('ngaythang')->get();

            $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
            $m_donvi->ngaytu=$ngaytu;
            $m_donvi->ngayden=$ngayden;
            //dd($model);
            return view('reports.QD02.bosunglylich')
                ->with('model',$model)
                ->with('m_cv',$m_cv)
                ->with('m_daotao',$m_daotao)
                ->with('m_kt',$m_kt)
                ->with('m_kl',$m_kl)
                ->with('m_donvi',$m_donvi)
                ->with('pageTitle','Phiếu bổ sung lý lịch');
        } else
            return view('errors.notlogin');
    }
}
