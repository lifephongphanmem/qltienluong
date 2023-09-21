<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\dmphanloaidonvi;
use App\dmthongtuquyetdinh;
use App\nguonkinhphi;
use App\nguonkinhphi_huyen;
use App\nguonkinhphi_khoi;
use App\nguonkinhphi_tinh;
use App\dmdonvibaocao;
use App\dmphanloaicongtac;
use App\dmphanloaict;
use App\dmphongban;
use App\dsdonviquanly;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class xemdulieu_nguonController extends Controller
{
    public function index_khoi(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $nam = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first()->namdt;
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');

            // $model_donvi = dmdonvi::select('madv', 'tendv', 'macqcq', 'maphanloai', 'phanloaitaikhoan')
            //     ->wherein('madv', function ($query) use ($madv) {
            //         $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            //     })->wherenotin('madv', function ($query) use ($madv, $nam) {
            //         $query->select('madv')->from('dmdonvi')
            //             ->whereyear('ngaydung', '<=', $nam)
            //             ->where('trangthai', 'TD')
            //             ->get();
            //     })->get();
                $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')->wherein('madv', getDonviHuyen($nam,$madv)['m_donvi'])->get();
            //dd($model_donvi);
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
            $model_phanloai = array_column($model_phanloai->toarray(), 'tenphanloai', 'maphanloai');
            foreach ($model_phanloai as $key => $key)
                $a_phanloai[$key] = $model_phanloai[$key];
            //$a_phanloai['GD'] = 'Khối Giáo Dục';
            $a_phanloai['ALL'] = '--Chọn tất cả--';
            $model_nguon = nguonkinhphi::wherein('madv', function ($query) use ($madv) {
                $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
            })->get();

            $model_nguon_khoi = nguonkinhphi_huyen::where('madv', $madv)->get();
            $model_tonghopkhoi = nguonkinhphi_khoi::where('macqcq', $madv)
                ->where('trangthai', 'DAGUI')->get();

            foreach ($model_donvi as $dv) {
                //kiểm tra xem đã tổng hợp thành dữ liệu khối  gửi lên huyện chưa?
                $nguon_khoi = $model_nguon_khoi->where('sohieu', $inputs['sohieu'])->first();
                if ($nguon_khoi != null  && $nguon_khoi->trangthai == 'DAGUI') {
                    $dv->tralai = false;
                } else {
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('sohieu', $inputs['sohieu'])->where('madv', $dv->madv)->first();
                $khoi = $model_tonghopkhoi->where('sohieu', $inputs['sohieu'])->where('madv', $dv->madv)->first();
                if ($nguon != null && $nguon->trangthai == 'DAGUI') {
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                } elseif ($khoi != null && $khoi->trangthai == 'DAGUI') {
                    $dv->masodv = $khoi->masodv;
                    $dv->trangthai = 'DAGUI';
                } else {
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthai']);
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            return view('functions.viewdata.nguonkinhphi.index')
                ->with('model', $model_donvi)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('a_phanloai', $a_phanloai)
                ->with('furl', '/nguon_kinh_phi/')
                ->with('furl_th', 'chuc_nang/tong_hop_nguon/khoi/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/khoi')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn');
        } else
            return view('errors.notlogin');
    }

    public function index_huyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $nam = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first()->namdt;
            //$ngay = date("Y-m-t", strtotime($inputs['nam'].'-'.$inputs['thang'].'-01'));
            $a_trangthai = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai', 'phanloaitaikhoan')
                ->where('macqcq', $madv)->where('madv', '<>', $madv)
                ->wherenotin('madv', function ($query) use ($madv, $nam) {
                    $query->select('madv')->from('dmdonvi')
                        ->whereyear('ngaydung', '<=', $nam)
                        ->where('trangthai', 'TD')
                        ->get();
                })
                ->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
            $model_phanloai = array_column($model_phanloai->toarray(), 'tenphanloai', 'maphanloai');
            foreach ($model_phanloai as $key => $key)
                $a_phanloai[$key] = $model_phanloai[$key];
            //$a_phanloai['GD'] = 'Khối Giáo Dục';
            $a_phanloai['ALL'] = '--Chọn tất cả--';


            $model_nguon = nguonkinhphi::where('trangthai', 'DAGUI')
                ->where('macqcq', $madv)
                ->wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->get();

            if (session('admin')->phamvitonghop == 'HUYEN') {
                // $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv','phanloaitaikhoan','maphanloai')
                //     ->where('macqcq',$madv)->where('madv','<>',$madv)
                //     ->wherenotin('madv', function ($query) use ($madv,$nam) {
                //         $query->select('madv')->from('dmdonvi')
                //             ->whereyear('ngaydung', '<=', $nam)
                //             ->where('trangthai', 'TD')
                //             ->get();
                //     })
                //     ->get();
                // $a_donvicapduoi = [];
                // //đơn vị nam=nam && macqcq=madv
                // $model_dsql = dsdonviquanly::where('nam', $nam)->where('macqcq', $madv)->get();
                // $a_donvicapduoi = array_unique(array_column($model_dsql->toarray(), 'madv'));
                // //dd($a_donvicapduoi);

                // //đơn vị có macqcq = madv (bang dmdonvi)
                // $model_dmdv = dmdonvi::where('macqcq', $madv)
                //     ->wherenotin('madv', function ($qr) use ($nam) {
                //         $qr->select('madv')->from('dsdonviquanly')->where('nam', $nam)->distinct()->get();
                //     }) //lọc các đơn vị đã khai báo trong dsdonviquanly
                //     ->where('madv', '!=', $madv) //bỏ đơn vị tổng hợp
                //     ->get();
                // $a_donvicapduoi = array_unique(array_merge(array_column($model_dmdv->toarray(), 'madv'), $a_donvicapduoi));
                // $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv', $a_donvicapduoi)->get();
                // $m_donvi = array_diff($a_donvicapduoi, array_column($model_donvitamdung->toarray(), 'madv'));
                $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')->wherein('madv', getDonviHuyen($nam,$madv)['m_donvi'])->get();

                $model_nguon = nguonkinhphi::where('trangthai', 'DAGUI')
                    ->where('macqcq', $madv)
                    ->wherein('madv', function ($query) use ($madv) {
                        $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                    })->get();
                $model_nguon_huyen = nguonkinhphi_huyen::where('trangthai', 'DAGUI')
                    ->where('macqcq', $madv)
                    ->wherein('madv', function ($query) use ($madv) {
                        $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                    })->get();
            }
            //dd($model_nguon->toarray());


            $model_nguon_khoi = nguonkinhphi_tinh::where('madv', $madv)->get();

            foreach ($model_donvi as $dv) {
                //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
                $nguon_khoi = $model_nguon_khoi->where('sohieu', $inputs['sohieu'])->first();
                if (isset($nguon_khoi) && $nguon_khoi->trangthai == 'DAGUI') {
                    $dv->tralai = false;
                } else {
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('sohieu', $inputs['sohieu'])->where('madv', $dv->madv)->first();
                $nguon_huyen = $model_nguon_huyen->where('sohieu', $inputs['sohieu'])->where('madv', $dv->madv)->first();
                if (isset($nguon) && $nguon->trangthai == 'DAGUI') {
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                } elseif (isset($nguon_huyen) && $nguon_huyen->trangthai == 'DAGUI') {
                    $dv->masodv = $nguon_huyen->masodv;
                    $dv->trangthai = 'DAGUI';
                } else {
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthai']);
            }
            if (!isset($inputs['phanloai']) || $inputs['phanloai'] != 'ALL') {
                $model_donvi = $model_donvi->where('maphanloai', $inputs['phanloai']);
            }
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            return view('functions.viewdata.nguonkinhphi.index')
                ->with('model', $model_donvi)
                ->with('inputs', $inputs)
                ->with('furl', '/nguon_kinh_phi/')
                ->with('a_trangthai', $a_trangthai)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('a_phanloai', $a_phanloai)
                ->with('furl_th', 'chuc_nang/tong_hop_nguon/huyen/')
                ->with('furl_xem', '/chuc_nang/xem_du_lieu/nguon/huyen')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn');
        } else
            return view('errors.notlogin');
    }

    public function index_tinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $madvbc = $inputs['madiaban'];
            $nam = dmthongtuquyetdinh::where('sohieu', $inputs['sohieu'])->first()->namdt;
            $dv_th = dmdonvi::where('madvbc', $madvbc)->where('phanloaitaikhoan', 'TH')->first();
            $madv = $dv_th->madv;
            // dd($dv_th);
            // dd($inputs);
            //$madvqlkv = dmdonvibaocao::where('madvbc',$madvbc)->first()->madvcq;
            $model_dvbc = dmdonvibaocao::where('level', 'H')->get();
            $a_trangthai_dl = array('ALL' => '--Chọn trạng thái dữ liệu--', 'CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $model_nguon = nguonkinhphi::where('sohieu', $inputs['sohieu'])
                ->where('trangthai', 'DAGUI')
                ->where('madvbc', $madvbc)->get();
            // dd($model_nguon);
            $model_tinh = nguonkinhphi_tinh::where('sohieu', $inputs['sohieu'])
                ->where('madvbc', $madvbc)
                ->first();
            // dd($model_tinh);
            $a_trangthai = getStatus();
            // $model_donvi = dmdonvi::select('madv', 'tendv')->where('madvbc', $madvbc)->where('phanloaitaikhoan','SD')->get();
            $a_donvicapduoi = [];
            //đơn vị nam=nam && macqcq=madv
            $model_dsql = dsdonviquanly::where('nam', $nam)->where('macqcq', $madv)->get();
            $a_donvicapduoi = array_unique(array_column($model_dsql->toarray(), 'madv'));
            //dd($a_donvicapduoi);

            //đơn vị có macqcq = madv (bang dmdonvi)
            $model_dmdv = dmdonvi::where('macqcq', $madv)
                ->wherenotin('madv', function ($qr) use ($nam) {
                    $qr->select('madv')->from('dsdonviquanly')->where('nam', $nam)->distinct()->get();
                }) //lọc các đơn vị đã khai báo trong dsdonviquanly
                ->where('madv', '!=', $madv) //bỏ đơn vị tổng hợp
                ->get();
            $a_donvicapduoi = array_unique(array_merge(array_column($model_dmdv->toarray(), 'madv'), $a_donvicapduoi));
            $model_donvitamdung = dmdonvi::where('trangthai', 'TD')->wherein('madv', $a_donvicapduoi)->get();
            $m_donvi = array_diff($a_donvicapduoi, array_column($model_donvitamdung->toarray(), 'madv'));
            $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')->wherein('madv', $m_donvi)->get();
            // dd($model_donvi);
            foreach ($model_donvi as $dv) {
                $nguon = $model_nguon->where('madv', $dv->madv)->first();
                if (isset($nguon)) {
                    $dv->trangthai = $nguon->trangthai;
                    $dv->masodv = $nguon->masodv;
                    $dv->masot = $nguon->masot;
                } else {
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = NULL;
                    $dv->masot = null;
                }
                if (isset($model_tinh)) {
                    // $dv->masot = $model_tinh->masodv;
                    $dv->dulieu = isset($nguon) ? $nguon->trangthai : 'CHOGUI';
                } else {
                    $dv->dulieu = 'CHOGUI';
                }
            }
            // dd($model_donvi);
            // dd($inputs);
            if (!isset($inputs['trangthai']) || $inputs['trangthai'] != 'ALL') {
                // $model_donvi = $model_donvi->where('trangthai',$inputs['trangthai']);
                $model_donvi = $model_donvi->where('dulieu', $inputs['trangthai'])->where('trangthai', $inputs['trangthai']);
            }
            // dd($model_donvi);
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            return view('functions.tonghopnguon.index_tinh')
                ->with('model', $model_donvi)
                ->with('a_trangthai', $a_trangthai)
                ->with('a_trangthai_dl', $a_trangthai_dl)
                ->with('soluong', $model_nguon->unique('madv')->count() . '/' . $model_donvi->count('madv'))
                ->with('madvbc', $madvbc)
                ->with('inputs', $inputs)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('a_dvbc', array_column($model_dvbc->toArray(), 'tendvbc', 'madvbc'))
                ->with('furl', '/chuc_nang/tong_hop_nguon/')
                ->with('furl_dv', '/nguon_kinh_phi/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp nguồn kinh phí');
        } else
            return view('errors.notlogin');
    }

    function danhsach(Request $request)
    {

        if (Session::has('admin')) {
            $inputs = $request->all();
            $madv = session('admin')->madv;
            $nam = dmthongtuquyetdinh::where('sohieu', $inputs['sohieuds'])->first()->namdt;
            $a_trangthai = array('CHOGUI' => 'Chưa gửi dữ liệu', 'DAGUI' => 'Đã gửi dữ liệu');
            $model_donvi = dmdonvi::select('madv', 'tendv', 'maphanloai')
                ->where('macqcq', $madv)->where('madv', '<>', $madv)
                ->wherenotin('madv', function ($query) use ($madv, $nam) {
                    $query->select('madv')->from('dmdonvi')
                        ->whereyear('ngaydung', '<=', $nam)
                        ->where('trangthai', 'TD')
                        ->get();
                })->get();
            $model_phanloai = dmphanloaidonvi::wherein('maphanloai', array_column($model_donvi->toarray(), 'maphanloai'))->get();
            $model_phanloai = array_column($model_phanloai->toarray(), 'tenphanloai', 'maphanloai');
            foreach ($model_phanloai as $key => $key)
                $a_phanloai[$key] = $model_phanloai[$key];
            //$a_phanloai['GD'] = 'Khối Giáo Dục';
            $a_phanloai['ALL'] = '--Chọn tất cả--';


            $model_nguon = nguonkinhphi::where('trangthai', 'DAGUI')
                ->where('macqcq', $madv)
                ->wherein('madv', function ($query) use ($madv) {
                    $query->select('madv')->from('dmdonvi')->where('macqcq', $madv)->where('madv', '<>', $madv)->get();
                })->get();

            $model_nguon_khoi = nguonkinhphi_tinh::where('madv', $madv)->get();

            foreach ($model_donvi as $dv) {
                //kiểm tra xem đã tổng hợp thành dữ liệu huyện gửi lên tỉnh chưa?
                $nguon_khoi = $model_nguon_khoi->where('sohieu', $inputs['sohieuds'])->first();
                if ($nguon_khoi != null && $nguon_khoi->trangthai == 'DAGUI') {
                    $dv->tralai = false;
                } else {
                    $dv->tralai = true;
                }

                $nguon = $model_nguon->where('sohieu', $inputs['sohieuds'])->where('madv', $dv->madv)->first();
                if ($nguon != null && $nguon->trangthai == 'DAGUI') {
                    $dv->masodv = $nguon->masodv;
                    $dv->trangthai = 'DAGUI';
                } else {
                    $dv->trangthai = 'CHOGUI';
                    $dv->masodv = null;
                }
            }

            if (!isset($inputs['trangthaids']) || $inputs['trangthaids'] != 'ALL') {
                $model_donvi = $model_donvi->where('trangthai', $inputs['trangthaids']);
            }
            //  dd($model_donvi->toarray());
            $m_dv = dmdonvi::where('madv', $madv)->first();
            if (isset($inputs['excel'])) {
                Excel::create('THluong', function ($excel) use ($model_donvi, $a_trangthai, $m_dv, $inputs) {
                    $excel->sheet('New sheet', function ($sheet) use ($model_donvi, $a_trangthai, $m_dv, $inputs) {
                        $sheet->loadView('reports.nguonkinhphi.huyen.danhsach')
                            ->with('model', $model_donvi)
                            ->with('a_trangthai', $a_trangthai)
                            ->with('m_dv', $m_dv)
                            ->with('furl', '/chuc_nang/tong_hop_luong/')
                            ->with('pageTitle', 'THluong');
                        $sheet->setAutoSize(false);
                        $sheet->setFontFamily('Tahoma');
                        $sheet->setFontBold(false);
                    });
                })->download('xls');
            } else {
                return view('reports.nguonkinhphi.huyen.danhsach')
                    ->with('model', $model_donvi)
                    ->with('a_trangthai', $a_trangthai)
                    ->with('m_dv', $m_dv)
                    ->with('furl', '/chuc_nang/tong_hop_luong/')
                    ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
            }
        } else
            return view('errors.notlogin');
    }
    public function edit(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
        } else
            return view('errors.notlogin');
    }

    public function getKhoiToCongTac(Request $request)
    {
        $inputs = $request->all();
        $model = dmphongban::where('madv', $inputs['madv'])->get();
        $result['status'] = 'success';
        $result['message'] = '<div id="row_khoitocongtac" class="row"><div class="col-md-12">';
        $result['message'] .= '<label>Khối/Tổ công tác</label>';
        $result['message'] .= '<select class="form-control select2me" name="mapb">';
        $result['message'] .= '<option value="ALL">Tất cả</option>';
        foreach ($model as $val) {
            $result['message'] .= '<option value="' . $val->mapb . '">' . $val->tenpb . '</option>';
        }
        $result['message'] .= '</select></div></div>';
        die(json_encode($result));
    }
}
