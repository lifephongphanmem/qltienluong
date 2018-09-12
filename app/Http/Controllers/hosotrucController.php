<?php

namespace App\Http\Controllers;

use App\hosotruc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosotrucController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = hosotruc::where('madv', session('admin')->madv)->get();

            return view('manage.truc.index')
                ->with('furl', '/nghiep_vu/truc/')
                ->with('furl_ajax', '/ajax/truc/')
                //->with('macanbo', $macanbo)
                ->with('model', $model)
                ->with('pageTitle', 'Danh sách cán bộ trực công tác');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            if ($inputs['id'] == 'ADD') {
                $inputs['madv'] = session('admin')->madv;
                $inputs['macanbo'] =session('admin')->madv . '_' . getdate()[0];
                $inputs['heso'] = chkDbl($inputs['heso']);
                unset($inputs['id']);
                hosotruc::create($inputs);
            } else {
                //unset($inputs['id']);
                hosotruc::find($inputs['id'])->update($inputs);
            }
            return redirect('/nghiep_vu/truc/danh_sach');
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
        $model = hosotruc::find($inputs['id']);
        $model->status = 'success';
        die(json_encode($model));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosotruc::find($id);

            $model->delete();
            return redirect('/nghiep_vu/truc/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
