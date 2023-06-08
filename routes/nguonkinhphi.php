<?php

use App\Http\Controllers\nguonkinhphiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'nguon_kinh_phi'], function () {
    Route::get('danh_sach', [nguonkinhphiController::class,'index']);
    Route::get('chi_tiet', 'nguonkinhphiController@edit');

    Route::post('create', 'nguonkinhphiController@create');
    Route::post('create_mau', 'nguonkinhphiController@create_mau');
    Route::get('get', 'nguonkinhphiController@getinfo');
    Route::get('ma_so={masodv}', 'nguonkinhphiController@edit');
    Route::post('update', 'nguonkinhphiController@update');
    Route::post('senddata', 'nguonkinhphiController@senddata'); //gửi dữ liệu
    Route::get('ma_so={masodv}/in', 'nguonkinhphiController@printf');
    Route::get('getlydo', 'nguonkinhphiController@getlydo');

    Route::get('printf', 'nguonkinhphiController@printf_data'); //in theo thoại
    Route::post('mautt107', 'nguonkinhphiController@printf_tt107');
    Route::post('mautt107_m2', 'nguonkinhphiController@printf_tt107_m2');
    Route::get('mautt107_m3', 'nguonkinhphiController@printf_tt107_m3');
    Route::get('nangluong', 'nguonkinhphiController@printf_nangluong');

    Route::get('del/{id}', 'nguonkinhphiController@destroy');
    Route::get('get_thongtu', 'nguonkinhphiController@getinfor_thongtu');
    //2023.06.07 làm theo TT78/2022 cho vạn ninh
    Route::post('tonghopnhucau_donvi', 'nguonkinhphiController@tonghopnhucau_donvi');
    Route::post('tonghopnhucau_donvi_2a', 'nguonkinhphiController@tonghopnhucau_donvi_2a');
    //2023.06.07

    Route::group(['prefix' => 'khoi'], function () {
        Route::post('senddata', 'tonghopluong_khoiController@senddata'); //gửi dữ liệu
        Route::post('tralai', 'tonghopluong_khoiController@tralai'); //trả lại dữ liệu
        Route::get('getlydo', 'tonghopluong_khoiController@getlydo'); //lý do trả lại dữ liệu
        Route::get('mautt107_m2', 'tonghopnguon_khoiController@printf_tt107_m2');
    });
    Route::group(['prefix' => 'huyen'], function () {
        Route::get('danh_sach', 'nguonkinhphi_huyen_baocaoController@index');
        Route::get('create', 'nguonkinhphi_huyen_baocaoController@create');
        Route::post('store', 'nguonkinhphi_huyen_baocaoController@store');
        Route::get('ma_so={masodv}', 'nguonkinhphi_huyen_baocaoController@edit');
        Route::get('ma_so={masodv}/show', 'nguonkinhphi_huyen_baocaoController@show');
        Route::post('update', 'nguonkinhphi_huyen_baocaoController@update');
        Route::get('del/{id}', 'nguonkinhphi_huyen_baocaoController@destroy');
        Route::post('senddata', 'nguonkinhphi_huyen_baocaoController@senddata'); //gửi dữ liệu
        Route::get('get_thongtu', 'nguonkinhphi_huyen_baocaoController@getinfor_thongtu');
        Route::get('mautt107_m2', 'tonghopnguon_huyenController@printf_tt107_m2');
    });
});

