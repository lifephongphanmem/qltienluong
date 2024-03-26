<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\nhomphanloaict;
use Illuminate\Support\Facades\Session;

class nhomphanloaictController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $model_pl = nhomphanloaict::all();  
            return view('system.danhmuc.nhomphanloaict.index')
                ->with('model',$model_pl)
                ->with('pageTitle', 'Nhóm phân loại công tác');
        } else
            return view('errors.notlogin');
    }
}
