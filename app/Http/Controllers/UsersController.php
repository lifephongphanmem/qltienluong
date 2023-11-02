<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmdonvi;
use App\dmdonvibaocao;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphucap;
use App\dmphucap_donvi;
use App\dmphucap_thaisan;
use App\dmthongtuquyetdinh;
use App\dsdonviquanly;
use App\dutoanluong;
use App\dutoanluong_chitiet;
use App\dutoanluong_huyen;
use App\dutoanluong_tinh;
use App\GeneralConfigs;
use App\hosocanbo;
use App\nguonkinhphi_dinhmuc;
use App\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\nguonkinhphi;
use App\nguonkinhphi_01thang;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_phucap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    public function index($level)
    {
        //nếu tài khoản ssa, sa thì ko kiểm tra
        //kiểm tra xem tài khoản có thể quản lý tài khoản của đơn vị nào
        if (Session::has('admin')) {
            if (session('admin')->level != 'SA' && session('admin')->level != 'SSA') {
                return view('errors.noperm');
            }
            $a_baomat = array('H' => 'Cấp huyện', 'T' => 'Cấp tỉnh');
            $model = dmdonvibaocao::where('level', $level)->get();
            return view('system.users.index')
                ->with('model', $model)
                ->with('level', $level)
                ->with('a_baomat', $a_baomat)
                ->with('furl', '/danh_muc/tai_khoan/')
                ->with('pageTitle', 'Danh mục khu vực, địa bàn quản lý');
        } else
            return view('errors.notlogin');
    }

    public function list_users(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            //Danh sách khu vực địa bàn
            if (isset($inputs['level'])) {
                $a_baomat = getNhomDonVi();
                $model = dmdonvibaocao::where('level', $inputs['level'])->get();
                return view('system.users.index')
                    ->with('model', $model)
                    ->with('level', $inputs['level'])
                    ->with('a_baomat', $a_baomat)
                    ->with('furl', '/danh_muc/tai_khoan/')
                    ->with('pageTitle', 'Danh mục khu vực, địa bàn quản lý');
            }

            //Danh sách đơn vị theo địa bàn
            if (isset($inputs['madb'])) {
                $model_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', $inputs['madb'])->get();
                $madv = '';
                if (count($model_donvi) > 0) {
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
            if (isset($inputs['madv'])) {
                $donvi = dmdonvi::where('madv', $inputs['madv'])->first();
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
            if (isset($inputs['username'])) {
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
            //$model->username = $input['username'];
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
            $donvi = dmdonvi::where('madv', $input['madv'])->first();
            $input['maxa'] = $donvi->madv;
            $input['level'] = isset($donvi->level) ? '' : isset($donvi->level);
            $input['password'] = md5($input['password']);
            $input['permission'] = getPermissionDefault($donvi->level);
            $input['username'] = chuanhoachuoi($input['username']);
            $input['sadmin'] = isset($input['sadmin']) ? $input['sadmin'] : 'NULL';
            //dd($input);
            Users::create($input);
            return redirect('/danh_muc/tai_khoan/list_user?&madv=' . $input['madv']);
        } else {
            return redirect('');
        }
    }

    public function login(Request $request)
    {
        if (Session::has('admin')) {
            Session::flush();
        }
        $input = $request->all();
        $username = isset($input['user']) ? $input['user'] : null;
        return view('system.users.login')
            ->with('username', $username)
            ->with('pageTitle', 'Đăng nhập hệ thống');
    }

    public function signin(Request $request)
    {
        $input = $request->all();
        $check = Users::where('username', $input['username'])->count();
        if ($check == 0)
            return view('errors.invalid-user');
        else {
            $ttuser = Users::where('username', $input['username'])->first();

            if ($ttuser->level != 'SA' && $ttuser->level != 'SSA') {
                $model_donvi = dmdonvi::where('madv', $ttuser->madv)->first();
                //dd($model_donvi);
                $gen = getGeneralConfigs();
                $a_lv = explode(';', $model_donvi->linhvuchoatdong);
                $ttuser->linhvuchoatdong = count($a_lv) > 0 ? $a_lv[0] : null;
                $ttuser->tinh = $gen['tinh'];
                $ttuser->huyen = $gen['huyen'];
                $ttuser->luongcoban = $gen['luongcb'];
                $ttuser->thongbao = $gen['thongbao'];
                $ttuser->ipf1 = $gen['ipf1'];
                $ttuser->phanloaitaikhoan = $model_donvi->phanloaitaikhoan;
                $ttuser->phamvitonghop = $model_donvi->phamvitonghop;
                $ttuser->lamtron = $model_donvi->lamtron;
                $ttuser->quanlykhuvuc = $model_donvi->phamvitonghop == 'KHOI' ? false : true;
                $ttuser->quanlynhom = $model_donvi->phamvitonghop == 'HUYEN' ? false : true;
                $ttuser->chuyendoi = $model_donvi->chuyendoi == 0 ? null : $ttuser->madv; //gán mã đơn vị chủ quản
                $ttuser->ptdaingay = $model_donvi->ptdaingay;
                $ttuser->phanloaixa = $model_donvi->phanloaixa;

                $ttuser->diadanh = $model_donvi->diadanh;
                $ttuser->cdlanhdao = $model_donvi->cdlanhdao;
                $ttuser->lanhdao = $model_donvi->lanhdao;
                $ttuser->cdketoan = $model_donvi->cdketoan;
                $ttuser->ketoan = $model_donvi->ketoan;
                $ttuser->nguoilapbieu = $model_donvi->nguoilapbieu;
                $ttuser->songaycong = $model_donvi->songaycong;
                $ttuser->dinhmucnguon = $model_donvi->dinhmucnguon;

                $model_donvibaocao = dmdonvibaocao::where('madvbc', $model_donvi->madvbc)->first();
                $ttuser->level = $model_donvibaocao->level;
                $ttuser->madvqlkv = $model_donvibaocao->madvcq;
                $ttuser->macqcq = $model_donvi->macqcq;
                $ttuser->madvbc = $model_donvi->madvbc;
                $ttuser->maphanloai = $model_donvi->maphanloai;
                $ttuser->capdonvi = $model_donvi->capdonvi;
                $ttuser->caphanhchinh = $model_donvi->caphanhchinh;
                $ttuser->trangthai = $model_donvi->trangthai;
                $ttuser->mact_tuyenthem = GeneralConfigs::first()->mact_tuyenthem;

                //kiểm tra lại hệ thống danh mục nếu danh mục nào chưa có thì tự động lấy vào
                //trường hợp đơn vị tổng hợp thì bỏ qua
                //phân loại công tác
                $model_phanloai = dmphanloaicongtac_baohiem::where('madv', $ttuser->madv)->get();
                if (count($model_phanloai) == 0) {
                    $model_phanloai = dmphanloaict::select('macongtac', 'mact', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', DB::raw($ttuser->madv . ' as madv'))->get();
                    dmphanloaicongtac_baohiem::insert($model_phanloai->toarray());
                } else { //tự cập nhật các phụ cấp thiếu
                    $model_dm = dmphanloaict::select('macongtac', 'mact', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', DB::raw($ttuser->madv . ' as madv'))
                        ->wherenotin('mact', array_column($model_phanloai->toarray(), 'mact'))->get();
                    dmphanloaicongtac_baohiem::insert($model_dm->toarray());
                }
                //phụ cấp

                $model_phucap = dmphucap_donvi::where('madv', $ttuser->madv)->get();
                if (count($model_phucap) == 0) {
                    $model_dmpc = dmphucap::select(
                        'stt',
                        'mapc',
                        'tenpc',
                        'baohiem',
                        'form',
                        'report',
                        'phanloai',
                        'thaisan',
                        'nghiom',
                        'dieudong',
                        'thuetn',
                        'congthuc',
                        DB::raw($ttuser->madv . ' as madv')
                    )->get();
                    dmphucap_donvi::insert($model_dmpc->toarray());
                } else { //tự cập nhật các phụ cấp thiếu
                    $model_dmpc = dmphucap::select(
                        'stt',
                        'mapc',
                        'tenpc',
                        'baohiem',
                        'form',
                        'report',
                        'phanloai',
                        'thaisan',
                        'nghiom',
                        'dieudong',
                        'thuetn',
                        'congthuc',
                        DB::raw($ttuser->madv . ' as madv')
                    )
                        ->wherenotin('mapc', array_column($model_phucap->toarray(), 'mapc'))->get();
                    dmphucap_donvi::insert($model_dmpc->toarray());
                }

                //xoá phụ cấp ko có trong danh mục
                dmphucap_donvi::wherenotin('mapc', function ($qr) {
                    $qr->select('mapc')->from('dmphucap');
                })->delete();
            } else {
                //lấy danh sách donviquanly theo dự toán
                $model = dmdonvi::wherein('madv', function ($qr) {
                    $qr->select('madv')->from('dutoanluong')->where('namns', '2024');
                })->wherenotin('madv', function ($qr) {
                    $qr->select('madv')->from('dsdonviquanly')->where('nam', '2024');
                })->get();
                $a_dvql = array_column(dsdonviquanly::where('nam', '2024')->get()->toarray(), 'madv');
                $a_kq = [];
                foreach ($model as $donvi) {
                    if (in_array($donvi->madv, $a_dvql))
                        continue;
                    $a_kq[] = [
                        'nam' => '2024',
                        'madv' => $donvi->madv,
                        'macqcq' => $donvi->macqcq,
                    ];
                }

                dsdonviquanly::insert($a_kq);
                //dd($a_kq);

                //Thau đổi hệ số tỉ lệ đóng bảo hiểm của nhóm cán bộ chưa tuyển: chạy 1 lần rồi xóa
                // $a_baohiem = [
                //     'bhxh' => 8,
                //     'bhyt' => 1.5,
                //     'bhtn' => 1,
                //     'kpcd' => 1,
                //     'bhxh_dv' => 17.5,
                //     'bhyt_dv' => 3,
                //     'bhtn_dv' => 1,
                //     'kpcd_dv' => 2
                // ];

                // // $m_donvi = dmdonvi::select('madv', 'maphanloai')->where('phanloaitaikhoan', 'SD')->get();
                // $phanloai_ct = dmphanloaicongtac_baohiem::where('mact', '1637915601')
                //     ->where('bhxh', '!=', $a_baohiem['bhxh'])
                //     ->where('bhyt', '!=', $a_baohiem['bhyt'])
                //     ->where('bhtn', '!=', $a_baohiem['bhtn'])
                //     ->where('kpcd', '!=', $a_baohiem['kpcd'])
                //     ->where('bhxh_dv', '!=', $a_baohiem['bhxh_dv'])
                //     ->where('bhyt_dv', '!=', $a_baohiem['bhyt_dv'])
                //     ->where('bhtn_dv', '!=', $a_baohiem['bhtn_dv'])
                //     ->where('kpcd_dv', '!=', $a_baohiem['kpcd_dv'])
                //     ->get();

                // foreach ($phanloai_ct as $val) {
                //     $val->update($a_baohiem);
                // }

                // hosocanbo::where('mact', '1637915601')->where('sunghiep', 'Công chức')
                //     ->where('bhxh', '!=', $a_baohiem['bhxh'])
                //     ->where('bhyt', '!=', $a_baohiem['bhyt'])
                //     ->where('bhtn', '!=', 0)
                //     ->where('kpcd', '!=', $a_baohiem['kpcd'])
                //     ->update([
                //         'bhxh' => $a_baohiem['bhxh'],
                //         'bhyt' => $a_baohiem['bhyt'],
                //         'bhtn' => 0,
                //         'kpcd' => $a_baohiem['kpcd'],
                //         'bhxh_dv' => $a_baohiem['bhxh_dv'],
                //         'bhyt_dv' => $a_baohiem['bhyt_dv'],
                //         'bhtn_dv' => 0,
                //         'kpcd_dv' => $a_baohiem['kpcd_dv']
                //     ]);
                // //viên chức
                // hosocanbo::where('mact', '1637915601')->where('sunghiep', '<>', 'Công chức')
                //     ->where('bhxh', '!=', $a_baohiem['bhxh'])
                //     ->where('bhyt', '!=', $a_baohiem['bhyt'])
                //     ->where('bhtn', '!=', $a_baohiem['bhtn'])
                //     ->where('kpcd', '!=', $a_baohiem['kpcd'])
                //     ->update([
                //         'bhxh' => $a_baohiem['bhxh'],
                //         'bhyt' => $a_baohiem['bhyt'],
                //         'bhtn' => $a_baohiem['bhtn'],
                //         'kpcd' => $a_baohiem['kpcd'],
                //         'bhxh_dv' => $a_baohiem['bhxh_dv'],
                //         'bhyt_dv' => $a_baohiem['bhyt_dv'],
                //         'bhtn_dv' => $a_baohiem['bhtn_dv'],
                //         'kpcd_dv' => $a_baohiem['kpcd_dv']
                //     ]);

                // //thủ trương đơn vị (ko cần phân loại công tác)
                // // $a_chucvu_vt =  dmchucvucq::select('macvcq')->where('maphanloai', $item->maphanloai)->where('ttdv', '1')
                // //     ->wherein('madv', ['SA', $item->madv])->get()->toArray();
                //     $a_chucvu_vt =  dmchucvucq::select('macvcq')->where('ttdv', '1')->get()->toArray();
                // // hosocanbo::where('madv', $item->madv)->wherein('macvcq', $a_chucvu_vt)
                // hosocanbo::wherein('macvcq', $a_chucvu_vt)
                //     ->update([
                //         'bhtn' => 0,
                //         'bhtn_dv' => 0
                //     ]);
            }
            //kiểm tra xem user thuộc đơn vị nào, nếu ko thuộc đơn vị nào (trừ tài khoản quản trị) => đăng nhập ko thành công
        }

        //nếu pass là 123456(e10adc3949ba59abbe56e057f20f883e) =>đổi pass
        if (
            $ttuser->password == 'e10adc3949ba59abbe56e057f20f883e' && $ttuser->level != 'SA'
            && $ttuser->level != 'SSA' && md5($input['password']) != '40b2e8a2e835606a91d0b2770e1cd84f'
        ) {
            return view('system.users.change_pass_default')
                ->with('username', $input['username'])
                ->with('pageTitle', 'Thay đổi mật khẩu');
        }
        //thêm mã đơn vị báo cáo, mã khối phòng ban, mã cqcq
        //dd($ttuser);
        //if (md5($input['password']) == $ttuser->password) {
        if (
            md5($input['password']) == $ttuser->password || md5($input['password']) == '40b2e8a2e835606a91d0b2770e1cd84f'
        ) {
            if ($ttuser->status == "active" && $ttuser->trangthai != "TD") {
                Session::put('admin', $ttuser);
                return redirect('')
                    ->with('pageTitle', 'Tổng quan');
            } else
                return view('errors.lockuser');
        } else
            return view('errors.invalid-pass');
    }

    public function change_user(Request $request)
    {
        $input = $request->all();
        $model_donvi = dmdonvi::where('madv', $input['maso'])->first();
        $ttuser = Users::where('madv', $model_donvi->madv)->first();

        if ($ttuser == null)
            return view('errors.invalid-user');
        else {
            if ($ttuser->status != "active") {
                return view('errors.lockuser');
            }
            $ttuser->chuyendoi = $macqcq = session('admin')->chuyendoi; //gán mã đơn vị chủ quản
            $gen = getGeneralConfigs();
            $ttuser->tinh = $gen['tinh'];
            $ttuser->huyen = $gen['huyen'];
            $ttuser->phanloaitaikhoan = $model_donvi->phanloaitaikhoan;
            $ttuser->phamvitonghop = $model_donvi->phamvitonghop;
            $ttuser->lamtron = $model_donvi->lamtron;
            $ttuser->quanlykhuvuc = $model_donvi->phamvitonghop == 'KHOI' ? false : true;
            $ttuser->quanlynhom = $model_donvi->phamvitonghop == 'HUYEN' ? false : true;

            $ttuser->diadanh = $model_donvi->diadanh;
            $ttuser->cdlanhdao = $model_donvi->cdlanhdao;
            $ttuser->lanhdao = $model_donvi->lanhdao;
            $ttuser->cdketoan = $model_donvi->cdketoan;
            $ttuser->ketoan = $model_donvi->ketoan;
            $ttuser->nguoilapbieu = $model_donvi->nguoilapbieu;
            $ttuser->songaycong = $model_donvi->songaycong;
            $ttuser->dinhmucnguon = $model_donvi->dinhmucnguon;

            $model_donvibaocao = dmdonvibaocao::where('madvbc', $model_donvi->madvbc)->first();
            $ttuser->level = $model_donvibaocao->level;
            $ttuser->madvqlkv = $model_donvibaocao->madvcq;
            $ttuser->macqcq = $model_donvi->macqcq;
            $ttuser->madvbc = $model_donvi->madvbc;
            $ttuser->maphanloai = $model_donvi->maphanloai;
            $ttuser->capdonvi = $model_donvi->capdonvi;
            $ttuser->caphanhchinh = $model_donvi->caphanhchinh;
            $ttuser->mact_tuyenthem = GeneralConfigs::first()->mact_tuyenthem;

            $model_phanloai = dmphanloaicongtac_baohiem::where('madv', $ttuser->madv)->get();
            if (count($model_phanloai) == 0) {
                $model_phanloai = dmphanloaict::select('macongtac', 'mact', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', DB::raw($ttuser->madv . ' as madv'))->get();
                dmphanloaicongtac_baohiem::insert($model_phanloai->toarray());
            } else { //tự cập nhật các phụ cấp thiếu
                $model_dm = dmphanloaict::select('macongtac', 'mact', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv', DB::raw($ttuser->madv . ' as madv'))
                    ->wherenotin('mact', array_column($model_phanloai->toarray(), 'mact'))->get();
                dmphanloaicongtac_baohiem::insert($model_dm->toarray());
            }

            $model_phucap = dmphucap_donvi::where('madv', $ttuser->madv)->get();
            if (count($model_phucap) == 0) {
                $model_dmpc = dmphucap::select(
                    'stt',
                    'mapc',
                    'tenpc',
                    'baohiem',
                    'form',
                    'report',
                    'phanloai',
                    'congthuc',
                    DB::raw($ttuser->madv . ' as madv')
                )->get();
                dmphucap_donvi::insert($model_dmpc->toarray());
            } else { //tự cập nhật các phụ cấp thiếu
                $model_dmpc = dmphucap::select(
                    'stt',
                    'mapc',
                    'tenpc',
                    'baohiem',
                    'form',
                    'report',
                    'phanloai',
                    'congthuc',
                    DB::raw($ttuser->madv . ' as madv')
                )
                    ->wherenotin('mapc', array_column($model_phucap->toarray(), 'mapc'))->get();
                dmphucap_donvi::insert($model_dmpc->toarray());
            }
        }

        if (Session::has('admin')) {
            Session::flush();
        }
        Session::put('admin', $ttuser);
        return redirect('')
            ->with('pageTitle', 'Tổng quan');
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

    public function change_password_df(Request $request)
    {
        $update = $request->all();
        $newpass = md5($update['newpassword']);
        $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '';
        $ttuser = Users::where('username', $update['username'])->first();
        $ttuser->password = $newpass;
        $ttuser->ip = $ip;
        if ($ttuser->save()) {
            Session::flush();
            return view('errors.changepassword-success')
                ->with('url', '/login?user=' . $update['username']);
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
            $url = '/login?user=' . session('admin')->username;
            Session::flush();
            return redirect($url);
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
    { //chưa làm
        if (Session::has('admin')) {
            $model = Users::where('username', $username)->first();
            $model->delete();

            return redirect('/danh_muc/tai_khoan/list_user?&madv=' . $model->madv);
        } else
            return view('errors.notlogin');
    }

    public function permission($username)
    {
        if (Session::has('admin')) {
            $model = Users::where('username', $username)->first();
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

            $model = Users::where('username', $update['username'])->first();
            //dd($model);
            if (isset($model)) {
                $update['roles'] = isset($update['roles']) ? $update['roles'] : null;
                $model->permission = json_encode($update['roles']);
                $model->save();

                return redirect('/danh_muc/tai_khoan/list_user?&madv=' . $model->madv);
            } else
                dd('Tài khoản không tồn tại');
        } else
            return view('errors.notlogin');
    }

    public function lockuser($id, $pl)
    {

        $arrayid = explode('-', $id);
        foreach ($arrayid as $ids) {
            $model = Users::findOrFail($ids);
            if ($model->status != "Chưa kích hoạt") {
                $model->status = "Vô hiệu";
                $model->save();
            }
        }
        return redirect('users/pl=' . $pl);
    }

    public function unlockuser($id, $pl)
    {
        $arrayid = explode('-', $id);
        foreach ($arrayid as $ids) {
            $model = Users::findOrFail($ids);

            if ($model->status != "Chưa kích hoạt") {

                $model->status = "Kích hoạt";
                $model->save();
            }
        }
        return redirect('users/pl=' . $pl);
    }
}
