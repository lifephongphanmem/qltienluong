<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'ajax'], function () {
    //Route::get('kieuct','ajaxController@getKieuCT');
    //Route::get('tenct','ajaxController@getTenCT');
    //Route::get('tennb','ajaxController@getTenNB');
    Route::get('bac', 'ajaxController@getBac');
    Route::get('heso', 'ajaxController@getHS');
    //Route::get('msnb','ajaxController@getMSNB');
    Route::get('checkmadv', 'ajaxController@checkmadv');
    //Route::get('phucap','ajaxController@getPhuCap');

    // Route::group(['prefix' => 'tai_lieu'], function () {
    //     Route::get('add', 'hosotailieuController@store');
    //     Route::get('update', 'hosotailieuController@update');
    //     Route::get('get', 'hosotailieuController@get_detail');
    // });
    Route::group(['prefix' => 'quan_he_gd'], function () {
        Route::get('add_bt', 'hosoquanhegdController@store_bt');
        Route::get('update_bt', 'hosoquanhegdController@update_bt');
        Route::get('add_vc', 'hosoquanhegdController@store_vc');
        Route::get('update_vc', 'hosoquanhegdController@update_vc');
        Route::get('get', 'hosoquanhegdController@get_detail');
    });

    // Route::group(['prefix' => 'chuc_vu'], function () {
    //     Route::get('add', 'hosochucvuController@store');
    //     Route::get('update', 'hosochucvuController@update');
    //     Route::get('get', 'hosochucvuController@get_detail');
    // });
    // Route::group(['prefix' => 'bao_hiem'], function () {
    //     Route::get('add', 'hosobaohiemyteController@store');
    //     Route::get('update', 'hosobaohiemyteController@update');
    //     Route::get('get', 'hosobaohiemyteController@get_detail');
    // });
    Route::group(['prefix' => 'luc_luong_vu_trang'], function () {
        Route::get('add', 'hosollvtController@store');
        Route::get('update', 'hosollvtController@update');
        Route::get('get', 'hosollvtController@get_detail');
    });
    Route::group(['prefix' => 'chi_tieu'], function () {
        Route::get('add', 'chitieubiencheController@store');
        Route::get('update', 'chitieubiencheController@update');
        Route::get('get', 'chitieubiencheController@get_detail');
        Route::get('getNamChiTieu', 'chitieubiencheController@getNamChiTieu');
    });
    Route::group(['prefix' => 'dao_tao'], function () {
        Route::get('add', 'hosodaotaoController@store');
        Route::get('update', 'hosodaotaoController@update');
        Route::get('get', 'hosodaotaoController@getinfo');
    });
    Route::group(['prefix' => 'cong_tac'], function () {
        Route::get('add', 'hosocongtacController@store');
        Route::get('update', 'hosocongtacController@update');
        Route::get('get', 'hosocongtacController@getinfo');
    });
    Route::group(['prefix' => 'cong_tac_nuoc_ngoai'], function () {
        Route::get('add', 'hosocongtacnnController@store');
        Route::get('update', 'hosocongtacnnController@update');
        Route::get('get', 'hosocongtacnnController@getinfo');
    });
    Route::group(['prefix' => 'luong'], function () {
        Route::get('add', 'hosoluongController@store');
        Route::get('update', 'hosoluongController@update');
        Route::get('get', 'hosoluongController@getinfo');
    });
    /*
    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('add','hosophucapController@store');
        Route::get('update','hosophucapController@update');
        Route::get('get','hosophucapController@getinfo');
    });
    */
    // Route::group(['prefix' => 'binh_bau'], function () {
    //     Route::get('add', 'hosobinhbauController@store');
    //     Route::get('update', 'hosobinhbauController@update');
    //     Route::get('get', 'hosobinhbauController@getinfo');
    // });
    Route::group(['prefix' => 'khen_thuong'], function () {
        Route::get('add', 'hosokhenthuongController@store');
        Route::get('update', 'hosokhenthuongController@update');
        Route::get('get', 'hosokhenthuongController@getinfo');
    });
    Route::group(['prefix' => 'ky_luat'], function () {
        Route::get('add', 'hosokyluatController@store');
        Route::get('update', 'hosokyluatController@update');
        Route::get('get', 'hosokyluatController@getinfo');
    });
    // Route::group(['prefix' => 'thanh_tra'], function () {
    //     Route::get('add', 'hosothanhtraController@store');
    //     Route::get('update', 'hosothanhtraController@update');
    //     Route::get('get', 'hosothanhtraController@getinfo');
    // });
    Route::group(['prefix' => 'nhan_xet'], function () {
        Route::get('add', 'hosonhanxetdgController@store');
        Route::get('update', 'hosonhanxetdgController@update');
        Route::get('get', 'hosonhanxetdgController@getinfo');
    });

    // Route::group(['prefix' => 'het_tap_su'], function () {
    //     Route::get('add', 'dshettapsuController@store');
    //     Route::get('update', 'dshettapsuController@update');
    //     Route::get('get', 'dshettapsuController@getinfo');
    // });
    // Route::group(['prefix' => 'huu_tri'], function () {
    //     Route::get('add', 'dshuutriController@store');
    //     Route::get('update', 'dshuutriController@update');
    //     Route::get('get', 'dshuutriController@getinfo');
    // });

    Route::group(['prefix' => 'nang_luong'], function () {
        Route::get('add', 'dsnangluongController@store');
        Route::get('update', 'dsnangluongController@update');
        Route::get('get', 'dsnangluongController@getinfo');
    });

    //    Route::group(['prefix'=>'dieu_dong'],function(){
    //        Route::get('add','hosoluanchuyenController@store_dd');
    //        Route::get('update','hosoluanchuyenController@update_dd');
    //        Route::get('get','hosoluanchuyenController@get_detail');
    //    });

    Route::group(['prefix' => 'bang_luong'], function () {
        Route::get('add', 'bangluongController@store');
        Route::get('update', 'bangluongController@update');
        Route::get('get', 'bangluongController@getinfo');
        Route::get('get_nguonkp', 'bangluongController@getinfor_nguonkp');
    });

    Route::group(['prefix' => 'khoi_pb'], function () {
        Route::get('add', 'dmkhoipbController@store');
        Route::get('update', 'dmkhoipbController@update');
        Route::get('get', 'dmkhoipbController@getinfo');
    });

    Route::group(['prefix' => 'du_toan'], function () {
        Route::get('add', 'dutoanluongController@store');
        Route::get('update', 'dutoanluongController@update');
        Route::get('get', 'dutoanluongController@get_detail');
    });
});
