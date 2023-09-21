<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_bangluong;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\tonghopluong_khoi;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class tonghopnguon_khoiController extends Controller
{
    //Mỗi khối chỉ tồn tại 1 bản ghi theo TTQD
    //không có hàm tổng hợp riêng (chỉ in)=>phát ra ban ghi ở bảng nguonkinhphi_khoi => dữ liệu đã gửi
    public function index_130818(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $model_nguon = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('trangthai', 'DAGUI')
                ->where('macqcq', $madv)->get();

            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên
            $model_donvi = dmdonvi::select('madv', 'tendv')
                ->wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->get();


            //$model_donvi = dmdonvi::select('madv', 'tendv')->where('macqcq', $madv)->get();
            foreach ($model_donvi as $dv) {
                $nguon = $model_nguon->where('madv', $dv->madv)->first();
                if (isset($nguon)) {
                    $dv->trangthai = $nguon->trangthai;
                    $dv->masodv = $nguon->masodv;
                } else {
                    $dv->trangthai = 'CHUATAO';
                    $dv->masodv = NULL;
                }
            }

            return view('functions.tonghopnguon.index')
                ->with('model', $model_donvi)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong', $model_nguon->count('madv') . '/' . $model_donvi->count('madv'))
                ->with('furl', '/chuc_nang/tong_hop_nguon/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    public function index()
    {
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            $model_nguon = nguonkinhphi::where('trangthai', 'DAGUI')->where('macqcq', $madv)->get();
            $model_nguon_khoi = nguonkinhphi_khoi::where('madv', $madv)->get();
            $model = dmthongtuquyetdinh::all();
            $a_trangthai = getStatus();
            //Lấy dữ liệu các đơn vị cấp dưới đã gửi lên

            //2023.09.13
            // $model_donvi = dmdonvi::select('madv', 'tendv')
            //     ->wherein('madv', function ($query) use ($madv) {
            //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            //     })->get();

            // $soluong = $model_donvi->count();



            // dd($model_donvi);

            $dulieukhoi = nguonkinhphi_khoi::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('nguonkinhphi_khoi')->where('macqcq', $madv)->where('madv', '<>', $madv)
                    ->where('trangthai', 'DAGUI')->get();
            })->where('trangthai', 'DAGUI')->get();
            //  dd($model);
            foreach ($model as $dv) {
                //dd($dv);
                // $nam = $dv->namdt;
                // $model_donvi = dmdonvi::select('madv', 'tendv', 'macqcq', 'maphanloai', 'phanloaitaikhoan')
                //     ->wherein('madv', function ($query) use ($madv) {
                //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                //     })->wherenotin('madv', function ($query) use ($madv, $nam) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->whereyear('ngaydung', '<=', $nam)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })->get();

                // $soluong = $model_donvi->count();
                $nguon_khoi = $model_nguon_khoi->where('sohieu', $dv->sohieu)->first();
                $nam=$dv->namdt;
                // $model_donvi = dmdonvi::select('madv', 'tendv', 'macqcq', 'maphanloai', 'phanloaitaikhoan')
                //     ->wherein('madv', function ($query) use ($madv) {
                //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                //     })
                //     ->wherenotin('madv', function ($query) use ($madv, $nam) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->whereyear('ngaydung', '<=', $nam)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })->get();
                // $soluong = $model_donvi->count();
                $soluong = count(getDonviHuyen($nam,$madv)['m_donvi']);
                if ($nguon_khoi != null) {
                    //Đã tổng hợp dữ liệu
                    $dv->sldv = $soluong . '/' . $soluong;
                    $dv->masodv = $nguon_khoi->masodv;
                    $dv->trangthai = $nguon_khoi->trangthai;
                    //$dv->trangthai = 'DAGUI';
                } else {
                    //Chưa tổng hợp dữ liệu
                    $sl = $model_nguon->where('sohieu', $dv->sohieu)->count();
                    $khoi = $dulieukhoi->where('sohieu', $dv->sohieu)->count();
                    $dv->sldv = $sl + $khoi . '/' . $soluong;
                    $dv->masodv = null;
                    if ($dv->sohieu == 'tt78_2022') 
                    
                    //dd($khoi);

                    if (($sl + $khoi) == 0) {
                        $dv->trangthai = 'CHUADL';
                    } elseif (($sl + $khoi) < $soluong) {
                        $dv->trangthai = 'CHUADAYDU';
                    } elseif (($sl + $khoi) == $soluong) {
                        $dv->trangthai = 'CHUAGUI';
                    } else {
                        $dv->trangthai = 'CHUATAO';
                    }
                }
            }
            $inputs['madvbc'] = session('admin')->madvbc;
            $inputs['macqcq'] = session('admin')->madv;
            // dd($model);
            return view('functions.tonghopnguon.khoi.index')
                ->with('model', $model)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('soluong', $soluong)
                ->with('furl', '/chuc_nang/tong_hop_nguon/khoi')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/khoi')
                ->with('furl_th', '/chuc_nang/tong_hop_nguon/huyen/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    public function tralai(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = nguonkinhphi::where('masodv', $inputs['masodv'])->first();
            $model->trangthai = 'TRALAI';
            $model->lydo = $inputs['lydo'];
            $model->save();

            return redirect('/chuc_nang/xem_du_lieu/nguon/khoi?sohieu=' . $model->sohieu . '&trangthai=ALL&phanloai=ALL');
        } else
            return view('errors.notlogin');
    }

    function senddata(Request $requests)
    {
        if (Session::has('admin')) {
            if (session('admin')->macqcq == '') {
                return view('errors.chuacqcq');
            }
            $inputs = $requests->all();
            $madv = session('admin')->madv;
            $model_nguon_khoi = nguonkinhphi_khoi::where('sohieu', $inputs['sohieu'])->where('madv', $madv)->first();

            //$model_nguon = nguonkinhphi::where('sohieu',$inputs['sohieu'])->where('macqcq', $madv)->get();
            if ($model_nguon_khoi != null) {
                //Trường hợp đơn vị bị trả lại dữ liệu muốn gửi lại
                $model_nguon_khoi->trangthai = 'DAGUI';
                $model_nguon_khoi->nguoilap = session('admin')->name;
                $model_nguon_khoi->ngaylap = Carbon::now()->toDateTimeString();
                $model_nguon_khoi->save();

                //Xoá hết mã khối cũ
                nguonkinhphi::where('sohieu', $inputs['sohieu'])->where('macqcq', $madv)
                    ->update(['masok' => null]);
                //Update mã khối mới
                nguonkinhphi::where('sohieu', $inputs['sohieu'])->where('macqcq', $madv)->where('trangthai', 'DAGUI')
                    ->update(['masok' => $model_nguon_khoi->masodv]);
            } else {
                $inputs['madv'] = session('admin')->madv;
                $inputs['masodv'] = getdate()[0];;
                $inputs['trangthai'] = 'DAGUI';
                $inputs['noidung'] = 'Đơn vị ' . getTenDV(session('admin')->madv) . ' tổng hợp dữ liệu từ các đơn vị cấp dưới.';
                $inputs['nguoilap'] = session('admin')->name;
                $inputs['ngaylap'] = Carbon::now()->toDateTimeString();
                $inputs['macqcq'] = session('admin')->macqcq;
                $inputs['madvbc'] = session('admin')->madvbc;

                //Xoá hết mã khối cũ
                nguonkinhphi::where('sohieu', $inputs['sohieu'])->where('macqcq', $madv)
                    ->update(['masok' => null]);
                //Update mã khối mới
                nguonkinhphi::where('sohieu', $inputs['sohieu'])->where('macqcq', $madv)->where('trangthai', 'DAGUI')
                    ->update(['masok' => $inputs['masodv']]);

                //import vào bảng nguonkinhphi_khoi do nguonkinhphi_khoi(TH) = nguonkinhphi(SD)
                nguonkinhphi_khoi::create($inputs);
                nguonkinhphi_huyen::create($inputs);
            }
            return redirect('/chuc_nang/tong_hop_nguon/khoi/index');
        } else
            return view('errors.notlogin');
    }

    function printf_tt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = nguonkinhphi_bangluong::where('masodv', $inputs['maso'])->where('thang', '07')->orderby('stt')->get();
            //dd($model);

            //$model = dutoanluong_bangluong::where('masodv', $inputs['masodv'])->orderby('thang')->get();
            $model_thongtin = nguonkinhphi::where('masodv', $inputs['maso'])->first();
            $a_congtac = array_column(dmphanloaict::wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get()->toArray(), 'tenct', 'mact');
            //dd($a_ct);
            //cho trương hợp đơn vị cấp trên in dữ liệu dv câp dưới mà ko sai tên đơn vị
            $m_dv = dmdonvi::where('madv', $model_thongtin->madv)->first();
            $a_phucap = array();
            $col = 0;
            $m_pc = dmphucap_donvi::where('madv', $model_thongtin->madv)->orderby('stt')->get()->toarray();

            foreach ($m_pc as $ct) {
                if ($model->sum($ct['mapc']) > 0) {
                    $a_phucap[$ct['mapc']] = $ct['report'];
                    $col++;
                }
            }

            foreach ($model as $ct) {
                foreach ($m_pc as $pc) {
                    $ma = $pc['mapc'];
                    $ma_st = 'st_' . $pc['mapc'];
                    $ct->$ma = $ct->$ma * 6;
                    $ct->$ma_st = $ct->$ma_st * 6;
                }
                $ct->tonghs = $ct->tonghs * 6;
                $ct->ttl = $ct->luongtn * 6;
                $ct->stbhxh_dv = $ct->stbhxh_dv * 6;
                $ct->stbhyt_dv = $ct->stbhyt_dv * 6;
                $ct->stkpcd_dv = $ct->stkpcd_dv * 6;
                $ct->stbhtn_dv = $ct->stbhtn_dv * 6;
                $ct->ttbh_dv = $ct->ttbh_dv * 6;
            }

            //dd($model);
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'namns' => $model_thongtin->namns
            );

            return view('reports.nguonkinhphi.khoi.bangluong_m2')
                ->with('thongtin', $thongtin)
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_congtac', $a_congtac)
                ->with('pageTitle', 'Tổng hợp dự toán lương tại đơn vị');
        } else
            return view('errors.notlogin');
    }

    function tonghop(Request $requests)
    {
        //Kiểm tra cấp đơn vị xem đơn vị để update trường masoh hoặc masot
        if (Session::has('admin')) {
            $inputs = $requests->all();
            $model = nguonkinhphi::where('macqcq', session('admin')->madv)
                ->where('sohieu', $inputs['sohieu'])->get();
            $model_donvi = dmdonvi::where('madvbc', session('admin')->madvbc)->get();
            if (count($model) == 0) {
                return view('errors.nodata');
            }
            foreach ($model as $ct) {
                $donvi = $model_donvi->where('madv', $ct->madv)->first();
                $ct->phanloainguon = $donvi->phanloainguon;
                $ct->maphanloai = $donvi->maphanloai;
            }
            $m_dv = dmdonvi::where('madv', session('admin')->madv)->first();

            $a_A = array();
            $a_A[0] = array('tt' => '1', 'noidung' => '50% tăng thu NSĐP (không kể tăng thu tiền sử dụng đất) thực hiện 2016 so dự toán Thủ tướng Chính phủ giao năm 2016', 'sotien' => '0');
            $a_A[1] = array('tt' => '2', 'noidung' => 'Số tiết kiệm 10% chi thường xuyên dự toán năm 2017', 'sotien' => '0');
            $a_A[2] = array('tt' => '3', 'noidung' => 'Số thu được huy động từ nguồn để lại đơn vị năm 2017', 'sotien' => '0');
            $a_A[3] = array('tt' => 'a', 'noidung' => 'Nguồn huy động từ các đơn vị tự đảm bảo', 'sotien' => '0');
            $a_A[4] = array('tt' => '+', 'noidung' => 'Học phí', 'sotien' => '0');
            $a_A[5] = array('tt' => '+', 'noidung' => 'Viện phí', 'sotien' => '0');
            $a_A[6] = array('tt' => '+', 'noidung' => 'Nguồn thu khác', 'sotien' => '0');
            $a_A[7] = array('tt' => 'b', 'noidung' => 'Nguồn huy động từ các đơn vị chưa tự đảm bảo', 'sotien' => '0');
            $a_A[8] = array('tt' => '+', 'noidung' => 'Học phí', 'sotien' => '0');
            $a_A[9] = array('tt' => '+', 'noidung' => 'Viện phí', 'sotien' => '0');
            $a_A[10] = array('tt' => '+', 'noidung' => 'Nguồn thu khác', 'sotien' => '0');
            $a_A[11] = array('tt' => '4', 'noidung' => 'Nguồn thực hiện cải cách tiền lương năm 2016 chưa sử dụng hết chuyển sang 2017', 'sotien' => '0');

            //
            $a_BI = array();
            $a_BI[0] = array('tt' => '1', 'noidung' => 'Quỹ tiền lương, phụ cấp tăng thêm đối với cán bộ công chức khu vực hành chính, sự nghiệp ', 'sotien' => '0');
            $a_BI[1] = array('tt' => '', 'noidung' => 'Trong đó: nhu cầu tăng thêm đối với các đơn vị sự nghiệp tự đảm bảo ', 'sotien' => '0');
            $a_BI[2] = array('tt' => '2', 'noidung' => 'Quỹ lương, phụ cấp tăng thêm đối với cán bộ chuyên trách và công chức cấp xã', 'sotien' => '0');
            $a_BI[3] = array('tt' => '3', 'noidung' => 'Hoạt động phí tăng thêm đối với đại biểu hội đồng nhân dân các cấp', 'sotien' => '0');
            $a_BI[4] = array('tt' => '4', 'noidung' => 'Quỹ trợ cấp tăng thêm đối với cán bộ xã nghỉ việc hưởng trợ cấp hàng tháng theo NĐ 76/2017/NĐ-CP', 'sotien' => '0');
            $a_BI[5] = array('tt' => '5', 'noidung' => 'Kinh phí tăng thêm để thực hiện chế độ đối với cán bộ không chuyên trách cấp xã, thôn và tổ dân phố', 'sotien' => '0');
            $a_BI[6] = array('tt' => '6', 'noidung' => 'Kinh phí tăng thêm để thực hiện phụ cấp trách nhiệm đối với cấp ủy viên các cấp theo QĐ số 169-QĐ/TW ngày 24/6/2008', 'sotien' => '0');
            $a_BI[7] = array('tt' => '7', 'noidung' => 'Kinh phí tăng thêm thực hiện chế độ bồi dưỡng phục vụ hoạt động cấp ủy thuộc cấp tỉnh theo Quy định 3115-QĐ/VVPTW', 'sotien' => '0');

            $a_BII = array();
            $a_BII[0] = array('tt' => '1', 'noidung' => 'Kinh phí hỗ trợ chênh lệch tiền lương cho người có thu nhập thấp (NĐ17/2015/NĐ-CP) và mức lương 1,21 (6 tháng)', 'sotien' => '0');
            $a_BII[1] = array('tt' => '2', 'noidung' => 'Kinh phí tăng, giảm do điều chỉnh địa bàn vùng KTXH ĐBKK năm 2017 theo Quyết định số 131/QĐ-TTg và Quyết định số 582/QĐ-TTg của Thủ tướng Chính phủ', 'sotien' => '0');
            $a_BII[2] = array('tt' => '3', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách tinh giản biên chế năm 2017 theo NĐ số 108/2014/NĐ-CP ngày 20/11/2014 (Đối tượng đã được Bộ Nội vụ thẩm định)', 'sotien' => '0');
            $a_BII[3] = array('tt' => '4', 'noidung' => 'Nhu cầu kinh phí thực hiện chính sách nghỉ hưu trước tuổi năm 2017 theo NĐ số 26/2014/NĐ-CP ngày 09/3/2015', 'sotien' => '0');

            //Tính toán
            $a_A[1]['sotien'] = $model->sum('tietkiem');
            $model_tudb = $model->wherein('phanloainguon', array('CHITXDT', 'CTX'));
            $a_A[4]['sotien'] = $model_tudb->sum('hocphi');
            $a_A[5]['sotien'] = $model_tudb->sum('vienphi');
            $a_A[6]['sotien'] = $model_tudb->sum('nguonthu');
            $a_A[3]['sotien'] =  $a_A[4]['sotien'] +  $a_A[5]['sotien'] +  $a_A[6]['sotien'];
            //$a_BI[1]['sotien'] = $model->luongphucap;
            $a_A[8]['sotien'] = $model->sum('hocphi') - $model_tudb->sum('hocphi');
            $a_A[9]['sotien'] = $model->sum('vienphi') - $model_tudb->sum('vienphi');
            $a_A[10]['sotien'] = $model->sum('nguonthu') - $model_tudb->sum('nguonthu');
            $a_A[7]['sotien'] =  $a_A[8]['sotien'] +  $a_A[9]['sotien'] +  $a_A[10]['sotien'];
            $a_A[2]['sotien'] =  $a_A[3]['sotien'] +  $a_A[7]['sotien'];

            $model_xp = $model->where('maphanloai', 'KVXP');

            $a_BI[1]['sotien'] = $model_tudb->sum('luongphucap');
            $a_BI[2]['sotien'] = $model_xp->sum('luongphucap');
            $a_BI[0]['sotien'] = $model->sum('luongphucap') - $model_xp->sum('luongphucap');

            $a_BI[3]['sotien'] = $model->sum('daibieuhdnd');
            $a_BI[4]['sotien'] = $model->sum('nghihuu');
            $a_BI[5]['sotien'] = $model->sum('canbokct');
            $a_BI[6]['sotien'] = $model->sum('uyvien');
            $a_BI[7]['sotien'] = $model->sum('boiduong');

            $a_BII[0]['sotien'] = $model->sum('thunhapthap');
            $a_BII[1]['sotien'] = $model->sum('diaban');
            $a_BII[2]['sotien'] = $model->sum('tinhgiam');
            $a_BII[3]['sotien'] = $model->sum('nghihuusom');

            $a_TC = array(
                'A' => ($a_A[0]['sotien'] + $a_A[1]['sotien'] + $a_A[2]['sotien'] + $a_A[11]['sotien']),
                'BI' => (array_sum(array_column($a_BI, 'sotien')) - $a_BI[1]['sotien']),
                'BII' => (array_sum(array_column($a_BII, 'sotien')))
            );

            return view('reports.thongtu67.donvi.mau4a')
                ->with('model', $model)
                ->with('a_A', $a_A)
                ->with('a_BI', $a_BI)
                ->with('a_BII', $a_BII)
                ->with('a_TC', $a_TC)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Danh sách nguồn kinh phí của đơn vị');
        } else
            return view('errors.notlogin');
    }
    function getlydo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();

        $model = nguonkinhphi_khoi::select('lydo')->where('masodv', $inputs['masodv'])->first();

        die($model);
    }
}
