<?php

use App\Http\Controllers\nguonkinhphiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'nguon_kinh_phi'], function () {
    Route::get('danh_sach', [nguonkinhphiController::class, 'index']);
    Route::get('chi_tiet', 'nguonkinhphiController@edit');

    Route::post('create', 'nguonkinhphiController@create');
    Route::post('create_mau', 'nguonkinhphiController@create_mau');
    Route::get('get', 'nguonkinhphiController@getinfo');
    Route::get('ma_so={masodv}', 'nguonkinhphiController@edit');
    Route::post('update', 'nguonkinhphiController@update');
    Route::post('senddata', 'nguonkinhphiController@senddata'); //gửi dữ liệu
    Route::get('ma_so={masodv}/in', 'nguonkinhphiController@printf');
    Route::get('getlydo', 'nguonkinhphiController@getlydo');
    Route::get('del/{id}', 'nguonkinhphiController@destroy');
    Route::get('get_thongtu', 'nguonkinhphiController@getinfor_thongtu');

    Route::get('printf', 'nguonkinhphi_donvi_baocaoController@printf_data'); //in theo thoại
    Route::post('mautt107', 'nguonkinhphi_donvi_baocaoController@printf_tt107');
    Route::post('mautt107_m2', 'nguonkinhphi_donvi_baocaoController@printf_tt107_m2');
    Route::get('mautt107_m3', 'nguonkinhphi_donvi_baocaoController@printf_tt107_m3');
    //Route::get('nangluong', 'nguonkinhphi_donvi_baocaoController@printf_nangluong');
    Route::post('nangluong', 'nguonkinhphi_donvi_baocaoController@printf_nangluong');
    //2023.06.07 làm theo TT78/2022 cho vạn ninh
    Route::post('tonghopnhucau_donvi', 'nguonkinhphi_donvi_baocaoController@tonghopnhucau_donvi');
    Route::post('tonghopnhucau_donvi_2a', 'nguonkinhphi_donvi_baocaoController@tonghopnhucau_donvi_2a');
    Route::post('tonghopnhucau_donvi_2a_2', 'nguonkinhphi_donvi_baocaoController@tonghopnhucau_donvi_2a_2');


    Route::post('mau2b', 'nguonkinhphi_donvi_baocaoController@mau2b');
    Route::post('mau2c', 'nguonkinhphi_donvi_baocaoController@mau2c');
    Route::post('mau2d', 'nguonkinhphi_donvi_baocaoController@mau2d');
    Route::post('mau2e', 'nguonkinhphi_donvi_baocaoController@mau2e');
    Route::post('mau2g', 'nguonkinhphi_donvi_baocaoController@mau2g');
    // Route::post('mau2c', 'nguonkinhphi_donvi_baocaoController@mau2c');
    // Route::post('mau2d', 'nguonkinhphi_donvi_baocaoController@mau2d');
    // Route::post('mau2dd', 'nguonkinhphi_donvi_baocaoController@mau2dd');
    // Route::post('mau2e', 'nguonkinhphi_donvi_baocaoController@mau2e');
    // Route::post('mau2g', 'nguonkinhphi_donvi_baocaoController@mau2g');
    // Route::post('mau2h', 'nguonkinhphi_donvi_baocaoController@mau2h');
    // Route::post('mau2i', 'nguonkinhphi_donvi_baocaoController@mau2i');
    // Route::post('mau2k', 'nguonkinhphi_donvi_baocaoController@mau2k');
    // Route::post('mau2l', 'nguonkinhphi_donvi_baocaoController@mau2l');
    
    Route::post('mau4a', 'nguonkinhphi_donvi_baocaoController@mau4a');
    Route::post('mau4b', 'nguonkinhphi_donvi_baocaoController@mau4b');

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
