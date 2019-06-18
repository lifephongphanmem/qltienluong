<?php

namespace App\Http\Controllers;


use App\bangluong;
use App\bangluong_ct;
use App\bangluong_truc;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\hosocanbo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController as data;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class bangluong_inController extends Controller
{
    //<editor-fold desc="In tổng hợp lương">
    public function printf_mautt107_th(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $inputs['madv'] = session('admin')->madv;
            list($a_canbo, $a_pc) = $this->getData($inputs);
            //dd($a_canbo);
            $m_dv = dmdonvi::where('madv',$inputs['madv'])->first();

            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap')
                ->where('thang',$inputs['thang_th'])
                ->where('nam',$inputs['nam_th'])
                ->where('madv',$inputs['madv'])
                ->where('phanloai', 'BANGLUONG')->first();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucap = array();
            $col = 0;

            foreach($a_pc as $k=>$v){
                $st = array_sum(array_column($a_canbo,$k));;
                if ($st > 0) {
                    $a_phucap[$k] = $v['report'];
                    $col++;
                }
            }

            return view('reports.bangluong.tonghopbangluong.mautt107')
                ->with('a_canbo',$a_canbo)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('a_ct',getPhanLoaiCT(false))
                ->with('a_congtac',a_unique(a_split($a_canbo,array('mact'))))
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_excel(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', function($query) use($mabl){
                    $query->select('mact')->from('bangluong_ct')->where('mabl',$mabl);
                })->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            Excel::create('BANGLUONG_107',function($excel) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                $excel->sheet('New sheet', function($sheet) use($m_dv,$thongtin,$model,$col,$model_congtac,$a_phucap){
                    $sheet->loadView('reports.bangluong.donvi.mautt107_excel')
                        ->with('model',$model->sortBy('stt'))
                        ->with('model_pb',getPhongBan())
                        ->with('m_dv',$m_dv)
                        ->with('thongtin',$thongtin)
                        ->with('col',$col)
                        ->with('model_congtac',$model_congtac)
                        ->with('a_phucap',$a_phucap)
                        ->with('pageTitle','Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_th_m2(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv'] = session('admin')->madv;
            list($a_canbo, $a_pc) = $this->getData($inputs);

            $m_dv = dmdonvi::where('madv',$inputs['madv'])->first();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap')
                ->where('thang',$inputs['thang_th'])
                ->where('nam',$inputs['nam_th'])
                ->where('madv',$inputs['madv'])
                ->where('phanloai', 'BANGLUONG')->first();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucap = array();
            $col = 0;

            foreach($a_pc as $k=>$v){
                $st = array_sum(array_column($a_canbo,$k));;
                if ($st > 0) {
                    $a_phucap[$k] = $v['report'];
                    $col++;
                }
            }
            $a_chucvu = array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(), 'tenvt', 'macvcq');

            return view('reports.bangluong.tonghopbangluong.mautt107_m2')
                ->with('a_canbo',$a_canbo)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('a_ct',getPhanLoaiCT(false))
                ->with('a_congtac',a_unique(a_split($a_canbo,array('mact'))))
                ->with('a_phucap',$a_phucap)
                ->with('a_chucvu',$a_chucvu)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_pb_th(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv'] = session('admin')->madv;
            list($a_canbo, $a_pc) = $this->getData($inputs);

            $m_dv = dmdonvi::where('madv',$inputs['madv'])->first();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap')
                ->where('thang',$inputs['thang_th'])
                ->where('nam',$inputs['nam_th'])
                ->where('madv',$inputs['madv'])
                ->where('phanloai', 'BANGLUONG')->first();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucap = array();
            $col = 0;

            foreach($a_pc as $k=>$v){
                $st = array_sum(array_column($a_canbo,$k));;
                if ($st > 0) {
                    $a_phucap[$k] = $v['report'];
                    $col++;
                }
            }
            //dd($a_canbo);
            return view('reports.bangluong.tonghopbangluong.mautt107_pb')
                ->with('a_canbo',$a_canbo)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('a_pb',getPhongBan(false))
                ->with('a_phongban',a_unique(a_split($a_canbo,array('mapb'))))
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau185_th(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv'] = session('admin')->madv;
            list($a_canbo, $a_pc) = $this->getData($inputs);

            $m_dv = dmdonvi::where('madv',$inputs['madv'])->first();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap')
                ->where('thang',$inputs['thang_th'])
                ->where('nam',$inputs['nam_th'])
                ->where('madv',$inputs['madv'])
                ->where('phanloai', 'BANGLUONG')->first();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucap = array();
            $col = 0;

            foreach($a_pc as $k=>$v){
                $st = array_sum(array_column($a_canbo,$k));;
                if ($st > 0) {
                    $a_phucap[$k] = $v['report'];
                    $col++;
                }
            }


            return view('reports.bangluong.tonghopbangluong.maubangluong')
                ->with('a_canbo',$a_canbo)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('a_ct',getPhanLoaiCT(false))
                ->with('a_congtac',a_unique(a_split($a_canbo,array('mact'))))
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau07_th(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv'] = session('admin')->madv;
            list($a_canbo, $a_pc) = $this->getData($inputs);

            $m_dv = dmdonvi::where('madv',$inputs['madv'])->first();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap')
                ->where('thang',$inputs['thang_th'])
                ->where('nam',$inputs['nam_th'])
                ->where('madv',$inputs['madv'])
                ->where('phanloai', 'BANGLUONG')->first();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucap = array();
            $col = 0;

            foreach($a_pc as $k=>$v){
                $st = array_sum(array_column($a_canbo,$k));;
                if ($st > 0) {
                    $a_phucap[$k] = $v['report'];
                    $col++;
                }
            }

            return view('reports.bangluong.tonghopbangluong.mau07')
                ->with('a_canbo',$a_canbo)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('a_ct',getPhanLoaiCT(false))
                ->with('a_congtac',a_unique(a_split($a_canbo,array('mact'))))
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>

    //<editor-fold desc="In bảng lương">
    public function printf_mauthpl(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            //dd($inputs);
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();

            //$model = bangluong_ct::where('mabl',$mabl)->get(); //dùng hàm lấy dữ liệu chung
            $model = (new data())->getBangluong_ct($m_bl->thang,$m_bl->mabl);
            //$model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            //$a_cv = getChucVuCQ(false);
            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact',a_unique(array_column($model->toarray(),'mact')))->get();
            //dd($model_congtac);
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_phucap = array();
            $a_phucap_st = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $a_phucap_st['st_'.$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            foreach($model_congtac as $ct){
                $canbo = $model->where('mact', $ct->mact)->where('congtac','<>','CHUCVU');
                $canbo_kn = $model->where('mact', $ct->mact)->where('congtac','CHUCVU');
                $ct->soluong = count($canbo);
                /* ko tính kiêm nhiệm chức vụ
                $ct->soluong = count($canbo) + count($canbo_kn);
                */
                //dd($canbo);
                foreach($a_phucap as $k=>$v){
                    $st = 'st_'.$k;
                    $ct->$k = $canbo->sum($k) + $canbo_kn->sum($k);
                    $ct->$st = $canbo->sum($st) + $canbo_kn->sum($st);
                }
                $ct->ttl = $canbo->sum('ttl');
                $ct->ttl_kn = $canbo_kn->sum('ttl');
                $ct->ttbh = $canbo->sum('ttbh') + $canbo_kn->sum('ttbh');
                $ct->luongtn = $canbo->sum('luongtn') + $canbo_kn->sum('luongtn');
            }
            //dd($model_congtac);
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'col'=>$col);

            return view('reports.bangluong.donvi.mauthpl')
                ->with('model',$model_congtac)
                ->with('m_dv',$m_dv)
                ->with('a_phucap',$a_phucap_st)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauthpc(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            //dd($inputs);
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban')->where('mabl',$mabl)->first();
            $model_bl = (new data())->getBangluong_ct($m_bl->thang,$m_bl->mabl);
            //$model_bl = bangluong_ct::where('mabl',$mabl)->get();
            //$model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();
            //$a_cv = getChucVuCQ(false);
            //dd($model_bl);
            $model = array(
                array('mact'=>'1536459380','mapc'=>'all','congtac'=>'all','tenct'=>'Cấp ủy viên','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536459380','mapc'=>'pckn','congtac'=>'','tenct'=>'Ủy viên ủy ban kiểm tra đảng ủy','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536459382','mapc'=>'all','congtac'=>'all','tenct'=>'Chi ủy viên','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402868','mapc'=>'all','congtac'=>'all','tenct'=>'Phụ cấp đại biểu HĐND','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402868','mapc'=>'pcdith','congtac'=>'all','tenct'=>'Hỗ trợ thông tin liên lạc đại biểu HĐND','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402870','mapc'=>'all','congtac'=>'all','tenct'=>'Phụ cấp đại biểu HĐND chuyên trách','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402868','mapc'=>'pckn','congtac'=>'all','tenct'=>'Phụ cấp kiêm nhiệm các chức danh của HĐND','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536459160','mapc'=>'pcd','congtac'=>'all','tenct'=>'Phụ cấp cán bộ làm công tác tiếp nhận và trả kết quả','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402895','mapc'=>'all','congtac'=>'all','tenct'=>'Phụ cấp cán bộ trung tâm học tập cộng đồng','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402878','mapc'=>'pctn','congtac'=>'all','tenct'=>'Phụ cấp trách nhiệm cán bộ quản lý quân sự','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536402878','mapc'=>'pcdbn','congtac'=>'all','tenct'=>'Phụ cấp đặc thù phó CHT quân sự','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1537427170','mapc'=>'all','congtac'=>'all','tenct'=>'Phụ cấp đội hoạt động xã hội tình nguyện','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
                array('mact'=>'1536459160','mapc'=>'pcdh','congtac'=>'all','tenct'=>'Phụ cấp công chức chuyên trách về ứng dụng công nghệ thông tin','soluong'=>0,'ttl'=>0,'ttl_kn'=>0),
            );
            for($i=0;$i<count($model);$i++){
                $m_kq = $model_bl->where('mact',$model[$i]['mact']);
                if(count($m_kq)>0){
                    if($model[$i]['mapc'] == 'all'){
                        $model[$i]['soluong'] = count($m_kq);
                        $model[$i]['ttl'] = $m_kq->sum('ttl');
                        //$model[$i]['luongtn'] = $m_kq->sum('luongtn');
                    }else{
                        $mapc = $model[$i]['mapc'];
                        $mapc_st = 'st_'.$model[$i]['mapc'];
                        foreach($m_kq as $pc){
                            if($pc->$mapc > 0){
                                $model[$i]['soluong'] ++;
                                $model[$i]['ttl'] += $pc->$mapc_st;
                                //$model[$i]['luongtn'] += $pc->$mapc;
                            }
                        }
                    }
                }
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban);

            return view('reports.bangluong.donvi.mauthpc')
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>

    //<editor-fold desc="Chi khác">
    public function printf_mauctphi(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            //dd($inputs);
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban','phanloai')->where('mabl',$mabl)->first();
            $a_pl = getPhanLoaiBangLuong();
            $m_bl->tenphanloai = isset($a_pl[$m_bl->phanloai]) ? $a_pl[$m_bl->phanloai] : 'Bảng thanh toán chi khác';
            //$model = bangluong_ct::where('mabl',$mabl)->get(); //dùng hàm lấy dữ liệu chung
            $model = bangluong_truc::where('mabl',$m_bl->mabl)->get();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact',a_unique(array_column($model->toarray(),'mact')))->get();
            //dd($model_congtac);

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                ''
                );
            //dd($model_congtac);
            return view('reports.bangluong.donvi.mauctphi')
                ->with('model',$model)
                //->with('model_congtac',$model_congtac)
                ->with('m_dv',$m_dv)
                ->with('m_bl',$m_bl)
                ->with('a_cv',getChucVuCQ(false))
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>
    /**
     * @param $inputs
     * @return array
     */
    public function getData($inputs)
    {
        $getData = new data();
        $model = $getData->getBangluong_ct_th($inputs['thang_th'], $inputs['nam_th'], $inputs['madv'], isset($inputs['manguonkp'])? $inputs['manguonkp']: null);
        if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
            $model = a_getelement_equal($model, array('mapb' => $inputs['mapb']));
        }
        if (isset($inputs['macvcq']) && $inputs['macvcq'] != '') {
            $model = a_getelement_equal($model, array('macvcq' => $inputs['macvcq']));
        }
        if (isset($inputs['mact']) && $inputs['mact'] != '') {
            $model = a_getelement_equal($model, array('mact' => $inputs['mact']));
            return array($inputs, $model);
        }
        //kiểm tra xem có lấy bảng lương truy lĩnh ko
        $model_tl = array();
        if(isset($inputs['in_truylinh'])){
            //lấy bảng truy lĩnh lương
            //tạo thành mảng rồi cộng vào ds cán bộ
            if(isset($inputs['manguonkp'])){
                $model_tl = \App\bangluong::join('bangluong_ct','bangluong.mabl','=','bangluong_ct.mabl')
                    ->where('bangluong.thang',$inputs['thang_th'])
                    ->where('bangluong.nam', $inputs['nam_th'])
                    ->where('bangluong.madv',$inputs['madv'])
                    ->where('bangluong.phanloai', 'TRUYLINH')
                    ->select('bangluong_ct.*')
                    //->orderby('bangluong_ct.stt')
                    ->get()->sortby('stt')->toarray();
            }else{
                $model_tl = \App\bangluong::join('bangluong_ct','bangluong.mabl','=','bangluong_ct.mabl')
                    ->where('bangluong.thang',$inputs['thang_th'])
                    ->where('bangluong.nam', $inputs['nam_th'])
                    ->where('bangluong.madv',$inputs['madv'])
                    ->wherein('bangluong_ct.manguonkp',$inputs['manguonkp'])
                    ->where('bangluong.phanloai', 'TRUYLINH')
                    ->select('bangluong_ct.*')
                    //->orderby('bangluong_ct.stt')
                    ->get()->sortby('stt')->toarray();
            }
        }
        //dd($model_tl);
        //
        //lấy danh sách cán bộ
        $a_canbo = a_split($model, array('macanbo', 'macvcq', 'mapb', 'mact', 'msngbac'));
        $a_canbo = a_unique($a_canbo);

        $a_pc = dmphucap_donvi::where('madv', $inputs['madv'])->where('phanloai', '<', '3')
            ->wherenotin('mapc', array('hesott'))->get()->keyby('mapc')->toarray();
        //dd($a_canbo);
        for ($i = 0; $i < count($a_canbo); $i++) {
            $data = a_getelement_equal($model, $a_canbo[$i]);
            $a_canbo[$i]['ttl'] = array_sum(array_column($data, 'ttl'));
            $a_canbo[$i]['giaml'] = array_sum(array_column($data, 'giaml'));
            $a_canbo[$i]['bhct'] = array_sum(array_column($data, 'bhct'));
            $a_canbo[$i]['thuetn'] = array_sum(array_column($data, 'thuetn'));
            $a_canbo[$i]['luongtn'] = array_sum(array_column($data, 'luongtn'));

            $a_canbo[$i]['stbhxh'] = array_sum(array_column($data, 'stbhxh'));
            $a_canbo[$i]['stbhyt'] = array_sum(array_column($data, 'stbhyt'));
            $a_canbo[$i]['stkpcd'] = array_sum(array_column($data, 'stkpcd'));
            $a_canbo[$i]['stbhtn'] = array_sum(array_column($data, 'stbhtn'));
            $a_canbo[$i]['ttbh'] = array_sum(array_column($data, 'ttbh'));
            $a_canbo[$i]['stbhxh_dv'] = array_sum(array_column($data, 'stbhxh_dv'));
            $a_canbo[$i]['stbhyt_dv'] = array_sum(array_column($data, 'stbhyt_dv'));
            $a_canbo[$i]['stkpcd_dv'] = array_sum(array_column($data, 'stkpcd_dv'));
            $a_canbo[$i]['stbhtn_dv'] = array_sum(array_column($data, 'stbhtn_dv'));
            $a_canbo[$i]['ttbh_dv'] = array_sum(array_column($data, 'ttbh_dv'));

            $first = array_shift($data);
            $a_canbo[$i]['tencanbo'] = $first['tencanbo'];
            $a_canbo[$i]['stt'] = $first['stt'];
            foreach ($a_pc as $k => $v) {
                $a_canbo[$i][$k] = $first[$k];
                $a_canbo[$i]['st_' . $k] = $first['st_' . $k];
            }
            $a_canbo[$i]['hs_vuotkhung'] = $first['hs_vuotkhung'];
            $a_canbo[$i]['tonghs'] = $first['tonghs'];

            $a_canbo[$i]['ttl_tl'] = 0;//lưu tiền lương truy lĩnh để sau cộng cùng bảng lương
            if(count($model_tl)>0){
                //nếu chỉ lấy mã can bo => chưa tính trường hợp kiêm nhiệm
                $data_tl = a_getelement_equal($model_tl, array('macanbo'=>$a_canbo[$i]['macanbo']));
                //dd($data_tl);
                $a_canbo[$i]['ttl_tl'] = array_sum(array_column($data_tl, 'ttl'));

                $a_canbo[$i]['luongtn'] += array_sum(array_column($data_tl, 'luongtn'));
                $a_canbo[$i]['stbhxh'] += array_sum(array_column($data_tl, 'stbhxh'));
                $a_canbo[$i]['stbhyt'] += array_sum(array_column($data_tl, 'stbhyt'));
                $a_canbo[$i]['stkpcd'] += array_sum(array_column($data_tl, 'stkpcd'));
                $a_canbo[$i]['stbhtn'] += array_sum(array_column($data_tl, 'stbhtn'));
                $a_canbo[$i]['ttbh'] += array_sum(array_column($data_tl, 'ttbh'));
                $a_canbo[$i]['stbhxh_dv'] += array_sum(array_column($data_tl, 'stbhxh_dv'));
                $a_canbo[$i]['stbhyt_dv'] += array_sum(array_column($data_tl, 'stbhyt_dv'));
                $a_canbo[$i]['stkpcd_dv'] += array_sum(array_column($data_tl, 'stkpcd_dv'));
                $a_canbo[$i]['stbhtn_dv'] += array_sum(array_column($data_tl, 'stbhtn_dv'));
                $a_canbo[$i]['ttbh_dv'] += array_sum(array_column($data_tl, 'ttbh_dv'));
            }
        }

        return array($a_canbo, $a_pc);
    }

}
