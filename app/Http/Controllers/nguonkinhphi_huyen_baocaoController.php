<?php

namespace App\Http\Controllers;

use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi_huyen_baocao;
use App\nguonkinhphi_huyen_baocao_chitiet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class nguonkinhphi_huyen_baocaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi_huyen_baocao::where('madv',session('admin')->madv)->orderby('namns')->get();
            $lvhd = getLinhVucHoatDong(false);
            foreach($model as $ct){
                $ct->tongcong = $ct->thuchien + $ct->dutoan19 + $ct->dutoan18 + $ct->tietkiem17 + $ct->tietkiem18 + $ct->tietkiem19
                    + $ct->dbhocphi + $ct->dbvienphi + $ct->dbkhac + $ct->kdbhocphi + $ct->kdbvienphi + $ct->kdbkhac
                    + $ct->tietkiemchi + $ct->bosung + $ct->caicach;
                $ct->linhvuc = isset($lvhd[$ct->linhvuchoatdong])? $lvhd[$ct->linhvuchoatdong]:'' ;
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            //dd($model);
            return view('manage.nguonkinhphihuyen.index')
                ->with('furl','/nguon_kinh_phi/huyen/')
                ->with('a_trangthai',getStatus())
                ->with('model',$model)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('pageTitle','Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Session::has('admin')) {

            return view('manage.nguonkinhphihuyen.create')
                ->with('furl','/nguon_kinh_phi/huyen/')
                ->with('type', 'create')
                ->with('pageTitle', 'Tạo nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['masodv'] = session('admin')->madv . '_' . getdate()[0];

            $inputs['thuchien'] = chkDbl($inputs['thuchien']);
            $inputs['dutoan19'] = chkDbl($inputs['dutoan19']);
            $inputs['dutoan18'] = chkDbl($inputs['dutoan18']);
            $inputs['tietkiem17'] = chkDbl($inputs['tietkiem17']);
            $inputs['tietkiem18'] = chkDbl($inputs['tietkiem18']);
            $inputs['tietkiem19'] = chkDbl($inputs['tietkiem19']);
            $inputs['tietkiem19gd'] = chkDbl($inputs['tietkiem19gd']);
            $inputs['tietkiem19dt'] = chkDbl($inputs['tietkiem19dt']);
            $inputs['tietkiem19yte'] = chkDbl($inputs['tietkiem19yte']);
            $inputs['tietkiem19khac'] = chkDbl($inputs['tietkiem19khac']);
            $inputs['tietkiem19qlnn'] = chkDbl($inputs['tietkiem19qlnn']);
            $inputs['tietkiem19xa'] = chkDbl($inputs['tietkiem19xa']);

            $inputs['dbhocphi'] = chkDbl($inputs['dbhocphi']);
            $inputs['dbhocphigd'] = chkDbl($inputs['dbhocphigd']);
            $inputs['dbhocphidt'] = chkDbl($inputs['dbhocphidt']);
            $inputs['dbhocphiyte'] = chkDbl($inputs['dbhocphiyte']);
            $inputs['dbhocphikhac'] = chkDbl($inputs['dbhocphikhac']);
            $inputs['dbhocphiqlnn'] = chkDbl($inputs['dbhocphiqlnn']);
            $inputs['dbhocphixa'] = chkDbl($inputs['dbhocphixa']);

            $inputs['dbvienphi'] = chkDbl($inputs['dbvienphi']);
            $inputs['dbvienphigd'] = chkDbl($inputs['dbvienphigd']);
            $inputs['dbvienphidt'] = chkDbl($inputs['dbvienphidt']);
            $inputs['dbvienphiyte'] = chkDbl($inputs['dbvienphiyte']);
            $inputs['dbvienphikhac'] = chkDbl($inputs['dbvienphikhac']);
            $inputs['dbvienphiqlnn'] = chkDbl($inputs['dbvienphiqlnn']);
            $inputs['dbvienphixa'] = chkDbl($inputs['dbvienphixa']);

            $inputs['dbkhac'] = chkDbl($inputs['dbkhac']);
            $inputs['dbkhacgd'] = chkDbl($inputs['dbkhacgd']);
            $inputs['dbkhacdt'] = chkDbl($inputs['dbkhacdt']);
            $inputs['dbkhacyte'] = chkDbl($inputs['dbkhacyte']);
            $inputs['dbkhackhac'] = chkDbl($inputs['dbkhackhac']);
            $inputs['dbkhacqlnn'] = chkDbl($inputs['dbkhacqlnn']);
            $inputs['dbkhacxa'] = chkDbl($inputs['dbkhacxa']);
            //

            $inputs['kdbhocphi'] = chkDbl($inputs['kdbhocphi']);
            $inputs['kdbhocphigd'] = chkDbl($inputs['kdbhocphigd']);
            $inputs['kdbhocphidt'] = chkDbl($inputs['kdbhocphidt']);
            $inputs['kdbhocphiyte'] = chkDbl($inputs['kdbhocphiyte']);
            $inputs['kdbhocphikhac'] = chkDbl($inputs['kdbhocphikhac']);
            $inputs['kdbhocphiqlnn'] = chkDbl($inputs['kdbhocphiqlnn']);
            $inputs['kdbhocphixa'] = chkDbl($inputs['kdbhocphixa']);

            $inputs['kdbvienphi'] = chkDbl($inputs['kdbvienphi']);
            $inputs['kdbvienphigd'] = chkDbl($inputs['kdbvienphigd']);
            $inputs['kdbvienphidt'] = chkDbl($inputs['kdbvienphidt']);
            $inputs['kdbvienphiyte'] = chkDbl($inputs['kdbvienphiyte']);
            $inputs['kdbvienphikhac'] = chkDbl($inputs['kdbvienphikhac']);
            $inputs['kdbvienphiqlnn'] = chkDbl($inputs['kdbvienphiqlnn']);
            $inputs['kdbvienphixa'] = chkDbl($inputs['kdbvienphixa']);

            $inputs['kdbkhac'] = chkDbl($inputs['kdbkhac']);
            $inputs['kdbkhacgd'] = chkDbl($inputs['kdbkhacgd']);
            $inputs['kdbkhacdt'] = chkDbl($inputs['kdbkhacdt']);
            $inputs['kdbkhacyte'] = chkDbl($inputs['kdbkhacyte']);
            $inputs['kdbkhackhac'] = chkDbl($inputs['kdbkhackhac']);
            $inputs['kdbkhacqlnn'] = chkDbl($inputs['kdbkhacqlnn']);
            $inputs['kdbkhacxa'] = chkDbl($inputs['kdbkhacxa']);

            $inputs['tietkiemchi'] = chkDbl($inputs['tietkiemchi']);
            $inputs['tietkiemchigd'] = chkDbl($inputs['tietkiemchigd']);
            $inputs['tietkiemchidt'] = chkDbl($inputs['tietkiemchidt']);
            $inputs['tietkiemchiyte'] = chkDbl($inputs['tietkiemchiyte']);
            $inputs['tietkiemchikhac'] = chkDbl($inputs['tietkiemchikhac']);
            $inputs['tietkiemchiqlnn'] = chkDbl($inputs['tietkiemchiqlnn']);
            $inputs['tietkiemchixa'] = chkDbl($inputs['tietkiemchixa']);

            $inputs['bosung'] = chkDbl($inputs['bosung']);
            $inputs['caicach'] = chkDbl($inputs['caicach']);
            $inputs['madv'] = session('admin')->madv;
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['macqcq'] = session('admin')->macqcq;
            $inputs['trangthai'] = "CHUAGUI";

            $a_linhvuc = array('gd'=>'GD','dt'=>'DT','yte'=>'YTE','khac'=>'KHAC','qlnn'=>'QLNN','xa'=>'KVXP');
            foreach($a_linhvuc as $lv=>$key){
                $modelct = new nguonkinhphi_huyen_baocao_chitiet();
                $modelct->masodv = $inputs['masodv'];
                $modelct->sohieu = $inputs['sohieu'];
                $modelct->namns = $inputs['namns'];
                $modelct->linhvuchoatdong = $a_linhvuc[$lv];
                $modelct->tietkiem = $inputs['tietkiem19'.$lv];
                $modelct->dbhocphi = $inputs['dbhocphi'.$lv];
                $modelct->dbvienphi = $inputs['dbvienphi'.$lv];
                $modelct->dbkhac = $inputs['dbkhac'.$lv];
                $modelct->kdbhocphi = $inputs['kdbhocphi'.$lv];
                $modelct->kdbvienphi = $inputs['kdbvienphi'.$lv];
                $modelct->kdbkhac = $inputs['kdbkhac'.$lv];
                $modelct->tietkiemchi = $inputs['tietkiemchi'.$lv];
                $modelct->madv = $inputs['madv'];
                $modelct->madvbc = $inputs['madvbc'];
                $modelct->macqcq = $inputs['macqcq'];
                $modelct->save();
            }
            nguonkinhphi_huyen_baocao::create($inputs);
            return redirect('/nguon_kinh_phi/huyen/danh_sach');
        }else
            return view('errors.notlogin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($masodv)
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi_huyen_baocao::where('masodv', $masodv)->first();

            return view('manage.nguonkinhphihuyen.show')
                ->with('furl', '/nguon_kinh_phi/huyen/')
                ->with('model', $model)
                ->with('pageTitle', 'Chi tiết nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($masodv)
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi_huyen_baocao::where('masodv', $masodv)->first();

            return view('manage.nguonkinhphihuyen.edit')
                ->with('furl', '/nguon_kinh_phi/huyen/')
                ->with('model', $model)
                ->with('pageTitle', 'Chi tiết nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi_huyen_baocao::where('masodv',$inputs['masodv'])->first();

            $inputs['thuchien'] = chkDbl($inputs['thuchien']);
            $inputs['dutoan19'] = chkDbl($inputs['dutoan19']);
            $inputs['dutoan18'] = chkDbl($inputs['dutoan18']);
            $inputs['tietkiem17'] = chkDbl($inputs['tietkiem17']);
            $inputs['tietkiem18'] = chkDbl($inputs['tietkiem18']);
            $inputs['tietkiem19'] = chkDbl($inputs['tietkiem19']);
            $inputs['tietkiem19gd'] = chkDbl($inputs['tietkiem19gd']);
            $inputs['tietkiem19dt'] = chkDbl($inputs['tietkiem19dt']);
            $inputs['tietkiem19yte'] = chkDbl($inputs['tietkiem19yte']);
            $inputs['tietkiem19khac'] = chkDbl($inputs['tietkiem19khac']);
            $inputs['tietkiem19qlnn'] = chkDbl($inputs['tietkiem19qlnn']);
            $inputs['tietkiem19xa'] = chkDbl($inputs['tietkiem19xa']);

            $inputs['dbhocphi'] = chkDbl($inputs['dbhocphi']);
            $inputs['dbhocphigd'] = chkDbl($inputs['dbhocphigd']);
            $inputs['dbhocphidt'] = chkDbl($inputs['dbhocphidt']);
            $inputs['dbhocphiyte'] = chkDbl($inputs['dbhocphiyte']);
            $inputs['dbhocphikhac'] = chkDbl($inputs['dbhocphikhac']);
            $inputs['dbhocphiqlnn'] = chkDbl($inputs['dbhocphiqlnn']);
            $inputs['dbhocphixa'] = chkDbl($inputs['dbhocphixa']);

            $inputs['dbvienphi'] = chkDbl($inputs['dbvienphi']);
            $inputs['dbvienphigd'] = chkDbl($inputs['dbvienphigd']);
            $inputs['dbvienphidt'] = chkDbl($inputs['dbvienphidt']);
            $inputs['dbvienphiyte'] = chkDbl($inputs['dbvienphiyte']);
            $inputs['dbvienphikhac'] = chkDbl($inputs['dbvienphikhac']);
            $inputs['dbvienphiqlnn'] = chkDbl($inputs['dbvienphiqlnn']);
            $inputs['dbvienphixa'] = chkDbl($inputs['dbvienphixa']);

            $inputs['dbkhac'] = chkDbl($inputs['dbkhac']);
            $inputs['dbkhacgd'] = chkDbl($inputs['dbkhacgd']);
            $inputs['dbkhacdt'] = chkDbl($inputs['dbkhacdt']);
            $inputs['dbkhacyte'] = chkDbl($inputs['dbkhacyte']);
            $inputs['dbkhackhac'] = chkDbl($inputs['dbkhackhac']);
            $inputs['dbkhacqlnn'] = chkDbl($inputs['dbkhacqlnn']);
            $inputs['dbkhacxa'] = chkDbl($inputs['dbkhacxa']);
            //

            $inputs['kdbhocphi'] = chkDbl($inputs['kdbhocphi']);
            $inputs['kdbhocphigd'] = chkDbl($inputs['kdbhocphigd']);
            $inputs['kdbhocphidt'] = chkDbl($inputs['kdbhocphidt']);
            $inputs['kdbhocphiyte'] = chkDbl($inputs['kdbhocphiyte']);
            $inputs['kdbhocphikhac'] = chkDbl($inputs['kdbhocphikhac']);
            $inputs['kdbhocphiqlnn'] = chkDbl($inputs['kdbhocphiqlnn']);
            $inputs['kdbhocphixa'] = chkDbl($inputs['kdbhocphixa']);

            $inputs['kdbvienphi'] = chkDbl($inputs['kdbvienphi']);
            $inputs['kdbvienphigd'] = chkDbl($inputs['kdbvienphigd']);
            $inputs['kdbvienphidt'] = chkDbl($inputs['kdbvienphidt']);
            $inputs['kdbvienphiyte'] = chkDbl($inputs['kdbvienphiyte']);
            $inputs['kdbvienphikhac'] = chkDbl($inputs['kdbvienphikhac']);
            $inputs['kdbvienphiqlnn'] = chkDbl($inputs['kdbvienphiqlnn']);
            $inputs['kdbvienphixa'] = chkDbl($inputs['kdbvienphixa']);

            $inputs['kdbkhac'] = chkDbl($inputs['kdbkhac']);
            $inputs['kdbkhacgd'] = chkDbl($inputs['kdbkhacgd']);
            $inputs['kdbkhacdt'] = chkDbl($inputs['kdbkhacdt']);
            $inputs['kdbkhacyte'] = chkDbl($inputs['kdbkhacyte']);
            $inputs['kdbkhackhac'] = chkDbl($inputs['kdbkhackhac']);
            $inputs['kdbkhacqlnn'] = chkDbl($inputs['kdbkhacqlnn']);
            $inputs['kdbkhacxa'] = chkDbl($inputs['kdbkhacxa']);

            $inputs['tietkiemchi'] = chkDbl($inputs['tietkiemchi']);
            $inputs['tietkiemchigd'] = chkDbl($inputs['tietkiemchigd']);
            $inputs['tietkiemchidt'] = chkDbl($inputs['tietkiemchidt']);
            $inputs['tietkiemchiyte'] = chkDbl($inputs['tietkiemchiyte']);
            $inputs['tietkiemchikhac'] = chkDbl($inputs['tietkiemchikhac']);
            $inputs['tietkiemchiqlnn'] = chkDbl($inputs['tietkiemchiqlnn']);
            $inputs['tietkiemchixa'] = chkDbl($inputs['tietkiemchixa']);

            $inputs['bosung'] = chkDbl($inputs['bosung']);
            $inputs['caicach'] = chkDbl($inputs['caicach']);

            //dd($inputs);
            $model->update($inputs);

            return redirect('/nguon_kinh_phi/huyen/danh_sach');
        }else
            return view('errors.notlogin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Session::has('admin')) {
            $model = nguonkinhphi_huyen_baocao::find($id);
            nguonkinhphi_huyen_baocao_chitiet::where('masodv',$model->masodv)->delete();
            $model->delete();
            return redirect('/nguon_kinh_phi/huyen/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function getinfor_thongtu(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = dmthongtuquyetdinh::where('sohieu',$inputs['sohieu'])->first();
        die($model);
    }

    function senddata(Request $requests){
        if (Session::has('admin')) {
            $inputs = $requests->all();
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $m_nkp = nguonkinhphi_huyen_baocao::where('masodv', $inputs['masodv'])->first();
            $model = nguonkinhphi_huyen_baocao::where('sohieu', $m_nkp->sohieu)->where('madv', session('admin')->madv)->get();
            foreach($model as $nguon) {
                $nguon->trangthai = 'DAGUI';
                $nguon->macqcq = session('admin')->macqcq;
                $nguon->nguoiguidv = session('admin')->name;
                $nguon->ngayguidv = Carbon::now()->toDateTimeString();
                $nguon->save();
            }
            return redirect('/nguon_kinh_phi/huyen/danh_sach');
        } else
            return view('errors.notlogin');
    }
}
