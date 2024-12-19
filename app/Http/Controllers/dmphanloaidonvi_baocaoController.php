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
            dmphanloaidonvi_baocao::where('maphanloai_goc', $model->maphanloai_nhom)->where('madvbc',$model->madvbc)->delete();
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
            $chk = dmphanloaidonvi::where('maphanloai', $inputs['maphanloai_goc'])->get();
            if ($chk->count() > 0) {
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
    public function donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['macqcq'] = $inputs['macqcq'] ?? session('admin')->madv;
            $model = dmdonvi::where('macqcq', $inputs['macqcq'])->where('madv', '<>', $inputs['macqcq'])
                ->whereNotIn('madv', function ($query) {
                    $query->from('dmdonvi')
                        ->select('madv')
                        ->where('trangthai', 'TD');
                })
                ->orderBy('stt')
                ->get();
            $model_capduoi = dmdonvi::wherein('macqcq', array_column($model->toarray(), 'madv'))
                ->whereNotIn('madv', function ($query) {
                    $query->from('dmdonvi')
                        ->select('madv')
                        ->where('trangthai', 'TD');
                })
                ->orderBy('stt')
                ->get();
            $maxstt_parent = $model->max('stt') ?? 0;
            $maxstt_children = $model_capduoi->max('stt') ?? 0;
            return view('system.danhmuc.baocao.donvi')
                ->with('model', $model)
                ->with('maxstt_parent', $maxstt_parent)
                ->with('maxstt_children', $maxstt_children)
                ->with('model_capduoi', $model_capduoi)
                ->with('furl', '/danh_muc/bao_cao/')
                ->with('pageTitle', 'Thiết lập đơn vị');
        } else
            return view('errors.notlogin');
    }
    public function sua(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $model_parent=dmdonvi::where('madv',$inputs['madv'])->first();
            if(isset($model_parent)&&$model_parent->stt != $inputs['stt_parent']){
                $model_parent->stt=$inputs['stt_parent'];
                $model_parent->save();
            }
            foreach ($inputs['stt'] as $k => $ct) {
                // dd($ct);
                $model = dmdonvi::where('madv', $k)->first();
                $model->stt = $ct;
                $model->save();
            };


            return redirect('he_thong/bao_cao/don_vi')
                ->with('success', 'Sửa thành công');
        } else
            return view('errors.notlogin');
    }

    public function getDV(Request $request)
    {
        $inputs = $request->all();
        $model = dmdonvi::select('madv', 'tendv', 'stt')->where('macqcq', $inputs['macqcq'])
            ->whereNotIn('madv', function ($query) {
                $query->from('dmdonvi')
                    ->select('madv')
                    ->where('trangthai', 'TD');
            })
            ->get();
        $result = array(
            'status' => 'fail',
            'html' => ''
        );

        $result['html'] .= '<div class="row" id="dv_capduoi">';
        foreach ($model as $ct) {
            $result['html'] .= '<div class="col-md-10">';
            $result['html'] .= '<div class="form-group">';
            $result['html'] .= '<input type="text" class="form-control" value="' . $ct->tendv . '" readonly>';
            $result['html'] .= '</div>';
            $result['html'] .= '</div>';

            $result['html'] .= '<div class="col-md-2">';
            $result['html'] .= '<div class="form-group">';
            $result['html'] .= '<input type="text" class="form-control" name="stt[' . $ct->madv . ']" value="' . $ct->stt . '">';
            $result['html'] .= '</div>';
            $result['html'] .= '</div>';
        }
        $result['html'] .= '</div>';
        $result['status'] = 'success';
        return response()->json($result);
    }
}
