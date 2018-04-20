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
use App\hosocanbo;
use App\hosochucvu;
use App\hosoluong;
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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class hosocanboController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            //$m_hs=hosocanbo::where('madv',session('admin')->maxa)->get();
            $m_hs=hosocanbo::where('madv',session('admin')->madv)->get();

            $dmphongban=dmphongban::select('mapb','tenpb')->where('madv',session('admin')->madv)->get();
            $dmchucvud=dmchucvud::select('tencv', 'macvd')->get();
            $dmchucvucq=dmchucvucq::select('tencv', 'macvcq','sapxep')->get();
            $dmcongtac=dmphanloaict::select('mact', 'tenct')->get();

            foreach($m_hs as $hs){
                $phongban = $dmphongban->where('mapb',$hs->mapb)->first();
                if(count($phongban)>0){
                    $hs->tenpb = $phongban->tenpb;
                }

                $chucvud = $dmchucvud->where('macvd',$hs->macvd)->first();
                if(count($chucvud)>0){
                    $hs->tencvd = $chucvud->tencv;
                }

                $chucvucq = $dmchucvucq->where('macvcq',$hs->macvcq)->first();
                if(count($chucvucq)>0){
                    $hs->tencvcq = $chucvucq->tencv;
                    $hs->sapxep = $chucvucq->sapxep;
                }

                $congtac = $dmcongtac->where('mact',$hs->mact)->first();
                if(count($congtac)>0){
                    $hs->tenct = $congtac->tenct;
                }

            }

            $model = $m_hs->sortBy('stt');
            return view('manage.hosocanbo.index')
                ->with('model',$model)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách cán bộ');
        } else
            return view('errors.notlogin');
    }

    //Bỏ
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
            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'),'dantoc')->get()->toarray(),'dantoc','maso');
            //$m_pb= dmphongban::where('madv',session('admin')->madv)->get();
            //$m_pb = dmphongban::where('madv',session('admin')->madv)->get();
            $m_cvcq = dmchucvucq::where('maphanloai',session('admin')->maphanloai)
                ->wherein('madv',['SA',session('admin')->madv])->get();
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc=array_column(dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
            //$m_cvd= dmchucvud::all();
            $m_plnb=nhomngachluong::select('manhom','tennhom','heso','namnb' )->distinct()->get();
            $m_pln=ngachluong::select('tenngachluong','manhom','msngbac','heso','namnb')
                ->distinct()->get();
            foreach($m_pln as $mangach){
                $nhomnb = $m_plnb->where('manhom',$mangach->manhom)->first();
                if(count($nhomnb) > 0 && $mangach->manhom != 'CBCT'){
                    $mangach->heso = $nhomnb->heso;
                    $mangach->namnb = $nhomnb->namnb;
                }
            }
            $macanbo=session('admin')->madv . '_' . getdate()[0];
            //$m_pc=dmphucap::all('mapc','tenpc','hesopc')->toArray();
            $a_donvi = dmdonvi::where('madv',session('admin')->madv)->first()->toarray();

            return view('manage.hosocanbo.create')
                ->with('type','create')
                ->with('macanbo',$macanbo)
                //danh mục
                ->with('m_linhvuc',$m_linhvuc)
                ->with('model_dt',$model_dt)
                //->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                //->with('m_cvd',$m_cvd)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('m_plnb',$m_plnb)
                ->with('m_pln',$m_pln)
                //->with('m_pc',$m_pc)
                ->with('a_phucap',getColPhuCap())
                ->with('a_donvi',$a_donvi)
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
            //$insert['ngaycap']=getDateTime($insert['ngaycap']);
            $insert['ngaytu']=getDateTime($insert['ngaytu']);
            $insert['ngayden']=getDateTime($insert['ngayden']);
            $insert['ngayvd']=getDateTime($insert['ngayvd']);
            $insert['ngayvdct']=getDateTime($insert['ngayvdct']);
            $insert['ngayvao']=getDateTime($insert['ngayvao']);
            $insert['ngaybc']=getDateTime($insert['ngaybc']);
            $insert['macvd']=($insert['macvd']==''?NULL:$insert['macvd']);
            //$insert['truylinhtungay']=getDateTime($insert['truylinhtungay']);
            //$insert['truylinhdenngay']=getDateTime($insert['truylinhdenngay']);

            $insert['heso'] = chkDbl($insert['heso']);
            //$insert['hesott'] = chkDbl($insert['hesott']);
            $insert['hesopc'] = chkDbl($insert['hesopc']);
            $insert['vuotkhung'] = chkDbl($insert['vuotkhung']);
            $insert['pccv'] = chkDbl($insert['pccv']);
            $insert['pckn'] = chkDbl($insert['pckn']);
            $insert['pckv'] = chkDbl($insert['pckv']);
            $insert['pccovu'] = chkDbl($insert['pccovu']);
            $insert['pctn'] = chkDbl($insert['pctn']);
            $insert['pctnn'] = chkDbl($insert['pctnn']);
            $insert['pcvk'] = chkDbl($insert['pcvk']); //lưu thông tin pc đảng ủy viên
            $insert['pcdbqh'] = chkDbl($insert['pcdbqh']);
            $insert['pcth'] = chkDbl($insert['pcth']);
            $insert['pcudn'] = chkDbl($insert['pcudn']);
            $insert['pcdbn'] = chkDbl($insert['pcdbn']);
            $insert['pcld'] = chkDbl($insert['pcld']);
            $insert['pcdh'] = chkDbl($insert['pcdh']);
            $insert['pck'] = chkDbl($insert['pck']);
            //$insert['pctnvk'] = chkDbl($insert['pctnvk']);
            $insert['pcbdhdcu'] = chkDbl($insert['pcbdhdcu']);
            $insert['pcdang'] = chkDbl($insert['pcdang']);
            $insert['pcthni'] = chkDbl($insert['pcthni']);
            $insert['pclt'] = chkDbl($insert['pclt']);
            $insert['pcdd'] = chkDbl($insert['pcdd']);
            $insert['pcct'] = chkDbl($insert['pcct']);
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

            $model_nhomct = dmphanloaicongtac::select('macongtac','tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct','macongtac','mact')->get();
            $model_dt = array_column(dmdantoc::select(DB::raw('dantoc as maso'),'dantoc')->get()->toarray(),'dantoc','maso');
            //$m_pb= dmphongban::where('madv',session('admin')->madv)->get();
            //khối phòng ban giờ là lĩnh vực hoạt động
            $m_linhvuc = array_column(dmkhoipb::all()->toArray(),'tenkhoipb','makhoipb');
            $m_pb = dmphongban::where('madv',session('admin')->madv)->get();
            $m_cvcq = dmchucvucq::where('maphanloai',session('admin')->maphanloai)->get();
            //$m_cvd = dmchucvud::all();
            $m_plnb = nhomngachluong::select('manhom','tennhom')->distinct()->get();
            $m_pln = ngachluong::select('tenngachluong','manhom','msngbac')->distinct()->get();
            $a_linhvuc = explode(',',$model->lvhd);
            $a_donvi = dmdonvi::where('madv',session('admin')->madv)->first()->toarray();

            return view('manage.hosocanbo.edit')
                ->with('model',$model)
                ->with('type','edit')
                ->with('model_dt',$model_dt)
                ->with('m_pb',$m_pb)
                ->with('m_cvcq',$m_cvcq)
                //->with('m_cvd',$m_cvd)
                ->with('model_nhomct',$model_nhomct)
                ->with('model_tenct',$model_tenct)
                ->with('m_linhvuc',$m_linhvuc)
                ->with('a_linhvuc',$a_linhvuc)
                ->with('m_plnb',$m_plnb)
                ->with('m_pln',$m_pln)
                ->with('a_phucap',getColPhuCap())
                ->with('a_donvi',$a_donvi)
                ->with('pageTitle','Sửa thông tin hồ sơ cán bộ');
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

    public function update(Request $request, $id)
    {
        if (Session::has('admin')) {
            $insert = $request->all();
            $model = hosocanbo::find($id);
            //Xử lý file ảnh
            $img=$request->file('anh');

            if(isset($img)) {
                //Xóa ảnh cũ
                if(File::exists($model->anh))
                File::Delete($model->anh);

                $filename = $model->macanbo . '.' . $img->getClientOriginalExtension();
                $img->move(public_path() . '/data/uploads/anh/', $filename);
                $insert['anh']='/data/uploads/anh/'. $filename;
            }

            $insert['ngaysinh']=getDateTime($insert['ngaysinh']);
            //$insert['ngaycap']=getDateTime($insert['ngaycap']);
            $insert['ngaytu']=getDateTime($insert['ngaytu']);
            $insert['ngayden']=getDateTime($insert['ngayden']);
            $insert['ngayvd']=getDateTime($insert['ngayvd']);
            $insert['ngayvdct']=getDateTime($insert['ngayvdct']);
            $insert['ngayvao']=getDateTime($insert['ngayvao']);
            $insert['ngaybc']=getDateTime($insert['ngaybc']);
            $insert['macvd']=($insert['macvd']==''?NULL:$insert['macvd']);
            //$insert['truylinhtungay']=getDateTime($insert['truylinhtungay']);
            //$insert['truylinhdenngay']=getDateTime($insert['truylinhdenngay']);

            $insert['heso'] = chkDbl($insert['heso']);
            $insert['hesopc'] = chkDbl($insert['hesopc']);
            //$insert['hesott'] = chkDbl($insert['hesott']);
            $insert['vuotkhung'] = chkDbl($insert['vuotkhung']);
            $insert['pccv'] = chkDbl($insert['pccv']);
            $insert['pckn'] = chkDbl($insert['pckn']);
            $insert['pckv'] = chkDbl($insert['pckv']);
            $insert['pccovu'] = chkDbl($insert['pccovu']);
            $insert['pctn'] = chkDbl($insert['pctn']);
            $insert['pctnn'] = chkDbl($insert['pctnn']);
            $insert['pcvk'] = chkDbl($insert['pcvk']);//lưu thông tin pc đảng ủy viên
            $insert['pcdbqh'] = chkDbl($insert['pcdbqh']);
            $insert['pcth'] = chkDbl($insert['pcth']);
            $insert['pcudn'] = chkDbl($insert['pcudn']);
            $insert['pcdbn'] = chkDbl($insert['pcdbn']);
            $insert['pcld'] = chkDbl($insert['pcld']);
            $insert['pcdh'] = chkDbl($insert['pcdh']);
            $insert['pck'] = chkDbl($insert['pck']);
            //$insert['pctnvk'] = chkDbl($insert['pctnvk']);
            $insert['pcbdhdcu'] = chkDbl($insert['pcbdhdcu']);
            $insert['pcdang'] = chkDbl($insert['pcdang']);
            $insert['pcthni'] = chkDbl($insert['pcthni']);
            $insert['pclt'] = chkDbl($insert['pclt']);
            $insert['pcdd'] = chkDbl($insert['pcdd']);
            $insert['pcct'] = chkDbl($insert['pcct']);

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

    //<editor-fold desc="Nhận danh sách cán bộ từ file Excel">
    public function infor_excel(){
        if(Session::has('admin')){
            $a_donvi = dmdonvi::where('madv',session('admin')->madv)->first()->toarray();
            $a_phucap = getColPhuCap_Excel();
            return view('manage.hosocanbo.excel.information')
                ->with('a_phucap',$a_phucap)
                ->with('a_donvi',$a_donvi)
                ->with('url','/nghiep_vu/ho_so/')
                ->with('pageTitle','Thông tin nhận danh sách cán bộ từ file Excel');

        }else
            return view('errors.notlogin');
    }

    function create_excel(Request $request){
        if(Session::has('admin')){
            $madv=session('admin')->madv;
            $inputs=$request->all();

            $ar_sheet = explode(',',$inputs['sheet']);
            if(count($ar_sheet) == 0){
                $ar_sheet[] = '0';
            }else{
                for($i=0;$i<count($ar_sheet);$i++){
                    $ar_sheet[$i] = $ar_sheet[$i] - 1;
                }
            }

            $bd=$inputs['tudong'];
            $sd=$inputs['sodong'];
            $filename = $madv . date('YmdHis');
            $request->file('fexcel')->move(public_path() . '/data/uploads/excels/', $filename . '.xls');
            $path = public_path() . '/data/uploads/excels/' . $filename . '.xls';

            $data = [];
            //dd ($ar_sheet);
            Excel::load($path, function($reader) use (&$data,$bd,$sd,$ar_sheet) {
                $obj = $reader->getExcel();
                $sheetCount = $obj->getSheetCount();

                foreach($ar_sheet as $sheetNumber){
                    //if($sheetNumber == 1){dd($sheetNumber);}
                    //dd($sheetNumber);
                    if($sheetNumber <= $sheetCount - 1){
                        $sheet = $obj->getSheet($sheetNumber);
                        //$sheet = $obj->getSheet(0);
                        $Row = $sheet->getHighestRow();
                        $Row = $sd+$bd > $Row ? $Row : ($sd+$bd);
                        $Col = $sheet->getHighestColumn();

                        for ($r = $bd; $r <= $Row; $r++)
                        {
                            //$rowData = $sheet->toArray(null,true,true,true) giữ lại tiêu đề A=>'val'
                            $rowData = $sheet->rangeToArray('A' . $r . ':' . $Col . $r, NULL, TRUE, FALSE);//'0'=>'val'
                            $data[] = $rowData[0];
                        }
                    }
                }
            });

            //chuyển từ A=>0; B=>1,...
            foreach($inputs as $key=>$val) {
                $ma=ord($val);
                if($ma>=65 && $ma<=90){
                    $inputs[$key]=$ma-65;
                }
                if($ma>=97 && $ma<=122){
                    $inputs[$key]=$ma-97;
                }
            }

            //nhận dữ liệu vào bảng hồ sơ cán bộ
            $model_donvi = dmdonvi::where('madv',$madv)->first();
            $model_msngbac = ngachluong::all();
            $model_nhomngbac = nhomngachluong::all();
            $model_chucvu = dmchucvucq::all();

            $a_col = array(
                    '0'=>'heso','1'=>'vuotkhung'
                );
            foreach(getColPhuCap() as $key=>$val){
                if($model_donvi->$key < 3){
                    $a_col[] = $key;
                }
            }
            //dd($a_col);
            $i=0; //lấy 1 số thêm vào ngày giờ vì for chạy xong trong 1s
            foreach ($data as $row) {
                if ($row[$inputs['tencanbo']] == '') {
                    //Tên cán bộ rỗng => thoát
                    continue;
                }
                $model = new hosocanbo();
                $model->madv = $madv;
                $model->macanbo = $madv. '_' . (getdate()[0] + $i++);
                $model->macongchuc = $row[$inputs['macongchuc']];
                $model->tencanbo = $row[$inputs['tencanbo']];
                $model->macvcq = $row[$inputs['macvcq']];
                $model->msngbac = $row[$inputs['msngbac']];
                $model->bac = 1;
                //Tính bậc lương (mặc định 1), lấy mã chức vụ cho cán bộ (mặc định chức vụ không xác định)
                foreach ($a_col as $key => $val) {
                    $model->$val = isset($row[$inputs[$val]]) ? chkDbl($row[$inputs[$val]]) : 0;
                }
                // Từ hệ số lương tính ra bậc lương của cán bộ
                $msngbac = $model_msngbac->where('msngbac',$model->msngbac)->first();
                if(isset($msngbac)){
                    $nhomngbac = $model_nhomngbac->where('manhom',$msngbac->manhom)->first();
                    if(isset($nhomngbac)){
                        $model->bac =(int)(($model->heso - $nhomngbac->heso)/$nhomngbac->hesochenhlech) + 1;
                    }
                }else{
                    $model->msngbac = null;
                }
                //Lấy mã chức vụ nếu có
                $chucvu = $model_chucvu->where('macvcq',$model->macvcq)->first();
                if(isset($chucvu)) {
                    $model->macvcq = isset($chucvu) ? $chucvu->macvcq : null;
                }
                $model->save();
            }

            File::Delete($path);
            return redirect('nghiep_vu/ho_so/danh_sach');
        }else
            return view('errors.notlogin');
    }
    //</editor-fold>
}