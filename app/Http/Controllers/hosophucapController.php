<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmphongban;
use App\dmphucap;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\Http\Requests;
use App\hosophucap;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class hosophucapController extends Controller
{
    function index(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_cb = hosocanbo::where('madv',session('admin')->madv)->where('theodoi','<','9')->orderby('stt')->get();
            //$a_ct = array_column(dmphanloaict::all()->toArray(), 'tenct', 'mact');
            //$a_pb = getPhongBan(false);
            $a_cv = getChucVuCQ(false);
            if(!isset($inputs['canbo'])) {                
                $inputs['canbo'] = $m_cb->first()->macanbo ?? '';                
            }

            $model = hosophucap::where('macanbo', $inputs['canbo'])->get();
            //$m_pc = dmphucap_donvi::all('mapc', 'tenpc')->toArray();
            $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai','<','3')
                ->get()->toarray(),'tenpc', 'mapc');


            //dd($m_pc);
            $inputs['furl'] = '/nghiep_vu/qua_trinh/phu_cap/';
            //$inputs['furl_ajax'] = '/ajax/phu_cap/';
            //dd($inputs);
            return view('manage.phucap.index')
                ->with('inputs', $inputs)
                //->with('a_pb', $a_pb)
                ->with('a_cv', $a_cv)
                ->with('a_cb',array_column($m_cb->toarray(),'tencanbo','macanbo'))
                ->with('m_cb', $m_cb)
                ->with('model', $model)
                ->with('a_pc', $a_pc)
                ->with('pageTitle', 'Danh sách quá trình hưởng lương, phụ cấp');
        } else
            return view('errors.notlogin');
    }

    function create(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['furl'] = '/nghiep_vu/qua_trinh/phu_cap/';
            $model = hosocanbo::select('macanbo','macvcq','tencanbo')->where('macanbo',$inputs['macanbo'])->first();
            $model->maphanloai = 'CONGTAC';
            $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai','<','3')
                ->get()->toarray(),'tenpc', 'mapc');

            return view('manage.phucap.create')
                ->with('inputs',$inputs)
                ->with('model',$model)
                ->with('a_pc',$a_pc)
                ->with('pageTitle', 'Thêm mới quá trình hưởng lương, phụ cấp');

        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            if($inputs['ngaytu']>=$inputs['ngayden']){
                return view('errors.data_error')
                    ->with('message','Ngày tháng không hợp lệ.')
                    ->with('furl','/nghiep_vu/qua_trinh/phu_cap/danh_sach?canbo='.$inputs['macanbo']);
            }
            $ngaytu = $inputs['ngaytu'];
            $ngayden = $inputs['ngayden'];
            //kiêm tra xem phụ cấp đang được hưởng ko
            $m_chk = hosophucap::where('macanbo',$inputs['macanbo'])->where('mapc',$inputs['mapc'])
                ->where(function($qr)use($ngaytu,$ngayden){
                    $qr->WhereBetween('ngaytu',[$ngaytu,$ngayden])
                    ->orWhereBetween('ngayden',[$ngaytu,$ngayden]);
                })//->toSql();
                ->get();

            if(count($m_chk) > 0){
                $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)
                    ->where('phanloai','<','3')
                    ->get()->toarray(),'tenpc', 'mapc');

                return view('errors.data_error')
                    ->with('message',''.$a_pc[$inputs['mapc']] .' vẫn đang được hưởng (thời gian hưởng không hợp lệ).')
                    ->with('furl','/nghiep_vu/qua_trinh/phu_cap/danh_sach?canbo='.$inputs['macanbo']);
            }

            $model = new hosophucap();
            $model->maso = session('admin')->madv . '_' . getdate()[0];;
            $model->maphanloai = 'CONGTAC';
            $model->macanbo = $inputs['macanbo'];
            $model->macvcq = $inputs['macvcq'];
            $model->ngaytu = getDateTime($inputs['ngaytu']);
            $model->ngayden = getDateTime($inputs['ngayden']);
            $model->mapc = $inputs['mapc'];
            $model->heso = chkDbl($inputs['heso']);
            $model->save();

            return redirect('/nghiep_vu/qua_trinh/phu_cap/danh_sach?canbo='.$inputs['macanbo']);
        } else
            return view('errors.notlogin');
    }

    function edit(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = hosophucap::where('maso',$inputs['maso'])->first();
            $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai','<','3')
                ->get()->toarray(),'tenpc', 'mapc');
            $inputs['furl'] = '/nghiep_vu/qua_trinh/phu_cap/';
            //dd($model);
            return view('manage.phucap.edit')
                ->with('inputs',$inputs)
                ->with('model',$model)
                ->with('a_pc',$a_pc)
                ->with('pageTitle', 'Thông tin quá trình hưởng lương, phụ cấp');

        } else
            return view('errors.notlogin');
    }

    function update(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $ngaytu = $inputs['ngaytu'];
            $ngayden = $inputs['ngayden'];

            $m_chk = hosophucap::where('macanbo',$inputs['macanbo'])
                ->where('mapc',$inputs['mapc'])
                ->where('maso','<>',$inputs['maso'])
                ->where(function($qr)use($ngaytu,$ngayden){
                    $qr->WhereBetween('ngaytu',[$ngaytu,$ngayden])
                        ->orWhereBetween('ngayden',[$ngaytu,$ngayden]);
                })//->toSql();
                ->get();

            if(count($m_chk) > 0){
                $a_pc = array_column(dmphucap_donvi::where('madv', session('admin')->madv)
                    ->where('phanloai','<','3')
                    ->get()->toarray(),'tenpc', 'mapc');

                return view('errors.data_error')
                    ->with('message',''.$a_pc[$inputs['mapc']] .' vẫn đang được hưởng (thời gian hưởng không hợp lệ).')
                    ->with('furl','/nghiep_vu/qua_trinh/phu_cap/danh_sach?canbo='.$inputs['macanbo']);
            }
            $model = hosophucap::where('maso',$inputs['maso'])->first();
            $model->ngaytu = getDateTime($inputs['ngaytu']);
            $model->ngayden = getDateTime($inputs['ngayden']);
            $model->mapc = $inputs['mapc'];
            $model->heso = chkDbl($inputs['heso']);
            $model->save();
            return redirect('/nghiep_vu/qua_trinh/phu_cap/danh_sach?canbo='.$inputs['macanbo']);
        } else
            return view('errors.notlogin');
    }

    function destroy(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = hosophucap::where('maso',$inputs['maso'])->first();
            $model->delete();
            return redirect('/nghiep_vu/qua_trinh/phu_cap/danh_sach?canbo='.$model->macanbo);
        } else
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
        $model = hosophucap::find($inputs['id']);
        die($model);
    }

    //<editor-fold desc="Tra cứu">
    function search(){
        if (Session::has('admin')) {
            $m_pb=dmphongban::all('mapb','tenpb');
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq');
            $m_pc=dmphucap::all('mapc','tenpc','hesopc')->toArray();

            return view('search.phucap.index')
                ->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                ->with('m_pc',$m_pc)
                ->with('pageTitle','Tra cứu hồ sơ quá trình phụ cấp của cán bộ');
        } else
            return view('errors.notlogin');
    }

    function result(Request $request){
        if (Session::has('admin')) {
            $model=hosocanbo::join('dmchucvucq', 'hosocanbo.macvcq', '=', 'dmchucvucq.macvcq')
                ->join('hosotinhtrangct', 'hosocanbo.macanbo', '=', 'hosotinhtrangct.macanbo')
                ->join('hosophucap', 'hosocanbo.macanbo', '=', 'hosophucap.macanbo')
                ->select('hosocanbo.macanbo','hosocanbo.tencanbo','hosocanbo.macvcq','hosocanbo.mapb','hosocanbo.gioitinh'
                    ,'hosophucap.ngaytu','hosophucap.ngayden','hosophucap.mapc','hosophucap.hesopc')
                ->where('hosotinhtrangct.hientai','1')
                ->where('hosotinhtrangct.phanloaict','Đang công tác')
                ->get();

            $inputs=$request->all();
            if($inputs['tencanbo']!=''){$model=$model->where('tencanbo', $inputs['tencanbo']);}
            if($inputs['mapb']!=''){$model=$model->where('mapb', $inputs['mapb']);}
            if($inputs['macvcq']!=''){$model=$model->where('macvcq', $inputs['macvcq']);}
            if($inputs['ngaytu']!=''){$model=$model->where('ngaytu','>=', $inputs['ngaytu']);}
            if($inputs['ngayden']!=''){$model=$model->where('ngayden','<=', $inputs['ngayden']);}
            if($inputs['gioitinh']!=''){$model=$model->where('gioitinh',$inputs['gioitinh']);}
            if($inputs['mapc']!=''){$model=$model->where('mapc',$inputs['mapc']);}

            $m_pc=array_column(dmphucap::all('mapc','tenpc')->toArray(),'tenpc','mapc');
            foreach($model as $hs){
                $hs->tenpc=$m_pc[$hs->mapc];
            }
            /*
            $m_pb=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach($model as $hs){
                $hs->tenpb=getInfoPhongBan($hs,$m_pb);
                $hs->tencvcq=getInfoChucVuCQ($hs,$m_cvcq);
            }
            */
            return view('search.phucap.result')
                ->with('model',$model)
                ->with('pageTitle','Kết quả tra cứu hồ sơ quá trình phụ cấp của cán bộ');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>
}
