<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\Users;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class dmdonvibaocaoController extends Controller
{
    //begin - đơn vị báo cáo
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmdonvibaocao::where('level', $inputs['level'])->get();
            foreach ($model as $donvi) {
                $donvi->tendv = getTenDV($donvi->madvcq);
            }
            return view('system.danhmuc.donvibaocao.index')
                ->with('model', $model)
                ->with('level', $inputs['level'])
                ->with('furl', '/danh_muc/khu_vuc/')
                ->with('pageTitle', 'Danh mục khu vực, địa bàn quản lý');
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
    public function list_donvi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmdonvi::where('madvbc', $inputs['ma_so'])->where('phanloaitaikhoan', $inputs['phan_loai'])->get();
            $a_donvi = array_column(dmdonvi::where('madvbc', $inputs['ma_so'])->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            //$a_donvi = array_column($model->toarray(), 'tendv', 'madv');
            $a_phanloai = getPhanLoaiDonVi();
            $a_phamvi = getPhamViTongHop();
            $model_capdv = getCapDonVi();
            foreach ($model as $donvi) {
                $donvi->tencqcq = isset($a_donvi[$donvi->macqcq]) ? $a_donvi[$donvi->macqcq] : '';
                $donvi->phanloai = isset($a_phanloai[$donvi->maphanloai])?$a_phanloai[$donvi->maphanloai]:'';
                $donvi->capdutoan = isset($model_capdv[$donvi->capdonvi]) ? $model_capdv[$donvi->capdonvi] : '';
                $donvi->phamvi = isset($a_phamvi[$donvi->phamvitonghop])?$a_phamvi[$donvi->phamvitonghop]:'';
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();

            return view('system.danhmuc.donvibaocao.index_donvi')
                ->with('model', $model)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('a_donvi', $a_donvi)
                ->with('inputs', $inputs)
                ->with('url', '/danh_muc/khu_vuc/')
                ->with('pageTitle', 'Danh mục đơn vị');
        } else
            return view('errors.notlogin');
    }

    public function show_donvi($madvbc, $madonvi)
    {
        if (Session::has('admin')) {
            $model = dmdonvi::where('madv', $madonvi)->first();
            //$model_donvi = array_column(dmdonvi::select('madv','tendv')->where('madvbc',$madvbc)->get()->toarray(),'tendv','madv');
            $model_donvi = array_column(dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)->where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            $a_phanloai = array_column(dmphanloaidonvi::all()->toarray(), 'tenphanloai', 'maphanloai');
            $model_plxa = getPhanLoaiXa();
            $model_capdv = getCapDonVi();
            $model_linhvuc = getLinhVucHoatDong(false);

            return view('system.danhmuc.donvibaocao.edit_donvi_nhaplieu')
                ->with('model', $model)
                ->with('model_donvi', $model_donvi)
                ->with('model_phanloai', $a_phanloai)
                ->with('model_plxa', $model_plxa)
                ->with('model_capdv', $model_capdv)
                ->with('model_linhvuc', $model_linhvuc)
                ->with('url', '/danh_muc/khu_vuc/')
                ->with('pageTitle', 'Chỉnh sửa thông tin đơn vị');
        } else
            return view('errors.notlogin');
    }

    function update_donvi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['tendv']=removespace($inputs['tendv']);
            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            if($inputs['macqcq'] == 'NULL'){
                $inputs['macqcq']=null;
            }
            $model->update($inputs);

            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }

    public function create_donvi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $model_donvi = array_column(dmdonvi::select('madv','tendv')->where('madvbc', $inputs['ma_so'])->where('phanloaitaikhoan', 'TH')->get()->toarray(),'tendv','madv');
            $a_phanloai = getPhanLoaiDonVi();
            $model_plxa = getPhanLoaiXa(false);
            $model_capdv = getCapDonVi();
            $model_linhvuc = getLinhVucHoatDong(false);
            if($inputs['phan_loai'] == 'SD'){
                return view('system.danhmuc.donvibaocao.create_donvi_nhaplieu')
                    ->with('url','/danh_muc/khu_vuc/')
                    ->with('model_donvi',$model_donvi)
                    ->with('a_phanloai',$a_phanloai)
                    ->with('inputs',$inputs)
                    ->with('model_phanloai',$a_phanloai)
                    ->with('model_plxa',$model_plxa)
                    ->with('model_capdv',$model_capdv)
                    ->with('model_linhvuc',$model_linhvuc)
                    ->with('pageTitle','Thêm mới đơn vị');
            }else{
                //một địa bàn chỉ có 1 tài khoản TH tổng

                $count_donvi = dmdonvi::where('madvbc', $inputs['ma_so'])->where('phamvitonghop', 'HUYEN')->count();
                if($count_donvi == 0){//chưa có đơn vị tổng hơp 'Toàn huyện; Tất cả các sở, ban ngành'
                    $a_phamvi = array(
                        'KHOI'=>'Khối; Sở, ban ngành',
                        'HUYEN'=>'Toàn huyện; Tất cả các sở, ban ngành'
                    );
                }else{//Đã có
                    $a_phamvi = array('KHOI'=>'Khối; Sở, ban ngành');
                }
                return view('system.danhmuc.donvibaocao.create_donvi_tonghop')
                    ->with('url','/danh_muc/khu_vuc/')
                    ->with('model_donvi',$model_donvi)
                    ->with('a_phamvi',$a_phamvi)
                    ->with('inputs',$inputs)
                    ->with('pageTitle','Thêm mới đơn vị');
            }

        } else
            return view('errors.notlogin');
    }

    public function store_donvi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv']=getdate()[0];
            $inputs['name'] = $inputs['tendv'];
            $inputs['maxa'] = $inputs['madv'];
            $inputs['password'] = md5($inputs['password']);
            $inputs['status'] = 'active';
            $inputs['sadmin'] = 'NULL';
            Users::create($inputs);
            dmdonvi::create($inputs);

            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$inputs['madvbc'].'&phan_loai='.$inputs['phanloaitaikhoan']);
        } else
            return view('errors.notlogin');
    }

    public function destroy_donvi($madv){
        if (Session::has('admin')) {
            $model = dmdonvi::where('madv',$madv)->first();
            $model->delete();
            Users::where('madv',$madv)->delete();
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
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
        $model = dmdonvi::where('madvbc',$inputs['madvbc'])->where('phamvitonghop','HUYEN')->get();
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


    //bỏ
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

    function update_plct(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            DB::statement("Update hosocanbo set mact = '".$inputs['mact_moi']."' where mact='".$inputs['mact_cu']."' and madv ='".$inputs['madv']."'");
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }
}
