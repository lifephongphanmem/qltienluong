<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmkhoipb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class dmchucvucqController extends Controller
{
    public function index(){
        if (Session::has('admin')) {
            //Nếu là quyền X, H khi thêm mới tự động lấy makhoipb trong bảng dmdonvi
            //Nếu là quyền T, quản trị =>full

            switch(session('admin')->level){
                case 'H':{
                    $makpb=getMaKhoiPB(session('admin')->mahuyen);
                    $model=dmchucvucq::where('makhoipb',$makpb)->orderby('sapxep')->get();
                    $model_kpb=dmkhoipb::select('makhoipb','tenkhoipb')->where('makhoipb',$makpb)->get()->toarray();
                    break;
                }
                case 'T':{
                    $model=dmchucvucq::orderby('sapxep')->get();
                    $model_kpb=dmkhoipb::select('makhoipb','tenkhoipb')->get()->toarray();
                    break;
                }
                default:{
                    $makpb=getMaKhoiPB(session('admin')->maxa);
                    $model=dmchucvucq::where('makhoipb',$makpb)->orderby('sapxep')->get();
                    $model_kpb=dmkhoipb::select('makhoipb','tenkhoipb')->where('makhoipb',$makpb)->get()->toarray();
                    break;
                }
            }

            return view('system.danhmuc.chucvucq.index')
                ->with('model',$model)
                ->with('model_kpb',array_column($model_kpb,'tenkhoipb','makhoipb'))
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
        $model = new dmchucvucq();
        $model->macvcq = session('admin')->madv .'.'.getdate()[0];
        $model->tencv = $inputs['tencv'];
        $model->ghichu = $inputs['ghichu'];
        $model->sapxep = $inputs['sapxep'];
        $model->makhoipb = $inputs['makhoipb'];
        $model->save();

        //Trả lại kết quả
        $result['message'] = 'Thao tác thành công.';
        $result['status'] = 'success';

        die(json_encode($result));
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dmchucvucq::findOrFail($id);
            $model->delete();
            return redirect('/danh_muc/chuc_vu_cq/index');
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
        $model->tencv = $inputs['tencv'];
        $model->ghichu = $inputs['ghichu'];
        $model->sapxep = $inputs['sapxep'];
        $model->makhoipb = $inputs['makhoipb'];
        $model->save();

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
