<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\hosotruylinh;
use App\hosotruylinh_nguon;
use App\hosotruylinh_nguon_temp;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\ngachluong;
use App\nhomngachluong;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class hosotruylinhController extends Controller
{
    function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_canbo = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get();
            $model = hosotruylinh::where('madv', session('admin')->madv);
            //dd($model->get());
            if($inputs['thang'] != 'ALL'){
                $model = $model->wheremonth('ngayden',$inputs['thang']);
            }
            if($inputs['nam'] != 'ALL'){
                $model = $model->whereyear('ngayden',$inputs['nam']);
            }
            $a_bangluong = bangluong::select('mabl','thang','nam')->where('madv',session('admin')->madv)
                ->where('phanloai','TRUYLINH')
                ->get()->keyBy('mabl')->toarray();

            $model = $model->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            $a_cv = getChucVuCQ(false);
            foreach($model as $hs){
                $hs->tencvcq = isset($a_cv[$hs->macvcq])?$a_cv[$hs->macvcq] : '';
                if(isset($a_bangluong[$hs->mabl])){
                    $hs->thang = $a_bangluong[$hs->mabl]['thang'];
                    $hs->nam = $a_bangluong[$hs->mabl]['nam'];
                }else{
                    $hs->mabl = null;
                }
            }
            return view('manage.truylinh.index')
                ->with('model', $model->sortby('ngaytu'))
                ->with('inputs', $inputs)
                ->with('a_canbo', array_column($model_canbo->toarray(), 'tencanbo', 'macanbo'))
                ->with('a_pl', getPhanLoaiTruyLinh(false))
                ->with('furl', '/nghiep_vu/truy_linh/')
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('pageTitle', 'Danh sách cán bộ được truy lĩnh lương');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            //$insert['luongcoban'] = getDbl($insert['luongcoban']);
            $insert['thangtl'] = getDbl($insert['thangtl']);
            $insert['ngaytl'] = getDbl($insert['ngaytl']);
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            if($insert['trangthai'] == 'ADD'){
                $insert['madv'] = session('admin')->madv;
                foreach($model_pc as $pc){
                    if(isset($insert[$pc->mapc])){
                        $insert[$pc->mapc] = chkDbl($insert[$pc->mapc]);
                    }
                }
                $insert['pthuong'] = chkDbl($insert['pthuong']);
                $insert['pthuong'] = $insert['pthuong']<0 || $insert['pthuong']>100? 100 : $insert['pthuong'];
                //$insert['maso'] = session('admin')->madv . '_' . getdate()[0];
                hosotruylinh::create($insert);
                DB::statement("Insert into hosotruylinh_nguon(manguonkp,luongcoban,maso) SELECT manguonkp, luongcoban, maso FROM hosotruylinh_nguon_temp WHERE maso='".$insert['maso']."'");
                DB::statement("Delete FROM hosotruylinh_nguon_temp WHERE maso='".$insert['maso']."'");
            }else{

                foreach($model_pc as $pc){
                    if(isset($insert[$pc->mapc])){
                        $insert[$pc->mapc] = chkDbl($insert[$pc->mapc]);
                    }
                }
                //$model = hosotruylinh::where('maso',$insert['maso'])->first();
                $insert['pthuong'] = $insert['pthuong']<0 || $insert['pthuong']>100? 100 : $insert['pthuong'];
                hosotruylinh::where('maso',$insert['maso'])->first()->update($insert);
            }

            return redirect('nghiep_vu/truy_linh/danh_sach?thang=ALL&nam='.date('Y'));
        } else
            return view('errors.notlogin');
    }

    //bỏ
    function update(Request $request)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $model = hosotruylinh::where('maso', $insert['maso_edit'])->first();
            $insert['ngaytu'] = $insert['ngaytu_edit'];
            $insert['msngbac'] = $insert['msngbac_edit'];
            $insert['hesott'] = $insert['hesott_edit'];
            //$insert['luongcoban'] = getDbl($insert['luongcoban']);
            $insert['thangtl'] = getDbl($insert['thangtl']);
            $insert['ngaytl'] = getDbl($insert['ngaytl']);

            $model->update($insert);
            return redirect('nghiep_vu/truy_linh/danh_sach?thang='.date('m').'&nam='.date('Y'));
        } else
            return view('errors.notlogin');
    }

    function getinfo(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = hosotruylinh::where('maso',$inputs['maso'])->first();
        //dd($model);
        die($model);
    }

    function get_thongtin_canbo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = hosocanbo::where('macanbo', $inputs['maso'])->first();
        if ($model == null) {
            die($model);
        }

        $model_nb = ngachluong::where('msngbac', $model->msngbac)->first();
        if ($model_nb == null) {
            die($model);
        }//cán bộ ko có msngbac => ko tính toán

        $model_nhom = nhomngachluong::where('manhom', $model_nb->manhom)->first();
        if ($model_nb->manhom == 'CBCT') {
            if ($model->bac < $model_nb->bacvuotkhung) {
                $model->hesott = $model_nb->hesochenhlech;
            } else {//cán bộ được hưởng vượt khung => hàng năm tự động tăng %vk ko tăng bậc
                if ($model->vuotkhung == $model_nb->vuotkhung) {
                    $model->hesott = ($model_nb->vuotkhung * $model->heso) / 100;
                } else {
                    $model->hesott = $model->heso / 100; //truy lĩnh 1%
                }
            }
        } else {
            if ($model->bac < $model_nhom->bacvuotkhung) {
                $model->hesott = $model_nhom->hesochenhlech;
            } else {//cán bộ được hưởng vượt khung => hàng năm tự động tăng %vk ko tăng bậc
                if ($model->vuotkhung == $model_nhom->vuotkhung) {
                    $model->hesott = ($model_nhom->vuotkhung * $model->heso) / 100;
                } else {
                    $model->hesott = $model->heso / 100; //truy lĩnh 1%
                }
            }
        }

        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosotruylinh::find($id);
            DB::statement("Delete FROM hosotruylinh_nguon_temp WHERE maso='".$model->maso."'");
            //$macanbo = $model->macanbo;
            $model->delete();
            return redirect('nghiep_vu/truy_linh/danh_sach?thang='.date('m').'&nam='.date('Y'));
        } else
            return view('errors.notlogin');
    }

    function destroy_mul(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $maso = explode(',',$inputs['maso']);
            //dd($maso);
            hosotruylinh::wherein('maso',$maso)->delete();
            hosotruylinh_nguon::wherein('maso',$maso)->delete();
            return redirect('nghiep_vu/truy_linh/danh_sach?thang='.date('m').'&nam='.date('Y'));
        } else
            return view('errors.notlogin');
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_pl = getPhanLoaiTruyLinh();
            $a_nkp = getNguonKP(false);

            //msngbac: kiểm tra xem mã ngạch bậc: xem lấy mã số xem truy lĩnh hệ số hay vượt khung
            //tnn: tính 1% thâm niên nghề. Các hệ số ko có
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            if(isset($inputs['maso'])){
                $inputs['trangthai'] = 'EDIT';
                $model = hosotruylinh::where('maso',$inputs['maso'])->first();
                $model_nkp = hosotruylinh_nguon::where('maso',$inputs['maso'])->get();
                $model->tentruylinh = isset($a_pl[$model->maphanloai]) ? $a_pl[$model->maphanloai] : '';
                //dd($model_nkp);
                switch($model->maphanloai){
                    case 'MSNGBAC':{
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'CHUCVU':{
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'TNN':{
                        //dd($model);
                        return view('manage.truylinh.create_tnn')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }

                    case 'NGAYLV':{
                        return view('manage.truylinh.create_ngaylv')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'KHAC':{
                        return view('manage.truylinh.create_khac')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }

                    default:{
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                }
            }else{
            //thêm mới
                $inputs['trangthai'] = 'ADD';
                $model = hosocanbo::where('macanbo',$inputs['macanbo'])->first();
                $model->ngaytu = null;
                $model->ngayden = null;
                $model->maso = session('admin')->madv . '_' . getdate()[0];

                $model->maphanloai = $inputs['maphanloai'];
                $model->tentruylinh = isset($a_pl[$model->maphanloai]) ? $a_pl[$model->maphanloai] : '';

                foreach(getNguonTruyLinh_df() as $nkp=>$luongcb){
                    $m_nguon = new hosotruylinh_nguon_temp();
                    $m_nguon->macanbo = $model->macanbo;
                    $m_nguon->maso = $model->maso;
                    $m_nguon->manguonkp = $nkp;
                    $m_nguon->luongcoban = $luongcb;
                    $m_nguon->madv = session('admin')->madv;
                    $m_nguon->save();
                }
                $model_nkp = hosotruylinh_nguon_temp::where('maso',$model->maso)->get();
                //dd($model_nkp);
                //dd($a_nkp);
                switch($inputs['maphanloai']){
                    case 'MSNGBAC':{
                        $heso = 0;
                        $a_nhomnb = ngachluong::all()->keyBy('msngbac')->toarray();
                        $msngbac = $model->msngbac;
                        if(isset($a_nhomnb[$msngbac])) {
                            $nhomnb = $a_nhomnb[$msngbac];
                            //hesolonnhat
                            //$hesomax = $nhomnb['heso'] + ($nhomnb['heso'] * $nhomnb['hesochenhlech']);
                            $hesomax = $nhomnb['hesolonnhat'];
                            if ($model->heso >= $hesomax) {
                                $vuotkhung = $model->vuotkhung == 0 ? $nhomnb['vuotkhung'] : 1;
                                $heso = ($vuotkhung * $model->heso) / 100;
                            } else {
                                $heso = $nhomnb['hesochenhlech'];
                            }
                        }
                        foreach($model_pc as $pc){
                            if($pc->phanloai != 2){
                                $mapc = $pc->mapc;
                                $model->$mapc = 0;
                            }

                        }
                        $model->heso = $heso;
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        $model_pc = $model_pc->where('phanloai',2);

                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;

                    }
                    case 'CHUCVU':{
                        foreach($model_pc as $pc){
                            if($pc->phanloai != 2){
                                $mapc = $pc->mapc;
                                $model->$mapc = 0;
                            }
                        }
                        $model->heso = 0;
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        $model_pc = $model_pc->where('phanloai',2);
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;

                    }
                    case 'TNN':{
                        $heso = ($model->heso + ($model->heso * $model->vuotkhung)/100 + $model->pccv)/100;
                        foreach($model_pc as $pc){
                            $mapc = $pc->mapc;
                            $model->$mapc = 0;
                        }
                        $model->heso = $heso;
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        $model_pc = null;
                        return view('manage.truylinh.create_tnn')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'NGAYLV':{
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        return view('manage.truylinh.create_ngaylv')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                    case 'KHAC':{
                        $model->luongcoban = getGeneralConfigs()['luongcb'];
                        return view('manage.truylinh.create_khac')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_heso', array('hesott'))
                            ->with('model_pc', $model_pc)
                            ->with('a_nkp',$a_nkp)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }

                    default:{
                        return view('manage.truylinh.create')
                            ->with('furl', '/nghiep_vu/truy_linh/')
                            ->with('inputs',$inputs)
                            ->with('model',$model)
                            ->with('model_nkp',$model_nkp)
                            ->with('a_nkp',$a_nkp)
                            ->with('a_heso', array('heso','vuotkhung'))
                            ->with('model_pc', $model_pc)
                            ->with('pageTitle', 'Thêm mới cán bộ truy lĩnh lương');
                        break;
                    }
                }
            }
        } else
            return view('errors.notlogin');
    }

    function create_all(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $sott = getdate()[0]; //lưu mã số
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->wherein('phanloai',['0','2'])->get();
            $a_th = array_merge(array('macanbo', 'pthuong', 'tencanbo', 'madv', 'macvcq', 'mapb', 'stt', 'mact'),
                array_column($model_pc->toarray(),'mapc'));
            $a_thkn = array_merge(array('macanbo', 'pthuong', 'phanloai', 'madv', 'macvcq', 'mapb', 'mact'),
                array_column($model_pc->toarray(),'mapc'));

            $a_data = array();
            $a_nkp = array();
            if (in_array('ALL', $inputs['mact'])) {
                $m_hs = hosocanbo::select($a_th)->where('madv',session('admin')->madv)->where('theodoi','<','9')->get();
                $m_hskn = hosocanbo_kiemnhiem::select($a_thkn)->where('madv',session('admin')->madv)->get();
            } else {
                $m_hs = hosocanbo::select($a_th)->where('madv',session('admin')->madv)
                    ->wherein('mact',$inputs['mact'])
                    ->where('theodoi','<','9')->get();
                $m_hskn = hosocanbo_kiemnhiem::select($a_thkn)->where('madv',session('admin')->madv)
                    ->wherein('mact',$inputs['mact'])
                    ->get();
            }

            foreach($m_hs as $hs) {
                $maso = $hs->madv . '_' . $sott++;
                $hs->maphanloai = $inputs['maphanloai'];
                $hs->phanloai = 'CONGTAC';
                $hs->maso = $maso;
                $hs->thangtl = $inputs['thangtl'];
                $hs->ngaytl = $inputs['ngaytl'];
                $hs->ngaytu = $inputs['ngaytu'];
                $hs->ngayden = $inputs['ngayden'];
                $a_data[] = $hs->toarray();
                $a_nkp[] = array(
                    'maso' => $maso,
                    'manguonkp' => $inputs['manguonkp'],
                    'luongcoban' => $inputs['luongcoban'],
                );
            }

            foreach($m_hskn as $hs) {
                $hoso = $m_hs->where('macanbo',$hs->macanbo);
                if(count($hoso) == 0){
                    continue;
                }
                $maso = $hs->madv . '_' . $sott++;
                $hs->maphanloai = $inputs['maphanloai'];
                $hs->tencanbo = $hoso->first()->tencanbo;
                $hs->stt = $hoso->first()->stt;
                $hs->maso = $maso;
                $hs->thangtl = $inputs['thangtl'];
                $hs->ngaytl = $inputs['ngaytl'];
                $hs->ngaytu = $inputs['ngaytu'];
                $hs->ngayden = $inputs['ngayden'];
                $a_data[] = $hs->toarray();
                $a_nkp[] = array(
                    'maso' => $maso,
                    'manguonkp' => $inputs['manguonkp'],
                    'luongcoban' => $inputs['luongcoban'],
                );
            }
            hosotruylinh::insert($a_data);
            hosotruylinh_nguon::insert($a_nkp);
            return redirect('nghiep_vu/truy_linh/danh_sach?thang=ALL&nam='.date('Y'));
        } else
            return view('errors.notlogin');
    }

    function getinfor_nkp(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        if($inputs['trangthai']){
            die(hosotruylinh_nguon_temp::find($inputs['id']));
        }else{
            die(hosotruylinh_nguon::find($inputs['id']));
        }
    }

    function store_nkp(Request $request)
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
        $insert = $request->all();
        $insert['luongcoban'] = getDbl($insert['luongcoban']);
        if($insert['trangthai'] == 'ADD'){//thao tác vào bảng tạm
            $model_chk = hosotruylinh_nguon_temp::where('maso', $insert['maso'])->where('manguonkp', $insert['manguonkp'])->first();
            if($model_chk != null){
                $model_chk->luongcoban = $insert['luongcoban'];
                $model_chk->manguonkp = $insert['manguonkp'];
                $model_chk->save();
            }else{
                $model = new hosotruylinh_nguon_temp();
                $model->maso = $insert['maso'];
                $model->manguonkp = $insert['manguonkp'];
                $model->luongcoban = $insert['luongcoban'];
                $model->save();
            }
            $model = hosotruylinh_nguon_temp::where('maso', $insert['maso'])->get();
        }else{//thao tác vào bảng gốc
            $model_chk = hosotruylinh_nguon::where('maso', $insert['maso'])->where('manguonkp', $insert['manguonkp'])->first();
            if($model_chk != null){
                $model_chk->luongcoban = $insert['luongcoban'];
                $model_chk->manguonkp = $insert['manguonkp'];
                $model_chk->save();
            }else{
                $model = new hosotruylinh_nguon();
                $model->maso = $insert['maso'];
                $model->manguonkp = $insert['manguonkp'];
                $model->luongcoban = $insert['luongcoban'];
                $model->save();
            }
            $model = hosotruylinh_nguon::where('maso', $insert['maso'])->get();
        }

        $result = $this->retun_html_kn($result, $model);

        die(json_encode($result));
    }

    function destroy_nkp(Request $request)
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
        if($inputs['trangthai'] == 'ADD'){//thao tác vào bảng tạm
            $model = hosotruylinh_nguon_temp::find($inputs['id']);
            $model->delete();
            $model = hosotruylinh_nguon_temp::where('maso', $model->maso)->get();
        }else{
            $model = hosotruylinh_nguon::find($inputs['id']);
            $model->delete();
            $model = hosotruylinh_nguon::where('maso', $model->maso)->get();
        }
        //$a_pl = getPhanLoaiKiemNhiem();
        $result = $this->retun_html_kn($result, $model);
        die(json_encode($result));
    }

    function retun_html_kn($result, $model)
    {
        $a_nkp = getNguonKP(false);
        $result['message'] = '<div class="row" id="dsnkp">';
        $result['message'] .= '<div class="col-md-12">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_4">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr>';
        $result['message'] .= '<th class="text-center" style="width: 5%">STT</th>';
        $result['message'] .= '<th class="text-center">Nguồn kinh phí</th>';
        $result['message'] .= '<th class="text-center" style="width: 15%">Số tiền</th>';
        $result['message'] .= '<th class="text-center" style="width: 15%">Thao tác</th>';

        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';
        $result['message'] .= '<tbody>';
        if (count($model) > 0) {
            foreach ($model as $key => $value) {
                $result['message'] .= '<tr>';
                $result['message'] .= '<td style="text-align: center">' . ($key + 1) . '</td>';
                $result['message'] .= '<td>' . (isset($a_nkp[$value->manguonkp]) ? $a_nkp[$value->manguonkp] : '') . '</td>';
                $result['message'] .= '<td>' . dinhdangso($value->luongcoban) . '</td>';
                $result['message'] .= '<td>' .
                    '<button type="button" data-target="#nguonkp-modal" data-toggle="modal" class="btn btn-default btn-xs mbs" onclick="edit_nkp(&#39;' . $value->id . '&#39;);"><i class="fa fa-edit"></i>&nbsp;Sửa</button>' .
                    '<button type="button" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal" onclick="cfDel(&#39;'.$value->id. '&#39;)" ><i class="fa fa-trash-o"></i>&nbsp;Xóa</button>'

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
}
