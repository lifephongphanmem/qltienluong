<?php

namespace App\Http\Controllers;

use App\dmchucvucq;
use App\dmchucvud;
use App\dmdantoc;
use App\dmdonvi;
use App\dmkhoipb;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap;
use App\hosocanbo;
use App\hosochucvu;
use App\hosocongtac;
use App\hosodaotao;
use App\hosokhenthuong;
use App\hosokyluat;
use App\hosollvt;
use App\hosoluong;
use App\hosoquanhegd;
use App\hosotinhtrangct;
use App\ngachbac;
use App\ngachluong;
use App\nhomngachluong;
use App\phucapdanghuong;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class hosocanboController extends Controller
{
    function index(){
        if (Session::has('admin')) {

            //$m_hs=hosocanbo::where('madv',session('admin')->maxa)->get();
            $m_hs=hosocanbo::where('madv',session('admin')->madv)
                ->get();

            $dmphongban=dmphongban::all('mapb','tenpb')->toArray();
            $dmchucvud=dmchucvud::all('tencv', 'macvd')->toArray();
            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach($m_hs as $hs){
                $hs->tenpb=getInfoPhongBan($hs,$dmphongban);
                $hs->tencvd=getInfoChucVuD($hs,$dmchucvud);
                $hs->tencvcq=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            //dd($m_hs);

            return view('manage.hosocanbo.index')
                ->with('model',$m_hs)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    function index_thoicongtac(){
        if (Session::has('admin')) {

            //$m_hs=hosocanbo::where('madv',session('admin')->maxa)->get();
            $m_hs=hosocanbo::join('dmchucvucq', 'hosocanbo.macvcq', '=', 'dmchucvucq.macvcq')
                ->select('hosocanbo.*', 'dmchucvucq.sapxep')
                ->where('hosocanbo.theodoi','0')
                ->where('hosocanbo.madv',session('admin')->madv)
                ->orderby('dmchucvucq.sapxep')
                ->get();

            $dmphongban=dmphongban::all('mapb','tenpb')->toArray();
            $dmchucvud=dmchucvud::all('tencv', 'macvd')->toArray();
            $dmchucvucq=dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach($m_hs as $hs){
                $hs->tenpb=getInfoPhongBan($hs,$dmphongban);
                $hs->tencvd=getInfoChucVuD($hs,$dmchucvud);
                $hs->tencvcq=getInfoChucVuCQ($hs,$dmchucvucq);
            }
            //dd($m_hs);

            return view('manage.hosocanbo.index_thoicongtac')
                ->with('model',$m_hs)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách cán bộ đã thôi công tác');
        } else
            return view('errors.notlogin');
    }

    function create(){
        if (Session::has('admin')) {
            //$makhoipb=getMaKhoiPB(session('admin')->madv);
            $model_nhomct=dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct=dmphanloaict::select('tenct','macongtac')->get();
            $model_dt=array_column(dmdantoc::select(DB::raw('dantoc as maso'),'dantoc')->get()->toarray(),'dantoc','maso');
            //$m_pb= dmphongban::where('madv',session('admin')->madv)->get();
            $m_pb = dmphongban::all();
            $m_cvcq = dmchucvucq::where('maphanloai',session('admin')->level)->get();
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc=array_column(dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
            $m_cvd= dmchucvud::all();
            $m_plnb=nhomngachluong::select('manhom','tennhom')->distinct()->get();
            $m_pln=ngachluong::select('tenngachluong','manhom','msngbac')->distinct()->get();

            $macanbo=session('admin')->madv . '_' . getdate()[0];
            $m_pc=dmphucap::all('mapc','tenpc','hesopc')->toArray();

            return view('manage.hosocanbo.create')
                ->with('type','create')
                ->with('macanbo',$macanbo)
                //danh mục
                ->with('m_linhvuc',$m_linhvuc)
                ->with('model_dt',$model_dt)
                ->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                ->with('m_cvd',$m_cvd)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('m_plnb',$m_plnb)
                ->with('m_pln',$m_pln)
                ->with('m_pc',$m_pc)
                ->with('pageTitle','Tạo hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function store(Request $request){
        if (Session::has('admin')) {
            $insert = $request->all();
            $madv=session('admin')->madv;
            $macanbo=$insert['macanbo'];

            //Xử lý file ảnh
            //dd($request->file('anh'));
            $img=$request->file('anh');
            $filename='';
            if(isset($img)) {
                $filename = $macanbo . '_' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
            }

            $insert['anh']=($filename==''?'':'/data/uploads/anh/'. $filename);
            $insert['madv'] = $madv;

            $insert['ngaysinh']=getDateTime($insert['ngaysinh']);
            $insert['ngaycap']=getDateTime($insert['ngaycap']);
            $insert['ngaytu']=getDateTime($insert['ngaytu']);
            $insert['ngayden']=getDateTime($insert['ngayden']);
            $insert['ngayvd']=getDateTime($insert['ngayvd']);
            $insert['ngayvdct']=getDateTime($insert['ngayvdct']);
            $insert['ngayvao']=getDateTime($insert['ngayvao']);
            $insert['ngaybc']=getDateTime($insert['ngaybc']);
            $insert['macvd']=($insert['macvd']==''?NULL:$insert['macvd']);

            $insert['heso'] = chkDbl($insert['pccv']);
            $insert['vuotkhung'] = chkDbl($insert['pccv']);
            $insert['pccv'] = chkDbl($insert['pccv']);
            $insert['pckn'] = chkDbl($insert['pckn']);
            $insert['pckv'] = chkDbl($insert['pckv']);
            $insert['pccovu'] = chkDbl($insert['pccovu']);
            $insert['pctn'] = chkDbl($insert['pctn']);
            $insert['pctnn'] = chkDbl($insert['pctnn']);
            $insert['pcvk'] = chkDbl($insert['pcvk']);
            $insert['pcdbqh'] = chkDbl($insert['pcdbqh']);
            $insert['pcth'] = chkDbl($insert['pcth']);
            $insert['pcudn'] = chkDbl($insert['pcudn']);
            $insert['pcdbn'] = chkDbl($insert['pcdbn']);
            $insert['pcld'] = chkDbl($insert['pcld']);
            $insert['pcdh'] = chkDbl($insert['pcdh']);
            $insert['pck'] = chkDbl($insert['pck']);
            $insert['pctnvk'] = chkDbl($insert['pctnvk']);
            $insert['pcbdhdcu'] = chkDbl($insert['pcbdhdcu']);
            //dd($insert);
            hosocanbo::create($insert);

            return redirect('nghiep_vu/ho_so/danh_sach');
        }else
            return view('errors.notlogin');
    }

    function show($id){
        if (Session::has('admin')) {
            //$makhoipb=getMaKhoiPB(session('admin')->madv);
            $model = hosocanbo::find($id);
            //$m_hosoct = hosotinhtrangct::where('macanbo',$model->macanbo)->where('hientai','1')->first();

            $model_nhomct=dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct=dmphanloaict::select('tenct','macongtac')->get();
            $model_dt=array_column(dmdantoc::select(DB::raw('dantoc as maso'),'dantoc')->get()->toarray(),'dantoc','maso');
            //$m_pb= dmphongban::where('madv',session('admin')->madv)->get();
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc=array_column(dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
            $m_pb = dmphongban::all();
            $m_cvcq = dmchucvucq::where('maphanloai',session('admin')->level)->get();
            $m_cvd= dmchucvud::all();
            $m_plnb=nhomngachluong::select('manhom','tennhom')->distinct()->get();
            $m_pln=ngachluong::select('tenngachluong','manhom','msngbac')->distinct()->get();


            return view('manage.hosocanbo.edit')
                ->with('model',$model)
                ->with('type','edit')
                ->with('model_dt',$model_dt)
                ->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                ->with('m_cvd',$m_cvd)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('m_plnb',$m_plnb)
                ->with('m_linhvuc',$m_linhvuc)
                ->with('m_pln',$m_pln)
                ->with('pageTitle','Sửa thông tin hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    public function update(Request $request, $id)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $model = hosocanbo::find($id);
            //Xử lý file ảnh
            $img=$request->file('anh');
            $filename='';
            if(isset($img)) {
                //Xóa ảnh cũ
                if(File::exists($model->anh))
                File::Delete($model->anh);

                $filename = $model->macanbo . '.' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
            }

            $insert['anh']=($filename==''?'':'/data/uploads/anh/'. $filename);
            $insert['ngaysinh']=getDateTime($insert['ngaysinh']);
            $insert['ngaycap']=getDateTime($insert['ngaycap']);
            $insert['ngaytu']=getDateTime($insert['ngaytu']);
            $insert['ngayden']=getDateTime($insert['ngayden']);
            $insert['ngayvd']=getDateTime($insert['ngayvd']);
            $insert['ngayvdct']=getDateTime($insert['ngayvdct']);
            $insert['ngayvao']=getDateTime($insert['ngayvao']);
            $insert['ngaybc']=getDateTime($insert['ngaybc']);
            $insert['macvd']=($insert['macvd']==''?NULL:$insert['macvd']);

            $insert['heso'] = chkDbl($insert['pccv']);
            $insert['vuotkhung'] = chkDbl($insert['vuotkhung']);
            $insert['pccv'] = chkDbl($insert['pccv']);
            $insert['pckn'] = chkDbl($insert['pckn']);
            $insert['pckv'] = chkDbl($insert['pckv']);
            $insert['pccovu'] = chkDbl($insert['pccovu']);
            $insert['pctn'] = chkDbl($insert['pctn']);
            $insert['pctnn'] = chkDbl($insert['pctnn']);
            $insert['pcvk'] = chkDbl($insert['pcvk']);
            $insert['pcdbqh'] = chkDbl($insert['pcdbqh']);
            $insert['pcth'] = chkDbl($insert['pcth']);
            $insert['pcudn'] = chkDbl($insert['pcudn']);
            $insert['pcdbn'] = chkDbl($insert['pcdbn']);
            $insert['pcld'] = chkDbl($insert['pcld']);
            $insert['pcdh'] = chkDbl($insert['pcdh']);
            $insert['pck'] = chkDbl($insert['pck']);
            $insert['pctnvk'] = chkDbl($insert['pctnvk']);
            $insert['pcbdhdcu'] = chkDbl($insert['pcbdhdcu']);

            $model->update($insert);

            return redirect('nghiep_vu/ho_so/danh_sach');
        }else
            return view('errors.notlogin');
    }

    //<editor-fold desc="Tra cứu">
    function search(){
        if (Session::has('admin')) {
            $m_pb=dmphongban::all('mapb','tenpb');
            $m_dt=dmdantoc::all('dantoc');
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq');

            return view('search.hosocanbo.index')
                ->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                ->with('m_dt',$m_dt)
                ->with('pageTitle','Tra cứu hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }

    function result(Request $request){
        if (Session::has('admin')) {
            $_sql="select hosocanbo.id,hosocanbo.macanbo,hosocanbo.tencanbo,hosocanbo.anh,hosocanbo.macvcq,hosocanbo.mapb,hosocanbo.gioitinh,dmchucvucq.sapxep,hosocanbo.ngaysinh
                   from hosocanbo, dmchucvucq
                   Where hosocanbo.macvcq=dmchucvucq.macvcq and
                      hosocanbo.theodoi ='1'";

            $inputs=$request->all();
            $s_dk = getConditions($inputs, array('_token'), 'hosocanbo');
            if($s_dk!='') {$_sql .=' and '.$s_dk;}

            $model=DB::select(DB::raw($_sql));

            $m_pb=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq')->toArray();

            foreach($model as $hs){
                $hs->tenpb=getInfoPhongBan($hs,$m_pb);
                $hs->tencvcq=getInfoChucVuCQ($hs,$m_cvcq);
            }

            return view('search.hosocanbo.result')
                ->with('model',$model)
                ->with('pageTitle','Kết quả tra cứu hồ sơ cán bộ');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>

    function syll($id){
        if (Session::has('admin')) {
            $model=hosocanbo::find($id);
            $macanbo=$model->macanbo;
            $m_pb=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            $m_nb=ngachbac::select('tennb', 'msngbac')->distinct()->get()->toArray();

            $model->tenpb=getInfoPhongBan($model,$m_pb);
            $model->tencvcq=getInfoChucVuCQ($model,$m_cvcq);
            $model->tenviethoa=Str::upper($model->tencanbo);
            $model->tennb=getInfoTenNB($model,$m_nb);

            $m_llvt=hosollvt::where('macanbo',$macanbo)->first();

            $m_daotao=hosodaotao::where('macanbo',$macanbo)->orderby('ngaytu')->get();
            $m_congtac=hosocongtac::where('macanbo',$macanbo)->orderby('ngaytu')->get();
            $m_qhbt=hosoquanhegd::join('dmquanhegd', 'hosoquanhegd.quanhe', '=', 'dmquanhegd.quanhe')
                ->where('hosoquanhegd.macanbo',$macanbo)
                ->where('hosoquanhegd.phanloai','Bản thân')
                ->orderby('dmquanhegd.sapxep')->get();
            $m_qhvc=hosoquanhegd::join('dmquanhegd', 'hosoquanhegd.quanhe', '=', 'dmquanhegd.quanhe')
                ->where('hosoquanhegd.macanbo',$macanbo)
                ->where('hosoquanhegd.phanloai','Vợ chồng')
                ->orderby('dmquanhegd.sapxep')->get();

            $luong=hosoluong::select('ngaytu',DB::raw('CONCAT(msngbac, "/", bac) AS ngachbac'),'heso')->where('macanbo',$macanbo)->orderby('ngaytu')->get()->toarray();

            $thang=array_column($luong,'ngaytu');
            $msngbac=array_column($luong,'ngachbac');
            $heso=array_column($luong,'heso');

            $m_luong=array();
            $m_luong[]=$thang;
            $m_luong[]=$msngbac;
            $m_luong[]=$heso;

            $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
            //dd($model);
            return view('reports.QD02.soyeulylich')
                ->with('model',$model)
                ->with('m_llvt',$m_llvt)
                ->with('m_daotao',$m_daotao)
                ->with('m_congtac',$m_congtac)
                ->with('m_qhbt',$m_qhbt)
                ->with('m_qhvc',$m_qhvc)
                ->with('m_luong',$m_luong)
                ->with('m_donvi',$m_donvi)
                ->with('pageTitle','Sơ yếu lý lịch');
        } else
            return view('errors.notlogin');
    }

    function tomtatts($id){
        if (Session::has('admin')) {
            $model=hosocanbo::find($id);
            $macanbo=$model->macanbo;
            $m_pb=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            $m_nb=ngachbac::select('tennb', 'msngbac')->distinct()->get()->toArray();

            $model->tenpb=getInfoPhongBan($model,$m_pb);
            $model->tencvcq=getInfoChucVuCQ($model,$m_cvcq);
            $model->tenviethoa=Str::upper($model->tencanbo);
            $model->tennb=getInfoTenNB($model,$m_nb);

            $m_llvt=hosollvt::where('macanbo',$macanbo)->first();
            $m_congtac=hosocongtac::where('macanbo',$macanbo)->orderby('ngaytu')->get();

            $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
            //dd($m_congtac);
            return view('reports.QD02.tomtattieusu')
                ->with('model',$model)
                ->with('m_llvt',$m_llvt)
                ->with('m_congtac',$m_congtac)
                ->with('m_donvi',$m_donvi)
                ->with('pageTitle','Tóm tắt tiểu sử');
        } else
            return view('errors.notlogin');
    }

    function bsll(Request $request){
        if (Session::has('admin')) {
            $inputs=$request->all();
            $ngaytu=$inputs['ngaytu'];
            $ngayden=$inputs['ngayden'];

            $model=hosocanbo::find($inputs['idct']);
            $macanbo=$model->macanbo;
            $m_pb=dmphongban::all('mapb','tenpb')->toArray();
            $m_cvcq=dmchucvucq::all('tencv', 'macvcq')->toArray();
            $m_nb=ngachbac::select('tennb', 'msngbac')->distinct()->get()->toArray();

            $model->tenpb=getInfoPhongBan($model,$m_pb);
            $model->tencvcq=getInfoChucVuCQ($model,$m_cvcq);
            $model->tenviethoa=Str::upper($model->tencanbo);
            $model->tennb=getInfoTenNB($model,$m_nb);

            $m_cv=hosochucvu::where('macanbo',$macanbo)->where('ngayden','>=',$ngaytu)->get();
            foreach($m_cv as $cv){
                $cv->tencvcq=getInfoChucVuCQ($cv,$m_cvcq);
            }
            //Chỉ lấy bằng cấp nào đã học xong trong khoảng thời gian kết xuất
            $m_daotao=hosodaotao::where('macanbo',$macanbo)->wherebetween('ngayden',array($ngaytu,$ngayden))->orderby('ngaytu')->get();
            $m_kt=hosokhenthuong::where('macanbo',$macanbo)->wherebetween('ngaythang',array($ngaytu,$ngayden))->orderby('ngaythang')->get();
            $m_kl=hosokyluat::where('macanbo',$macanbo)->wherebetween('ngaythang',array($ngaytu,$ngayden))->orderby('ngaythang')->get();

            $m_donvi=dmdonvi::where('madv',session('admin')->madv)->first();
            $m_donvi->ngaytu=$ngaytu;
            $m_donvi->ngayden=$ngayden;
            //dd($model);
            return view('reports.QD02.bosunglylich')
                ->with('model',$model)
                ->with('m_cv',$m_cv)
                ->with('m_daotao',$m_daotao)
                ->with('m_kt',$m_kt)
                ->with('m_kl',$m_kl)
                ->with('m_donvi',$m_donvi)
                ->with('pageTitle','Phiếu bổ sung lý lịch');
        } else
            return view('errors.notlogin');
    }

    function destroy($id){
        if (Session::has('admin')) {
            $model = hosocanbo::find($id);
            $model->delete();
            return redirect('nghiep_vu/ho_so/danh_sach');
        } else
            return view('errors.notlogin');
    }

    function phucap(Request $request){
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
        $model=phucapdanghuong::where('macanbo',$inputs['macanbo'])->where('mapc',$inputs['mapc'])->first();

        if(count($model)>0){
            $model->ngaytu = $inputs['ngaytu'];
            $model->ngayden = $inputs['ngayden'];
            $model->hesopc = $inputs['hesopc'];
            $model->baohiem =$inputs['baohiem'];
            $model->save();
        }else {
            $model = new phucapdanghuong();
            $model->macanbo = $inputs['macanbo'];
            $model->mapc = $inputs['mapc'];
            $model->ngaytu = $inputs['ngaytu'];
            $model->ngayden = $inputs['ngayden'];
            $model->hesopc = $inputs['hesopc'];
            $model->baohiem =$inputs['baohiem'];
            $model->madv = session('admin')->madv;
            $model->save();
        }
        $model = phucapdanghuong::join('dmphucap','phucapdanghuong.mapc','dmphucap.mapc')
            ->select('phucapdanghuong.*','dmphucap.tenpc')
            ->where('phucapdanghuong.macanbo',$inputs['macanbo'])->get();
        $result = $this->return_html($result, $model);

        die(json_encode($result));
    }

    function detroys_phucap(Request $request){
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
        $model = phucapdanghuong::findOrFail($inputs['id']);
        $model->delete();
        $model = phucapdanghuong::where('macanbo',$inputs['macanbo'])->get();
        $result = $this->return_html($result, $model);

        die(json_encode($result));
    }

    function get_phucap(Request $request){
        if(!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = phucapdanghuong::find($inputs['id']);
        die($model);
    }

    public function return_html($result, $model)
    {
        $result['message'] = '<div class="col-md-12" id="thongtinphucap">';
        $result['message'] .= '<table class="table table-striped table-bordered table-hover" id="sample_3">';
        $result['message'] .= '<thead>';
        $result['message'] .= '<tr>';
        $result['message'] .= '<th width="5%" style="text-align: center">STT</th>';
        $result['message'] .= '<th class="text-center">Từ ngày</th>';
        $result['message'] .= '<th class="text-center">Đến ngày</th>';
        $result['message'] .= '<th class="text-center">Tên phụ cấp</th>';
        $result['message'] .= '<th class="text-center">Hệ số</th>';
        $result['message'] .= '<th class="text-center">Thao tác</th>';
        $result['message'] .= '</tr>';
        $result['message'] .= '</thead>';

        $stt=1;
        $result['message'] .= '<tbody>';
        if (count($model) > 0) {
            foreach ($model as $key => $ct) {
                $result['message'] .= '<tr>';
                $result['message'] .= '<td style="text-align: center">' . $stt++ . '</td>';
                $result['message'] .= '<td style="text-align: right">' . getDayVn($ct->ngaytu) . '</td>';
                $result['message'] .= '<td style="text-align: right">' . getDayVn($ct->ngayden) . '</td>';
                $result['message'] .= '<td style="text-align: right">' . $ct->tenpc . '</td>';
                $result['message'] .= '<td style="text-align: right">' . $ct->hesopc . '</td>';
                $result['message'] .= '<td>
                                    <button type="button" onclick="edit_phucap('.$ct->id.')" class="btn btn-info btn-xs mbs">
                                        <i class="fa fa-edit"></i>&nbsp;Chỉnh sửa</button>
                                    <button type="button" onclick="del_phucap('.$ct->id.')" class="btn btn-danger btn-xs mbs" data-target="#modal-delete" data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
                </td>';
                $result['message'] .= '</tr>';
            }
            $result['message'] .= '</tbody>';
            $result['message'] .= '</table>';
            $result['message'] .= '</div>';
            $result['message'] .= '</div>';
            $result['status'] = 'success';
            return $result;
        }
        return $result;
    }
}
