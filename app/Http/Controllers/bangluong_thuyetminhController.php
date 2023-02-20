<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\bangluong_thuyetminh;
use App\bangthuyetminh;
use App\bangthuyetminh_chitiet;
use App\dmnguonkinhphi;
use App\dmphanloaict;
use App\dmphucap_donvi;
use App\hosocanbo;
use App\Http\Controllers\dataController as data;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\tonghopluong_donvi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class bangluong_thuyetminhController extends Controller
{
    function ThuyetMinhChiTiet(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_tm = bangthuyetminh::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->first();

            $model = bangthuyetminh_chitiet::where('mathuyetminh', $model_tm->mathuyetminh)->orderby('tanggiam', 'desc')->get();

            $model_tonghop = tonghopluong_donvi::where('madv', session('admin')->madv)
                ->where('thang', $model_tm->thang)->where('nam', $model_tm->nam)->first();
            $inputs['thaotac'] = isset($model_tonghop) ? false : true;
            $inputs['mathuyetminh'] = $model_tm->mathuyetminh ?? null;
            $a_nguonkp =  array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $inputs['chenhlech'] =  getDbl($model->where('tanggiam', 'TANG')->sum('sotien')) - getDbl($model->where('tanggiam', 'GIAM')->sum('sotien'));
            return view('manage.bangluong.thuyetminh.index')
                ->with('model', $model)
                ->with('model_tm', $model_tm)
                ->with('a_nguonkp', $a_nguonkp)
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Danh sách bảng lương');
        } else
            return view('errors.notlogin');
    }

    function TaoThuyetMinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $inputs['phanloaibl'] = ['TRUYLINH', 'BANGLUONG']; //làm trc
            $model = bangthuyetminh::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->first();
            if ($model != null) {
                return view('errors.trungdulieu')
                    ->with('furl', '/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam'])
                    ->with('pageTitle', 'Thông báo');
            }
            //
            //Tạo bảng thuyết minh
            $mathuyetminh = session('admin')->madv . '_' . getdate()[0];;
            $model_thuyetminh = new bangthuyetminh();
            $model_thuyetminh->mathuyetminh = $mathuyetminh;
            $model_thuyetminh->madv = session('admin')->madv;
            $model_thuyetminh->noidung = 'Thuyết minh chi tiết bảng lương tháng ' . $inputs['thang'] . ' năm ' . $inputs['nam'];
            $model_thuyetminh->phanloai = $inputs['phanloai'];
            $model_thuyetminh->thang = $inputs['thang'];
            $model_thuyetminh->nam = $inputs['nam'];
            //Tính toán chi tiết
            //Lấy danh sách bảng lương, truy lĩnh trong tháng
            $m_bangluong = bangluong::select('madv', 'thang', 'mabl', 'manguonkp', 'nam')
                ->where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])
                ->wherein('phanloai', $inputs['phanloaibl'])
                ->get();
            $m_bangluong_chitiet = (new dataController)->getBangluong_ct_ar($inputs['thang'], array_column($m_bangluong->toArray(), 'mabl'));

            //dd($m_bangluong_chitiet);
            //lấy danh sách bản lương, truy lĩnh tháng trc
            if ($m_bangluong != null && $inputs['thang'] == '01') {
                $thang = '12';
                $nam = str_pad($inputs['nam'] - 1, 4, '0', STR_PAD_LEFT);
            } else {
                $thang = str_pad($inputs['thang'] - 1, 2, '0', STR_PAD_LEFT);
                $nam =  $inputs['nam'];
            }

            $m_bangluong_trc = bangluong::select('madv', 'thang', 'mabl', 'manguonkp', 'nam')
                ->where('madv', session('admin')->madv)
                ->where('thang', $thang)
                ->where('nam', $nam)
                ->wherein('phanloai', $inputs['phanloaibl'])
                ->get();

            if (count($m_bangluong_trc) >= 0) {
                $m_bangluong_trc_chitiet = (new dataController)->getBangluong_ct_ar($thang, array_column($m_bangluong_trc->toArray(), 'mabl'));
            } else {
                $m_bangluong_trc_chitiet = [];
            }

            if (count($m_bangluong_chitiet) > 0 && count($m_bangluong_trc_chitiet) > 0) {
                if ($inputs['phanloai'] == 'CANBO') {
                    $a_thuyetminh = $this->ThuyetMinh_CanBo($m_bangluong_chitiet, $m_bangluong_trc_chitiet, $mathuyetminh);
                } else {
                    $a_thuyetminh = $this->ThuyetMinh_PhuCap($m_bangluong_chitiet, $m_bangluong_trc_chitiet, $mathuyetminh, session('admin')->madv);
                }
                //dd($a_thuyetminh);
                //Tạo thuyết minh
                bangthuyetminh_chitiet::insert($a_thuyetminh);
                $model_thuyetminh->save();
            } else {
                //Tạo thuyết minh rỗng
                $model_thuyetminh->save();
            }
            return redirect('/chuc_nang/bang_luong/ThuyetMinhChiTiet?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
        } else
            return view('errors.notlogin');
    }

    function ThuyetMinh_CanBo($m_bangluong, $m_bangluong_trc, $mathuyetminh)
    {
        $a_kq = [];

        //Lấy danh sách cán bộ theo mã cán bộ, họ tên, mã công tác
        $a_canbo = $m_bangluong->keyby(function ($ct) {
            return $ct['macanbo'] . '_' . $ct['mact'];
        })->map(function ($data) {
            return collect($data->toArray())
                ->only(['macanbo', 'tencanbo', 'mact'])
                ->all();
        })->toarray();

        $a_canbo_trc = $m_bangluong_trc->keyby(function ($ct) {
            return $ct['macanbo'] . '_' . $ct['mact'];
        })->map(function ($data) {
            return collect($data->toArray())
                ->only(['macanbo', 'tencanbo', 'mact'])
                ->all();
        })->toarray();
        $a_canbo_giam = array_diff_key($a_canbo_trc, $a_canbo);
        $a_canbo_them = array_diff_key($a_canbo, $a_canbo_trc);

        $stt = 1; //gán số thứ tự

        //Cán bộ giảm trong tháng
        $m_bangluong_trc = $m_bangluong_trc->keyby(function ($ct) {
            return $ct['macanbo'] . '_' . $ct['mact'];
        });

        foreach ($a_canbo_giam as $key => $val) {
            $thongtin = $m_bangluong_trc->where('macanbo', $val['macanbo'])->where('mact', $val['mact']);
            $a_kq[] = [
                'stt' => $stt++,
                'mathuyetminh' => $mathuyetminh,
                'noidung' => $val['tencanbo'],
                'tanggiam' => 'GIAM',
                'sotien' => $thongtin->sum('ttl'),
            ];
        }
        //Cán bộ tăng thêm trong tháng
        $m_bangluong = $m_bangluong->keyby(function ($ct) {
            return $ct['macanbo'] . '_' . $ct['mact'];
        });

        foreach ($a_canbo_them as $key => $val) {
            $thongtin = $m_bangluong->where('macanbo', $val['macanbo'])->where('mact', $val['mact']);
            $a_kq[] = [
                'stt' => $stt++,
                'mathuyetminh' => $mathuyetminh,
                'noidung' => $val['tencanbo'],
                'tanggiam' => 'TANG',
                'sotien' => $thongtin->sum('ttl'),
            ];
            //xóa cán bộ ra để tính toán
            $m_bangluong->forget($key);
        }

        //Tính toán số liệu
        foreach ($m_bangluong as $key => $val) {
            $chenhlech = $val['luongtn'] - ($m_bangluong_trc[$key]['luongtn'] ?? $val['luongtn']); //nếu ko tìm thấy thì gan = 0 ==>bỏ qua
            if ($chenhlech > 0) {
                //Tăng
                $a_kq[] = [
                    'stt' => $stt++,
                    'mathuyetminh' => $mathuyetminh,
                    'noidung' => $val['tencanbo'],
                    'tanggiam' => 'TANG',
                    'sotien' => $chenhlech,
                ];
            } elseif ($chenhlech < 0) {
                //giảm
                $a_kq[] = [
                    'stt' => $stt++,
                    'mathuyetminh' => $mathuyetminh,
                    'noidung' => $val['tencanbo'],
                    'tanggiam' => 'GIAM',
                    'sotien' => -$chenhlech,
                ];
            }
        }
        return $a_kq;
    }

    function ThuyetMinh_PhuCap($m_bangluong, $m_bangluong_trc, $mathuyetminh, $madv)
    {
        $a_kq = [];
        //Lấy danh sách phụ cấp tại đơn vị
        $model_pc = dmphucap_donvi::where('madv', $madv)->where('phanloai', '<', '3')->get();
        $stt = 1;
        foreach ($model_pc as $ct) {
            $mapc_st = 'st_' . $ct->mapc;
            $chenhlech = $m_bangluong->sum($mapc_st) - $m_bangluong_trc->sum($mapc_st);
            if ($chenhlech > 0) {
                //Tăng
                $a_kq[] = [
                    'stt' => $stt++,
                    'mathuyetminh' => $mathuyetminh,
                    'noidung' => $ct->tenpc,
                    'tanggiam' => 'TANG',
                    'sotien' => $chenhlech,
                ];
            } elseif ($chenhlech < 0) {
                //giảm
                $a_kq[] = [
                    'stt' => $stt++,
                    'mathuyetminh' => $mathuyetminh,
                    'noidung' => $ct->tenpc,
                    'tanggiam' => 'GIAM',
                    'sotien' => -$chenhlech,
                ];
            }
        }
        //Duyệt phụ cấp rồi so sánh
        return $a_kq;
    }
    function LuuThuyetMinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['sotien'] = getDbl($inputs['sotien']);
            //dd($inputs);
            $m_thuyetminh = bangthuyetminh::where('mathuyetminh', $inputs['mathuyetminh'])->first();
            $model = bangthuyetminh_chitiet::where('id', $inputs['id'])->first();
            unset($inputs['id']);
            if ($model == null) {
                bangthuyetminh_chitiet::create($inputs);
            } else {
                $model->update($inputs);
            }

            return redirect('/chuc_nang/bang_luong/ThuyetMinhChiTiet?thang=' . $m_thuyetminh->thang . '&nam=' . $m_thuyetminh->nam);
        } else
            return view('errors.notlogin');
    }

    function LayThuyetMinh(Request $request)
    {
        $result = array(
            'status' => 'fail',
            'message' => 'error',
        );
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $model = bangthuyetminh_chitiet::where('id', $inputs['id'])->first();
        die(json_encode($model));
    }

    function XoaThuyetMinhChiTiet(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangthuyetminh_chitiet::where('id', $inputs['id'])->first();
            $m_thuyetminh = bangthuyetminh::where('mathuyetminh', $model->mathuyetminh)->first();
            $model->delete();
            return redirect('/chuc_nang/bang_luong/ThuyetMinhChiTiet?thang=' . $m_thuyetminh->thang . '&nam=' . $m_thuyetminh->nam);
        } else
            return view('errors.notlogin');
    }

    function XoaThuyetMinh($id)
    {
        if (Session::has('admin')) {            
            $m_thuyetminh = bangthuyetminh::where('id', $id)->first();
            bangthuyetminh_chitiet::where('mathuyetminh', $m_thuyetminh->mathuyetminh)->delete();            $m_thuyetminh->delete();
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $m_thuyetminh->thang . '&nam=' . $m_thuyetminh->nam);
        } else
            return view('errors.notlogin');
    }
}
