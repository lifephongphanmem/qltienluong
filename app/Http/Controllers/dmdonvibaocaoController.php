<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\Users;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class dmdonvibaocaoController extends Controller
{
    //begin - đơn vị báo cáo
    public function index($level){
        if (Session::has('admin')) {
            /*
            if(session('admin')->level !='SA' && session('admin')->level !='SSA'){
                return view('errors.noperm');
            }
            */
            $a_baomat=array('H'=>'Cấp huyện','T'=>'Cấp tỉnh');
            $model=dmdonvibaocao::where('level',$level)->get();
            foreach($model as $donvi){
                $donvi->tendv=getTenDV($donvi->madvcq);
            }
            return view('system.danhmuc.donvibaocao.index')
                ->with('model',$model)
                ->with('level',$level)
                ->with('a_baomat',$a_baomat)
                ->with('furl','/danh_muc/khu_vuc/')
                ->with('pageTitle','Danh mục khu vực, địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
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
        $inputs['madvbc']=getdate()[0];
        $inputs['tendvbc']=removespace($inputs['tendvbc']);
        dmdonvibaocao::create($inputs);

        $result['message'] = "Thêm mới thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
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
        $inputs['tendvbc']=removespace($inputs['tendvbc']);
        $model = dmdonvibaocao::where('madvbc',$inputs['madvbc'])->first();
        $model->update($inputs);

        $result['message'] = "Cập nhật thành công.";
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function destroy($madvbc){
        if (Session::has('admin')) {
            $model = dmdonvibaocao::where('madvbc',$madvbc)->first();
            $model->delete();

            return redirect('/danh_muc/khu_vuc/ma_so='.$model->level);
        }else
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
        $model = dmdonvibaocao::where('madvbc',$inputs['madvbc'])->first();
        die($model);
    }
    //end - đơn vị báo cáo

    //begin - đơn vị thuộc khu vực báo cáo
    public function list_donvi($madvbc){
        if (Session::has('admin')) {
            $model=dmdonvi::where('madvbc',$madvbc)->get();
            return view('system.danhmuc.donvibaocao.index_donvi')
                ->with('model',$model)
                ->with('madvbc',$madvbc)
                ->with('url','/danh_muc/khu_vuc/')
                ->with('pageTitle','Danh mục đơn vị');
        } else
            return view('errors.notlogin');
    }

    public function show_donvi($madvbc, $madonvi){
        if (Session::has('admin')) {
            $model=dmdonvi::where('madv',$madonvi)->first();
            $model_dvbc=dmdonvibaocao::where('madvbc',$model->madvbc)->first();
            $model_kpb =  array_column(dmkhoipb::select('makhoipb','tenkhoipb')->get()->toarray(),'tenkhoipb','makhoipb');
            return view('system.danhmuc.donvibaocao.edit_donvi')
                ->with('model',$model)
                ->with('model_kpb',$model_kpb)
                ->with('url','/danh_muc/khu_vuc/')
                ->with('pageTitle','Chỉnh sửa thông tin đơn vị');
        } else
            return view('errors.notlogin');
    }

    function update_donvi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['tendv']=removespace($inputs['tendv']);
            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            $model->update($inputs);

            return redirect('/danh_muc/khu_vuc/ma_so='.$model->madvbc.'/list_unit');
        }else
            return view('errors.notlogin');
    }

    public function create_donvi($madvbc){
        if (Session::has('admin')) {
            $model=dmdonvibaocao::where('madvbc',$madvbc)->first();
            $model_kpb =  array_column(dmkhoipb::select('makhoipb','tenkhoipb')->get()->toarray(),'tenkhoipb','makhoipb');
            return view('system.danhmuc.donvibaocao.create_donvi')
                ->with('url','/danh_muc/khu_vuc/')
                ->with('model_kpb',$model_kpb)
                ->with('madvbc',$madvbc)
                ->with('pageTitle','Thêm mới đơn vị');
        } else
            return view('errors.notlogin');
    }

    public function store_donvi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            if($inputs['madv'] == ''){
                $inputs['madv']=getdate()[0];
            }

            dmdonvi::create($inputs);

            return redirect('/danh_muc/khu_vuc/ma_so='.$inputs['madvbc'].'/list_unit');
        } else
            return view('errors.notlogin');
    }

    public function destroy_donvi($madv){
        if (Session::has('admin')) {
            $model = dmdonvi::where('madv',$madv)->first();
            $model->delete();
            Users::where('madv',$madv)->delete();
            return redirect('/danh_muc/khu_vuc/ma_so='.$model->madvbc.'/list_unit');
        } else
            return view('errors.notlogin');
    }

    function get_list_unit(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmdonvi::where('madvbc',$inputs['madvbc'])->get();
        $madvcq = dmdonvibaocao::where('madvbc',$inputs['madvbc'])->first()->madvcq;
        if(count($model)==0){
            $result['status']='fail';
            $result['message']='<div class="modal-body" id="donvichuquan">';
            $result['message'] .='<label class="form-control-label">Chọn đơn vị quản lý</label>';
            $result['message'] .='<select class="form-control select2me" name="madvcq_cq" id="madvcq_cq">';
            $result['message'] .='</div>';
            die(json_encode($result));
        }
        $result['status']='success';
        $result['message']='<div class="modal-body" id="donvichuquan">';

        $result['message'] .='<label class="form-control-label">Chọn đơn vị quản lý</label>';
        $result['message'] .='<select class="form-control select2me" name="madvcq_cq" id="madvcq_cq">';
        foreach($model as $donvi){
            $result['message'] .='<option value="'.$donvi->madv.'"'.($donvi->madv==$madvcq?'selected':'').'>'.$donvi->tendv.'</option>';
        }
        $result['message'] .='</div>';
        die(json_encode($result));
    }

    function set_management(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        $model = dmdonvibaocao::where('madvbc',$inputs['madvbc'])->first();
        $model->update($inputs);

        $result['status']='success';
        $result['message']='';

        die(json_encode($result));
    }
    //end - đơn vị trực thuộc báo cáo

    public function list_donvi_luong($macqcq){
        if (Session::has('admin')) {
            //$model=dmdonvi::where('macqcq',$macqcq)->get();
            $model_donvi = dmdonvi::select('madv', 'tendv')->where('macqcq', $macqcq)->get();
            $donvi= $model_donvi->first();

            $model_bangluong = bangluong::where('madv', $donvi->madv)->get();
            return view('functions.tonghopluong.index_users')
                ->with('model', $model_bangluong)
                ->with('madv', $donvi->madv)
                ->with('model_donvi', array_column($model_donvi->toarray(), 'tendv', 'madv'))
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }

    public function donvi_luong(Request $request){
        if (Session::has('admin')) {
            //$donvi=dmdonvi::where('madv',session('admin')->madv)->get();
            $inputs=$request->all();
            $donvi= dmdonvi::where('madv', session('admin')->madv)->first();
            $model_donvi = dmdonvi::select('madv', 'tendv')->where('macqcq', $donvi->macqcq)->get();
            foreach($model_donvi as $donvi){
                $model_bangluong = bangluong::where('madv', $donvi->madv)
                    ->where('thang', $inputs['thang'])
                    ->where('nam', $inputs['nam'])
                    ->first();

                $donvi->mabl= (isset($model_bangluong)?$model_bangluong->mabl:NULL);
            }
            //dd($model_donvi->toarray());
            return view('functions.tonghopluong.index_users')
                ->with('model', $model_donvi)

               // ->with('model_donvi', array_column($model_donvi->toarray(), 'tendv', 'madv'))
                ->with('furl','/chuc_nang/tong_hop_luong/')
                ->with('pageTitle','Danh sách đơn vị tổng hợp lương');

        } else
            return view('errors.notlogin');
    }
}
