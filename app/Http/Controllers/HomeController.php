<?php

namespace App\Http\Controllers;


use App\dmdonvibaocao;
use App\GeneralConfigs;
use App\hosocanbo;
use App\Users;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
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
                $model = hosocanbo::select('macanbo', 'tencanbo', 'msngbac', 'sunghiep', 'gioitinh', 'ngaytu', 'ngayden','ngaysinh','mact')
                    ->where('theodoi','<' ,'9')
                    ->where('madv', session('admin')->madv)
                    ->get();
                $a_ketqua = array();
                $a_ketqua['congchuc'] = $model->where('sunghiep', 'Công chức')->count();
                $a_ketqua['vienchuc'] = $model->where('sunghiep', 'Viên chức')->count();
                $a_ketqua['khac'] = $model->where('sunghiep', 'Khác')->count();
                $a_ketqua['tapsu'] = $model->where('tenct', 'Tập sự')->count();
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
                    if(isset($ct->ngaysinh)){
                        $ct->ngaynghi = $dt->modify(' +'.($ct->gioitinh == 'Nam' ?$gen['tuoinam']: $gen['tuoinu'] ).' year')->format('Y-m-d');;
                    }
                    /*
                    if(isset($ct->ngayden)){
                        $dt_luong = Carbon::create($ct->ngayden);
                        $ct->ngayden = isset($dt_luong)?  $dt_luong->addDay(): null;
                        $ct->nam_luong=date_format($dt_luong,'Y');
                    }else{
                        $dt_luong = null;
                        $ct->nam_luong = null;
                    }
*/

                    //$ct->ngayden = isset($dt_luong)?  $date->add: null;

                }

                $m_nghihuu = $model->where('nam','<=' ,$date['year']);
                $m_nangluong = $model->where('nam_luong', $date['year']);
                //$m_sinhnhat=$model->where('thang',$date['mon']);
                //$m_hettapsu= $model->where('tenct','Hết tập sự');//Chưa làm
                //dd($m_nangluong->toarray());
                return view('dashboard')
                    ->with('m_nangluong', $m_nangluong)
                    ->with('m_nghihuu', $m_nghihuu)
                    //->with('m_sinhnhat',$m_sinhnhat)
                    //->with('m_hettapsu',$m_hettapsu)
                    ->with('a_ketqua', $a_ketqua)
                    ->with('pageTitle', 'Tổng quan');
            }

        } else
            return view('welcome');

    }

    public function setting()
    {
        if (Session::has('admin')) {
            if(session('admin')->sadmin == 'ssa')
            {
                $model = GeneralConfigs::first();
                $setting = $model->setting;

                return view('system.general.setting')
                    ->with('setting',json_decode($setting))
                    ->with('pageTitle','Cấu hình chức năng chương trình');
            }

        }else
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
        }else
            return view('welcome');
    }

    public function listusers(Request $request)
    {
        $inputs = $request->all();
        $model_diaban = array_column(dmdonvibaocao::select('tendvbc','madvbc')->get()->toArray(),'tendvbc','madvbc');
        $a_diaban = array('ALL'=>'--Chọn địa bàn, khu vực--');
        foreach($model_diaban as $key=>$val){
            $a_diaban[$key] = $val;
        }

        $madvbc = $inputs['madiaban'];
        $model = Users::wherein('madv',function($qr)use($madvbc){
            $qr->select('madv')->from('dmdonvi')->where('madvbc',$madvbc)->get();
        })->get();


        return view('manage.taikhoan.index_users')
            ->with('a_diaban',$a_diaban)
            ->with('model',$model)
            ->with('madvbc',$madvbc)
            ->with('pageTitle','Danh sách đơn vị');


    }

}
