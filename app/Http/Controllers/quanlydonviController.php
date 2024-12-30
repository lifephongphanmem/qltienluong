<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\Users;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\lichsu_hddonvi;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class quanlydonviController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $a_dv_tonghop = array_column(dmdonvi::where('phanloaitaikhoan', 'TH')->get()->toarray(), 'tendv', 'madv');
            $madv = session('admin')->madv;
            $inputs['madv'] = $inputs['madv'] ?? array_key_first($a_dv_tonghop);
            // dd($inputs);
            if (session('admin')->phamvitonghop == 'KHOI') {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'trangthai', 'ngaydung', 'ngaytao', 'ghichu')
                    ->where('macqcq', $madv)->where('madv', '<>', $madv)->distinct()->get();
            }
            if (session('admin')->phamvitonghop == 'HUYEN') {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv', 'phanloaitaikhoan', 'trangthai', 'ngaydung', 'ngaytao', 'ghichu')
                    ->where('macqcq', $madv)->where('madv', '<>', $madv)->distinct()->get();
            }
            if (session('admin')->sadmin == 'SSA') {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv', 'macqcq', 'phanloaitaikhoan', 'trangthai', 'ngaydung', 'ngaytao', 'ghichu')
                    ->where('macqcq', $inputs['madv'])
                    ->distinct()->get();
            }
            $a_trangthai = array(
                'ALL' => 'Chọn tất cả',
                'TD' => 'Tạm dừng',
                'HD' => 'Hoạt động'
            );
            return view('manage.taikhoan.index_quanly')
                ->with('model', $model_donvi)
                ->with('a_dv_tonghop', $a_dv_tonghop)
                ->with('a_dv_tonghop_in', ['ALL' => 'Chọn tất cả '] + $a_dv_tonghop)
                ->with('inputs', $inputs)
                ->with('a_trangthai', $a_trangthai)
                ->with('furl', '/he_thong/don_vi/')
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function stop(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            // dd($inputs);
            $model = dmdonvi::where('madv', $inputs['madvstop'])->first();
            $model->ngaytao = $inputs['ngaytao'];
            if ($inputs['ngaydung'] != '') {
                $model->ngaydung = $inputs['ngaydung'];
                $model->trangthai = 'TD';
                $model->ghichu = $inputs['ghichu'];
                $modeluser = Users::where('madv', $inputs['madvstop'])->first();
                $modeluser->status = 'notactive';
                $modeluser->save();
            }
            $model->save();
            $this->add_history($model);
            return redirect('/he_thong/don_vi/stopdv?madv=' . $model->macqcq);
        } else
            return view('errors.notlogin');
    }
    public function active(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dmdonvi::where('madv', $inputs['madvactive'])->first();
            // $model->ngaydung = null;
            $model->ngaydung = $inputs['ngaydung'];
            $model->trangthai = 'HD';
            $model->ghichu = $inputs['ghichu'];
            $model->save();
            $modeluser = Users::where('madv', $inputs['madvactive'])->first();
            $modeluser->status = 'active';
            $modeluser->save();
            $this->add_history($model);
            return redirect('/he_thong/don_vi/stopdv?madv=' . $model->macqcq);
        } else
            return view('errors.notlogin');
    }
    public function add_history($model)
    {
        $m_history = new lichsu_hddonvi();
        $m_history->mals = getdate()[0];
        $m_history->madv = $model->madv;
        $m_history->macqcq = $model->macqcq;
        $m_history->ghichu = $model->ghichu;
        $m_history->ngaythang = $model->ngaydung;
        $m_history->action = $model->trangthai == null ? 'HD' : $model->trangthai;
        $m_history->save();
    }

    public function NhatKy(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['denngay']=$inputs['denngay']==''?Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d'):$inputs['denngay'];
            // dd($inputs);
            $model = lichsu_hddonvi::where(function ($q) use ($inputs) {
                if ($inputs['madv'] != 'ALL') {
                    $q->where('macqcq', $inputs['madv']);
                }
                if ($inputs['tungay'] != null) {
                    $q->where('ngaythang', '>=', $inputs['tungay']);
                }
                if ($inputs['denngay'] != '') {
                    $q->where('ngaythang', '<=', $inputs['denngay']);
                }
                if ($inputs['trangthai'] != 'ALL') {
                    $q->where('action', $inputs['trangthai']);
                }
            })
                ->get();
            $a_dv= array_column(dmdonvi::where('phanloaitaikhoan','TH')->get()->toarray(), 'tendv', 'madv');
            $a_trangthai = array(
                'TD' => 'Tạm dừng',
                'HD' => 'Hoạt động'
            );
            return view('manage.taikhoan.nhatky', compact('model', 'a_dv','a_trangthai','inputs'))
                ->with('a_madv',array_column(dmdonvi::all()->toarray(),'tendv','madv'))
                ->with('pageTitle', 'Danh sách đơn vị tổng hợp lương');
        } else
            return view('errors.notlogin');
    }
}
