<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmphucap_phanloai;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class dmphucapController extends Controller
{
    public function fix_mapc()
    {
        //chạy update cho cao bằng, lạng sơn
        $model_donvi = dmdonvi::all();
        //$a_pc = array('pckct','hesobl','pcd','pctdt','pcud61');
        $a_pc = array('pcud61');
        $model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->wherein('mapc', $a_pc)->get();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pckct')->first();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','hesobl')->first();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pcd')->first();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pctdt')->first();
        //$phucap = dmphucap::where('mapc','hesobl')->first();

        foreach ($model_donvi as $donvi) {
            $model_phucap = dmphucap_donvi::where('madv', $donvi->madv)->wherein('mapc', $a_pc)->get();
            if (count($model_phucap) > 0) {
                foreach ($model_dmpc as $pc) {
                    $chekbl = $model_phucap->where('mapc', $pc->mapc);
                    //$chekbl = $model_phucap->where('mapc','hesobl');
                    if (count($chekbl) == 0) {
                        $pc->madv = $donvi->madv;
                        dmphucap_donvi::insert($pc->toarray());
                    }
                }
            }
        }


        dd('ok');
    }

    public function fix_ct()
    {
        //chạy update cho cao bằng, lạng sơn
        $model_donvi = dmdonvi::all();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','hesobl')->first();
        //$model_dmpc = dmphucap::select('mapc', 'tenpc', 'baohiem', 'form', 'report', 'phanloai', 'congthuc')->where('mapc','pcd')->first();
        $model_dmpc = dmphanloaict::select('mact', 'macongtac')->where('mact', '1537427170')->first();
        //$phucap = dmphucap::where('mapc','hesobl')->first();
        foreach ($model_donvi as $donvi) {
            $model_phucap = dmphanloaicongtac_baohiem::where('madv', $donvi->madv)->get();
            if (count($model_phucap) > 0) {
                $chekbl = $model_phucap->where('mact', '1537427170');
                //$chekbl = $model_phucap->where('mapc','hesobl');
                if (count($chekbl) == 0) {
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
            $model = dmphucap::all(); //default
            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC();
            //$a_th = getColTongHop();
            //            dd($a_ct);
            foreach ($model as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                $ct->tencongthuc = '';
                //$ct->tonghop = in_array($ct->mapc,$a_th) ? 'Tổng hợp và dự toán':'';
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
            if (session('admin')->level != 'SA' && session('admin')->level != 'SSA') {
                Session::flush();
                return view('errors.notlogin');
            }

            $inputs = $request->all();
            $inputs['congthuc'] = getDbl($inputs['phanloai']) == 2 ? $inputs['congthuc'] : '';
            dmphucap::where('mapc', $inputs['mapc'])->first()->update($inputs);
            return redirect('/he_thong/phu_cap/index');
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model =  dmphucap::where('mapc', $inputs['maso'])->first(); //default

            ///dd($model);
            return view('system.danhmuc.phucap.edit')
                ->with('model', $model)
                ->with('furl', '/he_thong/phu_cap/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function getinfo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmphucap::where('mapc', $inputs['mapc'])->first();
        die($model);
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dmphucap::findOrFail($id);
            $model->delete();
            return redirect('/he_thong/phu_cap/index');
        } else
            return view('errors.notlogin');
    }

    function index_donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$model = dmphucap_donvi::where('madv', session('admin')->madv)->wherenotin('mapc',$a_lock)->get();
            $inputs['phanloaipc'] = $inputs['phanloaipc'] ?? 'ALL';
            switch ($inputs['phanloaipc']) {
                case 'ALL': {
                        $model = dmphucap_donvi::where('madv', session('admin')->madv)->orderby('stt')->get();
                        break;
                    }
                case 'COSO': {
                        $model = dmphucap_donvi::where('madv', session('admin')->madv)->where('pccoso', '1')->orderby('stt')->get();
                        break;
                    }
                default:
                    $model = dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->orderby('stt')->get();
            }


            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC(false);
            //dd($a_ct);
            $a_th = getColTongHop();
            $a_dt = getColDuToan();
            $a_plct = setArrayAll(array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact'));
            foreach ($model as $ct) {
                $ct->tenphanloai = isset($a_pl[$ct->phanloai]) ? $a_pl[$ct->phanloai] : '';
                $congthuc = explode(',', $ct->congthuc);
                //$ct->tencongthuc = '';
                $ct->tonghop = in_array($ct->mapc, $a_th) ? '1' : '0';
                $ct->dutoan = in_array($ct->mapc, $a_dt) ? '1' : '0';
                foreach ($congthuc as $bg) {
                    $ct->tencongthuc .= isset($a_ct[$bg]) ? ($a_ct[$bg] . '; ') : '';
                }
                foreach (explode(',', $ct->baohiem_plct) as $plct) {
                    $ct->tenplct .= ($a_plct[$plct] ?? '') . '; ';
                }
            }
            return view('system.danhmuc.phucap.index_donvi')
                ->with('model', $model)
                ->with('inputs', $inputs)
                ->with('furl', '/danh_muc/phu_cap/don_vi/')
                ->with('pageTitle', 'Thông tin phân loại phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function edit_donvi(Request $request)
    {
        if (Session::has('admin')) {
            if (session('admin')->level == 'SA' || session('admin')->level == 'SSA') {
                Session::flush();
                return view('errors.notlogin');
            }

            $a_lock = array('heso', 'hesott', 'hesobl');

            $inputs = $request->all();
            $a_pl = getPhanLoaiPhuCap();
            $a_ct = getCongThucTinhPC(false);
            $model = dmphucap_donvi::where('mapc', $inputs['maso'])->where('madv', session('admin')->madv)->first();
            if (in_array($inputs['maso'], $a_lock)) {
                $a_pl =  array('0' => 'Hệ số', '3' => 'Ẩn');
            }

            if ($inputs['maso'] == 'vuotkhung') {
                $a_pl =  array('2' => 'Phần trăm', '3' => 'Ẩn');
                $a_ct = array('heso' => 'Lương ngạch bậc');
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            //dd($model);
            return view('system.danhmuc.phucap.edit_donvi')
                ->with('model', $model)
                ->with('a_pl', $a_pl)
                ->with('a_ct', $a_ct)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('furl', '/danh_muc/phu_cap/don_vi/')
                ->with('pageTitle', 'Sửa thông tin phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function update_donvi(Request $request)
    {
        if (Session::has('admin')) {
            if (session('admin')->level == 'SA' || session('admin')->level == 'SSA') {
                Session::flush();
                return view('errors.notlogin');
            }
            $inputs = $request->all();
            //dd($request);
            if (in_array('ALL', $inputs['baohiem_plct'])) {
                $inputs['baohiem_plct'] = 'ALL';
            } else {
                $inputs['baohiem_plct'] = implode(',', $inputs['baohiem_plct']);
            }
            $inputs['congthuc'] = getDbl($inputs['phanloai']) == 2 ? $inputs['congthuc'] : '';
            dmphucap_donvi::where('mapc', $inputs['mapc'])->where('madv', session('admin')->madv)->first()->update($inputs);
            return redirect('/danh_muc/phu_cap/don_vi');
        } else
            return view('errors.notlogin');
    }

    function anhien(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmphucap_donvi::find($inputs['id']);
            $model->congthuc = '';

            //Tính riêng vượt khung
            if ($model->mapc == 'vuotkhung') {
                if ($model->phanloai == 3) {
                    $model->phanloai = 2;
                    $model->congthuc = 'heso';
                } else {
                    $model->phanloai = 3;
                }
            } else {
                if ($model->phanloai == 3) {
                    $model->phanloai = 0;
                } else {
                    $model->phanloai = 3;
                }
            }
            $model->save();
            return redirect('/danh_muc/phu_cap/don_vi');
        } else
            return view('errors.notlogin');
    }

    function default_pc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_phanloai = dmphucap_phanloai::where('maphanloai', session('admin')->maphanloai)->get();
            foreach ($model_phanloai as $pl) {
                dmphucap_donvi::where('mapc', $pl->mapc)
                    ->where('madv', session('admin')->madv)
                    ->update(['phanloai' => $pl->phanloai, 'congthuc' => $pl->congthuc]);
            }
            return redirect('/danh_muc/phu_cap/don_vi');
        } else
            return view('errors.notlogin');
    }

    function set_pccoso(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            dmphucap_donvi::where('mapc', $inputs['mapc'])->where('madv', $inputs['madv'])->first()->update(['pccoso' => 1]);
            return redirect('/danh_muc/phu_cap/don_vi');
        } else
            return view('errors.notlogin');
    }
}
