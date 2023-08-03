<?php

use App\Http\Controllers\dutoanluongController;
use App\Http\Controllers\hosotamngungtheodoiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'nghiep_vu'], function () {
    Route::group(['prefix' => 'ho_so'], function () {
        Route::get('danh_sach', 'hosocanboController@index');
        Route::patch('update/{id}', 'hosocanboController@update');
        Route::get('create', 'hosocanboController@create');
        Route::get('maso={id}', 'hosocanboController@show');
        Route::get('del/maso={id}', 'hosocanboController@destroy');
        Route::post('store', 'hosocanboController@store');

        Route::get('nhan_excel', 'hosocanboController@infor_excel');
        Route::post('create_excel', 'hosocanboController@create_excel');
        //ajax
        Route::get('get_congtac', 'hosocanboController@get_congtac');
        Route::get('get_chucvu_bh', 'hosocanboController@get_chucvu_bh');

        /*
        Route::get('phucap','hosocanboController@phucap');
        Route::get('get_phucap','hosocanboController@get_phucap');
        Route::get('del_phucap','hosocanboController@detroys_phucap');

        Route::get('syll/{id}','hosocanboController@syll');
        Route::get('ttts/{id}','hosocanboController@tomtatts');
        Route::post('bsll/{id}','hosocanboController@bsll');
        */
        Route::get('thoi_cong_tac', 'hosocanboController@index_thoicongtac');

        //
        Route::post('indanhsach', 'hosocanboController@indanhsach');
        Route::post('indanhsach_excel', 'hosocanboController@indanhsach_excel');
        Route::get('inhoso', 'hosocanboController@inhoso');

        Route::post('store_kiemnhiem', 'hosocanboController@store_kiemnhiem');
        
        Route::get('store_chvu', 'hosocanboController@store_chvu');
        Route::get('store_kct', 'hosocanboController@store_kct');
        Route::get('store_dbhdnd', 'hosocanboController@store_dbhdnd');
        Route::get('store_qs', 'hosocanboController@store_qs');
        Route::get('store_cuv', 'hosocanboController@store_cuv');
        Route::get('store_cd', 'hosocanboController@store_cd');
        Route::get('store_mc', 'hosocanboController@store_mc');
        Route::get('store_tn', 'hosocanboController@store_tn');
        Route::get('getinfor_kn', 'hosocanboController@getinfor_kn');
        Route::get('delete_kn', 'hosocanboController@delete_kn');

        Route::get('temp/store_chvu', 'hosocanboController@store_chvu_temp');
        Route::get('temp/store_kct', 'hosocanboController@store_kct_temp');
        Route::get('temp/store_dbhdnd', 'hosocanboController@store_dbhdnd_temp');
        Route::get('temp/store_qs', 'hosocanboController@store_qs_temp');
        Route::get('temp/store_cuv', 'hosocanboController@store_cuv_temp');
        Route::get('temp/store_cd', 'hosocanboController@store_cd_temp');
        Route::get('temp/store_mc', 'hosocanboController@store_mc_temp');
        Route::get('temp/store_tn', 'hosocanboController@store_tn_temp');
        Route::get('temp/getinfor_kn', 'hosocanboController@getinfor_kn_temp');
        Route::get('temp/delete_kn', 'hosocanboController@delete_kn_temp_temp');
        // Quản lý hồ sơ khối huyện
        Route::get('danh_sach_th', 'quanlyhosoController@index');
        Route::get('nang_luong_th', 'quanlyhosoController@index_nangluong');
        Route::get('nghi_huu_th', 'quanlyhosoController@index_nghihuu');
        Route::post('danh_sach_nang_luong', 'quanlyhosoController@innangluong_th');
        Route::post('danh_sach_nghi_huu', 'quanlyhosoController@innghihuu_th');
    });

    Route::group(['prefix' => 'nhan_su'], function () {
        Route::get('danh_sach', 'hosonhansuController@index');
        Route::patch('update/{id}', 'hosonhansuController@update');
        Route::get('create', 'hosonhansuController@create');
        Route::get('maso={id}', 'hosonhansuController@show');
        Route::get('del/maso={id}', 'hosonhansuController@destroy');
        Route::post('store', 'hosonhansuController@store');

        Route::get('phucap', 'hosonhansuController@phucap');
        Route::get('get_phucap', 'hosonhansuController@get_phucap');
        Route::get('del_phucap', 'hosonhansuController@detroys_phucap');

        Route::get('syll/{id}', 'hosonhansuController@syll');
        Route::get('ttts/{id}', 'hosonhansuController@tomtatts');
        Route::post('bsll/{id}', 'hosonhansuController@bsll');
    });

    Route::group(['prefix' => 'quan_ly'], function () {

        Route::group(['prefix' => 'du_toan'], function () {
            Route::get('', [dutoanluongController::class, 'show']);
            Route::get('danh_sach', 'dutoanluongController@index');
            Route::get('del/{id}', 'dutoanluongController@destroy');
            Route::post('create', 'dutoanluongController@create');
            Route::get('create_mau', 'dutoanluongController@create_mau');
            Route::post('thong_tin', 'dutoanluongController@thongtin_dutoan');
            Route::post('tao_du_toan', 'dutoanluongController@tao_dutoan');
            Route::get('getchitieu', 'dutoanluongController@getchitieu');
            Route::post('updchitieu', 'dutoanluongController@updchitieu');
            Route::post('kinhphiKoCT', 'dutoanluongController@kinhphiKoCT');
            //Route::get('checkNamDuToan','dutoanluongController@checkNamDT');
            //Route::get('checkBangLuong','dutoanluongController@checkBangLuong');
            Route::post('senddata', 'dutoanluongController@senddata'); //gửi dữ liệu
            Route::post('tralai', 'dutoanluongController@tralai'); //trả lại dữ liệu
            Route::get('getlydo', 'dutoanluongController@getlydo'); //lý do trả lại dữ liệu
            Route::get('detail/del/{id}', 'dutoanluongController@destroy_detail');
            Route::post('detail/update', 'dutoanluongController@update_detail');
            Route::get('detail/get', 'dutoanluongController@get_detail');

            Route::get('printf', 'dutoanluongController@printf_data');
            Route::get('printf_m2', 'dutoanluongController@printf_data_m2');
            Route::get('printf_bl/ma_so={masodv}', 'dutoanluongController@printf_bl');
            Route::post('mautt107', 'dutoanluongController@printf_tt107');
            Route::post('mautt107_m2', 'dutoanluongController@printf_tt107_m2');
            Route::get('mautt107_m3', 'dutoanluongController@printf_tt107_m3');
            Route::get('nangluong', 'dutoanluongController@printf_nangluong');
            //thiết kế mẫu in cho vạn ninh
            Route::get('kinhphikhongchuyentrach', 'dutoanluong_insolieuController@kinhphikhongchuyentrach');
            Route::get('tonghopcanboxa', 'dutoanluong_insolieuController@tonghopcanboxa');
            Route::post('tonghopbienche', 'dutoanluong_insolieuController@tonghopbienche');
            Route::post('tonghophopdong', 'dutoanluong_insolieuController@tonghophopdong');
            Route::post('bangluongbienche', 'dutoanluong_insolieuController@bangluongbienche');
            Route::post('bangluonghopdong', 'dutoanluong_insolieuController@bangluonghopdong');
        });
        /*
        Route::group(['prefix'=>'dia_ban_dbkk'],function(){
            Route::get('index','dmdiabandbkkController@index');
            Route::get('del/{id}','dmdiabandbkkController@destroy');
            Route::post('store','dmdiabandbkkController@store');//insert + update
            Route::get('get','dmdiabandbkkController@getinfo');

            Route::get('ma_so={madiaban}','dmdiabandbkkController@detail');
            Route::get('del_detail/{id}','dmdiabandbkkController@destroy_detail');
            Route::get('add_canbo','dmdiabandbkkController@store_detail');
        });
        */
        //làm cho lai châu
        // Route::group(['prefix' => 'tai_lieu'], function () {
        //     Route::get('/maso={macanbo}', 'hosotailieuController@index');
        //     Route::get('del/{id}', 'hosotailieuController@destroy');
        // });
        // Route::group(['prefix' => 'quan_he_bt'], function () {
        //     Route::get('/maso={macanbo}', 'hosoquanhegdController@index_bt');
        //     Route::get('del/{id}', 'hosoquanhegdController@destroy_bt');
        // });
        // Route::group(['prefix' => 'quan_he_vc'], function () {
        //     Route::get('/maso={macanbo}', 'hosoquanhegdController@index_vc');
        //     Route::get('del/{id}', 'hosoquanhegdController@destroy_vc');
        // });
        //        Route::group(['prefix'=>'dieu_dong'],function(){
        //            Route::get('/maso={macanbo}','hosoluanchuyenController@index_dd');
        //            Route::get('del/{id}','hosoluanchuyenController@destroy_dd');
        //        });
        // Route::group(['prefix' => 'chuc_vu'], function () {
        //     Route::get('/maso={macanbo}', 'hosochucvuController@index');
        //     Route::get('del/{id}', 'hosochucvuController@destroy');
        // });
        // Route::group(['prefix' => 'bhyt'], function () {
        //     Route::get('/maso={macanbo}', 'hosobaohiemyteController@index');
        //     Route::get('del/{id}', 'hosobaohiemyteController@destroy');
        // });
        // Route::group(['prefix' => 'llvt'], function () {
        //     Route::get('/maso={macanbo}', 'hosollvtController@index');
        //     Route::get('del/{id}', 'hosollvtController@destroy');
        // });
    });

    Route::group(['prefix' => 'tam_ngung'], function () {
        Route::get('danh_sach', [hosotamngungtheodoiController::class, 'index']);
        Route::get('del/{id}', 'hosotamngungtheodoiController@destroy');

        Route::get('create', 'hosotamngungtheodoiController@create');
        Route::post('store', 'hosotamngungtheodoiController@store');
        Route::get('edit', 'hosotamngungtheodoiController@edit');
        Route::post('update', 'hosotamngungtheodoiController@update');

        Route::get('get', 'hosotamngungtheodoiController@getinfo');
    });

    Route::group(['prefix' => 'truy_linh'], function () {
        Route::get('danh_sach', 'hosotruylinhController@index');
        Route::get('del/{id}', 'hosotruylinhController@destroy');
        Route::get('create', 'hosotruylinhController@create');
        Route::post('create_all', 'hosotruylinhController@create_all');
        Route::post('store', 'hosotruylinhController@store');
        Route::post('update', 'hosotruylinhController@update');
        Route::get('get', 'hosotruylinhController@getinfo');
        Route::get('get_thongtin_canbo', 'hosotruylinhController@get_thongtin_canbo');
        Route::get('get_nkp', 'hosotruylinhController@getinfor_nkp');
        Route::get('store_nkp', 'hosotruylinhController@store_nkp');
        Route::get('del_nkp', 'hosotruylinhController@destroy_nkp');

        Route::post('del_mul', 'hosotruylinhController@destroy_mul');
    });

    Route::group(['prefix' => 'da_nghi'], function () {
        Route::get('danh_sach', 'hosothoicongtacController@index');
        Route::get('del/{id}', 'hosothoicongtacController@destroy');
        Route::post('store', 'hosothoicongtacController@store');
        Route::get('get', 'hosothoicongtacController@getinfo');
    });

    Route::group(['prefix' => 'truc'], function () {
        Route::get('create', 'hosotrucController@create');
        Route::get('edit', 'hosotrucController@edit');
        Route::get('danh_sach', 'hosotrucController@index');
        Route::get('del/{id}', 'hosotrucController@destroy');
        Route::post('store', 'hosotrucController@store');
        Route::get('get', 'hosotrucController@getinfo');

        Route::post('copy', 'hosotrucController@copy');
    });

    Route::group(['prefix' => 'dieu_dong'], function () {
        Route::get('danh_sach', 'hosodieudongController@index');
        Route::get('create', 'hosodieudongController@create');
        Route::get('accept', 'hosodieudongController@accept');
        Route::post('store', 'hosodieudongController@store');
        Route::post('store_accept', 'hosodieudongController@store_accept');
        Route::get('del/{id}', 'hosodieudongController@destroy');

        Route::get('/maso={macanbo}', 'hosoluanchuyenController@index_dd');
    });

    Route::group(['prefix' => 'qua_trinh'], function () {
        Route::group(['prefix' => 'dao_tao'], function () {
            Route::get('/maso={macanbo}', 'hosodaotaoController@index');
            Route::get('del/{id}', 'hosodaotaoController@destroy');
        });
        Route::group(['prefix' => 'cong_tac'], function () {
            Route::get('/maso={macanbo}', 'hosocongtacController@index');
            Route::get('del/{id}', 'hosocongtacController@destroy');
        });
        Route::group(['prefix' => 'cong_tac_nn'], function () {
            Route::get('/maso={macanbo}', 'hosocongtacnnController@index');
            Route::get('del/{id}', 'hosocongtacnnController@destroy');
        });
        Route::group(['prefix' => 'luong'], function () {
            Route::get('/maso={macanbo}', 'hosoluongController@index');
            Route::get('del/{id}', 'hosoluongController@destroy');
        });
        Route::group(['prefix' => 'phu_cap'], function () {
            Route::get('danh_sach', 'hosophucapController@index');
            Route::get('create', 'hosophucapController@create');
            Route::post('store', 'hosophucapController@store');

            Route::get('edit', 'hosophucapController@edit');
            Route::post('update', 'hosophucapController@update');
            Route::post('del', 'hosophucapController@destroy');
        });
    });

    // Route::group(['prefix' => 'danh_gia'], function () {
    //     Route::group(['prefix' => 'binh_bau'], function () {
    //         Route::get('/maso={macanbo}', 'hosobinhbauController@index');
    //         Route::get('del/{id}', 'hosobinhbauController@destroy');
    //     });
    //     Route::group(['prefix' => 'khen_thuong'], function () {
    //         Route::get('/maso={macanbo}', 'hosokhenthuongController@index');
    //         Route::get('del/{id}', 'hosokhenthuongController@destroy');
    //     });
    //     Route::group(['prefix' => 'ky_luat'], function () {
    //         Route::get('/maso={macanbo}', 'hosokyluatController@index');
    //         Route::get('del/{id}', 'hosokyluatController@destroy');
    //     });
    //     Route::group(['prefix' => 'thanh_tra'], function () {
    //         Route::get('/maso={macanbo}', 'hosothanhtraController@index');
    //         Route::get('del/{id}', 'hosothanhtraController@destroy');
    //     });
    //     Route::group(['prefix' => 'nhan_xet'], function () {
    //         Route::get('/maso={macanbo}', 'hosonhanxetdgController@index');
    //         Route::get('del/{id}', 'hosonhanxetdgController@destroy');
    //     });
    // });

    // Route::group(['prefix' => 'chi_tieu'], function () {
    //     Route::get('danh_sach', 'chitieubiencheController@index');
    //     Route::get('create', 'chitieubiencheController@create');
    //     Route::get('del/{id}', 'chitieubiencheController@destroy');
    //     Route::post('store', 'chitieubiencheController@store');
    //     Route::get('get', 'chitieubiencheController@get_detail');
    // });
});
