<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'tra_cuu'], function () {
    Route::group(['prefix' => 'ho_so'], function () {
        Route::get('', 'hosocanboController@search');
        Route::post('ket_qua', 'hosocanboController@result');
    });

    // Route::group(['prefix' => 'truy_linh'], function () {
    //     Route::get('', 'tracuuController@search_truylinh');
    //     Route::post('ket_qua', 'hosocanboController@result');
    // });

    /*
    Route::group(['prefix'=>'cong_tac'],function(){
        Route::get('','hosocongtacController@search');
        Route::post('ket_qua','hosocongtacController@result');
    });
    Route::group(['prefix'=>'dao_tao'],function(){
        Route::get('','hosodaotaoController@search');
        Route::post('ket_qua','hosodaotaoController@result');
    });
    */
    Route::group(['prefix' => 'luong'], function () {
        Route::get('', 'hosoluongController@search');
        Route::post('ket_qua', 'hosoluongController@result');
    });

    Route::group(['prefix' => 'chi_luong'], function () {
        Route::get('', 'bangluongController@search');
        Route::post('ket_qua', 'bangluongController@result');
    });
    /*
    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('','hosophucapController@search');
        Route::post('ket_qua','hosophucapController@result');
    });
    Route::group(['prefix'=>'gia_dinh'],function(){
        Route::get('','hosoquanhegdController@search');
        Route::post('ket_qua','hosoquanhegdController@result');
    });
    */
});
