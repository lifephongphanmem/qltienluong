<?php

namespace App\Http\Controllers;


use App\dmdonvi;
use App\dmdonvibaocao;
use App\GeneralConfigs;
use App\hosocanbo;
use App\Users;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (Session::has('admin')) {
            if (session('admin')->username == 'sa')
                return redirect('cau_hinh_he_thong');
            else {
                //thêm phân loại công tác
                //chia ra màn hình đơn vị; màn hình đơn vị tổng hợp
                $model = hosocanbo::select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden', 'ngaysinh', 'mact')
                    ->where('theodoi', '<', '9')
                    ->where('madv', session('admin')->madv)
                    ->get();
                if (session('admin')->phanloaitaikhoan == 'TH')
                    $model = hosocanbo::join('dmdonvi', 'hosocanbo.madv', 'dmdonvi.madv')
                        ->select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'tnndenngay', 'ngaytu', 'ngayden', 'ngaysinh', 'mact')
                        ->where('theodoi', '<', '9')
                        ->where('dmdonvi.macqcq', session('admin')->madv)
                        ->get();

                $a_ketqua = array();
                $a_ketqua['congchuc'] = $model->where('sunghiep', 'Công chức')->count();
                $a_ketqua['vienchuc'] = $model->where('sunghiep', 'Viên chức')->count();
                $a_ketqua['khac'] = $model->where('sunghiep', 'Khác')->count();
                $a_ketqua['tapsu'] = $model->where('tenct ', 'Tập sự')->count();
                $a_ketqua['chinhthuc'] = $model->count() - $a_ketqua['tapsu'];

                $a_ketqua['gt_nam'] = $model->where('gioitinh', 'Nam')->count();
                $a_ketqua['gt_nu'] = $model->where('gioitinh', 'Nữ')->count();

                $date = getdate();
                $gen = getGeneralConfigs();

                foreach ($model as $ct) {
                    $dt = date_create($ct->ngaysinh);

                    $ct->thang = date_format($dt, 'm');
                    $ct->nam = $ct->gioitinh == 'Nam' ? date_format($dt, 'Y') + $gen['tuoinam'] : date_format($dt, 'Y') + $gen['tuoinu'];
                    if (isset($ct->ngayden)) {
                        $dt_luong = date_create($ct->ngayden);
                        $ct->nam_luong = date_format($dt_luong, 'Y');
                        $ct->ngaynangluong = $dt_luong->format('Y-m-d');
                    } else {
                        $ct->nam_luong = null;
                        $ct->ngaynangluong = null;
                    }

                    if (isset($ct->tnndenngay)) {
                        $dt_luong = date_create($ct->tnndenngay);
                        $ct->nam_nghe = date_format($dt_luong, 'Y');
                        //$ct->ngaynangluong = $dt_luong->format('Y-m-d');
                    } else {
                        $ct->nam_nghe = null;
                        //$ct->ngaynangluong = null;
                    }

                    if (isset($ct->ngaysinh)) {
                        $ct->ngaynghi = $dt->modify(' +' . ($ct->gioitinh == 'Nam' ? $gen['tuoinam'] : $gen['tuoinu']) . ' year')->format('Y-m-d');;
                    }
                }

                $m_nghihuu = $model->where('nam', '<=', $date['year']);
                $m_nangluong = $model->where('nam_luong', $date['year']);
                $m_nghe = $model->where('nam_nghe', $date['year']);
                //$m_sinhnhat=$model->where('thang',$date['mon']);
                $m_luanchuyen = \App\hosodieudong::where('madv_dd', session('admin')->madv)->where('trangthai', 'CHONHAN')->get();
                //dd($m_nangluong->toarray());

                //chuyển đổi tài khoản
                //$m_donvi_cd = dmdonvi::where('macqcq', session('admin')->chuyendoi)->get();

                return view('dashboard')
                    ->with('m_nangluong', $m_nangluong->sortby('ngayden'))
                    ->with('m_nghihuu', $m_nghihuu->sortby('ngaysinh'))
                    ->with('m_nghe', $m_nghe->sortby('tnndenngay'))
                    ->with('m_luanchuyen', $m_luanchuyen)
                    //->with('m_donvi_cd', $m_donvi_cd)
                    ->with('a_ketqua', $a_ketqua)
                    ->with('pageTitle', 'Tổng quan');
            }

        } else {
            //dd(getGeneralConfigs());
            return view('welcome')
                ->with('a_gen', getGeneralConfigs());
        }
    }

    public function baotri()
    {
        return view('errors.baotri');
    }

    public function setting()
    {
        if (Session::has('admin')) {
            if (session('admin')->sadmin == 'ssa') {
                $model = GeneralConfigs::first();
                $setting = $model->setting;

                return view('system.general.setting')
                    ->with('setting', json_decode($setting))
                    ->with('pageTitle', 'Cấu hình chức năng chương trình');
            }

        } else
            return view('welcome');
    }

    public function upsetting(Request $request)
    {
        if (Session::has('admin')) {
            $update = $request->all();

            $model = GeneralConfigs::first();

            $update['roles'] = isset($update['roles']) ? $update['roles'] : null;
            $model->setting = json_encode($update['roles']);
            $model->save();

            return redirect('cau_hinh_he_thong');
        } else
            return view('welcome');
    }

    public function listusers(Request $request){
        $inputs = $request->all();
        $model_diaban = array_column(dmdonvibaocao::select('tendvbc', 'madvbc')->get()->toArray(), 'tendvbc', 'madvbc');
        $a_diaban = array('ALL' => '--Chọn địa bàn, khu vực--');
        foreach ($model_diaban as $key => $val) {
            $a_diaban[$key] = $val;
        }

        $madvbc = $inputs['madiaban'];
        $model_dv = dmdonvi::where('madvbc', $inputs['madiaban'])->get();
        $model = Users::wherein('madv', array_column($model_dv->toarray(), 'madv'))->get();
        $a_donvi = $model_dv->keyby('madv')->toarray();
        $a_pl = getPhanLoaiTaiKhoan();
        foreach ($model as $us) {
            $donvi = $a_donvi[$us->madv];
            $us->tendv = $donvi['tendv'];
            $us->phanloaitaikhoan = $a_pl[$donvi['phanloaitaikhoan']];
            $us->status = $us->status == 'active' ? 'Kích hoạt' : 'Vô hiệu hóa';
        }
        //dd($model_dv);
        return view('manage.taikhoan.index_users')
            ->with('a_diaban', $a_diaban)
            //->with('a_donvi', array_column($model_dv->toarray(), 'tendv', 'madv'))
            ->with('model', $model)
            ->with('madvbc', $madvbc)
            ->with('pageTitle', 'Danh sách đơn vị');
    }

    public function list_donvi_cd(){
        if (Session::has('admin')) {
            $m_donvi_cd = dmdonvi::where('macqcq', session('admin')->chuyendoi)
                ->orwhere('madv', session('admin')->chuyendoi)->get();

            return view('system.manage.list_donvi')
                ->with('m_donvi_cd', $m_donvi_cd)
                ->with('pageTitle', 'Chuyển đơn vị');
        } else
            return view('errors.notlogin');
    }
}
