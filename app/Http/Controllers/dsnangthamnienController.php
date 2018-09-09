<?php

namespace App\Http\Controllers;

use App\dsnangthamnien;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class dsnangthamnienController extends Controller
{
    function index(){
        if (Session::has('admin')) {
            $model = dsnangthamnien::where('madv',session('admin')->madv)->get();
            return view('manage.thamnien.index')
                ->with('furl','/chuc_nang/tham_nien/')
                ->with('furl_ajax','/ajax/nang_luong/')
                ->with('model',$model)
                ->with('tendv',getTenDV(session('admin')->madv))
                ->with('pageTitle','Danh sách nâng thâm niên nghề');
        } else
            return view('errors.notlogin');
    }
}
