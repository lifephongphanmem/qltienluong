<?php

namespace App\Http\Controllers;

use App\dmdonvi;
use App\Users;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class quanlydonviController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('admin')) {
            $madv = session('admin')->madv;
            if(session('admin')->phamvitonghop == 'KHOI')
            {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv','phanloaitaikhoan','trangthai','ngaydung')
                    ->where('macqcq',$madv)->where('madv','<>',$madv)->distinct()->get();
            }
            if(session('admin')->phamvitonghop == 'HUYEN')
            {
                $model_donvi = dmdonvi::select('dmdonvi.madv', 'dmdonvi.tendv','phanloaitaikhoan','trangthai','ngaydung')
                    ->where('macqcq',$madv)->where('madv','<>',$madv)->distinct()->get();
            }
            return view('manage.taikhoan.index_quanly')
                ->with('model', $model_donvi)
                ->with('furl','/he_thong/don_vi/')
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
            //dd($inputs);
            $model = dmdonvi::where('madv', $inputs['madvstop'])->first();
            $model->ngaydung = $inputs['ngaydung'];
            $model->trangthai = 'TD';
            $model->save();
            $modeluser = Users::where('madv',$inputs['madvstop'])->first();
            $modeluser->status = 'notactive';
            $modeluser->save();
            return redirect('/he_thong/don_vi/stopdv');
        } else
            return view('errors.notlogin');
    }
    public function active(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            //dd($inputs);
            $model = dmdonvi::where('madv', $inputs['madvactive'])->first();
            $model->ngaydung = null;
            $model->trangthai = 'HD';
            $model->save();
            $modeluser = Users::where('madv',$inputs['madvactive'])->first();
            $modeluser->status = 'active';
            $modeluser->save();
            return redirect('/he_thong/don_vi/stopdv');
        } else
            return view('errors.notlogin');
    }
}
