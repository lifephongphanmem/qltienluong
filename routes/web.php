<?php
use Illuminate\Support\Facades\Route;

Route::get('', 'HomeController@index');
//Route::get('', 'HomeController@baotri');
Route::get('thongtinhotro', 'vanphonghotroController@thongtinhotro');
Route::get('test', 'dataController@update');
Route::get('ajaxtest', 'ajaxController@test');


Route::get('/setting', 'HomeController@setting');
Route::post('/setting', 'HomeController@upsetting');
Route::get('danh_sach_tai_khoan', 'HomeController@listusers');
Route::get('don_vi_cd', 'HomeController@list_donvi_cd');
Route::get('fix_pc', 'dmphucapController@fix_mapc');
//Route::get('fix_ct','dmphucapController@fix_ct');
//Route::get('fix_dv','hosocanboController@upd_dm');


// <editor-fold defaultstate="collapsed" desc="--Hệ thống--">
// <editor-fold defaultstate="collapsed" desc="--Danh mục--">

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="--Quản lý chung--">
Route::group(['prefix' => 'van_phong'], function () {
    Route::get('danh_sach', 'vanphonghotroController@index');
    Route::get('get_chucnang', 'vanphonghotroController@edit');
    Route::post('store', 'vanphonghotroController@store');
    Route::post('delete', 'vanphonghotroController@destroy');
});
// </editor-fold>
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="--Setting--">
Route::get('cau_hinh_he_thong', 'GeneralConfigsController@index');
Route::get('cau_hinh_he_thong/{id}/edit', 'GeneralConfigsController@edit');
Route::patch('cau_hinh_he_thong/{id}', 'GeneralConfigsController@update');

//Users
Route::get('login', 'UsersController@login');
Route::post('signin', 'UsersController@signin');
Route::post('/change_password_df', 'UsersController@change_password_df');
Route::get('/change-password', 'UsersController@cp');
Route::post('/change-password', 'UsersController@cpw');
Route::get('/checkpass', 'UsersController@checkpass');
Route::get('/checkuser', 'UsersController@checkuser');
Route::get('/checkmasothue', 'UsersController@checkmasothue');
Route::get('logout', 'UsersController@logout');
Route::get('users/{id}/edit', 'UsersController@edit');
Route::patch('users/{id}', 'UsersController@update');
Route::get('users/{id}/phan-quyen', 'UsersController@permission');
Route::post('users/phan-quyen', 'UsersController@uppermission');
Route::post('users/delete', 'UsersController@destroy');
Route::get('users/lock/{id}/{pl}', 'UsersController@lockuser');
Route::get('users/unlock/{id}/{pl}', 'UsersController@unlockuser');
//EndUsers
// </editor-fold>//End Setting

Route::group(['prefix' => 'user'], function () {
    Route::post('signin', 'usersController@signin');
    Route::get('logout', 'usersController@logout');
    Route::get('change_user', 'usersController@change_user');
});


include('danhmuc.php');

include('nghiepvu.php');

include('nguonkinhphi.php');

include('chucnang.php');

include('baocao.php');

include('tonghopbaocao.php');

include('tracuu.php');

include('ajax.php');

include('hethong.php');
