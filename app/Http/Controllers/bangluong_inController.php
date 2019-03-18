<?php

namespace App\Http\Controllers;


use App\bangluong;
use App\dmdonvi;
use App\dmphucap_donvi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController as data;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class bangluong_inController extends Controller
{
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

            return view('reports.bangluong.tonghopbangluong.mautt107_m2')
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
            $a_canbo[$i]['tonghs'] = $first['tonghs'];
        }

        return array($a_canbo, $a_pc);
    }

}
