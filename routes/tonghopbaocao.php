<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tong_hop_bao_cao'], function () {
    Route::get('danh_sach', 'baocaothongtu67Controller@index');

    Route::post('mau2a1_tt68', 'baocaothongtu67Controller@mau2a1_tt67');
    Route::post('mau2a1_tt68excel', 'baocaothongtu67Controller@mau2a1_tt67excel');
    Route::post('mau2a2_tt68', 'baocaothongtu67Controller@mau2a2_tt67');
    Route::post('mau2a2_tt68excel', 'baocaothongtu67Controller@mau2a2_tt67excel');
    Route::post('mau2b_tt68', 'baocaothongtu67Controller@mau2b_tt67');
    Route::post('mau2b_tt68excel', 'baocaothongtu67Controller@mau2b_tt67excel');

    Route::post('mau2c_tt68', 'baocaothongtu67Controller@mau2c_tt67');
    Route::post('mau2c_tt68excel', 'baocaothongtu67Controller@mau2c_tt67excel');
    Route::post('mau2d_tt68', 'baocaothongtu67Controller@mau2d_tt67');
    Route::post('mau2d_tt68excel', 'baocaothongtu67Controller@mau2d_tt67excel');
    Route::post('mau2đ_tt68', 'baocaothongtu67Controller@mau2đ_tt67');
    Route::post('mau2e_tt68', 'baocaothongtu67Controller@mau2e_tt67');
    Route::post('mau2e_tt68excel', 'baocaothongtu67Controller@mau2e_tt67excel');
    Route::post('mau2g_tt68', 'baocaothongtu67Controller@mau2g_tt67');
    Route::post('mau2g_tt68excel', 'baocaothongtu67Controller@mau2g_tt67excel');
    Route::post('mau2h_tt68', 'baocaothongtu67Controller@mau2h_tt67');
    Route::post('mau2h_tt68excel', 'baocaothongtu67Controller@mau2h_tt67excel');
    Route::post('mau4a_tt68', 'baocaothongtu67Controller@mau4a_tt67');
    Route::post('mau4a_tt68excel', 'baocaothongtu67Controller@mau4a_tt67excel');
    Route::post('mau4b_tt68', 'baocaothongtu67Controller@mau4b_tt67');
    Route::post('mau4b_tt68excel', 'baocaothongtu67Controller@mau4b_tt67excel');
    Route::post('mau4b_tt68bs', 'baocaothongtu67Controller@mau4b_tt67bs');
    Route::post('mau4b_tt68bsexcel', 'baocaothongtu67Controller@mau4b_tt67bsexcel');

    Route::post('tonghopluong_huyen_CR', 'baocaothongtu67Controller@tonghopluong_huyen_CR');
    Route::post('tonghopluong_tinh_CR', 'baocaothongtu67Controller@tonghopluong_tinh_CR');
    Route::post('chitraluong_ct_CR', 'baocaothongtu67Controller@chitraluong_ct_huyenCR');
    Route::get('danhsach', 'baocaothongtu67Controller@danhsachdonvi');
});