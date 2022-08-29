<?php

namespace App\Http\Controllers;

use App\chitieubienche;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dutoanluong;
use App\hosocanbo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class chitieubiencheController extends Controller
{
    function index(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_dutoan = dutoanluong::where('madv',session('admin')->madv)->where('namns',$inputs['namct'])->first();
            $inputs['trangthai'] = isset($m_dutoan) ? false : true;
            $model = chitieubienche::where('madv',session('admin')->madv)->where('nam',$inputs['namct'])->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $a_ct = array_column($model_tenct->toarray(),'tenct','mact');
            foreach($model as $ct) {
                $ct->tenct = isset($a_ct[$ct->mact]) ? $a_ct[$ct->mact] : '';
            }
            return view('manage.chitieubienche.index')
                ->with('furl','/nghiep_vu/chi_tieu/')
                ->with('model',$model)
                ->with('m_lv', getLinhVucHoatDong(false))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('inputs', $inputs)

                ->with('pageTitle','Danh sách chỉ tiêu biên chế của đơn vị');
        } else
            return view('errors.notlogin');
    }

    function get_detail(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = chitieubienche::find($inputs['id']);
        die($model);
    }

    function create(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $inputs['id'] = isset($inputs['id']) ? $inputs['id'] : -1;
            $model = chitieubienche::where('id', $inputs['id'])->first();
            $a_plct = [];
            //tính lại lọc plct để tránh chọn lại các plct đã có trong chỉ tiêu
            //chú ý plct khi chỉnh sửa

            $model_pc = dmphucap_donvi::where('madv', \session('admin')->madv)
                ->wherenotin('mapc',['hesott'])->get();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();

            //dd($model);
            if($model==null) {
                $madv = session('admin')->madv;
                $nam = $inputs['nam'];
                $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')
                    ->wherenotin('mact', function ($query) use ($madv, $nam) {
                        $query->select('mact')->from('chitieubienche')
                            ->where('madv', $madv)->where('nam', $nam)->get();
                    })->get();
                $model = new chitieubienche();
                $model->id = -1;
                $model->nam = $inputs['nam'];
                $model->mact_tuyenthem = $model_tenct->first()->mact;
                $model->mact = $model_tenct->first()->mact;
            }else {
                $id = $inputs['id'];
                $madv = $model->madv;
                $nam = $model->nam;
                $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')
                    ->wherenotin('mact', function ($query) use ($id, $madv, $nam) {
                        $query->select('mact')->from('chitieubienche')
                            ->where('madv', $madv)->where('nam', $nam)
                            ->where('id', '<>', $id)->get();
                    })->get();
            }
            return view('manage.chitieubienche.create')
                ->with('furl', '/nghiep_vu/chi_tieu/')
                ->with('model', $model)
                ->with('model_pc',$model_pc->sortby('stt'))
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle', 'Thông tin chỉ tiêu biên chế');


        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (!Session::has('admin')) {
            return view('errors.notlogin');
        }
        $inputs = $request->all();
        $inputs['soluongduocgiao'] = chkDbl($inputs['soluongduocgiao']);
        $inputs['soluongbienche'] = chkDbl($inputs['soluongbienche']);
        $inputs['soluongcongchuc'] = chkDbl($inputs['soluongcongchuc']);
        $inputs['soluongvienchuc'] = chkDbl($inputs['soluongvienchuc']);
        $inputs['soluongtuyenthem'] = chkDbl($inputs['soluongtuyenthem']);
        $inputs['madv'] = session('admin')->madv;
        //dd($inputs);
        $model = chitieubienche::where('id', $inputs['id'])->first();
        if($model == null){
            unset($inputs['id']);
            chitieubienche::create($inputs);
        }else{
            $model->update($inputs);
        }
        return redirect('/nghiep_vu/chi_tieu/danh_sach?namct='.$inputs['nam']);
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = chitieubienche::find($id);
            $model->delete();
            return redirect('/nghiep_vu/chi_tieu/danh_sach?namct='.$model->nam);
        } else
            return view('errors.notlogin');
    }
}
