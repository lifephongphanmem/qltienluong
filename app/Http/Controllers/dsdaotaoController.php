<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dsdaotao;
use App\dsdaotao_chitiet;
use App\hosocanbo;
use App\hosocanbo_temp;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dsdaotaoController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dsdaotao::where('madv',session('admin')->madv)->get();

            return view('functions.daotao.index')
                ->with('furl','/chuc_nang/dao_tao/')
                ->with('furl_ajax','/ajax/dao_tao/')
                ->with('model',$model)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách đào tạo, bồi dưỡng');
        } else
            return view('errors.notlogin');
    }

    function create(){
        if (Session::has('admin')) {
            return view('functions.daotao.create')
                ->with('furl','/chuc_nang/dao_tao/')
                ->with('madv',session('admin')->madv)
                ->with('mads',session('admin')->madv .'_'.getdate()[0])
                ->with('pageTitle','Chi tiết danh sách đào tạo, bồi dưỡng');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {
            $insert = $request->all();
            $insert['ngaytu']=convert2date($insert['ngaytu']);
            $insert['ngayden']=convert2date($insert['ngayden']);
            $insert['ngayqd']=convert2date($insert['ngayqd']);

            dsdaotao::create($insert);
            $model_canbo=hosocanbo_temp::where('mads', $insert['mads'])->get();
            foreach($model_canbo as $canbo){
                dsdaotao_chitiet::create($canbo->toarray());
            }
            hosocanbo_temp::where('mads', $insert['mads'])->delete();
            return redirect('chuc_nang/dao_tao/danh_sach');
        }else
            return view('errors.notlogin');
    }

    function update(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs=$request->all();
        $model = dshuutri::find($inputs['id']);

        $model->soqd=$inputs['soqd'];
        $model->ngayqd=$inputs['ngayqd'];
        $model->nguoiky=$inputs['nguoiky'];
        $model->coquanqd=$inputs['coquanqd'];
        $model->noidung=$inputs['noidung'];
        $model->ngayxet=$inputs['ngayxet'];

        $model->save();

        $result['message'] = 'Cập nhật thành công.';
        $result['status'] = 'success';
        die(json_encode($result));
    }

    function show($maht){
        if (Session::has('admin')) {//hosotinhtrangct
            $model=DB::table('hosotinhtrangct')
                ->join('hosocanbo', 'hosotinhtrangct.macanbo', '=', 'hosocanbo.macanbo')
                ->join('dmchucvucq', 'hosocanbo.macvcq', '=', 'dmchucvucq.macvcq')
                ->select('hosotinhtrangct.*', 'dmchucvucq.sapxep','hosocanbo.tencanbo','hosocanbo.macvcq','hosocanbo.ngaysinh')
                ->where('hosotinhtrangct.maht',$maht)
                ->orderby('dmchucvucq.sapxep')
                ->get();

            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach($model as $hs){
                $hs->tencv=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            return view('manage.huutri.huutri')
                ->with('furl','/chuc_nang/huu_tri/')
                ->with('model',$model)
                ->with('pageTitle','Chi tiết danh sách xét hưu trí');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = dshuutri::find($id);
            $maht=$model->maht;
            $m_hs=hosotinhtrangct::select('macanbo')->where('maht',$maht)->get()->toarray();
            DB::table('hosotinhtrangct')
                ->wherein('macanbo', $m_hs)->update(['hientai' => 1]);
            $model->delete();
            return redirect('/chuc_nang/huu_tri/danh_sach');
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
        $model = dshuutri::find($inputs['id']);
        die($model);
    }

    function get_canbo_temp(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $mads=$inputs['mads'];

        $model=hosocanbo::join('dmchucvucq', 'hosocanbo.macvcq', '=', 'dmchucvucq.macvcq')
            ->select('hosocanbo.macanbo','hosocanbo.tencanbo','hosocanbo.mapb', 'dmchucvucq.sapxep')
            ->where('hosocanbo.madv',$inputs['madv'])
            ->where('hosocanbo.theodoi','1')
            ->wherenotin('hosocanbo.macanbo',function($query) use($mads){
                $query->select('macanbo')
                    ->from('hosocanbo_temp')
                    ->where('mads',$mads)
                    ->get();
                })->orderby('dmchucvucq.sapxep')->get();

        $model_phongban=getPhongBanX();

        $result['status']='success';
        $result['message']='<div class="modal-body" id="thongtincanbo"><div class="row"><div class="col-md-12"><div class="form-group">';
        $result['message'].='<label class="control-label">Họ tên cán bộ</label>';
        $result['message'].='<select class="form-control select2me" id="macb_chon" name="macb_chon">';

        foreach($model_phongban as $pb) {
            $result['message'] .= '<optgroup label="'.$pb->tenpb.'">';
            $model_canbo = $model->where('mapb',$pb->mapb);
            foreach($model_canbo as $cb) {
                $result['message'] .= '<option value = "'.$cb->macanbo.'">'.$cb->tencanbo.'</option >';
            }
            $result['message'] .= '</optgroup >';
        }
        $result['message'] .= '</select></div></div></div></div>';

        die(json_encode($result));
    }

    function add_canbo_temp(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model_canbo=hosocanbo::where('macanbo', $inputs['macanbo'])->first();
        $model_canbo->mads=$inputs['mads'];
        hosocanbo_temp::create($model_canbo->toarray());

        $model = hosocanbo_temp::where('mads',$inputs['mads'])->get();

        $result = $this->return_html_canbo($model);

        die(json_encode($result));
    }

    /**
     * @param $result
     * @param $model
     * @return mixed
     */
    public function return_html_canbo($model,$result=array())
    {
        $phongban=array_column(getPhongBanX()->toarray(),'tenpb','mapb');
        $chucvu=array_column(dmchucvucq::all()->toArray(),'tencv','macvcq');
        $canbo=array_column(getCanBoX()->toarray(),'tenpb','mapb');

        $result['status'] = 'success';
        $result['message'] = '<div class="row" id="thongtinchitiet">';
        $result['message'] .= '<div class="col-md-12">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_3">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr>';
        $result['message'] .= '<th style="text-align: center">STT</th>';
        $result['message'] .= '<th style="text-align: center">Họ và tên</th>';
        $result['message'] .= '<th style="text-align: center">Ngày sinh</th>';
        $result['message'] .= '<th style="text-align: center">Phòng ban</th>';
        $result['message'] .= '<th style="text-align: center">Chức vụ</th>';
        $result['message'] .= '<th style="text-align: center">Thao tác</th>';
        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';
        $result['message'] .= '<tbody>';
        $i = 1;
        foreach ($model as $key => $value) {
            $result['message'] .= '<tr>';
            $result['message'] .= '<td class="text-center">' . $i++ . '</td>';
            $result['message'] .= '<td>' . $value->tencanbo . '</td>';
            $result['message'] .= '<td>' . getDayVn($value->ngaysinh) . '</td>';
            $result['message'] .= '<td>' . $phongban[$value->mapb] . '</td>';
            $result['message'] .= '<td>' . $chucvu[$value->macvcq] . '</td>';
            $result['message'] .= '<td>';
            $result['message'] .= '<button type="button" onclick="cfDel(' . $value->id . ')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">';
            $result['message'] .= '<i class="fa fa-trash-o"></i>&nbsp;Xóa</button></td>';
            $result['message'] .= '</tr>';
        }
        $result['message'] .= '</tbody>';
        $result['message'] .= '</table>';
        $result['message'] .= '</div>';
        $result['message'] .= '</div>';
        return $result;
    }
}
