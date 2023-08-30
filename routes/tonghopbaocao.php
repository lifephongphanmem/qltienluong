<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tong_hop_bao_cao'], function () {
    Route::get('danh_sach', 'baocaotonghop_tinhController@index');

    // Route::post('mau2a1_tt68', 'baocaothongtu67Controller@mau2a1_tt67');
    // Route::post('mau2a1_tt68excel', 'baocaothongtu67Controller@mau2a1_tt67excel');
    // Route::post('mau2a2_tt68', 'baocaothongtu67Controller@mau2a2_tt67');
    // Route::post('mau2a2_tt68excel', 'baocaothongtu67Controller@mau2a2_tt67excel');
    // Route::post('mau2b_tt68', 'baocaothongtu67Controller@mau2b_tt67');
    // Route::post('mau2b_tt68excel', 'baocaothongtu67Controller@mau2b_tt67excel');

    // Route::post('mau2c_tt68', 'baocaothongtu67Controller@mau2c_tt67');
    // Route::post('mau2c_tt68excel', 'baocaothongtu67Controller@mau2c_tt67excel');
    // Route::post('mau2d_tt68', 'baocaothongtu67Controller@mau2d_tt67');
    // Route::post('mau2d_tt68excel', 'baocaothongtu67Controller@mau2d_tt67excel');
    // Route::post('mau2đ_tt68', 'baocaothongtu67Controller@mau2đ_tt67');
    // Route::post('mau2e_tt68', 'baocaothongtu67Controller@mau2e_tt67');
    // Route::post('mau2e_tt68excel', 'baocaothongtu67Controller@mau2e_tt67excel');
    // Route::post('mau2g_tt68', 'baocaothongtu67Controller@mau2g_tt67');
    // Route::post('mau2g_tt68excel', 'baocaothongtu67Controller@mau2g_tt67excel');
    // Route::post('mau2h_tt68', 'baocaothongtu67Controller@mau2h_tt67');
    // Route::post('mau2h_tt68excel', 'baocaothongtu67Controller@mau2h_tt67excel');
    // Route::post('mau4a_tt68', 'baocaothongtu67Controller@mau4a_tt67');
    // Route::post('mau4a_tt68excel', 'baocaothongtu67Controller@mau4a_tt67excel');
    // Route::post('mau4b_tt68', 'baocaothongtu67Controller@mau4b_tt67');
    // Route::post('mau4b_tt68excel', 'baocaothongtu67Controller@mau4b_tt67excel');
    // Route::post('mau4b_tt68bs', 'baocaothongtu67Controller@mau4b_tt67bs');
    // Route::post('mau4b_tt68bsexcel', 'baocaothongtu67Controller@mau4b_tt67bsexcel');

    Route::post('tonghopluong_huyen_CR', 'baocaotonghop_tinhController@tonghopluong_huyen_CR');
    Route::post('tonghopluong_tinh_CR', 'baocaotonghop_tinhController@tonghopluong_tinh_CR');
    Route::post('chitraluong_ct_CR', 'baocaotonghop_tinhController@chitraluong_ct_huyenCR');
    Route::get('danhsach', 'baocaotonghop_tinhController@danhsachdonvi');
    Route::post('tonghopluong_tinh', 'baocaotonghop_tinhController@tonghopluong_tinh');
    Route::post('tonghopbienche', 'baocaotonghop_tinhController@tonghopbienche');
    Route::post('tonghophopdong', 'baocaotonghop_tinhController@tonghophopdong');
    //Nhu cầu
    Route::post('tonghopnhucau_tinh', 'baocaotonghop_tinhController@tonghopnhucau_tinh');
    Route::post('tonghopnhucau2a_tinh', 'baocaotonghop_tinhController@tonghopnhucau2a_tinh');

    Route::group(['prefix' => 'nhu_cau_kinh_phi'], function () {
        Route::post('mau2a_tonghop', 'baocaonhucaukinhphi_tinhController@mau2a_tonghop');
        Route::post('mau2a', 'baocaonhucaukinhphi_tinhController@mau2a');
        Route::post('mau2b_tonghop', 'baocaonhucaukinhphi_tinhController@mau2b_tonghop');
        Route::post('mau2b', 'baocaonhucaukinhphi_tinhController@mau2b');
        Route::post('mau2c_tonghop', 'baocaonhucaukinhphi_tinhController@mau2c_tonghop');
        Route::post('mau2c', 'baocaonhucaukinhphi_tinhController@mau2c');
        Route::post('mau2d_tonghop', 'baocaonhucaukinhphi_tinhController@mau2d_tonghop');
        Route::post('mau2d', 'baocaonhucaukinhphi_tinhController@mau2d');
        Route::post('mau2e_tonghop', 'baocaonhucaukinhphi_tinhController@mau2e_tonghop');
        Route::post('mau2e', 'baocaonhucaukinhphi_tinhController@mau2e');
        Route::post('mau2g_tonghop', 'baocaonhucaukinhphi_tinhController@mau2g_tonghop');
        Route::post('mau2g', 'baocaonhucaukinhphi_tinhController@mau2g');
        Route::post('mau4a_tonghop', 'baocaonhucaukinhphi_tinhController@mau4a_tonghop');
        Route::post('mau4a', 'baocaonhucaukinhphi_tinhController@mau4a');
        Route::post('mau4b_tonghop', 'baocaonhucaukinhphi_tinhController@mau4b_tonghop');
        Route::post('mau4b', 'baocaonhucaukinhphi_tinhController@mau4b');
    });
});
