<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\dutoanluong_bangluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_khoi;
use App\dutoanluong_nangluong;
use App\dutoanluong_tinh;
use App\hosocanbo;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dutoanluong_tinhController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['namns'] = $inputs['namns'] ?? date('Y');
            //$m_dutoan = dutoanluong_tinh::where('madv', session('admin')->madv)->where('namns', $inputs['namns'])->get();
            $model = dmdonvibaocao::where('level', 'H')->orderby('sapxep')->get();
            $m_dutoan_huyen = dutoanluong_huyen::where('namns', $inputs['namns'])
                ->where('macqcq', session('admin')->madv)
                ->where('trangthai', 'DAGUI')->get();
            $a_trangthai = getStatus();
            // dd($model);
            foreach ($model as $dv) {
                $dutoan_huyen = $m_dutoan_huyen->where('madv', $dv->madvcq)->first();
                $dv->trangthai = $dutoan_huyen->trangthai ?? 'CHOGUI';
                $dv->masodv = $dutoan_huyen->masodv ?? null;
            }

            $model_tenct = dmphanloaict::wherein('mact', getPLCTDuToan())->get();
            $model_nhomct = dmphanloaicongtac::wherein('macongtac', array_unique(array_column($model_tenct->toarray(), 'macongtac')))->get();
            $a_trangthai_in = array('ALL' => '--Tất cả đơn vị--', 'CHOGUI' => 'Đơn vị chưa gửi dữ liệu', 'DAGUI' => 'Đơn vị đã gửi dữ liệu');
            $a_phanloai = array_column(dmphanloaidonvi::all()->toarray(), 'tenphanloai', 'maphanloai');

            $m_dvbc = dmdonvibaocao::where('level', 'T')->get();
            $a_donviql = array_column(dmdonvi::wherein('madvbc', array_column($m_dvbc->toarray(), 'madvbc'))->get()->toarray(), 'tendv', 'madv');
            return view('functions.dutoanluong.tinh.index')
                ->with('model', $model->sortby('namns'))
                ->with('model_tenct', $model_tenct)
                ->with('model_nhomct', $model_nhomct)
                ->with('a_trangthai', $a_trangthai)
                ->with('a_trangthai_in', $a_trangthai_in)
                ->with('a_phanloai', setArrayAll($a_phanloai))
                ->with('inputs', $inputs)
                ->with('a_donviql', $a_donviql)
                // ->with('soluong', $soluong)
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/du_toan/huyen')
                ->with('furl_th', '/chuc_nang/du_toan_luong/huyen/')
                ->with('pageTitle', 'Danh sách tổng hợp dự toán lương');
        } else
            return view('errors.notlogin');
    }

    public function tao_du_toan(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dutoanluong_huyen::where('madv', $inputs['madv'])->where('namns', $inputs['namns'])->first();
            if ($model != null) {
                $inputs['masodv'] = $model->masodv;
            } else {
                $inputs['trangthai'] = 'CHUAGUI';
                $inputs['masodv'] = $inputs['madv'] . '_' . getdate()[0];
                $inputs['ngaylap'] = date('Y-m-d');
                dutoanluong_huyen::create($inputs);
            }
            dutoanluong::where('macqcq', $inputs['madv'])->where('namns', $inputs['namns'])->update(['masoh' => $inputs['masodv']]);

            return redirect('/chuc_nang/du_toan_luong/huyen/index');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        //kiểm tra xem đó là đơn vị SD hay TH&KHOI
        if (Session::has('admin')) {
            $inputs = $request->all();            
            $model = dutoanluong_huyen::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];            
            $model->save();
            dutoanluong::where('macqcq',$model->madv)->where('namns',$model->namns)->update(['masot'=>null]);
            return redirect('/chuc_nang/du_toan_luong/tinh/index?namns=' . $model->namns);
        } else
            return view('errors.notlogin');
    }
}
