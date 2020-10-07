<?php

namespace App\Http\Controllers;

use App\vanphonghotro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class vanphonghotroController extends Controller
{
    public function thongtinhotro(){
        return view('thongtinhotro')
            ->with('pageTitle', 'Thông tin hỗ trợ');
    }

    public function index(){
        if (Session::has('admin')) {
            $model = vanphonghotro::all();
            $a_vp = array_column($model->toArray(),'vanphong','vanphong');
            $col =(int) 12 / (count($a_vp)>0?count($a_vp) : 1);
            $col = $col < 4 ? 4 : $col;
            return view('system.vanphonghotro.index')
                ->with('model', $model)
                ->with('a_vp', $a_vp)
                ->with('col', $col)
                ->with('pageTitle', 'Danh sách cán bộ hỗ trợ');
        } else
            return view('errors.notlogin');
    }

    public function store(Request $request){
        if(Session::has('admin')){
            $inputs = $request->all();
            $check = vanphonghotro::where('maso',$inputs['maso'])->first();

            if ($check == null) {
                $inputs['maso'] = getdate()[0];
                vanphonghotro::create($inputs);
            } else {
                $check->update($inputs);
            }
            return redirect('/van_phong/danh_sach');
        }else
            return view('errors.notlogin');
    }

    public function edit(Request $request)
    {

        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = vanphonghotro::where('maso', $inputs['maso'])->first();
        die($model);
    }

    public function destroy(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            vanphonghotro::where('maso', $inputs['maso'])->first()->delete();
            return redirect('/van_phong/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
