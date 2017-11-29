<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmkhoipb;
use App\dmphanloaidonvi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class dmchucvucqController extends Controller
{
    public function index($maphanloai){
        if (Session::has('admin')) {
            //dd(session('admin'));
            //neu quyen admin thi mo tat ca
            if(session('admin')->level=='SA' || session('admin')->level=='SSA'){
                $model_pl = dmphanloaidonvi::all();
                if($maphanloai=='SA' || $maphanloai =='SSA'){
                    $maphanloai= 'KVXP';
                }
            }else{
                $model_pl = dmphanloaidonvi::where('maphanloai',session('admin')->maphanloai)->get();
            }

            $model = dmchucvucq::where('maphanloai',session('admin')->maphanloai)->get();
            //dd($model);
            return view('system.danhmuc.chucvucq.index')
                ->with('model',$model)
                ->with('model_pl',array_column($model_pl->toArray(),'tenphanloai','maphanloai'))
                ->with('mapl',$maphanloai)
                ->with('furl','/danh_muc/chuc_vu_cq/')
                ->with('pageTitle','Danh mục chức vụ');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $inputs['macvcq'] = session('admin')->madv .'_'.getdate()[0];
        dmchucvucq::create($inputs);
        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmchucvucq::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/chuc_vu_cq/ma_so='.session('admin')->level);
        }else
            return view('errors.notlogin');
    }

    function update(Request $request){
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
        $model = dmchucvucq::where('macvcq',$inputs['macvcq'])->first();
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
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
        $model = dmchucvucq::where('macvcq',$inputs['macvcq'])->first();
        die($model);
    }
}
