<?php

use App\Http\Controllers\dsdonviquanlyController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'he_thong'], function () {
    Route::group(['prefix' => 'DonViQuanLy'], function () {
        Route::get('danh_sach',[dsdonviquanlyController::class,'danh_sach']);
        Route::post('ThayDoi', [dsdonviquanlyController::class,'ThayDoi']);
        Route::get('Xoa/{id}', [dsdonviquanlyController::class,'Xoa']);
    });

    Route::group(['prefix' => 'don_vi'], function () {
        Route::get('don_vi', 'dmdonviController@information_local');
        Route::get('stopdv', 'quanlydonviController@index');    
        Route::post('stopdv/stop', 'quanlydonviController@stop');
        Route::post('stopdv/active', 'quanlydonviController@active');
        Route::get('thong_tin', 'dmdonviController@edit_local');
        Route::get('thong_tin_th', 'dmdonviController@edit_th');
        Route::post('/{madv}', 'dmdonviController@update_local');
        Route::get('chung', 'dmdonviController@information_global');
        Route::get('maso={id}/edit_global', 'dmdonviController@edit_global');
        Route::patch('/{id}/global', 'dmdonviController@update_global');
        Route::get('capnhat_bh', 'dmphanloaicongtac_baohiemController@capnhat');
        Route::get('bao_hiem', 'dmphanloaicongtac_baohiemController@index');
        Route::get('update_bh', 'dmphanloaicongtac_baohiemController@update');
        Route::get('get_bh', 'dmphanloaicongtac_baohiemController@getinfo');
    });

    Route::group(['prefix' => 'quan_tri'], function () {
        Route::get('don_vi', 'dmdonviController@information_manage');
        Route::get('don_vi/create', 'dmdonviController@create_manage');
        Route::post('store', 'dmdonviController@store_manage');

        Route::get('don_vi/maso={madv}', 'dmdonviController@list_account');
        Route::get('don_vi/maso={madv}/create', 'dmdonviController@create_account');
        Route::post('don_vi/maso={madv}/store', 'dmdonviController@store_account');
        Route::get('don_vi/maso={id}/edit', 'dmdonviController@edit_account');
        Route::patch('don_vi/maso={id}/update', 'dmdonviController@update_account');
        Route::post('destroy_account', 'dmdonviController@destroy_account');

        //Route::get('don_vi/maso={id}/phan_quyen','dmdonviController@permission_list');
        //Route::post('don_vi/maso={id}/up_perm','dmdonviController@permission_update');

        Route::get('don_vi/maso={madv}/don_vi', 'dmdonviController@edit_information');
        Route::patch('don_vi/maso={madv}', 'dmdonviController@update_information');

        Route::get('he_thong', 'GeneralConfigsController@index');
        Route::get('he_thong/edit', 'GeneralConfigsController@edit');
        Route::post('he_thong/update', 'GeneralConfigsController@update');
    });

    Route::group(['prefix' => 'phu_cap'], function () {
        Route::get('index', 'dmphucapController@index');
        Route::get('del/{id}', 'dmphucapController@destroy');
        Route::get('get', 'dmphucapController@getinfo');

        Route::get('create', 'dmphucapController@create');
        Route::post('update', 'dmphucapController@update');
        Route::get('edit', 'dmphucapController@edit');
        Route::post('store', 'dmphucapController@store');
    });

    Route::group(['prefix' => 'chuc_vu'], function () {
        Route::get('index', 'dmchucvucqController@index');
        Route::get('del/{id}', 'dmchucvucqController@destroy');
        Route::get('add', 'dmchucvucqController@store');
        Route::get('update', 'dmchucvucqController@update');
        Route::get('get', 'dmchucvucqController@getinfo');
    });

    Route::group(['prefix' => 'dinh_muc'], function () {
        Route::get('danh_sach', 'nguonkinhphi_dinhmucController@index');
        Route::get('get', 'nguonkinhphi_dinhmucController@getinfo');
        Route::post('store', 'nguonkinhphi_dinhmucController@store');
        Route::post('destroy', 'nguonkinhphi_dinhmucController@destroy');
        Route::get('get_ct', 'nguonkinhphi_dinhmucController@getinfor_ct');
        Route::get('update_ct', 'nguonkinhphi_dinhmucController@update_ct');

        Route::get('phu_cap', 'nguonkinhphi_dinhmucController@phucap');
        Route::post('store_pc', 'nguonkinhphi_dinhmucController@store_pc');
        Route::get('del/{id}', 'nguonkinhphi_dinhmucController@destroy_pc');
        Route::post('update_luongcb', 'nguonkinhphi_dinhmucController@update_luongcb');
    });

    Route::group(['prefix' => 'bao_cao'], function () {
        Route::get('danh_sach', 'dmphanloaidonvi_baocaoController@index');
        Route::get('inbaocao', 'dmphanloaidonvi_baocaoController@inbaocao');
        Route::post('store', 'dmphanloaidonvi_baocaoController@store');
        Route::get('del/{id}', 'dmphanloaidonvi_baocaoController@destroy');
    });
});
?>