<?php

namespace App\Http\Controllers;

use App\dmbaomat;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\DnDvLt;
use App\DnDvLtReg;
use App\DonViDvVt;
use App\DonViDvVtReg;
use App\Register;
use App\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    public function index($level){
        //nếu tài khoản ssa, sa thì ko kiểm tra
        //kiểm tra xem tài khoản có thể quản lý tài khoản của đơn vị nào
        if (Session::has('admin')) {
            if(session('admin')->level !='SA' && session('admin')->level !='SSA'){
                return view('errors.noperm');
            }
            $a_baomat=array('H'=>'Cấp huyện','T'=>'Cấp tỉnh');
            $model=dmdonvibaocao::where('level',$level)->get();
            return view('system.users.index')
                ->with('model',$model)
                ->with('level',$level)
                ->with('a_baomat',$a_baomat)
                ->with('furl','/danh_muc/tai_khoan/')
                ->with('pageTitle','Danh mục khu vực, địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    public function list_users(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();

            //Danh sách khu vực địa bàn
            if(isset($inputs['level'])){
                $a_baomat=array('H'=>'Cấp huyện','T'=>'Cấp tỉnh');
                $model=dmdonvibaocao::where('level',$inputs['level'])->get();
                return view('system.users.index')
                    ->with('model',$model)
                    ->with('level',$inputs['level'])
                    ->with('a_baomat',$a_baomat)
                    ->with('furl','/danh_muc/tai_khoan/')
                    ->with('pageTitle','Danh mục khu vực, địa bàn quản lý');
            }

            //Danh sách đơn vị theo địa bàn
            if(isset($inputs['madb'])) {
                $model_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', $inputs['madb'])->get();
                $madv = '';
                if(count($model_donvi)> 0){
                    $madv = $model_donvi->first()->madv;
                }
                $model = Users::where('madv', $madv)->get();

                return view('system.users.index_users')
                    ->with('model', $model)
                    ->with('madv', $madv)
                    ->with('model_donvi', array_column($model_donvi->toarray(), 'tendv', 'madv'))
                    ->with('url', '/danh_muc/tai_khoan/')
                    ->with('pageTitle', 'Danh mục tài khoản sử dụng');
            }

            //Danh sách tài khoản theo đơn vị
            if(isset($inputs['madv'])) {
                $donvi= dmdonvi::where('madv', $inputs['madv'])->first();
                $model_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', $donvi->madvbc)->get();
                $model = Users::where('madv', $inputs['madv'])->get();
                return view('system.users.index_users')
                    ->with('model', $model)
                    ->with('madv', $inputs['madv'])
                    ->with('model_donvi', array_column($model_donvi->toarray(), 'tendv', 'madv'))
                    ->with('url', '/danh_muc/tai_khoan/')
                    ->with('pageTitle', 'Danh mục tài khoản sử dụng');
            }

            //Chỉnh sửa thông tin người dùng
            if(isset($inputs['username'])) {
                $model = Users::where('username', $inputs['username'])->first();
                return view('system.users.edit')
                    ->with('model', $model)
                    ->with('url', '/danh_muc/tai_khoan/')
                    ->with('pageTitle', 'Danh mục tài khoản sử dụng');
            }

        } else
            return view('errors.notlogin');
    }

    public function create($madv)
    {
        if (Session::has('admin')) {
            return view('system.users.create')
                ->with('madv', $madv)
                ->with('url', '/danh_muc/tai_khoan/')
                ->with('pageTitle', 'Tạo mới tài khoản người dùng');
        } else
            return view('errors.notlogin');
    }

    public function update(Request $request, $id)
    {
        if (Session::has('admin')) {
            $input = $request->all();
            $model = Users::findOrFail($id);
            $model->name = $input['name'];
            $model->phone = $input['phone'];
            $model->email = $input['email'];
            $model->status = $input['status'];
            $model->username = $input['username'];
            if ($input['newpass'] != '')
                $model->password = md5($input['newpass']);
            $model->save();

            return redirect('/danh_muc/tai_khoan/list_user?&madv=' . $model->madv);

        } else {
            return redirect('');
        }
    }
    public function store(Request $request)
    {
        if (Session::has('admin')) {
            $input = $request->all();
            $donvi = dmdonvi::where('madv',$input['madv'])->first();
            $input['maxa'] = $donvi->madv;
            $input['level'] = isset($donvi->level)?'':isset($donvi->level);
            $input['password'] = md5($input['password']);
            $input['permission'] = getPermissionDefault($donvi->level);
            $input['sadmin'] = isset($input['sadmin'])?$input['sadmin'] : 'NULL';
            Users::create($input);

            return redirect('/danh_muc/tai_khoan/list_user?&madv=' . $input['madv']);

        } else {
            return redirect('');
        }
    }

    public function login()
    {
        if (Session::has('admin')) {
            Session::flush();
        }
        return view('system.users.login')
            ->with('pageTitle', 'Đăng nhập hệ thống');
    }

    public function signin(Request $request)
    {
        $input = $request->all();
        $check = Users::where('username', $input['username'])->count();
        if ($check == 0)
            return view('errors.invalid-user');
        else{
            $ttuser = Users::where('username', $input['username'])->first();

            if($ttuser->level != 'SA'){
                $model_donvi = dmdonvi::where('madv', $ttuser->madv)->first();
                $model_donvibaocao = dmdonvibaocao::where('madvbc', $model_donvi->madvbc)->first();
                $model_donvicapduoi = dmdonvi::where('macqcq', $ttuser->madv)->get();

                //Kiểm tra xem có phải tài khoản thuộc đơn vị quản lý khu vực ko ?
                if($model_donvibaocao->madvcq == $ttuser->madv){
                    $ttuser->quanlykhuvuc=true;
                }else{
                    $ttuser->quanlykhuvuc=false;
                }

                //Kiểm tra xem đơn vị có quản lý đơn vị cấp dưới nào ko ?
                if(count($model_donvicapduoi)>0){
                    $ttuser->quanlynhom=true;
                }else{
                    $ttuser->quanlynhom=false;
                }
                $ttuser->level = $model_donvibaocao->level;
                $ttuser->madvqlkv = $model_donvibaocao->madvcq;
                $ttuser->macqcq = $model_donvi->macqcq;
                $ttuser->madvbc = $model_donvi->madvbc;
                $ttuser->maphanloai = $model_donvi->maphanloai;
                $ttuser->capdonvi = $model_donvi->capdonvi;
            }
            //kiểm tra xem user thuộc đơn vị nào, nếu ko thuộc đơn vị nào (trừ tài khoản quản trị) => đăng nhập ko thành công
        }


        //thêm mã đơn vị báo cáo, mã khối phòng ban, mã cqcq
        //dd($ttuser);
        if (md5($input['password']) == $ttuser->password) {

            if ($ttuser->status == "active") {
                Session::put('admin', $ttuser);
                //dd(session('admin'));
                return redirect('')
                    ->with('pageTitle', 'Tổng quan');
            } else
                return view('errors.lockuser');

        } else
            return view('errors.invalid-pass');
    }

    public function cp()
    {
        if (Session::has('admin')) {
            return view('system.users.change-pass')
                ->with('pageTitle', 'Thay đổi mật khẩu');
        } else
            return view('errors.notlogin');
    }

    public function cpw(Request $request)
    {
        $update = $request->all();
        $username = session('admin')->username;
        $password = session('admin')->password;
        $newpass2 = $update['newpassword2'];
        $currentPassword = $update['current-password'];

        if (md5($currentPassword) == $password) {
            $ttuser = Users::where('username', $username)->first();
            $ttuser->password = md5($newpass2);
            if ($ttuser->save()) {
                Session::flush();
                return view('errors.changepassword-success');
            }
        } else {
            dd('Mật khẩu cũ không đúng???');
        }
    }

    public function checkpass(Request $request)
    {
        $input = $request->all();
        $passmd5 = md5($input['pass']);

        if (session('admin')->password == $passmd5) {
            echo 'ok';
        } else {
            echo 'cancel';
        }
    }

    public function checkuser(Request $request)
    {
        $input = $request->all();
        $newusser = $input['user'];

        $model = Users::where('username', $newusser)
            ->first();
        if (isset($model)) {
            echo 'cancel';
        } else {
            echo 'ok';
        }
    }

    public function logout()
    {
        if (Session::has('admin')) {
            Session::flush();
            return redirect('/login');
        } else {
            return redirect('');
        }
    }

    public function edit($id)
    {
        if (Session::has('admin')) {
            $model = Users::findOrFail($id);
            return view('system.users.edit')
                ->with('model', $model)
                ->with('pageTitle', 'Chỉnh sửa thông tin tài khoản');

        } else {
            return redirect('');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($username)
    {//chưa làm
        if (Session::has('admin')) {
            $model = Users::where('username',$username)->first();
            $model->delete();

            return redirect('/danh_muc/tai_khoan/list_user?&madv='.$model->madv);

        } else
            return view('errors.notlogin');
    }

    public function permission($username)
    {
        if (Session::has('admin')) {
            $model = Users::where('username',$username)->first();
            $permission = json_decode(!empty($model->permission) ? $model->permission : getPermissionDefault($model->level));

            return view('system.users.perms')
                ->with('permission', $permission)
                ->with('url', '/danh_muc/tai_khoan/')
                ->with('model', $model)
                ->with('pageTitle', 'Phân quyền cho tài khoản');
        } else
            return view('errors.notlogin');
    }

    public function uppermission(Request $request)
    {
        if (Session::has('admin')) {
            $update = $request->all();
            //dd($request);
            //$id = $request['id'];

            $model = Users::where('username',$update['username'])->first();
            //dd($model);
            if (isset($model)) {
                $update['roles'] = isset($update['roles']) ? $update['roles'] : null;
                $model->permission = json_encode($update['roles']);
                $model->save();

                return redirect('/danh_muc/tai_khoan/list_user?&madv='.$model->madv);

            } else
                dd('Tài khoản không tồn tại');

        } else
            return view('errors.notlogin');
    }

    public function lockuser($id,$pl)
    {

        $arrayid = explode('-', $id);
        foreach ($arrayid as $ids) {
            $model = Users::findOrFail($ids);
            if ($model->status != "Chưa kích hoạt") {
                $model->status = "Vô hiệu";
                $model->save();
            }
        }
        return redirect('users/pl='.$pl);

    }

    public function unlockuser($id,$pl)
    {
        $arrayid = explode('-', $id);
        foreach ($arrayid as $ids) {
            $model = Users::findOrFail($ids);

            if ($model->status != "Chưa kích hoạt") {

                $model->status = "Kích hoạt";
                $model->save();
            }
        }
        return redirect('users/pl='.$pl);
    }
}
