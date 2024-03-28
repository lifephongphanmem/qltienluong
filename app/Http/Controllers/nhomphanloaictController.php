<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaict;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\nhomphanloaict;
use App\nhomphanloaict_phanloaict;
use Illuminate\Support\Facades\Session;

class nhomphanloaictController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_pl = nhomphanloaict::orderBy('stt')->get();
            $model_tenct = dmphanloaict::all();
            $a_model_tenct=array_column($model_tenct->toarray(),'tenct','mact');
            // dd($a_model_tenct);
            foreach ($model_pl as $val) {
                $m_phanloai = nhomphanloaict_phanloaict::where('manhom', $val->manhom)->get();
                $a_ten=array();
                foreach($m_phanloai as $key=>$ct)
                {
                    $a_ten[$key]=$a_model_tenct[$ct->maphanloaict];
                }
                $val->phanloai = implode(';', $a_ten);
            }
            $stt=($model_pl->max('stt') + 1)??1;

            $model_nhomct = dmphanloaicongtac::wherein('macongtac', array_unique(array_column($model_tenct->toarray(), 'macongtac')))->get();
            return view('system.danhmuc.nhomphanloaict.index')
                ->with('model', $model_pl)
                ->with('stt', $stt)
                ->with('model_tenct', $model_tenct)
                ->with('model_nhomct', $model_nhomct)
                ->with('furl', '/danh_muc/nhomphanloaict')
                ->with('pageTitle', 'Nhóm phân loại công tác');
        } else
            return view('errors.notlogin');
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        // dd($inputs);
        $manhom = getdate()[0];
        $data1 = [
            'manhom' => $manhom,
            'tennhom' => $inputs['tennhom'],
            'stt' => $inputs['stt']
        ];
        $mact=$inputs['mact'];
        $data2=array();
        nhomphanloaict::create($data1);
        foreach ($mact as $ct) {
            $data2 = [
                'manhom' => $manhom,
                'maphanloaict' => $ct
            ];
            nhomphanloaict_phanloaict::create($data2);
        }
       return redirect('/danh_muc/nhomphanloaict/index');
    }

    public function update(Request $request)
    {
        $inputs=$request->all();
        $model=nhomphanloaict::where('manhom',$inputs['manhom'])->first();
        if(isset($model)){
            $model->update(['tennhom'=>$inputs['tennhom'],'stt'=>$inputs['stt']]);

            nhomphanloaict_phanloaict::where('manhom',$inputs['manhom'])->delete();
            foreach($inputs['mact'] as $ct){
                $data=[
                    'manhom'=>$model->manhom,
                    'maphanloaict'=>$ct
                ];
                nhomphanloaict_phanloaict::create($data);
            }
        }

        return redirect('/danh_muc/nhomphanloaict/index');
    }

    public function edit(Request $request)
    {
        $inputs=$request->all();
        $model=nhomphanloaict::where('manhom',$inputs['manhom'])->first();
        $m_phanloai=nhomphanloaict_phanloaict::where('manhom',$inputs['manhom'])->get();
        $a_phanloai=array_column($m_phanloai->toarray(),'maphanloaict');
        $model->phanloai=$a_phanloai;

        return response()->json($model);
    }
    public function destroy($id)
    {
        $model=nhomphanloaict::findOrFail($id);
        if(isset($model)){
            nhomphanloaict_phanloaict::where('manhom',$model->manhom)->delete();
            $model->delete();
        }

        return redirect('/danh_muc/nhomphanloaict/index');
    }
}
