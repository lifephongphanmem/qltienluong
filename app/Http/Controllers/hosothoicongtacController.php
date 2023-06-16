<?php

namespace App\Http\Controllers;

use App\hosocanbo;
use App\hosothoicongtac;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class hosothoicongtacController extends Controller
{
    function index()
    {
        if (Session::has('admin')) {
            $model = hosothoicongtac::where('madv', session('admin')->madv)->get();

            $a_phanloai = getPhanLoaiThoiCongTac();
            $a_canbo = array_column(hosocanbo::where('madv', session('admin')->madv)->orderby('stt')->get()->toarray(), 'tencanbo', 'macanbo');
            foreach ($model as $hs) {
                $hs->phanloai = isset($a_phanloai[$hs->maphanloai]) ? $a_phanloai[$hs->maphanloai] : "";
            }

            return view('manage.hosothoicongtac.index')
                ->with('model', $model)
                ->with('a_phanloai', $a_phanloai)
                ->with('a_canbo', $a_canbo)
                ->with('furl', '/nghiep_vu/da_nghi/')
                ->with('tendv', getTenDV(session('admin')->madv))
                ->with('pageTitle', 'Danh sách cán bộ đã thôi công tác');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {
            $insert = $request->all();
            //dd($insert);
            $model_chk = hosothoicongtac::where('macanbo',$insert['macanbo'])->first();
            if($model_chk != null){
                $model_chk->update($insert);
                //dd(1);
                return redirect('nghiep_vu/da_nghi/danh_sach');
                //return view('errors.thoicongtac');
            }
            $insert['ngaynghi'] = getDateTime($insert['ngaynghi']);
            $insert['ngayqd'] = getDateTime($insert['ngayqd']);
            if($insert['maso'] == 'ADD'){
                $model_hoso = hosocanbo::where('macanbo',$insert['macanbo'])->first();
                //dd($model_hoso);
                $model_hoso->update(['theodoi'=>'9']);
                $model_hoso->maso = getdate()[0];
                $model_hoso->maphanloai = $insert['maphanloai'];
                $model_hoso->ngaynghi = $insert['ngaynghi'];
                $model_hoso->soqd = $insert['soqd'];
                $model_hoso->ngayqd = $insert['ngayqd'];
                $model_hoso->nguoiky = $insert['nguoiky'];
                $model_hoso->lydo = $insert['lydo'];
                $a_kq = $model_hoso->toarray();
                unset($a_kq['id']);
                hosothoicongtac::create($a_kq);
                //update theodoi = 9;
                //chạy lại số thứ tự
                $m_canbo = hosocanbo::select('macanbo','stt')->where('madv',$model_hoso->madv)->where('stt','>',$model_hoso->stt)->get();
                //dd($m_canbo->toarray());
                foreach($m_canbo as $cb){
                    $tt = chkDbl($cb->stt) - 1;
                    //dd($tt);
                    hosocanbo::where('macanbo',$cb->macanbo)->update(['stt'=>$tt]);
                }
            }else{
               hosothoicongtac::where('maso', $insert['maso'])->first()->update($insert);
            }

            return redirect('nghiep_vu/da_nghi/danh_sach');
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
        $model = hosothoicongtac::where('maso',$inputs['maso'])->first();
        die($model);
    }

    function destroy($id){
        if (Session::has('admin')) {

            $model = hosothoicongtac::find($id);           
            $model_hoso = hosocanbo::where('macanbo',$model->macanbo)->first();
            $stt = $model_hoso->stt;//lưu lại do $m_canbo chứa cả thông tin $model_hoso nên stt bị thay đổi theo
            //dd($model_hoso);
            $m_canbo = hosocanbo::select('macanbo','stt')->where('madv',$model_hoso->madv)->where('stt','>=',$model_hoso->stt)->get();
            //dd($m_canbo->toarray());
            foreach($m_canbo as $cb){
                $tt = chkDbl($cb->stt) + 1;
                hosocanbo::where('macanbo',$cb->macanbo)->update(['stt'=>$tt]);
            }
            hosocanbo::where('macanbo',$model->macanbo)->update(['theodoi'=>'0','stt'=>$stt]);
            //$macanbo = $model->macanbo;
            $model->delete();
            return redirect('/nghiep_vu/da_nghi/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
