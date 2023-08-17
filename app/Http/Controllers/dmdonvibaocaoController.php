<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmkhoipb;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphanloaidonvi;
use App\dmphongban;
use App\hosocanbo;
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
            $model_donvi = dmdonvi::where('madvbc', $inputs['ma_so'])->get();
            $a_dv = array_column($model_donvi->where('phanloaitaikhoan', 'TH')->toarray(), 'tendv', 'madv');
            //$a_donvi = array_column($model->toarray(), 'tendv', 'madv');
            $a_phanloai = getPhanLoaiDonVi();
            $a_phamvi = getPhamViTongHop();
            $model_capdv = getCapDonVi();
            foreach ($model as $donvi) {
                $donvi->tencqcq = isset($a_dv[$donvi->macqcq]) ? $a_dv[$donvi->macqcq] : '';
                $donvi->phanloai = isset($a_phanloai[$donvi->maphanloai])?$a_phanloai[$donvi->maphanloai]:'';
                $donvi->capdutoan = isset($model_capdv[$donvi->capdonvi]) ? $model_capdv[$donvi->capdonvi] : '';
                $donvi->phamvi = isset($a_phamvi[$donvi->phamvitonghop])?$a_phamvi[$donvi->phamvitonghop]:'';
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $m_linhvuc = array_column(dmkhoipb::all()->toArray(), 'tenkhoipb', 'makhoipb');

            return view('system.danhmuc.donvibaocao.index_donvi')
                ->with('model', $model)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('m_linhvuc', $m_linhvuc)
                ->with('a_donvi', array_column($model_donvi->where('phanloaitaikhoan','<>','TH')->toarray(),'tendv', 'madv'))
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

    function update_sunghiep(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            DB::statement("Update hosocanbo set sunghiep = '".$inputs['sunghiep_moi']."' where sunghiep='".$inputs['sunghiep_cu']."' and madv ='".$inputs['madv']."'");
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }

    function update_nguonkp(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            if(isset($inputs['manguonkp'])){
                $inputs['manguonkp'] = implode(',',$inputs['manguonkp']);
            }else{
                $inputs['manguonkp'] = '';
            }

            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            DB::statement("Update hosocanbo set manguonkp = '".$inputs['manguonkp']."' where mact='".$inputs['mact']."' and madv ='".$inputs['madv']."'");
            DB::statement("Update hosocanbo_kiemnhiem set manguonkp = '".$inputs['manguonkp']."' where mact='".$inputs['mact']."' and madv ='".$inputs['madv']."'");
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }

    function update_linhvuchoatdong(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            if(isset($inputs['linhvuchoatdong'])){
                $inputs['linhvuchoatdong'] = implode(',',$inputs['linhvuchoatdong']);
            }else{
                $inputs['linhvuchoatdong'] = '';
            }

            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            DB::statement("Update hosocanbo set lvhd = '".$inputs['linhvuchoatdong']."' where madv ='".$inputs['madv']."'");
            // DB::statement("Update hosocanbo_kiemnhiem set lvhd = '".$inputs['linhvuchoatdong']."' where madv ='".$inputs['madv']."'");
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }

    function del_dscanbo($madv){
        if (Session::has('admin')) {
            $model = dmdonvi::where('madv',$madv)->first();
            if(session('admin')->sadmin == 'SSA'){
                DB::statement('Delete From hosocanbo where madv ='.$madv);
            }
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }

    function get_canbo(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $a_cv_m = array();
            $a_pb_m = array();
            $a_cv = array();
            $a_pb = array();

            $model_canbo = hosocanbo::where('madv', $inputs['madv_lay'])->get();
            $maso = getdate()[0]; //lưu mã số

            $model_chucvu_moi = dmchucvucq::where('madv', $inputs['madv'])->get();
            $a_chucvu = array();
            foreach($model_chucvu_moi as $chucvu){
                $ma = '-' . chuanhoachuoi(trim($chucvu->tencv)) . '-';
                $a_chucvu[$ma] = $chucvu->macvcq;
            }

            $model_chucvu_cu = dmchucvucq::where('madv', $inputs['madv_lay'])
                ->wherein('macvcq',a_unique( array_column($model_canbo->toarray(),'macvcq')))->get();
            //$a_chucvu_cu = array();
            foreach($model_chucvu_cu as $chucvu){
                $ma = '-' . chuanhoachuoi(trim($chucvu->tencv)) . '-';
                if(isset($a_chucvu[$ma])){
                    $a_cv[$chucvu->macvcq] = $a_chucvu[$ma];
                }else{
                    $maso_m = $inputs['madv'] . '_' . ($maso++);
                    $a_cv_m[] = ['macvcq'=>$maso_m, 'tencv' => $chucvu->tencv, 'madv' => $inputs['madv']];
                    $a_cv[$chucvu->macvcq] = $maso_m;
                }
            }

            $model_phongban_moi = dmphongban::where('madv',$inputs['madv'])->get();
            $a_phongban = array();
            foreach($model_phongban_moi as $phongban) {
                $ma = '-' . chuanhoachuoi(trim($phongban->tenpb)) . '-';
                $a_phongban[$ma] = $phongban->mapb;
            }

            $model_phongban_cu = dmphongban::where('madv',$inputs['madv_lay'])
                ->wherein('mapb',a_unique( array_column($model_canbo->toarray(),'mapb')))->get();
            foreach($model_phongban_cu as $phongban) {
                $ma = '-' . chuanhoachuoi(trim($phongban->tenpb)) . '-';
                if(isset($a_phongban[$ma])){
                    $a_pb[$phongban->mapb] = $a_phongban[$ma];
                }else{
                    $maso_m = $inputs['madv'] . '_' . ($maso++);
                    $a_pb_m[] = ['mapb'=>$maso_m, 'tenpb' => $phongban->tenpb, 'madv' => $inputs['madv']];
                    $a_pb[$phongban->mapb] = $maso_m;
                }
            }

            $j = getDbl((hosocanbo::where('madv', $inputs['madv'])->get()->max('stt'))) + 1;

            $a_cb = array();
            $model_canbo = hosocanbo::where('madv', $inputs['madv_lay'])->where('theodoi','<','9')->get();
            //lấy danh sách chức vụ, phòng ban của đơn vị cũ
            foreach($model_canbo as $canbo){
                $canbo->stt = $j++;
                $canbo->madv = $inputs['madv'];
                $canbo->macanbo = $inputs['madv']. '_' . $maso++;
                if(isset($a_cv[$canbo->macvcq])){
                    //set lại mã chức vụ nếu chưa có
                    $canbo->macvcq = $a_cv[$canbo->macvcq];
                }

                if(isset($a_pb[$canbo->mapb])){
                    //set lại mã chức vụ nếu chưa có
                    $canbo->mapb = $a_pb[$canbo->mapb];
                }
                $kq = $canbo->toarray();
                unset($kq['id']);
                $a_cb[] = $kq;
            }
            //dd($a_cb);
            if(count($a_cv_m)){
                dmchucvucq::insert($a_cv_m);
            }
            if(count($a_pb_m)){
                dmphongban::insert($a_pb_m);
            }
            if(count($a_cb)){
                hosocanbo::insert($a_cb);
            }

            $model = dmdonvi::where('madv',$inputs['madv'])->first();
            return redirect('/danh_muc/khu_vuc/chi_tiet?ma_so='.$model->madvbc.'&phan_loai='.$model->phanloaitaikhoan);
        }else
            return view('errors.notlogin');
    }
}
