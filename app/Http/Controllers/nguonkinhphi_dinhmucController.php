<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\nguonkinhphi;
use App\nguonkinhphi_dinhmuc;
use App\nguonkinhphi_dinhmuc_ct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class nguonkinhphi_dinhmucController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi_dinhmuc::where('madv', session('admin')->madv)->get();
            $a_pl = getNguonKP(false);
            $a_ct = array('ALL' => 'Tất cả');
            foreach(getPhanLoaiCT(false) as $k=>$v){
                $a_ct[$k] = $v;
            }
            foreach($model as $ct){
                foreach(explode(',',$ct->mact) as $val){
                    $ct->tenct .= isset($a_ct[$val]) ? ($a_ct[$val].'; ') : '';
                }
                $ct->tennguonkp = isset($a_pl[$ct->manguonkp]) ? $a_pl[$ct->manguonkp] : '';
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            return view('system.danhmuc.dinhmucnguon.index')
                ->with('model', $model)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('furl', '/he_thong/dinh_muc/')
                ->with('pageTitle', 'Danh mục định mức nguồn');
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
        $model = nguonkinhphi_dinhmuc::where('maso',$inputs['maso'])->first();
        //$a_pl = getNguonKP(false);
        //$model->tennguonkp = isset($a_pl[$model->manguonkp]) ? $a_pl[$model->manguonkp] : '';
        die($model);
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            if (in_array('ALL', $inputs['mact'])) {
                $mact = 'ALL';
            } else {
                $mact = implode(',', $inputs['mact']);
            }

            if ($inputs['maso'] == 'ADD') {//thêm mới
                $m_chk = nguonkinhphi_dinhmuc::where('manguonkp', $inputs['manguonkp'])->where('madv', session('admin')->madv)->get();
                if (count($m_chk) == 0) {//chưa có định mức với mã nguồn kp
                    $model = new nguonkinhphi_dinhmuc();
                    $model->maso = session('admin')->madv . '_' . getdate()[0];
                    $model->mact = $mact;
                    $model->manguonkp = $inputs['manguonkp'];
                    $model->luongcoban = chkDbl($inputs['luongcoban']);
                    $model->baohiem = $inputs['baohiem'];
                    $model->madv = session('admin')->madv;
                    $model->save();
                } else {//đã có định mức => kiểm tra phân loại công tác
                    $a_ct = getPhanLoaiCT(false);
                    $a_nkp = getNguonKP(false);

                    if ($mact == 'ALL') {
                        return view('errors.data_error')
                            ->with('message', $a_nkp[$inputs['manguonkp']] . ' đã thiết lập định mức nguồn nên không thể thêm định mức nguồn cho tất cả các phân loại công tác.')
                            ->with('furl', '/he_thong/dinh_muc/danh_sach');
                    }

                    $a_plct = array();
                    foreach ($m_chk as $ct) {
                        $a_plct = array_merge($a_plct, explode(',', $ct->mact));
                    }

                    if (in_array('ALL', $a_plct)) {
                        return view('errors.data_error')
                            ->with('message', $a_nkp[$inputs['manguonkp']] . ' đã thiết lập định mức nguồn cho tất cả các phân loại công tác.')
                            ->with('furl', '/he_thong/dinh_muc/danh_sach');
                    }

                    $b_chk = true;
                    $mess = '';

                    foreach ($inputs['mact'] as $ma) {
                        if (in_array($ma, $a_plct)) {
                            $b_chk = false;
                            $mess .= $a_ct[$ma] . '; ';
                        }
                    }
                    if ($b_chk) {//thêm mới
                        $model = new nguonkinhphi_dinhmuc();
                        $model->maso = session('admin')->madv . '_' . getdate()[0];
                        $model->mact = $mact;
                        $model->manguonkp = $inputs['manguonkp'];
                        $model->luongcoban = chkDbl($inputs['luongcoban']);
                        $model->baohiem = $inputs['baohiem'];
                        $model->madv = session('admin')->madv;
                        $model->save();
                    } else {//báo lỗi
                        return view('errors.data_error')
                            ->with('message', 'Phân loại công tác: ' . $mess . 'đã được thiết lập định mức nguồn.')
                            ->with('furl', '/he_thong/dinh_muc/danh_sach');
                    }
                }
            } else {//update
                $a_ct = getPhanLoaiCT(false);
                $a_nkp = getNguonKP(false);

                //lấy danh mục phân loại công tác theo định mức (trừ đinh mức ra)
                $m_goc = nguonkinhphi_dinhmuc::where('maso', $inputs['maso'])->first();

                $m_chk = nguonkinhphi_dinhmuc::where('manguonkp', $m_goc->manguonkp)
                    ->where('maso', '<>', $inputs['maso'])
                    ->where('madv', session('admin')->madv)->get();
                
                if(count($m_chk)>0){
                    if ($mact == 'ALL') {
                        return view('errors.data_error')
                            ->with('message', $a_nkp[$m_goc->manguonkp] . ' đã thiết lập định mức nguồn nên không thể thêm định mức nguồn cho tất cả các phân loại công tác.')
                            ->with('furl', '/he_thong/dinh_muc/danh_sach');
                    }

                    $a_plct = array();
                    foreach ($m_chk as $ct) {
                        $a_plct = array_merge($a_plct, explode(',', $ct->mact));
                    }
                    $b_chk = true;
                    $mess = '';

                    foreach ($inputs['mact'] as $ma) {
                        if (in_array($ma, $a_plct)) {
                            $b_chk = false;
                            $mess .= $a_ct[$ma] . '; ';
                        }
                    }
                    if ($b_chk) {//thêm mới
                        $m_goc->mact = $mact;
                        $m_goc->luongcoban = chkDbl($inputs['luongcoban']);
                        $m_goc->baohiem = $inputs['baohiem'];
                        $m_goc->save();
                    } else {//báo lỗi
                        return view('errors.data_error')
                            ->with('message', 'Phân loại công tác: ' . $mess . 'đã được thiết lập định mức nguồn.')
                            ->with('furl', '/he_thong/dinh_muc/danh_sach');
                    }

                }else{
                    $m_goc->mact = $mact;
                    $m_goc->luongcoban = chkDbl($inputs['luongcoban']);
                    $m_goc->baohiem = $inputs['baohiem'];
                    $m_goc->save();
                }
            }

            return redirect('/he_thong/dinh_muc/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function destroy(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            DB::statement("Delete From nguonkinhphi_dinhmuc where maso = '".$inputs['maso_del']."';");
            DB::statement("Delete From nguonkinhphi_dinhmuc_ct where maso = '".$inputs['maso_del']."';");
            return redirect('/he_thong/dinh_muc/danh_sach');
        } else
            return view('errors.notlogin');
    }

    public function phucap(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_nguon = nguonkinhphi_dinhmuc::where('maso', $inputs['maso'])->first();
            $model = nguonkinhphi_dinhmuc_ct::where('maso', $inputs['maso'])->get();
            $a_pc = array_column($model->toarray(), 'mapc');
            $m_phucap = dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai','<',3)->get();
            $m_pc_dm = $m_phucap->wherein('mapc', $a_pc);
            $m_pc = $m_phucap->diff($m_pc_dm);
                //->wherenotin('mapc', $a_pc)->get();
            //$model_phucap = dmphucap_donvi::wherenotin('mapc', $a_pc)->get();
            return view('system.danhmuc.dinhmucnguon.details')
                ->with('model', $model)
                ->with('model_nguon', $model_nguon)
                ->with('model_phucap', array_column($m_pc->toarray(), 'tenpc', 'mapc'))
                ->with('model_phucap_dm', array_column($m_pc_dm->toarray(), 'tenpc', 'mapc'))
                ->with('furl', '/he_thong/dinh_muc/')
                ->with('pageTitle', 'Danh mục định mức nguồn');
        } else
            return view('errors.notlogin');
    }

    function store_pc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['tenpc'] = dmphucap_donvi::where('madv', session('admin')->madv)->where('mapc',$inputs['mapc'])->first()->tenpc;
            $inputs['madv'] = session('admin')->madv;
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['tinhtheodm'] = $inputs['tinhtheodm'];
            nguonkinhphi_dinhmuc_ct::create($inputs);
            return redirect('/he_thong/dinh_muc/phu_cap?maso='.$inputs['maso']);
        } else
            return view('errors.notlogin');
    }

    function update_luongcb(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $model = nguonkinhphi_dinhmuc_ct::where('maso', $inputs['maso'])
                ->wherein('mapc', $inputs['mapc'])
                ->get();
            foreach($model as $ct){
                $ct->tinhtheodm = $inputs['tinhtheodm'];
                $ct->luongcoban = $inputs['luongcoban'];
                $ct->save();
            }

            return redirect('/he_thong/dinh_muc/phu_cap?maso='.$inputs['maso']);
        } else
            return view('errors.notlogin');
    }

    function destroy_pc($id){
        if (Session::has('admin')) {
            $model = nguonkinhphi_dinhmuc_ct::findOrFail($id);
            $model->delete();
            return redirect('/he_thong/dinh_muc/phu_cap?maso='.$model->maso);
        }else
            return view('errors.notlogin');
    }

    function getinfor_ct(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = nguonkinhphi_dinhmuc_ct::find($inputs['id']);
        $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->get()->toarray(), 'tenpc', 'mapc');

        $model->tenpc = isset($a_pc[$model->mapc]) ? $a_pc[$model->mapc] : '';
        die($model);
    }

    function update_ct(Request $request){
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
        nguonkinhphi_dinhmuc_ct::find($inputs['id'])->update(['luongcoban'=>$inputs['luongcoban'],'tinhtheodm'=>$inputs['tinhtheodm']]);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }
}
