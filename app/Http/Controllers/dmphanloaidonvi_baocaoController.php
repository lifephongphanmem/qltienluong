<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmphanloaidonvi;
use App\dmphanloaidonvi_baocao;
use App\dmphucap;
use App\dmphucap_phanloai;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dmphanloaidonvi_baocaoController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madvbc'] = $inputs['madvbc'] ?? session('admin')->madvbc;
            $model = dmphanloaidonvi_baocao::where('madvbc', session('admin')->madvbc)->get();
            $a_phanloai = array_column(dmphanloaidonvi::wherenotin('maphanloai', array_column($model->toarray(), 'maphanloai_nhom'))->get()->toArray(), 'tenphanloai', 'maphanloai');
            $a_goc = array_column(dmphanloaidonvi_baocao::wherenotin('maphanloai_nhom', array_keys($a_phanloai))->get()->toArray(), 'tenphanloai_nhom', 'maphanloai_nhom');
            return view('system.danhmuc.baocao.index')
                ->with('model', $model)
                ->with('a_phanloai', $a_phanloai)
                ->with('a_nhomgoc', $a_goc)
                ->with('furl', '/danh_muc/bao_cao/')
                ->with('pageTitle', 'Thiết lập báo cáo');
        } else
            return view('errors.notlogin');
    }

    public function inbaocao(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madvbc'] = $inputs['madvbc'] ?? session('admin')->madvbc;
            $m_phanloai = dmphanloaidonvi_baocao::where('madvbc', session('admin')->madvbc)->get();
            //$m_phanloai = dmphanloaidonvi::all();
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();

            $a_tenpc = array_column(dmphucap::all()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array();
            $a_pc = getColDuToan();
            $col = count($a_pc);
            foreach ($a_pc as $ct) {
                $a_phucap[$ct] = $a_tenpc[$ct];
            }
            $m_donvi_baocao = dmdonvi::where('madvbc', $inputs['madvbc'])->get();
            return view('system.danhmuc.baocao.inbaocao')
                ->with('m_donvi', $m_donvi)
                ->with('m_phanloai', $m_phanloai)
                ->with('m_donvi_baocao', $m_donvi_baocao)
                ->with('a_phucap', $a_phucap)
                ->with('col', $col)
                ->with('lamtron', session('admin')->lamtron ?? 3)
                ->with('pageTitle', 'In mẫu báo cáo thiết lập');
        } else
            return view('errors.notlogin');
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = dmphanloaidonvi_baocao::findOrFail($id);
            //Xoá mã gốc tự động xoá các mã con
            dmphanloaidonvi_baocao::where('maphanloai_goc',$model->maphanloai_nhom )->delete();
            $model->delete();
            return redirect('/he_thong/bao_cao/danh_sach?madvbc=' . $model->madvbc);
        } else
            return view('errors.notlogin');
    }

    public function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();            
            $inputs['madvbc'] = session('admin')->madvbc;
            $model = dmphanloaidonvi_baocao::where('madvbc', $inputs['madvbc'])->where('maphanloai_nhom', $inputs['maphanloai_nhom'])->first();
            //Kiểm tra nếu mã nhóm trùng với mã trong phân loại đơn vị thì báo lỗi
            $chk = dmphanloaidonvi::where('maphanloai',$inputs['maphanloai_goc'])->get();
            if($chk->count() > 0){
                dd('Mã số này đã có trong danh mục phân loại đơn vị nên không thể dùng làm mã gốc');
            }
            //dd($model);
            if ($model != null) {
                $model->update($inputs);
            } else
                dmphanloaidonvi_baocao::create($inputs);
            return redirect('/he_thong/bao_cao/danh_sach?madvbc=' . $inputs['madvbc']);
        } else
            return view('errors.notlogin');
    }
}
