<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'danh_muc'], function () {
    Route::group(['prefix' => 'phong_ban'], function () {
        Route::get('index', 'dmphongbanController@index');
        Route::get('del/{id}', 'dmphongbanController@destroy');
        Route::get('add', 'dmphongbanController@store');
        Route::get('update', 'dmphongbanController@update');
        Route::get('get', 'dmphongbanController@getinfo');
    });

    Route::group(['prefix' => 'chuc_vu'], function () {
        Route::get('index', 'dmchucvucqController@index_donvi');
        Route::get('del/{id}', 'dmchucvucqController@destroy_donvi');
        Route::get('add', 'dmchucvucqController@store');
        Route::get('update', 'dmchucvucqController@update');
        Route::get('get', 'dmchucvucqController@getinfo');
    });

    Route::group(['prefix' => 'dan_toc'], function () {
        Route::get('index', 'dmdantocController@index');
        Route::get('store', 'dmdantocController@store');
        Route::get('del/{id}', 'dmdantocController@destroy');
    });

    Route::group(['prefix' => 'phu_cap'], function () {
        Route::get('don_vi', 'dmphucapController@index_donvi');
        Route::get('don_vi/edit', 'dmphucapController@edit_donvi');
        Route::post('don_vi/update', 'dmphucapController@update_donvi');

        Route::get('don_vi/anhien', 'dmphucapController@anhien');
        Route::post('don_vi/default_pc', 'dmphucapController@default_pc');
        Route::post('don_vi/pccoso', 'dmphucapController@set_pccoso');
    });

    Route::group(['prefix' => 'cong_tac'], function () {
        Route::get('index', 'dmphanloaictController@index');
        Route::get('del/{id}', 'dmphanloaictController@destroy');
        Route::get('add', 'dmphanloaictController@store');
        Route::get('update', 'dmphanloaictController@update');
        Route::get('get', 'dmphanloaictController@getinfo');

        Route::get('ma_so={macongtac}', 'dmphanloaictController@detail');
        Route::get('del_detail/{id}', 'dmphanloaictController@destroy_detail');
        Route::get('add_detail', 'dmphanloaictController@store_detail');
        Route::post('update_detail', 'dmphanloaictController@update_detail');
        Route::get('get_detail', 'dmphanloaictController@getinfo_detail');
        //Cho đơn vị cấp dưới xem
        Route::get('don_vi', 'dmphanloaictController@xemdulieu');
    });

    Route::group(['prefix' => 'don_vi'], function () {
        Route::get('maso={maso}', 'dmdonviController@index');
        Route::get('store', 'dmdonviController@store');
        Route::get('del/{id}', 'dmdonviController@destroy');
        Route::get('change/maso={madv}', 'dmdonviController@change');
    });

    Route::group(['prefix' => 'khoi_pb'], function () {
        Route::get('index', 'dmkhoipbController@index');
        Route::get('store', 'dmkhoipbController@store');
        Route::get('del/{id}', 'dmkhoipbController@destroy');
    });

    Route::group(['prefix' => 'khu_vuc'], function () {
        Route::get('danh_sach', 'dmdonvibaocaoController@index');
        Route::get('get', 'dmdonvibaocaoController@getinfo');
        Route::get('add', 'dmdonvibaocaoController@store');
        Route::get('update', 'dmdonvibaocaoController@update');

        Route::get('del/{maso}', 'dmdonvibaocaoController@destroy');

        Route::get('chi_tiet', 'dmdonvibaocaoController@list_donvi');
        Route::get('ma_so={makhuvuc}&don_vi={madonvi}/edit', 'dmdonvibaocaoController@show_donvi');
        Route::get('create', 'dmdonvibaocaoController@create_donvi');

        Route::patch('update_donvi', 'dmdonvibaocaoController@update_donvi');
        Route::post('store_donvi', 'dmdonvibaocaoController@store_donvi');
        Route::get('del_donvi/{madv}', 'dmdonvibaocaoController@destroy_donvi');
        Route::get('get_list_unit', 'dmdonvibaocaoController@get_list_unit');
        Route::get('set_management', 'dmdonvibaocaoController@set_management');
        Route::post('get_canbo', 'dmdonvibaocaoController@get_canbo');

        Route::get('update_plct', 'dmdonvibaocaoController@update_plct');
        Route::get('update_sunghiep', 'dmdonvibaocaoController@update_sunghiep');
        Route::get('update_nguonkp', 'dmdonvibaocaoController@update_nguonkp');
        Route::get('del_dscanbo/{maso}', 'dmdonvibaocaoController@del_dscanbo');
        Route::get('update_linhvuchoatdong', 'dmdonvibaocaoController@update_linhvuchoatdong');

        Route::get('getPhanLoai', 'ajaxController@getPhanLoai');
    });

    Route::group(['prefix' => 'tai_khoan'], function () {
        Route::get('list_user', 'UsersController@list_users');
        Route::get('ma_so={madv}/create', 'UsersController@create');
        Route::post('add_user', 'UsersController@store');

        Route::get('ma_so={taikhoan}/permission', 'UsersController@permission');
        Route::post('ma_so={taikhoan}/uppermission', 'UsersController@uppermission');
        Route::get('del_taikhoan/{madv}', 'UsersController@destroy');
    });

    Route::group(['prefix' => 'nguon_kinh_phi'], function () {
        Route::get('index', 'dmnguonkinhphiController@index');
        Route::get('del/{id}', 'dmnguonkinhphiController@destroy');
        Route::get('add', 'dmnguonkinhphiController@store');
        Route::get('update', 'dmnguonkinhphiController@update');
        Route::get('get', 'dmnguonkinhphiController@getinfo');
    });

    Route::group(['prefix' => 'pl_don_vi'], function () {
        Route::get('index', 'dmphanloaidonviController@index');
        Route::get('del/{id}', 'dmphanloaidonviController@destroy');
        Route::get('store', 'dmphanloaidonviController@store');
        // Route::get('update', 'dmphanloaidonviController@update');
        Route::get('get', 'dmphanloaidonviController@getinfo');

        Route::get('', 'dmphanloaidonviController@phucap');
        Route::get('edit', 'dmphanloaidonviController@edit_phucap');
        Route::post('phu_cap/update', 'dmphanloaidonviController@update_phucap');
        Route::get('anhien', 'dmphanloaidonviController@anhien');
    });

    Route::group(['prefix' => 'ngach_bac'], function () {
        Route::get('index', 'dmngachluongController@index');
        Route::get('del/{id}', 'dmngachluongController@destroy');
        Route::post('store', 'dmngachluongController@store'); //insert + update
        Route::get('get', 'dmngachluongController@getinfo');

        Route::get('ma_so={macongtac}', 'dmngachluongController@detail');
        Route::get('del_detail/{id}', 'dmngachluongController@destroy_detail');
        Route::post('store_detail', 'dmngachluongController@store_detail'); //insert + update
        Route::get('get_detail', 'dmngachluongController@getinfo_detail');

        Route::get('danhsach', 'dmngachluongController@danhsach');
    });

    Route::group(['prefix' => 'tieu_muc'], function () {
        Route::get('index', 'dmtieumuc_defaultController@index');
        Route::post('store', 'dmtieumuc_defaultController@store');

        Route::get('del/{id}', 'dmtieumuc_defaultController@destroy');
        Route::get('get', 'dmtieumuc_defaultController@getinfo');
    });

    Route::group(['prefix' => 'thai_san'], function () {
        Route::get('danh_sach', 'dmphucap_thaisanController@index');
        Route::post('store', 'dmphucap_thaisanController@store');
        Route::get('del/{id}', 'dmphucap_thaisanController@destroy');
    });

    Route::group(['prefix' => 'thong_tu'], function () {
        Route::get('index', 'dmthongtuquyetdinhController@index');
        Route::get('del/{id}', 'dmthongtuquyetdinhController@destroy');
        Route::get('store', 'dmthongtuquyetdinhController@store');
        Route::get('get', 'dmthongtuquyetdinhController@getinfo');
    });

    Route::group(['prefix' => 'thuetncn'], function () {
        Route::get('index', 'dmthuetncnController@index');
        Route::get('del/{id}', 'dmthuetncnController@destroy');
        Route::post('store', 'dmthuetncnController@store');
        Route::get('get', 'dmthuetncnController@getinfo');

        Route::get('detail', 'dmthuetncnController@detail');
        Route::post('store_detail', 'dmthuetncnController@store_detail');
        Route::get('get_detail', 'dmthuetncnController@getinfo_detail');
        Route::get('del_detail/{id}', 'dmthuetncnController@destroy_detail');
    });
});
