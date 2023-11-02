<?php

namespace App\Http\Controllers;

use App\bangluong;
use App\dmthuetncn;
use App\dmthuetncn_ct;
use App\hosophucap;
use App\hosothoicongtac;
use App\Http\Controllers\dataController as data;
use App\bangluong_truc;
use App\bangthuyetminh;
use App\dmchucvucq;
use App\dmdonvi;
use App\dmnguonkinhphi;
use App\dmphanloaicongtac;
use App\dmphanloaicongtac_baohiem;
use App\dmphanloaict;
use App\dmphongban;
use App\dmphucap;
use App\dmphucap_donvi;
//use App\dmphucap_thaisan;
use App\dmtieumuc_default;
use App\hosocanbo;
use App\hosocanbo_kiemnhiem;
use App\hosotamngungtheodoi;
use App\hosotruc;
use App\hosotruylinh;
use App\ngachluong;
use App\nguonkinhphi_dinhmuc;
use App\nguonkinhphi_dinhmuc_ct;
use App\tonghopluong_donvi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class bangluongController extends Controller
{
    function chitra(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            foreach (getNguonTruyLinh() as $k => $v) {
                $inputs['manguonkp'] = $k;
                $inputs['luongcb'] = $v;
            }
            $inputs['furl'] = '/chuc_nang/bang_luong/';
            $inputs['furl_ajax'] = '/ajax/bang_luong/';
            $inputs['dinhmuc'] = 0;
            $dinhmuc = nguonkinhphi_dinhmuc::where('manguonkp', $inputs['manguonkp'])
                ->where('madv', session('admin')->madv)->first();
            $maso = isset($dinhmuc) ? $dinhmuc->maso : '';
            $dinhmuc_ct = nguonkinhphi_dinhmuc_ct::where('maso', $maso)->get();

            if (count($dinhmuc_ct) > 0) {
                $inputs['dinhmuc'] = 1;
                $inputs['luongcb'] = $dinhmuc->luongcoban;
            }

            //$m_nguonkp = array_column(dmnguonkinhphi::all()->toArray(), 'tennguonkp', 'manguonkp');
            $m_nguonkp = dmnguonkinhphi::all();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();
            $model = bangluong::where('madv', session('admin')->madv)->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->get();
            //bảng lương mẫu
            $model_bl = bangluong::where('madv', session('admin')->madv)->where('phanloai', 'BANGLUONG')->orderby('nam')->orderby('thang')->get();
            $model_tonghop = tonghopluong_donvi::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->first();
            $thaotac = isset($model_tonghop) ? false : true;
            $inputs['thaotac'] = $thaotac;

            $a_pc = array(
                'heso', 'vuotkhung', 'pctnn', 'pccovu', 'pcud61', 'pcudn', 'hesott',
                'pccv', 'pctaicu', 'hesopc', 'pcthni'
            ); //do các loại phụ cấp lưu lại

            $m_nguonkp_bl = $m_nguonkp->wherein('manguonkp', a_unique(array_column($model->toarray(), 'manguonkp')));
            //$a_phucap_trichnop = dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->get();
            $a_phucap_trichnop = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->get()->toArray(), 'tenpc', 'mapc');
            $a_phucap = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_pc)->get()->toArray(), 'tenpc', 'mapc');
            $a_phucaplst = array_column(dmphucap_donvi::where('madv', session('admin')->madv)->wherein('phanloai', ['0', '2'])->get()->toArray(), 'tenpc', 'mapc');
            $m_linhvuc = getLinhVucHoatDong(false);
            $m_donvi = dmdonvi::where('madv', session('admin')->madv)->first();
            //thêm mới thuyết minh vào bảng lương
            $model_thuyetminh = bangthuyetminh::where('madv', session('admin')->madv)
                ->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->get();
            foreach ($model_thuyetminh as $thuyetminh) {
                $thuyetminh->phanloai = 'THUYETMINH';
                $model->add($thuyetminh);
            }
            return view('manage.bangluong.index')
                //->with('furl', '/chuc_nang/bang_luong/')
                //->with('furl_ajax', '/ajax/bang_luong/')
                ->with('model', $model)
                ->with('model_bl', $model_bl)
                ->with('inputs', $inputs)
                ->with('m_linhvuc', $m_linhvuc)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('m_nguonkp', array_column($m_nguonkp->toArray(), 'tennguonkp', 'manguonkp'))
                ->with('a_nguonkp_bl', array_column($m_nguonkp_bl->toArray(), 'tennguonkp', 'manguonkp'))
                ->with('a_phucap', $a_phucap)
                ->with('a_phucaplst', $a_phucaplst)
                ->with('a_phucap_trichnop', array_merge(array('ALL' => 'Tất cả các loại phụ cấp'), $a_phucap_trichnop))
                ->with('a_phanloai', getPhanLoaiBangLuong())
                ->with('phucaploaitru', $m_donvi->phucaploaitru)
                ->with('phucapluusotien', $m_donvi->phucapluusotien)
                ->with('pageTitle', 'Danh sách bảng lương');
        } else
            return view('errors.notlogin');
    }

    function capnhat(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl_cu = $inputs['mabl_capnhat'];
            $model = bangluong::where('mabl', $mabl_cu)->first();
            //dd($model);
            $inputs['luongcoban'] = $model->luongcoban;
            $mabl = session('admin')->madv . '_' . getdate()[0];
            $inputs['mabl'] = $mabl;
            $inputs['thang'] = $model->thang;
            $inputs['nam'] = $model->nam;
            $inputs['madv'] = $model->madv;
            $inputs['luongcoban'] = $model->luongcoban;
            $inputs['phanloai'] = $model->phanloai;
            $inputs['manguonkp'] = $model->manguonkp;
            $inputs['linhvuchoatdong'] = $model->linhvuchoatdong;
            $inputs['phucaploaitru'] = $model->phucaploaitru;
            $inputs['phucapluusotien'] = $model->phucapluusotien;

            $dinhmuc = nguonkinhphi_dinhmuc::join('nguonkinhphi_dinhmuc_ct', 'nguonkinhphi_dinhmuc.maso', '=', 'nguonkinhphi_dinhmuc_ct.maso')
                ->select('nguonkinhphi_dinhmuc_ct.mapc')
                ->where('nguonkinhphi_dinhmuc.manguonkp', $model->manguonkp)->where('nguonkinhphi_dinhmuc.madv', session('admin')->madv)
                ->get()->count();
            (new data())->destroyBangluong_ct($inputs['thang'], $mabl_cu);
            /*
            $delchitiet = (new Delbangluong_ct($mabl_cu, $inputs['thang']))->delay(Carbon::now()->addSecond(10));
            //$delchitiet->delay(Carbon::now()->addSecond(10));
            dispatch($delchitiet);
            */
            //dd($dinhmuc);
            //chạy trc hàm kiểm tra do trong hàm ko return view() đc
            $model_phucap = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'tenpc', 'thaisan', 'nghiom', 'dieudong', 'thuetn', 'tapsu')
                ->where('madv', session('admin')->madv)
                ->where('phanloai', '<', '3')
                ->wherenotin('mapc', array_merge(['hesott'], explode(',', $inputs['phucaploaitru'])))->get();
            if (count(SapXepPhuCap($model_phucap)) == 0) {
                return view('errors.data_error')
                    ->with('message', 'Phụ cấp cơ sở bị lập lại. Bạn hãy kiểm tra lại danh mục phụ cấp trước khi tính lương')
                    ->with('furl', '/chuc_nang/bang_luong/chi_tra?thang=' . date('m') . '&nam=' . date('Y'));
            }
            if ($dinhmuc > 0) {
                $this->tinhluong_dinhmuc($inputs);
            } else {
                $this->tinhluong_khongdinhmuc($inputs);
            }

            //Tạo bảng lương
            $model->mabl = $mabl;
            $model->save();
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
            //return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'] . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function capnhat_nkp(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if ($model->manguonkp != $inputs['manguonkp']) {
                $model->manguonkp = $inputs['manguonkp'];
                $model->save();
                switch ($model->thang) {
                    case '01': {
                            DB::statement("Update bangluong_ct_01 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '02': {
                            DB::statement("Update bangluong_ct_02 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '03': {
                            DB::statement("Update bangluong_ct_03 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '04': {
                            DB::statement("Update bangluong_ct_04 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '05': {
                            DB::statement("Update bangluong_ct_05 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '06': {
                            DB::statement("Update bangluong_ct_06 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '07': {
                            DB::statement("Update bangluong_ct_07 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '08': {
                            DB::statement("Update bangluong_ct_08 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '09': {
                            DB::statement("Update bangluong_ct_09 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '10': {
                            DB::statement("Update bangluong_ct_10 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '11': {
                            DB::statement("Update bangluong_ct_11 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                    case '12': {
                            DB::statement("Update bangluong_ct_12 set manguonkp='" . $inputs['manguonkp'] . "' where mabl ='" . $model->mabl . "'");
                            break;
                        }
                }
            }
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $model->thang . '&nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }
    //Insert + update bảng lương
    function store(Request $request)
    {
        $inputs = $request->all();
        // dd($inputs);
        $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
        $model = bangluong::where('mabl', $inputs['mabl'])->first();
        // dd($model);
        if (isset($model)) {
            //update
            $inputs['phucaploaitru'] = $model->phucaploaitru;
            $inputs['phucapluusotien'] = $model->phucapluusotien;
            $model->update($inputs);
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
        } else {
            if (isset($inputs['phucaploaitru'])) {
                $inputs['phucaploaitru'] = implode(',', $inputs['phucaploaitru']);
            } else {
                $inputs['phucaploaitru'] = '';
            }
            if (isset($inputs['phucapluusotien'])) {
                $inputs['phucapluusotien'] = implode(',', $inputs['phucapluusotien']);
            } else {
                $inputs['phucapluusotien'] = '';
            }

            if (!isset($inputs['trungbangluong'])) {
                $model_chk = bangluong::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])
                    ->where('phanloai', 'BANGLUONG')
                    ->where('manguonkp', $inputs['manguonkp'])
                    ->where('linhvuchoatdong', $inputs['linhvuchoatdong'])
                    ->where('madv', session('admin')->madv)
                    ->first();

                if (isset($model_chk)) {
                    return view('errors.trungbangluong');
                }
            }

            $madv = session('admin')->madv;
            $inputs['mabl'] = $madv . '_' . getdate()[0];
            $inputs['madv'] = $madv;
            $inputs['phanloai'] = 'BANGLUONG';
            $inputs['linhvuchoatdong'] = !isset($inputs['linhvuchoatdong']) ? 'QLNN' : $inputs['linhvuchoatdong'];
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            dmdonvi::where('madv', $madv)->update(['phucaploaitru' => $inputs['phucaploaitru'], 'phucapluusotien' => $inputs['phucapluusotien']]);
            //chạy trc hàm kiểm tra do trong hàm ko return view() đc
            $model_phucap = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'tenpc', 'thaisan', 'nghiom', 'dieudong', 'thuetn', 'tapsu')
                ->where('madv', session('admin')->madv)
                ->where('phanloai', '<', '3')
                ->wherenotin('mapc', array_merge(['hesott'], explode(',', $inputs['phucaploaitru'])))->get();
            $a_pc = SapXepPhuCap($model_phucap);
            //dd($model_phucap);
            if (isset($a_pc['trangthai'])) {
                $a_tenpc = array_column($model_phucap->toarray(), 'tenpc', 'mapc');
                //dd($a_tenpc);
                $tenpc = '';
                foreach ($a_pc['mapc'] as $pc) {
                    $tenpc .= '- ' . ($a_tenpc[$pc] . ';</br>');
                }
                return view('errors.data_error')
                    ->with('message', 'Phụ cấp cơ sở: </br> <b>' . $tenpc . '</b> có công thức chưa phù hợp. Bạn hãy thiết lập lại công thức cho các phụ cấp trước khi tính lương')
                    ->with('furl', '/chuc_nang/bang_luong/chi_tra?thang=' . date('m') . '&nam=' . date('Y'));
            }

            if (boolval($inputs['dinhmuc'])) {
                //dd($inputs['dinhmuc']);
                $this->tinhluong_dinhmuc($inputs);
            } else {
                $this->tinhluong_khongdinhmuc($inputs);
            }
        }

        //Tạo bảng lương
        bangluong::create($inputs);
        return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
    }
    //cán bộ đi học nộp 100% bảo hiểm, cán bộ tập sự nộp bảo hiểm theo lương
    //làm hàm lấy ds cán bộ đang công tác: notin thôi công tac and notin ngaycongtac < ngày cuối tháng lương
    //xác định mức lương cơ bản cán bộ (phân loại công tác - nguồn kinh phí - phụ cấp)
    //lấy theo mức lương mặc định theo nguồn kinh phí

    //lấy theo nguồn => tạo theo phân loại công tác - mức phụ cấp (mặc định theo nguồn)
    /*foreach(phanloaicongtac){
        foreach(phucap){}
    }
    */
    function tinhluong_dinhmuc($inputs)
    {
        $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01');
        $m_tamngung = hosotamngungtheodoi::wherein('maphanloai', ['THAISAN', 'KHONGLUONG', 'DAINGAY'])
            ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)
            ->where('madv', $inputs['madv'])->get();
        $a_thaisan = $m_tamngung->where('maphanloai', 'THAISAN')->keyBy('macanbo')->toarray();
        $a_khongluong = array_column($m_tamngung->where('maphanloai', 'KHONGLUONG')->toarray(), 'macanbo');
        $a_daingay = array_column($m_tamngung->where('maphanloai', 'DAINGAY')->toarray(), 'macanbo');
        //Lấy thông tin nghỉ ốm nghỉ phép tháng trc
        $m_nghi = hosotamngungtheodoi::select('songaycong', 'songaynghi', 'macanbo', 'maphanloai')
            ->where('madv', $inputs['madv'])->wherein('maphanloai', ['NGHIPHEP', 'NGHIOM', 'DUONGSUC'])
            ->whereYear('ngaythanhtoan', $inputs['nam'])->whereMonth('ngaythanhtoan', $inputs['thang'])
            ->get();
        $a_duongsuc = $m_nghi->where('maphanloai', 'DUONGSUC')->keyBy('macanbo')->toarray();
        $a_nghiphep = $m_nghi->wherein('maphanloai', ['NGHIPHEP', 'NGHIOM'])->keyBy('macanbo')->toarray();

        //ds cán bộ
        $model_phucap = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'tenpc', 'thaisan', 'nghiom', 'dieudong', 'thuetn', 'tapsu')
            ->where('madv', session('admin')->madv)
            ->where('phanloai', '<', '3')
            ->wherenotin('mapc', array_merge(['hesott'], explode(',', $inputs['phucaploaitru'])))->get();

        $a_th = array_merge(
            array('macanbo', 'macvcq', 'mapb', 'manguonkp', 'mact', 'baohiem', 'pthuong'),
            array_column($model_phucap->toarray(), 'mapc')
        );

        $model_canbo_kn = hosocanbo_kiemnhiem::select(array_merge($a_th, array('phanloai')))->where('madv', session('admin')->madv)->get();
        $a_mact_ht = a_unique(array_column($model_canbo_kn->toarray(), 'mact'));

        $ngaycuoithang = Carbon::create($inputs['nam'], $inputs['thang'] + 1, 0)->toDateString();
        $ngaydauthang = $ngaylap->toDateString();
        //ds cán bộ thôi công tác
        $a_cbn = hosothoicongtac::select('macanbo')->where('madv', $inputs['madv'])
            ->where(function ($qr) use ($ngaycuoithang) {
                $qr->where('ngaynghi', '<=', $ngaycuoithang)->orWhereNull('ngaynghi');
            })->get()->toarray();
        $a_th = array_merge(
            array(
                'stt', 'tencanbo', 'msngbac', 'bac', 'theodoi',
                'khongnopbaohiem', 'ngaytu', 'tnntungay', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv',
                'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'ngaybc', 'lvhd', 'mucluongbaohiem'
            ),
            $a_th
        );

        $m_cb = hosocanbo::select($a_th)->where('madv', $inputs['madv'])->wherenotin('macanbo', $a_cbn)->get();
        if (isset($inputs['capnhatnangluong'])) {
            $m_hs = hosophucap::where('madv', session('admin')->madv)
                ->wherenotin('mapc', ['heso', 'vuotkhung', 'pctnn'])
                ->where(function ($qr) use ($ngaydauthang) {
                    $qr->where(function ($q) use ($ngaydauthang) {
                        $q->where('ngaytu', '<=', $ngaydauthang)
                            ->where('ngayden', '>', $ngaydauthang);
                    })->orwhere('ngayden', '<', $ngaydauthang);
                })->get();
            $m_cb = (new data())->getCanBo_bl($m_cb, $m_hs, $ngaydauthang);
        }

        foreach ($m_cb as $canbo) {
            //Dùng tìm kiếm các bộ nào phù hợp. Do lvhd là mảng nên pải lọc
            $a_lv = explode(',', $canbo->lvhd);
            if (in_array($inputs['linhvuchoatdong'], $a_lv) || $canbo->lvhd == null || $canbo->lvhd == '') {
                $canbo->lvhd = $inputs['linhvuchoatdong'];
            }

            $a_nguon = explode(',', $canbo->manguonkp);
            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if (in_array($inputs['manguonkp'], $a_nguon) || $canbo->manguonkp == null || $canbo->manguonkp == '') {
                $canbo->manguonkp = $inputs['manguonkp'];
            }

            if (!in_array($canbo->mact, $a_mact_ht)) {
                $a_mact_ht[] = $canbo->mact;
            }
        }
        $a_ct = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');
        //$model_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get();

        //Không tính truy lĩnh
        $a_goc = array('heso', 'vuotkhung', 'pccv'); //mảng phụ cấp làm công thức tính
        //=> lấy phụ cấp theo nguồn chứ ko pải phụ cấp toàn hệ thống
        //$m_dm = nguonkinhphi_dinhmuc::where('madv', session('admin')->madv)->where('manguonkp', $inputs['manguonkp'])->first();
        //$model_dimhmuc = nguonkinhphi_dinhmuc_ct::where('maso', $m_dm->maso)->wherenotin('mapc', explode(',', $inputs['phucaploaitru']))->get();

        $m_dmng = nguonkinhphi_dinhmuc::where('madv', session('admin')->madv)->where('manguonkp', $inputs['manguonkp'])->get();
        $m_dmng_ct = nguonkinhphi_dinhmuc_ct::wherein('maso', array_column($m_dmng->toarray(), 'maso'))
            ->wherenotin('mapc', explode(',', $inputs['phucaploaitru']))->get();
        //dd($m_dmng);
        //phân loại công tác nào ko có trong định mức => gán lương cơ bản = 0
        $a_nkp_dm = array();
        $a_nkp_tinhtheodm = array();
        foreach ($m_dmng as $ng) {
            $dmng_ct = $m_dmng_ct->where('maso', $ng->maso);
            $chitiet = array_column($dmng_ct->toarray(), 'luongcoban', 'mapc');
            $tinhtheodm = array_column($m_dmng_ct->where('maso', $ng->maso)->toarray(), 'luongcoban', 'mapc');
            if ($ng->mact == 'ALL') {
                //gán mảng nguồn từ đầu
                $a_nkp_dm = array();
                foreach ($a_mact_ht as $ct) {
                    $a_nkp_dm[$ct] = $chitiet;
                    $a_nkp_tinhtheodm[$ct] = $tinhtheodm;
                }
                goto dinhmucnguon;
            }

            foreach (explode(',', $ng->mact) as $ct) {
                $a_nkp_dm[$ct] = $chitiet;
                $a_nkp_tinhtheodm[$ct] = $tinhtheodm;
            }
        }
        //dd($a_nkp_dm);
        dinhmucnguon:
        $a_ts = array_column($model_phucap->where('thaisan', 1)->toarray(), 'mapc');
        $a_no = array_column($model_phucap->where('nghiom', 1)->toarray(), 'mapc');
        $a_dd = array_column($model_phucap->where('dieudong', 1)->toarray(), 'mapc');
        $a_tapsu = array_column($model_phucap->where('tapsu', 1)->toarray(), 'mapc');
        //$a_dd = array_column($model_phucap->wherein('mapc', ['pclt', 'pckv'])->toarray(), 'mapc');//các loại phụ cấp cán bộ được điều động động đến được hưởng
        $a_pc_coth = array('pcudn', 'pctnn', 'pctaicu');
        $ptdn = round(session('admin')->ptdaingay / 100, session('admin')->lamtron);
        //$ngaycong = dmdonvi::where('madv',$inputs['madv'])->first()->songaycong;

        $a_data_canbo = array();
        foreach ($m_cb as $cb) {
            $a_pc_kobh = explode(',', $cb->khongnopbaohiem);
            $cb->ghichu = '';
            if (getDayVn($cb->ngaytu) != '') {
                $ngaytu = Carbon::createFromFormat('Y-m-d', $cb->ngaytu);
                if ($ngaytu->month == $inputs['thang'] && $ngaytu->year == $inputs['nam']) {
                    $cb->ghichu .= 'Nâng lương ngạch bâc;';
                }
            }

            if (getDayVn($cb->tnntungay) != '') {
                $ngaytu = Carbon::createFromFormat('Y-m-d', $cb->tnntungay);
                if ($ngaytu->month == $inputs['thang'] && $ngaytu->year == $inputs['nam']) {
                    $cb->ghichu .= 'Nâng lương thâm niên nghề;';
                }
            }

            $cb->manguonkp = $inputs['manguonkp'];
            $cb->mabl = $inputs['mabl'];
            $cb->congtac = 'CONGTAC';
            $cb->macongtac = isset($a_ct[$cb->mact]) ? $a_ct[$cb->mact] : '';
            //lưu lại hệ số gốc 1 số loại pc
            $cb->hs_vuotkhung = isset($cb->vuotkhung) ? $cb->vuotkhung : 0;
            $cb->hs_pctnn = isset($cb->pctnn) ? $cb->pctnn : 0;
            $cb->hs_pccovu = isset($cb->pccovu) ? $cb->pccovu : 0;
            $cb->hs_pcud61 = isset($cb->pcud61) ? $cb->pcud61 : 0;
            $cb->hs_pcudn = isset($cb->pcudn) ? $cb->pcudn : 0;
            $cb->luongcoban = $inputs['luongcoban'];
            $cb->giaml = $cb->songaytruc = $cb->songaycong = 0;
            $cb->songaylv = $cb->tongngaylv = session('admin')->songaycong;
            //trường hợp cán bộ tập sự, thử việc nộp bảo hiểm theo số lương thực nhận
            // (chỉ cần tính các phụ cấp theo hệ số và số tiền; hệ số theo % sẽ đc tính lại theo phụ cấp gốc)
            if ($cb->theodoi == 6 || $cb->mact == '1506673422') {
                foreach ($a_tapsu as $pc) {
                    if ($cb->$pc > 0 && $cb->$pc < 50) {
                        $cb->$pc = round($cb->$pc * $cb->pthuong / 100, session('admin')->lamtron);
                    }
                }
            }
            $cb->vuotkhung = isset($cb->vuotkhung) ? round($cb->heso * $cb->vuotkhung / 100, session('admin')->lamtron) : 0;

            foreach ($a_pc_coth as $phca) { //tính trc 1 số phụ cấp làm phụ cấp cơ sở
                $pc = $model_phucap->where('mapc', $phca)->first();
                if ($pc != null) { //do 1 số nguồn ko lấy thâm niên nghề làm cơ sở
                    $pl = getDbl($pc->phanloai);
                    switch ($pl) {
                        case 2: { //phần trăm
                                $heso = 0;
                                foreach (explode(',', $pc->congthuc) as $ct) {
                                    if ($ct != '' && $ct != $phca)
                                        $heso += $cb->$ct;
                                }
                                $cb->$phca = round($heso * $cb->$phca / 100, session('admin')->lamtron);
                                break;
                            }
                        default: {
                                break;
                            }
                    }
                }
            }
            //để kiểm tra ở đây để tính một số loại phụ cấp trc can bộ kiêm nhiệm (nếu không cùng nguồn, lĩnh vực với chức danh chính)
            if ($cb->manguonkp != $inputs['manguonkp'] || $cb->lvhd != $inputs['linhvuchoatdong']) {
                continue; //cán bộ ko thuộc nguồn quản lý => ko tính lương
            }
            //ngày công tác không thỏa mãn
            if (getDayVn($cb->ngaybc) != '' && $cb->ngaybc > $ngaycuoithang) {
                continue;
            }

            //phân loại công tác không trong đinh mức nguồn
            if (!isset($a_nkp_dm[$cb->mact])) {
                continue;
            }

            $khongluong = in_array($cb->macanbo, $a_khongluong) ? true : false;
            $daingay = in_array($cb->macanbo, $a_daingay) ? true : false;
            $nghi = isset($a_nghiphep[$cb->macanbo]) ? true : false;
            $thaisan = isset($a_thaisan[$cb->macanbo]) ? true : false;
            $duongsuc = isset($a_duongsuc[$cb->macanbo]) ? true : false;

            //tính hệ số bảo hiểm (cán bộ thai sản + nghỉ ko lương + cán bộ điều động đến => ko pai đóng bảo hiểm =>set luon bảo hiểm = 0 để ko tính)
            if ($khongluong || $daingay || $thaisan || $cb->theodoi == 4) {
                $cb->baohiem = 0;
                $cb->bhxh = $cb->bhyt = $cb->kpcd = $cb->bhtn = 0;
                $cb->bhxh_dv = $cb->bhyt_dv = $cb->kpcd_dv = $cb->bhtn_dv = 0;
            } else {
                $cb->bhxh = floatval($cb->bhxh) / 100;
                $cb->bhyt = floatval($cb->bhyt) / 100;
                $cb->kpcd = floatval($cb->kpcd) / 100;
                $cb->bhtn = floatval($cb->bhtn) / 100;
                $cb->bhxh_dv = floatval($cb->bhxh_dv) / 100;
                $cb->bhyt_dv = floatval($cb->bhyt_dv) / 100;
                $cb->kpcd_dv = floatval($cb->kpcd_dv) / 100;
                $cb->bhtn_dv = floatval($cb->bhtn_dv) / 100;
            }

            //các biển lưu để tính lương
            $tien = $tonghs = 0;
            $bhxh = $bhyt = $kpcd = $bhtn = 0;
            $bhxh_dv = $bhyt_dv = $kpcd_dv = $bhtn_dv = 0;

            foreach ($model_phucap as $pc) {
                $mapc = $pc->mapc;
                $mapc_st = 'st_' . $mapc;
                $cb->$mapc_st = 0;
                $pc->sotien = 0;
                if ($cb->$mapc <= 0) {
                    continue;
                }
                //kiểm tra phụ cấp =>ko trong nhóm tính
                //ko trong nhóm phụ cấp gốc = >set = 0
                if (!isset($a_nkp_dm[$cb->mact][$mapc])) {
                    if (!in_array($mapc, $a_goc)) {
                        $cb->$mapc = 0;
                    }
                    continue;
                }
                $pc->luongcoban = $a_nkp_dm[$cb->mact][$mapc];
                //cán bộ được điều động đến chỉ hưởng các loại phụ cấp trong $a_dd
                //chưa set các trường hợp để hệ sô, số tiền = 0 (3 phụ cấp gốc, cán bộ đc điều động đến ko đóng bảo hiểm)
                if ($cb->theodoi == 4) {
                    if (!in_array($mapc, $a_dd) && !in_array($mapc, $a_goc)) {
                        $cb->$mapc = 0;
                        continue;
                    } elseif (in_array($mapc, $a_goc)) {
                        continue;
                    }
                }

                //cán bộ đang điều động
                if ($cb->theodoi == 3 && in_array($mapc, $a_dd)) {
                    $cb->$mapc = 0;
                    continue;
                }
                //$pc->heso_goc = $cb->$mapc;
                $pl = getDbl($pc->phanloai);
                switch ($pl) {
                    case 0: { //hệ số
                            $pc->sotien = round($cb->$mapc * $pc->luongcoban, 0);
                            break;
                        }
                    case 1: { //số tiền
                            $pc->sotien = $a_nkp_tinhtheodm[$cb->mact][$mapc] ? $pc->luongcoban : chkDbl($cb->$mapc);
                            break;
                        }
                    case 2: { //phần trăm
                            if ($mapc != 'vuotkhung' && !in_array($mapc, $a_pc_coth)) {
                                $heso = 0;
                                foreach (explode(',', $pc->congthuc) as $cthuc) {
                                    if ($cthuc != '')
                                        $heso += $cb->$cthuc;
                                }
                                $cb->$mapc = round($heso * $cb->$mapc / 100, session('admin')->lamtron);
                            }

                            $pc->sotien = round($cb->$mapc * $pc->luongcoban, 0);
                            break;
                        }
                    default: { //trường hợp còn lại (ẩn,...)
                            break;
                        }
                }

                if ($khongluong) {
                    goto ketthuc_phucap;
                }
                if ($thaisan && !in_array($mapc, $a_ts)) {
                    goto ketthuc_phucap;
                }
                if ($daingay && !in_array($mapc, $a_ts)) {
                    goto ketthuc_phucap;
                }

                if ($cb->$mapc < 500) {
                    $tonghs += $cb->$mapc;
                }
                //                if ($pc->phanloai != '2') {
                //                    $tonghs += $cb->$mapc;
                //                }

                $pc->sotien = $daingay ? round($pc->sotien * $ptdn, 0) : $pc->sotien;
                $tien += $pc->sotien;
                $cb->$mapc_st = $pc->sotien;
                if (
                    $pc->baohiem == 1 && !in_array($mapc, $a_pc_kobh)
                    && !in_array($cb->macongtac, ['KHONGCT', 'KHAC'])
                ) {
                    $bhxh += round($pc->sotien * $cb->bhxh, 0);
                    $bhyt += round($pc->sotien * $cb->bhyt, 0);
                    $kpcd += round($pc->sotien * $cb->kpcd, 0);
                    $bhtn += round($pc->sotien * $cb->bhtn, 0);
                    $bhxh_dv += round($pc->sotien * $cb->bhxh_dv, 0);
                    $bhyt_dv += round($pc->sotien * $cb->bhyt_dv, 0);
                    $kpcd_dv += round($pc->sotien * $cb->kpcd_dv, 0);
                    $bhtn_dv += round($pc->sotien * $cb->bhtn_dv, 0);
                }
                ketthuc_phucap:
            }

            if ($cb->baohiem == 1) {
                //Ưu tiên trường hợp cán bộ đóng bảo hiểm theo mức lương
                if ($cb->mucluongbaohiem > 0) {
                    $baohiem = $cb->mucluongbaohiem;
                    $cb->stbhxh = round($baohiem * $cb->bhxh, 0);
                    $cb->stbhyt = round($baohiem * $cb->bhyt, 0);
                    $cb->stkpcd = round($baohiem * $cb->kpcd, 0);
                    $cb->stbhtn = round($baohiem * $cb->bhtn, 0);
                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->stbhxh_dv = round($baohiem * $cb->bhxh_dv, 0);
                    $cb->stbhyt_dv = round($baohiem * $cb->bhyt_dv, 0);
                    $cb->stkpcd_dv = round($baohiem * $cb->kpcd_dv, 0);
                    $cb->stbhtn_dv = round($baohiem * $cb->bhtn_dv, 0);
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                } elseif (in_array($cb->macongtac, ['KHONGCT', 'KHAC'])) {
                    //cán bộ ko chuyên trách nộp bảo hiểm theo lương cơ bản
                    $baohiem = $inputs['luongcoban'];
                    $cb->stbhxh = round($baohiem * $cb->bhxh, 0);
                    $cb->stbhyt = round($baohiem * $cb->bhyt, 0);
                    $cb->stkpcd = round($baohiem * $cb->kpcd, 0);
                    $cb->stbhtn = round($baohiem * $cb->bhtn, 0);
                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->stbhxh_dv = round($baohiem * $cb->bhxh_dv, 0);
                    $cb->stbhyt_dv = round($baohiem * $cb->bhyt_dv, 0);
                    $cb->stkpcd_dv = round($baohiem * $cb->kpcd_dv, 0);
                    $cb->stbhtn_dv = round($baohiem * $cb->bhtn_dv, 0);
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                } else {
                    $cb->stbhxh = $bhxh;
                    $cb->stbhyt = $bhyt;
                    $cb->stkpcd = $kpcd;
                    $cb->stbhtn = $bhtn;
                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->stbhxh_dv = $bhxh_dv;
                    $cb->stbhyt_dv = $bhyt_dv;
                    $cb->stkpcd_dv = $kpcd_dv;
                    $cb->stbhtn_dv = $bhtn_dv;
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                }
            } else {
                $cb->ttbh_dv = $cb->stbhxh = $cb->stbhyt = $cb->stkpcd = $cb->stbhtn = 0;
                $cb->ttbh = $cb->stbhxh_dv = $cb->stbhyt_dv = $cb->stkpcd_dv = $cb->stbhtn_dv = 0;
            }

            //Cán bộ đang đi công tác, đi học (bỏ qua các loại tạm ngưng theo dõi)
            if ($cb->theodoi == 2) {
                $tien = $tonghs = 0;
                foreach ($model_phucap as $pc) {
                    $maso = $pc->mapc;
                    $maso_st = 'st_' . $maso;
                    $cb->$maso = round($cb->$maso * $cb->pthuong / 100, session('admin')->lamtron);
                    $cb->$maso_st = round($cb->$maso_st * $cb->pthuong / 100);

                    //phụ cấp ko tính theo số tiền và đc hưởng
                    if ($cb->$maso < 10000 && $cb->$maso_st > 0) {
                        $tonghs += $cb->$maso;
                    }
                    $tien += $cb->$maso_st;
                }
                goto tinhluong;
            }

            //Cán bộ tập sự, thử việc
            //            if ($cb->theodoi == 6 || $cb->mact == '1506673422') {
            //                $tien = $tonghs = 0;
            //                foreach ($model_phucap as $pc) {
            //                    $maso = $pc->mapc;
            //                    $maso_st = 'st_' . $maso;
            //                    if(in_array($maso, $a_tapsu)){
            //                        $cb->$maso = round($cb->$maso * $cb->pthuong / 100, session('admin')->lamtron);
            //                        $cb->$maso_st = round($cb->$maso_st * $cb->pthuong / 100);
            //                    }
            //
            //                    //phụ cấp ko tính theo số tiền và đc hưởng
            //                    if ($cb->$maso < 10000 && $cb->$maso_st > 0) {
            //                        $tonghs += $cb->$maso;
            //                    }
            //                    $tien += $cb->$maso_st;
            //                }
            //                goto tinhluong;
            //            }


            if ($daingay) {
                $cb->tencanbo .= ' (nghỉ dài ngày)';
                $cb->congtac = 'DAINGAY';
                goto tinhluong;
            }

            //nếu cán bộ nghỉ thai sản
            if ($thaisan) {
                $ts = $m_tamngung->where('macanbo', $cb->macanbo)->first();
                $cb->tencanbo = $cb->tencanbo . '(nghỉ thai sản)';
                $cb->congtac = 'THAISAN';
                $cb->ghichu .= 'Nghỉ thai sản từ ' . getDayVn($ts->ngaytu) . ' đến ' . getDayVn($ts->ngayden) . ';';
                goto tinhluong;
            }

            if ($khongluong) {
                $cb->tencanbo = $cb->tencanbo . '(nghỉ không lương)';
                goto tinhluong;
            }

            if ($nghi) {
                $cb_nghi = $a_nghiphep[$cb->macanbo];
                $ngaynghi = $cb_nghi['songaynghi'] > 0 ? $cb_nghi['songaynghi'] : 0;
                $ngaycong = $cb_nghi['songaycong'] > 0 ? $cb_nghi['songaycong'] : 1;
                $cb->songaytruc = $ngaynghi;
                $cb->songaycong = $ngaycong;
                //$m_cb[$key]['congtac'] = 'NGHIPHEP';
                $sotiencong = $model_phucap->wherein('mapc', $a_no)->sum('sotien');
                $tiencong = $sotiencong / $ngaycong;

                if ($cb_nghi['songaynghi'] >= 15) { //nghỉ quá 15 ngày thì ko đóng bảo hiểm
                    $cb->stbhxh = $cb->stbhyt = $cb->stkpcd = $cb->stbhtn = $cb->ttbh = 0;
                    $cb->stbhxh_dv = $cb->stbhyt_dv = $cb->stkpcd_dv = $cb->stbhtn_dv = $cb->ttbh_dv = 0;
                }
                //dd($tiencong);
                $cb->giaml = $cb_nghi['songaynghi'] >= $ngaycong ? round($sotiencong) : round($tiencong * $cb_nghi['songaynghi'], 0);
            }

            if ($duongsuc) {
                $cb_nghi = $a_duongsuc[$cb->macanbo];
                $ngaynghi = $cb_nghi['songaynghi'] > 0 ? $cb_nghi['songaynghi'] : 0;
                $ngaycong = $cb_nghi['songaycong'] > 0 ? $cb_nghi['songaycong'] : 1;
                $cb->songaytruc = $ngaynghi;
                $cb->songaycong = $ngaycong;
                //$m_cb[$key]['congtac'] = 'NGHIPHEP';
                $sotiencong = $model_phucap->wherein('mapc', $a_no)->sum('sotien');
                $tiencong = $sotiencong / $ngaycong;

                if ($cb_nghi['songaynghi'] >= 15) { //nghỉ quá 15 ngày thì ko đóng bảo hiểm
                    $cb->stbhxh = $cb->stbhyt = $cb->stkpcd = $cb->stbhtn = $cb->ttbh = 0;
                    $cb->stbhxh_dv = $cb->stbhyt_dv = $cb->stkpcd_dv = $cb->stbhtn_dv = $cb->ttbh_dv = 0;
                }
                $cb->giaml = $cb_nghi['songaynghi'] >= $ngaycong ? round($sotiencong) : round($tiencong * $cb_nghi['songaynghi'], 0);
            }
            tinhluong:

            foreach (explode(',', $inputs['phucapluusotien']) as $mapc) {
                if ($mapc != '') {
                    $tonghs -= $cb->$mapc;
                    $maso_st = 'st_' . $maso;
                    $cb->$mapc = $cb->$maso_st;
                }
            }

            $cb->tonghs = $tonghs;
            $cb->ttl = $tien;
            $cb->luongtn = $cb->ttl - $cb->ttbh - $cb->giaml;

            $a_data_canbo[] = $cb->toarray();
        }
        //Mảng chứa các cột bỏ để chạy hàm insert
        $a_col_cb = array(
            'id', 'bac', 'baohiem', 'macongtac', 'pthuong', 'theodoi', 'ngaybc',
            'khongnopbaohiem', 'ngaytu', 'tnntungay', 'ngayden', 'tnndenngay', 'lvhd', 'mucluongbaohiem', 'phanloai'
        );
        $a_data_canbo = unset_key($a_data_canbo, $a_col_cb);
        // dd($a_data_canbo);
        foreach (array_chunk($a_data_canbo, 50) as $data) {
            (new data())->storeBangLuong($inputs['thang'], $data);
        }
        //Tính toán lương cho cán bộ kiêm nhiệm
        //$m_donvi = dmdonvi::where('madv',$madv)->first();
        //dd($a_nkp_dm);
        foreach ($model_canbo_kn as $cb) {
            $a_nguon = explode(',', $cb->manguonkp);
            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if ($cb->manguonkp != '' && !in_array($inputs['manguonkp'], $a_nguon) && $cb->manguonkp != null) {
                continue;
            }
            //trong kiêm nhiệm: thâm niên lấy  % lương hệ số
            //đặc thù tính
            //lấy thông tin ở bảng hồ sơ cán bộ để lấy thông tin lương, phụ cấp
            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
            $canbo = $m_cb->where('macanbo', $cb->macanbo)->first();
            //$canbo = $m_cb->where('macanbo',$cb->macanbo)->first(); không dùng được do khi lọc nguồn bỏ mất cán bộ này
            if ($canbo == null) {
                continue;
            }

            $cb->tencanbo = $canbo->tencanbo;
            $cb->mabl = $inputs['mabl'];
            $cb->manguonkp = $inputs['manguonkp'];
            $cb->luongcoban = $inputs['luongcoban'];
            $cb->congtac = 'KIEMNHIEM';
            //tính thâm niên
            $pctn = $model_phucap->where('mapc', 'pcthni')->first();
            //trường đơn vị ko mở phụ cấp thâm niên
            if ($pctn != null) {
                $pl = getDbl($pctn->phanloai);
                switch ($pl) {
                    case 0:
                    case 1: { //số tiền
                            //giữ nguyên ko cần tính
                            break;
                        }
                    case 2: { //phần trăm
                            $heso = 0;
                            foreach (explode(',', $pctn->congthuc) as $ct) {
                                if ($ct != '' && $ct != 'pcthni')
                                    $heso += $canbo->$ct;
                            }
                            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                            $heso += $canbo->hesopc;
                            $cb->pcthni = round($heso * $cb->pcthni / 100, session('admin')->lamtron);
                            break;
                        }
                    default: { //trường hợp còn lại (ẩn,...)
                            $cb->pcthni = 0;
                            break;
                        }
                }
            }

            //
            $cb->vuotkhung = isset($cb->vuotkhung) ? round($cb->vuotkhung * $cb->heso / 100, session('admin')->lamtron)   : 0;
            $canbo->pcthni = isset($cb->pcthni) ? $cb->pcthni : 0; //set vao hồ sơ cán bộ để tính công thức lương
            $canbo->pctn = isset($cb->pctn) ? $cb->pctn : 0;
            $ths = 0;
            $tt = 0;
            //lương cơ bản gán theo loại phụ cấp => tính tiền luôn
            foreach ($model_phucap as $pc) {
                $mapc = $pc->mapc;
                $pc->luongcoban = $a_nkp_dm[$cb->mact][$mapc] ?? 0; //Nếu phân loại công tác ko có thì gán lương cơ bản = 0;
                $mapc_st = 'st_' . $pc->mapc;
                if ($cb->$mapc <= 0) {
                    continue;
                }

                $pl = getDbl($pc->phanloai);

                switch ($pl) {
                    case 0: { //hệ số
                            $ths += $cb->$mapc;
                            $cb->$mapc_st = round($cb->$mapc * $pc->luongcoban, 0);
                            $tt += $cb->$mapc_st;
                            break;
                        }
                    case 1: { //số tiền
                            $cb->$mapc = $a_nkp_tinhtheodm[$cb->mact][$mapc] ? $pc->luongcoban : chkDbl($cb->$mapc);
                            $cb->$mapc_st = $cb->$mapc;
                            $tt += chkDbl($cb->$mapc_st);
                            break;
                        }
                    case 2: { //phần trăm
                            if (!in_array($mapc, ['pcthni', 'vuotkhung'])) {
                                $heso = 0;
                                if ($pl == 2) {
                                    foreach (explode(',', $pc->congthuc) as $cthuc) {
                                        if ($cthuc != '')
                                            $heso += $canbo->$cthuc;
                                    }
                                }

                                $cb->$mapc = round($heso * $cb->$mapc / 100, session('admin')->lamtron);
                                $ths += $cb->$mapc;
                                $cb->$mapc_st = round($cb->$mapc * $pc->luongcoban, 0);
                                $tt += $cb->$mapc_st;
                            }
                            break;
                        }
                    default: { //trường hợp còn lại (ẩn,...)
                            $cb->$mapc = 0;
                            break;
                        }
                }
            }

            $cb->tonghs = $ths;
            $cb->ttl = $tt;
            $cb->ttbh = $cb->ttbh_dv = 0;
            if ($cb->baohiem) {
                $cb->stbhxh = round($inputs['luongcoban'] * $cb->bhxh, 0);
                $cb->stbhyt = round($inputs['luongcoban'] * $cb->bhyt, 0);
                $cb->stkpcd = round($inputs['luongcoban'] * $cb->kpcd, 0);
                $cb->stbhtn = round($inputs['luongcoban'] * $cb->bhtn, 0);
                $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                $cb->stbhxh_dv = round($inputs['luongcoban'] * $cb->bhxh_dv, 0);
                $cb->stbhyt_dv = round($inputs['luongcoban'] * $cb->bhyt_dv, 0);
                $cb->stkpcd_dv = round($inputs['luongcoban'] * $cb->kpcd_dv, 0);
                $cb->stbhtn_dv = round($inputs['luongcoban'] * $cb->bhtn_dv, 0);
                $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
            } else {
                $cb->bhxh = 0;
                $cb->bhyt = 0;
                $cb->kpcd = 0;
                $cb->bhtn = 0;
                $cb->bhxh_dv = 0;
                $cb->bhyt_dv = 0;
                $cb->kpcd_dv = 0;
                $cb->bhtn_dv = 0;
            }

            $cb->luongtn = $cb->ttl - $cb->ttbh;
            // if ($cb->macanbo = '1511709119_1689318626') {
            //     dd($cb);
            // }
            $a_k = $cb->toarray();
            unset($a_k['id']);
            unset($a_k['phanloai']);

            (new data())->createBangLuong($inputs['thang'], $a_k);
        }
    }

    function tinhluong_khongdinhmuc($inputs)
    {
        $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01');
        //dd($ngaylap);
        $m_tamngung = hosotamngungtheodoi::wherein('maphanloai', ['THAISAN', 'KHONGLUONG', 'DAINGAY', 'KYLUAT'])
            ->where('ngaytu', '<=', $ngaylap)->where('ngayden', '>=', $ngaylap)
            ->where('madv', $inputs['madv'])->get();
        $a_khongluong = array_column($m_tamngung->where('maphanloai', 'KHONGLUONG')->toarray(), 'macanbo');
        $a_daingay = array_column($m_tamngung->where('maphanloai', 'DAINGAY')->toarray(), 'macanbo');
        $a_thaisan = $m_tamngung->where('maphanloai', 'THAISAN')->keyBy('macanbo')->toarray();
        $a_kyluat = $m_tamngung->where('maphanloai', 'KYLUAT')->keyBy('macanbo')->toarray();

        $m_nghi = hosotamngungtheodoi::select('songaycong', 'songaynghi', 'macanbo', 'maphanloai')
            ->where('madv', $inputs['madv'])->wherein('maphanloai', ['NGHIPHEP', 'NGHIOM', 'DUONGSUC'])
            ->whereYear('ngaythanhtoan', $inputs['nam'])->whereMonth('ngaythanhtoan', $inputs['thang'])
            ->get();

        $a_duongsuc = $m_nghi->where('maphanloai', 'DUONGSUC')->keyBy('macanbo')->toarray();
        $a_nghiphep = $m_nghi->wherein('maphanloai', ['NGHIPHEP', 'NGHIOM'])->keyBy('macanbo')->toarray();

        $model_phucap = dmphucap_donvi::select('mapc', 'phanloai', 'congthuc', 'baohiem', 'tenpc', 'thaisan', 'nghiom', 'dieudong', 'thuetn', 'tapsu', 'baohiem_plct', 'pccoso')
            ->where('madv', session('admin')->madv)->where('phanloai', '<', '3')
            ->wherenotin('mapc', array_merge(['hesott'], explode(',', $inputs['phucaploaitru'])))->get();
        //$a_pc = $model_phucap->keyBy('mapc')->toarray();
        //dd($a_pc);
        //thuế thu nhập cá nhân
        // dd($model_phucap);
        $m_thue = dmthuetncn::where('ngayapdung', '<=', $ngaylap)->orderby('ngayapdung', 'desc')->first();
        if ($m_thue != null) {
            $banthan = $m_thue->banthan;
            $phuthuoc = $m_thue->phuthuoc;
            $a_mucthue = dmthuetncn_ct::where('sohieu', $m_thue->sohieu)->orderby('muctu')->get()->toarray();
        } else {
            $banthan = $phuthuoc = 0;
            $a_mucthue = [];
        }

        //kiêm nhiệm
        $a_th = array_merge(
            array('macanbo', 'macvcq', 'mapb', 'manguonkp', 'mact', 'baohiem', 'pthuong'),
            array_column($model_phucap->toarray(), 'mapc')
        );

        //$m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th,array('phanloai')))->where('madv',$inputs['madv'])->wherein('manguonkp',[$inputs['manguonkp'],'',null])->get()->toArray();;
        $m_cb_kn = hosocanbo_kiemnhiem::select(array_merge($a_th, array('phanloai')))->where('madv', $inputs['madv'])->get()->toArray();;
        //dd($m_cb_kn);
        //công tác
        $a_th = array_merge(
            array(
                'stt', 'tencanbo', 'msngbac', 'bac', 'theodoi', 'nguoiphuthuoc',
                'khongnopbaohiem', 'ngaytu', 'tnntungay', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv',
                'bhyt_dv', 'bhtn_dv', 'kpcd_dv', 'ngaybc', 'lvhd', 'mucluongbaohiem'
            ),
            $a_th
        );

        //$m_dv = dmdonvi::where('madv', $inputs['madv'])->first();
        //dd($m_cb_kn);

        $ngaycuoithang = Carbon::create($inputs['nam'], $inputs['thang'] + 1, 0)->toDateString();
        $ngaydauthang = $ngaylap->toDateString();
        //ds cán bộ thôi công tác
        $a_cbn = hosothoicongtac::select('macanbo')->where('madv', $inputs['madv'])
            ->where(function ($qr) use ($ngaycuoithang) {
                $qr->where('ngaynghi', '<=', $ngaycuoithang)->orWhereNull('ngaynghi');
            })->get()->toarray();
        //dd($a_cbn);
        //ds cán bộ
        //$m_cb = hosocanbo::select($a_th)->where('madv', $inputs['madv'])->wherenotin('macanbo',$a_cbn)->get()->keyBy('macanbo')->toArray();
        $m_cb = hosocanbo::select($a_th)->where('madv', $inputs['madv'])->wherenotin('macanbo', $a_cbn)->get();
        // dd($m_cb);
        $m_bh_cb = array();
        foreach ($m_cb as $key => $item) {
            $m_bh_cb[$item->macanbo] = $item->only(['bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv']);
        }

        // dd($m_bh_cb);
        //chay hàm lấy lại hàm sửa dữ liệu
        if (isset($inputs['capnhatnangluong'])) {
            $m_hs = hosophucap::where('madv', session('admin')->madv)
                ->wherenotin('mapc', ['heso', 'vuotkhung', 'pctnn'])
                ->where(function ($qr) use ($ngaydauthang) {
                    $qr->where(function ($q) use ($ngaydauthang) {
                        $q->where('ngaytu', '<=', $ngaydauthang)
                            ->where('ngayden', '>', $ngaydauthang);
                    })->orwhere('ngayden', '<', $ngaydauthang);
                })->get();
            $m_cb = (new data())->getCanBo_bl($m_cb, $m_hs, $ngaydauthang);
        }

        $m_cb = $m_cb->keyBy('macanbo')->toArray();
        // dd($m_cb);
        $a_phanloai = dmphanloaicongtac_baohiem::where('madv', session('admin')->madv)->get()->keyBy('mact')->toArray();
        $a_nhomct = array_column(dmphanloaict::all()->toarray(), 'macongtac', 'mact');
        // dd($a_phanloai);
        $ptdn = round(session('admin')->ptdaingay / 100, session('admin')->lamtron); //cán bộ nghỉ dài ngày hưởng 50% lương

        //$a_dd = array_column($model_phucap->wherein('mapc', ['pclt', 'pckv'])->toarray(), 'mapc');//các loại phụ cấp cán bộ được điều động động đến được hưởng (chưa làm cho định mức)
        $a_dd = array_column($model_phucap->where('dieudong', 1)->toarray(), 'mapc');
        $a_ts = array_column($model_phucap->where('thaisan', 1)->toarray(), 'mapc');
        $a_no = array_column($model_phucap->where('nghiom', 1)->toarray(), 'mapc');
        $a_thue = array_column($model_phucap->where('thuetn', 1)->toarray(), 'mapc');
        $a_tapsu = array_column($model_phucap->where('tapsu', 1)->toarray(), 'mapc');

        $a_goc = array_keys(getCongThucTinhPC(false));
        $a_pc = SapXepPhuCap($model_phucap);
        // dd($model_phucap);
        // dd($a_pc);
        if (count($a_pc) == 0) {
            return view('errors.data_error')
                ->with('message', 'Phụ cấp cơ sở bị lập lại. Bạn hãy kiểm tra lại danh mục phụ cấp trước khi tính lương')
                ->with('furl', '/chuc_nang/bang_luong/chi_tra?thang=' . date('m') . '&nam=' . date('Y'));
        }

        $ngaycong = session('admin')->songaycong;
        $a_data_canbo = array();
        //$a_data_phucap = array();
        //$a_pc_coth = array('pcudn', 'pctnn', 'pctaicu');
        // dd($m_cb);
        foreach ($m_cb as $key => $val) {
            $a_lv = explode(',', $m_cb[$key]['lvhd']);
            if (in_array($inputs['linhvuchoatdong'], $a_lv) || $m_cb[$key]['lvhd'] == null || $m_cb[$key]['lvhd'] == '') {
                $m_cb[$key]['lvhd'] = $inputs['linhvuchoatdong'];
            }

            //chạy tính hệ số + vượt khung trc để tính cho kiêm nhiệm (trường hợp tạo bảng lương không hưởng ở nguồn kinh phí này)

            //trường hợp cán bộ tập sự, thử việc nộp bảo hiểm theo số lương thực nhận
            // (chỉ cần tính các phụ cấp theo hệ số và số tiền; hệ số theo % sẽ đc tính lại theo phụ cấp gốc)
            if ($m_cb[$key]['theodoi'] == 6 || $m_cb[$key]['mact'] == '1506673422') {
                foreach ($a_tapsu as $pc) {
                    if ($m_cb[$key][$pc] > 0 && $m_cb[$key][$pc] < 50) {
                        $m_cb[$key][$pc] = round($m_cb[$key][$pc] * $m_cb[$key]['pthuong'] / 100, session('admin')->lamtron);
                    }
                }
            }


            //$m_cb[$key]['vuotkhung'] = isset($m_cb[$key]['vuotkhung']) ? round($val['heso'] * $val['vuotkhung'] / 100, session('admin')->lamtron) : 0;
            $m_cb[$key]['mabl'] = $inputs['mabl'];
            $m_cb[$key]['manguonkp'] = $inputs['manguonkp'];
            $m_cb[$key]['congtac'] = 'CONGTAC';
            $m_cb[$key]['macongtac'] = isset($a_nhomct[$m_cb[$key]['mact']]) ? $a_nhomct[$m_cb[$key]['mact']] : '';
            //lưu hệ số gốc

            $m_cb[$key]['hs_vuotkhung'] = isset($val['vuotkhung']) ? $val['vuotkhung'] : 0;
            $m_cb[$key]['hs_pctnn'] = isset($val['pctnn']) ? $val['pctnn'] : 0;
            $m_cb[$key]['hs_pccovu'] = isset($val['pccovu']) ? $val['pccovu'] : 0;
            $m_cb[$key]['hs_pcud61'] = isset($val['pcud61']) ? $val['pcud61'] : 0;
            $m_cb[$key]['hs_pcudn'] = isset($val['pcudn']) ? $val['pcudn'] : 0;
            $m_cb[$key]['hs_pclt'] = isset($val['pclt']) ? $val['pclt'] : 0;
            $m_cb[$key]['hs_pcth'] = isset($val['pcth']) ? $val['pcth'] : 0; //TH trường Khánh Thành - Khánh Vĩnh
            $m_cb[$key]['luongcoban'] = $inputs['luongcoban'];
            $m_cb[$key]['giaml'] = $m_cb[$key]['songaytruc'] = $m_cb[$key]['songaycong'] = 0;
            $m_cb[$key]['songaylv'] = $m_cb[$key]['tongngaylv'] = session('admin')->songaycong; //set mặc định = tổng số ngày công của đơn vị
            //$m_cb[$key]['songaytruc'] = $m_cb[$key]['songaycong'] = 0;

            $a_nguon = explode(',', $val['manguonkp']);
            $a_pc_kobh = explode(',', $val['khongnopbaohiem']);

            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if ($val['manguonkp'] != '' && $val['manguonkp'] != null && !in_array($inputs['manguonkp'], $a_nguon)) {
                //dd($val['manguonkp']);
                continue; //cán bộ ko thuộc nguồn quản lý => ko tính lương
            }

            if ($m_cb[$key]['lvhd'] != $inputs['linhvuchoatdong']) {
                //dd($m_cb[$key]['lvhd']);
                continue; //cán bộ ko thuộc lvhd => ko tính lương
            }
            //ngày công tác không thỏa mãn
            if (getDayVn($m_cb[$key]['ngaybc']) != '' && $m_cb[$key]['ngaybc'] > $ngaycuoithang) {
                //dd($m_cb[$key]);
                continue;
            }

            $m_cb[$key]['ghichu'] = '';
            if (getDayVn($m_cb[$key]['ngaytu']) != '') {
                $ngaytu = Carbon::createFromFormat('Y-m-d', $m_cb[$key]['ngaytu']);
                if ($ngaytu->month == $inputs['thang'] && $ngaytu->year == $inputs['nam']) {
                    $m_cb[$key]['ghichu'] .= 'Nâng lương ngạch bâc;';
                }
            }

            if (getDayVn($m_cb[$key]['tnntungay']) != '') {
                $ngaytu = Carbon::createFromFormat('Y-m-d', $m_cb[$key]['tnntungay']);
                if ($ngaytu->month == $inputs['thang'] && $ngaytu->year == $inputs['nam']) {
                    $m_cb[$key]['ghichu'] .= 'Nâng lương thâm niên nghề;';
                }
            }

            $khongluong = in_array($m_cb[$key]['macanbo'], $a_khongluong) ? true : false;
            $daingay = in_array($m_cb[$key]['macanbo'], $a_daingay) ? true : false;
            $nghi = isset($a_nghiphep[$m_cb[$key]['macanbo']]) ? true : false;
            $thaisan = isset($a_thaisan[$m_cb[$key]['macanbo']]) ? true : false;
            $duongsuc = isset($a_duongsuc[$m_cb[$key]['macanbo']]) ? true : false;
            $kyluat = isset($a_kyluat[$m_cb[$key]['macanbo']]) ? true : false;
            // dd($m_cb[$key]);            
            //tính hệ số bảo hiểm (cán bộ thai sản + nghỉ ko lương + cán bộ điều động đến => ko pai đóng bảo hiểm =>set luon bảo hiểm = 0 để ko tính)
            if ($khongluong || $daingay || $thaisan || $m_cb[$key]['theodoi'] == 4 || $m_cb[$key]['baohiem'] == 0) {
                $m_cb[$key]['baohiem'] = 0;
                $m_cb[$key]['bhxh'] = $m_cb[$key]['bhyt'] = $m_cb[$key]['kpcd'] = $m_cb[$key]['bhtn'] = 0;
                $m_cb[$key]['bhxh_dv'] = $m_cb[$key]['bhyt_dv'] = $m_cb[$key]['kpcd_dv'] = $m_cb[$key]['bhtn_dv'] = 0;
            } else {
                $m_cb[$key]['bhxh'] = chkDbl(floatval($val['bhxh'] / 100));
                $m_cb[$key]['bhyt'] = chkDbl(floatval($val['bhyt'] / 100));
                $m_cb[$key]['kpcd'] = chkDbl(floatval($val['kpcd'] / 100));
                $m_cb[$key]['bhtn'] = chkDbl(floatval($val['bhtn'] / 100));
                $m_cb[$key]['bhxh_dv'] = chkDbl(floatval($val['bhxh_dv'] / 100));
                $m_cb[$key]['bhyt_dv'] = chkDbl(floatval($val['bhyt_dv'] / 100));
                $m_cb[$key]['kpcd_dv'] = chkDbl(floatval($val['kpcd_dv'] / 100));
                $m_cb[$key]['bhtn_dv'] = chkDbl(floatval($val['bhtn_dv'] / 100));
            }
            // dd($m_cb[$key]);
            //các biển lưu để tính lương
            $tien = $tonghs = 0;
            $bhxh = $bhyt = $kpcd = $bhtn = 0;
            $bhxh_dv = $bhyt_dv = $kpcd_dv = $bhtn_dv = 0;
            // dd($a_pc);
            // dd($m_cb[$key]);
            //Tính phụ cấp
            foreach ($a_pc as $k => $v) {
                $mapc = $v['mapc'];
                $m_cb[$key]['st_' . $mapc] = $a_pc[$k]['ttbh_dv'] =  $a_pc[$k]['ttbh'] = $a_pc[$k]['sotien'] = 0;
                $a_pc[$k]['stbhxh'] = $a_pc[$k]['stbhyt'] = $a_pc[$k]['stkpcd'] = $a_pc[$k]['stbhtn'] = 0;
                $a_pc[$k]['stbhxh_dv'] = $a_pc[$k]['stbhyt_dv'] = $a_pc[$k]['stkpcd_dv'] = $a_pc[$k]['stbhtn_dv'] = 0;
                if ($m_cb[$key][$mapc] <= 0) {
                    continue;
                }

                //cán bộ được điều động đến chỉ hưởng các loại phụ cấp trong $a_dd
                //chưa set các trường hợp để hệ sô, số tiền = 0 (3 phụ cấp gốc, cán bộ đc điều động đến ko đóng bảo hiểm)
                if ($m_cb[$key]['theodoi'] == 4) {
                    if (!in_array($mapc, $a_dd) && !in_array($mapc, $a_goc)) {
                        $m_cb[$key][$mapc] = 0;
                        continue;
                    } elseif (in_array($mapc, $a_goc)) {
                        continue;
                    }
                }
                //cán bộ đang điều động
                if ($m_cb[$key]['theodoi'] == 3 && in_array($mapc, $a_dd)) {
                    $m_cb[$key][$mapc] = 0;
                    continue;
                }

                // dd($a_pc[$k]);
                switch ($a_pc[$k]['phanloai']) {
                    case 0: { //hệ số
                            $a_pc[$k]['sotien'] = round($m_cb[$key][$mapc] * $inputs['luongcoban']);
                            // dd( $a_pc[$k]['sotien']);
                            break;
                        }
                    case 1: { //số tiền
                            $a_pc[$k]['sotien'] = chkDbl($m_cb[$key][$mapc]);
                            break;
                        }
                    case 2: { //phần trăm

                            $sotien = 0;
                            $heso=0;
                            foreach (explode(',', $a_pc[$k]['congthuc']) as $ct) {
                                if ($ct != '')
                                    // $sotien += $m_cb[$key]['st_' . $ct];
                                    $heso += $m_cb[$key][$ct];
                            }
                            // dd($heso);
                            // $sotien = round(($sotien * $m_cb[$key][$mapc]) / 100, session('admin')->lamtron);
                            $heso = round(($heso * $m_cb[$key][$mapc]) / 100, session('admin')->lamtron);

                            $a_pc[$k]['sotien'] = round($heso * $inputs['luongcoban'], session('admin')->lamtron);
                            $m_cb[$key][$mapc] = $heso;
                            //do tính hệ số đã làm tròn => (hệ số * lương cơ bản) != (số tiền) => nhân lại để đúng số tiền
                            // $a_pc[$k]['sotien'] = round($m_cb[$key][$mapc] * $inputs['luongcoban']);
                            break;
                        }
                    default: { //trường hợp còn lại (ẩn,...)
                            break;
                        }
                }

                if ($khongluong) {
                    goto ketthuc_phucap;
                }
                if ($thaisan && !in_array($mapc, $a_ts) && !in_array($mapc, $a_goc)) {
                    //dd($m_cb[$key]);
                    goto ketthuc_phucap;
                }
                if ($daingay && !in_array($mapc, $a_ts) && !in_array($mapc, $a_goc)) {
                    goto ketthuc_phucap;
                }

                if ($m_cb[$key][$mapc] < 500) {
                    $tonghs += $m_cb[$key][$mapc];
                }

                //                if($a_pc[$k]['phanloai'] != '2'){
                //                    $tonghs += $m_cb[$key][$mapc];
                //                }
                //$tien += $a_pc[$k]['sotien'];
                $a_pc[$k]['sotien'] = $daingay ? round($a_pc[$k]['sotien'] * $ptdn, 0) : round($a_pc[$k]['sotien'], 0);
                $tien += $a_pc[$k]['sotien'];
                $m_cb[$key]['st_' . $mapc] = round($a_pc[$k]['sotien'], 0);

                //xử lý phân loại công tác trc cho đỡ rối
                $dongbaohiem = $a_pc[$k]['baohiem'];
                if ($dongbaohiem == 1 && $a_pc[$k]['baohiem_plct'] != 'ALL' && !in_array($m_cb[$key]['mact'], explode(',', $a_pc[$k]['baohiem_plct']))) {
                    $dongbaohiem = 0;
                }

                if (
                    $m_cb[$key]['baohiem'] == 1 && $dongbaohiem == 1 && !in_array($mapc, $a_pc_kobh)
                    && !in_array($m_cb[$key]['macongtac'], ['KHONGCT', 'KHAC'])
                ) { //cán bộ quân sự, đại biểu hội đồng nd đóng theo mức lương co ban
                    $bhxh += round($a_pc[$k]['sotien'] * $m_cb[$key]['bhxh'], 0);
                    $bhyt += round($a_pc[$k]['sotien'] * $m_cb[$key]['bhyt'], 0);
                    $kpcd += round($a_pc[$k]['sotien'] * $m_cb[$key]['kpcd'], 0);
                    $bhtn += round($a_pc[$k]['sotien'] * $m_cb[$key]['bhtn'], 0);
                    $bhxh_dv += round($a_pc[$k]['sotien'] * $m_cb[$key]['bhxh_dv'], 0);
                    $bhyt_dv += round($a_pc[$k]['sotien'] * $m_cb[$key]['bhyt_dv'], 0);
                    $kpcd_dv += round($a_pc[$k]['sotien'] * $m_cb[$key]['kpcd_dv'], 0);
                    $bhtn_dv += round($a_pc[$k]['sotien'] * $m_cb[$key]['bhtn_dv'], 0);
                }
                ketthuc_phucap:
            }

            //  dd($a_pc);
            // dd($m_cb[$key]);
            //$ths = $ths + $heso_goc - $cb->heso;//do chỉ lương nb hưởng 85%, các hệ số hưởng %, bảo hiểm thì lấy 100% để tính
            //nếu cán bộ nghỉ thai sản (không gộp vào phần tính phụ cấp do trên bảng lương hiển thị hệ số nhưng ko có tiền)
            //$cb->tonghs = $tonghs;

            //if($m_cb[$key]['macongtac'] == 'KHONGCT') {
            if ($m_cb[$key]['baohiem'] == 1) {
                //ưu tiên trươc: trường hợp cán bộ đóng bảo hiểm theo mức lương 
                if ($m_cb[$key]['mucluongbaohiem'] > 0) {
                    $baohiem = $m_cb[$key]['mucluongbaohiem'];
                    $m_cb[$key]['stbhxh'] = round($baohiem * $m_cb[$key]['bhxh'], 0);
                    $m_cb[$key]['stbhyt'] = round($baohiem * $m_cb[$key]['bhyt'], 0);
                    $m_cb[$key]['stkpcd'] = round($baohiem * $m_cb[$key]['kpcd'], 0);
                    $m_cb[$key]['stbhtn'] = round($baohiem * $m_cb[$key]['bhtn'], 0);
                    $m_cb[$key]['ttbh'] = $m_cb[$key]['stbhxh'] + $m_cb[$key]['stbhyt']
                        + $m_cb[$key]['stkpcd'] + $m_cb[$key]['stbhtn'];
                    $m_cb[$key]['stbhxh_dv'] = round($baohiem * $m_cb[$key]['bhxh_dv'], 0);
                    $m_cb[$key]['stbhyt_dv'] = round($baohiem * $m_cb[$key]['bhyt_dv'], 0);
                    $m_cb[$key]['stkpcd_dv'] = round($baohiem * $m_cb[$key]['kpcd_dv'], 0);
                    $m_cb[$key]['stbhtn_dv'] = round($baohiem * $m_cb[$key]['bhtn_dv'], 0);
                    $m_cb[$key]['ttbh_dv'] = $m_cb[$key]['stbhxh_dv'] + $m_cb[$key]['stbhyt_dv']
                        + $m_cb[$key]['stkpcd_dv'] + $m_cb[$key]['stbhtn_dv'];
                } elseif (in_array($m_cb[$key]['macongtac'], ['KHONGCT', 'KHAC'])) {
                    //cán bộ quân sự, đại biểu hội đồng nd đóng theo mức lương co ban
                    $baohiem = $inputs['luongcoban'];
                    $m_cb[$key]['stbhxh'] = round($baohiem * $m_cb[$key]['bhxh'], 0);
                    $m_cb[$key]['stbhyt'] = round($baohiem * $m_cb[$key]['bhyt'], 0);
                    $m_cb[$key]['stkpcd'] = round($baohiem * $m_cb[$key]['kpcd'], 0);
                    $m_cb[$key]['stbhtn'] = round($baohiem * $m_cb[$key]['bhtn'], 0);
                    $m_cb[$key]['ttbh'] = $m_cb[$key]['stbhxh'] + $m_cb[$key]['stbhyt']
                        + $m_cb[$key]['stkpcd'] + $m_cb[$key]['stbhtn'];
                    $m_cb[$key]['stbhxh_dv'] = round($baohiem * $m_cb[$key]['bhxh_dv'], 0);
                    $m_cb[$key]['stbhyt_dv'] = round($baohiem * $m_cb[$key]['bhyt_dv'], 0);
                    $m_cb[$key]['stkpcd_dv'] = round($baohiem * $m_cb[$key]['kpcd_dv'], 0);
                    $m_cb[$key]['stbhtn_dv'] = round($baohiem * $m_cb[$key]['bhtn_dv'], 0);
                    $m_cb[$key]['ttbh_dv'] = $m_cb[$key]['stbhxh_dv'] + $m_cb[$key]['stbhyt_dv']
                        + $m_cb[$key]['stkpcd_dv'] + $m_cb[$key]['stbhtn_dv'];
                } else {
                    $m_cb[$key]['stbhxh'] = $bhxh;
                    $m_cb[$key]['stbhyt'] = $bhyt;
                    $m_cb[$key]['stkpcd'] = $kpcd;
                    $m_cb[$key]['stbhtn'] = $bhtn;
                    $m_cb[$key]['ttbh'] = $m_cb[$key]['stbhxh'] + $m_cb[$key]['stbhyt']
                        + $m_cb[$key]['stkpcd'] + $m_cb[$key]['stbhtn'];
                    $m_cb[$key]['stbhxh_dv'] = $bhxh_dv;
                    $m_cb[$key]['stbhyt_dv'] = $bhyt_dv;
                    $m_cb[$key]['stkpcd_dv'] = $kpcd_dv;
                    $m_cb[$key]['stbhtn_dv'] = $bhtn_dv;
                    $m_cb[$key]['ttbh_dv'] = $m_cb[$key]['stbhxh_dv'] + $m_cb[$key]['stbhyt_dv']
                        + $m_cb[$key]['stkpcd_dv'] + $m_cb[$key]['stbhtn_dv'];
                }
            } else {
                $m_cb[$key]['stbhxh'] = $m_cb[$key]['stbhyt'] = $m_cb[$key]['stkpcd'] = $m_cb[$key]['stbhtn'] = $m_cb[$key]['ttbh'] = 0;
                $m_cb[$key]['stbhxh_dv'] = $m_cb[$key]['stbhyt_dv'] = $m_cb[$key]['stkpcd_dv'] = $m_cb[$key]['stbhtn_dv'] = $m_cb[$key]['ttbh_dv'] = 0;
            }

            //Cán bộ đang đi công tác, đi học (bỏ qua các loại tạm ngưng theo dõi)
            if (($m_cb[$key]['theodoi'] == 2)) {
                $tien = $tonghs = 0;
                foreach ($a_pc as $k => $v) {
                    $m_cb[$key][$k] = round($m_cb[$key][$k] * $m_cb[$key]['pthuong'] / 100, session('admin')->lamtron);
                    $m_cb[$key]['st_' . $k] = round($m_cb[$key]['st_' . $k] * $m_cb[$key]['pthuong'] / 100);

                    //phụ cấp ko tính theo số tiền và đc hưởng
                    if ($m_cb[$key][$k] < 10000 && $m_cb[$key]['st_' . $k] > 0) {
                        $tonghs += $m_cb[$key][$k];
                    }
                    $tien += $m_cb[$key]['st_' . $k];
                }
                goto tinhluong;
            }
            //Cán bộ tập sự, thử việc
            //            if ($m_cb[$key]['theodoi'] == 6 || $m_cb[$key]['mact'] == '1506673422') {
            //                $tien = $tonghs = 0;
            //                foreach ($a_pc as $k => $v) {
            //                    if(in_array($k,$a_tapsu)){
            //                        $m_cb[$key][$k] = round($m_cb[$key][$k] * $m_cb[$key]['pthuong'] / 100, session('admin')->lamtron);
            //                        $m_cb[$key]['st_' . $k] = round($m_cb[$key]['st_' . $k] * $m_cb[$key]['pthuong'] / 100);
            //                    }
            //
            //                    //phụ cấp ko tính theo số tiền và đc hưởng
            //                    if ($m_cb[$key][$k] < 10000 && $m_cb[$key]['st_' . $k] > 0) {
            //                        $tonghs += $m_cb[$key][$k];
            //                    }
            //                    $tien += $m_cb[$key]['st_' . $k];
            //
            //                }
            //                goto tinhluong;
            //            }

            if ($daingay) {
                $m_cb[$key]['tencanbo'] .= ' (nghỉ dài ngày)';
                $m_cb[$key]['congtac'] = 'DAINGAY';
                goto tinhluong;
            }

            if ($thaisan) {
                $tien = $tonghs = 0;
                $m_cb[$key]['tencanbo'] .= ' (nghỉ thai sản)';
                $m_cb[$key]['ghichu'] .= 'Nghỉ thai sản từ ' . getDayVn($a_thaisan[$key]['ngaytu']) . ' đến ' . getDayVn($a_thaisan[$key]['ngayden']) . ';';
                $m_cb[$key]['congtac'] = 'THAISAN';

                foreach ($a_pc as $k => $v) {
                    if (!in_array($k, $a_ts)) {
                        //$m_cb[$key][$k] = round($m_cb[$key][$k] * $m_cb[$key]['pthuong'] / 100, session('admin')->lamtron);
                        $m_cb[$key]['st_' . $k] = 0;
                    }

                    //phụ cấp ko tính theo số tiền và đc hưởng
                    if ($m_cb[$key][$k] < 10000 && $m_cb[$key]['st_' . $k] > 0) {
                        $tonghs += $m_cb[$key][$k];
                    }
                    $tien += $m_cb[$key]['st_' . $k];
                }
                //gán lại hệ số bảo hiểm
                $m_cb[$key]['bhxh'] = $m_bh_cb[$key]['bhxh'];
                $m_cb[$key]['bhyt'] = $m_bh_cb[$key]['bhyt'];
                $m_cb[$key]['bhtn'] = $m_bh_cb[$key]['bhtn'];
                $m_cb[$key]['kpcd'] = $m_bh_cb[$key]['kpcd'];
                $m_cb[$key]['bhxh_dv'] = $m_bh_cb[$key]['bhxh_dv'];
                $m_cb[$key]['bhyt_dv'] = $m_bh_cb[$key]['bhyt_dv'];
                $m_cb[$key]['bhtn_dv'] = $m_bh_cb[$key]['bhtn_dv'];
                $m_cb[$key]['kpcd_dv'] = $m_bh_cb[$key]['kpcd_dv'];
                //dd($m_cb[$key]);
                goto tinhluong;
            }

            // dd($m_cb['1565583148_1697247356']);
            if ($khongluong) { //tính không lương rồi thoát
                $m_cb[$key]['tencanbo'] .= ' (nghỉ không lương)';
                $m_cb[$key]['congtac'] = 'KHONGLUONG';
                // foreach ($a_pc as $k => $v) {
                //     $mapc = $v['mapc'];
                //     $mapc_st = 'st_' . $v['mapc'];

                //     $m_cb[$key][$mapc] = round($m_cb[$key][$mapc] * 0.5, session('admin')->lamtron);
                //     $m_cb[$key][$mapc_st] = round($m_cb[$key][$mapc_st] * 0.5, 0);

                //     $tonghs += $m_cb[$key][$mapc];
                //     $tien += $m_cb[$key][$mapc_st];
                // }
                // // dd($m_bh_cb[$key]);
                //gán lại hệ số bảo hiểm
                $m_cb[$key]['bhxh'] = $m_bh_cb[$key]['bhxh'];
                $m_cb[$key]['bhyt'] = $m_bh_cb[$key]['bhyt'];
                $m_cb[$key]['bhtn'] = $m_bh_cb[$key]['bhtn'];
                $m_cb[$key]['kpcd'] = $m_bh_cb[$key]['kpcd'];
                $m_cb[$key]['bhxh_dv'] = $m_bh_cb[$key]['bhxh_dv'];
                $m_cb[$key]['bhyt_dv'] = $m_bh_cb[$key]['bhyt_dv'];
                $m_cb[$key]['bhtn_dv'] = $m_bh_cb[$key]['bhtn_dv'];
                $m_cb[$key]['kpcd_dv'] = $m_bh_cb[$key]['kpcd_dv'];
                goto tinhluong;
            }

            if ($nghi) {
                $cb_nghi = $a_nghiphep[$m_cb[$key]['macanbo']];
                //dd($cb_nghi);
                //$ngaycong = $cb_nghi['songaycong'] > 0 ? $cb_nghi['songaycong'] : $ngaycong;
                $ngaynghi = $cb_nghi['songaynghi'] > 0 ? $cb_nghi['songaynghi'] : 0;
                $ngaycong = $cb_nghi['songaycong'] > 0 ? $cb_nghi['songaycong'] : 1;
                $m_cb[$key]['songaytruc'] = $ngaynghi;
                $m_cb[$key]['songaycong'] = $ngaycong;
                //$m_cb[$key]['congtac'] = 'NGHIPHEP';
                $sotiencong = 0;

                foreach ($a_no as $no) {
                    $ma = 'st_' . $no;
                    $sotiencong += $m_cb[$key][$ma];
                }
                //$sotiencong = $inputs['luongcoban'] * $heso_no;
                $tiencong = $sotiencong / $ngaycong;

                if ($cb_nghi['songaynghi'] >= 15) { //nghỉ quá 15 ngày thì ko đóng bảo hiểm
                    $m_cb[$key]['stbhxh'] = $m_cb[$key]['stbhyt'] = $m_cb[$key]['stkpcd'] = $m_cb[$key]['stbhtn'] = 0;
                    $m_cb[$key]['stbhxh_dv'] = $m_cb[$key]['stbhyt_dv'] = $m_cb[$key]['stkpcd_dv'] = $m_cb[$key]['stbhtn_dv'] = 0;
                    $m_cb[$key]['ttbh'] = $m_cb[$key]['ttbh_dv'] = 0;
                }
                //dd($tiencong);
                $m_cb[$key]['giaml'] = $cb_nghi['songaynghi'] >= $ngaycong ? round($sotiencong) : round($tiencong * $cb_nghi['songaynghi'], 0);
            }

            if ($duongsuc) {
                $cb_nghi = $a_duongsuc[$m_cb[$key]['macanbo']];
                $ngaycong = $cb_nghi['songaycong'] > 0 ? $cb_nghi['songaycong'] : $ngaycong;
                $ngaynghi = $cb_nghi['songaynghi'] > 0 ? $cb_nghi['songaynghi'] : 0;

                $m_cb[$key]['songaytruc'] = $ngaynghi;
                $m_cb[$key]['songaycong'] = $ngaycong;
                //$m_cb[$key]['congtac'] = 'DUONGSUC';
                $sotiencong = 0;
                foreach ($a_no as $no) {
                    $ma = 'st_' . $no;
                    $sotiencong += $m_cb[$key][$ma];
                }
                //$sotiencong = $inputs['luongcoban'] * $heso_no;
                $tiencong = round($sotiencong / $ngaycong, 0);

                if ($cb_nghi['songaynghi'] >= 15) { //nghỉ quá 15 ngày thì ko đóng bảo hiểm
                    $m_cb[$key]['stbhxh'] = $m_cb[$key]['stbhyt'] = $m_cb[$key]['stkpcd'] = $m_cb[$key]['stbhtn'] = 0;
                    $m_cb[$key]['stbhxh_dv'] = $m_cb[$key]['stbhyt_dv'] = $m_cb[$key]['stkpcd_dv'] = $m_cb[$key]['stbhtn_dv'] = 0;
                    $m_cb[$key]['ttbh'] = $m_cb[$key]['ttbh_dv'] = 0;
                }
                $m_cb[$key]['giaml'] = $cb_nghi['songaynghi'] >= $ngaycong ? round($sotiencong) : ($tiencong * $cb_nghi['songaynghi']);
            }
            //Tính % hưởng cho cán bộ bị kỷ luật
            if ($kyluat) {
                $tonghs = 0;
                $tien = 0;
                $m_cb[$key]['tencanbo'] .= ' (kỷ luật)';
                foreach ($a_pc as $k => $v) {
                    $mapc = $v['mapc'];
                    $mapc_st = 'st_' . $v['mapc'];

                    $m_cb[$key][$mapc] = round($m_cb[$key][$mapc] * 0.5, session('admin')->lamtron);
                    $m_cb[$key][$mapc_st] = round($m_cb[$key][$mapc_st] * 0.5, 0);

                    $tonghs += $m_cb[$key][$mapc];
                    $tien += $m_cb[$key][$mapc_st];
                }
            }

            tinhluong:

            foreach (explode(',', $inputs['phucapluusotien']) as $mapc) {
                if ($mapc != '') {
                    $tonghs -= $m_cb[$key][$mapc];
                    $maso_st = 'st_' . $mapc;
                    $m_cb[$key][$mapc] = $m_cb[$key][$maso_st];
                }
            }


            $m_cb[$key]['tonghs'] = $tonghs;
            $m_cb[$key]['ttl'] = $tien;

            // dd($m_cb[$key]);
            //tính thuế thu nhập
            $m_cb[$key]['thuetn'] = 0;
            if (isset($inputs['thuetncn'])) {
                $tienthue = 0;
                foreach ($a_thue as $thue) {
                    $mapc = 'st_' . $thue;
                    $tienthue += $m_cb[$key][$mapc];
                }
                $tienthue = $tienthue - $banthan - $phuthuoc * $m_cb[$key]['nguoiphuthuoc'];
                if ($tienthue <= 0) {
                    goto luuketqua;
                }
                //                if($key == '1570522209_1585851396'){
                //                    dd($tienthue);
                //                }

                foreach ($a_mucthue as $thue) {
                    if ($tienthue < $thue['muctu']) {
                        goto luuketqua;
                    }
                    $m_cb[$key]['thuetn'] += round(($tienthue > $thue['mucden'] ? $thue['mucden'] - $thue['muctu'] : $tienthue - $thue['muctu'])
                        * $thue['phantram'] / 100);
                }
            }
            // dd($m_cb[$key]);
            luuketqua:
            $m_cb[$key]['luongtn'] = $m_cb[$key]['ttl'] - $m_cb[$key]['ttbh'] - $m_cb[$key]['giaml'] - $m_cb[$key]['thuetn'];
            $a_data_canbo[] = $m_cb[$key];
        }
        // dd($m_cb);
        //Mảng chứa các cột bỏ để chạy hàm insert
        $a_col_cb = array(
            'id', 'bac', 'baohiem', 'macongtac', 'pthuong', 'theodoi', 'ngaybc',
            'khongnopbaohiem', 'ngaytu', 'tnntungay', 'ngayden', 'tnndenngay', 'lvhd', 'nguoiphuthuoc', 'mucluongbaohiem', 'phanloai'
        );
        $a_data_canbo = unset_key($a_data_canbo, $a_col_cb);

        // dd($a_data_canbo);
        foreach (array_chunk($a_data_canbo, 1) as $data) {
            (new data())->storeBangLuong($inputs['thang'], $data);
        }

        //Tính toán lương cho cán bộ kiêm nhiệm
        $a_kn_canbo = array();
        //$a_kn_phucap = array();
        for ($i = 0; $i < count($m_cb_kn); $i++) {

            if (!array_key_exists($m_cb_kn[$i]['macanbo'], $m_cb)) {
                continue;
            }

            $a_nguon = explode(',', $m_cb_kn[$i]['manguonkp']);
            //nếu cán bộ ko set nguồn (null, '') hoặc trong nguồn thì sét luôn =  ma nguồn để tạo bang lương
            if ($m_cb_kn[$i]['manguonkp'] != '' && !in_array($inputs['manguonkp'], $a_nguon) && $m_cb_kn[$i]['manguonkp'] != null) {
                continue;
            }
            $canbo = $m_cb[$m_cb_kn[$i]['macanbo']];
            $m_cb_kn[$i]['tencanbo'] = $canbo['tencanbo'];
            $m_cb_kn[$i]['manguonkp'] = $inputs['manguonkp'];
            $m_cb_kn[$i]['luongcoban'] = $inputs['luongcoban'];
            $m_cb_kn[$i]['stt'] = $canbo['stt'];
            $m_cb_kn[$i]['mabl'] = $inputs['mabl'];
            // $m_cb_kn[$i]['congtac'] = $m_cb_kn[$i]['phanloai'];
            $m_cb_kn[$i]['congtac'] = 'KIEMNHIEM';
            $m_cb_kn[$i]['vuotkhung'] = isset($m_cb_kn[$i]['vuotkhung']) ? round($m_cb_kn[$i]['vuotkhung'] * $m_cb_kn[$i]['heso'] / 100,  session('admin')->lamtron) : 0;
            $m_cb_kn[$i]['stbhxh'] = 0;
            $m_cb_kn[$i]['stbhyt'] = 0;
            $m_cb_kn[$i]['stkpcd'] = 0;
            $m_cb_kn[$i]['stbhtn'] = 0;
            $m_cb_kn[$i]['ttbh'] = 0;
            $m_cb_kn[$i]['stbhxh_dv'] = 0;
            $m_cb_kn[$i]['stbhyt_dv'] = 0;
            $m_cb_kn[$i]['stkpcd_dv'] = 0;
            $m_cb_kn[$i]['stbhtn_dv'] = 0;
            $m_cb_kn[$i]['ttbh_dv'] = 0;
            if (isset($a_phanloai[$m_cb_kn[$i]['mact']])) {
                $phanloai = $a_phanloai[$m_cb_kn[$i]['mact']];
                $m_cb_kn[$i]['bhxh'] = floatval($phanloai['bhxh']) / 100;
                $m_cb_kn[$i]['bhyt'] = floatval($phanloai['bhyt']) / 100;
                $m_cb_kn[$i]['kpcd'] = floatval($phanloai['kpcd']) / 100;
                $m_cb_kn[$i]['bhtn'] = floatval($phanloai['bhtn']) / 100;
                $m_cb_kn[$i]['bhxh_dv'] = floatval($phanloai['bhxh_dv']) / 100;
                $m_cb_kn[$i]['bhyt_dv'] = floatval($phanloai['bhyt_dv']) / 100;
                $m_cb_kn[$i]['kpcd_dv'] = floatval($phanloai['kpcd_dv']) / 100;
                $m_cb_kn[$i]['bhtn_dv'] = floatval($phanloai['bhtn_dv']) / 100;
            } else {
                $m_cb_kn[$i]['bhxh'] = 0;
                $m_cb_kn[$i]['bhyt'] = 0;
                $m_cb_kn[$i]['kpcd'] = 0;
                $m_cb_kn[$i]['bhtn'] = 0;
                $m_cb_kn[$i]['bhxh_dv'] = 0;
                $m_cb_kn[$i]['bhyt_dv'] = 0;
                $m_cb_kn[$i]['kpcd_dv'] = 0;
                $m_cb_kn[$i]['bhtn_dv'] = 0;
            }

            if (isset($a_pc['pcthni'])) {
                $pctn = $a_pc['pcthni'];
                //dd($pctn);
                switch ($pctn['phanloai']) {
                    case 2: { //phần trăm
                            $heso = 0;
                            foreach (explode(',', $pctn['congthuc']) as $ct) {
                                if ($ct != '' && $ct != 'pcthni')
                                    $heso += $canbo[$ct];
                            }
                            //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                            $heso += $canbo['hesopc'];
                            $m_cb_kn[$i]['pcthni'] = round($heso * $m_cb_kn[$i]['pcthni'] / 100, session('admin')->lamtron);
                            break;
                        }
                    default: { //trường hợp còn lại (ẩn,...)
                            break;
                        }
                }
            }

            $tonghs = $tien = 0;
            $canbo['pcthni'] = isset($m_cb_kn[$i]['pcthni']) ? $m_cb_kn[$i]['pcthni'] : 0; //set vao hồ sơ cán bộ để tính công thức lương
            $canbo['pctn'] = isset($m_cb_kn[$i]['pctn']) ? $m_cb_kn[$i]['pctn'] : 0;

            foreach ($a_pc as $k => $v) {
                $mapc = $v['mapc'];
                $m_cb_kn[$i]['st_' . $mapc] = 0;
                if ($m_cb_kn[$i][$mapc] <= 0) {
                    continue;
                }
                //$a_pc[$k]['heso_goc'] =$m_cb_kn[$i][$mapc];//lưu lại hệ số gốc

                switch ($v['phanloai']) {
                    case 0: { //hệ số
                            //$tonghs += $m_cb_kn[$i][$mapc];
                            $m_cb_kn[$i]['st_' . $mapc] = round($m_cb_kn[$i][$mapc] * $inputs['luongcoban']);
                            //$tien += $m_cb_kn[$i]['st_' . $mapc];
                            break;
                        }
                    case 1: { //số tiền
                            //$tien += chkDbl($m_cb_kn[$i][$mapc]);
                            $m_cb_kn[$i]['st_' . $mapc] = chkDbl($m_cb_kn[$i][$mapc]);
                            break;
                        }
                    case 2: { //phần trăm
                            if (!in_array($mapc, ['pcthni', 'vuotkhung'])) {
                                $heso = 0;
                                foreach (explode(',', $v['congthuc']) as $cthuc) {
                                    if ($cthuc != '')
                                        $heso += $canbo[$cthuc];
                                }

                                //công thức hệ số (lấy thêm hệ số phụ cấp do cán bộ không chuyên trách nhập hệ số vào hesopc)
                                $heso += $canbo['hesopc'];
                                $m_cb_kn[$i][$mapc] = round($heso * $m_cb_kn[$i][$mapc] / 100, session('admin')->lamtron);
                                //$tonghs += $m_cb_kn[$i][$mapc];
                                $m_cb_kn[$i]['st_' . $mapc] = round($m_cb_kn[$i][$mapc] * $inputs['luongcoban']);
                                //$tien += $m_cb_kn[$i]['st_' . $mapc];
                            } else {
                                $m_cb_kn[$i]['st_pcthni'] = $m_cb_kn[$i]['pcthni'] * $inputs['luongcoban'];
                                //$tonghs += $m_cb_kn[$i]['pcthni'];
                                //$tien += $m_cb_kn[$i]['st_pcthni'];
                            }
                            break;
                        }
                    default: { //trường hợp còn lại (ẩn,...)
                            $m_cb_kn[$i][$mapc] = 0;
                            $a_pc[$k]['sotien'] = 0;
                            break;
                        }
                }

                $tien += $m_cb_kn[$i]['st_' . $mapc];
                if ($m_cb_kn[$i][$mapc] < 500) {
                    $tonghs += $m_cb_kn[$i][$mapc];
                }
            }

            //Tính % hưởng cho cán bộ
            if ($m_cb_kn[$i]['pthuong'] < 100) {
                $tonghs = 0;
                $tien = 0;
                foreach ($a_pc as $k => $v) {
                    $mapc = $v['mapc'];
                    $mapc_st = 'st_' . $v['mapc'];

                    $m_cb_kn[$i][$mapc] = round($m_cb_kn[$i][$mapc] * $m_cb_kn[$i]['pthuong'] / 100, session('admin')->lamtron);
                    $m_cb_kn[$i][$mapc_st] = round($m_cb_kn[$i][$mapc_st] * $m_cb_kn[$i]['pthuong'] / 100, 0);

                    $tonghs += $m_cb_kn[$i][$mapc];
                    $tien += $m_cb_kn[$i][$mapc_st];
                }
            }

            // dd($a_thaisan);
            //Tính thai sản cho cán bộ kiêm nhiệm
            $thaisan = isset($a_thaisan[$m_cb_kn[$i]['macanbo']]) ? true : false;
            $m_cb_kn[$i]['ghichu'] = '';
            if ($thaisan) {
                $tien = $tonghs = 0;
                $m_cb_kn[$i]['ghichu'] .= 'Nghỉ thai sản từ ' . getDayVn($a_thaisan[$m_cb_kn[$i]['macanbo']]['ngaytu']) . ' đến ' . getDayVn($a_thaisan[$m_cb_kn[$i]['macanbo']]['ngayden']) . ';';
                $m_cb_kn[$i]['congtac'] = 'THAISAN';

                foreach ($a_pc as $k => $v) {
                    if (!in_array($k, $a_ts)) {
                        $m_cb_kn[$i]['st_' . $k] = 0;
                    }

                    //phụ cấp ko tính theo số tiền và đc hưởng
                    if ($m_cb_kn[$i][$k] < 10000 && $m_cb_kn[$i]['st_' . $k] > 0) {
                        $tonghs += $m_cb_kn[$i][$k];
                    }
                    $tien += $m_cb_kn[$i]['st_' . $k];
                }
            }

            $m_cb_kn[$i]['tonghs'] = $tonghs;
            $m_cb_kn[$i]['ttl'] = $tien;

            if ($m_cb_kn[$i]['baohiem'] == 1) {
                $m_cb_kn[$i]['stbhxh'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['bhxh'], 0);
                $m_cb_kn[$i]['stbhyt'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['bhyt'], 0);
                $m_cb_kn[$i]['stkpcd'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['kpcd'], 0);
                $m_cb_kn[$i]['stbhtn'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['bhtn'], 0);
                $m_cb_kn[$i]['stbhxh_dv'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['bhxh_dv'], 0);
                $m_cb_kn[$i]['stbhyt_dv'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['bhyt_dv'], 0);
                $m_cb_kn[$i]['stkpcd_dv'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['kpcd_dv'], 0);
                $m_cb_kn[$i]['stbhtn_dv'] = round($inputs['luongcoban'] * $m_cb_kn[$i]['bhtn_dv'], 0);

                $m_cb_kn[$i]['ttbh'] = $m_cb_kn[$i]['stbhxh'] + $m_cb_kn[$i]['stbhyt'] + $m_cb_kn[$i]['stkpcd'] + $m_cb_kn[$i]['stbhtn'];
                $m_cb_kn[$i]['ttbh_dv'] = $m_cb_kn[$i]['stbhxh_dv'] + $m_cb_kn[$i]['stbhyt_dv'] + $m_cb_kn[$i]['stkpcd_dv'] + $m_cb_kn[$i]['stbhtn_dv'];
            } else { //cán bộ ko đóng bảo hiểm =>set tỷ lệ bảo hiểm = 0
                $m_cb_kn[$i]['bhxh'] = 0;
                $m_cb_kn[$i]['bhyt'] = 0;
                $m_cb_kn[$i]['kpcd'] = 0;
                $m_cb_kn[$i]['bhtn'] = 0;
                $m_cb_kn[$i]['bhxh_dv'] = 0;
                $m_cb_kn[$i]['bhyt_dv'] = 0;
                $m_cb_kn[$i]['kpcd_dv'] = 0;
                $m_cb_kn[$i]['bhtn_dv'] = 0;
            }
            $m_cb_kn[$i]['luongtn'] = $m_cb_kn[$i]['ttl'] - $m_cb_kn[$i]['ttbh'];
            $a_kn_canbo[] = $m_cb_kn[$i];
        }
        //Mảng chứa các cột bỏ để chạy hàm insert
        $a_col_cbkn = array('id', 'bac', 'baohiem', 'macongtac', 'pthuong', 'theodoi', 'phanloai', 'khongnopbaohiem'); //'manguonkp',
        $a_kn_canbo = unset_key($a_kn_canbo, $a_col_cbkn);
        //dd($a_kn_canbo);
        foreach (array_chunk($a_kn_canbo, 50) as $data) {
            //bangluong_ct::insert($data);
            (new data())->storeBangLuong($inputs['thang'], $data);
        }
        //dd($a_data_canbo);
        //dd($a_kn_canbo);
    }

    function store_truylinh(Request $request)
    { //chưa tính theo định mức
        if (Session::has('admin')) {
            //lương cơ bản và nguồn lấy trong chi tiết truy lĩnh
            $inputs = $request->all();
            //dd($inputs);
            $inputs['mabl'] = $inputs['mabl_truylinh'];
            $inputs['thang'] = $inputs['thang_truylinh'];
            $inputs['nam'] = $inputs['nam_truylinh'];
            $inputs['noidung'] = $inputs['noidung_truylinh'];
            $inputs['linhvuchoatdong'] = $inputs['linhvuchoatdong_truylinh'];
            //$inputs['manguonkp'] = $inputs['manguonkp_truylinh'];
            //$inputs['luongcoban'] = $inputs['luongcoban_truylinh'];
            $inputs['phanloai'] = $inputs['phanloai_truylinh'];
            $inputs['nguoilap'] = $inputs['nguoilap_truylinh'];
            $inputs['ngaylap'] = $inputs['ngaylap_truylinh'];
            $inputs['phantramhuong'] = 100;

            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if ($model != null) {
                //$inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
            } else {
                //insert
                $madv = session('admin')->madv;
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;
                $ngaylap = Carbon::create($inputs['nam'], $inputs['thang'], '01')->addMonth(1)->addDay(-1);
                //$ngaylap = $inputs['nam'] . '-' . $inputs['thang'] . '-01';
                $model_canbo = hosotruylinh::join('hosotruylinh_nguon', 'hosotruylinh.maso', '=', 'hosotruylinh_nguon.maso')
                    ->select('hosotruylinh.*', 'hosotruylinh_nguon.manguonkp', 'hosotruylinh_nguon.luongcoban')
                    ->where('hosotruylinh.madv', $madv)
                    ->where('hosotruylinh.ngayden', '<=', $ngaylap)->wherenull('hosotruylinh.mabl')->get();
                //dd($model_canbo);

                $a_hoso = hosocanbo::select('mapb', 'mact', 'stt', 'macanbo', 'macvcq', 'bhxh', 'bhyt', 'bhtn', 'kpcd', 'bhxh_dv', 'bhyt_dv', 'bhtn_dv', 'kpcd_dv')
                    ->where('madv', session('admin')->madv)->get()->keyby('macanbo')->toarray();

                $a_goc = array('heso', 'vuotkhung', 'pccv');
                $model_phucap = dmphucap_donvi::where('madv', session('admin')->madv)
                    ->wherenotin('mapc', array('hesott'))->get();
                //Tạo bảng lương

                $ngaycong = session('admin')->songaycong;
                //$ngaycong = dmdonvi::where('madv', $madv)->first()->songaycong;
                //dd($model_canbo->toarray());
                $a_ct = array_column(dmphanloaict::all()->toArray(), 'macongtac', 'mact');

                foreach ($model_canbo as $cb) {
                    if (!isset($a_hoso[$cb->macanbo])) {
                        continue;
                    }
                    $cb->macongtac = isset($a_ct[$cb->mact]) ? $a_ct[$cb->mact] : '';
                    $hoso = $a_hoso[$cb->macanbo];

                    //Gán tham số mặc định
                    $cb->mabl = $inputs['mabl'];
                    //$cb->vuotkhung = 0;//đơn vị tạo trước update
                    $cb->sunghiep = null;
                    if ($cb->mact == '' || $cb->mact == null) {
                        $cb->mact = $hoso['mact'];
                        $cb->macvcq = $hoso['macvcq'];
                        $cb->mapb = $hoso['macvcq'];
                        $cb->stt = $hoso['stt'];
                    }
                    $cb->bhxh = floatval($hoso['bhxh']) / 100;
                    $cb->bhyt = floatval($hoso['bhyt']) / 100;
                    $cb->kpcd = floatval($hoso['kpcd']) / 100;
                    $cb->bhtn = floatval($hoso['bhtn']) / 100;
                    $cb->bhxh_dv = floatval($hoso['bhxh_dv']) / 100;
                    $cb->bhyt_dv = floatval($hoso['bhyt_dv']) / 100;
                    $cb->kpcd_dv = floatval($hoso['kpcd_dv']) / 100;
                    $cb->bhtn_dv = floatval($hoso['bhtn_dv']) / 100;
                    $cb->congtac = 'TRUYLINH';

                    if (getDateTime($cb->ngayden) == null) {
                        $cb->ngayden = $ngaylap;
                    }
                    $cb->heso = round($cb->heso * $cb->pthuong / 100, session('admin')->lamtron);
                    $cb->vuotkhung = ($cb->heso * $cb->vuotkhung) / 100;

                    $tonghs = $tongtien = 0;
                    foreach ($model_phucap as $ct) {
                        $mapc = $ct->mapc;
                        $mapc_st = 'st_' . $ct->mapc;
                        $tien = 0;
                        $ct->stbhxh = 0;
                        $ct->stbhyt = 0;
                        $ct->stkpcd = 0;
                        $ct->stbhtn = 0;
                        $ct->stbhxh_dv = 0;
                        $ct->stbhyt_dv = 0;
                        $ct->stkpcd_dv = 0;
                        $ct->stbhtn_dv = 0;
                        switch (getDbl($ct->phanloai)) {
                            case 0: { //hệ số
                                    $tonghs += $cb->$mapc;
                                    $tien = round($cb->$mapc * $cb->luongcoban, 0);
                                    break;
                                }
                            case 1: { //số tiền
                                    $tongtien += chkDbl($cb->$mapc);
                                    $tien = chkDbl($cb->$mapc);
                                    break;
                                }
                            case 2: { //phần trăm
                                    $heso = 0;
                                    if ($mapc != 'vuotkhung') { //vượt khung đã tính ở trên
                                        foreach (explode(',', $ct->congthuc) as $cthuc) {
                                            if ($cthuc != '')
                                                $heso += $cb->$cthuc;
                                        }
                                        $cb->$mapc = round(($heso * $cb->$mapc) / 100, session('admin')->lamtron);
                                    }

                                    $tonghs += $cb->$mapc;
                                    $tien = round($cb->$mapc * $cb->luongcoban, 0);
                                    break;
                                }
                            default: { //trường hợp còn lại (ẩn,...)
                                    $cb->$mapc = 0;
                                    break;
                                }
                        }

                        //tiền công 1 tháng để tính bh
                        $ct->sotien = $tien;
                        //Tính lại số tiền để gán vào cột phụ cấp (theo ngày + tháng)
                        $cb->$mapc_st = round(($tien / $ngaycong) * $cb->ngaytl, 0) + $tien * $cb->thangtl;
                        //tính bảo hiểm
                        if (
                            $ct->baohiem == 1 &&
                            ($cb->maphanloai != 'KHAC' || ($cb->maphanloai == 'KHAC' && !in_array($mapc, $a_goc)))
                        ) {
                            $ct->stbhxh = round($ct->sotien * $cb->bhxh, 0);
                            $ct->stbhyt = round($ct->sotien * $cb->bhyt, 0);
                            $ct->stkpcd = round($ct->sotien * $cb->kpcd, 0);
                            $ct->stbhtn = round($ct->sotien * $cb->bhtn, 0);
                            $ct->ttbh = $ct->stbhxh + $ct->stbhyt + $ct->stkpcd + $ct->stbhtn;
                            $ct->stbhxh_dv = round($ct->sotien * $cb->bhxh_dv, 0);
                            $ct->stbhyt_dv = round($ct->sotien * $cb->bhyt_dv, 0);
                            $ct->stkpcd_dv = round($ct->sotien * $cb->kpcd_dv, 0);
                            $ct->stbhtn_dv = round($ct->sotien * $cb->bhtn_dv, 0);
                            $ct->ttbh_dv = $ct->stbhxh_dv + $ct->stbhyt_dv + $ct->stkpcd_dv + $ct->stbhtn_dv;
                        }
                    }

                    //phân loại truy lĩnh khác : hệ số cơ sở set = 0;heso, vuotkhung, pccv
                    if ($cb->maphanloai == 'KHAC') {
                        $tonghs = $tonghs - $cb->heso - $cb->vuotkhung - $cb->pccv;
                    }
                    $cb->tonghs = $tonghs;

                    //Tính lại số tiền để gán vào cột phụ cấp

                    //1 tháng
                    if ($cb->macongtac == 'KHONGCT') { //cán bộ KOCT đóng bảo hiểm hệ số 1
                        $stbhxh = round($cb->luongcoban * $cb->bhxh);
                        $stbhyt = round($cb->luongcoban * $cb->bhyt);
                        $stkpcd = round($cb->luongcoban * $cb->kpcd);
                        $stbhtn = round($cb->luongcoban * $cb->bhtn);

                        $stbhxh_dv = round($cb->luongcoban * $cb->bhxh_dv);
                        $stbhyt_dv = round($cb->luongcoban * $cb->bhyt_dv);
                        $stkpcd_dv = round($cb->luongcoban * $cb->kpcd_dv);
                        $stbhtn_dv = round($cb->luongcoban * $cb->bhtn_dv);
                    } else {
                        $stbhxh = $model_phucap->sum('stbhxh');
                        $stbhyt = $model_phucap->sum('stbhyt');
                        $stkpcd = $model_phucap->sum('stkpcd');
                        $stbhtn = $model_phucap->sum('stbhtn');
                        $stbhxh_dv = $model_phucap->sum('stbhxh_dv');
                        $stbhyt_dv = $model_phucap->sum('stbhyt_dv');
                        $stkpcd_dv = $model_phucap->sum('stkpcd_dv');
                        $stbhtn_dv = $model_phucap->sum('stbhtn_dv');
                    }

                    if ($cb->ngaytl >= $ngaycong / 2) {
                        $cb->stbhxh = round(($stbhxh * $cb->ngaytl) / $ngaycong + $stbhxh * $cb->thangtl);
                        $cb->stbhyt = round(($stbhyt * $cb->ngaytl) / $ngaycong + $stbhyt * $cb->thangtl);
                        $cb->stkpcd = round(($stkpcd * $cb->ngaytl) / $ngaycong + $stkpcd * $cb->thangtl);
                        $cb->stbhtn = round(($stbhtn * $cb->ngaytl) / $ngaycong + $stbhtn * $cb->thangtl);

                        $cb->stbhxh_dv = round(($stbhxh_dv * $cb->ngaytl) / $ngaycong + $stbhxh_dv * $cb->thangtl);
                        $cb->stbhyt_dv = round(($stbhyt_dv * $cb->ngaytl) / $ngaycong + $stbhyt_dv * $cb->thangtl);
                        $cb->stkpcd_dv = round(($stkpcd_dv * $cb->ngaytl) / $ngaycong + $stkpcd_dv * $cb->thangtl);
                        $cb->stbhtn_dv = round(($stbhtn_dv * $cb->ngaytl) / $ngaycong + $stbhtn_dv * $cb->thangtl);
                    } else {
                        $cb->stbhxh = round($stbhxh * $cb->thangtl);
                        $cb->stbhyt = round($stbhyt * $cb->thangtl);
                        $cb->stkpcd = round($stkpcd * $cb->thangtl);
                        $cb->stbhtn = round($stbhtn * $cb->thangtl);

                        $cb->stbhxh_dv = round($stbhxh_dv * $cb->thangtl);
                        $cb->stbhyt_dv = round($stbhyt_dv * $cb->thangtl);
                        $cb->stkpcd_dv = round($stkpcd_dv * $cb->thangtl);
                        $cb->stbhtn_dv = round($stbhtn_dv * $cb->thangtl);
                    }

                    $thangtl = round($cb->luongcoban * $tonghs) + $tongtien;
                    //$ngaytl = round($thangtl / $ngaycong, 0);

                    $cb->ttbh = $cb->stbhxh + $cb->stbhyt + $cb->stkpcd + $cb->stbhtn;
                    $cb->ttbh_dv = $cb->stbhxh_dv + $cb->stbhyt_dv + $cb->stkpcd_dv + $cb->stbhtn_dv;
                    $cb->ttl = round($thangtl * $cb->thangtl + ($cb->ngaytl * $thangtl) / $ngaycong, 0);
                    $cb->luongtn = $cb->ttl - $cb->ttbh;
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    //ưng mỗi phân loại set về hệ số: chucvu => pccv=heso; tnn: pctnn=heso
                    if ($cb->maphanloai == 'KHAC') {
                        $cb->heso = 0;
                        $cb->vuotkhung = 0;
                        $cb->pccv = 0;

                        $cb->st_heso = 0;
                        $cb->st_vuotkhung = 0;
                        $cb->st_pccv = 0;
                    }

                    if ($cb->maphanloai == 'CHUCVU') {
                        $cb->pccv = $cb->heso;
                        $cb->heso = 0;
                        $cb->st_pccv = $cb->st_heso;
                        $cb->st_heso = 0;
                    }

                    if ($cb->maphanloai == 'TNN') {
                        $cb->pctnn = $cb->heso;
                        $cb->heso = 0;
                        $cb->st_pctnn = $cb->st_heso;
                        $cb->st_heso = 0;
                    }
                    $cb->ghichu = $cb->noidung;
                    $kq = $cb->toarray();
                    unset($kq['id']);
                    //dd($kq);
                    (new data())->createBangLuong($inputs['thang'], $kq);
                }
                //dd($model_canbo->toarray());
                $model_canbo = $model_canbo->map(function ($data) {
                    return collect($data->toArray())
                        ->only(['maso'])
                        ->all();
                });
                bangluong::create($inputs);
                hosotruylinh::wherein('maso', $model_canbo->toarray())->update(['mabl' => $inputs['mabl']]);
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'] . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function store_truc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_truc'];
            $inputs['thang'] = $inputs['thang_truc'];
            $inputs['nam'] = $inputs['nam_truc'];
            $inputs['noidung'] = $inputs['noidung_truc'];
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban_truc']);
            $inputs['phanloai'] = $inputs['phanloai_truc'];
            $inputs['nguoilap'] = $inputs['nguoilap_truc'];
            $inputs['ngaylap'] = $inputs['ngaylap_truc'];
            $inputs['manguonkp'] = $inputs['manguonkp_truc'];
            $inputs['linhvuchoatdong'] = $inputs['linhvuchoatdong_truc'];
            //$inputs['songay'] = getDbl($inputs['songay_truc']);
            $inputs['phantramhuong'] = 100;
            //dd($inputs);
            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if ($model != null) {
                $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
            } else {
                //insert
                $madv = session('admin')->madv;
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;

                $model = hosotruc::select(
                    'macanbo',
                    'tencanbo',
                    'songaytruc',
                    'songaycong',
                    'heso',
                    'vuotkhung',
                    'pccv',
                    'pcdh',
                    'pctn',
                    'pcudn',
                    'pcud61',
                    'pcld',
                    'pclade',
                    'maso'
                )
                    ->where('madv', $madv)->where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->get();
                $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)
                    ->wherein('mapc', ['pcdh', 'pctn', 'pcudn', 'pcud61', 'pcld', 'pclade'])
                    ->get();
                $m_hoso = hosocanbo::select('macanbo', 'stt', 'macvcq', 'mapb', 'mact')->where('madv', session('admin')->madv)
                    ->get()->keyby('macanbo')->toarray();

                $a_data = array();
                foreach ($model as $cb) {
                    //Gán tham số mặc định
                    $cb->mabl = $inputs['mabl'];
                    $cb->stt = 99;
                    $cb->macvcq = $cb->mapb = $cb->mact = '';
                    $cb->luongcoban = $inputs['luongcoban'];
                    $cb->manguonkp = $inputs['manguonkp'];
                    if (isset($m_hoso[$cb->macanbo])) {
                        $hoso = $m_hoso[$cb->macanbo];
                        $cb->stt = $hoso['stt'];
                        $cb->macvcq = $hoso['macvcq'];
                        $cb->mapb = $hoso['mapb'];
                        $cb->mact = $hoso['mact'];
                    }

                    $cb->vuotkhung = round($cb->heso * $cb->vuotkhung / 100, session('admin')->lamtron);
                    $hesotinh = $cb->songaytruc / $cb->songaycong;
                    $tonghs = $tongtt = 0;

                    foreach ($model_pc as $pc) {
                        $mapc = $pc->mapc;
                        $mapc_st = 'st_' . $pc->mapc;
                        switch (getDbl($pc->phanloai)) {
                            case 0: { //hệ số
                                    //$cb->$mapc = $cb->$mapc * $hesotinh;
                                    $cb->$mapc = round($cb->$mapc * $hesotinh, session('admin')->lamtron);
                                    $cb->$mapc_st = round($cb->$mapc * $cb->luongcoban);
                                    $tonghs += $cb->$mapc;
                                    $tongtt += $cb->$mapc_st;
                                    break;
                                }
                            case 1: { //số tiền
                                    $cb->$mapc = round($cb->$mapc * $hesotinh);
                                    $cb->$mapc_st = $cb->$mapc;
                                    $tongtt += $cb->$mapc_st;
                                    break;
                                }
                            case 2: { //phần trăm
                                    $heso = 0;
                                    //ko có vượt khung
                                    foreach (explode(',', $pc->congthuc) as $cthuc) {
                                        if ($cthuc != '')
                                            $heso += $cb->$cthuc;
                                    }
                                    $cb->$mapc = round($heso * $cb->$mapc * $hesotinh / 100, session('admin')->lamtron);
                                    $cb->$mapc_st = round($cb->$mapc * $cb->luongcoban);
                                    $tonghs += $cb->$mapc;
                                    $tongtt += $cb->$mapc_st;
                                    break;
                                }
                            default: { //trường hợp còn lại (ẩn,...)
                                    //$cb->$mapc = 0;
                                    break;
                                }
                        }
                    }
                    $cb->tonghs = $tonghs;
                    $cb->ttl = $tongtt;
                    $cb->luongtn = $tongtt;
                    $cb->heso = 0;
                    $cb->vuotkhung = 0;
                    $cb->pccv = 0;
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    $a_data[] = $cb->toarray();
                }
                //dd($a_data);
                foreach (array_chunk($a_data, 50)  as $data) {
                    //bangluong_ct::insert($data);
                    (new data())->storeBangLuong($inputs['thang'], $data);
                }
                bangluong::create($inputs);
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'] . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function store_ctp(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if (isset($model)) {
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
            } else {
                //insert
                $madv = session('admin')->madv;
                //lấy ngày cuối tháng
                $ngaycuoithang = Carbon::create($inputs['nam'], $inputs['thang'] + 1, 0)->toDateString();
                //ds cán bộ thôi công tác
                $a_cbn = hosothoicongtac::select('macanbo')->where('madv', $madv)
                    ->where(function ($qr) use ($ngaycuoithang) {
                        $qr->where('ngaynghi', '<=', $ngaycuoithang)->orWhereNull('ngaynghi');
                        //})->toSql();dd($a_cbn);
                    })->get()->toarray();
                //ds cán bộ

                $m_cb = hosocanbo::select('macvcq', 'mapb', 'mact', 'macanbo', 'tencanbo', 'ngaybc', 'pcctp')
                    ->where('madv', $madv)->where('pcctp', '>', 0)->wherenotin('macanbo', $a_cbn)->get();
                //dd($m_cb->toarray());
                $a_data = array();
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;
                //$ngaylap = Carbon::create($inputs['nam'],$inputs['thang'],'01');
                foreach ($m_cb as $cb) {
                    if (getDayVn($cb->ngaybc) != '' && $cb->ngaybc > $ngaycuoithang) {
                        continue;
                    }
                    $cb->mabl = $inputs['mabl'];
                    $cb->congtac = $inputs['phanloai'];
                    $cb->ttl =  $cb->pcctp;
                    $cb->heso =  $cb->pcctp;
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    $kq = $cb->toarray();
                    unset($kq['ngaybc']);
                    unset($kq['pcctp']);
                    $a_data[] = $kq;
                    //lưu vào db
                    //bangluong_truc::create($kq);
                }
                foreach (array_chunk($a_data, 100)  as $data) {
                    bangluong_truc::insert($data);
                }
                bangluong::create($inputs);
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'] . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function store_trichnop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if ($model != null) {
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $inputs['thang'] . '&nam=' . $inputs['nam']);
            } else {
                $madv = session('admin')->madv;
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['maquy'] = $inputs['mabl'];
                $inputs['madv'] = $madv;
                //dd($inputs);
                $model_canbo = (new data())->getBangluong_ct($inputs['thang'], $inputs['mabl_trichnop']);
                $a_phucap = array_column(dmphucap_donvi::where('madv', session('admin')->madv)
                    ->where('phanloai', '<', '3')->get()->toarray(), 'mapc');
                $a_data = array();

                switch ($inputs['pptinh']) {
                    case 'sotien': {
                            foreach ($model_canbo as $cb) {
                                $trichnop = round(chkDbl($inputs['sotien']), $inputs['lamtron']);
                                $a_data[] = array(
                                    'mabl' => $inputs['mabl'], 'congtac' => $inputs['phanloai'], 'ttl' => $trichnop,
                                    'macvcq' => $cb->macvcq, 'mapb' => $cb->mapb, 'mact' => $cb->mact, 'macanbo' => $cb->macanbo,
                                    'tencanbo' => $cb->tencanbo, 'stt' => $cb->stt
                                );
                            }
                            break;
                        }
                    case 'ngaycong': {
                            $inputs['tongngaycong'] = chkDbl($inputs['tongngaycong']) < 1 ? 1 : chkDbl($inputs['tongngaycong']);
                            $inputs['phantramtinh'] = chkDbl($inputs['phantramtinh']) <= 0 || chkDbl($inputs['phantramtinh']) > 100
                                ? 100 : chkDbl($inputs['phantramtinh']);
                            $inputs['ngaycong'] = chkDbl($inputs['ngaycong']);

                            $heso = ($inputs['ngaycong'] * $inputs['phantramtinh']) / ($inputs['tongngaycong'] * 100);
                            foreach ($model_canbo as $cb) {
                                $sotien = 0;
                                if (!isset($inputs['phucap']) || in_array('ALL', $inputs['phucap'])) {
                                    foreach ($a_phucap as $pc) {
                                        $pc_st = 'st_' . $pc;
                                        $sotien += $cb->$pc_st;
                                    }
                                } else {
                                    foreach ($inputs['phucap'] as $pc) {
                                        $pc_st = 'st_' . $pc;
                                        $sotien += $cb->$pc_st;
                                    }
                                }
                                //dd($inputs);
                                $trichnop = round($sotien * $heso, $inputs['lamtron']);

                                $a_data[] = array(
                                    'mabl' => $inputs['mabl'], 'congtac' => $inputs['phanloai'], 'ttl' => $trichnop,
                                    'macvcq' => $cb->macvcq, 'mapb' => $cb->mapb, 'mact' => $cb->mact, 'macanbo' => $cb->macanbo,
                                    'tencanbo' => $cb->tencanbo, 'stt' => $cb->stt
                                );
                            }
                            break;
                        }
                    case 'phantram': {
                            $inputs['phantram'] = chkDbl($inputs['phantram']);
                            foreach ($model_canbo as $cb) {
                                $sotien = 0;
                                if (!isset($inputs['phucap']) || in_array('ALL', $inputs['phucap'])) {
                                    foreach ($a_phucap as $pc) {
                                        $pc_st = 'st_' . $pc;
                                        $sotien += $cb->$pc_st;
                                    }
                                } else {
                                    foreach ($inputs['phucap'] as $pc) {
                                        $pc_st = 'st_' . $pc;
                                        $sotien += $cb->$pc_st;
                                    }
                                }
                                $trichnop = round(($sotien / 100) * $inputs['phantram'], $inputs['lamtron']);
                                $a_data[] = array(
                                    'mabl' => $inputs['mabl'], 'congtac' => $inputs['phanloai'], 'ttl' => $trichnop,
                                    'macvcq' => $cb->macvcq, 'mapb' => $cb->mapb, 'mact' => $cb->mact, 'macanbo' => $cb->macanbo,
                                    'tencanbo' => $cb->tencanbo, 'stt' => $cb->stt
                                );
                            }

                            break;
                        }
                }

                foreach (array_chunk($a_data, 100) as $data) {
                    bangluong_truc::insert($data);
                }
                bangluong::create($inputs);

                return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'] . '&mapb=');
            }
        } else
            return view('errors.notlogin');
    }

    function store_chikhac(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
            $inputs['phantramhuong'] = 100;
            $model = bangluong::where('mabl', $inputs['mabl'])->first();
            if ($model != null) {
                $inputs['luongcoban'] = getDbl($inputs['luongcoban']);
                $model->update($inputs);
                return redirect('/chuc_nang/bang_luong/danh_sach');
            } else {
                //insert
                $madv = session('admin')->madv;
                //lấy ngày cuối tháng
                $ngaycuoithang = Carbon::create($inputs['nam'], $inputs['thang'] + 1, 0)->toDateString();
                //ds cán bộ thôi công tác
                $a_cbn = hosothoicongtac::select('macanbo')->where('madv', $madv)->where(function ($qr) use ($ngaycuoithang) {
                    $qr->where('ngaynghi', '<=', $ngaycuoithang)->orWhereNull('ngaynghi');
                    //})->toSql();dd($a_cbn);
                })->get()->toarray();
                //ds cán bộ
                $m_cb = hosocanbo::select('macvcq', 'mapb', 'mact', 'macanbo', 'tencanbo', 'ngaybc')->where('madv', $madv)->wherenotin('macanbo', $a_cbn)->get();
                dd($inputs);
                $a_data = array();
                $inputs['mabl'] = $madv . '_' . getdate()[0];
                $inputs['madv'] = $madv;
                //$ngaylap = Carbon::create($inputs['nam'],$inputs['thang'],'01');
                foreach ($m_cb as $cb) {
                    if (getDayVn($cb->ngaybc) != '' && $cb->ngaybc > $ngaycuoithang) {
                        continue;
                    }
                    $cb->mabl = $inputs['mabl'];
                    $cb->congtac = $inputs['congtac'];
                    $cb->ttl =  $inputs['luongcoban'];
                    //lưu vào bảng phụ cấp theo lương (chỉ có hệ số)
                    $kq = $cb->toarray();
                    unset($kq['ngaybc']);
                    $a_data[] = $kq;
                    //lưu vào db
                    //bangluong_truc::create($kq);
                }
                foreach (array_chunk($a_data, 100)  as $data) {
                    bangluong_truc::insert($data);
                }
                bangluong::create($inputs);
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $inputs['mabl'] . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function show(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'phanloai')->where('mabl', $inputs['mabl'])->first();
            $model_nhomct = dmphanloaicongtac::select('macongtac', 'tencongtac')->get();
            $model_tenct = dmphanloaict::select('tenct', 'macongtac', 'mact')->get();

            if ($m_bl->phanloai == 'TRICHNOP' || $m_bl->phanloai == 'CTPHI') {
                $model = bangluong_truc::where('mabl', $inputs['mabl'])->get();
                $a_pl = getPhanLoaiBangLuong();
                $m_bl->tenphanloai = isset($a_pl[$m_bl->phanloai]) ? $a_pl[$m_bl->phanloai] : '';
                return view('manage.bangluong.bangluong_truc')
                    ->with('furl', '/chuc_nang/bang_luong/')
                    ->with('model', $model)
                    ->with('m_bl', $m_bl)
                    ->with('pageTitle', 'Bảng lương chi tiết');
            }
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
                $model = $model->where('mapb', $inputs['mapb']);
            }

            return view('manage.bangluong.bangluong')
                ->with('furl', '/chuc_nang/bang_luong/')
                ->with('model', $model)
                ->with('m_bl', $m_bl)
                ->with('model_nhomct', $model_nhomct)
                ->with('model_tenct', $model_tenct)
                ->with('a_cv', getChucVuCQ(false))
                ->with('a_ct', getPhanLoaiCT(false))
                ->with('inputs', $inputs)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function destroy($id)
    {
        if (Session::has('admin')) {
            $model = bangluong::find($id);
            if (isset($model) && $model->phanloai == 'TRUYLINH') {
                hosotruylinh::where('mabl', $model->mabl)
                    ->update(['mabl' => null]);
            }
            //bangluong_ct::where('mabl', $model->mabl)->delete();
            (new data())->destroyBangluong_ct($model->thang, $model->mabl);
            //bangluong_phucap::where('mabl', $model->mabl)->delete();
            //bangluong_truc::where('mabl', $model->mabl)->delete();
            $model->delete();
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $model->thang . '&nam=' . $model->nam);
        } else
            return view('errors.notlogin');
    }

    function destroy_ct(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);
            //dd($model);
            hosotruylinh::where('mabl', $model->mabl)
                ->where('macanbo', $model->macanbo)
                ->update(['mabl' => null]);
            $model->delete();
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $m_bl->mabl . '&mapb=');
            //return redirect('/chuc_nang/bang_luong/bang_luong?mabl='.$model->mabl.'&mapb='.$model->mapb);
            //return redirect('/chuc_nang/bang_luong/maso='.$model->mabl);
        } else
            return view('errors.notlogin');
    }

    function destroy_truc($id)
    {
        if (Session::has('admin')) {
            $model = bangluong_truc::find($id);
            $model->delete();
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $model->mabl . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function getinfor_nguonkp(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();

        $inputs['luongcb'] = getGeneralConfigs()['luongcb'];
        $inputs['dinhmuc'] = 0;
        $dinhmuc = nguonkinhphi_dinhmuc::where('manguonkp', $inputs['manguonkp'])->where('madv', session('admin')->madv)->first();
        $maso = $dinhmuc->maso ?? '';
        $dinhmuc_ct = nguonkinhphi_dinhmuc_ct::where('maso', $maso)->get();
        if (count($dinhmuc_ct) > 0) {
            $inputs['dinhmuc'] = 1;
            $inputs['luongcb'] = $dinhmuc->luongcoban;
        }

        die(json_encode($inputs));
    }

    function getinfo(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $model = bangluong::where('mabl', $inputs['mabl'])->first();
        die($model);
    }

    function get_chitiet(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }
        $inputs = $request->all();
        $mapc = $inputs['mapc'];
        $mapc_st = 'st_' . $inputs['mapc'];
        $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
        $model_luong = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);
        $model = dmphucap::where('mapc', $inputs['mapc'])->first();
        $model->heso = $model_luong->$mapc;
        $model->sotien = $model_luong->$mapc_st;
        $model->luongcoban = $model_luong->luongcoban;
        die($model);
    }

    function detail(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model_bangluong = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct_cb($model_bangluong->thang, $inputs['maso']);
            $m_nb = ngachluong::where('msngbac', $model->msngbac)->first();
            $model->tennb = isset($m_nb) ? $m_nb->tenngachluong : '';

            //$a_goc = array('heso','vuotkhung','hesott','hesopc');
            $model_pc = dmphucap_donvi::where('madv', $model_bangluong->madv)->where('phanloai', '<', '3')->get();
            //dd($model_pc);

            if ($model_bangluong->phanloai == 'TRUYLINH') {
                $model_truylinh = hosotruylinh::where('macanbo', $model->macanbo)->where('mabl', $model->mabl)->first();
                $model->ngaytu = $model_truylinh->ngaytu;
                $model->ngayden = $model_truylinh->ngayden;
                foreach ($model_pc as $pc) {
                    $mapc = $pc->mapc;
                    $mapc_st = 'st_' . $mapc;
                    $pc->heso = $model->$mapc;
                    $pc->sotien = $model->$mapc_st;
                    $pc->macanbo = $model->macanbo;
                    $pc->mabl = $model->mabl;
                }

                return view('manage.bangluong.chitiet_truylinh')
                    ->with('furl', '/chuc_nang/bang_luong/')
                    ->with('model', $model)
                    ->with('model_pc', $model_pc->sortby('stt'))
                    ->with('pageTitle', 'Chi tiết bảng lương');
            } else {
                $model->luongcoban = $model_bangluong->luongcoban;
                //dd($model);
                foreach ($model_pc as $pc) {
                    $mapc = $pc->mapc;
                    $mapc_st = 'st_' . $mapc;
                    $pc->heso = $model->$mapc;
                    $pc->sotien = $model->$mapc_st;
                    $pc->macanbo = $model->macanbo;
                    $pc->mabl = $model->mabl;
                }

                return view('manage.bangluong.chitiet')
                    ->with('furl', '/chuc_nang/bang_luong/')
                    ->with('model', $model)
                    ->with('model_pc', $model_pc->sortby('stt'))
                    ->with('pageTitle', 'Chi tiết bảng lương');
            }
        } else
            return view('errors.notlogin');
    }

    function updatect(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);
            /* bổ qua vì đã chạy chi tiết phụ cấp
            $model_pc = dmphucap_donvi::where('madv', session('admin')->madv)->get();
            foreach($model_pc as $pc){
                if(isset($inputs[$pc->mapc])){
                    $inputs[$pc->mapc] = chkDbl($inputs[$pc->mapc]);
                }
            }
            */
            $inputs['ttl'] = round(chkDbl($inputs['ttl']));
            $inputs['giaml'] = chkDbl($inputs['giaml']);
            $inputs['tienthuong'] = chkDbl($inputs['tienthuong']);
            $inputs['trichnop'] = chkDbl($inputs['trichnop']);
            $inputs['thuetn'] = chkDbl($inputs['thuetn']);
            $inputs['bhct'] = chkDbl($inputs['bhct']);
            $inputs['stbhxh'] = chkDbl($inputs['stbhxh']);
            $inputs['stbhyt'] = chkDbl($inputs['stbhyt']);
            $inputs['stkpcd'] = chkDbl($inputs['stkpcd']);
            $inputs['stbhtn'] = chkDbl($inputs['stbhtn']);
            $inputs['ttbh'] = chkDbl($inputs['ttbh']);
            $inputs['stbhxh_dv'] = chkDbl($inputs['stbhxh_dv']);
            $inputs['stbhyt_dv'] = chkDbl($inputs['stbhyt_dv']);
            $inputs['stkpcd_dv'] = chkDbl($inputs['stkpcd_dv']);
            $inputs['stbhtn_dv'] = chkDbl($inputs['stbhtn_dv']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['luongtn'] = round(chkDbl($inputs['luongtn']));

            //dd($inputs);
            $model->update($inputs);
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $model->mabl . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function updatect_truylinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);

            $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);

            //$inputs['hesott'] = chkDbl($inputs['hesott']);
            $inputs['luongcoban'] = chkDbl($inputs['luongcoban']);
            $inputs['ttl'] = chkDbl($inputs['ttl']);
            $inputs['stbhxh'] = chkDbl($inputs['stbhxh']);
            $inputs['stbhyt'] = chkDbl($inputs['stbhyt']);
            $inputs['stkpcd'] = chkDbl($inputs['stkpcd']);
            $inputs['stbhtn'] = chkDbl($inputs['stbhtn']);
            $inputs['ttbh'] = chkDbl($inputs['ttbh']);
            $inputs['stbhxh_dv'] = chkDbl($inputs['stbhxh_dv']);
            $inputs['stbhyt_dv'] = chkDbl($inputs['stbhyt_dv']);
            $inputs['stkpcd_dv'] = chkDbl($inputs['stkpcd_dv']);
            $inputs['stbhtn_dv'] = chkDbl($inputs['stbhtn_dv']);
            $inputs['ttbh_dv'] = chkDbl($inputs['ttbh_dv']);
            $inputs['luongtn'] = chkDbl($inputs['luongtn']);

            unset($inputs['id']);
            //dd($inputs);
            $model->update($inputs);
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $model->mabl . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function update_chitiet(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_bl = bangluong::where('mabl', $inputs['mabl_hs'])->first();
            $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id_hs']);
            $m_cb = hosocanbo::where('macanbo', $model->macanbo)->first();
            $model_phucap = dmphucap_donvi::where('madv', $m_bl->madv)
                ->where('phanloai', '<', '3')
                ->wherenotin('mapc', ['hesott'])->get();
            //dd($m_bl);
            $m_thue = dmthuetncn::where('ngayapdung', '<=', $m_bl->ngaylap)->orderby('ngayapdung', 'desc')->first();
            if ($m_thue != null) {
                $banthan = $m_thue->banthan;
                $phuthuoc = $m_thue->phuthuoc;
                $a_mucthue = dmthuetncn_ct::where('sohieu', $m_thue->sohieu)->orderby('muctu')->get()->toarray();
            } else {
                $banthan = $phuthuoc = 0;
                $a_mucthue = [];
            }
            // dd($inputs);
            $a_bh = array_column($model_phucap->where('baohiem', 1)->toarray(), 'mapc');
            $a_dd = array_column($model_phucap->where('dieudong', 1)->toarray(), 'mapc');
            $a_ts = array_column($model_phucap->where('thaisan', 1)->toarray(), 'mapc');
            $a_no = array_column($model_phucap->where('nghiom', 1)->toarray(), 'mapc');
            $a_thue = array_column($model_phucap->where('thuetn', 1)->toarray(), 'mapc');
            $a_tapsu = array_column($model_phucap->where('tapsu', 1)->toarray(), 'mapc');

            $a_pc = $model_phucap->keyby('mapc')->toarray();
            //dd($inputs);
            //bảng chi trả lương
            if ($m_bl->phanloai == 'BANGLUONG') {
                $mapc_st = 'st_' . $inputs['mapc'];
                $mapc = $inputs['mapc'];
                $model->$mapc = chkDbl($inputs['heso']);
                $model->$mapc_st = getDbl($inputs['sotien']);

                //các biển lưu để tính lương
                $tien = $tonghs = $baohiem = 0;
                $tienthue = 0;

                foreach ($a_pc as $ma => $val) {
                    $ma_st = 'st_' . $ma;
                    $tien += $model->$ma_st;
                    if ($model->$ma_st > 0 && $model->$ma < 50) { // do 1 số loại phụ cấp lưu lại hệ số làm hệ số gốc
                        $tonghs += $model->$ma;
                    }
                    if ($val['baohiem'] == '1') {
                        $baohiem += $model->$ma_st;
                    }
                    if (in_array($ma, $a_thue)) {
                        $tienthue += $model->$ma_st;
                    }
                }

                $tienthue = $tienthue - $banthan - $phuthuoc * getDbl($m_cb->nguoiphuthuoc);
                if ($tienthue > 0) {
                    foreach ($a_mucthue as $thue) {
                        if ($tienthue > $thue['muctu']) {
                            $model->thuetn += round(($tienthue > $thue['mucden'] ? $thue['mucden'] - $thue['muctu'] : $tienthue - $thue['muctu'])
                                * $thue['phantram'] / 100);
                        }
                    }
                }
                $model->stbhxh = round($model->bhxh * $baohiem, 0);
                $model->stbhyt = round($model->bhyt * $baohiem, 0);
                $model->stkpcd = round($model->kpcd * $baohiem, 0);
                $model->stbhtn = round($model->bhtn * $baohiem, 0);
                $model->ttbh = $model->stbhxh + $model->stbhyt + $model->stkpcd + $model->stbhtn;
                $model->stbhxh_dv = round($model->bhxh_dv * $baohiem, 0);
                $model->stbhyt_dv = round($model->bhyt_dv * $baohiem, 0);
                $model->stkpcd_dv = round($model->kpcd_dv * $baohiem, 0);
                $model->stbhtn_dv = round($model->bhtn_dv * $baohiem, 0);
                $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

                $model->tonghs = $tonghs;
                $model->ttl = $tien;
                $model->luongtn = $model->ttl - $model->ttbh - $model->giaml - $model->thuetn - $model->trichnop + $model->bhct + $model->tienthuong;

                ////                $m_cb[$key]['luongtn'] = $m_cb[$key]['ttl'] - $m_cb[$key]['ttbh'] - $m_cb[$key]['giaml'] - $m_cb[$key]['thuetn'];
                //
                //                dd($model);
                //                $inputs['heso'] = chkDbl($inputs['heso']);
                //                $inputs['luongcb'] = chkDbl($inputs['luongcb']);
                //                $inputs['sotien'] = chkDbl($inputs['sotien']);
                //                //dd($inputs);
                //                //Tính lương mới
                //                $sotien_cl = $inputs['sotien'] - $model->$mapc_st;
                //                $heso_cl = $inputs['heso'] > 1000 ? 0 : $inputs['heso'] - $model->$mapc;//nếu > 1000 - >số tiền ko pai công lại hệ số
                //
                ////                $model->$mapc_st = $inputs['sotien'];
                ////                $model->$mapc = $inputs['heso'];
                //                //Tính lại bao hiểm (các trường hợp thai sản, dai ngày, ko lương => số tiền = 0;
                //                //  => nếu ko đóng bảo thì tỷ lệ bảo hiểm = 0)
                //                if ($model->congtac != 'THAISAN' && $model->congtac != 'DAINGAY' && $model->congtac != 'KHONGLUONG') {
                //                    $baohiem = $model->st_heso + $model->st_vuotkhung + $model->st_pccv + $model->st_pctnn;
                //                    $model->stbhxh = round($model->bhxh * $baohiem, 0);
                //                    $model->stbhyt = round($model->bhyt * $baohiem, 0);
                //                    $model->stkpcd = round($model->kpcd * $baohiem, 0);
                //                    $model->stbhtn = round($model->bhtn * $baohiem, 0);
                //                    $model->ttbh = $model->stbhxh + $model->stbhyt + $model->stkpcd + $model->stbhtn;
                //                    $model->stbhxh_dv = round($model->bhxh_dv * $baohiem, 0);
                //                    $model->stbhyt_dv = round($model->bhyt_dv * $baohiem, 0);
                //                    $model->stkpcd_dv = round($model->kpcd_dv * $baohiem, 0);
                //                    $model->stbhtn_dv = round($model->bhtn_dv * $baohiem, 0);
                //                    $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;
                //                }
                //
                //                $model->tonghs += $heso_cl;
                //                $model->ttl += $sotien_cl;
                //                $model->luongtn = $model->ttl - $model->ttbh - $model->giaml - $model->thuetn - $model->trichnop + $model->bhct + $model->tienthuong;

                //dd($model);
                $model->save();
            }

            //bảng truy lĩnh lương
            if ($m_bl->phanloai == 'TRUYLINH') {
                $mapc = $inputs['mapc'];
                $mapc_st = 'st_' . $inputs['mapc'];

                $inputs['heso'] = chkDbl($inputs['heso']);
                $inputs['luongcb'] = chkDbl($inputs['luongcb']);
                $inputs['sotien'] = chkDbl($inputs['sotien']);
                //dd($inputs);
                //Tính lương mới
                $sotien_cl = $inputs['sotien'] - $model->$mapc_st;
                $heso_cl = $inputs['heso'] > 1000 ? 0 : $inputs['heso'] - $model->$mapc; //nếu > 1000 - >số tiền ko pai công lại hệ số

                $model->$mapc_st = $inputs['sotien'];
                $model->$mapc = $inputs['heso'];
                //Tính lại bao hiểm (các trường hợp thai sản, dai ngày, ko lương => số tiền = 0;
                //  => nếu ko đóng bảo thì tỷ lệ bảo hiểm = 0)
                $baohiem = round(($model->st_heso + $model->st_vuotkhung + $model->st_pccv + $model->st_pctnn) * $model->thangtl);
                if ($model->ngaytl >= session('admin')->songaycong) {
                    $baohiem += round(($model->st_heso + $model->st_vuotkhung + $model->st_pccv + $model->st_pctnn) * $model->ngaytl / session('admin')->songaycong);
                }

                $model->stbhxh = round($model->bhxh * $baohiem, 0);
                $model->stbhyt = round($model->bhyt * $baohiem, 0);
                $model->stkpcd = round($model->kpcd * $baohiem, 0);
                $model->stbhtn = round($model->bhtn * $baohiem, 0);
                $model->ttbh = $model->stbhxh + $model->stbhyt + $model->stkpcd + $model->stbhtn;
                $model->stbhxh_dv = round($model->bhxh_dv * $baohiem, 0);
                $model->stbhyt_dv = round($model->bhyt_dv * $baohiem, 0);
                $model->stkpcd_dv = round($model->kpcd_dv * $baohiem, 0);
                $model->stbhtn_dv = round($model->bhtn_dv * $baohiem, 0);
                $model->ttbh_dv = $model->stbhxh_dv + $model->stbhyt_dv + $model->stkpcd_dv + $model->stbhtn_dv;

                $model->tonghs += $heso_cl;
                $model->ttl += $sotien_cl;
                $model->luongtn = $model->ttl - $model->ttbh - $model->giaml - $model->thuetn - $model->trichnop + $model->bhct + $model->tienthuong;
                //dd($model);
                $model->save();
            }
            //dd($model);

            ketthuc:
            return redirect('/chuc_nang/bang_luong/can_bo?mabl=' . $model->mabl . '&maso=' . $model->id);
        } else
            return view('errors.notlogin');
    }

    function detail_chikhac(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = bangluong_truc::find($inputs['maso']);
            return view('manage.bangluong.chitiet_truc')
                ->with('furl', '/chuc_nang/bang_luong/')
                ->with('model', $model)
                ->with('pageTitle', 'Chi tiết bảng lương');
        } else
            return view('errors.notlogin');
    }

    function updatect_chikhac(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong_truc::find($inputs['id']);
            $model->heso = chkDbl($inputs['heso']);
            $model->ttl = chkDbl($inputs['ttl']);
            $model->save();
            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $model->mabl . '&mapb=');
        } else
            return view('errors.notlogin');
    }

    function get_ct(Request $request)
    {
        if (!Session::has('admin')) {
            $result = array(
                'status' => 'fail',
                'message' => 'permission denied',
            );
            die(json_encode($result));
        }

        $inputs = $request->all();
        $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
        $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);
        die($model);
    }

    function updatect_khenthuong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            if (in_array('ALL', $inputs['mact'])) {
                $mact = 'ALL';
            } else {
                $mact = implode(',', $inputs['mact']);
            }
            $m_bangluong = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct($m_bangluong->thang, $m_bangluong->mabl);
            //$m_cb[$key]['luongtn'] = $m_cb[$key]['ttl'] - $m_cb[$key]['ttbh'] - $m_cb[$key]['giaml'] - $m_cb[$key]['thuetn'];
            foreach ($model as $chitiet) {
                if ($mact != 'ALL' && in_array($chitiet->mact, $inputs['mact'])) {
                    continue;
                }
                $chitiet->save();
            }
            //            dd($model);
            //            $model->heso = chkDbl($inputs['heso']);
            //            $model->ttl = chkDbl($inputs['ttl']);
            //            $model->save();
            return redirect('/chuc_nang/bang_luong/chi_tra?thang=' . $m_bangluong->thang . '&nam=' . $m_bangluong->nam);
        } else
            return view('errors.notlogin');
    }

    function ThemCanBo(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_hs = hosocanbo::where('madv', session('admin')->madv)->where('theodoi', '<', '9')->get()->sortby('stt');
            //dd($inputs);
            if (isset($inputs['macanbo'])) {
                $model = $m_hs->where('macanbo', $inputs['macanbo'])->first();
            } else {
                $model = new hosocanbo();
            }
            $model_bangluong = bangluong::where('mabl', $inputs['mabl'])->first();
            $model_pc = dmphucap_donvi::where('madv', $model_bangluong->madv)->where('phanloai', '<', '3')->get();
            return view('manage.bangluong.ThemCanBo')
                ->with('furl', '/chuc_nang/bang_luong/')
                ->with('model', $model)
                ->with('model_pc', $model_pc->sortby('stt'))
                ->with('a_cb', array_column($m_hs->toarray(), 'tencanbo', 'macanbo'))
                ->with('pageTitle', 'Chi tiết bảng lương');
        } else
            return view('errors.notlogin');
    }

    function updatect_plct(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);
            $model->update(['mact' => $inputs['mact']]);
            if (isset($inputs['up_hoso'])) {
                $pl = dmphanloaicongtac_baohiem::where('mact', $inputs['mact'])->where('madv', session('admin')->madv)->first();
                $canbo = hosocanbo::where('macanbo', $model->macanbo)->first();
                if ($canbo->baohiem == 0) {
                    $canbo->update(['mact' => $inputs['mact']]);
                } else {
                    $canbo->update([
                        'mact' => $inputs['mact'],
                        "bhxh" => $pl->bhxh,
                        "bhyt" => $pl->bhyt,
                        "bhtn" => $pl->bhtn,
                        "kpcd" => $pl->kpcd,
                        "bhxh_dv" => $pl->bhxh_dv,
                        "bhyt_dv" => $pl->bhyt_dv,
                        "bhtn_dv" => $pl->bhtn_dv,
                        "kpcd_dv" => $pl->kpcd_dv,
                    ]);
                }
            }

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $model->mabl . '&mapb=' . $model->mapb);
        } else
            return view('errors.notlogin');
    }

    function updatect_ngaycong(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $m_bl = bangluong::where('mabl', $inputs['mabl'])->first();
            $model = (new data())->getBangluong_ct_cb($m_bl->thang, $inputs['id']);
            $model->update([
                'songaylv' => chkDbl($inputs['songaylv']),
                'tongngaylv' => chkDbl($inputs['tongngaylv'])
            ]);

            return redirect('/chuc_nang/bang_luong/bang_luong?mabl=' . $model->mabl . '&mapb=' . $model->mapb);
        } else
            return view('errors.notlogin');
    }
    // ngày 24/10/2019: các view có đuôi "_chk" là để kiểm tra xem còn dùng ko

    public function inbangluong($mabl)
    {
        if (Session::has('admin')) {
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'nguoilap', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);

            //$mabl = $m_bl->mabl;
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            //$a_pl = a_unique(array_column($model->toarray(),'mact'));
            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang, 'phanloai' => $m_bl->phanloai,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'cochu' => 11,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_phucapbc = getColPhuCap_BaoCao();
            $a_phucap = array();
            $col = 0;
            foreach ($a_phucapbc as $key => $val) {
                if ($m_dv->$key < 3) {
                    $a_phucap[$key] = $val;
                    $col++;
                }
            }

            return view('reports.bangluong.donvi.maubangluong')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    function inbaohiem($mabl)
    {
        if (Session::has('admin')) {
            $m_dv = dmdonvi::where('madv', session('admin')->maxa)->first();
            $m_bl = bangluong::select('thang', 'nam', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $mabl);
            $tendvcq = dmdonvi::where('madv', $m_dv->macqcq)->first()->tendv;

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'cochu' => 11,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.maubaohiem')
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('tendvcq', $tendvcq)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Bảng trích nộp bảo hiểm chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau01(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();
            /*
            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(),'tencanbo','macanbo');
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $ct->tencanbo = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
                }
            }
            */
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = getColPhuCap_BaoCao();
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')
                ->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            //dd($thongtin);
            return view('reports.bangluong.donvi.maubangluong')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau01_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau1'];
            $inputs['mapb'] = $inputs['mapb_mau1'];
            $inputs['macvcq'] = $inputs['macvcq_mau1'];
            $inputs['mact'] = $inputs['mact_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(), 'tencanbo', 'macanbo');
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $ct->tencanbo = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
                }
            }

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }

            Excel::create('BANGLUONG_01', function ($excel) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                    $sheet->loadView('reports.bangluong.donvi.maubangluong_excel')
                        ->with('model', $model->sortBy('stt'))
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('col', $col)
                        ->with('model_congtac', $model_congtac)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle', 'Bảng lương chi tiết');
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

    public function printf_mautt107(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH','KHONGCT']);
            $model = $this->getBangLuong($inputs);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => isset($inputs['cochu']) ? $inputs['cochu'] : 11,
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
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
            // dd($model);
            return view('reports.bangluong.donvi.mautt107')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_m2(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            //$a_pl = array_column($model_pc->toarray(),'phanloai','mapc');
            //dd($a_pl);
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            //dd($a_phucap);
            //chạy lại để tính lại phụ cấp
            $luongcb = $m_bl->luongcoban;
            foreach ($model as $cb) {
                $cb->ttl_tn = 0;
                if ($cb->congtac == 'DAINGAY' || $cb->congtac == 'THAISAN' || $cb->congtac == 'KHONGLUONG') {
                    $cb->tonghs = 0;
                    foreach ($a_phucap as $k => $v) {
                        if ($cb->$k > 1000) {
                            $cb->ttl_tn += chkDbl($cb->$k);
                        } else {
                            $cb->tonghs += $cb->$k;
                            $cb->ttl_tn += round($cb->$k * $luongcb, 0);
                        }
                    }
                } else {
                    $cb->ttl_tn = $cb->ttl;
                }
            }

            return view('reports.bangluong.donvi.mautt107_m2')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_m3(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $a_chucvu = getChucVuCQ(false);
            //dd($a_chucvu);
            $model_canbo = $this->getBangLuong($inputs);

            $model = $model_canbo->where('congtac', 'CONGTAC');
            $model_kn = $model_canbo->where('congtac', '<>', 'CONGTAC');
            //Duyệt lại bảng kiêm nhiêm => nếu kiêm nhiệm và chính # phân loại công tác => chuyển cán bộ về bảng chính
            foreach ($model_kn as $key => $kn) {
                $chk = $model->where('macanbo', $kn->macanbo)->where('mact', $kn->mact);
                if ($chk->count() == 0) {
                    $model->add($kn);
                    $model_kn->forget($key);
                }
            }
            //dd($model_kn->toarray());
            // $model_kn = $model_canbo->where('congtac', 'CHUCVU');
            //dd($model->where('macanbo', '1511749299_1690123438'));
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
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
            $a_cb = a_unique(array_column($model->toarray(), 'macanbo'));
            $a_cb_kn = a_unique(array_column($model_kn->toarray(), 'macanbo'));
            //dd($model_kn->toarray());

            //dd($model);
            foreach ($model as $cb) {
                $canbo = $model_kn->where('macanbo', $cb->macanbo)->where('mact', $cb->mact);

                //làm lại chức danh kiêm nhiệm chỉ vào chức danh chính (...)
                if ($canbo != null) {
                    foreach ($canbo as $cbkn) {
                        $cb->ttl_kn += $cbkn->ttl;
                        $cb->luongtn_kn += $cbkn->luongtn;
                        $cb->macvcq_kn .= (isset($a_chucvu[$cbkn->macvcq]) ? $a_chucvu[$cbkn->macvcq] : '')  . '; ';
                    }
                }
            }
            //dd($a_phucap);
            //chạy lại để tính lại phụ cấp
            /*
            $luongcb = $m_bl->luongcoban;
            foreach($model as $cb){
                $cb->ttl_tn = $cb->ttl;
                if($cb->congtac == 'DAINGAY' || $cb->congtac == 'THAISAN' || $cb->congtac == 'KHONGLUONG'){
                    $cb->tonghs = 0;
                    foreach($a_phucap as $k=>$v) {
                        $cb->tonghs += $cb->$k;
                    }
                    $cb->ttl_tn =round($cb->tonghs * $luongcb, 0);
                }
            }
            */

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            return view('reports.bangluong.donvi.mautt107_m3')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('a_chucvu', $a_chucvu)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautt107_pb(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            /*
            //lưu mã pb lại vì chưa lọc theo phòng ban
            $mapb = $inputs['mapb'];
            */
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong_moi($inputs);

            $model_pb = dmphongban::select('mapb', 'tenpb')
                ->wherein('mapb', a_unique(array_column($model->toarray(), 'mapb')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
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
            return view('reports.bangluong.donvi.mautt107_pb')
                ->with('model', $model)
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

    public function printf_mau02_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mautt107'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu']
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

            Excel::create('BANGLUONG_02', function ($excel) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                    $sheet->loadView('reports.bangluong.donvi.mautt107')
                        ->with('model', $model)
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('col', $col)
                        ->with('model_congtac', $model_congtac)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle', 'Bảng lương chi tiết');
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

    public function printf_mau03(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = $hoso->tencanbo ?? null;
                }
            }

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            return view('reports.bangluong.donvi.maulangson')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau03_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = $hoso->tencanbo ?? null;
                }
            }

            Excel::create('BANGLUONG_03', function ($excel) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                    $sheet->loadView('reports.bangluong.donvi.maulangson_excel')
                        ->with('model', $model)
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('col', $col)
                        ->with('model_congtac', $model_congtac)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle', 'Bảng lương chi tiết');
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

    public function printf_mau04(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            //dd($a_phucap);
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'hesott', 'pccv');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model as $ct) {
                $ct->tiencongngay = round($ct->luongtn / $ct->tongngaylv);
                //làm công thức này cho đỡ sai số

                $ct->tiencong = $ct->luongtn + ($ct->songaylv - $ct->tongngaylv) * $ct->tiencongngay;
            }

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                }
            }
            return view('reports.bangluong.donvi.maulangson_m2')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau04_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                    $ct->tencanbo = $hoso->tencanbo ?? null;
                }
            }
            Excel::create('BANGLUONG_04', function ($excel) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                    $sheet->loadView('reports.bangluong.donvi.maubangluong_phongban_excel')
                        ->with('model', $model->sortBy('stt'))
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('col', $col)
                        ->with('model_congtac', $model_congtac)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle', 'Bảng lương chi tiết');
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

    public function printf_mau05(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }

            return view('reports.bangluong.donvi.mau05')
                ->with('model', $model)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau05_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau5'];
            $inputs['mapb'] = $inputs['mapb_mau5'];
            $inputs['macvcq'] = $inputs['macvcq_mau5'];
            $inputs['mact'] = $inputs['mact_mau5'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //dd($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(), 'tencanbo', 'macanbo');
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $ct->tencanbo = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
                }
            }

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            $a_goc = array('heso', 'vuotkhung', 'hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                $a_phucap[$ct->mapc] = $ct->report;
                $col++;
            }
            Excel::create('BANGLUONG_05', function ($excel) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                    $sheet->loadView('reports.bangluong.donvi.mau05_excel')
                        ->with('model', $model)
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('col', $col)
                        ->with('model_congtac', $model_congtac)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle', 'Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    //$sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);
                    //$sheet->setAllBorders('thin');

                    $sheet->setWidth('C', 10);
                    $sheet->setWidth('D', 30);
                    $sheet->setWidth('E', 15);

                    /*
                    $sheet->mergeCells('Q20:R20');
                    $sheet->setMergeColumn(array(
                        'columns' => ['Q'],
                        'rows' => [
                            [20, 21]
                        ]
                    ));
                    */

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau06(Request $request)
    {
        /*
         lấy danh sách phụ cấp trừ lương của đơn vị
        khi tính lại lương trừ các khoản giảm trừ theo phụ cấp
        (chú ý phần làm tròn số)
         * */
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['mabl'] = $inputs['mabl_mau6'];
            $inputs['mapb'] = $inputs['mapb_mau6'];
            $inputs['macvcq'] = $inputs['macvcq_mau6'];
            $inputs['mact'] = $inputs['mact_mau6'];

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong_moi($inputs);

            if (isset($inputs['inbaohiem'])) {
                $model_tm = dmtieumuc_default::all();
            } else {
                $model_tm = dmtieumuc_default::where('phanloai', 'CHILUONG')->get();
            }
            //dd($model_tm);
            $model_congtac = dmphanloaict::select('mact', 'tenct', 'macongtac')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'inbaohiem' => isset($inputs['inbaohiem']) ? true : false,
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
                //'tenpb' => $a_pb[$inputs['mapb']],
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_goc = array('heso','vuotkhung','hesott');
            $m_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->orderby('stt')->get();
            $a_pc = $m_pc->keyby('mapc')->toarray();
            $a_ts = array_column($m_pc->where('thaisan', '1')->toarray(), 'mapc');
            $a_nghiphep = array_column($m_pc->where('nghiom', '1')->toarray(), 'mapc');
            $a_phucap = array();
            $col = 0;
            //dd($a_nghiphep);
            foreach ($a_pc as $key => $val) {
                if ($model->sum($val['mapc']) > 0) {
                    $a_phucap[$val['mapc']] = $val['report'];
                    $col++;
                }
            }
            $a_phca = $a_pc;
            $a_bh = a_getelement_equal($a_pc, array('baohiem' => 1));
            $a_nhomct = array_column($model_congtac->toarray(), 'macongtac', 'mact');

            //dd($a_bh);
            $a_pc_tm = new Collection();

            foreach ($model as $ct) {
                $ct->macongtac = isset($a_nhomct[$ct->mact]) ? $a_nhomct[$ct->mact] : '';
                //chưa tính trường hợp nghỉ ts
                //foreach ($a_bh as $k => $v) {
                foreach ($a_phca as $k => $v) {
                    $mapc = $v['mapc'];
                    $mapc_st = 'st_' . $mapc;
                    $a_phca[$k]['macanbo'] = $ct->macanbo;
                    $a_phca[$k]['stbhxh'] = 0;
                    $a_phca[$k]['stbhyt'] = 0;
                    $a_phca[$k]['stkpcd'] = 0;
                    $a_phca[$k]['stbhtn'] = 0;
                    $a_phca[$k]['ttbh'] = 0;
                    $a_phca[$k]['stbhxh_dv'] = 0;
                    $a_phca[$k]['stbhyt_dv'] = 0;
                    $a_phca[$k]['stkpcd_dv'] = 0;
                    $a_phca[$k]['stbhtn_dv'] = 0;
                    $a_phca[$k]['ttbh_dv'] = 0;
                    //theo y.c để phụ cấp ko hưởng = 0
                    //do số tiền hưởng theo phụ cấp đã = 0 nên có thể kiểm tra: $ct->$mapc_st == 0 ==> $ct->$mapc = 0;
                    if ($ct->congtac == 'THAISAN' && !in_array($mapc, $a_ts)) {
                        $ct->$mapc = 0;
                        continue;
                    }
                    $a_phca[$k]['mact'] = $ct->mact;
                    $a_phca[$k]['heso'] = $ct->$mapc;
                    $a_phca[$k]['sotien'] = $ct->$mapc_st;
                    $a_phca[$k]['giaml'] = 0;

                    //kiểm tra xem có bảo hiểm ko tính lại bảo hiểm
                    if (isset($a_bh[$k])) {
                        //cán bộ KHONGCT, KHAC đóng bảo hiểm hệ số 1.0 (ko theo hệ số)=> tính bảo hiểm chỉ gán vào 'heso' hoặc 'hesopc'
                        if ($ct->macongtac == 'KHONGCT' || $ct->macongtac == 'KHAC') {
                            if (in_array($k, ['heso', 'hesopc']) && $ct->$k > 0) {
                                $a_phca[$k]['stbhxh'] = round($ct->luongcoban * $ct->bhxh, 0);
                                $a_phca[$k]['stbhyt'] = round($ct->luongcoban * $ct->bhyt, 0);
                                $a_phca[$k]['stkpcd'] = round($ct->luongcoban * $ct->kpcd, 0);
                                $a_phca[$k]['stbhtn'] = round($ct->luongcoban * $ct->bhtn, 0);
                                $a_phca[$k]['ttbh'] = $a_phca[$k]['stbhxh'] + $a_phca[$k]['stbhyt'] + $a_phca[$k]['stkpcd'] + $a_phca[$k]['stbhtn'];
                                $a_phca[$k]['stbhxh_dv'] = round($ct->luongcoban * $ct->bhxh_dv, 0);
                                $a_phca[$k]['stbhyt_dv'] = round($ct->luongcoban * $ct->bhyt_dv, 0);
                                $a_phca[$k]['stkpcd_dv'] = round($ct->luongcoban * $ct->kpcd_dv, 0);
                                $a_phca[$k]['stbhtn_dv'] = round($ct->luongcoban * $ct->bhtn_dv, 0);
                                $a_phca[$k]['ttbh_dv'] = $a_phca[$k]['stbhxh_dv'] + $a_phca[$k]['stbhyt_dv'] + $a_phca[$k]['stkpcd_dv'] + $a_phca[$k]['stbhtn_dv'];
                            }
                        } else {
                            $a_phca[$k]['stbhxh'] = round($a_phca[$k]['sotien'] * $ct->bhxh, 0);
                            $a_phca[$k]['stbhyt'] = round($a_phca[$k]['sotien'] * $ct->bhyt, 0);
                            $a_phca[$k]['stkpcd'] = round($a_phca[$k]['sotien'] * $ct->kpcd, 0);
                            $a_phca[$k]['stbhtn'] = round($a_phca[$k]['sotien'] * $ct->bhtn, 0);
                            $a_phca[$k]['ttbh'] = $a_phca[$k]['stbhxh'] + $a_phca[$k]['stbhyt'] + $a_phca[$k]['stkpcd'] + $a_phca[$k]['stbhtn'];
                            $a_phca[$k]['stbhxh_dv'] = round($a_phca[$k]['sotien'] * $ct->bhxh_dv, 0);
                            $a_phca[$k]['stbhyt_dv'] = round($a_phca[$k]['sotien'] * $ct->bhyt_dv, 0);
                            $a_phca[$k]['stkpcd_dv'] = round($a_phca[$k]['sotien'] * $ct->kpcd_dv, 0);
                            $a_phca[$k]['stbhtn_dv'] = round($a_phca[$k]['sotien'] * $ct->bhtn_dv, 0);
                            $a_phca[$k]['ttbh_dv'] = $a_phca[$k]['stbhxh_dv'] + $a_phca[$k]['stbhyt_dv'] + $a_phca[$k]['stkpcd_dv'] + $a_phca[$k]['stbhtn_dv'];
                        }
                    }
                    //Tính lại số tiền giảm trừ
                    if ($ct->songaytruc > 0 && in_array($mapc, $a_nghiphep)) {
                        $ct->songaycong = $ct->songaycong == 0 ? session('admin')->songaycong : $ct->songaycong;
                        $a_phca[$k]['giaml'] = round(($a_phca[$k]['sotien'] / $ct->songaycong) * $ct->songaytruc);
                    }

                    $a_pc_tm->add($a_phca[$k]);
                }
            }
            //dd($a_pc_tm); //đổi về collection
            // dd($model_tm);
            foreach ($model_tm as $tm) {
                $tm->heso = 0;
                $tm->giaml = 0;
                $tm->sotien = 0;
                $tm->stbhxh = 0;
                $tm->stbhyt = 0;
                $tm->stkpcd = 0;
                $tm->stbhtn = 0;
                $tm->ttbh = 0;
                $tm->stbhxh_dv = 0;
                $tm->stbhyt_dv = 0;
                $tm->stkpcd_dv = 0;
                $tm->stbhtn_dv = 0;
                $tm->ttbh_dv = 0;

                if ($tm->muc == '6300') { //tính bảo hiểm
                    $m_tinhtoan = $model;
                    foreach (explode(',', $tm->mapc) as $maso) {
                        if ($maso != '') {
                            $tm->sotien += $m_tinhtoan->sum($maso);
                        }
                    }
                    continue;
                }
                $m_tinhtoan = $a_pc_tm;
                /*
                //check sự nghiệp =>bỏ
                if ($tm->sunghiep != 'ALL' && $tm->sunghiep != 'null') {
                    $m_tinhtoan = $m_tinhtoan->where('sunghiep', $tm->sunghiep);
                }
                */
                //check mã công tác
                if ($tm->mact != 'ALL' && $tm->mact != 'null') {
                    $ar_ct = explode(',', $tm->mact);
                    $m_tinhtoan = $m_tinhtoan->wherein('mact', $ar_ct);
                }

                foreach (explode(',', $tm->mapc) as $pc) {
                    if ($pc != '' && $pc != 'null') {
                        $m_tinhtoan_pc = $m_tinhtoan->where('mapc', $pc);
                        //$m_tinhtoan_pc = a_getelement_equal($m_tinhtoan_pc, array('mapc'=>$pc));
                        $tm->heso += $m_tinhtoan_pc->sum('heso');
                        $tm->giaml += $m_tinhtoan_pc->sum('giaml');
                        $tm->sotien += $m_tinhtoan_pc->sum('sotien');
                        $tm->stbhxh += $m_tinhtoan_pc->sum('stbhxh');
                        $tm->stbhyt += $m_tinhtoan_pc->sum('stbhyt');
                        $tm->stkpcd += $m_tinhtoan_pc->sum('stkpcd');
                        $tm->stbhtn += $m_tinhtoan_pc->sum('stbhtn');
                        $tm->ttbh += $m_tinhtoan_pc->sum('ttbh');
                        $tm->stbhxh_dv += $m_tinhtoan_pc->sum('stbhxh_dv');
                        $tm->stbhyt_dv += $m_tinhtoan_pc->sum('stbhyt_dv');
                        $tm->stkpcd_dv += $m_tinhtoan_pc->sum('stkpcd_dv');
                        $tm->stbhtn_dv += $m_tinhtoan_pc->sum('stbhtn_dv');
                        $tm->ttbh_dv += $m_tinhtoan_pc->sum('ttbh_dv');
                    }
                }
            }
            // dd($model_tm);
            $model_tm = $model_tm->where('sotien', '>', 0);
            //dd($model_tm->toArray());
            $a_muc = $model_tm->map(function ($data) {
                return collect($data->toArray())
                    ->only(['muc'])
                    ->all();
            });
            $a_muc = a_unique($a_muc);
            //dd($model_tm);
            return view('reports.bangluong.donvi.mau06')
                ->with('model', $model)
                ->with('model_tm', $model_tm)
                ->with('a_muc', $a_muc)
                //->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau07(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            //$inputs['cochu'] = $inputs['cochu_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);
            //$data_phucap = bangluong_phucap::where('mabl', $inputs['mabl'])->get()->toarray();
            //$model_st = $this->getBangLuong($inputs,1)->wherein('phanloai', ['CVCHINH','KHONGCT']);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = getColPhuCap_BaoCao();
            $a_goc = array('hesott');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $a_phucap = array();
            $a_phucap_st = array();
            $col = 0;

            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $a_phucap_st['st_' . $ct->mapc] = $ct->report;
                    $col++;
                }
            }
            //dd($model);
            return view('reports.bangluong.donvi.mau07')
                ->with('model', $model)
                //->with('model_st', $model_st)
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('a_phucap_st', $a_phucap_st)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau07_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            //$inputs['cochu'] = $inputs['cochu_mau1'];
            $model = $this->getBangLuong($inputs)->wherein('phanloai', ['CVCHINH', 'KHONGCT']);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(), 'tencanbo', 'macanbo');
            foreach ($model as $ct) {
                if ($ct->phanloai == 'KHONGCT') {
                    $ct->tencanbo = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
                }
            }

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'luongcb' => $m_bl->luongcoban
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = getColPhuCap_BaoCao();
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

            Excel::create('BANGLUONG_07', function ($excel) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $col, $model_congtac, $a_phucap) {
                    $sheet->loadView('reports.bangluong.donvi.mau07_excel')
                        ->with('model', $model->sortBy('stt'))
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('col', $col)
                        ->with('model_congtac', $model_congtac)
                        ->with('a_phucap', $a_phucap)
                        ->with('pageTitle', 'Bảng lương chi tiết');
                    //$sheet->setPageMargin(0.25);
                    $sheet->setAutoSize(false);
                    $sheet->setFontFamily('Tahoma');
                    $sheet->setFontBold(false);

                    //$sheet->setColumnFormat(array('D' => '#,##0.00'));
                });
            })->download('xls');

            //dd($thongtin);
        } else
            return view('errors.notlogin');
    }

    public function printf_mauquy(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_bl_trichnop = bangluong::select('mabl', 'maquy', 'tenquy')->where('mabl_trichnop', $mabl)->get();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $model = $this->getBangLuong($inputs);
            $model_trichnop = bangluong_truc::wherein('mabl', array_column($m_bl_trichnop->toarray(), 'mabl'))->get();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->get();
            $a_phucap = array();
            $a_hs = array();

            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->report;
                    $col++;
                    if ($ct->mapc == 'vuotkhung' || $ct->mapc == 'pctnn') {
                        $col++;
                        $a_hs[] = $ct->mapc;
                    }
                }
            }

            foreach ($m_bl_trichnop as $trichnop) { //maquy trùng với mabl
                $maso = $trichnop->mabl;
                $bl_tn = $model_trichnop->where('mabl', $maso)->keyby('macanbo')->toarray();
                //dd($bl_trichnop);
                foreach ($model as $ct) {
                    if (isset($bl_tn[$ct->macanbo])) {
                        $ct->$maso = $bl_tn[$ct->macanbo]['ttl'];
                        $ct->luongtn -= $ct->$maso;
                    }
                }
            }

            //dd($model->where('congtac','<>','CONGTAC'));
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            //xử lý ẩn hiện cột phụ cấp => biết tổng số cột hiện => colspan trên báo cáo
            //$a_phucapbc = get            ColPhuCap_BaoCao();

            //dd($model->toarray());
            return view('reports.bangluong.donvi.mautrichnop')
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('a_hs', $a_hs) //mảng chứa các phụ cấp in hệ số
                ->with('a_trichnop', array_column($m_bl_trichnop->toarray(), 'tenquy', 'mabl'))
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauds(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['madv'] = session('admin')->madv;

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $model = $this->getBangLuong_moi($inputs);
            //dd($model);
            //$model = bangluong_ct::where('mabl',$mabl)->get();

            //            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(),'sotk','macanbo');
            //            foreach($model as $ct) {
            //                $ct->sotk = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
            //            }
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.maudschitra')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauds_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            //$model = bangluong_ct::where('mabl',$mabl)->get();

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $a_hoso = array_column(hosocanbo::where('madv', $m_bl->madv)->get()->toarray(), 'sotk', 'macanbo');
            foreach ($model as $ct) {
                $ct->sotk = isset($a_hoso[$ct->macanbo]) ? $a_hoso[$ct->macanbo] : '';
            }

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap
            );

            Excel::create('DSCHITRA', function ($excel) use ($m_dv, $thongtin, $model, $model_congtac) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model, $model_congtac) {
                    $sheet->loadView('reports.bangluong.donvi.maudschitra')
                        ->with('model', $model->sortBy('stt'))
                        ->with('model_pb', getPhongBan())
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('model_congtac', $model_congtac)
                        ->with('pageTitle', 'DS CHI TRA');
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

    function printf_maubh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'noidung')->where('mabl', $mabl)->first();
            $model = $this->getBangLuong($inputs);
            //$model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            //dd($request->all());

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $tendvcq = dmdonvi::where('madv', $m_dv->macqcq)->first()->tendv;


            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.maubaohiem')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('tendvcq', $tendvcq)
                ->with('thongtin', $thongtin)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Bảng trích nộp bảo hiểm chi tiết');
        } else
            return view('errors.notlogin');
    }

    //chưa làm xuất bh ra excel
    function printf_maubh_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai')->where('mabl', $mabl)->first();
            $model = $this->getBangLuong($inputs);
            //$model_hoso = hosocanbo::where('madv',$m_bl->madv)->get();

            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $dmchucvucq = dmchucvucq::all('tencv', 'macvcq')->toArray();
            foreach ($model as $hs) {
                $hs->tencv = getInfoChucVuCQ($hs, $dmchucvucq);
            }
            $thongtin = array(
                'nguoilap' => session('admin')->name,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam
            );
            return view('reports.bangluong.maubaohiem')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('model_congtac', $model_congtac)
                ->with('pageTitle', 'Bảng trích nộp bảo hiểm chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maudbhdnd(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl)->where('mact', '1536402868');
            //$model = bangluong_ct::where('mabl',$mabl)->where('mact','1536402868')->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);
            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $ct->tenchucvu = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->sotk = $hoso->sotk;
                $ct->lvtd = $hoso->lvtd;
                $ct->hspc = $ct->pcdbqh + $ct->hesopc;
                $ct->sotienpc = $ct->hspc * $ct->luongcb;
                $ct->sotienkn = $ct->pckn * $ct->luongcb;
                $ct->sotien = $ct->sotienpc + $ct->sotienkn;
            }

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.maudbhdnd')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maudbhdnd_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->sotk =  $hoso->sotk ?? null;
                $ct->lvtd = $hoso->lvtd ?? null;
                $ct->hspc = $hoso->pcdbqh ?? null;
                $ct->sotien = $ct->hspc * $ct->luongcb;
            }
            $model = $model->where('hspc', '>', 0);

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban
            );

            Excel::create('PC ĐBHĐND', function ($excel) use ($m_dv, $thongtin, $model) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model) {
                    $sheet->loadView('reports.bangluong.donvi.maudbhdnd')
                        ->with('model', $model->sortBy('stt'))
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle', 'DS CHI TRA');
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

    public function printf_maublpc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();

            //$model = bangluong_ct::where('mabl', $mabl)->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_chucvu = getChucVuCQ();
            //dd($a_chucvu);
            $tencv = isset($a_chucvu[$inputs['macvcq']]) ? $a_chucvu[$inputs['macvcq']] : '';
            $tencv = strlen($inputs['macvcq']) == 0 ? 'Tất cả các chức vụ' : $tencv;

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->lvtd = $hoso->lvtd;
                $ct->sotk = $hoso->sotk ?? null;
                $ct->lvtd = $hoso->lvtd ?? null;
                $ct->hspc = ($ct->phanloai == 'KHAC' || $ct->congtac == 'KHONGCT') ? $ct->hesopc : $ct->heso;
                $ct->sotien = $ct->hspc * $ct->luongcb;
            }

            //$model = $model->where('heso', '>', 0);
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            //dd($model);
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'tencv' => Str::upper($tencv),
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.maublpc')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maublpc_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = $this->getBangLuong($inputs);

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();

            //$model = bangluong_ct::where('mabl', $mabl)->get();
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_chucvu = getChucVuCQ();

            $tencv = isset($a_chucvu[$inputs['macvcq']]) ? $a_chucvu[$inputs['macvcq']] : '';
            $tencv = strlen($inputs['macvcq']) == 0 ? 'Tất cả các chức vụ' : $tencv;

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->sotk = $hoso->sotk ?? null;
                $ct->lvtd = $hoso->lvtd ?? null;
                $ct->sotien = $ct->heso * $ct->luongcb;
            }
            $model = $model->where('heso', '>', 0);
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'tencv' => Str::upper($tencv)
            );

            Excel::create('BANGLUONG', function ($excel) use ($m_dv, $thongtin, $model) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model) {
                    $sheet->loadView('reports.bangluong.donvi.maublpc')
                        ->with('model', $model->sortBy('stt'))
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle', 'DS CHI TRA');
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

    public function printf_maubchd(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl)->where('mact', '1536459380');
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = $a_cv[$hoso->macvcq] ?? '';
                $ct->chucvukn = $a_cv[$ct->macvcq] ?? '';
                $ct->sotk = $hoso->sotk ?? null;
                $ct->lvtd = $hoso->lvtd ?? null;

                //if($ct->phanloai == 'CAPUY'){
                if ($ct->congtac == 'CAPUY') {
                    $ct->pcvk = $ct->hesopc;
                    //$ct->pckn = $ct->hesopc;
                }
                $ct->sotien = ($ct->pcvk + $ct->pckn) * $ct->luongcb;
            }
            /*
             *
            $model_cvc = $model->where('pcvk','>',0)->where('phanloai','CVCHINH');
            $model_kn = $model->where('hesopc','>',0)->where('phanloai','CAPUY');
            foreach($model_kn as $ct){
                $model_cvc->add($ct);
            }
            */
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            //dd($model);
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.maubchd')
                //->with('model',$model_cvc->sortBy('stt'))
                ->with('model', $model)
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maubchd_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();

            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = $a_cv[$hoso->macvcq] ?? '';
                $ct->chucvukn = $a_cv[$ct->macvcq] ?? '';
                $ct->sotk = $hoso->sotk ?? null;
                $ct->lvtd = $hoso->lvtd ?? null;

                if ($ct->phanloai == 'CAPUY') {
                    $ct->pcvk = $ct->hesopc;
                    //$ct->pckn = $ct->hesopc;
                }
                $ct->sotien = ($ct->pcvk + $ct->pckn) * $ct->luongcb;
            }

            $model_cvc = $model->where('pcvk', '>', 0)->where('phanloai', 'CVCHINH');
            $model_kn = $model->where('hesopc', '>', 0)->where('phanloai', 'CAPUY');
            foreach ($model_kn as $ct) {
                $model_cvc->add($ct);
            }
            $model = $model_cvc;
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban
            );

            Excel::create('BCH DANGUY', function ($excel) use ($m_dv, $thongtin, $model) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model) {
                    $sheet->loadView('reports.bangluong.donvi.maubchd')
                        ->with('model', $model)
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle', 'DS CHI TRA');
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

    public function printf_mauqs(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl)->where('mact', '1536402878');
            if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
                $model = $model->where('mapb', $inputs['mapb']);
            }

            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $ct->st_pck = $ct->pck * $ct->luongcb;
                $ct->st_pcdbn = $ct->pcdbn * $ct->luongcb;
                $ct->st_pctn = $ct->pctn * $ct->luongcb;
                $ct->st_pcthni = $ct->pcthni * $ct->luongcb;
                $ct->sotien = $ct->st_pck + $ct->st_pcdbn + $ct->st_pctn + $ct->st_pcthni;
            }

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.mauqs')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauqs_excel(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
            if (isset($inputs['mapb']) && $inputs['mapb'] != '') {
                $model = $model->where('mapb', $inputs['mapb']);
            }

            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);


            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();
                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                $ct->st_hesopc = $ct->hesopc * $ct->luongcb;
                $ct->st_pcdbn = $ct->pcdbn * $ct->luongcb;
                $ct->st_pctn = $ct->pctn * $ct->luongcb;
                $ct->st_pcthni = $ct->pcthni * $ct->luongcb;
                $ct->sotien = $ct->st_hesopc + $ct->st_pcdbn + $ct->st_pctn + $ct->st_pcthni;
            }

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban
            );

            Excel::create('QUANSU', function ($excel) use ($m_dv, $thongtin, $model) {
                $excel->sheet('New sheet', function ($sheet) use ($m_dv, $thongtin, $model) {
                    $sheet->loadView('reports.bangluong.donvi.mauqs_excel')
                        ->with('model', $model->sortBy('stt'))
                        ->with('m_dv', $m_dv)
                        ->with('thongtin', $thongtin)
                        ->with('pageTitle', 'DS CHI TRA');
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

    public function printf_maucd(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl)->where('mact', '1536402895');
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';
                //do khi nhap trong chuc vu chinh (nhap vap hesopc)
                $ct->sotienkn = ($ct->pckn + $ct->hesopc) * $ct->luongcb;
                $ct->sotienk = $ct->pck * $ct->luongcb;
                $ct->sotien = ($ct->pck + $ct->pckn + +$ct->hesopc) * $ct->luongcb;
            }

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.maucd')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maumc(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl)->where('mact', '1536459160');
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';

                $ct->sotiendh = $ct->pcdh * $ct->luongcb;
                $ct->sotienk = $ct->pck * $ct->luongcb;
                $ct->sotien = $ct->sotiendh + $ct->pcd;
            }

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.maumc')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautinhnguyen(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl)->where('mact', '1537427170');
            $model_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
            $a_cv = getChucVuCQ(false);

            foreach ($model as $ct) {
                $ct->luongcb = $m_bl->luongcoban;
                $hoso = $model_hoso->where('macanbo', $ct->macanbo)->first();

                $ct->tencanbo = $hoso->tencanbo;
                $ct->chucvu = isset($a_cv[$hoso->macvcq]) ? $a_cv[$hoso->macvcq] : '';
                $ct->chucvukn = isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : '';

                $ct->sotien = $ct->hesopc * $ct->luongcb;
            }

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap,
                'luongcb' => $m_bl->luongcoban,
                'innoidung' => false,
                'noidung' => $m_bl->noidung,
            );

            return view('reports.bangluong.donvi.mautinhnguyen')
                ->with('model', $model->sortBy('stt'))
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maumtm(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $mabl = $inputs['mabl'];
            //$model_phucap = bangluong_phucap::where('mabl', $mabl)->get();
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $m_dv->tendvcq = getTenDB($m_dv->madvbc);
            $inputs['madv'] = $m_dv->madv;
            $model = $this->getBangLuong_moi($inputs);
            /*
             * Mặc định lấy bảo hiểm
             if(isset($inputs['inbaohiem'])){
                $model_tm = dmtieumuc_default::all();
            }else{
                $model_tm = dmtieumuc_default::where('phanloai','CHILUONG')->get();
            }
             * */
            $model_tm = dmtieumuc_default::all();
            //dd($model_tm);

            $a_pb = getPhongBan();
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'mapb' => $inputs['mapb'],
                'tenpb' => $a_pb[$inputs['mapb']],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            $model_congtac = dmphanloaict::select('mact', 'tenct', 'macongtac')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();

            $m_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->orderby('stt')->get();
            $a_pc = $m_pc->keyby('mapc')->toarray();
            $a_ts = array_column($m_pc->where('thaisan', '1')->toarray(), 'mapc');
            $a_nghiphep = array_column($m_pc->where('nghiom', '1')->toarray(), 'mapc');
            $a_phucap = array();
            $col = 0;

            foreach ($a_pc as $key => $val) {
                if ($model->sum($val['mapc']) > 0) {
                    $a_phucap[$val['mapc']] = $val['report'];
                    $col++;
                }
            }
            $a_phca = $a_pc;
            $a_bh = a_getelement_equal($a_pc, array('baohiem' => 1));
            $a_nhomct = array_column($model_congtac->toarray(), 'macongtac', 'mact');
            //dd($a_bh);
            $a_pc_tm = new Collection();
            //dd($a_pc);
            foreach ($model as $ct) {
                $ct->macongtac = isset($a_nhomct[$ct->mact]) ? $a_nhomct[$ct->mact] : '';
                //chưa tính trường hợp nghỉ ts
                //foreach ($a_bh as $k => $v) {
                foreach ($a_phca as $k => $v) {
                    $mapc = $v['mapc'];
                    $mapc_st = 'st_' . $mapc;
                    $a_phca[$k]['macanbo'] = $ct->macanbo;
                    $a_phca[$k]['stbhxh'] = $a_phca[$k]['stbhyt'] = $a_phca[$k]['stkpcd'] = $a_phca[$k]['stbhtn'] = 0;
                    $a_phca[$k]['ttbh'] = $a_phca[$k]['ttbh_dv'] = 0;
                    $a_phca[$k]['stbhxh_dv'] = $a_phca[$k]['stbhyt_dv'] = $a_phca[$k]['stkpcd_dv'] = $a_phca[$k]['stbhtn_dv'] = 0;

                    if ($ct->congtac == 'THAISAN' && !in_array($mapc, $a_ts)) {
                        $ct->$mapc = 0; //theo y.c để phụ cấp ko hưởng = 0
                        continue;
                    }
                    $a_phca[$k]['mact'] = $ct->mact;
                    $a_phca[$k]['heso'] = $ct->$mapc;
                    $a_phca[$k]['giaml'] = 0;
                    $a_phca[$k]['sotien'] = $ct->$mapc_st;

                    //kiểm tra xem có bảo hiểm ko tính lại bảo hiểm
                    if (isset($a_bh[$k])) {
                        //cán bộ KHONGCT, KHAC đóng bảo hiểm hệ số 1.0 (ko theo hệ số)=> tính bảo hiểm chỉ gán vào 'heso' hoặc 'hesopc'
                        if ($ct->macongtac == 'KHONGCT' || $ct->macongtac == 'KHAC') {
                            if (in_array($k, ['heso', 'hesopc']) && $ct->$k > 0) {
                                $a_phca[$k]['stbhxh'] = round($ct->luongcoban * $ct->bhxh, 0);
                                $a_phca[$k]['stbhyt'] = round($ct->luongcoban * $ct->bhyt, 0);
                                $a_phca[$k]['stkpcd'] = round($ct->luongcoban * $ct->kpcd, 0);
                                $a_phca[$k]['stbhtn'] = round($ct->luongcoban * $ct->bhtn, 0);
                                $a_phca[$k]['ttbh'] = $a_phca[$k]['stbhxh'] + $a_phca[$k]['stbhyt'] + $a_phca[$k]['stkpcd'] + $a_phca[$k]['stbhtn'];
                                $a_phca[$k]['stbhxh_dv'] = round($ct->luongcoban * $ct->bhxh_dv, 0);
                                $a_phca[$k]['stbhyt_dv'] = round($ct->luongcoban * $ct->bhyt_dv, 0);
                                $a_phca[$k]['stkpcd_dv'] = round($ct->luongcoban * $ct->kpcd_dv, 0);
                                $a_phca[$k]['stbhtn_dv'] = round($ct->luongcoban * $ct->bhtn_dv, 0);
                                $a_phca[$k]['ttbh_dv'] = $a_phca[$k]['stbhxh_dv'] + $a_phca[$k]['stbhyt_dv'] + $a_phca[$k]['stkpcd_dv'] + $a_phca[$k]['stbhtn_dv'];
                            }
                        } else {
                            $a_phca[$k]['stbhxh'] = round($a_phca[$k]['sotien'] * $ct->bhxh, 0);
                            $a_phca[$k]['stbhyt'] = round($a_phca[$k]['sotien'] * $ct->bhyt, 0);
                            $a_phca[$k]['stkpcd'] = round($a_phca[$k]['sotien'] * $ct->kpcd, 0);
                            $a_phca[$k]['stbhtn'] = round($a_phca[$k]['sotien'] * $ct->bhtn, 0);
                            $a_phca[$k]['ttbh'] = $a_phca[$k]['stbhxh'] + $a_phca[$k]['stbhyt'] + $a_phca[$k]['stkpcd'] + $a_phca[$k]['stbhtn'];
                            $a_phca[$k]['stbhxh_dv'] = round($a_phca[$k]['sotien'] * $ct->bhxh_dv, 0);
                            $a_phca[$k]['stbhyt_dv'] = round($a_phca[$k]['sotien'] * $ct->bhyt_dv, 0);
                            $a_phca[$k]['stkpcd_dv'] = round($a_phca[$k]['sotien'] * $ct->kpcd_dv, 0);
                            $a_phca[$k]['stbhtn_dv'] = round($a_phca[$k]['sotien'] * $ct->bhtn_dv, 0);
                            $a_phca[$k]['ttbh_dv'] = $a_phca[$k]['stbhxh_dv'] + $a_phca[$k]['stbhyt_dv'] + $a_phca[$k]['stkpcd_dv'] + $a_phca[$k]['stbhtn_dv'];
                        }
                    }
                    //Tính lại số tiền giảm trừ
                    if ($ct->songaytruc > 0 && in_array($mapc, $a_nghiphep)) {
                        $ct->songaycong = $ct->songaycong == 0 ? session('admin')->songaycong : $ct->songaycong;
                        $a_phca[$k]['giaml'] = round(($a_phca[$k]['sotien'] / $ct->songaycong) * $ct->songaytruc);
                    }
                    $a_pc_tm->add($a_phca[$k]);
                    //$a_pc_tm[] = $a_phca[$k];
                }
            }
            //dd($a_pc_tm);

            foreach ($model_tm as $tm) {
                $tm->heso = 0;
                $tm->giaml = 0;
                $tm->sotien = 0;
                $tm->stbhxh = 0;
                $tm->stbhyt = 0;
                $tm->stkpcd = 0;
                $tm->stbhtn = 0;
                $tm->ttbh = 0;
                $tm->stbhxh_dv = 0;
                $tm->stbhyt_dv = 0;
                $tm->stkpcd_dv = 0;
                $tm->stbhtn_dv = 0;
                $tm->ttbh_dv = 0;

                if ($tm->muc == '6300') {
                    $m_tinhtoan = $model;
                    foreach (explode(',', $tm->mapc) as $maso) {
                        if ($maso != '') {
                            $tm->sotien += $m_tinhtoan->sum($maso);
                        }
                    }
                    continue;
                }
                /* bỏ
                //check sự nghiệp
                if ($tm->sunghiep != 'ALL' && $tm->sunghiep != 'null') {
                    $m_tinhtoan = $m_tinhtoan->where('sunghiep', $tm->sunghiep);
                }
                */
                $m_tinhtoan = $a_pc_tm;
                //check mã công tác
                if ($tm->mact != 'ALL' && $tm->mact != 'null') {
                    $ar_ct = explode(',', $tm->mact);
                    $m_tinhtoan = $m_tinhtoan->wherein('mact', $ar_ct);
                }

                foreach (explode(',', $tm->mapc) as $pc) {
                    if ($pc != '' && $pc != 'null') {
                        $m_tinhtoan_pc = $m_tinhtoan->where('mapc', $pc);
                        //$m_tinhtoan_pc = a_getelement_equal($m_tinhtoan_pc, array('mapc'=>$pc));
                        $tm->heso += $m_tinhtoan_pc->sum('heso');
                        $tm->giaml += $m_tinhtoan_pc->sum('giaml');
                        $tm->sotien += $m_tinhtoan_pc->sum('sotien');
                        $tm->stbhxh += $m_tinhtoan_pc->sum('stbhxh');
                        $tm->stbhyt += $m_tinhtoan_pc->sum('stbhyt');
                        $tm->stkpcd += $m_tinhtoan_pc->sum('stkpcd');
                        $tm->stbhtn += $m_tinhtoan_pc->sum('stbhtn');
                        $tm->ttbh += $m_tinhtoan_pc->sum('ttbh');
                        $tm->stbhxh_dv += $m_tinhtoan_pc->sum('stbhxh_dv');
                        $tm->stbhyt_dv += $m_tinhtoan_pc->sum('stbhyt_dv');
                        $tm->stkpcd_dv += $m_tinhtoan_pc->sum('stkpcd_dv');
                        $tm->stbhtn_dv += $m_tinhtoan_pc->sum('stbhtn_dv');
                        $tm->ttbh_dv += $m_tinhtoan_pc->sum('ttbh_dv');
                    }
                }
            }
            //dd($model_tm);
            $model_tm = $model_tm->where('sotien', '>', 0);

            $a_muc = $model_tm->map(function ($data) {
                return collect($data->toArray())
                    ->only(['muc'])
                    ->all();
            });
            $a_muc = a_unique($a_muc);
            //dd($model_tm);
            return view('reports.bangluong.donvi.maumtm')
                //->with('model', $model->sortBy('stt'))
                ->with('model_tm', $model_tm)
                ->with('a_muc', $a_muc)
                //->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                //->with('model_congtac', $model_congtac)
                //->with('a_phucap', $a_phucap)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mauC02_KH(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $mabl = $inputs['mabl'];
            $model = $this->getBangLuong($inputs);
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $model_congtac = dmphanloaict::select('mact', 'tenct')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();
            $a_goc = array('hesott', 'heso', 'luonghd');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $m_hscb = hosocanbo::wherein('macanbo', array_column($model->toarray(), 'macanbo'))->get();
            $a_sotk = array_column($m_hscb->toarray(), 'sotk', 'macanbo');
            $a_nganhang = array_column($m_hscb->toarray(), 'tennganhang', 'macanbo');
            $a_macanbo = array_column($m_hscb->toarray(), 'macanbo');
            //thêm sotk và ngân hàng của cán bộ vào
            foreach ($model as $ct) {
                in_array($ct->macanbo, $a_macanbo) ? $ct->sotk = $a_sotk[$ct->macanbo] : '';
                in_array($ct->macanbo, $a_macanbo) ? $ct->tennganhang = $a_nganhang[$ct->macanbo] : '';
            }

            // dd($model);
            $a_phucap = array();/* Hiển thị các cột phụ cấp*/
            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->form;
                    $col++;
                }
            }
            $a_bh = array(); /* Hiển thị cột không tính bảo hiểm*/
            foreach ($a_phucap as $key => $pc) {
                foreach ($model_pc as $ct) {
                    if ($key == $ct->mapc) {
                        $a_bh[$ct->mapc] = $ct->baohiem;
                    }
                }
            }
            //Tính số tiền chuyển qua số tài khoản
            foreach ($model as $ct) {
                //Tính bảo hiểm
                //Cột biên chế
                $ct->stbhxh != 0 ? $ct->bhxh_bc = $ct->st_heso * 8 / 100 : $ct->bhxh_bc = 0;
                $ct->stbhyt != 0 ? $ct->bhyt_bc = $ct->st_heso * 1.5 / 100 : $ct->bhyt_bc = 0;
                $ct->stbhtn != 0 ? $ct->bhtn_bc = $ct->st_heso * 1 / 100 : $ct->bhtn_bc = 0;

                //Cột hợp đồng
                if ($ct->ttbh != 0) {
                    $ct->bhxh_luonghd = $ct->st_luonghd == 0 ? 0 : $ct->st_luonghd * 8 / 100;
                    $ct->bhyt_luonghd = $ct->st_luonghd == 0 ? 0 : $ct->st_luonghd * 1.5 / 100;
                    $ct->bhtn_luonghd = $ct->st_luonghd == 0 ? 0 : $ct->st_luonghd * 1 / 100;
                }
                //Tổng bảo hiểm bc, hd
                $ct->ttbh_bc = $ct->bhxh_bc + $ct->bhyt_bc + $ct->bhtn_bc;
                $ct->ttbh_luonghd = $ct->bhxh_luonghd + $ct->bhyt_luonghd + $ct->bhtn_luonghd;

                $ct->sotk == null ? $ct->nhantienmat = $ct->luongtn : $ct->chuyenkhoan = $ct->luongtn;
                $ct->sotk == null ? $ct->nhantienmat_bc = $ct->st_heso - $ct->ttbh_bc : $ct->chuyenkhoan_bc = $ct->st_heso - $ct->ttbh_bc; //bc=biên chế
                $ct->sotk == null ? $ct->nhantienmat_hd = $ct->st_luonghd -  $ct->ttbh_luonghd : $ct->chuyenkhoan_hd = $ct->st_luonghd -  $ct->ttbh_luonghd;

                foreach ($a_phucap as $key => $val) {
                    $k = 'st_' . $key;
                    $pc_cttm = 'cttm_' . $key;
                    $pc_ctck = 'ctck_' . $key;

                    //Bảo hiểm các phụ cấp nếu có
                    $bhxh_pc = 'bhxh_' . $key;
                    $bhyt_pc = 'bhyt_' . $key;
                    $bhtn_pc = 'bhtn_' . $key;
                    $ttbh_pc = 'ttbh_' . $key;
                    // $a_bh[$key] == 0 ?$ct->$bhxh_pc=0:$ct->$bhxh_pc=$ct->$k*8/100;
                    // $a_bh[$key] == 0 ?$ct->$bhyt_pc=0:$ct->$bhyt_pc=$ct->$k*1.5/100;
                    // $a_bh[$key] == 0 ?$ct->$bhtn_pc=0:$ct->$bhtn_pc=$ct->$k*1/100;
                    if ($a_bh[$key] != 0) {
                        $ct->stbhxh == 0 ? $ct->$bhxh_pc = 0 : $ct->$bhxh_pc = $ct->$k * 8 / 100;
                        $ct->stbhyt == 0 ? $ct->$bhyt_pc = 0 : $ct->$bhyt_pc = $ct->$k * 1.5 / 100;
                        $ct->stbhtn == 0 ? $ct->$bhtn_pc = 0 : $ct->$bhtn_pc = $ct->$k * 1 / 100;
                    } else {
                        $ct->$bhxh_pc = 0;
                        $ct->$bhyt_pc = 0;
                        $ct->$bhtn_pc = 0;
                    }


                    $ct->$ttbh_pc = $ct->$bhxh_pc + $ct->$bhyt_pc + $ct->$bhtn_pc;

                    // if($a_bh[$key]== 0){
                    //     $ct->sotk == null?$ct->$pc_cttm=$ct->$k :$ct->$pc_ctck=$ct->$k;
                    // }else{
                    //     $ct->sotk == null?$ct->$pc_cttm=$ct->$k - ($ct->$k * 10.5/100):$ct->$pc_ctck=$ct->$k - ($ct->$k * 10.5/100);
                    // }

                    // if($a_bh[$key]== 0){
                    //     $ct->sotk == null?$ct->$pc_cttm=$ct->$k :$ct->$pc_ctck=$ct->$k;
                    // }else{
                    $ct->sotk == null ? $ct->$pc_cttm = $ct->$k - $ct->$ttbh_pc : $ct->$pc_ctck = $ct->$k - $ct->$ttbh_pc;
                    // }

                }
            }

            $a_pb = getPhongBan();
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'mapb' => $inputs['mapb'],
                'tenpb' => $a_pb[$inputs['mapb']],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );
            $model_tm = dmtieumuc_default::all();
            $a_tm = array_column($model_tm->toarray(), 'mapc'); //Mảng để so sánh
            //kiểm tra xem có danh mục tiểu mục hay không
            $a_tieumuc = [];
            foreach ($a_phucap as $key => $val) {
                $tm = $model_tm->where('mapc', $key)->first();
                $tm == null ? $a_tieumuc : array_push($a_tieumuc, $tm);
            }
            // dd($col);
            // dd($a_phucap);
            return view('reports.bangluong.donvi.mauC02_KH')
                ->with('model', $model)
                ->with('model_pc', $model_pc)
                ->with('col', $col)
                ->with('a_phucap', $a_phucap)
                ->with('a_bh', $a_bh)
                ->with('a_tm', $a_tm)
                ->with('a_tieumuc', $a_tieumuc)
                ->with('model_congtac', $model_congtac)
                ->with('model_tm', $model_tm)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_mautruylinh(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //$inputs['mabl'] = $inputs['mabl'];
            //$model = $this->getBangLuong($inputs);
            $a_chucvu = getChucVuCQ(false);
            $a_dmnkp = getNguonKP(false);
            //dd($a_chucvu);

            //dd($model_canbo->toarray());
            $model = $this->getBangLuong($inputs);

            //dd($inputs);
            //dd($model_kn);
            $mabl = $inputs['mabl'];
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
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

            $a_nguon = $model->map(function ($data) {
                return collect($data->toArray())
                    ->only(['manguonkp'])
                    ->all();
            });
            $a_nguon = a_unique($a_nguon);
            //dd($a_nguon);
            return view('reports.bangluong.truylinh.mautruylinh')
                ->with('model', $model->sortBy('stt'))
                ->with('model_pb', getPhongBan())
                ->with('m_dv', $m_dv)
                ->with('thongtin', $thongtin)
                ->with('col', $col)
                ->with('a_nguon', $a_nguon)
                ->with('a_phucap', $a_phucap)
                ->with('a_chucvu', $a_chucvu)
                ->with('a_dmnkp', $a_dmnkp)
                ->with('a_dmnkp', $a_dmnkp)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    //Phân loai = 0: hệ sô; 1: số tiền
    function getBangLuong($inputs, $phanloai = 0)
    {
        $mabl = $inputs['mabl'];
        $m_bl = bangluong::select('madv', 'thang', 'mabl')->where('mabl', $mabl)->first();
        $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
        //dd($m_bl);
        $m_hoso = hosocanbo::where('madv', $m_bl->madv)->get();
        $a_ht = array_column($m_hoso->toarray(), 'tencanbo', 'macanbo');
        $sunghiep = array_column($m_hoso->toarray(), 'sunghiep', 'macanbo');
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');
        $a_cv = dmchucvucq::all('tenvt', 'macvcq', 'tencv')->keyBy('macvcq')->toArray();

        foreach ($model as $hs) {
            // if (isset($inputs['inchucvuvt'])) {
            //     $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq]['tenvt'] : '';
            // } else {
            $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq]['tencv'] : '';
            // }

            $hs->sunghiep = isset($sunghiep[$hs->macanbo]) ? $sunghiep[$hs->macanbo] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            if ($hs->tencanbo == '') {
                $hs->tencanbo = isset($a_ht[$hs->macanbo]) ? $a_ht[$hs->macanbo] : ''; //kiêm nhiệm chưa có tên cán bộ
            }
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
        if (isset($inputs['manguonkp']) && $inputs['manguonkp'] != '') {
            $model = $model->where('manguonkp', $inputs['manguonkp']);
        }
        //sắp xếp 
        $sort = []; //mảng để sắp xếp
        $sapxep = isset($inputs['sapxep']) ? $inputs['sapxep'] : '';
        if ($sapxep == 'stt') {
            $sort = [[$sapxep, 'asc']];
        }
        if ($sapxep == 'pccv') {
            $sort = [
                ['pccv', 'desc'],
                ['heso', 'asc']
            ];
        }
        // dd($sort);
        $model = $model->sortBy($sort);
        //dd($model);
        return $model;
    }

    function getBangLuong_moi($inputs)
    {
        //$model = bangluong_ct::where('mabl', $inputs['mabl'])->get();
        $m_bl = bangluong::select('madv', 'thang', 'mabl')->where('mabl', $inputs['mabl'])->first();
        $model = (new data())->getBangluong_ct($m_bl->thang, $m_bl->mabl);
        $a_hoso = hosocanbo::select('macanbo', 'sunghiep', 'sotk', 'tennganhang', 'ngaytu', 'tnntungay', 'socmnd')
            ->where('madv', $m_bl->madv)->get()->keyby('macanbo')->toarray();

        $a_cv = dmchucvucq::all('tenvt', 'macvcq', 'tencv')->keyBy('macvcq')->toArray();
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');

        foreach ($model as $hs) {
            if (isset($a_hoso[$hs->macanbo])) {
                $hoso = $a_hoso[$hs->macanbo];
                $hs->sunghiep = $hoso['sunghiep'];
                $hs->ngaytu = $hoso['ngaytu'];
                $hs->tnntungay = $hoso['tnntungay'];
                $hs->sotk = $hoso['sotk'];
                $hs->socmnd = $hoso['socmnd'];
                $hs->tennganhang = $hoso['tennganhang'];
                if ($hs->tencanbo == '') {
                    $hs->tencanbo = $hoso['tencanbo']; //kiêm nhiệm chưa có tên cán bộ
                }
            }
            if (isset($inputs['inchucvuvt'])) {
                $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq]['tenvt'] : '';
            } else {
                $hs->tencv = isset($a_cv[$hs->macvcq]) ? $a_cv[$hs->macvcq]['tencv'] : '';
            }
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
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
        //sắp xếp 
        $sort = []; //mảng để sắp xếp
        $sapxep = isset($inputs['sapxep']) ? $inputs['sapxep'] : '';
        if ($sapxep == 'stt') {
            $sort = [[$sapxep, 'asc']];
        }
        if ($sapxep == 'pccv') {
            $sort = [
                ['pccv', 'desc'],
                ['heso', 'asc']
            ];
        }
        // dd($sort);
        $model = $model->sortBy($sort);
        return $model;
    }

    //<editor-fold desc="Tra cứu">
    function search()
    {
        if (Session::has('admin')) {
            $model_dv = dmdonvi::where('madv', session('admin')->madv)->get();
            if (session('admin')->quanlynhom) {
                $model_dv = dmdonvi::where('macqcq', session('admin')->madv)->get();
            }

            if (session('admin')->quanlykhuvuc) {
                $model_dv = dmdonvi::where('madvbc', session('admin')->madvbc)->get();
            }

            return view('search.chiluong.index')
                ->with('model_dv', $model_dv)
                ->with('pageTitle', 'Tra cứu chi trả lương');
        } else
            return view('errors.notlogin');
    }

    function result(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model = bangluong::where('thang', $inputs['thang'])->where('nam', $inputs['nam'])->where('madv', $inputs['madv'])->get();

            return view('search.chiluong.result')
                ->with('model', $model)
                ->with('pageTitle', 'Kết quả tra cứu chi trả lương của cán bộ');
        } else
            return view('errors.notlogin');
    }

    public function printf_mau09_KH(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $m_bl = bangluong::select('madv', 'thang', 'mabl', 'manguonkp', 'nam')->where('mabl', $inputs['mabl'])->first();
            // Lấy bảng lương truy lĩnh
            $m_truylinh = bangluong::select('madv', 'thang', 'mabl', 'manguonkp', 'nam')
                ->where('thang', $m_bl->thang)
                ->where('nam', $m_bl->nam)
                ->where('madv', $m_bl->madv)->where('phanloai', 'TRUYLINH')
                ->first();
            if ($m_truylinh != null) {
                $model_truylinh = (new data())->getBangluong_ct($m_truylinh->thang, $m_truylinh->mabl);
            }

            if ($m_bl != null && $m_bl->thang == '01') {
                $thang = '12';
                $nam = str_pad($m_bl->nam - 1, 4, '0', STR_PAD_LEFT);
            } else {
                $thang = str_pad($m_bl->thang - 1, 2, '0', STR_PAD_LEFT);
                $nam = $m_bl->nam;
            }
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->get();
            $a_bh = $model_pc->where('baohiem', '1');
            $a_tenpc = array_column($model_pc->toarray(), 'tenpc', 'mapc');

            //bảng lương tháng trước
            $m_bl_trc = bangluong::select('madv', 'thang', 'mabl', 'manguonkp', 'nam')
                ->where('thang', $thang)->where('nam', $nam)->where('manguonkp', $m_bl->manguonkp)
                ->where('madv', $m_bl->madv)
                ->first();
            if ($m_bl_trc != null) {
                $model_bl_trc = (new data())->getBangluong_ct($m_bl_trc->thang, $m_bl_trc->mabl);
            }

            //bảng lương truy lĩnh tháng trước
            $m_truylinh_trc = bangluong::select('madv', 'thang', 'mabl', 'manguonkp', 'nam')
                ->where('thang', $thang)->where('nam', $nam)
                ->where('manguonkp', $m_bl->manguonkp)
                ->where('madv', $m_bl->madv)
                ->where('phanloai', 'TRUYLINH')
                ->first();
            if ($m_truylinh_trc != null) {
                $model_truylinh_trc = (new data())->getBangluong_ct($m_truylinh_trc->thang, $m_truylinh_trc->mabl);
            }


            $model = $this->getBangLuong($inputs);
            $model_congtac = dmphanloaict::select('mact', 'tenct', 'macongtac')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();
            // $hscb=hosocanbo::all();
            $m_hs = hosocanbo::wherein('macanbo', array_column($model->toarray(), 'macanbo'))->get();
            $a_macanbo = array_column($m_hs->toarray(), 'macanbo');
            $a_sotk = array_column($m_hs->toarray(), 'sotk', 'macanbo');
            $a_nganhang = array_column($m_hs->toarray(), 'tennganhang', 'macanbo');
            $a_tencanbo = array_column($m_hs->toarray(), 'tencanbo', 'macanbo');

            $a_luong = [
                'heso', 'vuotkhung', 'pccv', 'pckv', 'pcth', 'pclade', 'pcdh', 'pcdbqh', 'pcudn', 'pcdbn',
                'pcud61', 'pctnn', 'pcthni', 'pclt', 'pcdang', 'pccovu', 'pcct', 'pctn', 'pckn', 'pcdd', 'pcvk'
            ];
            $a_tangthem = [];
            $a_tienthuong = [];
            $a_phucap = ['pck'];
            $a_khoan = ['pcctp', 'pcxaxe', 'pcdith'];
            $a_hocbong = [];
            $a_hopdong = ['luonghd'];
            $a_pc = array_column($model_pc->toarray(), 'mapc');
            $m_congtac_hd = dmphanloaict::where('macongtac', 'HOPDONG')->orwhere('tenct', 'like', 'Hợp đồng%')->get();

            $a_congtac_hd = array_column($m_congtac_hd->toarray(), 'mact');
            //Tính toán cho các cột Tổng số, lương tháng,truy lĩnh...
            foreach ($model as $ct) {

                $ct->luong = 0;
                foreach ($a_luong as $pc) {
                    $st = 'st_' . $pc;
                    $ct->luong += $ct->$st;
                }
                if ($ct->luong > 0) { //trừ bảo hiểm
                    $ct->luong -= ($ct->ttbh + $ct->giaml + $ct->thuetn);
                }
                $ct->hopdong = 0;
                foreach ($a_hopdong as $pc) {
                    $st = 'st_' . $pc;
                    $ct->hopdong += $ct->$st;
                }
                if ($ct->hopdong > 0) { //trừ bảo hiểm
                    $ct->hopdong -= ($ct->ttbh + $ct->giaml + $ct->thuetn);
                }
                $ct->tangthem = 0;
                $ct->tienthuong = 0;
                $ct->phucap = 0;
                foreach ($a_phucap as $pc) {
                    $st = 'st_' . $pc;
                    $ct->phucap += $ct->$st;
                }
                $ct->khoan = 0;
                foreach ($a_khoan as $pc) {
                    $st = 'st_' . $pc;
                    $ct->khoan += $ct->$st;
                }
                $ct->hocbong = 0;
                $ct->truylinh = 0;
                if ($m_truylinh != null) { // Nếu tháng đó có bảng lương truy lĩnh
                    foreach ($model_truylinh as $val) {
                        if ($ct->macanbo == $val->macanbo && $ct->mact == $val->mact) {
                            $ct->truylinh = $val->luongtn;
                        }
                    }
                }

                //nếu trong nhóm hợp đồng thì chuyển luong sang hopdong
                if (in_array($ct->mact, $a_congtac_hd)) {
                    $ct->hopdong += $ct->luong;
                    $ct->luong = 0;
                }
                // $ct->tongso = $ct->luong + $ct->truylinh + $ct->hopdong + $ct->tangthem + $ct->tienthuong + $ct->phucap + $ct->khoan + $ct->hocbong;
                $ct->tongso = $ct->luongtn + $ct->truylinh;
            }

            //Tính toán tiền chênh giữa 2 tháng
            $model_thaydoi = new Collection();
            $message = '';
            $a_cb = array_column($model->toarray(), 'macanbo');
            if ($m_bl_trc != null) {
                foreach ($model as $ct) {
                    $canbo = $model_bl_trc->where('macanbo', $ct->macanbo)->where('mact', $ct->mact)
                        ->where('mapb', $ct->mapb)->first();

                    if ($canbo != null) {
                        foreach ($model_pc as $pc) {
                            $mapc = $pc->mapc;
                            $mapc_st = 'st_' . $pc->mapc;
                            $ct->$mapc -= $canbo->$mapc;
                            $ct->$mapc_st -= $canbo->$mapc_st;
                            if ($ct->$mapc_st > 0) {
                                $ct->ghichu .= 'Tăng ' . $a_tenpc[$mapc] . '; ';
                            };
                            if ($ct->$mapc_st < 0) {
                                $ct->ghichu .= 'Giảm ' . $a_tenpc[$mapc] . '; ';
                            }
                        }


                        //tính lại lương thực nhận do đã giảm trừ
                        // $ct->luongtn = $ct->ttl - $ct->ttbh - ($canbo->ttl - $canbo->ttbh);
                        $ct->luongtn -= $canbo->luongtn;
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
                    } else {
                        foreach ($model_pc as $pc) {
                            $mapc = $pc->mapc;
                            $mapc_st = 'st_' . $pc->mapc;
                            // $ct->$mapc -= $canbo->$mapc;
                            // $ct->$mapc_st -= $canbo->$mapc_st;
                            // if ($ct->$mapc_st > 0) {
                            //     $ct->ghichu .= 'Tăng ' . $a_tenpc[$mapc] . '; ';
                            // };
                            // if ($ct->$mapc_st < 0) {
                            //     $ct->ghichu .= 'Giảm ' . $a_tenpc[$mapc] . '; ';
                            // }
                        }
                        $ct->ghichu .= 'Cán bộ mới';
                    }
                    if ($m_truylinh != null) { //nếu có bảng truy lĩnh lương 
                        if ($canbo != null) {
                            foreach ($model_truylinh as $val) {

                                if (isset($model_truylinh_trc)) {
                                    $cb_truylinh = $model_truylinh_trc->where('macanbo', $val->macanbo)->where('mact', $val->mact)
                                        ->where('mapb', $val->mapb)->first();
                                    if ($cb_truylinh != null) {
                                        $ct->truylinh = $val->luongtn - $cb_truylinh->luongtn;
                                        if ($ct->truylinh > 0) {
                                            $ct->ghichu .= 'Tăng do truy lĩnh lương';
                                        } else {
                                            $ct->ghichu .= 'Giảm do truy lĩnh lương';
                                        }
                                    }
                                } else if ($val->mact == $canbo->mact && $val->macanbo == $ct->macanbo) {
                                    $ct->ghichu .= 'Tăng do truy lĩnh lương';
                                }
                            }
                        }
                    };

                    $ct->luongthaydoi = $ct->luongtn + $ct->truylinh;

                    //nếu ttl > 0 =>add
                    if ($ct->luongthaydoi != 0) {
                        $model_thaydoi->add($ct);
                    }
                }

                $a_mpl = array(
                    'NGHIHUU' => 'nghỉ hưu',
                    'NGHIVIEC' => 'nghỉ việc',
                    'BUOCNGHI' => 'buộc thôi việc',
                    'DIEUDONG' => 'hết thời gian điều động',
                    'LUANCHUYEN' => 'luân chuyển cán bộ',
                    'KHAC' => ''
                );

                //Cán bộ nghỉ việc
                $canbo_nghiviec = $model_bl_trc->wherenotin('macanbo', $a_cb);
                if ($canbo_nghiviec != null) {
                    $a_cv = dmchucvucq::all('tenvt', 'macvcq', 'tencv')->keyBy('macvcq')->toArray();
                    foreach ($canbo_nghiviec as $cbnghiviec) {
                        // $cb_tamnghi=hosotamngungtheodoi::where('macanbo',$cbnghiviec->macanbo)->first();
                        $cb_nghiviec = hosothoicongtac::where('macanbo', $cbnghiviec->macanbo)->first();
                        // if($cb_tamnghi !=null){
                        //     $cbnghiviec->ghichu = 'Cán bộ tạm nghỉ do '.$cb_tamnghi->lydo;
                        // }
                        if ($cb_nghiviec != null) {
                            if ($cb_nghiviec->maphanloai == 'KHAC') {
                                $cbnghiviec->ghichu = 'Cán bộ nghỉ việc do ' . $cb_nghiviec->lydo;
                            } else {
                                $cbnghiviec->ghichu = 'Cán bộ nghỉ việc do ' . $a_mpl[$cb_nghiviec->maphanloai];
                            }
                        }
                        $cbnghiviec->tencv = isset($a_cv[$cbnghiviec->macvcq]) ? $a_cv[$cbnghiviec->macvcq]['tencv'] : '';
                        $cbnghiviec->luongthaydoi = 0 - $cbnghiviec->luongtn;

                        $model_thaydoi->add($cbnghiviec);
                    }
                }
            } else {
                $message = 'Không tìm thấy bảng lương tháng ' . $thang . ' năm ' . $nam . ' (cùng nguồn kinh phí) để so sánh.';
            }


            //Cán bộ nghỉ việc
            //     if($m_bl_trc != null){
            //     $canbo_nghiviec=$model_bl_trc->wherenotin('macanbo',$a_cb);
            //     if($canbo_nghiviec != null){
            //         $a_cv = dmchucvucq::all('tenvt', 'macvcq', 'tencv')->keyBy('macvcq')->toArray();
            //         foreach($canbo_nghiviec as $cbnghiviec){
            //             $cbnghiviec->tencv = isset($a_cv[$cbnghiviec->macvcq]) ? $a_cv[$cbnghiviec->macvcq]['tencv'] : '';
            //             $cbnghiviec->luongthaydoi=0 - $cbnghiviec->luongtn;
            //             $cbnghiviec->ghichu = 'Nghỉ việc';
            //             $model_thaydoi->add($cbnghiviec);
            //         }
            //     }
            // }
            // dd($model_thaydoi);
            // dd($model);
            $a_pb = getPhongBan();
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'mapb' => $inputs['mapb'],
                'tenpb' => $a_pb[$inputs['mapb']],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
            );

            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            return view('reports.bangluong.donvi.mau09kh')
                ->with('model', $model)
                ->with('a_sotk', $a_sotk)
                ->with('a_nganhang', $a_nganhang)
                ->with('a_macanbo', $a_macanbo)
                ->with('a_tencanbo', $a_tencanbo)
                ->with('model_congtac', $model_congtac)
                ->with('thongtin', $thongtin)
                ->with('m_dv', $m_dv)
                ->with('message', $message)
                ->with('model_thaydoi', $model_thaydoi)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }
    public function printf_maublcbct(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['inchucvuvt'] = '';
            $mabl = $inputs['mabl'];
            $model = $this->getBangLuong($inputs);
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung', 'manguonkp')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $model_congtac = dmphanloaict::select('mact', 'tenct', 'macongtac')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();
            $a_goc = array('hesott', 'heso', 'luonghd');
            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $m_hscb = hosocanbo::wherein('macanbo', array_column($model->toarray(), 'macanbo'))->get();
            $a_sotk = array_column($m_hscb->toarray(), 'sotk', 'macanbo');
            $a_nganhang = array_column($m_hscb->toarray(), 'tennganhang', 'macanbo');
            $a_macanbo = array_column($m_hscb->toarray(), 'macanbo');
            //thêm sotk và ngân hàng của cán bộ vào
            foreach ($model as $ct) {
                in_array($ct->macanbo, $a_macanbo) ? $ct->sotk = $a_sotk[$ct->macanbo] : '';
                in_array($ct->macanbo, $a_macanbo) ? $ct->tennganhang = $a_nganhang[$ct->macanbo] : '';
            }
            $a_phucap = array();/* Hiển thị các cột phụ cấp*/
            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->form;
                    $col++;
                }
            }

            $a_bh = array(); /* Hiển thị cột không tính bảo hiểm*/
            foreach ($a_phucap as $key => $pc) {
                foreach ($model_pc as $ct) {
                    if ($key == $ct->mapc) {
                        $a_bh[$ct->mapc] = $ct->baohiem;
                    }
                }
            }

            //tính những cán bộ chuyển khoản
            foreach ($model as $ct) {

                $ct->sotk == null ? $ct->nhantienmat = $ct->luongtn : $ct->chuyenkhoan = $ct->luongtn;
                // $ct->sotk == null?$ct->nhantienmat_bc=$ct->st_heso - ($ct->st_heso * 10.5/100):$ct->chuyenkhoan_bc=$ct->st_heso - ($ct->st_heso * 10.5/100);//bc=biên chế
                // $ct->sotk == null?$ct->nhantienmat_hd=$ct->st_luonghd- ($ct->st_luonghd * 10.5/100):$ct->chuyenkhoan_hd=$ct->st_luonghd - ($ct->st_luonghd * 10.5/100);

                foreach ($a_phucap as $key => $val) {
                    $k = 'st_' . $key;
                    $pc_cttm = 'cttm_' . $key;
                    $pc_ctck = 'ctck_' . $key;
                    if ($a_bh[$key] == 0) {
                        $ct->sotk == null ? $ct->$pc_cttm = $ct->$k : $ct->$pc_ctck = $ct->$k;
                    } else {
                        $ct->sotk == null ? $ct->$pc_cttm = $ct->$k - ($ct->$k * 10.5 / 100) : $ct->$pc_ctck = $ct->$k - ($ct->$k * 10.5 / 100);
                    }
                }
            }
            $nguonkp = dmnguonkinhphi::select('tennguonkp')->where('manguonkp', $m_bl->manguonkp)->first();
            $model_tm = dmtieumuc_default::all();


            $a_tm = array_column($model_tm->toarray(), 'mapc'); //Mảng để so sánh
            //kiểm tra xem có danh mục tiểu mục hay không
            $a_tieumuc = [];
            foreach ($a_phucap as $key => $val) {
                $tm = $model_tm->where('mapc', $key)->first();
                $tm == null ? $a_tieumuc : array_push($a_tieumuc, $tm);
            }

            $a_pb = getPhongBan();
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'mapb' => $inputs['mapb'],
                'tenpb' => $a_pb[$inputs['mapb']],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
                'nguonkp' => $nguonkp->tennguonkp,
                'mucluong' => $m_bl->luongcoban,
            );
            // $model_pb=dmphongban::select('mapb','tenpb')->wherein('mapb', a_unique(array_column($model->toarray(), 'mapb')))->get();
            // dd($model_pb);
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            //Lấy danh sách kiêm nhiệm cho bảng lương cbkct thôn
            $a_cv = getChucVuCQ(false);
            foreach ($model as $key => $val) {

                $a_kiemnhiem = hosocanbo_kiemnhiem::select('macanbo', 'macvcq')->where('macanbo', $val->macanbo)->get();
                if ($a_kiemnhiem != []) {
                    foreach ($a_kiemnhiem as $item) {
                        $val->chucvukiemnhiem = isset($a_cv[$item->macvcq]) ? $a_cv[$item->macvcq] : '';
                    }
                }
            };

            // dd($model);  
            return   view('reports.bangluong.donvi.maublcbct')
                ->with('thongtin', $thongtin)
                ->with('m_bl', $m_bl)
                ->with('m_dv', $m_dv)
                ->with('model', $model)
                ->with('model_congtac', $model_congtac)
                ->with('a_phucap', $a_phucap)
                ->with('col', $col)
                ->with('model_tm', $model_tm)
                ->with('a_tm', $a_tm)
                ->with('a_bh', $a_bh)
                ->with('a_tieumuc', $a_tieumuc)
                ->with('pageTitle', 'Bảng lương chi tiết');
        } else
            return view('errors.notlogin');
    }

    public function printf_maublcbkct(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $inputs['inchucvuvt'] = '';
            $mabl = $inputs['mabl'];
            $model = $this->getBangLuong($inputs);
            // if($inputs['mact']== ''){
            //     $a_mct=['1506673604','1535613221','1536402878','1506673695'];
            //     $model=$model->wherein('mact',$a_mct);
            // }
            $m_bl = bangluong::select('thang', 'nam', 'mabl', 'madv', 'ngaylap', 'phanloai', 'luongcoban', 'noidung', 'manguonkp')->where('mabl', $mabl)->first();
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();
            $model_congtac = dmphanloaict::select('mact', 'tenct', 'macongtac')
                ->wherein('mact', a_unique(array_column($model->toarray(), 'mact')))->get();
            // $inputs['mact'] == '1506673604'|| $inputs['mact'] ==''?$a_goc = array('hesott','heso','luonghd'):$a_goc = array('hesott');
            in_array($inputs['mact'], ['1506673695', '1535613221', '1536402878']) ? $a_goc = array('hesott') : $a_goc = array('hesott', 'heso', 'luonghd');

            $model_pc = dmphucap_donvi::where('madv', $m_bl->madv)->where('phanloai', '<', '3')->wherenotin('mapc', $a_goc)->get();
            $m_hscb = hosocanbo::wherein('macanbo', array_column($model->toarray(), 'macanbo'))->get();
            $a_sotk = array_column($m_hscb->toarray(), 'sotk', 'macanbo');
            $a_nganhang = array_column($m_hscb->toarray(), 'tennganhang', 'macanbo');
            $a_macanbo = array_column($m_hscb->toarray(), 'macanbo');
            //thêm sotk và ngân hàng của cán bộ vào
            foreach ($model as $ct) {
                in_array($ct->macanbo, $a_macanbo) ? $ct->sotk = $a_sotk[$ct->macanbo] : '';
                in_array($ct->macanbo, $a_macanbo) ? $ct->tennganhang = $a_nganhang[$ct->macanbo] : '';
            }
            $a_phucap = array();/* Hiển thị các cột phụ cấp*/
            $col = 0;
            foreach ($model_pc as $ct) {
                if ($model->sum($ct->mapc) > 0) {
                    $a_phucap[$ct->mapc] = $ct->form;
                    $col++;
                }
            }

            $a_bh = array(); /* Hiển thị cột không tính bảo hiểm*/
            foreach ($a_phucap as $key => $pc) {
                foreach ($model_pc as $ct) {
                    if ($key == $ct->mapc) {
                        $a_bh[$ct->mapc] = $ct->baohiem;
                    }
                }
            }

            //tính những cán bộ chuyển khoản
            // foreach($model as $ct){

            //     $ct->sotk == null?$ct->nhantienmat=$ct->luongtn:$ct->chuyenkhoan=$ct->luongtn;
            //     foreach($a_phucap as $key=>$val){
            //         $k='st_'.$key;
            //         $pc_cttm='cttm_'.$key;                   
            //         $pc_ctck='ctck_'.$key;                    
            //         if($key== 'heso'){
            //             if($ct->ttbh != 0){
            //                 $ct->sotk == null?$ct->$pc_cttm=$ct->st_heso - $ct->ttbh :$ct->$pc_ctck=$ct->st_heso - $ct->ttbh;
            //             }else{
            //                 $ct->sotk == null?$ct->$pc_cttm=$ct->st_heso :$ct->$pc_ctck=$ct->st_heso;
            //             }
            //         }else{
            //             if($a_bh[$key]!= 0){
            //             $ct->sotk == null?$ct->$pc_cttm=$ct->$k - ($ct->k * 9.5/100):$ct->$pc_ctck=$ct->$k - ($ct->k*9.5/100);
            //             }else{
            //                 $ct->sotk == null?$ct->$pc_cttm=$ct->$k :$ct->$pc_ctck=$ct->$k ;
            //             }
            //         }                                      
            //     }
            // }
            $nguonkp = dmnguonkinhphi::select('tennguonkp')->where('manguonkp', $m_bl->manguonkp)->first();
            // $inputs['mact'] == '1506673604'|| $inputs['mact'] =='' ?$model_tm = dmtieumuc_default::all():$model_tm = dmtieumuc_default::all()->unique('mapc');
            in_array($inputs['mact'], ['1506673695', '1535613221', '1536402878']) ? $model_tm = dmtieumuc_default::all()->unique('mapc') : $model_tm = dmtieumuc_default::all();


            $a_tm = array_column($model_tm->toarray(), 'mapc'); //Mảng để so sánh
            //kiểm tra xem có danh mục tiểu mục hay không
            $a_tieumuc = [];
            foreach ($a_phucap as $key => $val) {
                $tm = $model_tm->where('mapc', $key)->first();
                $tm == null ? $a_tieumuc : array_push($a_tieumuc, $tm);
            }

            $a_pb = getPhongBan();
            $thongtin = array(
                'nguoilap' => $m_bl->nguoilap,
                'thang' => $m_bl->thang,
                'nam' => $m_bl->nam,
                'ngaylap' => $m_bl->ngaylap, 'phanloai' => $m_bl->phanloai,
                'cochu' => $inputs['cochu'],
                'mapb' => $inputs['mapb'],
                'tenpb' => $a_pb[$inputs['mapb']],
                'innoidung' => isset($inputs['innoidung']),
                'noidung' => $m_bl->noidung,
                'nguonkp' => $nguonkp->tennguonkp,
                'mucluong' => $m_bl->luongcoban,
            );
            // $model_pb=dmphongban::select('mapb','tenpb')->wherein('mapb', a_unique(array_column($model->toarray(), 'mapb')))->get();
            // dd($model_pb);
            $m_dv = dmdonvi::where('madv', $m_bl->madv)->first();

            //Lấy danh sách kiêm nhiệm cho bảng lương cbkct thôn
            $a_cv = getChucVuCQ(false);
            foreach ($model as $key => $val) {

                $a_kiemnhiem = hosocanbo_kiemnhiem::select('macanbo', 'macvcq')->where('macanbo', $val->macanbo)->get();
                if ($a_kiemnhiem != []) {
                    foreach ($a_kiemnhiem as $item) {
                        $val->chucvukiemnhiem = isset($a_cv[$item->macvcq]) ? $a_cv[$item->macvcq] : '';
                    }
                }
            };
            $model_bh = $model->where('ttbh', "!=", 0);

            //Tính cho các cột 8%, 1.5%, 9.5% bảo hiểm
            $a_tbh = array(
                'bhxh_hs' => 0,
                'bhyt_hs' => 0
            );

            foreach ($model as $ct) {
                $ct->sotk == null ? $ct->nhantienmat = $ct->luongtn : $ct->chuyenkhoan = $ct->luongtn;
                //tính bảo hiểm cho nhóm cán bộ không chuyên trách
                if (in_array($ct->mact, ['1506673695', '1535613221', '1536402878'])) {
                    $ct->bhxh_hs = $ct->stbhxh;
                    $ct->bhyt_hs = $ct->stbhyt;
                    //Tính tiền ck hay nhận tiền mặt cho nhóm này luôn
                    if ($ct->ttbh != 0) {
                        $ct->sotk == null ? $ct->cttm_heso = $ct->st_heso - ($ct->bhxh_hs + $ct->bhyt_hs) : $ct->ctck_heso = $ct->st_heso - ($ct->bhxh_hs + $ct->bhyt_hs);
                    } else {
                        $ct->sotk == null ? $ct->cttm_heso = $ct->st_heso : $ct->ctck_heso = $ct->st_heso;
                    }
                    foreach ($a_phucap as $key => $val) {
                        $k = 'st_' . $key;
                        $pc_cttm = 'cttm_' . $key;
                        $pc_ctck = 'ctck_' . $key;
                        $ct->sotk == null ? $ct->$pc_cttm = $ct->$k : $ct->$pc_ctck = $ct->$k;
                    }
                } else {
                    // if($ct->mact == '1506673604'){ //tính bảo hiểm cho cán bộ chuyên trách
                    $ct->bhxh_hs = $ct->ttbh == 0 ? 0 : $ct->st_heso * 8 / 100;
                    $ct->bhyt_hs = $ct->ttbh == 0 ? 0 : $ct->st_heso * 1.5 / 100;
                    if ($ct->ttbh != 0) {
                        $ct->bhxh_luonghd = $ct->st_luonghd == 0 ? 0 : $ct->st_luonghd * 8 / 100;
                        $ct->bhyt_luonghd = $ct->st_luonghd == 0 ? 0 : $ct->st_luonghd * 1.5 / 100;
                    }

                    if ($ct->ttbh != 0) {
                        $ct->sotk == null ? $ct->cttm_heso = $ct->st_heso - ($ct->bhxh_hs + $ct->bhyt_hs) : $ct->ctck_heso = $ct->st_heso - ($ct->bhxh_hs + $ct->bhyt_hs);
                    } else {
                        $ct->sotk == null ? $ct->cttm_heso = $ct->st_heso : $ct->ctck_heso = $ct->st_heso;
                    }
                    foreach ($a_phucap as $key => $val) {
                        $bhxh_pc = 'bhxh_' . $key;
                        $bhyt_pc = 'bhyt_' . $key;
                        $st_pc = 'st_' . $key;
                        $a_bh[$key] == 0 ? $ct->$bhxh_pc = 0 : $ct->$bhxh_pc = $ct->$st_pc * 8 / 100;
                        $a_bh[$key] == 0 ? $ct->$bhyt_pc = 0 : $ct->$bhyt_pc = $ct->$st_pc * 1.5 / 100;

                        $pc_cttm = 'cttm_' . $key;
                        $pc_ctck = 'ctck_' . $key;
                        // if($a_bh[$key]!= 0){
                        $ct->sotk == null ? $ct->$pc_cttm = $ct->$st_pc - ($ct->$bhxh_pc + $ct->$bhyt_pc) : $ct->$pc_ctck = $ct->$st_pc - ($ct->$bhxh_pc + $ct->$bhyt_pc);
                        // }else{
                        //     $ct->sotk == null?$ct->$pc_cttm=$ct->$st_pc :$ct->$pc_ctck=$ct->$st_pc ;
                        // }
                    }

                    //Tính tiền chuyển khoản hoặc tiền mặt cho hệ số và luonhd
                    // if($ct->ttbh != 0){
                    //     $ct->sotk == null?$ct->cttm_heso=$ct->st_heso - $ct->ttbh :$ct->ctck_heso=$ct->st_heso - $ct->ttbh;
                    // }else{
                    //     $ct->sotk == null?$ct->cttm_heso=$ct->st_heso :$ct->ctck_heso=$ct->st_heso;
                    // };
                }

                // if($ct->ttbh != 0){
                $ct->sotk == null ? $ct->cttm_heso = $ct->st_heso - ($ct->bhxh_hs + $ct->bhyt_hs) : $ct->ctck_heso = $ct->st_heso - ($ct->bhxh_hs + $ct->bhyt_hs);
                $ct->sotk == null ? $ct->cttm_luonghd = $ct->st_luonghd - ($ct->bhxh_luonghd + $ct->bhxh_luonghd) : $ct->ctck_heso = $ct->st_luonghd - ($ct->bhxh_luonghd + $ct->bhxh_luonghd);
                // }else{
                //     $ct->sotk == null?$ct->cttm_heso=$ct->st_heso :$ct->ctck_heso=$ct->st_heso;
                // };
            }
            // dd($a_tbh);
            // dd($model->where('mact','1506673604')); 
            // dd($model_tm); 
            switch ($inputs['mact']) {
                case '1506673695': {
                        return   view('reports.bangluong.donvi.maublcbkct_xa')
                            ->with('thongtin', $thongtin)
                            ->with('m_bl', $m_bl)
                            ->with('m_dv', $m_dv)
                            ->with('model', $model)
                            ->with('model_congtac', $model_congtac)
                            ->with('a_phucap', $a_phucap)
                            ->with('col', $col)
                            ->with('model_tm', $model_tm)
                            ->with('a_bh', $a_bh)
                            ->with('a_tm', $a_tm)
                            ->with('a_tieumuc', $a_tieumuc)
                            ->with('pageTitle', 'Bảng lương chi tiết');
                        break;
                    }
                case '1535613221': {
                        return   view('reports.bangluong.donvi.maublcbkct_thon')
                            ->with('thongtin', $thongtin)
                            ->with('m_bl', $m_bl)
                            ->with('m_dv', $m_dv)
                            ->with('model', $model)
                            ->with('model_congtac', $model_congtac)
                            ->with('a_phucap', $a_phucap)
                            ->with('col', $col)
                            ->with('model_tm', $model_tm)
                            ->with('a_bh', $a_bh)
                            ->with('a_tm', $a_tm)
                            ->with('a_tieumuc', $a_tieumuc)
                            ->with('pageTitle', 'Bảng lương chi tiết');
                        break;
                    }
                case '1536402878': {
                        return   view('reports.bangluong.donvi.maublpc_lldq')
                            ->with('thongtin', $thongtin)
                            ->with('m_bl', $m_bl)
                            ->with('m_dv', $m_dv)
                            ->with('model', $model)
                            ->with('model_congtac', $model_congtac)
                            ->with('a_phucap', $a_phucap)
                            ->with('col', $col)
                            ->with('model_tm', $model_tm)
                            ->with('a_bh', $a_bh)
                            ->with('a_tm', $a_tm)
                            ->with('a_tieumuc', $a_tieumuc)
                            ->with('pageTitle', 'Bảng lương chi tiết');
                        break;
                    }
                case '1506673604': {
                        return   view('reports.bangluong.donvi.maublcbct')
                            ->with('thongtin', $thongtin)
                            ->with('m_bl', $m_bl)
                            ->with('m_dv', $m_dv)
                            ->with('model', $model)
                            ->with('model_congtac', $model_congtac)
                            ->with('a_phucap', $a_phucap)
                            ->with('col', $col)
                            ->with('model_tm', $model_tm)
                            ->with('a_tm', $a_tm)
                            ->with('a_bh', $a_bh)
                            ->with('a_tieumuc', $a_tieumuc)
                            ->with('model_bh', $model_bh)
                            ->with('pageTitle', 'Bảng lương chi tiết');
                        break;
                    }
                default: {
                        return   view('reports.bangluong.donvi.maublxaphuong')
                            ->with('thongtin', $thongtin)
                            ->with('m_bl', $m_bl)
                            ->with('m_dv', $m_dv)
                            ->with('model', $model)
                            ->with('model_congtac', $model_congtac)
                            ->with('a_phucap', $a_phucap)
                            ->with('col', $col)
                            ->with('model_tm', $model_tm)
                            ->with('a_tm', $a_tm)
                            ->with('a_bh', $a_bh)
                            ->with('a_tieumuc', $a_tieumuc)
                            ->with('model_bh', $model_bh)
                            ->with('pageTitle', 'Bảng lương chi tiết');
                    }
            }
        } else
            return view('errors.notlogin');
    }
}
