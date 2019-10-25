<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_ct;
use App\bangluong_truc;
use App\dmchucvucq;
use App\dmphongban;
use App\dmdonvi;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\hosocanbo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban','noidung')->where('mabl',$mabl)->first();

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
                'col'=>$col,
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);

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
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban','noidung')->where('mabl',$mabl)->first();
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
                'luongcb' => $m_bl->luongcoban,
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);

            return view('reports.bangluong.donvi.mauthpc')
                ->with('model',$model)
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    //mẫu cam ranh
    public function printf_mautt107_m4(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai','luongcoban','noidung')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu'],
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();
            $a_pl = array_column($model_pc->toarray(),'phanloai','mapc');
            //dd($a_pl);
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            //dd($a_phucap);
            //chạy lại để tính lại phụ cấp
            $luongcb = $m_bl->luongcoban;
            foreach($model as $cb){
                $cb->ttl_tn = $cb->ttl;
                $cb->ttl = $cb->ttl - $cb->giaml;
                if($cb->congtac == 'DAINGAY' || $cb->congtac == 'THAISAN' || $cb->congtac == 'KHONGLUONG'){
                    $cb->tonghs = 0;
                    foreach($a_phucap as $k=>$v) {
                        $cb->tonghs += $cb->$k;
                    }
                    $cb->ttl_tn = round($cb->tonghs * $luongcb, 0);
                    $cb->giaml = $cb->ttl_tn - $cb->ttl;//in mức giảm lương
                }
            }
            return view('reports.bangluong.donvi.mautt107_m4')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_dangkyluong(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai','luongcoban','noidung')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu'],
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);
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
            //dd($a_phucap);
            //chạy lại để tính lại phụ cấp, bảo hiểm cho cán bộ nghỉ
            foreach($model as $cb){
                if($cb->congtac == 'DAINGAY' || $cb->congtac == 'THAISAN' || $cb->congtac == 'KHONGLUONG'){
                    $heso = $sotien = 0;
                    $stbhxh = $stbhyt = $stkpcd = $stbhtn = 0;
                    $stbhxh_dv = $stbhyt_dv = $stkpcd_dv = $stbhtn_dv = 0;

                    foreach($model_pc as $ct) {
                        $mapc = $ct->mapc;
                        $mapc_st ='st_'.$ct->mapc;

                        switch ($ct->phanloai) {
                            case 1: {//số tiền
                                $sotien += $cb->$mapc;
                                $cb->$mapc_st = $cb->$mapc;
                                break;
                            }

                            default: {//trường hợp còn lại (ẩn,...)
                                $heso += $cb->$mapc;
                                $cb->$mapc_st = round($cb->$mapc * $cb->luongcoban);
                                $sotien = 0;
                                break;
                            }
                        }
                        if ($ct->baohiem == 1) {
                            $stbhxh += round($cb->$mapc_st * $cb->bhxh);
                            $stbhyt += round($cb->$mapc_st * $cb->bhyt);
                            $stkpcd += round($cb->$mapc_st * $cb->kpcd);
                            $stbhtn += round($cb->$mapc_st * $cb->bhtn);

                            $stbhxh_dv += round($cb->$mapc_st * $cb->bhxh_dv);
                            $stbhyt_dv += round($cb->$mapc_st * $cb->bhyt_dv);
                            $stkpcd_dv += round($cb->$mapc_st * $cb->kpcd_dv);
                            $stbhtn_dv += round($cb->$mapc_st * $cb->bhtn_dv);
                        }
                    }
                    $cb->stbhxh = $stbhxh;
                    $cb->stbhyt = $stbhyt;
                    $cb->stkpcd = $stkpcd;
                    $cb->stbhtn= $stbhtn;
                    $cb->ttbh = $stbhxh + $stbhyt + $stkpcd + $stbhtn;
                    $cb->stbhxh_dv = $stbhxh_dv;
                    $cb->stbhyt_dv = $stbhyt_dv;
                    $cb->stkpcd_dv = $stkpcd_dv;
                    $cb->stbhtn_dv = $stbhtn_dv;
                    $cb->ttbh_dv = $stbhxh_dv + $stbhyt_dv + $stkpcd_dv + $stbhtn_dv;
                    $cb->tonghs = $heso;
                    $cb->ttl = $sotien;
                }
            }
            //dd($model);
            return view('reports.bangluong.donvi.mautt107_dk_m2')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauds_m2(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv'] = session('admin')->madv;

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai','noidung')->where('mabl',$mabl)->first();
            $model= $this->getBangLuong($inputs);
            //dd($model);
            //$model = bangluong_ct::where('mabl',$mabl)->get();

            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(),'sotk','macanbo');
            foreach($model as $ct) {
                $ct->sotk = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
            }
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);

            return view('reports.bangluong.donvi.maudschitra_m2')
                ->with('model',$model->sortBy('stt'))
                ->with('model_pb',getPhongBan())
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('model_congtac',$model_congtac)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_dstangluong(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $m_bl = bangluong::select('madv','thang','mabl','manguonkp', 'nam')->where('mabl', $inputs['mabl'])->first();

            if($m_bl->thang == '01'){
                $thang = '12';
                $nam = str_pad($m_bl->nam - 1, 4, '0', STR_PAD_LEFT) ;
            }else{
                $thang = str_pad($m_bl->thang - 1, 2, '0', STR_PAD_LEFT) ;
                $nam = $m_bl->nam;
            }

            $m_bl_trc = bangluong::select('madv','thang','mabl','manguonkp', 'nam')
                ->where('thang',$thang)->where('nam',$nam)->where('manguonkp',$m_bl->manguonkp)
                ->where('madv',$m_bl->madv)
                ->first();

            if(count($m_bl_trc) == 0){
                return view('errors.nodata')
                    ->with('message','Không tìm thấy bảng lương tháng '.$thang.' năm '.$nam.' (cùng nguồn kinh phí) để so sánh.');
            }

            $model_bl = (new data())->getBangluong_ct($m_bl->thang,$m_bl->mabl);
            $model_trc = (new data())->getBangluong_ct($m_bl_trc->thang,$m_bl_trc->mabl);

            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->get();

            $model = new Collection();
            foreach($model_bl as $ct) {//trường hợp giảm lương
                $canbo = $model_trc->where('macanbo', $ct->macanbo)->where('mact', $ct->mact)
                    ->where('mapb', $ct->mapb)->first();
                if (count($canbo) > 0) {
                    foreach ($model_pc as $pc) {
                        $mapc = $pc->mapc;
                        $mapc_st = 'st_' . $pc->mapc;
                        $ct->$mapc -= $canbo->$mapc;
                        $ct->$mapc_st -= $canbo->$mapc_st;
                    }
                    //tính lại lương thực nhận do đã giảm trừ
                    $ct->luongtn = $ct->ttl - $ct->ttbh - $canbo->ttl + $canbo->ttbh;
                    $ct->tonghs -= $canbo->tonghs;
                    $ct->ttl -= $canbo->ttl;
                    $ct->stbhxh -= $canbo->stbhxh;
                    $ct->stbhyt -= $canbo->stbhyt;
                    $ct->stkpcd -= $canbo->stkpcd;
                    $ct->stbhtn -= $canbo->stbhtn;
                    $ct->ttbh -= $canbo->ttbh;
                    $ct->stbhxh_dv -= $canbo->stbhxh_dv;
                    $ct->stbhyt_dv -= $canbo->stbhyt_dv;
                    $ct->stkpcd_dv -= $canbo->stkpcd_dv;
                    $ct->stbhtn_dv -= $canbo->stbhtn_dv;
                    $ct->ttbh_dv -= $canbo->ttbh_dv;

                }
                //nếu ttl > 0 =>add
                if ($ct->ttl > 0) {
                    $model->add($ct);
                }
            }
            //dd($model);

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo

            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            return view('reports.bangluong.donvi.dstangluong')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Danh sách cán bộ tăng lương');
        } else
            return view('errors.notlogin');
    }

    public function printf_dsgiamluong(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $m_bl = bangluong::select('madv','thang','mabl','manguonkp', 'nam')->where('mabl', $inputs['mabl'])->first();

            if($m_bl->thang == '01'){
                $thang = '12';
                $nam = str_pad($m_bl->nam - 1, 4, '0', STR_PAD_LEFT) ;
            }else{
                $thang = str_pad($m_bl->thang - 1, 2, '0', STR_PAD_LEFT) ;
                $nam = $m_bl->nam;
            }

            $m_bl_trc = bangluong::select('madv','thang','mabl','manguonkp', 'nam')
                ->where('thang',$thang)->where('nam',$nam)->where('manguonkp',$m_bl->manguonkp)
                ->where('madv',$m_bl->madv)
                ->first();

            if(count($m_bl_trc) == 0){
                return view('errors.nodata')
                    ->with('message','Không tìm thấy bảng lương tháng '.$thang.' năm '.$nam.' (cùng nguồn kinh phí) để so sánh.');
            }

            $model_bl = (new data())->getBangluong_ct($m_bl->thang,$m_bl->mabl);
            $model_trc = (new data())->getBangluong_ct($m_bl_trc->thang,$m_bl_trc->mabl);

            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->get();

            $model = new Collection();

            foreach($model_trc as $ct) {//trường hợp giảm lương
                $canbo = $model_bl->where('macanbo', $ct->macanbo)->where('mact', $ct->mact)
                    ->where('mapb', $ct->mapb)->first();
                if (count($canbo) > 0) {
                    foreach ($model_pc as $pc) {
                        $mapc = $pc->mapc;
                        $mapc_st = 'st_' . $pc->mapc;
                        $ct->$mapc -= $canbo->$mapc;
                        $ct->$mapc_st -= $canbo->$mapc_st;
                    }
                    //tính lại lương thực nhận do đã giảm trừ
                    $ct->luongtn = $ct->ttl - $ct->ttbh - $canbo->ttl + $canbo->ttbh;
                    $ct->tonghs -= $canbo->tonghs;
                    $ct->ttl -= $canbo->ttl;
                    $ct->stbhxh -= $canbo->stbhxh;
                    $ct->stbhyt -= $canbo->stbhyt;
                    $ct->stkpcd -= $canbo->stkpcd;
                    $ct->stbhtn -= $canbo->stbhtn;
                    $ct->ttbh -= $canbo->ttbh;
                    $ct->stbhxh_dv -= $canbo->stbhxh_dv;
                    $ct->stbhyt_dv -= $canbo->stbhyt_dv;
                    $ct->stkpcd_dv -= $canbo->stkpcd_dv;
                    $ct->stbhtn_dv -= $canbo->stbhtn_dv;
                    $ct->ttbh_dv -= $canbo->ttbh_dv;

                }
                //nếu ttl > 0 =>add
                if ($ct->ttl > 0) {
                    $model->add($ct);
                }

            }
            //dd($model);

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin = array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu']);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo

            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            return view('reports.bangluong.donvi.dsgiamluong')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Danh sách cán bộ tăng lương');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_pb_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            /*
            //lưu mã pb lại vì chưa lọc theo phòng ban
            $mapb = $inputs['mapb'];
            */
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai','noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong($inputs);

            $model_pb = dmphongban::select('mapb', 'tenpb')
                ->wherein('mapb', a_unique(array_column($model->toarray(),'mapb')))->get();

            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,
                );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            return view('reports.bangluong.donvi.mautt107_pb_m2')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_pb', $model_pb)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    //</editor-fold>

    //<editor-fold desc="Bảng lương mẫu lai châu">
    public function printf_mautt107_lc(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai','luongcoban','noidung')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu'],
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->wherenotin('mapc',$a_goc)->get();

            //dd($a_pl);
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    if($ct->mapc == 'heso'){
                        $a_phucap['heso'] = $ct->report;
                        $col++;
                        $a_phucap['st_heso'] = 'Lương chính';
                        $col++;
                    }elseif ($ct->mapc == 'pctnn'){
                        $a_phucap['hs_pctnn'] = '% TN';
                        $col++;
                        $a_phucap['st_pctnn'] = $ct->report;
                        $col++;
                    }else{
                        $ma_st = 'st_'. $ct->mapc;
                        $a_phucap[$ma_st] = $ct->report;
                        $col++;
                    }
                }
            }
            //dd($a_phucap);
            //chạy lại để tính lại phụ cấp

            return view('reports.bangluong.donvi.mautt107_lc')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_lc_xp(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','phanloai','luongcoban','noidung')->where('mabl',$mabl)->first();
            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact','tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(),'mact')))->get();

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,'phanloai'=>$m_bl->phanloai,
                'cochu'=>$inputs['cochu'],
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,);
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott','heso','hesobl','pccv');
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')
                ->wherenotin('mapc',$a_goc)->get();

            //dd($a_pl);
            $a_phucap = array();
            $col = 0;

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $ma_st = 'st_' . $ct->mapc;
                    $a_phucap[$ma_st] = $ct->report;
                    $col++;
                }
            }
            //dd($a_phucap);
            //chạy lại để tính lại phụ cấp

            return view('reports.bangluong.donvi.mautt107_lc_xp')
                ->with('model',$model->sortBy('stt'))
                ->with('m_dv',$m_dv)
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                ->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
                ->with('pageTitle','Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_lc_pb(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai','noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong($inputs);

            $model_pb = dmphongban::select('mapb', 'tenpb')
                ->wherein('mapb', a_unique(array_column($model->toarray(),'mapb')))->get();

            $thongtin = array('nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    if($ct->mapc == 'hesopc'){
                        $a_phucap['hesopc'] = $ct->report;
                        $col++;
                        $a_phucap['st_hesopc'] = 'Sinh hoạt phí';
                        $col++;
                    }else{
                        $ma_st = 'st_'. $ct->mapc;
                        $a_phucap[$ma_st] = $ct->report;
                        $col++;
                    }
                }
            }
            return view('reports.bangluong.donvi.mautt107_lc_pb')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_pb', $model_pb)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
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
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban','phanloai','noidung')->where('mabl',$mabl)->first();
            $a_pl = getPhanLoaiBangLuong();
            $m_bl->tenphanloai = isset($a_pl[$m_bl->phanloai]) ? $a_pl[$m_bl->phanloai] : 'Bảng thanh toán chi khác';
            //$model = bangluong_ct::where('mabl',$mabl)->get(); //dùng hàm lấy dữ liệu chung
            $model = bangluong_truc::where('mabl',$m_bl->mabl)->get();

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,
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

    public function printf_mautruc(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            //dd($inputs);
            $m_bl = bangluong::select('thang','nam','mabl','madv','ngaylap','luongcoban','phanloai','noidung')->where('mabl',$mabl)->first();
            $a_pl = getPhanLoaiBangLuong();
            $m_bl->tenphanloai = isset($a_pl[$m_bl->phanloai]) ? $a_pl[$m_bl->phanloai] : 'Bảng thanh toán chi khác';
            $model = (new data())->getBangluong_ct($m_bl->thang,$m_bl->mabl);

            $a_phucap = array();
            $col = 0;
            $model_pc = dmphucap_donvi::where('madv',$m_bl->madv)->where('phanloai','<','3')->get();

            foreach($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }

            $m_dv = dmdonvi::where('madv',$m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin=array('nguoilap'=>$m_bl->nguoilap,
                'thang'=>$m_bl->thang,
                'nam'=>$m_bl->nam,
                'ngaylap'=>$m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung'=>isset($inputs['innoidung']),
                'noidung'=>$m_bl->noidung,
            );

            return view('reports.bangluong.donvi.mautruc')
                ->with('model',$model)
                //->with('model_congtac',$model_congtac)
                ->with('m_dv',$m_dv)
                ->with('m_bl',$m_bl)
                ->with('a_cv',getChucVuCQ(false))
                ->with('thongtin',$thongtin)
                ->with('col',$col)
                //->with('model_congtac',$model_congtac)
                ->with('a_phucap',$a_phucap)
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
        $model = $getData->getBangluong_ct_th($inputs['thang_th'], $inputs['nam_th'], $inputs['madv'],
                    isset($inputs['manguonkp'])? $inputs['manguonkp']: null, 'BANGLUONG');

        $model_tl = array();
        if(isset($inputs['in_truylinh'])){
            $model_tl = $getData->getBangluong_ct_th($inputs['thang_th'], $inputs['nam_th'], $inputs['madv'],
                isset($inputs['manguonkp'])? $inputs['manguonkp']: null, 'TRUYLINH');
        }
        if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
            $model = $model->where('mapb' ,$inputs['mapb']);
            $model_tl = $model_tl->where('mapb' ,$inputs['mapb']);
        }
        if (isset($inputs['macvcq']) && $inputs['macvcq'] != '') {
            $model = $model->where('macvcq' ,$inputs['macvcq']);
            $model_tl = $model_tl->where('macvcq' ,$inputs['macvcq']);
        }
        if (isset($inputs['mact']) && $inputs['mact'] != '') {
            $model = $model->where('mact' ,$inputs['mact']);
            $model_tl = $model_tl->where('mact' ,$inputs['mact']);
        }

        $model_canbo = $model->unique('macanbo');
        //dd($model_canbo);

        $a_pc = dmphucap_donvi::where('madv', $inputs['madv'])->where('phanloai', '<', '3')
            ->wherenotin('mapc', array('hesott'))->get()->keyby('mapc')->toarray();
        //dd($a_canbo);
        //duyệt từng cán bộm từng phụ cấp để tính lại tổng hệ số
        //-> kiểm tra số tiền > 0 => cộng vào tổng hệ số.

        //dd($model_tl);
        foreach($model_canbo as $cb){
            $m_canbo = $model->where('macanbo',$cb->macanbo);
            $cb->ttl = $m_canbo->sum('ttl');
            $cb->giaml = $m_canbo->sum('giaml');
            $cb->bhct = $m_canbo->sum('bhct');
            $cb->thuetn = $m_canbo->sum('thuetn');
            $cb->luongtn = $m_canbo->sum('luongtn');

            $cb->stbhxh = $m_canbo->sum('stbhxh');
            $cb->stbhyt = $m_canbo->sum('stbhyt');
            $cb->stkpcd = $m_canbo->sum('stkpcd');
            $cb->stbhtn = $m_canbo->sum('stbhtn');
            $cb->ttbh = $m_canbo->sum('ttbh');
            $cb->stbhxh_dv = $m_canbo->sum('stbhxh_dv');
            $cb->stbhyt_dv = $m_canbo->sum('stbhyt_dv');
            $cb->stkpcd_dv = $m_canbo->sum('stkpcd_dv');
            $cb->stbhtn_dv = $m_canbo->sum('stbhtn_dv');
            $cb->ttbh_dv = $m_canbo->sum('ttbh_dv');

            $cb->tonghs = 0;
            foreach ($a_pc as $k => $v) {
                $mapc_st = 'st_' . $k;
                $cb->$mapc_st = $m_canbo->sum($mapc_st);
                // lấy hệ số phụ cấp của bảng lương có hệ số
                //c1: where($k,>,0), sum()/count()
                //c2: foreach()
                $canbo = $m_canbo->where($k,'>',0);
                $cb->$k = $canbo->sum($k)/(count($canbo) == 0 ? 1 : count($canbo));
                //hệ số và tính lương
                if($cb->$k< 1000 && $cb->$mapc_st > 0){
                    $cb->tonghs += $cb->$k;
                }
            }

            $cb->ttl_tl = 0;//lưu tiền lương truy lĩnh để sau cộng cùng bảng lương
            if(count($model_tl)>0){
                //nếu chỉ lấy mã can bo => chưa tính trường hợp kiêm nhiệm
                $m_canbo_tl = $model_tl->where('macanbo',$cb->macanbo);
                $cb->ttl_tl = $m_canbo_tl->sum('ttl');
                $cb->luongtn += $m_canbo_tl->sum('luongtn');
                $cb->stbhxh += $m_canbo_tl->sum('stbhxh');
                $cb->stbhyt += $m_canbo_tl->sum('stbhyt');
                $cb->stkpcd += $m_canbo_tl->sum('stkpcd');
                $cb->stbhtn += $m_canbo_tl->sum('stbhtn');
                $cb->ttbh += $m_canbo_tl->sum('ttbh');
                $cb->stbhxh_dv += $m_canbo_tl->sum('stbhxh_dv');
                $cb->stbhyt_dv += $m_canbo_tl->sum('stbhyt_dv');
                $cb->stkpcd_dv += $m_canbo_tl->sum('stkpcd_dv');
                $cb->stbhtn_dv += $m_canbo_tl->sum('stbhtn_dv');
                $cb->ttbh_dv += $m_canbo_tl->sum('ttbh_dv');
            }
        }
        return array($model_canbo->toarray(), $a_pc);
    }

    function getBangLuong($inputs)
    {
        $mabl = $inputs['mabl'];
        $m_bl = bangluong::select('madv','thang','mabl')->where('mabl', $mabl)->first();
        $model = (new data())->getBangluong_ct($m_bl->thang,$m_bl->mabl);
        //dd($m_bl);
        $a_hoso = hosocanbo::select('macanbo','sunghiep','sotk','tennganhang','ngaytu','tnntungay','socmnd')
            ->where('madv', $m_bl->madv)->get()->keyby('macanbo')->toarray();
        $a_cv = dmchucvucq::all('tenvt', 'macvcq','tencv')->keyBy('macvcq')->toArray();
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');

        foreach ($model as $hs) {
            if(isset($a_hoso[$hs->macanbo])){
                $hoso = $a_hoso[$hs->macanbo];
                $hs->sunghiep = $hoso['sunghiep'];
                $hs->ngaytu = $hoso['ngaytu'];
                $hs->tnntungay = $hoso['tnntungay'];
                $hs->sotk = $hoso['sotk'];
                $hs->socmnd = $hoso['socmnd'];
                $hs->tennganhang = $hoso['tennganhang'];
                if($hs->tencanbo == ''){
                    $hs->tencanbo = $hoso['tencanbo']; //kiêm nhiệm chưa có tên cán bộ
                }
            }
            if(isset($inputs['inchucvuvt'])){
                $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq]['tenvt'] : '';
            }else{
                $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq]['tencv'] : '';
            }
            //$hs->tencvcq = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            // $hs->tencanbo = isset($a_ht[$hs->macanbo]) ? $a_ht[$hs->macanbo] : ''; //kiêm nhiệm chưa có tên cán bộ
        }

        if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
            $model = $model->where('mapb', $inputs['mapb']);
        }
        if (isset($inputs['macvcq']) && $inputs['macvcq'] != '') {
            $model = $model->where('macvcq', $inputs['macvcq']);
        }
        if (isset($inputs['mact']) && $inputs['mact'] != '') {
            $model = $model->where('mact', $inputs['mact']);
        }
        //dd($model);
        return $model;
    }

}
