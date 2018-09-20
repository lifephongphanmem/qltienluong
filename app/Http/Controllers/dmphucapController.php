<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class dmphucapController extends Controller
{
    public function fix_mapc(){
        //chạy update cho cao bằng, lạng sơn
        $model_donvi = dmdonvi::all();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','hesobl')->first();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pcd')->first();
        $model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pctdt')->first();
        //$phucap = dmphucap::where('mapc','hesobl')->first();
        foreach($model_donvi as $donvi){
            $model_phucap = dmphucap_donvi::where('madv',$donvi->madv)->get();
            if(count($model_phucap)>0){
                $chekbl = $model_phucap->where('mapc','pctdt');
                //$chekbl = $model_phucap->where('mapc','hesobl');
                if(count($chekbl)== 0){
                    $model_dmpc->madv = $donvi->madv;
                    dmphucap_donvi::insert($model_dmpc->toarray());
                }
            }
        }
        dd('ok');
    }

    public function fix_ct(){
        //chạy update cho cao bằng, lạng sơn
        $model_donvi = dmdonvi::all();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','hesobl')->first();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pcd')->first();
        $model_dmpc = dmphanloaict::select('mact', 'macongtac')->where('mact','1537427170')->first();
        //$phucap = dmphucap::where('mapc','hesobl')->first();
        foreach($model_donvi as $donvi){
            $model_phucap = dmphanloaicongtac_baohiem::where('madv',$donvi->madv)->get();
            if(count($model_phucap)>0){
                $chekbl = $model_phucap->where('mact','1537427170');
                //$chekbl = $model_phucap->where('mapc','hesobl');
                if(count($chekbl)== 0){
                    $model_dmpc->madv = $donvi->madv;
                    dmphanloaicongtac_baohiem::insert($model_dmpc->toarray());
                }
            }
        }
        dd('ok');
    }

    public function index()
    {
        if (Session::has('admin')) {
            $model = dmphucap::all();//default
            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC();
            foreach ($model as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                $ct->tencongthuc = '';
                foreach ($congthuc as $bg) {
                    $ct->tencongthuc .= isset($a_ct[$bg]) ? ($a_ct[$bg] . '; ') : '';
                }
            }
            return view('system.danhmuc.phucap.index')
                ->with('model', $model)
                ->with('furl', '/he_thong/phu_cap/')
                ->with('pageTitle', 'Danh mục phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function create()
    {
        if (Session::has('admin')) {
            return view('system.danhmuc.phucap.create')
                ->with('furl', '/he_thong/phu_cap/')
                ->with('pageTitle', 'Thêm mới phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['congthuc'] = getDbl($inputs['phanloai']) == 2 ? $inputs['congthuc'] : '';
            dmphucap::create($inputs);
            return redirect('/he_thong/phu_cap/index');
        } else
            return view('errors.notlogin');
    }

    function update(Request $request)
    {
        if (Session::has('admin')) {
            //kiểm tra lại quyền của User (đi tập huấn một số đơn vị ko hiểu sao lại cập nhật luôn vào danh mục
            if(session('admin')->level !='SA' && session('admin')->level !='SSA'){
                Session::flush();
                return view('errors.notlogin');
            }

            $inputs = $request->all();
            $inputs['congthuc'] = getDbl($inputs['phanloai']) == 2 ? $inputs['congthuc'] : '';
            dmphucap::where('mapc',$inputs['mapc'])->first()->update($inputs);
            return redirect('/he_thong/phu_cap/index');
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model =  dmphucap::where('mapc',$inputs['maso'])->first();//default

            ///dd($model);
            return view('system.danhmuc.phucap.edit')
                ->with('model', $model)
                ->with('furl', '/he_thong/phu_cap/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
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
        $model = dmphucap::where('mapc',$inputs['mapc'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmphucap::findOrFail($id);
            $model->delete();
            return redirect('/he_thong/phu_cap/index');
        }else
            return view('errors.notlogin');
    }

    function index_donvi()
    {
        if (Session::has('admin')) {
            $a_lock = array('heso', 'vuotkhung', 'hesott','hesobl');
            $model = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc',$a_lock)->get();

            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC();
            foreach ($model as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                $ct->tencongthuc = '';
                foreach ($congthuc as $bg) {
                    $ct->tencongthuc .= isset($a_ct[$bg]) ? ($a_ct[$bg] . '; ') : '';
                }
            }
            return view('system.danhmuc.phucap.index_donvi')
                ->with('model', $model)
                //->with('a_lock', )
                ->with('furl', '/danh_muc/phu_cap/don_vi/')
                ->with('pageTitle', 'Thông tin phân loại phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function edit_donvi(Request $request)
    {
        if (Session::has('admin')) {

            //kiểm tra lại quyền của User (đi tập huấn một số đơn vị ko hiểu sao lại cập nhật luôn vào danh mục
            if(session('admin')->level =='SA' || session('admin')->level == 'SSA'){
                Session::flush();
                return view('errors.notlogin');
            }

            $inputs = $request->all();
            $model = dmphucap_donvi::where('mapc', $inputs['maso'])->where('madv', session('admin')->madv)->first();
            return view('system.danhmuc.phucap.edit_donvi')
                ->with('model', $model)
                ->with('furl', '/danh_muc/phu_cap/don_vi/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function update_donvi(Request $request)
    {
        if (Session::has('admin')) {
            if(session('admin')->level =='SA' || session('admin')->level == 'SSA'){
                Session::flush();
                return view('errors.notlogin');
            }
            $inputs = $request->all();
            $inputs['congthuc'] = getDbl($inputs['phanloai']) == 2 ? $inputs['congthuc'] : '';
            dmphucap_donvi::where('mapc', $inputs['mapc'])->where('madv', session('admin')->madv)->first()->update($inputs);
            return redirect('/danh_muc/phu_cap/don_vi');
        } else
            return view('errors.notlogin');
    }
}
